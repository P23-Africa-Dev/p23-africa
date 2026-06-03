<?php

namespace App\Jobs;

use App\Mail\EventUpdatedMail;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEventUpdateNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $eventId,
        public string $recipientEmail,
        public ?string $recipientName,
        public array $changes,
        public ?int $updatedById = null
    ) {}

    public function handle(): void
    {
        $event = Event::find($this->eventId);

        if (!$event) {
            Log::warning('Event update notification job skipped: event not found', [
                'event_id' => $this->eventId,
                'recipient_email' => $this->recipientEmail,
            ]);

            return;
        }

        Mail::to($this->recipientEmail)->send(new EventUpdatedMail(
            $event,
            $this->recipientName,
            $this->changes
        ));

        Log::info('Event update notification sent', [
            'event_id' => $this->eventId,
            'recipient_email' => $this->recipientEmail,
            'updated_by' => $this->updatedById,
            'changed_fields' => $this->changes['changed_fields'] ?? [],
        ]);
    }
}
