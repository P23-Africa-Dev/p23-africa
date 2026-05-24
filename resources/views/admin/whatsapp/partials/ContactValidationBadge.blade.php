@php
    $contact = $contact ?? null;
    $status = $status ?? ($contact?->validation_status ?: 'pending');

    $label = match ($status) {
        'valid' => 'WhatsApp Verified',
        'invalid' => 'Not on WhatsApp',
        'failed' => 'Validation Failed',
        default => 'Validation Pending',
    };

    $class = match ($status) {
        'valid' => 'bg-success',
        'invalid' => 'bg-danger',
        'failed' => 'bg-warning text-dark',
        default => 'bg-secondary',
    };
@endphp

<span class="badge {{ $class }}" data-wa-validation-badge>{{ $label }}</span>
