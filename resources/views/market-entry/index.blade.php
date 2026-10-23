@extends('layouts.master')
@section('title', 'Market Entry & Expansion across Nigeria, Ghana, Kenya & South Africa | P23 Africa')
@section('body_class', 'p23-black-navbar-page')

@section('meta')
    <meta name="description"
        content="Enter Africa with confidence. Build with local knowledge. P23 Africa helps businesses test demand, vet distributors, and execute commercial expansion across Nigeria, Ghana, Kenya, and South Africa.">
    <meta name="keywords"
        content="Market entry Africa, expand business to Nigeria, expand to Ghana, African business expansion, distributor due diligence Africa, demand validation Africa">
    <meta property="og:title" content="Market Entry & Expansion | P23 Africa">
    <meta property="og:description" content="Move from market opportunity to practical execution across Nigeria, Ghana, Kenya, and South Africa.">
    <meta property="og:image" content="{{ asset('images/market-entry/hero-office-woman.png') }}">
    <link rel="stylesheet" href="{{ asset('css/market-entry-new.css') }}">
    <link rel="stylesheet" href="{{ asset('css/services-new.css') }}">
@endsection

@section('navbar')
    @include('layouts.includes.navbar-v2', ['theme' => 'dark', 'active' => 'what-we-do'])
@endsection

