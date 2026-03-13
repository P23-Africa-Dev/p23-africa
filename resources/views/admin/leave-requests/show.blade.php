<x-app-layout>

    @php
        $user = auth()->user();
        $isRequester = $leaveRequest->user_id === $user->id;
        $isSupervisor = $leaveRequest->supervisor_id === $user->id || $user->hasRole('admin');
        $baseUrl = orgRoute('admin.leave-requests.index');
        $badgeColors = [
            'pending' => 'background:rgba(245,158,11,.12);color:#92400e;',
            'approved' => 'background:rgba(16,185,129,.12);color:#065f46;',
            'rejected' => 'background:rgba(239,68,68,.12);color:#991b1b;',
            'modified' => 'background:rgba(59,130,246,.12);color:#1e40af;',
            'sender_okay' => 'background:rgba(99,102,241,.12);color:#4338ca;',
            'sender_not_okay' => 'background:rgba(100,116,139,.12);color:#475569;',
        ];
    @endphp

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold mb-1" style="font-size:1.25rem;">Leave Request #{{ $leaveRequest->id }}</h2>
                <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">
                    <span class="badge"
                        style="{{ $badgeColors[$leaveRequest->status] ?? '' }}font-size:.7rem;">{{ $leaveRequest->status_name }}</span>
                    &middot; Submitted {{ $leaveRequest->created_at->diffForHumans() }}
                </p>
            </div>
            <a href="{{ $baseUrl }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:.875rem;">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size:.875rem;">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Left Column: Details --}}
        <div class="col-lg-7">

            {{-- Request Details Card --}}
            <div class="card anim-in mb-4">
                <div class="card-header">
                    <h5 class="mb-0" style="font-size:.9375rem;display:flex;align-items:center;gap:.5rem;">
                        <span
                            style="width:28px;height:28px;border-radius:8px;background:rgba(99,102,241,.12);color:var(--p-primary);display:inline-flex;align-items:center;justify-content:center;">
                            <i class="bi bi-calendar-range" style="font-size:.85rem;"></i>
                        </span>
                        Leave Request Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div
                                style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">
                                Requester</div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <div
                                    style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--p-primary),var(--p-accent));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;color:#fff;">
                                    {{ strtoupper(substr($leaveRequest->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;">{{ $leaveRequest->user->name }}</div>
                                    <div style="font-size:.75rem;color:var(--text-muted);">
                                        {{ $leaveRequest->user->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div
                                style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">
                                Supervisor</div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <div
                                    style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--p-accent),var(--p-primary));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;color:#fff;">
                                    {{ strtoupper(substr($leaveRequest->supervisor->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;">{{ $leaveRequest->supervisor->name }}</div>
                                    <div style="font-size:.75rem;color:var(--text-muted);">
                                        {{ $leaveRequest->supervisor->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div
                                style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">
                                Leave Type</div>
                            <div style="font-weight:600;margin-top:4px;">{{ $leaveRequest->type_name }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div
                                style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">
                                Status</div>
                            <div style="margin-top:4px;">
                                <span class="badge"
                                    style="{{ $badgeColors[$leaveRequest->status] ?? '' }}font-size:.75rem;">{{ $leaveRequest->status_name }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div
                                style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">
                                Requested Period</div>
                            <div style="font-weight:600;margin-top:4px;">
                                {{ $leaveRequest->start_date->format('M d, Y') }} —
                                {{ $leaveRequest->end_date->format('M d, Y') }}
                                <span class="text-muted" style="font-weight:400;">({{ $leaveRequest->duration_days }}
                                    working day{{ $leaveRequest->duration_days > 1 ? 's' : '' }})</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div
                                style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">
                                Reason</div>
                            <div
                                style="margin-top:4px;line-height:1.6;background:var(--bg-secondary);padding:.75rem 1rem;border-radius:var(--radius-md);font-size:.875rem;">
                                {{ $leaveRequest->reason }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modifications Card (if modified) --}}
            @if ($leaveRequest->modified_start_date)
                <div class="card anim-in mb-4" style="border-left:4px solid #3b82f6;">
                    <div class="card-header" style="background:rgba(59,130,246,.04);">
                        <h5 class="mb-0" style="font-size:.9375rem;display:flex;align-items:center;gap:.5rem;">
                            <span
                                style="width:28px;height:28px;border-radius:8px;background:rgba(59,130,246,.12);color:#3b82f6;display:inline-flex;align-items:center;justify-content:center;">
                                <i class="bi bi-pencil-square" style="font-size:.85rem;"></i>
                            </span>
                            Supervisor Modifications
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div
                                    style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">
                                    Modified Period</div>
                                <div style="font-weight:600;margin-top:4px;color:#2563eb;">
                                    {{ $leaveRequest->modified_start_date->format('M d, Y') }} —
                                    {{ $leaveRequest->modified_end_date->format('M d, Y') }}
                                    <span class="text-muted"
                                        style="font-weight:400;">({{ $leaveRequest->modified_duration_days }} working
                                        day{{ $leaveRequest->modified_duration_days > 1 ? 's' : '' }})</span>
                                </div>
                            </div>
                            @if ($leaveRequest->supervisor_note)
                                <div class="col-12">
                                    <div
                                        style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">
                                        Supervisor's Note</div>
                                    <div
                                        style="margin-top:4px;line-height:1.6;background:rgba(59,130,246,.05);padding:.75rem 1rem;border-radius:var(--radius-md);font-size:.875rem;border:1px solid rgba(59,130,246,.15);">
                                        {{ $leaveRequest->supervisor_note }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Sender Response (if responded) --}}
            @if (in_array($leaveRequest->status, ['sender_okay', 'sender_not_okay']))
                <div class="card anim-in mb-4"
                    style="border-left:4px solid {{ $leaveRequest->status === 'sender_okay' ? '#10b981' : '#ef4444' }};">
                    <div class="card-header"
                        style="background:{{ $leaveRequest->status === 'sender_okay' ? 'rgba(16,185,129,.04)' : 'rgba(239,68,68,.04)' }};">
                        <h5 class="mb-0" style="font-size:.9375rem;display:flex;align-items:center;gap:.5rem;">
                            <span
                                style="width:28px;height:28px;border-radius:8px;background:{{ $leaveRequest->status === 'sender_okay' ? 'rgba(16,185,129,.12)' : 'rgba(239,68,68,.12)' }};color:{{ $leaveRequest->status === 'sender_okay' ? '#10b981' : '#ef4444' }};display:inline-flex;align-items:center;justify-content:center;">
                                <i class="bi {{ $leaveRequest->status === 'sender_okay' ? 'bi-hand-thumbs-up' : 'bi-hand-thumbs-down' }}"
                                    style="font-size:.85rem;"></i>
                            </span>
                            Employee Response: {{ $leaveRequest->status === 'sender_okay' ? 'Okay' : 'Not Okay' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($leaveRequest->sender_response_note)
                            <div style="line-height:1.6;font-size:.875rem;">{{ $leaveRequest->sender_response_note }}
                            </div>
                        @else
                            <div class="text-muted" style="font-size:.875rem;">No additional note from the employee.
                            </div>
                        @endif
                        @if ($leaveRequest->sender_responded_at)
                            <div class="text-muted mt-2" style="font-size:.75rem;">
                                <i class="bi bi-clock me-1"></i>Responded
                                {{ $leaveRequest->sender_responded_at->diffForHumans() }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Decision Note (if decided) --}}
            @if ($leaveRequest->decided_at && $leaveRequest->decision_note)
                <div class="card anim-in mb-4"
                    style="border-left:4px solid {{ $leaveRequest->status === 'approved' ? '#10b981' : '#ef4444' }};">
                    <div class="card-header">
                        <h5 class="mb-0" style="font-size:.9375rem;">Final Decision Note</h5>
                    </div>
                    <div class="card-body">
                        <div style="line-height:1.6;font-size:.875rem;">{{ $leaveRequest->decision_note }}</div>
                        <div class="text-muted mt-2" style="font-size:.75rem;">
                            <i class="bi bi-clock me-1"></i>Decided {{ $leaveRequest->decided_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- Right Column: Actions --}}
        <div class="col-lg-5">

            {{-- SENDER: Respond to modifications --}}
            @if ($isRequester && $leaveRequest->status === 'modified')
                <div class="card anim-in mb-4" style="border:2px solid #3b82f6;">
                    <div class="card-header" style="background:rgba(59,130,246,.06);">
                        <h5 class="mb-0" style="font-size:.9375rem;">
                            <i class="bi bi-reply me-1"></i>Your Response Needed
                        </h5>
                    </div>
                    <div class="card-body">
                        <p style="font-size:.875rem;color:var(--text-muted);margin-bottom:1rem;">
                            Your supervisor has modified this leave request. Review the changes above and respond:
                        </p>
                        <form method="POST" action="{{ orgRoute('admin.leave-requests.respond', $leaveRequest) }}"
                            x-data="{ response: '' }">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:600;font-size:.875rem;">Add a note
                                    (optional)</label>
                                <textarea name="sender_response_note" rows="3" class="form-control"
                                    placeholder="Any comments about the modifications..."></textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" name="response" value="okay"
                                    class="btn btn-success flex-fill">
                                    <i class="bi bi-hand-thumbs-up me-1"></i>Okay
                                </button>
                                <button type="submit" name="response" value="not_okay"
                                    class="btn btn-outline-danger flex-fill">
                                    <i class="bi bi-hand-thumbs-down me-1"></i>Not Okay
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- SUPERVISOR: Actions --}}
            @if ($isSupervisor && !in_array($leaveRequest->status, ['approved', 'rejected']))

                {{-- Modify (only for pending requests) --}}
                @if ($leaveRequest->status === 'pending')
                    <div class="card anim-in mb-4">
                        <div class="card-header">
                            <h5 class="mb-0" style="font-size:.9375rem;">
                                <i class="bi bi-pencil-square me-1"></i>Modify Request
                            </h5>
                        </div>
                        <div class="card-body">
                            <p style="font-size:.8125rem;color:var(--text-muted);margin-bottom:1rem;">
                                Suggest different dates. The employee will be notified and can agree or disagree.
                            </p>
                            <form method="POST"
                                action="{{ orgRoute('admin.leave-requests.modify', $leaveRequest) }}">
                                @csrf
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label" style="font-weight:600;font-size:.8125rem;">New
                                            Start</label>
                                        <input type="date" name="modified_start_date"
                                            class="form-control form-control-sm"
                                            value="{{ $leaveRequest->start_date->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" style="font-weight:600;font-size:.8125rem;">New
                                            End</label>
                                        <input type="date" name="modified_end_date"
                                            class="form-control form-control-sm"
                                            value="{{ $leaveRequest->end_date->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-weight:600;font-size:.8125rem;">Note to
                                        Employee <span class="text-danger">*</span></label>
                                    <textarea name="supervisor_note" rows="3" class="form-control form-control-sm"
                                        placeholder="Explain why you're suggesting these changes..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-info btn-sm w-100">
                                    <i class="bi bi-send me-1"></i>Send Modifications
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Approve / Reject --}}
                <div class="card anim-in mb-4">
                    <div class="card-header">
                        <h5 class="mb-0" style="font-size:.9375rem;">
                            <i class="bi bi-check2-circle me-1"></i>Final Decision
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (in_array($leaveRequest->status, ['sender_okay', 'sender_not_okay']))
                            <div class="alert {{ $leaveRequest->status === 'sender_okay' ? 'alert-success' : 'alert-warning' }}"
                                style="font-size:.8125rem;">
                                <i
                                    class="bi {{ $leaveRequest->status === 'sender_okay' ? 'bi-hand-thumbs-up' : 'bi-hand-thumbs-down' }} me-1"></i>
                                The employee {{ $leaveRequest->status === 'sender_okay' ? 'agreed' : 'disagreed' }}
                                with your modifications.
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;font-size:.8125rem;">Decision Note
                                (optional)</label>
                            <textarea id="decision_note" rows="2" class="form-control form-control-sm"
                                placeholder="Add a note about your decision..."></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <form method="POST"
                                action="{{ orgRoute('admin.leave-requests.approve', $leaveRequest) }}"
                                class="flex-fill"
                                onsubmit="this.querySelector('[name=decision_note]').value = document.getElementById('decision_note').value">
                                @csrf
                                <input type="hidden" name="decision_note" value="">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-lg me-1"></i>Approve
                                </button>
                            </form>
                            <form method="POST"
                                action="{{ orgRoute('admin.leave-requests.reject', $leaveRequest) }}"
                                class="flex-fill"
                                onsubmit="this.querySelector('[name=decision_note]').value = document.getElementById('decision_note').value">
                                @csrf
                                <input type="hidden" name="decision_note" value="">
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-x-lg me-1"></i>Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Timeline --}}
            <div class="card anim-in">
                <div class="card-header">
                    <h5 class="mb-0" style="font-size:.9375rem;">
                        <i class="bi bi-clock-history me-1"></i>Timeline
                    </h5>
                </div>
                <div class="card-body" style="padding:.75rem 1rem;">
                    <div style="position:relative;padding-left:24px;">
                        {{-- Submitted --}}
                        <div style="position:relative;padding-bottom:16px;">
                            <div
                                style="position:absolute;left:-24px;top:2px;width:16px;height:16px;border-radius:50%;background:#3b82f6;border:2px solid #fff;z-index:1;">
                            </div>
                            <div
                                style="position:absolute;left:-17px;top:18px;width:2px;height:calc(100% - 18px);background:var(--border);">
                            </div>
                            <div style="font-size:.8125rem;font-weight:600;">Request Submitted</div>
                            <div style="font-size:.75rem;color:var(--text-muted);">
                                {{ $leaveRequest->created_at->format('M d, Y h:i A') }}</div>
                        </div>

                        @if ($leaveRequest->modified_start_date)
                            <div style="position:relative;padding-bottom:16px;">
                                <div
                                    style="position:absolute;left:-24px;top:2px;width:16px;height:16px;border-radius:50%;background:#f59e0b;border:2px solid #fff;z-index:1;">
                                </div>
                                <div
                                    style="position:absolute;left:-17px;top:18px;width:2px;height:calc(100% - 18px);background:var(--border);">
                                </div>
                                <div style="font-size:.8125rem;font-weight:600;">Modified by Supervisor</div>
                                <div style="font-size:.75rem;color:var(--text-muted);">
                                    {{ $leaveRequest->updated_at->format('M d, Y h:i A') }}</div>
                            </div>
                        @endif

                        @if ($leaveRequest->sender_responded_at)
                            <div style="position:relative;padding-bottom:16px;">
                                <div
                                    style="position:absolute;left:-24px;top:2px;width:16px;height:16px;border-radius:50%;background:{{ $leaveRequest->status === 'sender_okay' || $leaveRequest->status === 'approved' ? '#10b981' : '#6b7280' }};border:2px solid #fff;z-index:1;">
                                </div>
                                <div
                                    style="position:absolute;left:-17px;top:18px;width:2px;height:calc(100% - 18px);background:var(--border);">
                                </div>
                                <div style="font-size:.8125rem;font-weight:600;">Employee Responded:
                                    {{ str_contains($leaveRequest->status, 'okay') || $leaveRequest->status === 'approved' ? 'Okay' : 'Not Okay' }}
                                </div>
                                <div style="font-size:.75rem;color:var(--text-muted);">
                                    {{ $leaveRequest->sender_responded_at->format('M d, Y h:i A') }}</div>
                            </div>
                        @endif

                        @if ($leaveRequest->decided_at)
                            <div style="position:relative;padding-bottom:0;">
                                <div
                                    style="position:absolute;left:-24px;top:2px;width:16px;height:16px;border-radius:50%;background:{{ $leaveRequest->status === 'approved' ? '#10b981' : '#ef4444' }};border:2px solid #fff;z-index:1;">
                                </div>
                                <div style="font-size:.8125rem;font-weight:600;">
                                    {{ $leaveRequest->status === 'approved' ? 'Approved' : 'Rejected' }}</div>
                                <div style="font-size:.75rem;color:var(--text-muted);">
                                    {{ $leaveRequest->decided_at->format('M d, Y h:i A') }}</div>
                            </div>
                        @endif

                        @if (!$leaveRequest->decided_at)
                            <div style="position:relative;padding-bottom:0;">
                                <div
                                    style="position:absolute;left:-24px;top:2px;width:16px;height:16px;border-radius:50%;background:var(--border);border:2px solid #fff;z-index:1;">
                                </div>
                                <div style="font-size:.8125rem;font-weight:600;color:var(--text-muted);">Awaiting
                                    Decision</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
