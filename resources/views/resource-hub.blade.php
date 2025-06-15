@extends('layouts.res-layout')
@section('title', 'Resource Hub | P23 Africa')

@section('content')
    <!-- Blog Section -->
    <section class="blog-section">
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
@endsection

@include('include.resourse-js')