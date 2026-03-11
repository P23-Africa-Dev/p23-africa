<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestSenderResponse extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveRequest;
    public $organization;
    public $orgName;
    public $isOkay;

    public function __construct(LeaveRequest $leaveRequest, bool $isOkay, ?Organization $organization = null)
    {
        $this->leaveRequest = $leaveRequest->load(['user', 'supervisor']);
        $this->isOkay = $isOkay;
        $this->organization = $organization;
        $this->orgName = $organization ? $organization->name : config('app.name', 'P23 Africa');
    }

    public function build()
    {
        $response = $this->isOkay ? 'Agreed' : 'Disagreed';
        return $this->subject($this->leaveRequest->user->name . ' ' . $response . ' with Leave Modifications: ' . $this->orgName)
            ->view('emails.leave-request-sender-response');
    }
}
