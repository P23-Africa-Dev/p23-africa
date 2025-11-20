<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;
use App\Mail\ThankYouMail;
use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function submitForm(Request $request)
    {
        try {
            // Check if this is from live capture (has session_id)
            $sessionId = $request->input('session_id');
            if ($sessionId) {
                // If live capture is enabled, redirect to saveDraft with submission flag
                $request->merge(['is_submission' => true]);
                return $this->saveDraft($request);
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

            // Send emails
            try {
                // Send to site owner
                Mail::to('nurudeen@p23africa.com')->send(new ContactFormMail($sanitizedData));

                // Send thank-you to user
                Mail::to($sanitizedData['email'])->send(new ThankYouMail($sanitizedData));

                // Update email sent timestamp
                $contactSubmission->update(['email_sent_at' => now()]);

                Log::info('Contact form emails sent successfully', ['email' => $sanitizedData['email']]);
            } catch (\Exception $mailException) {
                Log::error('Failed to send contact form emails', [
                    'error' => $mailException->getMessage(),
                    'email' => $sanitizedData['email']
                ]);

                // Don't fail the entire request if email fails
                return response()->json([
                    'success' => true,
                    'message' => 'Your message has been received, but there was an issue sending confirmation emails. We will contact you soon.'
                ]);
            }

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
                ->where('status', 'draft')
                ->first();

            if ($submission) {
                // Update existing draft
                $contactData['status'] = $isSubmission ? 'submitted' : 'draft';
                if ($isSubmission) {
                    $contactData['submitted_at'] = now();
                }

                $submission->update($contactData);

                Log::info('Draft updated', [
                    'session_id' => $data['session_id'],
                    'submission_id' => $submission->id,
                    'is_submission' => $isSubmission
                ]);
            } else {
                // Create new draft
                $contactData['status'] = $isSubmission ? 'submitted' : 'draft';
                $contactData['draft_created_at'] = now();
                if ($isSubmission) {
                    $contactData['submitted_at'] = now();
                }

                $submission = ContactSubmission::create($contactData);

                Log::info('New draft created', [
                    'session_id' => $data['session_id'],
                    'submission_id' => $submission->id,
                    'is_submission' => $isSubmission
                ]);
            }

            // If this is a final submission, send emails
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
     */
    private function sendSubmissionEmails(ContactSubmission $submission)
    {
        try {
            $submissionData = $submission->toArray();

            // Send to site owner
            Mail::to('nurudeen@p23africa.com')->send(new ContactFormMail($submissionData));

            // Send thank-you to user if email is provided
            if ($submission->email) {
                Mail::to($submission->email)->send(new ThankYouMail($submissionData));
            }

            // Update email sent timestamp
            $submission->update(['email_sent_at' => now()]);

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
