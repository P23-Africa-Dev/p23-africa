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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'linkedin' => 'nullable|string',
            'company_name' => 'required|string',
            'country' => 'required|string',
            'position' => 'required|string',
            'years_of_operation' => 'required|string',
            'number_of_employees' => 'required|string',
            'goals' => 'nullable|string',
        ]);

        $submission = BrnSubmission::create($validated);

        // Auto-decline if 0-5 years or 1-10 employees
        if (
            $submission->years_of_operation === '0-5' ||
            $submission->number_of_employees === '1-10'
        ) {
            $submission->update(['status' => 'declined']);
            Mail::to($submission->email)->send(new BrnDeclined($submission));
        }

        return response()->json(['message' => 'Your application was submitted. You’ll be contacted soon.']);

        // return back()->with('success', 'Your application was submitted. You’ll be contacted soon.');
    }
}
