<?php

namespace App\Console\Commands;

use App\Mail\EventReminderMail;
use Illuminate\Console\Command;
use App\Models\Seat;
use App\Models\Event;
use App\Services\WhatsApp\EventWhatsAppAutomationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SendEventReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send event reminders 24 hours and 15 minutes before event';

    protected const REMINDER_WINDOW_MINUTES = 5;

    public function handle()
    {
        $now = now()->timezone(config('app.timezone'));
        $this->info('Starting event reminder check at ' . $now->format('Y-m-d H:i:s'));
        Log::info('Event reminder scheduler started', [
            'timezone' => config('app.timezone'),
            'now' => $now->toDateTimeString(),
        ]);

        // Send 24-hour reminders
        $this->send24HourReminders();

        // Send 15-minute reminders
        $this->send15MinuteReminders();

        $this->info('Event reminder check completed.');
        Log::info('Event reminder scheduler completed');

        return self::SUCCESS;
    }

    protected function send24HourReminders()
    {
        $now = Carbon::now(config('app.timezone'));
        $targetStart = $now->copy()->addHours(24)->subMinutes(self::REMINDER_WINDOW_MINUTES);
        $targetEnd = $now->copy()->addHours(24)->addMinutes(self::REMINDER_WINDOW_MINUTES);

        // Find events happening in ~24 hours that haven't had 24hr reminder sent
        $events = Event::whereNull('reminder_24hr_sent_at')
            ->whereBetween(DB::raw('TIMESTAMP(event_date, event_time)'), [
                $targetStart->toDateTimeString(),
                $targetEnd->toDateTimeString(),
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
        $now = Carbon::now(config('app.timezone'));
        $targetStart = $now->copy()->addMinutes(15)->subMinutes(self::REMINDER_WINDOW_MINUTES);
        $targetEnd = $now->copy()->addMinutes(15)->addMinutes(self::REMINDER_WINDOW_MINUTES);

        // Find events happening in ~15 minutes that haven't had 15min reminder sent
        $events = Event::whereNull('reminder_15min_sent_at')
            ->whereBetween(DB::raw('TIMESTAMP(event_date, event_time)'), [
                $targetStart->toDateTimeString(),
                $targetEnd->toDateTimeString(),
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
        try {
            app(EventWhatsAppAutomationService::class)->beforeEvent($event);
        } catch (\Throwable) {
            // Hook execution is best-effort and should not block reminder emails.
        }

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
                    : route('private.access', ['slug' => $event->slug, 'code' => $seat->seat_code]);

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
