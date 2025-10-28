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

<style>
    .playbook-hero {
        background: linear-gradient(135deg, #193e47 0%, #1deaa5 100%);
        color: white;
        padding: 80px 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .playbook-hero h1 {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .playbook-hero .subtitle {
        font-size: 1.3rem;
        margin-bottom: 30px;
        opacity: 0.9;
        line-height: 1.5;
    }

    .playbook-preview {
        max-width: 400px;
        width: 100%;
        transition: transform 0.3s ease;
        position: relative;
    }

    .playbook-preview:hover {
        transform: translateY(-10px) scale(1.02);
    }

    .btn-download-hero {
        background: white;
        color: #193e47;
        border: none;
        padding: 18px 40px;
        font-size: 1.2rem;
        font-weight: 600;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        margin-top: 20px;
        display: inline-block;
        text-decoration: none;
    }

    .btn-download-hero:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        color: #193e47;
        text-decoration: none;
    }

    .btn-download-hero:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    /* Playbook Download Button Styles */
    .btn-playbook {
        background: linear-gradient(135deg, #193e47 0%, #1deaa5 100%);
        color: white;
        border: none;
        padding: 15px 30px;
        font-size: 18px;
        font-weight: 600;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(25, 62, 71, 0.3);
        min-width: 350px;
    }

    .btn-playbook:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(25, 62, 71, 0.4);
    }

    .btn-playbook:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .features-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #193e47;
        text-align: center;
        margin-bottom: 60px;
    }

    .feature-card {
        background: white;
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        transition: transform 0.3s ease;
        height: 100%;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #193e47 0%, #1deaa5 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 24px;
        color: white;
    }

    .feature-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: #193e47;
        margin-bottom: 15px;
    }

    .feature-description {
        color: #666;
        line-height: 1.6;
    }

    .highlights-section {
        padding: 80px 0;
        background: white;
    }

    .highlight-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid #1deaa5;
    }

    .highlight-number {
        background: #193e47;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 20px;
        flex-shrink: 0;
    }

    .highlight-content {
        flex: 1;
    }

    .highlight-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #193e47;
        margin-bottom: 10px;
    }

    .highlight-text {
        color: #666;
        line-height: 1.5;
    }

    .cta-section {
        background: linear-gradient(135deg, #193e47 0%, #1deaa5 100%);
        color: white;
        padding: 80px 0;
        text-align: center;
    }

    .cta-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .cta-subtitle {
        font-size: 1.2rem;
        margin-bottom: 40px;
        opacity: 0.9;
    }

    .stats-section {
        background: #193e47;
        color: white;
        padding: 60px 0;
    }

    .stat-item {
        text-align: center;
        margin-bottom: 30px;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        color: #1deaa5;
        display: block;
    }

    .stat-label {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .testimonial-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .testimonial-card {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        position: relative;
    }

    .testimonial-quote {
        font-size: 1.1rem;
        line-height: 1.6;
        color: #333;
        margin-bottom: 20px;
        font-style: italic;
    }

    .testimonial-author {
        font-weight: 600;
        color: #193e47;
    }

    .testimonial-role {
        color: #666;
        font-size: 0.9rem;
    }

    .playbook-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 1001;
        padding: 20px;
        animation: fadeIn 0.3s ease;
    }

    .playbook-popup.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .playbook-popup-content {
        background: white;
        width: 100%;
        max-width: 500px;
        border-radius: 15px;
        padding: 40px 30px;
        position: relative;
        animation: slideIn 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .close-playbook-popup {
        position: absolute;
        right: 20px;
        top: 15px;
        font-size: 28px;
        cursor: pointer;
        color: #666;
        transition: color 0.2s ease;
    }

    .close-playbook-popup:hover {
        color: #193e47;
    }

    .playbook-title {
        font-size: 24px;
        font-weight: 700;
        color: #193e47;
        margin-bottom: 10px;
        text-align: center;
    }

    .playbook-subtitle {
        color: #666;
        text-align: center;
        margin-bottom: 30px;
        line-height: 1.5;
    }

    .form-group-playbook {
        margin-bottom: 20px;
    }

    .form-group-playbook label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
    }

    .form-group-playbook input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    .form-group-playbook input:focus {
        outline: none;
        border-color: #1deaa5;
        box-shadow: 0 0 0 3px rgba(29, 234, 165, 0.1);
    }

    .form-group-playbook input.error {
        border-color: #e74c3c;
    }

    .error-message {
        color: #e74c3c;
        font-size: 14px;
        margin-top: 5px;
        display: none;
    }

    .error-message.show {
        display: block;
    }

    .submit-playbook-btn {
        width: 100%;
        background: linear-gradient(135deg, #193e47 0%, #1deaa5 100%);
        color: white;
        border: none;
        padding: 15px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .submit-playbook-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(25, 62, 71, 0.3);
    }

    .submit-playbook-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Toast Notification Styles */
    .toast-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: linear-gradient(135deg, #193e47 0%, #1deaa5 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        z-index: 1002;
        transform: translateX(400px);
        transition: transform 0.3s ease;
        max-width: 350px;
        min-width: 300px;
    }

    .toast-notification.show {
        transform: translateX(0);
    }

    .toast-notification .toast-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .toast-notification .toast-title {
        font-weight: 600;
        font-size: 16px;
        margin: 0;
    }

    .toast-notification .toast-close {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        margin-left: 10px;
    }

    .toast-notification .toast-message {
        font-size: 14px;
        margin: 0;
        line-height: 1.4;
    }

    .toast-notification.success {
        background: #0d4036;
    }

    .toast-notification.error {
        background: #dc3545;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.9);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 768px) {
        .playbook-hero {
            padding: 60px 0;
            text-align: center;
        }

        .playbook-hero h1 {
            font-size: 2.5rem;
        }

        .playbook-hero .subtitle {
            font-size: 1.1rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .cta-title {
            font-size: 2rem;
        }

        .btn-download-hero {
            min-width: 280px;
            font-size: 16px;
            padding: 12px 25px;
        }

        .btn-playbook {
            min-width: 280px;
            font-size: 16px;
            padding: 12px 25px;
        }

        .toast-notification {
            bottom: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
            min-width: auto;
        }

        .playbook-popup-content {
            padding: 30px 20px;
            margin: 10px;
        }

        .playbook-title {
            font-size: 20px;
        }
    }
</style>

@section('content')
    <section class="playbook-hero">
        <div class="container pt-5">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-5 mb-lg-0">
                    <h1>P23 Africa's Founders Playbook</h1>
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
            <h2 class="section-title">What You'll Discover</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">🚀</div>
                        <h3 class="feature-title">Startup Fundamentals</h3>
                        <p class="feature-description">
                            Learn the essential building blocks for launching a successful business in Africa,
                            from ideation to execution.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">📈</div>
                        <h3 class="feature-title">Growth Strategies</h3>
                        <p class="feature-description">
                            Discover proven methods to scale your business across African markets with
                            sustainable growth tactics.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">💰</div>
                        <h3 class="feature-title">Funding & Investment</h3>
                        <p class="feature-description">
                            Navigate the African investment landscape and learn how to secure funding
                            for your ventures.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">🌍</div>
                        <h3 class="feature-title">Market Expansion</h3>
                        <p class="feature-description">
                            Strategic insights on expanding your business across different African
                            markets and cultures.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">🤝</div>
                        <h3 class="feature-title">Partnership Building</h3>
                        <p class="feature-description">
                            Build strategic partnerships and networks that accelerate your business
                            growth across the continent.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <h3 class="feature-title">Digital Transformation</h3>
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
            <h2 class="section-title">Key Highlights from the Playbook</h2>
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
            <h2 class="section-title">What Entrepreneurs Are Saying</h2>
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
            <h2 class="cta-title">Ready to Transform Your Business?</h2>
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
                <h3 class="playbook-title">Get Your Free Founders Playbook</h3>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const playbookBtn = document.getElementById('downloadPlaybookBtn');
            const playbookPopup = document.getElementById('playbookPopup');
            const playbookForm = document.getElementById('playbookForm');
            const closePlaybookPopup = document.querySelector('.close-playbook-popup');
            const submitBtn = document.getElementById('submitPlaybookBtn');

            // Initialize global variable for submission result
            window.playbookSubmissionResult = null;

            // Show popup when playbook button is clicked
            playbookBtn.addEventListener('click', function() {
                playbookPopup.classList.add('show');
                document.body.style.overflow = 'hidden';

                // Restore any previously saved form data
                restoreFormData();
            });

            // Close popup functionality
            function closePlaybookModal() {
                playbookPopup.classList.remove('show');
                document.body.style.overflow = 'auto';
                // Don't reset form immediately - let user reopen with data intact
            }

            closePlaybookPopup.addEventListener('click', closePlaybookModal);

            playbookPopup.addEventListener('click', function(e) {
                if (e.target === playbookPopup) {
                    closePlaybookModal();
                }
            });

            // Form validation
            function validateForm() {
                let isValid = true;
                const name = document.getElementById('playbook_name');
                const email = document.getElementById('playbook_email');
                const company = document.getElementById('playbook_company');

                // Clear previous errors
                clearErrors();

                // Validate name
                if (!name.value.trim()) {
                    showError('nameError', 'Full name is required');
                    name.classList.add('error');
                    isValid = false;
                }

                // Validate email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!email.value.trim()) {
                    showError('emailError', 'Email address is required');
                    email.classList.add('error');
                    isValid = false;
                } else if (!emailRegex.test(email.value.trim())) {
                    showError('emailError', 'Please enter a valid email address');
                    email.classList.add('error');
                    isValid = false;
                }

                // Validate company
                if (!company.value.trim()) {
                    showError('companyError', 'Company name is required');
                    company.classList.add('error');
                    isValid = false;
                }

                return isValid;
            }

            function showError(elementId, message) {
                const errorElement = document.getElementById(elementId);
                errorElement.textContent = message;
                errorElement.classList.add('show');
            }

            function clearErrors() {
                const inputs = playbookForm.querySelectorAll('input');
                const errors = playbookForm.querySelectorAll('.error-message');

                inputs.forEach(input => input.classList.remove('error'));
                errors.forEach(error => {
                    error.classList.remove('show');
                    error.textContent = '';
                });
            }

            function resetForm() {
                playbookForm.reset();
                clearErrors();
                clearStoredFormData(); // Clear localStorage when form is reset

                // Reset submit button
                submitBtn.disabled = false;
                submitBtn.querySelector('.submit-text').style.display = 'inline';
                submitBtn.querySelector('.submit-loading').style.display = 'none';
            }

            // Function to restore form data from localStorage
            function restoreFormData() {
                const savedData = localStorage.getItem('playbookFormData');
                if (savedData) {
                    try {
                        const formData = JSON.parse(savedData);
                        document.getElementById('playbook_name').value = formData.name || '';
                        document.getElementById('playbook_email').value = formData.email || '';
                        document.getElementById('playbook_company').value = formData.company || '';
                    } catch (e) {
                        console.error('Error restoring form data:', e);
                    }
                }
            }

            // Function to clear stored form data
            function clearStoredFormData() {
                localStorage.removeItem('playbookFormData');
            }

            // Countdown functionality
            function startCountdown() {
                const btnText = document.getElementById('playbookBtnText');
                const countdown = document.getElementById('playbookCountdown');
                const timer = document.getElementById('countdownTimer');

                btnText.style.display = 'none';
                countdown.style.display = 'inline';
                playbookBtn.disabled = true;

                let timeLeft = 10;
                timer.textContent = timeLeft;

                const countdownInterval = setInterval(() => {
                    timeLeft--;
                    timer.textContent = timeLeft;

                    if (timeLeft <= 0) {
                        clearInterval(countdownInterval);
                        btnText.style.display = 'inline';
                        countdown.style.display = 'none';
                        playbookBtn.disabled = false;
                        btnText.textContent = 'Playbook Sent! Check Your Email';

                        // Show toast notification immediately when countdown completes
                        if (window.playbookSubmissionResult) {
                            const data = window.playbookSubmissionResult;
                            if (data.success) {
                                // Clear stored form data on success
                                clearStoredFormData();

                                showToast(
                                    'Playbook Sent Successfully!',
                                    'The P23 Africa\'s Founders Playbook has been sent to your email. Please check your inbox.',
                                    'success'
                                );
                            } else {
                                showToast(
                                    'Submission Failed',
                                    data.message || 'An unexpected error occurred. Please try again.',
                                    'error',
                                    true
                                );
                                resetMainButton();
                            }
                            // Clear the stored result
                            window.playbookSubmissionResult = null;
                        } else {
                            // If submission result is not ready yet, show processing message
                            showToast(
                                'Processing Your Request',
                                'Your playbook request is being processed. You should receive it shortly in your email.',
                                'success'
                            );

                            // Keep checking for the result for up to 30 seconds
                            let checkCount = 0;
                            const resultChecker = setInterval(() => {
                                checkCount++;
                                if (window.playbookSubmissionResult) {
                                    clearInterval(resultChecker);
                                    const data = window.playbookSubmissionResult;

                                    // Hide the processing toast
                                    const existingToast = document.querySelector(
                                        '.toast-notification');
                                    if (existingToast) {
                                        hideToast(existingToast);
                                    }

                                    // Show final result after a brief delay
                                    setTimeout(() => {
                                        if (data.success) {
                                            clearStoredFormData();
                                            showToast(
                                                'Playbook Sent Successfully!',
                                                'The P23 Africa\'s Founders Playbook has been sent to your email. Please check your inbox.',
                                                'success'
                                            );
                                        } else {
                                            showToast(
                                                'Submission Failed',
                                                data.message ||
                                                'An unexpected error occurred. Please try again.',
                                                'error',
                                                true
                                            );
                                            resetMainButton();
                                        }
                                        window.playbookSubmissionResult = null;
                                    }, 500);
                                } else if (checkCount >= 30) {
                                    // Stop checking after 30 seconds
                                    clearInterval(resultChecker);

                                    // Hide processing toast and show timeout message
                                    const existingToast = document.querySelector(
                                        '.toast-notification');
                                    if (existingToast) {
                                        hideToast(existingToast);
                                    }

                                    setTimeout(() => {
                                        showToast(
                                            'Request Taking Longer Than Expected',
                                            'Your playbook request is still being processed. Please check your email in a few minutes.',
                                            'success'
                                        );
                                    }, 500);
                                }
                            }, 1000);
                        }

                        // Reset button text after 6 seconds (only if success)
                        setTimeout(() => {
                            if (btnText.textContent === 'Playbook Sent! Check Your Email') {
                                btnText.textContent = '📥 Download Free Playbook';
                            }
                        }, 6000);
                    }
                }, 1000);

                return countdownInterval;
            }

            // Toast notification functionality
            function showToast(title, message, type = 'success', showRetry = false) {
                // Remove existing toast if any
                const existingToast = document.querySelector('.toast-notification');
                if (existingToast) {
                    existingToast.remove();
                }

                // Create toast element
                const toast = document.createElement('div');
                toast.className = `toast-notification ${type}`;

                const retryButton = showRetry ? `
                        <button class="toast-retry-btn" style="
                            background: rgba(255,255,255,0.2);
                            border: 1px solid rgba(255,255,255,0.3);
                            color: white;
                            padding: 5px 10px;
                            border-radius: 4px;
                            font-size: 12px;
                            cursor: pointer;
                            margin-top: 8px;
                            transition: background 0.2s ease;
                        " onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                            Try Again
                        </button>
                    ` : '';

                toast.innerHTML = `
                        <div class="toast-header">
                            <h4 class="toast-title">${title}</h4>
                            <button class="toast-close">&times;</button>
                        </div>
                        <p class="toast-message">${message}</p>
                        ${retryButton}
                    `;

                // Add to body
                document.body.appendChild(toast);

                // Show toast
                setTimeout(() => toast.classList.add('show'), 100);

                // Close functionality
                const closeBtn = toast.querySelector('.toast-close');
                closeBtn.addEventListener('click', () => hideToast(toast));

                // Retry functionality
                if (showRetry) {
                    const retryBtn = toast.querySelector('.toast-retry-btn');
                    if (retryBtn) {
                        retryBtn.addEventListener('click', () => {
                            hideToast(toast);
                            // Reopen the popup with saved data
                            playbookPopup.classList.add('show');
                            document.body.style.overflow = 'hidden';
                            restoreFormData();
                        });
                    }
                }

                // Auto hide after 15 seconds for success, 10 seconds for retry toasts
                const autoHideDelay = showRetry ? 10000 : (type === 'success' ? 15000 : 7000);
                setTimeout(() => hideToast(toast), autoHideDelay);

                return toast;
            }

            function hideToast(toast) {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }

            // Form submission
            playbookForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!validateForm()) {
                    return;
                }

                // Capture form data BEFORE hiding the popup
                const formDataObject = {
                    name: document.getElementById('playbook_name').value.trim(),
                    email: document.getElementById('playbook_email').value.trim(),
                    company: document.getElementById('playbook_company').value.trim(),
                    _token: document.querySelector('meta[name="csrf-token"]').content || document
                        .querySelector(
                            'input[name="_token"]').value
                };

                // Store in localStorage as backup
                localStorage.setItem('playbookFormData', JSON.stringify(formDataObject));

                // Debug: Log form data
                console.log('Form data being sent:', formDataObject);

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.querySelector('.submit-text').style.display = 'none';
                submitBtn.querySelector('.submit-loading').style.display = 'inline';

                // Hide popup immediately after capturing data
                closePlaybookModal();

                // Start countdown on main button
                const countdownInterval = startCountdown();

                // Create FormData object from captured data
                const formData = new FormData();
                Object.keys(formDataObject).forEach(key => {
                    formData.append(key, formDataObject[key]);
                });

                // Submit to server
                fetch('{{ route('playbook.download') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': formDataObject._token,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(async response => {
                        const data = await response.json();

                        if (!response.ok) {
                            // Handle different HTTP status codes
                            if (response.status === 422) {
                                // Validation errors
                                let errorMessages = [];
                                if (data.errors) {
                                    Object.values(data.errors).forEach(fieldErrors => {
                                        if (Array.isArray(fieldErrors)) {
                                            errorMessages = errorMessages.concat(
                                                fieldErrors);
                                        } else {
                                            errorMessages.push(fieldErrors);
                                        }
                                    });
                                }

                                const errorMessage = errorMessages.length > 0 ?
                                    errorMessages.join(' ') :
                                    'Please check your form inputs and try again.';

                                throw new Error(`Validation Error: ${errorMessage}`);
                            } else if (response.status === 500) {
                                throw new Error('Server Error: ' + (data.message ||
                                    'Internal server error occurred.'));
                            } else {
                                throw new Error('HTTP Error: ' + (data.message ||
                                    `Request failed with status ${response.status}`));
                            }
                        }

                        return data;
                    })
                    .then(data => {
                        // Store the result for when countdown completes
                        window.playbookSubmissionResult = data;
                    })
                    .catch(error => {
                        console.error('Playbook download error:', error);

                        // Clear countdown and reset button immediately on error
                        clearInterval(countdownInterval);
                        resetMainButton();

                        // Clear any stored result
                        window.playbookSubmissionResult = null;

                        // Show error toast immediately
                        let errorTitle = 'Submission Failed';
                        let errorMessage = 'An error occurred. Please try again.';

                        if (error.message.includes('Validation Error:')) {
                            errorTitle = 'Form Validation Error';
                            errorMessage = error.message.replace('Validation Error: ', '');
                            showToast(errorTitle, errorMessage + ' Click "Try Again" to fix your form.',
                                'error', true);
                        } else if (error.message.includes('Server Error:')) {
                            errorTitle = 'Server Error';
                            errorMessage = error.message.replace('Server Error: ', '');
                            showToast(errorTitle, errorMessage, 'error', true);
                        } else if (error.message.includes('HTTP Error:')) {
                            errorTitle = 'Request Error';
                            errorMessage = error.message.replace('HTTP Error: ', '');
                            showToast(errorTitle, errorMessage, 'error', true);
                        } else if (error.message.includes('Failed to fetch') || error.message.includes(
                                'NetworkError')) {
                            errorTitle = 'Network Error';
                            errorMessage = 'Please check your internet connection and try again.';
                            showToast(errorTitle, errorMessage, 'error', true);
                        } else {
                            showToast(errorTitle, errorMessage, 'error', true);
                        }
                    })
                    .finally(() => {
                        // Reset submit button (this will run immediately but popup is already closed)
                        submitBtn.disabled = false;
                        submitBtn.querySelector('.submit-text').style.display = 'inline';
                        submitBtn.querySelector('.submit-loading').style.display = 'none';
                    });
            });

            // Helper function to reset the main button
            function resetMainButton() {
                const btnText = document.getElementById('playbookBtnText');
                const countdown = document.getElementById('playbookCountdown');
                btnText.style.display = 'inline';
                countdown.style.display = 'none';
                playbookBtn.disabled = false;
                btnText.textContent = '📥 Download Free Playbook';
            }
        });
    </script>
@endsection
