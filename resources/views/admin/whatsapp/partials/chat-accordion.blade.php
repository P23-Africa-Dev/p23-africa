@php
    $accordionKey = $key ?? null;
    $open = (bool) ($open ?? false);
    $title = $title ?? '';
    $bodyHtml = $bodyHtml ?? '';
@endphp

<details class="wa-accordion" @if ($accordionKey) data-wa-accordion="{{ $accordionKey }}" @endif
    @if ($open) open @endif>
    <summary>
        <span class="wa-accordion-title">{{ $title }}</span>
        <i class="bi bi-chevron-down wa-accordion-chevron"></i>
    </summary>
    <div class="wa-accordion-body">
        {!! $bodyHtml !!}
    </div>
</details>
