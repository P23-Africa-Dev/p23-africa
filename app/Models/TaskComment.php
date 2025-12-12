<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskComment extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'parent_id',
        'comment',
        'attachment_path',
        'attachment_name',
        'attachment_type',
        'mentioned_users',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'mentioned_users' => 'array',
    ];

    /**
     * Get the task this comment belongs to
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user who created this comment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (for replies)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(TaskComment::class, 'parent_id');
    }

    /**
     * Get replies to this comment
     */
    public function replies(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Check if comment has an attachment
     */
    public function hasAttachment(): bool
    {
        return !empty($this->attachment_path);
    }

    /**
     * Get the attachment URL
     */
    public function getAttachmentUrlAttribute(): ?string
    {
        if (!$this->attachment_path) {
            return null;
        }
        return asset('storage/' . $this->attachment_path);
    }

    /**
     * Check if attachment is an image
     */
    public function isImageAttachment(): bool
    {
        if (!$this->attachment_type) {
            return false;
        }
        return str_starts_with($this->attachment_type, 'image/');
    }

    /**
     * Get mentioned users as User models
     */
    public function getMentionedUsersModels()
    {
        if (empty($this->mentioned_users)) {
            return collect();
        }
        return User::whereIn('id', $this->mentioned_users)->get();
    }
}
