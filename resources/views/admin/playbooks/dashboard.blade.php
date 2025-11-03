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
                        <div class="col-md-4">
                            <div class="card bg-gradient-primary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-0">Total Downloads</h5>
                                            <h2 class="mt-2 mb-0">{{ $stats['total_downloads'] }}</h2>
                                            <small class="opacity-75">Across all playbooks</small>
                                        </div>
                                        <div class="text-end">
                                            <i class="fas fa-download fa-3x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-gradient-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-0">Premium Purchases</h5>
                                            <h2 class="mt-2 mb-0">{{ $stats['ultimate_funding_paid'] }}</h2>
                                            <small class="opacity-75">Paid Ultimate Funding access</small>
                                        </div>
                                        <div class="text-end">
                                            <i class="fas fa-crown fa-3x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-gradient-warning text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-0">Total Revenue</h5>
                                            <h2 class="mt-2 mb-0">₦{{ number_format($stats['total_revenue'], 2) }}</h2>
                                            <small class="opacity-75">From premium downloads</small>
                                        </div>
                                        <div class="text-end">
                                            <i class="fas fa-naira-sign fa-3x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Playbook Cards -->
                    <div class="row">
                        <!-- P23 Africa's Founders Playbook -->
                        <div class="col-lg-4 mb-4">
                            <div class="card playbook-card h-100">
                                <div class="card-header bg-primary text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-book me-2"></i>Founders Playbook
                                        </h5>
                                        <span class="badge bg-light text-primary">{{ $stats['playbook_downloads'] }}
                                            downloads</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted mb-4">
                                        Comprehensive guide to building and scaling successful businesses across Africa.
                                        Includes expert insights, proven strategies, and actionable advice.
                                    </p>

                                    <div class="stats-summary mb-4">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $stats['playbook_downloads'] }}</div>
                                            <div class="stat-label">Total Downloads</div>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <a href="{{ route('admin.playbooks.index') }}" class="btn btn-primary"
                                            style="color: #fff !important;">
                                            <i class="fas fa-eye me-2"></i>View Download Records
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer text-muted">
                                    <small>
                                        <i class="fas fa-clock me-1"></i>
                                        Last download:
                                        @if ($recentDownloads['playbook']->count() > 0)
                                            {{ $recentDownloads['playbook']->first()->created_at->diffForHumans() }}
                                        @else
                                            No downloads yet
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Funding Readiness Guidebook -->
                        <div class="col-lg-4 mb-4">
                            <div class="card playbook-card h-100">
                                <div class="card-header bg-success text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-money-bill-wave me-2"></i>Funding Readiness Guide
                                        </h5>
                                        <span class="badge bg-light text-success">{{ $stats['sales_book_downloads'] }}
                                            downloads</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted mb-4">
                                        Step-by-step guide to becoming investor-ready, built specifically for African
                                        entrepreneurs seeking funding or sustainable growth.
                                    </p>

                                    <div class="stats-summary mb-4">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $stats['sales_book_downloads'] }}</div>
                                            <div class="stat-label">Total Downloads</div>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <a href="{{ route('admin.salesbooks.index') }}" class="btn btn-success"
                                            style="color: #fff !important;">
                                            <i class="fas fa-eye me-2"></i>View Download Records
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer text-muted">
                                    <small>
                                        <i class="fas fa-clock me-1"></i>
                                        Last download:
                                        @if ($recentDownloads['sales_book']->count() > 0)
                                            {{ $recentDownloads['sales_book']->first()->created_at->diffForHumans() }}
                                        @else
                                            No downloads yet
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Ultimate Funding & Investor Directory (Premium) -->
                        <div class="col-lg-4 mb-4">
                            <div class="card playbook-card h-100">
                                <div class="card-header bg-warning text-dark">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-crown me-2"></i>Ultimate Funding Directory
                                        </h5>
                                        <span class="badge bg-dark">{{ $stats['ultimate_funding_downloads'] }}
                                            signups</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted mb-3">
                                        Premium directory with 500+ verified investors and funding opportunities
                                        across Africa. Includes direct contact information and investment criteria.
                                    </p>

                                    <div class="premium-stats mb-4">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="stat-value text-warning">
                                                    {{ $stats['ultimate_funding_downloads'] }}</div>
                                                <div class="stat-label">Total Signups</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="stat-value text-success">
                                                    {{ $stats['ultimate_funding_paid'] }}</div>
                                                <div class="stat-label">Paid Access</div>
                                            </div>
                                        </div>
                                        <div class="row text-center mt-2">
                                            <div class="col-12">
                                                <div class="stat-value text-primary">
                                                    ₦{{ number_format($stats['total_revenue'], 2) }}</div>
                                                <div class="stat-label">Total Revenue</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <a href="{{ route('admin.ultimatefunding.index') }}" class="btn btn-warning"
                                            style="color: #fff !important;">
                                            <i class="fas fa-eye me-2"></i>View Premium Records
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer text-muted">
                                    <small>
                                        <i class="fas fa-clock me-1"></i>
                                        Last signup:
                                        @if ($recentDownloads['ultimate_funding']->count() > 0)
                                            {{ $recentDownloads['ultimate_funding']->first()->created_at->diffForHumans() }}
                                        @else
                                            No signups yet
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    @if (
                        $recentDownloads['playbook']->count() > 0 ||
                            $recentDownloads['sales_book']->count() > 0 ||
                            $recentDownloads['ultimate_funding']->count() > 0)
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
                                                <th>Status</th>
                                                <th>Downloaded</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recentDownloads['playbook'] as $download)
                                                <tr>
                                                    <td>{{ $download->name }}</td>
                                                    <td>{{ $download->email }}</td>
                                                    <td><span class="badge bg-primary">Founders Playbook</span></td>
                                                    <td><span class="badge bg-success">Downloaded</span></td>
                                                    <td>{{ $download->created_at->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach
                                            @foreach ($recentDownloads['sales_book'] as $download)
                                                <tr>
                                                    <td>{{ $download->name }}</td>
                                                    <td>{{ $download->email }}</td>
                                                    <td><span class="badge bg-success">Funding Guide</span></td>
                                                    <td><span class="badge bg-success">Downloaded</span></td>
                                                    <td>{{ $download->created_at->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach
                                            @foreach ($recentDownloads['ultimate_funding'] as $download)
                                                <tr>
                                                    <td>{{ $download->name }}</td>
                                                    <td>{{ $download->email }}</td>
                                                    <td><span class="badge bg-warning text-dark">Ultimate Funding
                                                            Directory</span></td>
                                                    <td>
                                                        @if ($download->payment_completed)
                                                            <span class="badge bg-success">Paid & Downloaded</span>
                                                        @else
                                                            <span class="badge bg-secondary">Pending Payment</span>
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

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

        .stats-summary {
            text-align: center;
            padding: 1rem;
            background: #495057;
            border-radius: 0.5rem;
        }

        .premium-stats {
            background: linear-gradient(135deg, #798188 0%, #495057 100%);
            padding: 1rem;
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
