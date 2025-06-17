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
                <div class="row2">
                    <div class="column">
                        <div class="blog-title text-uppercase mb-2">Our Most Recent Blogs</div>
                        <div class="blog-subtitle">Explore Expert Articles, Market Trends, And Practical Advice To Scale
                            Your
                            Business
                            Across Africa And Beyond.</div>

                        @foreach ($blogs as $index => $blog)
                            @if ($index < 3)
                                @php
                                    $bgClasses = [
                                        'blog-blue', // Green
                                        'blog-yellow', // Red
                                        'blog-green', // Blue
                                    ];
                                @endphp

                                <div class="blog-card {{ $bgClasses[$index] }}">
                                    <div class="date">{{ \Carbon\Carbon::parse($blog->created_at)->format('l, F jS Y') }}
                                    </div>
                                    <div class="heading">{{ $blog->title }}</div>
                                    <div class="content mb-4">
                                        {!! \Illuminate\Support\Str::limit($blog->content_1, 600) !!}
                                    </div>
                                    <a href="{{ route('resource-show', $blog->slug) }}" class="read-more-btn">Read More →</a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="column">
                        @foreach ($blogs as $index => $blog)
                            @if ($index >= 3 && $index < 6)
                                @php
                                    $bgClasses = [
                                        'blog-green', // Green
                                        'blog-pink', // Red
                                        'blog-blue', // Blue
                                    ];
                                    $bgIndex = $index - 3;
                                @endphp
                                <div class="blog-card {{ $bgClasses[$bgIndex] }}">
                                    <div class="date">{{ \Carbon\Carbon::parse($blog->created_at)->format('l, F jS Y') }}</div>
                                    <div class="heading">{{ $blog->title }}</div>
                                    <div class="content mb-4">
                                        {!! \Illuminate\Support\Str::limit($blog->content_1, 600) !!}
                                    </div>
                                    <a href="{{ route('resource-show', $blog->slug) }}" class="read-more-btn">Read More →</a>
                                </div>
                            @endif
                        @endforeach

                        <div class="">
                            <a href="#" class="btn btn-archive"><span>Read More </span> <span class="mx-2"></span>
                                <span> →</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection

@include('include.resourse-js')
