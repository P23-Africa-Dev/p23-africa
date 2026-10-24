@extends('layouts.master')
@section('title', __('service_detail.distributor.meta_title'))
@section('body_class', 'p23-black-navbar-page sd-body')

@section('meta')
    <meta name="description"
        content="{{ __('service_detail.distributor.meta_description') }}">
    <meta property="og:title" content="{{ __('service_detail.distributor.og_title') }}">
    <meta property="og:description" content="{{ __('service_detail.distributor.og_description') }}">
    <meta property="og:image" content="{{ asset('images/services/sd-distributor-hero-1.jpg') }}">
    {{-- services-new.css carries the shared footer3 + navbar styles used across the service pages --}}
    <link rel="stylesheet" href="{{ asset('css/services-new.css') }}">
    <link rel="stylesheet" href="{{ asset('css/service-detail.css') }}">
@endsection

@section('navbar')
    @include('layouts.includes.navbar-v2', ['theme' => 'dark', 'active' => 'what-we-do'])
@endsection

@php
    $check = '<svg viewBox="196 2846 21 20" fill="none" aria-hidden="true" focusable="false"><path opacity=".1" d="M206.358 2846C200.656 2846 196.033 2850.48 196.033 2856C196.033 2861.52 200.656 2866 206.358 2866C212.061 2866 216.684 2861.52 216.684 2856C216.677 2850.48 212.058 2846.01 206.358 2846Z" fill="#fff"/><path d="M211.811 2852.83L205.921 2860.57C205.781 2860.75 205.571 2860.87 205.34 2860.9C205.109 2860.93 204.875 2860.87 204.691 2860.73L200.485 2857.47C200.114 2857.19 200.054 2856.66 200.351 2856.3C200.648 2855.94 201.189 2855.89 201.561 2856.17L205.068 2858.89L210.426 2851.85C210.601 2851.59 210.908 2851.45 211.223 2851.48C211.539 2851.51 211.812 2851.7 211.935 2851.99C212.058 2852.27 212.01 2852.59 211.811 2852.83Z" fill="#fff"/></svg>';
@endphp

