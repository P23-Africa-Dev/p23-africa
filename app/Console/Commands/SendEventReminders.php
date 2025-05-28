<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Seat;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendEventReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send email reminders for upcoming events';

    public function handle()
    {
        $now = Carbon::now();

        // 1 Day Before Reminder
        $dayBefore = $now->copy()->addDay()->toDateString();

        $seatsDayBefore = Seat::whereHas('event', function ($query) use ($dayBefore) {
            $query->whereDate('event_date', $dayBefore);
        })->get();

        foreach ($seatsDayBefore as $seat) {
            $this->sendReminder($seat, 'day-before');
        }

        // 15 Minutes Before Reminder
        $fifteenMinutesLater = $now->copy()->addMinutes(15);

        $seats15MinBefore = Seat::whereHas('event', function ($query) use ($fifteenMinutesLater) {
            $query->whereDate('event_date', $fifteenMinutesLater->toDateString())
                ->whereTime('event_time', $fifteenMinutesLater->format('H:i:00'));
        })->get();

        foreach ($seats15MinBefore as $seat) {
            $this->sendReminder($seat, 'fifteen-minutes');
        }

        $this->info('Reminders sent.');
    }

    protected function sendReminder($seat, $type)
    {
        $event = $seat->event;
        $firstName = explode(' ', $seat->name)[0];

        $link = $event->visibility === 'public'
            ? $event->link
            : route('private.access', ['slug' => $event->slug, 'code' => $seat->seat_code]);

        $data = [
            'firstName' => $firstName,
            'eventName' => $event->title,
            'eventDate' => Carbon::parse($event->event_date)->format('jS F, Y'),
            'eventTime' => Carbon::parse($event->event_time)->format('g:i A'),
            'link' => $link,
        ];

        $to = $seat->email;

        if ($type === 'day-before') {
            Mail::send('emails.reminders.day-before', $data, function ($message) use ($to, $event) {
                $message->to($to)
                    ->subject('🚨 1 Day to Go! ' . $event->title . ' is Tomorrow');
            });
        }

        if ($type === 'fifteen-minutes') {
            Mail::send('emails.reminders.fifteen-minutes', $data, function ($message) use ($to, $event) {
                $message->to($to)
                    ->subject('🔔 Starting Soon: ' . $event->title . ' in 15 Minutes!');
            });
        }
    }
}
