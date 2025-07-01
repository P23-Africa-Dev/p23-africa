<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventClick extends Model
{
    protected $fillable = [
        'event_id',
        'device_identifier',
        'device_type',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
