<?php

namespace App\Events;

use App\Models\User;
use App\Models\WhatsAppConversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhatsAppConversationAssigned implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public WhatsAppConversation $conversation;
    public ?User $assignee;

    public function __construct(WhatsAppConversation $conversation, ?User $assignee)
    {
        $this->conversation = $conversation;
        $this->assignee = $assignee;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('whatsapp.dashboard'),
            new PrivateChannel('whatsapp.conversation.' . $this->conversation->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'whatsapp.conversation.assigned';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversation->id,
            'assigned_to' => $this->conversation->assigned_to,
            'assignee_name' => $this->assignee?->name,
            'status' => $this->conversation->status,
            'updated_at' => optional($this->conversation->updated_at)->toIso8601String(),
        ];
    }
}
