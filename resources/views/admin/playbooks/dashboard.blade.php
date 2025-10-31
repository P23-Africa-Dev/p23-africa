<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Playbooks Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Dashboard Header -->
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Playbook Management Center</h3>
                        <p class="text-gray-600">Manage and monitor all playbook downloads from the P23 Africa platform.
                        </p>
                    </div>

                    <!-- Statistics Overview -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="card bg-gradient-primary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-0">Total Downloads</h5>
                                            <h2 class="mt-2 mb-0">{{ $totalDownloads }}</h2>
                                            <small class="opacity-75">Across all playbooks</small>
                                        </div>
                                        <div class="text-end">
                                            <i class="fas fa-download fa-3x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-gradient-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-0">This Month</h5>
                                            <h2 class="mt-2 mb-0">{{ $monthlyDownloads }}</h2>
                                            <small class="opacity-75">New downloads this month</small>
                                        </div>
                                        <div class="text-end">
                                            <i class="fas fa-calendar-alt fa-3x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Playbook Cards -->
                    <div class="row">
                        <!-- P23 Africa's Founders Playbook -->
                        <div class="col-lg-6 mb-4">
                            <div class="card playbook-card h-100">
                                <div class="card-header bg-primary text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-book me-2"></i>P23 Africa's Founders Playbook
                                        </h5>
                                        <span class="badge bg-light text-primary">{{ $foundersPlaybookCount }}
                                            downloads</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted mb-4">
                                        Comprehensive guide to building and scaling successful businesses across Africa.
                                        Includes expert insights, proven strategies, and actionable advice.
                                    </p>

                                    <div class="stats-grid mb-4">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $foundersPlaybookCount }}</div>
                                            <div class="stat-label">Total Downloads</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $foundersPlaybookWeek }}</div>
                                            <div class="stat-label">This Week</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $foundersPlaybookMonth }}</div>
                                            <div class="stat-label">This Month</div>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <a href="{{ route('admin.playbooks.index') }}" class="btn btn-primary">
                                            <i class="fas fa-eye me-2"></i>View Download Records
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer text-muted">
                                    <small>
                                        <i class="fas fa-clock me-1"></i>
                                        Last download:
                                        {{ $foundersPlaybookLast ? $foundersPlaybookLast->created_at->diffForHumans() : 'No downloads yet' }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Funding Readiness Guidebook -->
                        <div class="col-lg-6 mb-4">
                            <div class="card playbook-card h-100">
                                <div class="card-header bg-success text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-money-bill-wave me-2"></i>Funding Readiness Guidebook
                                        </h5>
                                        <span class="badge bg-light text-success">{{ $salesBookCount }} downloads</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted mb-4">
                                        Step-by-step guide to becoming investor-ready, built specifically for African
                                        entrepreneurs
                                        seeking funding or sustainable growth.
                                    </p>

                                    <div class="stats-grid mb-4">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $salesBookCount }}</div>
                                            <div class="stat-label">Total Downloads</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $salesBookWeek }}</div>
                                            <div class="stat-label">This Week</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $salesBookMonth }}</div>
                                            <div class="stat-label">This Month</div>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <a href="{{ route('admin.salesbooks.index') }}" class="btn btn-success">
                                            <i class="fas fa-eye me-2"></i>View Download Records
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer text-muted">
                                    <small>
                                        <i class="fas fa-clock me-1"></i>
                                        Last download:
                                        {{ $salesBookLast ? $salesBookLast->created_at->diffForHumans() : 'No downloads yet' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    @if ($recentDownloads->count() > 0)
                        <div class="card mt-5">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-history me-2"></i>Recent Download Activity
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Playbook</th>
                                                <th>Downloaded</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recentDownloads as $download)
                                                <tr>
                                                    <td>{{ $download->name }}</td>
                                                    <td>{{ $download->email }}</td>
                                                    <td>
                                                        @if ($download instanceof \App\Models\PlaybookDownload)
                                                            <span class="badge bg-primary">Founders Playbook</span>
                                                        @else
                                                            <span class="badge bg-success">Funding Guide</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $download->created_at->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .playbook-card {
            transition: transform 0.2s ease-in-out;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .playbook-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            text-align: center;
        }

        .stat-item {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #495057;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card-footer {
            background-color: rgba(0, 0, 0, 0.03);
            border-top: 1px solid rgba(0, 0, 0, 0.125);
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

</x-app-layout>
