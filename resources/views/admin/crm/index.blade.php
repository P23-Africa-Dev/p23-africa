<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Lead Management (CRM)') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Manage and track all your leads</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.crm.pipeline') }}" class="btn btn-outline-info">
                    <i class="fa fa-columns me-2"></i>Pipeline View
                </a>
                <a href="{{ route('admin.crm.export', request()->all()) }}" class="btn btn-outline-secondary">
                    <i class="fa fa-download me-2"></i>Export CSV
                </a>
                <a href="{{ route('admin.crm.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-2"></i>Add New Lead
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Toast Container -->
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

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-2 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3">
                                    <i class="fa fa-users text-primary fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Total Leads</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        {{ number_format($stats['total_leads']) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-info bg-opacity-10 rounded p-3">
                                    <i class="fa fa-star text-info fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">New</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        {{ number_format($stats['new_leads']) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded p-3">
                                    <i class="fa fa-spinner text-warning fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Active</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        {{ number_format($stats['active_leads']) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                    <i class="fa fa-trophy text-success fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Won</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        {{ number_format($stats['won_leads']) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-danger bg-opacity-10 rounded p-3">
                                    <i class="fa fa-clock text-danger fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Overdue</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        {{ number_format($stats['overdue_follow_ups']) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                    <i class="fa fa-dollar-sign text-success fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Pipeline</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        ${{ number_format($stats['total_value']) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.crm.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                placeholder="Search by name, email, company..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status"
                                class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">All Statuses</option>
                                @foreach (\App\Models\Lead::getStatuses() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="source"
                                class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">All Sources</option>
                                @foreach (\App\Models\Lead::getSources() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ request('source') === $key ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="assigned_to"
                                class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">All Assignees</option>
                                <option value="unassigned"
                                    {{ request('assigned_to') === 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="priority"
                                class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">All Priorities</option>
                                @foreach (\App\Models\Lead::getPriorities() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ request('priority') === $key ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bulk Actions Bar -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4" id="bulkActionsBar" style="display: none;">
                <div class="card-body py-2">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted"><span id="selectedCount">0</span> leads selected</span>
                        <select class="form-select form-select-sm w-auto" id="bulkStatus">
                            <option value="">Change Status</option>
                            @foreach (\App\Models\Lead::getStatuses() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <select class="form-select form-select-sm w-auto" id="bulkAssign">
                            <option value="">Assign To</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn">
                            <i class="fa fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Leads Table -->
            <div class="card border-0 shadow-sm dark:bg-gray-800">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark dark:text-gray-100">
                            <i class="fa fa-list me-2 text-primary"></i>Leads
                        </h5>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label text-dark dark:text-gray-100" for="selectAll">Select
                                All</label>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if ($leads->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="dark:bg-gray-700">
                                    <tr>
                                        <th width="40"><input type="checkbox" class="form-check-input"
                                                id="selectAllHeader"></th>
                                        <th class="dark:text-gray-100">Lead</th>
                                        <th class="dark:text-gray-100">Company</th>
                                        <th class="dark:text-gray-100">Status</th>
                                        <th class="dark:text-gray-100">Priority</th>
                                        <th class="dark:text-gray-100">Value</th>
                                        <th class="dark:text-gray-100">Assigned To</th>
                                        <th class="dark:text-gray-100">Follow Up</th>
                                        <th class="dark:text-gray-100">Source</th>
                                        <th class="dark:text-gray-100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leads as $lead)
                                        <tr class="{{ $lead->isFollowUpOverdue() ? 'table-warning' : '' }}">
                                            <td>
                                                <input type="checkbox" class="form-check-input lead-checkbox"
                                                    value="{{ $lead->id }}">
                                            </td>
                                            <td>
                                                <div class="fw-medium text-dark dark:text-gray-100">
                                                    {{ $lead->full_name }}</div>
                                                <small class="text-muted">
                                                    <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>
                                                </small>
                                                @if ($lead->phone)
                                                    <br><small class="text-muted"><i
                                                            class="fa fa-phone me-1"></i>{{ $lead->phone }}</small>
                                                @endif
                                            </td>
                                            <td class="dark:text-gray-100">
                                                @if ($lead->company)
                                                    <span class="text-primary fw-medium">{{ $lead->company }}</span>
                                                    @if ($lead->job_title)
                                                        <br><small class="text-muted">{{ $lead->job_title }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $lead->status_color }}">{{ $lead->status_label }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $lead->priority_color }}">{{ $lead->priority_label }}</span>
                                            </td>
                                            <td class="dark:text-gray-100">
                                                @if ($lead->estimated_value)
                                                    <span
                                                        class="text-success fw-medium">${{ number_format($lead->estimated_value) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="dark:text-gray-100">
                                                @if ($lead->assignedUser)
                                                    <span
                                                        class="badge bg-secondary">{{ $lead->assignedUser->name }}</span>
                                                @else
                                                    <span class="text-muted">Unassigned</span>
                                                @endif
                                            </td>
                                            <td class="dark:text-gray-100">
                                                @if ($lead->next_follow_up)
                                                    @if ($lead->isFollowUpOverdue())
                                                        <span class="text-danger fw-medium">
                                                            <i class="fa fa-exclamation-triangle me-1"></i>
                                                            {{ $lead->next_follow_up->format('M d') }}
                                                        </span>
                                                    @else
                                                        <span>{{ $lead->next_follow_up->format('M d, Y') }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-light text-dark">{{ $lead->source_label }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.crm.show', $lead) }}"
                                                        class="btn btn-sm btn-outline-info" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.crm.edit', $lead) }}"
                                                        class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-lead"
                                                        data-id="{{ $lead->id }}"
                                                        data-name="{{ $lead->full_name }}" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer bg-transparent border-0">
                            {{ $leads->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No leads found</h5>
                            <p class="text-muted">Create your first lead to get started with your CRM.</p>
                            <a href="{{ route('admin.crm.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus me-2"></i>Add New Lead
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectAll = document.getElementById('selectAll');
                const selectAllHeader = document.getElementById('selectAllHeader');
                const checkboxes = document.querySelectorAll('.lead-checkbox');
                const bulkActionsBar = document.getElementById('bulkActionsBar');
                const selectedCount = document.getElementById('selectedCount');
                const bulkStatus = document.getElementById('bulkStatus');
                const bulkAssign = document.getElementById('bulkAssign');
                const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

                function updateBulkBar() {
                    const checked = document.querySelectorAll('.lead-checkbox:checked');
                    selectedCount.textContent = checked.length;
                    bulkActionsBar.style.display = checked.length > 0 ? 'block' : 'none';
                }

                function getSelectedIds() {
                    return Array.from(document.querySelectorAll('.lead-checkbox:checked')).map(cb => cb.value);
                }

                [selectAll, selectAllHeader].forEach(el => {
                    if (el) {
                        el.addEventListener('change', function() {
                            checkboxes.forEach(cb => cb.checked = this.checked);
                            updateBulkBar();
                        });
                    }
                });

                checkboxes.forEach(cb => cb.addEventListener('change', updateBulkBar));

                // Bulk status change
                bulkStatus.addEventListener('change', function() {
                    if (!this.value) return;
                    const ids = getSelectedIds();
                    if (ids.length === 0) return;

                    bulkAction('status', this.value, ids);
                    this.value = '';
                });

                // Bulk assign
                bulkAssign.addEventListener('change', function() {
                    if (!this.value) return;
                    const ids = getSelectedIds();
                    if (ids.length === 0) return;

                    bulkAction('assign', this.value, ids);
                    this.value = '';
                });

                // Bulk delete
                bulkDeleteBtn.addEventListener('click', function() {
                    const ids = getSelectedIds();
                    if (ids.length === 0) return;

                    if (confirm(`Are you sure you want to delete ${ids.length} lead(s)?`)) {
                        bulkAction('delete', null, ids);
                    }
                });

                function bulkAction(action, value, ids) {
                    fetch('{{ route('admin.crm.bulk-update') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                action,
                                value,
                                ids
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', data.message);
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                showToast('error', data.message);
                            }
                        })
                        .catch(() => showToast('error', 'An error occurred'));
                }

                // Single delete
                document.querySelectorAll('.delete-lead').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const name = this.dataset.name;

                        if (confirm(`Are you sure you want to delete "${name}"?`)) {
                            fetch(`/admin/crm/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        showToast('success', data.message);
                                        this.closest('tr').remove();
                                    } else {
                                        showToast('error', data.message);
                                    }
                                })
                                .catch(() => showToast('error', 'Failed to delete lead'));
                        }
                    });
                });

                function showToast(type, message) {
                    const toast = document.getElementById(type + 'Toast');
                    const msgEl = document.getElementById(type + 'Message');
                    msgEl.textContent = message;
                    new bootstrap.Toast(toast).show();
                }
            });
        </script>
    @endpush
</x-app-layout>
