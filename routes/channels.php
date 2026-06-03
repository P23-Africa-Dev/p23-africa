<?php

use App\Services\LegacyContextService;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('whatsapp.dashboard', function ($user) {
    return app(LegacyContextService::class)->hasLegacyWhatsAppAccess($user);
});

Broadcast::channel('whatsapp.conversation.{conversationId}', function ($user, $conversationId) {
    return app(LegacyContextService::class)->hasLegacyWhatsAppAccess($user);
});
