<?php

namespace App\Http\Controllers;

use App\Mail\ChallengeResult;
use App\Mail\ChallengeSubmission;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function submitChallenge(Request $request)
    {
        $data = $request->validate([
            'challenge1' => 'nullable|string',
            'challenge2' => 'nullable|string',
            'challenge3' => 'nullable|string',
            'label' => 'required|in:FRAGILE FUNNEL,HIDDEN POWERHOUSE,CLOUDY CLIMBER',
            'email' => 'nullable|email'
        ]);

        // Send to company
        Mail::to('nurudeen@p23africa.com')->send(new ChallengeSubmission($data));

        // Send to user
        if (!empty($data['email'])) {
            Mail::to($data['email'])->send(new ChallengeResult($data));
        }   


        return response()->json(['status' => 'ok']);
    }
}
