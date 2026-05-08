<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackedLinkDailyStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracked_link_id',
        'date',
        'total_clicks',
        'unique_clicks',
        'repeated_clicks',
    ];

    protected $casts = [
        'date' => 'date',
        'total_clicks' => 'integer',
        'unique_clicks' => 'integer',
        'repeated_clicks' => 'integer',
    ];

    public function trackedLink(): BelongsTo
    {
        return $this->belongsTo(TrackedLink::class);
    }
}
