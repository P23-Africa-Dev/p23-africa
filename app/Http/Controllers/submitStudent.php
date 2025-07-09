<?php

namespace App\Http\Controllers;

use App\Mail\StudentMail;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class submitStudent extends Controller
{
    public function submitStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'uniname' => 'required|string|max:255',
            'region' => 'required|string|max:255',
        ]);

        $student  = Student::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'uniname' => $validated['uniname'],
            'region' => $validated['region'],
        ]);


        Mail::to($validated['email'])->send(new StudentMail($student));

        return response()->json(['message' => 'Request sent successfully']);
    }
}
