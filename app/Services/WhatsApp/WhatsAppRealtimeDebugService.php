<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Log;

class WhatsAppRealtimeDebugService
{
    public function enabled(): bool
    {
        return app()->hasDebugModeEnabled() && (bool) config('whatsapp.realtime_debug', true);
    }

    public function log(string $event, array $context = []): void
    {
        if (!$this->enabled()) {
            return;
        }

        Log::debug('whatsapp_realtime: ' . $event, $context);
    }

    public function logPollExecution(string $pollType, ?int $conversationId = null, array $context = []): void
    {
        $this->log('poll_execution', array_merge([
            'poll_type' => $pollType,
            'conversation_id' => $conversationId,
        ], $context));
    }

    public function logStatusSync(?int $messageId, ?string $status, array $context = []): void
    {
        $this->log('status_sync', array_merge([
            'message_id' => $messageId,
            'status' => $status,
        ], $context));
    }

    public function logMessageRefresh(?int $conversationId, int $count, array $context = []): void
    {
        $this->log('message_refresh', array_merge([
            'conversation_id' => $conversationId,
            'count' => $count,
        ], $context));
    }

    public function logCloseChat(?int $conversationId, string $action, array $context = []): void
    {
        $this->log('close_chat', array_merge([
            'conversation_id' => $conversationId,
            'action' => $action,
        ], $context));
    }

    public function logFrontendRegistration(string $component, array $context = []): void
    {
        $this->log('frontend_registration', array_merge([
            'component' => $component,
        ], $context));
    }

    public function logAssetLoading(string $asset, array $context = []): void
    {
        $this->log('asset_loading', array_merge([
            'asset' => $asset,
        ], $context));
    }
}
