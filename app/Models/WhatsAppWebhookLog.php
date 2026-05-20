<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppWebhookLog extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_webhook_logs';

    protected $fillable = [
        'event_type',
        'payload',
        'headers',
        'signature_valid',
        'status',
        'error_message',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'headers' => 'array',
            'signature_valid' => 'boolean',
            'processed_at' => 'datetime',
        ];
    }
}
