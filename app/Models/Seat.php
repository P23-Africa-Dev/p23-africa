<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'email',
        'phone',
        'job_title',
        'organization',
        'location',
        'industry',
        'expectations',
        'referral_source',
        'seat_code'
    ];

    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }

    public function whatsappRecipients(): HasMany
    {
        return $this->hasMany(EventWhatsAppRecipient::class, 'event_registration_id');
    }
}
