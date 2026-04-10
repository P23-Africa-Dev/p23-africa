<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    protected function shouldApplyOrgScope(?User $user): bool
    {
        return $user && $user->appliesOrganizationScope();
    }

    protected function scopeByOrganization($query, ?User $user, string $column = 'organization_id'): void
    {
        if (!$this->shouldApplyOrgScope($user)) {
            return;
        }

        if (is_null($user->organization_id)) {
            $query->whereNull($column);
            return;
        }

        $query->where($column, $user->organization_id);
    }

    /**
     * Display Gantt chart view for all projects and tasks
     */
    public function gantt(Request $request)
    {
        $user = Auth::user();

        // Get projects with tasks
        if ($user->hasRole(['admin', 'supervisor'])) {
            $query = Project::with(['tasks.assignees', 'creator']);
        } else {
            // Staff sees only projects where they have assigned tasks
            $query = Project::whereHas('tasks.assignees', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['tasks' => function ($q) use ($user) {
                $q->whereHas('assignees', function ($sq) use ($user) {
                    $sq->where('user_id', $user->id);
                });
            }, 'tasks.assignees', 'creator']);
        }

        $this->scopeByOrganization($query, $user);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->orderBy('start_date')->orderBy('name')->get();

        // Calculate date range for the Gantt chart
        $minDate = now()->subMonth();
        $maxDate = now()->addMonths(3);

        foreach ($projects as $project) {
            if ($project->start_date && $project->start_date < $minDate) {
                $minDate = $project->start_date->copy();
            }
            if ($project->due_date && $project->due_date > $maxDate) {
                $maxDate = $project->due_date->copy();
            }
            foreach ($project->tasks as $task) {
                if ($task->due_date && $task->due_date > $maxDate) {
                    $maxDate = $task->due_date->copy();
                }
            }
        }

        // Extend range a bit for better visualization
        $minDate = $minDate->startOfMonth();
        $maxDate = $maxDate->endOfMonth();

        $statuses = Project::getStatuses();

        return view('admin.projects.gantt', compact('projects', 'minDate', 'maxDate', 'statuses'));
    }

    public function index()
    {
        $user = Auth::user();

        $query = Project::with(['creator', 'tasks'])->latest();

        $this->scopeByOrganization($query, $user);

        if ($this->shouldApplyOrgScope($user)) {
            // Staff only sees projects they're assigned to
            if (!$user->hasRole('supervisor') && !$user->hasRole('admin')) {
                $query->whereHas('tasks.assignees', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
        }

        $projects = $query->paginate(10);

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);

        $statuses = Project::getStatuses();
        $priorities = Project::getPriorities();

        return view('admin.projects.create', compact('statuses', 'priorities'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => $request->filled('start_date')
                ? 'nullable|date|after_or_equal:start_date'
                : 'nullable|date',
        ];

        $data = $request->validate($rules);

        // Convert empty date strings to null for MySQL compatibility
        $data['start_date'] = $data['start_date'] ?: null;
        $data['due_date'] = $data['due_date'] ?: null;

        $data['created_by'] = Auth::id();
        // Set organization for the project (creator's org)
        $data['organization_id'] = Auth::user()->organization_id ?? null;

        $project = Project::create($data);

        // Send notification to admins
        AdminNotificationService::notifyNewProject($project, Auth::user());

        activity()
            ->causedBy(Auth::user())
            ->performedOn($project)
            ->withProperties(['name' => $project->name, 'status' => $project->status])
            ->log('Created project');

        return orgRedirect('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        $project->load(['creator', 'tasks.assignees', 'tasks.creator']);

        $statuses = \App\Models\Task::getStatuses();
        $priorities = \App\Models\Task::getPriorities();

        // Get assignable users based on current user's role
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            // Admin can assign to supervisors and staff
            $assignableUsers = User::role(['supervisor', 'staff'])->get();
        } elseif ($user->hasRole('supervisor')) {
            // Supervisor can only assign to staff
            $assignableUsers = User::role('staff')->get();
        } else {
            $assignableUsers = collect();
        }

        if ($this->shouldApplyOrgScope($user)) {
            $assignableUsers = $assignableUsers->where('organization_id', $user->organization_id)->values();
        }

        return view('admin.projects.show', compact('project', 'statuses', 'priorities', 'assignableUsers'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $statuses = Project::getStatuses();
        $priorities = Project::getPriorities();

        return view('admin.projects.edit', compact('project', 'statuses', 'priorities'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => $request->filled('start_date')
                ? 'nullable|date|after_or_equal:start_date'
                : 'nullable|date',
        ];

        $data = $request->validate($rules);

        // Convert empty date strings to null for MySQL compatibility
        $data['start_date'] = $data['start_date'] ?: null;
        $data['due_date'] = $data['due_date'] ?: null;

        $project->update($data);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($project)
            ->withProperties(['name' => $project->name])
            ->log('Updated project');

        return orgRedirect('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $projectName = $project->name;
        $projectId = $project->id;

        $project->delete();

        activity()
            ->causedBy(Auth::user())
            ->withProperties(['name' => $projectName, 'project_id' => $projectId])
            ->log('Deleted project');

        return orgRedirect('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    public function updateStatus(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
        ]);

        $project->update(['status' => $request->status]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($project)
            ->withProperties(['name' => $project->name, 'new_status' => $request->status])
            ->log('Updated project status');

        return response()->json([
            'success' => true,
            'message' => 'Project status updated successfully.',
            'status' => $project->status,
            'status_label' => Project::getStatuses()[$project->status],
            'badge_class' => $project->status_badge_class,
        ]);
    }
}
