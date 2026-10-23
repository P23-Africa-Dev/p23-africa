@extends('layouts.master')
@section('title', 'Distributor & Partner Development in Africa | P23 Africa')
@section('body_class', 'p23-black-navbar-page sd-body')

@section('meta')
    <meta name="description"
        content="Find the right partner in Africa, and set them up to sell. P23 Africa finds, verifies and reference-checks local distributors, importers and resellers, then helps you structure the partnership.">
    <meta property="og:title" content="Distributor & Partner Development | P23 Africa">
    <meta property="og:description" content="Choose your African distributor with evidence, before you commit stock, exclusivity or your brand.">
    <meta property="og:image" content="{{ asset('images/market-entry/hero-handshake.png') }}">
    <link rel="stylesheet" href="{{ asset('css/service-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer3.css') }}">
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
         HERO + WHO THIS IS FOR / WHY PARTNERS DECIDE
         ========================================== --}}
    <section class="sd-hero" id="hero" style="--hero-h: 764;">
        <div class="sd-inner">
            <div class="sd-hero__content">
                <h1 class="sd-hero__title">
                    Find the right partner <br class="sd-br-d">in Africa, and set them <br class="sd-br-d">up to sell.
                </h1>
                <p class="sd-hero__desc">
                    Most market entries fail on the partner, not the product. We find, verify and reference-check local distributors, importers and resellers, then help you structure the partnership so it starts well. You choose with evidence, before you commit stock, exclusivity or your brand.
                </p>
                <a href="{{ url('case-studies') }}" class="sd-hero__btn" id="sd-hero-case-studies-btn">Check out case studies</a>
            </div>

            <div class="sd-hero__visual" style="--ox: 791; --vw: 691; --vh: 764; --ar: 691 / 764;">
                <div class="sd-hero__img" style="--x: 791; --y: 0; --w: 666; --h: 515; --bg: #D9D9D9;">
                    <img src="{{ asset('images/market-entry/hero-handshake.png') }}" alt="Business partners shaking hands on a distribution agreement" loading="eager">
                </div>
                <div class="sd-hero__img" style="--x: 1016; --y: 187; --w: 466; --h: 577; --bg: #8B8B8B;">
                    <img src="{{ asset('images/services/global-reach-executive.jpg') }}" alt="P23 Africa executive reviewing partner verification" loading="eager">
                </div>
            </div>

            <div class="sd-who">
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
        </div>
    </section>

    {{-- ==========================================
         STAGE 1: FIND AND VERIFY
         ========================================== --}}
    <section class="sd-stage" id="stage-1" style="--stage-gap: 70;">
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
    <section class="sd-stage" id="stage-2" style="--stage-gap: 113;">
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
    <section class="sd-get sd-get--d" id="what-you-get" style="--get-gap: 47;">
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

        <div class="sd-pricing" id="pricing">
            <div class="sd-inner">
                {{-- Stage 1 --}}
                <article class="sd-price" style="--h: 981;">
                    <span class="sd-price__badge">Stage 1: Find &amp; Verify</span>
                    <div class="sd-price__row">
                        <span class="sd-price__amount">£5,000</span>
                        <span class="sd-price__per">per market</span>
                    </div>
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
                </article>

                <div class="sd-pricing__stack">
                    {{-- Stage 2 --}}
                    <article class="sd-price" style="--h: 719;">
                        <span class="sd-price__badge">Stage 2: Structure &amp; Activate</span>
                        <div class="sd-price__row">
                            <span class="sd-price__amount">£2,890</span>
                            <span class="sd-price__per">per partner</span>
                        </div>
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
                    </article>

                    {{-- Additional markets --}}
                    <article class="sd-price" style="--h: 243;">
                        <span class="sd-price__badge">Additional markets</span>
                        <div class="sd-price__row">
                            <span class="sd-price__amount">£3,500</span>
                            <span class="sd-price__per">per additional market</span>
                        </div>
                        <p class="sd-price__desc sd-price__desc--auto">Extend the sourcing and verification process into another African country, with market-specific research, screening and partner assessments.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@section('footer')
    @include('layouts.includes.footer3')
@endsection
