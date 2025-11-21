<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Consultation Application Details') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">{{ $application->name }} -
                    {{ $application->company }}</p>
            </div>
            <a href="{{ route('admin.consultation.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-2"></i>Back to Applications
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Status Overview -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                @if ($application->scheduledSessions->count() > 0)
                                    <i class="fa fa-calendar-check fa-3x text-info mb-2"></i>
                                    <h5 class="text-dark dark:text-gray-100">Scheduled</h5>
                                    <p class="text-muted dark:text-gray-400 mb-0">Session is scheduled</p>
                                @elseif($application->payments->where('status', 'successful')->count() > 0)
                                    <i class="fa fa-credit-card fa-3x text-success mb-2"></i>
                                    <h5 class="text-dark dark:text-gray-100">Payment Complete</h5>
                                    <p class="text-muted dark:text-gray-400 mb-0">Ready to schedule</p>
                                @else
                                    <i class="fa fa-clock fa-3x text-warning mb-2"></i>
                                    <h5 class="text-dark dark:text-gray-100">Pending Payment</h5>
                                    <p class="text-muted dark:text-gray-400 mb-0">Awaiting payment</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                        <div class="card-body">
                            <h6 class="text-dark dark:text-gray-100 mb-3">Quick Actions</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="mailto:{{ $application->email }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fa fa-envelope me-1"></i>Email Client
                                </a>
                                @if ($application->phone)
                                    <a href="tel:{{ $application->phone }}" class="btn btn-outline-success btn-sm">
                                        <i class="fa fa-phone me-1"></i>Call Client
                                    </a>
                                @endif
                                @if ($application->payments->where('status', 'successful')->count() > 0 && $application->scheduledSessions->count() == 0)
                                    <button class="btn btn-outline-info btn-sm">
                                        <i class="fa fa-calendar me-1"></i>Schedule Session
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Application Details -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                        <div class="card-header bg-transparent border-0 pt-4 pb-3">
                            <h5 class="mb-0 text-dark dark:text-gray-100">
                                <i class="fa fa-user me-2"></i>Application Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Personal Information -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-dark dark:text-gray-100 mb-3">Personal Information</h6>
                                    <div class="mb-3">
                                        <label class="form-label text-muted dark:text-gray-400">Full Name</label>
                                        <p class="text-dark dark:text-gray-200 mb-0">{{ $application->name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted dark:text-gray-400">Email Address</label>
                                        <p class="text-dark dark:text-gray-200 mb-0">{{ $application->email }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted dark:text-gray-400">Phone Number</label>
                                        <p class="text-dark dark:text-gray-200 mb-0">
                                            {{ $application->phone ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-dark dark:text-gray-100 mb-3">Business Information</h6>
                                    <div class="mb-3">
                                        <label class="form-label text-muted dark:text-gray-400">Company</label>
                                        <p class="text-dark dark:text-gray-200 mb-0">
                                            {{ $application->company ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted dark:text-gray-400">Industry</label>
                                        <p class="text-dark dark:text-gray-200 mb-0">
                                            {{ $application->industry ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted dark:text-gray-400">Applied On</label>
                                        <p class="text-dark dark:text-gray-200 mb-0">
                                            {{ $application->created_at->format('F d, Y \a\t h:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Business Details -->
                            @if ($application->business_description)
                                <div class="mb-4">
                                    <h6 class="text-dark dark:text-gray-100 mb-3">Business Description</h6>
                                    <div class="bg-light dark:bg-gray-700 p-3 rounded">
                                        <p class="text-dark dark:text-gray-200 mb-0">
                                            {{ $application->business_description }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Goals and Challenges -->
                            <div class="row">
                                @if ($application->selected_goals)
                                    <div class="col-md-6 mb-4">
                                        <h6 class="text-dark dark:text-gray-100 mb-3">Business Goals</h6>
                                        <ul class="list-unstyled">
                                            @foreach ($application->selected_goals ?? [] as $goal)
                                                <li class="text-dark dark:text-gray-200 mb-2">
                                                    <i class="fa fa-check text-success me-2"></i>{{ $goal }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if ($application->selected_challenges)
                                    <div class="col-md-6 mb-4">
                                        <h6 class="text-dark dark:text-gray-100 mb-3">Key Challenges</h6>
                                        <ul class="list-unstyled">
                                            @foreach ($application->selected_challenges ?? [] as $challenge)
                                                <li class="text-dark dark:text-gray-200 mb-2">
                                                    <i
                                                        class="fa fa-exclamation-triangle text-warning me-2"></i>{{ $challenge }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment & Schedule Information -->
                <div class="col-lg-4">
                    <!-- Payment Information -->
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition mb-4">
                        <div class="card-header bg-transparent border-0 pt-4 pb-3">
                            <h5 class="mb-0 text-dark dark:text-gray-100">
                                <i class="fa fa-credit-card me-2"></i>Payment Information
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($application->payments->count() > 0)
                                @foreach ($application->payments as $payment)
                                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span
                                                class="fw-medium text-dark dark:text-gray-100">${{ number_format($payment->amount / 100, 2) }}</span>
                                            <span
                                                class="badge bg-{{ $payment->status == 'successful' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </div>
                                        <div class="small text-muted dark:text-gray-400">
                                            <div>{{ ucfirst($payment->gateway) }} Payment</div>
                                            <div>{{ $payment->created_at->format('M d, Y h:i A') }}</div>
                                            @if ($payment->transaction_id)
                                                <div>ID: {{ $payment->transaction_id }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-3">
                                    <i class="fa fa-credit-card fa-2x text-muted dark:text-gray-400 mb-2"></i>
                                    <p class="text-muted dark:text-gray-400 mb-0">No payments recorded</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Scheduled Sessions -->
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                        <div class="card-header bg-transparent border-0 pt-4 pb-3">
                            <h5 class="mb-0 text-dark dark:text-gray-100">
                                <i class="fa fa-calendar me-2"></i>Scheduled Sessions
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($application->scheduledSessions->count() > 0)
                                @foreach ($application->scheduledSessions as $session)
                                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="fw-medium text-dark dark:text-gray-100 mb-2">
                                            {{ \Carbon\Carbon::parse($session->session_date)->format('F d, Y') }}
                                        </div>
                                        <div class="text-muted dark:text-gray-400 small">
                                            <div>Time:
                                                {{ \Carbon\Carbon::parse($session->session_time)->format('h:i A') }}
                                            </div>
                                            <div>Duration: {{ $session->duration ?? 90 }} minutes</div>
                                            @if ($session->meet_link)
                                                <div>
                                                    <a href="{{ $session->meet_link }}" target="_blank"
                                                        class="text-primary">
                                                        <i class="fa fa-external-link-alt me-1"></i>Meeting Link
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-3">
                                    <i class="fa fa-calendar fa-2x text-muted dark:text-gray-400 mb-2"></i>
                                    <p class="text-muted dark:text-gray-400 mb-0">No sessions scheduled</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
