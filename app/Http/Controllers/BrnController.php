<?php

namespace App\Http\Controllers;

use App\Helpers\ClickTracker;
use App\Mail\BrnDeclined;
use App\Models\BrnSubmission;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BrnController extends Controller
{
    public function brnIndex()
    {
        $events = Event::whereIn('display', ['open_to_all', 'brn_only'])
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->take(2) // or ->limit(5)
            ->get();

        ClickTracker::track('BRN_Page');

        return view('brn', compact('events'));
    }

    public function showForm()
    {
        ClickTracker::track('BRN_Form_Page');

        return view('brn-form');
    }

    public function checkEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $exists = BrnSubmission::where('email', $request->email)->exists();

            if ($exists) {
                return response()->json([
                    'exists' => true,
                    'message' => 'This email address is already registered.'
                ], 422);
            }

            return response()->json([
                'exists' => false,
                'message' => 'Email is available.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'exists' => false,
                'message' => 'Unable to validate email at the moment.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:brn_submissions,email|max:255',
            'phone' => 'required|string|max:50',
            'country_code' => 'nullable|string|max:10',
            'position' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'reg_number' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:500',
            'year_established' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'linkedin' => 'nullable|string|url|max:500',
            'country' => 'required|string|max:100',
            'industry' => 'nullable|string|max:100',
            'categories' => 'nullable|string|max:1000',
            'number_of_employees' => 'required|string|in:1-10,11-50,50-200,200+',
            'years_of_operation' => 'required|string|in:0-5,6-10,11-Above',
            'offer_value' => 'nullable|string|max:1000',
            'goals' => 'required|string|max:2000',
            'selected_outcome' => 'required|string|max:500',
            'b2b_interest' => 'required|string|in:yes,no,maybe',
        ], [
            'name.required' => 'Full name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.required' => 'Phone number is required.',
            'position.required' => 'Your title/position is required.',
            'company_name.required' => 'Company name is required.',
            'linkedin.url' => 'Please enter a valid LinkedIn profile URL.',
            'country.required' => 'Country of registration is required.',
            'number_of_employees.required' => 'Please select your number of employees.',
            'number_of_employees.in' => 'Please select a valid option for number of employees.',
            'years_of_operation.required' => 'Please select your business years of operation.',
            'years_of_operation.in' => 'Please select a valid option for years of operation.',
            'goals.required' => 'Please describe your company and what it does.',
            'selected_outcome.required' => 'Please select what you are most interested in from this network.',
            'year_established.integer' => 'Please enter a valid year.',
            'year_established.min' => 'Please enter a year from 1900 onwards.',
            'year_established.max' => 'Please enter a valid year.',
            'website.url' => 'Please enter a valid website URL.',
            'reg_number.max' => 'Registration number is too long.',
            'offer_value.max' => 'Offer value description is too long.',
            'categories.max' => 'Selected categories text is too long.',
        ]);

        try {
            // Combine country code and phone if provided
            if ($request->country_code && $request->phone) {
                $validated['phone'] = $validated['country_code'] . $validated['phone'];
                // Remove country_code from validated data as we don't store it separately
                unset($validated['country_code']);
            }

            // Clean and format LinkedIn URL if provided
            if (!empty($validated['linkedin'])) {
                $validated['linkedin'] = $this->normalizeLinkedInURL($validated['linkedin']);
            }

            // Clean website URL if provided
            if (!empty($validated['website'])) {
                $validated['website'] = $this->normalizeWebsiteURL($validated['website']);
            }

            // Create the submission
            $submission = BrnSubmission::create($validated);

            // Track submission for analytics
            ClickTracker::track('BRN_Form_Submission');

            // Auto-decline logic (optional - uncomment if needed)
            // if ($submission->years_of_operation === '0-5' || $submission->number_of_employees === '1-10') {
            //     $submission->update(['status' => 'declined']);
            //     Mail::to($submission->email)->send(new BrnDeclined($submission));
            // }

            return response()->json([
                'success' => true,
                'message' => 'Your application was submitted successfully. You will be contacted soon.',
                'submission_id' => $submission->id,
                'clear_storage' => true
            ]);
        } catch (\Exception $e) {
            Log::error('BRN Form Submission Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your application. Please try again.'
            ], 500);
        }
    }

    /**
     * Normalize LinkedIn URL to standard format
     */
    private function normalizeLinkedInURL($url)
    {
        if (empty($url)) return $url;

        // Remove any leading/trailing whitespace
        $url = trim($url);

        // Add https:// if not present
        if (!preg_match('/^https?:\/\//', $url)) {
            $url = 'https://' . $url;
        }

        // Ensure it's a valid LinkedIn URL
        if (!str_contains(strtolower($url), 'linkedin.com')) {
            // If it doesn't contain linkedin.com, assume it's a username
            $url = 'https://linkedin.com/in/' . trim($url, '/@');
        }

        return $url;
    }

    /**
     * Normalize website URL to standard format
     */
    private function normalizeWebsiteURL($url)
    {
        if (empty($url)) return $url;

        // Remove any leading/trailing whitespace
        $url = trim($url);

        // Add https:// if not present and it's not already a valid URL
        if (!preg_match('/^https?:\/\//', $url)) {
            $url = 'https://' . $url;
        }

        return $url;
    }

    /**
     * Display success page after form submission
     */
    public function success()
    {
        return view('brn-success');
    }
}
