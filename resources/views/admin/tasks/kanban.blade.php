<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task Board') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <h4 class="mb-0 fw-bold"><i class="fas fa-columns me-2"></i>Kanban Board</h4>

                            <!-- Project Filter -->
                            <select id="projectFilter" class="form-select form-select-sm" style="width: 200px;"
                                onchange="filterByProject(this.value)">
                                <option value="">All Projects</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ $projectId == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-list me-1"></i> List View
                            </a>
                            @if (auth()->user()->hasRole(['admin', 'supervisor']))
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#addTaskModal">
                                    <i class="fas fa-plus me-1"></i> Add Task
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Kanban Board -->
            <div class="kanban-container" x-data="kanbanBoard()">
                <div class="kanban-board">
                    @foreach ($statuses as $statusKey => $statusLabel)
                        <div class="kanban-column" data-status="{{ $statusKey }}">
                            <div class="kanban-column-header">
                                <div class="d-flex align-items-center">
                                    <div class="status-indicator {{ $statusKey }}"></div>
                                    <h6 class="mb-0 fw-bold">{{ $statusLabel }}</h6>
                                    <span
                                        class="badge bg-light text-dark ms-2">{{ $tasksByStatus[$statusKey]->count() }}</span>
                                </div>
                            </div>
                            <div class="kanban-column-body" id="column-{{ $statusKey }}"
                                ondrop="handleDrop(event, '{{ $statusKey }}')" ondragover="allowDrop(event)"
                                ondragenter="handleDragEnter(event)" ondragleave="handleDragLeave(event)">

                                @foreach ($tasksByStatus[$statusKey] as $task)
                                    <div class="kanban-card" draggable="true" data-task-id="{{ $task->id }}"
                                        ondragstart="handleDragStart(event)" ondragend="handleDragEnd(event)">

                                        <div class="kanban-card-header">
                                            <div class="priority-indicator {{ $task->priority }}"></div>
                                            @if ($task->project)
                                                <span
                                                    class="project-tag">{{ Str::limit($task->project->name, 15) }}</span>
                                            @endif
                                        </div>

                                        <h6 class="kanban-card-title">{{ $task->title }}</h6>

                                        @if ($task->description)
                                            <p class="kanban-card-desc">
                                                {{ Str::limit(strip_tags($task->description), 80) }}</p>
                                        @endif

                                        <div class="kanban-card-footer">
                                            <div class="d-flex align-items-center">
                                                @if ($task->assignees->isNotEmpty())
                                                    <div class="assignee-avatars">
                                                        @foreach ($task->assignees->take(2) as $assignee)
                                                            <div class="avatar-circle" title="{{ $assignee->name }}">
                                                                {{ strtoupper(substr($assignee->name, 0, 1)) }}
                                                            </div>
                                                        @endforeach
                                                        @if ($task->assignees->count() > 2)
                                                            <div class="avatar-circle more">
                                                                +{{ $task->assignees->count() - 2 }}</div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="d-flex align-items-center gap-2">
                                                @if ($task->due_date)
                                                    <span class="due-date {{ $task->isOverdue() ? 'overdue' : '' }}">
                                                        <i class="fas fa-clock"></i>
                                                        {{ $task->due_date->format('M d') }}
                                                    </span>
                                                @endif

                                                <!-- Comments Button -->
                                                <button type="button" class="btn btn-link btn-sm p-0 text-muted"
                                                    onclick="openCommentsModal({{ $task->id }}, '{{ addslashes($task->title) }}')"
                                                    title="View Comments">
                                                    <i class="fas fa-comment"></i>
                                                    <span class="badge bg-secondary"
                                                        id="comment-count-{{ $task->id }}">{{ $task->comments->count() }}</span>
                                                </button>

                                                @if (auth()->user()->hasRole(['admin', 'supervisor']))
                                                    <div class="dropdown">
                                                        <button class="btn btn-link btn-sm p-0 text-muted"
                                                            data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-h"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('admin.tasks.edit', $task) }}"><i
                                                                        class="fas fa-edit me-2"></i>Edit</a></li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.tasks.destroy', $task) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Delete this task?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item text-danger"><i
                                                                            class="fas fa-trash me-2"></i>Delete</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if ($tasksByStatus[$statusKey]->isEmpty())
                                    <div class="kanban-empty">
                                        <i class="fas fa-inbox"></i>
                                        <p>No tasks</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- Add Task Modal -->
    @if (auth()->user()->hasRole(['admin', 'supervisor']))
        <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTaskModalLabel"><i class="fas fa-plus-circle me-2"></i>Add New
                            Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.tasks.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold">Task Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required
                                    placeholder="Enter task title">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Describe the task"></textarea>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="project_id" class="form-label fw-semibold">Project <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="project_id" name="project_id" required>
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ $projectId == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-semibold">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        @foreach ($statuses as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="priority" class="form-label fw-semibold">Priority <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="priority" name="priority" required>
                                        <option value="low">Low</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="high">High</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="due_date" class="form-label fw-semibold">Due Date</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="assignees" class="form-label fw-semibold">Assign To</label>
                                <select class="form-select" id="assignees" name="assignees[]" multiple
                                    style="height: 120px;">
                                    @foreach ($assignableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}
                                            ({{ $user->getRoleNames()->first() }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple users</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Create
                                Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
        .kanban-container {
            overflow-x: auto;
            padding-bottom: 20px;
        }

        .kanban-board {
            display: flex;
            gap: 16px;
            min-width: max-content;
            padding: 4px;
        }

        .kanban-column {
            background: #f8f9fa;
            border-radius: 12px;
            width: 320px;
            min-width: 320px;
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 250px);
        }

        .kanban-column-header {
            padding: 16px;
            border-bottom: 1px solid #e9ecef;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .status-indicator.todo {
            background: #6c757d;
        }

        .status-indicator.in_progress {
            background: #0d6efd;
        }

        .status-indicator.review {
            background: #ffc107;
        }

        .status-indicator.completed {
            background: #198754;
        }

        .kanban-column-body {
            padding: 12px;
            flex: 1;
            overflow-y: auto;
            min-height: 200px;
            transition: background-color 0.2s;
        }

        .kanban-column-body.drag-over {
            background-color: #e3f2fd;
        }

        .kanban-card {
            background: white;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            cursor: grab;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #e9ecef;
        }

        .kanban-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .kanban-card.dragging {
            opacity: 0.5;
            transform: rotate(3deg);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .kanban-card-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .priority-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .priority-indicator.low {
            background: #17a2b8;
        }

        .priority-indicator.medium {
            background: #6c757d;
        }

        .priority-indicator.high {
            background: #ffc107;
        }

        .priority-indicator.urgent {
            background: #dc3545;
        }

        .project-tag {
            font-size: 0.7rem;
            background: #e9ecef;
            padding: 2px 8px;
            border-radius: 20px;
            color: #495057;
        }

        .kanban-card-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: #212529;
        }

        .kanban-card-desc {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .kanban-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .assignee-avatars {
            display: flex;
        }

        .avatar-circle {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 600;
            margin-left: -6px;
            border: 2px solid white;
        }

        .avatar-circle:first-child {
            margin-left: 0;
        }

        .avatar-circle.more {
            background: #6c757d;
        }

        .due-date {
            font-size: 0.75rem;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .due-date.overdue {
            color: #dc3545;
            font-weight: 600;
        }

        .kanban-empty {
            text-align: center;
            padding: 40px 20px;
            color: #adb5bd;
        }

        .kanban-empty i {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .kanban-empty p {
            margin: 0;
            font-size: 0.85rem;
        }

        /* Custom scrollbar */
        .kanban-column-body::-webkit-scrollbar {
            width: 6px;
        }

        .kanban-column-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .kanban-column-body::-webkit-scrollbar-thumb {
            background: #dee2e6;
            border-radius: 3px;
        }

        .kanban-column-body::-webkit-scrollbar-thumb:hover {
            background: #adb5bd;
        }

        /* Dark Mode Styles for Kanban Board */
        .dark .kanban-column {
            background: #1f2937 !important;
            border: 1px solid #374151;
        }

        .dark .kanban-column-header {
            border-bottom-color: #374151 !important;
        }

        .dark .kanban-column-header h6 {
            color: #f9fafb !important;
        }

        .dark .kanban-column-header .badge {
            background: #374151 !important;
            color: #d1d5db !important;
        }

        .dark .kanban-column-body.drag-over {
            background-color: #374151 !important;
        }

        .dark .kanban-card {
            background: #374151 !important;
            border-color: #4b5563 !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .dark .kanban-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border-color: #6b7280 !important;
        }

        .dark .kanban-card-title {
            color: #f9fafb !important;
        }

        .dark .kanban-card-desc {
            color: #9ca3af !important;
        }

        .dark .project-tag {
            background: #4b5563 !important;
            color: #d1d5db !important;
        }

        .dark .due-date {
            color: #9ca3af !important;
        }

        .dark .due-date.overdue {
            color: #f87171 !important;
        }

        .dark .avatar-circle {
            border-color: #374151 !important;
        }

        .dark .kanban-empty {
            color: #6b7280 !important;
        }

        .dark .kanban-empty i,
        .dark .kanban-empty p {
            color: #6b7280 !important;
        }

        /* Dark mode scrollbar */
        .dark .kanban-column-body::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .dark .kanban-column-body::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .dark .kanban-column-body::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        /* Dark mode for header card */
        .dark .bg-white {
            background-color: #1f2937 !important;
        }

        .dark .kanban-container h4 {
            color: #f9fafb !important;
        }
    </style>

    <script>
        let draggedCard = null;

        function filterByProject(projectId) {
            const url = new URL(window.location.href);
            if (projectId) {
                url.searchParams.set('project_id', projectId);
            } else {
                url.searchParams.delete('project_id');
            }
            window.location.href = url.toString();
        }

        function handleDragStart(e) {
            draggedCard = e.target;
            e.target.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', e.target.dataset.taskId);
        }

        function handleDragEnd(e) {
            e.target.classList.remove('dragging');
            document.querySelectorAll('.kanban-column-body').forEach(col => {
                col.classList.remove('drag-over');
            });
        }

        function allowDrop(e) {
            e.preventDefault();
        }

        function handleDragEnter(e) {
            e.preventDefault();
            if (e.target.classList.contains('kanban-column-body')) {
                e.target.classList.add('drag-over');
            }
        }

        function handleDragLeave(e) {
            if (e.target.classList.contains('kanban-column-body')) {
                e.target.classList.remove('drag-over');
            }
        }

        function handleDrop(e, newStatus) {
            e.preventDefault();
            const column = e.target.closest('.kanban-column-body');
            if (column) {
                column.classList.remove('drag-over');
            }

            if (!draggedCard) return;

            const taskId = draggedCard.dataset.taskId;
            const targetColumn = document.getElementById('column-' + newStatus);

            // Remove empty state if exists
            const emptyState = targetColumn.querySelector('.kanban-empty');
            if (emptyState) {
                emptyState.remove();
            }

            // Move the card
            targetColumn.appendChild(draggedCard);

            // Update task order
            updateTaskOrder(taskId, newStatus);
        }

        function updateTaskOrder(taskId, newStatus) {
            // Collect all tasks and their order in each column
            const tasks = [];
            document.querySelectorAll('.kanban-column').forEach(column => {
                const status = column.dataset.status;
                column.querySelectorAll('.kanban-card').forEach((card, index) => {
                    tasks.push({
                        id: parseInt(card.dataset.taskId),
                        order: index,
                        status: status
                    });
                });
            });

            fetch('{{ route('admin.tasks.update-order') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        tasks: tasks
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error('Failed to update task order');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function kanbanBoard() {
            return {
                // Alpine.js data for kanban board
            };
        }

        // Comments functionality
        let currentTaskId = null;
        let currentTaskTitle = '';

        function openCommentsModal(taskId, taskTitle) {
            currentTaskId = taskId;
            currentTaskTitle = taskTitle;
            document.getElementById('commentsModalLabel').textContent = `Comments - ${taskTitle}`;
            document.getElementById('commentInput').value = '';
            loadComments(taskId);
            new bootstrap.Modal(document.getElementById('commentsModal')).show();
        }

        function loadComments(taskId) {
            const container = document.getElementById('commentsContainer');
            container.innerHTML =
                '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Loading comments...</div>';

            fetch(`/admin/tasks/${taskId}/comments`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayComments(data.comments);
                    }
                })
                .catch(error => {
                    container.innerHTML = '<div class="alert alert-danger">Failed to load comments.</div>';
                    console.error('Error:', error);
                });
        }

        function displayComments(comments) {
            const container = document.getElementById('commentsContainer');

            if (comments.length === 0) {
                container.innerHTML =
                    '<div class="text-center text-muted py-4"><i class="fas fa-comments fa-2x mb-2"></i><p>No comments yet. Be the first to comment!</p></div>';
                return;
            }

            let html = '';
            comments.forEach(comment => {
                const createdAt = new Date(comment.created_at);
                const timeAgo = getTimeAgo(createdAt);
                const canDelete = {{ auth()->id() }} === comment.user_id ||
                    {{ auth()->user()->hasRole('admin') ? 'true' : 'false' }};

                html += `
                <div class="comment-item" id="comment-${comment.id}">
                    <div class="comment-avatar">
                        ${comment.user.name.charAt(0).toUpperCase()}
                    </div>
                    <div class="comment-content">
                        <div class="comment-header">
                            <strong>${comment.user.name}</strong>
                            <span class="text-muted small">${timeAgo}</span>
                        </div>
                        <div class="comment-body">${escapeHtml(comment.comment)}</div>
                        ${canDelete ? `<button class="btn btn-link btn-sm text-danger p-0 mt-1" onclick="deleteComment(${currentTaskId}, ${comment.id})"><i class="fas fa-trash"></i> Delete</button>` : ''}
                    </div>
                </div>
            `;
            });

            container.innerHTML = html;
        }

        function addComment() {
            const commentInput = document.getElementById('commentInput');
            const comment = commentInput.value.trim();

            if (!comment) {
                alert('Please enter a comment.');
                return;
            }

            const submitBtn = document.getElementById('submitCommentBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';

            fetch(`/admin/tasks/${currentTaskId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        comment: comment
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        commentInput.value = '';
                        loadComments(currentTaskId);
                        // Update comment count badge
                        const badge = document.getElementById(`comment-count-${currentTaskId}`);
                        if (badge) {
                            badge.textContent = parseInt(badge.textContent) + 1;
                        }
                    } else {
                        alert(data.message || 'Failed to add comment.');
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                    console.error('Error:', error);
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Post Comment';
                });
        }

        function deleteComment(taskId, commentId) {
            if (!confirm('Delete this comment?')) {
                return;
            }

            fetch(`/admin/tasks/${taskId}/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadComments(taskId);
                        // Update comment count badge
                        const badge = document.getElementById(`comment-count-${taskId}`);
                        if (badge) {
                            badge.textContent = Math.max(0, parseInt(badge.textContent) - 1);
                        }
                    } else {
                        alert(data.message || 'Failed to delete comment.');
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                    console.error('Error:', error);
                });
        }

        function getTimeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            const intervals = {
                year: 31536000,
                month: 2592000,
                week: 604800,
                day: 86400,
                hour: 3600,
                minute: 60
            };

            for (const [key, value] of Object.entries(intervals)) {
                const interval = Math.floor(seconds / value);
                if (interval >= 1) {
                    return interval === 1 ? `1 ${key} ago` : `${interval} ${key}s ago`;
                }
            }
            return 'Just now';
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }
    </script>

    <!-- Comments Modal -->
    <div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentsModalLabel"><i class="fas fa-comments me-2"></i>Comments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="commentsContainer">
                        <!-- Comments will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer d-block">
                    <div class="input-group">
                        <textarea class="form-control" id="commentInput" rows="2" placeholder="Write a comment..." maxlength="2000"></textarea>
                        <button class="btn btn-primary" type="button" id="submitCommentBtn" onclick="addComment()">
                            <i class="fas fa-paper-plane me-1"></i> Post Comment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .comment-item {
            display: flex;
            gap: 12px;
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .comment-item:last-child {
            border-bottom: none;
        }

        .comment-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #6c757d;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .comment-content {
            flex: 1;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .comment-body {
            color: #495057;
            white-space: pre-wrap;
            word-break: break-word;
        }
    </style>

</x-app-layout>
