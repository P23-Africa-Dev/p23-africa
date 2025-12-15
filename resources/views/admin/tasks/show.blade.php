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
                                    {!! strip_tags(($task->description)) !!}
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
                    <div class="card mb-4" id="mainCommentForm">
                        <div class="card-body">
                            <div id="replyingToContainer" class="alert alert-info d-none mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-reply me-2"></i>Replying to: <strong
                                            id="replyingToName"></strong></span>
                                    <button type="button" class="btn-close" onclick="cancelReply()"></button>
                                </div>
                            </div>

                            <div class="position-relative">
                                <textarea class="form-control mb-3" id="commentText" rows="3"
                                    placeholder="Write a comment... Use @ to mention someone" required></textarea>

                                <!-- Mentions Dropdown -->
                                <div id="mentionsDropdown" class="mentions-dropdown d-none">
                                    <div class="mentions-list"></div>
                                </div>
                            </div>

                            <!-- Attachment Preview -->
                            <div id="attachmentPreview" class="d-none mb-3">
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
                                            <img id="imagePreview" src="" alt="Preview"
                                                class="img-thumbnail" style="max-height: 150px;">
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
                                        <i class="fas fa-paperclip me-1"></i> Attach File
                                    </button>
                                </div>
                                <button type="button" class="btn btn-primary" id="submitCommentBtn"
                                    onclick="submitComment()">
                                    <i class="fas fa-paper-plane me-1"></i> Post Comment
                                </button>
                            </div>
                            <input type="hidden" id="parentCommentId" value="">
                        </div>
                    </div>

                    <!-- Comments List -->
                    <div id="commentsContainer">
                        <!-- Comments will be loaded via JavaScript -->
                        <div class="text-center py-4">
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
            z-index: 1000;
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
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
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
            margin-left: 40px;
            border-left: 3px solid #e9ecef;
            padding-left: 15px;
        }

        .comment-attachment {
            margin-top: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .comment-attachment img {
            max-width: 300px;
            max-height: 200px;
            border-radius: 6px;
        }

        .comment-attachment-file {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #495057;
        }

        .comment-attachment-file i {
            font-size: 1.5rem;
            color: #6c757d;
        }
    </style>

    <script>
        const taskId = {{ $task->id }};
        const csrfToken = '{{ csrf_token() }}';
        const currentUserId = {{ auth()->id() }};
        const isAdmin = {{ auth()->user()->hasRole('admin') ? 'true' : 'false' }};

        let mentionableUsers = [];
        let selectedMentionIndex = 0;
        let mentionStartPosition = -1;
        let selectedFile = null;

        // Load comments and mentionable users on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadComments();
            loadMentionableUsers();
            setupMentions();
            setupAttachment();
        });

        // Load mentionable users
        function loadMentionableUsers() {
            fetch(`/admin/tasks/${taskId}/mentionable-users`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mentionableUsers = data.users;
                    }
                })
                .catch(error => console.error('Error loading mentionable users:', error));
        }

        // Setup @mentions functionality
        function setupMentions() {
            const textarea = document.getElementById('commentText');
            const dropdown = document.getElementById('mentionsDropdown');

            textarea.addEventListener('input', function(e) {
                const cursorPos = this.selectionStart;
                const text = this.value;

                // Find @ symbol before cursor
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
                     data-user-id="${user.id}" data-user-name="${user.name}"
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

            // Replace @searchText with @userName
            const before = text.substring(0, mentionStartPosition);
            const after = text.substring(cursorPos);
            textarea.value = before + '@' + userName + ' ' + after;

            // Set cursor position after the mention
            const newPos = mentionStartPosition + userName.length + 2;
            textarea.setSelectionRange(newPos, newPos);
            textarea.focus();

            hideMentionsDropdown();
        }

        // Setup file attachment
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

                    // Show image preview if it's an image
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

        // Load comments
        function loadComments() {
            fetch(`/admin/tasks/${taskId}/comments`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderComments(data.comments);
                        updateCommentCount(data.comments.length);
                    }
                })
                .catch(error => {
                    console.error('Error loading comments:', error);
                    document.getElementById('commentsContainer').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>Failed to load comments
                        </div>
                    `;
                });
        }

        function renderComments(comments) {
            const container = document.getElementById('commentsContainer');

            if (comments.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-muted py-5" id="noCommentsMessage">
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
                            <div class="small text-muted mt-1">${escapeHtml(comment.attachment_name)}</div>
                        </div>
                    `;
                } else {
                    attachmentHtml = `
                        <div class="comment-attachment">
                            <a href="${comment.attachment_url}" target="_blank" class="comment-attachment-file text-decoration-none">
                                <i class="fas fa-file"></i>
                                <span>${escapeHtml(comment.attachment_name)}</span>
                                <i class="fas fa-download ms-2 small"></i>
                            </a>
                        </div>
                    `;
                }
            }

            let repliesHtml = '';
            if (comment.replies && comment.replies.length > 0) {
                repliesHtml = `
                    <div class="replies-container mt-3">
                        ${comment.replies.map(reply => renderComment(reply, true)).join('')}
                    </div>
                `;
            }

            const timeAgo = getTimeAgo(new Date(comment.created_at));

            return `
                <div class="card mb-3 ${isReply ? 'reply-comment' : ''}" id="comment-${comment.id}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px; font-size: 0.9rem; flex-shrink: 0;">
                                    ${initials}
                                </div>
                                <div>
                                    <div class="fw-semibold">${escapeHtml(comment.user.name)}</div>
                                    <small class="text-muted">${timeAgo}</small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                ${!isReply ? `
                                        <button onclick="replyToComment(${comment.id}, '${escapeHtml(comment.user.name)}')" 
                                                class="btn btn-sm btn-outline-secondary" title="Reply">
                                            <i class="fas fa-reply"></i>
                                        </button>
                                    ` : ''}
                                ${comment.can_delete ? `
                                        <button onclick="deleteComment(${comment.id})" 
                                                class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    ` : ''}
                            </div>
                        </div>
                        <div class="ms-5 ps-2">
                            <p class="mb-0">${formattedComment}</p>
                            ${attachmentHtml}
                        </div>
                    </div>
                    ${repliesHtml}
                </div>
            `;
        }

        function formatCommentWithMentions(text) {
            // Convert @username to highlighted mentions
            return escapeHtml(text).replace(/@(\w+(?:\s+\w+)*)/g, '<span class="comment-mention">@$1</span>');
        }

        // Reply to comment
        function replyToComment(commentId, userName) {
            document.getElementById('parentCommentId').value = commentId;
            document.getElementById('replyingToName').textContent = userName;
            document.getElementById('replyingToContainer').classList.remove('d-none');
            document.getElementById('commentText').focus();
            document.getElementById('commentText').placeholder = `Reply to ${userName}...`;

            // Scroll to comment form
            document.getElementById('mainCommentForm').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function cancelReply() {
            document.getElementById('parentCommentId').value = '';
            document.getElementById('replyingToContainer').classList.add('d-none');
            document.getElementById('commentText').placeholder = 'Write a comment... Use @ to mention someone';
        }

        // Submit comment
        function submitComment() {
            const commentText = document.getElementById('commentText').value.trim();
            if (!commentText) {
                alert('Please enter a comment');
                return;
            }

            const submitBtn = document.getElementById('submitCommentBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Posting...';

            // Extract mentioned user IDs from comment text
            const mentionedUserIds = extractMentionedUserIds(commentText);
            const parentId = document.getElementById('parentCommentId').value || null;

            // Create FormData for file upload
            const formData = new FormData();
            formData.append('comment', commentText);
            if (parentId) formData.append('parent_id', parentId);
            if (selectedFile) formData.append('attachment', selectedFile);
            if (mentionedUserIds.length > 0) {
                mentionedUserIds.forEach(id => formData.append('mentioned_users[]', id));
            }

            fetch(`/admin/tasks/${taskId}/comments`, {
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
                        loadComments(); // Reload all comments
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
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Post Comment';
                });
        }

        function extractMentionedUserIds(text) {
            const mentions = text.match(/@(\w+(?:\s+\w+)*)/g) || [];
            const userIds = [];

            mentions.forEach(mention => {
                const name = mention.substring(1); // Remove @
                const user = mentionableUsers.find(u =>
                    u.name.toLowerCase() === name.toLowerCase()
                );
                if (user && !userIds.includes(user.id)) {
                    userIds.push(user.id);
                }
            });

            return userIds;
        }

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
                        loadComments(); // Reload all comments
                    } else {
                        alert(data.message || 'Failed to delete comment');
                    }
                })
                .catch(error => {
                    console.error('Error deleting comment:', error);
                    alert('Failed to delete comment. Please try again.');
                });
        }

        function updateCommentCount(count) {
            const countBadge = document.getElementById('commentCount');
            if (countBadge) {
                countBadge.textContent = count;
            }
        }

        function getTimeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);

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
    </script>

</x-app-layout>
