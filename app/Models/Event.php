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
        'image',
        'booking_fields',
        'visibility',
        'reminder_24hr_sent_at',
        'reminder_15min_sent_at'
    ];

    protected $casts = [
        'booking_fields' => 'array',
        'reminder_24hr_sent_at' => 'datetime',
        'reminder_15min_sent_at' => 'datetime'
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

    public function getBookingFields()
    {
        return $this->booking_fields ?? ['name', 'email']; // Default fields
    }

    public static function getAvailableBookingFields()
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'job_title' => 'Job Title',
            'organization' => 'Organization',
            'location' => 'Location',
            'industry' => 'Industry',
            'expectations' => 'Expectations',
            'referral_source' => 'How did you hear about us?'
        ];
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
