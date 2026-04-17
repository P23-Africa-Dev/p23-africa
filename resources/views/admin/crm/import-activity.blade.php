<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="font-semibold text-xl text-dark leading-tight mb-1">Lead Import Activity</h2>
                <p class="text-muted text-sm mb-0">Track leads created by staff members in your organization.</p>
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

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ orgRoute('admin.crm.import-activity') }}" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Date Range</label>
                            <select name="period" id="period" class="form-select">
                                <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Today</option>
                                <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>This Week</option>
                                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>This Month</option>
                                <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="dateFromWrap" style="{{ $period === 'custom' ? '' : 'display:none;' }}">
                            <label class="form-label fw-semibold">From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from', $dateFrom) }}">
                        </div>
                        <div class="col-md-3" id="dateToWrap" style="{{ $period === 'custom' ? '' : 'display:none;' }}">
                            <label class="form-label fw-semibold">To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to', $dateTo) }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-filter me-2"></i>Apply Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Staff Members</h6>
                            <h3 class="mb-0">{{ number_format($summary['staff_count']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Total Leads</h6>
                            <h3 class="mb-0">{{ number_format($summary['total_leads']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Imported Leads</h6>
                            <h3 class="mb-0 text-success">{{ number_format($summary['imported_leads']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Manual/Converted</h6>
                            <h3 class="mb-0 text-info">{{ number_format($summary['manual_leads']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fa fa-users me-2"></i>Per-Staff Lead Activity</h5>
                    <small class="text-muted">{{ $dateFrom }} to {{ $dateTo }}</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Staff User</th>
                                    <th>Email</th>
                                    <th class="text-end">Total Leads</th>
                                    <th class="text-end">Imported</th>
                                    <th class="text-end">Manual/Converted</th>
                                    <th>Last Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($staffLeadStats as $row)
                                    <tr>
                                        <td class="fw-semibold">{{ $row['user']->name }}</td>
                                        <td>{{ $row['user']->email }}</td>
                                        <td class="text-end fw-bold">{{ number_format($row['total_leads']) }}</td>
                                        <td class="text-end text-success">{{ number_format($row['imported_leads']) }}</td>
                                        <td class="text-end text-info">{{ number_format($row['manual_leads']) }}</td>
                                        <td>
                                            @if ($row['last_activity_at'])
                                                {{ $row['last_activity_at']->format('M d, Y h:i A') }}
                                            @else
                                                <span class="text-muted">No leads in selected range</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No staff users found for this organization.</td>
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
                const period = document.getElementById('period');
                const fromWrap = document.getElementById('dateFromWrap');
                const toWrap = document.getElementById('dateToWrap');

                function toggleCustomDateFields() {
                    const isCustom = period.value === 'custom';
                    fromWrap.style.display = isCustom ? '' : 'none';
                    toWrap.style.display = isCustom ? '' : 'none';
                }

                period.addEventListener('change', toggleCustomDateFields);
                toggleCustomDateFields();
            });
        </script>
    @endpush
</x-app-layout>
