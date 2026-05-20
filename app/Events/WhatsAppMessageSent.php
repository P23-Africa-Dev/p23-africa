<?php

namespace App\Events;

use App\Models\WhatsAppMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhatsAppMessageSent implements ShouldBroadcastNow
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
        return 'whatsapp.message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->message->conversation_id,
            'message_id' => $this->message->id,
            'whatsapp_message_id' => $this->message->whatsapp_message_id,
            'type' => $this->message->type,
            'body' => $this->message->body,
            'status' => $this->message->status,
            'sent_at' => optional($this->message->sent_at)->toIso8601String(),
        ];
    }
}
