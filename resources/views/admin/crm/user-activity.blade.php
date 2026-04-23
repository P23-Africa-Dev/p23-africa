<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="font-semibold text-xl text-dark leading-tight mb-1">Lead Activity - {{ $targetUser->name }}</h2>
                <p class="text-muted text-sm mb-0">{{ $targetUser->email }} • Role: {{ $targetUser->roles->first()?->name ?? 'N/A' }}</p>
            </div>
            <a href="{{ orgRoute('admin.crm.import-activity') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-2"></i>Back to Analytics
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0"><i class="fa fa-filter me-2"></i>Filters</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ orgRoute('admin.crm.user-activity', $targetUser->id) }}" class="row g-3 align-items-end">
                        <!-- Date Range -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">From</label>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from', $filters['date_from']) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">To</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to', $filters['date_to']) }}">
                        </div>

                        <!-- Pipeline Filter -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Pipeline</label>
                            <select name="drive_ids[]" class="form-select form-select-sm" multiple data-placeholder="All Pipelines">
                                @foreach ($drives as $drive)
                                    <option value="{{ $drive->id }}" 
                                        {{ in_array($drive->id, $filters['drive_ids'] ?? []) ? 'selected' : '' }}>
                                        {{ $drive->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Lead Type Filter -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">Type</label>
                            <select name="lead_type" class="form-select form-select-sm">
                                <option value="all" {{ ($filters['lead_type'] ?? 'all') === 'all' ? 'selected' : '' }}>All Types</option>
                                <option value="imported" {{ ($filters['lead_type'] ?? 'all') === 'imported' ? 'selected' : '' }}>Imported</option>
                                <option value="manual" {{ ($filters['lead_type'] ?? 'all') === 'manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100 btn-sm">
                                <i class="fa fa-filter"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 bg-primary bg-opacity-10">
                        <div class="card-body">
                            <h6 class="text-muted mb-1"><i class="fa fa-list text-primary me-1"></i>Total Leads</h6>
                            <h3 class="mb-0 text-primary">{{ number_format($leads->total()) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 bg-info bg-opacity-10">
                        <div class="card-body">
                            <h6 class="text-muted mb-1"><i class="fa fa-download text-info me-1"></i>From Imports</h6>
                            <h3 class="mb-0 text-info">{{ number_format($leads->getCollection()->where('source_type', 'file_import')->count()) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 bg-warning bg-opacity-10">
                        <div class="card-body">
                            <h6 class="text-muted mb-1"><i class="fa fa-pen text-warning me-1"></i>Manual Entries</h6>
                            <h3 class="mb-0 text-warning">{{ number_format($leads->getCollection()->where('source_type', '!=', 'file_import')->count()) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leads Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0"><i class="fa fa-table me-2"></i>Leads Created</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Lead Name</th>
                                    <th>Email</th>
                                    <th>Company</th>
                                    <th>Pipeline</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Value</th>
                                    <th>Created At</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leads as $lead)
                                    <tr>
                                        <td class="fw-semibold">{{ $lead->first_name }} {{ $lead->last_name }}</td>
                                        <td><small>{{ $lead->email }}</small></td>
                                        <td>{{ $lead->company ?? '-' }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $lead->drive?->color ?? '#6c757d' }}">
                                                {{ $lead->drive?->name ?? 'Unassigned' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($lead->source_type === 'file_import')
                                                <span class="badge bg-info">Imported</span>
                                            @else
                                                <span class="badge bg-warning">Manual</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $lead->status }}</span>
                                        </td>
                                        <td>
                                            @if ($lead->estimated_value)
                                                <strong>{{ number_format($lead->estimated_value, 2) }} {{ $lead->currency }}</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td><small class="text-muted" title="{{ $lead->created_at->format('M d, Y h:i A') }}">{{ $lead->created_at->format('M d, Y') }}</small></td>
                                        <td class="text-center">
                                            <a href="{{ orgRoute('admin.crm.show', $lead->id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="View lead">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            <i class="fa fa-inbox text-muted me-2"></i>No leads found in the selected filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light border-top">
                    {{ $leads->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Select2 if available
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $('select[multiple]').select2({
                        placeholder: 'Select options...',
                        allowClear: true,
                        width: '100%'
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
