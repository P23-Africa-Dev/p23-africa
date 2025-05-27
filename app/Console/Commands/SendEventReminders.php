<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Seat;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\EventReminder;

class SendEventReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send event reminder emails 1 hour and 1 day before event time';

    public function handle()
    {
        $now = Carbon::now();
        $oneHourFromNow = $now->copy()->addHour()->format('Y-m-d H:i:00');
        $oneDayFromNow = $now->copy()->addDay()->format('Y-m-d H:i:00');

        $events = Event::whereRaw("CONCAT(event_date, ' ', event_time) = ? OR CONCAT(event_date, ' ', event_time) = ?", [
            $oneHourFromNow,
            $oneDayFromNow
        ])->get();

        foreach ($events as $event) {
            $eventDateTime = Carbon::parse($event->event_date . ' ' . $event->event_time);
            $timeToEvent = $eventDateTime->diffInHours($now, false) === -1 ? 'hour' : 'day';

            foreach ($event->seats as $seat) {
                Mail::to($seat->email)->send(new EventReminder($event, $seat, $timeToEvent));
            }
        }

        $this->info('Reminder emails sent successfully.');
    }
}
