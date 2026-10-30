@extends('layouts.master')
@section('title', $caseStudy['client'].' Case Study | P23 Africa')
@section('body_class', 'p23-black-navbar-page case-study-page')
@section('meta')
<meta name="description" content="{{ $caseStudy['summary'] }}">
<link rel="stylesheet" href="{{ asset('css/services-new.css') }}">
<link rel="stylesheet" href="{{ asset('css/case-studies.css') }}?v=7">
@endsection
@section('content')
<section class="cs-hero" aria-labelledby="case-title">
    <h1 id="case-title">{!! __('case_page.hero') !!}</h1>
</section>
<article class="cs-study" aria-label="{{ $caseStudy['client'] }} case study">
    <section class="cs-snapshot" id="snapshot" aria-labelledby="snapshot-title">
        <div class="cs-summary">
            <h2 id="snapshot-title">{{ __('case_page.snapshot') }}</h2>
            @if ($caseStudy['illustrative'])
                <p class="cs-example-note">{{ __('case_page.illustrative') }}</p>
            @endif
            <ul>
                @foreach (['client' => __('case_page.client'), 'sector' => __('case_page.sector'), 'market' => __('case_page.market'), 'service' => __('case_page.service'), 'result' => __('case_page.result'), 'timeline' => __('case_page.timeline')] as $key => $label)
                <li>{{ $label }}: {{ $caseStudy[$key] }}</li>
                @endforeach
            </ul>
        </div>
        <figure class="cs-visual">
            <img src="{{ asset($caseStudy['image']) }}" alt="{{ $caseStudy['image_alt'] }}" width="1068" height="602" fetchpriority="high">
            <figcaption>{{ $caseStudy['client'] }}</figcaption>
        </figure>
    </section>
    <section class="cs-challenge" aria-labelledby="challenge-title">
        <h2 id="challenge-title">{{ __('case_page.challenge') }}<br>
        {{ $caseStudy['client'] }}
    </h2>
        <p>{{ $caseStudy['challenge'] }}</p>
    </section>
    <section class="cs-process" aria-labelledby="process-title">
        <h2 id="process-title">{{ __('case_page.process') }}</h2>
        <div class="cs-cards">
            @foreach ($caseStudy['process'] as $step)
            <div class="cs-card">
                <div class="cs-icon"><img src="{{ asset('images/case-studies/'.$step['icon'].'.svg') }}" alt="" width="50" height="50"></div>
                <h3>{{ $step['title'] }}</h3>
                <p>{{ $step['description'] }}</p>
            </div>
            @endforeach
        </div>
    </section>
    <section class="cs-results" aria-label="Results and outcome">
        <div class="cs-data">
            <h2>{{ __('case_page.data') }}</h2>
            <dl class="cs-metrics">
                @foreach ($caseStudy['metrics'] as $metric)
                <div><dt>{{ $metric['value'] }}</dt><dd>{{ $metric['label'] }}</dd></div>
                @endforeach
            </dl>
            <p class="cs-validation">{{ $caseStudy['validation'] }}</p>
        </div>
        <div class="cs-outcome">
            <h2>{{ __('case_page.outcome') }}</h2>
            <p>{{ $caseStudy['outcome'] }}</p>
            <!-- @if ($caseStudy['quote'] ?? null)
                <blockquote class="cs-quote">
                    <p>“{{ $caseStudy['quote'] }}”</p>
                    <footer>Client review, {{ $caseStudy['client'] }}</footer>
                </blockquote>
            @endif -->
        </div>
    </section>
    <nav class="cs-navigation" aria-label="Browse case studies">
        <a href="{{ locale_route('case-studies.show', ['slug' => $previousStudy['slug']]) }}" aria-label="{{ __('case_page.prev', ['title' => $previousStudy['title']]) }}"><img src="{{ asset('images/case-studies/previous.svg') }}" width="16" height="16" alt=""></a>
        <a href="{{ locale_route('case-studies.show', ['slug' => $nextStudy['slug']]) }}" aria-label="{{ __('case_page.next', ['title' => $nextStudy['title']]) }}"><img src="{{ asset('images/case-studies/next.svg') }}" width="16" height="16" alt=""></a>
    </nav>
    <details class="cs-library" id="case-library" @if (request()->boolean('all')) open @endif>
        <summary>{{ __('case_page.explore') }}</summary>
        <ul>
            @foreach ($caseStudies as $study)
            <li><a href="{{ locale_route('case-studies.show', ['slug' => $study['slug']]) }}" @if ($study['slug'] === $caseStudy['slug']) aria-current="page" @endif>{{ $study['title'] }}@if ($study['illustrative']) <span>{{ __('case_page.illustrative_short') }}</span>@endif</a></li>
            @endforeach
        </ul>
    </details>
</article>
@endsection
@section('footer')
<div class="cs-closing">
    <section class="cs-discovery" id="discovery" aria-label="Book a discovery call">
        <h2>{{ $caseStudy['cta_heading'] }}</h2>
        <p>{{ $caseStudy['cta_description'] }}</p>
        <a href="{{ locale_route('contact') }}" class="cs-discovery-link">{{ __('case_page.discovery') }}</a>
    </section>
    @include('layouts.includes.footer3')
</div>
@endsection
