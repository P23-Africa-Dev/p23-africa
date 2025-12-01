<!-- Sidebar Component -->
<div x-data="{
    sidebarOpen: localStorage.getItem('sidebarOpen') === 'true' || localStorage.getItem('sidebarOpen') === null,
    activeMenu: null,
    init() {
        this.$watch('sidebarOpen', value => {
            localStorage.setItem('sidebarOpen', value);
        });
    }
}" class="sidebar-wrapper">
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'sidebar-expanded' : 'sidebar-collapsed'" class="sidebar dark-transition"
        style="position: fixed; top: 64px; left: 0; height: calc(100vh - 64px); z-index: 40; overflow-y: auto; overflow-x: hidden;">

        <!-- Sidebar Header -->
        <div class="sidebar-header" style="padding: 1rem;">
            <div class="d-flex align-items-center justify-content-between">
                <h6 x-show="sidebarOpen" x-transition class="mb-0 text-dark dark:text-gray-100 fw-bold"
                    style="font-size: 0.875rem; letter-spacing: 0.05em;">
                    NAVIGATION
                </h6>
                <button @click="sidebarOpen = !sidebarOpen"
                    class="btn btn-sm btn-light dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                    style="border-radius: 0.375rem;">
                    <i :class="sidebarOpen ? 'fa fa-chevron-left' : 'fa fa-chevron-right'"></i>
                </button>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="sidebar-nav" style="padding: 0 0.75rem 1rem 0.75rem;">

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa fa-home sidebar-icon"></i>
                <span x-show="sidebarOpen" x-transition class="sidebar-text">Dashboard</span>
            </a>

            <!-- Calendar -->
             @if (auth()->user()->hasRole('admin|supervisor'))  
            <a href="{{ route('admin.calendar.index') }}"
                class="sidebar-item {{ request()->routeIs('admin.calendar.*') ? 'active' : '' }}">
                <i class="fa fa-calendar-alt sidebar-icon"></i>
                <span x-show="sidebarOpen" x-transition class="sidebar-text">Calendar</span>
            </a>
            @endif

            <!-- Project Management Section -->
            <div class="sidebar-section" x-data="{ open: {{ request()->routeIs('admin.projects.*', 'admin.tasks.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="sidebar-item sidebar-dropdown-toggle">
                    <div class="d-flex align-items-center flex-grow-1">
                        <i class="fa fa-project-diagram sidebar-icon"></i>
                        <span x-show="sidebarOpen" x-transition class="sidebar-text">Projects</span>
                    </div>
                    <i x-show="sidebarOpen" :class="open ? 'fa fa-chevron-down' : 'fa fa-chevron-right'"
                        class="sidebar-dropdown-icon" x-transition></i>
                </button>

                <div x-show="open && sidebarOpen" x-transition class="sidebar-submenu">
                    <a href="{{ route('admin.projects.index') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                        <i class="fa fa-folder-open"></i>
                        <span>All Projects</span>
                    </a>
                    <a href="{{ route('admin.tasks.kanban') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.tasks.kanban') ? 'active' : '' }}">
                        <i class="fa fa-columns"></i>
                        <span>Kanban Board</span>
                    </a>
                    <a href="{{ route('admin.tasks.index') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.tasks.index') ? 'active' : '' }}">
                        <i class="fa fa-tasks"></i>
                        <span>Task List</span>
                    </a>
                </div>
            </div>

            <!-- Content Management Section -->
            <div class="sidebar-section" x-data="{ open: {{ request()->routeIs('admin.events.*', 'admin.blogs.*', 'admin.playbooks.*', 'admin.contact.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="sidebar-item sidebar-dropdown-toggle">
                    <div class="d-flex align-items-center flex-grow-1">
                        <i class="fa fa-edit sidebar-icon"></i>
                        <span x-show="sidebarOpen" x-transition class="sidebar-text">Content</span>
                    </div>
                    <i x-show="sidebarOpen" :class="open ? 'fa fa-chevron-down' : 'fa fa-chevron-right'"
                        class="sidebar-dropdown-icon" x-transition></i>
                </button>

                <div x-show="open && sidebarOpen" x-transition class="sidebar-submenu">
                    <a href="{{ route('admin.events.index') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <i class="fa fa-calendar"></i>
                        <span>Events</span>
                    </a>
                    <a href="{{ route('admin.blogs.index') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                        <i class="fa fa-book"></i>
                        <span>Resource Hub</span>
                    </a>
                    <a href="{{ route('admin.playbooks.dashboard') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.playbooks.*') ? 'active' : '' }}">
                        <i class="fa fa-download"></i>
                        <span>Ebook Downloads</span>
                    </a>
                    @if (auth()->user() && auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.contact.index') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
                            <i class="fa fa-envelope"></i>
                            <span>Contact Submissions</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- User Management Section -->
            <div class="sidebar-section" x-data="{ open: {{ request()->routeIs('admin.students.*', 'admin.staff.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="sidebar-item sidebar-dropdown-toggle">
                    <div class="d-flex align-items-center flex-grow-1">
                        <i class="fa fa-users sidebar-icon"></i>
                        <span x-show="sidebarOpen" x-transition class="sidebar-text">Users</span>
                    </div>
                    <i x-show="sidebarOpen" :class="open ? 'fa fa-chevron-down' : 'fa fa-chevron-right'"
                        class="sidebar-dropdown-icon" x-transition></i>
                </button>

                <div x-show="open && sidebarOpen" x-transition class="sidebar-submenu">
                    <a href="{{ route('admin.students.index') }}"
                        class="sidebar-subitem {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                        <i class="fa fa-user-graduate"></i>
                        <span>Students</span>
                    </a>
                    @if (auth()->user() && auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.staff.index') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                            <i class="fa fa-user-tie"></i>
                            <span>Staff</span>
                        </a>
                    @endif
                </div>
            </div>

            @if (auth()->user() && auth()->user()->hasRole('admin'))
                <!-- Applications & Reports Section -->
                <div class="sidebar-section" x-data="{ open: {{ request()->routeIs('admin.consultation.*', 'admin.brn.*', 'admin.clicks.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="sidebar-item sidebar-dropdown-toggle">
                        <div class="d-flex align-items-center flex-grow-1">
                            <i class="fa fa-chart-line sidebar-icon"></i>
                            <span x-show="sidebarOpen" x-transition class="sidebar-text">Applications</span>
                        </div>
                        <i x-show="sidebarOpen" :class="open ? 'fa fa-chevron-down' : 'fa fa-chevron-right'"
                            class="sidebar-dropdown-icon" x-transition></i>
                    </button>

                    <div x-show="open && sidebarOpen" x-transition class="sidebar-submenu">
                        <a href="{{ route('admin.consultation.index') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.consultation.*') ? 'active' : '' }}">
                            <i class="fa fa-handshake"></i>
                            <span>Consultations</span>
                        </a>
                        <a href="{{ route('admin.brn.index') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.brn.*') ? 'active' : '' }}">
                            <i class="fa fa-briefcase"></i>
                            <span>BRN Applications</span>
                        </a>
                        <a href="{{ route('admin.clicks.report') }}"
                            class="sidebar-subitem {{ request()->routeIs('admin.clicks.*') ? 'active' : '' }}">
                            <i class="fa fa-mouse-pointer"></i>
                            <span>Click Reports</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Divider -->
            <div class="sidebar-divider"></div>

            <!-- Settings -->
            <a href="{{ route('profile.edit') }}"
                class="sidebar-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="fa fa-cog sidebar-icon"></i>
                <span x-show="sidebarOpen" x-transition class="sidebar-text">Settings</span>
            </a>

            <!-- Activity Logs (Admin Only) -->
            @if (auth()->user() && auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.activity.logs') }}"
                    class="sidebar-item {{ request()->routeIs('admin.activity.logs') ? 'active' : '' }}">
                    <i class="fa fa-history sidebar-icon"></i>
                    <span x-show="sidebarOpen" x-transition class="sidebar-text">Activity Logs</span>
                </a>
            @endif

        </nav>

        <!-- Sidebar Footer -->
        <div x-show="sidebarOpen" x-transition class="sidebar-footer"
            style="padding: 1rem; border-top: 1px solid var(--border-color); margin-top: auto;">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 40px; height: 40px;">
                    <i class="fa fa-user text-primary"></i>
                </div>
                <div class="ms-3 flex-grow-1" style="min-width: 0;">
                    <p class="mb-0 fw-medium text-dark dark:text-gray-100"
                        style="font-size: 0.875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="mb-0 text-muted dark:text-gray-400" style="font-size: 0.75rem;">
                        @if (auth()->user()->hasRole('admin'))
                            Administrator
                        @elseif(auth()->user()->hasRole('supervisor'))
                            Supervisor
                        @else
                            Staff
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="sidebar-overlay d-lg-none"
        style="display: none;">
    </div>
</div>

<style>
    /* Sidebar Base Styles */
    .sidebar {
        background-color: var(--bg-primary);
        border-right: 1px solid var(--border-color);
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
        transition: width 0.3s ease, transform 0.3s ease;
    }

    .sidebar-expanded {
        width: 260px;
    }

    .sidebar-collapsed {
        width: 70px;
    }

    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: var(--border-color);
        border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: var(--text-secondary);
    }

    /* Sidebar Header */
    .sidebar-header {
        border-bottom: 1px solid var(--border-color);
    }

    /* Sidebar Items */
    .sidebar-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
        text-decoration: none;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
    }

    .sidebar-item:hover {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
    }

    .sidebar-item.active {
        background-color: #667eea;
        color: #ffffff !important;
    }

    .sidebar-item.active .sidebar-icon,
    .sidebar-item.active .sidebar-text {
        color: #ffffff !important;
    }

    .sidebar-icon {
        width: 20px;
        text-align: center;
        margin-right: 0.75rem;
        font-size: 1rem;
        color: var(--text-secondary);
    }

    .sidebar-text {
        font-size: 0.875rem;
        font-weight: 500;
        white-space: nowrap;
    }

    /* Dropdown Toggle */
    .sidebar-dropdown-toggle {
        font-weight: 500;
    }

    .sidebar-dropdown-icon {
        font-size: 0.75rem;
        margin-left: auto;
        color: var(--text-secondary);
        transition: transform 0.2s ease;
    }

    /* Submenu */
    .sidebar-submenu {
        margin-left: 0;
        padding-left: 0;
        margin-top: 0.25rem;
        margin-bottom: 0.5rem;
    }

    .sidebar-subitem {
        display: flex;
        align-items: center;
        padding: 0.625rem 1rem 0.625rem 2.75rem;
        margin-bottom: 0.125rem;
        color: var(--text-secondary);
        text-decoration: none;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .sidebar-subitem:hover {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
    }

    .sidebar-subitem.active {
        background-color: rgba(102, 126, 234, 0.1);
        color: #667eea;
        font-weight: 500;
    }

    .sidebar-subitem i {
        width: 16px;
        text-align: center;
        margin-right: 0.75rem;
        font-size: 0.875rem;
    }

    /* Divider */
    .sidebar-divider {
        height: 1px;
        background-color: var(--border-color);
        margin: 1rem 0;
    }

    /* Sidebar Footer */
    .sidebar-footer {
        position: sticky;
        bottom: 0;
        background-color: var(--bg-primary);
    }

    /* Mobile Overlay */
    .sidebar-overlay {
        position: fixed;
        top: 64px;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 35;
    }

    /* Dark Mode Specific */
    .dark .sidebar-item:hover {
        background-color: rgba(75, 85, 99, 0.5);
    }

    .dark .sidebar-subitem:hover {
        background-color: rgba(75, 85, 99, 0.5);
    }

    /* Responsive Adjustments */
    @media (max-width: 991px) {
        .sidebar-expanded {
            transform: translateX(0);
        }

        .sidebar-collapsed {
            transform: translateX(-100%);
        }

        .sidebar {
            width: 260px !important;
        }
    }

    @media (min-width: 992px) {
        .sidebar-overlay {
            display: none !important;
        }
    }
</style>
