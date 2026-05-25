<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.25rem;">Event Contacts</h2>
            <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Select event audiences and review only
                validated WhatsApp attendees.</p>
        </div>
    </x-slot>

    <div class="py-3">
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

        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ orgRoute('admin.whatsapp.events.contacts') }}"
                    class="row g-2 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Event Filter</label>
                        <select name="event_filter" class="form-select form-select-sm">
                            @foreach ($eventFilterOptions as $option)
                                <option value="{{ $option }}"
                                    {{ ($filters['event_filter'] ?? '') === $option ? 'selected' : '' }}>
                                    {{ ucfirst($option) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Single Event</label>
                        <select name="event_id" class="form-select form-select-sm">
                            <option value="">Select event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}"
                                    {{ (int) ($filters['event_id'] ?? 0) === (int) $event->id ? 'selected' : '' }}>
                                    {{ $event->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="form-label">Search Audience</label>
                        <input type="text" class="form-control form-control-sm" name="search"
                            value="{{ $search }}"
                            placeholder="Search attendee name, phone, email, event or seat code">
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-sm btn-success" type="submit">Apply</button>
                        <a href="{{ orgRoute('admin.whatsapp.events.contacts') }}"
                            class="btn btn-sm btn-outline-secondary">Reset</a>
                        <a href="{{ orgRoute('admin.whatsapp.events.broadcast', [
                            'source_type' => 'event_contacts',
                            'event_filter' => $filters['event_filter'] ?? 'upcoming',
                            'event_id' => $filters['event_id'] ?? null,
                        ]) }}"
                            class="btn btn-sm btn-outline-success ms-auto">
                            Send Event Broadcast
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Event Audience Selector</span>
                <small class="text-muted">Event Name, Attendee Count, WhatsApp Valid Count, Past/Upcoming</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3">Event</th>
                                <th>Date</th>
                                <th>Attendees</th>
                                <th>WhatsApp Valid</th>
                                <th>Status</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($events as $event)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold">{{ $event->title }}</div>
                                        <small class="text-muted">{{ $event->venue }}</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                                    <td>{{ $event->seats_count }}</td>
                                    <td>{{ $event->whatsapp_valid_count }}</td>
                                    <td>
                                        <span class="badge {{ $event->is_upcoming ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $event->is_upcoming ? 'Upcoming' : 'Past' }}
                                        </span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <a href="{{ orgRoute('admin.whatsapp.events.broadcast', [
                                            'source_type' => 'event_contacts',
                                            'event_filter' => 'single',
                                            'event_id' => $event->id,
                                        ]) }}"
                                            class="btn btn-sm btn-outline-success">
                                            Select
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No events found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">{{ $events->links() }}</div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Audience Preview</span>
                <small class="text-muted">Deduplicated, validated WhatsApp contacts only</small>
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
                                <th class="pe-3">Booking Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($audience as $row)
                                <tr>
                                    <td class="ps-3">{{ $row['name'] ?: 'N/A' }}</td>
                                    <td>{{ $row['phone'] }}</td>
                                    <td>{{ $row['email'] ?: '-' }}</td>
                                    <td>{{ $row['event_name'] ?: '-' }}</td>
                                    <td>{{ $row['seat_code'] ?: '-' }}</td>
                                    <td class="pe-3">{{ $row['booking_date'] ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No valid WhatsApp audience
                                        matched this filter.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">{{ $audience->links() }}</div>
        </div>
    </div>
</x-app-layout>
