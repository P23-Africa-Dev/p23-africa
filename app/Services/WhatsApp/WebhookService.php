<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppAccount;
use App\Models\WhatsAppWebhookLog;

class WebhookService
{
    public function __construct(
        protected MessageService $messageService,
        protected TemplateService $templateService,
    ) {}

    public function verifyWebhookToken(?string $verifyToken): bool
    {
        if (!$verifyToken) {
            return false;
        }

        $configuredToken = config('whatsapp.verify_token');
        if ($configuredToken && hash_equals((string) $configuredToken, (string) $verifyToken)) {
            return true;
        }

        return WhatsAppAccount::query()
            ->where('webhook_verify_token', $verifyToken)
            ->exists();
    }

    public function verifySignature(string $rawPayload, ?string $signatureHeader): bool
    {
        $secret = trim((string) config('whatsapp.webhook_secret'));

        if ($secret === '') {
            return true;
        }

        if (!$signatureHeader) {
            return false;
        }

        $signatureHeader = trim($signatureHeader);

        // Meta sends: X-Hub-Signature-256: sha256=<hex hash>
        // Some proxies may strip prefix and pass only the hash, so support both.
        if (str_starts_with(strtolower($signatureHeader), 'sha256=')) {
            $providedSignature = substr($signatureHeader, 7);
        } else {
            $providedSignature = $signatureHeader;
        }

        if ($providedSignature === '') {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $rawPayload, $secret);

        return hash_equals(strtolower($expectedSignature), strtolower($providedSignature));
    }

    public function handleWebhook(array $payload, array $headers = [], bool $signatureValid = true): WhatsAppWebhookLog
    {
        $accountId = $this->resolveAccountIdFromPayload($payload);

        $log = WhatsAppWebhookLog::create([
            'account_id' => $accountId,
            'event_type' => $this->extractEventType($payload),
            'payload' => $payload,
            'headers' => $headers,
            'signature_valid' => $signatureValid,
            'status' => 'received',
        ]);

        try {
            foreach ((array) data_get($payload, 'entry', []) as $entry) {
                foreach ((array) data_get($entry, 'changes', []) as $change) {
                    $this->processChange((array) $change);
                }
            }

            $log->update([
                'status' => 'processed',
                'processed_at' => now(),
            ]);
        } catch (\Throwable $exception) {
            $log->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'processed_at' => now(),
            ]);

            throw $exception;
        }

        return $log;
    }

    protected function processChange(array $change): void
    {
        $field = data_get($change, 'field');
        $value = (array) data_get($change, 'value', []);

        $accountId = $this->resolveAccountIdFromChangeValue($value);

        if ($field === 'messages') {
            $contacts = (array) data_get($value, 'contacts', []);

            foreach ((array) data_get($value, 'messages', []) as $messagePayload) {
                $from = data_get($messagePayload, 'from');
                $contactPayload = array_merge([
                    'wa_id' => $from,
                ], $this->findContactPayload($contacts, $from));
                $this->messageService->handleIncomingMessage((array) $messagePayload, $contactPayload, $accountId);
            }

            foreach ((array) data_get($value, 'statuses', []) as $statusPayload) {
                $this->messageService->handleStatusUpdate((array) $statusPayload, $accountId);
            }

            return;
        }

        if (in_array($field, ['message_template_status_update', 'template_status_update'], true)) {
            $this->templateService->handleTemplateWebhookEvent($value);
            return;
        }

        if (!empty($value['statuses'])) {
            foreach ((array) $value['statuses'] as $statusPayload) {
                $this->messageService->handleStatusUpdate((array) $statusPayload, $accountId);
            }
        }
    }

    /**
     * Look at the first change with a `metadata.phone_number_id` in the payload
     * and map it to a stored WhatsAppAccount. Used to tag webhook logs.
     */
    protected function resolveAccountIdFromPayload(array $payload): ?int
    {
        foreach ((array) data_get($payload, 'entry', []) as $entry) {
            foreach ((array) data_get($entry, 'changes', []) as $change) {
                $value = (array) data_get($change, 'value', []);
                $accountId = $this->resolveAccountIdFromChangeValue($value);
                if ($accountId !== null) {
                    return $accountId;
                }
            }
        }

        return null;
    }

    protected function resolveAccountIdFromChangeValue(array $value): ?int
    {
        $phoneNumberId = data_get($value, 'metadata.phone_number_id');
        if (!$phoneNumberId) {
            return null;
        }

        $accountId = WhatsAppAccount::query()
            ->where('phone_number_id', (string) $phoneNumberId)
            ->value('id');

        return $accountId ? (int) $accountId : null;
    }

    protected function findContactPayload(array $contacts, ?string $from): array
    {
        foreach ($contacts as $contact) {
            if ((string) data_get($contact, 'wa_id') === (string) $from) {
                return (array) $contact;
            }
        }

        return [];
    }

    protected function extractEventType(array $payload): ?string
    {
        return data_get($payload, 'entry.0.changes.0.field');
    }
}
