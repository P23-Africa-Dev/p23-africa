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

    </section>
@endsection
