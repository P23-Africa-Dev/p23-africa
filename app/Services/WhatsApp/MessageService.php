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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
        protected TemplateComposerService $templateComposerService,
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

    public function queueTemplateMessage(
        WhatsAppConversation $conversation,
        int $senderId,
        int $templateId,
        ?int $accountId = null,
        array $templateVariables = []
    ): WhatsAppMessage {
        if (!$this->whatsAppService->isEnabled()) {
            throw new RuntimeException('WhatsApp module is disabled. Set WHATSAPP_ENABLED=true (or provide a valid WhatsApp account config), then clear and recache Laravel config.');
        }

        $template = WhatsAppTemplate::query()->find($templateId);
        if (!$template || $template->status !== WhatsAppTemplate::STATUS_APPROVED) {
            throw ValidationException::withMessages([
                'template_id' => 'Selected template is unavailable or not approved by Meta.',
            ]);
        }

        $normalizedVariables = $this->templateComposerService->normalizeIncomingVariables($templateVariables);

        $templateComponents = [];
        if ($this->metaTemplateParameterService->supportsTemplate($template)) {
            $templatePayload = $this->metaTemplateParameterService->buildPayload($template, $normalizedVariables);
            $templateComponents = is_array(data_get($templatePayload, 'components'))
                ? data_get($templatePayload, 'components')
                : [];
        } else {
            $templateComponents = $this->templateComposerService->buildSendComponents($template, $normalizedVariables);
        }

        $preview = trim($this->templateComposerService->renderPreview($template, $normalizedVariables));
        $messageBody = sprintf(
            'Template: %s (%s)%s',
            (string) $template->name,
            (string) $template->language,
            $preview !== '' ? ' - ' . Str::limit(str_replace("\n", ' ', $preview), 160) : ''
        );

        $message = WhatsAppMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => WhatsAppMessage::SENDER_ADMIN,
            'type' => 'template',
            'body' => $messageBody,
            'payload' => [
                'requested_by' => $senderId,
                'account_id' => $accountId,
                'template_id' => $template->id,
                'direct_template' => true,
                'template_components' => $templateComponents,
                'template_variables' => $normalizedVariables,
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
            $directVariables = data_get($message->payload, 'template_variables', []);
            if (!is_array($directVariables)) {
                $directVariables = [];
            }

            $components = data_get($message->payload, 'template_components', []);
            if (!is_array($components) || empty($components)) {
                if ($this->metaTemplateParameterService->supportsTemplate($template)) {
                    $components = (array) data_get($this->metaTemplateParameterService->buildPayload($template, $directVariables), 'components', []);
                } else {
                    $components = $this->templateComposerService->buildSendComponents($template, $directVariables);
                }
            }

            return $this->whatsAppService->sendTemplateMessage(
                $message->conversation->contact->phone,
                [
                    'name' => (string) $template->name,
                    'language' => [
                        'code' => (string) ($template->language ?: 'en'),
                    ],
                    'components' => $components,
                ],
                $accountId
            );
        }

        $variables = data_get($message->payload, 'variables', []);
        if (!is_array($variables)) {
            $variables = [];
        }

        $sendTemplate = $this->resolveSendTemplate($template);

        if ($sendTemplate->id !== $template->id) {
            Log::info('Event broadcast template auto-resolved to approved revision', [
                'requested_template_id' => $template->id,
                'requested_template_name' => $template->name,
                'resolved_template_id' => $sendTemplate->id,
                'resolved_template_name' => $sendTemplate->name,
            ]);
        } elseif ($sendTemplate->status !== WhatsAppTemplate::STATUS_APPROVED) {
            // Local status can be stale; attempt Meta send and let Graph API decide.
            Log::warning('Sending template with non-approved local status (possible stale sync)', [
                'template_id' => $sendTemplate->id,
                'template_name' => $sendTemplate->name,
                'template_status' => $sendTemplate->status,
            ]);
        }

        if ($this->metaTemplateParameterService->supportsTemplate($sendTemplate)) {
            return $this->whatsAppService->sendTemplateMessage(
                $message->conversation->contact->phone,
                $this->metaTemplateParameterService->buildPayload($sendTemplate, $variables),
                $accountId
            );
        }

        // For approved templates outside of our specialized parameter mapper,
        // dynamically map placeholders so direct/event/broadcast pathways share one engine.
        $genericComponents = $this->templateComposerService->buildSendComponents($sendTemplate, $variables);

        return $this->whatsAppService->sendTemplateMessage(
            $message->conversation->contact->phone,
            [
                'name' => (string) $sendTemplate->name,
                'language' => [
                    'code' => (string) (data_get($sendTemplate->template_json, 'language') ?: $sendTemplate->language ?: 'en'),
                ],
                'components' => $genericComponents,
            ],
            $accountId
        );
    }

    protected function resolveSendTemplate(WhatsAppTemplate $template): WhatsAppTemplate
    {
        if ($template->status === WhatsAppTemplate::STATUS_APPROVED) {
            return $template;
        }

        $requestedName = strtolower(trim((string) $template->name));
        $baseName = $this->extractTemplateBaseName($requestedName);

        $candidates = WhatsAppTemplate::query()
            ->where('status', WhatsAppTemplate::STATUS_APPROVED)
            ->where(function ($query) use ($requestedName, $baseName) {
                $query->whereRaw('LOWER(name) = ?', [$requestedName])
                    ->orWhereRaw('LOWER(name) = ?', [$baseName])
                    ->orWhereRaw('LOWER(name) LIKE ?', [$baseName . '_v%']);
            })
            ->orderByDesc('id')
            ->get();

        $matched = $this->matchTemplateByLanguage($candidates, (string) $template->language)
            ?? $candidates->first();

        return $matched instanceof WhatsAppTemplate ? $matched : $template;
    }

    protected function matchTemplateByLanguage(Collection $templates, string $language): ?WhatsAppTemplate
    {
        $normalize = static function (string $value): string {
            return strtolower(str_replace('-', '_', trim($value)));
        };

        $target = $normalize($language);
        if ($target === '') {
            return $templates->first();
        }

        foreach ($templates as $template) {
            if (!$template instanceof WhatsAppTemplate) {
                continue;
            }

            $candidate = $normalize((string) ($template->language ?? ''));
            if ($candidate === '') {
                continue;
            }

            if ($candidate === $target || strtok($candidate, '_') === strtok($target, '_')) {
                return $template;
            }
        }

        return null;
    }

    protected function extractTemplateBaseName(string $name): string
    {
        $normalized = strtolower(trim($name));
        if (preg_match('/^(.*)_v\d+$/', $normalized, $matches)) {
            $base = trim((string) ($matches[1] ?? ''));
            return $base !== '' ? $base : $normalized;
        }

        return $normalized;
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
