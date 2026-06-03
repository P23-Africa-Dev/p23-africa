<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppTemplate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MetaTemplateSyncService
{
    public function __construct(
        protected WhatsAppService $whatsAppService,
        protected TemplateService $templateService,
    ) {}

    public function syncTemplates(?int $accountId = null, bool $onlyApproved = true, bool $dryRun = false, int $limit = 100): array
    {
        $limit = max(1, min($limit ?: (int) config('whatsapp.template_sync.page_limit', 100), 100));

        $startedAt = microtime(true);

        $result = [
            'account_id' => $accountId,
            'pages' => 0,
            'fetched' => 0,
            'approved_count' => 0,
            'upserted' => 0,
            'skipped' => 0,
            'failed' => 0,
            'dry_run' => $dryRun,
            'only_approved' => $onlyApproved,
            'duration_ms' => 0,
        ];

        $cursor = null;

        do {
            $result['pages']++;

            $page = $this->fetchPageWithContext($accountId, $cursor, $limit);
            $templates = (array) data_get($page, 'data', []);

            foreach ($templates as $templatePayload) {
                $result['fetched']++;

                try {
                    $attributes = $this->normalizeTemplateAttributes((array) $templatePayload);

                    if ($attributes['status'] === WhatsAppTemplate::STATUS_APPROVED) {
                        $result['approved_count']++;
                    }

                    if ($onlyApproved && $attributes['status'] !== WhatsAppTemplate::STATUS_APPROVED) {
                        $result['skipped']++;
                        continue;
                    }

                    if ($dryRun) {
                        $result['upserted']++;
                        continue;
                    }

                    $this->templateService->createOrUpdate($attributes);
                    $result['upserted']++;
                } catch (\Throwable $exception) {
                    $result['failed']++;

                    Log::warning('WhatsApp template sync item failed', [
                        'account_id' => $accountId,
                        'cursor' => $cursor,
                        'template_name' => data_get($templatePayload, 'name'),
                        'language' => data_get($templatePayload, 'language'),
                        'error' => $exception->getMessage(),
                    ]);
                }
            }

            $cursor = data_get($page, 'paging.cursors.after');
        } while (!blank($cursor));

        $result['duration_ms'] = (int) round((microtime(true) - $startedAt) * 1000);

        Log::info('WhatsApp template sync completed', Arr::except($result, ['dry_run', 'only_approved']) + [
            'dry_run' => $dryRun,
            'only_approved' => $onlyApproved,
        ]);

        return $result;
    }

    protected function fetchPageWithContext(?int $accountId, ?string $cursor, int $limit): array
    {
        try {
            return $this->whatsAppService->fetchMessageTemplatesPage($accountId, $cursor, $limit);
        } catch (RuntimeException $exception) {
            $statusCode = $this->extractStatusCodeFromException($exception);

            Log::error('WhatsApp template sync request failed', [
                'account_id' => $accountId,
                'cursor' => $cursor,
                'endpoint' => 'message_templates',
                'status_code' => $statusCode,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    protected function normalizeTemplateAttributes(array $template): array
    {
        $name = trim((string) data_get($template, 'name', ''));
        $language = trim((string) data_get($template, 'language', 'en'));

        if ($name === '') {
            throw new RuntimeException('Template payload is missing name.');
        }

        $rawStatus = (string) data_get($template, 'status', '');

        return [
            'name' => $name,
            'language' => $language === '' ? 'en' : $language,
            'category' => strtolower((string) data_get($template, 'category', 'utility')),
            'status' => $this->normalizeStatus($rawStatus),
            'template_format' => TemplateRendererService::FORMAT_JSON,
            'template_json' => $template,
            'rendered_content' => null,
            'preview_content' => null,
            'editor_metadata' => [
                'meta_status' => $rawStatus,
                'synced_from' => 'meta_graph',
                'synced_at' => now()->toIso8601String(),
            ],
        ];
    }

    protected function normalizeStatus(string $metaStatus): string
    {
        return match (strtoupper(trim($metaStatus))) {
            'APPROVED' => WhatsAppTemplate::STATUS_APPROVED,
            'REJECTED', 'DISABLED' => WhatsAppTemplate::STATUS_REJECTED,
            'PENDING', 'PENDING_DELETION', 'IN_APPEAL', 'PAUSED' => WhatsAppTemplate::STATUS_PENDING,
            'DRAFT' => WhatsAppTemplate::STATUS_DRAFT,
            default => WhatsAppTemplate::STATUS_DRAFT,
        };
    }

    protected function extractStatusCodeFromException(RuntimeException $exception): ?int
    {
        if (preg_match('/status\s+(\d{3})/i', $exception->getMessage(), $matches)) {
            return (int) $matches[1];
        }

        return null;
    }
}
