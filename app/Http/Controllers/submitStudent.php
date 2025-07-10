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
        $validated_students = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'uniname' => 'required|string|max:255',
            'region' => 'required|string|max:255',
        ]);

        Student::create([
            'name' => $validated_students['name'],
            'email' => $validated_students['email'],
            'phone' => $validated_students['phone'],
            'occupation' => $validated_students['occupation'],
            'uniname' => $validated_students['uniname'],
            'region' => $validated_students['region'],
        ]);


        Mail::to($validated_students['email'])->send(new StudentMail($validated_students));

        return response()->json(['message' => 'Request sent successfully']);
    }
}
