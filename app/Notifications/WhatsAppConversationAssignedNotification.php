<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\WhatsAppConversation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WhatsAppConversationAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected WhatsAppConversation $conversation,
        protected ?User $assignedBy = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $contactName = $this->conversation->contact?->name ?: $this->conversation->contact?->phone;

        return [
            'notification_type' => 'whatsapp_assigned',
            'title' => 'WhatsApp Conversation Assigned',
            'message' => 'You were assigned conversation with ' . ($contactName ?: 'Contact') . '.',
            'icon' => 'bi bi-person-check',
            'url' => '/admin/whatsapp/conversations?conversation=' . $this->conversation->id,
            'conversation_id' => $this->conversation->id,
            'assigned_by_id' => $this->assignedBy?->id,
            'assigned_by_name' => $this->assignedBy?->name,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
