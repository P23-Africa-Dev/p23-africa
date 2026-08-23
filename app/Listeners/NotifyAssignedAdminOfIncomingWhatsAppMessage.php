<?php

namespace App\Listeners;

use App\Events\WhatsAppMessageReceived;
use App\Models\User;
use App\Notifications\WhatsAppIncomingMessageNotification;
use App\Services\LegacyContextService;
use Illuminate\Support\Facades\Notification;

class NotifyAssignedAdminOfIncomingWhatsAppMessage
{
    public function __construct(
        protected LegacyContextService $legacyContextService,
    ) {}

    public function handle(WhatsAppMessageReceived $event): void
    {
        $message = $event->message->loadMissing('conversation.contact', 'conversation.account');
        $conversation = $message->conversation;

        if (!$conversation) {
            return;
        }

        $recipients = User::role(['admin', 'supervisor'])
            ->whereNull('organization_id')
            ->get()
            ->filter(fn (User $user) => $this->legacyContextService->hasLegacyWhatsAppAccess($user))
            ->values();

        if ($recipients->isEmpty()) {
            return;
        }

        Notification::send(
            $recipients,
            new WhatsAppIncomingMessageNotification($conversation, $message)
        );
    }
}
