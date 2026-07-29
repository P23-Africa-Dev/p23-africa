<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppContact;
use App\Models\WhatsAppConversation;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WhatsAppNumberValidationService
{
    public const STATUS_VALID = 'valid';
    public const STATUS_INVALID = 'invalid';
    public const STATUS_PENDING = 'pending';
    public const STATUS_FAILED = 'failed';

    public function __construct(
        protected ConversationService $conversationService,
        protected WhatsAppService $whatsAppService,
    ) {}

    public function normalizeNumber(string $phone, ?string $countryCode = null): string
    {
        $raw = trim($phone);
        if ($raw === '') {
            return '';
        }

        $hasPlus = str_starts_with($raw, '+');
        $digits = preg_replace('/\D+/', '', $raw) ?? '';
        if ($digits === '') {
            return '';
        }

        if ($hasPlus) {
            return '+' . $digits;
        }

        $codeDigits = preg_replace('/\D+/', '', (string) $countryCode) ?? '';
        if ($codeDigits !== '' && !str_starts_with($digits, $codeDigits)) {
            $digits = $codeDigits . ltrim($digits, '0');
        }

        return '+' . $digits;
    }

    public function validateWhatsAppNumber(string $phone, ?string $countryCode = null, bool $force = false): array
    {
        $normalized = $this->normalizeNumber($phone, $countryCode);
        if ($normalized === '') {
            return [
                'phone' => $phone,
                'normalized_phone' => $normalized,
                'is_whatsapp' => false,
                'validation_status' => self::STATUS_INVALID,
                'validated_at' => now(),
                'validation_cache_until' => now()->addHours(24),
                'source' => 'input_validation',
                'message' => 'Invalid phone number format.',
            ];
        }

        $contact = $this->checkExistingContact($normalized);
        if (!$force && $contact && $contact->validation_cache_until && $contact->validation_cache_until->isFuture()) {
            return [
                'phone' => $phone,
                'normalized_phone' => $normalized,
                'is_whatsapp' => $contact->is_whatsapp,
                'validation_status' => $contact->validation_status ?: self::STATUS_PENDING,
                'validated_at' => $contact->validated_at,
                'validation_cache_until' => $contact->validation_cache_until,
                'source' => 'cache',
                'message' => 'Validation loaded from cache.',
            ];
        }

        $account = $this->whatsAppService->resolveAccount();
        $response = $this->callMetaContactsValidation((string) $account['phone_number_id'], (string) $account['access_token'], $normalized);

        $status = self::STATUS_PENDING;
        $isWhatsApp = null;
        $message = 'Validation pending.';

        if ($response['supported']) {
            $status = $response['is_valid'] ? self::STATUS_VALID : self::STATUS_INVALID;
            $isWhatsApp = $response['is_valid'];
            $message = $response['is_valid']
                ? 'This number is available on WhatsApp.'
                : 'This number is not available on WhatsApp.';
        }

        return [
            'phone' => $phone,
            'normalized_phone' => $normalized,
            'is_whatsapp' => $isWhatsApp,
            'validation_status' => $status,
            'validated_at' => now(),
            'validation_cache_until' => now()->addHours(24),
            'source' => $response['supported'] ? 'meta_contacts' : 'meta_unavailable',
            'message' => $message,
            'meta_response' => $response['payload'],
        ];
    }

    public function checkExistingContact(string $normalizedPhone): ?WhatsAppContact
    {
        $digitsOnly = preg_replace('/\D+/', '', $normalizedPhone) ?? '';

        return WhatsAppContact::query()
            ->where('phone', $normalizedPhone)
            ->when($digitsOnly !== '' && $digitsOnly !== $normalizedPhone, function ($query) use ($digitsOnly) {
                $query->orWhere('phone', $digitsOnly);
            })
            ->first();
    }

    public function startConversation(array $validatedResult, ?string $name = null, ?int $accountId = null): WhatsAppConversation
    {
        if (($validatedResult['validation_status'] ?? null) === self::STATUS_INVALID) {
            throw new RuntimeException('This number is not available on WhatsApp.');
        }

        $conversation = $this->conversationService->findOrCreateConversationForPhone(
            (string) ($validatedResult['normalized_phone'] ?? ''),
            $name,
            [
                'validated_via' => $validatedResult['source'] ?? null,
                'validation_status' => $validatedResult['validation_status'] ?? self::STATUS_PENDING,
            ],
            $accountId
        );

        $contact = $conversation->contact;
        if ($contact) {
            $contact->update([
                'is_whatsapp' => $validatedResult['is_whatsapp'],
                'validation_status' => $validatedResult['validation_status'] ?? self::STATUS_PENDING,
                'validated_at' => $validatedResult['validated_at'] ?? now(),
                'validation_cache_until' => $validatedResult['validation_cache_until'] ?? now()->addHours(24),
                'last_chat_at' => now(),
            ]);
        }

        return $conversation;
    }

    public function saveContact(array $validatedResult, array $attributes = []): WhatsAppContact
    {
        $normalized = (string) ($validatedResult['normalized_phone'] ?? '');
        $contact = $this->checkExistingContact($normalized);

        if (!$contact) {
            $contact = new WhatsAppContact();
            $contact->phone = $normalized;
        }

        $contact->fill([
            'name' => $attributes['name'] ?? $contact->name,
            'country_code' => $attributes['country_code'] ?? $contact->country_code,
            'email' => $attributes['email'] ?? $contact->email,
            'company' => $attributes['company'] ?? $contact->company,
            'notes' => $attributes['notes'] ?? $contact->notes,
            'avatar' => $attributes['avatar'] ?? $contact->avatar,
            'is_whatsapp' => $validatedResult['is_whatsapp'],
            'validation_status' => $validatedResult['validation_status'] ?? self::STATUS_PENDING,
            'validated_at' => $validatedResult['validated_at'] ?? now(),
            'validation_cache_until' => $validatedResult['validation_cache_until'] ?? now()->addHours(24),
            'unsaved_contact' => false,
        ]);

        $contact->save();

        return $contact;
    }

    protected function callMetaContactsValidation(string $phoneNumberId, string $accessToken, string $normalizedPhone): array
    {
        $baseUrl = rtrim((string) config('whatsapp.base_url', 'https://graph.facebook.com'), '/');
        $graphVersion = trim((string) config('whatsapp.graph_version', 'v23.0'), '/');
        $endpoint = $baseUrl . '/' . $graphVersion . '/' . $phoneNumberId . '/contacts';

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->asJson()
            ->timeout(20)
            ->retry(1, 250)
            ->post($endpoint, [
                'messaging_product' => 'whatsapp',
                'blocking' => 'wait',
                'force_check' => true,
                'contacts' => [ltrim($normalizedPhone, '+')],
            ]);

        if (!$response->successful()) {
            return [
                'supported' => false,
                'is_valid' => null,
                'payload' => $response->json() ?? ['raw' => $response->body()],
            ];
        }

        $first = data_get($response->json(), 'contacts.0', []);
        $status = strtolower((string) data_get($first, 'status', ''));

        return [
            'supported' => true,
            'is_valid' => $status === 'valid',
            'payload' => $response->json() ?? [],
        ];
    }
}
