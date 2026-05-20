<?php

namespace App\Services\WhatsApp\Hooks;

use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\Contracts\AiAssistantHookInterface;
use App\Services\WhatsApp\Contracts\AutomationHookInterface;
use App\Services\WhatsApp\Contracts\CrmIntegrationHookInterface;
use App\Services\WhatsApp\Contracts\EventReminderHookInterface;
use Illuminate\Support\Facades\Log;

class FutureIntegrationBridge
{
    public function onIncomingMessage(WhatsAppConversation $conversation, WhatsAppMessage $message): void
    {
        $this->safeCall(function () use ($conversation, $message) {
            if (app()->bound(CrmIntegrationHookInterface::class)) {
                app(CrmIntegrationHookInterface::class)->onIncomingMessage($conversation, $message);
            }

            if (app()->bound(AutomationHookInterface::class)) {
                app(AutomationHookInterface::class)->onMessageLifecycleEvent('incoming', $conversation, $message);
            }
        }, 'incoming_message');
    }

    public function onOutgoingMessage(WhatsAppConversation $conversation, WhatsAppMessage $message): void
    {
        $this->safeCall(function () use ($conversation, $message) {
            if (app()->bound(CrmIntegrationHookInterface::class)) {
                app(CrmIntegrationHookInterface::class)->onOutgoingMessage($conversation, $message);
            }

            if (app()->bound(AutomationHookInterface::class)) {
                app(AutomationHookInterface::class)->onMessageLifecycleEvent('outgoing', $conversation, $message);
            }
        }, 'outgoing_message');
    }

    public function onConversationStatusChanged(WhatsAppConversation $conversation, string $from, string $to): void
    {
        $this->safeCall(function () use ($conversation, $from, $to) {
            if (app()->bound(CrmIntegrationHookInterface::class)) {
                app(CrmIntegrationHookInterface::class)->onConversationStatusChanged($conversation, $from, $to);
            }

            if (app()->bound(AutomationHookInterface::class)) {
                app(AutomationHookInterface::class)->onMessageLifecycleEvent('conversation_status_changed', $conversation, null, [
                    'from' => $from,
                    'to' => $to,
                ]);
            }

            if (app()->bound(EventReminderHookInterface::class) && $to === 'pending') {
                app(EventReminderHookInterface::class)->onConversationReadyForReminder($conversation, [
                    'trigger' => 'status_changed',
                    'from' => $from,
                    'to' => $to,
                ]);
            }
        }, 'status_change');
    }

    public function onMessageLifecycleEvent(string $event, WhatsAppConversation $conversation, ?WhatsAppMessage $message = null, array $context = []): void
    {
        $this->safeCall(function () use ($event, $conversation, $message, $context) {
            if (app()->bound(AutomationHookInterface::class)) {
                app(AutomationHookInterface::class)->onMessageLifecycleEvent($event, $conversation, $message, $context);
            }
        }, 'message_lifecycle_' . $event);
    }

    public function suggestReply(WhatsAppConversation $conversation, array $thread): ?string
    {
        if (!app()->bound(AiAssistantHookInterface::class)) {
            return null;
        }

        try {
            return app(AiAssistantHookInterface::class)->suggestReply($conversation, $thread);
        } catch (\Throwable $exception) {
            Log::warning('WhatsApp AI hook failed', [
                'conversation_id' => $conversation->id,
                'error' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    protected function safeCall(callable $callback, string $hook): void
    {
        try {
            $callback();
        } catch (\Throwable $exception) {
            Log::warning('WhatsApp future hook failed', [
                'hook' => $hook,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
