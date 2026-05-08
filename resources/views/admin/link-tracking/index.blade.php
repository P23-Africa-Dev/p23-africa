<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight mb-1">
                    Link Redirect Analytics
                </h2>
                <p class="text-muted mb-0">Track redirect clicks and performance trends for tracked links.</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ orgRoute('admin.link-tracking.export', request()->query()) }}"
                    class="btn btn-outline-success">
                    <i class="fa fa-download me-2"></i>Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="alert alert-info mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <strong>Tracking Route Pattern:</strong>
                        <span>/r/{tracking_code}</span>
                    </div>
                    <div>
                        <strong>Default Link:</strong>
                        <a href="{{ url('/r/glo-currency') }}" target="_blank"
                            rel="noopener">{{ url('/r/glo-currency') }}</a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ orgRoute('admin.link-tracking.index') }}"
                        class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="filter" class="form-label">Date Range</label>
                            <select id="filter" name="filter" class="form-select">
                                <option value="today" {{ $filter === 'today' ? 'selected' : '' }}>Today</option>
                                <option value="yesterday" {{ $filter === 'yesterday' ? 'selected' : '' }}>Yesterday
                                </option>
                                <option value="last_7_days" {{ $filter === 'last_7_days' ? 'selected' : '' }}>Last 7
                                    days</option>
                                <option value="last_30_days" {{ $filter === 'last_30_days' ? 'selected' : '' }}>Last 30
                                    days</option>
                                <option value="last_month" {{ $filter === 'last_month' ? 'selected' : '' }}>Last month
                                </option>
                                <option value="custom" {{ $filter === 'custom' ? 'selected' : '' }}>Custom range
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2 custom-date" style="{{ $filter === 'custom' ? '' : 'display:none;' }}">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" id="start_date" name="start_date"
                                value="{{ request('start_date', $startDate) }}" class="form-control">
                        </div>

                        <div class="col-md-2 custom-date" style="{{ $filter === 'custom' ? '' : 'display:none;' }}">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" id="end_date" name="end_date"
                                value="{{ request('end_date', $endDate) }}" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label for="tracking_code" class="form-label">Tracking Code</label>
                            <select id="tracking_code" name="tracking_code" class="form-select">
                                <option value="">All tracked links</option>
                                @foreach ($trackingCodes as $trackingCode)
                                    <option value="{{ $trackingCode }}"
                                        {{ $selectedTrackingCode === $trackingCode ? 'selected' : '' }}>
                                        {{ $trackingCode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="col-md-2">
                            <label for="referrer" class="form-label">Referrer</label>
                            <select id="referrer" name="referrer" class="form-select">
                                <option value="">All</option>
                                <option value="direct" {{ $selectedReferrer === 'direct' ? 'selected' : '' }}>
                                    Direct/Unknown</option>
                                @foreach ($referrerOptions as $referrerOption)
                                    <option value="{{ $referrerOption }}"
                                        {{ $selectedReferrer === $referrerOption ? 'selected' : '' }}>
                                        {{ $referrerOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="col-md-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-filter me-2"></i>Apply Filters
                            </button>
                            <a href="{{ orgRoute('admin.link-tracking.index') }}"
                                class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Total Clicks</h6>
                            <h3 class="mb-0">{{ number_format($totalClicks) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Unique Clicks</h6>
                            <h3 class="mb-0">{{ number_format($uniqueClicks) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Repeated Clicks</h6>
                            <h3 class="mb-0">{{ number_format($repeatedClicks) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Unique Rate</h6>
                            <h3 class="mb-0">{{ number_format($uniqueRate, 2) }}%</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-lg-8 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent"><strong>Daily Click Trends</strong></div>
                        <div class="card-body" style="min-height:260px;">
                            <canvas id="dailyTrendChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent"><strong>Most Active Periods</strong></div>
                        <div class="card-body">
                            @if ($topActivePeriod)
                                <p class="mb-1"><strong>Peak Hour:</strong>
                                    {{ str_pad((string) $topActivePeriod->hour_bucket, 2, '0', STR_PAD_LEFT) }}:00</p>
                                <p class="mb-3"><strong>Clicks:</strong>
                                    {{ number_format($topActivePeriod->total_clicks) }}</p>
                            @else
                                <p class="text-muted">No activity data for selected range.</p>
                            @endif

                            <div class="table-responsive" style="max-height:220px;">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Hour</th>
                                            <th class="text-end">Clicks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($hourlyTrends as $hourly)
                                            <tr>
                                                <td>{{ str_pad((string) $hourly->hour_bucket, 2, '0', STR_PAD_LEFT) }}:00
                                                </td>
                                                <td class="text-end">{{ number_format($hourly->total_clicks) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center text-muted">No data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent"><strong>Referrer Statistics</strong></div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Referrer</th>
                                        <th class="text-end">Clicks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($referrerStats as $referrerStat)
                                        <tr>
                                            <td style="max-width: 360px; overflow-wrap:anywhere;">
                                                {{ $referrerStat->referrer_source }}</td>
                                            <td class="text-end">{{ number_format($referrerStat->total_clicks) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">No referrer data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent"><strong>Tracked Links Performance</strong></div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Tracking Code</th>
                                        <th class="text-end">Clicks</th>
                                        <th class="text-end">Unique</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($linksPerformance as $link)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $link->tracking_code }}</div>
                                                <small class="text-muted">{{ $link->name }}</small>
                                            </td>
                                            <td class="text-end">{{ number_format($link->filtered_clicks_count) }}
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($link->filtered_unique_clicks_count) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No tracked links
                                                available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent"><strong>Recent Click Events</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Code</th>
                                    <th>IP</th>
                                    <th>Device</th>
                                    <th>Browser</th>
                                    <th>Referrer</th>
                                    <th>Unique</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentClicks as $click)
                                    <tr>
                                        <td>{{ $click->clicked_at?->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $click->trackedLink?->tracking_code }}</td>
                                        <td>{{ $click->ip_address ?: '-' }}</td>
                                        <td>{{ $click->device_type ?: '-' }}</td>
                                        <td>{{ trim(implode(' ', array_filter([$click->browser, $click->browser_version]))) ?: '-' }}
                                        </td>
                                        <td style="max-width:320px; overflow-wrap:anywhere;">
                                            {{ $click->referrer ?: 'Direct/Unknown' }}</td>
                                        <td>
                                            @if ($click->is_unique_click)
                                                <span class="badge bg-success">Unique</span>
                                            @else
                                                <span class="badge bg-secondary">Repeated</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No click events found
                                            for the selected filters.</td>
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
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const filter = document.getElementById('filter');
                const customDates = document.querySelectorAll('.custom-date');

                if (filter) {
                    filter.addEventListener('change', function() {
                        const showCustom = this.value === 'custom';
                        customDates.forEach((el) => {
                            el.style.display = showCustom ? '' : 'none';
                        });
                    });
                }

                const trendData = @json($dailyTrends);
                const labels = trendData.map(item => item.date_bucket);
                const total = trendData.map(item => Number(item.total_clicks || 0));
                const unique = trendData.map(item => Number(item.unique_clicks || 0));

                const canvas = document.getElementById('dailyTrendChart');
                if (canvas && window.Chart) {
                    new Chart(canvas, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                    label: 'Total Clicks',
                                    data: total,
                                    borderColor: '#0d6efd',
                                    backgroundColor: 'rgba(13, 110, 253, 0.15)',
                                    tension: 0.3,
                                    fill: true,
                                },
                                {
                                    label: 'Unique Clicks',
                                    data: unique,
                                    borderColor: '#198754',
                                    backgroundColor: 'rgba(25, 135, 84, 0.15)',
                                    tension: 0.3,
                                    fill: true,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
