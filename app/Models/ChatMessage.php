<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'consultation_application_id',
        'sender_name',
        'sender_email',
        'message',
        'sender_type',
        'is_read',
        'sent_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'sent_at' => 'datetime'
    ];

    /**
     * Get the consultation application that owns the chat message
     */
    public function consultationApplication(): BelongsTo
    {
        return $this->belongsTo(ConsultationApplication::class);
    }

    /**
     * Scope to get unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope to get user messages only
     */
    public function scopeFromUser($query)
    {
        return $query->where('sender_type', 'user');
    }

    /**
     * Scope to get admin messages only
     */
    public function scopeFromAdmin($query)
    {
        return $query->where('sender_type', 'admin');
    }
}
