<?php

namespace App\Services\WhatsApp;

use App\Models\Event;
use App\Models\Seat;
use Illuminate\Support\Facades\Log;

class EventWhatsAppAutomationService
{
    public function afterBooking(Seat $seat): void
    {
        Log::info('Event WhatsApp automation hook captured: after booking', [
            'event_id' => $seat->event_id,
            'seat_id' => $seat->id,
            'phone' => $seat->phone,
        ]);
    }

    public function beforeEvent(Event $event): void
    {
        Log::info('Event WhatsApp automation hook captured: before event', [
            'event_id' => $event->id,
            'event_date' => $event->event_date,
            'event_time' => $event->event_time,
        ]);
    }

    public function afterEvent(Event $event): void
    {
        Log::info('Event WhatsApp automation hook captured: after event', [
            'event_id' => $event->id,
        ]);
    }
}
