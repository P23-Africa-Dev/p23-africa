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
            <a href="{{ orgRoute('admin.consultation.index') }}" class="btn btn-outline-secondary">
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
                                        <label class="form-label text-muted dark:text-gray-400"><small>Full Name</small></label><br>
                                        <p class="text-dark dark:text-gray-200 mb-0 d-inline">{{ $application->name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted dark:text-gray-400"><small>Email Address</small></label><br>
                                        <p class="text-dark dark:text-gray-200 mb-0 d-inline">{{ $application->email }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted dark:text-gray-400"><small>Phone Number</small></label><br>
                                        <p class="text-dark dark:text-gray-200 mb-0 d-inline">
                                            {{ $application->phone ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted dark:text-gray-400"><small>Website/Social Handle</small></label><br>
                                        <p class="text-dark dark:text-gray-200 mb-0 d-inline">{{ $application->website_or_handle }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-dark dark:text-gray-100 mb-3">Business Information</h6>
                                    <div class="mb-3">
                                        <label class="form-label text-muted dark:text-gray-400"><small>Company</small></label><br>
                                        <p class="text-dark dark:text-gray-200 mb-0 d-inline">
                                            {{ $application->company ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted dark:text-gray-400"><small>Industry</small></label><br>
                                        <p class="text-dark dark:text-gray-200 mb-0 d-inline">
                                            {{ $application->industry ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted dark:text-gray-400"><small>Applied On</small></label><br>
                                        <p class="text-dark dark:text-gray-200 mb-0 d-inline">
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

                    <!-- Help Messages -->
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition mb-4">
                        <div class="card-header bg-transparent border-0 pt-4 pb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-dark dark:text-gray-100">
                                    <i class="fa fa-comments me-2"></i>Help Messages
                                </h5>
                                @if ($application->unreadChatMessages->count() > 0)
                                    <span class="badge bg-danger">{{ $application->unreadChatMessages->count() }}
                                        new</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            @if ($application->chatMessages->count() > 0)
                                <div class="chat-messages">
                                    @foreach ($application->chatMessages as $message)
                                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        @if ($message->sender_type === 'user')
                                                            <i class="fa fa-user-circle text-primary"></i>
                                                        @else
                                                            <i class="fa fa-user-shield text-success"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium text-dark dark:text-gray-100">
                                                            {{ $message->sender_name }}
                                                            @if ($message->sender_type === 'admin')
                                                                <small class="text-success">(Admin)</small>
                                                            @endif
                                                        </div>
                                                        <div class="small text-muted dark:text-gray-400">
                                                            {{ $message->sent_at->format('M d, Y h:i A') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @if (!$message->is_read && $message->sender_type === 'user')
                                                    <span class="badge bg-warning">New</span>
                                                @endif
                                            </div>
                                            <div class="text-dark dark:text-gray-200 message-content">
                                                {{ $message->message }}
                                            </div>
                                            @if (!$message->is_read && $message->sender_type === 'user')
                                                <div class="mt-2">
                                                    <button class="btn btn-sm btn-outline-primary mark-read-btn"
                                                        data-message-id="{{ $message->id }}">
                                                        <i class="fa fa-check me-1"></i>Mark as Read
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Quick Reply -->
                                <div class="mt-3 pt-3 border-top">
                                    <h6 class="text-dark dark:text-gray-100 mb-3">Quick Reply</h6>
                                    <form id="quick-reply-form">
                                        <div class="mb-3">
                                            <textarea class="form-control" id="reply-message" rows="3" placeholder="Type your reply here..."
                                                maxlength="1000"></textarea>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">This will be sent via email to the client</small>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fa fa-paper-plane me-1"></i>Send Reply
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fa fa-comments fa-2x text-muted dark:text-gray-400 mb-2"></i>
                                    <p class="text-muted dark:text-gray-400 mb-0">No help messages</p>
                                    <small class="text-muted dark:text-gray-400">
                                        The client can use the "Call For Help" feature during consultation booking
                                    </small>
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

    <style>
        .message-content {
            line-height: 1.6;
            word-wrap: break-word;
        }

        .chat-messages {
            font-size: 14px;
        }

        .mark-read-btn {
            font-size: 12px;
        }

        #reply-message {
            resize: vertical;
            min-height: 80px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mark message as read functionality
            document.querySelectorAll('.mark-read-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const messageId = this.getAttribute('data-message-id');
                    const btn = this;

                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Marking...';

                    fetch(`/admin/consultation/chat/mark-read/${messageId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                btn.closest('.mb-3').querySelector('.badge')?.remove();
                                btn.remove();

                                // Update the header badge
                                const headerBadge = document.querySelector(
                                    '.card-header .badge');
                                if (headerBadge) {
                                    const currentCount = parseInt(headerBadge.textContent.split(
                                        ' ')[0]);
                                    if (currentCount <= 1) {
                                        headerBadge.remove();
                                    } else {
                                        headerBadge.textContent = `${currentCount - 1} new`;
                                    }
                                }
                            } else {
                                throw new Error(data.message || 'Failed to mark as read');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fa fa-check me-1"></i>Mark as Read';
                            alert('Failed to mark message as read. Please try again.');
                        });
                });
            });

            // Quick reply functionality
            document.getElementById('quick-reply-form')?.addEventListener('submit', function(e) {
                e.preventDefault();

                const textarea = document.getElementById('reply-message');
                const message = textarea.value.trim();
                const submitBtn = this.querySelector('button[type="submit"]');

                if (!message) {
                    alert('Please type a message before sending.');
                    textarea.focus();
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Sending...';

                fetch(`/admin/consultations/{{ $application->id }}/reply`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            message: message
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            textarea.value = '';
                            alert('Reply sent successfully!');
                            // Optionally reload the page to show the new message
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Failed to send reply');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to send reply. Please try again.');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fa fa-paper-plane me-1"></i>Send Reply';
                    });
            });
        });
    </script>
</x-app-layout>
