@extends('layouts.master')
@section('title', 'About | P23 Africa')
@section('body_class', 'p23-black-navbar-page about-page')
@section('meta')
<meta name="description" content="Africa-focused, globally relevant. Meet P23 Africa and explore how local knowledge, trusted relationships, and practical execution help businesses grow.">
<meta property="og:image" content="{{ asset('images/about/hero.png') }}">
<link rel="stylesheet" href="{{ asset('css/services-new.css') }}">
<link rel="stylesheet" href="{{ asset('css/about-new.css') }}">
<script src="{{ asset('js/about-case-studies.js') }}" defer></script>
@endsection
@section('content')
<div class="about-new">
    <section class="ab-hero ab-container" aria-labelledby="about-title">
        <div class="ab-intro">
            <p class="ab-eyebrow">About us</p>
            <div class="ab-hero-copy">
                <h1 id="about-title">Our team is ready<br>to assist you</h1>
                <p><span>Africa-focused,</span> globally relevant. P23 Africa are a team of seasoned professionals helping businesses across sectors scale in a competitive and rapidly-changing market.</p>
            </div>
            <div class="ab-actions">
                <a href="{{ url('contact') }}" class="ab-talk">Talk to Us</a>
                <a href="{{ route('case-studies') }}" class="ab-case-link">
                    <img src="{{ asset('images/about/stars.svg') }}" width="98" height="18" alt="Five stars">
                    <span><strong>Click Here to</strong> <em>Read Case Studies</em></span>
                </a>
            </div>
        </div>
        <div class="ab-hero-image">
            <img src="{{ asset('images/about/hero.png') }}" alt="P23 Africa professionals connecting at a business event" width="4096" height="2878" fetchpriority="high">
        </div>
    </section>
    <section class="ab-trusted ab-container" aria-label="Trusted businesses">
        <p>Trusted by 25k+ businesses</p>
        <div class="ab-logos">
            @foreach (['walmart' => ['Walmart', 117.328], 'cisco' => ['Cisco', 224], 'volvo' => ['Volvo', 53.328], 'deloitte' => ['Deloitte', 224], 'okta' => ['Okta', 73]] as $logo => [$name, $width])
            <div><img src="{{ asset('images/about/'.$logo.'.svg') }}" alt="{{ $name }}" width="{{ $width }}" height="64" loading="lazy"></div>
            @endforeach
        </div>
    </section>
    <section class="ab-spotlight" aria-labelledby="spotlight-title">
        <div class="ab-spotlight-heading ab-container">
            <h2 id="spotlight-title">Executives<br>Spotlight.</h2>
            <p>Real success stories from CEOs who’ve grown through the power of referral-based networking.</p>
        </div>
        <div class="ab-executives">
            @foreach (['executive_one', 'executive_two', 'executive_two'] as $portrait)
            <article class="ab-executive ab-executive-{{ $loop->iteration }}">
                <div class="ab-executive-copy">
                    <h3>Fullname, Title</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p>
                </div>
                <img src="{{ asset('images/about/'.$portrait.'.png') }}" alt="Executive portrait" width="896" height="1200" loading="lazy">
            </article>
            @endforeach
        </div>
    </section>
    <section class="ab-case-studies ab-container" aria-labelledby="case-studies-title">
        <div class="ab-case-heading">
            <h2 id="case-studies-title">Our Case Studies</h2>
            <div class="ab-case-controls" aria-label="Browse case study cards">
                <button type="button" data-case-direction="-1" aria-label="Previous case study cards">←</button>
                <button type="button" data-case-direction="1" aria-label="Next case study cards">→</button>
            </div>
        </div>
        <div class="ab-case-cards" id="about-case-cards" tabindex="0" aria-label="Case studies">
            @foreach ($caseStudies as $study)
            <article class="ab-case-card">
                <h3>{{ $study['title'] }}</h3>
                @if ($study['illustrative'])<span class="ab-example">Illustrative example</span>@endif
                <p>{{ $study['summary'] }}</p>
                <a href="{{ route('case-studies.show', $study['slug']) }}" aria-label="Read more: {{ $study['title'] }}">Read More <img src="{{ asset('images/about/chevron.svg') }}" width="16" height="16" alt=""></a>
            </article>
            @endforeach
        </div>
        <a href="{{ route('case-studies', ['all' => 1]) }}#case-library" class="ab-view-all">View all case studies</a>
    </section>
    <section class="ab-statistics" aria-labelledby="growth-statistic">
        <div class="ab-stat-content">
            <h2 id="growth-statistic">57%</h2>
            <p>of organizations say operational inefficiencies<br>hold back their growth</p>
            <dl>
                <div><dt>68%</dt><dd>Start-ups</dd></div>
                <div><dt>59%</dt><dd>MSMEs</dd></div>
                <div><dt>46%</dt><dd>Enterprises</dd></div>
            </dl>
        </div>
    </section>
</div>
@endsection
@section('footer')
@include('layouts.includes.footer3')
@endsection
