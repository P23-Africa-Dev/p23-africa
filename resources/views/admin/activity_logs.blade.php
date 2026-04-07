<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Activity Logs') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Track all admin dashboard activities</p>
            </div>
            <a href="{{ orgRoute('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3">
                                    <i class="fa fa-clock text-primary fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1 small">Today</h6>
                                    <h4 class="mb-0 text-dark dark:text-gray-100">
                                        {{ $logs->where('created_at', '>=', now()->startOfDay())->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                    <i class="fa fa-calendar-week text-success fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1 small">This Week</h6>
                                    <h4 class="mb-0 text-dark dark:text-gray-100">
                                        {{ $logs->where('created_at', '>=', now()->startOfWeek())->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-info bg-opacity-10 rounded p-3">
                                    <i class="fa fa-users text-info fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1 small">Active Users</h6>
                                    <h4 class="mb-0 text-dark dark:text-gray-100">{{ $users->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded p-3">
                                    <i class="fa fa-list text-warning fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1 small">Total Logs</h6>
                                    <h4 class="mb-0 text-dark dark:text-gray-100">{{ $logs->total() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ orgRoute('admin.activity.logs') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small text-muted dark:text-gray-400">Search</label>
                            <input type="text" name="search"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                placeholder="Search activities..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted dark:text-gray-400">User</label>
                            <select name="user_id"
                                class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">All Users</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted dark:text-gray-400">Subject Type</label>
                            <select name="subject_type"
                                class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">All Types</option>
                                @foreach ($subjectTypes as $type)
                                    <option value="{{ $type }}"
                                        {{ request('subject_type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted dark:text-gray-400">Date From</label>
                            <input type="date" name="date_from"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted dark:text-gray-400">Date To</label>
                            <input type="date" name="date_to"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                            </button>
                            <a href="{{ orgRoute('admin.activity.logs') }}" class="btn btn-outline-secondary">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Activity Logs Table -->
            <div class="card border-0 shadow-sm dark:bg-gray-800">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <h5 class="mb-0 text-dark dark:text-gray-100">
                        <i class="fa fa-history me-2 text-primary"></i>Recent Activities
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light dark:bg-gray-700">
                                <tr>
                                    <th class="border-0 text-muted dark:text-gray-400 small fw-semibold">Date & Time
                                    </th>
                                    <th class="border-0 text-muted dark:text-gray-400 small fw-semibold">User</th>
                                    <th class="border-0 text-muted dark:text-gray-400 small fw-semibold">Action</th>
                                    <th class="border-0 text-muted dark:text-gray-400 small fw-semibold">Subject</th>
                                    <th class="border-0 text-muted dark:text-gray-400 small fw-semibold">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                    <tr class="dark:border-gray-700">
                                        <td class="dark:text-gray-300 align-middle">
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="fw-medium">{{ $log->created_at->format('M d, Y') }}</span>
                                                <small
                                                    class="text-muted dark:text-gray-500">{{ $log->created_at->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                        <td class="dark:text-gray-300 align-middle">
                                            @if ($log->causer)
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                        <i class="fa fa-user text-primary small"></i>
                                                    </div>
                                                    <div>
                                                        <span class="fw-medium">{{ $log->causer->name }}</span>
                                                        <br><small
                                                            class="text-muted dark:text-gray-500">{{ $log->causer->email }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge bg-secondary">System</span>
                                            @endif
                                        </td>
                                        <td class="dark:text-gray-300 align-middle">
                                            @php
                                                $actionClass = match (true) {
                                                    str_contains(strtolower($log->description), 'created')
                                                        => 'bg-success',
                                                    str_contains(strtolower($log->description), 'updated') => 'bg-info',
                                                    str_contains(strtolower($log->description), 'deleted')
                                                        => 'bg-danger',
                                                    str_contains(strtolower($log->description), 'suspended')
                                                        => 'bg-warning',
                                                    str_contains(strtolower($log->description), 'promoted')
                                                        => 'bg-primary',
                                                    str_contains(strtolower($log->description), 'reactivated')
                                                        => 'bg-success',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp
                                            <span
                                                class="badge {{ $actionClass }}">{{ ucfirst($log->description) }}</span>
                                        </td>
                                        <td class="dark:text-gray-300 align-middle">
                                            @if ($log->subject_type)
                                                <span
                                                    class="badge bg-light text-dark dark:bg-gray-600 dark:text-gray-200">
                                                    {{ class_basename($log->subject_type) }}
                                                </span>
                                                @if ($log->subject_id)
                                                    <small
                                                        class="text-muted dark:text-gray-500">#{{ $log->subject_id }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="dark:text-gray-300 align-middle">
                                            @if ($log->properties && $log->properties->count() > 0)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary js-activity-log-view"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#activityLogDetailsModal"
                                                    data-action="{{ $log->description }}"
                                                    data-user="{{ $log->causer?->name ?? 'System' }}"
                                                    data-date="{{ $log->created_at->format('M d, Y h:i A') }}"
                                                    data-properties-source="activity-log-properties-{{ $log->id }}">
                                                    <i class="fa fa-eye me-1"></i> View
                                                </button>
                                                <script type="application/json"
                                                    id="activity-log-properties-{{ $log->id }}">@json($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
                                            @else
                                                <span class="text-muted small">No details</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa fa-inbox fa-3x mb-3"></i>
                                                <p class="mb-0">No activity logs found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($logs->hasPages())
                    <div class="card-footer bg-transparent border-0">
                        <div class="d-flex justify-content-center">
                            {{ $logs->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="activityLogDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header border-0">
                    <h5 class="modal-title dark:text-gray-100">
                        <i class="fa fa-info-circle text-primary me-2"></i>Activity Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Action:</div>
                        <div class="col-sm-8 dark:text-gray-200" id="activityLogModalAction">-</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Performed by:</div>
                        <div class="col-sm-8 dark:text-gray-200" id="activityLogModalUser">-</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Date/Time:</div>
                        <div class="col-sm-8 dark:text-gray-200" id="activityLogModalDate">-</div>
                    </div>
                    <hr class="dark:border-gray-700">
                    <h6 class="text-muted mb-3">Properties</h6>
                    <pre id="activityLogModalProperties"
                        class="bg-light dark:bg-gray-700 p-3 rounded small dark:text-gray-200"
                        style="max-height: 300px; overflow: auto;">{}</pre>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function initActivityLogDetailsModal() {
                const modalElement = document.getElementById('activityLogDetailsModal');

                if (!modalElement || modalElement.dataset.initialized === 'true') {
                    return;
                }

                modalElement.dataset.initialized = 'true';

                const actionField = document.getElementById('activityLogModalAction');
                const userField = document.getElementById('activityLogModalUser');
                const dateField = document.getElementById('activityLogModalDate');
                const propertiesField = document.getElementById('activityLogModalProperties');

                modalElement.addEventListener('show.bs.modal', function(event) {
                    const trigger = event.relatedTarget;

                    if (!trigger) {
                        return;
                    }

                    actionField.textContent = trigger.dataset.action || '-';
                    userField.textContent = trigger.dataset.user || '-';
                    dateField.textContent = trigger.dataset.date || '-';

                    const sourceId = trigger.dataset.propertiesSource;
                    const sourceElement = sourceId ? document.getElementById(sourceId) : null;
                    propertiesField.textContent = sourceElement ? sourceElement.textContent.trim() : '{}';
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initActivityLogDetailsModal);
            } else {
                initActivityLogDetailsModal();
            }
        </script>
    @endpush
</x-app-layout>
