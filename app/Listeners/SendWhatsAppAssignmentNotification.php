<?php

namespace App\Listeners;

use App\Events\WhatsAppConversationAssigned;
use App\Notifications\WhatsAppConversationAssignedNotification;

class SendWhatsAppAssignmentNotification
{
    public function handle(WhatsAppConversationAssigned $event): void
    {
        if (!$event->assignee) {
            return;
        }

        $conversation = $event->conversation->loadMissing('contact');
        $event->assignee->notify(new WhatsAppConversationAssignedNotification($conversation));
    }
}
