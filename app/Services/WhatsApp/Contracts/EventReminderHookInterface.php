<?php

namespace App\Services\WhatsApp\Contracts;

use App\Models\WhatsAppConversation;

interface EventReminderHookInterface
{
    public function onConversationReadyForReminder(WhatsAppConversation $conversation, array $context = []): void;
}
