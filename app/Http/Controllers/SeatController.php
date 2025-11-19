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
        $event = Event::findOrFail($event_id);
        $bookingFields = $event->getBookingFields();

        // Build validation rules based on event's booking fields
        $validationRules = [];
        $fillableData = ['event_id' => $event->id];

        foreach ($bookingFields as $field) {
            $validationRules[$field] = 'required|string|max:255';

            // Handle special validation for email
            if ($field === 'email') {
                $validationRules[$field] = 'required|email|max:255';
            }

            // Handle special validation for phone
            if ($field === 'phone') {
                $validationRules[$field] = 'required|string|max:25'; // Increased to accommodate country codes
            }
        }

        $validated = $request->validate($validationRules);

        $seatCode = 'P23-' . strtoupper(Str::random(9));

        // Prepare data for seat creation
        foreach ($bookingFields as $field) {
            $fillableData[$field] = $validated[$field];
        }
        $fillableData['seat_code'] = $seatCode;

        $seat = Seat::create($fillableData);

        // $event = Event::findOrFail($event_id);

        // Determine which link to send
        $link = $event->visibility === 'public'
            ? $event->link
            : route('private.access', ['slug' => $event->slug, 'code' => $seatCode]);

        Mail::to($validated['email'])->send(new SeatBookedMail($seat, $event, $link));

        return response()->json(['message' => 'Seat booked successfully']);
    }
}
