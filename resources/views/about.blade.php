@extends('layouts.master')
@section('title', 'About | P23 Africa')


@section('content')
    <section class="hero-section animate-on-scroll">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="hero-content-block">
                <div class="left-block">
                    <!-- Visual Line -->
                    <div class="line-connector"></div>

                    <!-- Highlighted Box -->

                    <div class="main-box">
                        <h6>KNOW ABOUT US</h6>
                        <h1>EMPOWERING BUSINESSES.<br>TRANSFORMING MARKETS</h1>
                    </div>

                    <!-- Subdescription -->
                    <p class="descriptions">
                        {{-- <p class="descriptions"> --}}
                        <b>Africa-focused, globally relevant</b> <br> P23 Africa are a team of seasoned professionals
                        helping businesses across Africa scale in a competitive and rapidly-changing market.
                    </p>

                    <!-- Contact Button -->
                    <div class="c-btn">

                        <a href="{{ url('contact') }}" class="contact-btn">
                            Contact Us <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                {{-- <div class="right-block text-left" > --}}
                <div class="text-left">
                    <!-- Secondary Label -->
                    <div class="info-tag">
                        EXPANSION. BRAND. SALES
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="mission_vision" class="animate-on-scroll">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-md-0 mb-5">
                    <h5>Our Mission <span></span></h5>
                    <strong class="my-2">Business Empowerment</strong>
                    <p>
                        Our mission is to empower African businesses to thrive and innovate through strategic guidance and transformative solutions. We are committed to fostering sustainable growth, driving impactful change, and unlocking the full potential of businesses across the continent.
                    </p>
                </div>
                <div class="col-md-6">
                    <div id="h5">
                        <h5>Our Vision <span></span></h5>
                    </div>
                    <strong class="my-2">Excellence, Integrity, and Collaboration</strong>
                    <p>
                        With a vision rooted in excellence, integrity, and collaboration, we aspire to be the catalyst for Africa's economic advancement, empowering clients to navigate challenges, seize opportunities, and shape a prosperous future for generations to come.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="growth-section animate-on-scroll">
        <div class="container">
            <h5 class="my-auto"><img src="{{ asset('images/line.png') }}" alt=""> Our Three pillars to ensure success</h5>
            <h2>Empowering your growth <br> through;</h2>
        </div>
    </section>


    <div class="container growth-container animate-on-scroll">
        <div class="row justify-content-center g-2">
            <div class="col-md-4">
                <img src="{{ asset('images/knowledge_expert.png') }}" alt="">
                {{-- <div class="growth-card text-center">
                    <img src="{{ asset('images/icon_mark.png') }}" alt="Chioma Ike">
                    <h5>The Knowledge of Experts</h5>
                    <p>
                        Tap into decades of combined experience from industry specialists. We provide strategic insights tailored to your unique business journey.
                    </p>
                </div> --}}
            </div>
            <div class="col-md-4">
                <img src="{{ asset('images/simple_execution.png') }}" alt="">
                {{-- <div class="growth-card text-center">
                    <img src="{{ asset('images/icon_bulb.png') }}" alt="Charles">
                    <h5>Simple Execution</h5>
                    <p>
                        We strip away complexity to deliver clear, actionable steps. Our streamlined processes turn big ideas into tangible outcomes.
                    </p>
                </div> --}}
            </div>
            <div class="col-md-4">
                <img src="{{ asset('images/practical_guidance.png') }}" alt="">
                {{-- <div class="growth-card text-center">
                    <img src="{{ asset('images/icon_technical-support-2.png') }}" alt="Family Guy">
                    <h5>Practical Guidance</h5>
                    <p>
                        Beyond theory, we walk with you through implementation. Expect hands-on support to navigate real-world challenges with confidence.
                    </p>
                </div> --}}
            </div>
        </div>
    </div>


    <section class="commitment animate-on-scroll">
        <div class="container py-5">
            <div class="row g-0 align-items-stretch">

                <img class="img-fluid" src="{{ asset('images/Commitment.png') }}" alt="">
                <img class="img-fluid2" src="{{ asset('images/about_frame.png') }}" alt="">
                {{-- <div class="col-lg-6 commitment-box">
                <div class="py-4 px-2">
                    <h4>OUR COMMITMENT</h4>
                    <p class="mt-4">
                        With a commitment to excellence, integrity, and collaboration, we partner closely with our clients to understand their unique needs, goals, and aspirations. Through personalised consulting services, actionable insights, and hands-on support, we strive to empower businesses and entrepreneurs to thrive in dynamic and competitive markets.                    
                    </p>
                </div>
              </div> --}}

                {{-- <div class="col-lg-6 position-relative">
                <img src="{{ asset('images/about.jpg') }}" alt="Team discussion" class="team-image" />
        
                <div class="overlay-card">
                  <p>Expansion, Brand, Sales</p>
                  <br><br>
                  <p><strong>P23 Africa.</strong></p>
                </div>
              </div> --}}
            </div>
        </div>
    </section>


    <!-- Stats Section -->
    <section class="px-5 animate-on-scroll">
        <div class="stats-section3">
            <div class="">
                <div id="stats_img_desktop">
                    <img src="{{ asset('images/about-desktop.png') }}" alt="Card image">
                </div>
                <div id="stats_img_mobile">
                    <img src="{{ asset('images/about-mobile.png') }}" alt="Card image">
                </div>
            </div>
        </div>
    </section>
@endsection
