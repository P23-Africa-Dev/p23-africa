<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Get the booking fields configuration for an event
     */
    public function getBookingFields($id)
    {
        $event = Event::findOrFail($id);

        $bookingFields = $event->getBookingFields();

        return response()->json([
            'fields' => $bookingFields
        ]);
    }
}
