@extends('layouts.master')
@section('title', 'Nigeria Market Entry Case Study: HVAC Manufacturer | P23 Africa')

@section('meta')
    <meta name="description"
        content="How P23 Africa helped an HVAC manufacturer test Nigerian market demand, vet trusted distributors, and deploy ground tracking software before committing capital.">
    <meta name="keywords"
        content="Nigeria market entry, HVAC manufacturer Nigeria, distributor vetting Nigeria, demand testing Lagos Abuja, African commercial intelligence">
    <meta property="og:title" content="Nigeria Case Study: From Market Uncertainty to a Route into Nigeria | P23 Africa">
    <meta property="og:description" content="Discover how outbound demand testing and 10-point distributor due diligence opened Nigeria for an industrial manufacturer.">
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
                <li><a href="{{ url('services') }}">Services</a></li>
                <li class="separator">/</li>
                <li><a href="{{ route('services.market-entry') }}">Market Entry &amp; Expansion</a></li>
                <li class="separator">/</li>
                <li class="current">Nigeria HVAC Case Study</li>
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
                        <span class="case-meta-tag"><i class="fas fa-industry me-1"></i> HVAC &amp; Industrial Manufacturing</span>
                        <span class="case-meta-tag"><i class="fas fa-shield-alt me-1"></i> Pre-Capital Demand Testing</span>
                    </div>

                    <h1 class="p23-section-title text-white mb-3" style="font-size: clamp(2.2rem, 3.8vw, 3.4rem);">
                        From market uncertainty to a route into Nigeria.
                    </h1>

                    <p class="hero-subtitle mb-4">
                        Entering a new market is a significant commitment. For an HVAC manufacturer looking to expand into Nigeria, the first question wasn’t simply <em>“Can we sell here?”</em> It was: <strong>“Is there genuine demand, who will buy from us, what will they pay, and who can we trust to take our products to market?”</strong>
                    </p>

                    <p style="color: rgba(255, 255, 255, 0.75); font-size: 1.05rem;">
                        P23 Africa helped answer those questions before significant capital was committed — then supported the client in turning the opportunity into a functioning route to market.
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
                    <div class="p23-stat-label">Misallocated Capex</div>
                </div>
                <div class="col-6 col-md-3 p23-stat-item">
                    <div class="p23-stat-number">100% <span class="accent">Real</span></div>
                    <div class="p23-stat-label">Direct Commercial Feedback</div>
                </div>
                <div class="col-6 col-md-3 p23-stat-item">
                    <div class="p23-stat-number">10 <span class="accent">Checks</span></div>
                    <div class="p23-stat-label">Distributor Due Diligence Points</div>
                </div>
                <div class="col-6 col-md-3 p23-stat-item">
                    <div class="p23-stat-number">24/7 <span class="accent">Ground</span></div>
                    <div class="p23-stat-label">Operational Tech Tracking</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Dossier Content Body -->
    <section class="p23-section p23-section-white">
        <div class="container">

            <!-- The Challenge Card -->
            <div class="p23-challenge-box">
                <span class="p23-eyebrow">The Context &amp; Dilemma</span>
                <h2 class="p23-challenge-title">Testing the Nigerian market before investing</h2>
                <p class="p23-section-desc mb-3">
                    The client, an HVAC manufacturer, was exploring expansion into Nigeria and wanted to understand the commercial opportunity before committing resources to establishing a presence.
                </p>
                <p class="p23-section-desc mb-4">
                    They needed more than a market report. They needed <strong>evidence from the market</strong>:
                </p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p23-check-item"><i class="fas fa-question-circle" style="color: var(--p23-mint-deep);"></i> Who was actually interested in the product?</div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-check-item"><i class="fas fa-question-circle" style="color: var(--p23-mint-deep);"></i> Which types of businesses were buying?</div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-check-item"><i class="fas fa-question-circle" style="color: var(--p23-mint-deep);"></i> What were customers currently paying?</div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-check-item"><i class="fas fa-question-circle" style="color: var(--p23-mint-deep);"></i> Where was demand concentrated?</div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-check-item"><i class="fas fa-question-circle" style="color: var(--p23-mint-deep);"></i> Who could distribute the products?</div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-check-item"><i class="fas fa-question-circle" style="color: var(--p23-mint-deep);"></i> Which distributors could be trusted to represent the brand &amp; manage stock?</div>
                    </div>
                </div>
                <p class="p23-section-desc mt-4 mb-0">
                    P23 was brought in to test the opportunity and develop a practical route into the Nigerian market.
                </p>
            </div>

            <!-- Execution Breakdown (01 to 04) -->
            <div class="mt-5">
                <span class="p23-eyebrow">The 4-Stage Execution Playbook</span>
                <h2 class="p23-section-title mb-5">From demand testing to market entry</h2>

                <!-- 01 We tested the market -->
                <div class="p23-step-card mb-4">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">01</div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">We tested the market</h3>
                            <p class="p23-step-lead">
                                We designed an outbound demand-testing programme focused on real potential buyers and distributors. Rather than relying solely on surveys or secondary research, we went directly to the market.
                            </p>
                            <p class="p23-section-desc mb-3">
                                We identified and engaged relevant businesses across the target market, including potential distributors, contractors and end users. The objective was to generate real commercial signal from real businesses.
                            </p>
                            <div class="p23-checklist-grid">
                                <div class="p23-check-item"><i class="fas fa-check"></i> Who was actively buying HVAC products</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> What products they were currently using</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> What price points they were paying</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Which businesses were interested in the offering</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> What objections or barriers existed</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Which customer segments showed strongest potential</div>
                                <div class="p23-check-item"><i class="fas fa-check"></i> Which businesses were actively seeking alternatives</div>
                            </div>
                            <p class="p23-section-desc mt-3 mb-0" style="font-weight: 700; color: var(--p23-teal-dark);">
                                The result was a much clearer picture of where the opportunity existed, and where it didn’t.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Turning conversations into market intelligence -->
                <div class="p23-step-card p23-step-card-featured mb-4">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">Turning conversations into market intelligence</h3>
                            <p class="p23-step-lead">
                                The value wasn’t simply in generating leads. Every conversation provided information. We categorised responses to distinguish genuine commercial interest from general enquiries and non-engagement.
                            </p>
                            <p class="p23-section-desc">
                                That allowed us to identify clear patterns across five vital commercial dimensions:
                            </p>

                            <!-- 5 Intelligence Cards -->
                            <div class="p23-intel-grid">
                                <div class="p23-intel-card">
                                    <span class="intel-badge">01. Demand</span>
                                    <h5>Customer Groups</h5>
                                    <p>Which customer segments were actively interested vs merely curious?</p>
                                </div>
                                <div class="p23-intel-card">
                                    <span class="intel-badge">02. Pricing</span>
                                    <h5>Price Benchmarks</h5>
                                    <p>What were businesses currently paying for comparable imported products?</p>
                                </div>
                                <div class="p23-intel-card">
                                    <span class="intel-badge">03. Fit</span>
                                    <h5>Product Comparison</h5>
                                    <p>How did the client's offering compare with what the market already trusted?</p>
                                </div>
                                <div class="p23-intel-card">
                                    <span class="intel-badge">04. Barriers</span>
                                    <h5>Objection Mapping</h5>
                                    <p>What logistical, currency, or technical concerns prevented deals?</p>
                                </div>
                                <div class="p23-intel-card">
                                    <span class="intel-badge">05. Timing</span>
                                    <h5>Purchase Horizon</h5>
                                    <p>Which prospects had immediate requirements vs long-term cycles?</p>
                                </div>
                            </div>

                            <p class="p23-section-desc mb-0" style="font-weight: 700; color: var(--p23-teal-dark);">
                                The result was a much more informed view of the Nigerian opportunity, based on conversations with the market rather than assumptions about it.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 02 We identified and vetted distributors -->
                <div class="p23-step-card mb-4">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">02</div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">We identified and vetted distributors</h3>
                            <p class="p23-step-lead">
                                Once the demand picture became clearer, the next question was how to get the product into the market. We identified potential distributors and assessed them against the requirements of the business.
                            </p>
                            <p class="p23-section-desc">
                                This went far beyond compiling a generic directory of trading companies. We examined 10 critical operational factors:
                            </p>

                            <div class="p23-vetting-checklist">
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> Business history &amp; CAC registration</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> Existing product portfolio</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> Complementary brands &amp; potential conflicts</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> Proven frontline sales capability</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> Geographic reach across Lagos &amp; regional hubs</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> Warehousing &amp; secure storage capability</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> Key market relationships &amp; contractor trust</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> Commercial credit capacity &amp; balance sheet</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> Existing international supplier relationships</div>
                                <div class="p23-vetting-item"><i class="fas fa-check-circle"></i> Overall cultural &amp; strategic suitability</div>
                            </div>

                            <p class="p23-section-desc mb-0">
                                Where appropriate, we also carried out direct reference checks with existing supplier relationships. The objective was to eliminate the risk of choosing a partner simply because they looked impressive on paper.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 03 We facilitated the market entry -->
                <div class="p23-step-card mb-4">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">03</div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">We facilitated the market entry</h3>
                            <p class="p23-step-lead">
                                With the market opportunity validated and a suitable distributor identified, P23 facilitated the relationship between the manufacturer and the Nigerian partner.
                            </p>
                            <div class="p23-transition-callout">
                                <span class="p23-transition-callout-tag">Project Transition:</span>
                                <span class="p23-transition-callout-headline">
                                    From <span style="color: var(--p23-mint-deep);">“There may be a market”</span> ➔ to <span style="color: var(--p23-teal-dark);">“We have a functioning route into the market.”</span>
                                </span>
                            </div>
                            <p class="p23-section-desc mb-0">
                                P23 supported the client through the process of developing the distributor relationship, structuring agreements, and establishing the operational foundations for commercial activity in Nigeria.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 04 We helped maintain visibility on the ground -->
                <div class="p23-step-card mb-4">
                    <div class="p23-step-card-header">
                        <div class="p23-step-number-badge">04</div>
                        <div class="flex-grow-1">
                            <h3 class="p23-step-title">We helped maintain visibility on the ground</h3>
                            <p class="p23-step-lead">
                                Entering the market is only the beginning. Once products are in-market, the challenge becomes maintaining visibility over what is happening on the ground:
                            </p>
                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <div class="p23-check-item"><i class="fas fa-search-location"></i> Where are the products?</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p23-check-item"><i class="fas fa-user-clock"></i> What are agents doing?</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p23-check-item"><i class="fas fa-funnel-dollar"></i> Which leads are advancing?</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p23-check-item"><i class="fas fa-tasks"></i> What activity is taking place?</div>
                                </div>
                                <div class="col-md-8">
                                    <div class="p23-check-item"><i class="fas fa-exclamation-triangle text-danger"></i> Where might stock or sales opportunities be at risk?</div>
                                </div>
                            </div>
                            <p class="p23-section-desc">
                                To support this, we implemented <strong>P23’s tracking software</strong> to provide greater operational oversight of activity in the market.
                            </p>
                            <p class="p23-section-desc mb-0">
                                The system helps the client monitor activity through its agents on the ground, giving the business greater visibility while reducing some of the risks associated with managing a market remotely. It also creates a clearer view of opportunities, enabling the team to follow up on activity and maximise potential sales.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- The Result Section -->
            <div class="my-5 p23-case-outcome-showcase">
                <span class="p23-eyebrow">The Outcome</span>
                <h2 class="text-white mb-3" style="font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif; font-size: clamp(1.8rem, 2.8vw, 2.5rem);">
                    A market opportunity became a functioning route to market.
                </h2>
                <p style="color: rgba(244, 250, 248, 0.88); font-size: 1.1rem; line-height: 1.72;" class="mb-4">
                    Through a combination of demand testing, market intelligence, distributor vetting, relationship development and operational oversight, P23 helped the client move through the key stages of Nigerian market entry:
                </p>
                <div class="row g-3 text-white">
                    <div class="col-md-4">
                        <div class="p23-outcome-pill">
                            <i class="fas fa-check me-2" style="color: var(--p23-mint-bright);"></i> Market demand tested directly with buyers
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p23-outcome-pill">
                            <i class="fas fa-check me-2" style="color: var(--p23-mint-bright);"></i> Customer &amp; pricing intelligence gathered
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p23-outcome-pill">
                            <i class="fas fa-check me-2" style="color: var(--p23-mint-bright);"></i> Potential distributors vetted &amp; assessed
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-outcome-pill">
                            <i class="fas fa-check me-2" style="color: var(--p23-mint-bright);"></i> High-trust distributor relationship facilitated
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p23-outcome-pill">
                            <i class="fas fa-check me-2" style="color: var(--p23-mint-bright);"></i> Operational tracking software deployed on the ground
                        </div>
                    </div>
                </div>
                <p class="mt-4 mb-0" style="color: rgba(244, 250, 248, 0.92); font-size: 1.05rem;">
                    And the client was able to enter the Nigerian market with significantly more information and visibility than they would have had from desk research alone.
                </p>
            </div>

            <!-- What this means for businesses entering Nigeria (The Framework) -->
            <div class="my-5">
                <span class="p23-eyebrow">Strategic Principles</span>
                <h2 class="p23-section-title">What this means for businesses entering Nigeria</h2>
                <p class="p23-section-desc">
                    Market entry doesn’t have to begin with a major investment. There is a more practical way to approach the decision:
                </p>

                <div class="p23-takeaway-framework">
                    <div class="p23-takeaway-card">
                        <div class="p23-takeaway-step">01. Test Demand</div>
                        <p>Find out what the market is actually saying before shipping containers or signing long leases.</p>
                    </div>

                    <div class="p23-takeaway-card">
                        <div class="p23-takeaway-step">02. Validate Value</div>
                        <p>Understand who is interested, what they currently buy and what they are prepared to pay.</p>
                    </div>

                    <div class="p23-takeaway-card">
                        <div class="p23-takeaway-step">03. Vet Partners</div>
                        <p>Identify and rigorously assess the businesses that could help you reach the market reliably.</p>
                    </div>

                    <div class="p23-takeaway-card">
                        <div class="p23-takeaway-step">04. Maintain Eyes</div>
                        <p>Use the right systems and local support to understand what is happening once you’re in-market.</p>
                    </div>
                </div>

                <div class="p23-framework-summary-card my-4">
                    <h4>
                        This is how P23 approaches market entry: Understand. Validate. Connect. Execute.
                    </h4>
                    <p class="mb-0">
                        We don’t believe market entry should be based on assumptions. We help businesses gather market intelligence, test commercial demand, build the right relationships and put the infrastructure in place to support execution.
                    </p>
                </div>
            </div>

            <!-- Cross-Link Sibling Case Strip -->
            <div class="p23-sibling-case-strip">
                <div class="info-text">
                    <span class="text-uppercase" style="font-size: 0.78rem; letter-spacing: 0.1em; color: var(--p23-mint-glow); font-weight: 700;">Next Case Study Dossier</span>
                    <strong>Ghana: Construction Equipment Platform</strong>
                    <span>Scaling from multi-country testing to 5,000 contractors and 2,150 renters in six months.</span>
                </div>
                <a href="{{ route('services.market-entry.ghana') }}" class="p23-dossier-link">
                    Explore Ghana Case Study <span>→</span>
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
                        <span class="p23-eyebrow mb-2 d-inline-block">Nigeria Market Access</span>
                        <h2 class="p23-cta-title">Considering Nigeria?</h2>
                        <p class="p23-cta-desc">
                            If you’re exploring the Nigerian market and want to understand the opportunity before committing significant resources, let’s talk.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ url('book-consultation') }}" class="p23-btn-brand">
                                Start a conversation with P23 Africa <i class="fas fa-calendar-check ms-1"></i>
                            </a>
                            <a href="{{ route('services.market-entry') }}" class="p23-btn-outline-light">
                                Explore All Capabilities <i class="fas fa-arrow-left ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 text-center mt-4 mt-lg-0">
                        <div class="p-4 rounded-4" style="background: var(--p23-grad-silk-pill); border: 1px solid rgba(82, 204, 180, 0.35); box-shadow: var(--p23-shadow-silk-pill);">
                            <div class="mb-2" style="font-size: 2.2rem; color: var(--p23-mint-glow);">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4 class="text-white" style="font-family: 'GT Walsheim ConB', 'Montserrat', sans-serif;">Lagos &amp; Abuja Footprint</h4>
                            <p style="font-size: 0.92rem; color: rgba(244, 250, 248, 0.8);" class="mb-0">
                                Pre-capital demand assessment, distributor reference checks, and local tracking deployment.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
