<?php

namespace App\Mail;

use App\Models\Event;
use App\Services\EventUpdateNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array{changed_fields?: array<int, string>, old_values?: array<string, string>, new_values?: array<string, string>} $changes
     */
    public function __construct(
        public Event $event,
        public ?string $recipientName,
        public array $changes
    ) {}

    public function build()
    {
        return $this
            ->subject('Important Update: ' . $this->event->title)
            ->view('emails.event-updated')
            ->with([
                'event' => $this->event,
                'recipientName' => $this->recipientName,
                'changes' => $this->changes,
                'fieldLabels' => EventUpdateNotificationService::fieldLabels(),
            ]);
    }
}
