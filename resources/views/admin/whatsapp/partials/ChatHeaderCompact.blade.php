@php
    $selectedConversation = $selectedConversation ?? null;
    $statuses = $statuses ?? [];
    $assignees = $assignees ?? collect();
@endphp

<div class="wa-thread-header card" data-wa-thread-header>
    <div class="d-flex justify-content-between align-items-center gap-2">
        @php
            $contact = $selectedConversation->contact;
            $displayName = $contact?->name ?: $contact?->phone ?: 'Unknown Contact';
            $avatar = $contact?->profile_picture ?: $contact?->avatar;
            $initial = strtoupper(mb_substr((string) $displayName, 0, 1));
        @endphp

        <div class="min-w-0 d-flex align-items-center gap-2" data-wa-contact-summary>
            <span class="wa-avatar">
                @if ($avatar)
                    <img src="{{ $avatar }}" alt="{{ $displayName }}">
                @else
                    {{ $initial }}
                @endif
            </span>

            <div class="min-w-0">
                <div class="fw-semibold text-truncate" data-wa-contact-name>
                    {{ $displayName }}
                </div>
                @if ($contact?->phone)
                    <div class="small text-muted text-truncate" data-wa-contact-phone>{{ $contact->phone }}</div>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 wa-thread-actions">
            @include('admin.whatsapp.partials.CompactBadge', [
                'type' => 'validation-icon',
                'status' => $contact?->validation_status,
            ])
            <small class="text-muted d-none d-md-inline">Updated <span
                    data-wa-conversation-updated-at>{{ optional($selectedConversation->updated_at)->format('M d, H:i') }}</span></small>
            @include('admin.whatsapp.partials.ChatHeaderMenu', [
                'selectedConversation' => $selectedConversation,
                'statuses' => $statuses,
                'assignees' => $assignees,
            ])

            <span class="d-none" data-wa-conversation-status-badge>{{ ucfirst($selectedConversation->status) }}</span>
        </div>
    </div>
</div>
