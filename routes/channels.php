<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('whatsapp.dashboard', function ($user) {
    return $user->hasRole(['admin', 'supervisor']);
});

Broadcast::channel('whatsapp.conversation.{conversationId}', function ($user, $conversationId) {
    return $user->hasRole(['admin', 'supervisor']);
});
