<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SalesBookDownloadMail extends Mailable
{
    use Queueable, SerializesModels;

    public $download;

    /**
     * Create a new message instance.
     */
    public function __construct($download)
    {
        $this->download = $download;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Funding Readiness Guidebook is Ready!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sales-book-download',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        // Add the funding guide PDF if it exists
        $guidePath = public_path('playbook/Funding-Readiness-Guidebook.pdf');
        if (file_exists($guidePath)) {
            $attachments[] = Attachment::fromPath($guidePath)
                ->as('Funding-Readiness-Guidebook.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
