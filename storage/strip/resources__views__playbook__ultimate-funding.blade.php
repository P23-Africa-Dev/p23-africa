@extends('layouts.master')
@section('title', 'Ultimate Funding & Investor Directory for African Entrepreneurs | P23 Africa')

@section('meta')
    <meta name="description"
        content="Access the most complete Africa-focused funding and investor directory. Find active investors, global grant programs, and funding opportunities designed specifically for African entrepreneurs.">
    <meta name="keywords"
        content="funding directory, investor directory, African investors, startup funding, venture capital Africa, grant programs, angel investors, African entrepreneurs, funding opportunities, investor database">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Ultimate Funding & Investor Directory for African Entrepreneurs | P23 Africa">
    <meta property="og:title" content="Ultimate Funding & Investor Directory | P23 Africa">
    <meta property="og:description"
        content="Direct access to active investors, global grant programs, and funding opportunities. The ultimate directory curated for African entrepreneurs seeking the right capital.">
    <meta property="og:image" content="{{ asset('img/desktop-fund.png') }}">
    <meta property="og:type" content="website">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Flutterwave Payment Script -->
    
@endsection

{{--  --}}



@section('content')
    <!-- Success Alert for Payment -->
    <div id="paymentSuccessAlert" class="alert alert-success"
        style="display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 90%; max-width: 500px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2" style="color: #28a745; font-size: 1.2rem;"></i>
            <div>
                <strong>Payment Successful!</strong><br>
                <small>The Ultimate Funding Directory has been sent to your email.</small>
            </div>
            <button type="button" class="btn-close ms-auto"
                onclick="document.getElementById('paymentSuccessAlert').style.display='none'"></button>
        </div>
    </div>

    <section class="intro-sales funding-ebook">
        <div class="sales-container">
            <div class="intro-container">
                <div class="intro-content">
                    <h1>
                        The Ultimate Funding Directory for <span>African Entrepreneurs</span>
                    </h1>

                    <p>
                        The most complete, Africa-focused funding and investor directory, curated to help entrepreneurs find
                        the right capital faster.
                    </p>

                    <a href="{{ locale_route('playbook.checkout') }}" class="cta-btn">Download Now</a>
                </div>

                <div class="intro-image desktop">
                    <img src="{{ asset('/img/desktop-fund.png') }}" loading="lazy" alt="Entrepreneur using laptop" />

                </div>
                <div class="intro-image mobile">
                    <img src="{{ asset('/img/mobile-fund.png') }}" loading="lazy" alt="Entrepreneur using mobile" />
                </div>
            </div>
        </div>
    </section>

    <section class="book-section">
        <div class="sales-container">
            <div class="book-container">
                <div class="book-header">
                    <h3>What you’ll Find in Our Funding Directory</h3>
                    <p>
                        You’ve prepared your business. Now it’s time to find the right partners to fund your growth.
                    </p>
                </div>

                <div class="book-cards">
                    <div class="book-card card-1">
                        <div class="card-image">
                            <img src="{{ asset('./img/fund-1.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                    <div class="book-card card-2">
                        <div class="card-image">
                            <img src="{{ asset('./img/fund-2.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                    <div class="book-card card-3" id="getGuide">
                        <div class="card-image">
                            <img src="{{ asset('./img/fund-3.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                </div>

                <p class="closing-text">
                    This directory gives you direct access to active investors, global grant programs, and funding
                    opportunities designed for African entrepreneurs.
                </p>

                <a href="{{ locale_route('playbook.checkout') }}" class="cta-btn">Get Access to the Funding Directory</a>
            </div>
        </div>
    </section>

    <section class="premium-section">
        <div class="sales-container ">
            <div class="content">
                <div class="text-content">
                    <div class="text-content-upper">
                        <h2>Get Access to the Ultimate Funding & Investor Directory</p>

                            <a href="{{ locale_route('playbook.checkout') }}" class="learn-more">Get your Funding Directory
                                Here!</a>
                    </div>

                </div>
                <div class="info">
                    <div class="info-container">
                        <h3>“Before this directory, finding serious investors was guesswork. Now I know exactly who to
                            approach.”</h3>

                        <p>Amina K, Founder of a HealthTech Startup, Nigeria</p>

                    </div>


                </div>
            </div>
        </div>
    </section>
@endsection
