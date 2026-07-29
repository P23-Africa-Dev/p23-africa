<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsAppConversation extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_conversations';

    public const STATUS_OPEN = 'open';
    public const STATUS_PENDING = 'pending';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'account_id',
        'contact_id',
        'wa_id',
        'normalized_phone',
        'conversation_key',
        'assigned_to',
        'status',
        'last_message',
        'unread_count',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $conversation): void {
            $contactPhone = $conversation->contact?->phone;

            if (!$contactPhone && $conversation->contact_id) {
                $contactPhone = WhatsAppContact::query()
                    ->whereKey($conversation->contact_id)
                    ->value('phone');
            }

            $conversation->wa_id = self::sanitizeWaId($conversation->wa_id);
            $conversation->normalized_phone = self::normalizePhone($conversation->normalized_phone ?: $contactPhone);

            $conversationKey = self::buildConversationKey(
                $conversation->wa_id,
                $conversation->normalized_phone,
                $conversation->contact_id,
                $conversation->account_id ? (int) $conversation->account_id : null,
            );

            if ($conversationKey) {
                $conversation->conversation_key = $conversationKey;
            }
        });
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_OPEN,
            self::STATUS_PENDING,
            self::STATUS_CLOSED,
        ];
    }

    public static function sanitizeWaId(?string $waId): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $waId) ?? '';

        return $digits !== '' ? $digits : null;
    }

    public static function normalizePhone(?string $phone): ?string
    {
        $raw = trim((string) $phone);
        if ($raw === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $raw) ?? '';
        if ($digits === '') {
            return null;
        }

        return '+' . $digits;
    }

    public static function phoneCandidates(?string $phone): array
    {
        $normalized = self::normalizePhone($phone);
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';

        return array_values(array_filter(array_unique([
            $normalized,
            $digits !== '' ? $digits : null,
        ])));
    }

    public static function buildConversationKey(?string $waId, ?string $normalizedPhone, ?int $contactId, ?int $accountId = null): ?string
    {
        $prefix = 'acc' . (int) ($accountId ?? 0) . '_';

        $sanitizedWaId = self::sanitizeWaId($waId);
        if ($sanitizedWaId) {
            return $prefix . 'wa_' . sha1($sanitizedWaId);
        }

        $canonicalPhone = self::normalizePhone($normalizedPhone);
        if ($canonicalPhone) {
            return $prefix . 'ph_' . sha1($canonicalPhone);
        }

        if ($contactId) {
            return $prefix . 'ct_' . $contactId;
        }

        return null;
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(WhatsAppAccount::class, 'account_id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(WhatsAppContact::class, 'contact_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(WhatsAppMessage::class, 'conversation_id')->orderBy('created_at', 'asc');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(WhatsAppNote::class, 'conversation_id')->orderBy('id', 'desc');
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        if (!$status) {
            return $query;
        }

        return $query->where('status', $status);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function (Builder $subQuery) use ($term) {
            $subQuery->where('last_message', 'like', '%' . $term . '%')
                ->orWhereHas('contact', function (Builder $contactQuery) use ($term) {
                    $contactQuery->where('name', 'like', '%' . $term . '%')
                        ->orWhere('phone', 'like', '%' . $term . '%')
                        ->orWhere('email', 'like', '%' . $term . '%')
                        ->orWhere('company', 'like', '%' . $term . '%')
                        ->orWhere('notes', 'like', '%' . $term . '%');
                });
        });
    }
}
