<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CalendarController extends Controller
{
    public function downloadIcs(Seat $booking)
    {
        $event = $booking->event;

        $start = Carbon::parse($event->date_time);
        $end = $start->copy()->addHour();

        $ics = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//YourApp//Event Reminder//EN
BEGIN:VEVENT
UID:" . uniqid() . "
DTSTAMP:" . now()->format('Ymd\THis\Z') . "
DTSTART:" . $start->format('Ymd\THis') . "
DTEND:" . $end->format('Ymd\THis') . "
SUMMARY:" . addslashes($event->title) . "
DESCRIPTION:" . addslashes($event->description ?? 'Event Reminder') . "
LOCATION:" . addslashes($event->location ?? '') . "
URL:" . ($event->is_public ? $event->event_link : url('/private-event/' . $booking->unique_code)) . "
END:VEVENT
END:VCALENDAR";

        return new StreamedResponse(function () use ($ics) {
            echo $ics;
        }, 200, [
            'Content-Type' => 'text/calendar',
            'Content-Disposition' => 'attachment; filename="event.ics"',
        ]);
    }
}
