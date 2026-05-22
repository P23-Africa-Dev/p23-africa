@php
    $incoming = (bool) ($incoming ?? false);
    $status = strtolower((string) ($status ?? ($message->status ?? 'queued')));
    $messageId = $message->id ?? null;
    $messageBody = $message->body ?? '';
    $timeValue = optional($message->created_at ?? ($message->sent_at ?? $message->updated_at))->format('H:i');
@endphp

<div class="wa-msg-row {{ $incoming ? 'incoming' : 'outgoing' }} {{ $status === 'failed' ? 'is-failed' : '' }}"
    @if ($messageId) data-message-id="{{ $messageId }}" @endif
    data-message-status="{{ $status }}"
    data-message-updated-at="{{ optional($message->updated_at ?? $message->created_at)->toIso8601String() }}"
    data-message-body="{{ e($messageBody) }}">
    <div class="wa-bubble {{ $incoming ? 'incoming' : 'outgoing' }} {{ $status === 'sending' ? 'is-optimistic' : '' }}">
        <div class="wa-bubble-text">{!! nl2br(e($messageBody ?: '[No text body]')) !!}</div>
        <div class="wa-meta">
            <span>{{ $timeValue }}</span>
            <span class="d-inline-flex align-items-center gap-2">
                @if (!$incoming && $status === 'failed')
                    <button type="button" class="wa-retry-btn" data-wa-retry="1" data-wa-body="{{ e($messageBody) }}">
                        <i class="bi bi-arrow-clockwise"></i>
                        <span>Retry</span>
                    </button>
                @endif
                @if (!$incoming)
                    <span data-wa-status-slot>
                        @include('admin.whatsapp.partials.message-status', ['status' => $status])
                    </span>
                @endif
            </span>
        </div>
    </div>
</div>
