@php
    $status = strtolower((string) ($status ?? ''));
    $meta = match ($status) {
        'sending' => ['icon' => 'spinner-border spinner-border-sm', 'class' => 'text-muted', 'title' => 'Sending'],
        'queued' => ['icon' => 'bi bi-clock', 'class' => 'text-muted', 'title' => 'Queued'],
        'sent' => ['icon' => 'bi bi-check2', 'class' => 'text-muted', 'title' => 'Sent'],
        'delivered' => ['icon' => 'bi bi-check2-all', 'class' => 'text-muted', 'title' => 'Delivered'],
        'read' => ['icon' => 'bi bi-check2-all', 'class' => 'text-primary', 'title' => 'Read'],
        'failed' => ['icon' => 'bi bi-exclamation-triangle-fill', 'class' => 'text-danger', 'title' => 'Failed'],
        'pending_retry' => ['icon' => 'bi bi-arrow-clockwise', 'class' => 'text-warning', 'title' => 'Retry pending'],
        default => null,
    };
@endphp

@if ($meta)
    <span class="wa-status {{ $meta['class'] }}" title="{{ $meta['title'] }}" aria-label="{{ $meta['title'] }}">
        @if ($status === 'sending')
            <span class="wa-status-spinner spinner-border spinner-border-sm" role="status"></span>
        @else
            <i class="{{ $meta['icon'] }}"></i>
        @endif
    </span>
@endif
