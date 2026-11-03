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

    /**
     * Decode desired_outcomes whether stored as JSON string or array.
     */
    public function decodedOutcomes(): array
    {
        $raw = $this->desired_outcomes;
        if (is_array($raw)) {
            return $raw;
        }
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    /**
     * Full enquiry snapshot for contact-v2 → consultation bookings (and legacy motivation fields).
     */
    public function enquiryDetails(): array
    {
        $outcomes = $this->decodedOutcomes();

        return [
            'role' => $outcomes['role'] ?? null,
            'country_code' => $outcomes['country_code'] ?? null,
            'company_website' => $outcomes['company_website'] ?? $this->website_or_handle,
            'selected_services' => $outcomes['selected_services'] ?? $this->selected_goals ?? [],
            'expansion_markets' => $outcomes['expansion_markets'] ?? [],
            'message' => $outcomes['message'] ?? $outcomes['motivation'] ?? $this->business_description,
            'referral_source' => $outcomes['referral_source'] ?? null,
            'referral_other' => $outcomes['referral_other'] ?? null,
            'start_timeline' => $outcomes['start_timeline'] ?? null,
            'budget_range' => $outcomes['budget_range'] ?? null,
            'budget_currency' => $outcomes['budget_currency'] ?? 'GBP',
            'additional_notes' => $outcomes['additional_notes'] ?? $outcomes['success_definition'] ?? null,
            'preferred_slot' => $outcomes['preferred_slot'] ?? null,
            'contact_submission_id' => $outcomes['contact_submission_id'] ?? null,
            'assessment_outcome' => $outcomes['assessment_outcome'] ?? null,
            'form_flow' => $outcomes['form_flow'] ?? null,
        ];
    }
}
