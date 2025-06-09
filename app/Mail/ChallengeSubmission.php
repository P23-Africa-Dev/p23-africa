<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChallengeSubmission extends Mailable
{
    use Queueable, SerializesModels;

    public $challenge1;
    public $challenge2;
    public $challenge3;
    public $label;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->challenge1 = $data['challenge1'] ?? '';
        $this->challenge2 = $data['challenge2'] ?? '';
        $this->challenge3 = $data['challenge3'] ?? '';
        $this->label      = $data['label'] ?? '';
    }


    public function build()
    {
        return $this->subject('New Challenge Submission from Quiz')
            ->view('emails.challenge-submission');
    }
    

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Challenge Submission',
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

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
