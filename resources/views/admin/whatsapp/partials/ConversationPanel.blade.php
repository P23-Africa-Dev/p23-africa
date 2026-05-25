@php
    $conversations = $conversations ?? collect();
    $selectedConversation = $selectedConversation ?? null;
    $filters = $filters ?? ['search' => null, 'status' => null, 'assigned_to' => null];
    $statuses = $statuses ?? [];
    $assignees = $assignees ?? collect();
@endphp

<aside class="wa-panel card wa-conversation-panel" data-wa-conversation-panel>
    <div class="card-header wa-panel-header">
        <form method="GET" action="{{ orgRoute('admin.whatsapp.conversations') }}" class="row g-1" data-wa-search-form>
            <div class="col-12 col-md-7">
                <input type="text" class="form-control form-control-sm" name="search" value="{{ $filters['search'] }}"
                    placeholder="Search conversations" data-wa-search-input>
            </div>
            <div class="col-6 col-md-3">
                <select class="form-select form-select-sm" name="status">
                    <option value="">All Statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ $filters['status'] === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select class="form-select form-select-sm" name="assigned_to">
                    <option value="">All Assignees</option>
                    <option value="unassigned" {{ $filters['assigned_to'] === 'unassigned' ? 'selected' : '' }}>
                        Unassigned
                    </option>
                    @foreach ($assignees as $assignee)
                        <option value="{{ $assignee->id }}"
                            {{ (string) $filters['assigned_to'] === (string) $assignee->id ? 'selected' : '' }}>
                            {{ $assignee->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 d-flex gap-1">
                <button class="btn btn-sm btn-success" type="submit">Filter</button>
                <a class="btn btn-sm btn-outline-secondary"
                    href="{{ orgRoute('admin.whatsapp.conversations') }}">Reset</a>
            </div>
        </form>
    </div>

    <div class="card-body wa-list-scroll" data-wa-conversation-scroll>
        @forelse ($conversations as $conversation)
            @php
                $active = $selectedConversation && $selectedConversation->id === $conversation->id;
                $query = array_filter(array_merge(request()->query(), ['conversation' => $conversation->id]));
                $contact = $conversation->contact;
                $displayName = $contact?->name ?: $contact?->phone ?: 'Unknown Contact';
                $avatar = $contact?->profile_picture ?: $contact?->avatar;
                $initial = strtoupper(mb_substr((string) $displayName, 0, 1));
                $priority = data_get($contact?->metadata, 'priority');
            @endphp
            <div class="wa-conversation-item {{ $active ? 'active' : '' }}" data-wa-conversation-item-wrap>
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <a href="{{ orgRoute('admin.whatsapp.conversations', $query) }}"
                        class="wa-conversation-main-link text-decoration-none text-reset flex-grow-1 min-w-0"
                        data-wa-conversation-item="{{ $conversation->id }}">
                        <div class="d-flex align-items-start gap-2 min-w-0">
                            <span class="wa-avatar wa-avatar-sm flex-shrink-0">
                                @if ($avatar)
                                    <img src="{{ $avatar }}" alt="{{ $displayName }}">
                                @else
                                    {{ $initial }}
                                @endif
                            </span>
                            <div class="min-w-0 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <div class="fw-semibold text-truncate wa-conversation-title">{{ $displayName }}
                                    </div>
                                    <small data-wa-sidebar-updated-at class="text-muted flex-shrink-0">
                                        {{ optional($conversation->updated_at)->format('H:i') }}
                                    </small>
                                </div>
                                <div class="small text-muted wa-conversation-preview mt-1" data-wa-sidebar-last-message>
                                    {{ \Illuminate\Support\Str::limit($conversation->last_message ?: 'No messages yet.', 68) }}
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="d-flex align-items-center gap-1 flex-shrink-0">
                        @include('admin.whatsapp.partials.CompactBadge', [
                            'type' => 'validation-icon',
                            'status' => $contact?->validation_status,
                        ])
                        @if ($priority)
                            @include('admin.whatsapp.partials.CompactBadge', [
                                'type' => 'priority',
                                'status' => $priority,
                                'label' => ucfirst($priority),
                            ])
                        @endif
                        @include('admin.whatsapp.partials.CompactBadge', [
                            'type' => 'unread',
                            'value' => $conversation->unread_count,
                        ])

                        @include('admin.whatsapp.partials.ConversationActionsDropdown', [
                            'conversation' => $conversation,
                            'statuses' => $statuses,
                            'assignees' => $assignees,
                        ])
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">No conversations found.</div>
        @endforelse
    </div>

    <div class="card-footer py-2">{{ $conversations->links('pagination::bootstrap-5') }}</div>
</aside>
