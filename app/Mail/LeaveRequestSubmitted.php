<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveRequest;
    public $organization;
    public $orgName;

    public function __construct(LeaveRequest $leaveRequest, ?Organization $organization = null)
    {
        $this->leaveRequest = $leaveRequest->load(['user', 'supervisor']);
        $this->organization = $organization;
        $this->orgName = $organization ? $organization->name : config('app.name', 'P23 Africa');
    }

    public function build()
    {
        return $this->subject('New Leave Request from ' . $this->leaveRequest->user->name . ': ' . $this->orgName)
            ->view('emails.leave-request-submitted');
    }
}
