<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AfricanExpansionReportController extends Controller
{
    private const VISIT_ROUTE = 'African_Expansion_Landing_Page';

    private const DOWNLOAD_ROUTE = 'African_Expansion_Guide_Download';

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $baseQuery = PageVisit::query()->whereIn('route_name', [self::VISIT_ROUTE, self::DOWNLOAD_ROUTE]);
        $this->applyDateFilters($baseQuery, $filter, $startDate, $endDate);

        $totalVisits = (clone $baseQuery)->where('route_name', self::VISIT_ROUTE)->count();
        $totalDownloads = (clone $baseQuery)->where('route_name', self::DOWNLOAD_ROUTE)->count();
        $visitCountries = (clone $baseQuery)->where('route_name', self::VISIT_ROUTE)
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->pluck('country')
            ->unique()
            ->count();
        $downloadCountries = (clone $baseQuery)->where('route_name', self::DOWNLOAD_ROUTE)
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->pluck('country')
            ->unique()
            ->count();

        $countryExpr = "COALESCE(NULLIF(country, ''), 'Unknown')";
        $visitCase = "SUM(CASE WHEN route_name = '" . self::VISIT_ROUTE . "' THEN 1 ELSE 0 END)";
        $downloadCase = "SUM(CASE WHEN route_name = '" . self::DOWNLOAD_ROUTE . "' THEN 1 ELSE 0 END)";

        $countries = (clone $baseQuery)
            ->selectRaw("{$countryExpr} as country_name, {$visitCase} as visit_count, {$downloadCase} as download_count")
            ->groupBy(DB::raw($countryExpr))
            ->orderByRaw("({$visitCase} + {$downloadCase}) DESC")
            ->get();

        $activity = (clone $baseQuery)
            ->select([
                'id',
                'route_name',
                'page_title',
                'device_type',
                'ip_address',
                'browser',
                'browser_version',
                'country',
                'city',
                'created_at',
            ])
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.african-expansion.index', [
            'filter' => $filter,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalVisits' => $totalVisits,
            'totalDownloads' => $totalDownloads,
            'visitCountries' => $visitCountries,
            'downloadCountries' => $downloadCountries,
            'countries' => $countries,
            'activity' => $activity,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filter = $request->get('filter', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = PageVisit::query()->whereIn('route_name', [self::VISIT_ROUTE, self::DOWNLOAD_ROUTE]);
        $this->applyDateFilters($query, $filter, $startDate, $endDate);
        $rows = $query->orderByDesc('created_at')->get();

        $filename = 'african_expansion_guide_' . date('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID',
                'Type',
                'Page Title',
                'Device',
                'IP Address',
                'Country',
                'City',
                'Browser',
                'Date',
            ]);

            foreach ($rows as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->route_name === self::DOWNLOAD_ROUTE ? 'Guide open' : 'Visit',
                    $row->page_title,
                    ucfirst((string) $row->device_type),
                    $row->ip_address,
                    $row->country,
                    $row->city,
                    trim(($row->browser ?? '') . ' ' . ($row->browser_version ?? '')),
                    optional($row->created_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function applyDateFilters($query, string $filter, $startDate, $endDate): void
    {
        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', now());
                break;
            case '7days':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case '30days':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay(),
                    ]);
                }
                break;
        }
    }
}
