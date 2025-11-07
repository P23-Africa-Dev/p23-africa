<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlaybookDownloadMail extends Mailable
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
            subject: 'Your P23 Africa Founders Playbook is Ready!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.playbook-download',
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

        // Add the playbook PDF if it exists
        $playbookPath = public_path('playbook/P23 Africa\'s Founders Playbook.pdf');
        if (file_exists($playbookPath)) {
            $attachments[] = Attachment::fromPath($playbookPath)
                ->as('P23 Africa\'s Founders Playbook.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
