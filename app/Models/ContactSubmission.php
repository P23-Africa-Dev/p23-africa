<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'country_code',
        'services',
        'message',
        'budget_range',
        'ip_address',
        'user_agent',
        'email_sent_at'
    ];

    protected $casts = [
        'services' => 'array',
        'email_sent_at' => 'datetime'
    ];
}
