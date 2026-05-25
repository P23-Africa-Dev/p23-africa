<?php

namespace App\Services\WhatsApp;

use App\Models\Event;
use App\Models\Seat;
use App\Models\WhatsAppContact;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class EventAudienceService
{
    public const FILTER_SINGLE = 'single';
    public const FILTER_MULTIPLE = 'multiple';
    public const FILTER_PAST = 'past';
    public const FILTER_UPCOMING = 'upcoming';
    public const FILTER_ALL = 'all';

    public function __construct(
        protected WhatsAppNumberValidationService $numberValidationService,
    ) {}

    public function listEventsWithAudienceStats(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $events = $this->eventQuery($filters)
            ->withCount('seats')
            ->orderByDesc('event_date')
            ->orderByDesc('event_time')
            ->paginate($perPage)
            ->withQueryString();

        $events->getCollection()->transform(function (Event $event) {
            $validCount = $this->countValidAudienceForEvent($event);
            $eventDateTime = Carbon::parse((string) $event->event_date . ' ' . (string) $event->event_time);

            $event->setAttribute('whatsapp_valid_count', $validCount);
            $event->setAttribute('is_upcoming', $eventDateTime->isFuture());

            return $event;
        });

        return $events;
    }

    public function extractValidatedAudience(array $filters = []): Collection
    {
        if (!$this->hasSeatPhoneColumn()) {
            return collect();
        }

        $eventIds = $this->eventQuery($filters)->pluck('id')->all();

        if (empty($eventIds)) {
            return collect();
        }

        $seats = Seat::query()
            ->with('event:id,title,event_date,event_time,venue')
            ->whereIn('event_id', $eventIds)
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->orderByDesc('created_at')
            ->get();

        $audienceByPhone = [];

        foreach ($seats as $seat) {
            $rawPhone = trim((string) $seat->phone);
            if ($rawPhone === '') {
                continue;
            }

            $normalizedPhone = $this->numberValidationService->normalizeNumber($rawPhone);
            if ($normalizedPhone === '') {
                continue;
            }

            if (isset($audienceByPhone[$normalizedPhone])) {
                continue;
            }

            $contact = $this->numberValidationService->checkExistingContact($normalizedPhone);
            if ($this->isOptedOut($contact)) {
                continue;
            }

            $validation = $this->numberValidationService->validateWhatsAppNumber($normalizedPhone, null, false);
            if (($validation['validation_status'] ?? null) !== WhatsAppNumberValidationService::STATUS_VALID) {
                $this->persistValidationCache($contact, $seat, $normalizedPhone, $validation);
                continue;
            }

            $contact = $this->persistValidationCache($contact, $seat, $normalizedPhone, $validation);

            $audienceByPhone[$normalizedPhone] = [
                'seat_id' => $seat->id,
                'event_id' => $seat->event_id,
                'event_name' => $seat->event?->title,
                'event_date' => $seat->event?->event_date,
                'booking_name' => $seat->name,
                'name' => $seat->name,
                'phone' => $normalizedPhone,
                'email' => $seat->email,
                'seat_code' => $seat->seat_code,
                'booking_date' => optional($seat->created_at)->toDateString(),
                'company' => $seat->organization,
                'location' => $seat->location ?: $seat->event?->venue,
                'contact_id' => $contact?->id,
                'is_whatsapp' => true,
                'validated_at' => optional($contact?->validated_at)->toIso8601String(),
            ];
        }

        return collect(array_values($audienceByPhone));
    }

    public function countValidAudienceForEvent(Event $event): int
    {
        if (!$this->hasSeatPhoneColumn()) {
            return 0;
        }

        $seats = Seat::query()
            ->where('event_id', $event->id)
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->orderByDesc('created_at')
            ->get(['id', 'name', 'email', 'phone', 'seat_code', 'created_at', 'event_id']);

        $phones = [];
        $valid = 0;

        foreach ($seats as $seat) {
            $normalizedPhone = $this->numberValidationService->normalizeNumber((string) $seat->phone);
            if ($normalizedPhone === '' || isset($phones[$normalizedPhone])) {
                continue;
            }

            $phones[$normalizedPhone] = true;

            $contact = $this->numberValidationService->checkExistingContact($normalizedPhone);
            if ($this->isOptedOut($contact)) {
                continue;
            }

            if (($contact?->validation_status ?? null) === WhatsAppNumberValidationService::STATUS_VALID
                && ($contact?->validation_cache_until?->isFuture() ?? false)
            ) {
                $valid++;
            }
        }

        return $valid;
    }

    public function eventQuery(array $filters = [])
    {
        $filterType = $this->normalizeFilterType((string) ($filters['event_filter'] ?? self::FILTER_UPCOMING));
        $eventId = (int) ($filters['event_id'] ?? 0);
        $eventIds = array_values(array_filter(array_map('intval', (array) ($filters['event_ids'] ?? []))));

        $query = Event::query()->select(['id', 'title', 'event_date', 'event_time', 'venue']);

        if ($filterType === self::FILTER_SINGLE && $eventId > 0) {
            return $query->whereKey($eventId);
        }

        if ($filterType === self::FILTER_MULTIPLE && !empty($eventIds)) {
            return $query->whereIn('id', $eventIds);
        }

        if ($filterType === self::FILTER_PAST) {
            return $query->whereDate('event_date', '<', now()->toDateString());
        }

        if ($filterType === self::FILTER_UPCOMING) {
            return $query->whereDate('event_date', '>=', now()->toDateString());
        }

        return $query;
    }

    public function normalizeFilterType(string $filterType): string
    {
        $normalized = strtolower(trim($filterType));

        if (in_array($normalized, [self::FILTER_SINGLE, self::FILTER_MULTIPLE, self::FILTER_PAST, self::FILTER_UPCOMING, self::FILTER_ALL], true)) {
            return $normalized;
        }

        return self::FILTER_UPCOMING;
    }

    protected function persistValidationCache(?WhatsAppContact $contact, Seat $seat, string $normalizedPhone, array $validation): ?WhatsAppContact
    {
        $target = $contact;

        if (!$target) {
            $target = new WhatsAppContact();
            $target->phone = $normalizedPhone;
            $target->unsaved_contact = true;
        }

        $target->name = $seat->name ?: $target->name;
        $target->email = $seat->email ?: $target->email;
        $target->company = $seat->organization ?: $target->company;
        $target->is_whatsapp = $validation['is_whatsapp'] ?? false;
        $target->validation_status = $validation['validation_status'] ?? WhatsAppNumberValidationService::STATUS_PENDING;
        $target->validated_at = $validation['validated_at'] ?? now();
        $target->validation_cache_until = $validation['validation_cache_until'] ?? now()->addHours(24);

        $metadata = is_array($target->metadata) ? $target->metadata : [];
        $metadata['event_last_seen'] = [
            'event_id' => $seat->event_id,
            'seat_id' => $seat->id,
            'seen_at' => now()->toIso8601String(),
        ];
        $target->metadata = $metadata;

        $target->save();

        return $target->fresh();
    }

    protected function isOptedOut(?WhatsAppContact $contact): bool
    {
        if (!$contact) {
            return false;
        }

        $metadata = is_array($contact->metadata) ? $contact->metadata : [];

        $flags = [
            (bool) ($metadata['opt_out'] ?? false),
            (bool) ($metadata['opted_out'] ?? false),
            (bool) ($metadata['do_not_contact'] ?? false),
            (bool) ($metadata['unsubscribe'] ?? false),
            (bool) ($metadata['unsubscribed'] ?? false),
            (bool) ($metadata['blocked'] ?? false),
        ];

        return in_array(true, $flags, true);
    }

    protected function hasSeatPhoneColumn(): bool
    {
        return Schema::hasTable('seats') && Schema::hasColumn('seats', 'phone');
    }
}
