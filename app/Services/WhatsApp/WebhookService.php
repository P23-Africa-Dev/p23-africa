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
        $secret = (string) config('whatsapp.webhook_secret');

        if ($secret === '') {
            return true;
        }

        if (!$signatureHeader || !str_starts_with($signatureHeader, 'sha256=')) {
            return false;
        }

        $providedSignature = substr($signatureHeader, 7);
        $expectedSignature = hash_hmac('sha256', $rawPayload, $secret);

        return hash_equals($expectedSignature, $providedSignature);
    }

    public function handleWebhook(array $payload, array $headers = [], bool $signatureValid = true): WhatsAppWebhookLog
    {
        $log = WhatsAppWebhookLog::create([
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

        if ($field === 'messages') {
            $contacts = (array) data_get($value, 'contacts', []);

            foreach ((array) data_get($value, 'messages', []) as $messagePayload) {
                $from = data_get($messagePayload, 'from');
                $contactPayload = $this->findContactPayload($contacts, $from);
                $this->messageService->handleIncomingMessage((array) $messagePayload, $contactPayload);
            }

            foreach ((array) data_get($value, 'statuses', []) as $statusPayload) {
                $this->messageService->handleStatusUpdate((array) $statusPayload);
            }

            return;
        }

        if (in_array($field, ['message_template_status_update', 'template_status_update'], true)) {
            $this->templateService->handleTemplateWebhookEvent($value);
            return;
        }

        if (!empty($value['statuses'])) {
            foreach ((array) $value['statuses'] as $statusPayload) {
                $this->messageService->handleStatusUpdate((array) $statusPayload);
            }
        }
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
