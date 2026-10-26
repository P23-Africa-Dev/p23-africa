@extends('layouts.master')
@section('title', 'Services | P23 Africa')
@section('body_class', 'p23-black-navbar-page')

@section('meta')
    <meta name="description"
        content="Business Strategy | Market Access | Growth. Your next market is full of opportunity. Let's make it yours.">
    <meta name="keywords"
        content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
    <meta name="author" content="P23 Africa">
    <meta property="og:image" content="{{ asset('images/servces-bg.png') }}">
    <link rel="stylesheet" href="{{ asset('css/services-new.css') }}">
@endsection

@section('navbar')
    @include('layouts.includes.navbar-v2', ['theme' => 'dark', 'active' => 'what-we-do'])
@endsection

@section('content')
<div class="services-page-wrapper">
    {{-- ==========================================
         SECTION 1: HERO SECTION
         ========================================== --}}
    <section class="services-hero-section" id="hero">
        <div class="services-hero-container">
            <div class="services-hero-grid">
                
                {{-- Left Column: Copy & CTAs --}}
                <div class="services-hero-content">
                    <p class="hero-eyebrow">{{ __('services.eyebrow') }}</p>
                    
                    <h1 class="hero-headline">{{ __('services.hero') }} <em>{{ __('services.hero_em') }}</em></h1>
                    
                    <p class="hero-description">
                        {{ __('services.hero_body') }}
                    </p>
                    
                    <div class="hero-cta-group">
                        <a href="{{ locale_route('contact') }}" class="hero-cta-btn" id="hero-talk-to-us-btn">
                            {{ __('services.talk') }}
                        </a>
                        
                        <div class="hero-casestudy-proof" aria-label="{{ __('services.proof_label') }}">
                            <div class="hero-stars" aria-hidden="true">
                                <svg width="98" height="18" viewBox="0 0 98 18" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="{{ __('services.stars_label') }}">
                                    <path d="M9 0L11.0206 6.21885H17.5595L12.2694 10.0623L14.2901 16.2812L9 12.4377L3.70993 16.2812L5.73056 10.0623L0.440492 6.21885H6.97937L9 0Z" fill="#390908"/>
                                    <path d="M29 0L31.0206 6.21885H37.5595L32.2694 10.0623L34.2901 16.2812L29 12.4377L23.7099 16.2812L25.7306 10.0623L20.4405 6.21885H26.9794L29 0Z" fill="#390908"/>
                                    <path d="M49 0L51.0206 6.21885H57.5595L52.2694 10.0623L54.2901 16.2812L49 12.4377L43.7099 16.2812L45.7306 10.0623L40.4405 6.21885H46.9794L49 0Z" fill="#390908"/>
                                    <path d="M69 0L71.0206 6.21885H77.5595L72.2694 10.0623L74.2901 16.2812L69 12.4377L63.7099 16.2812L65.7306 10.0623L60.4405 6.21885H66.9794L69 0Z" fill="#390908"/>
                                    <path d="M89 0L91.0206 6.21885H97.5595L92.2694 10.0623L94.2901 16.2812L89 12.4377L83.7099 16.2812L85.7306 10.0623L80.4405 6.21885H86.9794L89 0Z" fill="#390908"/>
                                </svg>
                            </div>
                            <a href="{{ locale_route('case-studies') }}" class="hero-casestudy-link">
                                <strong>{{ __('services.click_here') }}</strong> <span>{{ __('services.read_cases') }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right Column: 2x2 Showcase Mosaic --}}
                <div class="services-hero-showcase">
                    <div class="hero-mosaic-grid">
                        
                        {{-- Tile 1 (Top Left): Blue Corporate Architecture --}}
                        <div class="mosaic-tile mosaic-tile-building">
                            <img src="{{ asset('images/services/hero-building.png') }}" 
                                 alt="P23 modern corporate towers representing African enterprise" 
                                 loading="eager">
                        </div>

                        {{-- Tile 2 (Top Right): 20+ Countries Maroon Arch Card --}}
                        <div class="mosaic-tile mosaic-tile-countries">
                            <img src="{{ asset('images/services/hero-countries-card.png') }}" 
                                 alt="Over 20 countries market presence across Africa" 
                                 loading="eager">
                        </div>

                        {{-- Tile 3 (Bottom Left): Strategic Advisory Team with Arched Dome --}}
                        <div class="mosaic-tile mosaic-tile-team">
                            <img src="{{ asset('images/services/hero-team.png') }}" 
                                 alt="P23 multidisciplinary consulting professionals" 
                                 loading="lazy">
                        </div>

                        {{-- Tile 4 (Bottom Right): 5000+ Expert Network Metric --}}
                        <div class="mosaic-tile mosaic-tile-stat">
                            <div class="stat-number">5000+</div>
                            <div class="stat-label">{{ __('services.expert_network') }}</div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ==========================================
         SECTION 1 BOTTOM: PARTNER LOGOS MARQUEE (RIGHT TO LEFT LOOP)
         ========================================== --}}
    @php
        $partnerLogos = [
            [
                'name' => 'CoolAir Systems',
                'src' => asset('images/partner-logos/coolair-systems.png'),
            ],
            [
                'name' => 'PackLine Machinery',
                'src' => asset('images/partner-logos/packline-machinery.png'),
            ],
            [
                'name' => 'CompactPower Equipment',
                'src' => asset('images/partner-logos/compactpower-equipment.png'),
            ],
            [
                'name' => 'Liftmasters International',
                'src' => asset('images/partner-logos/liftmasters-international.png'),
            ],
            [
                'name' => 'Agrimech International',
                'src' => asset('images/partner-logos/agrimech-international.png'),
            ],
            [
                'name' => 'Cloudworks Analytics',
                'src' => asset('images/partner-logos/cloudworks-analytics.png'),
            ],
            [
                'name' => 'Finedge Software',
                'src' => asset('images/partner-logos/finedge-software.png'),
            ],
        ];
    @endphp

    <section class="partner-logos-section" id="trusted-partners" aria-label="{{ __('services.trusted_label') }}">
        <div class="partner-logos-container">
            <p class="partner-logos-title">{{ __('services.trusted') }}</p>
        </div>

        <div class="partner-marquee-wrapper">
            <div class="partner-marquee-track">
                {{-- Group 1 --}}
                <div class="partner-marquee-group">
                    @foreach ($partnerLogos as $logo)
                        <div class="partner-logo-item" title="{{ $logo['name'] }}">
                            <img src="{{ $logo['src'] }}" alt="{{ $logo['name'] }}" loading="lazy">
                            <span class="partner-logo-name">{{ $logo['name'] }}</span>
                        </div>
                    @endforeach
                    @foreach ($partnerLogos as $logo)
                        <div class="partner-logo-item" title="{{ $logo['name'] }}">
                            <img src="{{ $logo['src'] }}" alt="{{ $logo['name'] }}" loading="lazy">
                            <span class="partner-logo-name">{{ $logo['name'] }}</span>
                        </div>
                    @endforeach
                </div>
                {{-- Group 2 (Duplicate for seamless continuous loop) --}}
                <div class="partner-marquee-group" aria-hidden="true">
                    @foreach ($partnerLogos as $logo)
                        <div class="partner-logo-item" title="{{ $logo['name'] }}">
                            <img src="{{ $logo['src'] }}" alt="{{ $logo['name'] }}" loading="lazy">
                            <span class="partner-logo-name">{{ $logo['name'] }}</span>
                        </div>
                    @endforeach
                    @foreach ($partnerLogos as $logo)
                        <div class="partner-logo-item" title="{{ $logo['name'] }}">
                            <img src="{{ $logo['src'] }}" alt="{{ $logo['name'] }}" loading="lazy">
                            <span class="partner-logo-name">{{ $logo['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         SECTION 2: "Growth is a goal. Getting there takes a plan."
         ========================================== --}}
    <section class="services-plan-section" id="growth-plan">
        <div class="plan-section-card">
            
            <h2 class="plan-section-title">
                {{ __('services.plan_line_1') }}<br>
                {{ __('services.plan_line_2') }}
            </h2>

            <div class="plan-cards-grid">
                
                {{-- Column 1: Market Entry & Expansion (Tall 805px Card with 3D Illustration) --}}
                <div class="plan-col-tall">
                    <div class="plan-card plan-card-market-entry">
                        <div class="plan-card-body">
                            <h3 class="plan-card-title">{{ __('services.market_entry') }}</h3>
                            <p class="plan-card-desc">
                                {{ __('services.entry_body') }}
                            </p>
                            <a href="{{ route('services.market-demand-testing') }}" class="plan-explore-btn" aria-label="Explore Market Demand Testing">
                                Explore <span class="btn-arrow">→</span>
                            </a>
                        </div>
                        <div class="plan-card-illustration">
                            <img src="{{ asset('images/services/lightbulb-road.png') }}" 
                                 alt="3D roadmap pointing upward to an illuminated lightbulb" 
                                 loading="lazy">
                        </div>
                    </div>
                </div>

                {{-- Column 2: Distributor (Top, 332px) & Operations (Bottom, 449px) --}}
                <div class="plan-col-stacked">
                    {{-- Distributor Card --}}
                    <div class="plan-card plan-card-distributor">
                        <h3 class="plan-card-title">{{ __('services.distributor') }}</h3>
                        <p class="plan-card-desc">
                            {{ __('services.distributor_body') }}
                        </p>
                        <a href="{{ locale_route('services.distributor-partner') }}" class="plan-explore-btn" aria-label="{{ __('services.explore_distributor') }}">
                            {{ __('services.explore') }} <span class="btn-arrow">→</span>
                        </a>
                    </div>

                    {{-- Operations Card --}}
                    <div class="plan-card plan-card-operations">
                        <h3 class="plan-card-title">{{ __('services.sales_support') }}</h3>
                        <p class="plan-card-desc">
                            {{ __('services.sales_body') }}
                        </p>
                        <a href="{{ locale_route('contact') }}" class="plan-explore-btn" aria-label="{{ __('services.explore_sales') }}">
                            {{ __('services.explore') }} <span class="btn-arrow">→</span>
                        </a>
                    </div>
                </div>

                {{-- Column 3: Investment (Top, 449px) & Outbound Sales (Bottom, 332px) --}}
                <div class="plan-col-stacked">
                    {{-- Investment Card --}}
                    <div class="plan-card plan-card-investment">
                        <h3 class="plan-card-title">{{ __('services.investment') }}</h3>
                        <p class="plan-card-desc">
                            {{ __('services.investment_body') }}
                        </p>
                        <a href="{{ locale_route('contact') }}" class="plan-explore-btn" aria-label="{{ __('services.explore_investment') }}">
                            {{ __('services.explore') }} <span class="btn-arrow">→</span>
                        </a>
                    </div>

                    {{-- Outbound Sales Card --}}
                    <div class="plan-card plan-card-outbound">
                        <h3 class="plan-card-title">{{ __('services.outbound') }}</h3>
                        <p class="plan-card-desc">
                            {{ __('services.outbound_body') }}
                        </p>
                        <a href="{{ route('services.outbound-demand-testing') }}" class="plan-explore-btn" aria-label="Explore Outbound Demand Testing">
                            Explore <span class="btn-arrow">→</span>
                        </a>
                    </div>
                </div>

            </div>

            {{-- Center Aligned Divider Line --}}
            <div class="plan-divider" role="separator" aria-hidden="true"></div>

            {{-- Bottom Metrics Grid (3 Stats) --}}
            <div class="plan-metrics-grid">
                
                {{-- Metric 1: 5,000+ --}}
                <div class="plan-metric-item">
                    <div class="plan-metric-number">5,000+</div>
                    <div class="plan-metric-label">{{ __('services.loved') }}</div>
                </div>

                {{-- Metric 2: 25,000+ --}}
                <div class="plan-metric-item">
                    <div class="plan-metric-number">25,000+</div>
                    <div class="plan-metric-label">{{ __('services.trusted_customers') }}</div>
                </div>

                {{-- Metric 3: 24 hrs --}}
                <div class="plan-metric-item">
                    <div class="plan-metric-number">24 hrs</div>
                    <div class="plan-metric-label">{{ __('services.available') }}</div>
                </div>

            </div>

        </div>
    </section>

    {{-- ==========================================
         SECTION 3: GLOBAL REACH. LOCAL APPROACH.
         ========================================== --}}
    <section class="services-reach-section" id="global-reach">
        <div class="reach-container">
            <div class="reach-grid">
                
                {{-- Left Column: Executive Photo with Top-Right Arch --}}
                <div class="reach-media">
                    <img src="{{ asset('images/services/global-reach-executive.png') }}"                          alt="P23 strategic leadership consulting and advisory" 
                         loading="lazy">
                </div>

                {{-- Right Column: Typography & Contact CTA --}}
                <div class="reach-content">
                    <h2 class="reach-title">
                        {{ __('services.reach_1') }}<br>
                        {{ __('services.reach_2') }}
                    </h2>
                    
                    <div class="reach-description">
                        <p>
                            {{ __('services.reach_body') }}
                        </p>
                        <p>
                            {{ __('services.reach_extra') }}
                        </p>
                    </div>

                    <a href="{{ locale_route('contact') }}" class="reach-contact-btn" id="reach-contact-btn">
                        {{ __('services.contact') }} <span class="btn-arrow">→</span>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- ==========================================
         SECTION 4: 57% OPERATIONAL INEFFICIENCIES STAT
         ========================================== --}}
    <section class="services-inefficiency-section" id="inefficiencies" aria-label="Operational Inefficiencies Growth Impact">
        <div class="inefficiency-card">
            
            <div class="inefficiency-content-wrapper">
                
                {{-- Architectural Left Bracket Accent --}}
                <div class="inefficiency-bracket" aria-hidden="true"></div>

                {{-- Headline Number & Callout Statement --}}
                <div class="inefficiency-hero-stat">
                    <div class="inefficiency-percent-wrap">
                        <div class="inefficiency-percent-backdrop" aria-hidden="true"></div>
                        <div class="inefficiency-percent">57%</div>
                    </div>
                    <p class="inefficiency-statement">
                        {{ __('services.inefficiency') }}
                    </p>
                </div>

                {{-- Centered 3-Column Breakdown Box --}}
                <div class="inefficiency-breakdown-box">
                    
                    {{-- Item 1: Start-ups --}}
                    <div class="breakdown-item">
                        <div class="breakdown-number">68%</div>
                        <div class="breakdown-label">{{ __('services.startups') }}</div>
                    </div>

                    {{-- Item 2: MSMEs --}}
                    <div class="breakdown-item">
                        <div class="breakdown-number">59%</div>
                        <div class="breakdown-label">{{ __('services.msmes') }}</div>
                    </div>

                    {{-- Item 3: Enterprises --}}
                    <div class="breakdown-item">
                        <div class="breakdown-number">46%</div>
                        <div class="breakdown-label">{{ __('services.enterprises') }}</div>
                    </div>

                </div>

            </div>

        </div>
    </section>

    {{-- ==========================================
         SUBSEQUENT SECTIONS CONTAINER (Ready for Section 5+)
         ========================================== --}}
    <div id="services-subsequent-sections">
        {{-- Additional sections will be mounted here if any --}}
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.includes.footer3')
@endsection

