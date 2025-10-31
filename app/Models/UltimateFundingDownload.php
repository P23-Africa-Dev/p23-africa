<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UltimateFundingDownload extends Model
{
    protected $fillable = [
        'name',
        'email',
        'company',
        'country',
        'payment_completed',
        'payment_reference',
        'amount_paid',
        'payment_status',
        'payment_date',
        'email_sent_at',
        'payment_data',
    ];

    protected $casts = [
        'payment_completed' => 'boolean',
        'amount_paid' => 'decimal:2',
        'payment_date' => 'datetime',
        'email_sent_at' => 'datetime',
        'payment_data' => 'array',
    ];
}
