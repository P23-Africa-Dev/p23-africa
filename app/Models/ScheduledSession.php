<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledSession extends Model
{
    protected $fillable = [
        'consultation_application_id',
        'session_date',
        'session_time',
        'timezone',
        'meet_link',
        'status'
    ];

    protected $casts = [
        'session_date' => 'date',
        'session_time' => 'datetime:H:i',
    ];

    public function consultationApplication(): BelongsTo
    {
        return $this->belongsTo(ConsultationApplication::class);
    }
}
