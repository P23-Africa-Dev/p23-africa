@extends('layouts.master')
@section('title', 'Business Consulting & Growth Solutions for Africa | P23 Africa')

@section('content')
    <header id="body">
        <div id="header">
            <section class="hero content-section animate-on-scroll">
                <div class="row">
                    <div class="col-md-7 hero-text my-auto">
                        <h1>Driving <span id="font">Successful</span><br><span id="buss">Businesses Across</span><br><img src="{{ asset('images/dot.png') }}"
                                id="dot" alt=""><span id="font"> Africa</span></h1>
                        <br>
                        <p>
                            We create tailored solutions for homegrown and global businesses at every stage of growth.
                        </p>
                        <br>
                        <a href="{{ url('contact') }}" class="">Contact Us →</a>
                    </div>
                    <div class="col-md-5 animated-images mt-5 mt-md-0">
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

    <div style="background-color: #003c32;" id="space">

    </div>
    <section id="highlight" class="position-relative overflow-hidden content-section animate-on-scroll d-flex align-items-center justify-content-center">
        <div class="highlight-overlay position-absolute top-0 start-0"></div>
        <div class="me-auto ms-auto" style="width: 65%;">
            <h3>
                We work with entrepreneurs at any stage, helping them build thriving businesses that solve problems and transform Africa.
            </h3>
        </div>
    </section>
    <div style="background-color: #003c32;" class="py-3">

    </div>


    <section class="container-fluid p-0 who-we-are content-section animate-on-scroll">
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
                    To build a business in Africa, you need more than talent. You need support.
                </p>
                <p class="mb-4">
                    At P23 Africa, we are a team of seasoned business consultants with a passion for empowering businesses in Africa to thrive in competitive markets.
                </p>
                <div>
                    <a href="{{ url('about') }}" class="text-decoration-none learn-more">
                        <span>Learn more</span>
                        <span class="arrow ms-2 d-inline-flex align-items-center justify-content-center">
                            ➔
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="how_we_help" class="content-section animate-on-scroll">
        <div class="py-5 content">
            <h2 class="section-title">How We Help Our Clients</h2>
            <div class="custom-underline"></div>

            <div class="row g-5">
                <div class="col-md-8">
                    <div class="row g-5">
                        <div class="col-md-6">
                            <div class="service-card" id="one">
                                <div class="service-icon">
                                    {{-- <i class="bi bi-signpost-2-fill"></i> --}}
                                    <img src="{{ asset('images/strategy.png') }}" alt="">
                                </div>
                                <div class="service-title">Business Strategy</div>
                                <p>We develop detailed and practical strategies to solve business problems and drive organisational goals</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-card" id="two">
                                <div class="service-icon">
                                    {{-- <i class="bi bi-tags-fill"></i> --}}
                                    <img src="{{ asset('images/sales.png') }}" alt="">
                                </div>
                                <div class="service-title">Marketing and Sales</div>
                                <p>We build and execute brand awareness, lead generation and conversion & retention strategies for businesses</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="service-card" id="three">
                                <div class="service-icon">
                                    {{-- <i class="bi bi-activity"></i> --}}
                                    <img src="{{ asset('images/operation.png') }}" alt="">
                                </div>
                                <div class="service-title">Operations and Efficiency</div>
                                <p>
                                    We build systems and processes to drive operational efficiency and output for businesses. We aid in the development of strategic plan and operational support tailored to your business goals & objectives.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expansion Column -->
                <div class="col-md-4 d-flex">
                    <div class="service-card d-flex flex-column w-100" id="four">
                        <div class="box-four">
                            <div class="service-icon">
                                {{-- <i class="bi bi-fullscreen"></i> --}}
                                <img src="{{ asset('images/expansion.png') }}" alt="">
                            </div>
                            <div class="service-title">Expansion</div>
                            <p>At P23 Africa, we offer low-risk market entry and expansion services for all businesses looking to break into new markets</p>
                        </div>
                        <div id="read">
                            <a href="{{ url('services') }}" class="read-more-btn mt-3">
                                Read More <i class="bi bi-arrow-right mx-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <a href="{{ url('services') }}" class="read-more-btn-2 mt-3">
                    Read More <i class="bi bi-arrow-right"></i>
                </a>
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

    <section class="testimonial-section content-section animate-on-scroll">
        <div class="container">
            <h2>What Our Client Say About Us</h2>
        </div>
    </section>
    {{-- <img src="{{ asset('images/frame-bottom.png') }}" alt=""> --}}

    <div class="testimonial-section-main">
        <div class="container testimonial-container">
            <div class="testimonial-wrapper">
                <div class="testimonial">
                    <div class="testimonial-card text-center">
                        <img src="{{ asset('images/testimonial01.png') }}" alt="Chioma Ike">
                        <p>
                            Working with this team was a game-changer. Their insights and strategies helped us grow and navigate new markets with confidence.
                        </p>
                        <h5><b>Chioma Chuwku</b></h5>
                        <h4>C.E.0</h4>
                    </div>
                </div>
                <div class="testimonial">
                    <div class="testimonial-card text-center">
                        <img src="{{ asset('images/testimonial3.png') }}" alt="Charles">
                        <p>
                            Their expertise and tailored approach transformed our business operations and boosted our efficiency. Highly recommended.                        
                        </p>
                        <h5><b>John Blakey</b></h5>
                        <h4>C.E.0</h4>
                    </div>
                </div>
                <div class="testimonial">
                    <div class="testimonial-card text-center">
                        <img src="{{ asset('images/testimonial03.png') }}" alt="Family Guy">
                        <p class="">
                            A true partner in our success. They provided the guidance we needed to scale our business while overcoming challenges.
                        </p>
    
                        <h5><b>Emily Nguyen</b></h5>
                        <h4>Managing Director</h4>  
                    </div>
                </div>
            </div>

            <div class="slider-controls">
                <span class="arrow left"><i><img src="{{ asset('images/left.png') }}" alt=""></i></span>
                <span class="arrow right"><i><img src="{{ asset('images/right.png') }}" alt=""></i></span>
            </div>
        </div>
    </div>
    <hr id="horiz">

    <section class="insight-section content-section animate-on-scroll">
        <div class="">
            <h2>P23 Insights</h2>
            <div class="row insight-content">
                <div class="col-md-6 insight-image-wrapper">
                    <img src="{{ asset('images/insight-side.jpg') }}" alt="Scolding Donald" class="insight-image" />
                    <div class="bottom-bar"></div>
                </div>
                <div class="col-md-6 insight-text">
                    <p class="date">1st May 2025</p>
                    <h3>Essential Guide to Doing Business in Africa</h3>
                    <p class="description">
                        Our essential guide helps foreign investors navigate Africa’s diverse markets, build local partnerships, and understand regulations, offering practical strategies for sustainable success.
                    </p>
                    <a href="#" class="read-more">Read More <span>→</span></a>
                </div>
            </div>
        </div>
    </section>

    <section class="carousel-container content-section animate-on-scroll">
        <div class="slider">
            <!-- Main Set -->
            <div class="insight-card">
                <img src="{{ asset('images/funding.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">30th April 2025</div>
                    <h5>State of Funding 2025</h5>
                    <p>Our solutions are designed to be flexible and performance focused. They can be delivered in-person, or more commonly</p>
                </div>
            </div>

            <div class="insight-card">
                <img src="{{ asset('images/girl.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">1st May 2025</div>
                    <h5>Understanding buyer habits</h5>
                    <p>Our solutions are designed to be flexible and performance focused. They can be delivered in-person, or more commonly</p>
                </div>
            </div>

            <div class="insight-card">
                <img src="{{ asset('images/plant.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">2nd May 2025</div>
                    <h5>Top Agri sectors to watch</h5>
                    <p>Our solutions are designed to be flexible and performance focused. They can be delivered in-person, or more commonly</p>
                </div>
            </div>

            <div class="insight-card">
                <img src="{{ asset('images/carousel00.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">5th May 2025</div>
                    <h5>Business trends 2025</h5>
                    <p>Business trends in Africa are evolving rapidly, with growth in digital transformation, green energy, and emerging markets driving new opportunities across the continent.</p>
                </div>
            </div>

            <!-- Duplicate Set for Seamless Loop -->
            <div class="insight-card">
                <img src="{{ asset('images/funding.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">30th April 2025</div>
                    <h5>State of Funding 2025</h5>
                    <p>Our solutions are designed to be flexible and performance focused. They can be delivered in-person, or more commonly</p>
                </div>
            </div>

            <div class="insight-card">
                <img src="{{ asset('images/girl.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">1st May 2025</div>
                    <h5>Understanding buyer habits</h5>
                    <p>Our solutions are designed to be flexible and performance focused. They can be delivered in-person, or more commonly</p>
                </div>
            </div>

            <div class="insight-card">
                <img src="{{ asset('images/plant.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">2nd May 2025</div>
                    <h5>Top Agri sectors to watch</h5>
                    <p>Our solutions are designed to be flexible and performance focused. They can be delivered in-person, or more commonly</p>
                </div>
            </div>

            <div class="insight-card">
                <img src="{{ asset('images/carousel00.jpg') }}" alt="Card image">
                <div class="card-body">
                    <div class="date">5th May 2025</div>
                    <h5>Business Trends 2025</h5>
                    <p>Business trends in Africa are evolving rapidly, with growth in digital transformation, green energy, and emerging markets driving new opportunities across the continent.</p>
                </div>
            </div>
        </div>

        <div id="btn">
            <a href="#" class="btn">More on the Resource Hub</a>
        </div>
    </section>
    <hr id="horiz">

    <!-- Stats Section -->
    <section class="px-4 content-section animate-on-scroll">
        <div class="stats-section2">
            <div class="">
                <div id="stats_img_desktop">
                    <img src="{{ asset('images/frame_desktop.png') }}" alt="Card image">
                </div>
                <div id="stats_img_mobile">
                    <img src="{{ asset('images/frame_mobile.png') }}" alt="Card image">
                </div>
            </div>
        </div>
    </section>


    <script>
  $(function () {
    const $wrapper = $('.testimonial-wrapper');
    const $testimonials = $('.testimonial');
    const total = $testimonials.length;
    let currentIndex = 0;

    function updateWidths() {
      const screenWidth = $(window).width();
      if (screenWidth < 580) {
        // Set for small screens
        $wrapper.css('width', `${total * 100}%`);
        $testimonials.css('width', `${100 / total}%`);
      } else {
        // Set for larger screens (still full width per testimonial)
        $wrapper.css('width', `100%`);
        $testimonials.css('width', `100%`);
      }
      // Reposition to current index after width change
      updateSlider(currentIndex);
    }

    function updateSlider(index) {
      const offset = -index * (100 / total);
      $wrapper.css('transform', `translateX(${offset}%)`);
    }

    $('.arrow.left').click(function () {
      currentIndex = (currentIndex - 1 + total) % total;
      updateSlider(currentIndex);
    });

    $('.arrow.right').click(function () {
      currentIndex = (currentIndex + 1) % total;
      updateSlider(currentIndex);
    });

    // Init on load
    updateWidths();

    // Recalculate on window resize
    $(window).resize(function () {
      updateWidths();
    });
  });
    </script>
@endsection
