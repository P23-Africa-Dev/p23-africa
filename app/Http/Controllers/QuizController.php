<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function submitChallenge(Request $request)
    {
        $request->validate([
            'challenge1' => 'nullable|string',
            'challenge2' => 'nullable|string',
            'challenge3' => 'nullable|string',
            'label' => 'required|in:FRAGILE FUNNEL,Hidden Powerhouse,Cloudy Climber'
        ]);

        $content = "New Quiz Challenge Submission:\n\n"
            . "Category: {$request->label}\n"
            . "Challenge 1: {$request->challenge1}\n"
            . "Challenge 2: {$request->challenge2}\n"
            . "Challenge 3: {$request->challenge3}";

        Mail::raw($content, function ($message) {
            $message->to('nurudeen@p23africa.com')->subject('Quiz Challenge Submission');
        });

        return response()->json(['status' => 'ok']);
    }
}
