<?php

namespace App\Console\Commands;

use App\Services\WhatsApp\MetaTemplateSyncService;
use Illuminate\Console\Command;

class SyncWhatsAppTemplatesCommand extends Command
{
    protected $signature = 'whatsapp:sync-templates
        {--account-id= : Sync templates for a specific whatsapp_accounts id}
        {--only-approved=1 : Sync only approved templates (1/0)}
        {--dry-run : Fetch and process without writing to database}';

    protected $description = 'Sync WhatsApp templates from Meta Graph API into local whatsapp_templates table.';

    public function handle(MetaTemplateSyncService $syncService): int
    {
        $accountId = $this->option('account-id');
        $accountId = is_null($accountId) || $accountId === '' ? null : (int) $accountId;

        $onlyApproved = filter_var((string) $this->option('only-approved'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $onlyApproved = is_null($onlyApproved) ? true : $onlyApproved;

        $dryRun = (bool) $this->option('dry-run');

        try {
            $result = $syncService->syncTemplates($accountId, $onlyApproved, $dryRun);
        } catch (\Throwable $exception) {
            $this->error('Template sync failed: ' . $exception->getMessage());

            return self::FAILURE;
        }

        $this->info('WhatsApp template sync completed.');
        $this->line('Account ID: ' . ($accountId ?: 'auto-resolved active account'));
        $this->line('Only approved: ' . ($onlyApproved ? 'yes' : 'no'));
        $this->line('Dry run: ' . ($dryRun ? 'yes' : 'no'));
        $this->line('Pages: ' . $result['pages']);
        $this->line('Fetched: ' . $result['fetched']);
        $this->line('Approved found: ' . $result['approved_count']);
        $this->line('Upserted: ' . $result['upserted']);
        $this->line('Skipped: ' . $result['skipped']);
        $this->line('Failed: ' . $result['failed']);
        $this->line('Duration: ' . $result['duration_ms'] . 'ms');

        return self::SUCCESS;
    }
}
