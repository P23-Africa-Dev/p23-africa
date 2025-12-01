<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Consultation Applications') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Manage and track consultation bookings</p>
            </div>
            <div class="d-flex gap-2">
                @if (auth()->user()->hasRole('admin'))
                    <button type="button" class="btn btn-outline-danger" id="bulkDeleteBtn" style="display: none;">
                        <i class="fa fa-trash me-2"></i>Delete Selected
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Live Activity Monitor -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition mb-4" id="live-activity-card"
                style="display: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-success mb-1">
                                <i class="fa fa-circle text-success me-2" style="animation: pulse 2s infinite;"></i>
                                Live Form Activity Detected
                            </h6>
                            <p class="text-muted mb-0" id="live-activity-text">Users are currently filling out
                                consultation forms</p>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-success" id="active-users-count">0</div>
                            <small class="d-block text-muted">Active Users</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Toast Container for AJAX Messages -->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                <div id="successToast" class="toast" role="alert">
                    <div class="toast-header bg-success text-white">
                        <i class="fa fa-check-circle me-2"></i>
                        <strong class="me-auto">Success</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body" id="successMessage"></div>
                </div>

                <div id="errorToast" class="toast" role="alert">
                    <div class="toast-header bg-danger text-white">
                        <i class="fa fa-exclamation-circle me-2"></i>
                        <strong class="me-auto">Error</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body" id="errorMessage"></div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-2 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3">
                                    <i class="fa fa-handshake text-primary fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1 small">Total</h6>
                                    <h4 class="mb-0 text-dark dark:text-gray-100">{{ $stats['total_applications'] }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-secondary bg-opacity-10 rounded p-3">
                                    <i class="fa fa-edit text-secondary fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1 small">Drafts</h6>
                                    <h4 class="mb-0 text-dark dark:text-gray-100">{{ $stats['total_drafts'] }}</h4>
                                    @if ($stats['active_drafts'] > 0)
                                        <small class="text-success">{{ $stats['active_drafts'] }} active</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded p-3">
                                    <i class="fa fa-clock text-warning fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1 small">Pending</h6>
                                    <h4 class="mb-0 text-dark dark:text-gray-100">{{ $stats['pending_applications'] }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                    <i class="fa fa-credit-card text-success fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1 small">Paid</h6>
                                    <h4 class="mb-0 text-dark dark:text-gray-100">{{ $stats['paid_applications'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-info bg-opacity-10 rounded p-3">
                                    <i class="fa fa-calendar-check text-info fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1 small">Scheduled</h6>
                                    <h4 class="mb-0 text-dark dark:text-gray-100">
                                        {{ $stats['scheduled_applications'] }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-light bg-opacity-10 rounded p-3">
                                    <i class="fa fa-chart-pie text-muted fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1 small">Avg Completion</h6>
                                    <h4 class="mb-0 text-dark dark:text-gray-100">{{ $stats['avg_completion'] }}%</h4>
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
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                                        Drafts</option>
                                    <option value="submitted"
                                        {{ request('status') == 'submitted' ? 'selected' : '' }}>
                                        Submitted</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        Pending Payment</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                    <option value="scheduled"
                                        {{ request('status') == 'scheduled' ? 'selected' : '' }}>
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
                                        @if (auth()->user()->hasRole('admin'))
                                            <th style="width: 40px;">
                                                <input type="checkbox" class="form-check-input" id="selectAll"
                                                    title="Select all">
                                            </th>
                                        @endif
                                        <th class="text-dark dark:text-gray-200">Applicant</th>
                                        <th class="text-dark dark:text-gray-200">Company</th>
                                        <th class="text-dark dark:text-gray-200">Industry</th>
                                        <th class="text-dark dark:text-gray-200">Status</th>
                                        <th class="text-dark dark:text-gray-200">Progress</th>
                                        <th class="text-dark dark:text-gray-200">Date</th>
                                        <th class="text-dark dark:text-gray-200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applications as $application)
                                        <tr data-id="{{ $application->id }}">
                                            @if (auth()->user()->hasRole('admin'))
                                                <td>
                                                    <input type="checkbox"
                                                        class="form-check-input application-checkbox"
                                                        value="{{ $application->id }}">
                                                </td>
                                            @endif
                                            <td>
                                                <div>
                                                    <div class="fw-medium text-dark dark:text-gray-100">
                                                        {{ $application->name ?? 'Anonymous' }}</div>
                                                    <small
                                                        class="text-muted dark:text-gray-400">{{ $application->email ?? 'No email provided' }}</small>
                                                    @if ($application->session_id)
                                                        <div><small class="badge badge-light">Session:
                                                                {{ substr($application->session_id, 0, 8) }}...</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-dark dark:text-gray-200">
                                                {{ $application->company ?? 'N/A' }}</td>
                                            <td class="text-dark dark:text-gray-200">
                                                {{ $application->industry ?? 'N/A' }}</td>
                                            <td>
                                                @if ($application->isDraft())
                                                    <span class="badge bg-secondary">
                                                        <i class="fa fa-edit me-1"></i>Draft
                                                    </span>
                                                    @if ($application->hasRecentActivity())
                                                        <small class="d-block text-success">Active</small>
                                                    @endif
                                                @elseif ($application->scheduledSessions->count() > 0)
                                                    <span class="badge bg-info">
                                                        <i class="fa fa-calendar-check me-1"></i>Scheduled
                                                    </span>
                                                @elseif($application->payments->where('status', 'successful')->count() > 0)
                                                    <span class="badge bg-success">
                                                        <i class="fa fa-credit-card me-1"></i>Paid
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fa fa-clock me-1"></i>Pending Payment
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($application->isDraft())
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $application->getCompletionPercentage() }}%"
                                                            aria-valuenow="{{ $application->getCompletionPercentage() }}"
                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ $application->getCompletionPercentage() }}%
                                                        complete</small>
                                                @else
                                                    <span class="text-success">
                                                        <i class="fa fa-check-circle me-1"></i>Complete
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-dark dark:text-gray-200">
                                                @if ($application->isDraft())
                                                    <div><strong>Draft:</strong>
                                                        {{ $application->draft_created_at ? $application->draft_created_at->format('M d, Y') : 'N/A' }}
                                                    </div>
                                                    <small class="text-muted dark:text-gray-400">Last activity:
                                                        {{ $application->last_activity_at ? $application->last_activity_at->diffForHumans() : 'N/A' }}</small>
                                                @else
                                                    <div><strong>Submitted:</strong>
                                                        {{ $application->created_at->format('M d, Y') }}</div>
                                                    <small
                                                        class="text-muted dark:text-gray-400">{{ $application->created_at->format('h:i A') }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('admin.consultation.show', $application->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fa fa-eye me-1"></i>View
                                                    </a>
                                                    @if (auth()->user()->hasRole('admin'))
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger delete-application"
                                                            data-id="{{ $application->id }}"
                                                            data-name="{{ $application->name ?? 'this application' }}"
                                                            title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
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

    <!-- Delete Functionality JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            const applicationCheckboxes = document.querySelectorAll('.application-checkbox');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

            function toggleBulkDeleteButton() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                if (bulkDeleteBtn) {
                    if (checkedBoxes.length > 0) {
                        bulkDeleteBtn.style.display = 'inline-block';
                    } else {
                        bulkDeleteBtn.style.display = 'none';
                    }
                }
            }

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    applicationCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    toggleBulkDeleteButton();
                });
            }

            applicationCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', toggleBulkDeleteButton);
            });

            // Delete single application
            document.querySelectorAll('.delete-application').forEach(button => {
                button.addEventListener('click', function() {
                    const applicationId = this.dataset.id;
                    const applicationName = this.dataset.name;

                    if (confirm(
                            `Are you sure you want to delete the application from "${applicationName}"? This action cannot be undone.`
                        )) {
                        deleteApplication(applicationId);
                    }
                });
            });

            // Bulk delete
            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', function() {
                    const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                    const ids = Array.from(checkedBoxes).map(cb => cb.value);

                    if (confirm(
                            `Are you sure you want to delete ${ids.length} consultation application(s)? This action cannot be undone.`
                        )) {
                        bulkDeleteApplications(ids);
                    }
                });
            }

            function deleteApplication(id) {
                // Show loading state
                const deleteBtn = document.querySelector(`[data-id="${id}"]`);
                const originalHtml = deleteBtn.innerHTML;
                deleteBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
                deleteBtn.disabled = true;

                fetch(`/admin/consultation/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                                'content') || '',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the row from the table
                            const row = document.querySelector(`tr[data-id="${id}"]`);
                            if (row) {
                                row.remove();
                            }
                            showToast('success', data.message || 'Application deleted successfully');
                            updateStats();
                        } else {
                            showToast('error', data.message || 'Failed to delete application');
                            deleteBtn.innerHTML = originalHtml;
                            deleteBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'An error occurred while deleting the application');
                        deleteBtn.innerHTML = originalHtml;
                        deleteBtn.disabled = false;
                    });
            }

            function bulkDeleteApplications(ids) {
                // Show loading state
                const bulkBtn = document.getElementById('bulkDeleteBtn');
                const originalHtml = bulkBtn.innerHTML;
                bulkBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Deleting...';
                bulkBtn.disabled = true;

                fetch('/admin/consultation/bulk-delete', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                                'content') || '',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            ids: ids
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove deleted rows from the table
                            ids.forEach(id => {
                                const row = document.querySelector(`tr[data-id="${id}"]`);
                                if (row) {
                                    row.remove();
                                }
                            });

                            showToast('success', data.message ||
                                `${ids.length} application(s) deleted successfully`);
                            bulkBtn.style.display = 'none';
                            updateStats();

                            // Uncheck select all
                            if (selectAllCheckbox) {
                                selectAllCheckbox.checked = false;
                            }
                        } else {
                            showToast('error', data.message || 'Failed to delete applications');
                        }
                        bulkBtn.innerHTML = originalHtml;
                        bulkBtn.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'An error occurred while deleting applications');
                        bulkBtn.innerHTML = originalHtml;
                        bulkBtn.disabled = false;
                    });
            }

            // Helper function to show toast notifications
            function showToast(type, message) {
                const toastElement = document.getElementById(type === 'success' ? 'successToast' : 'errorToast');
                const messageElement = document.getElementById(type === 'success' ? 'successMessage' :
                    'errorMessage');

                if (toastElement && messageElement) {
                    messageElement.textContent = message;

                    const toast = new bootstrap.Toast(toastElement, {
                        autohide: true,
                        delay: type === 'success' ? 5000 : 7000
                    });

                    toast.show();
                }
            }

            // Helper function to update stats after deletion
            function updateStats() {
                // If no applications remain, show empty state or reload
                const remainingRows = document.querySelectorAll('tbody tr').length;
                if (remainingRows === 0) {
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            }
        });
    </script>

    <!-- Real-time Updates JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let updateInterval;
            let isUpdating = false;

            // Function to update draft statistics
            async function updateDraftStats() {
                if (isUpdating) return;

                try {
                    isUpdating = true;
                    const response = await fetch('{{ route('admin.consultation.draft-stats') }}', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                ?.getAttribute('content') || ''
                        }
                    });

                    if (response.ok) {
                        const stats = await response.json();

                        // Update statistics on the page
                        updateStatCard('total_drafts', stats.total_drafts, stats.active_drafts);
                        updateStatCard('avg_completion', stats.avg_completion + '%');

                        // Show live activity monitor if there are active drafts
                        updateLiveActivityMonitor(stats.active_drafts, stats.total_drafts);

                        // Show notification if there are active drafts
                        if (stats.active_drafts > 0) {
                            showActiveSessionsIndicator(stats.active_drafts);
                        }

                        console.log('Draft stats updated:', stats);
                    }
                } catch (error) {
                    console.error('Failed to update draft stats:', error);
                } finally {
                    isUpdating = false;
                }
            }

            // Function to update live activity monitor
            function updateLiveActivityMonitor(activeUsers, totalDrafts) {
                const liveActivityCard = document.getElementById('live-activity-card');
                const activeUsersCount = document.getElementById('active-users-count');
                const liveActivityText = document.getElementById('live-activity-text');

                if (activeUsers > 0) {
                    liveActivityCard.style.display = 'block';
                    activeUsersCount.textContent = activeUsers;

                    const userText = activeUsers === 1 ? 'user is' : 'users are';
                    liveActivityText.textContent =
                        `${activeUsers} ${userText} currently filling out consultation forms`;
                } else {
                    liveActivityCard.style.display = 'none';
                }
            }

            // Function to update individual stat cards
            function updateStatCard(type, value, subtitle = null) {
                const cards = document.querySelectorAll('.card-body');
                cards.forEach(card => {
                    const heading = card.querySelector('h6');
                    if (heading) {
                        const text = heading.textContent.toLowerCase();
                        if ((type === 'total_drafts' && text.includes('drafts')) ||
                            (type === 'avg_completion' && text.includes('completion'))) {

                            const valueElement = card.querySelector('h4');
                            if (valueElement) {
                                valueElement.textContent = value;

                                // Add subtitle for active sessions
                                if (subtitle && type === 'total_drafts') {
                                    let subtitleElement = card.querySelector('.text-success');
                                    if (subtitle > 0) {
                                        if (!subtitleElement) {
                                            subtitleElement = document.createElement('small');
                                            subtitleElement.className = 'text-success';
                                            valueElement.parentNode.appendChild(subtitleElement);
                                        }
                                        subtitleElement.textContent = subtitle + ' active';
                                    } else if (subtitleElement) {
                                        subtitleElement.remove();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Function to show active sessions indicator
            function showActiveSessionsIndicator(count) {
                // Create or update a floating indicator
                let indicator = document.getElementById('active-sessions-indicator');
                if (!indicator) {
                    indicator = document.createElement('div');
                    indicator.id = 'active-sessions-indicator';
                    indicator.className =
                        'position-fixed top-0 end-0 m-3 alert alert-info alert-dismissible fade show';
                    indicator.style.zIndex = '1050';
                    indicator.innerHTML = `
                        <i class="fa fa-users me-2"></i>
                        <strong>${count}</strong> users are currently filling out consultation forms
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(indicator);
                } else {
                    const countElement = indicator.querySelector('strong');
                    if (countElement) {
                        countElement.textContent = count;
                    }
                }

                // Auto-hide after 10 seconds
                setTimeout(() => {
                    if (indicator && indicator.parentNode) {
                        indicator.remove();
                    }
                }, 10000);
            }

            // Function to refresh the applications table
            async function refreshApplicationsTable() {
                if (window.location.search.includes('status=draft') || !window.location.search) {
                    // Only refresh if we're viewing drafts or all applications
                    try {
                        // Use the dedicated applications table endpoint
                        const currentUrl = new URL(window.location);
                        const params = new URLSearchParams(currentUrl.search);

                        const tableUrl = new URL('{{ route('admin.consultation.applications-table') }}');

                        // Preserve current filters
                        if (params.has('status')) {
                            tableUrl.searchParams.set('status', params.get('status'));
                        }
                        if (params.has('search')) {
                            tableUrl.searchParams.set('search', params.get('search'));
                        }

                        const response = await fetch(tableUrl.toString(), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute('content') || '',
                                'Accept': 'text/html'
                            }
                        });

                        if (response.ok) {
                            const html = await response.text();

                            // Update only the table body
                            const currentTableBody = document.querySelector('tbody');
                            if (currentTableBody) {
                                const oldRowCount = currentTableBody.children.length;
                                currentTableBody.innerHTML = html;
                                const newRowCount = currentTableBody.children.length;
                                console.log(
                                    `Applications table refreshed: ${oldRowCount} -> ${newRowCount} rows`);

                                // Check for new drafts
                                const draftBadges = currentTableBody.querySelectorAll('.badge.bg-secondary');
                                console.log(`Draft applications in table: ${draftBadges.length}`);
                            }
                        }
                    } catch (error) {
                        console.error('Failed to refresh applications table:', error);
                    }
                }
            }

            // Start real-time updates
            function startRealTimeUpdates() {
                // Update draft stats every 10 seconds (table refresh disabled to not interfere with deletions)
                updateDraftStats(); // Initial update
                updateInterval = setInterval(() => {
                    updateDraftStats();
                    // Table refresh disabled - was causing issues with delete operations
                    // refreshApplicationsTable();
                }, 10000);

                console.log('Real-time updates started');
            }

            // Stop real-time updates
            function stopRealTimeUpdates() {
                if (updateInterval) {
                    clearInterval(updateInterval);
                    updateInterval = null;
                    console.log('Real-time updates stopped');
                }
            }

            // Start updates when page loads
            startRealTimeUpdates();

            // Stop updates when page is hidden/user switches tabs
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopRealTimeUpdates();
                } else {
                    startRealTimeUpdates();
                }
            });

            // Stop updates before page unload
            window.addEventListener('beforeunload', stopRealTimeUpdates);

            // Add visual indicator for real-time status
            const statusIndicator = document.createElement('div');
            statusIndicator.className = 'position-fixed bottom-0 end-0 m-3 badge bg-success';
            statusIndicator.innerHTML = '<i class="fa fa-wifi me-1"></i>Live Updates';
            statusIndicator.title = 'Real-time updates are active';
            document.body.appendChild(statusIndicator);
        });
    </script>

    <style>
        #active-sessions-indicator {
            max-width: 350px;
        }

        .position-fixed.badge {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .position-fixed.badge:hover {
            opacity: 1;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
</x-app-layout>
