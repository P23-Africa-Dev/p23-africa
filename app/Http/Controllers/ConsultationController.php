<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConsultationApplication;
use App\Models\Payment;
use App\Models\ScheduledSession;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
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
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:consultation_applications,email',
            'phone' => 'nullable|string|max:20',
            'company_brand_name' => 'required|string|max:255',
            'website_or_handle' => 'nullable|string|max:255',
            'industry' => 'required|string|max:255',
            'business_description' => 'required|string',
            'business_goals' => 'required|array|max:2',
            'key_challenges' => 'required|array|max:3',
            'motivation' => 'required|string',
            'success_definition' => 'required|string',
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
        } else {
            // Any non-success => failed
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
            // Create scheduled session
            $scheduledSession = ScheduledSession::create([
                'consultation_application_id' => $application->id,
                'session_date' => $request->session_date,
                'session_time' => $request->session_time,
                'timezone' => $request->timezone,
                'meet_link' => '', // This would be generated by your meeting platform
                'status' => 'scheduled'
            ]);

            // Update application status
            $application->update(['status' => 'scheduled']);

            // Clear session
            Session::forget(['consultation_application_id', 'payment_success']);

            $successMessage = 'Your consultation has been scheduled successfully! You will receive a confirmation email shortly.';

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'scheduled_session' => $scheduledSession,
                    'user_email' => $application->email
                ]);
            }

            return redirect()->route('consultation.application-forms')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            $errorMessage = 'An error occurred while scheduling your consultation. Please try again.';
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ]);
            }
            return back()->with('error', $errorMessage);
        }
    }
}
