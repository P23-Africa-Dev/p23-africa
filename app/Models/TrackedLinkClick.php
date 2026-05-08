<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackedLinkClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracked_link_id',
        'clicked_at',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'browser_version',
        'platform',
        'platform_version',
        'referrer',
        'session_id',
        'visitor_fingerprint',
        'is_unique_click',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
        'is_unique_click' => 'boolean',
    ];

    public function trackedLink(): BelongsTo
    {
        return $this->belongsTo(TrackedLink::class);
    }
}
