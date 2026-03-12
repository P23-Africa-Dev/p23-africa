<x-app-layout>

    @php
        $signInUrl = '';
        $sidebarUser = auth()->user();
        if ($sidebarUser && $sidebarUser->organization_id && !$sidebarUser->hasRole('admin')) {
            $org = $sidebarUser->organization;
            if ($org && $org->slug) {
                $signInUrl = '/' . $org->slug . '/admin/attendance/sign-in';
            }
        }
        if (!$signInUrl) {
            $signInUrl = '/admin/attendance/sign-in';
        }
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

    {{-- ─── Project / Task / Lead Quick Stats ─────────────────────────── --}}
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
                        <div class="stat-label">Leads</div>
                        <div class="stat-value">{{ $stats['total_leads'] ?? 0 }}</div>
                        <div class="stat-sub text-muted" style="font-size:.75rem;">{{ $stats['new_leads'] ?? 0 }} new
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
