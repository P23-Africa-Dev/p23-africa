<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Seat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $seat;
    public $timeToEvent;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, Seat $seat, $timeToEvent)
    {
        $this->event = $event;
        $this->seat = $seat;
        $this->timeToEvent = $timeToEvent;
    }


    public function build()
    {
        return $this->subject("Reminder: {$this->event->title} is in 1 {$this->timeToEvent}")
            ->view('emails.reminder');
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Event Reminder',
    //     );
    // }

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
