<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    protected $fillable = ['route_name', 'device_type', 'click_count'];
}
