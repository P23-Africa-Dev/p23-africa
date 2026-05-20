<?php

namespace App\Events;

use App\Models\WhatsAppMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhatsAppMessageStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public WhatsAppMessage $message;

    public function __construct(WhatsAppMessage $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('whatsapp.dashboard'),
            new PrivateChannel('whatsapp.conversation.' . $this->message->conversation_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'whatsapp.message.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->message->conversation_id,
            'message_id' => $this->message->id,
            'whatsapp_message_id' => $this->message->whatsapp_message_id,
            'status' => $this->message->status,
            'delivered_at' => optional($this->message->delivered_at)->toIso8601String(),
            'read_at' => optional($this->message->read_at)->toIso8601String(),
        ];
    }
}
