<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaybookDownload extends Model
{
    protected $fillable = [
        'name',
        'email',
        'company',
        'email_sent_at'
    ];

    protected $casts = [
        'email_sent_at' => 'datetime',
    ];
}
