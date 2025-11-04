<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ultimate Funding & Investor Directory Downloads') }}
            </h2>
            <a href="{{ route('admin.playbooks.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Playbooks Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 table-responsive">

                    <!-- Statistics Card -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">{{ $ultimateFunding->total() }}</h4>
                                            <p class="card-text">Total Signups</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-users fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">
                                                {{ $ultimateFunding->where('payment_completed', true)->count() }}</h4>
                                            <p class="card-text">Paid Access</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-crown fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">
                                                ${{ number_format($ultimateFunding->where('payment_completed', true)->sum('amount_paid'), 2) }}
                                            </h4>
                                            <p class="card-text">Total Revenue</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-dollar-sign fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">
                                                {{ $ultimateFunding->where('created_at', '>=', now()->subDays(7))->count() }}
                                            </h4>
                                            <p class="card-text">This Week</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-calendar-week fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-5">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($ultimateFunding->count() > 0)
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-list me-2"></i>Download Records
                                        <span class="badge bg-warning ms-2">{{ $ultimateFunding->total() }} total</span>
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="">
                                                <tr>
                                                    <th>#</th>
                                                    <th><i class="fas fa-user me-1"></i>Name</th>
                                                    <th><i class="fas fa-envelope me-1"></i>Email</th>
                                                    <th><i class="fas fa-building me-1"></i>Company</th>
                                                    <th><i class="fas fa-globe me-1"></i>Country</th>
                                                    {{-- <th><i class="fas fa-rocket me-1"></i>Funding Stage</th> --}}
                                                    <th><i class="fas fa-credit-card me-1"></i>Payment Status</th>
                                                    <th><i class="fas fa-calendar me-1"></i>Signup Date</th>
                                                    <th><i class="fas fa-cog me-1"></i>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ultimateFunding as $index => $funding)
                                                    <tr>
                                                        <td>{{ ($ultimateFunding->currentPage() - 1) * $ultimateFunding->perPage() + $index + 1 }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-circle me-2">
                                                                    {{ strtoupper(substr($funding->name, 0, 1)) }}
                                                                </div>
                                                                <strong>{{ $funding->name }}</strong>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="mailto:{{ $funding->email }}"
                                                                class="text-decoration-none">
                                                                {{ $funding->email }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted">{{ $funding->company }}</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-light text-dark">{{ $funding->country }}</span>
                                                        </td>
                                                        {{-- <td>
                                                            <span
                                                                class="badge bg-info">{{ $funding->funding_stage }}</span>
                                                        </td> --}}
                                                        <td>
                                                            @if ($funding->payment_completed)
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check me-1"></i>Paid
                                                                </span>
                                                                <small class="d-block text-muted mt-1">
                                                                    ${{ number_format($funding->amount_paid, 2) }}
                                                                </small>
                                                                @if ($funding->payment_date)
                                                                    <small class="d-block text-muted">
                                                                        {{ $funding->payment_date->format('M j, Y') }}
                                                                    </small>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-warning text-dark">
                                                                    <i class="fas fa-clock me-1"></i>Pending Payment
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="text-muted">
                                                                {{ $funding->created_at->format('M j, Y') }}
                                                            </span>
                                                            <small class="d-block text-muted">
                                                                {{ $funding->created_at->format('g:i A') }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <form
                                                                    action="{{ route('admin.ultimatefunding.destroy', $funding) }}"
                                                                    method="POST" class="d-inline"
                                                                    onsubmit="return confirm('Are you sure you want to delete this download record? This action cannot be undone.');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-outline-danger"
                                                                        title="Delete Record">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-crown fa-4x text-muted"></i>
                                </div>
                                <h4 class="text-muted">No Signups Yet</h4>
                                <p class="text-muted">No one has signed up for the Ultimate Funding Directory yet.</p>
                            </div>
                        @endif
                    </div>

                    @if ($ultimateFunding->hasPages())
                        <div class="d-flex justify-content-center my-4">
                            {{ $ultimateFunding->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(255, 193, 7, 0.05);
        }

        .btn-group .btn {
            margin-right: 5px;
        }

        .badge {
            font-size: 0.75em;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }
    </style>

</x-app-layout>
