@php
    $suggestedLocale = \App\Support\LocaleUrl::suggestedLocale(request());
@endphp
@if ($suggestedLocale)
    <div class="locale-suggestion" role="status">
        <a href="{{ \App\Support\LocaleUrl::switch($suggestedLocale) }}"
            onclick="document.cookie='locale_prompt=1;path=/;max-age=31536000;SameSite=Lax'">
            {{ __('nav.suggest.'.$suggestedLocale) }}
        </a>
        <button type="button" aria-label="{{ __('nav.suggest.dismiss') }}"
            onclick="document.cookie='locale_prompt=1;path=/;max-age=31536000;SameSite=Lax'; this.parentElement.remove();">&times;</button>
    </div>
    @once
        <style>
            .locale-suggestion {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 12px;
                background: #0d4036;
                color: #fff;
                padding: 8px 16px;
                font-size: 14px;
                position: relative;
                z-index: 1040;
            }

            .locale-suggestion a {
                color: #fff;
                text-decoration: underline;
            }

            .locale-suggestion button {
                background: transparent;
                border: 0;
                color: #fff;
                font-size: 20px;
                line-height: 1;
                cursor: pointer;
            }
        </style>
    @endonce
@endif
