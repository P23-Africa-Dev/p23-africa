<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppTemplate;
use App\Services\WhatsApp\TemplateService;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Submits a new WhatsApp template to Meta for approval and persists a local
 * record so it can be tracked before the webhook confirms the status change.
 */
class MetaTemplateSubmissionService
{
    private const META_TEMPLATE_NAME_MAX = 512;

    public function __construct(
        protected WhatsAppService $whatsAppService,
        protected TemplateService $templateService,
    ) {}

    /**
     * @param  array<int, array<string,mixed>>  $components  Meta-format components array
     * @return array{id: string|null, status: string, local_id: int, effective_name: string, mode: string, base_name: string}
     */
    public function submitTemplate(
        string $name,
        string $category,
        string $language,
        array $components,
    ): array {
        return $this->submitTemplateInternal(
            strtolower(trim($name)),
            strtolower(trim($name)),
            strtoupper(trim($category)),
            strtolower(trim($language)),
            $components,
            'create'
        );
    }

    /**
     * Meta does not support in-place editing for approved templates.
     * Updating is implemented as submitting a new revision name to Meta.
     *
     * @param  array<int, array<string,mixed>>  $components
     * @return array{id: string|null, status: string, local_id: int, effective_name: string, mode: string, base_name: string}
     */
    public function submitTemplateUpdate(
        string $baseName,
        string $category,
        string $language,
        array $components,
    ): array {
        $normalizedBase = strtolower(trim($baseName));
        $normalizedLanguage = strtolower(trim($language));
        $revisionName = $this->generateRevisionName($normalizedBase, $normalizedLanguage);

        return $this->submitTemplateInternal(
            $normalizedBase,
            $revisionName,
            strtoupper(trim($category)),
            $normalizedLanguage,
            $components,
            'update'
        );
    }

    /**
     * @param  array<int, array<string,mixed>>  $components
     * @return array{id: string|null, status: string, local_id: int, effective_name: string, mode: string, base_name: string}
     */
    protected function submitTemplateInternal(
        string $baseName,
        string $effectiveName,
        string $category,
        string $language,
        array $components,
        string $mode,
    ): array {
        $account = $this->whatsAppService->resolveAccount();

        $wabaId = (string) ($account['waba_id'] ?? '');
        if ($wabaId === '') {
            throw new RuntimeException('WhatsApp Business Account ID (WABA ID) is not configured.');
        }

        $accessToken = (string) ($account['access_token'] ?? '');
        $baseUrl     = rtrim((string) config('whatsapp.base_url', 'https://graph.facebook.com'), '/');
        $graphVersion = trim((string) config('whatsapp.graph_version', 'v23.0'), '/');

        $endpoint = $baseUrl . '/' . $graphVersion . '/' . $wabaId . '/message_templates';

        $metaPayload = [
            'name'       => $effectiveName,
            'category'   => $category,
            'language'   => $language,
            'components' => $components,
        ];

        $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
            ->acceptJson()
            ->asJson()
            ->timeout(20)
            ->post($endpoint, $metaPayload);

        if (! $response->successful()) {
            $json = $response->json();
            $detail = data_get($json, 'error.error_user_msg')
                ?? data_get($json, 'error.message')
                ?? $response->body();

            Log::warning('Meta template submission failed', [
                'name'     => $effectiveName,
                'base_name' => $baseName,
                'mode' => $mode,
                'status'   => $response->status(),
                'detail'   => $detail,
                'fbtrace'  => data_get($json, 'error.fbtrace_id'),
            ]);

            throw new RuntimeException('Meta rejected the template: ' . $detail);
        }

        $json     = $response->json() ?? [];
        $metaId   = (string) ($json['id'] ?? '');
        $rawStatus = strtoupper((string) ($json['status'] ?? 'PENDING'));

        $localStatus = match ($rawStatus) {
            'APPROVED'         => WhatsAppTemplate::STATUS_APPROVED,
            'REJECTED' => WhatsAppTemplate::STATUS_REJECTED,
            'DISABLED' => WhatsAppTemplate::STATUS_DISABLED,
            'PAUSED' => WhatsAppTemplate::STATUS_PAUSED,
            default            => WhatsAppTemplate::STATUS_PENDING,
        };

        // Persist locally so the admin can see it immediately.
        $local = $this->templateService->createOrUpdate([
            'name'             => $effectiveName,
            'category'         => strtolower($category),
            'language'         => $language,
            'status'           => $localStatus,
            'template_format'  => TemplateRendererService::FORMAT_JSON,
            'template_json'    => array_merge($metaPayload, $json),
            'rendered_content' => null,
            'preview_content'  => null,
            'editor_metadata'  => [
                'meta_id'      => $metaId,
                'meta_status'  => $rawStatus,
                'submitted_at' => now()->toIso8601String(),
                'source'       => $mode === 'update' ? 'ui_update_submission' : 'ui_submission',
                'base_name'    => $baseName,
                'effective_name' => $effectiveName,
                'mode'         => $mode,
            ],
        ]);

        Log::info('Meta template submitted', [
            'name'     => $effectiveName,
            'base_name' => $baseName,
            'mode' => $mode,
            'meta_id'  => $metaId,
            'status'   => $rawStatus,
            'local_id' => $local->id,
        ]);

        return [
            'id'       => $metaId ?: null,
            'status'   => $rawStatus,
            'local_id' => $local->id,
            'effective_name' => $effectiveName,
            'mode' => $mode,
            'base_name' => $baseName,
        ];
    }

    protected function generateRevisionName(string $baseName, string $language): string
    {
        $base = preg_replace('/[^a-z0-9_]/', '_', strtolower(trim($baseName))) ?? '';
        $base = trim($base, '_');
        if ($base === '') {
            $base = 'template';
        }

        $names = WhatsAppTemplate::query()
            ->where('language', strtolower(trim($language)))
            ->where(function ($query) use ($base) {
                $query->where('name', $base)
                    ->orWhere('name', 'like', $base . '_v%');
            })
            ->pluck('name')
            ->map(fn($name) => strtolower((string) $name))
            ->all();

        $maxVersion = in_array($base, $names, true) ? 1 : 0;

        foreach ($names as $name) {
            if (preg_match('/^' . preg_quote($base, '/') . '_v(\d+)$/', $name, $matches)) {
                $maxVersion = max($maxVersion, (int) $matches[1]);
            }
        }

        $nextVersion = max(2, $maxVersion + 1);
        $suffix = '_v' . $nextVersion;
        $maxBaseLength = max(1, self::META_TEMPLATE_NAME_MAX - strlen($suffix));
        $trimmedBase = substr($base, 0, $maxBaseLength);

        return $trimmedBase . $suffix;
    }
}
