<?php

namespace App\Events;

use App\Models\Click;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminClicksEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public Click $clicks;

    public function __construct(Click $clicks)
    {
        $this->clicks = $clicks;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('clicks-track'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->clicks->id,
            'route_name' => $this->clicks->route_name,
            'device_type' => $this->clicks->device_type,
            'click_count' => $this->clicks->click_count,
            'created_at' => $this->clicks->created_at,
        ];
    }
}
