<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Admin and Supervisor see all projects, Staff sees projects they're assigned to
        if ($user->hasRole(['admin', 'supervisor'])) {
            $projects = Project::with(['creator', 'tasks'])->latest()->paginate(10);
        } else {
            // Staff sees projects where they have assigned tasks
            $projects = Project::whereHas('tasks.assignees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['creator', 'tasks'])->latest()->paginate(10);
        }

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

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data['created_by'] = Auth::id();

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
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

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    public function updateStatus(Request $request, Project $project)
    {
        $request->validate([
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
        ]);

        $project->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Project status updated successfully.',
            'status' => $project->status,
            'status_label' => Project::getStatuses()[$project->status],
            'badge_class' => $project->status_badge_class,
        ]);
    }
}
