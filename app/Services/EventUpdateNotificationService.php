<?php

namespace App\Services;

use App\Mail\EventUpdatedMail;
use App\Models\Event;
use App\Models\Seat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EventUpdateNotificationService
{
    /**
     * Only attendee-facing fields are tracked for change notifications.
     */
    private const TRACKED_FIELDS = [
        'title',
        'event_date',
        'event_time',
        'venue',
        'link',
        'description',
    ];

    /**
     * Build a normalized diff for tracked fields.
     *
     * @return array{changed_fields: array<int, string>, old_values: array<string, string>, new_values: array<string, string>}
     */
    public function detectChanges(Event $event, array $incomingData): array
    {
        $changedFields = [];
        $oldValues = [];
        $newValues = [];

        foreach (self::TRACKED_FIELDS as $field) {
            $hasIncomingValue = array_key_exists($field, $incomingData);

            if (!$hasIncomingValue) {
                continue;
            }

            $old = $this->normalizeForComparison($field, $event->{$field});
            $new = $this->normalizeForComparison($field, $incomingData[$field]);

            if ($old === $new) {
                continue;
            }

            $changedFields[] = $field;
            $oldValues[$field] = $this->formatForDisplay($field, $event->{$field});
            $newValues[$field] = $this->formatForDisplay($field, $incomingData[$field]);
        }

        return [
            'changed_fields' => $changedFields,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ];
    }

    /**
     * Send notifications immediately to unique attendee emails.
     */
    public function sendNotifications(Event $event, array $changes, ?int $updatedById = null): int
    {
        $recipients = $this->uniqueRecipientsForEvent($event->id);
        $sentCount = 0;

        if ($recipients->isEmpty()) {
            Log::info('Event update notification skipped: no recipients found', [
                'event_id' => $event->id,
                'updated_by' => $updatedById,
            ]);

            return 0;
        }

        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient['email'])->send(new EventUpdatedMail(
                    $event,
                    $recipient['name'],
                    $changes
                ));

                $sentCount++;
            } catch (\Throwable $exception) {
                Log::error('Event update notification failed for recipient', [
                    'event_id' => $event->id,
                    'updated_by' => $updatedById,
                    'recipient_email' => $recipient['email'],
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        Log::info('Sent event update notifications', [
            'event_id' => $event->id,
            'updated_by' => $updatedById,
            'recipient_count' => $recipients->count(),
            'sent_count' => $sentCount,
            'changed_fields' => $changes['changed_fields'] ?? [],
        ]);

        return $sentCount;
    }

    /**
     * @return Collection<int, array{email: string, name: string}>
     */
    private function uniqueRecipientsForEvent(int $eventId): Collection
    {
        $seats = Seat::query()
            ->where('event_id', $eventId)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get(['email', 'name']);

        return $seats
            ->groupBy(function (Seat $seat): string {
                return mb_strtolower(trim((string) $seat->email));
            })
            ->map(function (Collection $group): array {
                /** @var Seat $first */
                $first = $group->first();

                return [
                    'email' => trim((string) $first->email),
                    'name' => trim((string) ($first->name ?? 'Attendee')),
                ];
            })
            ->values();
    }

    private function normalizeForComparison(string $field, mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($field === 'description') {
            return trim(strip_tags((string) $value));
        }

        if ($field === 'event_date') {
            return Carbon::parse($value)->toDateString();
        }

        if ($field === 'event_time') {
            return Carbon::parse($value)->format('H:i:s');
        }

        $normalized = trim((string) $value);

        return $normalized === '' ? null : $normalized;
    }

    private function formatForDisplay(string $field, mixed $value): string
    {
        if ($value === null || trim((string) $value) === '') {
            return 'Not set';
        }

        if ($field === 'event_date') {
            return Carbon::parse($value)->format('F j, Y');
        }

        if ($field === 'event_time') {
            return Carbon::parse($value)->format('g:i A');
        }

        if ($field === 'description') {
            return trim(strip_tags((string) $value));
        }

        return trim((string) $value);
    }

    /**
     * @return array<string, string>
     */
    public static function fieldLabels(): array
    {
        return [
            'title' => 'Event title',
            'event_date' => 'Date',
            'event_time' => 'Time',
            'venue' => 'Location',
            'link' => 'Meeting link',
            'description' => 'Description',
        ];
    }
}
