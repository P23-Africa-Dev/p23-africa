<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Consultation Applications') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Manage and track consultation bookings</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3">
                                    <i class="fa fa-handshake text-primary fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Total Applications</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">{{ $stats['total_applications'] }}
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
                                    <h3 class="mb-0 text-dark dark:text-gray-100">{{ $stats['pending_applications'] }}
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
                                    <h3 class="mb-0 text-dark dark:text-gray-100">{{ $stats['paid_applications'] }}</h3>
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
                                    <h3 class="mb-0 text-dark dark:text-gray-100">{{ $stats['scheduled_applications'] }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.consultation.index') }}">
                        <div class="row align-items-end">
                            <div class="col-md-4 mb-3">
                                <label for="search" class="form-label text-dark dark:text-gray-200">Search</label>
                                <input type="text"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    id="search" name="search" value="{{ request('search') }}"
                                    placeholder="Search by name, email, or company...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="status" class="form-label text-dark dark:text-gray-200">Status</label>
                                <select class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        Pending Payment</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>
                                        Scheduled</option>
                                </select>
                            </div>
                            <div class="col-md-5 mb-3">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fa fa-search me-1"></i>Filter
                                </button>
                                <a href="{{ route('admin.consultation.index') }}" class="btn btn-outline-secondary">
                                    <i class="fa fa-times me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Applications Table -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <h5 class="mb-0 text-dark dark:text-gray-100">
                        <i class="fa fa-list me-2"></i>Applications
                    </h5>
                </div>
                <div class="card-body">
                    @if ($applications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover dark:text-gray-200">
                                <thead>
                                    <tr class="border-bottom">
                                        <th class="text-dark dark:text-gray-200">Applicant</th>
                                        <th class="text-dark dark:text-gray-200">Company</th>
                                        <th class="text-dark dark:text-gray-200">Industry</th>
                                        <th class="text-dark dark:text-gray-200">Status</th>
                                        <th class="text-dark dark:text-gray-200">Applied</th>
                                        <th class="text-dark dark:text-gray-200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applications as $application)
                                        <tr>
                                            <td>
                                                <div>
                                                    <div class="fw-medium text-dark dark:text-gray-100">
                                                        {{ $application->name }}</div>
                                                    <small
                                                        class="text-muted dark:text-gray-400">{{ $application->email }}</small>
                                                </div>
                                            </td>
                                            <td class="text-dark dark:text-gray-200">
                                                {{ $application->company ?? 'N/A' }}</td>
                                            <td class="text-dark dark:text-gray-200">
                                                {{ $application->industry ?? 'N/A' }}</td>
                                            <td>
                                                @if ($application->scheduledSessions->count() > 0)
                                                    <span class="badge bg-info">Scheduled</span>
                                                @elseif($application->payments->where('status', 'successful')->count() > 0)
                                                    <span class="badge bg-success">Paid</span>
                                                @else
                                                    <span class="badge bg-warning">Pending Payment</span>
                                                @endif
                                            </td>
                                            <td class="text-dark dark:text-gray-200">
                                                <div>{{ $application->created_at->format('M d, Y') }}</div>
                                                <small
                                                    class="text-muted dark:text-gray-400">{{ $application->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.consultation.show', $application->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-eye me-1"></i>View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted dark:text-gray-400">
                                Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }}
                                of {{ $applications->total() }} results
                            </div>
                            {{ $applications->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-handshake fa-3x text-muted dark:text-gray-400 mb-3"></i>
                            <h5 class="text-muted dark:text-gray-400">No consultation applications found</h5>
                            <p class="text-muted dark:text-gray-500">Applications will appear here when users submit
                                consultation requests.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
