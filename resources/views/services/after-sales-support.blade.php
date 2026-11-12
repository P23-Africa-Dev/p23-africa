@extends('layouts.master')
@section('title', __('service_detail.after_sales.meta_title'))
@section('body_class', 'p23-black-navbar-page sd-body')

@section('meta')
    <meta name="description"
        content="{{ __('service_detail.after_sales.meta_description') }}">
    <meta property="og:title" content="{{ __('service_detail.after_sales.og_title') }}">
    <meta property="og:description" content="{{ __('service_detail.after_sales.og_description') }}">
    <meta property="og:image" content="{{ asset('images/services/sd-after-sales-hero.jpg') }}">
    <link rel="canonical" href="{{ url('services/after-sales-support') }}">
    {{-- services-new.css carries the shared footer3 + navbar styles used across the service pages --}}
    <link rel="stylesheet" href="{{ asset('css/services-new.css') }}?v={{ filemtime(public_path('css/services-new.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/service-detail.css') }}?v={{ filemtime(public_path('css/service-detail.css')) }}">

    <script type="application/ld+json">
    {!! json_encode([
      '@context' => 'https://schema.org',
      '@graph' => [
        [
          '@type' => 'Service',
          'name' => 'After-Sales Support for Equipment in Africa',
          'serviceType' => 'Local Equipment Service Desk, Warranty Triage & Field Engineer Coordination',
          'provider' => [
            '@type' => 'Organization',
            'name' => 'P23 Africa',
            'url' => 'https://p23africa.com'
          ],
          'url' => url('services/after-sales-support'),
          'description' => __('service_detail.after_sales.meta_description')
        ],
        [
          '@type' => 'BreadcrumbList',
          'itemListElement' => [
            [
              '@type' => 'ListItem',
              'position' => 1,
              'name' => 'Home',
              'item' => url('/')
            ],
            [
              '@type' => 'ListItem',
              'position' => 2,
              'name' => 'Services',
              'item' => url('services')
            ],
            [
              '@type' => 'ListItem',
              'position' => 3,
              'name' => 'After-Sales Support',
              'item' => url('services/after-sales-support')
            ]
          ]
        ]
      ]
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endsection

@section('navbar')
    @include('layouts.includes.navbar-v2', ['theme' => 'dark', 'active' => 'what-we-do'])
@endsection

@php
    $check = '<svg viewBox="196 2846 21 20" fill="none" aria-hidden="true" focusable="false"><path opacity=".1" d="M206.358 2846C200.656 2846 196.033 2850.48 196.033 2856C196.033 2861.52 200.656 2866 206.358 2866C212.061 2866 216.684 2861.52 216.684 2856C216.677 2850.48 212.058 2846.01 206.358 2846Z" fill="#fff"/><path d="M211.811 2852.83L205.921 2860.57C205.781 2860.75 205.571 2860.87 205.34 2860.9C205.109 2860.93 204.875 2860.87 204.691 2860.73L200.485 2857.47C200.114 2857.19 200.054 2856.66 200.351 2856.3C200.648 2855.94 201.189 2855.89 201.561 2856.17L205.068 2858.89L210.426 2851.85C210.601 2851.59 210.908 2851.45 211.223 2851.48C211.539 2851.51 211.812 2851.7 211.935 2851.99C212.058 2852.27 212.01 2852.59 211.811 2852.83Z" fill="#fff"/></svg>';
@endphp

@section('content')
<div class="sd-page sd-page--after-sales">

    {{-- ==========================================
         SECTION 1: HERO
         ========================================== --}}
    <section class="sd-hero" id="hero" style="--hero-h: 536; --btn-gap: 44;">
        <div class="sd-inner">
            <div class="sd-hero__content">
                <p class="sd-hero__eyebrow">{{ __('service_detail.after_sales.eyebrow') }}</p>
                <h1 class="sd-hero__title">
                    {!! __('service_detail.after_sales.hero') !!}
                </h1>
                <p class="sd-hero__desc">
                    {{ __('service_detail.after_sales.hero_desc') }}
                </p>
                <div class="sd-hero__actions">
                    <a href="{{ locale_route('contact') }}" class="sd-hero__btn" id="sd-hero-after-sales-btn">{{ __('service_detail.after_sales.get_in_touch') }}</a>
                    <div class="sd-hero__proof">
                        <svg class="sd-hero__stars" viewBox="374 552 98 17" fill="none" role="img" aria-label="{{ __('service_detail.after_sales.stars_label') }}">
                            <path d="M383 552L385.021 558.219H391.56L386.269 562.062L388.29 568.281L383 564.438L377.71 568.281L379.731 562.062L374.44 558.219H380.979L383 552Z" fill="#390908"/>
                            <path d="M403 552L405.021 558.219H411.56L406.269 562.062L408.29 568.281L403 564.438L397.71 568.281L399.731 562.062L394.44 558.219H400.979L403 552Z" fill="#390908"/>
                            <path d="M423 552L425.021 558.219H431.56L426.269 562.062L428.29 568.281L423 564.438L417.71 568.281L419.731 562.062L414.44 558.219H420.979L423 552Z" fill="#390908"/>
                            <path d="M443 552L445.021 558.219H451.56L446.269 562.062L448.29 568.281L443 564.438L437.71 568.281L439.731 562.062L434.44 558.219H440.979L443 552Z" fill="#390908"/>
                            <path d="M463 552L465.021 558.219H471.56L466.269 562.062L468.29 568.281L463 564.438L457.71 568.281L459.731 562.062L454.44 558.219H460.979L463 552Z" fill="#390908"/>
                        </svg>
                        <a href="{{ locale_route('case-studies') }}" class="sd-hero__proof-link">{!! __('service_detail.after_sales.proof_link') !!}</a>
                    </div>
                </div>
            </div>

            <div class="sd-hero__visual" style="--ox: 791; --vw: 666; --vh: 536; --ar: 666 / 536;">
                <div class="sd-hero__img" style="--x: 791; --y: 0; --w: 666; --h: 536; --bg: #D9D9D9;">
                    <img src="{{ asset('images/services/sd-after-sales-hero.jpg') }}" alt="{{ __('service_detail.after_sales.hero_alt') }}" loading="eager" fetchpriority="high">
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         SECTION 2: THE PROBLEM & WHAT WE DO
         ========================================== --}}
    <section class="sd-as-overview" id="problem-and-solution">
        <div class="sd-inner">
            <div class="sd-as-split">

                {{-- Left Column: The Problem --}}
                <article class="sd-as-problem">
                    <div>
                        <h2 class="sd-as-problem__title">{{ __('service_detail.after_sales.problem_title') }}</h2>
                        <p class="sd-as-problem__intro">{{ __('service_detail.after_sales.problem_intro') }}</p>
                        <ul class="sd-as-problem__list">
                            <li>{{ __('service_detail.after_sales.problem_1') }}</li>
                            <li>{{ __('service_detail.after_sales.problem_2') }}</li>
                            <li>{{ __('service_detail.after_sales.problem_3') }}</li>
                            <li>{{ __('service_detail.after_sales.problem_4') }}</li>
                            <li>{{ __('service_detail.after_sales.problem_5') }}</li>
                        </ul>
                    </div>
                    <div class="sd-as-problem__callout">
                        {{ __('service_detail.after_sales.problem_close') }}
                    </div>
                </article>

                {{-- Right Column: What We Do --}}
                <div class="sd-as-what">
                    <h2 class="sd-as-what__title">{{ __('service_detail.after_sales.what_title') }}</h2>
                    <p class="sd-as-what__intro">{{ __('service_detail.after_sales.what_intro') }}</p>

                    <div class="sd-as-what__grid">
                        <div class="sd-as-what__item">
                            <h3 class="sd-as-what__label">{{ __('service_detail.after_sales.what_1_label') }}</h3>
                            <p class="sd-as-what__text">{{ __('service_detail.after_sales.what_1_text') }}</p>
                        </div>
                        <div class="sd-as-what__item">
                            <h3 class="sd-as-what__label">{{ __('service_detail.after_sales.what_2_label') }}</h3>
                            <p class="sd-as-what__text">{{ __('service_detail.after_sales.what_2_text') }}</p>
                        </div>
                        <div class="sd-as-what__item">
                            <h3 class="sd-as-what__label">{{ __('service_detail.after_sales.what_3_label') }}</h3>
                            <p class="sd-as-what__text">{{ __('service_detail.after_sales.what_3_text') }}</p>
                        </div>
                        <div class="sd-as-what__item">
                            <h3 class="sd-as-what__label">{{ __('service_detail.after_sales.what_4_label') }}</h3>
                            <p class="sd-as-what__text">{{ __('service_detail.after_sales.what_4_text') }}</p>
                        </div>
                        <div class="sd-as-what__item">
                            <h3 class="sd-as-what__label">{{ __('service_detail.after_sales.what_5_label') }}</h3>
                            <p class="sd-as-what__text">{{ __('service_detail.after_sales.what_5_text') }}</p>
                        </div>
                        <div class="sd-as-what__item">
                            <h3 class="sd-as-what__label">{{ __('service_detail.after_sales.what_6_label') }}</h3>
                            <p class="sd-as-what__text">{{ __('service_detail.after_sales.what_6_text') }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ==========================================
         SECTION 3: HOW IT WORKS (7-STEP BENTO)
         ========================================== --}}
    <section class="sd-stage" id="how-it-works" style="--stage-gap: 80;">
        <div class="sd-inner">
            <h2 class="sd-stage__title">{{ __('service_detail.after_sales.how_title') }}</h2>

            <div class="sd-bento-as">
                {{-- Step 1 --}}
                <article class="sd-card" data-step="1" style="--h: 240; --bg: #283749; --pt: 32; --pl: 34; --pb: 32; --mw: 330;">
                    <span class="sd-num" style="--x: 335; --y: 18;" aria-hidden="true">1</span>
                    <div class="sd-card__inner">
                        <h3 class="sd-card-title">{{ __('service_detail.after_sales.step_1_title') }}</h3>
                        <p class="sd-card-text sd-gap-20">{{ __('service_detail.after_sales.step_1_text') }}</p>
                    </div>
                </article>

                {{-- Step 2 --}}
                <article class="sd-card" data-step="2" style="--h: 240; --bg: #FAF4F0; --fg: #283749; --pt: 32; --pl: 34; --pb: 32; --mw: 330;">
                    <span class="sd-num" style="--x: 335; --y: 18; --stroke: #283749;" aria-hidden="true">2</span>
                    <div class="sd-card__inner">
                        <h3 class="sd-card-title">{{ __('service_detail.after_sales.step_2_title') }}</h3>
                        <p class="sd-card-text sd-gap-20">{{ __('service_detail.after_sales.step_2_text') }}</p>
                    </div>
                </article>

                {{-- Step 3 --}}
                <article class="sd-card" data-step="3" style="--h: 240; --bg: #390908; --pt: 32; --pl: 34; --pb: 32; --mw: 330;">
                    <span class="sd-num" style="--x: 335; --y: 18;" aria-hidden="true">3</span>
                    <div class="sd-card__inner">
                        <h3 class="sd-card-title">{{ __('service_detail.after_sales.step_3_title') }}</h3>
                        <p class="sd-card-text sd-gap-20">{{ __('service_detail.after_sales.step_3_text') }}</p>
                    </div>
                </article>

                {{-- Step 4 (Span 2 columns on desktop) --}}
                <article class="sd-card sd-card--span-2" data-step="4" style="--h: 230; --bg: #E3E1CF; --fg: #283749; --pt: 32; --pl: 36; --pb: 32; --mw: 680;">
                    <span class="sd-num" style="--x: 745; --y: 18; --stroke: #283749;" aria-hidden="true">4</span>
                    <div class="sd-card__inner">
                        <h3 class="sd-card-title">{{ __('service_detail.after_sales.step_4_title') }}</h3>
                        <p class="sd-card-text sd-gap-20">{{ __('service_detail.after_sales.step_4_text') }}</p>
                    </div>
                </article>

                {{-- Step 5 --}}
                <article class="sd-card" data-step="5" style="--h: 230; --bg: #587C90; --pt: 32; --pl: 34; --pb: 32; --mw: 330;">
                    <span class="sd-num" style="--x: 335; --y: 18;" aria-hidden="true">5</span>
                    <div class="sd-card__inner">
                        <h3 class="sd-card-title">{{ __('service_detail.after_sales.step_5_title') }}</h3>
                        <p class="sd-card-text sd-gap-20">{{ __('service_detail.after_sales.step_5_text') }}</p>
                    </div>
                </article>

                {{-- Step 6 --}}
                <article class="sd-card" data-step="6" style="--h: 220; --bg: #1A283F; --pt: 32; --pl: 34; --pb: 32; --mw: 330;">
                    <span class="sd-num" style="--x: 335; --y: 18;" aria-hidden="true">6</span>
                    <div class="sd-card__inner">
                        <h3 class="sd-card-title">{{ __('service_detail.after_sales.step_6_title') }}</h3>
                        <p class="sd-card-text sd-gap-20">{{ __('service_detail.after_sales.step_6_text') }}</p>
                    </div>
                </article>

                {{-- Step 7 (Span 2 columns on desktop) --}}
                <article class="sd-card sd-card--span-2" data-step="7" style="--h: 220; --bg: #283749; --pt: 32; --pl: 36; --pb: 32; --mw: 680;">
                    <span class="sd-num" style="--x: 745; --y: 18;" aria-hidden="true">7</span>
                    <div class="sd-card__inner">
                        <h3 class="sd-card-title">{{ __('service_detail.after_sales.step_7_title') }}</h3>
                        <p class="sd-card-text sd-gap-20">{{ __('service_detail.after_sales.step_7_text') }}</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- ==========================================
         SECTION 4: WHAT YOU GET + SETTING UP + WHO CONTRACTS WITH WHOM
         (Matches the Distributor page dark band + overlapping cards layout)
         ========================================== --}}
    <section class="sd-get sd-get--d sd-get--dark" id="what-you-get" style="--get-gap: 76;">
        <div class="sd-get__band">
            <div class="sd-inner">
                <h2 class="sd-get__title">{{ __('service_detail.after_sales.get_title') }}</h2>
                <p class="sd-get__sub my-3">{!! __('service_detail.after_sales.get_sub') !!}</p>

                <ul class="sd-pills sd-pills--balanced">
                    <li class="sd-pill" style="--w: 390; --pl: 24;">{{ __('service_detail.after_sales.pill_1') }}</li>
                    <li class="sd-pill" style="--w: 340; --pl: 24;">{{ __('service_detail.after_sales.pill_2') }}</li>
                    <li class="sd-pill" style="--w: 445; --pl: 24;">{{ __('service_detail.after_sales.pill_3') }}</li>
                    <li class="sd-pill" style="--w: 440; --pl: 24;">{{ __('service_detail.after_sales.pill_4') }}</li>
                    <li class="sd-pill" style="--w: 610; --pl: 24;">{{ __('service_detail.after_sales.pill_5') }}</li>
                </ul>
            </div>
        </div>

        <div class="sd-pricing sd-pricing--v2" id="setting-up">
            <div class="sd-inner">
                {{-- Left Card: Setting up --}}
                <article class="sd-price sd-price--navy" style="--h: 690;">
                    <span class="sd-price__badge">{{ __('service_detail.after_sales.setup_title') }}</span>
                    <p class="sd-price__desc">{{ __('service_detail.after_sales.setup_intro') }}</p>
                    <h3 class="sd-price__included">{{ __('service_detail.after_sales.setup_title') }}</h3>
                    <ul class="sd-checks">
                        <li>{!! $check !!}<strong>{{ __('service_detail.after_sales.setup_1_label') }}:</strong> {{ __('service_detail.after_sales.setup_1_text') }}</li>
                        <li>{!! $check !!}<strong>{{ __('service_detail.after_sales.setup_2_label') }}:</strong> {{ __('service_detail.after_sales.setup_2_text') }}</li>
                        <li>{!! $check !!}<strong>{{ __('service_detail.after_sales.setup_3_label') }}:</strong> {{ __('service_detail.after_sales.setup_3_text') }}</li>
                        <li>{!! $check !!}<strong>{{ __('service_detail.after_sales.setup_4_label') }}:</strong> {{ __('service_detail.after_sales.setup_4_text') }}</li>
                        <li>{!! $check !!}<strong>{{ __('service_detail.after_sales.setup_5_label') }}:</strong> {{ __('service_detail.after_sales.setup_5_text') }}</li>
                        <li>{!! $check !!}<strong>{{ __('service_detail.after_sales.setup_6_label') }}:</strong> {{ __('service_detail.after_sales.setup_6_text') }}</li>
                        <li>{!! $check !!}<strong>{{ __('service_detail.after_sales.setup_7_label') }}:</strong> {{ __('service_detail.after_sales.setup_7_text') }}</li>
                    </ul>
                </article>

                <div class="sd-pricing__stack">
                    {{-- Top Right Card: Who contracts with whom --}}
                    <article class="sd-price sd-price--sand" id="who-contracts-with-whom" style="--h: 340;">
                        <span class="sd-price__badge">{{ __('service_detail.after_sales.contract_title') }}</span>
                        <h3 class="sd-price__included" style="--inc-gap: 24;">{{ __('service_detail.after_sales.contract_title') }}</h3>
                        <p class="sd-price__desc sd-price__desc--auto" style="margin-top: calc(18 * var(--u)) !important; line-height: 1.65;">
                            {{ __('service_detail.after_sales.contract_body') }}
                        </p>
                    </article>

                    {{-- Bottom Right Card: Need support on the ground? CTA --}}
                    <article class="sd-price sd-price--slate" id="cta" style="--h: 331;">
                        <span class="sd-price__badge">{{ __('service_detail.after_sales.eyebrow') }}</span>
                        <h3 class="sd-price__included" style="--inc-gap: 22;">{{ __('service_detail.after_sales.cta_title') }}</h3>
                        <p class="sd-price__desc sd-price__desc--auto" style="margin-top: calc(14 * var(--u)) !important; line-height: 1.55;">
                            {{ __('service_detail.after_sales.cta_desc') }}
                        </p>
                        <div class="sd-price__foot" style="--gap: 28;">
                            <a href="{{ locale_route('contact') }}" class="sd-hero__btn" id="sd-cta-after-sales-btn" style="margin-top: 0;">
                                {{ __('service_detail.after_sales.cta_btn') }}
                            </a>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         CASE STUDIES (same block as the Distributor page)
         ========================================== --}}
    <div class="sd-cases-embed">
        @include('include.featured-case-studies')
    </div>

</div>
@endsection

@section('footer')
    @include('layouts.includes.footer3')
@endsection
