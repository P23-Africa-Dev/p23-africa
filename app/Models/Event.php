<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'description',
        'link',
        'event_date',
        'event_time',
        'venue',
        'display',
        'image'
    ];

    // public function isPublic()
    // {
    //     return $this->type === 'public';
    // }


    public function seats()
    {
        return $this->hasMany(\App\Models\Seat::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $event->slug = Str::slug($event->title);
        });

        static::updating(function ($event) {
            $event->slug = Str::slug($event->title);
        });
    }
}
