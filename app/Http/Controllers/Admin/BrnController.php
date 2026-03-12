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
        $submissions = BrnSubmission::latest()->paginate(20);
        return view('admin.brn.index', compact('submissions'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'tier' => 'required|in:silver,gold,platinum',
        ]);

        $submission = BrnSubmission::findOrFail($id);
        $submission->update([
            'status' => 'approved',
            'tier' => $request->tier,
        ]);

        Mail::to($submission->email)->send(new BrnApproved($submission));

        // return back()->with('success', 'Approved and email sent.');
        return orgRedirect('admin.brn.index')->with('success', 'Approved as ' . ucfirst($request->tier) . ' member and email sent.');
    }

    public function decline($id)
    {
        $submission = BrnSubmission::findOrFail($id);
        $submission->update(['status' => 'declined']);

        Mail::to($submission->email)->send(new BrnDeclined($submission));

        // return back()->with('error', 'Declined and email sent.');
        return orgRedirect('admin.brn.index')->with('error', 'Declined and email sent.');
    }

    public function show($id)
    {
        $submission = BrnSubmission::findOrFail($id);
        return view('admin.brn.show', compact('submission'));
    }

    public function destroy($id)
    {
        $submission = BrnSubmission::findOrFail($id);
        $submission->delete();

        // return back()->with('success', 'BRN application deleted successfully.');
        return orgRedirect('admin.brn.index')->with('success', 'BRN application deleted successfully.');
    }
}
