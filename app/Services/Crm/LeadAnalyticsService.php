<?php

namespace App\Services\Crm;

use App\Models\Lead;
use App\Models\LeadDrive;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LeadAnalyticsService
{
    /**
     * Get lead creation activity grouped by user (staff + supervisors).
     * 
     * @param User $currentUser The authenticated user requesting the data
     * @param array $filters Filters: date_from, date_to, user_ids, drive_ids, lead_type
     * @return array
     */
    public function getLeadActivityByUser(User $currentUser, array $filters = []): array
    {
        // Determine date range
        $dateFrom = $this->parseDateFrom($filters['date_from'] ?? null);
        $dateTo = $this->parseDateTo($filters['date_to'] ?? null);

        // Get users to track (staff + supervisors within the organization)
        $trackedUsers = $this->getTrackedUsers($currentUser);

        // Build main query
        $query = Lead::query()
            ->select('created_by')
            ->selectRaw('COUNT(*) as total_leads')
            ->selectRaw("SUM(CASE WHEN source_type = 'file_import' THEN 1 ELSE 0 END) as imported_leads")
            ->selectRaw("SUM(CASE WHEN source_type != 'file_import' AND source_type IS NOT NULL THEN 1 ELSE 0 END) as manual_leads")
            ->selectRaw("SUM(CASE WHEN source_type IS NULL THEN 1 ELSE 0 END) as direct_leads")
            ->selectRaw('MAX(created_at) as last_activity_at')
            ->selectRaw('COUNT(DISTINCT drive_id) as pipelines_used')
            ->whereNotNull('created_by')
            ->whereIn('created_by', $trackedUsers->pluck('id')->toArray())
            ->whereDate('created_at', '>=', $dateFrom->toDateString())
            ->whereDate('created_at', '<=', $dateTo->toDateString());

        // Apply organization scope
        $this->applyOrgScope($query, $currentUser);

        // Apply user filter
        if (!empty($filters['user_ids'])) {
            $userIds = (array) $filters['user_ids'];
            $query->whereIn('created_by', $userIds);
            $trackedUsers = $trackedUsers->whereIn('id', $userIds);
        }

        // Apply drive/pipeline filter
        if (!empty($filters['drive_ids'])) {
            $query->whereIn('drive_id', (array) $filters['drive_ids']);
        }

        // Apply lead type filter
        if (!empty($filters['lead_type']) && $filters['lead_type'] !== 'all') {
            if ($filters['lead_type'] === 'imported') {
                $query->where('source_type', 'file_import');
            } elseif ($filters['lead_type'] === 'manual') {
                $query->whereNot('source_type', 'file_import');
            }
        }

        $query->groupBy('created_by');

        $counts = $query->get()->keyBy('created_by');

        // Map to user objects with stats
        $staffLeadStats = $trackedUsers->map(function (User $user) use ($counts) {
            $row = $counts->get($user->id);

            return [
                'user' => $user,
                'user_id' => $user->id,
                'total_leads' => (int) ($row->total_leads ?? 0),
                'imported_leads' => (int) ($row->imported_leads ?? 0),
                'manual_leads' => (int) ($row->manual_leads ?? 0),
                'direct_leads' => (int) ($row->direct_leads ?? 0),
                'last_activity_at' => isset($row->last_activity_at)
                    ? Carbon::parse($row->last_activity_at)
                    : null,
                'pipelines_used' => (int) ($row->pipelines_used ?? 0),
            ];
        })->sortByDesc('total_leads')->values();

        // Calculate active user stats
        $activeStaffStats = $staffLeadStats->where('total_leads', '>', 0);
        $activeCount = $activeStaffStats->count();

        // Calculate summary totals
        $summary = [
            'users_count' => $activeCount,
            'total_leads' => $staffLeadStats->sum('total_leads'),
            'imported_leads' => $staffLeadStats->sum('imported_leads'),
            'manual_leads' => $staffLeadStats->sum('manual_leads'),
            'direct_leads' => $staffLeadStats->sum('direct_leads'),
            'avg_leads_per_user' => $activeCount > 0
                ? round($staffLeadStats->sum('total_leads') / $activeCount, 2)
                : 0,
        ];

        return [
            'stats' => $staffLeadStats,
            'summary' => $summary,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'filters' => $filters,
        ];
    }

    /**
     * Get lead creation breakdown by pipeline/drive.
     */
    public function getLeadActivityByPipeline(User $currentUser, array $filters = []): Collection
    {
        $dateFrom = $this->parseDateFrom($filters['date_from'] ?? null);
        $dateTo = $this->parseDateTo($filters['date_to'] ?? null);

        $trackedUsers = $this->getTrackedUsers($currentUser);

        $query = Lead::query()
            ->select('drive_id')
            ->selectRaw('COUNT(*) as total_leads')
            ->selectRaw("SUM(CASE WHEN source_type = 'file_import' THEN 1 ELSE 0 END) as imported_leads")
            ->selectRaw("SUM(CASE WHEN source_type != 'file_import' AND source_type IS NOT NULL THEN 1 ELSE 0 END) as manual_leads")
            ->whereIn('created_by', $trackedUsers->pluck('id')->toArray())
            ->whereDate('created_at', '>=', $dateFrom->toDateString())
            ->whereDate('created_at', '<=', $dateTo->toDateString());

        $this->applyOrgScope($query, $currentUser);

        // Apply user filter
        if (!empty($filters['user_ids'])) {
            $query->whereIn('created_by', (array) $filters['user_ids']);
        }

        // Apply lead type filter
        if (!empty($filters['lead_type']) && $filters['lead_type'] !== 'all') {
            if ($filters['lead_type'] === 'imported') {
                $query->where('source_type', 'file_import');
            } elseif ($filters['lead_type'] === 'manual') {
                $query->whereNot('source_type', 'file_import');
            }
        }

        $query->groupBy('drive_id')->orderByDesc('total_leads');

        $results = $query->get();

        // Load drive information
        return $results->map(function ($row) {
            $drive = LeadDrive::find($row->drive_id);
            return [
                'drive' => $drive,
                'drive_name' => $drive?->name ?? 'Unassigned',
                'total_leads' => (int) $row->total_leads,
                'imported_leads' => (int) $row->imported_leads,
                'manual_leads' => (int) $row->manual_leads,
            ];
        });
    }

    /**
     * Get detailed lead activity for a specific user.
     */
    public function getUserLeadDetails(User $currentUser, int $userId, array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        // Verify user has access to view this user
        $targetUser = User::find($userId);
        if (!$targetUser || !$this->canViewUser($currentUser, $targetUser)) {
            abort(403, 'You do not have access to view this user\'s lead activity.');
        }

        $dateFrom = $this->parseDateFrom($filters['date_from'] ?? null);
        $dateTo = $this->parseDateTo($filters['date_to'] ?? null);

        $query = Lead::query()
            ->where('created_by', $userId)
            ->whereDate('created_at', '>=', $dateFrom->toDateString())
            ->whereDate('created_at', '<=', $dateTo->toDateString())
            ->with(['drive', 'assignedUser'])
            ->orderByDesc('created_at');

        $this->applyOrgScope($query, $currentUser);

        // Apply drive filter
        if (!empty($filters['drive_ids'])) {
            $query->whereIn('drive_id', (array) $filters['drive_ids']);
        }

        // Apply lead type filter
        if (!empty($filters['lead_type']) && $filters['lead_type'] !== 'all') {
            if ($filters['lead_type'] === 'imported') {
                $query->where('source_type', 'file_import');
            } elseif ($filters['lead_type'] === 'manual') {
                $query->whereNot('source_type', 'file_import');
            }
        }

        return $query->paginate(15);
    }

    /**
     * Get available tracked users for filtering (staff + supervisors).
     */
    public function getAvailableUsers(User $currentUser): Collection
    {
        $query = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['staff', 'supervisor']);
        })->orderBy('name');

        $this->applyOrgScope($query, $currentUser);

        return $query->get();
    }

    /**
     * Parse date_from filter.
     */
    protected function parseDateFrom(?string $date): Carbon
    {
        if ($date) {
            return Carbon::parse($date)->startOfDay();
        }
        return now()->startOfMonth();
    }

    /**
     * Parse date_to filter.
     */
    protected function parseDateTo(?string $date): Carbon
    {
        if ($date) {
            return Carbon::parse($date)->endOfDay();
        }
        return now()->endOfDay();
    }

    /**
     * Get tracked users (staff + supervisors) for the organization.
     */
    protected function getTrackedUsers(User $currentUser): Collection
    {
        $query = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['staff', 'supervisor']);
        })->orderBy('name');

        $this->applyOrgScope($query, $currentUser);

        return $query->get();
    }

    /**
     * Apply organization scope to a query.
     */
    protected function applyOrgScope($query, User $currentUser): void
    {
        if (!$currentUser || !$currentUser->appliesOrganizationScope()) {
            return;
        }

        if (is_null($currentUser->organization_id)) {
            $query->whereNull('organization_id');
            return;
        }

        $query->where('organization_id', $currentUser->organization_id);
    }

    /**
     * Check if current user can view another user's activity.
     */
    protected function canViewUser(User $currentUser, User $targetUser): bool
    {
        // Admins can view any user in their organization
        if ($currentUser->hasRole('admin')) {
            return true;
        }

        // Supervisors can only view staff users in their organization
        if ($currentUser->hasRole('supervisor')) {
            if ($targetUser->hasRole('staff') && $targetUser->organization_id === $currentUser->organization_id) {
                return true;
            }
        }

        return false;
    }
}
