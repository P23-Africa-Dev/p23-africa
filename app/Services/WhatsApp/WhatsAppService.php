<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppAccount;
use App\Services\AccessAuditService;
use App\Services\LegacyContextService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WhatsAppService
{
    public function __construct(
        protected LegacyContextService $legacyContextService,
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
            $selectedAccountId = Auth::user()?->whatsapp_account_id;

            if ($selectedAccountId) {
                $account = WhatsAppAccount::query()
                    ->where('id', $selectedAccountId)
                    ->where('status', 'active')
                    ->first();
            }
        }

        if (!$account) {
            $account = WhatsAppAccount::active()->oldest('id')->first();
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

        if ((bool) config('whatsapp.debug_logging', false)) {
            Log::debug('WhatsApp template send request', [
                'to' => $payload['to'],
                'template' => $templateName,
                'language' => $languageCode,
                'component_count' => count((array) data_get($templatePayload, 'components', [])),
                'components' => data_get($templatePayload, 'components', []),
            ]);
        }

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

    public function fetchMessageTemplatesPage(?int $accountId = null, ?string $afterCursor = null, int $limit = 100): array
    {
        $account = $this->resolveAccount($accountId);

        $wabaId = (string) ($account['waba_id'] ?? '');
        if (blank($wabaId)) {
            throw new RuntimeException('WhatsApp Business Account ID (WABA ID) is not configured.');
        }

        $query = [
            'fields' => 'id,name,status,category,language,components,parameter_format',
            'limit' => max(1, min($limit, 100)),
        ];

        if (!blank($afterCursor)) {
            $query['after'] = (string) $afterCursor;
        }

        return $this->get(
            $this->messageTemplatesEndpoint($wabaId),
            $query,
            (string) $account['access_token']
        );
    }

    protected function assertInteractiveAccess(): void
    {
        if (app()->runningInConsole() || !Auth::check()) {
            return;
        }

        $user = Auth::user();
        if ($this->legacyContextService->hasLegacyWhatsAppAccess($user)) {
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

    protected function messageTemplatesEndpoint(string $wabaId): string
    {
        $baseUrl = rtrim((string) config('whatsapp.base_url', 'https://graph.facebook.com'), '/');
        $graphVersion = trim((string) config('whatsapp.graph_version', 'v23.0'), '/');

        return $baseUrl . '/' . $graphVersion . '/' . $wabaId . '/message_templates';
    }

    protected function get(string $url, array $query, string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->timeout(20)
            ->retry(2, 300, null, false)
            ->get($url, $query);

        if (!$response->successful()) {
            throw new RuntimeException(
                'WhatsApp Cloud API request failed with status ' . $response->status() . ': ' . $response->body()
            );
        }

        return $response->json() ?? [];
    }

    protected function post(string $url, array $payload, string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->asJson()
            ->timeout(20)
            ->retry(2, 300, null, false)
            ->post($url, $payload);

        if (!$response->successful()) {
            $body = $response->body();
            $json = $response->json();

            Log::warning('WhatsApp Cloud API POST failed', [
                'url' => $url,
                'status' => $response->status(),
                'template_name' => data_get($payload, 'template.name'),
                'template_language' => data_get($payload, 'template.language.code'),
                'component_count' => count((array) data_get($payload, 'template.components', [])),
                'meta_error_code' => data_get($json, 'error.code'),
                'meta_error_type' => data_get($json, 'error.type'),
                'meta_error_message' => data_get($json, 'error.message'),
                'meta_error_details' => data_get($json, 'error.error_data.details'),
                'meta_fbtrace_id' => data_get($json, 'error.fbtrace_id'),
            ]);

            $errorPayload = is_array($json)
                ? json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                : $body;

            throw new RuntimeException(
                'WhatsApp Cloud API request failed with status ' . $response->status() . ': ' . $errorPayload
            );
        }

        return $response->json() ?? [];
    }
}
