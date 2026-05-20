<x-app-layout>
    @push('head')
        <style>
            .wa-shell {
                display: grid;
                grid-template-columns: 360px 1fr;
                gap: 1rem;
            }

            .wa-list {
                height: calc(100vh - 270px);
                overflow: auto;
            }

            .wa-thread {
                height: calc(100vh - 270px);
                display: flex;
                flex-direction: column;
            }

            .wa-messages {
                flex: 1;
                overflow-y: auto;
                padding: .75rem;
                background: linear-gradient(180deg, rgba(25, 135, 84, .04), rgba(255, 255, 255, 0));
                border-radius: .5rem;
                border: 1px solid var(--border);
            }

            .wa-msg-row {
                display: flex;
                margin-bottom: .75rem;
            }

            .wa-msg-row.incoming {
                justify-content: flex-start;
            }

            .wa-msg-row.outgoing {
                justify-content: flex-end;
            }

            .wa-bubble {
                max-width: 72%;
                border-radius: .75rem;
                padding: .55rem .7rem;
                font-size: .875rem;
                box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
            }

            .wa-bubble.incoming {
                background: #ffffff;
                border: 1px solid #d7dee4;
            }

            .wa-bubble.outgoing {
                background: #d1fae5;
                border: 1px solid #8ee3bf;
            }

            .wa-meta {
                display: flex;
                justify-content: space-between;
                margin-top: .35rem;
                font-size: .7rem;
                color: #6b7280;
            }

            .wa-conversation-item {
                border: 1px solid transparent;
                border-radius: .6rem;
                padding: .6rem;
                margin-bottom: .45rem;
                display: block;
                text-decoration: none;
                color: inherit;
                background: var(--bg-primary);
            }

            .wa-conversation-item:hover {
                border-color: rgba(25, 135, 84, .35);
                background: rgba(25, 135, 84, .04);
            }

            .wa-conversation-item.active {
                border-color: rgba(25, 135, 84, .8);
                background: rgba(25, 135, 84, .08);
            }

            .wa-notes {
                max-height: 180px;
                overflow-y: auto;
            }

            @media (max-width: 991px) {
                .wa-shell {
                    grid-template-columns: 1fr;
                }

                .wa-list,
                .wa-thread {
                    height: auto;
                }

                .wa-messages {
                    min-height: 320px;
                    max-height: 420px;
                }
            }
        </style>
    @endpush

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold mb-1" style="font-size:1.25rem;">WhatsApp Conversations</h2>
                <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Live inbox with polling fallback for
                    shared hosting.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success" id="waUnreadTotal">Unread: {{ $totalUnread }}</span>
                <a href="{{ orgRoute('admin.whatsapp.overview') }}" class="btn btn-outline-success btn-sm">Overview</a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
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

        <div class="wa-shell">
            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ orgRoute('admin.whatsapp.conversations') }}" class="row g-2">
                        <div class="col-12">
                            <input type="text" class="form-control form-control-sm" name="search"
                                value="{{ $filters['search'] }}" placeholder="Search name, phone, or message">
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
                                    {{ $filters['assigned_to'] === 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                                @foreach ($assignees as $assignee)
                                    <option value="{{ $assignee->id }}"
                                        {{ (string) $filters['assigned_to'] === (string) $assignee->id ? 'selected' : '' }}>
                                        {{ $assignee->name }}</option>
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
                <div class="card-body wa-list">
                    @forelse ($conversations as $conversation)
                        @php
                            $active = $selectedConversation && $selectedConversation->id === $conversation->id;
                            $query = array_filter(
                                array_merge(request()->query(), ['conversation' => $conversation->id]),
                            );
                        @endphp
                        <a href="{{ orgRoute('admin.whatsapp.conversations', $query) }}"
                            class="wa-conversation-item {{ $active ? 'active' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold">
                                        {{ $conversation->contact?->name ?: $conversation->contact?->phone }}</div>
                                    <div class="small text-muted">{{ $conversation->contact?->phone }}</div>
                                </div>
                                @if ($conversation->unread_count > 0)
                                    <span class="badge bg-danger">{{ $conversation->unread_count }}</span>
                                @endif
                            </div>
                            <div class="small text-muted mt-1">
                                {{ \Illuminate\Support\Str::limit($conversation->last_message, 50) }}</div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span
                                    class="badge {{ $conversation->status === 'open' ? 'bg-success' : ($conversation->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                    {{ ucfirst($conversation->status) }}
                                </span>
                                <small
                                    class="text-muted">{{ optional($conversation->updated_at)->diffForHumans() }}</small>
                            </div>
                        </a>
                    @empty
                        <div class="text-center text-muted py-4">No conversations found.</div>
                    @endforelse
                </div>
                <div class="card-footer">{{ $conversations->links() }}</div>
            </div>

            <div class="card">
                @if ($selectedConversation)
                    <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">
                                {{ $selectedConversation->contact?->name ?: $selectedConversation->contact?->phone }}
                            </div>
                            <small class="text-muted">{{ $selectedConversation->contact?->phone }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <form method="POST"
                                action="{{ orgRoute('admin.whatsapp.conversations.update', ['conversation' => $selectedConversation->id]) }}"
                                class="d-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}"
                                            {{ $selectedConversation->status === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <form method="POST"
                                action="{{ orgRoute('admin.whatsapp.conversations.update', ['conversation' => $selectedConversation->id]) }}"
                                class="d-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="assigned_to" class="form-select form-select-sm"
                                    onchange="this.form.submit()">
                                    <option value="">Unassigned</option>
                                    @foreach ($assignees as $assignee)
                                        <option value="{{ $assignee->id }}"
                                            {{ (int) $selectedConversation->assigned_to === (int) $assignee->id ? 'selected' : '' }}>
                                            {{ $assignee->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>

                    <div class="card-body wa-thread">
                        <div class="wa-messages" id="waMessages">
                            @forelse ($selectedConversation->messages as $message)
                                @php
                                    $incoming = $message->sender_type === 'contact';
                                @endphp
                                <div class="wa-msg-row {{ $incoming ? 'incoming' : 'outgoing' }}"
                                    data-message-id="{{ $message->id }}">
                                    <div class="wa-bubble {{ $incoming ? 'incoming' : 'outgoing' }}">
                                        <div>{{ $message->body ?: '[No text body]' }}</div>
                                        <div class="wa-meta">
                                            <span>{{ optional($message->created_at)->format('H:i') }}</span>
                                            <span class="text-uppercase">{{ $message->status }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted text-center py-4">No messages yet.</div>
                            @endforelse
                        </div>

                        <div class="mt-2 mb-2 d-flex justify-content-between align-items-center">
                            <small class="text-muted">Typing indicator placeholder: a real typing webhook can be
                                connected later.</small>
                            <button class="btn btn-sm btn-outline-secondary" type="button" disabled
                                title="Attachment sending is a prepared placeholder.">
                                <i class="bi bi-paperclip"></i> Attachment Placeholder
                            </button>
                        </div>

                        <form method="POST"
                            action="{{ orgRoute('admin.whatsapp.messages.send', ['conversation' => $selectedConversation->id]) }}"
                            class="mt-auto">
                            @csrf
                            <input type="hidden" name="type" value="text">
                            <div class="row g-2">
                                <div class="col-12 col-md-4">
                                    <select class="form-select form-select-sm" id="waTemplatePicker">
                                        <option value="">Insert template text (optional)</option>
                                        @foreach ($templates as $template)
                                            <option value="{{ json_encode($template->template_json) }}">
                                                {{ $template->name }} ({{ $template->language }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-8">
                                    <textarea name="body" id="waMessageBody" class="form-control" rows="2" placeholder="Type your reply..."></textarea>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button class="btn btn-success" type="submit">
                                        <i class="bi bi-send me-1"></i>Send Message
                                    </button>
                                </div>
                            </div>
                        </form>

                        <hr>

                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <div class="fw-semibold mb-2">Internal Notes</div>
                                <div class="wa-notes border rounded p-2 mb-2">
                                    @forelse ($selectedConversation->notes as $note)
                                        <div class="mb-2 pb-2 border-bottom">
                                            <div class="small fw-semibold">{{ $note->admin?->name ?: 'Admin' }}</div>
                                            <div class="small">{{ $note->note }}</div>
                                        </div>
                                    @empty
                                        <div class="text-muted small">No internal notes yet.</div>
                                    @endforelse
                                </div>
                                <form method="POST"
                                    action="{{ orgRoute('admin.whatsapp.notes.store', ['conversation' => $selectedConversation->id]) }}">
                                    @csrf
                                    <textarea name="note" class="form-control form-control-sm" rows="2" placeholder="Add internal note..."></textarea>
                                    <div class="mt-2 text-end">
                                        <button class="btn btn-sm btn-outline-success" type="submit">Save
                                            Note</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="fw-semibold mb-2">Delivery Status Guide</div>
                                <ul class="small mb-0">
                                    <li><span class="badge bg-secondary">queued</span> waiting for queue worker</li>
                                    <li><span class="badge bg-info text-dark">sent</span> accepted by Meta API</li>
                                    <li><span class="badge bg-primary">delivered</span> delivered to recipient device
                                    </li>
                                    <li><span class="badge bg-success">read</span> recipient read receipt received</li>
                                    <li><span class="badge bg-danger">failed</span> send failed, check webhook logs
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card-body text-center text-muted py-5">
                        Select a conversation from the left pane.
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const pollIntervalMs = {{ max(2, (int) $pollInterval) }} * 1000;
                const selectedConversationId = {{ $selectedConversation?->id ?: 'null' }};
                const pollUrl = @json(orgRoute('admin.whatsapp.poll'));
                let since = new Date().toISOString();

                const templatePicker = document.getElementById('waTemplatePicker');
                const bodyInput = document.getElementById('waMessageBody');

                if (templatePicker && bodyInput) {
                    templatePicker.addEventListener('change', function() {
                        if (!this.value) {
                            return;
                        }

                        try {
                            const json = JSON.parse(this.value);
                            const text = (json.components || [])
                                .filter(c => c.type === 'BODY')
                                .map(c => c.text || '')
                                .join('\n') || (json.body || '');
                            if (text) {
                                bodyInput.value = text;
                            }
                        } catch (e) {}
                    });
                }

                async function poll() {
                    try {
                        const response = await fetch(pollUrl + '?since=' + encodeURIComponent(since), {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            return;
                        }

                        const data = await response.json();
                        if (!data.success) {
                            return;
                        }

                        const unreadTotalEl = document.getElementById('waUnreadTotal');
                        if (unreadTotalEl) {
                            unreadTotalEl.textContent = 'Unread: ' + (data.unread_total || 0);
                        }

                        const hasConversationUpdates = Array.isArray(data.conversations) && data.conversations.length >
                            0;
                        const hasCurrentThreadUpdates = Array.isArray(data.messages) && selectedConversationId !==
                            null &&
                            data.messages.some(m => Number(m.conversation_id) === Number(selectedConversationId));

                        if (hasConversationUpdates || hasCurrentThreadUpdates) {
                            window.location.reload();
                            return;
                        }

                        since = data.server_time || new Date().toISOString();
                    } catch (e) {}
                }

                setInterval(poll, pollIntervalMs);
            })();
        </script>
    @endpush
</x-app-layout>
