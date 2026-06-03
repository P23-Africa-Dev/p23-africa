@php
    $firstName = trim((string) explode(' ', (string) ($recipientName ?: 'Attendee'))[0]);
    $firstName = $firstName !== '' ? $firstName : 'Attendee';

    $changedFields = $changes['changed_fields'] ?? [];
    $oldValues = $changes['old_values'] ?? [];
    $newValues = $changes['new_values'] ?? [];
@endphp

<p>Hi {{ $firstName }},</p>

<p>We wanted to let you know that an event you registered for has been updated.</p>

<h3>Updated Event Details</h3>
<p>
    <strong>Event Title:</strong> {{ $event->title }}<br>
    <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}<br>
    <strong>Time:</strong> {{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}<br>
    <strong>Location:</strong> {{ $event->venue ?: 'Not set' }}<br>
    <strong>Meeting Link:</strong>
    @if (!empty($event->link))
        <a href="{{ $event->link }}">{{ $event->link }}</a>
    @else
        Not set
    @endif
</p>

<p><strong>Description:</strong><br>{{ trim(strip_tags((string) $event->description)) ?: 'Not set' }}</p>

@if (!empty($changedFields))
    <h3>What Changed</h3>

    @foreach ($changedFields as $field)
        <p>
            <strong>{{ $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field)) }}:</strong><br>
            {{ $oldValues[$field] ?? 'Not set' }}<br>
            &rarr;<br>
            {{ $newValues[$field] ?? 'Not set' }}
        </p>
    @endforeach
@endif

<p>If you have any questions, please contact us at <a href="mailto:support@p23africa.com">support@p23africa.com</a>.</p>

<p>Thanks,<br>P23 Africa Team</p>
