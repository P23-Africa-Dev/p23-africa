@extends('layouts.master')
@section('title', __('service_detail.commercial_sales.meta_title'))
@section('body_class', 'p23-black-navbar-page sd-body')

@section('meta')
    <meta name="description" content="{{ __('service_detail.commercial_sales.meta_description') }}">
    <meta property="og:title" content="{{ __('service_detail.commercial_sales.og_title') }}">
    <meta property="og:description" content="{{ __('service_detail.commercial_sales.og_description') }}">
    <meta property="og:image" content="{{ asset('images/market-entry/practical-execution-handshake.png') }}">
    <link rel="canonical" href="{{ url('services/commercial-sales-team') }}">
    {{-- services-new.css carries the shared footer3 + navbar styles used across the service pages --}}
    <link rel="stylesheet" href="{{ asset('css/services-new.css') }}?v={{ filemtime(public_path('css/services-new.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/service-detail.css') }}?v={{ filemtime(public_path('css/service-detail.css')) }}">

    <script type="application/ld+json">
    {!! json_encode([
      '@context' => 'https://schema.org',
      '@graph' => [
        [
          '@type' => 'Service',
          'name' => __('service_detail.commercial_sales.hero_plain'),
          'serviceType' => 'Dedicated Commercial Sales Team & Local Sales Representation in Africa',
          'provider' => [
            '@type' => 'Organization',
            'name' => 'P23 Africa',
            'url' => 'https://p23africa.com'
          ],
          'url' => url('services/commercial-sales-team'),
          'description' => __('service_detail.commercial_sales.meta_description')
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
              'name' => __('service_detail.commercial_sales.eyebrow'),
              'item' => url('services/commercial-sales-team')
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
    $check = '<svg viewBox="196 2846 21 20" fill="none" aria-hidden="true" focusable="false"><path opacity=".1" d="M206.358 2846C200.656 2846 196.033 2850.48 196.033 2856C196.033 2861.52 200.656 2866 206.358 2866C212.061 2866 216.684 2861.52 216.684 2856C216.677 2850.48 212.058 2846.01 206.358 2846Z" fill="#fff"/><path d="M211.811 2852.83L205.921 2860.57C205.781 2860.75 205.571 2860.87 205.34 2860.9C205.109 2860.87 204.875 2860.87 204.691 2860.73L200.485 2857.47C200.114 2857.19 200.054 2856.66 200.351 2856.3C200.648 2855.94 201.189 2855.89 201.561 2856.17L205.068 2858.89L210.426 2851.85C210.601 2851.59 210.908 2851.45 211.223 2851.48C211.539 2851.51 211.812 2851.7 211.935 2851.99C212.058 2852.27 212.01 2852.59 211.811 2852.83Z" fill="#fff"/></svg>';
@endphp

@section('content')
<div class="sd-page sd-page--commercial-sales">

    {{-- =================================================================
         SECTION 1: HERO
         ================================================================= --}}
    <section class="sd-hero sd-cst-hero" id="hero" style="--hero-h: 580; --btn-gap: 44;">
        <div class="sd-inner sd-cst-hero__inner">
            <div class="sd-hero__content sd-cst-hero__content">
                <p class="sd-hero__eyebrow">{{ __('service_detail.commercial_sales.eyebrow') }}</p>
                <h1 class="sd-hero__title">{!! __('service_detail.commercial_sales.hero') !!}</h1>
                <p class="sd-hero__desc">
                    {{ __('service_detail.commercial_sales.hero_desc') }}
                </p>
                <div class="sd-hero__actions">
                    <a href="{{ locale_route('contact') }}" class="sd-hero__btn" id="sd-hero-commercial-sales-btn">{{ __('service_detail.commercial_sales.book_call') }}</a>
                    <div class="sd-hero__proof">
                        <svg class="sd-hero__stars" viewBox="374 552 98 17" fill="none" role="img" aria-label="{{ __('service_detail.commercial_sales.stars_label') }}">
                            <path d="M383 552L385.021 558.219H391.56L386.269 562.062L388.29 568.281L383 564.438L377.71 568.281L379.731 562.062L374.44 558.219H380.979L383 552Z" fill="#390908"/>
                            <path d="M403 552L405.021 558.219H411.56L406.269 562.062L408.29 568.281L403 564.438L397.71 568.281L399.731 562.062L394.44 558.219H400.979L403 552Z" fill="#390908"/>
                            <path d="M423 552L425.021 558.219H431.56L426.269 562.062L428.29 568.281L423 564.438L417.71 568.281L419.731 562.062L414.44 558.219H420.979L423 552Z" fill="#390908"/>
                            <path d="M443 552L445.021 558.219H451.56L446.269 562.062L448.29 568.281L443 564.438L437.71 568.281L439.731 562.062L434.44 558.219H440.979L443 552Z" fill="#390908"/>
                            <path d="M463 552L465.021 558.219H471.56L466.269 562.062L468.29 568.281L463 564.438L457.71 568.281L459.731 562.062L454.44 558.219H460.979L463 552Z" fill="#390908"/>
                        </svg>
                        <a href="{{ locale_route('case-studies') }}" class="sd-hero__proof-link">
                            {!! __('service_detail.commercial_sales.proof_link') !!}
                        </a>
                    </div>
                </div>
            </div>

            <div class="sd-hero__visual" style="--ox: 815; --vw: 604; --vh: 453; --ar: 604 / 453;">
                <figure class="sd-hero__img" style="--x: 917; --y: 66; --w: 502; --h: 363; --bg: #283749;">
                    <img src="{{ asset('images/market-entry/practical-execution-handshake.png') }}"
                         alt="{{ __('service_detail.commercial_sales.hero_alt_main') }}"
                         loading="eager" fetchpriority="high" decoding="async">
                </figure>
                <figure class="sd-hero__img" style="--x: 815; --y: 325; --w: 235; --h: 174; --bg: #E3E1CF;">
                    <img src="{{ asset('images/services/hero-team.png') }}"
                         alt="{{ __('service_detail.commercial_sales.hero_alt_sub') }}"
                         loading="eager" decoding="async">
                </figure>
                <div class="sd-cst-hero-badge" aria-hidden="true">
                    <span class="sd-cst-hero-badge__dot"></span>
                    <div class="sd-cst-hero-badge__meta">
                        <span class="sd-cst-hero-badge__num">{{ __('service_detail.commercial_sales.hero_badge_num') }}</span>
                        <span class="sd-cst-hero-badge__label">{{ __('service_detail.commercial_sales.hero_badge_label') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =================================================================
         SECTION 2: WHO THIS IS FOR
         ================================================================= --}}
    <section class="sd-cst-who" aria-labelledby="cst-who-heading">
        <div class="sd-inner">
            <div class="sd-cst-who__shell">
                <div class="sd-cst-who__header">
                    <div class="sd-cst-who__title-col">
                        <span class="sd-cst-kicker">{{ __('service_detail.commercial_sales.who_eyebrow') }}</span>
                        <h2 id="cst-who-heading" class="sd-cst-who__title">{{ __('service_detail.commercial_sales.who_title') }}</h2>
                    </div>
                    <div class="sd-cst-who__intro-col">
                        <p class="sd-cst-who__intro">
                            {{ __('service_detail.commercial_sales.who_intro') }}
                        </p>
                    </div>
                </div>

                <div class="sd-cst-who__cards" role="list">
                    <article class="sd-cst-who-card" role="listitem">
                        <div class="sd-cst-who-card__head">
                            <span class="sd-cst-who-card__num" aria-hidden="true">01</span>
                            <span class="sd-cst-who-card__icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22C12 22 19 14.25 19 9C19 5.13 15.87 2 12 2ZM12 11.5C10.62 11.5 9.5 10.38 9.5 9C9.5 7.62 10.62 6.5 12 6.5C13.38 6.5 14.5 7.62 14.5 9C14.5 10.38 13.38 11.5 12 11.5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </div>
                        <p class="sd-cst-who-card__text">{{ __('service_detail.commercial_sales.who_1_text') }}</p>
                    </article>

                    <article class="sd-cst-who-card sd-cst-who-card--navy" role="listitem">
                        <div class="sd-cst-who-card__head">
                            <span class="sd-cst-who-card__num" aria-hidden="true">02</span>
                            <span class="sd-cst-who-card__icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13M16 3.13C16.8604 3.3503 17.623 3.8507 18.1676 4.55231C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89317 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </div>
                        <p class="sd-cst-who-card__text">{{ __('service_detail.commercial_sales.who_2_text') }}</p>
                    </article>

                    <article class="sd-cst-who-card sd-cst-who-card--sand" role="listitem">
                        <div class="sd-cst-who-card__head">
                            <span class="sd-cst-who-card__num" aria-hidden="true">03</span>
                            <span class="sd-cst-who-card__icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M23 18L13.5 8.5L8.5 13.5L1 6M23 18H17M23 18V12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </div>
                        <p class="sd-cst-who-card__text">{{ __('service_detail.commercial_sales.who_3_text') }}</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- =================================================================
         SECTION 3: HOW IT WORKS (4-STEP BENTO ARCHITECTURE)
         ================================================================= --}}
    <section class="sd-stage sd-cst-how" style="--stage-gap: 78;" aria-labelledby="cst-how-heading">
        <div class="sd-inner">
            <div class="sd-cst-how__header">
                <span class="sd-cst-kicker">{{ __('service_detail.commercial_sales.how_eyebrow') }}</span>
                <h2 id="cst-how-heading" class="sd-cst-how__title">{{ __('service_detail.commercial_sales.how_title') }}</h2>
            </div>

            <div class="sd-cst-bento">

                {{-- STEP 1: 1. Market intelligence and company mapping --}}
                <article class="sd-card sd-cst-card sd-cst-card--step1" data-step="1" style="--bg: #283749; --fg: #FFFFFF;">
                    <span class="sd-num" style="--x: 640; --y: 28; --stroke: rgba(255,255,255,0.45);" aria-hidden="true">1</span>
                    <div class="sd-cst-card__body">
                        <div class="sd-cst-step1__lead">
                            <h3 class="sd-card__lead sd-cst-card__heading">
                                {{ __('service_detail.commercial_sales.step_1_title') }}
                            </h3>
                            <p class="sd-card-text sd-gap-8 sd-cst-step1__intro">
                                {{ __('service_detail.commercial_sales.step_1_intro') }}
                            </p>
                        </div>

                        <ul class="sd-cst-signals">
                            <li class="sd-cst-signal">
                                <span class="sd-cst-signal__dot" aria-hidden="true"></span>
                                <span>{{ __('service_detail.commercial_sales.step_1_sig_1') }}</span>
                            </li>
                            <li class="sd-cst-signal">
                                <span class="sd-cst-signal__dot" aria-hidden="true"></span>
                                <span>{{ __('service_detail.commercial_sales.step_1_sig_2') }}</span>
                            </li>
                            <li class="sd-cst-signal">
                                <span class="sd-cst-signal__dot" aria-hidden="true"></span>
                                <span>{{ __('service_detail.commercial_sales.step_1_sig_3') }}</span>
                            </li>
                            <li class="sd-cst-signal">
                                <span class="sd-cst-signal__dot" aria-hidden="true"></span>
                                <span>{{ __('service_detail.commercial_sales.step_1_sig_4') }}</span>
                            </li>
                            <li class="sd-cst-signal">
                                <span class="sd-cst-signal__dot" aria-hidden="true"></span>
                                <span>{{ __('service_detail.commercial_sales.step_1_sig_5') }}</span>
                            </li>
                            <li class="sd-cst-signal">
                                <span class="sd-cst-signal__dot" aria-hidden="true"></span>
                                <span>{{ __('service_detail.commercial_sales.step_1_sig_6') }}</span>
                            </li>
                        </ul>
                    </div>
                </article>

                {{-- STEP 2: 2. Online and offline buying signals --}}
                <article class="sd-card sd-cst-card sd-cst-card--step2" data-step="2" style="--bg: #390908; --fg: #FFFFFF;">
                    <span class="sd-num" style="--x: 420; --y: 28; --stroke: rgba(255,255,255,0.4);" aria-hidden="true">2</span>
                    <div class="sd-cst-card__body sd-cst-card__body--between">
                        <div>
                            <h3 class="sd-card__lead sd-cst-card__heading">
                                {{ __('service_detail.commercial_sales.step_2_title') }}
                            </h3>
                            <p class="sd-card-text sd-gap-20 sd-cst-step2__text">
                                {{ __('service_detail.commercial_sales.step_2_body') }}
                            </p>
                        </div>

                        <div class="sd-cst-flow-chips" aria-hidden="true">
                            <span class="sd-cst-flow-chip">{{ __('service_detail.commercial_sales.step_2_tag_1') }}</span>
                            <span class="sd-cst-flow-chip">{{ __('service_detail.commercial_sales.step_2_tag_2') }}</span>
                            <span class="sd-cst-flow-chip">{{ __('service_detail.commercial_sales.step_2_tag_3') }}</span>
                        </div>
                    </div>
                </article>

                {{-- STEP 3: 3. Direct customer engagement --}}
                <article class="sd-card sd-cst-card sd-cst-card--step3" data-step="3" style="--bg: #FAF4F0; --fg: #283749;">
                    <span class="sd-num" style="--x: 520; --y: 28; --stroke: #283749;" aria-hidden="true">3</span>
                    <div class="sd-cst-card__body sd-cst-card__body--between">
                        <div>
                            <h3 class="sd-card__lead sd-cst-card__heading">
                                {{ __('service_detail.commercial_sales.step_3_title') }}
                            </h3>
                            <p class="sd-card-text sd-gap-8 sd-cst-card__lead-copy">
                                {{ __('service_detail.commercial_sales.step_3_intro') }}
                            </p>
                            <ul class="sd-list sd-card-text sd-gap-20 sd-cst-list">
                                <li>{{ __('service_detail.commercial_sales.step_3_item_1') }}</li>
                                <li>{{ __('service_detail.commercial_sales.step_3_item_2') }}</li>
                                <li>{{ __('service_detail.commercial_sales.step_3_item_3') }}</li>
                                <li>{{ __('service_detail.commercial_sales.step_3_item_4') }}</li>
                                <li>{{ __('service_detail.commercial_sales.step_3_item_5') }}</li>
                            </ul>
                        </div>

                        <div class="sd-cst-callout sd-cst-callout--sand">
                            <p>{{ __('service_detail.commercial_sales.step_3_close') }}</p>
                        </div>
                    </div>
                </article>

                {{-- STEP 4: 4. Live tracking and visibility --}}
                <article class="sd-card sd-cst-card sd-cst-card--step4" data-step="4" style="--bg: #1A283F; --fg: #FFFFFF;">
                    <span class="sd-num" style="--x: 520; --y: 28; --stroke: rgba(255,255,255,0.4);" aria-hidden="true">4</span>
                    <div class="sd-cst-card__body sd-cst-card__body--between">
                        <div>
                            <h3 class="sd-card__lead sd-cst-card__heading">
                                {{ __('service_detail.commercial_sales.step_4_title') }}
                            </h3>
                            <p class="sd-card-text sd-gap-8 sd-cst-card__lead-copy">
                                {{ __('service_detail.commercial_sales.step_4_intro') }}
                            </p>
                            <ul class="sd-list sd-card-text sd-gap-20 sd-cst-list">
                                <li>{{ __('service_detail.commercial_sales.step_4_item_1') }}</li>
                                <li>{{ __('service_detail.commercial_sales.step_4_item_2') }}</li>
                                <li>{{ __('service_detail.commercial_sales.step_4_item_3') }}</li>
                                <li>{{ __('service_detail.commercial_sales.step_4_item_4') }}</li>
                                <li>{{ __('service_detail.commercial_sales.step_4_item_5') }}</li>
                            </ul>
                        </div>

                        <div class="sd-cst-callout sd-cst-callout--live">
                            <span class="sd-cst-live-dot" aria-hidden="true"></span>
                            <p>{{ __('service_detail.commercial_sales.step_4_close') }}</p>
                        </div>
                    </div>
                </article>

            </div>
        </div>
    </section>

    {{-- =================================================================
         SECTION 4: YOU OWN THE INTELLIGENCE + 6-MONTH PROGRAMME + RESULT + CTA
         ================================================================= --}}
    <section class="sd-get sd-get--d sd-get--dark sd-cst-own" style="--get-gap: 78;" aria-labelledby="cst-intel-heading">
        <div class="sd-get__band">
            <div class="sd-inner">
                <span class="sd-cst-kicker sd-cst-kicker--sand">{{ __('service_detail.commercial_sales.intel_eyebrow') }}</span>
                <h2 id="cst-intel-heading" class="sd-get__title sd-cst-own__title">{{ __('service_detail.commercial_sales.intel_title') }}</h2>
                <p class="sd-cst-own__body">
                    {{ __('service_detail.commercial_sales.intel_body') }}
                </p>

                <ul class="sd-pills sd-pills--balanced sd-cst-own__pills">
                    <li class="sd-pill" style="--w: 260; --pl: 22;">{{ __('service_detail.commercial_sales.intel_pill_1') }}</li>
                    <li class="sd-pill" style="--w: 250; --pl: 22;">{{ __('service_detail.commercial_sales.intel_pill_2') }}</li>
                    <li class="sd-pill" style="--w: 290; --pl: 22;">{{ __('service_detail.commercial_sales.intel_pill_3') }}</li>
                    <li class="sd-pill" style="--w: 280; --pl: 22;">{{ __('service_detail.commercial_sales.intel_pill_4') }}</li>
                </ul>
            </div>
        </div>

        <div class="sd-pricing sd-pricing--v2 sd-cst-programme">
            <div class="sd-inner">
                {{-- Left Card: What a typical six-month programme includes --}}
                <article class="sd-price sd-price--navy sd-cst-programme__card" style="--h: 510;">
                    <span class="sd-price__badge">{{ __('service_detail.commercial_sales.prog_badge') }}</span>
                    <h3 class="sd-price__included" style="--inc-gap: 24;">
                        {{ __('service_detail.commercial_sales.prog_title') }}
                    </h3>
                    <ul class="sd-checks sd-cst-programme__checks">
                        <li>{!! $check !!}{{ __('service_detail.commercial_sales.prog_1') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.commercial_sales.prog_2') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.commercial_sales.prog_3') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.commercial_sales.prog_4') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.commercial_sales.prog_5') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.commercial_sales.prog_6') }}</li>
                    </ul>
                </article>

                {{-- Right Stack: The result + Ready to put people on the ground? --}}
                <div class="sd-pricing__stack">
                    <article class="sd-price sd-price--sand sd-cst-result-card" style="--h: 245;">
                        <h3 class="sd-price__included" style="--inc-gap: 8;">{{ __('service_detail.commercial_sales.result_title') }}</h3>
                        <p class="sd-cst-result-card__body">
                            {{ __('service_detail.commercial_sales.result_body') }}
                        </p>
                    </article>

                    <article class="sd-price sd-price--slate sd-cst-cta-card" style="--h: 245;">
                        <h3 class="sd-price__included" style="--inc-gap: 8;">{{ __('service_detail.commercial_sales.cta_title') }}</h3>
                        <div class="sd-cst-cta-card__actions">
                            <a href="{{ locale_route('contact') }}" class="sd-hero__btn sd-cst-cta-card__btn">
                                {{ __('service_detail.commercial_sales.cta_btn') }}
                            </a>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- =================================================================
         SECTION 5: CASE STUDIES (SHARED INCLUDE)
         ================================================================= --}}
    <div class="sd-cases-embed">
        @include('include.featured-case-studies')
    </div>

</div>
@endsection

@section('footer')
    @include('layouts.includes.footer3')
@endsection
