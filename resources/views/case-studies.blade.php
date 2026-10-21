@extends('layouts.master')
@section('title', $caseStudy['client'].' Case Study | P23 Africa')
@section('body_class', 'p23-black-navbar-page case-study-page')
@section('meta')
<meta name="description" content="{{ $caseStudy['summary'] }}">
<link rel="stylesheet" href="{{ asset('css/services-new.css') }}">
<link rel="stylesheet" href="{{ asset('css/case-studies.css') }}?v=3">
@endsection
@section('content')
<section class="cs-hero" aria-labelledby="case-title">
    <h1 id="case-title">From market opportunity<br>to market presence</h1>
</section>
<article class="cs-study" aria-label="{{ $caseStudy['client'] }} case study">
    <section class="cs-snapshot" id="snapshot" aria-labelledby="snapshot-title">
        <div class="cs-summary">
            <h2 id="snapshot-title">Snapshot Box:</h2>
            @if ($caseStudy['illustrative'])
                <p class="cs-example-note">Illustrative example — sample content, not a client result.</p>
            @endif
            <ul>
                @foreach (['client' => 'Client', 'sector' => 'Sector', 'market' => 'Target Market', 'service' => 'Service Used', 'result' => 'Headline Result', 'timeline' => 'Timeline'] as $key => $label)
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
        <h2 id="challenge-title">The Challenge for<br>{{ $caseStudy['client'] }}</h2>
        <p>{{ $caseStudy['challenge'] }}</p>
    </section>
    <section class="cs-process" aria-labelledby="process-title">
        <h2 id="process-title">What We Did:</h2>
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
            <h2>The Data</h2>
            <dl class="cs-metrics">
                @foreach ($caseStudy['metrics'] as $metric)
                <div><dt>{{ $metric['value'] }}</dt><dd>{{ $metric['label'] }}</dd></div>
                @endforeach
            </dl>
            <p class="cs-validation">{{ $caseStudy['validation'] }}</p>
        </div>
        <div class="cs-outcome">
            <h2>The Outcome:</h2>
            <p>{{ $caseStudy['outcome'] }}</p>
            @if ($caseStudy['quote'] ?? null)
                <blockquote class="cs-quote">
                    <p>“{{ $caseStudy['quote'] }}”</p>
                    <footer>Client review, {{ $caseStudy['client'] }}</footer>
                </blockquote>
            @endif
        </div>
    </section>
    <nav class="cs-navigation" aria-label="Browse case studies">
        <a href="{{ route('case-studies.show', $previousStudy['slug']) }}" aria-label="Previous case study: {{ $previousStudy['title'] }}"><img src="{{ asset('images/case-studies/previous.svg') }}" width="16" height="16" alt=""></a>
        <a href="{{ route('case-studies.show', $nextStudy['slug']) }}" aria-label="Next case study: {{ $nextStudy['title'] }}"><img src="{{ asset('images/case-studies/next.svg') }}" width="16" height="16" alt=""></a>
    </nav>
    <details class="cs-library" id="case-library" @if (request()->boolean('all')) open @endif>
        <summary>Explore all case studies</summary>
        <ul>
            @foreach ($caseStudies as $study)
            <li><a href="{{ route('case-studies.show', $study['slug']) }}" @if ($study['slug'] === $caseStudy['slug']) aria-current="page" @endif>{{ $study['title'] }}@if ($study['illustrative']) <span>(Illustrative example)</span>@endif</a></li>
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
        <a href="{{ url('book-consultation') }}" class="cs-discovery-link">Book a Discovery Call</a>
    </section>
    @include('layouts.includes.footer3')
</div>
@endsection
