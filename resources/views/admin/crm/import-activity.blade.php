<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="font-semibold text-xl text-dark leading-tight mb-1">Lead Activity Analytics</h2>
                <p class="text-muted text-sm mb-0">Track leads created and imported by staff and supervisors in your organization.</p>
            </div>
            <a href="{{ orgRoute('admin.crm.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-2"></i>Back to CRM
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Advanced Filters -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0"><i class="fa fa-filter me-2"></i>Advanced Filters</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ orgRoute('admin.crm.import-activity') }}" class="row g-3 align-items-end">
                        <!-- Period Filter -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">Date Range</label>
                            <select name="period" id="period" class="form-select form-select-sm">
                                <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Today</option>
                                <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>This Week</option>
                                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>This Month</option>
                                <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>

                        <!-- Custom Date Range (Hidden by default) -->
                        <div class="col-md-2" id="dateFromWrap" style="{{ $period === 'custom' ? '' : 'display:none;' }}">
                            <label class="form-label fw-semibold small">From</label>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from', $dateFrom) }}">
                        </div>
                        <div class="col-md-2" id="dateToWrap" style="{{ $period === 'custom' ? '' : 'display:none;' }}">
                            <label class="form-label fw-semibold small">To</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to', $dateTo) }}">
                        </div>

                        <!-- Pipeline/Drive Filter -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">Pipeline</label>
                            <select name="drive_ids[]" id="drives" class="form-select form-select-sm" multiple data-placeholder="All Pipelines">
                                @foreach ($drives as $drive)
                                    <option value="{{ $drive->id }}" 
                                        {{ in_array($drive->id, $filters['drive_ids'] ?? []) ? 'selected' : '' }}>
                                        {{ $drive->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">Select multiple pipelines to filter</small>
                        </div>

                        <!-- User Filter -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">Users</label>
                            <select name="user_ids[]" id="users" class="form-select form-select-sm" multiple data-placeholder="All Users">
                                @foreach ($availableUsers as $availableUser)
                                    <option value="{{ $availableUser->id }}" 
                                        {{ in_array($availableUser->id, $filters['user_ids'] ?? []) ? 'selected' : '' }}>
                                        {{ $availableUser->name }} 
                                        ({{ $availableUser->roles->first()?->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">Select specific users to view</small>
                        </div>

                        <!-- Lead Type Filter -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">Lead Type</label>
                            <select name="lead_type" class="form-select form-select-sm">
                                <option value="all" {{ ($filters['lead_type'] ?? 'all') === 'all' ? 'selected' : '' }}>All Types</option>
                                <option value="imported" {{ ($filters['lead_type'] ?? 'all') === 'imported' ? 'selected' : '' }}>Imported</option>
                                <option value="manual" {{ ($filters['lead_type'] ?? 'all') === 'manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 btn-sm">
                                <i class="fa fa-filter me-1"></i>Apply Filters
                            </button>
                            @if (count(array_filter($filters)) > 0)
                                <a href="{{ orgRoute('admin.crm.import-activity') }}" class="btn btn-outline-secondary w-100 btn-sm mt-1">
                                    <i class="fa fa-times me-1"></i>Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="row g-3 mb-4">
                <div class="col-md-2">
                    <div class="card border-0 shadow-sm h-100 bg-primary bg-opacity-10">
                        <div class="card-body">
                            <h6 class="text-muted mb-1"><i class="fa fa-users text-primary me-1"></i>Active Users</h6>
                            <h3 class="mb-0 text-primary">{{ number_format($summary['users_count']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card border-0 shadow-sm h-100 bg-success bg-opacity-10">
                        <div class="card-body">
                            <h6 class="text-muted mb-1"><i class="fa fa-list text-success me-1"></i>Total Leads</h6>
                            <h3 class="mb-0 text-success">{{ number_format($summary['total_leads']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card border-0 shadow-sm h-100 bg-info bg-opacity-10">
                        <div class="card-body">
                            <h6 class="text-muted mb-1"><i class="fa fa-download text-info me-1"></i>Imported</h6>
                            <h3 class="mb-0 text-info">{{ number_format($summary['imported_leads']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card border-0 shadow-sm h-100 bg-warning bg-opacity-10">
                        <div class="card-body">
                            <h6 class="text-muted mb-1"><i class="fa fa-pen text-warning me-1"></i>Manual</h6>
                            <h3 class="mb-0 text-warning">{{ number_format($summary['manual_leads']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card border-0 shadow-sm h-100 bg-secondary bg-opacity-10">
                        <div class="card-body">
                            <h6 class="text-muted mb-1"><i class="fa fa-plus text-secondary me-1"></i>Direct</h6>
                            <h3 class="mb-0 text-secondary">{{ number_format($summary['direct_leads']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card border-0 shadow-sm h-100 bg-danger bg-opacity-10">
                        <div class="card-body">
                            <h6 class="text-muted mb-1"><i class="fa fa-chart-line text-danger me-1"></i>Avg/User</h6>
                            <h3 class="mb-0 text-danger">{{ number_format($summary['avg_leads_per_user'], 1) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pipeline Breakdown (if available) -->
            @if ($pipelineBreakdown->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0"><i class="fa fa-chart-bar me-2"></i>Leads by Pipeline</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pipeline</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">Imported</th>
                                        <th class="text-end">Manual</th>
                                        <th class="text-end">% of Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pipelineBreakdown as $pipeline)
                                        <tr>
                                            <td>
                                                <span class="badge" style="background-color: {{ $pipeline['drive']?->color ?? '#6c757d' }}">
                                                    {{ $pipeline['drive_name'] }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold">{{ number_format($pipeline['total_leads']) }}</td>
                                            <td class="text-end text-info">{{ number_format($pipeline['imported_leads']) }}</td>
                                            <td class="text-end text-warning">{{ number_format($pipeline['manual_leads']) }}</td>
                                            <td class="text-end text-muted">
                                                {{ $summary['total_leads'] > 0 ? round(($pipeline['total_leads'] / $summary['total_leads']) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Activity Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fa fa-clock me-2"></i>Lead Creation Activity by User</h6>
                    <small class="text-muted">{{ $dateFrom }} to {{ $dateTo }}</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Imported</th>
                                    <th class="text-end">Manual</th>
                                    <th class="text-end">Direct</th>
                                    <th class="text-end">Pipelines</th>
                                    <th>Last Activity</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($staffLeadStats as $row)
                                    <tr>
                                        <td class="fw-semibold">{{ $row['user']->name }}</td>
                                        <td>{{ $row['user']->email }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $row['user']->roles->first()?->name ?? 'N/A' }}</span>
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format($row['total_leads']) }}</td>
                                        <td class="text-end text-info">{{ number_format($row['imported_leads']) }}</td>
                                        <td class="text-end text-warning">{{ number_format($row['manual_leads']) }}</td>
                                        <td class="text-end text-secondary">{{ number_format($row['direct_leads']) }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-light text-dark">{{ $row['pipelines_used'] }}</span>
                                        </td>
                                        <td>
                                            @if ($row['last_activity_at'])
                                                <small class="text-muted" title="{{ $row['last_activity_at']->format('M d, Y h:i A') }}">
                                                    {{ $row['last_activity_at']->diffForHumans() }}
                                                </small>
                                            @else
                                                <span class="text-muted text-sm">No activity</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ orgRoute('admin.crm.user-activity', $row['user_id']) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="View detailed leads">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4 text-muted">
                                            <i class="fa fa-inbox text-muted me-2"></i>No lead activity found in the selected filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Handle period toggle for custom dates
                const period = document.getElementById('period');
                const fromWrap = document.getElementById('dateFromWrap');
                const toWrap = document.getElementById('dateToWrap');

                function toggleCustomDateFields() {
                    const isCustom = period.value === 'custom';
                    fromWrap.style.display = isCustom ? '' : 'none';
                    toWrap.style.display = isCustom ? '' : 'none';
                }

                period.addEventListener('change', toggleCustomDateFields);

                // Initialize Select2 for multi-select fields if available
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $('#drives').select2({
                        placeholder: 'All Pipelines',
                        allowClear: true,
                        width: '100%'
                    });
                    
                    $('#users').select2({
                        placeholder: 'All Users',
                        allowClear: true,
                        width: '100%'
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
