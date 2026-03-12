@foreach ($applications as $application)
    <tr data-id="{{ $application->id }}">
        @if (auth()->user()->hasRole('admin'))
            <td>
                <input type="checkbox" class="form-check-input application-checkbox" value="{{ $application->id }}">
            </td>
        @endif
        <td>
            <div>
                <div class="fw-medium text-dark dark:text-gray-100">
                    {{ $application->name ?? 'Anonymous' }}</div>
                <small class="text-muted dark:text-gray-400">{{ $application->email ?? 'No email provided' }}</small>
            </div>
        </td>
        <td class="text-dark dark:text-gray-200">
            {{ $application->company ?? 'N/A' }}</td>
        <td class="text-dark dark:text-gray-200">
            {{ $application->industry ?? 'N/A' }}</td>
        <td>
            @if ($application->scheduledSessions->count() > 0)
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
        <td class="text-dark dark:text-gray-200">
            <div><strong>Submitted:</strong>
                {{ $application->created_at->format('M d, Y') }}</div>
            <small class="text-muted dark:text-gray-400">{{ $application->created_at->format('h:i A') }}</small>
        </td>
        <td>
            <div class="d-flex gap-1">
                <a href="{{ orgRoute('admin.consultation.show', $application->id) }}"
                    class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-eye me-1"></i>View
                </a>
                @if (auth()->user()->hasRole('admin'))
                    <button type="button" class="btn btn-sm btn-outline-danger delete-application"
                        data-id="{{ $application->id }}" data-name="{{ $application->name ?? 'this application' }}"
                        title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>
                @endif
            </div>
        </td>
    </tr>
@endforeach
