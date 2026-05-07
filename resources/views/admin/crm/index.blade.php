<x-app-layout>
    @push('styles')
        <style>
            /* Status Dropdown Styles */
            .status-dropdown .status-badge-btn {
                cursor: pointer;
                font-size: 0.85rem;
                padding: 0.35rem 0.75rem;
                transition: all 0.2s ease;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .status-dropdown .status-badge-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            }

            .status-dropdown .dropdown-menu {
                min-width: 180px;
                padding: 0.5rem 0;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            }

            .status-dropdown .dropdown-item {
                padding: 0.5rem 1rem;
                display: flex;
                align-items: center;
                font-size: 0.9rem;
                transition: all 0.15s ease;
            }

            .status-dropdown .dropdown-item:hover {
                background-color: rgba(13, 110, 253, 0.1);
            }

            .status-dropdown .dropdown-item.active {
                background-color: rgba(13, 110, 253, 0.15);
                color: inherit;
                font-weight: 500;
            }

            .status-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                display: inline-block;
                margin-right: 8px;
            }

            /* Status dot colors - dynamically generated from lead statuses */
            @foreach ($leadStatuses as $ls)
                .status-dot.status-{{ $ls->slug }} {
                    background-color: {{ str_starts_with($ls->color, '#') ? $ls->color : 'var(--bs-' . $ls->color . ')' }};
                }
            @endforeach

            /* Dark mode support */
            [data-theme="dark"] .status-dropdown .dropdown-menu,
            .dark .status-dropdown .dropdown-menu {
                background-color: #2d3748;
                border-color: #4a5568;
            }

            [data-theme="dark"] .status-dropdown .dropdown-item,
            .dark .status-dropdown .dropdown-item {
                color: #e2e8f0;
            }

            [data-theme="dark"] .status-dropdown .dropdown-item:hover,
            .dark .status-dropdown .dropdown-item:hover {
                background-color: rgba(255, 255, 255, 0.1);
            }

            /* Status update loading state */
            .status-badge-btn.loading {
                opacity: 0.6;
                pointer-events: none;
            }

            .status-badge-btn.loading::after {
                content: '';
                width: 12px;
                height: 12px;
                border: 2px solid transparent;
                border-top-color: currentColor;
                border-radius: 50%;
                display: inline-block;
                margin-left: 5px;
                animation: spin 0.8s linear infinite;
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }
        </style>
    @endpush

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Lead Management (CRM)') }}
                    @if (isset($currentDrive))
                        <span class="badge ms-2"
                            style="background-color: {{ $currentDrive->color ?? '#6c757d' }}; font-size: 0.6em; vertical-align: middle;">
                            {{ $currentDrive->name }}
                        </span>
                    @endif
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Manage and track all your leads</p>
            </div>
            <div class="d-flex gap-2">
                <!-- Drive Switcher -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa fa-folder me-2"></i>
                        @if (request('drive') && request('drive') !== 'all')
                            @if (request('drive') === 'unassigned')
                                No Pipeline
                            @elseif (isset($currentDrive))
                                {{ $currentDrive->name }}
                            @else
                                Select Pipeline
                            @endif
                        @else
                            All Pipelines
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item {{ !request('drive') || request('drive') === 'all' ? 'active' : '' }}"
                                href="{{ orgRoute('admin.crm.index', array_merge(request()->except('drive', 'page'), ['drive' => 'all'])) }}">
                                <i class="fa fa-globe me-2 text-muted"></i>All Pipelines
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @forelse ($drives ?? [] as $drive)
                            <li>
                                <a class="dropdown-item {{ request('drive') == $drive->id ? 'active' : '' }}"
                                    href="{{ orgRoute('admin.crm.index', array_merge(request()->except('drive', 'page'), ['drive' => $drive->id])) }}">
                                    <span class="d-inline-block rounded-circle me-2"
                                        style="width: 12px; height: 12px; background-color: {{ $drive->color ?? '#6c757d' }};"></span>
                                    {{ $drive->name }}
                                    <span class="badge bg-secondary ms-2">{{ $drive->leads()->count() }}</span>
                                </a>
                            </li>
                        @empty
                            <li><span class="dropdown-item text-muted">No drives created</span></li>
                        @endforelse
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('drive') === 'unassigned' ? 'active' : '' }}"
                                href="{{ orgRoute('admin.crm.index', array_merge(request()->except('drive', 'page'), ['drive' => 'unassigned'])) }}">
                                <i class="fa fa-inbox me-2 text-muted"></i>No Pipeline
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-primary" href="{{ orgRoute('admin.crm.drives.index') }}">
                                <i class="fa fa-cog me-2"></i>Manage Pipelines
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="{{ orgRoute('admin.crm.pipeline', request()->only('drive')) }}" class="btn btn-outline-info">
                    <i class="fa fa-columns me-2"></i>Pipeline View
                </a>
                @hasanyrole('admin|supervisor')
                    <!--
                                <a href="{{ orgRoute('admin.crm.statuses.index') }}" class="btn btn-outline-dark">
                                    <i class="fa fa-tags me-2"></i>Manage Statuses
                                </a>  -->
                    <a href="{{ orgRoute('admin.crm.settings') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-cog me-2"></i>CRM Settings
                    </a>

                    <!--
                        <a href="{{ orgRoute('admin.crm.import-activity') }}" class="btn btn-outline-dark">
                            <i class="fa fa-chart-bar me-2"></i>Import Activity
                        </a> -->
                @endhasanyrole
                <a href="{{ orgRoute('admin.crm.import') }}" class="btn btn-outline-success">
                    <i class="fa fa-file-import me-2"></i>Import Leads
                </a>
                @if (($canCrossOrgExport ?? false) && $exportOrganizations->count() > 0)
                    <form method="GET" action="{{ orgRoute('admin.crm.export') }}"
                        class="d-flex gap-2 align-items-center">
                        @foreach (request()->except('page', 'organization_id', 'format') as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $item)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach

                        <select name="organization_id" class="form-select form-select-sm" style="min-width: 220px;"
                            required>
                            <option value="" disabled
                                {{ request()->filled('organization_id') ? '' : 'selected' }}>Select Organization
                            </option>
                            @foreach ($exportOrganizations as $organization)
                                <option value="{{ $organization->id }}"
                                    {{ (string) request('organization_id') === (string) $organization->id ? 'selected' : '' }}>
                                    {{ $organization->name }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" name="format" value="csv" class="btn btn-outline-secondary">
                            <i class="fa fa-download me-2"></i>Export CSV
                        </button>

                        <button type="submit" name="format" value="migration_json" class="btn btn-outline-dark">
                            <i class="fa fa-database me-2"></i>Export Migration JSON
                        </button>
                    </form>
                @else
                    <div class="btn-group" role="group" aria-label="Lead export options">
                        <a href="{{ orgRoute('admin.crm.export', array_merge(request()->except('page', 'organization_id', 'format'), ['format' => 'csv'])) }}"
                            class="btn btn-outline-secondary">
                            <i class="fa fa-download me-2"></i>Export CSV
                        </a>
                        <a href="{{ orgRoute('admin.crm.export', array_merge(request()->except('page', 'organization_id', 'format'), ['format' => 'migration_json'])) }}"
                            class="btn btn-outline-dark">
                            <i class="fa fa-database me-2"></i>Export Migration JSON
                        </a>
                    </div>
                @endif
                <a href="{{ orgRoute('admin.crm.create') }}" class="btn btn-primary">
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

            @if (session('import_errors'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="alert-heading mb-2">
                                <i class="fa fa-exclamation-triangle me-2"></i>Import completed with some issues
                            </h6>
                            <p class="mb-2 small text-muted">
                                The rows below were skipped during import. You can adjust your file and re-import to
                                include them.
                            </p>
                            <div style="max-height: 200px; overflow-y: auto;">
                                <ul class="mb-0 ps-3 small">
                                    @foreach (session('import_errors') as $importError)
                                        <li>{!! e($importError) !!}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @if (session('more_errors'))
                                <p class="mt-2 mb-0 text-muted small">...and {{ session('more_errors') }} more
                                    issue(s)
                                    not shown.</p>
                            @endif
                            <div class="mt-3">
                                <a href="{{ orgRoute('admin.crm.import') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-redo me-1"></i>Re-import with corrections
                                </a>
                            </div>
                        </div>
                    </div>
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
                                        {{ $crmCurrency }}{{ number_format($stats['total_value']) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ orgRoute('admin.crm.index') }}" class="row g-3">
                        <!-- Preserve drive filter -->
                        @if (request('drive'))
                            <input type="hidden" name="drive" value="{{ request('drive') }}">
                        @endif
                        <div class="col-md-3">
                            <input type="text" name="search"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                placeholder="Search by name, email, company..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status"
                                class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">All Statuses</option>
                                @foreach ($leadStatuses as $ls)
                                    <option value="{{ $ls->slug }}"
                                        {{ request('status') === $ls->slug ? 'selected' : '' }}>{{ $ls->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="source"
                                class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">All Sources</option>
                                @foreach ($sources as $key => $label)
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
                            @foreach ($leadStatuses as $ls)
                                <option value="{{ $ls->slug }}">{{ $ls->name }}</option>
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
                        @php
                            $hasCountryData = $leads->contains(fn($lead) => !empty($lead->country));
                        @endphp
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="dark:bg-gray-700">
                                    <tr>
                                        <th width="40"><input type="checkbox" class="form-check-input"
                                                id="selectAllHeader"></th>
                                        <th class="dark:text-gray-100">Lead</th>
                                        <th class="dark:text-gray-100">Company</th>
                                        @if ($hasCountryData)
                                            <th class="dark:text-gray-100">Country</th>
                                        @endif
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
                                            @if ($hasCountryData)
                                                <td class="dark:text-gray-100">
                                                    @if ($lead->country)
                                                        <span>{{ $lead->country }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                <div class="dropdown status-dropdown">
                                                    <button
                                                        class="btn btn-sm btn-{{ $lead->status_color }} dropdown-toggle status-badge-btn"
                                                        type="button" data-bs-toggle="dropdown"
                                                        data-id="{{ $lead->id }}" title="Click to change status">
                                                        {{ $lead->status_label }}
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        @foreach ($leadStatuses as $ls)
                                                            <li>
                                                                <a class="dropdown-item status-option {{ $lead->status === $ls->slug ? 'active' : '' }}"
                                                                    href="#" data-id="{{ $lead->id }}"
                                                                    data-status="{{ $ls->slug }}"
                                                                    data-is-lost="{{ $ls->is_lost ? '1' : '0' }}">
                                                                    <span
                                                                        class="status-dot status-{{ $ls->slug }}"></span>
                                                                    {{ $ls->name }}
                                                                    @if ($lead->status === $ls->slug)
                                                                        <i class="fa fa-check ms-2 text-success"></i>
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $lead->priority_color }}">{{ $lead->priority_label }}</span>
                                            </td>
                                            <td class="dark:text-gray-100">
                                                @if ($lead->estimated_value)
                                                    <span
                                                        class="text-success fw-medium">{{ $crmCurrency }}{{ number_format($lead->estimated_value) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="dark:text-gray-100">
                                                @if ($lead->assignedUser)
                                                    <span
                                                        class="badge bg-gray-500">{{ $lead->assignedUser->name }}</span>
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
                                                    class="badge bg-gray-500 text-dark dark:text-gray-100 border">{{ $lead->source_label }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ orgRoute('admin.crm.show', $lead) }}"
                                                        class="btn btn-sm btn-outline-info" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ orgRoute('admin.crm.edit', $lead) }}"
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
                            <a href="{{ orgRoute('admin.crm.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus me-2"></i>Add New Lead
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Lost Reason Modal -->
    <div class="modal fade" id="lostReasonModal" tabindex="-1" aria-labelledby="lostReasonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header">
                    <h5 class="modal-title dark:text-gray-100" id="lostReasonModalLabel">
                        <i class="fa fa-times-circle text-danger me-2"></i>Lost Reason
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted dark:text-gray-400 mb-3">Please provide a reason why this lead was lost
                        (optional).</p>
                    <textarea class="form-control dark:bg-gray-700 dark:text-gray-100" id="lostReasonText" rows="3"
                        placeholder="Enter reason..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmLostBtn">
                        <i class="fa fa-check me-2"></i>Mark as Lost
                    </button>
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

                // Status color mapping (dynamic from database)
                const statusColors = @json($leadStatuses->pluck('color', 'slug'));

                // Status labels mapping (dynamic from database)
                const statusLabels = @json($leadStatuses->pluck('name', 'slug'));

                // Lost status slugs
                const lostSlugs = @json($leadStatuses->where('is_lost', true)->pluck('slug')->values());

                // Lost reason modal
                const lostReasonModal = new bootstrap.Modal(document.getElementById('lostReasonModal'));
                let pendingLostUpdate = null;

                // Handle status dropdown click
                document.querySelectorAll('.status-option').forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.preventDefault();
                        const leadId = this.dataset.id;
                        const newStatus = this.dataset.status;
                        const btn = document.querySelector(`.status-badge-btn[data-id="${leadId}"]`);

                        // If changing to a "lost" status, show modal for reason
                        if (lostSlugs.includes(newStatus)) {
                            pendingLostUpdate = {
                                leadId,
                                status: newStatus,
                                btn,
                                dropdown: this.closest('.dropdown')
                            };
                            lostReasonModal.show();
                            return;
                        }

                        updateLeadStatus(leadId, newStatus, btn, this.closest('.dropdown'));
                    });
                });

                // Handle lost confirmation
                document.getElementById('confirmLostBtn').addEventListener('click', function() {
                    if (pendingLostUpdate) {
                        const reason = document.getElementById('lostReasonText').value;
                        updateLeadStatus(
                            pendingLostUpdate.leadId,
                            pendingLostUpdate.status,
                            pendingLostUpdate.btn,
                            pendingLostUpdate.dropdown,
                            reason
                        );
                        lostReasonModal.hide();
                        document.getElementById('lostReasonText').value = '';
                        pendingLostUpdate = null;
                    }
                });

                // Reset modal when closed
                document.getElementById('lostReasonModal').addEventListener('hidden.bs.modal', function() {
                    document.getElementById('lostReasonText').value = '';
                    pendingLostUpdate = null;
                });

                function parseApiResponse(response, fallbackMessage) {
                    return response.json()
                        .catch(() => ({}))
                        .then(data => {
                            if (!response.ok) {
                                throw new Error(data.message || fallbackMessage);
                            }

                            return data;
                        });
                }

                function updateLeadStatus(leadId, newStatus, btn, dropdown, lostReason = null) {
                    // Show loading state
                    btn.classList.add('loading');
                    btn.disabled = true;

                    const requestBody = {
                        status: newStatus
                    };
                    if (lostReason) {
                        requestBody.lost_reason = lostReason;
                    }

                    fetch(`${window.adminBasePath}/crm/${leadId}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(requestBody)
                        })
                        .then(res => parseApiResponse(res, 'Failed to update status'))
                        .then(data => {
                            btn.classList.remove('loading');
                            btn.disabled = false;

                            if (data.success) {
                                // Update button appearance
                                const newColor = statusColors[newStatus] || 'secondary';
                                const newLabel = statusLabels[newStatus] || newStatus;

                                // Remove all existing color classes
                                Object.values(statusColors).forEach(color => {
                                    btn.classList.remove(`btn-${color}`);
                                });

                                // Add new color class
                                btn.classList.add(`btn-${newColor}`);

                                // Update text (keep dropdown-toggle class)
                                btn.textContent = newLabel;

                                // Update dropdown active state
                                dropdown.querySelectorAll('.dropdown-item').forEach(item => {
                                    item.classList.remove('active');
                                    item.querySelector('.fa-check')?.remove();
                                });

                                const activeItem = dropdown.querySelector(`[data-status="${newStatus}"]`);
                                if (activeItem) {
                                    activeItem.classList.add('active');
                                    const checkIcon = document.createElement('i');
                                    checkIcon.className = 'fa fa-check ms-2 text-success';
                                    activeItem.appendChild(checkIcon);
                                }

                                showToast('success', data.message || 'Status updated successfully');
                            } else {
                                showToast('error', data.message || 'Failed to update status');
                            }
                        })
                        .catch(err => {
                            btn.classList.remove('loading');
                            btn.disabled = false;
                            showToast('error', err.message || 'Failed to update status');
                        });
                }

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
                    fetch('{{ orgRoute('admin.crm.bulk-update') }}', {
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
                        .then(res => parseApiResponse(res, 'Bulk action failed'))
                        .then(data => {
                            if (data.success) {
                                showToast('success', data.message);
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                showToast('error', data.message);
                            }
                        })
                        .catch(err => showToast('error', err.message || 'An error occurred'));
                }

                // Single delete
                document.querySelectorAll('.delete-lead').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const name = this.dataset.name;

                        if (confirm(`Are you sure you want to delete "${name}"?`)) {
                            fetch(`${window.adminBasePath}/crm/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => parseApiResponse(res, 'Failed to delete lead'))
                                .then(data => {
                                    if (data.success) {
                                        showToast('success', data.message);
                                        this.closest('tr').remove();
                                    } else {
                                        showToast('error', data.message);
                                    }
                                })
                                .catch(err => showToast('error', err.message ||
                                    'Failed to delete lead'));
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
