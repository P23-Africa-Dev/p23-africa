<?php

namespace App\Console\Commands;

use App\Mail\EventReminderMail;
use Illuminate\Console\Command;
use App\Models\Seat;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEventReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send event reminders 24 hours and 15 minutes before event';

    public function handle()
    {
        $this->info('Starting event reminder check at ' . now()->format('Y-m-d H:i:s'));

        // Send 24-hour reminders
        $this->send24HourReminders();

        // Send 15-minute reminders
        $this->send15MinuteReminders();

        $this->info('Event reminder check completed.');
    }

    protected function send24HourReminders()
    {
        $now = Carbon::now();
        $targetStart = $now->copy()->addHours(24)->subMinutes(1);
        $targetEnd = $now->copy()->addHours(24)->addMinutes(1);

        // Find events happening in ~24 hours that haven't had 24hr reminder sent
        $events = Event::whereNull('reminder_24hr_sent_at')
            ->whereRaw("CONCAT(event_date, ' ', event_time) BETWEEN ? AND ?", [
                $targetStart->format('Y-m-d H:i:s'),
                $targetEnd->format('Y-m-d H:i:s')
            ])
            ->get();

        foreach ($events as $event) {
            $this->sendRemindersForEvent($event, '24hr');
        }

        if ($events->count() > 0) {
            $this->info("Sent 24-hour reminders for {$events->count()} event(s).");
        }
    }

    protected function send15MinuteReminders()
    {
        $now = Carbon::now();
        $targetStart = $now->copy()->addMinutes(14);
        $targetEnd = $now->copy()->addMinutes(16);

        // Find events happening in ~15 minutes that haven't had 15min reminder sent
        $events = Event::whereNull('reminder_15min_sent_at')
            ->whereRaw("CONCAT(event_date, ' ', event_time) BETWEEN ? AND ?", [
                $targetStart->format('Y-m-d H:i:s'),
                $targetEnd->format('Y-m-d H:i:s')
            ])
            ->get();

        foreach ($events as $event) {
            $this->sendRemindersForEvent($event, '15min');
        }

        if ($events->count() > 0) {
            $this->info("Sent 15-minute reminders for {$events->count()} event(s).");
        }
    }

    protected function sendRemindersForEvent(Event $event, string $type)
    {
        $seats = $event->seats; // Get all registered attendees

        if ($seats->isEmpty()) {
            $this->warn("Event '{$event->title}' has no registered attendees. Skipping reminders.");
            // Still mark as sent so we don't keep checking
            $this->markReminderSent($event, $type);
            return;
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($seats as $seat) {
            if (empty($seat->email)) {
                continue;
            }

            try {
                $firstName = explode(' ', $seat->name)[0] ?? $seat->name;

                // Determine the event link based on visibility
                $link = $event->visibility === 'public'
                    ? $event->link
                    : url('/events/' . $event->slug);

                Mail::to($seat->email)->send(new EventReminderMail(
                    $seat,
                    $event,
                    $link,
                    $firstName
                ));

                $sentCount++;

                Log::info("Sent {$type} reminder to {$seat->email} for event '{$event->title}'");
            } catch (\Exception $e) {
                $failedCount++;
                Log::error("Failed to send {$type} reminder to {$seat->email} for event '{$event->title}': " . $e->getMessage());
            }
        }

        // Mark reminder as sent for this event
        $this->markReminderSent($event, $type);

        $this->info("Event '{$event->title}': Sent {$sentCount} {$type} reminder(s), {$failedCount} failed.");
    }

    protected function markReminderSent(Event $event, string $type)
    {
        if ($type === '24hr') {
            $event->reminder_24hr_sent_at = now();
        } else {
            $event->reminder_15min_sent_at = now();
        }
        $event->save();
    }
}
