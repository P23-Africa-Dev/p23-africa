<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventWhatsAppRecipient extends Model
{
    use HasFactory;

    protected $table = 'event_whatsapp_recipients';

    public const STATUS_QUEUED = 'queued';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_SKIPPED = 'skipped';

    protected $fillable = [
        'campaign_id',
        'event_registration_id',
        'contact_id',
        'phone',
        'status',
        'sent_at',
        'delivered_at',
        'message_id',
        'variables',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'delivered_at' => 'datetime',
            'variables' => 'array',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(EventWhatsAppCampaign::class, 'campaign_id');
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Seat::class, 'event_registration_id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(WhatsAppContact::class, 'contact_id');
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(WhatsAppMessage::class, 'message_id');
    }
}
