<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = ['event_id', 'name', 'email', 'phone', 'seat_code'];
}
