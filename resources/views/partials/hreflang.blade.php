@foreach (\App\Support\LocaleUrl::alternates() as $code => $href)
    <link rel="alternate" hreflang="{{ $code }}" href="{{ $href }}">
@endforeach
