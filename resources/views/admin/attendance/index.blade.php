<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="font-semibold mb-1" style="font-size:1.25rem;">Attendance</h2>
                <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">
                    {{ $isStaffOnly ?? false ? 'Your daily sign-in and sign-out records' : 'Track daily sign-in and sign-out records' }}
                </p>
            </div>
            <div class="d-flex gap-2" x-data="attendanceActions()">
                <button @click="signOut()" x-show="hasSignedIn && !hasSignedOut"
                    class="btn btn-outline-danger d-flex align-items-center gap-2" :disabled="loading" x-cloak>
                    <i class="bi bi-box-arrow-right"></i>
                    <span x-text="loading ? 'Signing out...' : 'Sign Out'"></span>
                </button>
                <span x-show="hasSignedIn && !hasSignedOut" class="badge bg-success d-flex align-items-center"
                    style="font-size:.75rem;" x-cloak>
                    <i class="bi bi-check-circle me-1"></i> Signed in at <span x-text="signedInAt"
                        class="ms-1"></span>
                </span>
                <span x-show="hasSignedOut" class="badge bg-secondary d-flex align-items-center"
                    style="font-size:.75rem;" x-cloak>
                    <i class="bi bi-check-circle me-1"></i> Signed out at <span x-text="signedOutAt"
                        class="ms-1"></span>
                </span>
            </div>
        </div>
    </x-slot>

    {{-- Today's Stats (supervisors/admins only) --}}
    @if (!($isStaffOnly ?? false))
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-4 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap" style="background:rgba(16,185,129,.12);color:var(--p-success);">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>
                        <div>
                            <div class="stat-label">Signed In Today</div>
                            <div class="stat-value">{{ $todayStats['total_signed_in'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap" style="background:rgba(245,158,11,.12);color:var(--p-warning);">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div>
                            <div class="stat-label">Still Working</div>
                            <div class="stat-value">{{ $todayStats['still_working'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap"
                            style="background:rgba(100,116,139,.12);color:var(--text-secondary);">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                        <div>
                            <div class="stat-label">Signed Out Today</div>
                            <div class="stat-value">{{ $todayStats['total_signed_out'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card mb-4 anim-in">
        <div class="card-body" style="padding:.75rem 1rem;">
            <form method="GET" class="row g-2 align-items-end">
                @if ($orgUsers->count())
                    <div class="col-auto">
                        <label class="form-label" style="font-size:.75rem;font-weight:600;">Employee</label>
                        <select name="user_id" class="form-select form-select-sm">
                            <option value="">All</option>
                            @foreach ($orgUsers as $u)
                                <option value="{{ $u->id }}"
                                    {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-auto">
                    <label class="form-label" style="font-size:.75rem;font-weight:600;">From</label>
                    <input type="date" name="from" class="form-control form-control-sm"
                        value="{{ request('from') }}">
                </div>
                <div class="col-auto">
                    <label class="form-label" style="font-size:.75rem;font-weight:600;">To</label>
                    <input type="date" name="to" class="form-control form-control-sm"
                        value="{{ request('to') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm"><i
                            class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">Clear</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Attendance Table --}}
    <div class="card anim-in">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:.875rem;">
                    <thead>
                        <tr style="background:var(--bg-secondary);">
                            <th
                                style="padding:.75rem 1rem;font-weight:600;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">
                                Employee</th>
                            <th
                                style="padding:.75rem 1rem;font-weight:600;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">
                                Date</th>
                            <th
                                style="padding:.75rem 1rem;font-weight:600;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">
                                Signed In</th>
                            <th
                                style="padding:.75rem 1rem;font-weight:600;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">
                                Signed Out</th>
                            <th
                                style="padding:.75rem 1rem;font-weight:600;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">
                                Duration</th>
                            <th
                                style="padding:.75rem 1rem;font-weight:600;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $att)
                            <tr>
                                <td style="padding:.75rem 1rem;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div
                                            style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--p-primary),var(--p-accent));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;color:#fff;">
                                            {{ strtoupper(substr($att->user->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $att->user->name }}</span>
                                    </div>
                                </td>
                                <td style="padding:.75rem 1rem;">{{ $att->date->format('M d, Y') }}</td>
                                <td style="padding:.75rem 1rem;">
                                    @if ($att->signed_in_at)
                                        <span class="text-success"><i
                                                class="bi bi-check-circle me-1"></i>{{ $att->signed_in_at->format('h:i A') }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td style="padding:.75rem 1rem;">
                                    @if ($att->signed_out_at)
                                        <span class="text-danger"><i
                                                class="bi bi-box-arrow-right me-1"></i>{{ $att->signed_out_at->format('h:i A') }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td style="padding:.75rem 1rem;">
                                    @if ($att->signed_in_at && $att->signed_out_at)
                                        @php
                                            $diff = $att->signed_in_at->diff($att->signed_out_at);
                                            $hours = $diff->h + $diff->days * 24;
                                            $mins = $diff->i;
                                        @endphp
                                        <span>{{ $hours }}h {{ $mins }}m</span>
                                    @elseif($att->signed_in_at)
                                        <span class="text-warning">In progress...</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td style="padding:.75rem 1rem;">
                                    @if ($att->signed_in_at && $att->signed_out_at)
                                        <span class="badge"
                                            style="background:rgba(16,185,129,.12);color:var(--p-success);font-size:.7rem;">Complete</span>
                                    @elseif($att->signed_in_at)
                                        <span class="badge"
                                            style="background:rgba(245,158,11,.12);color:var(--p-warning);font-size:.7rem;">Working</span>
                                    @else
                                        <span class="badge"
                                            style="background:rgba(100,116,139,.12);color:var(--text-secondary);font-size:.7rem;">No
                                            Record</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-calendar-x" style="font-size:2rem;opacity:.3;"></i>
                                    <p class="mb-0 mt-2">No attendance records found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $attendances->withQueryString()->links() }}
    </div>

    @push('scripts')
        <script>
            function attendanceActions() {
                @php
                    $routePrefix = request()->route() && str_starts_with(request()->route()->getName(), 'org.admin.') ? 'org.admin.' : 'admin.';
                    $signInUrl = $routePrefix === 'org.admin.' ? url(($orgSlug ?? '') . '/admin/attendance/sign-in') : orgRoute('admin.attendance.sign-in');
                    $signOutUrl = $routePrefix === 'org.admin.' ? url(($orgSlug ?? '') . '/admin/attendance/sign-out') : orgRoute('admin.attendance.sign-out');
                    $statusUrl = $routePrefix === 'org.admin.' ? url(($orgSlug ?? '') . '/admin/attendance/today-status') : orgRoute('admin.attendance.today-status');
                @endphp
                return {
                    hasSignedIn: false,
                    hasSignedOut: false,
                    signedInAt: '',
                    signedOutAt: '',
                    loading: false,
                    init() {
                        this.checkStatus();
                    },
                    async checkStatus() {
                        try {
                            const r = await fetch("{{ $statusUrl }}", {
                                headers: {
                                    "Accept": "application/json",
                                    "X-Requested-With": "XMLHttpRequest"
                                }
                            });
                            const d = await r.json();
                            this.hasSignedIn = d.has_signed_in;
                            this.hasSignedOut = d.has_signed_out;
                            this.signedInAt = d.signed_in_at || '';
                            this.signedOutAt = d.signed_out_at || '';
                        } catch (e) {}
                    },
                    async signOut() {
                        this.loading = true;
                        try {
                            const r = await fetch("{{ $signOutUrl }}", {
                                method: 'POST',
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json",
                                    "X-Requested-With": "XMLHttpRequest"
                                },
                            });
                            const d = await r.json();
                            if (d.signed_out || d.already_signed_out) {
                                this.hasSignedOut = true;
                                this.signedOutAt = d.attendance?.signed_out_at ? new Date(d.attendance.signed_out_at)
                                    .toLocaleTimeString('en-US', {
                                        hour: 'numeric',
                                        minute: '2-digit'
                                    }) : 'now';
                            }
                        } catch (e) {}
                        this.loading = false;
                    }
                };
            }
        </script>
    @endpush

</x-app-layout>
