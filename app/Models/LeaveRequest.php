<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supervisor_id',
        'organization_id',
        'type',
        'reason',
        'start_date',
        'end_date',
        'duration_days',
        'status',
        'supervisor_note',
        'modified_start_date',
        'modified_end_date',
        'modified_duration_days',
        'sender_response_note',
        'sender_responded_at',
        'decided_at',
        'decision_note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'modified_start_date' => 'date',
        'modified_end_date' => 'date',
        'sender_responded_at' => 'datetime',
        'decided_at' => 'datetime',
    ];

    public const TYPES = [
        'annual' => 'Annual Leave',
        'sick' => 'Sick Leave',
        'personal' => 'Personal Leave',
        'maternity' => 'Maternity Leave',
        'paternity' => 'Paternity Leave',
        'bereavement' => 'Bereavement Leave',
        'other' => 'Other',
    ];

    public const STATUSES = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'modified' => 'Modified by Supervisor',
        'sender_okay' => 'Sender Agreed',
        'sender_not_okay' => 'Sender Disagreed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the display name for the leave type.
     */
    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get the display name for the status.
     */
    public function getStatusNameAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get status badge color for UI display.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'modified' => 'info',
            'sender_okay' => 'primary',
            'sender_not_okay' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get the effective dates (modified if available, otherwise original).
     */
    public function getEffectiveStartDateAttribute()
    {
        return $this->modified_start_date ?? $this->start_date;
    }

    public function getEffectiveEndDateAttribute()
    {
        return $this->modified_end_date ?? $this->end_date;
    }

    public function getEffectiveDurationAttribute(): int
    {
        return $this->modified_duration_days ?? $this->duration_days;
    }
}
