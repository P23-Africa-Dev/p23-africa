<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Mail\ContactFormMail;
use App\Mail\ThankYouMail;
use App\Models\ContactSubmission;
use App\Models\ConsultationApplication;
use Illuminate\Support\Str;
use App\Models\Lead;
use App\Services\AdminNotificationService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\ContactLeadAssessment;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;

class ContactController extends Controller
{
    /**
     * Unified validation rules for contact form submissions
     * reCAPTCHA is validated separately with graceful fallback
     */
    private function getSubmissionValidationRules(bool $stepped = false, bool $assessment = false): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country_code' => 'required|string|max:10',
            'services' => 'required|string',
            'message' => 'required|string|max:2000',
            // reCAPTCHA removed from here - validated separately with graceful fallback
        ];

        if ($stepped) {
            $rules['budget_range'] = 'required|numeric|min:0|max:2147483647';
            $rules += [
                'role' => 'required|string|max:255',
                'industry' => ['required', Rule::in(config('contact.industries'))],
                'business_description' => 'required_without:company_website|nullable|string|max:1000',
                'company_website' => 'required_without:business_description|nullable|string|max:2048',
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

        if ($assessment) {
            unset($rules['business_description'], $rules['selected_services'], $rules['selected_services.*']);
            $rules['company_website'] = 'required|url:http,https|max:2048';
            $rules['selected_goals'] = 'required|array|min:1|max:8';
            $rules['selected_goals.*'] = ['required', 'string', 'distinct', Rule::in(array_keys(config('contact.assessment_goals')))];
            // Budget is entered as a whole amount in GBP and drives the next step.
            $rules['budget_range'] = 'required|numeric|min:0|max:2147483647';
            $rules['expansion_markets'] = 'nullable|array|max:10';
        }

        return $rules;
    }

    /**
     * Unified validation messages for contact form
     */
    private function getValidationMessages(): array
    {
        return [
            'name.required' => __('contact.validation.name'),
            'company_name.required' => __('contact.validation.company_name'),
            'company_name.max' => __('contact.validation.company_name_max'),
            'email.required' => __('contact.validation.email'),
            'email.email' => __('contact.validation.email_invalid'),
            'phone.required' => __('contact.validation.phone'),
            'phone.max' => __('contact.validation.phone_max'),
            'country_code.required' => __('contact.validation.country_code'),
            'country_code.max' => __('contact.validation.country_code_invalid'),
            'services.required' => __('contact.validation.services'),
            'message.required' => __('contact.validation.message'),
            'budget_range.required' => 'Please enter your budget.',
            'budget_range.integer' => 'Please enter your budget as a whole number.',
            'budget_range.numeric' => 'Budget must be a valid number.',
            'budget_range.min' => 'Budget cannot be negative.',
            'budget_range.max' => 'Please enter a smaller budget amount.',
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
            $assessment = $request->input('form_flow') === 'contact-v3';
            $stepped = $assessment || $request->input('form_flow') === 'contact-v2';
            if ($assessment && is_array($request->input('selected_goals'))) {
                $services = collect($request->input('selected_goals'))->filter(fn ($goal) => is_string($goal))
                    ->map(fn ($goal) => config("contact.assessment_goals.$goal.service"))->filter()->unique()->values()->all();
                $request->merge(['services' => implode(', ', $services) ?: 'Exploring / other request']);
            } elseif ($stepped && is_array($request->input('selected_services'))) {
                $request->merge(['services' => implode(', ', array_filter($request->input('selected_services'), 'is_string'))]);
            }
            // Validate before creating a deduplication lock so a corrected enquiry can be retried.
            $validated = $request->validate(
                $this->getSubmissionValidationRules($stepped, $assessment),
                $this->getValidationMessages()
            );
            $assessmentFingerprint = $stepped ? hash('sha256', json_encode([$validated, app()->getLocale()])) : null;

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
            if ($stepped) {
                $submissionKey .= '_'.$assessmentFingerprint;
            }

            // Only use cache-based deduplication if cache is healthy
            if ($cacheHealthy) {
                // Check if we're already processing this submission (prevents double-click)
                if (Cache::has($submissionKey) && !$stepped) {
                    Log::info('Duplicate submission prevented by cache lock', [
                        'request_id' => $requestId,
                        'email' => $request->input('email'),
                        'ip' => $request->ip()
                    ]);

                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => true,
                            'message' => __('contact.validation.already_sent'),
                            'outcome' => $stepped ? app(ContactLeadAssessment::class)->evaluate($validated) : null,
                        ]);
                    }

                    return redirect()->back()->with('success', __('contact.validation.already_sent'));
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
            $duplicateCheck = null;
            try {
                $duplicateCheck = ContactSubmission::where('email', $request->input('email'))
                    ->where('phone', $request->input('phone'))
                    ->where('status', 'submitted')
                    ->where('created_at', '>=', now()->subMinutes(10))
                    ->latest('id')
                    ->first();
            } catch (\Throwable $dbCheckError) {
                Log::warning('Duplicate check query skipped', ['error' => $dbCheckError->getMessage()]);
            }

            if ($duplicateCheck && (!$stepped || ($duplicateCheck->project_details['assessment_fingerprint'] ?? null) === $assessmentFingerprint)) {
                if ($stepped) {
                    $outcome = $duplicateCheck->project_details['assessment_result'] ?? app(ContactLeadAssessment::class)->evaluate($validated);
                    $request->session()->put('contact_assessment_id', $duplicateCheck->id);
                    $request->session()->put('contact_assessment_state', $this->buildAssessmentStateFromSubmission($duplicateCheck, $outcome['route']));
                }
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
                        'message' => __('contact.validation.already_sent'),
                        'outcome' => $stepped ? ($outcome ?? app(ContactLeadAssessment::class)->evaluate($validated)) : null,
                    ]);
                }

                return redirect()->back()->with('success', __('contact.validation.already_sent'));
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
                'budget_range' => $stepped ? (int) $validated['budget_range'] : null,
                'locale' => app()->getLocale(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'submitted',
                'submitted_at' => now(),
            ];

            if ($stepped) {
                $sanitizedData['project_details'] = [];
                foreach (['role', 'industry', 'company_website', 'business_description', 'expansion_markets', 'referral_source', 'referral_other', 'start_timeline', 'additional_notes'] as $field) {
                    $value = $validated[$field] ?? null;
                    $sanitizedData['project_details'][$field] = is_array($value)
                        ? array_map('strip_tags', $value)
                        : ($value === null ? null : strip_tags($value));
                }

                $outcome = app(ContactLeadAssessment::class)->evaluate($validated);
                $sanitizedData['project_details'] += [
                    'budget_currency' => config('contact.budget_currency', 'GBP'),
                    'assessment_outcome' => $outcome['route'],
                    'assessment_result' => $outcome,
                    'assessment_fingerprint' => $assessmentFingerprint,
                ];
            }

            if ($assessment) {
                $sanitizedData['project_details'] += [
                    'company_website' => $validated['company_website'],
                    'selected_goals' => array_map(fn ($goal) => config("contact.assessment_goals.$goal.label"), $validated['selected_goals']),
                ];
            }

            // Store in database with graceful fallback if optional columns are not yet migrated on the host
            $contactSubmission = null;
            try {
                $contactSubmission = ContactSubmission::create($sanitizedData);
            } catch (\Throwable $createError) {
                Log::warning('Primary ContactSubmission::create failed, retrying with base columns', [
                    'error' => $createError->getMessage(),
                ]);
                try {
                    $fallbackData = collect($sanitizedData)->except(['project_details', 'locale'])->all();
                    $contactSubmission = ContactSubmission::create($fallbackData);
                    $contactSubmission->project_details = $sanitizedData['project_details'] ?? null;
                } catch (\Throwable $fallbackError) {
                    Log::error('Fallback ContactSubmission::create failed, using in-memory submission', [
                        'error' => $fallbackError->getMessage(),
                    ]);
                    $contactSubmission = (new ContactSubmission())->forceFill($sanitizedData);
                }
            }

            if ($stepped) {
                if ($contactSubmission->id) {
                    $request->session()->put('contact_assessment_id', $contactSubmission->id);
                }
                $request->session()->put(
                    'contact_assessment_state',
                    $this->buildAssessmentStateFromSubmission($contactSubmission, $outcome['route'])
                );
            }

            // Send notification to admins
            if ($contactSubmission->exists) {
                try {
                    AdminNotificationService::notifyNewContactForm($contactSubmission);
                } catch (\Throwable $notifyError) {
                    Log::warning('Failed to notify admins of contact submission', [
                        'submission_id' => $contactSubmission->id,
                        'error' => $notifyError->getMessage(),
                    ]);
                }

                // Auto-create CRM lead from contact submission
                try {
                    $lead = Lead::createFromContactSubmission($contactSubmission);
                    AdminNotificationService::notifyNewLead($lead);
                } catch (\Throwable $e) {
                    Log::error('Failed to create lead from contact submission', [
                        'submission_id' => $contactSubmission->id,
                        'error' => $e->getMessage()
                    ]);
                }

                // Send emails using centralized method
                $this->sendSubmissionEmails($contactSubmission);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('contact.validation.sent'),
                    'outcome' => $outcome ?? null,
                ]);
            }

            return redirect()->back()->with('success', __('contact.validation.sent'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('contact.validation.check'),
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
    private function buildAssessmentStateFromSubmission(ContactSubmission $submission, string $route, ?array $slot = null): array
    {
        $details = is_array($submission->project_details) ? $submission->project_details : [];
        if ($slot) {
            $details['preferred_slot'] = $slot;
        }

        return [
            'outcome' => $route,
            'name' => (string) $submission->name,
            'company_name' => (string) $submission->company_name,
            'email' => (string) $submission->email,
            'phone' => (string) $submission->phone,
            'country_code' => (string) ($submission->country_code ?? '+27'),
            'services' => is_array($submission->services) ? $submission->services : [],
            'message' => Str::limit((string) $submission->message, 400, ''),
            'budget_range' => (int) ($submission->budget_range ?? 0),
            'project_details' => [
                'role' => $details['role'] ?? '',
                'industry' => $details['industry'] ?? 'Not specified',
                'business_description' => Str::limit((string) ($details['business_description'] ?? $submission->message), 400, ''),
                'expansion_markets' => $details['expansion_markets'] ?? [],
                'referral_source' => $details['referral_source'] ?? '',
                'start_timeline' => $details['start_timeline'] ?? '',
                'additional_notes' => Str::limit((string) ($details['additional_notes'] ?? ''), 300, ''),
                'company_website' => $details['company_website'] ?? null,
                'selected_goals' => $details['selected_goals'] ?? null,
                'assessment_outcome' => $route,
                'preferred_slot' => $details['preferred_slot'] ?? null,
            ],
        ];
    }

    private function encodeAssessmentState(ContactSubmission $submission, string $route, ?array $slot = null): string
    {
        $state = $this->buildAssessmentStateFromSubmission($submission, $route, $slot);
        $json = json_encode($state);

        return rtrim(strtr(base64_encode($json ?: '{}'), '+/', '-_'), '=');
    }

    private function decodeAssessmentToken(?string $token): ?array
    {
        if (!is_string($token) || trim($token) === '') {
            return null;
        }

        $padded = strtr($token, '-_', '+/');
        $mod = strlen($padded) % 4;
        if ($mod > 0) {
            $padded .= str_repeat('=', 4 - $mod);
        }

        $decoded = base64_decode($padded, true);
        if ($decoded === false) {
            return null;
        }

        $data = json_decode($decoded, true);

        return is_array($data) ? $data : null;
    }

    /**
     * The contact submission for this visitor's completed assessment, if it was routed to $route.
     */
    private function assessmentSubmission(Request $request, string $route): ?ContactSubmission
    {
        $submission = null;
        $sessionId = $request->session()->get('contact_assessment_id');

        if ($sessionId) {
            try {
                $submission = ContactSubmission::find($sessionId);
            } catch (\Throwable $e) {
                $submission = null;
            }
        }

        if ($submission) {
            $details = is_array($submission->project_details) ? $submission->project_details : [];
            if (empty($details) && is_array($request->session()->get('contact_assessment_state.project_details'))) {
                $details = $request->session()->get('contact_assessment_state.project_details');
                $submission->project_details = $details;
            }
            $outcome = $details['assessment_outcome'] ?? null;
            if ($outcome === $route) {
                return $submission;
            }
            if ($outcome !== null && !$request->filled('assessment')) {
                return null;
            }
        }

        $state = $this->decodeAssessmentToken($request->input('assessment'))
            ?? $request->session()->get('contact_assessment_state');

        if (!is_array($state)) {
            return null;
        }

        $evaluatedRoute = app(ContactLeadAssessment::class)->evaluate([
            'budget_range' => $state['budget_range'] ?? 0,
        ])['route'];
        $stateRoute = $state['outcome'] ?? $evaluatedRoute;

        if ($stateRoute !== $route || $evaluatedRoute !== $route) {
            return null;
        }

        $projectDetails = is_array($state['project_details'] ?? null) ? $state['project_details'] : [
            'role' => $state['role'] ?? '',
            'industry' => $state['industry'] ?? 'Not specified',
            'business_description' => $state['business_description'] ?? ($state['message'] ?? ''),
            'expansion_markets' => $state['expansion_markets'] ?? [],
            'referral_source' => $state['referral_source'] ?? '',
            'start_timeline' => $state['start_timeline'] ?? '',
            'additional_notes' => $state['additional_notes'] ?? '',
            'assessment_outcome' => $route,
        ];
        $projectDetails['assessment_outcome'] = $route;
        if (!empty($state['preferred_slot']) && empty($projectDetails['preferred_slot'])) {
            $projectDetails['preferred_slot'] = $state['preferred_slot'];
        }

        $attributes = [
            'name' => strip_tags((string) ($state['name'] ?? 'Guest')),
            'company_name' => strip_tags((string) ($state['company_name'] ?? '')),
            'email' => filter_var((string) ($state['email'] ?? ''), FILTER_SANITIZE_EMAIL),
            'phone' => strip_tags((string) ($state['phone'] ?? '')),
            'country_code' => strip_tags((string) ($state['country_code'] ?? '+27')),
            'services' => is_array($state['services'] ?? null) ? $state['services'] : [],
            'message' => strip_tags((string) ($state['message'] ?? '')),
            'budget_range' => (int) ($state['budget_range'] ?? 0),
            'project_details' => $projectDetails,
            'locale' => app()->getLocale(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'submitted',
            'submitted_at' => now(),
        ];

        try {
            $restored = ContactSubmission::create($attributes);
            $request->session()->put('contact_assessment_id', $restored->id);
        } catch (\Throwable $e) {
            $restored = (new ContactSubmission())->forceFill($attributes);
        }

        $request->session()->put(
            'contact_assessment_state',
            $this->buildAssessmentStateFromSubmission($restored, $route, $projectDetails['preferred_slot'] ?? null)
        );

        if (!empty($projectDetails['preferred_slot']) && !$request->session()->has('contact_consultation_slot')) {
            $request->session()->put('contact_consultation_slot', $projectDetails['preferred_slot']);
        }

        return $restored;
    }

    private function slotRules(bool $allowToday = true): array
    {
        return [
            // Paid consultations are booked from tomorrow onwards (ConsultationController::scheduleSession).
            'session_date' => 'required|date_format:Y-m-d|'.($allowToday ? 'after_or_equal' : 'after').':today|before_or_equal:'.now()->addMonths(6)->format('Y-m-d'),
            'session_time' => 'required|date_format:H:i',
            // The scheduling page sends the IANA zone "Africa/Lagos" for WAT.
            'timezone' => ['required', Rule::in(['GMT', 'UTC', 'WAT', 'Africa/Lagos'])],
        ];
    }

    /**
     * Route 1 (budget above the consultation band): pick a discovery call time.
     */
    public function showDiscoverySchedule(Request $request)
    {
        $submission = $this->assessmentSubmission($request, 'discovery');
        if (!$submission) {
            return redirect()->to(locale_route('contact'));
        }

        return view('consultation.schedule-consultation', [
            'application' => null,
            'scheduleMode' => 'discovery',
            'scheduleTitle' => __('contact.flow.discovery_title'),
            'scheduleBody' => __('contact.flow.discovery_body'),
            'scheduleAction' => locale_route('contact.discovery-request'),
            'confirmTitle' => __('contact.flow.discovery_confirmed'),
            'confirmBody' => __('contact.flow.discovery_confirmed_body'),
            'contactEmail' => $submission->email,
            'assessmentToken' => $this->encodeAssessmentState($submission, 'discovery'),
        ]);
    }

    public function requestDiscovery(Request $request)
    {
        $submission = $this->assessmentSubmission($request, 'discovery');
        if (!$submission) {
            return response()->json(['success' => false, 'message' => 'Please complete the contact form first.'], 403);
        }

        $validated = $request->validate($this->slotRules());
        $details = is_array($submission->project_details) ? $submission->project_details : [];
        $details['discovery_request'] = [
            'preferred_date' => $validated['session_date'],
            'preferred_time' => $validated['session_time'],
            'timezone' => $validated['timezone'],
            'requested_at' => now()->toIso8601String(),
        ];
        $details['discovery_request'] += $this->bookDiscoveryCall($submission, $validated);
        $submission->project_details = $details;
        if ($submission->exists) {
            try {
                $submission->update(['project_details' => $details]);
            } catch (\Throwable $e) {
                Log::warning('Could not persist discovery_request to DB', ['error' => $e->getMessage()]);
            }
        }

        try {
            if ($submission->exists) {
                AdminNotificationService::notifyNewContactForm($submission);
            }
            Mail::to('contact@p23africa.com')->send(new ContactFormMail($submission->toArray()));
        } catch (\Throwable $exception) {
            Log::error('Discovery request notification failed', ['submission_id' => $submission->id, 'error' => $exception->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => __('contact.flow.discovery_confirmed_body'),
            'user_email' => $submission->email,
        ]);
    }

    /**
     * Put the discovery call on the P23 calendar with a Google Meet link. Google emails the invite to the
     * visitor, which is what the confirmation modal promises. A calendar failure never loses the request:
     * it is still stored and the team is notified to follow up by hand.
     */
    private function bookDiscoveryCall(ContactSubmission $submission, array $slot): array
    {
        try {
            $timezone = $slot['timezone'] === 'WAT' ? 'Africa/Lagos' : $slot['timezone'];
            $start = Carbon::createFromFormat('Y-m-d H:i', $slot['session_date'].' '.$slot['session_time'], $timezone);
            $end = $start->copy()->addMinutes((int) config('contact.discovery_call_minutes', 30));
            $adminEmail = config('services.admin.email', 'contact@p23africa.com');
            $description = "Discovery call\n\n"
                ."Name: {$submission->name}\n"
                ."Company: {$submission->company_name}\n"
                ."Email: {$submission->email}\n"
                ."Phone: {$submission->phone}\n\n"
                ."Project:\n{$submission->message}\n\n"
                .'This call will be held on Google Meet.';

            $result = app(GoogleCalendarService::class)->createEvent(
                'P23 Africa Discovery Call',
                $description,
                $start->toIso8601String(),
                $end->toIso8601String(),
                $timezone,
                [$adminEmail, $submission->email]
            );

            if (!empty($result['success'])) {
                return [
                    'status' => 'scheduled',
                    'google_calendar_event_id' => $result['event_id'] ?? null,
                    'meet_link' => $result['meet_link'] ?? null,
                    'calendar_html_link' => $result['html_link'] ?? null,
                ];
            }

            Log::warning('Discovery call calendar event not created', ['submission_id' => $submission->id, 'error' => $result['error'] ?? null]);
        } catch (\Throwable $exception) {
            Log::error('Discovery call calendar booking failed', ['submission_id' => $submission->id, 'error' => $exception->getMessage()]);
        }

        return ['status' => 'requested'];
    }

    /**
     * Route 2 (consultation budget band), step 1: pick the consultation time.
     */
    public function showConsultationSchedule(Request $request)
    {
        $submission = $this->assessmentSubmission($request, 'consultation');
        if (!$submission) {
            return redirect()->to(locale_route('contact'));
        }

        return view('consultation.schedule-consultation', [
            'application' => null,
            'scheduleMode' => 'consultation',
            'scheduleTitle' => __('consultation.schedule_title'),
            'scheduleBody' => __('consultation.schedule_body'),
            'scheduleAction' => locale_route('contact.consultation.slot'),
            'assessmentToken' => $this->encodeAssessmentState($submission, 'consultation'),
        ]);
    }

    public function storeConsultationSlot(Request $request)
    {
        $submission = $this->assessmentSubmission($request, 'consultation');
        if (!$submission) {
            return response()->json(['success' => false, 'message' => 'Please complete the contact form first.'], 403);
        }

        $slot = $request->validate($this->slotRules(false));
        $request->session()->put('contact_consultation_slot', $slot);
        $details = is_array($submission->project_details) ? $submission->project_details : [];
        $details['preferred_slot'] = $slot;
        $submission->project_details = $details;
        if ($submission->exists) {
            try {
                $submission->update(['project_details' => $details]);
            } catch (\Throwable $e) {
                Log::warning('Could not persist preferred_slot to DB', ['error' => $e->getMessage()]);
            }
        }
        $request->session()->put(
            'contact_assessment_state',
            $this->buildAssessmentStateFromSubmission($submission, 'consultation', $slot)
        );

        $redirectParams = $request->filled('assessment')
            ? ['assessment' => $this->encodeAssessmentState($submission, 'consultation', $slot)]
            : [];

        return response()->json([
            'success' => true,
            'redirect' => locale_route('contact.consultation.challenges', $redirectParams),
        ]);
    }

    /**
     * Route 2, step 2: key challenges. Creates the consultation application and
     * hands over to the existing summary -> payment -> success screens.
     */
    public function showKeyChallenges(Request $request)
    {
        $submission = $this->assessmentSubmission($request, 'consultation');
        if (!$submission) {
            return redirect()->to(locale_route('contact'));
        }
        $slot = $request->session()->get('contact_consultation_slot')
            ?: ($submission->project_details['preferred_slot'] ?? null);
        if (!$slot) {
            return redirect()->to(locale_route('contact.consultation.schedule'));
        }
        $request->session()->put('contact_consultation_slot', $slot);

        return view('contact.key-challenges', [
            'challenges' => $this->keyChallengeOptions(),
            'assessmentToken' => $this->encodeAssessmentState($submission, 'consultation', $slot),
        ]);
    }

    public function submitKeyChallenges(Request $request)
    {
        $submission = $this->assessmentSubmission($request, 'consultation');
        $slot = $request->session()->get('contact_consultation_slot')
            ?: ($submission?->project_details['preferred_slot'] ?? null);
        if (!$submission || !$slot) {
            return redirect()->to(locale_route('contact'));
        }

        $validated = $request->validate([
            'key_challenges' => 'required|array|min:1|max:3',
            'key_challenges.*' => ['required', 'string', 'distinct', Rule::in($this->keyChallengeOptions())],
        ], [
            'key_challenges.required' => __('consultation.validation.challenges_required'),
            'key_challenges.min' => __('consultation.validation.challenges_required'),
            'key_challenges.max' => __('consultation.validation.challenges_max'),
        ]);

        $details = $submission->project_details ?? [];
        $appAttributes = [
            'booking_token' => (string) Str::uuid(),
            'name' => $submission->name,
            'email' => $submission->email,
            'phone' => trim(($submission->country_code ?? '').' '.$submission->phone),
            'company' => $submission->company_name,
            'website_or_handle' => $details['company_website'] ?? null,
            'industry' => $details['industry'] ?? 'Not specified',
            'business_description' => $details['business_description'] ?? $submission->message,
            'selected_goals' => $details['selected_goals'] ?? $submission->services ?? [],
            'selected_challenges' => array_values($validated['key_challenges']),
            'desired_outcomes' => json_encode([
                'motivation' => $submission->message,
                'success_definition' => $details['additional_notes'] ?? '',
                'preferred_slot' => $slot,
                'contact_submission_id' => $submission->id,
                'expansion_markets' => $details['expansion_markets'] ?? [],
            ]),
            'status' => 'pending',
            'submitted_at' => now(),
            'locale' => app()->getLocale(),
        ];

        try {
            $application = ConsultationApplication::create($appAttributes);
        } catch (\Throwable $createAppError) {
            Log::warning('Primary ConsultationApplication::create failed, retrying with base columns', [
                'error' => $createAppError->getMessage(),
            ]);
            try {
                $fallbackApp = collect($appAttributes)->except(['booking_token', 'locale'])->all();
                $application = ConsultationApplication::create($fallbackApp);
                $application->booking_token = $appAttributes['booking_token'];
            } catch (\Throwable $fallbackAppError) {
                $application = (new ConsultationApplication())->forceFill($appAttributes);
            }
        }

        $details['consultation_application_id'] = $application->id;
        $submission->project_details = $details;
        if ($submission->exists) {
            try {
                $submission->update(['project_details' => $details]);
            } catch (\Throwable $e) {
                Log::warning('Could not update consultation_application_id on submission', ['error' => $e->getMessage()]);
            }
        }

        if ($application->exists) {
            try {
                AdminNotificationService::notifyNewConsultation($application);
            } catch (\Throwable $exception) {
                Log::error('Consultation notification failed', ['application_id' => $application->id, 'error' => $exception->getMessage()]);
            }
        }

        $bookingState = [
            'booking_token' => $application->booking_token,
            'name' => $application->name,
            'email' => $application->email,
            'phone' => $application->phone,
            'company' => $application->company,
            'website_or_handle' => $application->website_or_handle,
            'industry' => $application->industry,
            'business_description' => Str::limit((string) $application->business_description, 400, ''),
            'selected_goals' => $application->selected_goals,
            'selected_challenges' => $application->selected_challenges,
            'desired_outcomes' => $application->desired_outcomes,
            'status' => $application->status,
            'locale' => $application->locale ?: app()->getLocale(),
        ];

        $request->session()->put('consultation_application_id', $application->id);
        $request->session()->put('consultation_booking_token', $application->booking_token);
        $request->session()->put('consultation_booking_state', $bookingState);

        $summaryParams = ['booking' => $application->booking_token];
        if ($request->filled('assessment')) {
            $summaryParams['state'] = rtrim(strtr(base64_encode(json_encode($bookingState) ?: '{}'), '+/', '-_'), '=');
        }

        return redirect()->to(locale_route('consultation.consultation-summary', $summaryParams));
    }

    private function keyChallengeOptions(): array
    {
        return array_map(fn ($key) => __("consultation.$key"), ['ch_sales', 'ch_marketing', 'ch_customers', 'ch_ops', 'ch_money', 'ch_fit', 'ch_scale']);
    }

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
            Mail::to('contact@p23africa.com')->send(new ContactFormMail($submissionData));

            // Queue thank-you email to user (async - won't block response)
            if ($submission->email) {
                Mail::to($submission->email)->send(
                    (new ThankYouMail($submissionData))->locale($submission->locale ?: 'en')
                );
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
