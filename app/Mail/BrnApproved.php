<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class BrnApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $submission;
    public $registrationUrl;

    public function __construct($submission)
    {
        $this->submission = $submission;

        // Data to be encrypted and sent to the other Laravel app
        $data = [
            'name' => $submission->name,
            'email' => $submission->email,
            'company_name' => $submission->company_name,
            'phone' => $submission->phone,
            'linkedin' => $submission->linkedin,
            'country' => $submission->country,
            'position' => $submission->position,
            'years_of_operation' => $submission->years_of_operation,
            'number_of_employees' => $submission->number_of_employees,
            'selected_outcome' => $submission->selected_outcome,
            'goals' => $submission->goals,
        ];

        // Encrypt the data and build the secure URL to the second app
        $encrypted = Crypt::encryptString(json_encode($data));

        // Replace this with the real URL of your second Laravel app
        $this->registrationUrl = "http://127.0.0.1:8000/registration/continue?data={$encrypted}";
    }

    public function build()
    {
        return $this->subject('BRN Membership Approved')
            ->markdown('emails.brn.approved')
            ->with([
                'submission' => $this->submission,
                'registrationUrl' => $this->registrationUrl,
            ]);
    }



    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
