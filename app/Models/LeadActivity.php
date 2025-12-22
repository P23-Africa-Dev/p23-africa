<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'user_id',
        'type',
        'title',
        'description',
        'old_value',
        'new_value',
        'scheduled_at',
        'completed_at',
        'is_completed',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    /**
     * Activity type options
     */
    public static function getTypes(): array
    {
        return [
            'note' => 'Note',
            'email' => 'Email',
            'call' => 'Call',
            'meeting' => 'Meeting',
            'task' => 'Task',
            'status_change' => 'Status Change',
            'follow_up' => 'Follow Up',
            'proposal_sent' => 'Proposal Sent',
            'document' => 'Document',
            'other' => 'Other',
        ];
    }

    /**
     * Get the lead this activity belongs to
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the user who created this activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the type label
     */
    public function getTypeLabelAttribute(): string
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }

    /**
     * Get the type icon for UI
     */
    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'note' => 'fa-sticky-note',
            'email' => 'fa-envelope',
            'call' => 'fa-phone',
            'meeting' => 'fa-calendar-check',
            'task' => 'fa-tasks',
            'status_change' => 'fa-exchange-alt',
            'follow_up' => 'fa-clock',
            'proposal_sent' => 'fa-file-invoice',
            'document' => 'fa-file-alt',
            default => 'fa-circle',
        };
    }

    /**
     * Get the type color for UI
     */
    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'note' => 'info',
            'email' => 'primary',
            'call' => 'success',
            'meeting' => 'warning',
            'task' => 'secondary',
            'status_change' => 'dark',
            'follow_up' => 'info',
            'proposal_sent' => 'success',
            'document' => 'primary',
            default => 'secondary',
        };
    }

    /**
     * Scope for pending activities
     */
    public function scopePending($query)
    {
        return $query->where('is_completed', false)
            ->whereNotNull('scheduled_at');
    }

    /**
     * Scope for completed activities
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope for overdue activities
     */
    public function scopeOverdue($query)
    {
        return $query->pending()
            ->where('scheduled_at', '<', now());
    }

    /**
     * Mark activity as completed
     */
    public function markAsCompleted(): self
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        return $this;
    }
}
