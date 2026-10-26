@extends('layouts.quiz-layout')
@section('title', __('quiz.quiz_title'))

@section('meta')
    <meta name="description"
        content="{{ __('quiz.meta') }}">
    <meta name="keywords"
        content="{{ __('quiz.meta') }}">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="{{ __('quiz.meta_generator') }}">
    <meta property="og:image" content="{{ asset('images/intro-bg.png') }}">
@endsection

@section('content')
    <header>
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="navbar-overlay d-none d-lg-block"></div>
            <div class="container-fluid px-md-5 d-flex align-items-center justify-content-between">
                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" style="visibility: hidden;" href="{{ locale_route('homepage') }}">
                    <img src="{{ asset('images/Logo2.png') }}" alt="">
                </a>

                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="{{ __('quiz.toggle_nav') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Nav Links  -->
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav nav-center-links">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ locale_route('sales') }}"> <span class="nav-main-text">{{ __('quiz.home') }}</span> </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{ __('quiz.how') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ locale_route('quiz') }}">{{ __('quiz.quiz') }}</a>
                        </li>
                    </ul>

                    <!-- Social Icons  -->
                    <div class="nav-menu-list d-lg-flex">
                        <div class="d-flex gap-3 icon-list">
                            <a class="nav-link" href="https://facebook.com" target="_blank">
                                <i class="bi bi-facebook fs-5"></i>
                            </a>
                            <a class="nav-link" href="https://instagram.com" target="_blank">
                                <i class="bi bi-instagram fs-5"></i>
                            </a>
                            <a class="nav-link" href="https://linkedin.com" target="_blank">
                                <i class="bi bi-linkedin fs-5"></i>
                            </a>
                            <a class="nav-link" href="https://pinterest.com" target="_blank">
                                <i class="bi bi-pinterest fs-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <section class="quiz-section">
        <div class="">
            <div class="content-container align-items-center">
                <div class="quiz-content container">
                    <div class="intro-content">
                        <h1><strong>{{ __('quiz.losing') }}</strong></h1>
                        <p class="intro-para">
                            {{ __('quiz.intro') }}
                        </p>
                    </div>
                    <div class="box-content-area mt-5">
                        <img class="main-side-bg" src="{{ asset('images/main-side-bg.png') }}" alt="" />
                        <div class="box-content">
                            <a href="{{ locale_route('start-quiz') }}" class="btn quiz-button">{{ __('quiz.start_now') }} <i class="bi bi-arrow-right"></i></a>
                            <p class="quiz-hint">
                                {{ __('quiz.hint') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="quiz-image-area d-flex justify-content-center align-items-center">
                    <div class="quiz-intro-overlay"></div>
                    <img src="{{ asset('images/quiz-desktop.png') }}" alt="{{ __('quiz.alt_people') }}"
                        class="img-fluid d-none d-lg-block" />
                    <img src="{{ asset('images/quiz-mobile.png') }}" alt="{{ __('quiz.alt_people') }}"
                        class="img-fluid d-block d-lg-none" />
                </div>
            </div>
        </div>
    </section>
@endsection
