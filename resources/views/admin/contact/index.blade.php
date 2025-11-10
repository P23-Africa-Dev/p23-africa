<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Contact Submissions') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Manage contact form submissions</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary dark:border-gray-600 dark:text-gray-300"
                    data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fa fa-filter me-2"></i>Filter
                </button>
                <a href="{{ route('admin.contact.export', request()->all()) }}"
                    class="btn btn-outline-primary dark:border-blue-500 dark:text-blue-400">
                    <i class="fa fa-download me-2"></i>Export CSV
                </a>
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

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3">
                                    <i class="fa fa-envelope text-primary fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Total Contacts</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">{{ $stats['total_contacts'] }}</h3>
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
                                    <i class="fa fa-calendar-day text-success fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Today</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">{{ $stats['today_contacts'] }}</h3>
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
                                    <i class="fa fa-calendar-week text-info fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">This Week</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">{{ $stats['this_week_contacts'] }}
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
                                    <i class="fa fa-calendar-alt text-warning fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">This Month</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">{{ $stats['this_month_contacts'] }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.contact.index') }}" class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="search"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                placeholder="Search by name, email, or message..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                value="{{ request('date_from') }}" placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                value="{{ request('date_to') }}" placeholder="To Date">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search me-2"></i>Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Submissions Table -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark dark:text-gray-100">
                            <i class="fa fa-list me-2 text-primary"></i>Contact Submissions
                        </h5>
                        @if (auth()->user()->hasRole('admin'))
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label text-dark dark:text-gray-100" for="selectAll">
                                    Select All
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($contacts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light dark:bg-gray-700">
                                    <tr>
                                        @if (auth()->user()->hasRole('admin'))
                                            <th class="dark:text-gray-100">
                                                <input type="checkbox" class="form-check-input" id="selectAllHeader">
                                            </th>
                                        @endif
                                        <th class="dark:text-gray-100">Name</th>
                                        <th class="dark:text-gray-100">Email</th>
                                        <th class="dark:text-gray-100">Services</th>
                                        <th class="dark:text-gray-100">Message</th>
                                        <th class="dark:text-gray-100">Submitted</th>
                                        <th class="dark:text-gray-100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contacts as $contact)
                                        <tr>
                                            @if (auth()->user()->hasRole('admin'))
                                                <td>
                                                    <input type="checkbox" class="form-check-input contact-checkbox"
                                                        value="{{ $contact->id }}">
                                                </td>
                                            @endif
                                            <td class="dark:text-gray-100">
                                                <div class="fw-medium">{{ $contact->name }}</div>
                                            </td>
                                            <td class="dark:text-gray-100">
                                                <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                                    {{ $contact->email }}
                                                </a>
                                            </td>
                                            <td class="dark:text-gray-100">
                                                @if ($contact->services && is_array($contact->services))
                                                    @foreach ($contact->services as $service)
                                                        <span class="badge bg-primary me-1">{{ $service }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="dark:text-gray-100">
                                                <div class="text-truncate" style="max-width: 200px;"
                                                    title="{{ $contact->message }}">
                                                    {{ $contact->message }}
                                                </div>
                                            </td>
                                            <td class="dark:text-gray-100">
                                                <small>{{ $contact->created_at->format('M d, Y') }}</small><br>
                                                <small
                                                    class="text-muted">{{ $contact->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#quickModal{{ $contact->id }}"
                                                        title="Quick Preview">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                    <a href="{{ route('admin.contact.show', $contact) }}"
                                                        class="btn btn-sm btn-outline-info" title="View Full Details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    @if (auth()->user()->hasRole('admin'))
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger delete-contact"
                                                            data-id="{{ $contact->id }}"
                                                            data-name="{{ $contact->name }}"
                                                            title="Delete Submission">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Quick Preview Modal -->
                                        <div class="modal fade" id="quickModal{{ $contact->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content dark:bg-gray-800">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title text-dark dark:text-gray-100">
                                                            Quick Preview - {{ $contact->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <strong
                                                                class="text-dark dark:text-gray-100">Email:</strong>
                                                            <a href="mailto:{{ $contact->email }}"
                                                                class="text-decoration-none ms-2">
                                                                {{ $contact->email }}
                                                            </a>
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong
                                                                class="text-dark dark:text-gray-100">Services:</strong>
                                                            <div class="mt-1">
                                                                @if ($contact->services && is_array($contact->services))
                                                                    @foreach ($contact->services as $service)
                                                                        <span
                                                                            class="badge bg-primary me-1">{{ $service }}</span>
                                                                    @endforeach
                                                                @else
                                                                    <span class="text-muted">No services
                                                                        specified</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong
                                                                class="text-dark dark:text-gray-100">Message:</strong>
                                                            <div class="mt-1 p-2 bg-light dark:bg-gray-700 rounded text-dark dark:text-gray-100"
                                                                style="max-height: 150px; overflow-y: auto;">
                                                                {{ Str::limit($contact->message, 300) }}
                                                            </div>
                                                        </div>
                                                        <div class="text-muted small">
                                                            Submitted:
                                                            {{ $contact->created_at->format('M d, Y h:i A') }}
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <a href="{{ route('admin.contact.show', $contact) }}"
                                                            class="btn btn-primary">
                                                            <i class="fa fa-eye me-2"></i>View Full Details
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer bg-transparent border-0">
                            {{ $contacts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No contact submissions found</h5>
                            <p class="text-muted">Contact submissions will appear here when users submit the contact
                                form.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for bulk operations and delete functionality -->
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Debug: Check if Bootstrap is available
                if (typeof bootstrap === 'undefined') {
                    console.error('Bootstrap JavaScript not loaded! Modals will not work.');
                } else {
                    console.log('Bootstrap JavaScript loaded successfully.');
                }
                // Select all functionality
                const selectAllCheckbox = document.getElementById('selectAll');
                const selectAllHeaderCheckbox = document.getElementById('selectAllHeader');
                const contactCheckboxes = document.querySelectorAll('.contact-checkbox');
                const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

                function toggleBulkDeleteButton() {
                    const checkedBoxes = document.querySelectorAll('.contact-checkbox:checked');
                    if (checkedBoxes.length > 0) {
                        bulkDeleteBtn.style.display = 'inline-block';
                    } else {
                        bulkDeleteBtn.style.display = 'none';
                    }
                }

                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function() {
                        contactCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        toggleBulkDeleteButton();
                    });
                }

                if (selectAllHeaderCheckbox) {
                    selectAllHeaderCheckbox.addEventListener('change', function() {
                        contactCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        toggleBulkDeleteButton();
                    });
                }

                contactCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', toggleBulkDeleteButton);
                });

                // Delete single contact
                document.querySelectorAll('.delete-contact').forEach(button => {
                    button.addEventListener('click', function() {
                        const contactId = this.dataset.id;
                        const contactName = this.dataset.name;

                        if (confirm(
                                `Are you sure you want to delete the contact submission from "${contactName}"?`
                            )) {
                            deleteContact(contactId);
                        }
                    });
                });

                // Bulk delete
                if (bulkDeleteBtn) {
                    bulkDeleteBtn.addEventListener('click', function() {
                        const checkedBoxes = document.querySelectorAll('.contact-checkbox:checked');
                        const ids = Array.from(checkedBoxes).map(cb => cb.value);

                        if (confirm(`Are you sure you want to delete ${ids.length} contact submission(s)?`)) {
                            bulkDeleteContacts(ids);
                        }
                    });
                }

                function deleteContact(id) {
                    fetch(`/admin/contact/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.message || 'Failed to delete contact submission.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the contact submission.');
                        });
                }

                function bulkDeleteContacts(ids) {
                    fetch('/admin/contact/bulk-delete', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
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
                                location.reload();
                            } else {
                                alert(data.message || 'Failed to delete contact submissions.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the contact submissions.');
                        });
                }
            });
        </script>
    @endpush
</x-app-layout>
