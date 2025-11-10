<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = [
        'name',
        'email',
        'services',
        'message',
        'ip_address',
        'user_agent',
        'email_sent_at'
    ];

    protected $casts = [
        'services' => 'array',
        'email_sent_at' => 'datetime'
    ];
}
