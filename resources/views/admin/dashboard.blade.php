<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold mb-1" style="font-size:1.25rem;">Dashboard</h2>
                <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">
                    Welcome back, <strong>{{ auth()->user()->name }}</strong>
                </p>
            </div>
            @if (auth()->user() && auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.staff.index') }}" class="btn btn-primary d-flex align-items-center gap-2">
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
                        <a href="{{ route('admin.events.index') }}" class="action-item">
                            <div class="d-flex align-items-center">
                                <div class="action-icon-wrap"
                                    style="background:rgba(99,102,241,.1);color:var(--p-primary);"><i
                                        class="bi bi-calendar3"></i></div>
                                <span>Manage Events</span>
                            </div>
                            <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.75rem;"></i>
                        </a>
                        <a href="{{ route('admin.blogs.index') }}" class="action-item">
                            <div class="d-flex align-items-center">
                                <div class="action-icon-wrap"
                                    style="background:rgba(16,185,129,.1);color:var(--p-success);"><i
                                        class="bi bi-book"></i></div>
                                <span>Resource Hub</span>
                            </div>
                            <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.75rem;"></i>
                        </a>
                        <a href="{{ route('admin.playbooks.dashboard') }}" class="action-item">
                            <div class="d-flex align-items-center">
                                <div class="action-icon-wrap"
                                    style="background:rgba(59,130,246,.1);color:var(--p-info);"><i
                                        class="bi bi-download"></i></div>
                                <span>Ebook Downloads</span>
                            </div>
                            <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.75rem;"></i>
                        </a>
                        @if (auth()->user() && auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.contact.index') }}" class="action-item">
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
                        <a href="{{ route('admin.students.index') }}" class="action-item">
                            <div class="d-flex align-items-center">
                                <div class="action-icon-wrap"
                                    style="background:rgba(16,185,129,.1);color:var(--p-success);"><i
                                        class="bi bi-mortarboard"></i></div>
                                <span>View Students</span>
                            </div>
                            <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.75rem;"></i>
                        </a>
                        @if (auth()->user() && auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.staff.index') }}" class="action-item">
                                <div class="d-flex align-items-center">
                                    <div class="action-icon-wrap"
                                        style="background:rgba(245,158,11,.1);color:var(--p-warning);"><i
                                            class="bi bi-person-badge"></i></div>
                                    <span>Manage Staff</span>
                                </div>
                                <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.75rem;"></i>
                            </a>
                            <a href="{{ route('admin.organizations.index') }}" class="action-item">
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
                            <a href="{{ route('admin.projects.index') }}" class="action-item"
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
                                <a href="{{ route('admin.projects.create') }}" class="action-item"
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
                            <a href="{{ route('admin.tasks.kanban') }}" class="action-item"
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
                            <a href="{{ route('admin.tasks.index') }}" class="action-item"
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
                            <a href="{{ route('admin.projects.gantt') }}" class="action-item"
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
                            <a href="{{ route('admin.crm.pipeline') }}" class="action-item"
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
                                <a href="{{ route('admin.crm.index') }}" class="action-item"
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
                                <a href="{{ route('admin.consultation.index') }}" class="action-item"
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
                                <a href="{{ route('admin.brn.index') }}" class="action-item"
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
                                <a href="{{ route('admin.clicks.report') }}" class="action-item"
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
                                <a href="{{ route('admin.activity.logs') }}" class="action-item"
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
