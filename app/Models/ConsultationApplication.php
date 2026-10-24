<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsultationApplication extends Model
{
    protected $fillable = [
        'booking_token',
        'name',
        'email',
        'phone',
        'company',
        'website_or_handle',
        'industry',
        'business_description',
        'selected_goals',
        'selected_challenges',
        'desired_outcomes',
        'status',
        'submitted_at',
        'locale',
    ];

    protected $casts = [
        'selected_goals' => 'array',
        'selected_challenges' => 'array',
        'submitted_at' => 'datetime',
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

    /**
     * Get user-friendly status text
     */
    public function getStatusText(): string
    {
        return match ($this->status) {
            'pending' => 'Pending Review',
            'payment_pending' => 'Payment Pending',
            'paid' => 'Payment Completed',
            'scheduled' => 'Consultation Scheduled',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status)
        };
    }
}
