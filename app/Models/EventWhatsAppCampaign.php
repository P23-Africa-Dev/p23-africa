<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventWhatsAppCampaign extends Model
{
    use HasFactory;

    protected $table = 'event_whatsapp_campaigns';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_QUEUED = 'queued';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'event_id',
        'template_id',
        'total_contacts',
        'valid_contacts',
        'sent',
        'failed',
        'status',
        'source_type',
        'audience_filters',
        'queued_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'audience_filters' => 'array',
            'queued_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(WhatsAppTemplate::class, 'template_id');
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(EventWhatsAppRecipient::class, 'campaign_id');
    }
}
