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
        'organization_id',
        'drive_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'employee_count',
        'year_founded',
        'industry',
        'job_title',
        'website',
        'company_linkedin_profile',
        'ceo_linkedin_profile',
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
     * Boot method to ensure every lead has a drive
     */
    protected static function boot()
    {
        parent::boot();

        // Ensure every lead has a drive_id before saving
        static::saving(function ($lead) {
            if (empty($lead->drive_id)) {
                // Get the default drive (Lead Bank)
                $defaultDrive = LeadDrive::getDefault();
                if (!$defaultDrive) {
                    // Fallback: find Lead Bank by slug
                    $defaultDrive = LeadDrive::where('slug', 'lead-bank')->first();
                }

                if ($defaultDrive) {
                    $lead->drive_id = $defaultDrive->id;
                }
            }
        });
    }

    /**
     * Status options — dynamic per organization.
     * Returns [ slug => name ] ordered by position.
     */
    public static function getStatuses(?int $orgId = null): array
    {
        // If no org specified, attempt to infer from the authenticated user
        if (is_null($orgId) && auth()->check()) {
            $user = auth()->user();
            $orgId = $user?->organization_id;
        }

        $map = LeadStatus::getMapForOrg($orgId);

        // Fallback to hardcoded if table is empty (e.g. before seeding)
        if (empty($map)) {
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

        return $map;
    }

    /**
     * Source options.
     * When $orgId is provided (organisation context), NOEL Waitlist is excluded
     * because that source is only relevant in the super-admin / global context.
     */
    public static function getSources(?int $orgId = null): array
    {
        $all = [
            'website'        => 'Website',
            'referral'       => 'Referral',
            'social_media'   => 'Social Media',
            'email_campaign' => 'Email Campaign',
            'cold_call'      => 'Cold Call',
            'event'          => 'Event',
            'advertisement'  => 'Advertisement',
            'noel_waitlist'  => 'NOEL Waitlist',
            'other'          => 'Other',
        ];

        // Organisation accounts do not use the NOEL Waitlist source
        if ($orgId !== null) {
            unset($all['noel_waitlist']);
        }

        return $all;
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
     * Industry options
     */
    public static function getIndustries(): array
    {
        return [
            'Technology' => 'Technology',
            'Healthcare' => 'Healthcare',
            'Finance' => 'Finance & Banking',
            'Education' => 'Education',
            'Retail' => 'Retail',
            'E-commerce' => 'E-commerce',
            'Manufacturing' => 'Manufacturing',
            'Real Estate' => 'Real Estate',
            'Agriculture' => 'Agriculture',
            'Energy' => 'Energy & Utilities',
            'Hospitality' => 'Hospitality & Tourism',
            'Media' => 'Media & Entertainment',
            'Transportation' => 'Transportation & Logistics',
            'Construction' => 'Construction',
            'Telecommunications' => 'Telecommunications',
            'Professional Services' => 'Professional Services',
            'Non-Profit' => 'Non-Profit',
            'Government' => 'Government',
            'Other' => 'Other',
        ];
    }

    /**
     * Get the drive this lead belongs to
     */
    public function drive(): BelongsTo
    {
        return $this->belongsTo(LeadDrive::class, 'drive_id');
    }

    /**
     * Organization relation
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
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
     * Get all emails for this lead
     */
    public function emails(): HasMany
    {
        return $this->hasMany(LeadEmail::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get unread incoming emails count
     */
    public function getUnreadEmailsCountAttribute(): int
    {
        return $this->emails()
            ->incoming()
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Get the full name attribute
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the status label attribute (dynamic from LeadStatus table)
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = self::getStatuses($this->organization_id);
        return $statuses[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
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
     * Get status color for UI (dynamic from LeadStatus table)
     */
    public function getStatusColorAttribute(): string
    {
        $colorMap = LeadStatus::getColorMapForOrg($this->organization_id);

        return $colorMap[$this->status] ?? 'secondary';
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
     * Check if lead is active (not in a terminal state — won or lost)
     */
    public function isActive(): bool
    {
        return !LeadStatus::isTerminal($this->status, $this->organization_id);
    }

    /**
     * Check if follow-up is overdue
     */
    public function isFollowUpOverdue(): bool
    {
        return $this->next_follow_up && $this->next_follow_up->isPast() && $this->isActive();
    }

    /**
     * Scope for new leads (leads with the default status)
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope for active leads (not in terminal states)
     */
    public function scopeActive($query)
    {
        // Get terminal slugs based on all orgs in the query context.
        // For simplicity, we collect all terminal slugs across all orgs
        // (won/lost statuses). This is safe because non-matching slugs
        // simply won't match any rows.
        $terminalSlugs = LeadStatus::where(function ($q) {
            $q->where('is_won', true)->orWhere('is_lost', true);
        })->pluck('slug')->unique()->toArray();

        if (empty($terminalSlugs)) {
            // Fallback
            $terminalSlugs = ['won', 'lost'];
        }

        return $query->whereNotIn('status', $terminalSlugs);
    }

    /**
     * Scope for won leads
     */
    public function scopeWon($query)
    {
        $wonSlugs = LeadStatus::where('is_won', true)
            ->pluck('slug')->unique()->toArray();

        if (empty($wonSlugs)) {
            $wonSlugs = ['won'];
        }

        return $query->whereIn('status', $wonSlugs);
    }

    /**
     * Scope for lost leads
     */
    public function scopeLost($query)
    {
        $lostSlugs = LeadStatus::where('is_lost', true)
            ->pluck('slug')->unique()->toArray();

        if (empty($lostSlugs)) {
            $lostSlugs = ['lost'];
        }

        return $query->whereIn('status', $lostSlugs);
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

        // Get Lead Bank drive (default drive for website leads)
        $leadBankDrive = LeadDrive::getDefault();
        if (!$leadBankDrive) {
            // Fallback: find Lead Bank by name if default is not set
            $leadBankDrive = LeadDrive::where('slug', 'lead-bank')->first();
        }

        return self::create([
            'drive_id' => $leadBankDrive?->id,
            'first_name' => $nameParts[0] ?? $submission->name,
            'last_name' => $nameParts[1] ?? null,
            'email' => $submission->email,
            'phone' => $submission->phone,
            'company' => $submission->company_name,
            'country' => $submission->country_code,
            'status' => LeadStatus::getDefaultSlug(null),
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

        // Get Lead Bank drive (default drive for website leads)
        $leadBankDrive = LeadDrive::getDefault();
        if (!$leadBankDrive) {
            // Fallback: find Lead Bank by name if default is not set
            $leadBankDrive = LeadDrive::where('slug', 'lead-bank')->first();
        }

        return self::create([
            'drive_id' => $leadBankDrive?->id,
            'first_name' => $nameParts[0] ?? $application->name,
            'last_name' => $nameParts[1] ?? null,
            'email' => $application->email,
            'phone' => $application->phone,
            'company' => $application->company,
            'website' => $application->website_or_handle,
            'status' => LeadStatus::getDefaultSlug(null),
            'source' => 'website',
            'priority' => 'high',
            'interested_services' => $application->selected_goals,
            'requirements' => $application->business_description,
            'source_type' => 'consultation_application',
            'source_id' => $application->id,
        ]);
    }
}
