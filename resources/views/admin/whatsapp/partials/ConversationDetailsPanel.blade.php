@php
    $selectedConversation = $selectedConversation ?? null;

    if (!$selectedConversation) {
        return;
    }

    $contact = $selectedConversation->contact;
    $assignees = $assignees ?? collect();

    $noteStoreUrl = orgRoute('admin.whatsapp.notes.store', [
        'conversation' => $selectedConversation->id,
    ]);

    $validationBodyHtml = view('admin.whatsapp.partials.CompactBadge', [
        'type' => 'validation-icon',
        'status' => $contact?->validation_status,
    ])->render();

    $contactInfoHtml =
        '
        <dl class="row mb-0 small">
            <dt class="col-4 text-muted">Name</dt><dd class="col-8">' .
        e($contact?->name ?: 'N/A') .
        '</dd>
            <dt class="col-4 text-muted">Phone</dt><dd class="col-8">' .
        e($contact?->phone ?: 'N/A') .
        '</dd>
            <dt class="col-4 text-muted">Company</dt><dd class="col-8">' .
        e($contact?->company ?: 'N/A') .
        '</dd>
            <dt class="col-4 text-muted">Email</dt><dd class="col-8">' .
        e($contact?->email ?: 'N/A') .
        '</dd>
        </dl>
    ';

    $validationHtml =
        '
        <div class="small d-flex align-items-center gap-2 mb-2">' .
        $validationBodyHtml .
        '<span>' .
        e(ucfirst($contact?->validation_status ?: 'pending')) .
        '</span></div>
        <div class="small text-muted">Last validated: ' .
        e(optional($contact?->validated_at)->format('M d, Y H:i') ?: 'Not yet') .
        '</div>
    ';

    $assignmentHtml =
        '
        <form method="POST" action="' .
        e(orgRoute('admin.whatsapp.conversations.update', ['conversation' => $selectedConversation->id])) .
        '" data-wa-ajax-conversation-form>
            ' .
        csrf_field() .
        '
            ' .
        method_field('PATCH') .
        '
            <div class="mb-2">
                <label class="form-label small mb-1">Assign User</label>
                <select name="assigned_to" class="form-select form-select-sm">
                    <option value="">Unassigned</option>';

    foreach ($assignees as $assignee) {
        $selected = (int) $selectedConversation->assigned_to === (int) $assignee->id ? ' selected' : '';
        $assignmentHtml .=
            '<option value="' . e($assignee->id) . '"' . $selected . '>' . e($assignee->name) . '</option>';
    }

    $assignmentHtml .= '
                </select>
            </div>
            <div>
                <label class="form-label small mb-1">Open / Closed</label>
                <select name="status" class="form-select form-select-sm">';

    foreach (['open', 'pending', 'closed'] as $statusOption) {
        $selected = $selectedConversation->status === $statusOption ? ' selected' : '';
        $assignmentHtml .=
            '<option value="' . e($statusOption) . '"' . $selected . '>' . e(ucfirst($statusOption)) . '</option>';
    }

    $assignmentHtml .= '
                </select>
            </div>
        </form>
    ';

    $deliveryBodyHtml = view('admin.whatsapp.partials.delivery-guide')->render();
    $notesBodyHtml = view('admin.whatsapp.partials.notes-panel', [
        'conversation' => $selectedConversation,
        'notes' => $selectedConversation->notes,
        'noteStoreUrl' => $noteStoreUrl,
    ])->render();
@endphp

<div class="card wa-details-panel" id="waDetailsPanel" data-wa-details-panel>
    <div class="card-header py-2 px-3">
        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-semibold small">Conversation Details</span>
            <span class="text-muted small">Collapsed by default</span>
        </div>
    </div>
    <div class="card-body p-2">
        @include('admin.whatsapp.partials.chat-accordion', [
            'title' => 'Contact Info',
            'key' => 'contact-info-' . $selectedConversation->id,
            'open' => false,
            'bodyHtml' => $contactInfoHtml,
        ])

        @include('admin.whatsapp.partials.chat-accordion', [
            'title' => 'Validation',
            'key' => 'validation-' . $selectedConversation->id,
            'open' => false,
            'bodyHtml' => $validationHtml,
        ])

        @include('admin.whatsapp.partials.chat-accordion', [
            'title' => 'Assignment',
            'key' => 'assignment-' . $selectedConversation->id,
            'open' => false,
            'bodyHtml' => $assignmentHtml,
        ])

        @include('admin.whatsapp.partials.chat-accordion', [
            'title' => 'Delivery',
            'key' => 'delivery-' . $selectedConversation->id,
            'open' => false,
            'bodyHtml' => $deliveryBodyHtml,
        ])

        @include('admin.whatsapp.partials.chat-accordion', [
            'title' => 'Internal Notes',
            'key' => 'notes-' . $selectedConversation->id,
            'open' => false,
            'bodyHtml' => $notesBodyHtml,
        ])
    </div>
</div>
