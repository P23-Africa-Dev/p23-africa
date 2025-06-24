<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'email',
        'rating',
        'comments',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