@section('content')
<div class="market-entry-page">

    {{-- ==========================================
         SECTION 1: HERO SECTION
         ========================================== --}}
    <section class="me-hero-section" id="hero">
        <div class="me-hero-container">
            <div class="me-hero-grid">
                
                {{-- Left Column: Copy & Actions --}}
                <div class="me-hero-content">
                    <h1 class="me-hero-headline">
                        Enter Africa with confidence. Build with local knowledge.
                    </h1>

                    <p class="me-hero-description">
                        Expanding into Africa presents significant opportunities, but successful market entry requires more than a market report. You need to understand the market, test the opportunity, find the right people, build the right relationships and execute locally.
                    </p>

                    <div class="me-hero-cta-group">
                        <a href="{{ url('contact') }}" class="me-hero-btn" id="me-hero-talk-to-us-btn">
                            Talk to Us
                        </a>

                        <div class="me-hero-proof">
                            <div class="me-hero-stars" aria-label="5 star rating">
                                <svg width="98" height="18" viewBox="0 0 98 18" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="5 stars rating">
                                    <path d="M9 0L11.0206 6.21885H17.5595L12.2694 10.0623L14.2901 16.2812L9 12.4377L3.70993 16.2812L5.73056 10.0623L0.440492 6.21885H6.97937L9 0Z" fill="#390908"/>
                                    <path d="M29 0L31.0206 6.21885H37.5595L32.2694 10.0623L34.2901 16.2812L29 12.4377L23.7099 16.2812L25.7306 10.0623L20.4405 6.21885H26.9794L29 0Z" fill="#390908"/>
                                    <path d="M49 0L51.0206 6.21885H57.5595L52.2694 10.0623L54.2901 16.2812L49 12.4377L43.7099 16.2812L45.7306 10.0623L40.4405 6.21885H46.9794L49 0Z" fill="#390908"/>
                                    <path d="M69 0L71.0206 6.21885H77.5595L72.2694 10.0623L74.2901 16.2812L69 12.4377L63.7099 16.2812L65.7306 10.0623L60.4405 6.21885H66.9794L69 0Z" fill="#390908"/>
                                    <path d="M89 0L91.0206 6.21885H97.5595L92.2694 10.0623L94.2901 16.2812L89 12.4377L83.7099 16.2812L85.7306 10.0623L80.4405 6.21885H86.9794L89 0Z" fill="#390908"/>
                                </svg>
                            </div>
                            <a href="{{ url('case-studies') }}" class="me-hero-case-studies-link">
                                <strong>Click Here to</strong> <em>Read Case Studies</em>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Two Stacked Visuals (470px and 643px) --}}
                <div class="me-hero-visuals">
                    <div class="me-visual-top">
                        <img src="{{ asset('images/market-entry/hero-office-woman.png') }}" 
                             alt="Professional consultant leading enterprise growth in Africa" 
                             loading="eager">
                    </div>

                    <div class="me-visual-bottom">
                        <img src="{{ asset('images/market-entry/hero-handshake.png') }}" 
                             alt="Trusted business partnership handshake in African market" 
                             loading="eager">
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ==========================================
         SECTION 2: OUR EXECUTION METHODOLOGY
         ========================================== --}}
    <section class="me-methodology-section" id="execution-methodology">
        <div class="me-methodology-container">
            <div class="me-methodology-grid">
                
                {{-- Left Title Column --}}
                <div class="me-methodology-title-block">
                    <h2 class="me-methodology-title">
                        Our<br>Execution<br>Methodology
                    </h2>
                </div>

                {{-- Right 5-Step Strip --}}
                <div class="me-methodology-steps">

                    {{-- Step 01 --}}
                    <div class="me-methodology-step">
                        <div class="me-step-header">
                            <img src="{{ asset('images/market-entry/step-01-lightbulb.png') }}" 
                                 alt="Understand the opportunity" 
                                 class="me-step-icon">
                            <span class="me-step-number">01</span>
                        </div>
                        <h3 class="me-step-title">Understand the opportunity</h3>
                        <p class="me-step-desc">
                            Before entering a market, you need to know where the opportunity actually exists.
                        </p>
                    </div>

                    {{-- Step 02 --}}
                    <div class="me-methodology-step">
                        <div class="me-step-header">
                            <img src="{{ asset('images/market-entry/step-02-validate.png') }}" 
                                 alt="Validate demand" 
                                 class="me-step-icon">
                            <span class="me-step-number">02</span>
                        </div>
                        <h3 class="me-step-title">Validate demand</h3>
                        <p class="me-step-desc">
                            Market research can tell you what a market looks like. Direct engagement tells you how the market responds.
                        </p>
                    </div>

                    {{-- Step 03 --}}
                    <div class="me-methodology-step">
                        <div class="me-step-header">
                            <img src="{{ asset('images/market-entry/step-03-people.png') }}" 
                                 alt="Find the right people" 
                                 class="me-step-icon">
                            <span class="me-step-number">03</span>
                        </div>
                        <h3 class="me-step-title">Find the right people</h3>
                        <p class="me-step-desc">
                            Successful market entry is often built around relationships and commercial introductions.
                        </p>
                    </div>

                    {{-- Step 04 --}}
                    <div class="me-methodology-step">
                        <div class="me-step-header">
                            <img src="{{ asset('images/market-entry/step-04-route.png') }}" 
                                 alt="Build the route to market" 
                                 class="me-step-icon">
                            <span class="me-step-number">04</span>
                        </div>
                        <h3 class="me-step-title">Build the route to market</h3>
                        <p class="me-step-desc">
                            There is no single way to enter an African market. We determine the approach that fits.
                        </p>
                    </div>

                    {{-- Step 05 --}}
                    <div class="me-methodology-step">
                        <div class="me-step-header">
                            <img src="{{ asset('images/market-entry/step-05-lock.png') }}" 
                                 alt="Execute locally" 
                                 class="me-step-icon">
                            <span class="me-step-number">05</span>
                        </div>
                        <h3 class="me-step-title">Execute locally</h3>
                        <p class="me-step-desc">
                            Our role doesn't end once the strategy is delivered. We support the practical execution.
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </section>

    {{-- ==========================================
         SECTION 3: PRACTICAL EXECUTION
         ========================================== --}}
    <section class="me-execution-section" id="practical-execution">
        <div class="me-execution-grid">
            
            {{-- Left: Visual with 100% Ground Operational Visibility Badge --}}
            <div class="me-execution-visual">
                <img src="{{ asset('images/market-entry/practical-execution-handshake.png') }}" 
                     alt="P23 Africa frontline execution and commercial partnerships" 
                     loading="lazy">
            </div>

            {{-- Right: Content & Link --}}
            <div class="me-execution-content">
                <h2 class="me-execution-headline">
                    P23 Africa helps businesses move from market opportunity to practical execution.
                </h2>

                <p class="me-execution-desc">
                    We combine market intelligence, commercial expertise, local networks and technology to help businesses enter and grow sustainably across our target regional corridors. Rather than hand over static theoretical binders, we sit with you on the frontline: qualifying commercial demand, screening reliable partners, and building transparent operational channels that safeguard your capital.
                </p>

                <a href="{{ url('contact') }}" class="me-execution-link" id="me-execution-cta-link">
                    Book a confidential market exploration session→
                </a>
            </div>

        </div>
    </section>

    {{-- ==========================================
         SECTION 4: MARKET ENTRY MOMENTUM IN AFRICA
         ========================================== --}}
    <section class="me-momentum-section" id="market-momentum">
        <div class="me-momentum-container">
            
            {{-- Top Header Row: Headline, Button, and Comparative Narrative --}}
            <div class="me-momentum-header">
                
                <div class="me-momentum-title-group">
                    <h2 class="me-momentum-title">
                        Market Entry Momentum In Africa
                    </h2>
                    <a href="{{ url('contact') }}" class="me-momentum-btn" id="me-momentum-talk-btn">
                        Talk to Us
                    </a>
                </div>

                <p class="me-momentum-desc">
                    Nigeria is different from Ghana. Ghana is different from Kenya. Kenya is different from South Africa. The opportunity, customer psychology, competitive environment and route to market vary dramatically across borders.
                </p>

            </div>

            {{-- Bottom Row: 4 Momentum Stat Cards (Nigeria, Ghana, Kenya, South Africa) --}}
            <div class="me-momentum-cards-grid">
                
                {{-- Card 1: Nigeria +9% --}}
                <div class="me-momentum-card" id="card-nigeria">
                    <img src="{{ asset('images/market-entry/momentum-nigeria.png') }}" 
                         alt="Market entry growth momentum Nigeria +9%" 
                         loading="lazy">
                </div>

                {{-- Card 2: Ghana +14% --}}
                <div class="me-momentum-card" id="card-ghana">
                    <img src="{{ asset('images/market-entry/momentum-ghana.png') }}" 
                         alt="Market entry growth momentum Ghana +14%" 
                         loading="lazy">
                </div>

                {{-- Card 3: Kenya +17% (Dark Accent Card) --}}
                <div class="me-momentum-card" id="card-kenya">
                    <img src="{{ asset('images/market-entry/momentum-kenya.png') }}" 
                         alt="Market entry growth momentum Kenya +17%" 
                         loading="lazy">
                </div>

                {{-- Card 4: South Africa +5% --}}
                <div class="me-momentum-card" id="card-south-africa">
                    <img src="{{ asset('images/market-entry/momentum-south-africa.png') }}" 
                         alt="Market entry growth momentum South Africa +5%" 
                         loading="lazy">
                </div>

            </div>

        </div>
    </section>

    {{-- ==========================================
         SECTION 5: FROM MARKET OPPORTUNITY TO MARKET PRESENCE (CASE STUDIES & TABS)
         ========================================== --}}
    <section class="me-casestudy-section" id="case-studies">
        
        {{-- Section Header --}}
        <div class="me-casestudy-header">
            <h2 class="me-casestudy-title">
                From market opportunity to market presence
            </h2>
            <p class="me-casestudy-subtitle">
                Examine how multinational manufacturers and scale-ups successfully entered African markets without risking unvetted capital.
            </p>
        </div>

        {{-- Animated Tabs Bar --}}
        <div class="me-tabs-wrapper">
            <div class="me-tabs-container" role="tablist" aria-label="Case Study Industry Tabs">
                <button type="button" 
                        class="me-tab-btn active" 
                        role="tab" 
                        aria-selected="true" 
                        aria-controls="pane-bld" 
                        data-target="#pane-bld"
                        id="tab-bld">
                    HVAC Manufacturer
                </button>
                <button type="button" 
                        class="me-tab-btn" 
                        role="tab" 
                        aria-selected="false" 
                        aria-controls="pane-fmcg" 
                        data-target="#pane-fmcg"
                        id="tab-fmcg">
                    Industrial Machinery
                </button>
                <button type="button" 
                        class="me-tab-btn" 
                        role="tab" 
                        aria-selected="false" 
                        aria-controls="pane-fintech" 
                        data-target="#pane-fintech"
                        id="tab-fintech">
                    FINTECH Scale-up
                </button>
            </div>
        </div>

        {{-- Proof Stars & Read Case Studies Link --}}
        <div class="me-casestudy-proof">
            <div class="me-casestudy-proof-stars">
                <img src="{{ asset('images/market-entry/stars-black.png') }}" 
                     alt="5 stars rating" 
                     width="90" 
                     height="18" 
                     loading="lazy">
            </div>
            <a href="{{ url('case-studies') }}" class="me-casestudy-proof-link">
                <strong>Click Here to</strong> <em>Read Case Studies</em>
            </a>
        </div>

        {{-- Case Study Showcase Panes --}}
        <div class="me-case-panes-wrapper">
            
            {{-- Pane 1: BLD Manufacturer (Default Active) --}}
            <div class="me-case-pane active" id="pane-bld" role="tabpanel" aria-labelledby="tab-bld">
                <div class="me-case-visual">
                    <img src="{{ asset('images/case-studies/thermoflow.png') }}" 
                         alt="Commercial HVAC units and industrial installations in Nigeria" 
                         loading="lazy">
                </div>

                <div class="me-case-article">
                    <h3 class="me-case-heading">
                        Validating HVAC distributor claims in Nigeria before committing capital
                    </h3>

                    <div class="me-case-body">
                        <p>
                            The client had been approached by three Nigerian distributors claiming established HVAC networks, but had no way to independently verify whether these partners possessed active field sales teams, real customer relationships, or just procurement contacts.
                        </p>
                        <p>
                            Through structured due diligence across 312 target commercial accounts, 2 of 3 claimed distributors were eliminated, protecting millions in unvetted capital and establishing a verified commercial distribution channel with 47 qualified buyer opportunities.
                        </p>
                    </div>

                    <a href="{{ route('case-studies.show', 'thermoflow-hvac') }}" class="me-case-continue-btn" id="btn-continue-reading-bld">
                        Continue Reading
                    </a>
                </div>
            </div>

            {{-- Pane 2: FMCG Manufacturer --}}
            <div class="me-case-pane" id="pane-fmcg" role="tabpanel" aria-labelledby="tab-fmcg">
                <div class="me-case-visual">
                    <img src="{{ asset('images/case-studies/packline.png') }}" 
                         alt="Automated packaging production line in an FMCG manufacturing plant" 
                         loading="lazy">
                </div>

                <div class="me-case-article">
                    <h3 class="me-case-heading">
                        Transforming a passive Nigerian distributor into a data-visible sales channel
                    </h3>

                    <div class="me-case-body">
                        <p>
                            Packline Industrial Machinery had an established Nigerian distributor for two years, but flat sales and zero visibility into field activity or customer pipeline left leadership blind to actual market demand.
                        </p>
                        <p>
                            P23 mapped 127 target FMCG and food processing factories, deployed structured territory management with Factory 23 activity tracking, and drove a 3× pipeline increase within six months with 100% verified field visibility.
                        </p>
                    </div>

                    <a href="{{ route('case-studies.show', 'packline-industrial-machinery') }}" class="me-case-continue-btn" id="btn-continue-reading-fmcg">
                        Continue Reading
                    </a>
                </div>
            </div>

            {{-- Pane 3: FINTECH Scale-up --}}
            <div class="me-case-pane" id="pane-fintech" role="tabpanel" aria-labelledby="tab-fintech">
                <div class="me-case-visual">
                    <img src="{{ asset('images/case-studies/finedge.png') }}" 
                         alt="Financial software and enterprise technology team collaborating in a modern office" 
                         loading="lazy">
                </div>

                <div class="me-case-article">
                    <h3 class="me-case-heading">
                        Accelerating commercial adoption for B2B financial software in West Africa
                    </h3>

                    <div class="me-case-body">
                        <p>
                            FinEdge had an established Ghanaian reseller for 18 months, but sporadic sales and zero visibility into field visits or pipeline status made it impossible to forecast revenue or assess true market demand.
                        </p>
                        <p>
                            By mapping 120 target institutions, instituting an outbound sales cadence, and tracking every field engagement through Factory 23, the team closed 12 enterprise contracts in six months—a 4× increase in closed deals.
                        </p>
                    </div>

                    <a href="{{ route('case-studies.show', 'finedge-software') }}" class="me-case-continue-btn" id="btn-continue-reading-fintech">
                        Continue Reading
                    </a>
                </div>
            </div>

        </div>

    </section>

    {{-- Interactive Tab Animation Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.me-tab-btn');
            const panes = document.querySelectorAll('.me-case-pane');

            tabButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    if (this.classList.contains('active')) return;

                    // Update Tab active states
                    tabButtons.forEach(b => {
                        b.classList.remove('active');
                        b.setAttribute('aria-selected', 'false');
                    });
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');

                    const targetId = this.getAttribute('data-target');
                    const targetPane = document.querySelector(targetId);

                    // Smooth animated transition
                    panes.forEach(pane => {
                        pane.style.opacity = '0';
                        pane.style.transform = 'translateY(12px)';
                        setTimeout(() => {
                            pane.classList.remove('active');
                            if (pane === targetPane) {
                                pane.classList.add('active');
                                setTimeout(() => {
                                    pane.style.opacity = '1';
                                    pane.style.transform = 'translateY(0)';
                                }, 30);
                            }
                        }, 200);
                    });
                });
            });
        });
    </script>

    {{-- ==========================================
         SECTION 6: READY TO EXPLORE YOUR NEXT MARKET (CTA & STATISTICS)
         Sitting seamlessly on top of footer3 with zero margin/space
         ========================================== --}}
    <section class="me-cta-section" id="explore-cta">
        <div class="me-cta-container">
            <div class="me-cta-grid">
                
                {{-- Left Column: Copy & Actions --}}
                <div class="me-cta-content">
                    <h2 class="me-cta-headline">
                        Ready to explore your next market?
                    </h2>

                    <p class="me-cta-desc">
                        Whether you’re testing your first African market, looking for a new route to market or expanding an existing operation, P23 can help you understand the opportunity and turn it into action.
                    </p>

                    <div class="me-cta-actions">
                        <a href="{{ url('contact') }}" class="me-cta-btn" id="btn-talk-to-p23-africa">
                            Talk to P23 Africa
                        </a>

                        <a href="{{ url('contact') }}" class="me-cta-link" id="link-book-market-exploration">
                            Book a confidential market exploration session→
                        </a>
                    </div>
                </div>

                {{-- Right Column: Statistics Media Prowess Donut Card --}}
                <div class="me-cta-visual">
                    <div class="me-cta-chart-card">
                        <img src="{{ asset('images/market-entry/statistics-media-prowess.png') }}" 
                             alt="P23 Africa market media prowess statistics 147K users" 
                             loading="lazy">
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection

@section('footer')
    @include('layouts.includes.footer3')
@endsection
