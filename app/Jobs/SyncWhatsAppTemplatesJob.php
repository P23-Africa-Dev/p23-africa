<?php

namespace App\Jobs;

use App\Services\WhatsApp\MetaTemplateSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncWhatsAppTemplatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public array $backoff = [60, 180, 300, 600];

    public function __construct(public int $accountId) {}

    public function handle(MetaTemplateSyncService $syncService): void
    {
        $syncService->syncTemplates(
            $this->accountId,
            (bool) config('whatsapp.template_sync.only_approved', true),
            false,
            (int) config('whatsapp.template_sync.page_limit', 100),
        );
    }

    public function failed(Throwable $exception): void
    {
        Log::error('WhatsApp template sync job failed', [
            'account_id' => $this->accountId,
            'error' => $exception->getMessage(),
        ]);
    }
}
