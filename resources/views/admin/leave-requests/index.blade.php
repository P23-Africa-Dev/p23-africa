<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="font-semibold mb-1" style="font-size:1.25rem;">Leave Requests</h2>
                <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">
                    Manage leave requests and approvals
                </p>
            </div>
            <a href="{{ url($orgSlug ?? '' ? '/' . ($orgSlug ?? '') . '/admin/leave-requests/create' : '/admin/leave-requests/create') }}"
                class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                <span>Request Leave</span>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:.875rem;">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size:.875rem;">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter --}}
    <div class="card mb-4 anim-in">
        <div class="card-body" style="padding:.75rem 1rem;">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label" style="font-size:.75rem;font-weight:600;">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach (\App\Models\LeaveRequest::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label" style="font-size:.75rem;font-weight:600;">Type</label>
                    <select name="type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        @foreach (\App\Models\LeaveRequest::TYPES as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm"><i
                            class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">Clear</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Leave Request Cards --}}
    <div class="row g-3">
        @forelse($leaveRequests as $lr)
            <div class="col-12 anim-in">
                @php
                    $showUrl =
                        $orgSlug ?? ''
                            ? '/' . ($orgSlug ?? '') . '/admin/leave-requests/' . $lr->id
                            : '/admin/leave-requests/' . $lr->id;
                @endphp
                <a href="{{ url($showUrl) }}" class="card"
                    style="text-decoration:none;color:inherit;display:block;transition:box-shadow .15s;">
                    <div class="card-body" style="padding:1rem 1.25rem;">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div
                                    style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--p-primary),var(--p-accent));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.875rem;color:#fff;flex-shrink:0;">
                                    {{ strtoupper(substr($lr->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:.9375rem;">{{ $lr->user->name }}</div>
                                    <div style="font-size:.8125rem;color:var(--text-muted);">
                                        {{ $lr->type_name }} &middot;
                                        {{ $lr->start_date->format('M d') }} — {{ $lr->end_date->format('M d, Y') }}
                                        ({{ $lr->duration_days }} day{{ $lr->duration_days > 1 ? 's' : '' }})
                                    </div>
                                    <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px;">
                                        <i class="bi bi-person-check me-1"></i>Supervisor: {{ $lr->supervisor->name }}
                                        &middot; {{ $lr->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $badgeColors = [
                                        'pending' => 'background:rgba(245,158,11,.12);color:#92400e;',
                                        'approved' => 'background:rgba(16,185,129,.12);color:#065f46;',
                                        'rejected' => 'background:rgba(239,68,68,.12);color:#991b1b;',
                                        'modified' => 'background:rgba(59,130,246,.12);color:#1e40af;',
                                        'sender_okay' => 'background:rgba(99,102,241,.12);color:#4338ca;',
                                        'sender_not_okay' => 'background:rgba(100,116,139,.12);color:#475569;',
                                    ];
                                @endphp
                                <span class="badge"
                                    style="{{ $badgeColors[$lr->status] ?? '' }}font-size:.7rem;font-weight:600;">
                                    {{ $lr->status_name }}
                                </span>
                                <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.75rem;"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size:3rem;opacity:.2;"></i>
                        <p class="mb-2 mt-3 text-muted">No leave requests found.</p>
                        <a href="{{ url($orgSlug ?? '' ? '/' . ($orgSlug ?? '') . '/admin/leave-requests/create' : '/admin/leave-requests/create') }}"
                            class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Submit a Leave Request
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $leaveRequests->withQueryString()->links() }}
    </div>

</x-app-layout>
