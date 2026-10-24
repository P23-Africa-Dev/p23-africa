@extends('layouts.master')
@section('title', 'Book a Consultation | P23 Africa')

@section('meta')
    <meta name="description"
        content="Book a personalized business consultation with P23 Africa. Get expert guidance on funding readiness, market positioning, strategy development, and investor engagement tailored to African entrepreneurs and growing businesses.">
    <meta name="keywords"
        content="book consultation, business consultation, P23 Africa, African entrepreneurs, business strategy, funding readiness, investor preparation, growth consulting, startup advisory, business development Africa">
    <meta name="author" content="P23 Africa">
    <meta name="generator" content="Book Consultation | P23 Africa">

    <meta property="og:title" content="Book a Consultation | P23 Africa">
    <meta property="og:description"
        content="Schedule a one-on-one consultation to strengthen your business strategy, improve funding readiness, and unlock growth opportunities with expert advisors at P23 Africa.">
    <meta property="og:image" content="{{ asset('img/consultation-page-thumbnail.png') }}">
    <meta property="og:type" content="website">

    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
@endsection


<!--   -->



@section('content')

    <section class="book-conversation-section">
        <div class="box-container">
            <div class="intro-container">
                <div class="left-content">
                    <h3>Let’s help you unlock growth clarity for your business.</h3>

                    <div class="info">
                        <img src="{{ asset('img/left-conv-pattern.png') }}" class="content-pattern" alt="" />
                        <div class="info-content">
                            <div class="btn">
                                <a href="{{ route('consultation.application-forms') }}">Let’s get started
                                    <div>
                                        <img src="{{ asset('img/right.png') }}" alt="" />
                                    </div>
                                </a>
                            </div>
                            <div class="para">
                                <p>
                                    <span>The consultation</span> will analyze your challenges,
                                    identify growth blockers, and design a roadmap to help you
                                    scale sustainably in African markets.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-content">
                    <img class="mobile" src="{{ asset('img/mobile-main-bg.png') }}" loading="lazy" alt="" />
                    <div class="desktop-image-cont">
                        <img class="desktop" loading="lazy" src="{{ asset('img/first-image.png') }}" alt="" />
                        {{-- <img class="desktop" loading="lazy" src="../img/right-desktop-bg.png" alt="" /> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection



