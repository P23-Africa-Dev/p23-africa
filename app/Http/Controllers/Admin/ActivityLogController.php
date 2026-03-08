<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Get the IDs of users in the same organization (for scoping activity logs).
     * Returns null for admins (no filtering needed).
     */
    protected function getOrgUserIds(): ?array
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return null; // Admin sees everything
        }

        if ($user->organization_id) {
            return User::where('organization_id', $user->organization_id)->pluck('id')->toArray();
        }

        // Non-org user: only see activities by non-org users
        return User::whereNull('organization_id')->pluck('id')->toArray();
    }

    /**
     * Apply org scoping to an activity query.
     */
    protected function scopeToOrg($query): void
    {
        $orgUserIds = $this->getOrgUserIds();

        if ($orgUserIds !== null) {
            $query->whereIn('causer_id', $orgUserIds);
        }
    }

    /**
     * Display the activity logs
     */
    public function index(Request $request)
    {
        $query = Activity::with('causer')->latest();

        // Scope to organization
        $this->scopeToOrg($query);

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        // Filter by action/description
        if ($request->filled('action')) {
            $query->where('description', 'like', '%' . $request->action . '%');
        }

        // Filter by subject type
        if ($request->filled('subject_type')) {
            $query->where('subject_type', 'like', '%' . $request->subject_type . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in properties
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('properties', 'like', "%{$search}%")
                    ->orWhereHas('causer', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $logs = $query->paginate(30)->withQueryString();

        // Get unique users who have activities for filter dropdown
        $usersQuery = Activity::select('causer_id')
            ->with('causer')
            ->whereNotNull('causer_id');
        $this->scopeToOrg($usersQuery);
        $users = $usersQuery->distinct()
            ->get()
            ->pluck('causer')
            ->filter()
            ->unique('id')
            ->values();

        // Get unique subject types for filter dropdown
        $subjectTypesQuery = Activity::select('subject_type')
            ->whereNotNull('subject_type');
        $this->scopeToOrg($subjectTypesQuery);
        $subjectTypes = $subjectTypesQuery->distinct()
            ->pluck('subject_type')
            ->map(fn($type) => class_basename($type))
            ->unique()
            ->values();

        // Get unique actions for filter dropdown
        $actionsQuery = Activity::select('description');
        $this->scopeToOrg($actionsQuery);
        $actions = $actionsQuery->distinct()
            ->pluck('description')
            ->unique()
            ->values();

        // Mark all logs as seen when viewing the page
        $this->markAsSeen();

        return view('admin.activity_logs', compact('logs', 'users', 'subjectTypes', 'actions'));
    }

    /**
     * Get activity log statistics
     */
    public function stats()
    {
        $todayQ = Activity::whereDate('created_at', today());
        $this->scopeToOrg($todayQ);
        $today = $todayQ->count();

        $weekQ = Activity::where('created_at', '>=', now()->startOfWeek());
        $this->scopeToOrg($weekQ);
        $thisWeek = $weekQ->count();

        $monthQ = Activity::where('created_at', '>=', now()->startOfMonth());
        $this->scopeToOrg($monthQ);
        $thisMonth = $monthQ->count();

        $totalQ = Activity::query();
        $this->scopeToOrg($totalQ);
        $total = $totalQ->count();

        return response()->json([
            'today' => $today,
            'this_week' => $thisWeek,
            'this_month' => $thisMonth,
            'total' => $total,
        ]);
    }

    /**
     * Get unseen activity logs count for current user
     */
    public function getUnseenCount()
    {
        $user = Auth::user();

        $view = ActivityLogView::where('user_id', $user->id)->first();

        $query = Activity::query();

        // Scope to organization
        $this->scopeToOrg($query);

        if ($view && $view->last_seen_activity_id) {
            $query->where('id', '>', $view->last_seen_activity_id);
        }

        // Don't count activities caused by the current user
        $query->where(function ($q) use ($user) {
            $q->where('causer_id', '!=', $user->id)
                ->orWhereNull('causer_id');
        });

        $count = $query->count();

        return response()->json([
            'count' => $count,
            'has_unseen' => $count > 0,
        ]);
    }

    /**
     * Get new activities for browser notifications
     */
    public function getNewActivities()
    {
        $user = Auth::user();

        $view = ActivityLogView::where('user_id', $user->id)->first();

        $query = Activity::with('causer')->latest();

        // Scope to organization
        $this->scopeToOrg($query);

        if ($view && $view->last_seen_activity_id) {
            $query->where('id', '>', $view->last_seen_activity_id);
        }

        // Don't include activities caused by the current user
        $query->where(function ($q) use ($user) {
            $q->where('causer_id', '!=', $user->id)
                ->orWhereNull('causer_id');
        });

        $activities = $query->limit(5)->get()->map(function ($activity) {
            return [
                'id' => $activity->id,
                'description' => $activity->description,
                'causer_name' => $activity->causer?->name ?? 'System',
                'subject_type' => $activity->subject_type ? class_basename($activity->subject_type) : null,
                'created_at' => $activity->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'activities' => $activities,
            'count' => $activities->count(),
        ]);
    }

    /**
     * Mark activity logs as seen
     */
    public function markAsSeen()
    {
        $user = Auth::user();
        $latestActivity = Activity::latest()->first();

        if ($latestActivity) {
            ActivityLogView::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'last_seen_activity_id' => $latestActivity->id,
                    'last_viewed_at' => now(),
                ]
            );
        }

        return response()->json(['success' => true]);
    }
}
