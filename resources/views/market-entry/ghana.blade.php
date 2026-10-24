@extends('layouts.master')
@section('title', __('market_country.gh.title'))

@section('meta')
    <meta name="description"
        content="{{ __('market_country.gh.meta') }}">
    <meta name="keywords"
        content="{{ __('market_country.gh.keywords') }}">
    <meta property="og:title" content="{{ __('market_country.gh.og_title') }}">
    <meta property="og:description" content="{{ __('market_country.gh.og_desc') }}">
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
                <li><a href="{{ locale_route('services.market-entry') }}">{{ __('services.market_entry') }}</a></li>
                <li class="separator">/</li>
                <li class="current">{{ __('market_country.gh.crumb') }}</li>
            </ul>
            <div class="p23-dossier-id-tag">
                DOSSIER REF: GH-CASE-2402
            </div>
        </div>
    </div>

    <!-- Case Study Hero -->
    <section class="p23-case-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-10">
                    <div class="case-meta-tags">
                        <span class="case-meta-tag"><i class="fas fa-flag me-1"></i> {{ __('market_country.gh.vs') }}</span>
                        <span class="case-meta-tag"><i class="fas fa-cubes me-1"></i> {{ __('market_country.gh.sector') }}</span>
                        <span class="case-meta-tag"><i class="fas fa-rocket me-1"></i> {{ __('market_country.gh.tag') }}</span>
                    </div>

                    <h1 class="p23-section-title text-white mb-3" style="font-size: clamp(2.2rem, 3.8vw, 3.4rem);">
                        {{ __('market_country.gh.hero') }}
                    </h1>

                    <p class="hero-subtitle mb-4">
                        {{ __('market_country.gh.hero_sub') }}
                    </p>

                    <p style="color: rgba(255, 255, 255, 0.75); font-size: 1.05rem;">
                        {{ __('market_country.gh.hero_2') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Metrics Strip (Directly inspired by Reference 2) -->
    <section class="p23-executive-stat-bar">
        <div class="container">
            <div class="row text-center align-items-center">
                <div class="col-12 col-md-4 p23-stat-item">
                    <div class="p23-stat-number">2,000<span class="accent">+</span></div>
                    <div class="p23-stat-label">{{ __('market_country.gh.vendors') }}</div>
                </div>
                <div class="col-12 col-md-4 p23-stat-item">
                    <div class="p23-stat-number">5,000<span class="accent">+</span></div>
                    <div class="p23-stat-label">{{ __('market_country.gh.contractors') }}</div>
                </div>
                <div class="col-12 col-md-4 p23-stat-item">
                    <div class="p23-stat-number">2,150<span class="accent">+</span></div>
                    <div class="p23-stat-label">{{ __('market_country.gh.renters') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Dossier Content Body -->
    <section class="p23-section p23-section-white">
        <div class="container">

            <!-- Section 1: First, we tested the markets -->
            <div class="p23-challenge-box">
                <span class="p23-eyebrow">{{ __('market_country.gh.phase1') }}</span>
                <h2 class="p23-challenge-title">{{ __('market_country.gh.which') }}</h2>
                <p class="p23-section-desc mb-3">
                    {{ __('market_country.gh.which_body') }}
                </p>
                <div class="p23-transition-callout">
                    <span class="p23-transition-callout-tag">{{ __('market_country.gh.core') }}</span>
                    <span class="p23-transition-callout-headline">
                        {{ __('market_country.gh.where') }}
                    </span>
                </div>
                <p class="p23-section-desc mb-4">
                    {!! __('market_country.gh.corridors') !!}
                </p>
                <p class="p23-section-desc mb-0">
                    {!! __('market_country.gh.emerged') !!}
                </p>
            </div>

            <!-- Section 2: Then we helped build the business -->
            <div class="p23-step-card my-4">
                <div class="p23-step-card-header">
                    <div class="p23-step-number-badge"><i class="fas fa-tools"></i></div>
                    <div class="flex-grow-1">
                        <span class="p23-eyebrow">{{ __('market_country.gh.phase2') }}</span>
                        <h3 class="p23-step-title">{{ __('market_country.gh.build') }}</h3>
                        <p class="p23-step-lead">
                            {!! __('market_country.gh.build_lead') !!}
                        </p>
                        <div class="p23-checklist-grid">
                            <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.gh.b1') }}</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.gh.b2') }}</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.gh.b3') }}</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.gh.b4') }}</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.gh.b5') }}</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.gh.b6') }}</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.gh.b7') }}</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.gh.b8') }}</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.gh.b9') }}</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> {{ __('market_country.gh.b10') }}</div>
                        </div>
                        <p class="p23-section-desc mt-3 mb-0" style="font-weight: 700; color: var(--p23-teal-dark);">
                            {{ __('market_country.gh.liquid') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 3: {{ __('market_country.gh.both') }} -->
            <div class="p23-step-card p23-step-card-featured my-4">
                <div class="p23-step-card-header">
                    <div class="p23-step-number-badge">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div class="flex-grow-1">
                        <span class="p23-eyebrow">{{ __('market_country.gh.liquidity') }}</span>
                        <h3 class="p23-step-title">Building both sides of the marketplace</h3>
                        <p class="p23-step-lead">
                            {{ __('market_country.gh.both_lead') }}
                        </p>
                        <p class="p23-section-desc">
                            {{ __('market_country.gh.both_body') }}
                        </p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="p23-highlight-card">
                                    <strong class="d-block text-dark mb-1" style="font-size: 1.05rem;">{{ __('market_country.gh.h1') }}</strong>
                                    <span style="font-size: 0.9rem; color: var(--p23-text-muted);">{{ __('market_country.gh.h1b') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p23-highlight-card">
                                    <strong class="d-block text-dark mb-1" style="font-size: 1.05rem;">{{ __('market_country.gh.h2') }}</strong>
                                    <span style="font-size: 0.9rem; color: var(--p23-text-muted);">{{ __('market_country.gh.h2b') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p23-highlight-card">
                                    <strong class="d-block text-dark mb-1" style="font-size: 1.05rem;">{{ __('market_country.gh.h3') }}</strong>
                                    <span style="font-size: 0.9rem; color: var(--p23-text-muted);">{{ __('market_country.gh.h3b') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Six months in, the numbers told the story -->
            <div class="my-5 p23-case-outcome-showcase">
                <span class="p23-eyebrow">{{ __('market_country.gh.traction') }}</span>
                <h2 class="text-white mb-4" style="font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif; font-size: clamp(1.8rem, 2.8vw, 2.5rem);">
                    {{ __('market_country.gh.numbers') }}
                </h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="p23-outcome-metric-card h-100">
                            <div class="display-6 fw-bold mb-2" style="color: var(--p23-mint-bright); font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif;">2,000+</div>
                            <h5 class="text-white">{{ __('market_country.gh.eq') }}</h5>
                            <p style="font-size: 0.92rem; color: rgba(244, 250, 248, 0.82); line-height: 1.6;" class="mb-0">
                                {{ __('market_country.gh.eq_b') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p23-outcome-metric-card h-100">
                            <div class="display-6 fw-bold mb-2" style="color: var(--p23-mint-bright); font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif;">5,000+</div>
                            <h5 class="text-white">{{ __('market_country.gh.co') }}</h5>
                            <p style="font-size: 0.92rem; color: rgba(244, 250, 248, 0.82); line-height: 1.6;" class="mb-0">
                                {{ __('market_country.gh.co_b') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p23-outcome-metric-card h-100">
                            <div class="display-6 fw-bold mb-2" style="color: var(--p23-mint-bright); font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif;">2,150+</div>
                            <h5 class="text-white">{{ __('market_country.gh.ar') }}</h5>
                            <p style="font-size: 0.92rem; color: rgba(244, 250, 248, 0.82); line-height: 1.6;" class="mb-0">
                                {{ __('market_country.gh.ar_b') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: We didn't stop at launch & Partnerships -->
            <div class="row g-4 my-4">
                <div class="col-12 col-lg-6">
                    <div class="p23-step-card h-100">
                        <span class="p23-eyebrow">{{ __('market_country.gh.agile') }}</span>
                        <h3 class="p23-step-title">{{ __('market_country.gh.opt') }}</h3>
                        <p class="p23-section-desc">
                            {{ __('market_country.gh.opt_1') }}
                        </p>
                        <p class="p23-section-desc mb-0">
                            {{ __('market_country.gh.opt_2') }}
                        </p>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="p23-step-card h-100 p23-step-card-featured">
                        <span class="p23-eyebrow">{{ __('market_country.gh.prop') }}</span>
                        <h3 class="p23-step-title">{{ __('market_country.gh.part') }}</h3>
                        <p class="p23-section-desc">
                            {!! __('market_country.gh.part_1') !!}
                        </p>
                        <p class="p23-section-desc mb-0">
                            {{ __('market_country.gh.part_2') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 6: Summary Matrix: "{{ __('market_country.gh.did') }}" -->
            <div class="my-5">
                <div class="p23-section-header text-center">
                    <span class="p23-eyebrow">{{ __('market_country.gh.exec') }}</span>
                    <h2 class="p23-section-title">What P23 actually did</h2>
                    <p class="p23-section-desc">
                        {{ __('market_country.gh.did_body') }}
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">{{ __('market_country.gh.e1') }}</span>
                            <h6 class="fw-bold mt-2">{{ __('market_country.gh.e1t') }}</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">{{ __('market_country.gh.e1b') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">{{ __('market_country.gh.e2') }}</span>
                            <h6 class="fw-bold mt-2">{{ __('market_country.gh.e2t') }}</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">{{ __('market_country.gh.e2b') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">{{ __('market_country.gh.e3') }}</span>
                            <h6 class="fw-bold mt-2">{{ __('market_country.gh.e3t') }}</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">{{ __('market_country.gh.e3b') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">{{ __('market_country.gh.e4') }}</span>
                            <h6 class="fw-bold mt-2">{{ __('market_country.gh.e4t') }}</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">{{ __('market_country.gh.e4b') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">{{ __('market_country.gh.e5') }}</span>
                            <h6 class="fw-bold mt-2">{{ __('market_country.gh.e5t') }}</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">{{ __('market_country.gh.e5b') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">{{ __('market_country.gh.e6') }}</span>
                            <h6 class="fw-bold mt-2">{{ __('market_country.gh.e6t') }}</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">{{ __('market_country.gh.e6b') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">{{ __('market_country.gh.e7') }}</span>
                            <h6 class="fw-bold mt-2">{{ __('market_country.gh.e7t') }}</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">{{ __('market_country.gh.e7b') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">{{ __('market_country.gh.e8') }}</span>
                            <h6 class="fw-bold mt-2">{{ __('market_country.gh.e8t') }}</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">{{ __('market_country.gh.e8b') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p23-framework-summary-card my-4">
                    <h4>
                        {{ __('market_country.gh.approach') }}
                    </h4>
                    <p class="mb-0">
                        {{ __('market_country.gh.approach_body') }}
                    </p>
                </div>
            </div>

            <!-- Cross-Link Sibling Case Strip -->
            <div class="p23-sibling-case-strip">
                <div class="info-text">
                    <span class="text-uppercase" style="font-size: 0.78rem; letter-spacing: 0.1em; color: var(--p23-mint-glow); font-weight: 700;">{{ __('market_country.gh.sibling') }}</span>
                    <strong>{{ __('market_country.gh.sib_title') }}</strong>
                    <span>{{ __('market_country.gh.sib_body') }}</span>
                </div>
                <a href="{{ locale_route('services.market-entry.nigeria') }}" class="p23-dossier-link">
                    {{ __('market_country.gh.sib_link') }} <span>→</span>
                </a>
            </div>

        </div>
    </section>

    <!-- Ghana Case Conversion CTA Banner -->
    <section class="p23-section p23-section-cream">
        <div class="container">
            <div class="p23-cta-banner-wrapper">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-8">
                        <span class="p23-eyebrow mb-2 d-inline-block">{{ __('market_country.gh.accel') }}</span>
                        <h2 class="p23-cta-title">{{ __('market_country.gh.looking') }}</h2>
                        <p class="p23-cta-desc">
                            {{ __('market_country.gh.cta') }}
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ locale_route('contact') }}" class="p23-btn-brand">
                                {{ __('market_country.gh.talk') }} <i class="fas fa-calendar-check ms-1"></i>
                            </a>
                            <a href="{{ locale_route('services.market-entry') }}" class="p23-btn-outline-light">
                                {{ __('market_country.gh.explore') }} <i class="fas fa-arrow-left ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 text-center mt-4 mt-lg-0">
                        <div class="p-4 rounded-4" style="background: var(--p23-grad-silk-pill); border: 1px solid rgba(82, 204, 180, 0.35); box-shadow: var(--p23-shadow-silk-pill);">
                            <div class="mb-2" style="font-size: 2.2rem; color: var(--p23-mint-glow);">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <h4 class="text-white" style="font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif;">{{ __('market_country.gh.hub') }}</h4>
                            <p style="font-size: 0.92rem; color: rgba(244, 250, 248, 0.8);" class="mb-0">
                                {{ __('market_country.gh.hub_body') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
