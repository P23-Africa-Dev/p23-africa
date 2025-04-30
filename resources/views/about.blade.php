@extends('layouts.master')
@section('title', 'About | P23 Africa')


@section('content')
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="hero-content-block" >
                <div class="left-block" >
                    <!-- Visual Line -->
                    <div class="line-connector"></div>

                    <!-- Highlighted Box -->
                    {{-- <div class="main-box"> --}}
                    <div class="">
                        <h6>KNOW ABOUT US</h6>
                        <h1><b>EMPOWERING BUSINESSES.<br>TRANSFORMING MARKETS</b></h1>
                    </div>

                    <!-- Subdescription -->
                    <p class="">
                    {{-- <p class="descriptions"> --}}
                        P23 Africa is a team of seasoned business consultants dedicated to helping SMEs and entrepreneurs thrive in
                        competitive markets across Africa and the UK.
                    </p>

                    <!-- Contact Button -->
                    <a href="#contact" class="">
                    {{-- <a href="#contact" class="contact-btn"> --}}
                        Contact Us <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                {{-- <div class="right-block text-left" > --}}
                <div class=" text-left" >
                    <!-- Secondary Label -->
                    <div class="">
                        EXPANSION, BRAND, SALES
                    </div>
                </div>
            </div>
        </div>
    </section>

   <section id="mission_vision">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Our Mission</h5>
                    <strong>Business Empowerment</strong>
                    <p>
                        To empower businesses with the knowledge, resources, and strategies they need to scale successfully. We are dedicated to helping companies of all sizes achieve sustainable growth, streamline operations, and successfully enter new markets.
                    </p>
                </div>
                <div class="col-md-6">
                    <div id="h5">
                        <h5>Our Vision</h5>
                    </div>
                    <strong>Business Empowerment</strong>
                    <p>
                        To empower businesses with the knowledge, resources, and strategies they need to scale successfully. We are dedicated to helping companies of all sizes achieve sustainable growth, streamline operations, and successfully enter new markets.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{--  <section class="growth-section">
        <div class="container">
            <h5>Our Three pillars to ensure success</h5>
            <h2>Empowering your growth <br> through;</h2>
        </div>
    </section>

    
    <div class="container growth-container">
        <div class="row justify-content-center g-4">
            <div class="col-md-4">
                <div class="growth-card text-center">
                    <img src="{{ asset('images/icon_mark.png') }}" alt="Chioma Ike">
                    <h5>The Knowledge of Experts</h5>
                    <p>
                        Tap into decades of combined experience from industry specialists. We provide strategic insights tailored to your unique business journey.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="growth-card text-center">
                    <img src="{{ asset('images/icon_bulb.png') }}" alt="Charles">
                    <h5>Simple Execution</h5>
                    <p>
                        We strip away complexity to deliver clear, actionable steps. Our streamlined processes turn big ideas into tangible outcomes.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="growth-card text-center">
                    <img src="{{ asset('images/icon_technical-support-2.png') }}" alt="Family Guy">
                    <h5>Practical Guidance</h5>
                    <p>
                        Beyond theory, we walk with you through implementation. Expect hands-on support to navigate real-world challenges with confidence.
                    </p>
                </div>
            </div>
        </div>
    </div>


    <section class="commitment">
        <div class="container py-5">
            <div class="row g-0 align-items-stretch">

                <img src="{{ asset('images/Commitment.png') }}" alt="">
              <div class="col-lg-6 commitment-box">
                <div class="py-4 px-5">
                    <h4>OUR COMMITMENT</h4>
                    <p class="mt-4">
                        With a commitment to excellence, integrity, and collaboration, we partner closely with our clients to understand their unique needs, goals, and aspirations. Through personalised consulting services, actionable insights, and hands-on support, we strive to empower businesses and entrepreneurs to thrive in dynamic and competitive markets.                    
                    </p>
                </div>
              </div>
        
              <div class="col-lg-6 position-relative">
                <img src="{{ asset('images/about.jpg') }}" alt="Team discussion" class="team-image" />
        
                <div class="overlay-card">
                  <p>Expansion, Brand, Sales</p>
                  <br><br>
                  <p><strong>P23 Africa.</strong></p>
                </div>
              </div>
            </div>
          </div>
    </section>


        <section class="container stats-about">
            <div class="stats-section-about">
                <div class="container me-auto ms-auto w-50">
                    <div class="stats-container">
                        <h1>55%</h1>
                        <div class="line-down"></div>
                    </div>
                    <p class="mx-4">of consumers learn about <br> brands or companies on <br> social media</p>
                    <div class="row justify-content-center sub-stats text-center mt-2 mx-4">
                        <div class="col-md-3">
                            <h4>73%</h4>
                            <p>Start-ups</p>
                        </div>
                        <div class="col-md-3">
                            <h4>73%</h4>
                            <p>MSMEs</p>
                        </div>
                        <div class="col-md-3">
                            <h4>73%</h4>
                            <p>Enterprises</p>
                        </div>
                    </div>
                </div>
            </div>
        </section> --}}
@endsection
