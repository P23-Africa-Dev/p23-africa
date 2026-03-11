{{-- =====================================================================
    Sidebar - P23 Africa Admin
    Uses admin-ui.css classes; all org-visibility guards preserved.
    ===================================================================== --}}
@php
    // Determine org URL prefix for all sidebar links
    $sidebarOrgPrefix = '';
    $sidebarUser = auth()->user();
    if ($sidebarUser && $sidebarUser->organization_id && !$sidebarUser->hasRole('admin')) {
        $org = $sidebarUser->organization;
        if ($org && $org->slug) {
            $sidebarOrgPrefix = '/' . $org->slug;
        }
    }
@endphp
<div x-data="{
    sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
    mobileSidebarOpen: false,
    init() {
        this.$watch('sidebarOpen', v => {
            localStorage.setItem('sidebarOpen', v);
            this.$dispatch('sidebar-toggle', { open: v });
        });
        window.addEventListener('sidebar-mobile-toggle', () => {
            this.mobileSidebarOpen = !this.mobileSidebarOpen;
        });
    }
}" class="sidebar-wrapper">

    {{-- Overlay (mobile) --}}
    <div class="sidebar-overlay" :class="mobileSidebarOpen ? 'visible' : ''" @click="mobileSidebarOpen=false"></div>

    {{-- Sidebar panel --}}
    <aside class="sidebar" :class="[sidebarOpen ? '' : 'collapsed', mobileSidebarOpen ? 'mobile-open' : '']">

        {{-- Header --}}
        <div class="sidebar-header">
            <span class="sidebar-header-label">Navigation</span>
            <button class="sidebar-collapse-btn d-none d-lg-flex" @click="sidebarOpen=!sidebarOpen"
                :title="sidebarOpen ? 'Collapse' : 'Expand'">
                <i :class="sidebarOpen ? 'bi bi-chevron-left' : 'bi bi-chevron-right'"></i>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="sidebar-nav">

            {{-- Dashboard --}}
            <a href="{{ url($sidebarOrgPrefix . '/admin/dashboard') }}" data-tooltip="Dashboard"
                class="sidebar-item {{ request()->routeIs('admin.dashboard', 'org.admin.dashboard') ? 'active' : '' }}">
                <span class="sidebar-icon"><i class="bi bi-speedometer2"></i></span>
                <span class="sidebar-text">Dashboard</span>
            </a>

            {{-- CRM --}}
            <a href="{{ url($sidebarOrgPrefix . '/admin/crm/pipeline') }}" data-tooltip="CRM"
                class="sidebar-item {{ request()->routeIs('admin.crm.*', 'org.admin.crm.*') ? 'active' : '' }}">
                <span class="sidebar-icon"><i class="bi bi-kanban"></i></span>
                <span class="sidebar-text">CRM</span>
            </a>

            {{-- Calendar (admin / non-org users only) --}}
            @if (auth()->user() && (auth()->user()->hasRole('admin') || !auth()->user()->organization_id))
                <a href="{{ url($sidebarOrgPrefix . '/admin/calendar') }}" data-tooltip="Calendar"
                    class="sidebar-item {{ request()->routeIs('admin.calendar.*', 'org.admin.calendar.*') ? 'active' : '' }}">
                    <span class="sidebar-icon"><i class="bi bi-calendar3"></i></span>
                    <span class="sidebar-text">Calendar</span>
                </a>
            @endif

            {{-- Projects dropdown --}}
            <div class="sidebar-section" x-data="{ open: {{ request()->routeIs('admin.projects.*', 'admin.tasks.*', 'org.admin.projects.*', 'org.admin.tasks.*') ? 'true' : 'false' }} }">
                <button @click="open=!open" data-tooltip="Projects" class="sidebar-dropdown-toggle">
                    <span class="sidebar-icon"><i class="bi bi-folder2-open"></i></span>
                    <span class="sidebar-text">Projects</span>
                    <i class="sidebar-chevron bi bi-chevron-down" :class="open ? 'open' : ''"></i>
                </button>
                <div class="sidebar-submenu" x-show="open && (sidebarOpen || mobileSidebarOpen)" x-transition>
                    <a href="{{ url($sidebarOrgPrefix . '/admin/projects') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.projects.*', 'org.admin.projects.*') ? 'active' : '' }}">
                        <i class="bi bi-folder-open"></i><span>All Projects</span>
                    </a>
                    <a href="{{ url($sidebarOrgPrefix . '/admin/tasks/kanban') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.tasks.kanban', 'org.admin.tasks.kanban') ? 'active' : '' }}"
                        style="position:relative;">
                        <i class="bi bi-view-stacked"></i><span>Kanban Board</span>
                        <span id="task-notification-badge"
                            style="display:none;position:absolute;top:50%;right:8px;transform:translateY(-50%);
                                     min-width:18px;height:18px;font-size:10px;font-weight:700;color:#fff;
                                     background:var(--p-danger);border-radius:999px;padding:0 4px;"></span>
                    </a>
                    <a href="{{ url($sidebarOrgPrefix . '/admin/tasks') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.tasks.index', 'org.admin.tasks.index') ? 'active' : '' }}">
                        <i class="bi bi-list-task"></i><span>Task List</span>
                    </a>
                    <a href="{{ url($sidebarOrgPrefix . '/admin/projects/gantt') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.projects.gantt', 'org.admin.projects.gantt') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-steps"></i><span>Gantt Chart</span>
                    </a>
                </div>
            </div>

            {{-- Attendance & Leave --}}
            <div class="sidebar-section" x-data="{ open: {{ request()->routeIs('admin.attendance.*', 'admin.leave-requests.*', 'org.admin.attendance.*', 'org.admin.leave-requests.*') ? 'true' : 'false' }} }">
                <button @click="open=!open" data-tooltip="Attendance" class="sidebar-dropdown-toggle">
                    <span class="sidebar-icon"><i class="bi bi-person-check"></i></span>
                    <span class="sidebar-text">Attendance</span>
                    <i class="sidebar-chevron bi bi-chevron-down" :class="open ? 'open' : ''"></i>
                </button>
                <div class="sidebar-submenu" x-show="open && (sidebarOpen || mobileSidebarOpen)" x-transition>
                    <a href="{{ url($sidebarOrgPrefix . '/admin/attendance') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.attendance.index', 'org.admin.attendance.index') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i><span>Attendance Log</span>
                    </a>
                    <a href="{{ url($sidebarOrgPrefix . '/admin/leave-requests') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.leave-requests.index', 'org.admin.leave-requests.index') ? 'active' : '' }}">
                        <i class="bi bi-envelope-paper"></i><span>Leave Requests</span>
                    </a>
                    <a href="{{ url($sidebarOrgPrefix . '/admin/leave-requests/calendar') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.leave-requests.calendar', 'org.admin.leave-requests.calendar') ? 'active' : '' }}">
                        <i class="bi bi-calendar-week"></i><span>Calendar</span>
                    </a>
                </div>
            </div>

            {{-- Content (admin / non-org users only) --}}
            @if (auth()->user() && (auth()->user()->hasRole('admin') || !auth()->user()->organization_id))
                <div class="sidebar-section" x-data="{ open: {{ request()->routeIs('admin.events.*', 'admin.blogs.*', 'admin.playbooks.*', 'admin.contact.*', 'org.admin.events.*', 'org.admin.blogs.*', 'org.admin.playbooks.*', 'org.admin.contact.*') ? 'true' : 'false' }} }">
                    <button @click="open=!open" data-tooltip="Content" class="sidebar-dropdown-toggle">
                        <span class="sidebar-icon"><i class="bi bi-pencil-square"></i></span>
                        <span class="sidebar-text">Content</span>
                        <i class="sidebar-chevron bi bi-chevron-down" :class="open ? 'open' : ''"></i>
                    </button>
                    <div class="sidebar-submenu" x-show="open && (sidebarOpen || mobileSidebarOpen)" x-transition>
                        <a href="{{ url($sidebarOrgPrefix . '/admin/events') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.events.*', 'org.admin.events.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-event"></i><span>Events</span>
                        </a>
                        <a href="{{ url($sidebarOrgPrefix . '/admin/blogs') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.blogs.*', 'org.admin.blogs.*') ? 'active' : '' }}">
                            <i class="bi bi-book"></i><span>Resource Hub</span>
                        </a>
                        <a href="{{ url($sidebarOrgPrefix . '/admin/playbooks-dashboard') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.playbooks.*', 'org.admin.playbooks.*') ? 'active' : '' }}">
                            <i class="bi bi-download"></i><span>Ebook Downloads</span>
                        </a>
                        @if (auth()->user() &&
                                auth()->user()->hasRole(['admin', 'supervisor']))
                            <a href="{{ url($sidebarOrgPrefix . '/admin/contact') }}"
                                class="sidebar-subitem {{ request()->routeIs('admin.contact.*', 'org.admin.contact.*') ? 'active' : '' }}">
                                <i class="bi bi-envelope"></i><span>Contact Submissions</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Users --}}
            @php
                $authUser = auth()->user();
                $isAdmin = $authUser && $authUser->hasRole('admin');
                $isSupervisorWithOrg = $authUser && $authUser->hasRole('supervisor') && $authUser->organization_id;
                $showUsersSection = $isAdmin || $isSupervisorWithOrg || ($authUser && !$authUser->organization_id);
            @endphp
            @if ($showUsersSection)
                <div class="sidebar-section" x-data="{ open: {{ request()->routeIs('admin.students.*', 'admin.staff.*', 'org.admin.students.*', 'org.admin.staff.*') ? 'true' : 'false' }} }">
                    <button @click="open=!open" data-tooltip="Users" class="sidebar-dropdown-toggle">
                        <span class="sidebar-icon"><i class="bi bi-people"></i></span>
                        <span class="sidebar-text">Users</span>
                        <i class="sidebar-chevron bi bi-chevron-down" :class="open ? 'open' : ''"></i>
                    </button>
                    <div class="sidebar-submenu" x-show="open && (sidebarOpen || mobileSidebarOpen)" x-transition>
                        @if ($isAdmin || !$authUser->organization_id)
                            <a href="{{ url($sidebarOrgPrefix . '/admin/students') }}"
                                class="sidebar-subitem {{ request()->routeIs('admin.students.*', 'org.admin.students.*') ? 'active' : '' }}">
                                <i class="bi bi-mortarboard"></i><span>Students</span>
                            </a>
                        @endif
                        @if ($isAdmin || $isSupervisorWithOrg)
                            <a href="{{ url($sidebarOrgPrefix . '/admin/staff') }}"
                                class="sidebar-subitem {{ request()->routeIs('admin.staff.*', 'org.admin.staff.*') ? 'active' : '' }}">
                                <i class="bi bi-person-badge"></i><span>Staff</span>
                            </a>
                        @endif
                        @if ($isAdmin)
                            <a href="{{ url($sidebarOrgPrefix . '/admin/organizations') }}"
                                class="sidebar-subitem {{ request()->routeIs('admin.organizations.*', 'org.admin.organizations.*') ? 'active' : '' }}">
                                <i class="bi bi-building"></i><span>Organizations</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Applications & Reports (admin only) --}}
            @if (auth()->user() && auth()->user()->hasRole('admin'))
                <div class="sidebar-section" x-data="{ open: {{ request()->routeIs('admin.consultation.*', 'admin.brn.*', 'admin.clicks.*', 'org.admin.consultation.*', 'org.admin.brn.*', 'org.admin.clicks.*') ? 'true' : 'false' }} }">
                    <button @click="open=!open" data-tooltip="Applications" class="sidebar-dropdown-toggle">
                        <span class="sidebar-icon"><i class="bi bi-graph-up-arrow"></i></span>
                        <span class="sidebar-text">Applications</span>
                        <i class="sidebar-chevron bi bi-chevron-down" :class="open ? 'open' : ''"></i>
                    </button>
                    <div class="sidebar-submenu" x-show="open && (sidebarOpen || mobileSidebarOpen)" x-transition>
                        <a href="{{ url($sidebarOrgPrefix . '/admin/consultations') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.consultation.*', 'org.admin.consultation.*') ? 'active' : '' }}">
                            <i class="bi bi-handshake"></i><span>Consultations</span>
                        </a>
                        <a href="{{ url($sidebarOrgPrefix . '/admin/brn') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.brn.*', 'org.admin.brn.*') ? 'active' : '' }}">
                            <i class="bi bi-briefcase"></i><span>BRN Applications</span>
                        </a>
                        <a href="{{ url($sidebarOrgPrefix . '/admin/clicks/report') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.clicks.*', 'org.admin.clicks.*') ? 'active' : '' }}">
                            <i class="bi bi-cursor"></i><span>Click Reports</span>
                        </a>
                    </div>
                </div>
            @endif

            {{-- Divider --}}
            <div class="sidebar-divider"></div>

            {{-- Settings --}}
            <a href="{{ route('profile.edit') }}" data-tooltip="Settings"
                class="sidebar-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <span class="sidebar-icon"><i class="bi bi-gear"></i></span>
                <span class="sidebar-text">Settings</span>
            </a>

            {{-- Activity Logs (admin only) --}}
            @if (auth()->user() && auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.activity.logs') }}" data-tooltip="Activity Logs"
                    class="sidebar-item {{ request()->routeIs('admin.activity.logs') ? 'active' : '' }}"
                    style="position:relative;">
                    <span class="sidebar-icon"><i class="bi bi-clock-history"></i></span>
                    <span class="sidebar-text">Activity Logs</span>
                    <span id="activity-unseen-badge"
                        style="display:none;position:absolute;top:6px;right:8px;
                             min-width:18px;height:18px;font-size:.65rem;font-weight:700;
                             color:#fff;background:var(--p-danger);border-radius:999px;
                             padding:0 4px;text-align:center;line-height:18px;"></span>
                </a>
            @endif

        </nav>

        {{-- User footer --}}
        <div class="sidebar-footer">
            <div class="sidebar-user-row">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="min-width:0;">
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-role">
                        @if (auth()->user()->hasRole('admin'))
                            Administrator
                        @elseif(auth()->user()->hasRole('supervisor'))
                            Supervisor
                        @else
                            Staff
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </aside>
</div>
