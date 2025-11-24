<?php

namespace App\Http\Controllers\Admin;

use App\Events\AdminClicksEvent;
use App\Http\Controllers\Controller;
use App\Models\Click;
use App\Models\PageVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ClickReportController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $deviceType = $request->get('device_type', 'all');
        $country = $request->get('country', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $pageRoute = $request->get('page_route', 'all');
        $browser = $request->get('browser', 'all');
        $search = $request->get('search');

        // Build the main query for page visits
        $visitsQuery = PageVisit::query();

        // Apply date filters
        $this->applyDateFilters($visitsQuery, $filter, $startDate, $endDate);

        // Apply other filters
        if ($deviceType !== 'all') {
            $visitsQuery->where('device_type', $deviceType);
        }

        if ($country !== 'all') {
            $visitsQuery->where('country', $country);
        }

        if ($pageRoute !== 'all') {
            $visitsQuery->where('route_name', $pageRoute);
        }

        if ($browser !== 'all') {
            $visitsQuery->where('browser', $browser);
        }

        if ($search) {
            $visitsQuery->where(function ($query) use ($search) {
                $query->where('route_name', 'like', "%{$search}%")
                    ->orWhere('page_title', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Get paginated visits with optimized query
        $visits = $visitsQuery
            ->select([
                'id',
                'route_name',
                'page_title',
                'page_url',
                'device_type',
                'ip_address',
                'browser',
                'browser_version',
                'country',
                'city',
                'is_unique_visitor',
                'created_at'
            ]) // Only select needed columns
            ->orderByDesc('created_at')
            ->paginate(25); // Reduced from 50 to 25 for better performance

        // Get analytics data (with caching)
        $analytics = $this->getAnalyticsData($request);

        // Get filter options with caching
        $filterOptions = $this->getFilterOptions();

        return view('admin.clicks.index', compact(
            'visits',
            'analytics',
            'filterOptions',
            'filter',
            'deviceType',
            'country',
            'startDate',
            'endDate',
            'pageRoute',
            'browser',
            'search'
        ));
    }

    public function export(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $deviceType = $request->get('device_type', 'all');
        $country = $request->get('country', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $pageRoute = $request->get('page_route', 'all');
        $browser = $request->get('browser', 'all');
        $search = $request->get('search');

        $visitsQuery = PageVisit::query();

        // Apply the same filters as the index method
        $this->applyDateFilters($visitsQuery, $filter, $startDate, $endDate);

        if ($deviceType !== 'all') {
            $visitsQuery->where('device_type', $deviceType);
        }

        if ($country !== 'all') {
            $visitsQuery->where('country', $country);
        }

        if ($pageRoute !== 'all') {
            $visitsQuery->where('route_name', $pageRoute);
        }

        if ($browser !== 'all') {
            $visitsQuery->where('browser', $browser);
        }

        if ($search) {
            $visitsQuery->where(function ($query) use ($search) {
                $query->where('route_name', 'like', "%{$search}%")
                    ->orWhere('page_title', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $visits = $visitsQuery->orderByDesc('created_at')->get();

        $filename = 'page_visits_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($visits) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID',
                'Page',
                'Page Title',
                'URL',
                'Device Type',
                'IP Address',
                'Country',
                'City',
                'Browser',
                'Platform',
                'Referrer',
                'Unique Visitor',
                'Time on Page (seconds)',
                'Visit Date',
            ]);

            foreach ($visits as $visit) {
                fputcsv($file, [
                    $visit->id,
                    str_replace('_', ' ', $visit->route_name),
                    $visit->page_title,
                    $visit->page_url,
                    ucfirst($visit->device_type),
                    $visit->ip_address,
                    $visit->country,
                    $visit->city,
                    $visit->browser . ' ' . $visit->browser_version,
                    $visit->platform . ' ' . $visit->platform_version,
                    $visit->referrer,
                    $visit->is_unique_visitor ? 'Yes' : 'No',
                    $visit->time_on_page,
                    $visit->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function applyDateFilters($query, $filter, $startDate, $endDate)
    {
        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', now());
                break;
            case 'yesterday':
                $query->whereDate('created_at', now()->subDay());
                break;
            case '7days':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case '30days':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case 'this_month':
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', now()->subMonth()->month)
                    ->whereYear('created_at', now()->subMonth()->year);
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    ]);
                }
                break;
        }
    }

    private function getAnalyticsData($request)
    {
        $filter = $request->get('filter', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Create cache key based on filters
        $cacheKey = 'analytics_' . md5($filter . $startDate . $endDate);

        // Cache analytics for 5 minutes to improve performance
        return cache()->remember($cacheKey, 300, function () use ($filter, $startDate, $endDate) {
            $baseQuery = PageVisit::query();
            $this->applyDateFilters($baseQuery, $filter, $startDate, $endDate);

            // Get basic metrics in a single optimized query
            $basicMetrics = $baseQuery
                ->selectRaw('
                    COUNT(*) as total_visits,
                    COUNT(CASE WHEN is_unique_visitor = 1 THEN 1 END) as unique_visitors,
                    COUNT(DISTINCT route_name) as total_pages,
                    AVG(CASE WHEN time_on_page IS NOT NULL THEN time_on_page END) as avg_time_on_page
                ')
                ->first();

            // Get device stats efficiently
            $deviceStats = (clone $baseQuery)
                ->select('device_type', DB::raw('count(*) as total'))
                ->groupBy('device_type')
                ->pluck('total', 'device_type')
                ->toArray();

            // Get top data with limits to improve performance
            $topPages = (clone $baseQuery)
                ->select('route_name', 'page_title', DB::raw('count(*) as total'))
                ->groupBy('route_name', 'page_title')
                ->orderByDesc('total')
                ->limit(5) // Reduced from 10 to 5
                ->get();

            $topCountries = (clone $baseQuery)
                ->whereNotNull('country')
                ->select('country', DB::raw('count(*) as total'))
                ->groupBy('country')
                ->orderByDesc('total')
                ->limit(5) // Reduced from 10 to 5
                ->get();

            $browserStats = (clone $baseQuery)
                ->whereNotNull('browser')
                ->select('browser', DB::raw('count(*) as total'))
                ->groupBy('browser')
                ->orderByDesc('total')
                ->limit(5) // Reduced from 10 to 5
                ->get();

            // Optimized daily visits - only last 5 days for better performance
            $dailyVisits = PageVisit::where('created_at', '>=', now()->subDays(5))
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as total'),
                    DB::raw('count(CASE WHEN is_unique_visitor = 1 THEN 1 END) as unique_count')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->limit(5) // Ensure maximum 5 data points
                ->get();

            // Ensure we always have clean data structure
            $dailyVisits = $dailyVisits->map(function ($item) {
                return [
                    'date' => $item->date,
                    'total' => (int)$item->total,
                    'unique_count' => (int)$item->unique_count
                ];
            });

            return [
                'total_visits' => $basicMetrics->total_visits ?? 0,
                'unique_visitors' => $basicMetrics->unique_visitors ?? 0,
                'total_pages' => $basicMetrics->total_pages ?? 0,
                'avg_time_on_page' => $basicMetrics->avg_time_on_page,
                'device_stats' => $deviceStats,
                'top_pages' => $topPages,
                'top_countries' => $topCountries,
                'browser_stats' => $browserStats,
                'daily_visits' => $dailyVisits,
            ];
        });
    }

    private function getFilterOptions()
    {
        // Cache filter options for 10 minutes since they don't change often
        return cache()->remember('analytics_filter_options', 600, function () {
            return [
                'countries' => PageVisit::whereNotNull('country')
                    ->distinct()
                    ->orderBy('country')
                    ->limit(50) // Limit to prevent huge lists
                    ->pluck('country'),

                'device_types' => PageVisit::distinct()
                    ->orderBy('device_type')
                    ->pluck('device_type'),

                'page_routes' => PageVisit::distinct()
                    ->orderBy('route_name')
                    ->limit(50) // Limit to prevent huge lists
                    ->pluck('route_name'),

                'browsers' => PageVisit::whereNotNull('browser')
                    ->distinct()
                    ->orderBy('browser')
                    ->limit(20) // Limit to prevent huge lists
                    ->pluck('browser'),
            ];
        });
    }

    /**
     * Get page title from route name
     */
    private function getPageTitle($routeName)
    {
        $titles = [
            'homepage' => 'Home Page',
            'Contact_Page' => 'Contact Us',
            'About_Page' => 'About Us',
            'Service_Page' => 'Our Services',
            'Sales_Page' => 'Sales',
            'Quiz_Page' => 'Sales Quiz',
            'NOEL_Form_Page' => 'NOEL Interest Form',
            'Quiz_Start_Page' => 'Quiz Start',
            'student' => 'Student Portal',
            'resource-hub' => 'Resource Hub',
            'event' => 'Events',
        ];

        return $titles[$routeName] ?? ucwords(str_replace(['_', '-'], ' ', $routeName));
    }

    /**
     * Run country backfill process via web interface
     */
    public function backfillCountries(Request $request)
    {
        $limit = (int) $request->get('limit', 50);

        try {
            // Run the backfill command
            Artisan::call('analytics:backfill-countries', [
                '--limit' => $limit
            ]);

            $output = Artisan::output();

            // Count remaining records that need updating
            $remaining = PageVisit::where(function ($query) {
                $query->where('country', 'Others')
                    ->orWhere('country', 'Unknown')
                    ->orWhereNull('country');
            })
                ->whereNotNull('ip_address')
                ->where('ip_address', '!=', '127.0.0.1')
                ->where('ip_address', 'not like', '192.168.%')
                ->where('ip_address', 'not like', '10.%')
                ->count();

            return response()->json([
                'success' => true,
                'message' => 'Country data updated successfully!',
                'output' => $output,
                'remaining' => $remaining,
                'processed' => $limit
            ]);
        } catch (\Exception $e) {
            Log::error('Country backfill failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update country data: ' . $e->getMessage()
            ], 500);
        }
    }
}
