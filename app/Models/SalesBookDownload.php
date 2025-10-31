<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesBookDownload extends Model
{
    protected $fillable = [
        'name',
        'email',
        'email_sent_at',
    ];

    protected $casts = [
        'email_sent_at' => 'datetime',
    ];
}
