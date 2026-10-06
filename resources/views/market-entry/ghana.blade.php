@extends('layouts.master')
@section('title', 'Ghana Market Entry Case Study: Construction Platform | P23 Africa')

@section('meta')
    <meta name="description"
        content="From multi-market testing across Ghana, Nigeria & Botswana to 5,000 contractors and 2,150 renters in six months. Discover how P23 built a functioning marketplace in Ghana.">
    <meta name="keywords"
        content="Ghana market entry, Ghana tech marketplace, Accra construction equipment, commercial expansion Ghana, market validation Africa">
    <meta property="og:title" content="Ghana Case Study: From Testing to 5,000 Contractors in 6 Months | P23 Africa">
    <meta property="og:description" content="How P23 Africa validated and scaled a digital marketplace in Ghana, onboarding 2,000+ vendors and 5,000+ contractors.">
    <meta property="og:image" content="{{ asset('images/servces-bg.png') }}">
    <link rel="stylesheet" href="{{ asset('css/market-entry.css') }}">
@endsection

@section('content')

    <!-- Dossier Breadcrumbs Bar -->
    <div class="p23-dossier-nav-bar">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <ul class="p23-breadcrumb">
                <li><a href="{{ route('homepage') }}">Home</a></li>
                <li class="separator">/</li>
                <li><a href="{{ url('services') }}">What We Do</a></li>
                <li class="separator">/</li>
                <li><a href="{{ route('services.market-entry') }}">Market Entry &amp; Expansion</a></li>
                <li class="separator">/</li>
                <li class="current">Ghana Platform Case Study</li>
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
                        <span class="case-meta-tag"><i class="fas fa-flag me-1"></i> Ghana (vs Nigeria &amp; Botswana)</span>
                        <span class="case-meta-tag"><i class="fas fa-cubes me-1"></i> Construction &amp; Equipment Marketplace</span>
                        <span class="case-meta-tag"><i class="fas fa-rocket me-1"></i> 6 Months Post-Launch Scale</span>
                    </div>

                    <h1 class="p23-section-title text-white mb-3" style="font-size: clamp(2.2rem, 3.8vw, 3.4rem);">
                        From market testing to 5,000 contractors and 2,150 renters in six months.
                    </h1>

                    <p class="hero-subtitle mb-4">
                        Entering a new market is one challenge. Building a functioning business in that market is another. When a South African construction company developed a digital platform connecting construction contractors with equipment providers, it wanted to understand where the concept had the strongest potential before committing to a full market launch.
                    </p>

                    <p style="color: rgba(255, 255, 255, 0.75); font-size: 1.05rem;">
                        P23 Africa helped test the opportunity across Ghana, Nigeria and Botswana. Ghana emerged as the strongest market. From there, we turned the opportunity into an operating business.
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
                    <div class="p23-stat-label">Equipment Vendors Onboarded</div>
                </div>
                <div class="col-12 col-md-4 p23-stat-item">
                    <div class="p23-stat-number">5,000<span class="accent">+</span></div>
                    <div class="p23-stat-label">Contractors Onboarded</div>
                </div>
                <div class="col-12 col-md-4 p23-stat-item">
                    <div class="p23-stat-number">2,150<span class="accent">+</span></div>
                    <div class="p23-stat-label">Contractors Actively Rented</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Dossier Content Body -->
    <section class="p23-section p23-section-white">
        <div class="container">

            <!-- Section 1: First, we tested the markets -->
            <div class="p23-challenge-box">
                <span class="p23-eyebrow">Phase 01 // Multi-Market Validation</span>
                <h2 class="p23-challenge-title">First, we tested the markets: Which market should we enter?</h2>
                <p class="p23-section-desc mb-3">
                    The client had developed a platform with the potential to transform how construction contractors access equipment. But before investing heavily in launching across Africa, there was an important question to answer:
                </p>
                <div class="p23-transition-callout">
                    <span class="p23-transition-callout-tag">Strategic Core Question:</span>
                    <span class="p23-transition-callout-headline">
                        “Where should we launch first?”
                    </span>
                </div>
                <p class="p23-section-desc mb-4">
                    P23 helped test demand across three dynamic target corridors: <strong>Ghana | Nigeria | Botswana</strong>.
                </p>
                <p class="p23-section-desc mb-0">
                    Rather than relying on assumptions about which market looked most attractive, we tested the proposition directly with the market. We engaged potential users, equipment providers and evaluated the commercial response to the platform. <strong>Ghana emerged as the strongest initial potential</strong>, giving the client a clear, data-driven basis for deciding where to focus its capital and talent.
                </p>
            </div>

            <!-- Section 2: Then we helped build the business -->
            <div class="p23-step-card my-4">
                <div class="p23-step-card-header">
                    <div class="p23-step-number-badge"><i class="fas fa-tools"></i></div>
                    <div class="flex-grow-1">
                        <span class="p23-eyebrow">Phase 02 // Launch Architecture</span>
                        <h3 class="p23-step-title">Then we helped build the business: From validation to launch</h3>
                        <p class="p23-step-lead">
                            Once Ghana had been identified as the priority market, the work shifted from <em>“Is there an opportunity?”</em> to <em>“How do we build it?”</em> P23 worked with local partners to establish the operation and support the client through early-stage launch:
                        </p>
                        <div class="p23-checklist-grid">
                            <div class="p23-check-item"><i class="fas fa-check"></i> Establish the local Ghana operation</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> Build the initial frontline team</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> Develop the go-to-market approach</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> Identify priority customer segments</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> Build the qualified prospect pipeline</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> Launch initial online marketing campaigns</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> Launch offline sales &amp; direct contractor visits</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> Develop equipment vendor partnerships</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> Acquire verified contractors onto platform</div>
                            <div class="p23-check-item"><i class="fas fa-check"></i> Establish ecosystem value-add partnerships</div>
                        </div>
                        <p class="p23-section-desc mt-3 mb-0" style="font-weight: 700; color: var(--p23-teal-dark);">
                            The objective wasn’t simply to launch a website. It was to build a functioning, liquid marketplace.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 3: Building both sides of the marketplace -->
            <div class="p23-step-card p23-step-card-featured my-4">
                <div class="p23-step-card-header">
                    <div class="p23-step-number-badge">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div class="flex-grow-1">
                        <span class="p23-eyebrow">Liquidity &amp; Supply-Demand</span>
                        <h3 class="p23-step-title">Building both sides of the marketplace</h3>
                        <p class="p23-step-lead">
                            Vendors and contractors had to grow together. A platform like this only works when there is active liquidity on both sides: contractors need access to machinery, while equipment owners need contractors ready to rent.
                        </p>
                        <p class="p23-section-desc">
                            That meant P23’s market development activity had to operate simultaneously across both audiences. Our prospecting and tracking software provided live visibility over activity:
                        </p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="p23-highlight-card">
                                    <strong class="d-block text-dark mb-1" style="font-size: 1.05rem;">Targeting &amp; Pipeline</strong>
                                    <span style="font-size: 0.9rem; color: var(--p23-text-muted);">Who was being targeted and contacted across Accra and mining/industrial belts.</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p23-highlight-card">
                                    <strong class="d-block text-dark mb-1" style="font-size: 1.05rem;">Relationship Stage</strong>
                                    <span style="font-size: 0.9rem; color: var(--p23-text-muted);">What stage each vendor relationship was at, from sign-up to fleet listing.</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p23-highlight-card">
                                    <strong class="d-block text-dark mb-1" style="font-size: 1.05rem;">Performance Feedback</strong>
                                    <span style="font-size: 0.9rem; color: var(--p23-text-muted);">Which marketing messages and outreach cadences produced verified rentals.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Six months in, the numbers told the story -->
            <div class="my-5 p23-case-outcome-showcase">
                <span class="p23-eyebrow">Traction at 6 Months</span>
                <h2 class="text-white mb-4" style="font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif; font-size: clamp(1.8rem, 2.8vw, 2.5rem);">
                    2,000+ vendors. 5,000+ contractors. 2,150+ renters.
                </h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="p23-outcome-metric-card h-100">
                            <div class="display-6 fw-bold mb-2" style="color: var(--p23-mint-bright); font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif;">2,000+</div>
                            <h5 class="text-white">Equipment Vendors</h5>
                            <p style="font-size: 0.92rem; color: rgba(244, 250, 248, 0.82); line-height: 1.6;" class="mb-0">
                                Equipment providers joined the platform, significantly expanding the catalog and availability of heavy machinery for contractors.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p23-outcome-metric-card h-100">
                            <div class="display-6 fw-bold mb-2" style="color: var(--p23-mint-bright); font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif;">5,000+</div>
                            <h5 class="text-white">Contractors Onboarded</h5>
                            <p style="font-size: 0.92rem; color: rgba(244, 250, 248, 0.82); line-height: 1.6;" class="mb-0">
                                The platform built a substantial, highly engaged community of contractors looking for reliable access to equipment.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p23-outcome-metric-card h-100">
                            <div class="display-6 fw-bold mb-2" style="color: var(--p23-mint-bright); font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif;">2,150+</div>
                            <h5 class="text-white">Active Renters</h5>
                            <p style="font-size: 0.92rem; color: rgba(244, 250, 248, 0.82); line-height: 1.6;" class="mb-0">
                                Critically, the platform was not simply collecting empty logins: over 2,150 contractors completed paid rentals, demonstrating meaningful commercial traction.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: We didn't stop at launch & Partnerships -->
            <div class="row g-4 my-4">
                <div class="col-12 col-lg-6">
                    <div class="p23-step-card h-100">
                        <span class="p23-eyebrow">Agile Growth</span>
                        <h3 class="p23-step-title">We didn’t stop at launch: Continuous market optimisation</h3>
                        <p class="p23-section-desc">
                            A successful market launch isn’t a fixed plan. The market tells you what is working, and what isn’t. As the business developed, P23 continued to monitor activity and performance, identifying where the strongest opportunities were emerging.
                        </p>
                        <p class="p23-section-desc mb-0">
                            We continually refined who was targeted, which messages resonated, where sales teams concentrated, and which commercial partnerships unlocked higher deal value.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="p23-step-card h-100 p23-step-card-featured">
                        <span class="p23-eyebrow">Proposition Value</span>
                        <h3 class="p23-step-title">Strengthening the proposition through partnerships</h3>
                        <p class="p23-section-desc">
                            As the platform developed, P23 facilitated strategic partnerships that improved customer confidence. One example was the development of <strong>integrated insurance partnerships</strong>.
                        </p>
                        <p class="p23-section-desc mb-0">
                            This gave equipment owners and renting contractors peace of mind with broader damage and loss protection, turning equipment hire from a high-friction risk into a trusted digital transaction.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 6: Summary Matrix: "What P23 actually did" -->
            <div class="my-5">
                <div class="p23-section-header text-center">
                    <span class="p23-eyebrow">Comprehensive Execution</span>
                    <h2 class="p23-section-title">What P23 actually did</h2>
                    <p class="p23-section-desc">
                        This project demonstrates that market entry doesn’t have to stop at research. P23 supported the client across the entire commercial lifecycle:
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">01. Validation</span>
                            <h6 class="fw-bold mt-2">Market Validation</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">Tested Ghana, Nigeria and Botswana to identify where proposition had strongest potential.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">02. Setup</span>
                            <h6 class="fw-bold mt-2">Local Market Setup</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">Worked with local partners to establish compliant in-country operation.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">03. Talent</span>
                            <h6 class="fw-bold mt-2">Team Development</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">Recruited and trained the ground sales &amp; operations team.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">04. GTM</span>
                            <h6 class="fw-bold mt-2">Go-to-Market Campaigns</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">Developed and executed online and offline acquisition funnels.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">05. Pipeline</span>
                            <h6 class="fw-bold mt-2">Active Prospecting</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">Identified and prioritised high-volume contractors and equipment yards.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">06. Tech</span>
                            <h6 class="fw-bold mt-2">Operational Visibility</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">Deployed P23 tracking software to maintain transparency over activities.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">07. Ecosystem</span>
                            <h6 class="fw-bold mt-2">Strategic Partnerships</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">Facilitated equipment insurance partnerships to build trust.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p23-execution-card">
                            <span class="p23-dossier-tag mb-2">08. Scale</span>
                            <h6 class="fw-bold mt-2">Market Optimisation</h6>
                            <p style="font-size: 0.88rem; color: var(--p23-text-body); margin: 0;">Used live performance data to continually focus resources on winning channels.</p>
                        </div>
                    </div>
                </div>

                <div class="p23-framework-summary-card my-4">
                    <h4>
                        From market testing to market growth. This is the P23 approach.
                    </h4>
                    <p class="mb-0">
                        Understand. Validate. Connect. Execute. Entering a market is only the beginning. Building a functioning, profitable business there is the real objective.
                    </p>
                </div>
            </div>

            <!-- Cross-Link Sibling Case Strip -->
            <div class="p23-sibling-case-strip">
                <div class="info-text">
                    <span class="text-uppercase" style="font-size: 0.78rem; letter-spacing: 0.1em; color: var(--p23-mint-glow); font-weight: 700;">Sibling Case Study Dossier</span>
                    <strong>Nigeria: HVAC Manufacturer</strong>
                    <span>Pre-capital demand testing, 10-point distributor due diligence, and ground tracking software.</span>
                </div>
                <a href="{{ route('services.market-entry.nigeria') }}" class="p23-dossier-link">
                    Explore Nigeria Case Study <span>→</span>
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
                        <span class="p23-eyebrow mb-2 d-inline-block">Ghana Market Acceleration</span>
                        <h2 class="p23-cta-title">Looking to enter Ghana?</h2>
                        <p class="p23-cta-desc">
                            If you’re considering Ghana, or trying to determine which African market should come first, P23 can help you test the opportunity, build the operation and develop the route to growth.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ url('book-consultation') }}" class="p23-btn-brand">
                                Start a conversation with P23 Africa <i class="fas fa-calendar-check ms-1"></i>
                            </a>
                            <a href="{{ route('services.market-entry') }}" class="p23-btn-outline-light">
                                Explore Market Entry &amp; Expansion <i class="fas fa-arrow-left ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 text-center mt-4 mt-lg-0">
                        <div class="p-4 rounded-4" style="background: var(--p23-grad-silk-pill); border: 1px solid rgba(82, 204, 180, 0.35); box-shadow: var(--p23-shadow-silk-pill);">
                            <div class="mb-2" style="font-size: 2.2rem; color: var(--p23-mint-glow);">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <h4 class="text-white" style="font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif;">Accra Commercial Hub</h4>
                            <p style="font-size: 0.92rem; color: rgba(244, 250, 248, 0.8);" class="mb-0">
                                Testing, team setup, marketplace liquidity, and corporate partnerships across Ghana.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
