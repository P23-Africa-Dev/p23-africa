<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Funding Readiness Guidebook Downloads') }}
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
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">{{ $salesBooks->total() }}</h4>
                                            <p class="card-text">Total Downloads</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-download fa-2x"></i>
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
                                                {{ $salesBooks->where('email_sent_at', '!=', null)->count() }}</h4>
                                            <p class="card-text">Emails Sent</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-envelope fa-2x"></i>
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
                                                {{ $salesBooks->where('created_at', '>=', now()->subDays(7))->count() }}
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
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">
                                                {{ $salesBooks->where('created_at', '>=', now()->subDays(30))->count() }}
                                            </h4>
                                            <p class="card-text">This Month</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-calendar-alt fa-2x"></i>
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

                        @if ($salesBooks->count() > 0)
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-list me-2"></i>Download Records
                                        <span class="badge bg-primary ms-2">{{ $salesBooks->total() }} total</span>
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
                                                    <th><i class="fas fa-phone me-1"></i>Phone</th>
                                                    <th><i class="fas fa-calendar me-1"></i>Download Date</th>
                                                    <th><i class="fas fa-paper-plane me-1"></i>Email Status</th>
                                                    <th><i class="fas fa-cog me-1"></i>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($salesBooks as $index => $salesBook)
                                                    <tr>
                                                        <td>{{ ($salesBooks->currentPage() - 1) * $salesBooks->perPage() + $index + 1 }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-circle me-2">
                                                                    {{ strtoupper(substr($salesBook->name, 0, 1)) }}
                                                                </div>
                                                                <strong>{{ $salesBook->name }}</strong>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="mailto:{{ $salesBook->email }}"
                                                                class="text-decoration-none">
                                                                {{ $salesBook->email }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <span>{{ $salesBook->company }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <span>{{ $salesBook->phone }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted">
                                                                {{ $salesBook->created_at->format('M j, Y') }}
                                                            </span>
                                                            <small class="d-block text-muted">
                                                                {{ $salesBook->created_at->format('g:i A') }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            @if ($salesBook->email_sent_at)
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check me-1"></i>Sent
                                                                </span>
                                                                <small class="d-block text-muted mt-1">
                                                                    {{ $salesBook->email_sent_at->format('M j, Y g:i A') }}
                                                                </small>
                                                            @else
                                                                <span class="badge bg-warning">
                                                                    <i class="fas fa-clock me-1"></i>Pending
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <form
                                                                    action="{{ route('admin.salesbooks.destroy', $salesBook->id) }}"
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
                                    <i class="fas fa-inbox fa-4x text-muted"></i>
                                </div>
                                <h4 class="text-muted">No Downloads Yet</h4>
                                <p class="text-muted">No one has downloaded the Funding Readiness Guidebook yet.</p>
                            </div>
                        @endif
                    </div>

                    @if ($salesBooks->hasPages())
                        <div class="d-flex justify-content-center my-4">
                            {{ $salesBooks->links('pagination::bootstrap-5') }}
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background-color: rgba(0, 123, 255, 0.05);
        }

        .btn-group .btn {
            margin-right: 5px;
        }

        .badge {
            font-size: 0.75em;
        }
    </style>

</x-app-layout>
