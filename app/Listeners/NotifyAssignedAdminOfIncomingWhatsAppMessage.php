<?php

namespace App\Listeners;

use App\Events\WhatsAppMessageReceived;
use App\Notifications\WhatsAppIncomingMessageNotification;

class NotifyAssignedAdminOfIncomingWhatsAppMessage
{
    public function handle(WhatsAppMessageReceived $event): void
    {
        $message = $event->message->loadMissing('conversation.contact', 'conversation.assignedUser');
        $assignedUser = $message->conversation->assignedUser;

        if (!$assignedUser) {
            return;
        }

        $assignedUser->notify(new WhatsAppIncomingMessageNotification($message->conversation, $message));
    }
}
