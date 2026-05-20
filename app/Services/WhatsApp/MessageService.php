<?php

namespace App\Services\WhatsApp;

use App\Events\WhatsAppMessageReceived;
use App\Events\WhatsAppMessageSent;
use App\Events\WhatsAppMessageStatusUpdated;
use App\Jobs\SendWhatsAppMessageJob;
use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\Hooks\FutureIntegrationBridge;
use Carbon\Carbon;
use RuntimeException;

class MessageService
{
    public function __construct(
        protected WhatsAppService $whatsAppService,
        protected ConversationService $conversationService,
        protected FutureIntegrationBridge $futureHooks,
    ) {}

    public function queueTextMessage(WhatsAppConversation $conversation, int $senderId, string $body, ?int $accountId = null): WhatsAppMessage
    {
        if (!$this->whatsAppService->isEnabled()) {
            throw new RuntimeException('WhatsApp module is disabled.');
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
        ]);

        $conversation->update([
            'last_message' => $body,
        ]);

        if ((bool) config('whatsapp.queue', true)) {
            SendWhatsAppMessageJob::dispatch($message->id, $accountId)->onQueue('whatsapp');
        } else {
            $this->processOutgoingMessage($message, $accountId);
            $message->refresh();
        }

        return $message;
    }

    public function processOutgoingMessage(WhatsAppMessage $message, ?int $accountId = null): WhatsAppMessage
    {
        $message->loadMissing('conversation.contact');

        if (!$message->conversation || !$message->conversation->contact) {
            throw new RuntimeException('Cannot send message without a linked conversation contact.');
        }

        if ($message->type !== 'text') {
            throw new RuntimeException('Only text messages are currently supported for sending.');
        }

        $response = $this->whatsAppService->sendTextMessage(
            $message->conversation->contact->phone,
            (string) $message->body,
            $accountId ?? data_get($message->payload, 'account_id')
        );

        $message->update([
            'whatsapp_message_id' => data_get($response, 'messages.0.id'),
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => now(),
            'payload' => array_merge($message->payload ?? [], [
                'response' => $response,
            ]),
        ]);

        $message = $message->fresh(['conversation.contact', 'conversation.assignedUser']);

        if ($message && $message->conversation) {
            $this->futureHooks->onOutgoingMessage($message->conversation, $message);
        }

        event(new WhatsAppMessageSent($message));

        return $message;
    }

    public function markFailed(WhatsAppMessage $message, string $error): void
    {
        $message->update([
            'status' => WhatsAppMessage::STATUS_FAILED,
            'payload' => array_merge($message->payload ?? [], [
                'error' => $error,
            ]),
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

        $message = $message->fresh(['conversation.contact', 'conversation.assignedUser']);

        if ($message && $message->conversation) {
            $this->futureHooks->onIncomingMessage($message->conversation, $message);
        }

        event(new WhatsAppMessageReceived($message));

        return $message;
    }

    public function handleStatusUpdate(array $statusPayload): ?WhatsAppMessage
    {
        $whatsAppMessageId = data_get($statusPayload, 'id');

        if (!$whatsAppMessageId) {
            return null;
        }

        $message = WhatsAppMessage::query()
            ->where('whatsapp_message_id', $whatsAppMessageId)
            ->orderByDesc('id')
            ->first();

        if (!$message) {
            return null;
        }

        $mappedStatus = $this->mapStatus((string) data_get($statusPayload, 'status', 'sent'));

        $updates = [
            'status' => $mappedStatus,
        ];

        $timestamp = data_get($statusPayload, 'timestamp');
        if ($timestamp) {
            $eventAt = Carbon::createFromTimestamp((int) $timestamp);

            if ($mappedStatus === WhatsAppMessage::STATUS_DELIVERED) {
                $updates['delivered_at'] = $eventAt;
            }

            if ($mappedStatus === WhatsAppMessage::STATUS_READ) {
                $updates['read_at'] = $eventAt;
                if (!$message->delivered_at) {
                    $updates['delivered_at'] = $eventAt;
                }
            }
        }

        $message->update($updates);
        $message = $message->fresh(['conversation.contact', 'conversation.assignedUser']);

        if ($message && $message->conversation) {
            $this->futureHooks->onMessageLifecycleEvent('status_updated', $message->conversation, $message, [
                'status_payload' => $statusPayload,
            ]);
        }

        event(new WhatsAppMessageStatusUpdated($message));

        return $message;
    }

    protected function mapStatus(string $status): string
    {
        return match (strtolower($status)) {
            'sent' => WhatsAppMessage::STATUS_SENT,
            'delivered' => WhatsAppMessage::STATUS_DELIVERED,
            'read' => WhatsAppMessage::STATUS_READ,
            'failed' => WhatsAppMessage::STATUS_FAILED,
            default => WhatsAppMessage::STATUS_SENT,
        };
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
}
