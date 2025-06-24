<?php

namespace App\Console\Commands;

use App\Mail\EventReminderMails;
use Illuminate\Console\Command;
use App\Models\Seat;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendEventReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send event reminders 24 hours and 15 minutes before event';

    public function handle()
    {
        // Send 24-hour reminders
        $this->sendRemindersForTimeFrame(now()->addDay(), '24hr');

        // Send 15-minute reminders
        $this->sendRemindersForTimeFrame(now()->addMinutes(15), '15min');
    }

    protected function sendRemindersForTimeFrame($targetTime, $type)
    {
        $events = Event::whereBetween('date_time', [
            $targetTime->copy()->startOfMinute(),
            $targetTime->copy()->endOfMinute(),
        ])->get();

        foreach ($events as $event) {
            $bookings = $event->bookings; // assumes Event has bookings() relationship

            foreach ($bookings as $booking) {
                // Avoid duplicate sends by checking a flag or timestamp if needed (advanced)

                Mail::to($booking->email)->send(new EventReminderMails($event, $booking, $type));
            }
        }

        $this->info("{$type} reminders sent.");
    }
}
