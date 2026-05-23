<?php

namespace App\Jobs;

use App\Models\WhatsAppAccount;
use App\Services\WhatsApp\WhatsAppBusinessProfileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SyncWhatsAppBusinessProfileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public array $backoff = [60, 180, 300, 600];

    public function __construct(public int $accountId) {}

    public function handle(WhatsAppBusinessProfileService $service): void
    {
        $account = WhatsAppAccount::query()->find($this->accountId);

        if (! $account) {
            return;
        }

        $service->syncBusinessProfile($account);
    }

    public function failed(Throwable $exception): void
    {
        $account = WhatsAppAccount::query()->find($this->accountId);

        if (! $account) {
            return;
        }

        $account->forceFill([
            'profile_sync_status' => WhatsAppBusinessProfileService::STATUS_SYNC_FAILED,
            'last_profile_sync' => now(),
        ])->save();
    }
}
