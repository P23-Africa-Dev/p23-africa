<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class LeadEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'user_id',
        'from_email',
        'from_name',
        'to_email',
        'to_name',
        'subject',
        'body',
        'body_html',
        'direction',
        'message_id',
        'in_reply_to',
        'thread_id',
        'status',
        'sent_at',
        'read_at',
        'attachments',
        'metadata',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'attachments' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($email) {
            // Generate a unique message ID if not set
            if (empty($email->message_id)) {
                // Extract domain from APP_URL or use a default
                $domain = self::getEmailDomain();
                $email->message_id = Str::uuid()->toString() . '@' . $domain;
            }

            // Generate thread ID if not set and this is a new conversation
            if (empty($email->thread_id) && empty($email->in_reply_to)) {
                $email->thread_id = Str::uuid()->toString();
            }
        });
    }

    /**
     * Get a valid email domain for Message-ID header
     */
    protected static function getEmailDomain(): string
    {
        // Try to get domain from MAIL_FROM_ADDRESS first
        $mailFrom = config('mail.from.address');
        if ($mailFrom && filter_var($mailFrom, FILTER_VALIDATE_EMAIL)) {
            $parts = explode('@', $mailFrom);
            if (isset($parts[1])) {
                return $parts[1];
            }
        }

        // Try to extract domain from APP_URL
        $appUrl = config('app.url', '');
        if ($appUrl) {
            $parsed = parse_url($appUrl);
            if (isset($parsed['host']) && $parsed['host'] !== 'localhost') {
                return $parsed['host'];
            }
        }

        // Fallback to a default domain
        return 'p23africa.com';
    }

    /**
     * Get the lead this email belongs to
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the user (admin) associated with this email
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for outgoing emails
     */
    public function scopeOutgoing($query)
    {
        return $query->where('direction', 'outgoing');
    }

    /**
     * Scope for incoming emails (replies)
     */
    public function scopeIncoming($query)
    {
        return $query->where('direction', 'incoming');
    }

    /**
     * Scope for sent emails
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for a specific thread
     */
    public function scopeInThread($query, $threadId)
    {
        return $query->where('thread_id', $threadId);
    }

    /**
     * Get emails in the same thread
     */
    public function thread()
    {
        return self::where('thread_id', $this->thread_id)
            ->orderBy('created_at', 'asc');
    }

    /**
     * Get the parent email (if this is a reply)
     */
    public function parentEmail()
    {
        if ($this->in_reply_to) {
            return self::where('message_id', $this->in_reply_to)->first();
        }
        return null;
    }

    /**
     * Get replies to this email
     */
    public function replies()
    {
        return self::where('in_reply_to', $this->message_id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Mark email as read
     */
    public function markAsRead(): self
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
        return $this;
    }

    /**
     * Check if the email is a reply
     */
    public function isReply(): bool
    {
        return !empty($this->in_reply_to);
    }

    /**
     * Check if the email is incoming (from lead)
     */
    public function isIncoming(): bool
    {
        return $this->direction === 'incoming';
    }

    /**
     * Check if the email is outgoing (from admin)
     */
    public function isOutgoing(): bool
    {
        return $this->direction === 'outgoing';
    }

    /**
     * Check if the email has been read
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Get a short preview of the email body
     */
    public function getPreviewAttribute(): string
    {
        return Str::limit(strip_tags($this->body), 100);
    }

    /**
     * Get status label for UI
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'sent' => 'Sent',
            'delivered' => 'Delivered',
            'failed' => 'Failed',
            'received' => 'Received',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'sent' => 'info',
            'delivered' => 'success',
            'failed' => 'danger',
            'received' => 'primary',
            default => 'secondary',
        };
    }

    /**
     * Get direction icon for UI
     */
    public function getDirectionIconAttribute(): string
    {
        return $this->isIncoming() ? 'fa-inbox' : 'fa-paper-plane';
    }

    /**
     * Get direction label for UI
     */
    public function getDirectionLabelAttribute(): string
    {
        return $this->isIncoming() ? 'Received' : 'Sent';
    }
}
