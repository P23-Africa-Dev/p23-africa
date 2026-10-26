@extends('layouts.master')
@section('title', 'Funding Readiness Guidebook E-Book | P23 Africa')

@section('meta')
    <meta name="description"
        content="Download your free Funding Readiness Guidebook. A step-by-step guide to becoming investor-ready, built specifically for African entrepreneurs seeking funding or sustainable growth.">
    <meta name="keywords"
        content="funding readiness, investor ready, African entrepreneurs, startup funding, pitch deck, business funding guide, investor directory, African startups, entrepreneurship guide">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Funding Readiness Guidebook for African Entrepreneurs | P23 Africa">
    <meta property="og:title" content="Funding Readiness Guidebook | P23 Africa">
    <meta property="og:description"
        content="Master your business story, understand your numbers, and pitch with confidence. Get your free guide to attract investors or grow sustainably.">
    <meta property="og:image" content="{{ asset('img/mobile.png') }}">
    <meta property="og:type" content="website">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

<!--   -->



@section('content')
    <section class="intro-sales">
        <div class="sales-container">
            <div class="intro-container">
                <div class="intro-content">
                    <h1>
                        Funding<br />
                        Readiness<br />
                        <span>Guidebook</span>
                    </h1>

                    <div class="intro-info">
                        <p>
                            A step-by-step guide to becoming investor-ready, built for
                            African entrepreneurs.
                        </p>

                        <a href="" class="cta-btn open-modal-btn">Download Now</a>
                    </div>


                </div>

                <div class="intro-image desktop">
                    <img src="{{ asset('./img/desktop.png') }}" loading="lazy" alt="Entrepreneur using laptop" />
                </div>
                <div class="intro-image mobile">
                    <img src="{{ asset('./img/mobile.png') }}" loading="lazy" alt="Entrepreneur using laptop" />
                </div>
            </div>
        </div>
    </section>

    <section class="book-section">
        <div class="sales-container">
            <div class="book-container">
                <div class="book-header">
                    <h3>About the Book</h3>
                    <p>
                        Funding is limited and competitive in Africa. This guide helps you
                        master your business story, understand your numbers, and pitch
                        with confidence — so you can attract the right investors or grow
                        sustainably without them.
                    </p>
                </div>

                <div class="book-cards">
                    <div class="book-card card-1">
                        <div class="card-image">
                            <img src="{{ asset('./img/card3.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                    <div class="book-card card-2">
                        <div class="card-image">
                            <img src="{{ asset('./img/card2.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                    <div class="book-card card-3" id="getGuide">
                        <div class="card-image">
                            <img src="{{ asset('./img/card1.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                </div>

                <p class="closing-text">
                    Walk away with a ready-to-use pitch deck, funding plan, and clarity
                    on your next move.
                </p>

                <a href="" class="cta-btn open-modal-btn">Get My Free Guide Now</a>
            </div>
        </div>
    </section>




    <section class="premium-section">
        <div class="sales-container">
            <div class="content">
                <div class="text-content">
                    <div class="text-content-upper">
                        <h2>When You’re Ready to Raise...</h2>
                        <p>Get Access to the Ultimate Funding & Investor Directory</p>
                    </div>
                    <div class="text-content-below">
                        <p>
                            Once you’ve completed your free guide, take the next step.
                            <img src="{{ asset('./img/arrow-right.png') }}" alt="" />
                        </p>
                        <!-- <span></span> -->
                    </div>
                </div>
                <div class="info">
                    <div class="info-container">
                        <h3>Our premium Funding Directory includes:</h3>
                        <div class="info-headings-container">
                            <div class="info-headings">
                                <div>
                                    <h5>40+</h5>
                                    <span> African countries <br> covered </span>
                                </div>
                                <div>
                                    <h5>100+</h5>
                                    <span> Global grant funders/VC investors </span>
                                </div>
                            </div>
                            <ul>
                                <li>Verified investors actively funding African startups</li>
                                <li>
                                    Bonus: Investor-ready templates and funding strategy
                                    planners
                                </li>
                            </ul>
                        </div>
                    </div>

                    <a href="#" class="learn-more">Learn More About the Investor Directory</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FORM MODAL -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-box">
            <h3>Kindly Input Your Details Below<br />And Proceed To Download</h3>

            <div class="success-message" id="successMessage"></div>

            <form id="downloadForm">
                @csrf

                <div class="modal-form-fields">
                    <div class="modal-input-fields">
                        <div>
                            <input type="text" name="name" placeholder="Enter Full name" required />
                            <div class="error-message" id="nameError"></div>
                        </div>

                        <div>
                            <input type="email" name="email" placeholder="Enter Email" required />
                            <div class="error-message" id="emailError"></div>
                        </div>
                    </div>

                    <div class="modal-input-fields">
                        <div>
                            <input type="text" name="company" placeholder="Company name" required />
                            <div class="error-message" id="companyError"></div>
                        </div>

                        <div>
                            <input type="text" name="phone" placeholder="Phone Number" required />
                            <div class="error-message" id="phoneError"></div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="form-btn" id="submitBtn">
                    <span class="btn-text">Download Now</span>
                    <span class="spinner" style="display: none;">
                        <div
                            style="display: inline-block; width: 16px; height: 16px; border: 2px solid transparent; border-top: 2px solid white; border-radius: 50%; animation: spin 1s linear infinite;">
                        </div>
                    </span>
                </button>
            </form>

            <button class="close-modal" id="closeModal"></button>
        </div>
    </div>

    <!-- SUCCESS POPUP -->
    <div class="success-overlay" id="successOverlay"></div>
    <div class="success-popup" id="successPopup">
        <div class="success-icon">✓</div>
        <h4>Success!</h4>
        <p id="successPopupMessage">Funding Readiness Guide will be sent to your email shortly!</p>
        <button class="popup-close-btn" id="closeSuccessPopup">Got it!</button>
    </div>
@endsection



