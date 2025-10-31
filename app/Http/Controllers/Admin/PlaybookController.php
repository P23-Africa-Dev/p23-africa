<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaybookDownload;
use App\Models\SalesBookDownload;
use App\Models\UltimateFundingDownload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PlaybookController extends Controller
{
    public function dashboard()
    {
        try {
            // Get all statistics
            $playbookDownloads = PlaybookDownload::count();
            $salesBookDownloads = SalesBookDownload::count();
            $ultimateFundingDownloads = UltimateFundingDownload::count();
            $ultimateFundingPaid = UltimateFundingDownload::where('payment_completed', true)->count();
            $totalRevenue = UltimateFundingDownload::where('payment_completed', true)->sum('amount_paid');

            // Recent downloads
            $recentPlaybookDownloads = PlaybookDownload::latest()->take(5)->get();
            $recentSalesBookDownloads = SalesBookDownload::latest()->take(5)->get();
            $recentUltimateFundingDownloads = UltimateFundingDownload::latest()->take(5)->get();

            $stats = [
                'playbook_downloads' => $playbookDownloads,
                'sales_book_downloads' => $salesBookDownloads,
                'ultimate_funding_downloads' => $ultimateFundingDownloads,
                'ultimate_funding_paid' => $ultimateFundingPaid,
                'total_revenue' => $totalRevenue,
                'total_downloads' => $playbookDownloads + $salesBookDownloads + $ultimateFundingDownloads,
            ];

            $recentDownloads = [
                'playbook' => $recentPlaybookDownloads,
                'sales_book' => $recentSalesBookDownloads,
                'ultimate_funding' => $recentUltimateFundingDownloads,
            ];

            return view('admin.playbooks.dashboard', compact('stats', 'recentDownloads'));
        } catch (\Exception $e) {
            Log::error('Error loading playbook dashboard', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'An error occurred loading the dashboard.');
        }
    }

    public function index()
    {
        $playbooks = PlaybookDownload::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.playbooks.index', compact('playbooks'));
    }

    public function destroy(PlaybookDownload $playbook)
    {
        if ($playbook->image_path) Storage::disk('public')->delete($playbook->image_path);
        $playbook->delete();
        return redirect()->route('admin.playbooks.index')->with('success', 'Playbook download record deleted successfully.');
    }
}
