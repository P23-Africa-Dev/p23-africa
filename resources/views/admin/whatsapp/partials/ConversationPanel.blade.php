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
            @endphp
            <div class="wa-conversation-item {{ $active ? 'active' : '' }}" data-wa-conversation-item-wrap>
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <a href="{{ orgRoute('admin.whatsapp.conversations', $query) }}"
                        class="wa-conversation-main-link text-decoration-none text-reset flex-grow-1 min-w-0"
                        data-wa-conversation-item="{{ $conversation->id }}">
                        <div class="fw-semibold text-truncate wa-conversation-title">
                            {{ $conversation->contact?->name ?: $conversation->contact?->phone }}
                        </div>
                        <div class="small text-muted text-truncate wa-conversation-phone">
                            {{ $conversation->contact?->phone }}</div>
                        <div class="mt-1 d-flex gap-1 flex-wrap">
                            @if ($conversation->contact?->unsaved_contact)
                                <span class="badge bg-info text-dark">Unsaved</span>
                            @endif
                            @include('admin.whatsapp.partials.ContactValidationBadge', [
                                'contact' => $conversation->contact,
                            ])
                        </div>
                    </a>
                    <div class="d-flex align-items-start gap-1">
                        <button type="button" class="btn btn-outline-warning wa-compact-btn" data-wa-clear-conversation
                            data-wa-clear-url="{{ orgRoute('admin.whatsapp.conversations.clear', ['conversation' => $conversation->id]) }}">
                            Clear
                        </button>
                        <button type="button" class="btn btn-outline-danger wa-compact-btn" data-wa-delete-contact
                            data-wa-delete-url="{{ $conversation->contact ? orgRoute('admin.whatsapp.contacts.destroy', ['contact' => $conversation->contact->id]) : '' }}"
                            {{ $conversation->contact ? '' : 'disabled' }}>
                            Delete
                        </button>
                        <span class="badge bg-danger {{ $conversation->unread_count > 0 ? '' : 'd-none' }}"
                            data-wa-sidebar-unread>{{ $conversation->unread_count }}</span>
                    </div>
                </div>
                <div class="small text-muted mt-1 wa-conversation-preview" data-wa-sidebar-last-message>
                    {{ \Illuminate\Support\Str::limit($conversation->last_message, 68) }}
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span
                        class="badge {{ $conversation->status === 'open' ? 'bg-success' : ($conversation->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}"
                        data-wa-sidebar-status>
                        {{ ucfirst($conversation->status) }}
                    </span>
                    <small data-wa-sidebar-updated-at
                        class="text-muted">{{ optional($conversation->updated_at)->diffForHumans() }}</small>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">No conversations found.</div>
        @endforelse
    </div>

    <div class="card-footer py-2">{{ $conversations->links('pagination::bootstrap-5') }}</div>
</aside>
