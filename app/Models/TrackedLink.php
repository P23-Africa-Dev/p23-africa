<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrackedLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tracking_code',
        'destination_url',
        'description',
        'is_active',
        'total_clicks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_clicks' => 'integer',
    ];

    public function clicks(): HasMany
    {
        return $this->hasMany(TrackedLinkClick::class);
    }

    public function dailyStats(): HasMany
    {
        return $this->hasMany(TrackedLinkDailyStat::class);
    }
}
