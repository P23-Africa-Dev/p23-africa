@extends('layouts.master')
@section('title', 'Market Demand Testing for Africa')
@section('body_class', 'p23-black-navbar-page')

@section('meta')
    <meta name="description"
        content="Test real market demand and validate commercial routes across Nigeria, Ghana, Kenya, and South Africa before committing capital.">
    <meta name="keywords"
        content="Market demand testing Africa, market entry Africa, expand business to Nigeria, expand to Ghana, African business expansion, distributor due diligence Africa, demand validation Africa">
    <meta property="og:title" content="Market Demand Testing for Africa">
    <meta property="og:description" content="Test real market demand and validate commercial routes across Nigeria, Ghana, Kenya, and South Africa before committing capital.">
    <meta property="og:image" content="{{ asset('images/market-entry/hero-office-woman.png') }}">
    <link rel="canonical" href="{{ url('services/market-demand-testing') }}">
    <link rel="stylesheet" href="{{ asset('css/market-entry-new.css') }}">
    <link rel="stylesheet" href="{{ asset('css/services-new.css') }}">

    <script type="application/ld+json">
    {!! json_encode([
      '@context' => 'https://schema.org',
      '@graph' => [
        [
          '@type' => 'Service',
          'name' => 'Market Demand Testing for Africa',
          'serviceType' => 'Market Entry & Commercial Validation',
          'provider' => [
            '@type' => 'Organization',
            'name' => 'P23 Africa',
            'url' => 'https://p23africa.com'
          ],
          'url' => url('services/market-demand-testing'),
          'description' => 'Test real market demand and validate commercial routes across Nigeria, Ghana, Kenya, and South Africa before committing capital.',
          'areaServed' => ['Nigeria', 'Ghana', 'Kenya', 'South Africa']
        ],
        [
          '@type' => 'BreadcrumbList',
          'itemListElement' => [
            [
              '@type' => 'ListItem',
              'position' => 1,
              'name' => 'Home',
              'item' => url('/')
            ],
            [
              '@type' => 'ListItem',
              'position' => 2,
              'name' => 'Services',
              'item' => url('services')
            ],
            [
              '@type' => 'ListItem',
              'position' => 3,
              'name' => 'Market Demand Testing',
              'item' => url('services/market-demand-testing')
            ]
          ]
        ]
      ]
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
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
                        {{ __('market.hero') }}
                    </h1>

                    <p class="me-hero-description">
                        {{ __('market.hero_body') }}
                    </p>

                    <div class="me-hero-cta-group">
                        <a href="{{ locale_route('contact') }}" class="me-hero-btn" id="me-hero-talk-to-us-btn">
                            {{ __('market.talk') }}
                        </a>

                        <div class="me-hero-proof">
                            <div class="me-hero-stars" aria-label="{{ __('market.stars') }}">
                                <svg width="98" height="18" viewBox="0 0 98 18" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="5 stars rating">
                                    <path d="M9 0L11.0206 6.21885H17.5595L12.2694 10.0623L14.2901 16.2812L9 12.4377L3.70993 16.2812L5.73056 10.0623L0.440492 6.21885H6.97937L9 0Z" fill="#390908"/>
                                    <path d="M29 0L31.0206 6.21885H37.5595L32.2694 10.0623L34.2901 16.2812L29 12.4377L23.7099 16.2812L25.7306 10.0623L20.4405 6.21885H26.9794L29 0Z" fill="#390908"/>
                                    <path d="M49 0L51.0206 6.21885H57.5595L52.2694 10.0623L54.2901 16.2812L49 12.4377L43.7099 16.2812L45.7306 10.0623L40.4405 6.21885H46.9794L49 0Z" fill="#390908"/>
                                    <path d="M69 0L71.0206 6.21885H77.5595L72.2694 10.0623L74.2901 16.2812L69 12.4377L63.7099 16.2812L65.7306 10.0623L60.4405 6.21885H66.9794L69 0Z" fill="#390908"/>
                                    <path d="M89 0L91.0206 6.21885H97.5595L92.2694 10.0623L94.2901 16.2812L89 12.4377L83.7099 16.2812L85.7306 10.0623L80.4405 6.21885H86.9794L89 0Z" fill="#390908"/>
                                </svg>
                            </div>
                            <a href="{{ locale_route('case-studies') }}" class="me-hero-case-studies-link">
                                <strong>{{ __('market.click_here') }}</strong> <em>{{ __('market.read_cases') }}</em>
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
                        {!! __('market.method') !!}
                    </h2>
                </div>

                {{-- Right 5-Step Strip --}}
                <div class="me-methodology-steps">

                    {{-- Step 01 --}}
                    <div class="me-methodology-step">
                        <div class="me-step-header">
                            <img src="{{ asset('images/market-entry/step-01-lightbulb.png') }}" 
                                 alt="{{ __('market.step1_title') }}" 
                                 class="me-step-icon">
                            <span class="me-step-number">01</span>
                        </div>
                        <h3 class="me-step-title">{{ __('market.step1_title') }}</h3>
                        <p class="me-step-desc">
                            {{ __('market.step1_body') }}
                        </p>
                    </div>

                    {{-- Step 02 --}}
                    <div class="me-methodology-step">
                        <div class="me-step-header">
                            <img src="{{ asset('images/market-entry/step-02-validate.png') }}" 
                                 alt="{{ __('market.step2_title') }}" 
                                 class="me-step-icon">
                            <span class="me-step-number">02</span>
                        </div>
                        <h3 class="me-step-title">{{ __('market.step2_title') }}</h3>
                        <p class="me-step-desc">
                            {{ __('market.step2_body') }}
                        </p>
                    </div>

                    {{-- Step 03 --}}
                    <div class="me-methodology-step">
                        <div class="me-step-header">
                            <img src="{{ asset('images/market-entry/step-03-people.png') }}" 
                                 alt="{{ __('market.step3_title') }}" 
                                 class="me-step-icon">
                            <span class="me-step-number">03</span>
                        </div>
                        <h3 class="me-step-title">{{ __('market.step3_title') }}</h3>
                        <p class="me-step-desc">
                            {{ __('market.step3_body') }}
                        </p>
                    </div>

                    {{-- Step 04 --}}
                    <div class="me-methodology-step">
                        <div class="me-step-header">
                            <img src="{{ asset('images/market-entry/step-04-route.png') }}" 
                                 alt="{{ __('market.step4_title') }}" 
                                 class="me-step-icon">
                            <span class="me-step-number">04</span>
                        </div>
                        <h3 class="me-step-title">{{ __('market.step4_title') }}</h3>
                        <p class="me-step-desc">
                            {{ __('market.step4_body') }}
                        </p>
                    </div>

                    {{-- Step 05 --}}
                    <div class="me-methodology-step">
                        <div class="me-step-header">
                            <img src="{{ asset('images/market-entry/step-05-lock.png') }}" 
                                 alt="{{ __('market.step5_title') }}" 
                                 class="me-step-icon">
                            <span class="me-step-number">05</span>
                        </div>
                        <h3 class="me-step-title">{{ __('market.step5_title') }}</h3>
                        <p class="me-step-desc">
                            {{ __('market.step5_body') }}
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
                    {{ __('market.execution') }}
                </h2>

                <p class="me-execution-desc">
                    {{ __('market.execution_body') }}
                </p>

                <a href="{{ locale_route('contact') }}" class="me-execution-link" id="me-execution-cta-link">
                    {{ __('market.book_session') }}
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
                        {{ __('market.momentum') }}
                    </h2>
                    <a href="{{ locale_route('contact') }}" class="me-momentum-btn" id="me-momentum-talk-btn">
                        {{ __('market.talk') }}
                    </a>
                </div>

                <p class="me-momentum-desc">
                    {{ __('market.momentum_body') }}
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
                {{ __('market.presence') }}
            </h2>
            <p class="me-casestudy-subtitle">
                {{ __('market.presence_body') }}
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
                    {{ __('market.tab_hvac') }}
                </button>
                <button type="button" 
                        class="me-tab-btn" 
                        role="tab" 
                        aria-selected="false" 
                        aria-controls="pane-fmcg" 
                        data-target="#pane-fmcg"
                        id="tab-fmcg">
                    {{ __('market.tab_machinery') }}
                </button>
                <button type="button" 
                        class="me-tab-btn" 
                        role="tab" 
                        aria-selected="false" 
                        aria-controls="pane-fintech" 
                        data-target="#pane-fintech"
                        id="tab-fintech">
                    {{ __('market.tab_fintech') }}
                </button>
            </div>
        </div>

        {{-- Proof Stars & Read Case Studies Link --}}
        <div class="me-casestudy-proof">
            <div class="me-casestudy-proof-stars">
                <img src="{{ asset('images/market-entry/stars-black.png') }}" 
                     alt="{{ __('market.stars') }}" 
                     width="90" 
                     height="18" 
                     loading="lazy">
            </div>
            <a href="{{ locale_route('case-studies') }}" class="me-casestudy-proof-link">
                <strong>{{ __('market.click_here') }}</strong> <em>{{ __('market.read_cases') }}</em>
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
                        {{ __('market.case1_title') }}
                    </h3>

                    <div class="me-case-body">
                        <p>
                            {{ __('market.case1_p1') }}
                        </p>
                        <p>
                            {{ __('market.case1_p2') }}
                        </p>
                    </div>

                    <a href="{{ locale_route('case-studies.show', ['slug' => 'thermoflow-hvac']) }}" class="me-case-continue-btn" id="btn-continue-reading-bld">
                        {{ __('market.continue') }}
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
                        {{ __('market.case2_title') }}
                    </h3>

                    <div class="me-case-body">
                        <p>
                            {{ __('market.case2_p1') }}
                        </p>
                        <p>
                            {{ __('market.case2_p2') }}
                        </p>
                    </div>

                    <a href="{{ locale_route('case-studies.show', ['slug' => 'packline-industrial-machinery']) }}" class="me-case-continue-btn" id="btn-continue-reading-fmcg">
                        {{ __('market.continue') }}
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
                        {{ __('market.case3_title') }}
                    </h3>

                    <div class="me-case-body">
                        <p>
                            {{ __('market.case3_p1') }}
                        </p>
                        <p>
                            {{ __('market.case3_p2') }}
                        </p>
                    </div>

                    <a href="{{ locale_route('case-studies.show', ['slug' => 'finedge-software']) }}" class="me-case-continue-btn" id="btn-continue-reading-fintech">
                        {{ __('market.continue') }}
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
                        {{ __('market.cta') }}
                    </h2>

                    <p class="me-cta-desc">
                        {{ __('market.cta_body') }}
                    </p>

                    <div class="me-cta-actions">
                        <a href="{{ locale_route('contact') }}" class="me-cta-btn" id="btn-talk-to-p23-africa">
                            {{ __('market.talk_p23') }}
                        </a>

                        <a href="{{ locale_route('contact') }}" class="me-cta-link" id="link-book-market-exploration">
                            {{ __('market.book_session') }}
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
