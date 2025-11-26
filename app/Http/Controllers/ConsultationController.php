<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConsultationApplication;
use App\Models\Payment;
use App\Models\ScheduledSession;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConsultationScheduledMail;
use App\Mail\ConsultationScheduledNotificationMail;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class ConsultationController extends Controller
{
    public function showApplicationForm()
    {
        return view('consultation.application-forms');
    }

    public function submitApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:5|max:20',
            'company_brand_name' => 'required|string|min:2|max:255',
            'website_or_handle' => 'nullable|string|max:255',
            'industry' => 'required|string|min:2|max:255',
            'business_description' => 'required|string|min:10|max:1000',
            'business_goals' => 'required|array|min:1|max:2',
            'business_goals.*' => 'string|max:255',
            'key_challenges' => 'required|array|min:1|max:3',
            'key_challenges.*' => 'string|max:255',
            'motivation' => 'required|string|min:10|max:1000',
            'success_definition' => 'required|string|min:10|max:1000',
        ], [
            // Custom error messages
            'full_name.required' => 'Full Name is required.',
            'full_name.min' => 'Full Name must be at least 2 characters.',
            'email.required' => 'Email Address is required.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Phone Number is required.',
            'phone.min' => 'Please enter a valid phone number.',
            'company_brand_name.required' => 'Company Name is required.',
            'company_brand_name.min' => 'Company Name must be at least 2 characters.',
            'industry.required' => 'Industry is required.',
            'industry.min' => 'Industry must be at least 2 characters.',
            'business_description.required' => 'Business Description is required.',
            'business_description.min' => 'Please provide at least 10 characters describing your business.',
            'business_goals.required' => 'Please select at least one business goal.',
            'business_goals.min' => 'Please select at least one business goal.',
            'business_goals.max' => 'Please select no more than 2 business goals.',
            'key_challenges.required' => 'Please select at least one key challenge.',
            'key_challenges.min' => 'Please select at least one key challenge.',
            'key_challenges.max' => 'Please select no more than 3 key challenges.',
            'motivation.required' => 'Please explain what made you book this consultation.',
            'motivation.min' => 'Please provide at least 10 characters.',
            'success_definition.required' => 'Please define what would make this session successful.',
            'success_definition.min' => 'Please provide at least 10 characters.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create consultation application
        $application = ConsultationApplication::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company_brand_name,
            'industry' => $request->industry,
            'business_description' => $request->business_description,
            'selected_goals' => $request->business_goals,
            'selected_challenges' => $request->key_challenges,
            'desired_outcomes' => json_encode([
                'motivation' => $request->motivation,
                'success_definition' => $request->success_definition
            ]),
            'status' => 'pending'
        ]);

        // Store application ID in session for next steps
        Session::put('consultation_application_id', $application->id);

        return redirect()->route('consultation.consultation-summary');
    }

    public function showSummary()
    {
        $applicationId = Session::get('consultation_application_id');

        if (!$applicationId) {
            return redirect()->route('consultation.application-forms')
                ->with('error', 'Please complete the application form first.');
        }

        $application = ConsultationApplication::find($applicationId);

        if (!$application) {
            return redirect()->route('consultation.application-forms')
                ->with('error', 'Application not found. Please complete the form again.');
        }

        return view('consultation.consultation-summary', compact('application'));
    }

    public function showPaymentSelection()
    {
        $applicationId = Session::get('consultation_application_id');

        if (!$applicationId) {
            // For testing purposes, create a sample application
            $application = ConsultationApplication::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '+1234567890',
                'company' => 'Test Company',
                'business_stage' => 'growth',
                'consultation_focus' => 'strategy',
                'specific_goals' => 'Test goals',
                'challenges' => 'Test challenges',
                'preferred_date' => now()->addDays(7)->format('Y-m-d'),
                'preferred_time' => '10:00',
                'timezone' => 'UTC',
                'additional_info' => 'Test application for development'
            ]);

            Session::put('consultation_application_id', $application->id);
            $applicationId = $application->id;
        }

        $application = ConsultationApplication::find($applicationId);

        if (!$application) {
            return redirect()->route('consultation.application-forms')
                ->with('error', 'Application not found.');
        }

        return view('consultation.payment-selection', compact('application'));
    }

    public function initiatePayment(Request $request)
    {
        // This method is called from the consultation summary page
        // It should redirect to the payment selection page
        $applicationId = Session::get('consultation_application_id');

        if (!$applicationId) {
            return redirect()->route('consultation.application-forms')
                ->with('error', 'Please complete the application form first.');
        }

        return redirect()->route('consultation.payment-selection');
    }

    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:flutterwave,stripe',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $applicationId = Session::get('consultation_application_id');
        $application = ConsultationApplication::find($applicationId);

        if (!$application) {
            return redirect()->route('consultation.application-forms')
                ->with('error', 'Application not found.');
        }

        // Create pending payment record
        $payment = Payment::create([
            'consultation_application_id' => $application->id,
            'payment_method' => $request->payment_method,
            'amount' => 75.00, // Set your consultation fee
            'currency' => 'USD',
            'status' => 'pending',
            'payment_reference' => 'REF_' . time() . '_' . $application->id,
        ]);

        // Store payment ID in session for callback handling
        Session::put('pending_payment_id', $payment->id);

        // Payment processing is now handled via JavaScript in frontend
        // This endpoint should not be reached in normal flow
        return redirect()->route('consultation.payment-selection')
            ->with('error', 'Please select a payment method and try again.');
    }

    public function createStripePaymentIntent(Request $request)
    {
        $applicationId = Session::get('consultation_application_id');
        $application = ConsultationApplication::find($applicationId);

        if (!$application) {
            return response()->json(['error' => 'Application not found'], 404);
        }

        try {
            // Set Stripe secret key from environment
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            // Create payment record
            $payment = Payment::create([
                'consultation_application_id' => $application->id,
                'payment_method' => 'stripe',
                'amount' => 75.00,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_reference' => 'stripe_' . time() . '_' . $application->id,
            ]);

            // Create PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => 7500, // $75.00 in cents
                'currency' => 'usd',
                'metadata' => [
                    'application_id' => $application->id,
                    'payment_id' => $payment->id,
                    'customer_name' => $application->name,
                    'customer_email' => $application->email,
                ],
                'description' => 'P23 Africa Business Consultation - 90 minutes',
            ]);

            // Update payment record with Stripe PaymentIntent ID
            $payment->update([
                'payment_reference' => $paymentIntent->id,
                'payment_data' => [
                    'payment_intent_id' => $paymentIntent->id,
                    'client_secret' => $paymentIntent->client_secret,
                ]
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe PaymentIntent creation failed: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to create payment intent',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function handlePaymentCallback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gateway' => 'required|in:flutterwave,stripe',
            'transaction_id' => 'required|string',
            'status' => 'required|string',
            'payment_data' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('consultation.payment-selection')
                ->with('error', 'Invalid payment callback data.');
        }

        $applicationId = Session::get('consultation_application_id');
        $application = ConsultationApplication::find($applicationId);

        if (!$application) {
            return redirect()->route('consultation.application-forms')
                ->with('error', 'Application not found.');
        }

        // Find or create payment record
        $payment = Payment::where('consultation_application_id', $application->id)
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            // Create payment record if doesn't exist (direct callback scenario)
            $payment = Payment::create([
                'consultation_application_id' => $application->id,
                'payment_method' => $request->gateway,
                'amount' => 75.00,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_reference' => $request->transaction_id,
            ]);
        }

        // Update payment record with gateway response
        $paymentData = $request->payment_data ? json_decode($request->payment_data, true) : null;

        // Map external gateway status to our DB enum values
        if ($request->status === 'successful' || $request->status === 'success') {
            $payment->update([
                'status' => 'successful',
                'paid_at' => now(),
                'payment_reference' => $request->transaction_id,
                'payment_data' => $paymentData,
            ]);

            // Mark application as paid
            $payment->consultationApplication->update(['status' => 'paid']);

            Session::put('payment_success', true);
            return redirect()->route('consultation.payment-success');
        } elseif ($request->status === 'pending_verification' || $request->status === 'pending' || $request->status === 'processing') {
            // Handle bank transfers and other delayed verification payments
            $payment->update([
                'status' => 'pending',
                'payment_reference' => $request->transaction_id,
                'payment_data' => $paymentData,
            ]);

            // Mark application as paid for bank transfers (trust the payment gateway)
            // Bank transfers will be verified by Flutterwave webhook later
            $payment->consultationApplication->update(['status' => 'paid']);

            Session::put('payment_success', true);
            Session::put('payment_method_used', $request->gateway);
            Session::put('is_pending_verification', true);

            return redirect()->route('consultation.payment-success');
        } else {
            // Any other status => failed
            $payment->update([
                'status' => 'failed',
                'payment_reference' => $request->transaction_id,
                'payment_data' => $paymentData,
            ]);

            return redirect()->route('consultation.payment-selection')
                ->with('error', 'Payment failed. Please try again.');
        }
    }

    // Legacy method for backwards compatibility
    public function handlePaymentCallbackLegacy($reference, $status)
    {
        $payment = Payment::where('payment_reference', $reference)->first();

        if (!$payment) {
            return redirect()->route('consultation.application-forms')
                ->with('error', 'Payment not found.');
        }

        // Map external gateway status to our DB enum values
        if ($status === 'success' || $status === 'successful') {
            $payment->update([
                'status' => 'successful',
                'paid_at' => now(),
            ]);

            // mark application as paid
            $payment->consultationApplication->update(['status' => 'paid']);

            Session::put('payment_success', true);
            return redirect()->route('consultation.payment-success');
        } else {
            // any non-success => failed
            $payment->update(['status' => 'failed']);
            return redirect()->route('consultation.consultation-summary')
                ->with('error', 'Payment failed. Please try again.');
        }
    }

    public function showPaymentSuccess()
    {
        if (!Session::get('payment_success')) {
            return redirect()->route('consultation.application-forms');
        }

        $applicationId = Session::get('consultation_application_id');
        $application = ConsultationApplication::with('payment')->find($applicationId);

        return view('consultation.payment-success', compact('application'));
    }

    public function showScheduling()
    {
        $applicationId = Session::get('consultation_application_id');

        if (!$applicationId) {
            return redirect()->route('consultation.application-forms');
        }

        $application = ConsultationApplication::find($applicationId);

        if (!$application || $application->status !== 'paid') {
            return redirect()->route('consultation.application-forms')
                ->with('error', 'Please complete payment first.');
        }

        return view('consultation.schedule-consultation', compact('application'));
    }

    public function scheduleSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_date' => 'required|date|after:today',
            'session_time' => 'required|date_format:H:i',
            'timezone' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }
            return back()->withErrors($validator)->withInput();
        }

        $applicationId = Session::get('consultation_application_id');
        $application = ConsultationApplication::find($applicationId);

        if (!$application || $application->status !== 'paid') {
            $errorMessage = 'Invalid application or payment not completed.';
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ]);
            }
            return redirect()->route('consultation.application-forms')
                ->with('error', $errorMessage);
        }

        try {
            // Combine session_date and session_time to create datetime
            $sessionDateTime = \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i',
                $request->session_date . ' ' . $request->session_time,
                $request->timezone
            );

            // Set end time (90 minutes later)
            $sessionEndTime = $sessionDateTime->copy()->addMinutes(90);

            // Format for Google Calendar API (ISO 8601)
            $startDateTime = $sessionDateTime->toIso8601String();
            $endDateTime = $sessionEndTime->toIso8601String();

            // Initialize Google Calendar Service
            $calendarService = new GoogleCalendarService();

            // Get admin email from config or environment
            $adminEmail = env('ADMIN_EMAIL', 'admin@p23africa.com');

            // Prepare event details
            $eventSummary = 'P23 Africa Business Consultation - ' . $application->name;
            $eventDescription = "90-minute business consultation session\n\n"
                . "Client: {$application->name}\n"
                . "Company: {$application->company}\n"
                . "Industry: {$application->industry}\n"
                . "Email: {$application->email}\n"
                . "Phone: {$application->phone}\n\n"
                . "Business Description:\n{$application->business_description}\n\n"
                . "This consultation will be conducted via Google Meet.";

            // Attendees: admin and client
            $attendees = [$adminEmail, $application->email];

            // Create Google Calendar event with Meet link
            $calendarResult = $calendarService->createEvent(
                $eventSummary,
                $eventDescription,
                $startDateTime,
                $endDateTime,
                $request->timezone,
                $attendees
            );

            if (!$calendarResult['success']) {
                throw new \Exception('Failed to create Google Calendar event: ' . ($calendarResult['error'] ?? 'Unknown error'));
            }

            // Create scheduled session with Google Calendar data
            $scheduledSession = ScheduledSession::create([
                'consultation_application_id' => $application->id,
                'session_date' => $request->session_date,
                'session_time' => $request->session_time,
                'timezone' => $request->timezone,
                'meet_link' => $calendarResult['meet_link'] ?? '',
                'google_calendar_event_id' => $calendarResult['event_id'],
                'google_meet_link' => $calendarResult['meet_link'],
                'calendar_html_link' => $calendarResult['html_link'],
                'status' => 'scheduled'
            ]);

            // Update application status
            $application->update(['status' => 'scheduled']);

            // Generate .ics file for download
            $icsContent = $calendarService->generateIcsFile(
                $eventSummary,
                $eventDescription,
                $startDateTime,
                $endDateTime,
                $calendarResult['meet_link'] ?? 'Google Meet',
                $adminEmail,
                $application->email
            );

            // Store .ics file temporarily for download
            $icsFilename = 'consultation_' . $application->id . '_' . time() . '.ics';
            $icsPath = storage_path('app/temp/' . $icsFilename);

            if (!file_exists(dirname($icsPath))) {
                mkdir(dirname($icsPath), 0755, true);
            }

            file_put_contents($icsPath, $icsContent);

            // Prepare email data object with all necessary information
            $emailData = (object)[
                'id' => $scheduledSession->id,
                'name' => $application->name,
                'email' => $application->email,
                'phone' => $application->phone,
                'company' => $application->company,
                'consultation_type' => $application->industry ?? 'Business Consultation',
                'consultation_date' => $sessionDateTime,
                'google_meet_link' => $calendarResult['meet_link'] ?? null,
                'calendar_html_link' => $calendarResult['html_link'] ?? null,
                'message' => $application->business_description ?? null,
                'created_at' => $scheduledSession->created_at,
            ];

            // Send professional email to client
            try {
                Mail::to($application->email)->send(new ConsultationScheduledMail($emailData, $icsContent));
                Log::info('Consultation confirmation email sent to: ' . $application->email);
            } catch (\Exception $emailError) {
                Log::error('Failed to send client email: ' . $emailError->getMessage());
            }

            // Send admin notification email
            try {
                Mail::to(env('ADMIN_EMAIL', 'contact@p23africa.com'))
                    ->send(new ConsultationScheduledNotificationMail($emailData));
                Log::info('Admin notification email sent to: ' . env('ADMIN_EMAIL', 'contact@p23africa.com'));
            } catch (\Exception $emailError) {
                Log::error('Failed to send admin email: ' . $emailError->getMessage());
            }

            // Clear session
            Session::forget(['consultation_application_id', 'payment_success']);
            Session::put('ics_filename', $icsFilename);

            $successMessage = 'Your consultation has been scheduled successfully! Calendar invitations have been sent via email with Google Meet link.';

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'scheduled_session' => $scheduledSession,
                    'user_email' => $application->email,
                    'meet_link' => $calendarResult['meet_link'],
                    'calendar_link' => $calendarResult['html_link'],
                    'ics_download_url' => route('consultation.download-ics', ['filename' => $icsFilename])
                ]);
            }

            return redirect()->route('consultation.application-forms')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            Log::error('Consultation scheduling failed: ' . $e->getMessage());

            $errorMessage = 'An error occurred while scheduling your consultation. Please try again.';
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error_details' => $e->getMessage()
                ]);
            }
            return back()->with('error', $errorMessage);
        }
    }

    /**
     * Download calendar .ics file
     */
    public function downloadIcs($filename)
    {
        $icsPath = storage_path('app/temp/' . $filename);

        if (!file_exists($icsPath)) {
            abort(404, 'Calendar file not found');
        }

        return response()->download($icsPath, $filename, [
            'Content-Type' => 'text/calendar',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Google Calendar OAuth callback
     */
    public function googleCalendarCallback(Request $request)
    {
        if (!$request->has('code')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Google Calendar authorization failed.');
        }

        $calendarService = new GoogleCalendarService();
        $success = $calendarService->handleAuthCallback($request->code);

        if ($success) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Google Calendar connected successfully!');
        }

        return redirect()->route('admin.dashboard')
            ->with('error', 'Failed to connect Google Calendar. Please try again.');
    }

    /**
     * Initiate Google Calendar OAuth
     */
    public function googleCalendarAuth()
    {
        $calendarService = new GoogleCalendarService();
        $authUrl = $calendarService->getAuthUrl();

        return redirect($authUrl);
    }

    /**
     * Handle Flutterwave webhook notifications
     * This endpoint will be called by Flutterwave when payment status changes
     */
    public function handleFlutterwaveWebhook(Request $request)
    {
        // Verify webhook signature if available
        $secretKey = env('FLUTTERWAVE_SECRET_KEY');
        $signature = $request->header('verif-hash');

        if ($signature && $secretKey) {
            $expectedSignature = hash('sha256', $secretKey);
            if ($signature !== $expectedSignature) {
                Log::warning('Invalid Flutterwave webhook signature');
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
            }
        }

        $webhookData = $request->all();
        Log::info('Flutterwave webhook received:', $webhookData);

        // Extract transaction data
        if (!isset($webhookData['data'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid webhook data'], 400);
        }

        $transactionData = $webhookData['data'];
        $transactionId = $transactionData['id'] ?? null;
        $status = $transactionData['status'] ?? null;
        $txRef = $transactionData['tx_ref'] ?? null;

        if (!$transactionId || !$status) {
            return response()->json(['status' => 'error', 'message' => 'Missing required data'], 400);
        }

        // Find payment record by transaction reference
        $payment = Payment::where('payment_reference', $transactionId)
            ->orWhere('payment_reference', $txRef)
            ->first();

        if (!$payment) {
            Log::warning('Payment not found for Flutterwave webhook', [
                'transaction_id' => $transactionId,
                'tx_ref' => $txRef
            ]);
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        // Update payment status based on webhook
        switch (strtolower($status)) {
            case 'successful':
                $payment->update([
                    'status' => 'successful',
                    'paid_at' => now(),
                    'payment_data' => $transactionData,
                ]);

                // Ensure application is marked as paid
                $payment->consultationApplication->update(['status' => 'paid']);

                Log::info('Payment confirmed via webhook', ['payment_id' => $payment->id]);
                break;

            case 'failed':
            case 'cancelled':
                $payment->update([
                    'status' => 'failed',
                    'payment_data' => $transactionData,
                ]);

                Log::info('Payment failed via webhook', ['payment_id' => $payment->id]);
                break;

            default:
                // For pending, processing, etc., keep current status
                $payment->update([
                    'payment_data' => $transactionData,
                ]);

                Log::info('Payment status updated via webhook', [
                    'payment_id' => $payment->id,
                    'status' => $status
                ]);
                break;
        }

        return response()->json(['status' => 'success'], 200);
    }
}
