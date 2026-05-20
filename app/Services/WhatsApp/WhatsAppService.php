<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppAccount;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WhatsAppService
{
    public function isEnabled(): bool
    {
        return (bool) config('whatsapp.enabled', false);
    }

    public function resolveAccount(?int $accountId = null): array
    {
        $account = null;

        if ($accountId) {
            $account = WhatsAppAccount::find($accountId);
        }

        if (!$account) {
            $account = WhatsAppAccount::active()->latest('id')->first();
        }

        if ($account) {
            return [
                'id' => $account->id,
                'business_name' => $account->business_name,
                'phone_number' => $account->phone_number,
                'phone_number_id' => $account->phone_number_id,
                'waba_id' => $account->waba_id,
                'access_token' => $account->access_token,
                'verify_token' => $account->webhook_verify_token,
            ];
        }

        $phoneNumberId = config('whatsapp.phone_number_id');
        $accessToken = config('whatsapp.access_token');

        if (blank($phoneNumberId) || blank($accessToken)) {
            throw new RuntimeException('WhatsApp account is not configured.');
        }

        return [
            'id' => null,
            'business_name' => config('app.name'),
            'phone_number' => null,
            'phone_number_id' => $phoneNumberId,
            'waba_id' => config('whatsapp.business_account_id'),
            'access_token' => $accessToken,
            'verify_token' => config('whatsapp.verify_token'),
        ];
    }

    public function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? $phone;
    }

    public function sendTextMessage(string $to, string $text, ?int $accountId = null): array
    {
        $account = $this->resolveAccount($accountId);

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->normalizePhone($to),
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $text,
            ],
        ];

        return $this->post(
            $this->messagesEndpoint((string) $account['phone_number_id']),
            $payload,
            (string) $account['access_token']
        );
    }

    public function sendImageMessage(string $to, array $imagePayload, ?int $accountId = null): array
    {
        throw new RuntimeException('Image message support is prepared but not implemented yet.');
    }

    public function sendVideoMessage(string $to, array $videoPayload, ?int $accountId = null): array
    {
        throw new RuntimeException('Video message support is prepared but not implemented yet.');
    }

    public function sendDocumentMessage(string $to, array $documentPayload, ?int $accountId = null): array
    {
        throw new RuntimeException('Document message support is prepared but not implemented yet.');
    }

    public function sendAudioMessage(string $to, array $audioPayload, ?int $accountId = null): array
    {
        throw new RuntimeException('Audio message support is prepared but not implemented yet.');
    }

    public function sendTemplateMessage(string $to, array $templatePayload, ?int $accountId = null): array
    {
        throw new RuntimeException('Template sending support is prepared but not implemented yet.');
    }

    public function sendInteractiveMessage(string $to, array $interactivePayload, ?int $accountId = null): array
    {
        throw new RuntimeException('Interactive message support is prepared but not implemented yet.');
    }

    protected function messagesEndpoint(string $phoneNumberId): string
    {
        $baseUrl = rtrim((string) config('whatsapp.base_url', 'https://graph.facebook.com'), '/');
        $graphVersion = trim((string) config('whatsapp.graph_version', 'v23.0'), '/');

        return $baseUrl . '/' . $graphVersion . '/' . $phoneNumberId . '/messages';
    }

    protected function post(string $url, array $payload, string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->asJson()
            ->timeout(20)
            ->retry(2, 300)
            ->post($url, $payload);

        if (!$response->successful()) {
            throw new RuntimeException(
                'WhatsApp Cloud API request failed with status ' . $response->status() . ': ' . $response->body()
            );
        }

        return $response->json() ?? [];
    }
}
