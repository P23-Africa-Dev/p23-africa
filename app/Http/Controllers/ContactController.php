<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Mail\ContactFormMail;
use App\Mail\ThankYouMail;
use App\Models\ContactSubmission;
use App\Models\Lead;
use App\Services\AdminNotificationService;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function submitForm(Request $request)
    {
        try {
            // Check if this is from live capture (has session_id)
            $sessionId = $request->input('session_id');
            if ($sessionId) {
                // If live capture is enabled, let saveDraft handle everything
                // This prevents duplicate submissions
                $request->merge(['is_submission' => true]);
                return $this->saveDraft($request);
            }

            // Generate a unique submission key for deduplication
            $submissionKey = 'contact_submission_' . md5(
                $request->input('email') .
                    $request->input('phone') .
                    $request->ip()
            );

            // Check if we're already processing this submission (prevents double-click)
            if (Cache::has($submissionKey)) {
                Log::info('Duplicate submission prevented by cache lock', [
                    'email' => $request->input('email'),
                    'ip' => $request->ip()
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Your message has already been sent successfully! We will get back to you soon.'
                    ]);
                }

                return redirect()->back()->with('success', 'Your message has already been sent successfully! We will get back to you soon.');
            }

            // Set a lock to prevent duplicate processing (expires in 60 seconds)
            Cache::put($submissionKey, true, 60);

            // Check for duplicate submission in database
            $duplicateCheck = ContactSubmission::where('email', $request->input('email'))
                ->where('phone', $request->input('phone'))
                ->where('status', 'submitted')
                ->where('created_at', '>=', now()->subMinutes(10))
                ->first();

            if ($duplicateCheck) {
                Log::warning('Duplicate submission attempt detected in DB', [
                    'email' => $request->input('email'),
                    'ip' => $request->ip(),
                    'existing_id' => $duplicateCheck->id
                ]);

                Cache::forget($submissionKey);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Your message has already been sent successfully! We will get back to you soon.'
                    ]);
                }

                return redirect()->back()->with('success', 'Your message has already been sent successfully! We will get back to you soon.');
            }

            // Validate the request for regular submission
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'country_code' => 'required|string|max:10',
                'services' => 'required|string',
                'message' => 'required|string|max:2000',
                'budget_range' => 'required|numeric|min:100|max:5000',
                'g-recaptcha-response' => 'required|captcha', // reCAPTCHA validation
            ], [
                'name.required' => 'Name is required.',
                'company_name.required' => 'Company name is required.',
                'company_name.max' => 'Company name must not exceed 255 characters.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'phone.required' => 'Phone number is required.',
                'phone.max' => 'Phone number must not exceed 20 characters.',
                'country_code.required' => 'Country code is required.',
                'country_code.max' => 'Invalid country code.',
                'services.required' => 'Please select at least one service.',
                'message.required' => 'Message is required.',
                'budget_range.required' => 'Please select your budget range.',
                'budget_range.numeric' => 'Budget must be a valid number.',
                'budget_range.min' => 'Budget must be at least $100.',
                'budget_range.max' => 'Budget must not exceed $5000.',
                'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
                'g-recaptcha-response.captcha' => 'reCAPTCHA verification failed. Please try again.',
            ]);

            // Parse services from comma-separated string to array
            $services = [];
            if (!empty($validated['services'])) {
                $services = array_map('trim', explode(',', $validated['services']));
                $services = array_filter($services); // Remove empty values
            }

            // Sanitize all inputs
            $sanitizedData = [
                'name' => strip_tags($validated['name']),
                'company_name' => strip_tags($validated['company_name']),
                'email' => filter_var($validated['email'], FILTER_SANITIZE_EMAIL),
                'phone' => strip_tags($validated['phone']),
                'country_code' => strip_tags($validated['country_code']),
                'services' => $services,
                'message' => strip_tags($validated['message']),
                'budget_range' => (int)$validated['budget_range'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'submitted',
                'submitted_at' => now(),
            ];

            // Store in database (this path only for non-live-capture submissions)
            $contactSubmission = ContactSubmission::create($sanitizedData);

            // Send notification to admins
            AdminNotificationService::notifyNewContactForm($contactSubmission);

            // Auto-create CRM lead from contact submission
            try {
                $lead = Lead::createFromContactSubmission($contactSubmission);
                AdminNotificationService::notifyNewLead($lead);
            } catch (\Exception $e) {
                Log::error('Failed to create lead from contact submission', [
                    'submission_id' => $contactSubmission->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Send emails using centralized method
            $this->sendSubmissionEmails($contactSubmission);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your message has been sent successfully! We will get back to you soon.'
                ]);
            }

            return redirect()->back()->with('success', 'Your message has been sent successfully! We will get back to you soon.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please check your input and try again.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Contact form submission error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An unexpected error occurred. Please try again later.'
                ], 500);
            }

            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }

    /**
     * Save or update form draft as user types
     */
    public function saveDraft(Request $request)
    {
        try {
            // Basic validation for draft saves - more lenient than final submission
            $validator = Validator::make($request->all(), [
                'session_id' => 'required|string|max:255',
                'name' => 'nullable|string|max:255',
                'company_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'country_code' => 'nullable|string|max:10',
                'services' => 'nullable|string',
                'message' => 'nullable|string|max:2000',
                'budget_range' => 'nullable|numeric|min:0|max:10000',
                'keystroke_count' => 'nullable|integer|min:0',
                'field_interactions' => 'nullable|array',
                'is_submission' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data provided',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            $isSubmission = $data['is_submission'] ?? false;

            // Require at least name OR email before saving draft (avoid empty rows)
            $hasName = !empty($data['name']) && trim($data['name']) !== '';
            $hasEmail = !empty($data['email']) && trim($data['email']) !== '';

            if (!$isSubmission && !$hasName && !$hasEmail) {
                // Don't save draft without at least name or email
                Log::info('Skipping draft save - no name or email provided', [
                    'session_id' => $data['session_id']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Waiting for name or email',
                    'status' => 'pending'
                ]);
            }

            // For submissions, use a lock to prevent race conditions
            if ($isSubmission) {
                $submissionLockKey = 'submission_lock_' . $data['session_id'];

                // Check if submission is already in progress
                if (Cache::has($submissionLockKey)) {
                    Log::info('Submission already in progress for session', [
                        'session_id' => $data['session_id']
                    ]);

                    // Find the existing submission
                    $existingSubmission = ContactSubmission::where('session_id', $data['session_id'])
                        ->where('status', 'submitted')
                        ->first();

                    if ($existingSubmission) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Form already submitted successfully!',
                            'submission_id' => $existingSubmission->id,
                            'status' => $existingSubmission->status
                        ]);
                    }
                }

                // Set lock for 60 seconds
                Cache::put($submissionLockKey, true, 60);
            }

            // Parse services if provided
            $services = [];
            if (!empty($data['services'])) {
                $services = array_map('trim', explode(',', $data['services']));
                $services = array_filter($services);
            }

            // Prepare data for storage
            $contactData = [
                'session_id' => $data['session_id'],
                'name' => !empty($data['name']) ? strip_tags($data['name']) : null,
                'company_name' => !empty($data['company_name']) ? strip_tags($data['company_name']) : null,
                'email' => !empty($data['email']) ? filter_var($data['email'], FILTER_SANITIZE_EMAIL) : null,
                'phone' => !empty($data['phone']) ? strip_tags($data['phone']) : null,
                'country_code' => !empty($data['country_code']) ? strip_tags($data['country_code']) : null,
                'services' => $services,
                'message' => !empty($data['message']) ? strip_tags($data['message']) : null,
                'budget_range' => $data['budget_range'] ?? null,
                'keystroke_count' => $data['keystroke_count'] ?? 0,
                'field_interactions' => $data['field_interactions'] ?? null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'last_activity_at' => now(),
            ];

            // Find existing draft or create new one
            $submission = ContactSubmission::where('session_id', $data['session_id'])
                ->first(); // Remove status filter to find any existing submission

            if ($submission) {
                // If this is a submission and the record is already submitted, avoid duplicate
                if ($isSubmission && $submission->status === 'submitted') {
                    Log::warning('Duplicate submission attempt via live capture', [
                        'session_id' => $data['session_id'],
                        'submission_id' => $submission->id,
                        'email' => $submission->email
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Form already submitted successfully!',
                        'submission_id' => $submission->id,
                        'status' => $submission->status
                    ]);
                }

                // Update existing draft/submission
                $contactData['status'] = $isSubmission ? 'submitted' : 'draft';
                if ($isSubmission) {
                    $contactData['submitted_at'] = now();
                }

                $submission->update($contactData);

                // Auto-create CRM lead when draft is converted to submission
                if ($isSubmission) {
                    AdminNotificationService::notifyNewContactForm($submission);

                    try {
                        $lead = Lead::createFromContactSubmission($submission);
                        AdminNotificationService::notifyNewLead($lead);
                    } catch (\Exception $e) {
                        Log::error('Failed to create lead from contact draft submission', [
                            'submission_id' => $submission->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                Log::info('Draft updated', [
                    'session_id' => $data['session_id'],
                    'submission_id' => $submission->id,
                    'is_submission' => $isSubmission
                ]);
            } else {
                // Check for duplicate based on email and phone if this is a submission
                if ($isSubmission && !empty($contactData['email']) && !empty($contactData['phone'])) {
                    $duplicateCheck = ContactSubmission::where('email', $contactData['email'])
                        ->where('phone', $contactData['phone'])
                        ->where('created_at', '>=', now()->subMinutes(5))
                        ->first();

                    if ($duplicateCheck) {
                        Log::warning('Duplicate submission attempt detected in saveDraft', [
                            'email' => $contactData['email'],
                            'existing_id' => $duplicateCheck->id
                        ]);

                        return response()->json([
                            'success' => true,
                            'message' => 'Form already submitted successfully!',
                            'submission_id' => $duplicateCheck->id,
                            'status' => $duplicateCheck->status
                        ]);
                    }
                }

                // Create new draft
                $contactData['status'] = $isSubmission ? 'submitted' : 'draft';
                $contactData['draft_created_at'] = now();
                if ($isSubmission) {
                    $contactData['submitted_at'] = now();
                }

                $submission = ContactSubmission::create($contactData);

                // Send notification to admins if this is a submission
                if ($isSubmission) {
                    AdminNotificationService::notifyNewContactForm($submission);

                    // Auto-create CRM lead from contact submission
                    try {
                        $lead = Lead::createFromContactSubmission($submission);
                        AdminNotificationService::notifyNewLead($lead);
                    } catch (\Exception $e) {
                        Log::error('Failed to create lead from new contact submission', [
                            'submission_id' => $submission->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                Log::info('New draft created', [
                    'session_id' => $data['session_id'],
                    'submission_id' => $submission->id,
                    'is_submission' => $isSubmission
                ]);
            }

            // If this is a final submission, send emails (with built-in duplicate protection)
            if ($isSubmission && $submission->status === 'submitted') {
                $this->sendSubmissionEmails($submission);
            }

            return response()->json([
                'success' => true,
                'message' => $isSubmission ? 'Form submitted successfully!' : 'Draft saved',
                'submission_id' => $submission->id,
                'status' => $submission->status
            ]);
        } catch (\Exception $e) {
            Log::error('Draft save error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => $request->input('session_id'),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save draft'
            ], 500);
        }
    }

    /**
     * Send emails for completed submissions
     * Uses atomic locking to prevent duplicate emails
     */
    private function sendSubmissionEmails(ContactSubmission $submission)
    {
        // Create a unique lock key for this submission
        $lockKey = 'email_sending_' . $submission->id;

        // Try to acquire a lock (prevents race conditions)
        $lock = Cache::lock($lockKey, 120); // 2 minute lock

        if (!$lock->get()) {
            Log::info('Email sending already in progress for submission', [
                'submission_id' => $submission->id
            ]);
            return;
        }

        try {
            // Refresh submission from DB to get latest state
            $submission->refresh();

            // Check if emails have already been sent for this submission
            if ($submission->email_sent_at) {
                Log::info('Emails already sent for this submission', [
                    'submission_id' => $submission->id,
                    'email' => $submission->email,
                    'email_sent_at' => $submission->email_sent_at
                ]);
                return;
            }

            // Double-check by querying DB directly (in case of race condition)
            $freshSubmission = ContactSubmission::where('id', $submission->id)
                ->whereNotNull('email_sent_at')
                ->first();

            if ($freshSubmission) {
                Log::info('Emails already sent (double-check)', [
                    'submission_id' => $submission->id
                ]);
                return;
            }

            // Mark as sent FIRST to prevent race conditions
            $submission->update(['email_sent_at' => now()]);

            $submissionData = $submission->toArray();

            // Send to site owner
            Mail::to('nurudeen@p23africa.com')->send(new ContactFormMail($submissionData));

            // Send thank-you to user if email is provided
            if ($submission->email) {
                Mail::to($submission->email)->send(new ThankYouMail($submissionData));
            }

            Log::info('Submission emails sent successfully', [
                'submission_id' => $submission->id,
                'email' => $submission->email
            ]);
        } catch (\Exception $mailException) {
            Log::error('Failed to send submission emails', [
                'error' => $mailException->getMessage(),
                'submission_id' => $submission->id,
                'email' => $submission->email
            ]);

            // If email sending failed, clear the email_sent_at so it can be retried
            // But only if it was set in this attempt
            // $submission->update(['email_sent_at' => null]);
        } finally {
            // Always release the lock
            $lock->release();
        }
    }

    /**
     * Get draft statistics for admin dashboard
     */
    public function getDraftStats()
    {
        $stats = [
            'total_drafts' => ContactSubmission::drafts()->count(),
            'today_drafts' => ContactSubmission::drafts()->whereDate('draft_created_at', today())->count(),
            'active_sessions' => ContactSubmission::drafts()
                ->where('last_activity_at', '>=', now()->subHours(24))
                ->count(),
            'avg_completion' => ContactSubmission::drafts()
                ->get()
                ->avg(function ($submission) {
                    return $submission->getCompletionPercentage();
                })
        ];

        return response()->json($stats);
    }
}
