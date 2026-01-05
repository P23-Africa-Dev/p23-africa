<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'job_title',
        'website',
        'country',
        'city',
        'address',
        'status',
        'source',
        'priority',
        'estimated_value',
        'currency',
        'interested_services',
        'requirements',
        'notes',
        'assigned_to',
        'last_contacted_at',
        'next_follow_up',
        'converted_at',
        'lost_reason',
        'source_type',
        'source_id',
    ];

    protected $casts = [
        'interested_services' => 'array',
        'estimated_value' => 'decimal:2',
        'last_contacted_at' => 'datetime',
        'next_follow_up' => 'datetime',
        'converted_at' => 'datetime',
    ];

    /**
     * Status options
     */
    public static function getStatuses(): array
    {
        return [
            'new' => 'New',
            'contacted' => 'Contacted',
            'qualified' => 'Qualified',
            'unqualified' => 'Unqualified',
            'proposal' => 'Proposal Sent',
            'negotiation' => 'Negotiation',
            'won' => 'Won',
            'lost' => 'Lost',
        ];
    }

    /**
     * Source options
     */
    public static function getSources(): array
    {
        return [
            'website' => 'Website',
            'referral' => 'Referral',
            'social_media' => 'Social Media',
            'email_campaign' => 'Email Campaign',
            'cold_call' => 'Cold Call',
            'event' => 'Event',
            'advertisement' => 'Advertisement',
            'other' => 'Other',
        ];
    }

    /**
     * Priority options
     */
    public static function getPriorities(): array
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];
    }

    /**
     * Service options
     */
    public static function getServices(): array
    {
        return [
            'Business Strategy',
            'Branding & Identity',
            'Digital Marketing',
            'Web Development',
            'Mobile App Development',
            'E-commerce Solutions',
            'Consulting',
            'Training & Workshops',
            'Content Creation',
            'SEO & Analytics',
            'Other',
        ];
    }

    /**
     * Get the user assigned to this lead
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all activities for this lead
     */
    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the full name attribute
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the status label attribute
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Get the source label attribute
     */
    public function getSourceLabelAttribute(): string
    {
        return self::getSources()[$this->source] ?? $this->source;
    }

    /**
     * Get the priority label attribute
     */
    public function getPriorityLabelAttribute(): string
    {
        return self::getPriorities()[$this->priority] ?? $this->priority;
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'new' => 'info',
            'contacted' => 'primary',
            'qualified' => 'warning',
            'unqualified' => 'secondary',
            'proposal' => 'secondary',
            'negotiation' => 'dark',
            'won' => 'success',
            'lost' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get priority color for UI
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'secondary',
            'medium' => 'info',
            'high' => 'warning',
            'urgent' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Check if lead is active (not won or lost)
     */
    public function isActive(): bool
    {
        return !in_array($this->status, ['won', 'lost']);
    }

    /**
     * Check if follow-up is overdue
     */
    public function isFollowUpOverdue(): bool
    {
        return $this->next_follow_up && $this->next_follow_up->isPast() && $this->isActive();
    }

    /**
     * Scope for new leads
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope for active leads (not won or lost)
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['won', 'lost']);
    }

    /**
     * Scope for won leads
     */
    public function scopeWon($query)
    {
        return $query->where('status', 'won');
    }

    /**
     * Scope for lost leads
     */
    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }

    /**
     * Scope for leads with overdue follow-ups
     */
    public function scopeOverdueFollowUp($query)
    {
        return $query->active()
            ->whereNotNull('next_follow_up')
            ->where('next_follow_up', '<', now());
    }

    /**
     * Scope for leads assigned to a user
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope for high value leads
     */
    public function scopeHighValue($query, $threshold = 10000)
    {
        return $query->where('estimated_value', '>=', $threshold);
    }

    /**
     * Log an activity for this lead
     */
    public function logActivity(string $type, string $title, ?string $description = null, array $metadata = []): LeadActivity
    {
        return $this->activities()->create([
            'user_id' => auth()->id(),
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Log a status change
     */
    public function logStatusChange(string $oldStatus, string $newStatus): LeadActivity
    {
        return $this->activities()->create([
            'user_id' => auth()->id(),
            'type' => 'status_change',
            'title' => 'Status changed',
            'description' => "Status changed from {$oldStatus} to {$newStatus}",
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
        ]);
    }

    /**
     * Create lead from contact submission
     */
    public static function createFromContactSubmission(ContactSubmission $submission): self
    {
        $nameParts = explode(' ', $submission->name, 2);

        return self::create([
            'first_name' => $nameParts[0] ?? $submission->name,
            'last_name' => $nameParts[1] ?? null,
            'email' => $submission->email,
            'phone' => $submission->phone,
            'company' => $submission->company_name,
            'country' => $submission->country_code,
            'status' => 'new',
            'source' => 'website',
            'priority' => 'medium',
            'interested_services' => $submission->services,
            'requirements' => $submission->message,
            'estimated_value' => $submission->budget_range,
            'source_type' => 'contact_submission',
            'source_id' => $submission->id,
        ]);
    }

    /**
     * Create lead from consultation application
     */
    public static function createFromConsultation(ConsultationApplication $application): self
    {
        $nameParts = explode(' ', $application->name, 2);

        return self::create([
            'first_name' => $nameParts[0] ?? $application->name,
            'last_name' => $nameParts[1] ?? null,
            'email' => $application->email,
            'phone' => $application->phone,
            'company' => $application->company,
            'website' => $application->website_or_handle,
            'status' => 'qualified',
            'source' => 'website',
            'priority' => 'high',
            'interested_services' => $application->selected_goals,
            'requirements' => $application->business_description,
            'source_type' => 'consultation_application',
            'source_id' => $application->id,
        ]);
    }
}
