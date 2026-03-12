<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Ensure the current user can access the given task based on organization.
     */
    protected function ensureTaskOrgAccess(Task $task, $user)
    {
        if ($user->hasRole('admin')) {
            return;
        }

        // If user belongs to an organization, require task to belong to same org.
        if ($user->organization_id) {
            if ($task->organization_id !== $user->organization_id) {
                abort(403, 'You are not authorized to access this task.');
            }
        } else {
            // If user has no org, require task to be global (null org)
            if (!is_null($task->organization_id)) {
                abort(403, 'You are not authorized to access this task.');
            }
        }
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $projectId = $request->get('project_id');

        $query = Task::with(['project', 'assignees', 'creator'])
            ->withCount('comments');

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        // Staff only sees tasks assigned to them
        if (!$user->hasRole(['admin', 'supervisor'])) {
            $query->whereHas('assignees', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Scope to user's organization for non-admin users
        if (!$user->hasRole('admin')) {
            if ($user->organization_id) {
                $query->where('organization_id', $user->organization_id);
            } else {
                $query->whereNull('organization_id');
            }
        }

        $tasks = $query->orderBy('order')->latest()->paginate(15);

        // Scope projects for dropdowns to user's organization (admins see all)
        $projectsQuery = Project::query();
        if (!$user->hasRole('admin')) {
            if ($user->organization_id) {
                $projectsQuery->where('organization_id', $user->organization_id);
            } else {
                $projectsQuery->whereNull('organization_id');
            }
        }
        $projects = $projectsQuery->get();

        return view('admin.tasks.index', compact('tasks', 'projects', 'projectId'));
    }

    public function kanban(Request $request)
    {
        $user = Auth::user();
        $projectId = $request->get('project_id');

        $query = Task::with(['project', 'assignees', 'creator', 'comments'])
            ->withCount('comments');

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        // Staff only sees tasks assigned to them
        if (!$user->hasRole(['admin', 'supervisor'])) {
            $query->whereHas('assignees', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Scope to organization for non-admins
        if (!$user->hasRole('admin')) {
            if ($user->organization_id) {
                $query->where('organization_id', $user->organization_id);
            } else {
                $query->whereNull('organization_id');
            }
        }

        $tasks = $query->orderBy('order')->get();

        $tasksByStatus = [
            'todo' => $tasks->where('status', 'todo')->values(),
            'in_progress' => $tasks->where('status', 'in_progress')->values(),
            'review' => $tasks->where('status', 'review')->values(),
            'completed' => $tasks->where('status', 'completed')->values(),
        ];

        // Scope projects for kanban view
        $projectsQuery = Project::query();
        if (!$user->hasRole('admin')) {
            if ($user->organization_id) {
                $projectsQuery->where('organization_id', $user->organization_id);
            } else {
                $projectsQuery->whereNull('organization_id');
            }
        }
        $projects = $projectsQuery->get();
        $statuses = Task::getStatuses();

        // Get assignable users based on current user's role
        if ($user->hasRole('admin')) {
            $assignableUsers = User::role(['supervisor', 'staff'])->get();
        } elseif ($user->hasRole('supervisor')) {
            $assignableUsers = User::role('staff')->get();
        } else {
            $assignableUsers = collect();
        }

        // Limit assignable users to same organization for non-admins
        if (!$user->hasRole('admin') && $user->organization_id) {
            $assignableUsers = $assignableUsers->where('organization_id', $user->organization_id)->values();
        }

        return view('admin.tasks.kanban', compact('tasksByStatus', 'projects', 'projectId', 'statuses', 'assignableUsers'));
    }

    public function show(Task $task)
    {
        $user = Auth::user();

        // Ensure organization-level access
        $this->ensureTaskOrgAccess($task, $user);

        // Check if user can view this task (assigned to them or admin/supervisor)
        $canView = $user->hasRole(['admin', 'supervisor']) ||
            $task->assignees->contains('id', $user->id);

        if (!$canView) {
            abort(403, 'You are not authorized to view this task.');
        }

        // Load relationships
        $task->load(['project', 'assignees', 'creator', 'comments.user']);

        $statuses = Task::getStatuses();
        $priorities = Task::getPriorities();

        return view('admin.tasks.show', compact('task', 'statuses', 'priorities'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole(['admin', 'supervisor'])) {
            abort(403, 'Only admins and supervisors can create tasks.');
        }

        // Scope projects for create form
        $projectsQuery = Project::query();
        if (!$user->hasRole('admin')) {
            if ($user->organization_id) {
                $projectsQuery->where('organization_id', $user->organization_id);
            } else {
                $projectsQuery->whereNull('organization_id');
            }
        }
        $projects = $projectsQuery->get();
        $statuses = Task::getStatuses();
        $priorities = Task::getPriorities();

        // Get assignable users
        if ($user->hasRole('admin')) {
            $assignableUsers = User::role(['supervisor', 'staff'])->get();
        } else {
            $assignableUsers = User::role('staff')->get();
        }

        // Limit assignable users to same organization for non-admins
        if (!$user->hasRole('admin') && $user->organization_id) {
            $assignableUsers = $assignableUsers->where('organization_id', $user->organization_id)->values();
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

        // Set organization for the task based on creator
        $data['organization_id'] = Auth::user()->organization_id ?? null;

        $task = Task::create($data);

        // Send notification to admins about new task
        AdminNotificationService::notifyNewTask($task, Auth::user());

        // Assign users to the task and send notifications
        if (!empty($data['assignees'])) {
            $this->validateAssignees($user, $data['assignees']);

            $assigneeData = [];
            foreach ($data['assignees'] as $userId) {
                $assigneeData[$userId] = ['assigned_by' => Auth::id()];
            }
            $task->assignees()->sync($assigneeData);

            // Send notifications to assignees
            $this->notifyAssignees($task, $data['assignees'], $user);
        }

        try {
            activity()
                ->causedBy(Auth::user())
                ->performedOn($task)
                ->withProperties(['title' => $task->title, 'project_id' => $task->project_id])
                ->log('Created task');
        } catch (\Exception $e) {
            Log::warning('Activity log failed: ' . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully.',
                'task' => $task->load('assignees'),
            ]);
        }

        return orgRedirect('admin.tasks.index', ['project_id' => $task->project_id])
            ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $user = Auth::user();

        // Ensure organization-level access
        $this->ensureTaskOrgAccess($task, $user);

        if (!$user->hasRole(['admin', 'supervisor'])) {
            abort(403, 'Only admins and supervisors can edit tasks.');
        }

        // Scope projects for edit form
        $projectsQuery = Project::query();
        if (!$user->hasRole('admin')) {
            if ($user->organization_id) {
                $projectsQuery->where('organization_id', $user->organization_id);
            } else {
                $projectsQuery->whereNull('organization_id');
            }
        }
        $projects = $projectsQuery->get();
        $statuses = Task::getStatuses();
        $priorities = Task::getPriorities();

        if ($user->hasRole('admin')) {
            $assignableUsers = User::role(['supervisor', 'staff'])->get();
        } else {
            $assignableUsers = User::role('staff')->get();
        }

        // Limit assignable users to same organization for non-admins
        if (!$user->hasRole('admin') && $user->organization_id) {
            $assignableUsers = $assignableUsers->where('organization_id', $user->organization_id)->values();
        }

        $task->load('assignees');

        return view('admin.tasks.edit', compact('task', 'projects', 'statuses', 'priorities', 'assignableUsers'));
    }

    public function update(Request $request, Task $task)
    {
        $user = Auth::user();

        // Ensure organization-level access
        $this->ensureTaskOrgAccess($task, $user);

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

        // Update assignees and notify new ones
        if (isset($data['assignees'])) {
            $this->validateAssignees($user, $data['assignees']);

            // Get current assignees before update
            $currentAssigneeIds = $task->assignees->pluck('id')->toArray();
            $newAssigneeIds = array_diff($data['assignees'], $currentAssigneeIds);

            $assigneeData = [];
            foreach ($data['assignees'] as $userId) {
                $assigneeData[$userId] = ['assigned_by' => Auth::id()];
            }
            $task->assignees()->sync($assigneeData);

            // Notify only new assignees
            if (!empty($newAssigneeIds)) {
                $this->notifyAssignees($task, $newAssigneeIds, $user);
            }
        }

        try {
            activity()
                ->causedBy(Auth::user())
                ->performedOn($task)
                ->withProperties(['title' => $task->title])
                ->log('Updated task');
        } catch (\Exception $e) {
            Log::warning('Activity log failed: ' . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully.',
                'task' => $task->load('assignees'),
            ]);
        }

        return orgRedirect('admin.tasks.index', ['project_id' => $task->project_id])
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $user = Auth::user();

        // Ensure organization-level access
        $this->ensureTaskOrgAccess($task, $user);

        if (!$user->hasRole(['admin', 'supervisor'])) {
            abort(403, 'Only admins and supervisors can delete tasks.');
        }

        $projectId = $task->project_id;
        $taskTitle = $task->title;
        $taskId = $task->id;

        $task->delete();

        try {
            activity()
                ->causedBy(Auth::user())
                ->withProperties(['title' => $taskTitle, 'task_id' => $taskId, 'project_id' => $projectId])
                ->log('Deleted task');
        } catch (\Exception $e) {
            Log::warning('Activity log failed: ' . $e->getMessage());
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully.',
            ]);
        }

        return orgRedirect('admin.tasks.kanban', ['project_id' => $projectId])
            ->with('success', 'Task deleted successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $user = Auth::user();

        // Ensure organization-level access
        $this->ensureTaskOrgAccess($task, $user);

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
            // Respect organization scope when finding the task for update
            if ($user->hasRole('admin')) {
                $task = Task::find($taskData['id']);
            } else {
                $task = Task::where('id', $taskData['id'])
                    ->where(function ($q) use ($user) {
                        if ($user->organization_id) {
                            $q->where('organization_id', $user->organization_id);
                        } else {
                            $q->whereNull('organization_id');
                        }
                    })->first();
            }

            if (!$task) {
                // Skip tasks that are not accessible in this organization
                continue;
            }

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

        // Ensure organization-level access
        $this->ensureTaskOrgAccess($task, $user);

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

        // Get current assignees before update
        $currentAssigneeIds = $task->assignees->pluck('id')->toArray();
        $newAssigneeIds = array_diff($request->assignees, $currentAssigneeIds);

        $assigneeData = [];
        foreach ($request->assignees as $userId) {
            $assigneeData[$userId] = ['assigned_by' => Auth::id()];
        }
        $task->assignees()->sync($assigneeData);

        // Notify only new assignees
        if (!empty($newAssigneeIds)) {
            $this->notifyAssignees($task, $newAssigneeIds, $user);
        }

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

            // Prevent assigning users from other organizations for non-admins
            if (!$user->hasRole('admin') && $user->organization_id) {
                if ($assignee->organization_id !== $user->organization_id) {
                    abort(403, 'You cannot assign tasks to users outside your organization.');
                }
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

    /**
     * Send notification to task assignees
     */
    protected function notifyAssignees(Task $task, array $assigneeIds, $assigner)
    {
        // Make sure task has project loaded for notification data
        $task->load('project');

        $assignees = User::whereIn('id', $assigneeIds)->get();

        foreach ($assignees as $assignee) {
            try {
                $assignee->notify(new TaskAssignedNotification($task, $assigner));
            } catch (\Exception $e) {
                Log::warning('Failed to send task notification to user ' . $assignee->id . ': ' . $e->getMessage());
            }
        }
    }

    /**
     * Get comments for a task
     */
    public function getComments(Task $task)
    {
        $user = Auth::user();

        // Ensure organization-level access
        $this->ensureTaskOrgAccess($task, $user);

        // Check if user can view this task (assigned to them or admin/supervisor)
        $canView = $user->hasRole(['admin', 'supervisor']) ||
            $task->assignees->contains('id', $user->id);

        if (!$canView) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this task.',
            ], 403);
        }

        // Get top-level comments with replies
        $comments = $task->comments()
            ->whereNull('parent_id')
            ->with(['user:id,name', 'replies.user:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform comments to include can_delete flag
        $comments = $comments->map(function ($comment) use ($user) {
            return $this->transformComment($comment, $user);
        });

        return response()->json([
            'success' => true,
            'comments' => $comments,
        ]);
    }

    /**
     * Transform comment for JSON response
     */
    protected function transformComment($comment, $user)
    {
        $data = [
            'id' => $comment->id,
            'comment' => $comment->comment,
            'user' => $comment->user,
            'created_at' => $comment->created_at,
            'can_delete' => $comment->user_id === $user->id || $user->hasRole('admin'),
            'attachment_path' => $comment->attachment_path,
            'attachment_name' => $comment->attachment_name,
            'attachment_type' => $comment->attachment_type,
            'attachment_url' => $comment->attachment_url,
            'is_image' => $comment->isImageAttachment(),
            'mentioned_users' => $comment->mentioned_users,
            'parent_id' => $comment->parent_id,
            'replies' => [],
        ];

        if ($comment->relationLoaded('replies') && $comment->replies->count() > 0) {
            $data['replies'] = $comment->replies->map(function ($reply) use ($user) {
                return $this->transformComment($reply, $user);
            });
        }

        return $data;
    }

    /**
     * Get assignees for @mentions
     */
    public function getAssigneesForMention(Task $task)
    {
        $user = Auth::user();

        // Ensure organization-level access
        $this->ensureTaskOrgAccess($task, $user);

        // Check if user can view this task
        $canView = $user->hasRole(['admin', 'supervisor']) ||
            $task->assignees->contains('id', $user->id);

        if (!$canView) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this task.',
            ], 403);
        }

        // Get all users who can be mentioned (assignees + creator + admin/supervisors)
        $mentionableUsers = collect();

        // Add task assignees
        $mentionableUsers = $mentionableUsers->merge($task->assignees);

        // Add task creator
        if ($task->creator) {
            $mentionableUsers->push($task->creator);
        }

        // Add project members if available
        if ($task->project && $task->project->members) {
            $mentionableUsers = $mentionableUsers->merge($task->project->members);
        }

        // Filter to same organization for non-admin users
        if (!$user->hasRole('admin') && $user->organization_id) {
            $mentionableUsers = $mentionableUsers->where('organization_id', $user->organization_id);
        }

        // Get unique users
        $users = $mentionableUsers->unique('id')->values()->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'initials' => strtoupper(substr($u->name, 0, 2)),
            ];
        });

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }

    /**
     * Add a comment to a task
     */
    public function addComment(Request $request, Task $task)
    {
        $user = Auth::user();

        // Ensure organization-level access
        $this->ensureTaskOrgAccess($task, $user);

        // Check if user can comment on this task (assigned to them or admin/supervisor)
        $canComment = $user->hasRole(['admin', 'supervisor']) ||
            $task->assignees->contains('id', $user->id);

        if (!$canComment) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to comment on this task.',
            ], 403);
        }

        $request->validate([
            'comment' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:task_comments,id',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,zip',
            'mentioned_users' => 'nullable|array',
            'mentioned_users.*' => 'exists:users,id',
        ]);

        $commentData = [
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => $request->comment,
            'parent_id' => $request->parent_id,
            'mentioned_users' => $request->mentioned_users,
        ];

        // Handle file attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('task-comments', $fileName, 'public');

            $commentData['attachment_path'] = $path;
            $commentData['attachment_name'] = $file->getClientOriginalName();
            $commentData['attachment_type'] = $file->getMimeType();
        }

        $comment = TaskComment::create($commentData);

        $comment->load('user:id,name');

        // Send notification about new comment
        AdminNotificationService::notifyTaskComment($comment, $task, $user);

        // Send notifications to mentioned users
        if (!empty($request->mentioned_users)) {
            $this->notifyMentionedUsers($comment, $task, $user, $request->mentioned_users);
        }

        try {
            activity()
                ->causedBy($user)
                ->performedOn($task)
                ->withProperties(['comment' => $comment->comment])
                ->log('Added comment to task');
        } catch (\Exception $e) {
            Log::warning('Activity log failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully.',
            'comment' => $this->transformComment($comment, $user),
        ]);
    }

    /**
     * Notify mentioned users
     */
    protected function notifyMentionedUsers($comment, $task, $mentioner, array $userIds)
    {
        $users = User::whereIn('id', $userIds)
            ->where('id', '!=', $mentioner->id) // Don't notify the person who mentioned
            ->get();

        foreach ($users as $mentionedUser) {
            try {
                // You can create a specific MentionNotification if needed
                // For now, we'll use the existing notification service
                AdminNotificationService::notifyTaskComment($comment, $task, $mentioner);
            } catch (\Exception $e) {
                Log::warning('Failed to send mention notification to user ' . $mentionedUser->id . ': ' . $e->getMessage());
            }
        }
    }

    /**
     * Delete a comment
     */
    public function deleteComment(Task $task, TaskComment $comment)
    {
        $user = Auth::user();

        // Ensure organization-level access
        $this->ensureTaskOrgAccess($task, $user);

        // Only the comment author or admin can delete
        if ($comment->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this comment.',
            ], 403);
        }

        // Check if comment belongs to this task
        if ($comment->task_id !== $task->id) {
            return response()->json([
                'success' => false,
                'message' => 'Comment does not belong to this task.',
            ], 400);
        }

        $comment->delete();

        try {
            activity()
                ->causedBy($user)
                ->performedOn($task)
                ->log('Deleted comment from task');
        } catch (\Exception $e) {
            Log::warning('Activity log failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.',
        ]);
    }
}
