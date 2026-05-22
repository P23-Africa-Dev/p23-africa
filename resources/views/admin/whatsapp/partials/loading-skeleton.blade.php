<div class="wa-skeleton-list" aria-hidden="true">
    @for ($i = 0; $i < 4; $i++)
        <div class="wa-skeleton-row {{ $i % 2 === 0 ? 'incoming' : 'outgoing' }}">
            <div class="wa-skeleton-bubble"></div>
        </div>
    @endfor
</div>
