<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappSecurityLog extends Model
{
    protected $table = 'whatsapp_security_logs';

    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'ip',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
