@php
    $selectedConversation = $selectedConversation ?? null;
    $statuses = $statuses ?? [];
    $assignees = $assignees ?? collect();
@endphp

<div class="wa-thread-header card" data-wa-thread-header>
    <div class="d-flex justify-content-between align-items-center gap-2">
        <div class="min-w-0">
            <div class="fw-semibold text-truncate">
                {{ $selectedConversation->contact?->name ?: $selectedConversation->contact?->phone }}
            </div>
            <div class="small text-muted text-truncate">{{ $selectedConversation->contact?->phone }}</div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap wa-thread-actions">
            <span
                class="badge {{ $selectedConversation->status === 'open' ? 'bg-success' : ($selectedConversation->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}"
                data-wa-conversation-status-badge>{{ ucfirst($selectedConversation->status) }}</span>
            <small class="text-muted">Updated <span
                    data-wa-conversation-updated-at>{{ optional($selectedConversation->updated_at)->format('M d, H:i') }}</span></small>

            <form method="POST"
                action="{{ orgRoute('admin.whatsapp.conversations.update', ['conversation' => $selectedConversation->id]) }}"
                class="d-flex align-items-center" data-wa-ajax-conversation-form>
                @csrf
                @method('PATCH')
                <select name="status" class="form-select form-select-sm">
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}"
                            {{ $selectedConversation->status === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </form>

            <form method="POST"
                action="{{ orgRoute('admin.whatsapp.conversations.update', ['conversation' => $selectedConversation->id]) }}"
                class="d-flex align-items-center" data-wa-ajax-conversation-form>
                @csrf
                @method('PATCH')
                <select name="assigned_to" class="form-select form-select-sm">
                    <option value="">Unassigned</option>
                    @foreach ($assignees as $assignee)
                        <option value="{{ $assignee->id }}"
                            {{ (int) $selectedConversation->assigned_to === (int) $assignee->id ? 'selected' : '' }}>
                            {{ $assignee->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="#waComposer" class="btn btn-sm btn-outline-success">Reply</a>
            @include('admin.whatsapp.partials.chat-close-button')
        </div>
    </div>
</div>
