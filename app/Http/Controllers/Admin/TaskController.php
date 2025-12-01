<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $projectId = $request->get('project_id');

        $query = Task::with(['project', 'assignees', 'creator']);

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        // Staff only sees tasks assigned to them
        if (!$user->hasRole(['admin', 'supervisor'])) {
            $query->whereHas('assignees', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $tasks = $query->orderBy('order')->latest()->paginate(15);
        $projects = Project::all();

        return view('admin.tasks.index', compact('tasks', 'projects', 'projectId'));
    }

    public function kanban(Request $request)
    {
        $user = Auth::user();
        $projectId = $request->get('project_id');

        $query = Task::with(['project', 'assignees', 'creator']);

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        // Staff only sees tasks assigned to them
        if (!$user->hasRole(['admin', 'supervisor'])) {
            $query->whereHas('assignees', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $tasks = $query->orderBy('order')->get();

        $tasksByStatus = [
            'todo' => $tasks->where('status', 'todo')->values(),
            'in_progress' => $tasks->where('status', 'in_progress')->values(),
            'review' => $tasks->where('status', 'review')->values(),
            'completed' => $tasks->where('status', 'completed')->values(),
        ];

        $projects = Project::all();
        $statuses = Task::getStatuses();

        // Get assignable users based on current user's role
        if ($user->hasRole('admin')) {
            $assignableUsers = User::role(['supervisor', 'staff'])->get();
        } elseif ($user->hasRole('supervisor')) {
            $assignableUsers = User::role('staff')->get();
        } else {
            $assignableUsers = collect();
        }

        return view('admin.tasks.kanban', compact('tasksByStatus', 'projects', 'projectId', 'statuses', 'assignableUsers'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole(['admin', 'supervisor'])) {
            abort(403, 'Only admins and supervisors can create tasks.');
        }

        $projects = Project::all();
        $statuses = Task::getStatuses();
        $priorities = Task::getPriorities();

        // Get assignable users
        if ($user->hasRole('admin')) {
            $assignableUsers = User::role(['supervisor', 'staff'])->get();
        } else {
            $assignableUsers = User::role('staff')->get();
        }

        $selectedProjectId = $request->get('project_id');

        return view('admin.tasks.create', compact('projects', 'statuses', 'priorities', 'assignableUsers', 'selectedProjectId'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole(['admin', 'supervisor'])) {
            abort(403, 'Only admins and supervisors can create tasks.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'status' => 'required|in:todo,in_progress,review,completed',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'assignees' => 'nullable|array',
            'assignees.*' => 'exists:users,id',
        ]);

        // Convert empty string to null for due_date
        if (empty($data['due_date'])) {
            $data['due_date'] = null;
        }

        $data['created_by'] = Auth::id();
        $data['order'] = Task::where('project_id', $data['project_id'])->max('order') + 1;

        $task = Task::create($data);

        // Assign users to the task
        if (!empty($data['assignees'])) {
            $this->validateAssignees($user, $data['assignees']);

            $assigneeData = [];
            foreach ($data['assignees'] as $userId) {
                $assigneeData[$userId] = ['assigned_by' => Auth::id()];
            }
            $task->assignees()->sync($assigneeData);
        }

        activity()
            ->causedBy(Auth::user())
            ->performedOn($task)
            ->withProperties(['title' => $task->title, 'project_id' => $task->project_id])
            ->log('Created task');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully.',
                'task' => $task->load('assignees'),
            ]);
        }

        return redirect()->route('admin.tasks.kanban', ['project_id' => $task->project_id])
            ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $user = Auth::user();

        if (!$user->hasRole(['admin', 'supervisor'])) {
            abort(403, 'Only admins and supervisors can edit tasks.');
        }

        $projects = Project::all();
        $statuses = Task::getStatuses();
        $priorities = Task::getPriorities();

        if ($user->hasRole('admin')) {
            $assignableUsers = User::role(['supervisor', 'staff'])->get();
        } else {
            $assignableUsers = User::role('staff')->get();
        }

        $task->load('assignees');

        return view('admin.tasks.edit', compact('task', 'projects', 'statuses', 'priorities', 'assignableUsers'));
    }

    public function update(Request $request, Task $task)
    {
        $user = Auth::user();

        if (!$user->hasRole(['admin', 'supervisor'])) {
            abort(403, 'Only admins and supervisors can edit tasks.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'status' => 'required|in:todo,in_progress,review,completed',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'assignees' => 'nullable|array',
            'assignees.*' => 'exists:users,id',
        ]);

        // Convert empty string to null for due_date
        if (empty($data['due_date'])) {
            $data['due_date'] = null;
        }

        $task->update($data);

        // Update assignees
        if (isset($data['assignees'])) {
            $this->validateAssignees($user, $data['assignees']);

            $assigneeData = [];
            foreach ($data['assignees'] as $userId) {
                $assigneeData[$userId] = ['assigned_by' => Auth::id()];
            }
            $task->assignees()->sync($assigneeData);
        }

        activity()
            ->causedBy(Auth::user())
            ->performedOn($task)
            ->withProperties(['title' => $task->title])
            ->log('Updated task');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully.',
                'task' => $task->load('assignees'),
            ]);
        }

        return redirect()->route('admin.tasks.kanban', ['project_id' => $task->project_id])
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $user = Auth::user();

        if (!$user->hasRole(['admin', 'supervisor'])) {
            abort(403, 'Only admins and supervisors can delete tasks.');
        }

        $projectId = $task->project_id;
        $taskTitle = $task->title;
        $taskId = $task->id;

        $task->delete();

        activity()
            ->causedBy(Auth::user())
            ->withProperties(['title' => $taskTitle, 'task_id' => $taskId, 'project_id' => $projectId])
            ->log('Deleted task');

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully.',
            ]);
        }

        return redirect()->route('admin.tasks.kanban', ['project_id' => $projectId])
            ->with('success', 'Task deleted successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $user = Auth::user();

        // Check if user can update this task (assigned to them or admin/supervisor)
        $canUpdate = $user->hasRole(['admin', 'supervisor']) ||
            $task->assignees->contains('id', $user->id);

        if (!$canUpdate) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this task.',
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:todo,in_progress,review,completed',
        ]);

        $task->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully.',
            'status' => $task->status,
            'status_label' => Task::getStatuses()[$task->status],
            'badge_class' => $task->status_badge_class,
        ]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:tasks,id',
            'tasks.*.order' => 'required|integer',
            'tasks.*.status' => 'required|in:todo,in_progress,review,completed',
        ]);

        $user = Auth::user();

        foreach ($request->tasks as $taskData) {
            $task = Task::find($taskData['id']);

            // Check if user can update this task
            $canUpdate = $user->hasRole(['admin', 'supervisor']) ||
                $task->assignees->contains('id', $user->id);

            if ($canUpdate) {
                $task->update([
                    'order' => $taskData['order'],
                    'status' => $taskData['status'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Task order updated successfully.',
        ]);
    }

    public function assign(Request $request, Task $task)
    {
        $user = Auth::user();

        if (!$user->hasRole(['admin', 'supervisor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only admins and supervisors can assign tasks.',
            ], 403);
        }

        $request->validate([
            'assignees' => 'required|array',
            'assignees.*' => 'exists:users,id',
        ]);

        $this->validateAssignees($user, $request->assignees);

        $assigneeData = [];
        foreach ($request->assignees as $userId) {
            $assigneeData[$userId] = ['assigned_by' => Auth::id()];
        }
        $task->assignees()->sync($assigneeData);

        return response()->json([
            'success' => true,
            'message' => 'Task assignees updated successfully.',
            'assignees' => $task->load('assignees')->assignees,
        ]);
    }

    protected function validateAssignees($user, array $assigneeIds)
    {
        foreach ($assigneeIds as $userId) {
            $assignee = User::find($userId);

            if (!$assignee) {
                continue;
            }

            // Admin can assign to supervisors and staff
            if ($user->hasRole('admin')) {
                if (!$assignee->hasRole(['supervisor', 'staff'])) {
                    abort(403, 'Admin can only assign tasks to supervisors and staff.');
                }
            }
            // Supervisor can only assign to staff
            elseif ($user->hasRole('supervisor')) {
                if (!$assignee->hasRole('staff')) {
                    abort(403, 'Supervisor can only assign tasks to staff.');
                }
            }
        }
    }
}
