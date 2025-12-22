<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Lead Pipeline') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Visual pipeline view of your leads</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.crm.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list me-2"></i>List View
                </a>
                <a href="{{ route('admin.crm.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-2"></i>Add New Lead
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .pipeline-container {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            padding-bottom: 20px;
            min-height: calc(100vh - 250px);
        }

        .pipeline-column {
            min-width: 280px;
            max-width: 300px;
            flex-shrink: 0;
        }

        .pipeline-header {
            padding: 12px 16px;
            border-radius: 10px 10px 0 0;
            color: white;
            font-weight: 600;
        }

        .pipeline-header.new {
            background: #17a2b8;
        }

        .pipeline-header.contacted {
            background: #007bff;
        }

        .pipeline-header.qualified {
            background: #ffc107;
            color: #212529;
        }

        .pipeline-header.proposal {
            background: #6c757d;
        }

        .pipeline-header.negotiation {
            background: #343a40;
        }

        .pipeline-header.won {
            background: #28a745;
        }

        .pipeline-header.lost {
            background: #dc3545;
        }

        .pipeline-body {
            background: #f8f9fa;
            border-radius: 0 0 10px 10px;
            padding: 12px;
            min-height: 400px;
        }

        .dark .pipeline-body {
            background: #374151;
        }

        .lead-card {
            background: white;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
            cursor: pointer;
        }

        .dark .lead-card {
            background: #1f2937;
        }

        .lead-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .lead-card .lead-name {
            font-weight: 600;
            color: #212529;
            margin-bottom: 4px;
        }

        .dark .lead-card .lead-name {
            color: #f3f4f6;
        }

        .lead-card .lead-company {
            color: #007bff;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .lead-card .lead-value {
            color: #28a745;
            font-weight: 600;
        }

        .lead-card .lead-meta {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .pipeline-stats {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <!-- Pipeline Summary -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3">
                                    <i class="fa fa-users text-primary fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Total in Pipeline</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        {{ collect($pipeline)->sum('count') }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                    <i class="fa fa-dollar-sign text-success fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Total Pipeline Value</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        ${{ number_format(collect($pipeline)->sum('value')) }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                    <i class="fa fa-trophy text-success fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Won Value</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        ${{ number_format($pipeline['won']['value'] ?? 0) }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-info bg-opacity-10 rounded p-3">
                                    <i class="fa fa-percentage text-info fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Win Rate</h6>
                                    @php
                                        $total = ($pipeline['won']['count'] ?? 0) + ($pipeline['lost']['count'] ?? 0);
                                        $winRate =
                                            $total > 0 ? round((($pipeline['won']['count'] ?? 0) / $total) * 100) : 0;
                                    @endphp
                                    <h3 class="mb-0 text-dark dark:text-gray-100">{{ $winRate }}%</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pipeline Board -->
            <div class="pipeline-container">
                @foreach ($statuses as $statusKey => $statusLabel)
                    @php
                        $columnData = $pipeline[$statusKey] ?? ['leads' => collect(), 'count' => 0, 'value' => 0];
                    @endphp
                    <div class="pipeline-column">
                        <div class="pipeline-header {{ $statusKey }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $statusLabel }}</span>
                                <span class="badge bg-white text-dark">{{ $columnData['count'] }}</span>
                            </div>
                            <div class="pipeline-stats">
                                <span>${{ number_format($columnData['value']) }}</span>
                            </div>
                        </div>
                        <div class="pipeline-body">
                            @forelse($columnData['leads'] as $lead)
                                <a href="{{ route('admin.crm.show', $lead) }}" class="text-decoration-none">
                                    <div class="lead-card">
                                        <div class="lead-name">{{ $lead->full_name }}</div>
                                        @if ($lead->company)
                                            <div class="lead-company">{{ $lead->company }}</div>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if ($lead->estimated_value)
                                                <span
                                                    class="lead-value">${{ number_format($lead->estimated_value) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                            <span
                                                class="badge bg-{{ $lead->priority_color }}">{{ $lead->priority_label }}</span>
                                        </div>
                                        <div class="lead-meta mt-2">
                                            <div class="d-flex justify-content-between">
                                                @if ($lead->assignedUser)
                                                    <span><i
                                                            class="fa fa-user me-1"></i>{{ $lead->assignedUser->name }}</span>
                                                @else
                                                    <span class="text-muted">Unassigned</span>
                                                @endif
                                                <span>{{ $lead->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if ($lead->next_follow_up)
                                                <div
                                                    class="mt-1 {{ $lead->isFollowUpOverdue() ? 'text-danger' : '' }}">
                                                    <i class="fa fa-clock me-1"></i>
                                                    {{ $lead->next_follow_up->format('M d') }}
                                                    @if ($lead->isFollowUpOverdue())
                                                        <span class="text-danger">(Overdue)</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="fa fa-inbox mb-2"></i>
                                    <p class="mb-0 small">No leads</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
