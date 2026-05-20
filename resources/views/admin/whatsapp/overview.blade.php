<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold mb-1" style="font-size:1.25rem;">WhatsApp Dashboard</h2>
                <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Monitor conversations, message flow,
                    and team workload.</p>
            </div>
            <a href="{{ orgRoute('admin.whatsapp.conversations') }}" class="btn btn-success">
                <i class="bi bi-chat-dots me-1"></i>Open Inbox
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        @include('admin.whatsapp.partials.nav')

        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Conversations</div>
                        <div class="h3 mb-0">{{ $stats['total_conversations'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="card h-100 border-success-subtle">
                    <div class="card-body">
                        <div class="text-muted small">Open</div>
                        <div class="h3 mb-0 text-success">{{ $stats['open_conversations'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="card h-100 border-warning-subtle">
                    <div class="card-body">
                        <div class="text-muted small">Pending</div>
                        <div class="h3 mb-0 text-warning">{{ $stats['pending_conversations'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="card h-100 border-secondary-subtle">
                    <div class="card-body">
                        <div class="text-muted small">Closed</div>
                        <div class="h3 mb-0 text-secondary">{{ $stats['closed_conversations'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Contacts</div>
                        <div class="h4 mb-0">{{ $stats['total_contacts'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Unread Messages</div>
                        <div class="h4 mb-0">{{ $stats['total_unread'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Incoming Today</div>
                        <div class="h4 mb-0">{{ $stats['incoming_today'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Outgoing Today</div>
                        <div class="h4 mb-0">{{ $stats['outgoing_today'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Recent Conversations</span>
                <a href="{{ orgRoute('admin.whatsapp.conversations') }}" class="btn btn-sm btn-outline-success">View
                    All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Contact</th>
                                <th>Last Message</th>
                                <th>Status</th>
                                <th>Assigned</th>
                                <th class="text-end pe-3">Unread</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentConversations as $conversation)
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ orgRoute('admin.whatsapp.conversations', ['conversation' => $conversation->id]) }}"
                                            class="text-decoration-none">
                                            {{ $conversation->contact?->name ?: $conversation->contact?->phone }}
                                        </a>
                                        <div class="small text-muted">{{ $conversation->contact?->phone }}</div>
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($conversation->last_message, 60) }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $conversation->status === 'open' ? 'bg-success' : ($conversation->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                            {{ ucfirst($conversation->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $conversation->assignedUser?->name ?: 'Unassigned' }}</td>
                                    <td class="text-end pe-3">{{ $conversation->unread_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No conversations yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
