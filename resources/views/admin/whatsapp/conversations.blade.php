<x-app-layout>
    @push('head')
        <style>
            .wa-chat-shell {
                display: grid;
                grid-template-columns: 280px minmax(0, 1fr) 320px;
                gap: 1rem;
                align-items: start;
            }

            .wa-panel,
            .wa-thread-panel {
                min-height: calc(100vh - 220px);
            }

            .wa-list-scroll {
                max-height: calc(100vh - 274px);
                overflow: auto;
                padding-right: .25rem;
            }

            .wa-thread-panel {
                display: flex;
                flex-direction: column;
                gap: .85rem;
                min-width: 0;
            }

            .wa-thread-header,
            .wa-thread-composer,
            .wa-thread-meta,
            .wa-panel,
            .wa-notes-card {
                border-radius: 18px;
            }

            .wa-thread-header {
                padding: .9rem 1rem;
            }

            .wa-thread-meta {
                display: flex;
                flex-wrap: wrap;
                gap: .5rem;
                align-items: center;
            }

            .wa-thread-meta .form-select,
            .wa-thread-meta .btn {
                height: 34px;
            }

            .wa-message-vault {
                position: relative;
                flex: 1;
                min-height: 0;
                border-radius: 18px;
                border: 1px solid var(--border);
                background:
                    radial-gradient(circle at top left, rgba(25, 135, 84, .04), transparent 35%),
                    linear-gradient(180deg, rgba(255, 255, 255, .86), rgba(255, 255, 255, .98));
                overflow: hidden;
            }

            .wa-message-list {
                height: 100%;
                overflow-y: auto;
                padding: 1rem;
                scroll-behavior: smooth;
            }

            .wa-msg-row {
                display: flex;
                margin-bottom: .7rem;
                animation: waFadeIn .16s ease-out;
            }

            .wa-msg-row.incoming {
                justify-content: flex-start;
            }

            .wa-msg-row.outgoing {
                justify-content: flex-end;
            }

            .wa-bubble {
                max-width: min(82%, 520px);
                border-radius: 18px;
                padding: .7rem .8rem;
                font-size: .93rem;
                line-height: 1.45;
                box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
                position: relative;
            }

            .wa-bubble.incoming {
                background: #fff;
                border: 1px solid rgba(15, 23, 42, .08);
                border-top-left-radius: 8px;
            }

            .wa-bubble.outgoing {
                background: linear-gradient(135deg, rgba(16, 185, 129, .15), rgba(34, 197, 94, .22));
                border: 1px solid rgba(16, 185, 129, .24);
                border-top-right-radius: 8px;
            }

            .wa-bubble.is-optimistic {
                opacity: .82;
                filter: saturate(.9);
            }

            .wa-bubble.is-failed {
                border-color: rgba(239, 68, 68, .35);
                background: rgba(239, 68, 68, .07);
            }

            .wa-bubble-text {
                white-space: pre-wrap;
                word-break: break-word;
            }

            .wa-meta {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: .75rem;
                margin-top: .45rem;
                font-size: .72rem;
                color: #64748b;
            }

            .wa-status {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 16px;
            }

            .wa-status-spinner {
                width: .92rem;
                height: .92rem;
            }

            .wa-status-transition {
                animation: waStatusPulse .24s ease;
            }

            .wa-retry-btn {
                display: inline-flex;
                align-items: center;
                gap: .3rem;
                border: 0;
                background: transparent;
                font-size: .72rem;
                color: var(--p-danger);
                padding: 0;
                cursor: pointer;
            }

            .wa-retry-btn:hover {
                text-decoration: underline;
            }

            .wa-conversation-item {
                border: 1px solid transparent;
                border-radius: 16px;
                padding: .78rem .85rem;
                margin-bottom: .55rem;
                display: block;
                text-decoration: none;
                color: inherit;
                background: var(--bg-primary);
                transition: transform .14s ease, border-color .14s ease, box-shadow .14s ease;
            }

            .wa-conversation-item:hover {
                border-color: rgba(25, 135, 84, .22);
                box-shadow: 0 8px 22px rgba(15, 23, 42, .05);
                transform: translateY(-1px);
            }

            .wa-conversation-item.active {
                border-color: rgba(25, 135, 84, .55);
                background: rgba(25, 135, 84, .07);
            }

            .wa-conversation-preview {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .wa-thread-composer {
                position: sticky;
                bottom: .75rem;
                z-index: 8;
                background: var(--bg-primary);
                border: 1px solid var(--border);
                box-shadow: 0 14px 30px rgba(15, 23, 42, .08);
                padding: .9rem;
            }

            .wa-composer-row {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                gap: .75rem;
                align-items: end;
            }

            [data-wa-message-body] {
                min-height: 56px;
                resize: none;
            }

            .wa-scroll-newest {
                position: absolute;
                right: 1rem;
                bottom: 1rem;
                opacity: 0;
                transform: translateY(10px);
                pointer-events: none;
                transition: opacity .15s ease, transform .15s ease;
            }

            .wa-scroll-newest.show {
                opacity: 1;
                transform: translateY(0);
                pointer-events: auto;
            }

            .wa-accordion {
                border: 1px solid var(--border);
                border-radius: 16px;
                background: var(--bg-primary);
                overflow: hidden;
            }

            .wa-accordion+.wa-accordion {
                margin-top: .75rem;
            }

            .wa-accordion summary {
                list-style: none;
                cursor: pointer;
                padding: .85rem 1rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: .75rem;
                font-weight: 600;
            }

            .wa-accordion summary::-webkit-details-marker {
                display: none;
            }

            .wa-accordion-body {
                border-top: 1px solid var(--border);
                padding: .9rem 1rem 1rem;
            }

            .wa-accordion-chevron {
                transition: transform .16s ease;
            }

            .wa-accordion[open] .wa-accordion-chevron {
                transform: rotate(180deg);
            }

            .wa-skeleton-list {
                display: grid;
                gap: .8rem;
            }

            .wa-skeleton-row {
                display: flex;
            }

            .wa-skeleton-row.incoming {
                justify-content: flex-start;
            }

            .wa-skeleton-row.outgoing {
                justify-content: flex-end;
            }

            .wa-skeleton-bubble {
                width: min(70%, 320px);
                height: 58px;
                border-radius: 18px;
                background: linear-gradient(90deg, rgba(148, 163, 184, .14), rgba(148, 163, 184, .24), rgba(148, 163, 184, .14));
                background-size: 200% 100%;
                animation: waSkeleton 1.3s infinite linear;
            }

            .wa-right-panel {
                position: sticky;
                top: 1rem;
            }

            .wa-side-sticky {
                position: sticky;
                top: 1rem;
            }

            .wa-thread-empty {
                min-height: 340px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .wa-neutral-shell {
                min-height: 420px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }

            .wa-neutral-card {
                width: 100%;
                max-width: 560px;
                border: 1px dashed rgba(100, 116, 139, .35);
                border-radius: 18px;
                background: linear-gradient(145deg, rgba(248, 250, 252, .95), rgba(241, 245, 249, .92));
                padding: 1.5rem;
            }

            .wa-neutral-icon {
                width: 72px;
                height: 72px;
                border-radius: 50%;
                margin: 0 auto;
                display: grid;
                place-items: center;
                font-size: 1.8rem;
                color: #0f766e;
                background: radial-gradient(circle at 30% 30%, rgba(16, 185, 129, .18), rgba(20, 184, 166, .08));
            }

            @keyframes waSkeleton {
                0% {
                    background-position: 200% 0;
                }

                100% {
                    background-position: -200% 0;
                }
            }

            @keyframes waFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(6px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes waStatusPulse {
                0% {
                    transform: scale(.86);
                    opacity: .72;
                }

                100% {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            @media (max-width: 1399px) {
                .wa-chat-shell {
                    grid-template-columns: 260px minmax(0, 1fr) 300px;
                }
            }

            @media (max-width: 1199px) {
                .wa-chat-shell {
                    grid-template-columns: 1fr;
                }

                .wa-side-sticky,
                .wa-right-panel,
                .wa-panel,
                .wa-thread-panel {
                    position: static;
                    min-height: auto;
                }

                .wa-list-scroll {
                    max-height: none;
                }

                .wa-message-vault {
                    min-height: 420px;
                }
            }
        </style>
    @endpush

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div>
                <h2 class="font-semibold mb-1" style="font-size:1.25rem;">WhatsApp Conversations</h2>
                <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Polling-based chat interface with
                    optimistic sends and delta updates.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-success" data-wa-unread-total data-count="{{ $totalUnread }}">Unread:
                    {{ $totalUnread }}</span>
                <a href="{{ orgRoute('admin.whatsapp.overview') }}" class="btn btn-outline-success btn-sm">Overview</a>
            </div>
        </div>
    </x-slot>

    <div class="py-4" data-wa-root data-wa-conversation-id="{{ $selectedConversation?->id ?? '' }}"
        data-wa-poll-url="{{ orgRoute('admin.whatsapp.poll') }}"
        data-wa-status-poll-url="{{ orgRoute('admin.whatsapp.status-poll') }}"
        data-wa-status-poll-interval="{{ $statusPollIntervalMs }}"
        data-wa-sidebar-poll-url="{{ orgRoute('admin.whatsapp.poll') }}"
        data-wa-poll-interval="{{ max(3, (int) $pollInterval) }}"
        data-wa-debug-url="{{ orgRoute('admin.whatsapp.realtime-debug') }}"
        data-wa-debug-enabled="{{ $realtimeDebugEnabled ? '1' : '0' }}" data-wa-poll-cursor="{{ $pollCursor }}">
        @include('admin.whatsapp.partials.nav')

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="wa-chat-shell">
            <aside class="wa-panel card wa-side-sticky">
                <div class="card-header py-3">
                    <form method="GET" action="{{ orgRoute('admin.whatsapp.conversations') }}" class="row g-2"
                        data-wa-search-form>
                        <div class="col-12">
                            <input type="text" class="form-control form-control-sm" name="search"
                                value="{{ $filters['search'] }}" placeholder="Search conversations"
                                data-wa-search-input>
                        </div>
                        <div class="col-6">
                            <select class="form-select form-select-sm" name="status">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ $filters['status'] === $status ? 'selected' : '' }}>{{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <select class="form-select form-select-sm" name="assigned_to">
                                <option value="">All Assignees</option>
                                <option value="unassigned"
                                    {{ $filters['assigned_to'] === 'unassigned' ? 'selected' : '' }}>Unassigned
                                </option>
                                @foreach ($assignees as $assignee)
                                    <option value="{{ $assignee->id }}"
                                        {{ (string) $filters['assigned_to'] === (string) $assignee->id ? 'selected' : '' }}>
                                        {{ $assignee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-sm btn-success" type="submit">Filter</button>
                            <a class="btn btn-sm btn-outline-secondary"
                                href="{{ orgRoute('admin.whatsapp.conversations') }}">Reset</a>
                        </div>
                    </form>
                </div>
                <div class="card-body wa-list-scroll">
                    @forelse ($conversations as $conversation)
                        @php
                            $active = $selectedConversation && $selectedConversation->id === $conversation->id;
                            $query = array_filter(
                                array_merge(request()->query(), ['conversation' => $conversation->id]),
                            );
                        @endphp
                        <a href="{{ orgRoute('admin.whatsapp.conversations', $query) }}"
                            class="wa-conversation-item {{ $active ? 'active' : '' }}"
                            data-wa-conversation-item="{{ $conversation->id }}">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="min-w-0">
                                    <div class="fw-semibold text-truncate">
                                        {{ $conversation->contact?->name ?: $conversation->contact?->phone }}
                                    </div>
                                    <div class="small text-muted text-truncate">{{ $conversation->contact?->phone }}
                                    </div>
                                </div>
                                <span class="badge bg-danger {{ $conversation->unread_count > 0 ? '' : 'd-none' }}"
                                    data-wa-sidebar-unread>{{ $conversation->unread_count }}</span>
                            </div>
                            <div class="small text-muted mt-1 wa-conversation-preview" data-wa-sidebar-last-message>
                                {{ \Illuminate\Support\Str::limit($conversation->last_message, 68) }}
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span
                                    class="badge {{ $conversation->status === 'open' ? 'bg-success' : ($conversation->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}"
                                    data-wa-sidebar-status>
                                    {{ ucfirst($conversation->status) }}
                                </span>
                                <small data-wa-sidebar-updated-at
                                    class="text-muted">{{ optional($conversation->updated_at)->diffForHumans() }}</small>
                            </div>
                        </a>
                    @empty
                        <div class="text-center text-muted py-4">No conversations found.</div>
                    @endforelse
                </div>
                <div class="card-footer">{{ $conversations->links() }}</div>
            </aside>

            <section class="wa-thread-panel">
                @if ($selectedConversation)
                    <div class="card wa-thread-header">
                        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                            <div class="min-w-0">
                                <div class="fw-semibold fs-5 text-truncate">
                                    {{ $selectedConversation->contact?->name ?: $selectedConversation->contact?->phone }}
                                </div>
                                <div class="small text-muted text-truncate">
                                    {{ $selectedConversation->contact?->phone }}</div>
                                <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
                                    <span
                                        class="badge {{ $selectedConversation->status === 'open' ? 'bg-success' : ($selectedConversation->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}"
                                        data-wa-conversation-status-badge>{{ ucfirst($selectedConversation->status) }}</span>
                                    <small class="text-muted">Updated <span
                                            data-wa-conversation-updated-at>{{ optional($selectedConversation->updated_at)->format('M d, H:i') }}</span></small>
                                </div>
                            </div>

                            <div class="wa-thread-meta">
                                <form method="POST"
                                    action="{{ orgRoute('admin.whatsapp.conversations.update', ['conversation' => $selectedConversation->id]) }}"
                                    class="d-flex gap-2 align-items-center" data-wa-ajax-conversation-form>
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
                                    class="d-flex gap-2 align-items-center" data-wa-ajax-conversation-form>
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

                                <a href="#waComposer" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-send me-1"></i>Reply
                                </a>

                                @include('admin.whatsapp.partials.chat-close-button')
                            </div>
                        </div>
                    </div>

                    <div data-wa-thread-content>
                        <div class="wa-message-vault card">
                            <div class="wa-message-list" id="waMessages" data-wa-message-list>
                                @forelse ($selectedConversation->messages as $message)
                                    @include('admin.whatsapp.partials.message-bubble', [
                                        'message' => $message,
                                        'incoming' => $message->sender_type === 'contact',
                                        'status' => $message->status,
                                    ])
                                @empty
                                    <div class="wa-thread-empty text-muted text-center" data-wa-empty-state>
                                        <div>
                                            <i class="bi bi-chat-square-text display-6 d-block mb-2"></i>
                                            <div>No messages yet.</div>
                                            <small>Start the conversation with a reply.</small>
                                        </div>
                                        <div class="mt-4 w-100">
                                            @include('admin.whatsapp.partials.loading-skeleton')
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <button type="button" class="btn btn-success btn-sm wa-scroll-newest"
                                data-wa-scroll-newest>
                                <i class="bi bi-arrow-down-circle me-1"></i>Newest message
                            </button>
                        </div>

                        <div class="wa-thread-composer" id="waComposer">
                            <form method="POST"
                                action="{{ orgRoute('admin.whatsapp.messages.send', ['conversation' => $selectedConversation->id]) }}"
                                data-wa-send-form>
                                @csrf
                                <input type="hidden" name="type" value="text">
                                <div class="row g-2 mb-2">
                                    <div class="col-12 col-lg-4">
                                        <select class="form-select form-select-sm" id="waTemplatePicker">
                                            <option value="">Insert template text (optional)</option>
                                            @foreach ($templates as $template)
                                                <option value="{{ json_encode($template->template_json) }}">
                                                    {{ $template->name }} ({{ $template->language }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-8">
                                        {{-- <div class="small text-muted mb-2 d-flex align-items-center gap-2">
                                            <span class="spinner-grow spinner-grow-sm text-success"
                                                role="status"></span>
                                            Typing placeholder ready for future typing webhooks.
                                        </div> --}}
                                        <textarea name="body" id="waMessageBody" class="form-control" rows="2" placeholder="Type your reply..."
                                            data-wa-message-body></textarea>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                                    <small class="text-muted">Press Enter to send, Shift+Enter for a new line.</small>
                                    <div class="d-flex gap-2 align-items-center">
                                        <button class="btn btn-outline-secondary btn-sm" type="button" disabled
                                            title="Attachment sending is a prepared placeholder."><i
                                                class="bi bi-paperclip"></i></button>
                                        <button class="btn btn-success" type="submit" data-wa-send-button>
                                            <i class="bi bi-send me-1"></i>Send Message
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card d-none" data-wa-empty-chat-wrap>
                        <div class="card-body">
                            @include('admin.whatsapp.partials.chat-empty-state', [
                                'selectedConversation' => $selectedConversation,
                            ])
                        </div>
                    </div>
                @else
                    <div class="card wa-thread-panel">
                        <div class="card-body">
                            @include('admin.whatsapp.partials.chat-empty-state', [
                                'selectedConversation' => null,
                            ])
                        </div>
                    </div>
                @endif
            </section>

            <aside class="wa-right-panel" data-wa-right-panel>
                @if ($selectedConversation)
                    @php
                        $notesBodyHtml = view('admin.whatsapp.partials.notes-panel', [
                            'conversation' => $selectedConversation,
                            'notes' => $selectedConversation->notes,
                            'noteStoreUrl' => orgRoute('admin.whatsapp.notes.store', [
                                'conversation' => $selectedConversation->id,
                            ]),
                        ])->render();

                        $deliveryBodyHtml = view('admin.whatsapp.partials.delivery-guide')->render();
                    @endphp

                    <div class="wa-right-panel">
                        @include('admin.whatsapp.partials.chat-accordion', [
                            'title' => 'Internal Notes',
                            'key' => 'notes-' . $selectedConversation->id,
                            'open' => false,
                            'bodyHtml' => $notesBodyHtml,
                        ])

                        @include('admin.whatsapp.partials.chat-accordion', [
                            'title' => 'Delivery Details',
                            'key' => 'delivery-' . $selectedConversation->id,
                            'open' => false,
                            'bodyHtml' => $deliveryBodyHtml,
                        ])

                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold">Message Health</span>
                                    <span class="badge bg-light text-dark">Polling</span>
                                </div>
                                <div class="small text-muted mb-0">
                                    Active conversation polling interval: {{ max(3, (int) $pollInterval) }}s
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card wa-right-panel">
                        <div class="card-body text-muted">
                            Right-side panels appear when a conversation is selected.
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</x-app-layout>
