<x-app-layout>
    @push('styles')
        <style>
            .analytics-stat-card {
                border-radius: 12px;
                transition: transform 0.15s ease, box-shadow 0.15s ease;
            }
            .analytics-stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0,0,0,0.10) !important;
            }
            .analytics-stat-card .stat-icon {
                width: 44px;
                height: 44px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.15rem;
            }
            .analytics-stat-card .stat-value {
                font-size: 1.65rem;
                font-weight: 700;
                line-height: 1.1;
            }
            .analytics-stat-card .stat-label {
                font-size: 0.78rem;
                font-weight: 500;
                text-transform: uppercase;
                letter-spacing: 0.04em;
            }
            .filter-card {
                border-radius: 12px;
                border: 1px solid rgba(0,0,0,0.07);
            }
            .filter-card .card-header {
                border-radius: 12px 12px 0 0;
                border-bottom: 1px solid rgba(0,0,0,0.06);
            }
            .section-card {
                border-radius: 12px;
                border: 1px solid rgba(0,0,0,0.07);
            }
            .section-card .card-header {
                border-radius: 12px 12px 0 0;
                border-bottom: 1px solid rgba(0,0,0,0.06);
                padding: 1rem 1.25rem;
            }
            .user-table tbody tr {
                transition: background 0.1s;
            }
            .user-table tbody tr:hover {
                background: rgba(13,110,253,0.03);
            }
            .user-avatar {
                width: 34px;
                height: 34px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 0.8rem;
                font-weight: 600;
                flex-shrink: 0;
            }
            .progress-bar-thin {
                height: 5px;
                border-radius: 3px;
            }
            .period-btn {
                border-radius: 8px;
                font-size: 0.82rem;
                font-weight: 500;
                padding: 0.35rem 0.85rem;
            }
            .period-btn.active {
                background: #0d6efd;
                color: #fff;
                border-color: #0d6efd;
            }
            .dark .filter-card,
            .dark .section-card {
                border-color: rgba(255,255,255,0.08);
            }
            .dark .filter-card .card-header,
            .dark .section-card .card-header {
                border-color: rgba(255,255,255,0.08);
            }
        </style>
    @endpush

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight mb-1">
                    <i class="fa fa-chart-bar text-primary me-2"></i>Lead Activity Analytics
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Track leads created and imported by staff and supervisors in your organization.</p>
            </div>
            <a href="{{ orgRoute('admin.crm.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-2"></i>Back to CRM
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-3" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- ── Period Quick-Select Pills ── --}}
            <div class="d-flex align-items-center gap-2 mb-4 flex-wrap">
                <span class="text-muted small fw-semibold me-1">Period:</span>
                @foreach (['daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month', 'custom' => 'Custom'] as $val => $label)
                    <a href="{{ $val !== 'custom' ? orgRoute('admin.crm.import-activity', array_merge(request()->except('period','date_from','date_to','page'), ['period' => $val])) : '#' }}"
                       class="btn period-btn {{ $period === $val ? 'active btn-primary' : 'btn-outline-secondary' }}"
                       @if($val === 'custom') id="customPeriodBtn" @endif>
                        {{ $label }}
                    </a>
                @endforeach

                {{-- Current date range badge --}}
                <span class="ms-auto badge bg-light text-dark border px-3 py-2" style="font-size:0.8rem;border-radius:8px;">
                    <i class="fa fa-calendar-alt me-1 text-primary"></i>
                    {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} — {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
                </span>
            </div>

            @php
                $activeFilters = count(array_filter([
                    !empty($filters['drive_ids']),
                    !empty($filters['user_ids']),
                    ($filters['lead_type'] ?? 'all') !== 'all',
                    $period === 'custom',
                ]));
            @endphp

            {{-- ── Advanced Filters ── --}}
            <div class="card filter-card shadow-sm mb-5" id="filterPanel">
                <div class="card-header bg-white dark:bg-gray-800 d-flex justify-content-between align-items-center py-3">
                    <span class="fw-semibold text-sm">
                        <i class="fa fa-sliders-h text-primary me-2"></i>Filters
                        @if ($activeFilters > 0)
                            <span class="badge bg-primary ms-1">{{ $activeFilters }}</span>
                        @endif
                    </span>
                    <small class="text-muted">Refine analytics by date, pipeline, user, and type</small>
                </div>
                <div class="card-body pb-3">
                    <form method="GET" action="{{ orgRoute('admin.crm.import-activity') }}" id="analyticsFilterForm">
                        <input type="hidden" name="period" id="periodInput" value="{{ $period }}">
                        <div class="row g-3 align-items-end">

                                {{-- Custom date range (visible only for custom period) --}}
                                <div class="col-sm-6 col-md-3" id="dateFromWrap" style="{{ $period === 'custom' ? '' : 'display:none;' }}">
                                    <label class="form-label fw-semibold small text-muted mb-1">From Date</label>
                                    <input type="date" name="date_from" class="form-control form-control-sm rounded-3"
                                           value="{{ request('date_from', $dateFrom) }}">
                                </div>
                                <div class="col-sm-6 col-md-3" id="dateToWrap" style="{{ $period === 'custom' ? '' : 'display:none;' }}">
                                    <label class="form-label fw-semibold small text-muted mb-1">To Date</label>
                                    <input type="date" name="date_to" class="form-control form-control-sm rounded-3"
                                           value="{{ request('date_to', $dateTo) }}">
                                </div>

                                {{-- Pipeline filter --}}
                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label fw-semibold small text-muted mb-1">
                                        <i class="fa fa-folder me-1"></i>Pipeline
                                    </label>
                                    <select name="drive_ids[]" id="drives" class="form-select form-select-sm rounded-3" multiple>
                                        @foreach ($drives as $drive)
                                            <option value="{{ $drive->id }}"
                                                {{ in_array($drive->id, $filters['drive_ids'] ?? []) ? 'selected' : '' }}>
                                                {{ $drive->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- User filter --}}
                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label fw-semibold small text-muted mb-1">
                                        <i class="fa fa-user me-1"></i>User
                                    </label>
                                    <select name="user_ids[]" id="users" class="form-select form-select-sm rounded-3" multiple>
                                        @foreach ($availableUsers as $au)
                                            <option value="{{ $au->id }}"
                                                {{ in_array($au->id, $filters['user_ids'] ?? []) ? 'selected' : '' }}>
                                                {{ $au->name }} ({{ $au->roles->first()?->name ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Lead type filter --}}
                                <div class="col-sm-6 col-md-2">
                                    <label class="form-label fw-semibold small text-muted mb-1">
                                        <i class="fa fa-tag me-1"></i>Lead Type
                                    </label>
                                    <select name="lead_type" class="form-select form-select-sm rounded-3">
                                        <option value="all"      {{ ($filters['lead_type'] ?? 'all') === 'all'      ? 'selected' : '' }}>All Types</option>
                                        <option value="imported" {{ ($filters['lead_type'] ?? 'all') === 'imported' ? 'selected' : '' }}>Imported</option>
                                        <option value="manual"   {{ ($filters['lead_type'] ?? 'all') === 'manual'   ? 'selected' : '' }}>Manual</option>
                                    </select>
                                </div>

                            {{-- Actions --}}
                            <div class="col-sm-6 col-md-1 d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm flex-grow-1" title="Apply">
                                    <i class="fa fa-check"></i>
                                </button>
                                @if ($activeFilters > 0 || $period !== 'monthly')
                                    <a href="{{ orgRoute('admin.crm.import-activity') }}" class="btn btn-outline-secondary btn-sm" title="Clear filters">
                                        <i class="fa fa-times"></i>
                                    </a>
                                @endif
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- ── Key Metrics ── --}}
            <div class="row g-3 mb-5">
                {{-- Hero Stat: Total Leads --}}
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card analytics-stat-card border-0 shadow flex-fill h-100" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4 position-relative overflow-hidden">
                            <i class="fa fa-layer-group position-absolute opacity-10" style="font-size: 8rem; right: -20px; bottom: -20px;"></i>
                            <div class="text-uppercase fw-bold mb-1" style="letter-spacing: 0.05em; font-size: 0.95rem; opacity: 0.9; z-index: 1;">Total Leads</div>
                            <div class="fw-bolder" style="font-size: 4rem; line-height: 1; z-index: 1;">{{ number_format($summary['total_leads']) }}</div>
                        </div>
                    </div>
                </div>

                {{-- Secondary Stats --}}
                @php
                    $secondaryStats = [
                        ['label' => 'Active Users',   'value' => $summary['users_count'],          'icon' => 'fa-users',      'color' => '#6c757d', 'bg' => '#f8f9fa'],
                        ['label' => 'Imported',       'value' => $summary['imported_leads'],        'icon' => 'fa-file-import','color' => '#6c757d', 'bg' => '#f8f9fa'],
                        ['label' => 'Manual',         'value' => $summary['manual_leads'],          'icon' => 'fa-pen-alt',    'color' => '#6c757d', 'bg' => '#f8f9fa'],
                        ['label' => 'Direct',         'value' => $summary['direct_leads'],          'icon' => 'fa-plus-circle','color' => '#6c757d', 'bg' => '#f8f9fa'],
                        ['label' => 'Avg / User',     'value' => number_format($summary['avg_leads_per_user'],1), 'icon' => 'fa-chart-line', 'color' => '#6c757d', 'bg' => '#f8f9fa', 'raw' => true],
                    ];
                @endphp
                <div class="col-12 col-lg-8">
                    <div class="row g-3 h-100">
                        @foreach ($secondaryStats as $card)
                            <div class="col-6 col-md-4">
                                <div class="card analytics-stat-card border-0 shadow-sm h-100">
                                    <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
                                        <div class="stat-icon flex-shrink-0 rounded-circle" style="background:{{ $card['bg'] }}; width: 40px; height: 40px; font-size: 1rem;">
                                            <i class="fa {{ $card['icon'] }}" style="color:{{ $card['color'] }};"></i>
                                        </div>
                                        <div>
                                            <div class="stat-label text-muted" style="font-size: 0.72rem;">{{ $card['label'] }}</div>
                                            <div class="stat-value text-dark" style="font-size: 1.3rem;">
                                                {{ isset($card['raw']) ? $card['value'] : number_format($card['value']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ── Pipeline Breakdown ── --}}
            @if ($pipelineBreakdown->count() > 0)
                <div class="card section-card shadow-sm mb-4">
                    <div class="card-header bg-white dark:bg-gray-800 d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">
                            <i class="fa fa-chart-bar text-primary me-2"></i>Breakdown by Pipeline
                        </span>
                        <small class="text-muted">{{ $pipelineBreakdown->count() }} pipeline(s)</small>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:35%">Pipeline</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">Imported</th>
                                        <th class="text-end">Manual</th>
                                        <th style="width:25%">Share</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pipelineBreakdown as $pipeline)
                                        @php $pct = $summary['total_leads'] > 0 ? round(($pipeline['total_leads'] / $summary['total_leads']) * 100, 1) : 0; @endphp
                                        <tr>
                                            <td>
                                                <span class="badge fw-semibold" style="background-color:{{ $pipeline['drive']?->color ?? '#6c757d' }};font-size:0.8rem;border-radius:6px;padding:0.35em 0.7em;">
                                                    {{ $pipeline['drive_name'] }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold">{{ number_format($pipeline['total_leads']) }}</td>
                                            <td class="text-end">
                                                <span class="badge bg-info bg-opacity-15 text-info fw-semibold">{{ number_format($pipeline['imported_leads']) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-warning bg-opacity-15 text-warning fw-semibold">{{ number_format($pipeline['manual_leads']) }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="flex-grow-1 bg-light rounded" style="height:6px;overflow:hidden;">
                                                        <div style="width:{{ $pct }}%;height:6px;background:{{ $pipeline['drive']?->color ?? '#6c757d' }};border-radius:3px;"></div>
                                                    </div>
                                                    <small class="text-muted fw-semibold" style="min-width:36px;">{{ $pct }}%</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── User Activity Table ── --}}
            <div class="card section-card shadow-sm">
                <div class="card-header bg-white dark:bg-gray-800 d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">
                        <i class="fa fa-users text-primary me-2"></i>Lead Creation by User
                    </span>
                    <span class="badge bg-light text-muted border" style="font-size:0.78rem;">
                        {{ \Carbon\Carbon::parse($dateFrom)->format('M d') }} — {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
                    </span>
                </div>
                <div class="card-body p-0">
                    @if ($staffLeadStats->isEmpty())
                        <div class="text-center py-5">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                                <i class="fa fa-folder-open fa-2x text-muted opacity-50"></i>
                            </div>
                            <h5 class="fw-bold text-dark">No Lead Activity Found</h5>
                            <p class="text-muted mb-4 max-w-md mx-auto">We couldn't find any lead tracking results matching your current filters. Try adjusting your dates, users, or pipelines to see some data.</p>
                            @if($activeFilters > 0 || $period !== 'monthly')
                                <a href="{{ orgRoute('admin.crm.import-activity') }}" class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="fa fa-refresh me-2"></i>Clear Filters
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table user-table mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end d-none d-md-table-cell">Imported</th>
                                        <th class="text-end d-none d-md-table-cell">Manual</th>
                                        <th class="text-end d-none d-lg-table-cell">Direct</th>
                                        <th class="d-none d-lg-table-cell">Share</th>
                                        <th class="d-none d-xl-table-cell">Last Activity</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $maxLeads = $staffLeadStats->max('total_leads') ?: 1; @endphp
                                    @foreach ($staffLeadStats as $row)
                                        @php
                                            $initials = collect(explode(' ', $row['user']->name))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');
                                            $pct = round(($row['total_leads'] / $maxLeads) * 100);
                                            $roleColors = ['admin' => '#dc3545', 'supervisor' => '#0d6efd', 'staff' => '#198754'];
                                            $role = $row['user']->roles->first()?->name ?? 'N/A';
                                            $roleColor = $roleColors[$role] ?? '#6c757d';
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="user-avatar flex-shrink-0" style="background:{{ $roleColor }}20;color:{{ $roleColor }};">
                                                        {{ $initials }}
                                                    </span>
                                                    <div>
                                                        <div class="fw-semibold text-sm" style="line-height:1.2;">{{ $row['user']->name }}</div>
                                                        <div class="text-muted" style="font-size:0.75rem;">{{ $row['user']->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge fw-semibold" style="background:{{ $roleColor }}20;color:{{ $roleColor }};border-radius:6px;">{{ $role }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-bold">{{ number_format($row['total_leads']) }}</span>
                                            </td>
                                            <td class="text-end d-none d-md-table-cell">
                                                <span class="badge bg-info bg-opacity-15 text-info fw-semibold">{{ number_format($row['imported_leads']) }}</span>
                                            </td>
                                            <td class="text-end d-none d-md-table-cell">
                                                <span class="badge bg-warning bg-opacity-15 text-warning fw-semibold">{{ number_format($row['manual_leads']) }}</span>
                                            </td>
                                            <td class="text-end d-none d-lg-table-cell">
                                                <span class="badge bg-secondary bg-opacity-15 text-secondary fw-semibold">{{ number_format($row['direct_leads']) }}</span>
                                            </td>
                                            <td class="d-none d-lg-table-cell" style="min-width:120px;">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="flex-grow-1 bg-light rounded" style="height:6px;overflow:hidden;">
                                                        <div class="bg-primary" style="width:{{ $pct }}%;height:6px;border-radius:3px;transition:width 0.4s;"></div>
                                                    </div>
                                                    <small class="text-muted" style="min-width:32px;">{{ $pct }}%</small>
                                                </div>
                                            </td>
                                            <td class="d-none d-xl-table-cell">
                                                @if ($row['last_activity_at'])
                                                    <span class="text-muted" style="font-size:0.8rem;" title="{{ $row['last_activity_at']->format('M d, Y H:i') }}">
                                                        <i class="fa fa-clock me-1 opacity-50"></i>{{ $row['last_activity_at']->diffForHumans() }}
                                                    </span>
                                                @else
                                                    <span class="text-muted" style="font-size:0.8rem;">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ orgRoute('admin.crm.user-activity', $row['user_id']) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   style="border-radius:8px;"
                                                   title="View leads for {{ $row['user']->name }}">
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const periodInput = document.getElementById('periodInput');
                const fromWrap = document.getElementById('dateFromWrap');
                const toWrap = document.getElementById('dateToWrap');
                const customBtn = document.getElementById('customPeriodBtn');

                if (customBtn) {
                    customBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        periodInput.value = 'custom';
                        fromWrap.style.display = '';
                        toWrap.style.display = '';
                        customBtn.classList.add('active', 'btn-primary');
                        customBtn.classList.remove('btn-outline-secondary');
                    });
                }

                // Select2 for multi-selects
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $('#drives').select2({ placeholder: 'All Pipelines', allowClear: true, width: '100%' });
                    $('#users').select2({ placeholder: 'All Users', allowClear: true, width: '100%' });
                }
            });
        </script>
    @endpush
</x-app-layout>
