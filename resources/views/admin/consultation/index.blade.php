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
                <div class="col-md-3 mb-3">
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
                <div class="col-md-3 mb-3">
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
                <div class="col-md-3 mb-3">
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
                <div class="col-md-3 mb-3">
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
            </div>

            <!-- Filters and Search -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ orgRoute('admin.consultation.index') }}">
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
                                    <option value="scheduled"
                                        {{ request('status') == 'scheduled' ? 'selected' : '' }}>
                                        Scheduled</option>
                                </select>
                            </div>
                            <div class="col-md-5 mb-3">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fa fa-search me-1"></i>Filter
                                </button>
                                <a href="{{ orgRoute('admin.consultation.index') }}" class="btn btn-outline-secondary">
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
                                                </div>
                                            </td>
                                            <td class="text-dark dark:text-gray-200">
                                                {{ $application->company ?? 'N/A' }}</td>
                                            <td class="text-dark dark:text-gray-200">
                                                {{ $application->industry ?? 'N/A' }}</td>
                                            <td>
                                                @if ($application->scheduledSessions->count() > 0)
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
                                            <td class="text-dark dark:text-gray-200">
                                                <div><strong>Submitted:</strong>
                                                    {{ $application->created_at->format('M d, Y') }}</div>
                                                <small
                                                    class="text-muted dark:text-gray-400">{{ $application->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ orgRoute('admin.consultation.show', $application->id) }}"
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
</x-app-layout>
