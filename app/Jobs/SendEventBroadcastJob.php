<?php

namespace App\Jobs;

use App\Models\EventWhatsAppCampaign;
use App\Models\EventWhatsAppRecipient;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\ConversationService;
use App\Services\WhatsApp\MessageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEventBroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 4;

    public array $backoff = [20, 60, 120, 300];

    public function __construct(
        public int $campaignId,
        public array $recipientIds,
    ) {}

    public function handle(
        MessageService $messageService,
        ConversationService $conversationService,
    ): void {
        $campaign = EventWhatsAppCampaign::query()
            ->with('template')
            ->find($this->campaignId);

        if (!$campaign || !$campaign->template) {
            return;
        }

        if ($campaign->status === EventWhatsAppCampaign::STATUS_QUEUED) {
            $campaign->update(['status' => EventWhatsAppCampaign::STATUS_PROCESSING]);
        }

        $recipients = EventWhatsAppRecipient::query()
            ->where('campaign_id', $campaign->id)
            ->whereIn('id', $this->recipientIds)
            ->where('status', EventWhatsAppRecipient::STATUS_QUEUED)
            ->get();

        if ($recipients->isEmpty()) {
            $this->finalizeCampaign($campaign);
            return;
        }

        $sent = 0;
        $failed = 0;

        foreach ($recipients as $recipient) {
            $phone = trim((string) $recipient->phone);
            if ($phone === '') {
                $recipient->update([
                    'status' => EventWhatsAppRecipient::STATUS_FAILED,
                ]);
                $failed++;
                continue;
            }

            try {
                $variables = is_array($recipient->variables) ? $recipient->variables : [];

                $conversation = $conversationService->findOrCreateConversationForPhone(
                    $phone,
                    (string) ($variables['name'] ?? ''),
                    [
                        'contact_id' => $recipient->contact_id,
                        'unsaved_contact' => false,
                    ],
                );

                $message = WhatsAppMessage::create([
                    'conversation_id' => $conversation->id,
                    'sender_type' => WhatsAppMessage::SENDER_SYSTEM,
                    'type' => 'template',
                    'body' => null,
                    'payload' => [
                        'account_id' => null,
                        'template_id' => $campaign->template_id,
                        'variables' => $variables,
                        'campaign_id' => $campaign->id,
                        'campaign_recipient_id' => $recipient->id,
                    ],
                    'status' => WhatsAppMessage::STATUS_QUEUED,
                    'status_dirty' => true,
                    'status_updated_at' => now(),
                ]);

                $processed = $messageService->processOutgoingMessage($message);

                $recipient->update([
                    'contact_id' => $recipient->contact_id ?: $conversation->contact_id,
                    'message_id' => $processed->id,
                    'status' => EventWhatsAppRecipient::STATUS_SENT,
                    'sent_at' => now(),
                ]);

                $sent++;
            } catch (\Throwable $exception) {
                if (isset($message) && $message instanceof WhatsAppMessage) {
                    try {
                        $messageService->markFailed($message, $exception->getMessage());
                    } catch (\Throwable) {
                        // Ignore nested failures while preserving recipient status.
                    }
                }

                $recipient->update([
                    'status' => EventWhatsAppRecipient::STATUS_FAILED,
                ]);

                $failed++;

                $metaError = $this->extractMetaApiError($exception->getMessage());

                Log::warning('Event broadcast recipient send failed', [
                    'campaign_id' => $campaign->id,
                    'recipient_id' => $recipient->id,
                    'error' => $exception->getMessage(),
                    'meta_code' => $metaError['code'] ?? null,
                    'meta_type' => $metaError['type'] ?? null,
                    'meta_message' => $metaError['message'] ?? null,
                    'meta_details' => $metaError['details'] ?? null,
                    'meta_fbtrace_id' => $metaError['fbtrace_id'] ?? null,
                ]);
            }

            usleep(120000);
        }

        if ($sent > 0) {
            $campaign->increment('sent', $sent);
        }

        if ($failed > 0) {
            $campaign->increment('failed', $failed);
        }

        $this->finalizeCampaign($campaign->fresh());
    }

    protected function finalizeCampaign(EventWhatsAppCampaign $campaign): void
    {
        $pendingRecipients = EventWhatsAppRecipient::query()
            ->where('campaign_id', $campaign->id)
            ->where('status', EventWhatsAppRecipient::STATUS_QUEUED)
            ->count();

        if ($pendingRecipients > 0) {
            return;
        }

        $campaign->refresh();

        $status = $campaign->sent > 0
            ? EventWhatsAppCampaign::STATUS_COMPLETED
            : EventWhatsAppCampaign::STATUS_FAILED;

        $campaign->update([
            'status' => $status,
            'completed_at' => now(),
        ]);
    }

    protected function extractMetaApiError(string $errorMessage): array
    {
        $start = strpos($errorMessage, '{');
        if ($start === false) {
            return [];
        }

        $json = substr($errorMessage, $start);
        if (!is_string($json) || trim($json) === '') {
            return [];
        }

        $end = strrpos($json, '}');
        if ($end !== false) {
            $json = substr($json, 0, $end + 1);
        }

        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            return [];
        }

        return [
            'code' => data_get($decoded, 'error.code'),
            'type' => data_get($decoded, 'error.type'),
            'message' => data_get($decoded, 'error.message'),
            'details' => data_get($decoded, 'error.error_data.details'),
            'fbtrace_id' => data_get($decoded, 'error.fbtrace_id'),
        ];
    }
}
