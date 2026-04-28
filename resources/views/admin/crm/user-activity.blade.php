<x-app-layout>
    @push('styles')
        <style>
            .ua-stat-card {
                border-radius: 12px;
                transition: transform 0.15s ease, box-shadow 0.15s ease;
            }
            .ua-stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0,0,0,0.10) !important;
            }
            .ua-stat-card .stat-icon {
                width: 44px;
                height: 44px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.15rem;
            }
            .ua-section-card {
                border-radius: 12px;
                border: 1px solid rgba(0,0,0,0.07);
            }
            .ua-section-card .card-header {
                border-radius: 12px 12px 0 0;
                border-bottom: 1px solid rgba(0,0,0,0.06);
            }
            .ua-table tbody tr { transition: background 0.1s; }
            .ua-table tbody tr:hover { background: rgba(13,110,253,0.03); }
            .lead-type-badge {
                font-size: 0.72rem;
                font-weight: 600;
                padding: 0.28em 0.6em;
                border-radius: 5px;
                letter-spacing: 0.02em;
            }
            .dark .ua-section-card { border-color: rgba(255,255,255,0.08); }
            .dark .ua-section-card .card-header { border-color: rgba(255,255,255,0.08); }
        </style>
    @endpush

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                {{-- User Avatar --}}
                @php
                    $initials = collect(explode(' ', $targetUser->name))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');
                    $role = $targetUser->roles->first()?->name ?? 'N/A';
                    $roleColors = ['admin' => '#dc3545', 'supervisor' => '#0d6efd', 'staff' => '#198754'];
                    $roleColor = $roleColors[$role] ?? '#6c757d';
                @endphp
                <div style="width:48px;height:48px;border-radius:12px;background:{{ $roleColor }}20;color:{{ $roleColor }};display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;flex-shrink:0;">
                    {{ $initials }}
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight mb-0">
                        {{ $targetUser->name }}
                    </h2>
                    <p class="text-muted dark:text-gray-400 text-sm mb-0">
                        {{ $targetUser->email }}
                        <span class="badge ms-1 fw-semibold" style="background:{{ $roleColor }}20;color:{{ $roleColor }};border-radius:5px;font-size:0.72rem;">{{ $role }}</span>
                    </p>
                </div>
            </div>
            <a href="{{ orgRoute('admin.crm.import-activity') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-2"></i>Back to Analytics
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ── Filters ── --}}
            <div class="card ua-section-card shadow-sm mb-4">
                <div class="card-header bg-white dark:bg-gray-800 py-3">
                    <span class="fw-semibold text-sm"><i class="fa fa-sliders-h text-primary me-2"></i>Filters</span>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ orgRoute('admin.crm.user-activity', $targetUser->id) }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label fw-semibold small text-muted mb-1">From</label>
                                <input type="date" name="date_from" class="form-control form-control-sm rounded-3"
                                       value="{{ request('date_from', $filters['date_from']) }}">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label fw-semibold small text-muted mb-1">To</label>
                                <input type="date" name="date_to" class="form-control form-control-sm rounded-3"
                                       value="{{ request('date_to', $filters['date_to']) }}">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label fw-semibold small text-muted mb-1">
                                    <i class="fa fa-folder me-1"></i>Pipeline
                                </label>
                                <select name="drive_ids[]" class="form-select form-select-sm rounded-3 ua-drives-select" multiple>
                                    @foreach ($drives as $drive)
                                        <option value="{{ $drive->id }}"
                                            {{ in_array($drive->id, $filters['drive_ids'] ?? []) ? 'selected' : '' }}>
                                            {{ $drive->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-2">
                                <label class="form-label fw-semibold small text-muted mb-1">
                                    <i class="fa fa-tag me-1"></i>Type
                                </label>
                                <select name="lead_type" class="form-select form-select-sm rounded-3">
                                    <option value="all"      {{ ($filters['lead_type'] ?? 'all') === 'all'      ? 'selected' : '' }}>All Types</option>
                                    <option value="imported" {{ ($filters['lead_type'] ?? 'all') === 'imported' ? 'selected' : '' }}>Imported</option>
                                    <option value="manual"   {{ ($filters['lead_type'] ?? 'all') === 'manual'   ? 'selected' : '' }}>Manual</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-1 d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm flex-grow-1" title="Apply">
                                    <i class="fa fa-check"></i>
                                </button>
                                <a href="{{ orgRoute('admin.crm.user-activity', $targetUser->id) }}" class="btn btn-outline-secondary btn-sm" title="Clear">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── Stats ── --}}
            @php
                $imported = $leads->getCollection()->where('source_type', 'file_import')->count();
                $manual   = $leads->getCollection()->where('source_type', '!=', 'file_import')->count();
            @endphp
            <div class="row g-3 mb-5">
                {{-- Hero Stat: Total Leads --}}
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="card ua-stat-card border-0 shadow flex-fill h-100" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4 position-relative overflow-hidden">
                            <i class="fa fa-layer-group position-absolute opacity-10" style="font-size: 8rem; right: -20px; bottom: -20px;"></i>
                            <div class="text-uppercase fw-bold mb-1" style="letter-spacing: 0.05em; font-size: 0.95rem; opacity: 0.9; z-index: 1;">Total Leads</div>
                            <div class="fw-bolder" style="font-size: 4rem; line-height: 1; z-index: 1;">{{ number_format($leads->total()) }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="row g-3 h-100">
                        <div class="col-6">
                            <div class="card ua-stat-card border-0 shadow-sm h-100">
                                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                                    <div class="stat-icon flex-shrink-0 rounded-circle" style="background:#f8f9fa; width: 44px; height: 44px; font-size: 1.1rem;">
                                        <i class="fa fa-file-import" style="color:#6c757d;"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted" style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;">Imported</div>
                                        <div class="text-dark" style="font-size:1.6rem;font-weight:700;line-height:1.1;">{{ number_format($imported) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card ua-stat-card border-0 shadow-sm h-100">
                                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                                    <div class="stat-icon flex-shrink-0 rounded-circle" style="background:#f8f9fa; width: 44px; height: 44px; font-size: 1.1rem;">
                                        <i class="fa fa-pen-alt" style="color:#6c757d;"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted" style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;">Manual</div>
                                        <div class="text-dark" style="font-size:1.6rem;font-weight:700;line-height:1.1;">{{ number_format($manual) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Leads Table ── --}}
            <div class="card ua-section-card shadow-sm">
                <div class="card-header bg-white dark:bg-gray-800 d-flex justify-content-between align-items-center py-3">
                    <span class="fw-semibold">
                        <i class="fa fa-table text-primary me-2"></i>Leads Created
                    </span>
                    <span class="badge bg-light text-muted border" style="font-size:0.78rem;">
                        {{ \Carbon\Carbon::parse($filters['date_from'])->format('M d') }} — {{ \Carbon\Carbon::parse($filters['date_to'])->format('M d, Y') }}
                    </span>
                </div>
                <div class="card-body p-0">
                    @if ($leads->isEmpty())
                        <div class="text-center py-5">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                                <i class="fa fa-folder-open fa-2x text-muted opacity-50"></i>
                            </div>
                            <h5 class="fw-bold text-dark">No Leads Found</h5>
                            <p class="text-muted mb-4 max-w-md mx-auto">We couldn't find any leads matching your current filters. Try adjusting your dates, pipelines, or types to see some data.</p>
                            @php
                                $hasFilters = !empty($filters['drive_ids']) || ($filters['lead_type'] ?? 'all') !== 'all' || 
                                              request()->filled('date_from') || request()->filled('date_to');
                            @endphp
                            @if($hasFilters)
                                <a href="{{ orgRoute('admin.crm.user-activity', $targetUser->id) }}" class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="fa fa-refresh me-2"></i>Clear Filters
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table ua-table mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Lead</th>
                                        <th class="d-none d-md-table-cell">Company</th>
                                        <th>Pipeline</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th class="text-end d-none d-lg-table-cell">Value</th>
                                        <th class="d-none d-xl-table-cell">Created</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leads as $lead)
                                        <tr>
                                            <td style="min-width:180px;">
                                                <div class="fw-semibold" style="line-height:1.2;">{{ $lead->first_name }} {{ $lead->last_name }}</div>
                                                <div style="font-size:0.76rem;color:#6c757d;">{{ $lead->email }}</div>
                                            </td>
                                            <td class="d-none d-md-table-cell text-muted" style="font-size:0.85rem;">
                                                {{ $lead->company ?? '—' }}
                                            </td>
                                            <td>
                                                @if ($lead->drive)
                                                    <span class="badge fw-semibold" style="background-color:{{ $lead->drive->color ?? '#6c757d' }};font-size:0.75rem;border-radius:6px;padding:0.32em 0.65em;">
                                                        {{ $lead->drive->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted" style="font-size:0.82rem;">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($lead->source_type === 'file_import')
                                                    <span class="lead-type-badge" style="background:#E3F8FC;color:#0dcaf0;">Imported</span>
                                                @else
                                                    <span class="lead-type-badge" style="background:#FFF3E8;color:#fd7e14;">Manual</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light border text-dark fw-normal" style="font-size:0.75rem;border-radius:5px;">{{ $lead->status }}</span>
                                            </td>
                                            <td class="text-end d-none d-lg-table-cell">
                                                @if ($lead->estimated_value)
                                                    <span class="fw-semibold">{{ number_format($lead->estimated_value, 0) }}</span>
                                                    <small class="text-muted ms-1">{{ $lead->currency }}</small>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="d-none d-xl-table-cell">
                                                <span class="text-muted" style="font-size:0.8rem;" title="{{ $lead->created_at->format('M d, Y H:i') }}">
                                                    {{ $lead->created_at->format('M d, Y') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ orgRoute('admin.crm.show', $lead->id) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   style="border-radius:8px;"
                                                   title="View lead">
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
                @if ($leads->hasPages())
                    <div class="card-footer bg-white dark:bg-gray-800 border-top px-4 py-3">
                        {{ $leads->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $('.ua-drives-select').select2({ placeholder: 'All Pipelines', allowClear: true, width: '100%' });
                }
            });
        </script>
    @endpush
</x-app-layout>
