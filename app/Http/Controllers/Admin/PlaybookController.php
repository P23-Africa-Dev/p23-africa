<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaybookDownload;
use App\Models\SalesBookDownload;
use Illuminate\Support\Facades\Storage;

class PlaybookController extends Controller
{
    public function dashboard()
    {
        // Get statistics for dashboard
        $foundersPlaybookCount = PlaybookDownload::count();
        $salesBookCount = SalesBookDownload::count();
        $totalDownloads = $foundersPlaybookCount + $salesBookCount;
        $monthlyDownloads = PlaybookDownload::where('created_at', '>=', now()->subDays(30))->count() +
            SalesBookDownload::where('created_at', '>=', now()->subDays(30))->count();

        // Weekly statistics
        $foundersPlaybookWeek = PlaybookDownload::where('created_at', '>=', now()->subDays(7))->count();
        $salesBookWeek = SalesBookDownload::where('created_at', '>=', now()->subDays(7))->count();

        // Monthly statistics
        $foundersPlaybookMonth = PlaybookDownload::where('created_at', '>=', now()->subDays(30))->count();
        $salesBookMonth = SalesBookDownload::where('created_at', '>=', now()->subDays(30))->count();

        // Last downloads
        $foundersPlaybookLast = PlaybookDownload::latest()->first();
        $salesBookLast = SalesBookDownload::latest()->first();

        // Recent downloads (mixed from both tables)
        $recentPlaybooks = PlaybookDownload::latest()->take(5)->get();
        $recentSalesBooks = SalesBookDownload::latest()->take(5)->get();
        $recentDownloads = collect($recentPlaybooks)->merge($recentSalesBooks)
            ->sortByDesc('created_at')->take(10);

        return view('admin.playbooks.dashboard', compact(
            'foundersPlaybookCount',
            'salesBookCount',
            'totalDownloads',
            'monthlyDownloads',
            'foundersPlaybookWeek',
            'salesBookWeek',
            'foundersPlaybookMonth',
            'salesBookMonth',
            'foundersPlaybookLast',
            'salesBookLast',
            'recentDownloads'
        ));
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
