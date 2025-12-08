<?php

namespace App\Http\Controllers;

use App\Helpers\ClickTracker;
use App\Mail\PlaybookDownloadMail;
use App\Mail\SalesBookDownloadMail;
use App\Mail\UltimateFundingDownloadMail;
use App\Models\PlaybookDownload;
use App\Models\SalesBookDownload;
use App\Models\UltimateFundingDownload;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PlaybookController extends Controller
{
    public function landing()
    {
        ClickTracker::track('Founders_Playbook_Page');

        return view('playbook-landing');
    }
    public function playbookOne()
    {
        ClickTracker::track('Funding_Readiness_Playbook_Page');

        return view('playbook.funding-readiness');
    }
    public function playbookTwo()
    {
        ClickTracker::track('Ultimate_Funding_Playbook_Page');

        return view('playbook.ultimate-funding');
    }

    public function download(Request $request)
    {
        try {
            Log::info('Playbook download request received', $request->all());

            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:playbook_downloads|max:255',
                'company' => 'required|string|max:255',
            ], [
                'name.required' => 'Full name is required.',
                'name.string' => 'Full name must be a valid text.',
                'name.max' => 'Full name cannot exceed 255 characters.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.max' => 'Email address cannot exceed 255 characters.',
                'company.required' => 'Company name is required.',
                'company.string' => 'Company name must be a valid text.',
                'company.max' => 'Company name cannot exceed 255 characters.',
            ]);

            if ($validator->fails()) {
                Log::warning('Playbook download validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Please check your form inputs.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if email already exists (optional - remove if you want to allow multiple downloads)
            $existingDownload = PlaybookDownload::where('email', $request->email)->first();

            if ($existingDownload) {
                Log::info('Updating existing playbook download record', ['email' => $request->email]);
                // Update existing record
                $download = $existingDownload;
                $download->update([
                    'name' => $request->name,
                    'company' => $request->company,
                ]);
            } else {
                Log::info('Creating new playbook download record', ['email' => $request->email]);
                // Create new record
                $download = PlaybookDownload::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'company' => $request->company,
                ]);

                // Send notification to admins for new downloads only
                AdminNotificationService::notifyPlaybookDownload($download);
            }

            // Send email with playbook attachment
            Log::info('Sending playbook email', ['email' => $request->email]);
            Mail::to($request->email)->send(new PlaybookDownloadMail($download));

            // Update email sent timestamp
            $download->update(['email_sent_at' => now()]);

            Log::info('Playbook download completed successfully', ['email' => $request->email]);

            return response()->json([
                'success' => true,
                'message' => 'Playbook will be sent to your email shortly!'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Database related errors
            Log::error('Database error in playbook download', [
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'A database error occurred. Please try again later.'
            ], 500);
        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            // Email sending errors
            Log::error('Email transport error in playbook download', [
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Email could not be sent. Please check your email address and try again.'
            ], 500);
        } catch (\Exception $e) {
            // General errors
            Log::error('General error in playbook download', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }

    public function salesBookDownload(Request $request)
    {
        try {
            Log::info('Sales book download request received', $request->all());

            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:sales_book_downloads|max:255',
                'company' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
            ], [
                'name.required' => 'Full name is required.',
                'name.string' => 'Full name must be a valid text.',
                'name.max' => 'Full name cannot exceed 255 characters.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email has already been used to download the guide.',
                'email.max' => 'Email address cannot exceed 255 characters.',
                'company.required' => 'Company name is required.',
                'company.string' => 'Company name must be a valid text.',
                'company.max' => 'Company name cannot exceed 255 characters.',
                'phone.required' => 'Phone number is required.',
                'phone.string' => 'Phone number must be a valid text.',
                'phone.max' => 'Phone number cannot exceed 20 characters.',
            ]);

            if ($validator->fails()) {
                Log::warning('Sales book download validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Please check your form inputs.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if email already exists (optional - remove if you want to allow multiple downloads)
            $existingDownload = SalesBookDownload::where('email', $request->email)->first();

            if ($existingDownload) {
                Log::info('Updating existing sales book download record', ['email' => $request->email]);
                // Update existing record
                $download = $existingDownload;
                $download->update([
                    'name' => $request->name,
                    'company' => $request->company,
                    'phone' => $request->phone,
                ]);
            } else {
                Log::info('Creating new sales book download record', ['email' => $request->email]);
                // Create new record
                $download = SalesBookDownload::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'company' => $request->company,
                    'phone' => $request->phone,
                ]);
            }

            // Send email with sales book attachment
            Log::info('Sending sales book email', ['email' => $request->email]);
            Mail::to($request->email)->send(new SalesBookDownloadMail($download));

            // Update email sent timestamp
            $download->update(['email_sent_at' => now()]);

            Log::info('Sales book download completed successfully', ['email' => $request->email]);

            return response()->json([
                'success' => true,
                'message' => 'Funding Readiness Guide will be sent to your email shortly!'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Database related errors
            Log::error('Database error in sales book download', [
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'A database error occurred. Please try again later.'
            ], 500);
        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            // Email sending errors
            Log::error('Email transport error in sales book download', [
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Email could not be sent. Please check your email address and try again.'
            ], 500);
        } catch (\Exception $e) {
            // General errors
            Log::error('General error in sales book download', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }

    public function ultimateFundingDownload(Request $request)
    {
        try {
            Log::info('Ultimate funding download request received', $request->all());

            // First, remove the unique validation for email to handle existing users
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'company' => 'required|string|max:255',
                'country' => 'required|string|max:255',
            ], [
                'name.required' => 'Full name is required.',
                'name.string' => 'Full name must be a valid text.',
                'name.max' => 'Full name cannot exceed 255 characters.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.max' => 'Email address cannot exceed 255 characters.',
                'company.required' => 'Company name is required.',
                'company.string' => 'Company name must be a valid text.',
                'company.max' => 'Company name cannot exceed 255 characters.',
                'country.required' => 'Country is required.',
                'country.string' => 'Country must be a valid text.',
                'country.max' => 'Country cannot exceed 255 characters.',
            ]);

            if ($validator->fails()) {
                Log::warning('Ultimate funding download validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Please check your form inputs.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if email already exists in the database
            $existingDownload = UltimateFundingDownload::where('email', $request->email)->first();

            if ($existingDownload) {
                // Check if user has already completed payment
                if ($existingDownload->payment_completed) {
                    Log::info('User already purchased using this email', ['email' => $request->email]);
                    return response()->json([
                        'success' => false,
                        'message' => 'User already purchased book using this email',
                        'action' => 'already_purchased'
                    ], 200);
                } else {
                    // User exists but hasn't completed payment - redirect to payment
                    Log::info('Email recognized, redirecting to payment', ['email' => $request->email]);
                    return response()->json([
                        'success' => true,
                        'message' => 'Email is recognised, you will be redirected to complete payment in 10s',
                        'action' => 'redirect_to_payment',
                        'countdown' => 10,
                        'download_id' => $existingDownload->id,
                        'amount' => 50.00,
                        'user_email' => $existingDownload->email,
                        'user_name' => $existingDownload->name
                    ]);
                }
            }

            Log::info('Creating new ultimate funding download record', ['email' => $request->email]);

            // Create new record
            $download = UltimateFundingDownload::create([
                'name' => $request->name,
                'email' => $request->email,
                'company' => $request->company,
                'country' => $request->country,
                'payment_completed' => false, // Will be updated after payment
                'amount_paid' => 50.00, // Set the expected amount
            ]);

            Log::info('Ultimate funding download record created successfully', ['id' => $download->id, 'email' => $request->email]);

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully! Please proceed with payment to access the Ultimate Funding Directory.',
                'action' => 'proceed_to_payment',
                'download_id' => $download->id,
                'amount' => 50.00,
                'user_email' => $download->email,
                'user_name' => $download->name
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Database related errors
            Log::error('Database error in ultimate funding download', [
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'A database error occurred. Please try again later.'
            ], 500);
        } catch (\Exception $e) {
            // General errors
            Log::error('General error in ultimate funding download', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }

    public function ultimateFundingPaymentCallback(Request $request)
    {
        try {
            Log::info('Ultimate funding payment callback received', $request->all());

            // Validate payment callback data - updated to match Flutterwave response
            $validator = Validator::make($request->all(), [
                'transaction_id' => 'required', // Allow both string and integer
                'tx_ref' => 'required|string',
            ]);

            if ($validator->fails()) {
                Log::warning('Ultimate funding payment callback validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payment callback data.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Extract download_id from tx_ref (format: UF_downloadId_timestamp)
            $txRef = $request->tx_ref;
            $parts = explode('_', $txRef);

            Log::info('Parsing tx_ref', ['tx_ref' => $txRef, 'parts' => $parts]);

            if (count($parts) < 2 || $parts[0] !== 'UF') {
                Log::error('Invalid tx_ref format', ['tx_ref' => $txRef]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid transaction reference format.'
                ], 422);
            }

            $downloadId = $parts[1];
            Log::info('Extracted download ID', ['download_id' => $downloadId]);

            $download = UltimateFundingDownload::find($downloadId);

            if (!$download) {
                Log::error('Download record not found', ['download_id' => $downloadId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Download record not found.'
                ], 404);
            }

            Log::info('Found download record', ['download' => $download->toArray()]);

            // Here you would normally verify the payment with Flutterwave API
            // For now, we'll assume the payment is successful since it came from their callback

            // Update payment details
            $updateResult = $download->update([
                'payment_completed' => true,
                'payment_reference' => (string) $request->transaction_id, // Convert to string
                'payment_status' => 'successful',
                'payment_date' => now(),
                'payment_data' => $request->all()
            ]);

            Log::info('Payment update result', ['update_result' => $updateResult, 'download_after_update' => $download->fresh()->toArray()]);            // Send email
            Log::info('Sending ultimate funding directory email', ['email' => $download->email]);
            Mail::to($download->email)->send(new UltimateFundingDownloadMail($download));

            // Update email sent timestamp
            $download->update(['email_sent_at' => now()]);

            Log::info('Ultimate funding directory email sent successfully', ['email' => $download->email]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful! The Ultimate Funding Directory has been sent to your email.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ultimate funding payment callback', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred processing the payment. Please contact support.'
            ], 500);
        }
    }

    /**
     * Create Stripe Payment Intent for Ultimate Funding Directory
     */
    public function createStripePaymentIntent(Request $request)
    {
        try {
            Log::info('Stripe payment intent request received', [
                'all_data' => $request->all(),
                'download_id' => $request->download_id,
                'amount' => $request->amount
            ]);

            $validator = Validator::make($request->all(), [
                'download_id' => 'required|integer|exists:ultimate_funding_downloads,id',
                'amount' => 'nullable|numeric|min:1',
            ]);

            if ($validator->fails()) {
                Log::warning('Stripe payment intent validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request data.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $download = UltimateFundingDownload::find($request->download_id);

            if (!$download) {
                return response()->json([
                    'success' => false,
                    'message' => 'Download record not found.'
                ], 404);
            }

            // Check if already paid
            if ($download->payment_completed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment has already been completed for this order.'
                ], 400);
            }

            // Use provided amount or default to 50
            $amount = $request->amount ?? 50;

            // Initialize Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret_key'));

            // Create Payment Intent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => (int)($amount * 100), // Stripe expects amount in cents
                'currency' => 'usd',
                'metadata' => [
                    'download_id' => $download->id,
                    'email' => $download->email,
                    'name' => $download->name,
                    'product' => 'Ultimate Funding Directory'
                ],
                'description' => 'Ultimate Funding Directory for African Entrepreneurs',
                'receipt_email' => $download->email,
            ]);

            Log::info('Stripe payment intent created', [
                'payment_intent_id' => $paymentIntent->id,
                'download_id' => $download->id
            ]);

            return response()->json([
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API error', [
                'message' => $e->getMessage(),
                'code' => $e->getStripeCode()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment processing error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error creating Stripe payment intent', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.'
            ], 500);
        }
    }

    /**
     * Handle Stripe Payment Callback
     */
    public function stripePaymentCallback(Request $request)
    {
        try {
            Log::info('Stripe payment callback received', $request->all());

            $validator = Validator::make($request->all(), [
                'payment_intent_id' => 'required|string',
                'download_id' => 'required|integer|exists:ultimate_funding_downloads,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid callback data.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Initialize Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret_key'));

            // Retrieve the payment intent to verify status
            $paymentIntent = \Stripe\PaymentIntent::retrieve($request->payment_intent_id);

            Log::info('Stripe payment intent retrieved', [
                'status' => $paymentIntent->status,
                'payment_intent_id' => $paymentIntent->id
            ]);

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment was not successful. Status: ' . $paymentIntent->status
                ], 400);
            }

            $download = UltimateFundingDownload::find($request->download_id);

            if (!$download) {
                return response()->json([
                    'success' => false,
                    'message' => 'Download record not found.'
                ], 404);
            }

            // Update payment details
            $download->update([
                'payment_completed' => true,
                'payment_reference' => $paymentIntent->id,
                'payment_status' => 'successful',
                'payment_method' => 'stripe',
                'payment_date' => now(),
                'payment_data' => [
                    'payment_intent_id' => $paymentIntent->id,
                    'amount' => $paymentIntent->amount / 100, // Convert from cents
                    'currency' => $paymentIntent->currency,
                    'status' => $paymentIntent->status,
                    'receipt_email' => $paymentIntent->receipt_email,
                ]
            ]);

            Log::info('Stripe payment verified and recorded', [
                'download_id' => $download->id,
                'payment_intent_id' => $paymentIntent->id
            ]);

            // Send email with the directory
            Log::info('Sending ultimate funding directory email', ['email' => $download->email]);
            Mail::to($download->email)->send(new UltimateFundingDownloadMail($download));

            // Update email sent timestamp
            $download->update(['email_sent_at' => now()]);

            Log::info('Ultimate funding directory email sent successfully', ['email' => $download->email]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful! The Ultimate Funding Directory has been sent to your email.'
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API error in callback', [
                'message' => $e->getMessage(),
                'code' => $e->getStripeCode()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error in Stripe payment callback', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred verifying the payment. Please contact support.'
            ], 500);
        }
    }

    public function resendFundingReadinessPending(Request $request)
    {
        try {
            Log::info('Resend funding readiness pending emails request initiated');

            // Get all funding readiness downloads where email_sent_at is null (pending)
            $pendingDownloads = SalesBookDownload::whereNull('email_sent_at')->get();

            if ($pendingDownloads->isEmpty()) {
                Log::info('No pending funding readiness downloads found');
                return response()->json([
                    'success' => true,
                    'message' => 'No pending emails found to resend.',
                    'sent_count' => 0,
                    'failed_count' => 0
                ]);
            }

            $successCount = 0;
            $failedCount = 0;
            $failedEmails = [];

            foreach ($pendingDownloads as $download) {
                try {
                    Log::info('Attempting to resend funding readiness email', ['email' => $download->email]);

                    // Send email with funding readiness guide attachment
                    Mail::to($download->email)->send(new SalesBookDownloadMail($download));

                    // Update email sent timestamp
                    $download->update(['email_sent_at' => now()]);

                    $successCount++;
                    Log::info('Funding readiness email resent successfully', ['email' => $download->email]);
                } catch (\Exception $emailError) {
                    $failedCount++;
                    $failedEmails[] = $download->email;
                    Log::error('Failed to resend funding readiness email', [
                        'email' => $download->email,
                        'error' => $emailError->getMessage()
                    ]);
                }
            }

            $message = "Resend completed! {$successCount} emails sent successfully.";
            if ($failedCount > 0) {
                $message .= " {$failedCount} emails failed to send.";
            }

            Log::info('Funding readiness resend operation completed', [
                'total_pending' => $pendingDownloads->count(),
                'success_count' => $successCount,
                'failed_count' => $failedCount,
                'failed_emails' => $failedEmails
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'sent_count' => $successCount,
                'failed_count' => $failedCount,
                'failed_emails' => $failedEmails
            ]);
        } catch (\Exception $e) {
            Log::error('Error in resend funding readiness pending operation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while resending emails. Please try again later.'
            ], 500);
        }
    }

    public function dashboard()
    {
        try {
            // Get all statistics
            $playbookDownloads = PlaybookDownload::count();
            $salesBookDownloads = SalesBookDownload::count();
            $ultimateFundingDownloads = UltimateFundingDownload::count();
            $ultimateFundingPaid = UltimateFundingDownload::where('payment_completed', true)->count();
            $totalRevenue = UltimateFundingDownload::where('payment_completed', true)->sum('amount_paid');

            // Recent downloads
            $recentPlaybookDownloads = PlaybookDownload::latest()->take(5)->get();
            $recentSalesBookDownloads = SalesBookDownload::latest()->take(5)->get();
            $recentUltimateFundingDownloads = UltimateFundingDownload::latest()->take(5)->get();

            $stats = [
                'playbook_downloads' => $playbookDownloads,
                'sales_book_downloads' => $salesBookDownloads,
                'ultimate_funding_downloads' => $ultimateFundingDownloads,
                'ultimate_funding_paid' => $ultimateFundingPaid,
                'total_revenue' => $totalRevenue,
                'total_downloads' => $playbookDownloads + $salesBookDownloads + $ultimateFundingDownloads,
            ];

            $recentDownloads = [
                'playbook' => $recentPlaybookDownloads,
                'sales_book' => $recentSalesBookDownloads,
                'ultimate_funding' => $recentUltimateFundingDownloads,
            ];

            return view('admin.playbooks.dashboard', compact('stats', 'recentDownloads'));
        } catch (\Exception $e) {
            Log::error('Error loading playbook dashboard', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'An error occurred loading the dashboard.');
        }
    }
}
