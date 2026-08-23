<?php

namespace App\Notifications;

use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WhatsAppIncomingMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected WhatsAppConversation $conversation,
        protected WhatsAppMessage $message,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $contactName = $this->conversation->contact?->name ?: $this->conversation->contact?->phone;
        $accountId = $this->conversation->account_id;

        $query = http_build_query(array_filter([
            'conversation' => $this->conversation->id,
            'message' => $this->message->id,
            'account_id' => $accountId,
        ]));

        return [
            'notification_type' => 'whatsapp_incoming',
            'title' => 'New WhatsApp Message',
            'message' => ($contactName ?: 'Contact') . ': ' . ($this->message->body ?: '[Attachment]'),
            'icon' => 'bi bi-whatsapp',
            'url' => '/admin/whatsapp/conversations?' . $query,
            'conversation_id' => $this->conversation->id,
            'account_id' => $accountId,
            'whatsapp_message_id' => $this->message->whatsapp_message_id,
            'message_id' => $this->message->id,
            'contact_name' => $contactName,
            'contact_phone' => $this->conversation->contact?->phone,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
