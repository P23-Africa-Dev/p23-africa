<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppAccount;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WhatsAppBusinessProfileService
{
    public const STATUS_PENDING_APPROVAL = 'pending_approval';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_SYNC_FAILED = 'sync_failed';
    public const STATUS_WAITING_REVIEW = 'waiting_review';

    public function syncBusinessProfile(?WhatsAppAccount $account = null): array
    {
        $account = $this->resolveAccount($account);

        $this->updateDisplayName($account);

        $payload = array_filter(
            array_merge(
                $this->updateBusinessDescription($account),
                $this->updateProfileImage($account)
            ),
            static fn($value) => $value !== null && $value !== '' && $value !== []
        );

        $response = $this->pushProfileToMeta($account, $payload);
        $this->refreshMetaStatus($account);

        return $response;
    }

    public function updateDisplayName(WhatsAppAccount $account): array
    {
        $account->forceFill([
            'display_name_status' => self::STATUS_PENDING_APPROVAL,
            'profile_sync_status' => self::STATUS_PENDING_APPROVAL,
        ])->save();

        return [
            'display_name' => trim((string) $account->business_name),
        ];
    }

    public function updateBusinessDescription(WhatsAppAccount $account): array
    {
        $about = trim((string) $account->business_name);

        if ($about === '') {
            $about = trim((string) config('app.name'));
        }

        $email = trim((string) config('mail.from.address'));
        $website = trim((string) config('app.url'));

        return array_filter([
            'about' => $about !== '' ? $about : null,
            'email' => $email !== '' ? $email : null,
            'websites' => $website !== '' ? [$website] : null,
        ], static fn($value) => $value !== null && $value !== '' && $value !== []);
    }

    public function updateProfileImage(WhatsAppAccount $account): array
    {
        $profileImageUrl = trim((string) $account->profile_image_url);

        if ($profileImageUrl === '') {
            return [];
        }

        return [
            'profile_picture_url' => $profileImageUrl,
        ];
    }

    public function pushProfileToMeta(WhatsAppAccount $account, array $profilePayload): array
    {
        $response = $this->sendProfileRequest($account, $profilePayload);

        if (! $response->successful()) {
            $account->forceFill([
                'profile_sync_status' => self::STATUS_SYNC_FAILED,
                'last_profile_sync' => now(),
            ])->save();

            throw new RuntimeException(
                'WhatsApp business profile sync failed with status ' . $response->status() . ': ' . $response->body()
            );
        }

        $account->forceFill([
            'profile_sync_status' => self::STATUS_PENDING_APPROVAL,
            'last_profile_sync' => now(),
            'meta_profile_id' => (string) data_get($response->json(), 'id', $account->meta_profile_id),
        ])->save();

        return $response->json() ?? [];
    }

    public function refreshMetaStatus(?WhatsAppAccount $account = null): array
    {
        $account = $this->resolveAccount($account);
        $response = $this->sendStatusRequest($account);

        if (! $response->successful()) {
            $account->forceFill([
                'profile_sync_status' => self::STATUS_SYNC_FAILED,
                'last_profile_sync' => now(),
            ])->save();

            throw new RuntimeException(
                'Unable to refresh WhatsApp business profile status with status ' . $response->status() . ': ' . $response->body()
            );
        }

        $data = $response->json() ?? [];
        $metaStatus = strtoupper((string) data_get($data, 'name_status', ''));
        $normalizedStatus = $this->normalizeDisplayNameStatus($metaStatus);

        $account->forceFill([
            'display_name_status' => $normalizedStatus,
            'profile_sync_status' => $this->mapProfileSyncStatus($normalizedStatus),
            'last_profile_sync' => now(),
            'meta_profile_id' => (string) data_get($data, 'id', $account->meta_profile_id),
        ])->save();

        return $data;
    }

    protected function resolveAccount(?WhatsAppAccount $account = null): WhatsAppAccount
    {
        if ($account) {
            return $account;
        }

        return WhatsAppAccount::query()->latest('id')->firstOrFail();
    }

    protected function sendProfileRequest(WhatsAppAccount $account, array $profilePayload): Response
    {
        return Http::withToken((string) $account->access_token)
            ->acceptJson()
            ->asJson()
            ->timeout(30)
            ->retry(2, 250)
            ->post($this->profileEndpoint((string) $account->phone_number_id), $profilePayload);
    }

    protected function sendStatusRequest(WhatsAppAccount $account): Response
    {
        return Http::withToken((string) $account->access_token)
            ->acceptJson()
            ->timeout(20)
            ->retry(2, 250)
            ->get($this->phoneNumberEndpoint((string) $account->phone_number_id), [
                'fields' => 'id,verified_name,name_status,quality_rating,code_verification_status',
            ]);
    }

    protected function profileEndpoint(string $phoneNumberId): string
    {
        return $this->graphEndpoint($phoneNumberId . '/whatsapp_business_profile');
    }

    protected function phoneNumberEndpoint(string $phoneNumberId): string
    {
        return $this->graphEndpoint($phoneNumberId);
    }

    protected function graphEndpoint(string $path): string
    {
        $baseUrl = rtrim((string) config('whatsapp.base_url', 'https://graph.facebook.com'), '/');
        $graphVersion = trim((string) config('whatsapp.graph_version', 'v23.0'), '/');

        return $baseUrl . '/' . $graphVersion . '/' . ltrim($path, '/');
    }

    protected function normalizeDisplayNameStatus(string $status): string
    {
        return match ($status) {
            'APPROVED', 'AVAILABLE_WITHOUT_REVIEW' => self::STATUS_APPROVED,
            'REJECTED', 'DECLINED' => self::STATUS_REJECTED,
            'PENDING_REVIEW', 'UNDER_REVIEW', 'REVIEWING' => self::STATUS_WAITING_REVIEW,
            'NON_EXISTS' => self::STATUS_PENDING_APPROVAL,
            default => self::STATUS_PENDING_APPROVAL,
        };
    }

    protected function mapProfileSyncStatus(string $displayNameStatus): string
    {
        return match ($displayNameStatus) {
            self::STATUS_APPROVED => self::STATUS_APPROVED,
            self::STATUS_REJECTED => self::STATUS_REJECTED,
            self::STATUS_WAITING_REVIEW => self::STATUS_WAITING_REVIEW,
            default => self::STATUS_PENDING_APPROVAL,
        };
    }
}
