@php
    $selectedConversation = $selectedConversation ?? null;
@endphp

<div class="wa-neutral-shell" data-wa-neutral-state>
    <div class="wa-neutral-card text-center">
        <div class="wa-neutral-icon mb-3">
            <i class="bi bi-chat-dots"></i>
        </div>
        <h4 class="mb-2">No conversation selected</h4>
        <p class="text-muted mb-3">Select a conversation from the left panel to start chatting.</p>

        @if ($selectedConversation)
            <div class="small text-muted mb-3">
                Last active:
                <strong>{{ $selectedConversation->contact?->name ?: $selectedConversation->contact?->phone }}</strong>
            </div>
        @endif

        <div class="d-flex justify-content-center gap-2 flex-wrap mb-3">
            <button type="button" class="btn btn-success btn-sm" data-wa-reopen-chat>
                <i class="bi bi-chat-left-text me-1"></i>Reopen Chat
            </button>
            <a href="{{ orgRoute('admin.whatsapp.overview') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-speedometer2 me-1"></i>Overview
            </a>
        </div>

        <div class="small text-muted">Recent activity stays in the sidebar and continues polling.</div>
    </div>
</div>
