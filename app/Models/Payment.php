<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'consultation_application_id',
        'payment_method',
        'payment_reference',
        'amount',
        'currency',
        'status',
        'payment_data',
        'paid_at'
    ];

    protected $casts = [
        'payment_data' => 'array',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function consultationApplication(): BelongsTo
    {
        return $this->belongsTo(ConsultationApplication::class);
    }
}
