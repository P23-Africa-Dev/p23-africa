<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <h4 class="mb-0 fw-bold">All Tasks</h4>

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
                            <a href="{{ route('admin.tasks.kanban', request()->query()) }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-columns me-1"></i> Kanban Board
                            </a>
                            @if (auth()->user()->hasRole(['admin', 'supervisor']))
                                <a href="{{ route('admin.tasks.create', request()->query()) }}" class="btn btn-dark">
                                    <i class="fas fa-plus me-1"></i> Add Task
                                </a>
                            @endif
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Task</th>
                                    <th>Project</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Assignees</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tasks as $index => $task)
                                    <tr>
                                        <td>{{ $tasks->firstItem() + $index }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $task->title }}</div>
                                            @if ($task->description)
                                                <small class="text-muted">{!! Str::limit(strip_tags($task->description), 50) !!}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.projects.show', $task->project) }}"
                                                class="text-decoration-none">
                                                {{ $task->project->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <!-- Status Dropdown -->
                                            <div x-data="{
                                                open: false,
                                                status: '{{ $task->status }}',
                                                statusLabel: '{{ \App\Models\Task::getStatuses()[$task->status] }}',
                                                badgeClass: '{{ $task->status_badge_class }}'
                                            }">
                                                <button @click="open = !open" class="badge border-0"
                                                    :class="badgeClass" style="cursor: pointer;">
                                                    <span x-text="statusLabel"></span>
                                                    <i class="fas fa-chevron-down ms-1" style="font-size: 0.6rem;"></i>
                                                </button>
                                                <div x-show="open" @click.away="open = false"
                                                    class="position-absolute bg-white rounded shadow-lg border mt-1 py-1"
                                                    style="z-index: 1000; min-width: 130px;">
                                                    @foreach (\App\Models\Task::getStatuses() as $key => $label)
                                                        <button
                                                            @click="
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
                                                            });
                                                            open = false;
                                                        "
                                                            class="dropdown-item small py-2"
                                                            style="cursor: pointer;">{{ $label }}</button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $task->priority_badge_class }}">{{ ucfirst($task->priority) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @foreach ($task->assignees->take(3) as $assignee)
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                        style="width: 28px; height: 28px; margin-left: -8px; border: 2px solid white; font-size: 0.7rem;"
                                                        title="{{ $assignee->name }}">
                                                        {{ strtoupper(substr($assignee->name, 0, 2)) }}
                                                    </div>
                                                @endforeach
                                                @if ($task->assignees->count() > 3)
                                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                                        style="width: 28px; height: 28px; margin-left: -8px; border: 2px solid white; font-size: 0.7rem;">
                                                        +{{ $task->assignees->count() - 3 }}
                                                    </div>
                                                @endif
                                                @if ($task->assignees->isEmpty())
                                                    <span class="text-muted small">Unassigned</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if ($task->due_date)
                                                <span class="{{ $task->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                                    {{ $task->due_date->format('M d, Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <!-- View Button -->
                                                <a href="{{ route('admin.tasks.show', $task) }}"
                                                    class="btn btn-outline-info mx-1" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <!-- Comments Button -->
                                                <button
                                                    onclick="openCommentsModal({{ $task->id }}, '{{ addslashes($task->title) }}')"
                                                    class="btn btn-outline-secondary position-relative mx-1"
                                                    title="Comments">
                                                    <i class="fas fa-comments"></i>
                                                    @if ($task->comments_count > 0)
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                                            style="font-size: 0.6rem;">
                                                            {{ $task->comments_count }}
                                                        </span>
                                                    @endif
                                                </button>

                                                @if (auth()->user()->hasRole(['admin', 'supervisor']))
                                                    <a href="{{ route('admin.tasks.edit', $task) }}"
                                                        class="btn btn-outline-primary mx-1" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.tasks.destroy', $task) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Delete this task?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger mx-1"
                                                            title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No tasks found</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $tasks->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Comments Modal -->
    <div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentsModalLabel">
                        <i class="fas fa-comments me-2"></i>Comments: <span id="taskTitle"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="commentsContainer">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <form id="addCommentForm" class="w-100">
                        <div class="input-group">
                            <textarea class="form-control" id="commentText" rows="2" placeholder="Write a comment..." required></textarea>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentTaskId = null;
        let commentsModal = null;

        // Initialize modal after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('commentsModal');
            if (modalElement) {
                commentsModal = new bootstrap.Modal(modalElement);
            }
        });

        function openCommentsModal(taskId, taskTitle) {
            currentTaskId = taskId;
            document.getElementById('taskTitle').textContent = taskTitle;

            // Initialize modal if not already done
            if (!commentsModal) {
                const modalElement = document.getElementById('commentsModal');
                if (modalElement) {
                    commentsModal = new bootstrap.Modal(modalElement);
                }
            }

            if (commentsModal) {
                commentsModal.show();
                loadComments(taskId);
            }
        }

        function loadComments(taskId) {
            const container = document.getElementById('commentsContainer');
            container.innerHTML = `
                <div class="text-center py-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;

            fetch(`/admin/tasks/${taskId}/comments`)
                .then(response => response.json())
                .then(data => {
                    displayComments(data.comments);
                })
                .catch(error => {
                    console.error('Error loading comments:', error);
                    container.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> Failed to load comments
                        </div>
                    `;
                });
        }

        function displayComments(comments) {
            const container = document.getElementById('commentsContainer');

            if (comments.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-comments fa-3x mb-3 opacity-25"></i>
                        <p>No comments yet. Be the first to comment!</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = comments.map(comment => `
                <div class="card mb-2" id="comment-${comment.id}">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                     style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    ${comment.user.name.substring(0, 2).toUpperCase()}
                                </div>
                                <div>
                                    <div class="fw-semibold">${escapeHtml(comment.user.name)}</div>
                                    <small class="text-muted">${getTimeAgo(comment.created_at)}</small>
                                </div>
                            </div>
                            ${comment.can_delete ? `
                                            <button onclick="deleteComment(${currentTaskId}, ${comment.id})" 
                                                    class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        ` : ''}
                        </div>
                        <div class="ms-5">
                            <p class="mb-0">${escapeHtml(comment.comment)}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        document.getElementById('addCommentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            addComment();
        });

        function addComment() {
            const commentText = document.getElementById('commentText').value.trim();
            if (!commentText) return;

            const submitBtn = document.querySelector('#addCommentForm button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            fetch(`/admin/tasks/${currentTaskId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        comment: commentText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('commentText').value = '';
                        loadComments(currentTaskId);

                        // Update comment count badge in the table
                        updateCommentCount(currentTaskId, 1);
                    }
                })
                .catch(error => {
                    console.error('Error adding comment:', error);
                    alert('Failed to add comment. Please try again.');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send';
                });
        }

        function deleteComment(taskId, commentId) {
            if (!confirm('Delete this comment?')) return;

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

                        // Update comment count badge in the table
                        updateCommentCount(taskId, -1);
                    }
                })
                .catch(error => {
                    console.error('Error deleting comment:', error);
                    alert('Failed to delete comment. Please try again.');
                });
        }

        function updateCommentCount(taskId, delta) {
            // Find the comment button for this task and update the badge
            const buttons = document.querySelectorAll(`button[onclick*="openCommentsModal(${taskId}"]`);
            buttons.forEach(button => {
                const badge = button.querySelector('.badge');
                const currentCount = badge ? parseInt(badge.textContent) : 0;
                const newCount = currentCount + delta;

                if (newCount > 0) {
                    if (badge) {
                        badge.textContent = newCount;
                    } else {
                        button.innerHTML += `
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                ${newCount}
                            </span>
                        `;
                    }
                } else if (badge) {
                    badge.remove();
                }
            });
        }

        function getTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);

            if (seconds < 60) return 'just now';
            if (seconds < 3600) return Math.floor(seconds / 60) + ' minutes ago';
            if (seconds < 86400) return Math.floor(seconds / 3600) + ' hours ago';
            if (seconds < 604800) return Math.floor(seconds / 86400) + ' days ago';

            return date.toLocaleDateString();
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function filterByProject(projectId) {
            const url = new URL(window.location.href);
            if (projectId) {
                url.searchParams.set('project_id', projectId);
            } else {
                url.searchParams.delete('project_id');
            }
            window.location.href = url.toString();
        }
    </script>

</x-app-layout>
