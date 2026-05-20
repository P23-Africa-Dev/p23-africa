<?php

namespace App\Services\WhatsApp\Contracts;

use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;

interface AutomationHookInterface
{
    public function onMessageLifecycleEvent(string $event, WhatsAppConversation $conversation, ?WhatsAppMessage $message = null, array $context = []): void;
}
