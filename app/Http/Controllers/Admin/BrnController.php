<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BrnApproved;
use App\Mail\BrnDeclined;
use App\Models\BrnSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BrnController extends Controller
{
    public function adminView()
    {
        $submissions = BrnSubmission::latest()->get();
        return view('admin.brn.index', compact('submissions'));
    }

    public function approve($id)
    {
        $submission = BrnSubmission::findOrFail($id);
        $submission->update(['status' => 'approved']);

        Mail::to($submission->email)->send(new BrnApproved($submission));

        return back()->with('success', 'Approved and email sent.');
    }

    public function decline($id)
    {
        $submission = BrnSubmission::findOrFail($id);
        $submission->update(['status' => 'declined']);

        Mail::to($submission->email)->send(new BrnDeclined($submission));

        return back()->with('error', 'Declined and email sent.');
    }
}
