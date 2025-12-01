<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Welcome back, {{ auth()->user()->name }}</p>
            </div>
            @if (auth()->user() && auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.staff.index') }}"
                    class="btn btn-dark dark:bg-gray-600 dark:border-gray-600 dark:hover:bg-gray-700 dark-transition">
                    <i class="fa fa-users me-2"></i>Manage Staff
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Quick Stats Section -->
            @if (auth()->user() && auth()->user()->hasRole('admin|supervisor'))
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3">
                                        <i class="fa fa-calendar text-primary fa-2x"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="text-muted dark:text-gray-400 mb-1">Total Events</h6>
                                        <h3 class="mb-0 text-dark dark:text-gray-100">{{ $stats['total_events'] ?? 0 }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                        <i class="fa fa-users text-success fa-2x"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="text-muted dark:text-gray-400 mb-1">Students</h6>
                                        <h3 class="mb-0 text-dark dark:text-gray-100">
                                            {{ $stats['total_students'] ?? 0 }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-info bg-opacity-10 rounded p-3">
                                        <i class="fa fa-book text-info fa-2x"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="text-muted dark:text-gray-400 mb-1">Resources</h6>
                                        <h3 class="mb-0 text-dark dark:text-gray-100">
                                            {{ $stats['total_resources'] ?? 0 }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded p-3">
                                        <i class="fa fa-download text-warning fa-2x"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="text-muted dark:text-gray-400 mb-1">Downloads</h6>
                                        <h3 class="mb-0 text-dark dark:text-gray-100">
                                            {{ $stats['total_downloads'] ?? 0 }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Contact Stats Row -->
            @if (auth()->user() && auth()->user()->hasRole('admin|supervisor'))
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-secondary bg-opacity-10 rounded p-3">
                                        <i class="fa fa-envelope text-secondary fa-2x"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="text-muted dark:text-gray-400 mb-1">Total Contacts</h6>
                                        <h3 class="mb-0 text-dark dark:text-gray-100">
                                            {{ $stats['total_contacts'] ?? 0 }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                        <i class="fa fa-calendar-day text-success fa-2x"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="text-muted dark:text-gray-400 mb-1">Today's Contacts</h6>
                                        <h3 class="mb-0 text-dark dark:text-gray-100">
                                            {{ $stats['today_contacts'] ?? 0 }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-info bg-opacity-10 rounded p-3">
                                        <i class="fa fa-calendar-alt text-info fa-2x"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="text-muted dark:text-gray-400 mb-1">Recent Contacts (30d)</h6>
                                        <h3 class="mb-0 text-dark dark:text-gray-100">
                                            {{ $stats['recent_contacts'] ?? 0 }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Consultation Stats Row -->
            @if (auth()->user() && auth()->user()->hasRole('admin'))
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3">
                                        <i class="fa fa-handshake text-primary fa-2x"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="text-muted dark:text-gray-400 mb-1">Total Consultations</h6>
                                        <h3 class="mb-0 text-dark dark:text-gray-100">
                                            {{ $stats['total_consultations'] ?? 0 }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded p-3">
                                        <i class="fa fa-clock text-warning fa-2x"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="text-muted dark:text-gray-400 mb-1">Pending Payment</h6>
                                        <h3 class="mb-0 text-dark dark:text-gray-100">
                                            {{ $stats['pending_consultations'] ?? 0 }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                        <i class="fa fa-credit-card text-success fa-2x"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="text-muted dark:text-gray-400 mb-1">Paid</h6>
                                        <h3 class="mb-0 text-dark dark:text-gray-100">
                                            {{ $stats['paid_consultations'] ?? 0 }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-info bg-opacity-10 rounded p-3">
                                        <i class="fa fa-calendar-check text-info fa-2x"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="text-muted dark:text-gray-400 mb-1">Scheduled</h6>
                                        <h3 class="mb-0 text-dark dark:text-gray-100">
                                            {{ $stats['scheduled_consultations'] ?? 0 }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Dashboard Cards -->
            <div class="row">
                <!-- Content Management Section -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition h-100">
                        <div class="card-header bg-transparent border-0 pt-4 pb-3">
                            <h5 class="mb-0 text-dark dark:text-gray-100 dark-transition">
                                <i class="fa fa-edit me-2 text-primary"></i>Content Management
                            </h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('admin.events.index') }}"
                                class="d-flex align-items-center justify-content-between text-decoration-none p-3 mb-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark-transition">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                        <i class="fa fa-calendar text-primary"></i>
                                    </div>
                                    <span class="text-dark dark:text-gray-100 fw-medium">Manage Events</span>
                                </div>
                                <i class="fa fa-arrow-right text-muted dark:text-gray-400"></i>
                            </a>

                            <a href="{{ route('admin.blogs.index') }}"
                                class="d-flex align-items-center justify-content-between text-decoration-none p-3 mb-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark-transition">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                        <i class="fa fa-book text-success"></i>
                                    </div>
                                    <span class="text-dark dark:text-gray-100 fw-medium">Resource Hub</span>
                                </div>
                                <i class="fa fa-arrow-right text-muted dark:text-gray-400"></i>
                            </a>

                            <a href="{{ route('admin.playbooks.dashboard') }}"
                                class="d-flex align-items-center justify-content-between text-decoration-none p-3 mb-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark-transition">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                        <i class="fa fa-download text-info"></i>
                                    </div>
                                    <span class="text-dark dark:text-gray-100 fw-medium">Ebook Downloads</span>
                                </div>
                                <i class="fa fa-arrow-right text-muted dark:text-gray-400"></i>
                            </a>

                            @if (auth()->user() && auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.contact.index') }}"
                                    class="d-flex align-items-center justify-content-between text-decoration-none p-3 rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark-transition">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                            <i class="fa fa-envelope text-warning"></i>
                                        </div>
                                        <span class="text-dark dark:text-gray-100 fw-medium">Contact Submissions</span>
                                    </div>
                                    <i class="fa fa-arrow-right text-muted dark:text-gray-400"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- User Management Section -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition h-100">
                        <div class="card-header bg-transparent border-0 pt-4 pb-3">
                            <h5 class="mb-0 text-dark dark:text-gray-100 dark-transition">
                                <i class="fa fa-users me-2 text-success"></i>User Management
                            </h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('admin.students.index') }}"
                                class="d-flex align-items-center justify-content-between text-decoration-none p-3 mb-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark-transition">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                        <i class="fa fa-user-graduate text-success"></i>
                                    </div>
                                    <span class="text-dark dark:text-gray-100 fw-medium">View Students</span>
                                </div>
                                <i class="fa fa-arrow-right text-muted dark:text-gray-400"></i>
                            </a>

                            @if (auth()->user() && auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.staff.index') }}"
                                    class="d-flex align-items-center justify-content-between text-decoration-none p-3 rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark-transition">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                            <i class="fa fa-user-tie text-warning"></i>
                                        </div>
                                        <span class="text-dark dark:text-gray-100 fw-medium">Manage Staff</span>
                                    </div>
                                    <i class="fa fa-arrow-right text-muted dark:text-gray-400"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Project Management Section -->
                @if (auth()->user() && auth()->user()->hasRole('admin|supervisor|staff'))
                    <div class="col-lg-12 mb-4">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                            <div class="card-header bg-transparent border-0 pt-4 pb-3">
                                <h5 class="mb-0 text-dark dark:text-gray-100 dark-transition">
                                    <i class="fa fa-project-diagram me-2 text-purple"></i>Project Management
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ route('admin.projects.index') }}"
                                            class="d-flex align-items-center justify-content-between text-decoration-none p-3 rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark-transition">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-purple bg-opacity-10 rounded p-2 me-3"
                                                    style="background-color: rgba(111, 66, 193, 0.1);">
                                                    <i class="fa fa-tasks" style="color: #6f42c1;"></i>
                                                </div>
                                                <span class="text-dark dark:text-gray-100 fw-medium">View All
                                                    Projects</span>
                                            </div>
                                            <i class="fa fa-arrow-right text-muted dark:text-gray-400"></i>
                                        </a>
                                    </div>
                                    @if (auth()->user() && auth()->user()->hasRole('admin|supervisor'))
                                        <div class="col-md-4 mb-3">
                                            <a href="{{ route('admin.projects.create') }}"
                                                class="d-flex align-items-center justify-content-between text-decoration-none p-3 rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark-transition">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                                        <i class="fa fa-plus-circle text-success"></i>
                                                    </div>
                                                    <span class="text-dark dark:text-gray-100 fw-medium">Create New
                                                        Project</span>
                                                </div>
                                                <i class="fa fa-arrow-right text-muted dark:text-gray-400"></i>
                                            </a>
                                        </div>
                                    @endif
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ route('admin.tasks.index') }}"
                                            class="d-flex align-items-center justify-content-between text-decoration-none p-3 rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark-transition">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                                    <i class="fa fa-tasks text-info"></i>
                                                </div>
                                                <span class="text-dark dark:text-gray-100 fw-medium">Manage
                                                    Tasks</span>
                                            </div>
                                            <i class="fa fa-arrow-right text-muted dark:text-gray-400"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (auth()->user() && auth()->user()->hasRole('admin'))
                    <!-- Applications & Reports Section -->
                    <div class="col-lg-12 mb-4">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                            <div class="card-header bg-transparent border-0 pt-4 pb-3">
                                <h5 class="mb-0 text-dark dark:text-gray-100 dark-transition">
                                    <i class="fa fa-chart-line me-2 text-info"></i>Applications & Reports
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ route('admin.consultation.index') }}"
                                            class="d-flex align-items-center justify-content-between text-decoration-none p-3 rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark-transition">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                    <i class="fa fa-handshake text-primary"></i>
                                                </div>
                                                <span class="text-dark dark:text-gray-100 fw-medium">Consultation
                                                    Bookings</span>
                                            </div>
                                            <i class="fa fa-arrow-right text-muted dark:text-gray-400"></i>
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ route('admin.brn.index') }}"
                                            class="d-flex align-items-center justify-content-between text-decoration-none p-3 rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark-transition">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-danger bg-opacity-10 rounded p-2 me-3">
                                                    <i class="fa fa-file-alt text-danger"></i>
                                                </div>
                                                <span class="text-dark dark:text-gray-100 fw-medium">NOEL Interest
                                                    Applications</span>
                                            </div>
                                            <i class="fa fa-arrow-right text-muted dark:text-gray-400"></i>
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ route('admin.clicks.report') }}"
                                            class="d-flex align-items-center justify-content-between text-decoration-none p-3 rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark-transition">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                                    <i class="fa fa-mouse-pointer text-info"></i>
                                                </div>
                                                <span class="text-dark dark:text-gray-100 fw-medium">Pages Report
                                                    (Clicks)</span>
                                            </div>
                                            <i class="fa fa-arrow-right text-muted dark:text-gray-400"></i>
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ route('admin.activity.logs') }}"
                                            class="d-flex align-items-center justify-content-between text-decoration-none p-3 rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark-transition">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary bg-opacity-10 rounded p-2 me-3">
                                                    <i class="fa fa-history text-secondary"></i>
                                                </div>
                                                <span class="text-dark dark:text-gray-100 fw-medium">Activity
                                                    Logs</span>
                                            </div>
                                            <i class="fa fa-arrow-right text-muted dark:text-gray-400"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
