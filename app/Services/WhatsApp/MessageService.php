<?php

namespace App\Services\WhatsApp;

use App\Events\WhatsAppMessageReceived;
use App\Events\WhatsAppMessageSent;
use App\Events\WhatsAppMessageStatusUpdated;
use App\Jobs\SendWhatsAppMessageJob;
use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppTemplate;
use App\Services\WhatsApp\Hooks\FutureIntegrationBridge;
use Carbon\Carbon;
use RuntimeException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class MessageService
{
    public function __construct(
        protected WhatsAppService $whatsAppService,
        protected ConversationService $conversationService,
        protected MessageStatusSyncService $messageStatusSyncService,
        protected WhatsAppRealtimeDebugService $realtimeDebugService,
        protected FutureIntegrationBridge $futureHooks,
        protected TemplateRendererService $templateRendererService,
        protected MetaTemplateParameterService $metaTemplateParameterService,
    ) {}

    public function queueTextMessage(WhatsAppConversation $conversation, int $senderId, string $body, ?int $accountId = null): WhatsAppMessage
    {
        if (!$this->whatsAppService->isEnabled()) {
            throw new RuntimeException('WhatsApp module is disabled. Set WHATSAPP_ENABLED=true (or provide a valid WhatsApp account config), then clear and recache Laravel config.');
        }

        if ($this->shouldEnforceCustomerServiceWindow() && !$this->hasRecentInboundReply($conversation)) {
            throw ValidationException::withMessages([
                'body' => 'Cannot send free-form text because the 24-hour WhatsApp customer service window has expired. Use an approved template message (Meta error 131047).',
            ]);
        }

        $message = WhatsAppMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => WhatsAppMessage::SENDER_ADMIN,
            'type' => 'text',
            'body' => $body,
            'payload' => [
                'requested_by' => $senderId,
                'account_id' => $accountId,
            ],
            'status' => WhatsAppMessage::STATUS_QUEUED,
            'status_dirty' => true,
            'status_updated_at' => now(),
        ]);

        $conversation->update([
            'last_message' => $body,
        ]);

        $conversation->contact?->update([
            'last_chat_at' => now(),
        ]);

        if ((bool) config('whatsapp.queue', true)) {
            SendWhatsAppMessageJob::dispatch($message->id, $accountId)->onQueue('whatsapp');
        } else {
            $this->processOutgoingMessage($message, $accountId);
            $message->refresh();
        }

        return $message;
    }

    public function queueTemplateMessage(WhatsAppConversation $conversation, int $senderId, int $templateId, ?int $accountId = null): WhatsAppMessage
    {
        if (!$this->whatsAppService->isEnabled()) {
            throw new RuntimeException('WhatsApp module is disabled. Set WHATSAPP_ENABLED=true (or provide a valid WhatsApp account config), then clear and recache Laravel config.');
        }

        $template = WhatsAppTemplate::query()->find($templateId);
        if (!$template || $template->status !== WhatsAppTemplate::STATUS_APPROVED) {
            throw ValidationException::withMessages([
                'template_id' => 'Selected template is unavailable or not approved by Meta.',
            ]);
        }

        $message = WhatsAppMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => WhatsAppMessage::SENDER_ADMIN,
            'type' => 'template',
            'body' => sprintf('Template: %s (%s)', (string) $template->name, (string) $template->language),
            'payload' => [
                'requested_by' => $senderId,
                'account_id' => $accountId,
                'template_id' => $template->id,
                'direct_template' => true,
                'template_components' => [],
            ],
            'status' => WhatsAppMessage::STATUS_QUEUED,
            'status_dirty' => true,
            'status_updated_at' => now(),
        ]);

        $conversation->update([
            'last_message' => (string) $message->body,
        ]);

        $conversation->contact?->update([
            'last_chat_at' => now(),
        ]);

        if ((bool) config('whatsapp.queue', true)) {
            SendWhatsAppMessageJob::dispatch($message->id, $accountId)->onQueue('whatsapp');
        } else {
            $this->processOutgoingMessage($message, $accountId);
            $message->refresh();
        }

        return $message;
    }

    protected function shouldEnforceCustomerServiceWindow(): bool
    {
        return (bool) config('whatsapp.enforce_customer_service_window', true);
    }

    protected function hasRecentInboundReply(WhatsAppConversation $conversation): bool
    {
        $windowStart = now()->subHours(24);

        return WhatsAppMessage::query()
            ->where('conversation_id', $conversation->id)
            ->where('sender_type', WhatsAppMessage::SENDER_CONTACT)
            ->where('created_at', '>=', $windowStart)
            ->exists();
    }

    public function processOutgoingMessage(WhatsAppMessage $message, ?int $accountId = null): WhatsAppMessage
    {
        $message->loadMissing('conversation.contact');

        if (!$message->conversation || !$message->conversation->contact) {
            throw new RuntimeException('Cannot send message without a linked conversation contact.');
        }

        $resolvedAccountId = $accountId ?? data_get($message->payload, 'account_id');

        if ($message->type === 'text') {
            $response = $this->whatsAppService->sendTextMessage(
                $message->conversation->contact->phone,
                (string) $message->body,
                $resolvedAccountId
            );
        } elseif ($message->type === 'template') {
            $response = $this->sendTemplateContent($message, $resolvedAccountId);
        } else {
            throw new RuntimeException('Unsupported message type for sending: ' . $message->type);
        }

        $message->update([
            'whatsapp_message_id' => data_get($response, 'messages.0.id'),
            'status' => WhatsAppMessage::STATUS_SENT,
            'status_dirty' => true,
            'status_updated_at' => now(),
            'sent_at' => now(),
            'payload' => array_merge($message->payload ?? [], [
                'response' => $response,
            ]),
        ]);

        $message = $message->fresh(['conversation.contact', 'conversation.assignedUser']);

        if ($message && $message->conversation) {
            $this->futureHooks->onOutgoingMessage($message->conversation, $message);
        }

        $this->realtimeDebugService->logStatusSync($message?->id, $message?->status, [
            'conversation_id' => $message?->conversation_id,
            'source' => 'process_outgoing_message',
        ]);

        $this->dispatchRealtimeEvent(new WhatsAppMessageSent($message), [
            'conversation_id' => $message->conversation_id,
            'message_id' => $message->id,
            'direction' => 'outgoing',
        ]);

        return $message;
    }

    protected function sendTemplateContent(WhatsAppMessage $message, ?int $accountId = null): array
    {
        $templateId = (int) data_get($message->payload, 'template_id', 0);

        if ($templateId <= 0) {
            throw new RuntimeException('Template message payload must include template_id.');
        }

        $template = WhatsAppTemplate::query()->find($templateId);
        if (!$template) {
            throw new RuntimeException('Template not found: ' . $templateId);
        }

        if ((bool) data_get($message->payload, 'direct_template', false)) {
            return $this->whatsAppService->sendTemplateMessage(
                $message->conversation->contact->phone,
                [
                    'name' => (string) $template->name,
                    'language' => [
                        'code' => (string) ($template->language ?: 'en'),
                    ],
                    'components' => is_array(data_get($message->payload, 'template_components'))
                        ? data_get($message->payload, 'template_components')
                        : [],
                ],
                $accountId
            );
        }

        $variables = data_get($message->payload, 'variables', []);
        if (!is_array($variables)) {
            $variables = [];
        }

        if ($template->status === WhatsAppTemplate::STATUS_APPROVED && $this->metaTemplateParameterService->supportsTemplate($template)) {
            return $this->whatsAppService->sendTemplateMessage(
                $message->conversation->contact->phone,
                $this->metaTemplateParameterService->buildPayload($template, $variables),
                $accountId
            );
        }

        $format = (string) ($template->template_format ?: TemplateRendererService::FORMAT_JSON);

        $sourceContent = $format === TemplateRendererService::FORMAT_JSON
            ? ($template->template_json ?? [])
            : (string) ($template->rendered_content ?: data_get($template->editor_metadata, 'source_content', ''));

        $rendered = $this->templateRendererService->render($format, $sourceContent, $variables);
        $textBody = (string) data_get($rendered, 'whatsapp_payload.components.0.text', '');
        $firstMedia = data_get($rendered, 'whatsapp_payload.media.0');

        if (is_array($firstMedia) && data_get($firstMedia, 'type') === 'image' && data_get($firstMedia, 'url')) {
            return $this->whatsAppService->sendImageMessage(
                $message->conversation->contact->phone,
                [
                    'link' => (string) data_get($firstMedia, 'url'),
                    'caption' => $textBody,
                ],
                $accountId
            );
        }

        return $this->whatsAppService->sendTextMessage(
            $message->conversation->contact->phone,
            $textBody,
            $accountId
        );
    }

    public function markFailed(WhatsAppMessage $message, string $error): void
    {
        $message->update([
            'status' => WhatsAppMessage::STATUS_FAILED,
            'status_dirty' => true,
            'status_updated_at' => now(),
            'payload' => array_merge($message->payload ?? [], [
                'error' => $error,
            ]),
        ]);

        $this->realtimeDebugService->logStatusSync($message->id, WhatsAppMessage::STATUS_FAILED, [
            'conversation_id' => $message->conversation_id,
            'source' => 'mark_failed',
        ]);
    }

    public function handleIncomingMessage(array $messagePayload, array $contactPayload = []): ?WhatsAppMessage
    {
        $from = (string) data_get($messagePayload, 'from', '');

        if ($from === '') {
            return null;
        }

        $contactName = data_get($contactPayload, 'profile.name');
        $conversation = $this->conversationService->findOrCreateConversationForPhone($from, $contactName, [
            'wa_id' => data_get($contactPayload, 'wa_id'),
            'raw_contact' => $contactPayload,
            'unsaved_contact' => true,
        ]);

        $type = (string) data_get($messagePayload, 'type', 'text');
        $body = $this->extractBody($messagePayload);

        $sentAt = null;
        $timestamp = data_get($messagePayload, 'timestamp');
        if ($timestamp) {
            $sentAt = Carbon::createFromTimestamp((int) $timestamp);
        }

        $message = WhatsAppMessage::create([
            'conversation_id' => $conversation->id,
            'whatsapp_message_id' => data_get($messagePayload, 'id'),
            'sender_type' => WhatsAppMessage::SENDER_CONTACT,
            'type' => $type,
            'body' => $body,
            'payload' => $messagePayload,
            'status' => WhatsAppMessage::STATUS_RECEIVED,
            'sent_at' => $sentAt,
        ]);

        $conversation->update([
            'status' => WhatsAppConversation::STATUS_OPEN,
            'last_message' => $body ?: '[' . ucfirst($type) . ']',
            'unread_count' => max(0, $conversation->unread_count + 1),
        ]);

        $conversation->contact?->update([
            'last_chat_at' => now(),
        ]);

        $message = $message->fresh(['conversation.contact', 'conversation.assignedUser']);

        if ($message && $message->conversation) {
            $this->futureHooks->onIncomingMessage($message->conversation, $message);
        }

        $this->dispatchRealtimeEvent(new WhatsAppMessageReceived($message), [
            'conversation_id' => $message->conversation_id,
            'message_id' => $message->id,
            'direction' => 'incoming',
        ]);

        return $message;
    }

    public function handleStatusUpdate(array $statusPayload): ?WhatsAppMessage
    {
        $message = $this->messageStatusSyncService->syncMessageStatus($statusPayload);

        if (!$message) {
            return null;
        }

        $this->realtimeDebugService->logStatusSync($message->id, $message->status, [
            'conversation_id' => $message->conversation_id,
            'source' => 'webhook_status_update',
            'meta_error' => $this->extractMetaStatusError($statusPayload),
        ]);

        if ($message && $message->conversation) {
            $this->futureHooks->onMessageLifecycleEvent('status_updated', $message->conversation, $message, [
                'status_payload' => $statusPayload,
            ]);
        }

        $this->dispatchRealtimeEvent(new WhatsAppMessageStatusUpdated($message), [
            'conversation_id' => $message->conversation_id,
            'message_id' => $message->id,
            'direction' => 'status',
        ]);

        return $message;
    }

    protected function extractMetaStatusError(array $statusPayload): ?array
    {
        $errors = data_get($statusPayload, 'errors', []);
        if (!is_array($errors) || empty($errors)) {
            return null;
        }

        $first = is_array($errors[0] ?? null) ? $errors[0] : [];

        return [
            'code' => data_get($first, 'code'),
            'title' => data_get($first, 'title'),
            'message' => data_get($first, 'message'),
            'details' => data_get($first, 'error_data.details'),
        ];
    }

    protected function extractBody(array $messagePayload): ?string
    {
        $type = (string) data_get($messagePayload, 'type', 'text');

        return match ($type) {
            'text' => data_get($messagePayload, 'text.body'),
            'image' => data_get($messagePayload, 'image.caption') ?: '[Image]',
            'video' => data_get($messagePayload, 'video.caption') ?: '[Video]',
            'document' => data_get($messagePayload, 'document.caption') ?: '[Document]',
            'audio' => '[Audio]',
            'interactive' => '[Interactive Message]',
            default => '[Unsupported message type: ' . $type . ']',
        };
    }

    protected function dispatchRealtimeEvent(object $event, array $context = []): void
    {
        try {
            event($event);
        } catch (\Throwable $exception) {
            Log::warning('WhatsApp realtime event dispatch skipped', array_merge($context, [
                'event' => get_class($event),
                'error' => $exception->getMessage(),
            ]));
        }
    }
}
