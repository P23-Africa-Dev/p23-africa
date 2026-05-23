<?php

namespace App\Services\WhatsApp;

use App\Models\User;
use App\Models\WhatsAppAccount;
use App\Models\WhatsAppSettingSecurity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class DeveloperPasscodeService
{
    private const DEFAULT_PASSCODE = 'WhatsAppPass@2026';
    private const DEFAULT_MASTER_PASSCODE = 'HappyDevReset401';
    private const UNLOCK_TTL_MINUTES = 15;
    private const SESSION_UNLOCKED_UNTIL = 'whatsapp_dev_unlocked_until';
    private const SESSION_UNLOCK_VERSION = 'whatsapp_dev_unlock_version';
    private const CACHE_UNLOCK_VERSION_KEY = 'whatsapp_dev_unlock_version';

    public function __construct(
        protected DeveloperSecurityAuditService $auditService,
    ) {}

    public function ensureSecurityRecord(): WhatsAppSettingSecurity
    {
        $record = WhatsAppSettingSecurity::query()->firstOrCreate([], [
            'developer_passcode' => Hash::make(self::DEFAULT_PASSCODE),
            'master_passcode_hash' => Hash::make(self::DEFAULT_MASTER_PASSCODE),
        ]);

        if (blank($record->master_passcode_hash)) {
            $record->update([
                'master_passcode_hash' => Hash::make(self::DEFAULT_MASTER_PASSCODE),
            ]);
        }

        return $record->refresh();
    }

    public function isDeveloperManager(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        if ($user->hasRole('super-admin') || $user->hasRole('developer')) {
            return true;
        }

        return $user->hasRole('admin') && is_null($user->organization_id);
    }

    public function verifyAndUnlock(Request $request, string $passcode): bool
    {
        $record = $this->ensureSecurityRecord();
        $user = $request->user();

        if (!Hash::check($passcode, (string) $record->developer_passcode)) {
            $this->log($request, 'unlock_attempt', 'failure');

            return false;
        }

        $expiresAt = now()->addMinutes(self::UNLOCK_TTL_MINUTES);
        $version = $this->currentUnlockVersion();

        $request->session()->put(self::SESSION_UNLOCKED_UNTIL, $expiresAt->toIso8601String());
        $request->session()->put(self::SESSION_UNLOCK_VERSION, $version);

        if ($user) {
            $this->log($request, 'unlock_attempt', 'success');
        }

        return true;
    }

    public function isUnlocked(Request $request): bool
    {
        $until = $request->session()->get(self::SESSION_UNLOCKED_UNTIL);
        $version = (string) $request->session()->get(self::SESSION_UNLOCK_VERSION, '');

        if (!$until || $version === '') {
            return false;
        }

        $validUntil = Carbon::parse((string) $until);

        if ($validUntil->isPast()) {
            $this->clearUnlock($request);

            return false;
        }

        if ($version !== $this->currentUnlockVersion()) {
            $this->clearUnlock($request);

            return false;
        }

        return true;
    }

    public function clearUnlock(Request $request): void
    {
        $request->session()->forget([
            self::SESSION_UNLOCKED_UNTIL,
            self::SESSION_UNLOCK_VERSION,
        ]);
    }

    public function forceRelockAllSessions(Request $request): void
    {
        $newVersion = (string) now()->timestamp;
        Cache::forever(self::CACHE_UNLOCK_VERSION_KEY, $newVersion);
        $this->clearUnlock($request);
        $this->log($request, 'force_relock', 'success');
    }

    public function updatePasscode(Request $request, string $newPasscode): void
    {
        $record = $this->ensureSecurityRecord();
        $record->update([
            'developer_passcode' => Hash::make($newPasscode),
        ]);

        $this->forceRelockAllSessions($request);
        $this->log($request, 'developer_passcode_update', 'success');
    }

    public function verifyMasterPasscode(Request $request, string $masterPasscode): bool
    {
        $record = $this->ensureSecurityRecord();

        $valid = Hash::check($masterPasscode, (string) $record->master_passcode_hash);

        $this->log(
            $request,
            'master_passcode_validation',
            $valid ? 'success' : 'failure'
        );

        return $valid;
    }

    public function updateMasterPasscode(Request $request, string $newMasterPasscode): void
    {
        $record = $this->ensureSecurityRecord();
        $record->update([
            'master_passcode_hash' => Hash::make($newMasterPasscode),
        ]);

        $this->forceRelockAllSessions($request);
        $this->log($request, 'master_passcode_update', 'success');
    }

    public function resetToDefaultPasscode(Request $request): void
    {
        $this->updatePasscode($request, self::DEFAULT_PASSCODE);
        $this->log($request, 'passcode_reset', 'success');
    }

    public function maskValue(?string $value, int $visibleTail = 4): string
    {
        $raw = trim((string) $value);

        if ($raw === '') {
            return '********';
        }

        $length = strlen($raw);

        if ($visibleTail <= 0) {
            return str_repeat('*', $length);
        }

        if ($length <= $visibleTail) {
            return str_repeat('*', $length);
        }

        return str_repeat('*', $length - $visibleTail) . substr($raw, -$visibleTail);
    }

    public function maskedSensitiveFields(?WhatsAppAccount $account): array
    {
        return [
            'phone_number_id' => $this->maskValue($account?->phone_number_id, 4),
            'business_account_id' => $this->maskValue($account?->waba_id, 4),
            'waba_id' => $this->maskValue($account?->waba_id, 0),
            'status' => $this->maskValue($account?->status, 0),
            'access_token' => $this->maskValue($account?->access_token, 0),
            'webhook_verify_token' => $this->maskValue($account?->webhook_verify_token, 0),
        ];
    }

    public function log(Request $request, string $action, string $status): void
    {
        $this->auditService->log($request, $action, $status);
    }

    private function currentUnlockVersion(): string
    {
        return (string) Cache::rememberForever(self::CACHE_UNLOCK_VERSION_KEY, static function () {
            return (string) now()->timestamp;
        });
    }
}
