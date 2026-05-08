<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrackedLink;
use App\Models\TrackedLinkClick;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TrackedLinkAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user() ?? Auth::user();
        $this->ensureLegacyAnalyticsAccess($user);

        $validated = $request->validate([
            'filter' => 'nullable|in:today,yesterday,last_7_days,last_30_days,last_month,custom',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'tracking_code' => 'nullable|string|max:255',
            'referrer' => 'nullable|string|max:255',
        ]);

        $filter = $validated['filter'] ?? 'last_30_days';
        [$startAt, $endAt] = $this->resolveDateRange($filter, $validated['start_date'] ?? null, $validated['end_date'] ?? null);

        $baseQuery = TrackedLinkClick::query()
            ->with('trackedLink:id,name,tracking_code')
            ->whereBetween('clicked_at', [$startAt, $endAt]);

        if (!empty($validated['tracking_code'])) {
            $baseQuery->whereHas('trackedLink', function (Builder $query) use ($validated) {
                $query->where('tracking_code', $validated['tracking_code']);
            });
        }

        if (!empty($validated['referrer'])) {
            if ($validated['referrer'] === 'direct') {
                $baseQuery->where(function (Builder $query) {
                    $query->whereNull('referrer')->orWhere('referrer', '');
                });
            } else {
                $baseQuery->where('referrer', $validated['referrer']);
            }
        }

        $totalClicks = (clone $baseQuery)->count();
        $uniqueClicks = (clone $baseQuery)->where('is_unique_click', true)->count();
        $repeatedClicks = max(0, $totalClicks - $uniqueClicks);

        $driver = DB::connection()->getDriverName();
        $dateExpr = $driver === 'sqlite' ? "strftime('%Y-%m-%d', clicked_at)" : 'DATE(clicked_at)';
        $hourExpr = $driver === 'sqlite' ? "strftime('%H', clicked_at)" : 'HOUR(clicked_at)';

        $dailyTrends = (clone $baseQuery)
            ->selectRaw("{$dateExpr} as date_bucket, COUNT(*) as total_clicks, SUM(CASE WHEN is_unique_click = 1 THEN 1 ELSE 0 END) as unique_clicks")
            ->groupBy('date_bucket')
            ->orderBy('date_bucket')
            ->get();

        $hourlyTrends = (clone $baseQuery)
            ->selectRaw("{$hourExpr} as hour_bucket, COUNT(*) as total_clicks")
            ->groupBy('hour_bucket')
            ->orderByDesc('total_clicks')
            ->orderBy('hour_bucket')
            ->get();

        $referrerStats = (clone $baseQuery)
            ->selectRaw("CASE WHEN referrer IS NULL OR referrer = '' THEN 'Direct/Unknown' ELSE referrer END as referrer_source, COUNT(*) as total_clicks")
            ->groupBy('referrer_source')
            ->orderByDesc('total_clicks')
            ->limit(20)
            ->get();

        $linksPerformance = TrackedLink::query()
            ->withCount([
                'clicks as filtered_clicks_count' => function (Builder $query) use ($startAt, $endAt) {
                    $query->whereBetween('clicked_at', [$startAt, $endAt]);
                },
                'clicks as filtered_unique_clicks_count' => function (Builder $query) use ($startAt, $endAt) {
                    $query->whereBetween('clicked_at', [$startAt, $endAt])->where('is_unique_click', true);
                },
            ])
            ->orderByDesc('filtered_clicks_count')
            ->get(['id', 'name', 'tracking_code', 'destination_url', 'is_active', 'total_clicks']);

        $topActivePeriod = $hourlyTrends->first();

        $referrerOptions = (clone $baseQuery)
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->distinct()
            ->pluck('referrer')
            ->values();

        $trackingCodes = TrackedLink::query()->orderBy('tracking_code')->pluck('tracking_code');

        $recentClicks = (clone $baseQuery)
            ->latest('clicked_at')
            ->limit(100)
            ->get();

        return view('admin.link-tracking.index', [
            'filter' => $filter,
            'startDate' => $startAt->toDateString(),
            'endDate' => $endAt->toDateString(),
            'selectedTrackingCode' => $validated['tracking_code'] ?? '',
            'selectedReferrer' => $validated['referrer'] ?? '',
            'totalClicks' => $totalClicks,
            'uniqueClicks' => $uniqueClicks,
            'repeatedClicks' => $repeatedClicks,
            'uniqueRate' => $totalClicks > 0 ? round(($uniqueClicks / $totalClicks) * 100, 2) : 0,
            'dailyTrends' => $dailyTrends,
            'hourlyTrends' => $hourlyTrends,
            'topActivePeriod' => $topActivePeriod,
            'referrerStats' => $referrerStats,
            'linksPerformance' => $linksPerformance,
            'trackingCodes' => $trackingCodes,
            'referrerOptions' => $referrerOptions,
            'recentClicks' => $recentClicks,
        ]);
    }

    public function export(Request $request)
    {
        $user = $request->user() ?? Auth::user();
        $this->ensureLegacyAnalyticsAccess($user);

        $validated = $request->validate([
            'filter' => 'nullable|in:today,yesterday,last_7_days,last_30_days,last_month,custom',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'tracking_code' => 'nullable|string|max:255',
            'referrer' => 'nullable|string|max:255',
        ]);

        $filter = $validated['filter'] ?? 'last_30_days';
        [$startAt, $endAt] = $this->resolveDateRange($filter, $validated['start_date'] ?? null, $validated['end_date'] ?? null);

        $query = TrackedLinkClick::query()
            ->with('trackedLink:id,name,tracking_code,destination_url')
            ->whereBetween('clicked_at', [$startAt, $endAt])
            ->latest('clicked_at');

        if (!empty($validated['tracking_code'])) {
            $query->whereHas('trackedLink', function (Builder $builder) use ($validated) {
                $builder->where('tracking_code', $validated['tracking_code']);
            });
        }

        if (!empty($validated['referrer'])) {
            if ($validated['referrer'] === 'direct') {
                $query->where(function (Builder $builder) {
                    $builder->whereNull('referrer')->orWhere('referrer', '');
                });
            } else {
                $query->where('referrer', $validated['referrer']);
            }
        }

        $filename = 'tracked-link-clicks-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'Click ID',
                'Tracking Code',
                'Link Name',
                'Destination URL',
                'Clicked At',
                'IP Address',
                'Device Type',
                'Browser',
                'Platform',
                'Referrer',
                'Unique Click',
            ]);

            $query->chunkById(500, function ($clicks) use ($file) {
                foreach ($clicks as $click) {
                    fputcsv($file, [
                        $click->id,
                        $click->trackedLink?->tracking_code,
                        $click->trackedLink?->name,
                        $click->trackedLink?->destination_url,
                        $click->clicked_at?->format('Y-m-d H:i:s'),
                        $click->ip_address,
                        $click->device_type,
                        trim(implode(' ', array_filter([$click->browser, $click->browser_version]))),
                        trim(implode(' ', array_filter([$click->platform, $click->platform_version]))),
                        $click->referrer,
                        $click->is_unique_click ? 'Yes' : 'No',
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function resolveDateRange(string $filter, ?string $startDate, ?string $endDate): array
    {
        return match ($filter) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'yesterday' => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
            'last_7_days' => [now()->subDays(6)->startOfDay(), now()->endOfDay()],
            'last_30_days' => [now()->subDays(29)->startOfDay(), now()->endOfDay()],
            'last_month' => [now()->subMonthNoOverflow()->startOfMonth()->startOfDay(), now()->subMonthNoOverflow()->endOfMonth()->endOfDay()],
            'custom' => [
                $startDate ? Carbon::parse($startDate)->startOfDay() : now()->subDays(29)->startOfDay(),
                $endDate ? Carbon::parse($endDate)->endOfDay() : now()->endOfDay(),
            ],
            default => [now()->subDays(29)->startOfDay(), now()->endOfDay()],
        };
    }

    private function ensureLegacyAnalyticsAccess($user): void
    {
        $isAllowedRole = $user && ($user->hasRole('admin') || $user->hasRole('supervisor'));
        $isLegacyContext = $user && is_null($user->organization_id);

        if (!$isAllowedRole || !$isLegacyContext) {
            abort(403, 'Forbidden');
        }
    }
}
