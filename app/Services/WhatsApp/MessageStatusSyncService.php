<?php

namespace App\Services\WhatsApp;

use App\Models\EventWhatsAppRecipient;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class MessageStatusSyncService
{
    public function getPendingStatusUpdates(int $conversationId, Carbon|string|null $since = null, int $limit = 250): Collection
    {
        $sinceAt = $this->normalizeSince($since);

        return WhatsAppMessage::query()
            ->select(['id', 'conversation_id', 'status', 'status_dirty', 'status_updated_at', 'updated_at'])
            ->where('conversation_id', $conversationId)
            ->where('sender_type', WhatsAppMessage::SENDER_ADMIN)
            ->whereIn('status', [
                WhatsAppMessage::STATUS_QUEUED,
                WhatsAppMessage::STATUS_SENT,
                WhatsAppMessage::STATUS_DELIVERED,
                WhatsAppMessage::STATUS_READ,
                WhatsAppMessage::STATUS_FAILED,
            ])
            ->where(function ($query) use ($sinceAt) {
                $query->where('status_dirty', true)
                    ->orWhere('status_updated_at', '>', $sinceAt)
                    ->orWhere('updated_at', '>', $sinceAt);
            })
            ->orderByRaw('CASE WHEN status_dirty = 1 THEN 0 ELSE 1 END')
            ->orderBy('status_updated_at')
            ->orderBy('updated_at')
            ->limit(max(1, min($limit, 1000)))
            ->get();
    }

    public function markStatusUpdatesDelivered(Collection|array $messageIds): void
    {
        $ids = collect($messageIds)
            ->map(static fn($id) => (int) $id)
            ->filter(static fn($id) => $id > 0)
            ->values();

        if ($ids->isEmpty()) {
            return;
        }

        WhatsAppMessage::query()
            ->whereIn('id', $ids->all())
            ->update(['status_dirty' => false]);
    }

    public function applyStatusUpdate(
        WhatsAppMessage $message,
        string $status,
        Carbon|string|null $eventAt = null,
        array $extraPayload = []
    ): WhatsAppMessage {
        $mappedStatus = $this->mapStatus($status);
        $eventAt = $this->normalizeTimestamp($eventAt);
        $eventTimestamp = $eventAt ?: now();

        $currentRank = $this->statusRank((string) $message->status);
        $nextRank = $this->statusRank($mappedStatus);

        if ($nextRank < $currentRank) {
            return $message->fresh(['conversation.contact', 'conversation.assignedUser']);
        }

        $updates = [
            'status' => $mappedStatus,
            'status_dirty' => true,
            'status_updated_at' => $eventTimestamp,
        ];

        if ($mappedStatus === WhatsAppMessage::STATUS_SENT && !$message->sent_at) {
            $updates['sent_at'] = $eventTimestamp;
        }

        if ($mappedStatus === WhatsAppMessage::STATUS_DELIVERED) {
            $updates['delivered_at'] = $eventTimestamp;
            if (!$message->sent_at) {
                $updates['sent_at'] = $eventTimestamp;
            }
        }

        if ($mappedStatus === WhatsAppMessage::STATUS_READ) {
            $updates['read_at'] = $eventTimestamp;
            if (!$message->delivered_at) {
                $updates['delivered_at'] = $eventTimestamp;
            }
            if (!$message->sent_at) {
                $updates['sent_at'] = $eventTimestamp;
            }
        }

        if ($mappedStatus === WhatsAppMessage::STATUS_FAILED) {
            $updates['payload'] = array_merge($message->payload ?? [], [
                'status_error' => Arr::only($extraPayload, ['errors', 'status', 'timestamp', 'id']),
            ]);
        }

        $message->update($updates);

        $this->syncCampaignRecipientStatus($message, $mappedStatus, $eventTimestamp);

        return $message->fresh(['conversation.contact', 'conversation.assignedUser']);
    }

    public function syncMessageStatus(array $statusPayload): ?WhatsAppMessage
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

        $eventAt = null;
        if (data_get($statusPayload, 'timestamp')) {
            $eventAt = Carbon::createFromTimestamp((int) data_get($statusPayload, 'timestamp'));
        }

        return $this->applyStatusUpdate(
            $message,
            (string) data_get($statusPayload, 'status', 'sent'),
            $eventAt,
            $statusPayload
        );
    }

    protected function normalizeSince(Carbon|string|null $since): Carbon
    {
        if ($since instanceof Carbon) {
            return $since;
        }

        if (is_string($since) && trim($since) !== '') {
            try {
                return Carbon::parse($since);
            } catch (\Throwable) {
                // fall through
            }
        }

        return now()->subMinutes(2);
    }

    protected function normalizeTimestamp(Carbon|string|null $value): ?Carbon
    {
        if ($value instanceof Carbon) {
            return $value;
        }

        if (is_string($value) && trim($value) !== '') {
            try {
                return Carbon::parse($value);
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }

    protected function mapStatus(string $status): string
    {
        return match (strtolower($status)) {
            'queued' => WhatsAppMessage::STATUS_QUEUED,
            'sending' => WhatsAppMessage::STATUS_QUEUED,
            'sent' => WhatsAppMessage::STATUS_SENT,
            'delivered' => WhatsAppMessage::STATUS_DELIVERED,
            'read' => WhatsAppMessage::STATUS_READ,
            'failed' => WhatsAppMessage::STATUS_FAILED,
            default => WhatsAppMessage::STATUS_SENT,
        };
    }

    protected function statusRank(string $status): int
    {
        return match (strtolower($status)) {
            WhatsAppMessage::STATUS_FAILED => 99,
            WhatsAppMessage::STATUS_READ => 4,
            WhatsAppMessage::STATUS_DELIVERED => 3,
            WhatsAppMessage::STATUS_SENT => 2,
            WhatsAppMessage::STATUS_QUEUED => 1,
            default => 0,
        };
    }

    protected function syncCampaignRecipientStatus(WhatsAppMessage $message, string $mappedStatus, Carbon $eventTimestamp): void
    {
        $payload = is_array($message->payload) ? $message->payload : [];

        $recipientId = (int) ($payload['campaign_recipient_id'] ?? 0);
        if ($recipientId <= 0) {
            return;
        }

        $recipient = EventWhatsAppRecipient::query()->find($recipientId);
        if (!$recipient) {
            return;
        }

        if ($mappedStatus === WhatsAppMessage::STATUS_SENT) {
            $recipient->update([
                'status' => EventWhatsAppRecipient::STATUS_SENT,
                'message_id' => $message->id,
                'sent_at' => $recipient->sent_at ?: $eventTimestamp,
            ]);

            return;
        }

        if (in_array($mappedStatus, [WhatsAppMessage::STATUS_DELIVERED, WhatsAppMessage::STATUS_READ], true)) {
            $recipient->update([
                'status' => EventWhatsAppRecipient::STATUS_DELIVERED,
                'message_id' => $message->id,
                'sent_at' => $recipient->sent_at ?: $eventTimestamp,
                'delivered_at' => $eventTimestamp,
            ]);

            return;
        }

        if ($mappedStatus === WhatsAppMessage::STATUS_FAILED) {
            $recipient->update([
                'status' => EventWhatsAppRecipient::STATUS_FAILED,
                'message_id' => $message->id,
            ]);
        }
    }
}
