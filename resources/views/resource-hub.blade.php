@extends('layouts.res-layout')
@section('title', 'Resource Hub | P23 Africa')

@section('content')
    <section class="whole-resource">
        <!-- Blog Section -->
        <section class="blog-sec">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                        <div class="blog-date">1st May 2025</div>
                        <h2 class="blog-title">Understanding Buyer Habits</h2>
                        <p class="blog-description">
                            Buyer habits in Africa are evolving fast, driven by digital adoption and a growing middle class,
                            but differences across regions and trust issues still pose challenges.
                        </p>
                        <a href="#" class="btn btn-custom mt-4">
                            Read Blog
                            <span>&rarr;</span>
                        </a>
                    </div>
                    <div class="col-lg-6 d-flex justify-content-center">
                        <div class="image-container">
                            <img src="{{ asset('images/resource-header.png') }}" alt="Woman Image" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mind-behind-insight">
            <div class="container py-5">
                <div class="section-title">Minds Behind the Insights</div>

                <!-- Desktop View -->
                <div class="row carousel-desktop">
                    <div class="col-md-4 mb-4">
                        <div class="team-card first">
                            <div class="team-name">Name<br>Surname</div>
                            <img src="{{ asset('images/first-mind.png') }}" alt="Person 1">
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="team-card mid">
                            <div class="team-name">Name<br>Surname</div>
                            <img src="{{ asset('images/second-mind.png') }}" alt="Person 2">
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="team-card">
                            <div class="team-name">Name<br>Surname</div>
                            <img src="{{ asset('images/third-mind.png') }}" alt="Person 3">
                        </div>
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="carousel-mobile">
                    <div class="carousel-container">
                        <div class="carousel-inner-custom" id="carouselSlides">
                            <div class="carousel-item-custom">
                                <div class="team-card">
                                    <div class="team-name">Name<br>Surname</div>
                                    <img src="{{ asset('images/first-mind.png') }}" alt="Person 1">
                                </div>
                            </div>
                            <div class="carousel-item-custom">
                                <div class="team-card mid">
                                    <div class="team-name">Name<br>Surname</div>
                                    <img src="{{ asset('images/second-mind.png') }}" alt="Person 2">
                                </div>
                            </div>
                            <div class="carousel-item-custom">
                                <div class="team-card">
                                    <div class="team-name">Name<br>Surname</div>
                                    <img src="{{ asset('images/third-mind.png') }}" alt="Person 3">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Arrow Buttons -->
                    <div class="arrow-btns">
                        <button class="arrow-btn" onclick="slideLeft()">←</button>
                        <button class="arrow-btn" onclick="slideRight()">→</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="blog">
            <div class="container blog-section">
                <div class="blog-title text-uppercase mb-2">Our Most Recent Blogs</div>
                <div class="blog-subtitle">Explore Expert Articles, Market Trends, And Practical Advice To Scale Your
                    Business
                    Across Africa And Beyond.</div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="blog-card blog-blue">
                            <div class="date">1st May 2025</div>
                            <div class="heading">Understanding Buyer Habits</div>
                            <div class="content">
                                Buyer habits in Africa are evolving fast, often driven by digital access and generational
                                preferences. As more buyers rely on smartphones and digital media, the sales journey has
                                shifted. Today’s buyer seeks education and guidance before making decisions...
                            </div>
                            <button class="read-more-btn">Read More →</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="blog-card blog-green">
                            <div class="date">1st May 2025</div>
                            <div class="heading">Understanding Buyer Habits</div>
                            <div class="content">
                                This is a shorter post to show height flexibility.
                            </div>
                            <button class="read-more-btn">Read More →</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="blog-card blog-pink">
                            <div class="date">1st May 2025</div>
                            <div class="heading">Understanding Buyer Habits</div>
                            <div class="content">
                                Buyer habits in Africa are evolving fast, often driven by digital access and generational
                                preferences. Today’s buyer seeks education and guidance before making decisions. As such,
                                marketers must use targeted content, influencers, and digital tools to remain relevant in
                                Africa’s evolving market landscape.
                            </div>
                            <button class="read-more-btn">Read More →</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="blog-card blog-yellow">
                            <div class="date">1st May 2025</div>
                            <div class="heading">Understanding Buyer Habits</div>
                            <div class="content">
                                Another short version to create visual imbalance across the layout.
                            </div>
                            <button class="read-more-btn">Read More →</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="blog-card blog-gray">
                            <div class="date">1st May 2025</div>
                            <div class="heading">Understanding Buyer Habits</div>
                            <div class="content">
                                Buyer habits in Africa are evolving fast, often driven by digital access and generational
                                preferences. The growth of smartphones and digital payment systems continues to change how
                                African consumers behave. Marketers and business owners need to adapt fast...
                            </div>
                            <button class="read-more-btn">Read More →</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection

@include('include.resourse-js')
