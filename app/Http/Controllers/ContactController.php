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
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
            'job_title' => 'nullable|string',
            'company_name' => 'nullable|string',
            'phone_no' => 'required|string'
        ]);

        $data = $request->only('name', 'email', 'message', 'job_title', 'company_name', 'phone_no');

        // Send to site owner
        Mail::to('contact@p23africa.com')->send(new ContactFormMail($data));

        // Send thank-you to user
        Mail::to($data['email'])->send(new ThankYouMail($data));

        return redirect()->back()->with('success', 'Your message has been sent!');
    }
}
