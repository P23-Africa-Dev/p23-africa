<?php

namespace App\Services\WhatsApp;

use App\Jobs\SendEventBroadcastJob;
use App\Models\EventWhatsAppCampaign;
use App\Models\EventWhatsAppRecipient;
use App\Models\WhatsAppContact;
use App\Models\WhatsAppTemplate;
use App\Services\AccessAuditService;
use App\Services\LegacyWhatsAppAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class EventWhatsAppService
{
    protected const CHUNK_SIZE = 75;

    public function __construct(
        protected EventAudienceService $audienceService,
        protected WhatsAppNumberValidationService $numberValidationService,
        protected TemplateRendererService $templateRendererService,
        protected LegacyWhatsAppAccessService $legacyWhatsAppAccessService,
        protected AccessAuditService $accessAuditService,
    ) {}

    public function queueCampaign(array $payload): EventWhatsAppCampaign
    {
        $this->assertInteractiveAccess();

        $template = WhatsAppTemplate::query()->find((int) Arr::get($payload, 'template_id'));
        if (!$template) {
            throw new RuntimeException('Selected template was not found.');
        }

        $sourceType = strtolower((string) Arr::get($payload, 'source_type', 'event_contacts'));

        $audience = match ($sourceType) {
            'saved_contacts' => $this->savedContactsAudience($payload),
            'manual_contacts' => $this->manualAudience($payload),
            default => $this->eventAudience($payload),
        };

        if ($audience->isEmpty()) {
            throw new RuntimeException('No valid WhatsApp audience found for this broadcast.');
        }

        $campaign = DB::transaction(function () use ($payload, $template, $audience, $sourceType) {
            $filters = [
                'event_filter' => Arr::get($payload, 'event_filter'),
                'event_id' => (int) Arr::get($payload, 'event_id'),
                'event_ids' => array_values(array_filter(array_map('intval', (array) Arr::get($payload, 'event_ids', [])))),
            ];

            $campaign = EventWhatsAppCampaign::query()->create([
                'event_id' => $filters['event_filter'] === EventAudienceService::FILTER_SINGLE ? ($filters['event_id'] ?: null) : null,
                'template_id' => $template->id,
                'total_contacts' => $audience->count(),
                'valid_contacts' => $audience->count(),
                'sent' => 0,
                'failed' => 0,
                'status' => EventWhatsAppCampaign::STATUS_QUEUED,
                'source_type' => $sourceType,
                'audience_filters' => $filters,
                'queued_at' => now(),
            ]);

            $recipientIds = [];

            foreach ($audience->chunk(200) as $chunk) {
                $created = $campaign->recipients()->createMany($chunk->map(function (array $row) {
                    return [
                        'event_registration_id' => Arr::get($row, 'event_registration_id'),
                        'contact_id' => Arr::get($row, 'contact_id'),
                        'phone' => Arr::get($row, 'phone'),
                        'status' => EventWhatsAppRecipient::STATUS_QUEUED,
                        'variables' => Arr::get($row, 'variables', []),
                    ];
                })->values()->all());

                $recipientIds = array_merge($recipientIds, $created->pluck('id')->all());
            }

            foreach (array_chunk($recipientIds, self::CHUNK_SIZE) as $recipientIdChunk) {
                SendEventBroadcastJob::dispatch($campaign->id, $recipientIdChunk)->onQueue('whatsapp');
            }

            return $campaign->fresh(['template']);
        });

        return $campaign;
    }

    public function previewTemplateForAudience(array $payload, int $limit = 5): array
    {
        $this->assertInteractiveAccess();

        $template = WhatsAppTemplate::query()->find((int) Arr::get($payload, 'template_id'));
        if (!$template) {
            throw new RuntimeException('Selected template was not found.');
        }

        $sourceType = strtolower((string) Arr::get($payload, 'source_type', 'event_contacts'));

        $audience = match ($sourceType) {
            'saved_contacts' => $this->savedContactsAudience($payload),
            'manual_contacts' => $this->manualAudience($payload),
            default => $this->eventAudience($payload),
        };

        $samples = $audience->take(max(1, $limit))->map(function (array $row) use ($template) {
            $variables = Arr::get($row, 'variables', []);
            $sourceContent = ($template->template_format ?? TemplateRendererService::FORMAT_JSON) === TemplateRendererService::FORMAT_JSON
                ? ($template->template_json ?? [])
                : (string) ($template->rendered_content ?: data_get($template->editor_metadata, 'source_content', ''));

            $rendered = $this->templateRendererService->render(
                (string) ($template->template_format ?: TemplateRendererService::FORMAT_JSON),
                $sourceContent,
                $variables,
            );

            return [
                'name' => Arr::get($row, 'name'),
                'phone' => Arr::get($row, 'phone'),
                'event_name' => Arr::get($variables, 'event_name'),
                'message_preview' => strip_tags((string) data_get($rendered, 'preview_content', '')),
            ];
        })->values()->all();

        return [
            'total' => $audience->count(),
            'samples' => $samples,
        ];
    }

    protected function eventAudience(array $payload): Collection
    {
        $rows = $this->audienceService->extractValidatedAudience([
            'event_filter' => Arr::get($payload, 'event_filter', EventAudienceService::FILTER_UPCOMING),
            'event_id' => Arr::get($payload, 'event_id'),
            'event_ids' => Arr::get($payload, 'event_ids', []),
        ]);

        return $rows->map(function (array $row) {
            return [
                'event_registration_id' => Arr::get($row, 'seat_id'),
                'contact_id' => Arr::get($row, 'contact_id'),
                'phone' => Arr::get($row, 'phone'),
                'name' => Arr::get($row, 'name'),
                'variables' => [
                    'name' => $this->resolveName(Arr::get($row, 'name'), null, null),
                    'event_name' => Arr::get($row, 'event_name'),
                    'event_date' => Arr::get($row, 'event_date') ? date('M d, Y', strtotime((string) Arr::get($row, 'event_date'))) : '',
                    'location' => Arr::get($row, 'location'),
                    'seat_code' => Arr::get($row, 'seat_code'),
                    'email' => Arr::get($row, 'email'),
                    'phone' => Arr::get($row, 'phone'),
                    'booking_date' => Arr::get($row, 'booking_date'),
                    'company' => Arr::get($row, 'company'),
                ],
            ];
        })->values();
    }

    protected function savedContactsAudience(array $payload): Collection
    {
        $ids = array_values(array_filter(array_map('intval', (array) Arr::get($payload, 'saved_contact_ids', []))));

        $query = WhatsAppContact::query()
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->where('validation_status', WhatsAppNumberValidationService::STATUS_VALID)
            ->where(function ($sub) {
                $sub->whereNull('validation_cache_until')
                    ->orWhere('validation_cache_until', '>=', now());
            });

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        $seen = [];

        return $query->orderByDesc('last_chat_at')->get()->filter(function (WhatsAppContact $contact) {
            return !$this->isOptedOut($contact);
        })->map(function (WhatsAppContact $contact) use (&$seen) {
            $normalizedPhone = $this->numberValidationService->normalizeNumber((string) $contact->phone);
            if ($normalizedPhone === '' || isset($seen[$normalizedPhone])) {
                return null;
            }

            $seen[$normalizedPhone] = true;

            return [
                'event_registration_id' => null,
                'contact_id' => $contact->id,
                'phone' => $normalizedPhone,
                'name' => $contact->name,
                'variables' => [
                    'name' => $this->resolveName(null, data_get($contact->metadata, 'whatsapp_profile_name'), $contact->name),
                    'event_name' => '',
                    'event_date' => '',
                    'location' => '',
                    'seat_code' => '',
                    'email' => $contact->email,
                    'phone' => $normalizedPhone,
                    'booking_date' => '',
                    'company' => $contact->company,
                ],
            ];
        })->filter()->values();
    }

    protected function manualAudience(array $payload): Collection
    {
        $manualRows = preg_split('/\r\n|\r|\n/', (string) Arr::get($payload, 'manual_contacts', '')) ?: [];

        $audience = [];
        $seen = [];

        foreach ($manualRows as $line) {
            $row = trim($line);
            if ($row === '') {
                continue;
            }

            $parts = array_map('trim', explode(',', $row, 2));
            $name = count($parts) > 1 ? $parts[0] : null;
            $phone = count($parts) > 1 ? $parts[1] : $parts[0];

            $validation = $this->numberValidationService->validateWhatsAppNumber((string) $phone, null, false);
            $normalizedPhone = (string) ($validation['normalized_phone'] ?? '');

            if ($normalizedPhone === '' || isset($seen[$normalizedPhone])) {
                continue;
            }

            if (($validation['validation_status'] ?? null) !== WhatsAppNumberValidationService::STATUS_VALID) {
                continue;
            }

            $seen[$normalizedPhone] = true;

            $contact = $this->numberValidationService->saveContact($validation, [
                'name' => $name,
                'unsaved_contact' => false,
            ]);

            if ($this->isOptedOut($contact)) {
                continue;
            }

            $audience[] = [
                'event_registration_id' => null,
                'contact_id' => $contact->id,
                'phone' => $normalizedPhone,
                'name' => $contact->name,
                'variables' => [
                    'name' => $this->resolveName(null, data_get($contact->metadata, 'whatsapp_profile_name'), $contact->name),
                    'event_name' => '',
                    'event_date' => '',
                    'location' => '',
                    'seat_code' => '',
                    'email' => $contact->email,
                    'phone' => $normalizedPhone,
                    'booking_date' => '',
                    'company' => $contact->company,
                ],
            ];
        }

        return collect($audience);
    }

    protected function resolveName(?string $bookingName, ?string $whatsAppProfileName, ?string $contactName): string
    {
        if (trim((string) $bookingName) !== '') {
            return trim((string) $bookingName);
        }

        if (trim((string) $whatsAppProfileName) !== '') {
            return trim((string) $whatsAppProfileName);
        }

        if (trim((string) $contactName) !== '') {
            return trim((string) $contactName);
        }

        return 'there';
    }

    protected function isOptedOut(?WhatsAppContact $contact): bool
    {
        if (!$contact) {
            return false;
        }

        $metadata = is_array($contact->metadata) ? $contact->metadata : [];

        return (bool) ($metadata['opt_out'] ?? false)
            || (bool) ($metadata['opted_out'] ?? false)
            || (bool) ($metadata['do_not_contact'] ?? false)
            || (bool) ($metadata['unsubscribe'] ?? false)
            || (bool) ($metadata['unsubscribed'] ?? false)
            || (bool) ($metadata['blocked'] ?? false);
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
            'Service-level event broadcast action blocked by legacy access policy.'
        );

        throw new RuntimeException('Forbidden WhatsApp access.');
    }
}
