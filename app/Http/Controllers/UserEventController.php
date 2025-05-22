<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserEventController extends Controller
{
    public function eventPage(){
        $events = Event::whereDate('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->take(5) // or ->limit(5)
            ->get();

        $past_events = Event::whereDate('event_date', '<', now())
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->take(5) // or ->limit(5)
            ->get();

        return view('event',  compact('events', 'past_events'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();


        return view('show-event', compact('event'));
    }

    public function allEvents(){
        $events = Event::whereDate('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->paginate(6); // You can change 6 to however many events you want per page

        return view('all-events', compact('events'));
    }


    public function search(Request $request)
    {
        $query = Event::query();

        // Keyword search
        if ($request->has('term')) {
            $query->where('title', 'LIKE', '%' . $request->term . '%');
        }

        // Filter by date
        if ($request->has('filter')) {
            $now = Carbon::now();
            if ($request->filter == 'this_month') {
                $query->whereMonth('event_date', $now->month)->whereYear('event_date', $now->year);
            } elseif ($request->filter == 'next_month') {
                $nextMonth = $now->copy()->addMonth();
                $query->whereMonth('event_date', $nextMonth->month)->whereYear('event_date', $nextMonth->year);
            } elseif ($request->filter == 'two_months') {
                $start = $now->copy()->addMonth();
                $end = $now->copy()->addMonths(2);
                $query->whereBetween('event_date', [$start->startOfMonth(), $end->endOfMonth()]);
            }
        }

        $events = $query->orderBy('event_date')->get();

        return response()->json($events);
    }



    public function join(Request $request)
    {
        $request->validate([
            'seat_code' => 'required|string',
            'event_id' => 'required|integer',
        ]);

        $seat = Seat::where('seat_code', $request->seat_code)
            ->where('event_id', $request->event_id)
            ->first();

        if (!$seat) {
            return redirect()->back()->with('invalid_seat', 'Invalid seat code');
        }

        return redirect()->route('event.live', $seat->seat_code);
    }

    public function liveEvent($slug, $code)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $seat = Seat::where('event_id', $event->id)->where('seat_code', $code)->firstOrFail();

        return view('events.live', compact('event', 'seat'));
    }


    // public function live($seatCode)
    // {
    //     $seat = Seat::where('seat_code', $seatCode)->firstOrFail();
    //     $event = Event::findOrFail($seat->event_id);

    //     return view('events.live', [
    //         'seat' => $seat,
    //         'event' => $event,
    //     ]);
    // }
}
