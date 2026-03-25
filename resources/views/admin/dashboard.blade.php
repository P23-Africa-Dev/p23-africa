<x-app-layout>

    @php
        $signInUrl = orgRoute('admin.attendance.sign-in');
    @endphp

    {{-- ─── Quick Sign-In Popup (first daily login) ─────────────────────── --}}
    @if (!($hasSignedInToday ?? true))
        {{-- Toast container for sign-in success --}}
        <div id="signInToastContainer" style="position:fixed;top:24px;right:24px;z-index:1080;">
            <div id="signInToast" class="toast align-items-center text-bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center gap-2" style="font-size:.875rem;">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>You've been signed in for today. Have a great day!</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>


        {{-- <div x-data="signInPopup()" x-show="visible" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="position:fixed;bottom:74px;right:24px;z-index:1060;max-width:340px;width:100%;"
            @click.away="dismiss()">
            <div class="card"
                style="border:2px solid var(--p-primary);box-shadow:0 12px 40px rgba(99,102,241,.25);overflow:hidden;">
                
                <div
                    style="height:4px;background:linear-gradient(90deg,var(--p-primary),var(--p-accent),var(--p-success),var(--p-primary));background-size:300% 100%;animation:signInGradient 3s ease infinite;">
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div
                                style="width:36px;height:36px;border-radius:10px;background:rgba(99,102,241,.12);color:var(--p-primary);display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-clock" style="font-size:1.1rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:.9375rem;">Good
                                    {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}!
                                </div>
                                <div style="font-size:.75rem;color:var(--text-muted);">{{ now()->format('l, M d') }}
                                </div>
                            </div>
                        </div>
                        <button @click="dismiss()" class="btn btn-sm p-0" style="opacity:.5;line-height:1;">
                            <i class="bi bi-x-lg" style="font-size:.75rem;"></i>
                        </button>
                    </div>
                    <p style="font-size:.8125rem;color:var(--text-muted);margin-bottom:.75rem;">
                        You haven't signed in for today yet. Mark your attendance?
                    </p>
                    <div class="d-flex gap-2">
                        <button @click="signIn()" :disabled="loading"
                            class="btn btn-primary btn-sm flex-fill d-flex align-items-center justify-content-center gap-1"
                            style="position:relative;overflow:hidden;">
                            <span x-show="!loading"><i class="bi bi-box-arrow-in-right me-1"></i>Sign In</span>
                            <span x-show="loading" style="display:none;">
                                <span class="spinner-border spinner-border-sm" role="status"></span> Signing In...
                            </span>
                        </button>
                        <button @click="dismiss()" class="btn btn-outline-secondary btn-sm"
                            style="font-size:.8125rem;">Later</button>
                    </div>
                </div>
            </div>
        </div> --}}

        <style>
            @keyframes signInGradient {
                0% {
                    background-position: 0% 50%;
                }

                50% {
                    background-position: 100% 50%;
                }

                100% {
                    background-position: 0% 50%;
                }
            }
        </style>

        <script>
            function signInPopup() {
                return {
                    visible: true,
                    loading: false,
                    signIn() {
                        this.loading = true;
                        fetch('{{ url($signInUrl) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(r => r.json())
                            .then(data => {
                                this.loading = false;
                                if (data.signed_in || data.already_signed_in) {
                                    this.visible = false;
                                    // Show Bootstrap toast after card has animated out
                                    setTimeout(() => {
                                        const toastEl = document.getElementById('signInToast');
                                        if (toastEl) {
                                            const toast = new bootstrap.Toast(toastEl);
                                            toast.show();
                                        }
                                    }, 250);
                                }
                            })
                            .catch(() => {
                                this.loading = false;
                                alert('Could not sign in. Please try from the Attendance page.');
                                this.visible = false;
                            });
                    },
                    dismiss() {
                        this.visible = false;
                    }
                }
            }
        </script>
    @endif

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold mb-1" style="font-size:1.25rem;">Dashboard</h2>
                <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">
                    Welcome back, <strong>{{ auth()->user()->name }}</strong>
                </p>
            </div>
            @if (auth()->user() && auth()->user()->hasRole('admin'))
                <a href="{{ orgRoute('admin.staff.index') }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-people"></i>
                    <span class="d-none d-sm-inline">Manage Staff</span>
                </a>
            @endif
        </div>
    </x-slot>

    {{-- ─── Quick Stats (admin / non-org users) ─────────────────────────── --}}
    @if (auth()->user() && (auth()->user()->hasRole('admin') || !auth()->user()->organization_id))
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap" style="background:rgba(99,102,241,.12);color:var(--p-primary);">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div>
                            <div class="stat-label">Total Events</div>
                            <div class="stat-value">{{ $stats['total_events'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap" style="background:rgba(16,185,129,.12);color:var(--p-success);">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                        <div>
                            <div class="stat-label">Students</div>
                            <div class="stat-value">{{ $stats['total_students'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap" style="background:rgba(59,130,246,.12);color:var(--p-info);">
                            <i class="bi bi-book"></i>
                        </div>
                        <div>
                            <div class="stat-label">Resources</div>
                            <div class="stat-value">{{ $stats['total_resources'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap" style="background:rgba(245,158,11,.12);color:var(--p-warning);">
                            <i class="bi bi-download"></i>
                        </div>
                        <div>
                            <div class="stat-label">Downloads</div>
                            <div class="stat-value">{{ $stats['total_downloads'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap"
                            style="background:rgba(100,116,139,.12);color:var(--text-secondary);">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div>
                            <div class="stat-label">Total Contacts</div>
                            <div class="stat-value">{{ $stats['total_contacts'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap" style="background:rgba(16,185,129,.12);color:var(--p-success);">
                            <i class="bi bi-calendar-day"></i>
                        </div>
                        <div>
                            <div class="stat-label">Today</div>
                            <div class="stat-value">{{ $stats['today_contacts'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap" style="background:rgba(6,182,212,.12);color:var(--p-accent);">
                            <i class="bi bi-calendar-range"></i>
                        </div>
                        <div>
                            <div class="stat-label">Last 30 Days</div>
                            <div class="stat-value">{{ $stats['recent_contacts'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Consultation Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap" style="background:rgba(99,102,241,.12);color:var(--p-primary);">
                            <i class="bi bi-handshake"></i>
                        </div>
                        <div>
                            <div class="stat-label">Total Consultations</div>
                            <div class="stat-value">{{ $stats['total_consultations'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap" style="background:rgba(245,158,11,.12);color:var(--p-warning);">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div>
                            <div class="stat-label">Pending Payment</div>
                            <div class="stat-value">{{ $stats['pending_consultations'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap" style="background:rgba(16,185,129,.12);color:var(--p-success);">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <div>
                            <div class="stat-label">Paid</div>
                            <div class="stat-value">{{ $stats['paid_consultations'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 anim-in">
                <div class="card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-wrap" style="background:rgba(59,130,246,.12);color:var(--p-info);">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <div class="stat-label">Scheduled</div>
                            <div class="stat-value">{{ $stats['scheduled_consultations'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ─── Project / Task Quick Stats ────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3 anim-in">
            <div class="card">
                <div class="stat-card-inner">
                    <div class="stat-icon-wrap" style="background:rgba(139,92,246,.12);color:#8b5cf6;">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <div>
                        <div class="stat-label">Projects</div>
                        <div class="stat-value">{{ $stats['total_projects'] ?? 0 }}</div>
                        <div class="stat-sub text-muted" style="font-size:.75rem;">
                            {{ $stats['active_projects'] ?? 0 }} active</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 anim-in">
            <div class="card">
                <div class="stat-card-inner">
                    <div class="stat-icon-wrap" style="background:rgba(59,130,246,.12);color:var(--p-info);">
                        <i class="bi bi-list-task"></i>
                    </div>
                    <div>
                        <div class="stat-label">Tasks</div>
                        <div class="stat-value">{{ $stats['total_tasks'] ?? 0 }}</div>
                        <div class="stat-sub text-muted" style="font-size:.75rem;">
                            {{ $stats['completed_tasks'] ?? 0 }} completed</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 anim-in">
            <div class="card">
                <div class="stat-card-inner">
                    <div class="stat-icon-wrap" style="background:rgba(245,158,11,.12);color:var(--p-warning);">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <div class="stat-label">Pending Tasks</div>
                        <div class="stat-value">{{ $stats['pending_tasks'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 anim-in">
            <div class="card">
                <div class="stat-card-inner">
                    <div class="stat-icon-wrap" style="background:rgba(16,185,129,.12);color:var(--p-success);">
                        <i class="bi bi-funnel"></i>
                    </div>
                    <div>
                        <div class="stat-label">Total Leads</div>
                        <div class="stat-value">{{ $stats['total_leads'] ?? 0 }}</div>
                        <div class="stat-sub text-muted" style="font-size:.75rem;">{{ $stats['new_leads'] ?? 0 }} new
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── CRM Statistics ──────────────────────────────────────────── --}}
    @if (($stats['total_leads'] ?? 0) > 0 || (auth()->user() && auth()->user()->organization_id))
        <div class="card mb-4 anim-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="font-size:.9375rem;display:flex;align-items:center;gap:.5rem;">
                    <span
                        style="width:28px;height:28px;border-radius:8px;background:rgba(16,185,129,.12);
                    color:var(--p-success);display:inline-flex;align-items:center;justify-content:center;">
                        <i class="bi bi-graph-up" style="font-size:.85rem;"></i>
                    </span>
                    CRM Statistics
                </h5>
                <a href="{{ orgRoute('admin.crm.pipeline') }}"
                    class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                    <i class="bi bi-kanban" style="font-size:.75rem;"></i> Pipeline
                </a>
            </div>
            <div class="card-body">
                {{-- Row 1: Key metrics --}}
                <div class="row g-3 mb-3">
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="text-center p-2 rounded"
                            style="background:rgba(59,130,246,.06);border:1px solid rgba(59,130,246,.15);">
                            <div
                                style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:2px;">
                                Active</div>
                            <div style="font-size:1.25rem;font-weight:700;color:var(--p-info);">
                                {{ $stats['active_leads'] ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="text-center p-2 rounded"
                            style="background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.15);">
                            <div
                                style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:2px;">
                                Won</div>
                            <div style="font-size:1.25rem;font-weight:700;color:var(--p-success);">
                                {{ $stats['won_leads'] ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="text-center p-2 rounded"
                            style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.15);">
                            <div
                                style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:2px;">
                                Lost</div>
                            <div style="font-size:1.25rem;font-weight:700;color:var(--p-danger);">
                                {{ $stats['lost_leads'] ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="text-center p-2 rounded"
                            style="background:rgba(139,92,246,.06);border:1px solid rgba(139,92,246,.15);">
                            <div
                                style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:2px;">
                                Conversion</div>
                            <div style="font-size:1.25rem;font-weight:700;color:#8b5cf6;">
                                {{ $stats['conversion_rate'] ?? 0 }}%</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="text-center p-2 rounded"
                            style="background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.15);">
                            <div
                                style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:2px;">
                                Pipeline Value</div>
                            <div style="font-size:1.05rem;font-weight:700;color:var(--p-warning);">
                                {{ $stats['crm_currency'] ?? '$' }}{{ number_format($stats['pipeline_value'] ?? 0, 0) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="text-center p-2 rounded"
                            style="background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.15);">
                            <div
                                style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:2px;">
                                Won Value</div>
                            <div style="font-size:1.05rem;font-weight:700;color:var(--p-success);">
                                {{ $stats['crm_currency'] ?? '$' }}{{ number_format($stats['won_value'] ?? 0, 0) }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Row 2: Alerts + This Month --}}
                <div class="row g-3 mb-3">
                    @if (($stats['overdue_follow_ups'] ?? 0) > 0)
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 rounded"
                                style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);">
                                <i class="bi bi-exclamation-triangle-fill"
                                    style="color:var(--p-danger);font-size:1.1rem;"></i>
                                <div>
                                    <span
                                        style="font-weight:600;color:var(--p-danger);">{{ $stats['overdue_follow_ups'] }}</span>
                                    <span style="font-size:.8125rem;color:var(--text-muted);"> overdue
                                        follow-up{{ $stats['overdue_follow_ups'] > 1 ? 's' : '' }}</span>
                                </div>
                                <a href="{{ orgRoute('admin.crm.index') }}?follow_up=overdue"
                                    class="btn btn-sm btn-outline-danger ms-auto" style="font-size:.75rem;">View</a>
                            </div>
                        </div>
                    @endif
                    <div class="col-12 col-md-{{ ($stats['overdue_follow_ups'] ?? 0) > 0 ? '6' : '12' }}">
                        <div class="d-flex align-items-center gap-3 p-2 rounded"
                            style="background:rgba(99,102,241,.06);border:1px solid rgba(99,102,241,.15);">
                            <div class="text-center" style="min-width:60px;">
                                <div style="font-size:.65rem;text-transform:uppercase;color:var(--text-muted);">This
                                    Month</div>
                                <div style="font-size:1.1rem;font-weight:700;color:var(--p-primary);">
                                    {{ $stats['this_month_leads'] ?? 0 }}</div>
                                <div style="font-size:.65rem;color:var(--text-muted);">new leads</div>
                            </div>
                            <div style="width:1px;height:32px;background:var(--border);"></div>
                            <div class="text-center" style="min-width:60px;">
                                <div style="font-size:.65rem;text-transform:uppercase;color:var(--text-muted);">This
                                    Month</div>
                                <div style="font-size:1.1rem;font-weight:700;color:var(--p-success);">
                                    {{ $stats['this_month_won'] ?? 0 }}</div>
                                <div style="font-size:.65rem;color:var(--text-muted);">won</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Row 3: Status Breakdown (horizontal bar style) --}}
                @php
                    $statusBreakdown = is_array($stats['status_breakdown'] ?? null) ? $stats['status_breakdown'] : [];
                    $totalForBar = array_sum(array_map(fn($item) => (int) ($item['count'] ?? 0), $statusBreakdown));
                    $colorMap = [
                        'primary' => '#6366f1',
                        'secondary' => '#64748b',
                        'success' => '#10b981',
                        'danger' => '#ef4444',
                        'warning' => '#f59e0b',
                        'info' => '#3b82f6',
                        'dark' => '#1e293b',
                        'light' => '#f1f5f9',
                    ];
                @endphp
                @if (!empty($statusBreakdown))
                    <div class="mb-3">
                        <div style="font-size:.8125rem;font-weight:600;margin-bottom:.5rem;">Lead Pipeline Breakdown
                        </div>
                        @if ($totalForBar > 0)
                            <div class="d-flex rounded overflow-hidden"
                                style="height:10px;background:var(--bg-muted);">
                                @foreach ($statusBreakdown as $slug => $sb)
                                    @if ($sb['count'] > 0)
                                        <div style="width:{{ ($sb['count'] / $totalForBar) * 100 }}%;background:{{ $colorMap[$sb['color']] ?? '#6366f1' }};"
                                            title="{{ $sb['name'] }}: {{ $sb['count'] }}"></div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        {{-- Legend --}}
                        <div class="d-flex flex-wrap gap-3 mt-2">
                            @foreach ($statusBreakdown as $slug => $sb)
                                <div class="d-flex align-items-center gap-1" style="font-size:.75rem;">
                                    <span
                                        style="width:8px;height:8px;border-radius:50%;background:{{ $colorMap[$sb['color']] ?? '#6366f1' }};display:inline-block;"></span>
                                    <span style="color:var(--text-muted);">{{ $sb['name'] }}</span>
                                    <span style="font-weight:600;">{{ $sb['count'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Row 4: Drives breakdown --}}
                @if (!empty($stats['drive_breakdown']) && count($stats['drive_breakdown']) > 1)
                    <div>
                        <div style="font-size:.8125rem;font-weight:600;margin-bottom:.5rem;">Pipelines (Drives)</div>
                        <div class="row g-2">
                            @foreach ($stats['drive_breakdown'] as $db)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="p-2 rounded d-flex align-items-center gap-2"
                                        style="border:1px solid var(--border);">
                                        <span
                                            style="width:8px;height:8px;border-radius:50%;background:{{ $db['color'] ?? '#6366f1' }};flex-shrink:0;"></span>
                                        <div style="min-width:0;flex:1;">
                                            <div
                                                style="font-size:.75rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                {{ $db['name'] }}</div>
                                            <div style="font-size:.7rem;color:var(--text-muted);">
                                                {{ $db['count'] }} leads ·
                                                {{ $stats['crm_currency'] ?? '$' }}{{ number_format($db['value'], 0) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ─── Main cards row ──────────────────────────────────────────────── --}}
    <div class="row g-4">

        {{-- Content Management (admin / non-org) --}}
        @if (auth()->user() && (auth()->user()->hasRole('admin') || !auth()->user()->organization_id))
            <div class="col-lg-6 anim-in">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0" style="font-size:.9375rem;display:flex;align-items:center;gap:.5rem;">
                            <span
                                style="width:28px;height:28px;border-radius:8px;background:rgba(99,102,241,.12);
                                      color:var(--p-primary);display:inline-flex;align-items:center;justify-content:center;">
                                <i class="bi bi-pencil-square" style="font-size:.85rem;"></i>
                            </span>
                            Content Management
                        </h5>
                    </div>
                    <div class="card-body" style="padding:.5rem;">
                        <a href="{{ orgRoute('admin.events.index') }}" class="action-item">
                            <div class="d-flex align-items-center">
                                <div class="action-icon-wrap"
                                    style="background:rgba(99,102,241,.1);color:var(--p-primary);"><i
                                        class="bi bi-calendar3"></i></div>
                                <span>Manage Events</span>
                            </div>
                            <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.75rem;"></i>
                        </a>
                        <a href="{{ orgRoute('admin.blogs.index') }}" class="action-item">
                            <div class="d-flex align-items-center">
                                <div class="action-icon-wrap"
                                    style="background:rgba(16,185,129,.1);color:var(--p-success);"><i
                                        class="bi bi-book"></i></div>
                                <span>Resource Hub</span>
                            </div>
                            <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.75rem;"></i>
                        </a>
                        <a href="{{ orgRoute('admin.playbooks.dashboard') }}" class="action-item">
                            <div class="d-flex align-items-center">
                                <div class="action-icon-wrap"
                                    style="background:rgba(59,130,246,.1);color:var(--p-info);"><i
                                        class="bi bi-download"></i></div>
                                <span>Ebook Downloads</span>
                            </div>
                            <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.75rem;"></i>
                        </a>
                        @if (auth()->user() && auth()->user()->hasRole('admin'))
                            <a href="{{ orgRoute('admin.contact.index') }}" class="action-item">
                                <div class="d-flex align-items-center">
                                    <div class="action-icon-wrap"
                                        style="background:rgba(245,158,11,.1);color:var(--p-warning);"><i
                                            class="bi bi-envelope"></i></div>
                                    <span>Contact Submissions</span>
                                </div>
                                <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.75rem;"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- User Management (admin / non-org) --}}
            <div class="col-lg-6 anim-in">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0" style="font-size:.9375rem;display:flex;align-items:center;gap:.5rem;">
                            <span
                                style="width:28px;height:28px;border-radius:8px;background:rgba(16,185,129,.12);
                                      color:var(--p-success);display:inline-flex;align-items:center;justify-content:center;">
                                <i class="bi bi-people" style="font-size:.85rem;"></i>
                            </span>
                            User Management
                        </h5>
                    </div>
                    <div class="card-body" style="padding:.5rem;">
                        <a href="{{ orgRoute('admin.students.index') }}" class="action-item">
                            <div class="d-flex align-items-center">
                                <div class="action-icon-wrap"
                                    style="background:rgba(16,185,129,.1);color:var(--p-success);"><i
                                        class="bi bi-mortarboard"></i></div>
                                <span>View Students</span>
                            </div>
                            <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.75rem;"></i>
                        </a>
                        @if (auth()->user() && auth()->user()->hasRole('admin'))
                            <a href="{{ orgRoute('admin.staff.index') }}" class="action-item">
                                <div class="d-flex align-items-center">
                                    <div class="action-icon-wrap"
                                        style="background:rgba(245,158,11,.1);color:var(--p-warning);"><i
                                            class="bi bi-person-badge"></i></div>
                                    <span>Manage Staff</span>
                                </div>
                                <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.75rem;"></i>
                            </a>
                            <a href="{{ orgRoute('admin.organizations.index') }}" class="action-item">
                                <div class="d-flex align-items-center">
                                    <div class="action-icon-wrap"
                                        style="background:rgba(99,102,241,.1);color:var(--p-primary);"><i
                                            class="bi bi-building"></i></div>
                                    <span>Organizations</span>
                                </div>
                                <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.75rem;"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif {{-- /admin|non-org Content+User cards --}}

        {{-- Project Management --}}
        <div class="col-12 anim-in">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="font-size:.9375rem;display:flex;align-items:center;gap:.5rem;">
                        <span
                            style="width:28px;height:28px;border-radius:8px;background:rgba(139,92,246,.12);
                                      color:#8b5cf6;display:inline-flex;align-items:center;justify-content:center;">
                            <i class="bi bi-folder2-open" style="font-size:.85rem;"></i>
                        </span>
                        Project Management
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="{{ orgRoute('admin.projects.index') }}" class="action-item"
                                style="border:1px solid var(--border);border-radius:var(--radius-md);">
                                <div class="d-flex align-items-center">
                                    <div class="action-icon-wrap"
                                        style="background:rgba(139,92,246,.1);color:#8b5cf6;"><i
                                            class="bi bi-folder-open"></i></div>
                                    <span style="font-size:.8125rem;">All Projects</span>
                                </div>
                            </a>
                        </div>
                        @if (auth()->user() && auth()->user()->hasRole('admin|supervisor'))
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="{{ orgRoute('admin.projects.create') }}" class="action-item"
                                    style="border:1px solid var(--border);border-radius:var(--radius-md);">
                                    <div class="d-flex align-items-center">
                                        <div class="action-icon-wrap"
                                            style="background:rgba(16,185,129,.1);color:var(--p-success);"><i
                                                class="bi bi-plus-circle"></i></div>
                                        <span style="font-size:.8125rem;">New Project</span>
                                    </div>
                                </a>
                            </div>
                        @endif
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="{{ orgRoute('admin.tasks.kanban') }}" class="action-item"
                                style="border:1px solid var(--border);border-radius:var(--radius-md);">
                                <div class="d-flex align-items-center">
                                    <div class="action-icon-wrap"
                                        style="background:rgba(59,130,246,.1);color:var(--p-info);"><i
                                            class="bi bi-view-stacked"></i></div>
                                    <span style="font-size:.8125rem;">Kanban Board</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="{{ orgRoute('admin.tasks.index') }}" class="action-item"
                                style="border:1px solid var(--border);border-radius:var(--radius-md);">
                                <div class="d-flex align-items-center">
                                    <div class="action-icon-wrap"
                                        style="background:rgba(6,182,212,.1);color:var(--p-accent);"><i
                                            class="bi bi-list-task"></i></div>
                                    <span style="font-size:.8125rem;">Task List</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="{{ orgRoute('admin.projects.gantt') }}" class="action-item"
                                style="border:1px solid var(--border);border-radius:var(--radius-md);">
                                <div class="d-flex align-items-center">
                                    <div class="action-icon-wrap"
                                        style="background:rgba(245,158,11,.1);color:var(--p-warning);"><i
                                            class="bi bi-bar-chart-steps"></i></div>
                                    <span style="font-size:.8125rem;">Gantt Chart</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="{{ orgRoute('admin.crm.pipeline') }}" class="action-item"
                                style="border:1px solid var(--border);border-radius:var(--radius-md);">
                                <div class="d-flex align-items-center">
                                    <div class="action-icon-wrap"
                                        style="background:rgba(99,102,241,.1);color:var(--p-primary);"><i
                                            class="bi bi-kanban"></i></div>
                                    <span style="font-size:.8125rem;">CRM Pipeline</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="{{ orgRoute('admin.spreadsheets.index') }}" class="action-item"
                                style="border:1px solid var(--border);border-radius:var(--radius-md);">
                                <div class="d-flex align-items-center">
                                    <div class="action-icon-wrap"
                                        style="background:rgba(16,185,129,.1);color:var(--p-success);"><i
                                            class="bi bi-file-earmark-spreadsheet"></i></div>
                                    <span style="font-size:.8125rem;">Spreadsheets</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Applications & Reports (admin / non-org) --}}
        @if (auth()->user() && (auth()->user()->hasRole('admin') || !auth()->user()->organization_id))
            <div class="col-12 anim-in">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0" style="font-size:.9375rem;display:flex;align-items:center;gap:.5rem;">
                            <span
                                style="width:28px;height:28px;border-radius:8px;background:rgba(59,130,246,.12);
                                      color:var(--p-info);display:inline-flex;align-items:center;justify-content:center;">
                                <i class="bi bi-graph-up-arrow" style="font-size:.85rem;"></i>
                            </span>
                            Applications &amp; Reports
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="{{ orgRoute('admin.crm.index') }}" class="action-item"
                                    style="border:1px solid var(--border);border-radius:var(--radius-md);">
                                    <div class="d-flex align-items-center">
                                        <div class="action-icon-wrap"
                                            style="background:rgba(16,185,129,.1);color:var(--p-success);"><i
                                                class="bi bi-funnel"></i></div>
                                        <span style="font-size:.8125rem;">CRM / Leads</span>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="{{ orgRoute('admin.consultation.index') }}" class="action-item"
                                    style="border:1px solid var(--border);border-radius:var(--radius-md);">
                                    <div class="d-flex align-items-center">
                                        <div class="action-icon-wrap"
                                            style="background:rgba(99,102,241,.1);color:var(--p-primary);"><i
                                                class="bi bi-handshake"></i></div>
                                        <span style="font-size:.8125rem;">Consultations</span>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="{{ orgRoute('admin.brn.index') }}" class="action-item"
                                    style="border:1px solid var(--border);border-radius:var(--radius-md);">
                                    <div class="d-flex align-items-center">
                                        <div class="action-icon-wrap"
                                            style="background:rgba(239,68,68,.1);color:var(--p-danger);"><i
                                                class="bi bi-file-earmark-text"></i></div>
                                        <span style="font-size:.8125rem;">BRN Applications</span>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="{{ orgRoute('admin.clicks.report') }}" class="action-item"
                                    style="border:1px solid var(--border);border-radius:var(--radius-md);">
                                    <div class="d-flex align-items-center">
                                        <div class="action-icon-wrap"
                                            style="background:rgba(6,182,212,.1);color:var(--p-accent);"><i
                                                class="bi bi-cursor"></i></div>
                                        <span style="font-size:.8125rem;">Click Reports</span>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="{{ orgRoute('admin.activity.logs') }}" class="action-item"
                                    style="border:1px solid var(--border);border-radius:var(--radius-md);">
                                    <div class="d-flex align-items-center">
                                        <div class="action-icon-wrap"
                                            style="background:rgba(100,116,139,.1);color:var(--text-secondary);"><i
                                                class="bi bi-clock-history"></i></div>
                                        <span style="font-size:.8125rem;">Activity Logs</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>{{-- /row g-4 --}}

</x-app-layout>
