<?php

namespace App\Mail;

use App\Models\LeadEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class LeadMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public LeadEmail $leadEmail;
    public User $sender;

    /**
     * Create a new message instance.
     */
    public function __construct(LeadEmail $leadEmail, User $sender)
    {
        $this->leadEmail = $leadEmail;
        $this->sender = $sender;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->sender->email, $this->sender->name),
            replyTo: [new Address($this->sender->email, $this->sender->name)],
            subject: $this->leadEmail->subject,
        );
    }

    /**
     * Get the message headers.
     */
    public function headers(): Headers
    {
        $headers = new Headers(
            messageId: $this->leadEmail->message_id,
        );

        // If this is a reply, add In-Reply-To and References headers
        if ($this->leadEmail->in_reply_to) {
            return new Headers(
                messageId: $this->leadEmail->message_id,
                references: [$this->leadEmail->in_reply_to],
                text: [
                    'In-Reply-To' => '<' . $this->leadEmail->in_reply_to . '>',
                ],
            );
        }

        return $headers;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.lead-email',
            with: [
                'leadEmail' => $this->leadEmail,
                'sender' => $this->sender,
                'lead' => $this->leadEmail->lead,
            ],
        );
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
