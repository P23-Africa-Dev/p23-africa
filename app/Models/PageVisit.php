<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_name',
        'page_title',
        'page_url',
        'device_type',
        'ip_address',
        'user_agent',
        'browser',
        'browser_version',
        'platform',
        'platform_version',
        'referrer',
        'session_id',
        'country',
        'city',
        'timezone',
        'latitude',
        'longitude',
        'is_unique_visitor',
        'screen_width',
        'screen_height',
        'time_on_page'
    ];

    protected $casts = [
        'is_unique_visitor' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Scopes for filtering
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    public function scopeLastDays($query, $days)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByDevice($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeUniqueVisitors($query)
    {
        return $query->where('is_unique_visitor', true);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
