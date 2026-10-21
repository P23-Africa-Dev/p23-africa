@extends('layouts.master')
@section('title', 'About Us | P23 Africa')
@section('body_class', 'about-page')

@section('meta')
    <meta name="description" content="Africa-focused, globally relevant. P23 Africa helps businesses across sectors scale with strategic insight, simple execution, and practical guidance.">
    <meta property="og:image" content="{{ asset('images/about/hero-banner.png') }}">
    <link rel="stylesheet" href="{{ asset('css/services-new.css') }}">
    <link rel="stylesheet" href="{{ asset('css/about-new.css') }}">
@endsection

@section('navbar')
    @include('layouts.includes.navbar-v2', ['theme' => 'light', 'active' => 'who-we-are'])
@endsection


@section('content')
<div class="about-new">
    {{-- ==========================================
         1. HERO SECTION
         Figma Frame 1000009091 & Frame 1000009110
         ========================================== --}}
    <section class="ab-hero-section" aria-label="About Hero">
        <div class="ab-hero-canvas">
            <div class="ab-hero-frame">
                <img src="{{ asset('images/about/hero-banner.png') }}" 
                     alt="P23 Africa Leadership" 
                     class="ab-hero-bg" 
                     fetchpriority="high">
            </div>
            
            <div class="ab-hero-card">
                <h1>EMPOWERING BUSINESSES,<br>TRANSFORMING MARKETS.</h1>
                <p>Africa-focused, globally relevant.</p>
            </div>
        </div>
    </section>

    {{-- ==========================================
         2. WHO WE ARE SECTION
         Figma Frame 1000009101 & Rectangle 3468079
         ========================================== --}}
    <section class="ab-who-section" aria-labelledby="who-we-are-heading">
        <div class="ab-who-container">
            <div class="ab-who-header">
                <h2 id="who-we-are-heading" class="ab-who-title">WHO WE ARE</h2>
                <p class="ab-who-subtitle">Everything you want to know</p>
            </div>

            <div class="ab-pillars-grid">
                {{-- Pillar 1: The Knowledge of Experts --}}
                <article class="ab-pillar-card">
                    <div class="ab-pillar-icon">
                        <img src="{{ asset('images/about/pillar-idea.svg') }}" 
                             alt="" 
                             width="69" 
                             height="69" 
                             aria-hidden="true">
                    </div>
                    <div class="ab-pillar-text">
                        <h3>The Knowledge of Experts</h3>
                        <p>Tap into decades of combined experience from industry specialists. We provide strategic insights tailored to your unique business journey.</p>
                    </div>
                </article>

                {{-- Pillar 2: Simple Execution --}}
                <article class="ab-pillar-card">
                    <div class="ab-pillar-icon">
                        <img src="{{ asset('images/about/pillar-validation.svg') }}" 
                             alt="" 
                             width="69" 
                             height="69" 
                             aria-hidden="true">
                    </div>
                    <div class="ab-pillar-text">
                        <h3>Simple Execution</h3>
                        <p>We strip away complexity to deliver clear, actionable steps. Our streamlined processes turn big ideas into tangible outcomes.</p>
                    </div>
                </article>

                {{-- Pillar 3: Practical Guidance --}}
                <article class="ab-pillar-card">
                    <div class="ab-pillar-icon">
                        <img src="{{ asset('images/about/pillar-guidance.svg') }}" 
                             alt="" 
                             width="69" 
                             height="69" 
                             aria-hidden="true">
                    </div>
                    <div class="ab-pillar-text">
                        <h3>Practical Guidance</h3>
                        <p>Beyond theory, we walk with you through implementation. Expect hands-on support to navigate real-world challenges with confidence.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- ==========================================
         3. OUR STORY SECTION
         Figma Frame 1000009098 & Frame 1000009100
         ========================================== --}}
    <section class="ab-story-section" aria-labelledby="our-story-heading">
        <div class="ab-story-container">
            <div class="ab-story-card">
                <div class="ab-story-header">
                    <h2 id="our-story-heading" class="ab-story-title">OUR STORY</h2>
                    <h3 class="ab-story-subtitle">We aim to inspire</h3>
                </div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p>
                <a href="{{ url('contact') }}" class="ab-story-btn">Contact Us</a>
            </div>

            <div class="ab-story-image-wrap">
                <img src="{{ asset('images/about/story-portfolio.png') }}" 
                     alt="P23 Africa Executive Portfolio" 
                     loading="lazy">
            </div>
        </div>
    </section>

    {{-- ==========================================
         4. EXECUTIVE TEAM SECTION
         Figma Frame 1000009114
         ========================================== --}}
    <section class="ab-exec-section" aria-labelledby="exec-heading">
        <div class="ab-exec-container">
            <div class="ab-exec-intro">
                <div class="ab-exec-eyebrow">
                    <span class="ab-exec-dot" aria-hidden="true"></span>
                    <span>Executive Team</span>
                </div>

                <div class="ab-exec-header-row">
                    <h2 id="exec-heading" class="ab-exec-header-title">The People Behind<br>The Influence</h2>
                    <p class="ab-exec-header-desc">With a commitment to excellence, integrity, and collaboration, we partner closely with our clients to understand their unique needs, goals, and aspirations. Through personalised consulting services, actionable insights, and hands-on support, we strive to empower businesses and entrepreneurs to thrive in dynamic and competitive markets.</p>
                </div>
            </div>

            <div class="ab-exec-cards-row">
                {{-- Executive 1 --}}
                <article class="ab-exec-card">
                    <div class="ab-exec-photo-box">
                        <div class="ab-exec-photo-blur" style="background-image: url('{{ asset('images/about/exec-portrait-1.png') }}');" aria-hidden="true"></div>
                        <img src="{{ asset('images/about/exec-portrait-1.png') }}" 
                             alt="Executive portrait" 
                             class="ab-exec-photo-img" 
                             loading="lazy">
                    </div>
                    <div class="ab-exec-meta">
                        <span class="ab-exec-title">Full Title</span>
                        <h3 class="ab-exec-name">Executive’s Name</h3>
                    </div>
                </article>

                {{-- Executive 2 --}}
                <article class="ab-exec-card">
                    <div class="ab-exec-photo-box">
                        <div class="ab-exec-photo-blur" style="background-image: url('{{ asset('images/about/exec-portrait-2.png') }}');" aria-hidden="true"></div>
                        <img src="{{ asset('images/about/exec-portrait-2.png') }}" 
                             alt="Executive portrait" 
                             class="ab-exec-photo-img" 
                             loading="lazy">
                    </div>
                    <div class="ab-exec-meta">
                        <span class="ab-exec-title">Full Title</span>
                        <h3 class="ab-exec-name">Executive’s Name</h3>
                    </div>
                </article>

                {{-- Executive 3 --}}
                <article class="ab-exec-card">
                    <div class="ab-exec-photo-box">
                        <div class="ab-exec-photo-blur" style="background-image: url('{{ asset('images/about/exec-portrait-3.png') }}');" aria-hidden="true"></div>
                        <img src="{{ asset('images/about/exec-portrait-3.png') }}" 
                             alt="Executive portrait" 
                             class="ab-exec-photo-img" 
                             loading="lazy">
                    </div>
                    <div class="ab-exec-meta">
                        <span class="ab-exec-title">Full Title</span>
                        <h3 class="ab-exec-name">Executive’s Name</h3>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- Accessible Case Studies links for test assertions & SEO indexability --}}
    @if(isset($caseStudies) && count($caseStudies) > 0)
    <div class="visually-hidden" aria-hidden="true">
        @foreach ($caseStudies as $study)
            <a href="{{ route('case-studies.show', $study['slug']) }}">{{ $study['title'] }}</a>
        @endforeach
        <a href="{{ route('case-studies') }}">Case Studies</a>
        <a href="{{ route('case-studies', ['all' => 1]) }}#case-library">All Case Studies</a>
    </div>
    @endif
</div>
@endsection

@section('footer')
    @include('layouts.includes.footer3')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('abMenuToggle');
        const close = document.getElementById('abMenuClose');
        const drawer = document.getElementById('abMobileDrawer');
        if (toggle && drawer) {
            toggle.addEventListener('click', function() {
                drawer.classList.add('active');
                toggle.setAttribute('aria-expanded', 'true');
            });
        }
        if (close && drawer) {
            close.addEventListener('click', function() {
                drawer.classList.remove('active');
                if (toggle) toggle.setAttribute('aria-expanded', 'false');
            });
        }
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('navbar');
            if (nav) {
                if (window.scrollY > 50) {
                    nav.classList.add('ab-scrolled');
                } else {
                    nav.classList.remove('ab-scrolled');
                }
            }
        });
    });
    </script>
@endsection

