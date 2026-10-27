@extends('layouts.master')
@section('title', 'African Expansion for Manufacturers | Free Executive Field Guide | P23 Africa')
@section('body_class', 'p23-black-navbar-page')

@section('meta')
    <meta name="description"
        content="Download the free executive field guide on African market expansion for industrial manufacturers. Learn practical distributor due diligence, demand validation, and commercial sales operations.">
    <meta name="keywords"
        content="African market expansion, manufacturer expansion Africa, industrial equipment Africa, distributor due diligence Africa, demand testing Africa, commercial operations Africa, P23 Africa playbook">
    <meta name="author" content="P23 Africa">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="African Expansion for Manufacturers | Executive Field Guide">
    <meta property="og:description"
        content="A practical, 18-page field guide on market entry, distributor auditing, and commercial operations across 20+ African markets.">
    <meta property="og:image" content="{{ asset('images/homepage-thumbnail-og.png') }}">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="African Expansion for Manufacturers | P23 Africa">
    <meta name="twitter:description"
        content="A practical, 18-page field guide on market entry, distributor auditing, and commercial operations across 20+ African markets.">
    <meta name="twitter:image" content="{{ asset('images/homepage-thumbnail-og.png') }}">

    <link rel="stylesheet" href="{{ asset('css/services-new.css') }}">

    <style>
        /* Scoped Enhancements for Lead Magnet Landing Page */
        .lead-takeaways {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin: 0 0 clamp(24px, 3vw, 36px) 0;
            max-width: 550px;
        }

        .lead-takeaway-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-family: var(--font-sans);
            font-size: 13.5px;
            line-height: 1.6;
            color: #222222;
        }

        .lead-takeaway-item i {
            color: #1A283F;
            font-size: 16px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .lead-takeaway-item strong {
            color: #000000;
            font-weight: 600;
        }

        .lead-cta-meta {
            font-family: var(--font-sans);
            font-size: 11.5px;
            color: rgba(5, 0, 56, 0.65);
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
        }

        .lead-cta-meta i {
            color: #28a745;
            font-size: 13px;
        }

        .lead-card-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: #FFFFFF !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-family: var(--font-sans);
            font-weight: 600;
            font-size: 14.5px;
            padding: 13px 26px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .lead-card-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.6);
            color: #FFFFFF !important;
            transform: translateY(-2px);
        }

        .lead-final-action-box {
            margin-top: clamp(36px, 4vw, 56px);
            padding: clamp(28px, 3.5vw, 40px);
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
        }

        .lead-final-action-text h4 {
            font-family: var(--font-sans);
            font-size: clamp(20px, 2.2vw, 26px);
            font-weight: 700;
            color: #FFFFFF;
            margin: 0 0 8px 0;
            line-height: 1.25;
        }

        .lead-final-action-text p {
            font-family: var(--font-sans);
            font-size: 14px;
            color: #d0d8e4;
            margin: 0;
            line-height: 1.5;
        }

        .lead-final-action-buttons {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .lead-final-download-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #FFFFFF;
            color: #1A283F !important;
            font-family: var(--font-sans);
            font-weight: 700;
            font-size: 15px;
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.25);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .lead-final-download-btn:hover {
            background: #E3E1CF;
            color: #181818 !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
        }

        @media (max-width: 991px) {
            .lead-final-action-box {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }
            .lead-final-action-buttons {
                justify-content: center;
            }
            .lead-final-download-btn,
            .lead-card-btn-secondary {
                width: 100%;
            }
        }
    </style>
@endsection

@section('navbar')
    @include('layouts.includes.navbar-v2', ['theme' => 'dark', 'active' => 'what-we-do'])
@endsection

