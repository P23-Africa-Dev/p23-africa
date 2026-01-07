<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ $lead->full_name }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">
                    <span class="badge bg-{{ $lead->status_color }} me-2">{{ $lead->status_label }}</span>
                    @if ($lead->company)
                        {{ $lead->company }}
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.crm.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Back
                </a>
                <a href="{{ route('admin.crm.edit', $lead) }}" class="btn btn-primary">
                    <i class="fa fa-edit me-2"></i>Edit Lead
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Toast Container -->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                <div id="successToast" class="toast" role="alert">
                    <div class="toast-header bg-success text-white">
                        <i class="fa fa-check-circle me-2"></i>
                        <strong class="me-auto">Success</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body" id="successMessage"></div>
                </div>
                <div id="errorToast" class="toast" role="alert">
                    <div class="toast-header bg-danger text-white">
                        <i class="fa fa-exclamation-circle me-2"></i>
                        <strong class="me-auto">Error</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body" id="errorMessage"></div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column - Lead Info -->
                <div class="col-lg-4">
                    <!-- Contact Information -->
                    <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                        <div class="card-header bg-transparent border-0 pt-4">
                            <h5 class="mb-0 text-dark dark:text-gray-100">
                                <i class="fa fa-user me-2 text-primary"></i>Contact Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted small">Email</label>
                                <div class="text-dark dark:text-gray-100">
                                    <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>
                                </div>
                            </div>
                            @if ($lead->phone)
                                <div class="mb-3">
                                    <label class="text-muted small">Phone</label>
                                    <div class="text-dark dark:text-gray-100">
                                        <a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a>
                                    </div>
                                </div>
                            @endif
                            @if ($lead->company)
                                <div class="mb-3">
                                    <label class="text-muted small">Company</label>
                                    <div class="text-dark dark:text-gray-100 fw-medium">{{ $lead->company }}</div>
                                </div>
                            @endif
                            @if ($lead->job_title)
                                <div class="mb-3">
                                    <label class="text-muted small">Job Title</label>
                                    <div class="text-dark dark:text-gray-100">{{ $lead->job_title }}</div>
                                </div>
                            @endif
                            @if ($lead->website)
                                <div class="mb-3">
                                    <label class="text-muted small">Website</label>
                                    <div class="text-dark dark:text-gray-100">
                                        <a href="{{ $lead->website }}" target="_blank">{{ $lead->website }}</a>
                                    </div>
                                </div>
                            @endif
                            @if ($lead->country || $lead->city)
                                <div class="mb-3">
                                    <label class="text-muted small">Location</label>
                                    <div class="text-dark dark:text-gray-100">
                                        {{ implode(', ', array_filter([$lead->city, $lead->country])) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Lead Details -->
                    <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                        <div class="card-header bg-transparent border-0 pt-4">
                            <h5 class="mb-0 text-dark dark:text-gray-100">
                                <i class="fa fa-info-circle me-2 text-primary"></i>Lead Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted small">Status</label>
                                <div>
                                    <select class="form-select form-select-sm w-auto" id="statusSelect"
                                        data-lead="{{ $lead->id }}">
                                        @foreach ($statuses as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ $lead->status === $key ? 'selected' : '' }}>{{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Priority</label>
                                <div>
                                    <span
                                        class="badge bg-{{ $lead->priority_color }}">{{ $lead->priority_label }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Source</label>
                                <div class="text-dark dark:text-gray-100">{{ $lead->source_label }}</div>
                            </div>
                            @if ($lead->estimated_value)
                                <div class="mb-3">
                                    <label class="text-muted small">Estimated Value</label>
                                    <div class="text-success fw-bold fs-5">
                                        ${{ number_format($lead->estimated_value, 2) }}</div>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="text-muted small">Assigned To</label>
                                <div>
                                    <select class="form-select form-select-sm w-auto" id="assignSelect"
                                        data-lead="{{ $lead->id }}">
                                        <option value="">Unassigned</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ $lead->assigned_to == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Next Follow Up</label>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($lead->next_follow_up)
                                        @if ($lead->isFollowUpOverdue())
                                            <span class="text-danger fw-medium">
                                                <i class="fa fa-exclamation-triangle me-1"></i>
                                                {{ $lead->next_follow_up->format('M d, Y h:i A') }}
                                            </span>
                                        @else
                                            <span
                                                class="text-dark dark:text-gray-100">{{ $lead->next_follow_up->format('M d, Y h:i A') }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Not scheduled</span>
                                    @endif
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#followUpModal">
                                        <i class="fa fa-calendar-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @if ($lead->last_contacted_at)
                                <div class="mb-3">
                                    <label class="text-muted small">Last Contacted</label>
                                    <div class="text-dark dark:text-gray-100">
                                        {{ $lead->last_contacted_at->format('M d, Y h:i A') }}</div>
                                </div>
                            @endif
                            <div class="mb-0">
                                <label class="text-muted small">Created</label>
                                <div class="text-dark dark:text-gray-100">
                                    {{ $lead->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Interested Services -->
                    @if ($lead->interested_services && count($lead->interested_services) > 0)
                        <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                            <div class="card-header bg-transparent border-0 pt-4">
                                <h5 class="mb-0 text-dark dark:text-gray-100">
                                    <i class="fa fa-briefcase me-2 text-primary"></i>Interested Services
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach ($lead->interested_services as $service)
                                    <span class="badge bg-primary me-1 mb-1">{{ $service }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Requirements -->
                    @if ($lead->requirements)
                        <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                            <div class="card-header bg-transparent border-0 pt-4">
                                <h5 class="mb-0 text-dark dark:text-gray-100">
                                    <i class="fa fa-file-alt me-2 text-primary"></i>Requirements
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-dark dark:text-gray-100 mb-0" style="white-space: pre-wrap;">
                                    {{ $lead->requirements }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if ($lead->notes)
                        <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                            <div class="card-header bg-transparent border-0 pt-4">
                                <h5 class="mb-0 text-dark dark:text-gray-100">
                                    <i class="fa fa-sticky-note me-2 text-primary"></i>Internal Notes
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-dark dark:text-gray-100 mb-0" style="white-space: pre-wrap;">
                                    {{ $lead->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Activity Timeline -->
                <div class="col-lg-8">
                    <!-- Add Activity Card -->
                    <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                        <div class="card-header bg-transparent border-0 pt-4">
                            <h5 class="mb-0 text-dark dark:text-gray-100">
                                <i class="fa fa-plus-circle me-2 text-primary"></i>Add Activity
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="activityForm">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <select name="type"
                                            class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                            required>
                                            @foreach ($activityTypes as $key => $label)
                                                @if ($key !== 'status_change')
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="title"
                                            class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                            placeholder="Activity title" required>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea name="description" rows="2"
                                            class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                            placeholder="Description (optional)"></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="datetime-local" name="scheduled_at"
                                            class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                            placeholder="Schedule (optional)">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-plus me-2"></i>Add Activity
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Activity Timeline -->
                    <div class="card border-0 shadow-sm dark:bg-gray-800">
                        <div class="card-header bg-transparent border-0 pt-4">
                            <h5 class="mb-0 text-dark dark:text-gray-100">
                                <i class="fa fa-history me-2 text-primary"></i>Activity Timeline
                            </h5>
                        </div>
                        <div class="card-body" id="activityTimeline">
                            @forelse($lead->activities as $activity)
                                <div class="d-flex mb-4" data-activity="{{ $activity->id }}">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-{{ $activity->type_color }} bg-opacity-10 rounded-circle p-2"
                                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i
                                                class="fa {{ $activity->type_icon }} text-{{ $activity->type_color }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 text-dark dark:text-gray-100">{{ $activity->title }}
                                                </h6>
                                                <span
                                                    class="badge bg-{{ $activity->type_color }} me-2">{{ $activity->type_label }}</span>
                                                @if ($activity->user)
                                                    <small class="text-muted">by {{ $activity->user->name }}</small>
                                                @endif
                                            </div>
                                            <small
                                                class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                        @if ($activity->description)
                                            <p class="text-dark dark:text-gray-300 mt-2 mb-0"
                                                style="white-space: pre-wrap;">{{ $activity->description }}</p>
                                        @endif
                                        @if ($activity->scheduled_at && !$activity->is_completed)
                                            <div class="mt-2">
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fa fa-clock me-1"></i>Scheduled:
                                                    {{ $activity->scheduled_at->format('M d, Y h:i A') }}
                                                </span>
                                                <button class="btn btn-sm btn-outline-success ms-2 complete-activity"
                                                    data-id="{{ $activity->id }}">
                                                    <i class="fa fa-check me-1"></i>Complete
                                                </button>
                                            </div>
                                        @endif
                                        @if ($activity->is_completed)
                                            <div class="mt-2">
                                                <span class="badge bg-success">
                                                    <i class="fa fa-check-circle me-1"></i>Completed
                                                    {{ $activity->completed_at ? $activity->completed_at->diffForHumans() : '' }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fa fa-history fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No activities yet. Add one above!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Email Communication -->
                    <div class="card border-0 shadow-sm dark:bg-gray-800 mt-4">
                        <div
                            class="card-header bg-transparent border-0 pt-4 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-dark dark:text-gray-100">
                                <i class="fa fa-envelope me-2 text-primary"></i>Email Communication
                                <span class="badge bg-danger ms-2 d-none" id="unreadEmailBadge">0</span>
                            </h5>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#composeEmailModal">
                                <i class="fa fa-paper-plane me-2"></i>Compose Email
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="emailList">
                                <div class="text-center py-4" id="emailLoading">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="text-muted mt-2">Loading emails...</p>
                                </div>
                                <div id="emailContent" class="d-none">
                                    <!-- Emails will be loaded here via JavaScript -->
                                </div>
                                <div class="text-center py-4 d-none" id="noEmails">
                                    <i class="fa fa-envelope-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No emails yet. Send the first email to start a conversation!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compose Email Modal -->
    <div class="modal fade" id="composeEmailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-dark dark:text-gray-100">
                        <i class="fa fa-paper-plane me-2 text-primary"></i>Compose Email to {{ $lead->full_name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="composeEmailForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-dark dark:text-gray-100">From</label>
                            <input type="text"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                value="{{ auth()->user()->email }}" readonly>
                            <small class="text-muted">Email will be sent from your account email address</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark dark:text-gray-100">To</label>
                            <input type="text"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                value="{{ $lead->email }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark dark:text-gray-100">Subject <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="subject" id="emailSubject"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                placeholder="Enter email subject" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark dark:text-gray-100">Message <span
                                    class="text-danger">*</span></label>
                            <textarea name="body" id="emailBody" rows="8"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                placeholder="Type your message here..." required></textarea>
                        </div>
                        <input type="hidden" name="reply_to_id" id="replyToId" value="">
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="sendEmailBtn">
                            <i class="fa fa-paper-plane me-2"></i>Send Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Email Modal -->
    <div class="modal fade" id="viewEmailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-dark dark:text-gray-100" id="viewEmailSubject">Email Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewEmailBody">
                    <!-- Email content will be loaded here -->
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="replyToEmailBtn">
                        <i class="fa fa-reply me-2"></i>Reply
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Follow Up Modal -->
    <div class="modal fade" id="followUpModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-dark dark:text-gray-100">Schedule Follow Up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="followUpForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-dark dark:text-gray-100">Follow Up Date & Time</label>
                            <input type="datetime-local" name="next_follow_up"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark dark:text-gray-100">Note (optional)</label>
                            <textarea name="note" rows="2" class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                placeholder="Add a note..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lost Reason Modal -->
    <div class="modal fade" id="lostReasonModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-dark dark:text-gray-100">Mark as Lost</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="lostReasonForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-dark dark:text-gray-100">Reason for losing this lead</label>
                            <textarea name="lost_reason" rows="3"
                                class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" placeholder="Enter the reason..."
                                required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Mark as Lost</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const leadId = {{ $lead->id }};

                // Status change
                document.getElementById('statusSelect').addEventListener('change', function() {
                    const newStatus = this.value;

                    if (newStatus === 'lost') {
                        const modal = new bootstrap.Modal(document.getElementById('lostReasonModal'));
                        modal.show();
                        return;
                    }

                    updateStatus(newStatus);
                });

                function updateStatus(status, lostReason = null) {
                    fetch(`/admin/crm/${leadId}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status,
                                lost_reason: lostReason
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', data.message);
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                showToast('error', data.message);
                            }
                        })
                        .catch(() => showToast('error', 'Failed to update status'));
                }

                // Lost reason form
                document.getElementById('lostReasonForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const reason = this.querySelector('[name="lost_reason"]').value;
                    updateStatus('lost', reason);
                    bootstrap.Modal.getInstance(document.getElementById('lostReasonModal')).hide();
                });

                // Assignment change
                document.getElementById('assignSelect').addEventListener('change', function() {
                    fetch(`/admin/crm/${leadId}/assign`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                assigned_to: this.value || null
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', data.message);
                            } else {
                                showToast('error', data.message);
                            }
                        })
                        .catch(() => showToast('error', 'Failed to assign lead'));
                });

                // Add activity
                document.getElementById('activityForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    fetch(`/admin/crm/${leadId}/activity`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', data.message);
                                this.reset();
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                showToast('error', data.message);
                            }
                        })
                        .catch(() => showToast('error', 'Failed to add activity'));
                });

                // Complete activity
                document.querySelectorAll('.complete-activity').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const activityId = this.dataset.id;

                        fetch(`/admin/crm/activity/${activityId}/complete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    showToast('success', data.message);
                                    setTimeout(() => location.reload(), 1000);
                                } else {
                                    showToast('error', data.message);
                                }
                            })
                            .catch(() => showToast('error', 'Failed to complete activity'));
                    });
                });

                // Follow up form
                document.getElementById('followUpForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    fetch(`/admin/crm/${leadId}/follow-up`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', data.message);
                                bootstrap.Modal.getInstance(document.getElementById('followUpModal'))
                                    .hide();
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                showToast('error', data.message);
                            }
                        })
                        .catch(() => showToast('error', 'Failed to schedule follow-up'));
                });

                // Email functionality
                let currentEmails = [];
                let currentViewingEmail = null;

                // Load emails on page load
                loadEmails();

                function loadEmails() {
                    fetch(`/admin/crm/${leadId}/emails`, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('emailLoading').classList.add('d-none');

                            if (data.success && data.emails && data.emails.length > 0) {
                                currentEmails = data.emails;
                                renderEmails(data.emails);
                                document.getElementById('emailContent').classList.remove('d-none');
                                document.getElementById('noEmails').classList.add('d-none');
                            } else {
                                document.getElementById('emailContent').classList.add('d-none');
                                document.getElementById('noEmails').classList.remove('d-none');
                            }
                        })
                        .catch(err => {
                            console.error('Failed to load emails:', err);
                            document.getElementById('emailLoading').classList.add('d-none');
                            document.getElementById('noEmails').classList.remove('d-none');
                        });
                }

                function renderEmails(emails) {
                    const container = document.getElementById('emailContent');
                    container.innerHTML = '';

                    // Group emails by thread
                    const threads = {};
                    emails.forEach(email => {
                        const threadId = email.thread_id || email.id;
                        if (!threads[threadId]) {
                            threads[threadId] = [];
                        }
                        threads[threadId].push(email);
                    });

                    // Sort threads by most recent email
                    const sortedThreads = Object.values(threads).sort((a, b) => {
                        const aLatest = new Date(a[0].created_at);
                        const bLatest = new Date(b[0].created_at);
                        return bLatest - aLatest;
                    });

                    sortedThreads.forEach(threadEmails => {
                        const latestEmail = threadEmails[0];
                        const hasUnread = threadEmails.some(e => e.direction === 'incoming' && !e.read_at);

                        const emailItem = document.createElement('div');
                        emailItem.className =
                            `email-item p-3 border-bottom ${hasUnread ? 'bg-light' : ''} cursor-pointer`;
                        emailItem.style.cursor = 'pointer';
                        emailItem.onclick = () => viewEmail(latestEmail);

                        emailItem.innerHTML = `
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fa ${latestEmail.direction === 'incoming' ? 'fa-inbox text-primary' : 'fa-paper-plane text-success'} me-2"></i>
                                        <h6 class="mb-0 text-dark dark:text-gray-100 ${hasUnread ? 'fw-bold' : ''}">
                                            ${escapeHtml(latestEmail.subject)}
                                        </h6>
                                        ${hasUnread ? '<span class="badge bg-danger ms-2">New Reply</span>' : ''}
                                    </div>
                                    <p class="text-muted mb-1 small">
                                        ${latestEmail.direction === 'incoming' ? 'From: ' + escapeHtml(latestEmail.from_name || latestEmail.from_email) : 'To: ' + escapeHtml(latestEmail.to_name || latestEmail.to_email)}
                                    </p>
                                    <p class="text-muted mb-0 small text-truncate" style="max-width: 400px;">
                                        ${escapeHtml(latestEmail.body.substring(0, 100))}${latestEmail.body.length > 100 ? '...' : ''}
                                    </p>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">${formatDate(latestEmail.created_at)}</small>
                                    <div class="mt-1">
                                        <span class="badge bg-${latestEmail.status === 'sent' ? 'success' : latestEmail.status === 'received' ? 'primary' : latestEmail.status === 'failed' ? 'danger' : 'secondary'}">
                                            ${latestEmail.status}
                                        </span>
                                    </div>
                                    ${threadEmails.length > 1 ? `<small class="text-muted"><i class="fa fa-reply me-1"></i>${threadEmails.length} messages</small>` : ''}
                                </div>
                            </div>
                        `;

                        container.appendChild(emailItem);
                    });
                }

                function viewEmail(email) {
                    currentViewingEmail = email;

                    // Load the full thread
                    fetch(`/admin/crm/${leadId}/emails/thread/${email.thread_id}`, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success && data.emails) {
                                renderEmailThread(data.emails, email.subject);
                            } else {
                                // Show single email if thread fetch fails
                                renderEmailThread([email], email.subject);
                            }
                        })
                        .catch(() => {
                            renderEmailThread([email], email.subject);
                        });
                }

                function renderEmailThread(emails, subject) {
                    const modal = document.getElementById('viewEmailModal');
                    document.getElementById('viewEmailSubject').textContent = subject;

                    let bodyHtml = '<div class="email-thread">';

                    emails.forEach((email, index) => {
                        const isIncoming = email.direction === 'incoming';
                        bodyHtml += `
                            <div class="email-message ${index > 0 ? 'border-top pt-3 mt-3' : ''} ${isIncoming ? 'bg-light p-3 rounded' : ''}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong class="text-dark dark:text-gray-100">
                                            <i class="fa ${isIncoming ? 'fa-inbox text-primary' : 'fa-paper-plane text-success'} me-2"></i>
                                            ${isIncoming ? 'From' : 'To'}: ${escapeHtml(isIncoming ? (email.from_name || email.from_email) : (email.to_name || email.to_email))}
                                        </strong>
                                        ${email.user ? `<span class="text-muted ms-2">(${escapeHtml(email.user.name)})</span>` : ''}
                                    </div>
                                    <small class="text-muted">${formatDate(email.created_at)}</small>
                                </div>
                                <div class="email-body text-dark dark:text-gray-100" style="white-space: pre-wrap;">
                                    ${escapeHtml(email.body)}
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-${email.status === 'sent' ? 'success' : email.status === 'received' ? 'primary' : email.status === 'failed' ? 'danger' : 'secondary'}">
                                        ${email.status}
                                    </span>
                                    ${email.sent_at ? `<small class="text-muted ms-2">Sent: ${formatDate(email.sent_at)}</small>` : ''}
                                </div>
                            </div>
                        `;
                    });

                    bodyHtml += '</div>';
                    document.getElementById('viewEmailBody').innerHTML = bodyHtml;

                    new bootstrap.Modal(modal).show();
                }

                // Reply to email button
                document.getElementById('replyToEmailBtn').addEventListener('click', function() {
                    if (currentViewingEmail) {
                        // Close view modal
                        bootstrap.Modal.getInstance(document.getElementById('viewEmailModal')).hide();

                        // Pre-fill compose form for reply
                        const subject = currentViewingEmail.subject.startsWith('Re:') ?
                            currentViewingEmail.subject :
                            'Re: ' + currentViewingEmail.subject;
                        document.getElementById('emailSubject').value = subject;
                        document.getElementById('replyToId').value = currentViewingEmail.id;
                        document.getElementById('emailBody').value = '';

                        // Open compose modal
                        setTimeout(() => {
                            new bootstrap.Modal(document.getElementById('composeEmailModal')).show();
                        }, 300);
                    }
                });

                // Compose email form
                document.getElementById('composeEmailForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const sendBtn = document.getElementById('sendEmailBtn');
                    sendBtn.disabled = true;
                    sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

                    const formData = new FormData(this);

                    fetch(`/admin/crm/${leadId}/email`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            sendBtn.disabled = false;
                            sendBtn.innerHTML = '<i class="fa fa-paper-plane me-2"></i>Send Email';

                            if (data.success) {
                                showToast('success', data.message);
                                this.reset();
                                document.getElementById('replyToId').value = '';
                                bootstrap.Modal.getInstance(document.getElementById('composeEmailModal'))
                                    .hide();
                                loadEmails();
                            } else {
                                showToast('error', data.message || 'Failed to send email');
                            }
                        })
                        .catch(err => {
                            sendBtn.disabled = false;
                            sendBtn.innerHTML = '<i class="fa fa-paper-plane me-2"></i>Send Email';
                            showToast('error', 'Failed to send email');
                        });
                });

                // Reset compose form when modal is closed
                document.getElementById('composeEmailModal').addEventListener('hidden.bs.modal', function() {
                    document.getElementById('composeEmailForm').reset();
                    document.getElementById('replyToId').value = '';
                });

                // Helper functions
                function escapeHtml(text) {
                    if (!text) return '';
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }

                function formatDate(dateString) {
                    const date = new Date(dateString);
                    const now = new Date();
                    const diff = now - date;

                    // Less than 24 hours ago
                    if (diff < 86400000) {
                        return date.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                    // Less than 7 days ago
                    if (diff < 604800000) {
                        return date.toLocaleDateString([], {
                            weekday: 'short',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                    // Default
                    return date.toLocaleDateString([], {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                }

                function showToast(type, message) {
                    const toast = document.getElementById(type + 'Toast');
                    const msgEl = document.getElementById(type + 'Message');
                    msgEl.textContent = message;
                    new bootstrap.Toast(toast).show();
                }
            });
        </script>
    @endpush
</x-app-layout>
