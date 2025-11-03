<?php

namespace App\Http\Controllers;

use App\Helpers\ClickTracker;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function showForm(Request $request)
    {
        ClickTracker::track('Feedback_Page');

        return view('feedback.form', [
            'event_id' => $request->event_id,
            'email' => $request->email,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'email' => 'required|email',
            'rating' => 'required|string',
            'comments' => 'nullable|string',
        ]);

        Feedback::create($request->only(['event_id', 'email', 'rating', 'comments']));

        return redirect()->route('feedback.thankyou'); // Optional thank-you page
    }
}
