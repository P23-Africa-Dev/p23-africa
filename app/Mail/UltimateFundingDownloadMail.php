<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UltimateFundingDownloadMail extends Mailable
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
            subject: 'Your Ultimate Funding & Investor Directory is Ready!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ultimate-funding-download',
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

        // Add the Ultimate Funding Directory PDF if it exists
        $directoryPath = public_path('playbook/P23-Africa-Ultimate-Funding-Directory.pdf');
        if (file_exists($directoryPath)) {
            $attachments[] = Attachment::fromPath($directoryPath)
                ->as('P23-Africa-Ultimate-Funding-Directory.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
