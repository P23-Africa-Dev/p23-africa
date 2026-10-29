@extends('layouts.master')
@section('title', __('market_country.ng.title'))

@section('meta')
    <meta name="description"
        content="{{ __('market_country.ng.meta') }}">
    <meta name="keywords"
        content="{{ __('market_country.ng.keywords') }}">
    <meta property="og:title" content="{{ __('market_country.ng.og_title') }}">
    <meta property="og:description" content="{{ __('market_country.ng.og_desc') }}">
    <meta property="og:image" content="{{ asset('images/servces-bg.png') }}">
    <link rel="stylesheet" href="{{ asset('css/market-entry.css') }}">
@endsection

@section('content')

    <!-- Dossier Breadcrumbs Bar -->
    <div class="p23-dossier-nav-bar">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <ul class="p23-breadcrumb">
                <li><a href="{{ locale_route('homepage') }}">{{ __('nav.home') }}</a></li>
                <li class="separator">/</li>
                <li><a href="{{ locale_route('services') }}">{{ __('nav.services') }}</a></li>
                <li class="separator">/</li>
                <li><a href="{{ route('services.market-demand-testing') }}">Market Demand Testing</a></li>
                <li class="separator">/</li>
                <li class="current">{{ __('market_country.ng.crumb') }}</li>
            </ul>
            <div class="p23-dossier-id-tag">
                DOSSIER REF: NG-CASE-2401
            </div>
        </div>
    </div>

    <!-- Case Study Hero (Inspired by C-Suite Editorial Layout) -->
    <section class="p23-case-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-9">
                    <div class="case-meta-tags">
                        <span class="case-meta-tag"><i class="fas fa-flag me-1"></i> Nigeria</span>
                        <span class="case-meta-tag"><i class="fas fa-industry me-1"></i> {{ __('market_country.ng.sector') }}</span>
                        <span class="case-meta-tag"><i class="fas fa-shield-alt me-1"></i> {{ __('market_country.ng.tag') }}</span>
                    </div>

                    <h1 class="p23-section-title text-white mb-3" style="font-size: clamp(2.2rem, 3.8vw, 3.4rem);">
                        {{ __('market_country.ng.hero') }}
                    </h1>

                    <p class="hero-subtitle mb-4">
                        {!! __('market_country.ng.hero_sub') !!}
                    </p>

                    <p style="color: rgba(255, 255, 255, 0.75); font-size: 1.05rem;">
                        {{ __('market_country.ng.hero_2') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Executive Stat Bar -->
    <section class="p23-executive-stat-bar">
        <div class="container">
            <div class="row text-center align-items-center">
                <div class="col-6 col-md-3 p23-stat-item">
                    <div class="p23-stat-number">0 <span class="accent">Naira</span></div>
                    <div class="p23-stat-label">{{ __('market_country.ng.capex') }}</div>
                </div>
                <div class="col-6 col-md-3 p23-stat-item">
                    <div class="p23-stat-number">100% <span class="accent">{{ __('market_country.ng.real') }}</span></div>
                    <div class="p23-stat-label">{{ __('market_country.ng.feedback') }}</div>
                </div>
                <div class="col-6 col-md-3 p23-stat-item">
                    <div class="p23-stat-number">10 <span class="accent">{{ __('market_country.ng.checks') }}</span></div>
                    <div class="p23-stat-label">{{ __('market_country.ng.diligence') }}</div>
                </div>
                <div class="col-6 col-md-3 p23-stat-item">
                    <div class="p23-stat-number">24/7 <span class="accent">{{ __('market_country.ng.ground') }}</span></div>
                    <div class="p23-stat-label">{{ __('market_country.ng.tracking') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Dossier Content Body -->
    <section class="p23-section p23-section-white">
        <div class="container">

            <!-- The Challenge Card -->
            <div class="p23-challenge-box">
                <span class="p23-eyebrow">{{ __('market_country.ng.context') }}</span>
                <h2 class="p23-challenge-title">{{ __('market_country.ng.context_title') }}</h2>
                <p class="p23-section-desc mb-3">
                    {{ __('market_country.ng.context_1') }}
                </p>
                <p class="p23-section-desc mb-4">
                    {!! __('market_country.ng.context_2') !!}
                </p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p23-check-item"><i class="fas fa-question-circle" style="color: var(--p23-mint-deep);"></i> {{ __('market_country.ng.q1') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-check-item"><i class="fas fa-question-circle" style="color: var(--p23-mint-deep);"></i> {{ __('market_country.ng.q2') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-check-item"><i class="fas fa-question-circle" style="color: var(--p23-mint-deep);"></i> {{ __('market_country.ng.q3') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-check-item"><i class="fas fa-question-circle" style="color: var(--p23-mint-deep);"></i> {{ __('market_country.ng.q4') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-check-item"><i class="fas fa-question-circle" style="color: var(--p23-mint-deep);"></i> {{ __('market_country.ng.q5') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-check-item"><i class="fas fa-question-circle" style="color: var(--p23-mint-deep);"></i> {{ __('market_country.ng.q6') }}</div>
                    </div>
                </div>
                <p class="p23-section-desc mt-4 mb-0">
                    {{ __('market_country.ng.brought') }}
                </p>
            </div>

            <!-- Execution Breakdown (01 to 04) -->
            <div class="mt-5">
                <span class="p23-eyebrow">{{ __('market_country.ng.playbook') }}</span>
                <h2 class="p23-section-title mb-5">{{ __('market_country.ng.playbook_title') }}</h2>

                <!-- 01 {{ __('market_country.ng.s1') }} -->
                <div class="p23-step-card mb-4">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">01</div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">We tested the market</h3>
                            <p class="p23-step-lead">
                                {{ __('market_country.ng.s1_lead') }}
                            </p>
                            <p class="p23-section-desc mb-3">
                                {{ __('market_country.ng.s1_body') }}
                            </p>
                            <div class="p23-checklist-grid">
                                <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.ng.c1') }}</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.ng.c2') }}</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.ng.c3') }}</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.ng.c4') }}</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.ng.c5') }}</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.ng.c6') }}</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.ng.c7') }}</div>
                            </div>
                            <p class="p23-section-desc mt-3 mb-0" style="font-weight: 700; color: var(--p23-teal-dark);">
                                {{ __('market_country.ng.s1_result') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- {{ __('market_country.ng.intel') }} -->
                <div class="p23-step-card p23-step-card-featured mb-4">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">Turning conversations into market intelligence</h3>
                            <p class="p23-step-lead">
                                {{ __('market_country.ng.intel_lead') }}
                            </p>
                            <p class="p23-section-desc">
                                {{ __('market_country.ng.intel_body') }}
                            </p>

                            <!-- 5 Intelligence Cards -->
                            <div class="p23-intel-grid">
                                <div class="p23-intel-card">
                                    <span class="intel-badge">{{ __('market_country.ng.d1') }}</span>
                                    <h5>{{ __('market_country.ng.d1t') }}</h5>
                                    <p>{{ __('market_country.ng.d1b') }}</p>
                                </div>
                                <div class="p23-intel-card">
                                    <span class="intel-badge">{{ __('market_country.ng.d2') }}</span>
                                    <h5>{{ __('market_country.ng.d2t') }}</h5>
                                    <p>{{ __('market_country.ng.d2b') }}</p>
                                </div>
                                <div class="p23-intel-card">
                                    <span class="intel-badge">{{ __('market_country.ng.d3') }}</span>
                                    <h5>{{ __('market_country.ng.d3t') }}</h5>
                                    <p>{{ __('market_country.ng.d3b') }}</p>
                                </div>
                                <div class="p23-intel-card">
                                    <span class="intel-badge">{{ __('market_country.ng.d4') }}</span>
                                    <h5>{{ __('market_country.ng.d4t') }}</h5>
                                    <p>{{ __('market_country.ng.d4b') }}</p>
                                </div>
                                <div class="p23-intel-card">
                                    <span class="intel-badge">{{ __('market_country.ng.d5') }}</span>
                                    <h5>{{ __('market_country.ng.d5t') }}</h5>
                                    <p>{{ __('market_country.ng.d5b') }}</p>
                                </div>
                            </div>

                            <p class="p23-section-desc mb-0" style="font-weight: 700; color: var(--p23-teal-dark);">
                                {{ __('market_country.ng.intel_result') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 02 {{ __('market_country.ng.s2') }} -->
                <div class="p23-step-card mb-4">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">02</div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">We identified and vetted distributors</h3>
                            <p class="p23-step-lead">
                                {{ __('market_country.ng.s2_lead') }}
                            </p>
                            <p class="p23-section-desc">
                                {{ __('market_country.ng.s2_body') }}
                            </p>

                            <div class="p23-vetting-checklist">
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> {{ __('market_country.ng.v1') }}</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> {{ __('market_country.ng.v2') }}</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> {{ __('market_country.ng.v3') }}</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> {{ __('market_country.ng.v4') }}</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> {{ __('market_country.ng.v5') }}</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> {{ __('market_country.ng.v6') }}</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> {{ __('market_country.ng.v7') }}</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> {{ __('market_country.ng.v8') }}</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> {{ __('market_country.ng.v9') }}</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> {{ __('market_country.ng.v10') }}</div>
                            </div>

                            <p class="p23-section-desc mb-0">
                                {{ __('market_country.ng.s2_end') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 03 {{ __('market_country.ng.s3') }} -->
                <div class="p23-step-card mb-4">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">03</div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">We facilitated the market entry</h3>
                            <p class="p23-step-lead">
                                {{ __('market_country.ng.s3_lead') }}
                            </p>
                            <div class="p23-transition-callout">
                                <span class="p23-transition-callout-tag">{{ __('market_country.ng.transition') }}</span>
                                <span class="p23-transition-callout-headline">
                                    {{ __('market_country.ng.from') }} <span style="color: var(--p23-mint-deep);">{{ __('market_country.ng.may') }}</span> ➔ <span style="color: var(--p23-teal-dark);">{{ __('market_country.ng.to') }}</span>
                                </span>
                            </div>
                            <p class="p23-section-desc mb-0">
                                {{ __('market_country.ng.s3_end') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 04 {{ __('market_country.ng.s4') }} -->
                <div class="p23-step-card mb-4">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">04</div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">We helped maintain visibility on the ground</h3>
                            <p class="p23-step-lead">
                                {{ __('market_country.ng.s4_lead') }}
                            </p>
                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <div class="p23-check-item"><i class="fas fa-search-location"></i> {{ __('market_country.ng.g1') }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p23-check-item"><i class="fas fa-user-clock"></i> {{ __('market_country.ng.g2') }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p23-check-item"><i class="fas fa-funnel-dollar"></i> {{ __('market_country.ng.g3') }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p23-check-item"><i class="fas fa-tasks"></i> {{ __('market_country.ng.g4') }}</div>
                                </div>
                                <div class="col-md-8">
                                    <div class="p23-check-item"><i class="fas fa-exclamation-triangle text-danger"></i> {{ __('market_country.ng.g5') }}</div>
                                </div>
                            </div>
                            <p class="p23-section-desc">
                                {!! __('market_country.ng.s4_soft') !!}
                            </p>
                            <p class="p23-section-desc mb-0">
                                {{ __('market_country.ng.s4_end') }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- The Result Section -->
            <div class="my-5 p23-case-outcome-showcase">
                <span class="p23-eyebrow">{{ __('market_country.ng.outcome_label') }}</span>
                <h2 class="text-white mb-3" style="font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif; font-size: clamp(1.8rem, 2.8vw, 2.5rem);">
                    {{ __('market_country.ng.outcome_title') }}
                </h2>
                <p style="color: rgba(244, 250, 248, 0.88); font-size: 1.1rem; line-height: 1.72;" class="mb-4">
                    {{ __('market_country.ng.outcome_body') }}
                </p>
                <div class="row g-3 text-white">
                    <div class="col-md-4">
                        <div class="p23-outcome-pill">
                            <i class="fas fa-check me-2" style="color: var(--p23-mint-bright);"></i> {{ __('market_country.ng.o1') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p23-outcome-pill">
                            <i class="fas fa-check me-2" style="color: var(--p23-mint-bright);"></i> {{ __('market_country.ng.o2') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p23-outcome-pill">
                            <i class="fas fa-check me-2" style="color: var(--p23-mint-bright);"></i> {{ __('market_country.ng.o3') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-outcome-pill">
                            <i class="fas fa-check me-2" style="color: var(--p23-mint-bright);"></i> {{ __('market_country.ng.o4') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-outcome-pill">
                            <i class="fas fa-check me-2" style="color: var(--p23-mint-bright);"></i> {{ __('market_country.ng.o5') }}
                        </div>
                    </div>
                </div>
                <p class="mt-4 mb-0" style="color: rgba(244, 250, 248, 0.92); font-size: 1.05rem;">
                    {{ __('market_country.ng.outcome_end') }}
                </p>
            </div>

            <!-- {{ __('market_country.ng.principles_title') }} (The Framework) -->
            <div class="my-5">
                <span class="p23-eyebrow">{{ __('market_country.ng.principles') }}</span>
                <h2 class="p23-section-title">What this means for businesses entering Nigeria</h2>
                <p class="p23-section-desc">
                    {{ __('market_country.ng.principles_body') }}
                </p>

                <div class="p23-takeaway-framework">
                    <div class="p23-takeaway-card">
                        <div class="p23-takeaway-step">{{ __('market_country.ng.t1') }}</div>
                        <p>{{ __('market_country.ng.t1b') }}</p>
                    </div>

                    <div class="p23-takeaway-card">
                        <div class="p23-takeaway-step">{{ __('market_country.ng.t2') }}</div>
                        <p>{{ __('market_country.ng.t2b') }}</p>
                    </div>

                    <div class="p23-takeaway-card">
                        <div class="p23-takeaway-step">{{ __('market_country.ng.t3') }}</div>
                        <p>{{ __('market_country.ng.t3b') }}</p>
                    </div>

                    <div class="p23-takeaway-card">
                        <div class="p23-takeaway-step">{{ __('market_country.ng.t4') }}</div>
                        <p>{{ __('market_country.ng.t4b') }}</p>
                    </div>
                </div>

                <div class="p23-framework-summary-card my-4">
                    <h4>
                        {{ __('market_country.ng.approach') }}
                    </h4>
                    <p class="mb-0">
                        {{ __('market_country.ng.approach_body') }}
                    </p>
                </div>
            </div>

            <!-- Cross-Link Sibling Case Strip -->
            <div class="p23-sibling-case-strip">
                <div class="info-text">
                    <span class="text-uppercase" style="font-size: 0.78rem; letter-spacing: 0.1em; color: var(--p23-mint-glow); font-weight: 700;">{{ __('market_country.ng.next') }}</span>
                    <strong>{{ __('market_country.ng.next_title') }}</strong>
                    <span>{{ __('market_country.ng.next_body') }}</span>
                </div>
                <a href="{{ locale_route('services.market-entry.ghana') }}" class="p23-dossier-link">
                    {{ __('market_country.ng.next_link') }} <span>→</span>
                </a>
            </div>

        </div>
    </section>

    <!-- Nigeria Case Conversion CTA Banner -->
    <section class="p23-section p23-section-cream">
        <div class="container">
            <div class="p23-cta-banner-wrapper">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-8">
                        <span class="p23-eyebrow mb-2 d-inline-block">{{ __('market_country.ng.access') }}</span>
                        <h2 class="p23-cta-title">{{ __('market_country.ng.consider') }}</h2>
                        <p class="p23-cta-desc">
                            {{ __('market_country.ng.cta') }}
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ locale_route('contact') }}" class="p23-btn-brand">
                                {{ __('market_country.ng.talk') }} <i class="fas fa-calendar-check ms-1"></i>
                            </a>
                            <a href="{{ route('services.market-demand-testing') }}" class="p23-btn-outline-light">
                                Explore All Capabilities <i class="fas fa-arrow-left ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 text-center mt-4 mt-lg-0">
                        <div class="p-4 rounded-4" style="background: var(--p23-grad-silk-pill); border: 1px solid rgba(82, 204, 180, 0.35); box-shadow: var(--p23-shadow-silk-pill);">
                            <div class="mb-2" style="font-size: 2.2rem; color: var(--p23-mint-glow);">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4 class="text-white" style="font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif;">{{ __('market_country.ng.footprint') }}</h4>
                            <p style="font-size: 0.92rem; color: rgba(244, 250, 248, 0.8);" class="mb-0">
                                {{ __('market_country.ng.foot_body') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
