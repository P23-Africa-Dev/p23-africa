<x-app-layout>
    @push('head')
        <style>
            .wa-chat-shell {
                --wa-shell-height: calc(100vh - 190px);
                display: grid;
                grid-template-columns: 280px minmax(0, 1fr) 260px;
                gap: .5rem;
                align-items: stretch;
            }

            .wa-density-compact .card-header,
            .wa-density-compact .card-body,
            .wa-density-compact .card-footer {
                padding-top: .5rem;
                padding-bottom: .5rem;
            }

            .wa-density-compact .btn,
            .wa-density-compact .form-control,
            .wa-density-compact .form-select,
            .wa-density-compact .badge,
            .wa-density-compact small,
            .wa-density-compact .small {
                font-size: .76rem;
            }

            .wa-compact-btn {
                min-height: 27px;
                padding: .08rem .42rem;
                font-size: .72rem;
                line-height: 1.2;
            }

            .wa-conversation-panel,
            .wa-thread-card {
                height: var(--wa-shell-height);
            }

            .wa-conversation-panel {
                display: flex;
                flex-direction: column;
                border-radius: 14px;
                overflow: hidden;
            }

            .wa-panel-header {
                padding: .6rem .7rem;
            }

            .wa-list-scroll {
                flex: 1;
                overflow-y: auto;
                overscroll-behavior: contain;
                padding: .55rem;
            }

            .wa-conversation-item {
                border: 1px solid rgba(148, 163, 184, .28);
                border-radius: 12px;
                padding: .45rem .48rem;
                margin-bottom: .35rem;
                display: block;
                text-decoration: none;
                color: inherit;
                background: var(--bg-primary);
                transition: border-color .12s ease, box-shadow .12s ease;
            }

            .wa-conversation-main-link {
                display: block;
            }

            .wa-conversation-title {
                font-size: .81rem;
                line-height: 1.18;
            }

            .wa-conversation-phone {
                font-size: .7rem;
                line-height: 1.1;
            }

            .wa-conversation-item:hover {
                border-color: rgba(25, 135, 84, .35);
                box-shadow: 0 5px 14px rgba(15, 23, 42, .06);
            }

            .wa-conversation-item.active {
                border-color: rgba(25, 135, 84, .65);
                background: rgba(25, 135, 84, .07);
            }

            .wa-conversation-preview {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .wa-thread-card {
                display: flex;
                flex-direction: column;
                border-radius: 14px;
                overflow: hidden;
            }

            .wa-thread-header {
                border: 0;
                border-bottom: 1px solid var(--border);
                border-radius: 0;
                padding: .36rem .52rem;
                background: rgba(255, 255, 255, .9);
            }

            .wa-thread-actions .form-select,
            .wa-thread-actions .btn {
                height: 30px;
                padding-top: .2rem;
                padding-bottom: .2rem;
                font-size: .78rem;
            }

            .wa-thread-content {
                display: flex;
                flex-direction: column;
                min-height: 0;
                flex: 1;
            }

            .wa-message-vault {
                position: relative;
                flex: 1;
                min-height: 0;
                overflow: hidden;
                background:
                    radial-gradient(circle at top left, rgba(25, 135, 84, .04), transparent 35%),
                    linear-gradient(180deg, rgba(255, 255, 255, .88), rgba(255, 255, 255, .98));
                border-bottom: 1px solid var(--border);
            }

            .wa-message-list {
                height: 100%;
                overflow-y: auto;
                overscroll-behavior: contain;
                padding: .7rem .8rem;
                scroll-behavior: smooth;
            }

            .wa-history-loader {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: .45rem;
                color: #64748b;
                margin-bottom: .55rem;
            }

            .wa-msg-row {
                display: flex;
                margin-bottom: .56rem;
            }

            .wa-msg-row.incoming {
                justify-content: flex-start;
            }

            .wa-msg-row.outgoing {
                justify-content: flex-end;
            }

            .wa-bubble {
                max-width: min(82%, 500px);
                border-radius: 14px;
                padding: .45rem .56rem;
                font-size: .78rem;
                line-height: 1.34;
                box-shadow: 0 6px 18px rgba(15, 23, 42, .05);
            }

            .wa-bubble.incoming {
                background: #fff;
                border: 1px solid rgba(15, 23, 42, .08);
                border-top-left-radius: 7px;
            }

            .wa-bubble.outgoing {
                background: linear-gradient(135deg, rgba(16, 185, 129, .13), rgba(34, 197, 94, .2));
                border: 1px solid rgba(16, 185, 129, .2);
                border-top-right-radius: 7px;
            }

            .wa-bubble-text {
                white-space: pre-wrap;
                word-break: break-word;
            }

            .wa-meta {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: .6rem;
                margin-top: .36rem;
                font-size: .68rem;
                color: #64748b;
            }

            .wa-thread-composer {
                position: sticky;
                bottom: 0;
                z-index: 3;
                background: var(--bg-primary);
                padding: .4rem .52rem;
            }

            [data-wa-message-body] {
                min-height: 44px;
                resize: none;
            }

            .wa-scroll-newest {
                position: absolute;
                right: .8rem;
                bottom: .8rem;
                opacity: 0;
                transform: translateY(8px);
                pointer-events: none;
                transition: opacity .15s ease, transform .15s ease;
            }

            .wa-scroll-newest.show {
                opacity: 1;
                transform: translateY(0);
                pointer-events: auto;
            }

            .wa-right-panel {
                border-radius: 14px;
                padding: 0;
            }

            .wa-right-panel .wa-accordion {
                border-radius: 12px;
            }

            .wa-right-panel .wa-accordion summary {
                padding: .58rem .72rem;
                font-size: .84rem;
            }

            .wa-right-panel .wa-accordion-body {
                padding: .58rem .72rem .72rem;
            }

            .wa-status-transition {
                animation: waStatusPulse .24s ease;
            }

            .wa-status-spinner {
                width: .86rem;
                height: .86rem;
            }

            .wa-thread-empty {
                min-height: 280px;
                display: flex;
                align-items: center;
                justify-content: center;
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
                    grid-template-columns: 260px minmax(0, 1fr) 240px;
                }
            }

            @media (max-width: 1199px) {
                .wa-chat-shell {
                    grid-template-columns: 1fr;
                }

                .wa-chat-shell {
                    --wa-shell-height: auto;
                }

                .wa-conversation-panel,
                .wa-thread-card {
                    height: auto;
                }

                .wa-list-scroll,
                .wa-message-list {
                    max-height: 420px;
                }
            }
        </style>
    @endpush

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <div>
                <h2 class="font-semibold mb-0" style="font-size:1rem;">WhatsApp Conversations</h2>
                <p class="mb-0" style="font-size:.74rem;color:var(--text-muted);">Compact dashboard chat with
                    incremental
                    updates and lazy history.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success" data-wa-unread-total data-count="{{ $totalUnread }}">Unread:
                    {{ $totalUnread }}</span>
                <button class="btn btn-success btn-sm wa-compact-btn" type="button" data-bs-toggle="modal"
                    data-bs-target="#waNewChatModal">New Chat</button>
                <a href="{{ orgRoute('admin.whatsapp.overview') }}"
                    class="btn btn-outline-success btn-sm wa-compact-btn">Overview</a>
            </div>
        </div>
    </x-slot>

    <div class="py-2 wa-density-compact" data-wa-root data-wa-conversation-id="{{ $selectedConversation?->id ?? '' }}"
        data-wa-poll-url="{{ orgRoute('admin.whatsapp.poll') }}"
        data-wa-status-poll-url="{{ orgRoute('admin.whatsapp.status-poll') }}"
        data-wa-status-poll-interval="{{ $statusPollIntervalMs }}"
        data-wa-sidebar-poll-url="{{ orgRoute('admin.whatsapp.poll') }}"
        data-wa-poll-interval="{{ max(3, (int) $pollInterval) }}"
        data-wa-quick-chat-validate-url="{{ orgRoute('admin.whatsapp.quick-chat.validate') }}"
        data-wa-quick-chat-start-url="{{ orgRoute('admin.whatsapp.quick-chat.start') }}"
        data-wa-save-contact-url="{{ orgRoute('admin.whatsapp.quick-chat.save-contact') }}"
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
            @include('admin.whatsapp.partials.ConversationPanel', [
                'conversations' => $conversations,
                'selectedConversation' => $selectedConversation,
                'filters' => $filters,
                'statuses' => $statuses,
                'assignees' => $assignees,
            ])

            <section class="wa-thread-panel">
                @if ($selectedConversation)
                    @include('admin.whatsapp.partials.MessageViewport', [
                        'selectedConversation' => $selectedConversation,
                        'templates' => $templates,
                        'statuses' => $statuses,
                        'assignees' => $assignees,
                        'historyUrl' => orgRoute('admin.whatsapp.conversations.history', [
                            'conversation' => $selectedConversation->id,
                        ]),
                        'historyLimit' => $historyPageSize,
                    ])
                @else
                    <div class="card wa-thread-card">
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
                @else
                    <div class="card">
                        <div class="card-body text-muted small">
                            Right-side panels appear when a conversation is selected.
                        </div>
                    </div>
                @endif
            </aside>
        </div>

        @include('admin.whatsapp.partials.NewChatModal')
        @include('admin.whatsapp.partials.SaveContactModal')
    </div>
</x-app-layout>
