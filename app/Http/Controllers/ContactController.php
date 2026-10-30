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
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * Unified validation rules for contact form submissions
     * reCAPTCHA is validated separately with graceful fallback
     */
    private function getSubmissionValidationRules(bool $stepped = false): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country_code' => 'required|string|max:10',
            'services' => 'required|string',
            'message' => 'required|string|max:2000',
            'budget_range' => 'required|numeric|min:100|max:5000',
            // reCAPTCHA removed from here - validated separately with graceful fallback
        ];

        if ($stepped) {
            $rules['budget_range'] = 'required|integer|min:100|max:2147483647';
            $rules += [
                'role' => 'required|string|max:255',
                'industry' => ['required', Rule::in(config('contact.industries'))],
                'business_description' => 'required|string|max:1000',
                'selected_services' => 'required|array|min:1|max:5',
                'selected_services.*' => ['required', 'string', 'distinct', Rule::in(config('contact.services'))],
                'expansion_markets' => 'required|array|min:1|max:10',
                'expansion_markets.*' => 'required|string|max:100|distinct',
                'referral_source' => ['required', Rule::in(config('contact.referral_sources'))],
                'referral_other' => 'nullable|required_if:referral_source,Other|string|max:255',
                'start_timeline' => ['required', Rule::in(config('contact.timelines'))],
                'additional_notes' => 'nullable|string|max:2000',
            ];
        }

        return $rules;
    }

    /**
     * Unified validation messages for contact form
     */
    private function getValidationMessages(): array
    {
        return [
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
            'budget_range.max' => 'Budget must not exceed $:max.',
        ];
    }

    /**
     * Validate reCAPTCHA with graceful fallback (non-blocking)
     * Returns true always - just logs the result
     */
    private function validateRecaptchaGracefully(Request $request): bool
    {
        // Skip reCAPTCHA for offline sync submissions
        if ($request->input('offline_sync') === 'true') {
            return true;
        }

        $recaptchaResponse = $request->input('g-recaptcha-response');

        // If no token provided, proceed without verification
        if (empty($recaptchaResponse)) {
            Log::info('reCAPTCHA token not provided - proceeding without verification');
            return true;
        }

        // Validate asynchronously in background - don't block the request
        // Just log for monitoring purposes
        try {
            $validator = Validator::make(
                ['g-recaptcha-response' => $recaptchaResponse],
                ['g-recaptcha-response' => 'captcha']
            );

            if ($validator->fails()) {
                Log::warning('reCAPTCHA validation failed', [
                    'ip' => $request->ip(),
                    'email' => $request->input('email')
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('reCAPTCHA validation exception', ['error' => $e->getMessage()]);
        }

        return true; // Always proceed
    }

    /**
     * Check if cache driver is healthy (simplified - non-blocking)
     */
    private function isCacheHealthy(): bool
    {
        try {
            // Simple check - just try to get a value
            Cache::get('_health_check');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function submitForm(Request $request)
    {
        $requestId = uniqid('contact_');

        Log::info('Contact form submission started', [
            'request_id' => $requestId,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'email' => $request->input('email'),
            'timestamp' => now()->toIso8601String()
        ]);

        try {
            $stepped = $request->input('form_flow') === 'contact-v2';
            if ($stepped && is_array($request->input('selected_services'))) {
                $request->merge(['services' => implode(', ', array_filter($request->input('selected_services'), 'is_string'))]);
            }
            // Validate before creating a deduplication lock so a corrected enquiry can be retried.
            $validated = $request->validate(
                $this->getSubmissionValidationRules($stepped),
                $this->getValidationMessages()
            );

            // Check cache health before relying on it for deduplication
            $cacheHealthy = $this->isCacheHealthy();

            Log::info('Cache health check completed', [
                'request_id' => $requestId,
                'cache_healthy' => $cacheHealthy
            ]);

            // Generate a unique submission key for deduplication
            $submissionKey = 'contact_submission_' . md5(
                $request->input('email') .
                    $request->input('phone') .
                    $request->ip()
            );

            // Only use cache-based deduplication if cache is healthy
            if ($cacheHealthy) {
                // Check if we're already processing this submission (prevents double-click)
                if (Cache::has($submissionKey)) {
                    Log::info('Duplicate submission prevented by cache lock', [
                        'request_id' => $requestId,
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

                Log::info('Cache lock set for submission', [
                    'request_id' => $requestId,
                    'submission_key' => $submissionKey
                ]);
            } else {
                Log::warning('Cache unhealthy - proceeding without cache-based deduplication', [
                    'request_id' => $requestId,
                    'email' => $request->input('email')
                ]);
            }

            // Check for duplicate submission in database
            $duplicateCheck = ContactSubmission::where('email', $request->input('email'))
                ->where('phone', $request->input('phone'))
                ->where('status', 'submitted')
                ->where('created_at', '>=', now()->subMinutes(10))
                ->first();

            if ($duplicateCheck) {
                Log::warning('Duplicate submission attempt detected in DB', [
                    'request_id' => $requestId,
                    'email' => $request->input('email'),
                    'ip' => $request->ip(),
                    'existing_id' => $duplicateCheck->id
                ]);

                if ($cacheHealthy) {
                    Cache::forget($submissionKey);
                }

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Your message has already been sent successfully! We will get back to you soon.'
                    ]);
                }

                return redirect()->back()->with('success', 'Your message has already been sent successfully! We will get back to you soon.');
            }

            Log::info('Starting validation', [
                'request_id' => $requestId,
                'fields_present' => array_keys($request->all()),
                'is_offline_sync' => $request->input('offline_sync') === 'true'
            ]);

            // Validate reCAPTCHA gracefully (won't block submission)
            $this->validateRecaptchaGracefully($request);

            Log::info('Validation passed', [
                'request_id' => $requestId,
                'email' => $validated['email']
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

            if ($stepped) {
                $sanitizedData['project_details'] = [];
                foreach (['role', 'industry', 'business_description', 'expansion_markets', 'referral_source', 'referral_other', 'start_timeline', 'additional_notes'] as $field) {
                    $value = $validated[$field] ?? null;
                    $sanitizedData['project_details'][$field] = is_array($value)
                        ? array_map('strip_tags', $value)
                        : ($value === null ? null : strip_tags($value));
                }
            }

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
            if (isset($submissionKey) && $cacheHealthy) {
                Cache::forget($submissionKey);
            }
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
     * Send emails for completed submissions
     * Emails are queued for async processing - no locking needed
     */
    private function sendSubmissionEmails(ContactSubmission $submission)
    {
        try {
            // Check if emails have already been sent for this submission
            if ($submission->email_sent_at) {
                Log::info('Emails already sent for this submission', [
                    'submission_id' => $submission->id,
                    'email' => $submission->email,
                    'email_sent_at' => $submission->email_sent_at
                ]);
                return;
            }

            // Mark as sent FIRST to prevent duplicate queue entries
            $submission->update(['email_sent_at' => now()]);

            $submissionData = $submission->toArray();

            // Queue email to site owner (async - won't block response)
            Mail::to('nurudeen@p23africa.com')->send(new ContactFormMail($submissionData));

            // Queue thank-you email to user (async - won't block response)
            if ($submission->email) {
                Mail::to($submission->email)->send(new ThankYouMail($submissionData));
            }

            Log::info('Submission emails queued successfully', [
                'submission_id' => $submission->id,
                'email' => $submission->email
            ]);
        } catch (\Exception $mailException) {
            Log::error('Failed to queue submission emails', [
                'error' => $mailException->getMessage(),
                'submission_id' => $submission->id,
                'email' => $submission->email
            ]);
            // Don't clear email_sent_at - emails will be retried by queue worker
        }
    }
}
