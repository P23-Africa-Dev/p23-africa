<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Back Navigation -->
            <div class="mb-4">
                <a href="{{ route('admin.tasks.index', ['project_id' => $task->project_id]) }}"
                    class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Tasks
                </a>
                <a href="{{ route('admin.tasks.kanban', ['project_id' => $task->project_id]) }}"
                    class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-columns me-2"></i>Kanban Board
                </a>
            </div>

            <!-- Task Details Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-gray-900">

                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                        <div>
                            <h3 class="fw-bold mb-2">{{ $task->title }}</h3>
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="badge {{ $task->status_badge_class }}">
                                    {{ $statuses[$task->status] }}
                                </span>
                                <span class="badge {{ $task->priority_badge_class }}">
                                    {{ ucfirst($task->priority) }} Priority
                                </span>
                                @if ($task->isOverdue())
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if (auth()->user()->hasRole(['admin', 'supervisor']))
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i> Edit Task
                                </a>
                                <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Task Info Grid -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3 text-muted">
                                        <i class="fas fa-info-circle me-2"></i>Task Information
                                    </h6>
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <th style="width: 120px;" class="text-muted">Project</th>
                                            <td>
                                                <a href="{{ route('admin.projects.show', $task->project) }}"
                                                    class="text-decoration-none">
                                                    {{ $task->project->name }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Created By</th>
                                            <td>{{ $task->creator?->name ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Created</th>
                                            <td>{{ $task->created_at->format('M d, Y \a\t h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Updated</th>
                                            <td>{{ $task->updated_at->format('M d, Y \a\t h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Due Date</th>
                                            <td>
                                                @if ($task->due_date)
                                                    <span
                                                        class="{{ $task->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                                        {{ $task->due_date->format('M d, Y') }}
                                                        @if ($task->isOverdue())
                                                            <i class="fas fa-exclamation-triangle ms-1"></i>
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="text-muted">No due date set</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3 text-muted">
                                        <i class="fas fa-users me-2"></i>Assignees
                                    </h6>
                                    @if ($task->assignees->isNotEmpty())
                                        <div class="d-flex flex-column gap-2">
                                            @foreach ($task->assignees as $assignee)
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                        style="width: 36px; height: 36px; font-size: 0.8rem; flex-shrink: 0;">
                                                        {{ strtoupper(substr($assignee->name, 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $assignee->name }}</div>
                                                        <small
                                                            class="text-muted">{{ $assignee->getRoleNames()->first() }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-user-slash me-2"></i>No assignees
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if ($task->description)
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-3 text-muted">
                                    <i class="fas fa-align-left me-2"></i>Description
                                </h6>
                                <div class="task-description">
                                    {!! nl2br(e($task->description)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Status Update (for assigned users) -->
                    <div class="card mb-0" x-data="{
                        status: '{{ $task->status }}',
                        updating: false,
                        statusLabel: '{{ $statuses[$task->status] }}',
                        badgeClass: '{{ $task->status_badge_class }}'
                    }">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-muted">
                                <i class="fas fa-sync-alt me-2"></i>Update Status
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($statuses as $key => $label)
                                    <button
                                        @click="
                                            if(status === '{{ $key }}') return;
                                            updating = true;
                                            fetch('{{ route('admin.tasks.update-status', $task) }}', {
                                                method: 'PATCH',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({ status: '{{ $key }}' })
                                            })
                                            .then(res => res.json())
                                            .then(data => {
                                                if(data.success) {
                                                    status = data.status;
                                                    statusLabel = data.status_label;
                                                    badgeClass = data.badge_class;
                                                }
                                            })
                                            .finally(() => updating = false);
                                        "
                                        class="btn"
                                        :class="status === '{{ $key }}' ? 'btn-primary' : 'btn-outline-secondary'"
                                        :disabled="updating">
                                        @if ($key === 'todo')
                                            <i class="fas fa-clipboard-list me-1"></i>
                                        @elseif($key === 'in_progress')
                                            <i class="fas fa-spinner me-1"></i>
                                        @elseif($key === 'review')
                                            <i class="fas fa-eye me-1"></i>
                                        @elseif($key === 'completed')
                                            <i class="fas fa-check-circle me-1"></i>
                                        @endif
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-comments me-2"></i>Comments
                        <span class="badge bg-secondary ms-2" id="commentCount">{{ $task->comments->count() }}</span>
                    </h5>

                    <!-- Add Comment Form -->
                    <form id="addCommentForm" class="mb-4">
                        <div class="card">
                            <div class="card-body">
                                <textarea class="form-control mb-3" id="commentText" rows="3" placeholder="Write a comment..." required></textarea>
                                <button type="submit" class="btn btn-primary" id="submitCommentBtn">
                                    <i class="fas fa-paper-plane me-1"></i> Post Comment
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Comments List -->
                    <div id="commentsContainer">
                        @forelse ($task->comments as $comment)
                            <div class="card mb-3" id="comment-{{ $comment->id }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; font-size: 0.9rem; flex-shrink: 0;">
                                                {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $comment->user->name }}</div>
                                                <small
                                                    class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @if ($comment->user_id === auth()->id() || auth()->user()->hasRole('admin'))
                                            <button onclick="deleteComment({{ $comment->id }})"
                                                class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="ms-5 ps-2">
                                        <p class="mb-0">{{ $comment->comment }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5" id="noCommentsMessage">
                                <i class="fas fa-comments fa-3x mb-3 opacity-25"></i>
                                <p>No comments yet. Be the first to comment!</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        const taskId = {{ $task->id }};
        const csrfToken = '{{ csrf_token() }}';

        // Add Comment
        document.getElementById('addCommentForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const commentText = document.getElementById('commentText').value.trim();
            if (!commentText) return;

            const submitBtn = document.getElementById('submitCommentBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Posting...';

            fetch(`/admin/tasks/${taskId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        comment: commentText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('commentText').value = '';

                        // Remove "no comments" message if present
                        const noCommentsMsg = document.getElementById('noCommentsMessage');
                        if (noCommentsMsg) {
                            noCommentsMsg.remove();
                        }

                        // Add the new comment to the top of the list
                        const container = document.getElementById('commentsContainer');
                        const commentHtml = createCommentHtml(data.comment);
                        container.insertAdjacentHTML('afterbegin', commentHtml);

                        // Update comment count
                        updateCommentCount(1);
                    }
                })
                .catch(error => {
                    console.error('Error adding comment:', error);
                    alert('Failed to add comment. Please try again.');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Post Comment';
                });
        });

        // Delete Comment
        function deleteComment(commentId) {
            if (!confirm('Delete this comment?')) return;

            fetch(`/admin/tasks/${taskId}/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const commentElement = document.getElementById(`comment-${commentId}`);
                        if (commentElement) {
                            commentElement.remove();
                        }

                        // Update comment count
                        updateCommentCount(-1);

                        // Show "no comments" message if no comments left
                        const container = document.getElementById('commentsContainer');
                        if (container.querySelectorAll('.card').length === 0) {
                            container.innerHTML = `
                            <div class="text-center text-muted py-5" id="noCommentsMessage">
                                <i class="fas fa-comments fa-3x mb-3 opacity-25"></i>
                                <p>No comments yet. Be the first to comment!</p>
                            </div>
                        `;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error deleting comment:', error);
                    alert('Failed to delete comment. Please try again.');
                });
        }

        function createCommentHtml(comment) {
            const initials = comment.user.name.substring(0, 2).toUpperCase();
            const canDelete = comment.can_delete || comment.user_id === {{ auth()->id() }};

            return `
            <div class="card mb-3" id="comment-${comment.id}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px; font-size: 0.9rem; flex-shrink: 0;">
                                ${initials}
                            </div>
                            <div>
                                <div class="fw-semibold">${escapeHtml(comment.user.name)}</div>
                                <small class="text-muted">just now</small>
                            </div>
                        </div>
                        ${canDelete ? `
                                <button onclick="deleteComment(${comment.id})" 
                                        class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            ` : ''}
                    </div>
                    <div class="ms-5 ps-2">
                        <p class="mb-0">${escapeHtml(comment.comment)}</p>
                    </div>
                </div>
            </div>
        `;
        }

        function updateCommentCount(delta) {
            const countBadge = document.getElementById('commentCount');
            if (countBadge) {
                const currentCount = parseInt(countBadge.textContent) || 0;
                countBadge.textContent = Math.max(0, currentCount + delta);
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>

</x-app-layout>
