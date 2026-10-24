@extends('layouts.master')
@section('title', 'P23 Africa\'s Founders Playbook | Free Download')

@section('meta')
    <meta name="description"
        content="Download the P23 Africa's Founders Playbook - Your comprehensive guide to building and scaling successful businesses across Africa. Free expert insights, strategies, and actionable advice.">
    <meta name="keywords"
        content="founders playbook, Africa business guide, startup guide, business scaling, entrepreneurship Africa, P23 Africa, business strategy, market expansion">
    <meta name="author" content="P23 Africa">
    <meta name="generator" content="P23 Africa's Founders Playbook - Free Business Guide">
    <meta property="og:image" content="{{ asset('images/playbook-og.png') }}">
    <meta property="og:title" content="P23 Africa's Founders Playbook | Free Download">
    <meta property="og:description"
        content="Your comprehensive guide to building and scaling successful businesses across Africa">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection



@section('content')
    <section class="playbook-hero">
        <div class="container pt-5">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-5 mb-lg-0">
                    <h1>{{ __('playbook.title') }}</h1>
                    <p class="subtitle">
                        Your comprehensive guide to building and scaling successful businesses across Africa.
                        Get expert insights, proven strategies, and actionable advice from successful entrepreneurs.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 align-items-start">
                        <button id="downloadPlaybookBtn" class="btn-download-hero">
                            <span id="playbookBtnText">📥 Download Free Playbook</span>
                            <span id="playbookCountdown" style="display: none;">Sending... (<span
                                    id="countdownTimer">10</span>s)</span>
                        </button>
                        <div class="d-flex align-items-center gap-2 mt-5 position-relative">
                            <span class="text-white-50">✅ Instant Download</span>
                            <span class="text-white-50">•</span>
                            <span class="text-white-50">🔒 No Spam</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <div class="playbook-preview mx-auto">
                        <div
                            style="
                            width: 100%;
                            max-width: 350px;
                            height: 500px;
                            background: linear-gradient(145deg, #ffffff 0%, #f0f0f0 100%);
                            border-radius: 8px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            flex-direction: column;
                            box-shadow: 
                                -5px 0 10px rgba(0, 0, 0, 0.1),
                                5px 0 15px rgba(0, 0, 0, 0.2),
                                10px 0 20px rgba(0, 0, 0, 0.15);
                            border-left: 3px solid #e0e0e0;
                            border-right: 3px solid #d0d0d0;
                            position: relative;
                            transform: perspective(1000px) rotateY(-15deg);
                            transition: transform 0.3s ease;
                        ">
                            <!-- Book spine effect -->
                            <div
                                style="
                                position: absolute;
                                left: -8px;
                                top: 0;
                                width: 8px;
                                height: 100%;
                                background: linear-gradient(to right, #193e47 0%, #1deaa5 100%);
                                border-radius: 8px 0 0 8px;
                            ">
                            </div>

                            <!-- Book pages effect -->
                            <div
                                style="
                                position: absolute;
                                right: -3px;
                                top: 3px;
                                width: 3px;
                                height: calc(100% - 6px);
                                background: repeating-linear-gradient(
                                    0deg,
                                    #fff 0px,
                                    #fff 2px,
                                    #e0e0e0 2px,
                                    #e0e0e0 3px
                                );
                            ">
                            </div>

                            <!-- Book content -->
                            <div style="padding: 40px 30px; width: 100%;">
                                <div
                                    style="
                                    font-size: 22px;
                                    font-weight: 700;
                                    color: #193e47;
                                    text-align: center;
                                    margin-bottom: 10px;
                                ">
                                    P23 Africa's</div>

                                <div
                                    style="
                                    font-size: 26px;
                                    font-weight: 800;
                                    color: #1deaa5;
                                    text-align: center;
                                    margin-bottom: 20px;
                                    line-height: 1.2;
                                ">
                                    FOUNDERS<br>PLAYBOOK</div>

                                <div
                                    style="
                                    width: 60px;
                                    height: 4px;
                                    background: #193e47;
                                    margin: 0 auto 20px;
                                ">
                                </div>

                                <div
                                    style="
                                    font-size: 13px;
                                    color: #666;
                                    text-align: center;
                                    line-height: 1.6;
                                ">
                                    Your Complete Guide to Building & Scaling Successful Businesses in Africa</div>

                                <div
                                    style="
                                    position: absolute;
                                    bottom: 30px;
                                    left: 50%;
                                    transform: translateX(-50%);
                                    font-size: 42px;
                                    opacity: 0.1;
                                ">
                                    📚</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    {{-- <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Pages of Insights</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">15+</span>
                        <span class="stat-label">Key Strategies</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">100+</span>
                        <span class="stat-label">Actionable Tips</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">5K+</span>
                        <span class="stat-label">Downloads</span>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Features Section -->
    {{-- <section class="features-section">
        <div class="container">
            <h2 class="section-title">{{ __('playbook.discover') }}</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">🚀</div>
                        <h3 class="feature-title">{{ __('playbook.fundamentals') }}</h3>
                        <p class="feature-description">
                            Learn the essential building blocks for launching a successful business in Africa,
                            from ideation to execution.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">📈</div>
                        <h3 class="feature-title">{{ __('playbook.growth') }}</h3>
                        <p class="feature-description">
                            Discover proven methods to scale your business across African markets with
                            sustainable growth tactics.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">💰</div>
                        <h3 class="feature-title">{{ __('playbook.funding') }}</h3>
                        <p class="feature-description">
                            Navigate the African investment landscape and learn how to secure funding
                            for your ventures.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">🌍</div>
                        <h3 class="feature-title">{{ __('playbook.expansion') }}</h3>
                        <p class="feature-description">
                            Strategic insights on expanding your business across different African
                            markets and cultures.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">🤝</div>
                        <h3 class="feature-title">{{ __('playbook.partnerships') }}</h3>
                        <p class="feature-description">
                            Build strategic partnerships and networks that accelerate your business
                            growth across the continent.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <h3 class="feature-title">{{ __('playbook.digital') }}</h3>
                        <p class="feature-description">
                            Leverage technology and digital tools to optimize operations and
                            reach customers effectively.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Highlights Section -->
    {{-- <section class="highlights-section">
        <div class="container">
            <h2 class="section-title">{{ __('playbook.highlights') }}</h2>
            <div class="row">
                <div class="col-lg-6">
                    <div class="highlight-item">
                        <div class="highlight-number">1</div>
                        <div class="highlight-content">
                            <h4 class="highlight-title">Understanding African Markets</h4>
                            <p class="highlight-text">
                                Deep dive into the unique characteristics, opportunities, and challenges
                                of different African markets and consumer behaviors.
                            </p>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-number">2</div>
                        <div class="highlight-content">
                            <h4 class="highlight-title">Legal & Regulatory Framework</h4>
                            <p class="highlight-text">
                                Navigate the complex legal landscape with practical guidance on
                                business registration, compliance, and regulatory requirements.
                            </p>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-number">3</div>
                        <div class="highlight-content">
                            <h4 class="highlight-title">Financial Management</h4>
                            <p class="highlight-text">
                                Master financial planning, cash flow management, and investment
                                strategies tailored for African business environments.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="highlight-item">
                        <div class="highlight-number">4</div>
                        <div class="highlight-content">
                            <h4 class="highlight-title">Marketing & Customer Acquisition</h4>
                            <p class="highlight-text">
                                Effective marketing strategies and customer acquisition techniques
                                that work in African markets and cultures.
                            </p>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-number">5</div>
                        <div class="highlight-content">
                            <h4 class="highlight-title">Building Resilient Teams</h4>
                            <p class="highlight-text">
                                Learn how to recruit, manage, and retain top talent while building
                                a strong company culture across diverse markets.
                            </p>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-number">6</div>
                        <div class="highlight-content">
                            <h4 class="highlight-title">Exit Strategies & Scale</h4>
                            <p class="highlight-text">
                                Plan for long-term success with insights on exit strategies,
                                succession planning, and scaling internationally.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Testimonials Section -->
    {{-- <section class="testimonial-section">
        <div class="container">
            <h2 class="section-title">{{ __('playbook.saying') }}</h2>
            <div class="row">
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <p class="testimonial-quote">
                            "This playbook provided the exact roadmap I needed to scale my fintech startup
                            across three African countries. The insights are practical and actionable."
                        </p>
                        <div class="testimonial-author">Sarah Adebayo</div>
                        <div class="testimonial-role">CEO, PayFlow Africa</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <p class="testimonial-quote">
                            "The section on regulatory compliance saved us months of research and potential
                            legal issues. A must-read for any serious entrepreneur in Africa."
                        </p>
                        <div class="testimonial-author">Michael Okonkwo</div>
                        <div class="testimonial-role">Founder, AgriTech Solutions</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <p class="testimonial-quote">
                            "The funding strategies outlined in this playbook helped us secure $2M in Series A.
                            The investor network insights are invaluable."
                        </p>
                        <div class="testimonial-author">Amara Diallo</div>
                        <div class="testimonial-role">Co-founder, HealthTech Kenya</div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- CTA Section -->
    {{-- <section class="cta-section">
        <div class="container text-center">
            <h2 class="cta-title">{{ __('playbook.ready') }}</h2>
            <p class="cta-subtitle">
                Join thousands of successful entrepreneurs who have downloaded the P23 Africa's Founders Playbook
            </p>
            <button id="ctaDownloadBtn" class="btn-download-hero">
                📥 Get Your Free Copy Now
            </button>
        </div>
    </section> --}}

    <!-- Playbook Download Popup -->
    <div class="playbook-popup" id="playbookPopup">
        <div class="playbook-popup-content">
            <span class="close-playbook-popup">&times;</span>
            <div class="playbook-form-container">
                <h3 class="playbook-title">{{ __('playbook.free') }}</h3>
                <p class="playbook-subtitle">Enter your details to receive the P23 Africa's Founders Playbook directly in
                    your inbox.</p>

                <form id="playbookForm" class="playbook-form">
                    @csrf
                    <div class="form-group-playbook">
                        <label for="playbook_name">Full Name *</label>
                        <input type="text" id="playbook_name" name="name" required>
                        <div class="error-message" id="nameError"></div>
                    </div>

                    <div class="form-group-playbook">
                        <label for="playbook_email">Email Address *</label>
                        <input type="email" id="playbook_email" name="email" required>
                        <div class="error-message" id="emailError"></div>
                    </div>

                    <div class="form-group-playbook">
                        <label for="playbook_company">Company Name *</label>
                        <input type="text" id="playbook_company" name="company" required>
                        <div class="error-message" id="companyError"></div>
                    </div>

                    <button type="submit" class="submit-playbook-btn" id="submitPlaybookBtn">
                        <span class="submit-text">Get My Playbook</span>
                        <span class="submit-loading" style="display: none;">
                            <span class="spinner"></span> Submitting...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    
@endsection
