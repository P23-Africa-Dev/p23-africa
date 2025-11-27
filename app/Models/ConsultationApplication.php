<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsultationApplication extends Model
{
    protected $fillable = [
        'session_id',
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
        'draft_data',
        'draft_created_at',
        'submitted_at',
        'keystroke_count',
        'field_interactions',
        'ip_address',
        'user_agent',
        'last_activity_at'
    ];

    protected $casts = [
        'selected_goals' => 'array',
        'selected_challenges' => 'array',
        'draft_data' => 'array',
        'field_interactions' => 'array',
        'draft_created_at' => 'datetime',
        'submitted_at' => 'datetime',
        'last_activity_at' => 'datetime',
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
     * Scope to get only draft applications
     */
    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope to get only submitted applications (non-drafts)
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', '!=', 'draft');
    }

    /**
     * Check if the application is a draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if the application has been submitted
     */
    public function isSubmitted(): bool
    {
        return $this->status !== 'draft';
    }

    /**
     * Get the completion percentage for drafts
     */
    public function getCompletionPercentage(): float
    {
        if ($this->isSubmitted()) {
            return 100.0;
        }

        $requiredFields = [
            'name',
            'email',
            'phone',
            'company',
            'industry',
            'business_description',
            'selected_goals',
            'selected_challenges',
            'desired_outcomes'
        ];

        $completedFields = 0;
        $totalFields = count($requiredFields);

        foreach ($requiredFields as $field) {
            $value = $this->{$field};
            if (!empty($value)) {
                // Special handling for arrays
                if (is_array($value) && count($value) > 0) {
                    $completedFields++;
                } elseif (!is_array($value) && trim($value) !== '') {
                    $completedFields++;
                }
            }
        }

        return round(($completedFields / $totalFields) * 100, 1);
    }

    /**
     * Get user-friendly status text
     */
    public function getStatusText(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'pending' => 'Pending Review',
            'payment_pending' => 'Payment Pending',
            'paid' => 'Payment Completed',
            'scheduled' => 'Consultation Scheduled',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status)
        };
    }

    /**
     * Check if application has recent activity (within last 24 hours)
     */
    public function hasRecentActivity(): bool
    {
        return $this->last_activity_at && $this->last_activity_at->gt(now()->subDay());
    }

    /**
     * Get draft statistics for admin dashboard
     */
    public static function getDraftStats(): array
    {
        return [
            'total_drafts' => static::drafts()->count(),
            'today_drafts' => static::drafts()->whereDate('draft_created_at', today())->count(),
            'active_sessions' => static::drafts()
                ->where('last_activity_at', '>=', now()->subHours(24))
                ->count(),
            'avg_completion' => static::drafts()
                ->get()
                ->avg(fn($app) => $app->getCompletionPercentage())
        ];
    }
}
