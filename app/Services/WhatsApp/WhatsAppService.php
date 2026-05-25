<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppAccount;
use App\Services\AccessAuditService;
use App\Services\LegacyWhatsAppAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WhatsAppService
{
    public function __construct(
        protected LegacyWhatsAppAccessService $legacyWhatsAppAccessService,
        protected AccessAuditService $accessAuditService,
    ) {}

    public function isEnabled(): bool
    {
        $enabled = config('whatsapp.enabled');

        if (is_bool($enabled)) {
            return $enabled;
        }

        if (is_string($enabled) && trim($enabled) !== '') {
            $parsed = filter_var($enabled, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if (!is_null($parsed)) {
                return $parsed;
            }
        }

        try {
            $this->resolveAccount();
            return true;
        } catch (\Throwable) {
            return false;
        }
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
        $this->assertInteractiveAccess();

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
        $this->assertInteractiveAccess();

        $account = $this->resolveAccount($accountId);

        $link = (string) data_get($imagePayload, 'link', '');
        if ($link === '') {
            throw new RuntimeException('Image payload must include a public image link.');
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->normalizePhone($to),
            'type' => 'image',
            'image' => [
                'link' => $link,
                'caption' => (string) data_get($imagePayload, 'caption', ''),
            ],
        ];

        return $this->post(
            $this->messagesEndpoint((string) $account['phone_number_id']),
            $payload,
            (string) $account['access_token']
        );
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
        $this->assertInteractiveAccess();

        $account = $this->resolveAccount($accountId);

        $templateName = (string) data_get($templatePayload, 'name', '');
        $languageCode = (string) data_get($templatePayload, 'language.code', data_get($templatePayload, 'language', 'en'));

        if ($templateName === '') {
            throw new RuntimeException('Template payload must include a template name.');
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->normalizePhone($to),
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode,
                ],
                'components' => data_get($templatePayload, 'components', []),
            ],
        ];

        return $this->post(
            $this->messagesEndpoint((string) $account['phone_number_id']),
            $payload,
            (string) $account['access_token']
        );
    }

    public function sendInteractiveMessage(string $to, array $interactivePayload, ?int $accountId = null): array
    {
        throw new RuntimeException('Interactive message support is prepared but not implemented yet.');
    }

    protected function assertInteractiveAccess(): void
    {
        if (app()->runningInConsole() || !auth()->check()) {
            return;
        }

        $user = auth()->user();
        if ($this->legacyWhatsAppAccessService->canAccessWhatsApp($user)) {
            return;
        }

        $httpRequest = request();

        $this->accessAuditService->logDeniedWhatsAppAccess(
            $httpRequest instanceof Request ? $httpRequest : null,
            $user,
            'blocked',
            'Service-level WhatsApp send operation blocked by legacy access policy.'
        );

        throw new RuntimeException('Forbidden WhatsApp access.');
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
