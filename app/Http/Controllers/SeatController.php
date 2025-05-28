<?php

namespace App\Http\Controllers;

use App\Mail\SeatBookedMail;
use App\Models\Event;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SeatController extends Controller
{
    public function store(Request $request, $event_id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $seatCode = 'P23-' . strtoupper(Str::random(9));

        $seat  = Seat::create([
            'event_id' => $event_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'seat_code' => $seatCode,
        ]);

        $event = Event::findOrFail($event_id);

        // Determine which link to send
        $link = $event->visibility === 'public'
            ? $event->link
            : route('private.access', ['slug' => $event->slug, 'code' => $seatCode]);

        Mail::to($validated['email'])->send(new SeatBookedMail($seat, $event, $link));

        return response()->json(['message' => 'Seat booked successfully']);
    }
}
