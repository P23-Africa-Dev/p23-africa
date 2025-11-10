<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Contact Submission Details') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">
                    Viewing submission from {{ $contact->name }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.contact.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Back to List
                </a>
                @if (auth()->user()->hasRole('admin'))
                    <button type="button" class="btn btn-outline-danger" id="deleteContactBtn"
                        data-id="{{ $contact->id }}" data-name="{{ $contact->name }}">
                        <i class="fa fa-trash me-2"></i>Delete
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Contact Information Card -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition mb-4">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <h5 class="mb-0 text-dark dark:text-gray-100">
                        <i class="fa fa-user me-2 text-primary"></i>Contact Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted dark:text-gray-400 mb-1">
                                    <i class="fa fa-user me-2"></i>Full Name
                                </label>
                                <div class="form-control-plaintext text-dark dark:text-gray-100 fw-medium">
                                    {{ $contact->name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted dark:text-gray-400 mb-1">
                                    <i class="fa fa-envelope me-2"></i>Email Address
                                </label>
                                <div class="form-control-plaintext">
                                    <a href="mailto:{{ $contact->email }}"
                                        class="text-primary text-decoration-none fw-medium">
                                        {{ $contact->email }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Requested Card -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition mb-4">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <h5 class="mb-0 text-dark dark:text-gray-100">
                        <i class="fa fa-cogs me-2 text-success"></i>Services Requested
                    </h5>
                </div>
                <div class="card-body">
                    @if ($contact->services && is_array($contact->services) && count($contact->services) > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($contact->services as $service)
                                <span class="badge bg-primary fs-6 px-3 py-2">
                                    <i class="fa fa-check me-1"></i>{{ $service }}
                                </span>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <small class="text-muted dark:text-gray-400">
                                Total Services: {{ count($contact->services) }}
                            </small>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fa fa-exclamation-circle fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No specific services were selected</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Message Card -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition mb-4">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <h5 class="mb-0 text-dark dark:text-gray-100">
                        <i class="fa fa-comment me-2 text-info"></i>Message
                    </h5>
                </div>
                <div class="card-body">
                    <div class="bg-light dark:bg-gray-700 rounded p-4">
                        <div class="text-dark dark:text-gray-100" style="white-space: pre-line; line-height: 1.6;">
                            {{ $contact->message }}
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted dark:text-gray-400">
                            Message Length: {{ strlen($contact->message) }} characters
                        </small>
                    </div>
                </div>
            </div>

            <!-- Technical Information Card -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition mb-4">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <h5 class="mb-0 text-dark dark:text-gray-100">
                        <i class="fa fa-info-circle me-2 text-warning"></i>Submission Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted dark:text-gray-400 mb-1">
                                    <i class="fa fa-calendar me-2"></i>Submitted Date
                                </label>
                                <div class="form-control-plaintext text-dark dark:text-gray-100">
                                    {{ $contact->created_at->format('F d, Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted dark:text-gray-400 mb-1">
                                    <i class="fa fa-clock me-2"></i>Submitted Time
                                </label>
                                <div class="form-control-plaintext text-dark dark:text-gray-100">
                                    {{ $contact->created_at->format('h:i A') }}
                                    <small class="text-muted">({{ $contact->created_at->diffForHumans() }})</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted dark:text-gray-400 mb-1">
                                    <i class="fa fa-globe me-2"></i>IP Address
                                </label>
                                <div class="form-control-plaintext text-dark dark:text-gray-100">
                                    {{ $contact->ip_address ?? 'Not recorded' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted dark:text-gray-400 mb-1">
                                    <i class="fa fa-desktop me-2"></i>Browser/Device
                                </label>
                                <div class="form-control-plaintext text-dark dark:text-gray-100"
                                    title="{{ $contact->user_agent }}">
                                    @if ($contact->user_agent)
                                        @php
                                            $agent = $contact->user_agent;
                                            if (strpos($agent, 'Chrome') !== false) {
                                                $browser = 'Chrome';
                                            } elseif (strpos($agent, 'Firefox') !== false) {
                                                $browser = 'Firefox';
                                            } elseif (strpos($agent, 'Safari') !== false) {
                                                $browser = 'Safari';
                                            } elseif (strpos($agent, 'Edge') !== false) {
                                                $browser = 'Edge';
                                            } else {
                                                $browser = 'Unknown';
                                            }

                                            if (strpos($agent, 'Mobile') !== false) {
                                                $device = 'Mobile';
                                            } elseif (strpos($agent, 'Tablet') !== false) {
                                                $device = 'Tablet';
                                            } else {
                                                $device = 'Desktop';
                                            }
                                        @endphp
                                        {{ $browser }} on {{ $device }}
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">
                                            {{ Str::limit($contact->user_agent, 60) }}
                                        </small>
                                    @else
                                        Not recorded
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Status Card -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <h5 class="mb-0 text-dark dark:text-gray-100">
                        <i class="fa fa-envelope-open me-2 text-secondary"></i>Email Status
                    </h5>
                </div>
                <div class="card-body">
                    @if ($contact->email_sent_at)
                        <div class="alert alert-success border-0 d-flex align-items-center">
                            <i class="fa fa-check-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Email Response Sent Successfully</h6>
                                <p class="mb-0">
                                    Confirmation emails were sent on
                                    <strong>{{ $contact->email_sent_at->format('F d, Y \a\t h:i A') }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        ({{ $contact->email_sent_at->diffForHumans() }})
                                    </small>
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 d-flex align-items-center">
                            <i class="fa fa-exclamation-triangle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Email Status Unknown</h6>
                                <p class="mb-0">
                                    No record of confirmation emails being sent for this submission.
                                    This could indicate an email delivery issue.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-dark dark:text-gray-100">
                        <i class="fa fa-exclamation-triangle text-danger me-2"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-dark dark:text-gray-100">
                        Are you sure you want to delete the contact submission from
                        <strong>{{ $contact->name }}</strong>?
                    </p>
                    <p class="text-muted small mb-0">
                        This action cannot be undone. All information including the message and submission details will
                        be permanently removed.
                    </p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fa fa-trash me-2"></i>Delete Submission
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteBtn = document.getElementById('deleteContactBtn');
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function() {
                        deleteModal.show();
                    });
                }

                if (confirmDeleteBtn) {
                    confirmDeleteBtn.addEventListener('click', function() {
                        const contactId = deleteBtn.dataset.id;

                        // Show loading state
                        confirmDeleteBtn.disabled = true;
                        confirmDeleteBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Deleting...';

                        fetch(`/admin/contact/${contactId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Redirect to contact list with success message
                                    window.location.href = '{{ route('admin.contact.index') }}';
                                } else {
                                    alert(data.message || 'Failed to delete contact submission.');
                                    // Reset button state
                                    confirmDeleteBtn.disabled = false;
                                    confirmDeleteBtn.innerHTML =
                                        '<i class="fa fa-trash me-2"></i>Delete Submission';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred while deleting the contact submission.');
                                // Reset button state
                                confirmDeleteBtn.disabled = false;
                                confirmDeleteBtn.innerHTML =
                                    '<i class="fa fa-trash me-2"></i>Delete Submission';
                            });
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
