<?php

namespace App\Services\WhatsApp;

use App\Jobs\SendEventBroadcastJob;
use App\Models\EventWhatsAppCampaign;
use App\Models\EventWhatsAppRecipient;
use App\Models\WhatsAppContact;
use App\Models\WhatsAppTemplate;
use App\Services\AccessAuditService;
use App\Services\LegacyContextService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class EventWhatsAppService
{
    protected const CHUNK_SIZE = 75;

    public function __construct(
        protected EventAudienceService $audienceService,
        protected WhatsAppNumberValidationService $numberValidationService,
        protected TemplateRendererService $templateRendererService,
        protected MetaTemplateParameterService $metaTemplateParameterService,
        protected LegacyContextService $legacyContextService,
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
            $accountId = Arr::get($payload, 'account_id');
            $accountId = $accountId !== null ? (int) $accountId : null;

            $filters = [
                'event_filter' => Arr::get($payload, 'event_filter'),
                'event_id' => (int) Arr::get($payload, 'event_id'),
                'event_ids' => array_values(array_filter(array_map('intval', (array) Arr::get($payload, 'event_ids', [])))),
            ];

            $campaign = EventWhatsAppCampaign::query()->create([
                'event_id' => $filters['event_filter'] === EventAudienceService::FILTER_SINGLE ? ($filters['event_id'] ?: null) : null,
                'template_id' => $template->id,
                'account_id' => $accountId,
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
                SendEventBroadcastJob::dispatch($campaign->id, $recipientIdChunk, $accountId)->onQueue('whatsapp');
            }

            return $campaign->fresh(['template']);
        });

        return $campaign;
    }

    public function previewAudienceCount(array $payload): array
    {
        $sourceType = strtolower((string) Arr::get($payload, 'source_type', 'event_contacts'));

        $audience = match ($sourceType) {
            'saved_contacts' => $this->savedContactsAudience($payload),
            'manual_contacts' => $this->manualAudience($payload),
            default => $this->eventAudience($payload),
        };

        $eventCount = $sourceType === 'event_contacts'
            ? $audience->pluck('variables.event_title')->filter()->unique()->count()
            : 0;

        $selectedCount = $sourceType === 'saved_contacts'
            ? count(array_values(array_filter(array_map('intval', (array) Arr::get($payload, 'saved_contact_ids', [])))))
            : null;

        return [
            'total' => $audience->count(),
            'source_type' => $sourceType,
            'event_count' => $eventCount,
            'selected_count' => $selectedCount,
        ];
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

            if ($this->metaTemplateParameterService->supportsTemplate($template)) {
                return [
                    'name' => Arr::get($row, 'name'),
                    'phone' => Arr::get($row, 'phone'),
                    'event_name' => Arr::get($variables, 'event_title', Arr::get($variables, 'event_name')),
                    'message_preview' => nl2br(e($this->metaTemplateParameterService->buildPreview($template, $variables))),
                ];
            }

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
                    'event_title' => Arr::get($row, 'event_name'),
                    'event_name' => Arr::get($row, 'event_name'),
                    'event_date' => Arr::get($row, 'event_date') ? date('M d, Y', strtotime((string) Arr::get($row, 'event_date'))) : '',
                    'event_time' => Arr::get($row, 'event_time', ''),
                    'event_location' => Arr::get($row, 'location'),
                    'event_meeting_link' => Arr::get($row, 'event_link', Arr::get($row, 'event_page_url', '')),
                    'event_registration_link' => Arr::get($row, 'event_page_url', Arr::get($row, 'event_link', '')),
                    'event_slug' => Arr::get($row, 'event_slug', ''),
                    'event_page_url' => Arr::get($row, 'event_page_url', ''),
                    'location' => Arr::get($row, 'location'),
                    'event_link' => Arr::get($row, 'event_link', ''),
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
            ->where('phone', '!=', '');

        if (!empty($ids)) {
            $query->whereIn('id', $ids)
                ->where(function ($statusQuery) {
                    $statusQuery->whereNull('validation_status')
                        ->orWhere('validation_status', '!=', WhatsAppNumberValidationService::STATUS_INVALID);
                });
        } else {
            $query->where(function ($statusQuery) {
                $statusQuery->where(function ($validQuery) {
                    $validQuery->where('validation_status', WhatsAppNumberValidationService::STATUS_VALID)
                        ->where(function ($cacheQuery) {
                            $cacheQuery->whereNull('validation_cache_until')
                                ->orWhere('validation_cache_until', '>=', now());
                        });
                })->orWhereIn('validation_status', [
                    WhatsAppNumberValidationService::STATUS_PENDING,
                    WhatsAppNumberValidationService::STATUS_FAILED,
                ])->orWhereNull('validation_status');
            });
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
                    'event_title' => '',
                    'event_name' => '',
                    'event_date' => '',
                    'event_time' => '',
                    'event_location' => '',
                    'event_meeting_link' => '',
                    'event_registration_link' => '',
                    'event_slug' => '',
                    'event_page_url' => '',
                    'location' => '',
                    'event_link' => '',
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

            if (($validation['validation_status'] ?? null) === WhatsAppNumberValidationService::STATUS_INVALID) {
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
                    'event_title' => '',
                    'event_name' => '',
                    'event_date' => '',
                    'event_time' => '',
                    'event_location' => '',
                    'event_meeting_link' => '',
                    'event_registration_link' => '',
                    'event_slug' => '',
                    'event_page_url' => '',
                    'location' => '',
                    'event_link' => '',
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
            'Service-level event broadcast action blocked by legacy access policy.'
        );

        throw new RuntimeException('Forbidden WhatsApp access.');
    }
}
