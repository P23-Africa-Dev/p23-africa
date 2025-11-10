<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Student;
use App\Models\Blog;
use App\Models\PlaybookDownload;
use App\Models\SalesBookDownload;
use App\Models\UltimateFundingDownload;
use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get dashboard statistics
            $totalEvents = Event::count();
            $totalStudents = Student::count();
            $totalResources = Blog::count();

            // Total downloads from all playbook types
            $playbookDownloads = PlaybookDownload::count();
            $salesBookDownloads = SalesBookDownload::count();
            $ultimateFundingDownloads = UltimateFundingDownload::count();
            $totalDownloads = $playbookDownloads + $salesBookDownloads + $ultimateFundingDownloads;

            // Contact submissions stats
            $totalContacts = ContactSubmission::count();
            $recentContacts = ContactSubmission::where('created_at', '>=', now()->subDays(30))->count();
            $todayContacts = ContactSubmission::whereDate('created_at', today())->count();

            // Additional stats for more insights
            $recentEvents = Event::where('created_at', '>=', now()->subDays(30))->count();
            $recentStudents = Student::where('created_at', '>=', now()->subDays(30))->count();
            $recentResources = Blog::where('created_at', '>=', now()->subDays(30))->count();
            $recentDownloads = PlaybookDownload::where('created_at', '>=', now()->subDays(30))->count() +
                SalesBookDownload::where('created_at', '>=', now()->subDays(30))->count() +
                UltimateFundingDownload::where('created_at', '>=', now()->subDays(30))->count();

            $stats = [
                'total_events' => $totalEvents,
                'total_students' => $totalStudents,
                'total_resources' => $totalResources,
                'total_downloads' => $totalDownloads,
                'total_contacts' => $totalContacts,
                'playbook_downloads' => $playbookDownloads,
                'sales_book_downloads' => $salesBookDownloads,
                'ultimate_funding_downloads' => $ultimateFundingDownloads,
                'recent_events' => $recentEvents,
                'recent_students' => $recentStudents,
                'recent_resources' => $recentResources,
                'recent_downloads' => $recentDownloads,
                'recent_contacts' => $recentContacts,
                'today_contacts' => $todayContacts,
            ];

            return view('admin.dashboard', compact('stats'));
        } catch (\Exception $e) {
            // Log error and return default values
            Log::error('Dashboard stats error: ' . $e->getMessage());

            $stats = [
                'total_events' => 0,
                'total_students' => 0,
                'total_resources' => 0,
                'total_downloads' => 0,
                'total_contacts' => 0,
                'playbook_downloads' => 0,
                'sales_book_downloads' => 0,
                'ultimate_funding_downloads' => 0,
                'recent_events' => 0,
                'recent_students' => 0,
                'recent_resources' => 0,
                'recent_downloads' => 0,
                'recent_contacts' => 0,
                'today_contacts' => 0,
            ];

            return view('admin.dashboard', compact('stats'));
        }
    }
}
