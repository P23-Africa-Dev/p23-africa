@extends('layouts.master')
@section('title', 'Business Consulting & Growth Solutions for Africa | P23 Africa')

@section('content')
    <header id="body">
        <div id="header">
            <section class="hero">
                <div class="row">
                    <div class="col-md-7 hero-text my-auto">
                        <h1>Driving <span>Successful</span><br>Businesses Across<br><img src="{{ asset('images/dot.png') }}" id="dot" alt=""><span> Africa</span></h1>
                        <p>We create tailored solutions for homegrown and global businesses at every stage of growth.
                        </p>
                        <a href="{{ url('contact') }}">Contact Us →</a>
                    </div>  
                    <div class="col-md-5 animated-images">
                        <div class="fade-overlay fade-top"></div>
                        <div class="fade-overlay fade-bottom"></div>

                        <div class="column">
                            @foreach ($images as $image)
                                <img src="{{ asset('images/' . $image) }}" class="" alt="header-slider">
                            @endforeach

                            @foreach ($images as $image)
                                <img src="{{ asset('images/' . $image) }}" class="" alt="header-slider">
                            @endforeach
                        </div>
                        <div class="column2">
                            @foreach ($images2 as $image2)
                                <img src="{{ asset('images/' . $image2) }}" class="" alt="header-slider">
                            @endforeach

                            @foreach ($images2 as $image2)
                                <img src="{{ asset('images/' . $image2) }}" class="" alt="header-slider">
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </header>

    <div style="background-color: #003c32;" class="py-3">

    </div>
    <section id="highlight" class="position-relative overflow-hidden d-flex align-items-center justify-content-center">
        <div class="highlight-overlay position-absolute top-0 start-0"></div>
        <div class="me-auto ms-auto" style="width: 65%;">
            <h3>
                We collaborate across industries to empower founders in scaling their start-ups into transformative,
                world-changing enterprises.
            </h3>
        </div>
    </section>
    <div style="background-color: #003c32;" class="py-3">

    </div>


    <section class="container-fluid p-0 who-we-are">
        <div class="row g-0">
            <!-- Left Column -->
            <div class="col-md-6 position-relative overflow-hidden d-flex align-items-center justify-content-center">
                <div class="bg-overlay position-absolute top-0 start-0"></div>
                <img src="{{ asset('images/about.jpg') }}" alt="People"
                    class="img-fluid position-relative z-2 p-3 p-md-5">
            </div>

            <!-- Right Column -->
            <div class="col-md-6 d-flex flex-column justify-content-center" style="">
                <h2 class="fw-bold mb-4">Who We Are</h2>
                <p class="mb-3">
                    Building a successful business in Africa requires reliable support.
                    Whether you’re optimizing your processes, growing your team, or improving sales,
                </p>
                <p class="mb-4">
                    P23 Africa are a team of seasoned business consultants with a passion for empowering businesses
                    in
                    Africa to thrive in competitive markets.
                </p>
                <div>
                    <a href="#" class="btn btn-link text-decoration-none learn-more">
                        <span>Learn more</span>
                        <span class="arrow ms-2 d-inline-flex align-items-center justify-content-center">
                            ➔
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="how_we_help">
        <div class="container py-5">
            <h2 class="section-title">How we help our clients</h2>
            <div class="custom-underline"></div>

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="service-card" id="one">
                                <div class="service-icon"><i class="bi bi-signpost-2-fill"></i></div>
                                <div class="service-title">Business Strategy</div>
                                <p>We aid in the development of strategic plan and operational support tailored to
                                    your business goals & objectives.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-card" id="two">
                                <div class="service-icon"><i class="bi bi-tags-fill"></i></div>
                                <div class="service-title">Marketing and Sales</div>
                                <p>We aid in the development of strategic plan and operational support tailored to
                                    your business goals & objectives.</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="service-card" id="three">
                                <div class="service-icon"><i class="bi bi-activity"></i></div>
                                <div class="service-title">Operations and Efficiency</div>
                                <p>
                                    We aid in the development of strategic plan and operational support tailored to
                                    your business goals & objectives.
                                    We aid in the development of strategic plan and operational support tailored to
                                    your business goals & objectives.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expansion Column -->
                <div class="col-md-4 d-flex">
                    <div class="service-card d-flex flex-column justify-content-between w-100" id="four">
                        <div>
                            <div class="service-icon"><i class="bi bi-fullscreen"></i></div>
                            <div class="service-title">Expansion</div>
                            <p>We specialize in offering low-risk market entry services that eliminate the need for
                                physical setups and compliance hassles in the region.</p>
                        </div>
                        <div>
                            <a href="#" class="read-more-btn mt-3">
                                Read More <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="global_reach">
            <h2><strong>Global Reach.</strong> <span>Local Approach</span></h2>

            <div class="row justify-content-center text-center stats me-auto ms-auto mt-5">
                <div class="col-6 col-md-3 stat-box">
                    <div class="number" id="countries"></div>
                    <div class="label">Countries</div>
                </div>
                <div class="col-6 col-md-3 stat-box">
                    <div class="number" id="businesses"></div>
                    <div class="label">Businesses</div>
                </div>
                <div class="col-6 col-md-3 stat-box">
                    <div class="number" id="partnership"></div>
                    <div class="label">Partnership</div>
                </div>
                <div class="col-6 col-md-3 stat-box">
                    <div class="number" id="network"></div>
                    <div class="label">Expert Network</div>
                </div>
            </div>
        </div>
    </section>


    {{-- <section class="testimonial-section">
            <h2>What our Client say about us</h2>
          
            <div class="container mt-5">
              <div class="row justify-content-center g-4">
                <div class="col-md-4">
                  <div class="testimonial-card text-center">
                    <img src="https://randomuser.me/api/portraits/women/1.jpg" alt="Chioma Ike">
                    <h5>Chioma Ike</h5>
                    <p>We specialize in offering low-risk market entry services that eliminate the need for physical setups and compliance hassles in the region.</p>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="testimonial-card text-center">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Charles">
                    <h5>Charles</h5>
                    <p>We specialize in offering low-risk market entry services that eliminate the need for physical setups and compliance hassles in the region.</p>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="testimonial-card text-center">
                    <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Family Guy">
                    <h5>Family Guy</h5>
                    <p>We specialize in offering low-risk market entry services that eliminate the need for physical setups and compliance hassles in the region.</p>
                  </div>
                </div>
              </div>
            </div>
          </section> --}}

    <section class="testimonial-section">
        <div class="container">
            <h2>What our Client say about us</h2>
        </div>
    </section>

    <div class="container testimonial-container">
        <div class="row justify-content-center g-4">
            <div class="col-md-4">
                <div class="testimonial-card text-center">
                    <img src="{{ asset('images/testimonial1.jpg') }}" alt="Chioma Ike">
                    <h5>Chioma Ike</h5>
                    <p>We specialize in offering low-risk market entry services that eliminate the need for physical
                        setups and compliance hassles in the region.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card text-center">
                    <img src="{{ asset('images/testimonial2.jpg') }}" alt="Charles">
                    <h5>Charles</h5>
                    <p>We specialize in offering low-risk market entry services that eliminate the need for physical
                        setups and compliance hassles in the region.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card text-center">
                    <img src="{{ asset('images/testimonial3.jpg') }}" alt="Family Guy">
                    <h5>Family Guy</h5>
                    <p>We specialize in offering low-risk market entry services that eliminate the need for physical
                        setups and compliance hassles in the region.</p>
                </div>
            </div>
        </div>
    </div>


    <section class="insight-section">
        <div class="">
            <h2>P23 Insights</h2>
            <div  class="insight-content">
                <div class="insight-image-wrapper">
                    <img src="{{ asset('images/scolding_donald.jpg') }}" alt="Scolding Donald" class="insight-image" />
                    <div class="bottom-bar"></div>
                </div>
                <div class="insight-text">
                    <p class="date">18th Feb 2025</p>
                    <h3>Scolding Donald</h3>
                    <p class="description">
                        Our solutions are designed to be flexible and performance focused. They can be delivered
                        in-person, or more commonly remotely for a more cost effective and flexible solution.
                    </p>
                    <a href="#" class="read-more">Read More <span>→</span></a>
                </div>
            </div>
        </div>
    </section>

    <section class="carousel-container">
        <div class="slider">
            <!-- Main Set -->
            <div class="insight-card card">
                <img src="{{ asset('images/insight1.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">18th Feb 2025</div>
                    <h5>Business Strategies</h5>
                    <p>Our solutions are designed to be flexible and performance focused.</p>
                </div>
            </div>

            <div class="insight-card card">
                <img src="{{ asset('images/insight2.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">18th Feb 2025</div>
                    <h5>Workplace Benefits</h5>
                    <p>Our solutions are designed to be flexible and performance focused.</p>
                </div>
            </div>

            <div class="insight-card card">
                <img src="{{ asset('images/insight3.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">18th Feb 2025</div>
                    <h5>Business Strategies</h5>
                    <p>Our solutions are designed to be flexible and performance focused.</p>
                </div>
            </div>

            <!-- Duplicate Set for Seamless Loop -->
            <div class="insight-card card">
                <img src="{{ asset('images/insight1.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">18th Feb 2025</div>
                    <h5>Business Strategies</h5>
                    <p>Our solutions are designed to be flexible and performance focused.</p>
                </div>
            </div>

            <div class="insight-card card">
                <img src="{{ asset('images/insight2.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">18th Feb 2025</div>
                    <h5>Workplace Benefits</h5>
                    <p>Our solutions are designed to be flexible and performance focused.</p>
                </div>
            </div>

            <div class="insight-card card">
                <img src="{{ asset('images/insight3.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">18th Feb 2025</div>
                    <h5>Business Strategies</h5>
                    <p>Our solutions are designed to be flexible and performance focused.</p>
                </div>
            </div>
        </div>

        <div id="btn">
            <a href="#" class="btn">More on the Resource Hub</a>
        </div>
    </section>


    <!-- Stats Section -->
    <section class="px-5">
        <div class="stats-section">
            <div class="container">
                <div class="stats-container">
                    <h1>63%</h1>
                    <div class="line-down"></div>
                </div>
                <p class="mx-4">of consumers learn about brands or companies on social media</p>
                <div class="row justify-content-center sub-stats text-center mt-5 mx-md-4 mx-2">
                    <div class="col-3">
                        <h4>73%</h4>
                        <p>Start-ups</p>
                    </div>
                    <div class="col-3">
                        <h4>73%</h4>
                        <p>MSMEs</p>
                    </div>
                    <div class="col-3">
                        <h4>73%</h4>
                        <p>Enterprises</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
