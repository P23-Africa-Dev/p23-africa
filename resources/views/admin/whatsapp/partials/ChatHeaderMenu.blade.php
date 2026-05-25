@php
    $selectedConversation = $selectedConversation ?? null;
    $statuses = $statuses ?? [];
    $assignees = $assignees ?? collect();

    if (!$selectedConversation) {
        return;
    }

    $statusSelectFormId = 'waHeaderStatusSelectForm' . $selectedConversation->id;
    $assignFormId = 'waHeaderAssignForm' . $selectedConversation->id;
    $openFormId = 'waHeaderOpenForm' . $selectedConversation->id;
    $closeFormId = 'waHeaderCloseForm' . $selectedConversation->id;
    $replyFormId = 'waHeaderReplyForm' . $selectedConversation->id;
@endphp

<div class="wa-actions-dropdown" data-wa-actions-dropdown>
    <button class="btn btn-link wa-menu-trigger" type="button" aria-expanded="false" aria-haspopup="true"
        aria-label="Open chat header actions" data-wa-action-menu-trigger>
        <i class="bi bi-three-dots-vertical"></i>
    </button>

    <div class="wa-menu wa-menu-header" data-wa-action-menu role="menu" tabindex="-1">
        <div class="wa-menu-section-title">CONTACT</div>
        @if ($selectedConversation->contact?->unsaved_contact)
            <button type="button" class="dropdown-item" data-wa-menu-action data-bs-toggle="modal"
                data-bs-target="#waSaveContactModal" data-wa-open-save-contact
                data-wa-phone="{{ $selectedConversation->contact?->phone }}"
                data-wa-name="{{ $selectedConversation->contact?->name }}"
                data-wa-country-code="{{ $selectedConversation->contact?->country_code }}">
                <i class="bi bi-person-plus me-2"></i>Save Contact
            </button>
        @endif
        @if ($selectedConversation->contact)
            <a class="dropdown-item" data-wa-menu-action
                href="{{ orgRoute('admin.whatsapp.contacts', ['search' => $selectedConversation->contact->phone]) }}">
                <i class="bi bi-pencil-square me-2"></i>Edit Contact
            </a>
        @endif

        <div class="wa-menu-divider"></div>
        <div class="wa-menu-section-title">CHAT</div>
        <button class="dropdown-item" type="button" data-wa-menu-action data-wa-download-thread
            data-wa-conversation-id="{{ $selectedConversation->id }}"
            data-wa-conversation-name="{{ $selectedConversation->contact?->name ?: $selectedConversation->contact?->phone }}"
            data-wa-conversation-phone="{{ $selectedConversation->contact?->phone }}">
            <i class="bi bi-download me-2"></i>Download
        </button>
        <button type="button" class="dropdown-item" data-wa-menu-action data-wa-open-details-panel
            data-wa-open-accordion="contact-info-{{ $selectedConversation->id }}">
            <i class="bi bi-info-circle me-2"></i>Conversation Details
        </button>
        <button type="button" class="dropdown-item" data-wa-menu-action
            data-wa-toggle-accordion="notes-{{ $selectedConversation->id }}" data-wa-open-details-panel>
            <i class="bi bi-journal-text me-2"></i>Notes
        </button>
        <button type="button" class="dropdown-item" data-wa-menu-action
            data-wa-toggle-accordion="delivery-{{ $selectedConversation->id }}" data-wa-open-details-panel>
            <i class="bi bi-truck me-2"></i>Delivery Status
        </button>
        <button type="button" class="dropdown-item" data-wa-menu-action data-wa-close-chat>
            <i class="bi bi-arrows-collapse me-2"></i>Collapse Chat View
        </button>

        <div class="wa-menu-divider"></div>
        <div class="wa-menu-section-title">STATUS</div>

        <form id="{{ $assignFormId }}" method="POST"
            action="{{ orgRoute('admin.whatsapp.conversations.update', ['conversation' => $selectedConversation->id]) }}"
            data-wa-ajax-conversation-form class="px-2 py-1">
            @csrf
            @method('PATCH')
            <label class="wa-menu-label">Assign User</label>
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

        <form id="{{ $statusSelectFormId }}" method="POST"
            action="{{ orgRoute('admin.whatsapp.conversations.update', ['conversation' => $selectedConversation->id]) }}"
            data-wa-ajax-conversation-form class="px-2 py-1">
            @csrf
            @method('PATCH')
            <label class="wa-menu-label">Open / Closed</label>
            <select name="status" class="form-select form-select-sm">
                @foreach ($statuses as $status)
                    <option value="{{ $status }}"
                        {{ $selectedConversation->status === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </form>

        <form id="{{ $openFormId }}" method="POST"
            action="{{ orgRoute('admin.whatsapp.conversations.update', ['conversation' => $selectedConversation->id]) }}"
            data-wa-ajax-conversation-form class="d-none">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="open">
        </form>
        <form id="{{ $closeFormId }}" method="POST"
            action="{{ orgRoute('admin.whatsapp.conversations.update', ['conversation' => $selectedConversation->id]) }}"
            data-wa-ajax-conversation-form class="d-none">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="closed">
        </form>
        <form id="{{ $replyFormId }}" method="POST"
            action="{{ orgRoute('admin.whatsapp.conversations.update', ['conversation' => $selectedConversation->id]) }}"
            data-wa-ajax-conversation-form class="d-none">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="pending">
        </form>

        <button type="button" class="dropdown-item" data-wa-menu-action data-wa-menu-submit="{{ $openFormId }}">
            <i class="bi bi-unlock me-2"></i>Open
        </button>
        <button type="button" class="dropdown-item" data-wa-menu-action data-wa-menu-submit="{{ $closeFormId }}">
            <i class="bi bi-lock me-2"></i>Close Chat
        </button>
        <button type="button" class="dropdown-item" data-wa-menu-action data-wa-menu-submit="{{ $replyFormId }}">
            <i class="bi bi-reply me-2"></i>Mark Replied
        </button>

        <div class="dropdown-item-text d-flex align-items-center justify-content-between pt-1 pb-1 px-3">
            <span class="text-muted">Validation</span>
            <span class="d-inline-flex align-items-center gap-2">
                @include('admin.whatsapp.partials.CompactBadge', [
                    'type' => 'validation-icon',
                    'status' => $selectedConversation->contact?->validation_status,
                ])
                <span
                    class="small">{{ ucfirst($selectedConversation->contact?->validation_status ?: 'pending') }}</span>
            </span>
        </div>
    </div>
</div>
