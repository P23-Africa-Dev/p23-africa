@foreach ($applications as $application)
    <tr>
        <td>
            <div>
                <div class="fw-medium text-dark dark:text-gray-100">
                    {{ $application->name ?? 'Anonymous' }}</div>
                <small class="text-muted dark:text-gray-400">{{ $application->email ?? 'No email provided' }}</small>
                @if ($application->session_id)
                    <div><small class="badge badge-light">Session:
                            {{ substr($application->session_id, 0, 8) }}...</small>
                    </div>
                @endif
            </div>
        </td>
        <td class="text-dark dark:text-gray-200">
            {{ $application->company ?? 'N/A' }}</td>
        <td class="text-dark dark:text-gray-200">
            {{ $application->industry ?? 'N/A' }}</td>
        <td>
            @if ($application->isDraft())
                <span class="badge bg-secondary">
                    <i class="fa fa-edit me-1"></i>Draft
                </span>
                @if ($application->hasRecentActivity())
                    <small class="d-block text-success">Active</small>
                @endif
            @elseif ($application->scheduledSessions->count() > 0)
                <span class="badge bg-info">
                    <i class="fa fa-calendar-check me-1"></i>Scheduled
                </span>
            @elseif($application->payments->where('status', 'successful')->count() > 0)
                <span class="badge bg-success">
                    <i class="fa fa-credit-card me-1"></i>Paid
                </span>
            @else
                <span class="badge bg-warning">
                    <i class="fa fa-clock me-1"></i>Pending Payment
                </span>
            @endif
        </td>
        <td>
            @if ($application->isDraft())
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" role="progressbar"
                        style="width: {{ $application->getCompletionPercentage() }}%"
                        aria-valuenow="{{ $application->getCompletionPercentage() }}" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <small class="text-muted">{{ $application->getCompletionPercentage() }}% complete</small>
                @if ($application->keystroke_count > 0)
                    <div><small class="text-info">{{ $application->keystroke_count }} keystrokes</small></div>
                @endif
                @if ($application->last_activity_at)
                    <div><small class="text-muted">Last activity:
                            {{ $application->last_activity_at->diffForHumans() }}</small></div>
                @endif
            @else
                <span class="text-muted">{{ $application->created_at->format('M d, Y H:i') }}</span>
            @endif
        </td>
        <td>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.consultation.show', $application->id) }}"
                    class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-eye"></i> View
                </a>
                @if (!$application->isDraft())
                    <button class="btn btn-sm btn-outline-secondary" onclick="openChatModal({{ $application->id }})">
                        <i class="fa fa-comments"></i> Chat
                        @if ($application->unreadChatMessages->count() > 0)
                            <span class="badge bg-danger ms-1">{{ $application->unreadChatMessages->count() }}</span>
                        @endif
                    </button>
                @endif
            </div>
        </td>
    </tr>
@endforeach
