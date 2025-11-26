<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsultationApplication extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'industry',
        'business_description',
        'selected_goals',
        'selected_challenges',
        'desired_outcomes',
        'status'
    ];

    protected $casts = [
        'selected_goals' => 'array',
        'selected_challenges' => 'array',
    ];

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function scheduledSession(): HasOne
    {
        return $this->hasOne(ScheduledSession::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scheduledSessions(): HasMany
    {
        return $this->hasMany(ScheduledSession::class);
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('sent_at', 'asc');
    }

    public function unreadChatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->where('is_read', false);
    }
}
