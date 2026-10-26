<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConsultationApplication;
use App\Models\Payment;
use App\Models\ScheduledSession;
use App\Models\ChatMessage;
use App\Services\GoogleCalendarService;
use App\Services\AdminNotificationService;
use App\Models\Lead;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Mail\ConsultationScheduledMail;
use App\Mail\ConsultationScheduledNotificationMail;
use App\Mail\ChatMessageNotification;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class ConsultationController extends Controller
{
    private const CONSULTATION_AMOUNT_GBP = 75.00;
    private const CONSULTATION_CURRENCY = 'GBP';
    private const STRIPE_MINIMUM_GBP_PENCE = 30;
    private const STRIPE_ZERO_DECIMAL_CURRENCIES = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];

    public function showApplicationForm(Request $request)
    {
        return view('consultation.application-forms');
    }

    public function submitApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:5|max:30',
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
            'key_challenges.required' => __('consultation.validation.challenges_required'),
            'key_challenges.min' => __('consultation.validation.challenges_required'),
            'key_challenges.max' => __('consultation.validation.challenges_max'),
            'motivation.required' => __('consultation.validation.motivation'),
            'motivation.min' => __('consultation.validation.motivation_min'),
            'success_definition.required' => __('consultation.validation.success'),
            'success_definition.min' => __('consultation.validation.success_min'),
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Prepare the application data
        $applicationData = [
            'booking_token' => (string) Str::uuid(),
            'name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company_brand_name,
            'website_or_handle' => $request->website_or_handle,
            'industry' => $request->industry,
            'business_description' => $request->business_description,
            'selected_goals' => $request->business_goals,
            'selected_challenges' => $request->key_challenges,
            'desired_outcomes' => json_encode([
                'motivation' => $request->motivation,
                'success_definition' => $request->success_definition
            ]),
            'status' => 'pending',
            'submitted_at' => now(),
            'locale' => app()->getLocale(),
        ];

        // Create new consultation application
        $application = ConsultationApplication::create($applicationData);

        // Send notification to admins
        AdminNotificationService::notifyNewConsultation($application);

        // Auto-create CRM lead from consultation application
        try {
            $lead = Lead::createFromConsultation($application);
            AdminNotificationService::notifyNewLead($lead);
        } catch (\Exception $e) {
            Log::error('Failed to create lead from consultation', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
        }

        Log::info('New consultation application created', [
            'application_id' => $application->id,
            'email' => $request->email
        ]);

        $this->persistBookingContext($application);

        return $this->redirectWithBooking('consultation.consultation-summary', $application);
    }

    public function showSummary(Request $request)
    {
        $application = $this->resolveApplication($request);

        if (!$application) {
            return $this->localizedRedirect('consultation.application-forms')
                ->with('error', 'Please complete the application form first.');
        }

        return view('consultation.consultation-summary', compact('application'));
    }

    public function showPaymentSelection(Request $request)
    {
        $application = $this->resolveApplication($request);

        if (!$application) {
            return $this->localizedRedirect('consultation.application-forms')
                ->with('error', 'Please complete the application form first.');
        }

        if ($application->status === 'pending') {
            $application->update(['status' => 'payment_pending']);
        }

        return view('consultation.payment-selection', compact('application'));
    }

    public function initiatePayment(Request $request)
    {
        $application = $this->resolveApplication($request);

        if (!$application) {
            return $this->localizedRedirect('consultation.application-forms')
                ->with('error', 'Please complete the application form first.');
        }

        return $this->redirectWithBooking('consultation.payment-selection', $application);
    }

    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:stripe',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $application = $this->resolveApplication($request);

        if (!$application) {
            return $this->localizedRedirect('consultation.application-forms')
                ->with('error', 'Application not found.');
        }

        // Create pending payment record
        $payment = Payment::create([
            'consultation_application_id' => $application->id,
            'payment_method' => $request->payment_method,
            'amount' => self::CONSULTATION_AMOUNT_GBP,
            'currency' => self::CONSULTATION_CURRENCY,
            'status' => 'pending',
            'payment_reference' => 'REF_' . time() . '_' . $application->id,
        ]);

        // Store payment ID in session for callback handling
        Session::put('pending_payment_id', $payment->id);

        // Payment processing is now handled via JavaScript in frontend
        // This endpoint should not be reached in normal flow
        return $this->redirectWithBooking('consultation.payment-selection', $application)
            ->with('error', 'Please select a payment method and try again.');
    }

    public function createStripePaymentIntent(Request $request)
    {
        $application = $this->resolveApplication($request);

        if (!$application) {
            return response()->json(['error' => 'Application not found'], 404);
        }

        try {
            // Set Stripe secret key from config
            Stripe::setApiKey(config('services.stripe.secret_key'));

            $preferredCurrency = strtoupper((string) $request->input('preferred_currency', ''));
            $chargeDetails = $this->resolveConsultationCharge($preferredCurrency);

            $amount = $chargeDetails['amount'];
            $currency = $chargeDetails['currency'];
            $amountInSmallestUnit = $chargeDetails['amount_in_smallest_unit'];

            Log::info('Stripe payment currency resolved', [
                'application_id' => $application->id,
                'requested_currency' => $preferredCurrency,
                'resolved_currency' => $currency,
                'resolved_amount' => $amount,
                'resolution_source' => $chargeDetails['source'] ?? 'default',
            ]);

            if (strtoupper($currency) === self::CONSULTATION_CURRENCY && $amountInSmallestUnit < self::STRIPE_MINIMUM_GBP_PENCE) {
                return response()->json([
                    'error' => 'Payment amount is below Stripe minimum.',
                    'message' => 'Unable to start payment because the configured consultation amount is invalid. Please contact support.',
                    'code' => 'amount_below_minimum',
                    'details' => 'Consultation payment must be at least 0.30 GBP.',
                ], 422);
            }

            // Create payment record
            $payment = Payment::create([
                'consultation_application_id' => $application->id,
                'payment_method' => 'stripe',
                'amount' => $amount,
                'currency' => $currency,
                'status' => 'pending',
                'payment_reference' => 'stripe_' . time() . '_' . $application->id,
            ]);

            // Create PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInSmallestUnit,
                'currency' => strtolower($currency),
                'automatic_payment_methods' => ['enabled' => true],
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
                'amount' => $amount,
                'currency' => strtoupper($currency),
                'display_amount' => strtoupper($currency) . ' ' . number_format((float) $amount, 2),
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe PaymentIntent creation failed: ' . $e->getMessage(), [
                'application_id' => $application->id,
                'requested_currency' => strtoupper((string) $request->input('preferred_currency', '')),
            ]);

            return response()->json([
                'error' => 'Failed to create payment intent',
                'message' => 'Unable to initialize card payment right now. Please try again in a moment.',
                'code' => 'payment_intent_creation_failed',
            ], 500);
        }
    }

    public function handlePaymentCallback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gateway' => 'required|in:stripe',
            'transaction_id' => 'nullable|string',
            'status' => 'required|string',
            'payment_data' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Payment callback validation failed', [
                'errors' => $validator->errors()->toArray(),
                'payload' => $request->except(['_token']),
            ]);

            return $this->localizedRedirect('consultation.payment-selection')
                ->with('error', 'Invalid payment callback data.');
        }

        $status = strtolower((string) $request->status);
        $transactionId = trim((string) $request->transaction_id);

        if ($transactionId === '' && in_array($status, ['successful', 'success', 'pending_verification', 'pending', 'processing'], true)) {
            Log::warning('Payment callback missing transaction id for non-failure status', [
                'status' => $status,
                'gateway' => $request->gateway,
            ]);

            return $this->localizedRedirect('consultation.payment-selection')
                ->with('error', 'Invalid payment callback data.');
        }

        $application = $this->resolveApplication($request, false);

        Log::info('Payment callback received', [
            'gateway' => $request->gateway,
            'transaction_id' => $transactionId,
            'status' => $status,
            'application_id' => $application?->id
        ]);

        // Find payment record - for Stripe, look by payment_reference first since it was set during PaymentIntent creation
        $payment = null;

        if ($request->gateway === 'stripe') {
            // For Stripe, first try to find by the payment_reference (PaymentIntent ID)
            $stripeQuery = Payment::where('payment_reference', $transactionId);
            if ($application) {
                $stripeQuery->where('consultation_application_id', $application->id);
            }
            $payment = $stripeQuery->first();

            Log::info('Stripe payment lookup by reference', [
                'payment_reference' => $transactionId,
                'found' => $payment ? true : false,
                'payment_id' => $payment ? $payment->id : null
            ]);
        }

        // If not found by payment_reference, try the original method
        if (!$payment && $application) {
            $payment = Payment::where('consultation_application_id', $application->id)
                ->where('status', 'pending')
                ->latest()
                ->first();

            Log::info('Payment lookup by application and pending status', [
                'application_id' => $application->id,
                'found' => $payment ? true : false,
                'payment_id' => $payment ? $payment->id : null,
                'current_reference' => $payment ? $payment->payment_reference : null
            ]);
        }

        if (!$payment) {
            $payment = Payment::where('payment_reference', $transactionId)->first();
        }

        if (!$payment && $application) {
            // Create payment record if doesn't exist (direct callback scenario)
            $payment = Payment::create([
                'consultation_application_id' => $application->id,
                'payment_method' => $request->gateway,
                'amount' => self::CONSULTATION_AMOUNT_GBP,
                'currency' => self::CONSULTATION_CURRENCY,
                'status' => 'pending',
                'payment_reference' => $transactionId !== '' ? $transactionId : ('stripe_failed_' . time() . '_' . $application->id),
            ]);
        }

        if (!$payment) {
            return $this->localizedRedirect('consultation.application-forms')
                ->with('error', 'Payment not found for this booking. Please try again.');
        }

        $application = $payment->consultationApplication;
        $this->persistBookingContext($application);

        if (!$application) {
            return $this->localizedRedirect('consultation.application-forms')
                ->with('error', 'Application not found.');
        }

        // Update payment record with gateway response
        $paymentData = $request->payment_data ? json_decode($request->payment_data, true) : null;

        // Map external gateway status to our DB enum values
        if ($status === 'successful' || $status === 'success') {
            // For Stripe payments that already have the correct payment_reference, don't update it again
            $updateData = [
                'status' => 'successful',
                'paid_at' => now(),
                'payment_data' => $paymentData,
            ];

            // Only update payment_reference if it's different (to avoid unique constraint violation)
            $referenceNeedsUpdate = $transactionId !== '' && $payment->payment_reference !== $transactionId;
            if ($referenceNeedsUpdate) {
                $updateData['payment_reference'] = $transactionId;
            }

            Log::info('Updating payment to successful', [
                'payment_id' => $payment->id,
                'current_reference' => $payment->payment_reference,
                'new_reference' => $transactionId,
                'reference_needs_update' => $referenceNeedsUpdate,
                'update_data_keys' => array_keys($updateData)
            ]);

            $payment->update($updateData);

            // Mark application as paid
            $payment->consultationApplication->update(['status' => 'paid']);

            Session::put('payment_success', true);
            return $this->redirectWithBooking('consultation.payment-success', $application);
        } elseif ($status === 'pending_verification' || $status === 'pending' || $status === 'processing') {
            $updateData = [
                'status' => 'pending',
                'payment_data' => $paymentData,
            ];

            // Only update payment_reference if it's different (to avoid unique constraint violation)
            if ($transactionId !== '' && $payment->payment_reference !== $transactionId) {
                $updateData['payment_reference'] = $transactionId;
            }

            $payment->update($updateData);

            // Mark application as paid for pending Stripe states to let user continue booking.
            $payment->consultationApplication->update(['status' => 'paid']);

            Session::put('payment_success', true);
            Session::put('payment_method_used', $request->gateway);
            Session::put('is_pending_verification', true);

            return $this->redirectWithBooking('consultation.payment-success', $application);
        } else {
            // Any other status => failed
            $updateData = [
                'status' => 'failed',
                'payment_data' => $paymentData,
            ];

            // Only update payment_reference if it's different (to avoid unique constraint violation)
            if ($transactionId !== '' && $payment->payment_reference !== $transactionId) {
                $updateData['payment_reference'] = $transactionId;
            }

            $payment->update($updateData);

            Log::warning('Stripe payment failed', $this->buildStripeFailureLogContext(
                $application,
                $payment,
                $status,
                $transactionId,
                $paymentData
            ));

            $userFacingMessage = $this->mapStripeFailureUserMessage($paymentData);

            return $this->redirectWithBooking('consultation.payment-selection', $application)
                ->with('error', $userFacingMessage);
        }
    }

    // Legacy method for backwards compatibility
    public function handlePaymentCallbackLegacy($reference, $status)
    {
        $payment = Payment::where('payment_reference', $reference)->first();

        if (!$payment) {
            return $this->localizedRedirect('consultation.application-forms')
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
            $this->persistBookingContext($payment->consultationApplication);

            Session::put('payment_success', true);
            return $this->redirectWithBooking('consultation.payment-success', $payment->consultationApplication);
        } else {
            // any non-success => failed
            $payment->update(['status' => 'failed']);
            return $this->redirectWithBooking('consultation.consultation-summary', $payment->consultationApplication)
                ->with('error', 'Payment failed. Please try again.');
        }
    }

    public function showPaymentSuccess(Request $request)
    {
        $application = $this->resolveApplication($request, false);

        if (!$application) {
            return $this->localizedRedirect('consultation.application-forms');
        }

        $application = ConsultationApplication::with(['payments' => function ($q) {
            $q->whereIn('status', ['successful', 'pending'])->latest();
        }])->find($application->id);

        if (!$application) {
            return $this->localizedRedirect('consultation.application-forms')
                ->with('error', 'Application not found.');
        }

        // Get the latest relevant payment for display
        $latestPayment = $application->payments->first();

        return view('consultation.payment-success', compact('application', 'latestPayment'));
    }

    public function showScheduling(Request $request)
    {
        $application = $this->resolveApplication($request, false);

        if (!$application || !in_array($application->status, ['paid', 'scheduled', 'completed'], true)) {
            return $this->localizedRedirect('consultation.application-forms')
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

        $application = $this->resolveApplication($request, false);

        if (!$application || $application->status !== 'paid') {
            $errorMessage = 'Invalid application or payment not completed.';
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ]);
            }
            return $this->localizedRedirect('consultation.application-forms')
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
            $adminEmail = config('services.admin.email', 'contact@p23africa.com');

            // Prepare event details
            // $eventSummary = 'P23 Africa Business Consultation - ' . $application->name;
            $eventSummary = 'P23 Africa Consultation';
            $eventDescription = "90-minute consultation session\n\n"
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
                Mail::to(config('services.admin.email', 'contact@p23africa.com'))
                    ->send(new ConsultationScheduledNotificationMail($emailData));
                Log::info('Admin notification email sent to: ' . config('services.admin.email', 'contact@p23africa.com'));
            } catch (\Exception $emailError) {
                Log::error('Failed to send admin email: ' . $emailError->getMessage());
            }

            // Clear session
            Session::forget(['payment_success']);
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

            return $this->localizedRedirect('consultation.application-forms')
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
            return orgRedirect('admin.dashboard')
                ->with('error', 'Google Calendar authorization failed.');
        }

        $calendarService = new GoogleCalendarService();
        $success = $calendarService->handleAuthCallback($request->code);

        if ($success) {
            return orgRedirect('admin.dashboard')
                ->with('success', 'Google Calendar connected successfully!');
        }

        return orgRedirect('admin.dashboard')
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
     * Send a chat message from the help system
     */
    public function sendChatMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'required|exists:consultation_applications,id',
            'message' => 'required|string|min:1|max:1000',
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'nullable|email|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get the consultation application
            $application = ConsultationApplication::find($request->application_id);

            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'Consultation application not found'
                ], 404);
            }

            // Create the chat message
            $chatMessage = ChatMessage::create([
                'consultation_application_id' => $application->id,
                'sender_name' => $request->sender_name,
                'sender_email' => $request->sender_email,
                'message' => $request->message,
                'sender_type' => 'user',
                'sent_at' => now()
            ]);

            // Send email notification
            try {
                Mail::to('contact@p23africa.com')->send(new ChatMessageNotification($chatMessage));
            } catch (\Exception $e) {
                Log::error('Failed to send chat message email notification', [
                    'error' => $e->getMessage(),
                    'chat_message_id' => $chatMessage->id
                ]);
                // Don't fail the entire request if email fails
            }

            Log::info('Chat message sent', [
                'chat_message_id' => $chatMessage->id,
                'application_id' => $application->id,
                'sender' => $request->sender_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => [
                    'id' => $chatMessage->id,
                    'sent_at' => $chatMessage->sent_at->toISOString()
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to send chat message', [
                'error' => $e->getMessage(),
                'application_id' => $request->application_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again.'
            ], 500);
        }
    }

    /**
     * Get chat messages for a consultation application
     */
    public function getChatMessages($applicationId)
    {
        try {
            $application = ConsultationApplication::with('chatMessages')->find($applicationId);

            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'Consultation application not found'
                ], 404);
            }

            $messages = $application->chatMessages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_name' => $message->sender_name,
                    'sender_type' => $message->sender_type,
                    'message' => $message->message,
                    'sent_at' => $message->sent_at->toISOString(),
                    'is_read' => $message->is_read
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $messages
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get chat messages', [
                'error' => $e->getMessage(),
                'application_id' => $applicationId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get messages'
            ], 500);
        }
    }

    private function resolveApplication(Request $request, bool $persist = true): ?ConsultationApplication
    {
        $application = null;

        $bookingToken = $request->query('booking')
            ?? $request->input('booking')
            ?? Session::get('consultation_booking_token');

        if ($bookingToken) {
            $application = ConsultationApplication::where('booking_token', $bookingToken)->first();
        }

        if (!$application) {
            $applicationId = Session::get('consultation_application_id');
            if ($applicationId) {
                $application = ConsultationApplication::find($applicationId);
            }
        }

        if ($application && $persist) {
            $this->persistBookingContext($application);
        }

        return $application;
    }

    private function persistBookingContext(ConsultationApplication $application): void
    {
        if (!$application->booking_token) {
            $application->booking_token = (string) Str::uuid();
            $application->save();
        }

        Session::put('consultation_application_id', $application->id);
        Session::put('consultation_booking_token', $application->booking_token);
    }

    private function redirectWithBooking(string $routeName, ConsultationApplication $application)
    {
        $locale = $application->locale ?: app()->getLocale();

        return $this->localizedRedirect($routeName, ['booking' => $application->booking_token], $locale);
    }

    private function localizedRedirect(string $routeName, array $parameters = [], ?string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        if (!in_array($locale, \App\Support\LocaleUrl::LOCALES, true)) {
            $locale = 'en';
        }

        $previous = app()->getLocale();
        app()->setLocale($locale);
        $url = locale_route($routeName, $parameters);
        app()->setLocale($previous);

        return redirect($url);
    }

    private function resolveConsultationCharge(string $preferredCurrency): array
    {
        $currency = self::CONSULTATION_CURRENCY;
        $amount = self::CONSULTATION_AMOUNT_GBP;
        $source = 'default_gbp';

        $targetCurrency = $this->sanitizeCurrency($preferredCurrency);
        $allowedCurrencies = $this->allowedStripeCurrencies();

        if ($targetCurrency !== self::CONSULTATION_CURRENCY && in_array($targetCurrency, $allowedCurrencies, true)) {
            $rate = $this->fetchExchangeRate(self::CONSULTATION_CURRENCY, $targetCurrency);

            if ($rate !== null && $rate > 0) {
                $currency = $targetCurrency;
                $amount = round(self::CONSULTATION_AMOUNT_GBP * $rate, 2);
                $source = 'fx_lookup';
            } else {
                $fallbackRate = $this->fallbackExchangeRate(self::CONSULTATION_CURRENCY, $targetCurrency);
                if ($fallbackRate !== null && $fallbackRate > 0) {
                    $currency = $targetCurrency;
                    $amount = round(self::CONSULTATION_AMOUNT_GBP * $fallbackRate, 2);
                    $source = 'fx_fallback';
                }
            }
        }

        return [
            'currency' => $currency,
            'amount' => $amount,
            'amount_in_smallest_unit' => $this->toSmallestCurrencyUnit($amount, $currency),
            'source' => $source,
        ];
    }

    private function sanitizeCurrency(string $currency): string
    {
        $normalized = strtoupper(trim($currency));

        if (!preg_match('/^[A-Z]{3}$/', $normalized)) {
            return self::CONSULTATION_CURRENCY;
        }

        return $normalized;
    }

    private function allowedStripeCurrencies(): array
    {
        $raw = (string) config('services.stripe.allowed_currencies', 'GBP,USD,EUR,NGN,GHS,KES,ZAR,CAD,AUD');

        $currencies = collect(explode(',', $raw))
            ->map(fn ($item) => strtoupper(trim($item)))
            ->filter(fn ($item) => preg_match('/^[A-Z]{3}$/', $item) === 1)
            ->unique()
            ->values()
            ->all();

        if (!in_array(self::CONSULTATION_CURRENCY, $currencies, true)) {
            $currencies[] = self::CONSULTATION_CURRENCY;
        }

        return $currencies;
    }

    private function fetchExchangeRate(string $baseCurrency, string $targetCurrency): ?float
    {
        $endpoint = (string) config('services.stripe.fx_rate_url', 'https://api.exchangerate.host/convert');

        try {
            $response = Http::timeout(5)->get($endpoint, [
                'from' => strtoupper($baseCurrency),
                'to' => strtoupper($targetCurrency),
                'amount' => 1,
            ]);

            if (!$response->ok()) {
                return null;
            }

            $result = $response->json('result');

            if (is_numeric($result) && (float) $result > 0) {
                return (float) $result;
            }
        } catch (\Throwable $e) {
            Log::warning('FX conversion lookup failed', [
                'base_currency' => $baseCurrency,
                'target_currency' => $targetCurrency,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    private function toSmallestCurrencyUnit(float $amount, string $currency): int
    {
        $upperCurrency = strtoupper($currency);

        if (in_array($upperCurrency, self::STRIPE_ZERO_DECIMAL_CURRENCIES, true)) {
            return (int) round($amount);
        }

        return (int) round($amount * 100);
    }

    private function fallbackExchangeRate(string $baseCurrency, string $targetCurrency): ?float
    {
        $configured = config('services.stripe.fx_fallback_rates', []);
        if (!is_array($configured)) {
            return null;
        }

        $key = strtoupper($baseCurrency) . '_' . strtoupper($targetCurrency);

        if (!isset($configured[$key]) || !is_numeric($configured[$key])) {
            return null;
        }

        return (float) $configured[$key];
    }

    private function buildStripeFailureLogContext(
        ConsultationApplication $application,
        Payment $payment,
        string $status,
        string $transactionId,
        ?array $paymentData
    ): array {
        $stripeError = $paymentData['stripe_error'] ?? [];
        $paymentIntent = $paymentData['payment_intent'] ?? [];
        $lastError = $paymentIntent['last_payment_error'] ?? [];

        $sanitizedPayload = $this->sanitizeStripeFailurePayload($paymentData);

        return [
            'application_id' => $application->id,
            'booking_token' => $application->booking_token,
            'payment_id' => $payment->id,
            'payment_reference' => $payment->payment_reference,
            'callback_status' => $status,
            'transaction_id' => $transactionId,
            'stripe_error_type' => $stripeError['type'] ?? null,
            'stripe_error_code' => $stripeError['code'] ?? null,
            'stripe_decline_code' => $stripeError['decline_code'] ?? null,
            'stripe_error_message' => $stripeError['message'] ?? null,
            'stripe_payment_intent_status' => $paymentIntent['status'] ?? null,
            'stripe_last_error_code' => $lastError['code'] ?? null,
            'stripe_last_decline_code' => $lastError['decline_code'] ?? null,
            'stripe_last_error_message' => $lastError['message'] ?? null,
            'gateway_payload' => $sanitizedPayload,
        ];
    }

    private function sanitizeStripeFailurePayload(?array $paymentData): ?array
    {
        if (!$paymentData) {
            return $paymentData;
        }

        $clone = $paymentData;

        if (isset($clone['payment_intent']['client_secret'])) {
            $clone['payment_intent']['client_secret'] = '[redacted]';
        }

        if (isset($clone['payment_intent']['last_payment_error']['payment_method']['billing_details']['email'])) {
            $clone['payment_intent']['last_payment_error']['payment_method']['billing_details']['email'] = '[redacted]';
        }

        return $clone;
    }

    private function mapStripeFailureUserMessage(?array $paymentData): string
    {
        $stripeError = $paymentData['stripe_error'] ?? [];
        $lastError = $paymentData['payment_intent']['last_payment_error'] ?? [];

        $errorCode = (string) ($stripeError['code'] ?? $lastError['code'] ?? '');
        $declineCode = (string) ($stripeError['decline_code'] ?? $lastError['decline_code'] ?? '');

        if ($declineCode === 'insufficient_funds') {
            return 'Your bank declined this payment due to insufficient funds. Please try another card or contact your bank.';
        }

        if ($declineCode === 'do_not_honor') {
            return 'Your bank did not approve this payment. Please contact your bank or try a different card.';
        }

        if ($declineCode === 'generic_decline' || $errorCode === 'card_declined') {
            return 'Your card was declined by your bank. Please try another card, ensure online/international payments are enabled, or contact your bank.';
        }

        if ($errorCode === 'authentication_required') {
            return 'Your bank requires additional authentication for this payment. Please try again and complete any verification prompts.';
        }

        if ($errorCode === 'expired_card') {
            return 'This card has expired. Please use another card.';
        }

        if ($errorCode === 'incorrect_cvc') {
            return 'The card security code is incorrect. Please check and try again.';
        }

        return 'Payment failed. Please try again or use a different card.';
    }
}