@section('content')
<div class="services-page-wrapper">

    {{-- ========================================================
         SECTION 1: HIGH-IMPACT HERO & VALUE PROPOSITION
         ======================================================== --}}
    <section class="services-hero-section" id="hero">
        <div class="services-hero-container">
            <div class="services-hero-grid">
                
                {{-- Left Column: Copy, Takeaways & Primary Action --}}
                <div class="services-hero-content">
                    <p class="hero-eyebrow">EXECUTIVE FIELD GUIDE • 2026 EDITION</p>
                    
                    <h1 class="hero-headline">
                        African Expansion for Manufacturers
                    </h1>
                    
                    <p class="hero-description">
                        A practical, 18-page field guide for international industrial manufacturers, equipment providers, and commercial leaders. Learn how to test real buyer demand, audit local distribution partners, avoid costly exclusivity lockups, and structure high-performing commercial sales operations across 20+ African markets.
                    </p>

                    {{-- Key Value Takeaways --}}
                    <div class="lead-takeaways" aria-label="Key Guide Takeaways">
                        <div class="lead-takeaway-item">
                            <i class="bi bi-shield-check"></i>
                            <div>
                                <strong>Distributor Due Diligence:</strong> 7-point on-ground audit framework to vet warehouse capacity, solvency, and real end-buyer relationships.
                            </div>
                        </div>
                        <div class="lead-takeaway-item">
                            <i class="bi bi-graph-up-arrow"></i>
                            <div>
                                <strong>Real Buyer Demand Validation:</strong> Evaluate commercial willingness-to-pay and procurement timelines before committing physical capital.
                            </div>
                        </div>
                        <div class="lead-takeaway-item">
                            <i class="bi bi-diagram-3"></i>
                            <div>
                                <strong>Route-to-Market Economics:</strong> Margin structuring, channel accountability, and currency safeguards for sustainable growth.
                            </div>
                        </div>
                    </div>
                    
                    {{-- CTA Group --}}
                    <div class="hero-cta-group">
                        <a href="{{ asset('documents/African.pdf') }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="hero-cta-btn" 
                           id="hero-download-btn"
                           title="African Expansion for Manufacturers">
                            <i class="bi bi-cloud-arrow-down-fill me-2"></i> Download Free Guide (PDF)
                        </a>
                        
                        <div class="hero-casestudy-proof" aria-label="5 Star Industry Rating">
                            <div class="hero-stars" aria-hidden="true">
                                <svg width="98" height="18" viewBox="0 0 98 18" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="5 stars rating">
                                    <path d="M9 0L11.0206 6.21885H17.5595L12.2694 10.0623L14.2901 16.2812L9 12.4377L3.70993 16.2812L5.73056 10.0623L0.440492 6.21885H6.97937L9 0Z" fill="#390908"/>
                                    <path d="M29 0L31.0206 6.21885H37.5595L32.2694 10.0623L34.2901 16.2812L29 12.4377L23.7099 16.2812L25.7306 10.0623L20.4405 6.21885H26.9794L29 0Z" fill="#390908"/>
                                    <path d="M49 0L51.0206 6.21885H57.5595L52.2694 10.0623L54.2901 16.2812L49 12.4377L43.7099 16.2812L45.7306 10.0623L40.4405 6.21885H46.9794L49 0Z" fill="#390908"/>
                                    <path d="M69 0L71.0206 6.21885H77.5595L72.2694 10.0623L74.2901 16.2812L69 12.4377L63.7099 16.2812L65.7306 10.0623L60.4405 6.21885H66.9794L69 0Z" fill="#390908"/>
                                    <path d="M89 0L91.0206 6.21885H97.5595L92.2694 10.0623L94.2901 16.2812L89 12.4377L83.7099 16.2812L85.7306 10.0623L80.4405 6.21885H86.9794L89 0Z" fill="#390908"/>
                                </svg>
                            </div>
                            <span class="hero-casestudy-link">
                                <strong>18-Page Practical Field Guide</strong> <span>• 100% Free Executive Download</span>
                            </span>
                        </div>
                    </div>

                    <div class="lead-cta-meta">
                        <i class="bi bi-check-circle-fill"></i> Direct instant PDF access • No credit card or gating required
                    </div>
                </div>

                {{-- Right Column: 2x2 Showcase Mosaic --}}
                <div class="services-hero-showcase">
                    <div class="hero-mosaic-grid">
                        
                        {{-- Tile 1 (Top Left): Blue Corporate Architecture --}}
                        <div class="mosaic-tile mosaic-tile-building">
                            <img src="{{ asset('images/services/hero-building.png') }}" 
                                 alt="Modern industrial operations across Africa" 
                                 loading="eager">
                        </div>

                        {{-- Tile 2 (Top Right): 20+ Countries Maroon Arch Card --}}
                        <div class="mosaic-tile mosaic-tile-countries">
                            <img src="{{ asset('images/services/hero-countries-card.png') }}" 
                                 alt="Market intelligence across 20+ African countries" 
                                 loading="eager">
                        </div>

                        {{-- Tile 3 (Bottom Left): Strategic Advisory Team with Arched Dome --}}
                        <div class="mosaic-tile mosaic-tile-team">
                            <img src="{{ asset('images/services/hero-team.png') }}" 
                                 alt="P23 Africa commercial field team and distributor auditors" 
                                 loading="lazy">
                        </div>

                        {{-- Tile 4 (Bottom Right): 18 Pages Metric --}}
                        <div class="mosaic-tile mosaic-tile-stat">
                            <div class="stat-number">18</div>
                            <div class="stat-label">Pages of Actionable Field Guidance</div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ========================================================
         SECTION 2: WHAT'S INSIDE THE PLAYBOOK & CONVERSION CLOSING
         ======================================================== --}}
    <section class="services-plan-section" id="playbook-pillars" aria-label="Inside the African Expansion Field Guide">
        <div class="plan-section-card">
            
            <h2 class="plan-section-title">
                Growth is a goal.<br>
                Entering Africa takes evidence.
            </h2>

            <div class="plan-cards-grid">
                
                {{-- Column 1: Distributor Due Diligence (Tall Card with 3D Illustration) --}}
                <div class="plan-col-tall">
                    <div class="plan-card plan-card-market-entry">
                        <div class="plan-card-body">
                            <h3 class="plan-card-title">Distributor Due Diligence & Audits</h3>
                            <p class="plan-card-desc">
                                Most international manufacturers fail in Africa not because of product defects, but because they sign exclusive agreements with unvetted agents who lack genuine commercial coverage. Discover our 7-step on-ground distributor audit to verify true warehouse capacity, solvency, and access to enterprise buyers.
                            </p>
                            <a href="{{ asset('documents/African.pdf') }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="plan-explore-btn" 
                               aria-label="Read distributor audit framework in PDF">
                                Read in Guide <span class="btn-arrow">→</span>
                            </a>
                        </div>
                        <div class="plan-card-illustration">
                            <img src="{{ asset('images/services/lightbulb-road.png') }}" 
                                 alt="Roadmap to successful African distribution partnerships" 
                                 loading="lazy">
                        </div>
                    </div>
                </div>

                {{-- Column 2: Outbound Demand (Top, 332px) & Sales Operations (Bottom, 449px) --}}
                <div class="plan-col-stacked">
                    {{-- Outbound Demand Card --}}
                    <div class="plan-card plan-card-distributor">
                        <h3 class="plan-card-title">Demand Validation Before Capex</h3>
                        <p class="plan-card-desc">
                            Test real buyer willingness-to-pay and procurement timelines with prospective institutional buyers in Nigeria, Kenya, and Ghana before committing inventory or setting up local entities.
                        </p>
                        <a href="{{ asset('documents/African.pdf') }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="plan-explore-btn" 
                           aria-label="Read demand validation section in PDF">
                            Explore Methodology <span class="btn-arrow">→</span>
                        </a>
                    </div>

                    {{-- Operations Card --}}
                    <div class="plan-card plan-card-operations">
                        <h3 class="plan-card-title">Commercial Operations & Governance</h3>
                        <p class="plan-card-desc">
                            Learn how to establish secondary sales visibility, structured milestone agreements, and transparent CRM reporting that keeps local distributor teams commercially aligned and accountable.
                        </p>
                        <a href="{{ asset('documents/African.pdf') }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="plan-explore-btn" 
                           aria-label="Read sales governance section in PDF">
                            Explore Governance <span class="btn-arrow">→</span>
                        </a>
                    </div>
                </div>

                {{-- Column 3: Route-to-Market (Top, 449px) & De-Risking Expansion (Bottom, 332px) --}}
                <div class="plan-col-stacked">
                    {{-- Route-to-Market Card --}}
                    <div class="plan-card plan-card-investment">
                        <h3 class="plan-card-title">Route-to-Market Economics</h3>
                        <p class="plan-card-desc">
                            Evaluate margin structures, import tariff classifications, and currency hedging strategies tailored specifically for industrial equipment, machinery, and commercial technologies.
                        </p>
                        <a href="{{ asset('documents/African.pdf') }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="plan-explore-btn" 
                           aria-label="Read commercial economics section in PDF">
                            Explore Economics <span class="btn-arrow">→</span>
                        </a>
                    </div>

                    {{-- De-Risking Expansion Card --}}
                    <div class="plan-card plan-card-outbound">
                        <h3 class="plan-card-title">Evidence-Based Expansion</h3>
                        <p class="plan-card-desc">
                            Proven decision gates designed for executive boards, investment committees, and export directors to expand with measurable risk control and verifiable market demand.
                        </p>
                        <a href="{{ asset('documents/African.pdf') }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="plan-explore-btn" 
                           aria-label="Read risk control section in PDF">
                            Read Frameworks <span class="btn-arrow">→</span>
                        </a>
                    </div>
                </div>

            </div>

            {{-- Center Aligned Divider Line --}}
            <div class="plan-divider" role="separator" aria-hidden="true"></div>

            {{-- Bottom Metrics Grid (3 Stats) --}}
            <div class="plan-metrics-grid">
                
                {{-- Metric 1: 18 Pages --}}
                <div class="plan-metric-item">
                    <div class="plan-metric-number">18</div>
                    <div class="plan-metric-label">Pages of Actionable Guidance</div>
                </div>

                {{-- Metric 2: 20+ African Markets --}}
                <div class="plan-metric-item">
                    <div class="plan-metric-number">20+</div>
                    <div class="plan-metric-label">African Markets Covered</div>
                </div>

                {{-- Metric 3: 100% Free --}}
                <div class="plan-metric-item">
                    <div class="plan-metric-number">100% Free</div>
                    <div class="plan-metric-label">Direct Executive Access</div>
                </div>

            </div>

            {{-- Final High-Converting Lead Trigger Box --}}
            <div class="lead-final-action-box">
                <div class="lead-final-action-text">
                    <h4>Get the Complete African Expansion Field Guide</h4>
                    <p>Read the 18-page practical playbook on market entry, distributor auditing, and commercial operations.</p>
                </div>
                <div class="lead-final-action-buttons">
                    <a href="{{ asset('documents/African.pdf') }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="lead-final-download-btn">
                        <i class="bi bi-cloud-arrow-down-fill me-2"></i> Open PDF in New Tab
                    </a>
                    <a href="{{ locale_route('contact') }}" class="lead-card-btn-secondary">
                        Speak with our Advisors <span class="btn-arrow">→</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

</div>
@endsection

@section('footer')
    @include('layouts.includes.footer3')
@endsection
