<?php

namespace App\Services\WhatsApp\Contracts;

use App\Models\WhatsAppConversation;

interface AiAssistantHookInterface
{
    public function suggestReply(WhatsAppConversation $conversation, array $thread): ?string;
}
