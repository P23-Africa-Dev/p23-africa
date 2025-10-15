<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\Mail\ThankYouMail;

class ContactController extends Controller
{
    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
            'job_title' => 'nullable|string',
            'company_name' => 'nullable|string',
            'phone_no' => 'required|string',
            'g-recaptcha-response' => 'required|captcha', // 👈 this enforces captcha
        ]);

        // Sanitize all inputs
        $sanitizedData = [
            'name' => strip_tags($validated['name']),
            'email' => filter_var($validated['email'], FILTER_SANITIZE_EMAIL),
            'message' => strip_tags($validated['message']),
            'job_title' => $validated['job_title'] ? strip_tags($validated['job_title']) : null,
            'company_name' => $validated['company_name'] ? strip_tags($validated['company_name']) : null,
            'phone_no' => strip_tags($validated['phone_no']),
        ];

        // Send to site owner with all sanitized data
        Mail::to('nurudeen@p23africa.com')->send(new ContactFormMail($sanitizedData));

        // Send thank-you to user
        Mail::to($sanitizedData['email'])->send(new ThankYouMail($sanitizedData));

        return redirect()->back()->with('success', 'Your message has been sent!');
    }
}
