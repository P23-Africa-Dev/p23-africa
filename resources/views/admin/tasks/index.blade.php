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
                            <a href="{{ orgRoute('admin.tasks.kanban', request()->query()) }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-columns me-1"></i> Kanban Board
                            </a>
                            @if (auth()->user()->hasRole(['admin', 'supervisor', 'staff']))
                                <a href="{{ orgRoute('admin.tasks.create', request()->query()) }}" class="btn btn-dark">
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
                                            <a href="{{ orgRoute('admin.projects.show', $task->project) }}"
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
                                                            fetch('{{ orgRoute('admin.tasks.update-status', $task) }}', {
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
                                                <a href="{{ orgRoute('admin.tasks.show', $task) }}"
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
                                                    <a href="{{ orgRoute('admin.tasks.edit', $task) }}"
                                                        class="btn btn-outline-primary mx-1" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ orgRoute('admin.tasks.destroy', $task) }}"
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
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentsModalLabel">
                        <i class="fas fa-comments me-2"></i>Comments: <span id="taskTitle"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Reply indicator -->
                    <div id="replyingToContainer" class="alert alert-info d-none mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-reply me-2"></i>Replying to: <strong
                                    id="replyingToName"></strong></span>
                            <button type="button" class="btn-close" onclick="cancelReply()"></button>
                        </div>
                    </div>

                    <!-- Add Comment Form -->
                    <div class="mb-4">
                        <div class="position-relative">
                            <textarea class="form-control mb-2" id="commentText" rows="3"
                                placeholder="Write a comment... Use @ to mention someone"></textarea>

                            <!-- Mentions Dropdown -->
                            <div id="mentionsDropdown" class="mentions-dropdown d-none">
                                <div class="mentions-list"></div>
                            </div>
                        </div>

                        <!-- Attachment Preview -->
                        <div id="attachmentPreview" class="d-none mb-2">
                            <div class="card">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-paperclip text-muted"></i>
                                            <span id="attachmentFileName" class="small"></span>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-link text-danger"
                                            onclick="removeAttachment()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div id="imagePreviewContainer" class="mt-2 d-none">
                                        <img id="imagePreview" src="" alt="Preview" class="img-thumbnail"
                                            style="max-height: 100px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <input type="file" id="attachmentInput" class="d-none"
                                    accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="document.getElementById('attachmentInput').click()">
                                    <i class="fas fa-paperclip me-1"></i> Attach
                                </button>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="submitCommentBtn"
                                onclick="addComment()">
                                <i class="fas fa-paper-plane me-1"></i> Send
                            </button>
                        </div>
                        <input type="hidden" id="parentCommentId" value="">
                    </div>

                    <hr>

                    <!-- Comments List -->
                    <div id="commentsContainer">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .mentions-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1050;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            max-height: 200px;
            overflow-y: auto;
        }

        .mention-item {
            padding: 10px 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background-color 0.2s;
        }

        .mention-item:hover,
        .mention-item.active {
            background-color: #f0f4ff;
        }

        .mention-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .comment-mention {
            background-color: #e3f2fd;
            color: #1976d2;
            padding: 1px 4px;
            border-radius: 4px;
            font-weight: 500;
        }

        .reply-comment {
            margin-left: 30px;
            border-left: 3px solid #e9ecef;
            padding-left: 12px;
        }

        .comment-attachment {
            margin-top: 8px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .comment-attachment img {
            max-width: 200px;
            max-height: 150px;
            border-radius: 4px;
        }

        .comment-attachment-file {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #495057;
            font-size: 0.85rem;
        }
    </style>

    <script>
        let currentTaskId = null;
        let commentsModal = null;
        let mentionableUsers = [];
        let selectedMentionIndex = 0;
        let mentionStartPosition = -1;
        let selectedFile = null;
        const csrfToken = '{{ csrf_token() }}';

        // Initialize modal after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('commentsModal');
            if (modalElement) {
                commentsModal = new bootstrap.Modal(modalElement);

                // Reset state when modal is hidden
                modalElement.addEventListener('hidden.bs.modal', function() {
                    cancelReply();
                    removeAttachment();
                    document.getElementById('commentText').value = '';
                    mentionableUsers = [];
                });
            }

            setupMentions();
            setupAttachment();
        });

        function openCommentsModal(taskId, taskTitle) {
            currentTaskId = taskId;
            document.getElementById('taskTitle').textContent = taskTitle;

            if (!commentsModal) {
                const modalElement = document.getElementById('commentsModal');
                if (modalElement) {
                    commentsModal = new bootstrap.Modal(modalElement);
                }
            }

            if (commentsModal) {
                commentsModal.show();
                loadComments(taskId);
                loadMentionableUsers(taskId);
            }
        }

        function loadMentionableUsers(taskId) {
            fetch(`${window.adminBasePath}/tasks/${taskId}/mentionable-users`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mentionableUsers = data.users;
                    }
                })
                .catch(error => console.error('Error loading mentionable users:', error));
        }

        function setupMentions() {
            const textarea = document.getElementById('commentText');
            const dropdown = document.getElementById('mentionsDropdown');

            textarea.addEventListener('input', function(e) {
                const cursorPos = this.selectionStart;
                const text = this.value;

                let atIndex = -1;
                for (let i = cursorPos - 1; i >= 0; i--) {
                    if (text[i] === '@') {
                        atIndex = i;
                        break;
                    } else if (text[i] === ' ' || text[i] === '\n') {
                        break;
                    }
                }

                if (atIndex >= 0) {
                    const searchText = text.substring(atIndex + 1, cursorPos).toLowerCase();
                    mentionStartPosition = atIndex;
                    showMentionsDropdown(searchText);
                } else {
                    hideMentionsDropdown();
                }
            });

            textarea.addEventListener('keydown', function(e) {
                if (!dropdown.classList.contains('d-none')) {
                    const items = dropdown.querySelectorAll('.mention-item');

                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        selectedMentionIndex = Math.min(selectedMentionIndex + 1, items.length - 1);
                        updateMentionSelection(items);
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        selectedMentionIndex = Math.max(selectedMentionIndex - 1, 0);
                        updateMentionSelection(items);
                    } else if (e.key === 'Enter' && items.length > 0) {
                        e.preventDefault();
                        items[selectedMentionIndex]?.click();
                    } else if (e.key === 'Escape') {
                        hideMentionsDropdown();
                    }
                }
            });
        }

        function showMentionsDropdown(searchText) {
            const dropdown = document.getElementById('mentionsDropdown');
            const list = dropdown.querySelector('.mentions-list');

            const filtered = mentionableUsers.filter(user =>
                user.name.toLowerCase().includes(searchText)
            );

            if (filtered.length === 0) {
                hideMentionsDropdown();
                return;
            }

            list.innerHTML = filtered.map((user, index) => `
                <div class="mention-item ${index === 0 ? 'active' : ''}" 
                     onclick="selectMention(${user.id}, '${escapeHtml(user.name)}')">
                    <div class="mention-avatar">${user.initials}</div>
                    <span>${escapeHtml(user.name)}</span>
                </div>
            `).join('');

            selectedMentionIndex = 0;
            dropdown.classList.remove('d-none');
        }

        function hideMentionsDropdown() {
            document.getElementById('mentionsDropdown').classList.add('d-none');
            mentionStartPosition = -1;
        }

        function updateMentionSelection(items) {
            items.forEach((item, index) => {
                item.classList.toggle('active', index === selectedMentionIndex);
            });
        }

        function selectMention(userId, userName) {
            const textarea = document.getElementById('commentText');
            const text = textarea.value;
            const cursorPos = textarea.selectionStart;

            const before = text.substring(0, mentionStartPosition);
            const after = text.substring(cursorPos);
            textarea.value = before + '@' + userName + ' ' + after;

            const newPos = mentionStartPosition + userName.length + 2;
            textarea.setSelectionRange(newPos, newPos);
            textarea.focus();

            hideMentionsDropdown();
        }

        function setupAttachment() {
            document.getElementById('attachmentInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 10 * 1024 * 1024) {
                        alert('File size must be less than 10MB');
                        this.value = '';
                        return;
                    }

                    selectedFile = file;
                    document.getElementById('attachmentFileName').textContent = file.name;
                    document.getElementById('attachmentPreview').classList.remove('d-none');

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('imagePreview').src = e.target.result;
                            document.getElementById('imagePreviewContainer').classList.remove('d-none');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        document.getElementById('imagePreviewContainer').classList.add('d-none');
                    }
                }
            });
        }

        function removeAttachment() {
            selectedFile = null;
            document.getElementById('attachmentInput').value = '';
            document.getElementById('attachmentPreview').classList.add('d-none');
            document.getElementById('imagePreviewContainer').classList.add('d-none');
        }

        function replyToComment(commentId, userName) {
            document.getElementById('parentCommentId').value = commentId;
            document.getElementById('replyingToName').textContent = userName;
            document.getElementById('replyingToContainer').classList.remove('d-none');
            document.getElementById('commentText').focus();
            document.getElementById('commentText').placeholder = `Reply to ${userName}...`;
        }

        function cancelReply() {
            document.getElementById('parentCommentId').value = '';
            document.getElementById('replyingToContainer').classList.add('d-none');
            document.getElementById('commentText').placeholder = 'Write a comment... Use @ to mention someone';
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

            fetch(`${window.adminBasePath}/tasks/${taskId}/comments`)
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

            container.innerHTML = comments.map(comment => renderComment(comment, false)).join('');
        }

        function renderComment(comment, isReply = false) {
            const initials = comment.user.name.substring(0, 2).toUpperCase();
            const formattedComment = formatCommentWithMentions(comment.comment);

            let attachmentHtml = '';
            if (comment.attachment_path) {
                if (comment.is_image) {
                    attachmentHtml = `
                        <div class="comment-attachment">
                            <a href="${comment.attachment_url}" target="_blank">
                                <img src="${comment.attachment_url}" alt="${escapeHtml(comment.attachment_name)}">
                            </a>
                        </div>
                    `;
                } else {
                    attachmentHtml = `
                        <div class="comment-attachment">
                            <a href="${comment.attachment_url}" target="_blank" class="comment-attachment-file text-decoration-none">
                                <i class="fas fa-file"></i>
                                <span>${escapeHtml(comment.attachment_name)}</span>
                            </a>
                        </div>
                    `;
                }
            }

            let repliesHtml = '';
            if (comment.replies && comment.replies.length > 0) {
                repliesHtml = comment.replies.map(reply => renderComment(reply, true)).join('');
            }

            return `
                <div class="card mb-2 ${isReply ? 'reply-comment' : ''}" id="comment-${comment.id}">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                     style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    ${initials}
                                </div>
                                <div>
                                    <div class="fw-semibold">${escapeHtml(comment.user.name)}</div>
                                    <small class="text-muted">${getTimeAgo(comment.created_at)}</small>
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                ${!isReply ? `
                                        <button onclick="replyToComment(${comment.id}, '${escapeHtml(comment.user.name)}')" 
                                                class="btn btn-sm btn-outline-secondary" title="Reply">
                                            <i class="fas fa-reply"></i>
                                        </button>
                                    ` : ''}
                                ${comment.can_delete ? `
                                        <button onclick="deleteComment(${currentTaskId}, ${comment.id})" 
                                                class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    ` : ''}
                            </div>
                        </div>
                        <div class="ms-5">
                            <p class="mb-0">${formattedComment}</p>
                            ${attachmentHtml}
                        </div>
                    </div>
                </div>
                ${repliesHtml}
            `;
        }

        function formatCommentWithMentions(text) {
            return escapeHtml(text).replace(/@(\w+(?:\s+\w+)*)/g, '<span class="comment-mention">@$1</span>');
        }

        function addComment() {
            const commentText = document.getElementById('commentText').value.trim();
            if (!commentText) {
                alert('Please enter a comment');
                return;
            }

            const submitBtn = document.getElementById('submitCommentBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            const mentionedUserIds = extractMentionedUserIds(commentText);
            const parentId = document.getElementById('parentCommentId').value || null;

            const formData = new FormData();
            formData.append('comment', commentText);
            if (parentId) formData.append('parent_id', parentId);
            if (selectedFile) formData.append('attachment', selectedFile);
            if (mentionedUserIds.length > 0) {
                mentionedUserIds.forEach(id => formData.append('mentioned_users[]', id));
            }

            fetch(`${window.adminBasePath}/tasks/${currentTaskId}/comments`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('commentText').value = '';
                        removeAttachment();
                        cancelReply();
                        loadComments(currentTaskId);
                        updateCommentCount(currentTaskId, 1);
                    } else {
                        alert(data.message || 'Failed to add comment');
                    }
                })
                .catch(error => {
                    console.error('Error adding comment:', error);
                    alert('Failed to add comment. Please try again.');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Send';
                });
        }

        function extractMentionedUserIds(text) {
            const mentions = text.match(/@(\w+(?:\s+\w+)*)/g) || [];
            const userIds = [];

            mentions.forEach(mention => {
                const name = mention.substring(1);
                const user = mentionableUsers.find(u =>
                    u.name.toLowerCase() === name.toLowerCase()
                );
                if (user && !userIds.includes(user.id)) {
                    userIds.push(user.id);
                }
            });

            return userIds;
        }

        function deleteComment(taskId, commentId) {
            if (!confirm('Delete this comment?')) return;

            fetch(`${window.adminBasePath}/tasks/${taskId}/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadComments(taskId);
                        updateCommentCount(taskId, -1);
                    }
                })
                .catch(error => {
                    console.error('Error deleting comment:', error);
                    alert('Failed to delete comment. Please try again.');
                });
        }

        function updateCommentCount(taskId, delta) {
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
