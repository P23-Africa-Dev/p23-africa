{{-- =====================================================================
    Sidebar - P23 Africa Admin
    Uses admin-ui.css classes; all org-visibility guards preserved.
    ===================================================================== --}}
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
            <a href="{{ route('admin.dashboard') }}" data-tooltip="Dashboard"
                class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="sidebar-icon"><i class="bi bi-speedometer2"></i></span>
                <span class="sidebar-text">Dashboard</span>
            </a>

            {{-- CRM --}}
            <a href="{{ route('admin.crm.pipeline') }}" data-tooltip="CRM"
                class="sidebar-item {{ request()->routeIs('admin.crm.*') ? 'active' : '' }}">
                <span class="sidebar-icon"><i class="bi bi-kanban"></i></span>
                <span class="sidebar-text">CRM</span>
            </a>

            {{-- Calendar (admin / non-org users only) --}}
            @if (auth()->user() && (auth()->user()->hasRole('admin') || !auth()->user()->organization_id))
                <a href="{{ route('admin.calendar.index') }}" data-tooltip="Calendar"
                    class="sidebar-item {{ request()->routeIs('admin.calendar.*') ? 'active' : '' }}">
                    <span class="sidebar-icon"><i class="bi bi-calendar3"></i></span>
                    <span class="sidebar-text">Calendar</span>
                </a>
            @endif

            {{-- Projects dropdown --}}
            <div class="sidebar-section" x-data="{ open: {{ request()->routeIs('admin.projects.*', 'admin.tasks.*') ? 'true' : 'false' }} }">
                <button @click="open=!open" data-tooltip="Projects" class="sidebar-dropdown-toggle">
                    <span class="sidebar-icon"><i class="bi bi-folder2-open"></i></span>
                    <span class="sidebar-text">Projects</span>
                    <i class="sidebar-chevron bi bi-chevron-down" :class="open ? 'open' : ''"></i>
                </button>
                <div class="sidebar-submenu" x-show="open && (sidebarOpen || mobileSidebarOpen)" x-transition>
                    <a href="{{ route('admin.projects.index') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                        <i class="bi bi-folder-open"></i><span>All Projects</span>
                    </a>
                    <a href="{{ route('admin.tasks.kanban') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.tasks.kanban') ? 'active' : '' }}"
                        style="position:relative;">
                        <i class="bi bi-view-stacked"></i><span>Kanban Board</span>
                        <span id="task-notification-badge"
                            style="display:none;position:absolute;top:50%;right:8px;transform:translateY(-50%);
                                     min-width:18px;height:18px;font-size:10px;font-weight:700;color:#fff;
                                     background:var(--p-danger);border-radius:999px;padding:0 4px;"></span>
                    </a>
                    <a href="{{ route('admin.tasks.index') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.tasks.index') ? 'active' : '' }}">
                        <i class="bi bi-list-task"></i><span>Task List</span>
                    </a>
                    <a href="{{ route('admin.projects.gantt') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.projects.gantt') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-steps"></i><span>Gantt Chart</span>
                    </a>
                </div>
            </div>

            {{-- Content (admin / non-org users only) --}}
            @if (auth()->user() && (auth()->user()->hasRole('admin') || !auth()->user()->organization_id))
                <div class="sidebar-section" x-data="{ open: {{ request()->routeIs('admin.events.*', 'admin.blogs.*', 'admin.playbooks.*', 'admin.contact.*') ? 'true' : 'false' }} }">
                    <button @click="open=!open" data-tooltip="Content" class="sidebar-dropdown-toggle">
                        <span class="sidebar-icon"><i class="bi bi-pencil-square"></i></span>
                        <span class="sidebar-text">Content</span>
                        <i class="sidebar-chevron bi bi-chevron-down" :class="open ? 'open' : ''"></i>
                    </button>
                    <div class="sidebar-submenu" x-show="open && (sidebarOpen || mobileSidebarOpen)" x-transition>
                        <a href="{{ route('admin.events.index') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-event"></i><span>Events</span>
                        </a>
                        <a href="{{ route('admin.blogs.index') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                            <i class="bi bi-book"></i><span>Resource Hub</span>
                        </a>
                        <a href="{{ route('admin.playbooks.dashboard') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.playbooks.*') ? 'active' : '' }}">
                            <i class="bi bi-download"></i><span>Ebook Downloads</span>
                        </a>
                        @if (auth()->user() &&
                                auth()->user()->hasRole(['admin', 'supervisor']))
                            <a href="{{ route('admin.contact.index') }}"
                                class="sidebar-subitem {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
                                <i class="bi bi-envelope"></i><span>Contact Submissions</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Users (admin / non-org users only) --}}
            @if (auth()->user() && (auth()->user()->hasRole('admin') || !auth()->user()->organization_id))
                <div class="sidebar-section" x-data="{ open: {{ request()->routeIs('admin.students.*', 'admin.staff.*') ? 'true' : 'false' }} }">
                    <button @click="open=!open" data-tooltip="Users" class="sidebar-dropdown-toggle">
                        <span class="sidebar-icon"><i class="bi bi-people"></i></span>
                        <span class="sidebar-text">Users</span>
                        <i class="sidebar-chevron bi bi-chevron-down" :class="open ? 'open' : ''"></i>
                    </button>
                    <div class="sidebar-submenu" x-show="open && (sidebarOpen || mobileSidebarOpen)" x-transition>
                        <a href="{{ route('admin.students.index') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                            <i class="bi bi-mortarboard"></i><span>Students</span>
                        </a>
                        @if (auth()->user() && auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.staff.index') }}"
                                class="sidebar-subitem {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                                <i class="bi bi-person-badge"></i><span>Staff</span>
                            </a>
                            <a href="{{ route('admin.organizations.index') }}"
                                class="sidebar-subitem {{ request()->routeIs('admin.organizations.*') ? 'active' : '' }}">
                                <i class="bi bi-building"></i><span>Organizations</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Applications & Reports (admin only) --}}
            @if (auth()->user() && auth()->user()->hasRole('admin'))
                <div class="sidebar-section" x-data="{ open: {{ request()->routeIs('admin.consultation.*', 'admin.brn.*', 'admin.clicks.*') ? 'true' : 'false' }} }">
                    <button @click="open=!open" data-tooltip="Applications" class="sidebar-dropdown-toggle">
                        <span class="sidebar-icon"><i class="bi bi-graph-up-arrow"></i></span>
                        <span class="sidebar-text">Applications</span>
                        <i class="sidebar-chevron bi bi-chevron-down" :class="open ? 'open' : ''"></i>
                    </button>
                    <div class="sidebar-submenu" x-show="open && (sidebarOpen || mobileSidebarOpen)" x-transition>
                        <a href="{{ route('admin.consultation.index') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.consultation.*') ? 'active' : '' }}">
                            <i class="bi bi-handshake"></i><span>Consultations</span>
                        </a>
                        <a href="{{ route('admin.brn.index') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.brn.*') ? 'active' : '' }}">
                            <i class="bi bi-briefcase"></i><span>BRN Applications</span>
                        </a>
                        <a href="{{ route('admin.clicks.report') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.clicks.*') ? 'active' : '' }}">
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
