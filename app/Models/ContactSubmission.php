<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'country_code',
        'services',
        'message',
        'budget_range',
        'project_details',
        'ip_address',
        'user_agent',
        'email_sent_at',
        'status',
        'session_id',
        'keystroke_count',
        'last_activity_at',
        'field_interactions',
        'draft_created_at',
        'submitted_at'
    ];

    protected $casts = [
        'services' => 'array',
        'project_details' => 'array',
        'field_interactions' => 'array',
        'email_sent_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'draft_created_at' => 'datetime',
        'submitted_at' => 'datetime'
    ];

    /**
     * Scope for draft submissions
     */
    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for submitted forms
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Check if submission is a draft
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * Check if submission was completed
     */
    public function isSubmitted()
    {
        return $this->status === 'submitted';
    }

    /**
     * Get the completion percentage based on filled fields
     */
    public function getCompletionPercentage()
    {
        $requiredFields = ['name', 'company_name', 'email', 'phone', 'services', 'message', 'budget_range'];
        $filledFields = 0;

        foreach ($requiredFields as $field) {
            if (!empty($this->$field)) {
                $filledFields++;
            }
        }

        return round(($filledFields / count($requiredFields)) * 100);
    }
}
