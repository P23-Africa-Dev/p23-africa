<?php

namespace App\Mail;

use App\Models\BrnRegistrationToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class BrnApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $submission;
    public $registrationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($submission)
    {
        $this->submission = $submission;

        // Data to be encrypted and stored
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
            'reg_number' => $submission->reg_number,
            'website' => $submission->website,
            'year_established' => $submission->year_established,
            'industry' => $submission->industry,
            'categories' => $submission->categories,
            'offer_value' => $submission->offer_value,
            'b2b_interest' => $submission->b2b_interest,
        ];

        // Encrypt the data
        $encrypted = Crypt::encryptString(json_encode($data));

        // Generate a unique short token
        $token = Str::random(32);

        // Store in database with expiration (7 days)
        BrnRegistrationToken::create([
            'token' => $token,
            'encrypted_data' => $encrypted,
            'expires_at' => now()->addDays(7),
        ]);

        // Build short URL with just the token
        $this->registrationUrl = "http://104.131.166.92/registration/continue?token={$token}";
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
