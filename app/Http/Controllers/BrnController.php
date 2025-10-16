<?php

namespace App\Http\Controllers;

use App\Helpers\ClickTracker;
use App\Mail\BrnDeclined;
use App\Models\BrnSubmission;
use App\Models\Event;
use Illuminate\Http\Request;
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
            'linkedin' => 'required|string|url|max:500',
            'company_name' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'position' => 'required|string|max:255',
            'years_of_operation' => 'required|string|in:0-5,6-10,11-Above',
            'number_of_employees' => 'required|string|in:1-10,11-50,50-200,200+',
            'selected_outcome' => 'required|string|max:500',
            'goals' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Full name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.required' => 'WhatsApp phone number is required.',
            'linkedin.required' => 'LinkedIn profile URL is required.',
            'linkedin.url' => 'Please enter a valid LinkedIn profile URL.',
            'company_name.required' => 'Company name is required.',
            'country.required' => 'Business headquarters country is required.',
            'position.required' => 'Your position in the business is required.',
            'years_of_operation.required' => 'Please select your business years of operation.',
            'years_of_operation.in' => 'Please select a valid option for years of operation.',
            'number_of_employees.required' => 'Please select your number of employees.',
            'number_of_employees.in' => 'Please select a valid option for number of employees.',
            'selected_outcome.required' => 'Please select what outcomes matter most to you.',
        ]);

        $submission = BrnSubmission::create($validated);

        // Auto-decline if 0-5 years or 1-10 employees
        // if (
        //     $submission->years_of_operation === '0-5' ||
        //     $submission->number_of_employees === '1-10'
        // ) {
        //     $submission->update(['status' => 'declined']);
        //     Mail::to($submission->email)->send(new BrnDeclined($submission));
        // }

        return response()->json(['message' => 'Your application was submitted. You will be contacted soon.']);

        // return back()->with('success', 'Your application was submitted. You will be contacted soon.');
    }
}
