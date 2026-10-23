@extends('layouts.master')
@section('title', 'Distributor & Partner Development in Africa | P23 Africa')
@section('body_class', 'p23-black-navbar-page sd-body')

@section('meta')
    <meta name="description"
        content="Find the right partner in Africa, and set them up to sell. P23 Africa finds, verifies and reference-checks local distributors, importers and resellers, then helps you structure the partnership.">
    <meta property="og:title" content="Distributor & Partner Development | P23 Africa">
    <meta property="og:description" content="Choose your African distributor with evidence, before you commit stock, exclusivity or your brand.">
    <meta property="og:image" content="{{ asset('images/market-entry/hero-handshake.png') }}">
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
                    Find the right partner <br class="sd-br-d">in Africa, and set them <br class="sd-br-d">up to sell.
                </h1>
                <p class="sd-hero__desc">
                    Most market entries fail on the partner, not the product. We find, verify and reference-check local distributors, importers and resellers, then help you structure the partnership so it starts well. You choose with evidence, before you commit stock, exclusivity or your brand.
                </p>
                <div class="sd-hero__actions">
                    <a href="{{ url('contact') }}" class="sd-hero__btn" id="sd-hero-get-in-touch-btn">Get in Touch</a>
                    <div class="sd-hero__proof">
                        <svg class="sd-hero__stars" viewBox="374 552 98 17" fill="none" role="img" aria-label="5 star rating">
                            <path d="M383 552L385.021 558.219H391.56L386.269 562.062L388.29 568.281L383 564.438L377.71 568.281L379.731 562.062L374.44 558.219H380.979L383 552Z" fill="#390908"/>
                            <path d="M403 552L405.021 558.219H411.56L406.269 562.062L408.29 568.281L403 564.438L397.71 568.281L399.731 562.062L394.44 558.219H400.979L403 552Z" fill="#390908"/>
                            <path d="M423 552L425.021 558.219H431.56L426.269 562.062L428.29 568.281L423 564.438L417.71 568.281L419.731 562.062L414.44 558.219H420.979L423 552Z" fill="#390908"/>
                            <path d="M443 552L445.021 558.219H451.56L446.269 562.062L448.29 568.281L443 564.438L437.71 568.281L439.731 562.062L434.44 558.219H440.979L443 552Z" fill="#390908"/>
                            <path d="M463 552L465.021 558.219H471.56L466.269 562.062L468.29 568.281L463 564.438L457.71 568.281L459.731 562.062L454.44 558.219H460.979L463 552Z" fill="#390908"/>
                        </svg>
                        <a href="{{ url('case-studies') }}" class="sd-hero__proof-link"><strong>Click Here to</strong> <em>Check out case studies</em></a>
                    </div>
                </div>
            </div>

            <div class="sd-hero__visual" style="--ox: 791; --vw: 697; --vh: 564; --ar: 697 / 564;">
                <div class="sd-hero__img" style="--x: 791; --y: 0; --w: 666; --h: 515; --bg: #D9D9D9;">
                    <img src="{{ asset('images/market-entry/hero-handshake.png') }}" alt="Business partners shaking hands on a distribution agreement" loading="eager">
                </div>
                <div class="sd-hero__img" style="--x: 1022; --y: 298; --w: 466; --h: 266; --bg: #8B8B8B;">
                    <img src="{{ asset('images/services/global-reach-executive.jpg') }}" alt="P23 Africa executive reviewing partner verification" loading="eager">
                </div>
            </div>

        </div>
    </section>

    {{-- ==========================================
         STAGE 1: FIND AND VERIFY
         ========================================== --}}
    <section class="sd-stage" id="stage-1" style="--stage-gap: 20;">
        <div class="sd-inner">
            <h2 class="sd-stage__title">Stage 1: Find and verify</h2>

            <div class="sd-bento-d">
                <div class="sd-bento-d__left">
                    {{-- 1 --}}
                    <article class="sd-card" style="--h: 358; --bg: #283749; --pt: 29.5; --pl: 39; --pb: 40; --mw: 345;">
                        <span class="sd-num" style="--x: 375; --y: 22;" aria-hidden="true">1</span>
                        <div class="sd-card__inner">
                            <h3 class="sd-card__lead">How it works</h3>
                            <p class="sd-card__sub">Stage 1: Find and verify</p>
                            <h4 class="sd-card-title sd-gap-lg" style="margin-top: calc(71 * var(--u));">We source candidates from <br class="sd-br-d">several channels</h4>
                            <p class="sd-card-text sd-gap-8">Our local teams find distributors and importers already active in your market, including companies that sell adjacent, non-competing products. This can also include any inbound leads you have.</p>
                        </div>
                    </article>

                    {{-- 4 --}}
                    <article class="sd-card sd-card--center" style="--h: 344; --bg: #E3E1CF; --fg: #283749; --pl: 36; --pt: 24; --pb: 24; --mw: 345;">
                        <span class="sd-num sd-num--ghost" style="--x: 348; --y: 23;" aria-hidden="true">4</span>
                        <span class="sd-num" style="--x: 368; --y: 39; --stroke: #283749;" aria-hidden="true">4</span>
                        <div class="sd-card__inner">
                            <h4 class="sd-card-title">We deliver a shortlist with a <br class="sd-br-d">recommendation</h4>
                            <p class="sd-card-text sd-gap-20">For each candidate: capacity assessment, reference check summary, red flags, and a fit rating against your requirements (exclusivity expectations, minimum order volumes, payment terms). You can decide from the document without another round of research.</p>
                        </div>
                    </article>
                </div>

                <div class="sd-bento-d__right">
                    <div class="sd-bento-d__row">
                        {{-- 2 --}}
                        <article class="sd-card sd-card--center" style="--h: 314; --bg: #FAF4F0; --fg: #283749; --pl: 26; --pt: 20; --pb: 16; --mw: 400;">
                            <span class="sd-num" style="--x: 362; --y: 9; --stroke: #283749;" aria-hidden="true">2</span>
                            <div class="sd-card__inner">
                                <h4 class="sd-card-title">We screen on hard criteria <br class="sd-br-d">before any relationship-building</h4>
                                <ul class="sd-list sd-card-text sd-gap-20">
                                    <li><strong>Registration and track record:</strong> status and years in operation, checked against the local corporate registry</li>
                                    <li><strong>Warehousing and storage:</strong> photos, video walkthrough and site visit.</li>
                                    <li><strong>Product lines:</strong> what they carry, and whether it complements or conflicts with yours</li>
                                    <li><strong>Financial strength:</strong> working capital indicators, using references from other suppliers they represent</li>
                                    <li><strong>Team and reach:</strong> sales team size and geographic coverage</li>
                                </ul>
                            </div>
                        </article>

                        {{-- 3 --}}
                        <article class="sd-card sd-card--center" style="--h: 314; --bg: #390908; --pl: 36; --pt: 24; --pb: 24; --mw: 305;">
                            <span class="sd-num" style="--x: 308; --y: 23;" aria-hidden="true">3</span>
                            <div class="sd-card__inner">
                                <h4 class="sd-card-title">We check references with <br class="sd-br-d">their existing suppliers</h4>
                                <p class="sd-card-text sd-gap-20">We ask each candidate for 2 to 3 brands they represent, then speak to those brands about payment reliability, order consistency, and whether the distributor has ever gone quiet or missed obligations.</p>
                            </div>
                        </article>
                    </div>

                    {{-- 5 --}}
                    <article class="sd-card sd-card--center sd-card--span" style="--h: 389; --bg: #587C90; --pl: 43; --pt: 24; --pb: 24; --mw: 765;">
                        <span class="sd-num" style="--x: 744; --y: 32;" aria-hidden="true">5</span>
                        <div class="sd-card__inner">
                            <h4 class="sd-card-title">Payment and cash-flow check</h4>
                            <div class="sd-card-text sd-gap-8">
                                <p>For each country, we check how you’ll actually get paid:</p>
                                <ul class="sd-list">
                                    <li><strong>Payment infrastructure:</strong> the methods partners and buyers use, and how reliable they are</li>
                                    <li><strong>Bank regulation:</strong> what local banks require to pay a foreign supplier</li>
                                    <li><strong>Foreign exchange compliance:</strong> access to hard currency, official versus parallel rates, repatriation rules and paperwork</li>
                                    <li><strong>The payment route:</strong> how money is converted, transferred and received, in which currency, and the fees and FX margin involved</li>
                                    <li><strong>Time to cash:</strong> how long it typically takes from a completed sale to funds you can use</li>
                                    <li><strong>Payment terms:</strong> what’s normal, what you can safely accept, and ways to reduce exposure</li>
                                </ul>
                                <p>You get a one-page payment route summary per country, rated straightforward, workable with conditions, or high risk.</p>
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
            <h2 class="sd-stage__title">Stage 2: Structure and activate</h2>

            <article class="sd-card sd-card--span sd-wide-card" style="--h: 205; --bg: #587C90; --pl: 78; --pt: 32; --pb: 32; --mw: 1150;">
                <div class="sd-card__inner">
                    <h4 class="sd-card-title">Structure and activate</h4>
                    <div class="sd-card-text sd-gap-8">
                        <p>Choosing a partner is the start. Once you’ve chosen, we help the partnership begin properly:</p>
                        <ul class="sd-list">
                            <li><strong>Terms and expectations:</strong> a clear brief on territory, exclusivity, minimum orders, targets and payment terms, so both sides start aligned</li>
                            <li><strong>Introductions and first contact:</strong> we make the introduction and run early conversations through our local team</li>
                            <li><strong>Activation plan:</strong> first-90-day priorities, including product training, initial target accounts and a first-order plan</li>
                            <li><strong>Early performance check:</strong> a review at 60 to 90 days of whether the partner is doing what was agreed, with a clear view on whether to continue, adjust or replace</li>
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
                <h2 class="sd-get__title">What you get</h2>
                <p class="sd-get__sub">Discover the features, benefits and value <br class="sd-br-d">you’ll receive.</p>

                <ul class="sd-pills">
                    <li class="sd-pill" style="--w: 291;">A sourced, screened longlist</li>
                    <li class="sd-pill" style="--w: 374;">Verified registration and capacity checks</li>
                    <li class="sd-pill" style="--w: 350;">Supplier reference calls, summarised</li>
                    <li class="sd-pill" style="--w: 543;">A decision-ready shortlist with a recommendation per candidate</li>
                    <li class="sd-pill" style="--w: 684;">With Stage 2: a partnership brief, an activation plan and an early performance review</li>
                </ul>
            </div>
        </div>

        <div class="sd-pricing sd-pricing--v2" id="pricing">
            <div class="sd-inner">
                {{-- Stage 1 --}}
                <article class="sd-price sd-price--navy" style="--h: 981;">
                    <span class="sd-price__badge">Stage 1: Find &amp; Verify</span>
                    <p class="sd-price__desc">We identify, assess and verify potential distributors, importers and commercial partners in your target African market.</p>
                    <h3 class="sd-price__included">What's included;</h3>
                    <ul class="sd-checks">
                        <li>{!! $check !!}Sourcing and screening of potential distributors and importers.</li>
                        <li>{!! $check !!}Shortlisting of 3 suitable distributor candidates against your agreed commercial requirements.</li>
                        <li>{!! $check !!}Company registration, operating history and product portfolio checks.</li>
                        <li>{!! $check !!}Assessment of operational capacity, sales coverage and relevant infrastructure.</li>
                        <li>{!! $check !!}Up to 6 site visits to assess shortlisted candidates and verify their operating facilities, where appropriate.</li>
                        <li>{!! $check !!}Supplier reference checks to assess payment reliability, order consistency and commercial track record.</li>
                        <li>{!! $check !!}Review of working-capital indicators and available evidence of financial capability.</li>
                        <li>{!! $check !!}A country-specific payment route and cash-flow risk assessment.</li>
                        <li>{!! $check !!}A comparative report covering candidate suitability, verification findings, identified risks and our recommendation.</li>
                    </ul>
                    <p class="sd-price__note">You’ll have a clear basis for deciding which distributor to pursue, what concerns need resolving and what to confirm before signing an agreement or committing stock.</p>
                    <div class="sd-price__foot" style="--gap: 40;">
                        <span class="sd-price__value">£5,000</span>
                        <span class="sd-price__unit">per market</span>
                    </div>
                </article>

                <div class="sd-pricing__stack">
                    {{-- Stage 2 --}}
                    <article class="sd-price sd-price--sand" style="--h: 719;">
                        <span class="sd-price__badge">Stage 2: Structure &amp; Activate</span>
                        <p class="sd-price__desc">Once you’ve selected your preferred distributor, we help establish the commercial relationship and prepare them to start selling.</p>
                        <h3 class="sd-price__included">What's included;</h3>
                        <ul class="sd-checks">
                            <li>{!! $check !!}Partnership brief covering territory, exclusivity, minimum orders, sales targets and payment terms.</li>
                            <li>{!! $check !!}Facilitated introductions and initial commercial discussions.</li>
                            <li>{!! $check !!}Alignment on responsibilities and performance expectations.</li>
                            <li>{!! $check !!}A practical 90-day activation plan.</li>
                            <li>{!! $check !!}Initial target-account priorities and first-order planning.</li>
                            <li>{!! $check !!}Product onboarding and training coordination, where required.</li>
                            <li>{!! $check !!}A performance review at 60–90 days, with recommendations to continue, adjust or replace the partner.</li>
                        </ul>
                        <div class="sd-price__foot" style="--gap: 38;">
                            <span class="sd-price__value">£2,890</span>
                            <span class="sd-price__unit">per partner</span>
                        </div>
                    </article>

                    {{-- Additional markets --}}
                    <article class="sd-price sd-price--slate" style="--h: 243;">
                        <span class="sd-price__badge">Additional markets</span>
                        <div class="sd-price__foot" style="--gap: 38;">
                            <span class="sd-price__value">£3,500</span>
                            <span class="sd-price__unit">per additional market</span>
                        </div>
                        <p class="sd-price__desc sd-price__desc--auto" style="margin-top: calc(35 * var(--u)) !important;">Extend the sourcing and verification process into another African country, with market-specific research, screening and partner assessments.</p>
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
                        <h2 class="sd-who__title">Who This Is For</h2>
                        <div class="sd-who__body">
                            <ul class="sd-list">
                                <li>Global Equipment and machinery manufacturers looking for distributors or importers</li>
                                <li>Software and infrastructure companies building a reseller or channel network</li>
                            </ul>
                            <p>If you’re asking “who should we sell through, and can we trust them?”, this is where we start.</p>
                        </div>
                    </div>
                    <div class="sd-who__col">
                        <h2 class="sd-who__title">Why Partners <br class="sd-br-d">Decide The Outcome</h2>
                        <p class="sd-who__body">
                            A distributor is hard to undo. Exclusivity, stock on credit and your brand reputation are all in their hands, and many “distribution companies” are one-person operations. A few weeks of checking costs far less than a year with the wrong partner.
                        </p>
                    </div>
                </div>

            <a href="{{ url('contact') }}" class="sd-whosec__btn" id="sd-who-get-in-touch-btn">
                Get in Touch
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
