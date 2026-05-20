<?php

namespace App\Services\WhatsApp\Contracts;

use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;

interface CrmIntegrationHookInterface
{
    public function onIncomingMessage(WhatsAppConversation $conversation, WhatsAppMessage $message): void;

    public function onOutgoingMessage(WhatsAppConversation $conversation, WhatsAppMessage $message): void;

    public function onConversationStatusChanged(WhatsAppConversation $conversation, string $previousStatus, string $newStatus): void;
}
