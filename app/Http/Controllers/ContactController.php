<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;
use App\Mail\ThankYouMail;
use App\Models\ContactSubmission;

class ContactController extends Controller
{
    public function submitForm(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'company_name' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'country_code' => 'nullable|string|max:10',
                'services' => 'nullable|string', // Services will come as comma-separated string
                'message' => 'required|string|max:2000',
                'budget_range' => 'nullable|integer|min:100|max:5000',
                'g-recaptcha-response' => 'required|captcha', // reCAPTCHA validation
            ], [
                'name.required' => 'Name is required.',
                'company_name.max' => 'Company name must not exceed 255 characters.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'phone.max' => 'Phone number must not exceed 20 characters.',
                'country_code.max' => 'Invalid country code.',
                'message.required' => 'Message is required.',
                'budget_range.integer' => 'Budget must be a valid number.',
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
                'company_name' => !empty($validated['company_name']) ? strip_tags($validated['company_name']) : null,
                'email' => filter_var($validated['email'], FILTER_SANITIZE_EMAIL),
                'phone' => !empty($validated['phone']) ? strip_tags($validated['phone']) : null,
                'country_code' => !empty($validated['country_code']) ? strip_tags($validated['country_code']) : null,
                'services' => $services,
                'message' => strip_tags($validated['message']),
                'budget_range' => !empty($validated['budget_range']) ? (int)$validated['budget_range'] : null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];

            // Store in database
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
}