@section('content')
<div class="sd-page sd-page--distributor">

    {{-- ==========================================
         HERO
         ========================================== --}}
    <section class="sd-hero" id="hero" style="--hero-h: 564;">
        <div class="sd-inner">
            <div class="sd-hero__content">
                <h1 class="sd-hero__title">
                    {!! __('service_detail.distributor.hero') !!}
                </h1>
                <p class="sd-hero__desc">
                    {{ __('service_detail.distributor.hero_desc') }}
                </p>
                <div class="sd-hero__actions">
                    <a href="{{ locale_route('contact') }}" class="sd-hero__btn" id="sd-hero-get-in-touch-btn">{{ __('service_detail.distributor.get_in_touch') }}</a>
                    <div class="sd-hero__proof">
                        <svg class="sd-hero__stars" viewBox="374 552 98 17" fill="none" role="img" aria-label="{{ __('service_detail.distributor.stars_label') }}">
                            <path d="M383 552L385.021 558.219H391.56L386.269 562.062L388.29 568.281L383 564.438L377.71 568.281L379.731 562.062L374.44 558.219H380.979L383 552Z" fill="#390908"/>
                            <path d="M403 552L405.021 558.219H411.56L406.269 562.062L408.29 568.281L403 564.438L397.71 568.281L399.731 562.062L394.44 558.219H400.979L403 552Z" fill="#390908"/>
                            <path d="M423 552L425.021 558.219H431.56L426.269 562.062L428.29 568.281L423 564.438L417.71 568.281L419.731 562.062L414.44 558.219H420.979L423 552Z" fill="#390908"/>
                            <path d="M443 552L445.021 558.219H451.56L446.269 562.062L448.29 568.281L443 564.438L437.71 568.281L439.731 562.062L434.44 558.219H440.979L443 552Z" fill="#390908"/>
                            <path d="M463 552L465.021 558.219H471.56L466.269 562.062L468.29 568.281L463 564.438L457.71 568.281L459.731 562.062L454.44 558.219H460.979L463 552Z" fill="#390908"/>
                        </svg>
                        <a href="{{ locale_route('case-studies') }}" class="sd-hero__proof-link">{!! __('service_detail.distributor.proof_link') !!}</a>
                    </div>
                </div>
            </div>

            <div class="sd-hero__visual" style="--ox: 791; --vw: 697; --vh: 564; --ar: 697 / 564;">
                <div class="sd-hero__img" style="--x: 791; --y: 0; --w: 666; --h: 515; --bg: #D9D9D9;">
                    <img src="{{ asset('images/services/sd-distributor-hero-1.jpg') }}" alt="{{ __('service_detail.distributor.hero_alt_1') }}" loading="eager" fetchpriority="high">
                </div>
                <div class="sd-hero__img" style="--x: 1022; --y: 298; --w: 466; --h: 266; --bg: #8B8B8B;">
                    <img src="{{ asset('images/services/sd-distributor-hero-2.jpg') }}" alt="{{ __('service_detail.distributor.hero_alt_2') }}" loading="eager">
                </div>
            </div>

        </div>
    </section>

    {{-- ==========================================
         STAGE 1: FIND AND VERIFY
         ========================================== --}}
    <section class="sd-stage" id="stage-1" style="--stage-gap: 20;">
        <div class="sd-inner">
            <h2 class="sd-stage__title">{{ __('service_detail.distributor.stage_1_title') }}</h2>

            <div class="sd-bento-d">
                <div class="sd-bento-d__left">
                    {{-- 1 --}}
                    <article class="sd-card" style="--h: 358; --bg: #283749; --pt: 29.5; --pl: 39; --pb: 40; --mw: 345;">
                        <span class="sd-num" style="--x: 375; --y: 22;" aria-hidden="true">1</span>
                        <div class="sd-card__inner">
                            <h3 class="sd-card__lead">{{ __('service_detail.distributor.how_it_works') }}</h3>
                            <p class="sd-card__sub">{{ __('service_detail.distributor.stage_1_title') }}</p>
                            <h4 class="sd-card-title sd-gap-lg" style="margin-top: calc(71 * var(--u));">{!! __('service_detail.distributor.source_title') !!}</h4>
                            <p class="sd-card-text sd-gap-8">{{ __('service_detail.distributor.source_text') }}</p>
                        </div>
                    </article>

                    {{-- 4 --}}
                    <article class="sd-card sd-card--center" style="--h: 344; --bg: #E3E1CF; --fg: #283749; --pl: 36; --pt: 24; --pb: 24; --mw: 345;">
                        <span class="sd-num sd-num--ghost" style="--x: 348; --y: 23;" aria-hidden="true">4</span>
                        <span class="sd-num" style="--x: 368; --y: 39; --stroke: #283749;" aria-hidden="true">4</span>
                        <div class="sd-card__inner">
                            <h4 class="sd-card-title">{!! __('service_detail.distributor.shortlist_title') !!}</h4>
                            <p class="sd-card-text sd-gap-20">{{ __('service_detail.distributor.shortlist_text') }}</p>
                        </div>
                    </article>
                </div>

                <div class="sd-bento-d__right">
                    <div class="sd-bento-d__row">
                        {{-- 2 --}}
                        <article class="sd-card sd-card--center" style="--h: 314; --bg: #FAF4F0; --fg: #283749; --pl: 26; --pt: 20; --pb: 16; --mw: 400;">
                            <span class="sd-num" style="--x: 362; --y: 9; --stroke: #283749;" aria-hidden="true">2</span>
                            <div class="sd-card__inner">
                                <h4 class="sd-card-title">{!! __('service_detail.distributor.screen_title') !!}</h4>
                                <ul class="sd-list sd-card-text sd-gap-20">
                                    <li>{!! __('service_detail.distributor.screen_registration') !!}</li>
                                    <li>{!! __('service_detail.distributor.screen_warehousing') !!}</li>
                                    <li>{!! __('service_detail.distributor.screen_products') !!}</li>
                                    <li>{!! __('service_detail.distributor.screen_financial') !!}</li>
                                    <li>{!! __('service_detail.distributor.screen_team') !!}</li>
                                </ul>
                            </div>
                        </article>

                        {{-- 3 --}}
                        <article class="sd-card sd-card--center" style="--h: 314; --bg: #390908; --pl: 36; --pt: 24; --pb: 24; --mw: 305;">
                            <span class="sd-num" style="--x: 308; --y: 23;" aria-hidden="true">3</span>
                            <div class="sd-card__inner">
                                <h4 class="sd-card-title">{!! __('service_detail.distributor.references_title') !!}</h4>
                                <p class="sd-card-text sd-gap-20">{{ __('service_detail.distributor.references_text') }}</p>
                            </div>
                        </article>
                    </div>

                    {{-- 5 --}}
                    <article class="sd-card sd-card--center sd-card--span" style="--h: 389; --bg: #587C90; --pl: 43; --pt: 24; --pb: 24; --mw: 765;">
                        <span class="sd-num" style="--x: 744; --y: 32;" aria-hidden="true">5</span>
                        <div class="sd-card__inner">
                            <h4 class="sd-card-title">{{ __('service_detail.distributor.payment_title') }}</h4>
                            <div class="sd-card-text sd-gap-8">
                                <p>{{ __('service_detail.distributor.payment_intro') }}</p>
                                <ul class="sd-list">
                                    <li>{!! __('service_detail.distributor.payment_infrastructure') !!}</li>
                                    <li>{!! __('service_detail.distributor.payment_bank') !!}</li>
                                    <li>{!! __('service_detail.distributor.payment_fx') !!}</li>
                                    <li>{!! __('service_detail.distributor.payment_route') !!}</li>
                                    <li>{!! __('service_detail.distributor.payment_time') !!}</li>
                                    <li>{!! __('service_detail.distributor.payment_terms') !!}</li>
                                </ul>
                                <p>{{ __('service_detail.distributor.payment_outro') }}</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         STAGE 2: STRUCTURE AND ACTIVATE
         ========================================== --}}
    <section class="sd-stage" id="stage-2" style="--stage-gap: 45;">
        <div class="sd-inner">
            <h2 class="sd-stage__title">{{ __('service_detail.distributor.stage_2_title') }}</h2>

            <article class="sd-card sd-card--span sd-wide-card" style="--h: 205; --bg: #587C90; --pl: 78; --pt: 32; --pb: 32; --mw: 1150;">
                <div class="sd-card__inner">
                    <h4 class="sd-card-title">{{ __('service_detail.distributor.stage_2_card_title') }}</h4>
                    <div class="sd-card-text sd-gap-8">
                        <p>{{ __('service_detail.distributor.stage_2_intro') }}</p>
                        <ul class="sd-list">
                            <li>{!! __('service_detail.distributor.stage_2_terms') !!}</li>
                            <li>{!! __('service_detail.distributor.stage_2_introductions') !!}</li>
                            <li>{!! __('service_detail.distributor.stage_2_activation') !!}</li>
                            <li>{!! __('service_detail.distributor.stage_2_review') !!}</li>
                        </ul>
                    </div>
                </div>
            </article>
        </div>
    </section>

    {{-- ==========================================
         WHAT YOU GET + PRICING
         ========================================== --}}
    <section class="sd-get sd-get--d sd-get--dark" id="what-you-get" style="--get-gap: 76;">
        <div class="sd-get__band">
            <div class="sd-inner">
                <h2 class="sd-get__title">{{ __('service_detail.distributor.get_title') }}</h2>
                <p class="sd-get__sub my-3">{!! __('service_detail.distributor.get_sub') !!}</p>

                <ul class="sd-pills">
                    <li class="sd-pill" style="--w: 291;">{{ __('service_detail.distributor.pill_longlist') }}</li>
                    <li class="sd-pill" style="--w: 374;">{{ __('service_detail.distributor.pill_checks') }}</li>
                    <li class="sd-pill" style="--w: 350;">{{ __('service_detail.distributor.pill_references') }}</li>
                    <li class="sd-pill" style="--w: 543;">{{ __('service_detail.distributor.pill_shortlist') }}</li>
                    <li class="sd-pill" style="--w: 684;">{{ __('service_detail.distributor.pill_stage_2') }}</li>
                </ul>
            </div>
        </div>

        <div class="sd-pricing sd-pricing--v2" id="pricing">
            <div class="sd-inner">
                {{-- Stage 1 --}}
                <article class="sd-price sd-price--navy" style="--h: 981;">
                    <span class="sd-price__badge">{{ __('service_detail.distributor.price_stage_1_badge') }}</span>
                    <p class="sd-price__desc">{{ __('service_detail.distributor.price_stage_1_desc') }}</p>
                    <h3 class="sd-price__included">{{ __('service_detail.distributor.included') }}</h3>
                    <ul class="sd-checks">
                        <li>{!! $check !!}{{ __('service_detail.distributor.check_sourcing') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.distributor.check_shortlist') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.distributor.check_registration') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.distributor.check_capacity') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.distributor.check_visits') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.distributor.check_references') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.distributor.check_capital') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.distributor.check_payment') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.distributor.check_report') }}</li>
                    </ul>
                    <p class="sd-price__note">{{ __('service_detail.distributor.price_stage_1_note') }}</p>
                    <div class="sd-price__foot" style="--gap: 40;">
                        <span class="sd-price__value">£5,000</span>
                        <span class="sd-price__unit">{{ __('service_detail.distributor.per_market') }}</span>
                    </div>
                </article>

                <div class="sd-pricing__stack">
                    {{-- Stage 2 --}}
                    <article class="sd-price sd-price--sand" style="--h: 719;">
                        <span class="sd-price__badge">{{ __('service_detail.distributor.price_stage_2_badge') }}</span>
                        <p class="sd-price__desc">{{ __('service_detail.distributor.price_stage_2_desc') }}</p>
                        <h3 class="sd-price__included">{{ __('service_detail.distributor.included') }}</h3>
                        <ul class="sd-checks">
                            <li>{!! $check !!}{{ __('service_detail.distributor.check_brief') }}</li>
                            <li>{!! $check !!}{{ __('service_detail.distributor.check_introductions') }}</li>
                            <li>{!! $check !!}{{ __('service_detail.distributor.check_alignment') }}</li>
                            <li>{!! $check !!}{{ __('service_detail.distributor.check_plan') }}</li>
                            <li>{!! $check !!}{{ __('service_detail.distributor.check_accounts') }}</li>
                            <li>{!! $check !!}{{ __('service_detail.distributor.check_onboarding') }}</li>
                            <li>{!! $check !!}{{ __('service_detail.distributor.check_review') }}</li>
                        </ul>
                        <div class="sd-price__foot" style="--gap: 38;">
                            <span class="sd-price__value">£2,890</span>
                            <span class="sd-price__unit">{{ __('service_detail.distributor.per_partner') }}</span>
                        </div>
                    </article>

                    {{-- Additional markets --}}
                    <article class="sd-price sd-price--slate" style="--h: 243;">
                        <span class="sd-price__badge">{{ __('service_detail.distributor.price_extra_badge') }}</span>
                        <div class="sd-price__foot" style="--gap: 38;">
                            <span class="sd-price__value">£3,500</span>
                            <span class="sd-price__unit">{{ __('service_detail.distributor.per_extra_market') }}</span>
                        </div>
                        <p class="sd-price__desc sd-price__desc--auto" style="margin-top: calc(35 * var(--u)) !important;">{{ __('service_detail.distributor.price_extra_desc') }}</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         WHO THIS IS FOR / WHY PARTNERS DECIDE
         ========================================== --}}
    <section class="sd-whosec" id="who-this-is-for">
        <div class="sd-inner">
                <div class="sd-who sd-who--static">
                    <div class="sd-who__col">
                        <h2 class="sd-who__title">{{ __('service_detail.distributor.who_title') }}</h2>
                        <div class="sd-who__body">
                            <ul class="sd-list">
                                <li>{{ __('service_detail.distributor.who_equipment') }}</li>
                                <li>{{ __('service_detail.distributor.who_software') }}</li>
                            </ul>
                            <p>{{ __('service_detail.distributor.who_close') }}</p>
                        </div>
                    </div>
                    <div class="sd-who__col">
                        <h2 class="sd-who__title">{!! __('service_detail.distributor.why_title') !!}</h2>
                        <p class="sd-who__body">
                            {{ __('service_detail.distributor.why_body') }}
                        </p>
                    </div>
                </div>

            <a href="{{ locale_route('contact') }}" class="sd-whosec__btn" id="sd-who-get-in-touch-btn">
                {{ __('service_detail.distributor.get_in_touch') }}
                <svg viewBox="1220.5 3451.64 16 12.72" fill="none" aria-hidden="true"><path d="M1229.41 3451.98C1229.62 3451.76 1229.91 3451.64 1230.21 3451.64C1230.52 3451.64 1230.81 3451.76 1231.02 3451.98L1236.17 3457.19C1236.38 3457.41 1236.5 3457.7 1236.5 3458.01C1236.5 3458.31 1236.38 3458.61 1236.17 3458.83L1231.02 3464.04C1230.81 3464.25 1230.52 3464.37 1230.22 3464.36C1229.92 3464.36 1229.63 3464.24 1229.42 3464.02C1229.21 3463.81 1229.09 3463.52 1229.09 3463.22C1229.08 3462.91 1229.2 3462.62 1229.41 3462.4L1232.5 3459.17H1221.64C1221.34 3459.17 1221.05 3459.04 1220.83 3458.83C1220.62 3458.61 1220.5 3458.31 1220.5 3458.01C1220.5 3457.7 1220.62 3457.41 1220.83 3457.19C1221.05 3456.97 1221.34 3456.85 1221.64 3456.85H1232.5L1229.41 3453.61C1229.19 3453.4 1229.07 3453.1 1229.07 3452.79C1229.07 3452.49 1229.19 3452.19 1229.41 3451.98Z" fill="currentColor"/></svg>
            </a>
        </div>
    </section>

    {{-- ==========================================
         CASE STUDIES (same block as the homepage)
         ========================================== --}}
    <div class="sd-cases-embed">
        @include('include.featured-case-studies')
    </div>

</div>
@endsection

@section('footer')
    @include('layouts.includes.footer3')
@endsection
