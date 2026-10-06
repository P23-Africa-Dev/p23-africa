@extends('layouts.master')
@section('title', 'Market Entry & Expansion across Nigeria, Ghana, Kenya & South Africa | P23 Africa')

@section('meta')
    <meta name="description"
        content="Enter Africa with confidence. Build with local knowledge. P23 Africa helps businesses test demand, vet distributors, and execute commercial expansion across Nigeria, Ghana, Kenya, and South Africa.">
    <meta name="keywords"
        content="Market entry Africa, expand business to Nigeria, expand to Ghana, African business expansion, distributor due diligence Africa, demand validation Africa">
    <meta property="og:title" content="Market Entry & Expansion | P23 Africa">
    <meta property="og:description" content="Move from market opportunity to practical execution across Nigeria, Ghana, Kenya, and South Africa.">
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
                <li class="current">Market Entry &amp; Expansion</li>
            </ul>
            <div class="p23-dossier-id-tag">
                P23 // COMMERCIAL ADVISORY // 2026
            </div>
        </div>
    </div>

    <!-- Hero Section (C-Suite Editorial Design) -->
    <section class="market-entry-hero">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-12 col-lg-9">
                    <div class="hero-editorial-badge">
                        <span class="pulse-dot"></span>
                        <span>Commercial Intelligence &amp; Practical Execution</span>
                    </div>

                    <h1>
                        Enter Africa with confidence. <br>
                        <span class="accent-editorial">Build with local knowledge.</span>
                    </h1>

                    <p class="hero-subtitle">
                        Expanding into Africa presents significant opportunities, but successful market entry requires more than a market report. You need to understand the market, test the opportunity, find the right people, build the right relationships and execute locally.
                    </p>

                    <div class="market-country-strip">
                        <span class="strip-label">Primary Footprint:</span>
                        <a href="{{ route('services.market-entry.nigeria') }}" class="market-pill-item">
                            <i class="fas fa-landmark me-1" style="color: var(--p23-mint-bright);"></i> Nigeria
                        </a>
                        <a href="{{ route('services.market-entry.ghana') }}" class="market-pill-item">
                            <i class="fas fa-chart-line me-1" style="color: var(--p23-mint-bright);"></i> Ghana
                        </a>
                        <span class="market-pill-item">
                            <i class="fas fa-network-wired me-1"></i> Kenya
                        </span>
                        <span class="market-pill-item">
                            <i class="fas fa-city me-1"></i> South Africa
                        </span>
                    </div>

                    <div class="hero-cta-group">
                        <a href="{{ url('book-consultation') }}" class="p23-btn-brand">
                            Talk to P23 Africa <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                        <a href="#case-studies" class="p23-btn-outline-light">
                            Explore Case Studies <i class="fas fa-chevron-down ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Executive Stat Bar Strip (Inspired by Reference 2) -->
    <section class="p23-executive-stat-bar">
        <div class="container">
            <div class="row text-center align-items-center">
                <div class="col-12 col-md-3 p23-stat-item">
                    <div class="p23-stat-number">4 <span class="accent">Key Hubs</span></div>
                    <div class="p23-stat-label">Nigeria • Ghana • Kenya • South Africa</div>
                </div>
                <div class="col-12 col-md-3 p23-stat-item">
                    <div class="p23-stat-number">01 <span class="accent">Approach</span></div>
                    <div class="p23-stat-label">Outbound Demand Validation First</div>
                </div>
                <div class="col-12 col-md-3 p23-stat-item">
                    <div class="p23-stat-number">10 <span class="accent">Point</span></div>
                    <div class="p23-stat-label">Distributor Due Diligence Framework</div>
                </div>
                <div class="col-12 col-md-3 p23-stat-item">
                    <div class="p23-stat-number">100% <span class="accent">Ground</span></div>
                    <div class="p23-stat-label">Operational Visibility &amp; Tech Tracking</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Narrative & Positioning -->
    <section class="p23-section p23-section-white">
        <div class="container">
            <div class="row align-items-center justify-content-between g-5">
                <div class="col-12 col-lg-6">
                    <span class="p23-eyebrow">The Execution Imperative</span>
                    <h2 class="p23-section-title">
                        P23 Africa helps businesses move from market opportunity to practical execution.
                    </h2>
                    <p class="p23-section-desc mb-4">
                        We combine market intelligence, commercial expertise, local networks and technology to help businesses enter and grow sustainably across our target regional corridors.
                    </p>
                    <p class="p23-section-desc mb-4">
                        Rather than hand over static theoretical binders, we sit with you on the frontline: qualifying commercial demand, screening reliable partners, and building transparent operational channels that safeguard your capital.
                    </p>
                    <a href="{{ url('book-consultation') }}" class="p23-dossier-link">
                        Book a confidential market exploration session <span>→</span>
                    </a>
                </div>
                <div class="col-12 col-lg-5">
                    <div class="p23-dossier-data-box">
                        <div class="box-title">
                            <i class="fas fa-shield-alt me-2" style="color: var(--p23-mint-bright);"></i> Why Generic Reports Fail
                        </div>
                        <p class="mb-3" style="font-size: 0.96rem; line-height: 1.6; color: rgba(244, 250, 248, 0.88);">
                            Secondary desk research rarely uncovers what buyers are willing to pay today, which distributors actually maintain functional cold chains or warehouses, or where currency and regulatory bottlenecks create friction.
                        </p>
                        <div class="p23-outcome-pill mb-2">
                            <i class="fas fa-check-circle" style="color: var(--p23-mint-glow);"></i> Direct engagement replaces assumptions with verified signals
                        </div>
                        <div class="p23-outcome-pill mb-2">
                            <i class="fas fa-check-circle" style="color: var(--p23-mint-glow);"></i> On-the-ground reference checks mitigate distributor default
                        </div>
                        <div class="p23-outcome-pill">
                            <i class="fas fa-check-circle" style="color: var(--p23-mint-glow);"></i> Proprietary tracking technology delivers remote oversight
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Case Studies Section: "From market opportunity to market presence" -->
    <!-- (Inspired by Reference 1: Executive Editorial Dossier Sheet & Reference 2: Tabbed Showcase) -->
    <section class="p23-section p23-section-cream" id="case-studies">
        <div class="container">
            <div class="p23-section-header text-center">
                <span class="p23-eyebrow">Proven Field Outcomes</span>
                <h2 class="p23-section-title">From market opportunity to market presence</h2>
                <p class="p23-section-desc">
                    Examine how multinational manufacturers and scale-ups successfully entered African markets without risking unvetted capital.
                </p>

                <!-- Interactive Dossier Switcher Tabs -->
                <div class="p23-case-tabs-wrapper mt-4">
                    <div class="p23-case-tabs" role="tablist">
                        <button class="p23-case-tab-btn active" id="tab-ng-btn" onclick="switchCaseDossier('nigeria')">
                            Nigeria: HVAC Manufacturer
                        </button>
                        <button class="p23-case-tab-btn" id="tab-gh-btn" onclick="switchCaseDossier('ghana')">
                            Ghana: Construction Platform
                        </button>
                    </div>
                </div>
            </div>

            <!-- DOSSIER 1: NIGERIA CASE (Default Active) -->
            <div id="dossier-nigeria" class="p23-dossier-frame">
                <div class="p23-dossier-folio">
                    <div class="folio-left">
                        <div class="binder-punches">
                            <span class="punch-hole"></span>
                            <span class="punch-hole"></span>
                            <span class="punch-hole"></span>
                        </div>
                        <span class="folio-category">Executive Dossier // Case Brief</span>
                    </div>
                    <div class="folio-right">
                        REF: P23-DOSSIER-NG-HVAC
                    </div>
                </div>

                <div class="p23-dossier-body">
                    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                        <span class="p23-dossier-tag"><i class="fas fa-flag me-1"></i> NIGERIA</span>
                        <span class="p23-dossier-meta">SECTOR: INDUSTRIAL HVAC MANUFACTURING</span>
                    </div>

                    <h3 class="p23-dossier-headline">
                        HVAC Manufacturer: Testing demand and vetting distributors before committing capital.
                    </h3>

                    <div class="p23-dossier-overview-row">
                        <div>
                            <p class="p23-dossier-lead">
                                An HVAC manufacturer wanted to explore the Nigerian market before committing significant capital. Rather than relying on high-level surveys, P23 was engaged to generate genuine commercial signal from real businesses.
                            </p>
                            <p class="p23-dossier-lead">
                                P23 tested demand through direct commercial outreach, gathered customer and pricing intelligence, identified potential distributors, and carried out rigorous distributor due diligence. We then facilitated the relationship with a vetted distributor and implemented tracking technology to give the client full visibility over activity on the ground.
                            </p>
                        </div>
                        <div class="p23-dossier-data-box">
                            <div class="box-title">
                                <i class="fas fa-chart-line text-success me-2"></i> Engagement Scope
                            </div>
                            <div class="data-box-metrics">
                                <div class="data-box-metric-item">
                                    <div class="data-box-metric-val">Pre</div>
                                    <div class="data-box-metric-sub">Capital Risk</div>
                                </div>
                                <div class="data-box-metric-item">
                                    <div class="data-box-metric-val">100%</div>
                                    <div class="data-box-metric-sub">Verified Intel</div>
                                </div>
                            </div>
                            <div style="font-size: 0.88rem; color: rgba(244, 250, 248, 0.88); line-height: 1.55;">
                                <strong>Outcome:</strong> Replaced market assumptions with direct commercial feedback, preventing misallocated capital and establishing a functioning channel.
                            </div>
                        </div>
                    </div>

                    <!-- 3 Highlights Cards (Inspired by Reference 1 - Clarify Health Highlights) -->
                    <div class="p23-highlights-header">
                        <i class="fas fa-star" style="color: var(--p23-mint-deep);"></i>
                        <h4>Highlights</h4>
                    </div>

                    <div class="p23-highlights-grid">
                        <div class="p23-highlight-card">
                            <div class="p23-highlight-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h5>Direct Outbound Demand Testing</h5>
                            <p>
                                Replaced surveys with targeted outreach across potential buyers and contractors to ascertain real purchasing requirements and current pricing benchmarks.
                            </p>
                        </div>

                        <div class="p23-highlight-card">
                            <div class="p23-highlight-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <h5>Distributor Due Diligence</h5>
                            <p>
                                Evaluated candidate distributors on commercial capacity, storage facilities, complementary brand conflicts, and reference checks with existing suppliers.
                            </p>
                        </div>

                        <div class="p23-highlight-card">
                            <div class="p23-highlight-icon">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <h5>Ground Visibility Software</h5>
                            <p>
                                Deployed P23 tracking software to monitor agent and distributor performance, ensuring transparency over stock movements and sales pipeline opportunities.
                            </p>
                        </div>
                    </div>

                    <div class="p23-dossier-action-footer">
                        <a href="{{ route('services.market-entry.nigeria') }}" class="p23-dossier-link">
                            Read the complete Nigeria HVAC case study <span>→</span>
                        </a>
                        <a href="{{ url('book-consultation') }}" class="p23-btn-outline-dark">
                            Discuss Nigeria Market Entry <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- DOSSIER 2: GHANA CASE (Initially Hidden) -->
            <div id="dossier-ghana" class="p23-dossier-frame" style="display: none;">
                <div class="p23-dossier-folio">
                    <div class="folio-left">
                        <div class="binder-punches">
                            <span class="punch-hole"></span>
                            <span class="punch-hole"></span>
                            <span class="punch-hole"></span>
                        </div>
                        <span class="folio-category">Executive Dossier // Case Brief</span>
                    </div>
                    <div class="folio-right">
                        REF: P23-DOSSIER-GH-PROPTECH
                    </div>
                </div>

                <div class="p23-dossier-body">
                    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                        <span class="p23-dossier-tag"><i class="fas fa-flag me-1"></i> GHANA</span>
                        <span class="p23-dossier-meta">SECTOR: CONSTRUCTION EQUIPMENT PLATFORM</span>
                    </div>

                    <h3 class="p23-dossier-headline">
                        Construction Equipment Platform: From multi-country testing to 5,000 contractors in six months.
                    </h3>

                    <div class="p23-dossier-overview-row">
                        <div>
                            <p class="p23-dossier-lead">
                                A South African construction company wanted to determine which market offered the strongest potential for its equipment-hire platform before committing to a full-scale launch.
                            </p>
                            <p class="p23-dossier-lead">
                                P23 tested the opportunity across Ghana, Nigeria, and Botswana, with Ghana emerging as the strongest market. We then helped establish the local operation, assemble the frontline team, launch multi-channel acquisition campaigns, and continuously optimise the go-to-market strategy.
                            </p>
                        </div>
                        <div class="p23-dossier-data-box">
                            <div class="box-title">
                                <i class="fas fa-trophy me-2" style="color: var(--p23-mint-bright);"></i> 6 Months Post-Launch
                            </div>
                            <div class="data-box-metrics">
                                <div class="data-box-metric-item">
                                    <div class="data-box-metric-val">2,000+</div>
                                    <div class="data-box-metric-sub">Vendors</div>
                                </div>
                                <div class="data-box-metric-item">
                                    <div class="data-box-metric-val">5,000+</div>
                                    <div class="data-box-metric-sub">Contractors</div>
                                </div>
                            </div>
                            <div style="font-size: 0.88rem; color: rgba(244, 250, 248, 0.88); line-height: 1.55;">
                                <strong>Traction:</strong> Over <strong>2,150+ contractors</strong> actively completed equipment rentals through the platform in the first 6 months.
                            </div>
                        </div>
                    </div>

                    <!-- 3 Highlights Cards -->
                    <div class="p23-highlights-header">
                        <i class="fas fa-star" style="color: var(--p23-mint-deep);"></i>
                        <h4>Highlights</h4>
                    </div>

                    <div class="p23-highlights-grid">
                        <div class="p23-highlight-card">
                            <div class="p23-highlight-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <h5>Cross-Border Opportunity Testing</h5>
                            <p>
                                Compared Ghana, Nigeria, and Botswana side-by-side to identify the exact market with the highest willingness-to-rent and least operational friction.
                            </p>
                        </div>

                        <div class="p23-highlight-card">
                            <div class="p23-highlight-icon">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            <h5>Two-Sided Marketplace Growth</h5>
                            <p>
                                Synchronised contractor demand with vendor equipment supply through coordinated online campaigns, ground sales, and prospecting software.
                            </p>
                        </div>

                        <div class="p23-highlight-card">
                            <div class="p23-highlight-icon">
                                <i class="fas fa-shield-virus"></i>
                            </div>
                            <h5>Value-Add Strategic Partnerships</h5>
                            <p>
                                Negotiated integrated insurance partnerships to safeguard rented machinery, boosting platform trust and customer retention.
                            </p>
                        </div>
                    </div>

                    <div class="p23-dossier-action-footer">
                        <a href="{{ route('services.market-entry.ghana') }}" class="p23-dossier-link">
                            Read the complete Ghana Marketplace case study <span>→</span>
                        </a>
                        <a href="{{ url('book-consultation') }}" class="p23-btn-outline-dark">
                            Discuss Ghana Market Entry <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Methodology Stepper: "What we do" -->
    <section class="p23-section p23-section-white" id="what-we-do">
        <div class="container">
            <div class="p23-section-header text-center">
                <span class="p23-eyebrow">Our Execution Methodology</span>
                <h2 class="p23-section-title">What we do</h2>
                <p class="p23-section-desc">
                    A rigorous five-stage approach designed to protect capital, validate commercial traction, and establish resilient operating presence.
                </p>
            </div>

            <div class="p23-methodology-stepper">

                <!-- Step 01 -->
                <div class="p23-step-card">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">01</div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">Understand the opportunity</h3>
                            <p class="p23-step-lead">
                                Before entering a market, you need to know where the opportunity actually exists. The objective is to give you the information needed to make informed commercial decisions.
                            </p>
                            <div class="p23-checklist-grid">
                                <div class="p23-check-item"><i class="fas fa-check"></i> Market and sector dynamics</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Customer segments</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Competitors &amp; alternatives</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Pricing structures</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Viable routes to market</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Potential regulatory barriers</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Relevant local businesses &amp; stakeholders</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 02 -->
                <div class="p23-step-card">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">02</div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">Validate demand</h3>
                            <p class="p23-step-lead">
                                Market research can tell you what a market looks like. Direct engagement can tell you how the market responds to your proposition. This helps replace assumptions with real market feedback.
                            </p>
                            <div class="p23-checklist-grid">
                                <div class="p23-check-item"><i class="fas fa-check"></i> Who is genuinely interested</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> What specs they look for</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> What they currently buy</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> What price points they pay</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> What objections &amp; friction exist</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Where demand clusters strongest</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 03 -->
                <div class="p23-step-card">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">03</div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">Find the right people</h3>
                            <p class="p23-step-lead">
                                Successful market entry is often built around relationships. Where appropriate, we help take those relationships beyond an introduction and into commercial discussions.
                            </p>
                            <div class="p23-checklist-grid">
                                <div class="p23-check-item"><i class="fas fa-check"></i> Target enterprise customers</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Established distributors</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Commissioned commercial agents</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Strategic industry partners</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Local suppliers &amp; logistics</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Regional investors &amp; stakeholders</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 04 -->
                <div class="p23-step-card">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">04</div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">Build the route to market</h3>
                            <p class="p23-step-lead">
                                There is no single way to enter an African market. We help determine and execute the specific approach that fits your proposition, financial capacity, and long-term objectives.
                            </p>
                            <div class="p23-checklist-grid">
                                <div class="p23-check-item"><i class="fas fa-check"></i> Direct sales structure</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Master distributor agreements</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Local JV &amp; operating partners</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> In-country dedicated team</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Strategic channel partnerships</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Phased capital deployment</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 05 -->
                <div class="p23-step-card">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">05</div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">Execute locally</h3>
                            <p class="p23-step-lead">
                                Our role doesn’t end once the strategy document is delivered. We support the practical work required to establish, de-risk, and develop your presence in-market.
                            </p>
                            <div class="p23-checklist-grid">
                                <div class="p23-check-item"><i class="fas fa-check"></i> Sales &amp; business development</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Marketing &amp; GTM campaigns</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Local team hiring &amp; onboarding</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Partner &amp; distributor management</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Customer acquisition pipelines</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Ground operational oversight</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Philosophy Matrix: "Africa is not one market" -->
    <section class="p23-pan-africa-section">
        <div class="container">
            <div class="p23-section-header text-center">
                <span class="p23-eyebrow">Local Nuance vs Standardization</span>
                <h2 class="p23-section-title">Africa is not one market.</h2>
                <p class="p23-section-desc" style="max-width: 740px; margin: 0 auto;">
                    Nigeria is different from Ghana. Ghana is different from Kenya. Kenya is different from South Africa. The opportunity, customer psychology, competitive environment and route to market vary dramatically across borders.
                </p>
            </div>

            <div class="p23-country-matrix-grid">
                <!-- Nigeria Card -->
                <div class="p23-country-matrix-card">
                    <div class="matrix-flag"><i class="fas fa-landmark"></i></div>
                    <div class="matrix-country-name">Nigeria</div>
                    <p class="matrix-desc">
                        High volume, dynamic enterprise demand, fragmented distribution networks, and critical distributor vetting requirements.
                    </p>
                    <a href="{{ route('services.market-entry.nigeria') }}" class="matrix-tag text-decoration-none">
                        View Nigeria Strategy <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <!-- Ghana Card -->
                <div class="p23-country-matrix-card">
                    <div class="matrix-flag"><i class="fas fa-chart-line"></i></div>
                    <div class="matrix-country-name">Ghana</div>
                    <p class="matrix-desc">
                        Stable commercial hub, early adopter ecosystem for digital platforms and tech-enabled marketplaces, strong regional gateway.
                    </p>
                    <a href="{{ route('services.market-entry.ghana') }}" class="matrix-tag text-decoration-none">
                        View Ghana Strategy <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <!-- Kenya Card -->
                <div class="p23-country-matrix-card">
                    <div class="matrix-flag"><i class="fas fa-network-wired"></i></div>
                    <div class="matrix-country-name">Kenya</div>
                    <p class="matrix-desc">
                        East Africa's financial and tech anchor, high mobile money penetration, structured corporate routes and regional integration.
                    </p>
                    <span class="matrix-tag">East Africa Hub</span>
                </div>

                <!-- South Africa Card -->
                <div class="p23-country-matrix-card">
                    <div class="matrix-flag"><i class="fas fa-city"></i></div>
                    <div class="matrix-country-name">South Africa</div>
                    <p class="matrix-desc">
                        Sophisticated institutional retail, formal supply chains, stringent compliance standards, and pan-continental expansion capital.
                    </p>
                    <span class="matrix-tag">Southern Africa Hub</span>
                </div>
            </div>

            <div class="text-center mt-4">
                <p style="color: rgba(244, 250, 248, 0.75); max-width: 650px; margin: 0 auto; font-size: 1.02rem;">
                    That’s why we build our approach around your business and the specific market you’re entering, rather than applying a standard expansion playbook.
                </p>
            </div>
        </div>
    </section>

    <!-- Closing Executive CTA Section -->
    <section class="p23-section p23-section-cream">
        <div class="container">
            <div class="p23-cta-banner-wrapper">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-8">
                        <span class="p23-eyebrow mb-2 d-inline-block">Take The Next Step</span>
                        <h2 class="p23-cta-title">Ready to explore your next market?</h2>
                        <p class="p23-cta-desc">
                            Whether you’re testing your first African market, looking for a new route to market or expanding an existing operation, P23 can help you understand the opportunity and turn it into action.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ url('book-consultation') }}" class="p23-btn-brand">
                                Talk to P23 Africa <i class="fas fa-calendar-check ms-1"></i>
                            </a>
                            <a href="{{ url('contact') }}" class="p23-btn-outline-light">
                                Start a conversation <i class="fas fa-comments ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 text-center mt-4 mt-lg-0">
                        <div class="p-4 rounded-4" style="background: var(--p23-grad-silk-pill); border: 1px solid rgba(82, 204, 180, 0.35); box-shadow: var(--p23-shadow-silk-pill);">
                            <div class="mb-2" style="font-size: 2.2rem; color: var(--p23-mint-glow);">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <h4 class="text-white" style="font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif;">Direct Advisory</h4>
                            <p style="font-size: 0.92rem; color: rgba(244, 250, 248, 0.8);" class="mb-0">
                                Meet our cross-border advisory team to discuss timeline, country scope, and testing feasibility.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dossier Switcher Script -->
    <script>
        function switchCaseDossier(country) {
            const ngDossier = document.getElementById('dossier-nigeria');
            const ghDossier = document.getElementById('dossier-ghana');
            const ngBtn = document.getElementById('tab-ng-btn');
            const ghBtn = document.getElementById('tab-gh-btn');

            if (country === 'nigeria') {
                ngDossier.style.display = 'block';
                ghDossier.style.display = 'none';
                ngBtn.classList.add('active');
                ghBtn.classList.remove('active');
            } else {
                ngDossier.style.display = 'none';
                ghDossier.style.display = 'block';
                ghBtn.classList.add('active');
                ngBtn.classList.remove('active');
            }
        }
    </script>

@endsection
