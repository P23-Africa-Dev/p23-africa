<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventClickLog extends Model
{
    protected $fillable = ['event_id', 'device_id', 'device_type'];
}
