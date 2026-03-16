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
use App\Models\ConsultationApplication;
use App\Models\Project;
use App\Models\Task;
use App\Models\Lead;
use App\Models\LeadDrive;
use App\Models\LeadStatus;
use App\Models\Attendance;
use App\Http\Controllers\Admin\ConsultationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $isAdmin = $user->hasRole('admin');
            $isOrgUser = !$isAdmin && $user->organization_id;

            $stats = [];

            // ── Admin / non-org stats (events, students, resources, etc.) ──
            if ($isAdmin || !$user->organization_id) {
                $stats['total_events'] = Event::count();
                $stats['total_students'] = Student::count();
                $stats['total_resources'] = Blog::count();

                $playbookDownloads = PlaybookDownload::count();
                $salesBookDownloads = SalesBookDownload::count();
                $ultimateFundingDownloads = UltimateFundingDownload::count();
                $stats['total_downloads'] = $playbookDownloads + $salesBookDownloads + $ultimateFundingDownloads;
                $stats['playbook_downloads'] = $playbookDownloads;
                $stats['sales_book_downloads'] = $salesBookDownloads;
                $stats['ultimate_funding_downloads'] = $ultimateFundingDownloads;

                $stats['total_contacts'] = ContactSubmission::count();
                $stats['recent_contacts'] = ContactSubmission::where('created_at', '>=', now()->subDays(30))->count();
                $stats['today_contacts'] = ContactSubmission::whereDate('created_at', today())->count();

                $consultationController = new ConsultationController();
                $consultationStats = $consultationController->getConsultationStats();
                $stats['total_consultations'] = $consultationStats['total_applications'];
                $stats['pending_consultations'] = $consultationStats['pending_applications'];
                $stats['paid_consultations'] = $consultationStats['paid_applications'];
                $stats['scheduled_consultations'] = $consultationStats['scheduled_applications'];
                $stats['today_consultations'] = $consultationStats['today_applications'];
                $stats['recent_consultations'] = $consultationStats['recent_applications'];

                $stats['recent_events'] = Event::where('created_at', '>=', now()->subDays(30))->count();
                $stats['recent_students'] = Student::where('created_at', '>=', now()->subDays(30))->count();
                $stats['recent_resources'] = Blog::where('created_at', '>=', now()->subDays(30))->count();
                $stats['recent_downloads'] = PlaybookDownload::where('created_at', '>=', now()->subDays(30))->count() +
                    SalesBookDownload::where('created_at', '>=', now()->subDays(30))->count() +
                    UltimateFundingDownload::where('created_at', '>=', now()->subDays(30))->count();
            }

            // ── Organization-scoped project / task / lead stats ──
            // Build org-scoped query helper
            $orgScope = function ($query) use ($isAdmin, $isOrgUser, $user) {
                if (!$isAdmin) {
                    if ($isOrgUser) {
                        $query->where('organization_id', $user->organization_id);
                    } else {
                        $query->whereNull('organization_id');
                    }
                }
                return $query;
            };

            $stats['total_projects'] = $orgScope(Project::query())->count();
            $stats['active_projects'] = $orgScope(Project::where('status', 'in_progress'))->count();
            $stats['total_tasks'] = $orgScope(Task::query())->count();
            $stats['completed_tasks'] = $orgScope(Task::where('status', 'completed'))->count();
            $stats['pending_tasks'] = $orgScope(Task::whereIn('status', ['todo', 'in_progress']))->count();
            $stats['total_leads'] = $orgScope(Lead::query())->count();
            $leadOrgId = $isAdmin ? null : ($user->organization_id ?? null);
            $defaultSlug = LeadStatus::getDefaultSlug($leadOrgId);
            $stats['new_leads'] = $orgScope(Lead::where('status', $defaultSlug))->count();

            // ── Comprehensive CRM stats ──────────────────────────────────
            $wonSlugs  = LeadStatus::getWonSlugs($leadOrgId);
            $lostSlugs = LeadStatus::getLostSlugs($leadOrgId);

            $stats['won_leads']  = $wonSlugs  ? $orgScope(Lead::whereIn('status', $wonSlugs))->count() : 0;
            $stats['lost_leads'] = $lostSlugs ? $orgScope(Lead::whereIn('status', $lostSlugs))->count() : 0;

            // Active = total minus won minus lost
            $terminalSlugs = array_merge($wonSlugs, $lostSlugs);
            $stats['active_leads'] = $terminalSlugs
                ? $orgScope(Lead::whereNotIn('status', $terminalSlugs))->count()
                : $stats['total_leads'];

            // Conversion rate (won / (won + lost)) — avoid division by zero
            $closedCount = $stats['won_leads'] + $stats['lost_leads'];
            $stats['conversion_rate'] = $closedCount > 0
                ? round(($stats['won_leads'] / $closedCount) * 100, 1)
                : 0;

            // Pipeline value (estimated_value of active leads)
            $stats['pipeline_value'] = $terminalSlugs
                ? (float) $orgScope(Lead::whereNotIn('status', $terminalSlugs))->sum('estimated_value')
                : (float) $orgScope(Lead::query())->sum('estimated_value');
            $stats['won_value'] = $wonSlugs
                ? (float) $orgScope(Lead::whereIn('status', $wonSlugs))->sum('estimated_value')
                : 0;

            // Overdue follow-ups
            $stats['overdue_follow_ups'] = $orgScope(
                Lead::whereNotNull('next_follow_up')
                    ->where('next_follow_up', '<', Carbon::now())
                    ->when(!empty($terminalSlugs), fn($q) => $q->whereNotIn('status', $terminalSlugs))
            )->count();

            // This month
            $startOfMonth = Carbon::now()->startOfMonth();
            $stats['this_month_leads'] = $orgScope(Lead::where('created_at', '>=', $startOfMonth))->count();
            $stats['this_month_won'] = $wonSlugs
                ? $orgScope(Lead::whereIn('status', $wonSlugs)->where('converted_at', '>=', $startOfMonth))->count()
                : 0;

            // Per-status breakdown [ slug => ['name'=>..,'color'=>..,'count'=>..] ]
            $allStatuses = LeadStatus::getForOrg($leadOrgId);
            $statusBreakdown = [];
            foreach ($allStatuses as $st) {
                $statusBreakdown[$st->slug] = [
                    'name'  => $st->name,
                    'color' => $st->color,
                    'count' => $orgScope(Lead::where('status', $st->slug))->count(),
                ];
            }
            $stats['status_breakdown'] = $statusBreakdown;

            // Per-drive breakdown
            $drives = LeadDrive::when(!$isAdmin, function ($q) use ($user) {
                $q->where('organization_id', $user->organization_id);
            })->get();
            $driveBreakdown = [];
            foreach ($drives as $d) {
                $driveBreakdown[] = [
                    'name'  => $d->name,
                    'color' => $d->color,
                    'count' => $orgScope(Lead::where('drive_id', $d->id))->count(),
                    'value' => (float) $orgScope(Lead::where('drive_id', $d->id))->sum('estimated_value'),
                ];
            }
            $stats['drive_breakdown'] = $driveBreakdown;

            // CRM currency symbol
            $stats['crm_currency'] = '$';
            if ($isOrgUser) {
                $org = \App\Models\Organization::find($user->organization_id);
                $stats['crm_currency'] = $org ? $org->getCrmCurrencySymbol() : '$';
            }

            $hasSignedInToday = Attendance::hasSignedInToday($user->id);

            return view('admin.dashboard', compact('stats', 'hasSignedInToday'));
        } catch (\Exception $e) {
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
                'total_projects' => 0,
                'active_projects' => 0,
                'total_tasks' => 0,
                'completed_tasks' => 0,
                'pending_tasks' => 0,
                'total_leads' => 0,
                'new_leads' => 0,
                'won_leads' => 0,
                'lost_leads' => 0,
                'active_leads' => 0,
                'conversion_rate' => 0,
                'pipeline_value' => 0,
                'won_value' => 0,
                'overdue_follow_ups' => 0,
                'this_month_leads' => 0,
                'this_month_won' => 0,
                'status_breakdown' => [],
                'drive_breakdown' => [],
                'crm_currency' => '$',
            ];

            $hasSignedInToday = false;
            try {
                $hasSignedInToday = Attendance::hasSignedInToday(Auth::id());
            } catch (\Exception $ex) {
            }

            return view('admin.dashboard', compact('stats', 'hasSignedInToday'));
        }
    }
}
