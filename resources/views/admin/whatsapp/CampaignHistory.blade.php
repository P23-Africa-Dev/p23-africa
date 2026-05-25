<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.25rem;">Campaign History</h2>
            <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Track queued, sent, failed, and delivered
                status across event WhatsApp broadcasts.</p>
        </div>
    </x-slot>

    <div class="py-3">
        @include('admin.whatsapp.partials.nav')

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Event Campaigns</span>
                <a href="{{ orgRoute('admin.whatsapp.events.broadcast') }}" class="btn btn-sm btn-success">New Event
                    Broadcast</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3">Campaign</th>
                                <th>Source</th>
                                <th>Template</th>
                                <th>Total</th>
                                <th>Queued</th>
                                <th>Sent</th>
                                <th>Failed</th>
                                <th>Delivered</th>
                                <th>Status</th>
                                <th class="pe-3 text-end">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($campaigns as $campaign)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold">#{{ $campaign->id }}</div>
                                        <small class="text-muted">
                                            {{ optional($campaign->created_at)->format('M d, Y H:i') }}
                                            @if ($campaign->event)
                                                | {{ $campaign->event->title }}
                                            @endif
                                        </small>
                                    </td>
                                    <td>{{ str_replace('_', ' ', ucfirst($campaign->source_type)) }}</td>
                                    <td>{{ $campaign->template?->name ?: '-' }}</td>
                                    <td>{{ $campaign->total_contacts }}</td>
                                    <td>{{ $campaign->queued_count }}</td>
                                    <td>{{ $campaign->sent_count }}</td>
                                    <td>{{ $campaign->failed_count }}</td>
                                    <td>{{ $campaign->delivered_count }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $campaign->status === 'completed' ? 'bg-success' : ($campaign->status === 'failed' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            {{ ucfirst($campaign->status) }}
                                        </span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <a href="{{ orgRoute('admin.whatsapp.events.campaign-history', ['campaign' => $campaign->id]) }}"
                                            class="btn btn-sm btn-outline-secondary">Inspect</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">No event campaigns yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">{{ $campaigns->links() }}</div>
        </div>

        @if ($selectedCampaign)
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Audience Preview: Campaign #{{ $selectedCampaign->id }}</span>
                    <span class="small text-muted">Template: {{ $selectedCampaign->template?->name ?: '-' }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3">Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Event</th>
                                    <th>Seat Code</th>
                                    <th>Status</th>
                                    <th class="pe-3">Sent At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($selectedRecipients as $recipient)
                                    <tr>
                                        <td class="ps-3">
                                            {{ $recipient->registration?->name ?: ($recipient->contact?->name ?: data_get($recipient->variables, 'name', 'N/A')) }}
                                        </td>
                                        <td>{{ $recipient->phone }}</td>
                                        <td>{{ $recipient->registration?->email ?: data_get($recipient->variables, 'email', '-') }}
                                        </td>
                                        <td>{{ $recipient->registration?->event?->title ?: data_get($recipient->variables, 'event_name', '-') }}
                                        </td>
                                        <td>{{ $recipient->registration?->seat_code ?: data_get($recipient->variables, 'seat_code', '-') }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $recipient->status === 'sent' ? 'bg-success' : ($recipient->status === 'failed' ? 'bg-danger' : 'bg-secondary') }}">
                                                {{ ucfirst($recipient->status) }}
                                            </span>
                                        </td>
                                        <td class="pe-3">
                                            {{ optional($recipient->sent_at)->format('M d, Y H:i') ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">No recipients found for
                                            this campaign.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
