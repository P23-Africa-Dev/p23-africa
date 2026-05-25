@php
    $type = $type ?? 'pill';
    $value = $value ?? null;
    $label = $label ?? null;
    $status = strtolower((string) ($status ?? 'pending'));

    $priorityClass = match ($status) {
        'high', 'urgent' => 'wa-badge-priority-high',
        'medium' => 'wa-badge-priority-medium',
        default => 'wa-badge-priority-low',
    };

    $validationIcon = match ($status) {
        'valid' => 'bi-patch-check-fill text-success',
        'invalid' => 'bi-patch-exclamation-fill text-danger',
        'failed' => 'bi-exclamation-triangle-fill text-warning',
        default => 'bi-hourglass-split text-warning',
    };

    $validationTitle = match ($status) {
        'valid' => 'WhatsApp valid',
        'invalid' => 'WhatsApp invalid',
        'failed' => 'Validation failed',
        default => 'Validation pending',
    };
@endphp

@if ($type === 'validation-icon')
    <span class="wa-validation-icon" title="{{ $validationTitle }}" aria-label="{{ $validationTitle }}">
        <i class="bi {{ $validationIcon }}"></i>
    </span>
@elseif ($type === 'unread' && (int) $value > 0)
    <span class="wa-mini-badge wa-mini-badge-unread" data-wa-sidebar-unread>{{ (int) $value }}</span>
@elseif ($type === 'priority' && $label)
    <span class="wa-mini-badge {{ $priorityClass }}">{{ $label }}</span>
@elseif ($label)
    <span class="wa-mini-badge">{{ $label }}</span>
@endif
