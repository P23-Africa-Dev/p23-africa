@php
    $conversation = $conversation ?? null;
    $statuses = $statuses ?? [];
    $assignees = $assignees ?? collect();

    if (!$conversation) {
        return;
    }

    $menuId = 'waConversationMenu' . $conversation->id;
    $statusFormId = 'waStatusForm' . $conversation->id;
    $assignFormId = 'waAssignForm' . $conversation->id;

    $conversationUrl = orgRoute(
        'admin.whatsapp.conversations',
        array_filter(
            array_merge(request()->query(), [
                'conversation' => $conversation->id,
            ]),
        ),
    );
@endphp

<div class="wa-actions-dropdown" data-wa-actions-dropdown>
    <button class="btn btn-link wa-menu-trigger" type="button" aria-expanded="false" aria-haspopup="true"
        aria-label="Open conversation actions" data-wa-action-menu-trigger>
        <i class="bi bi-three-dots-vertical"></i>
    </button>

    <div class="wa-menu" id="{{ $menuId }}" data-wa-action-menu role="menu" tabindex="-1">
        <div class="wa-menu-section-title">CONTACT</div>
        @if ($conversation->contact?->unsaved_contact)
            <button type="button" class="dropdown-item" data-wa-menu-action data-bs-toggle="modal"
                data-bs-target="#waSaveContactModal" data-wa-open-save-contact
                data-wa-phone="{{ $conversation->contact?->phone }}" data-wa-name="{{ $conversation->contact?->name }}"
                data-wa-country-code="{{ $conversation->contact?->country_code }}">
                <i class="bi bi-person-plus me-2"></i>Save Contact
            </button>
        @endif
        @if ($conversation->contact)
            <a class="dropdown-item" data-wa-menu-action
                href="{{ orgRoute('admin.whatsapp.contacts', ['search' => $conversation->contact->phone]) }}">
                <i class="bi bi-pencil-square me-2"></i>Edit Contact
            </a>
        @endif

        <div class="wa-menu-divider"></div>
        <div class="wa-menu-section-title">CHAT</div>
        <a class="dropdown-item" data-wa-menu-action href="{{ $conversationUrl }}">
            <i class="bi bi-chat-left-text me-2"></i>Open Chat
        </a>
        <button class="dropdown-item" type="button" data-wa-menu-action data-wa-clear-conversation
            data-wa-clear-url="{{ orgRoute('admin.whatsapp.conversations.clear', ['conversation' => $conversation->id]) }}">
            <i class="bi bi-eraser me-2"></i>Clear Chat
        </button>
        <button class="dropdown-item" type="button" data-wa-menu-action data-wa-download-thread
            data-wa-download-fallback-url="{{ $conversationUrl }}" data-wa-conversation-id="{{ $conversation->id }}"
            data-wa-conversation-name="{{ $conversation->contact?->name ?: $conversation->contact?->phone }}"
            data-wa-conversation-phone="{{ $conversation->contact?->phone }}">
            <i class="bi bi-download me-2"></i>Download
        </button>

        <div class="wa-menu-divider"></div>
        <div class="wa-menu-section-title">STATUS</div>
        <form id="{{ $statusFormId }}" method="POST"
            action="{{ orgRoute('admin.whatsapp.conversations.update', ['conversation' => $conversation->id]) }}"
            data-wa-ajax-conversation-form class="px-2 py-1">
            @csrf
            @method('PATCH')
            <label class="wa-menu-label">Open / Closed</label>
            <select name="status" class="form-select form-select-sm">
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" {{ $conversation->status === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </form>

        <form id="{{ $assignFormId }}" method="POST"
            action="{{ orgRoute('admin.whatsapp.conversations.update', ['conversation' => $conversation->id]) }}"
            data-wa-ajax-conversation-form class="px-2 py-1">
            @csrf
            @method('PATCH')
            <label class="wa-menu-label">Assign User</label>
            <select name="assigned_to" class="form-select form-select-sm">
                <option value="">Unassigned</option>
                @foreach ($assignees as $assignee)
                    <option value="{{ $assignee->id }}"
                        {{ (int) $conversation->assigned_to === (int) $assignee->id ? 'selected' : '' }}>
                        {{ $assignee->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <div class="dropdown-item-text d-flex align-items-center justify-content-between pt-1 pb-1 px-3">
            <span class="text-muted">Validation</span>
            <span class="d-inline-flex align-items-center gap-2">
                @include('admin.whatsapp.partials.CompactBadge', [
                    'type' => 'validation-icon',
                    'status' => $conversation->contact?->validation_status,
                ])
                <span class="small">{{ ucfirst($conversation->contact?->validation_status ?: 'pending') }}</span>
            </span>
        </div>

        <div class="wa-menu-divider"></div>
        <a class="dropdown-item" data-wa-menu-action href="{{ $conversationUrl }}#waDetailsPanel"
            data-wa-open-details-panel>
            <i class="bi bi-info-circle me-2"></i>Conversation Details
        </a>
        @if ($conversation->contact)
            <button type="button" class="dropdown-item text-danger" data-wa-menu-action data-wa-delete-contact
                data-wa-delete-url="{{ orgRoute('admin.whatsapp.contacts.destroy', ['contact' => $conversation->contact->id]) }}">
                <i class="bi bi-trash me-2"></i>Delete Contact
            </button>
        @endif

        <span class="badge d-none" data-wa-sidebar-status>{{ ucfirst($conversation->status) }}</span>
    </div>
</div>
