@extends('layouts.quiz-layout')
@section('title', 'Start Quiz | P23 Africa')

@section('meta')
    <meta name="description"
        content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
    <meta name="keywords"
        content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Business Consulting & Growth Solutions for Africa | P23 Africa">
    <meta property="og:image" content="{{ asset('images/intro-bg.png') }}">
@endsection

@section('content')
    <section class="main-quiz-section min-vh-10 mt-3">
        <div class="desktop-quiz-bg-overlay mt-4">
            <img class="main-quiz-left-bg" src="{{ asset('images/main-quiz-left-bg.png') }}" alt="" />
            <img class="main-quiz-right-bg" src="{{ asset('images/main-quiz-right-bg.png') }}" alt="" />
        </div>

        <div class="main-quiz-card">
            <div class="quiz-card-container">
                <div class="main-quiz-header">
                    <div class="quiz-header">
                        <div class="question-number-circle" id="questionNumber">01</div>
                        <div class="progress-container">
                            <div class="progress-bar-fill" id="progressBar" style="width: 0%"></div>
                            <span class="progress-text" id="progressText">1 of 8</span>
                        </div>
                        <h2 class="question-text" id="questionText"></h2>
                    </div>
                </div>
                <div>
                    <div class="main-quiz-body">
                        <div class="quiz-body-container">
                            <div id="quizWrapper">
                                <div class="answer-options" id="answerOptions"></div>
                                <div class="quiz-navigation">
                                    <button class="btn next-question-btn" id="nextBtn" disabled>
                                        <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Add this hidden form in your HTML -->
                            <div id="emailFormWrapper" style="display: none; text-align: center;">
                                <h3>Almost Done! Enter your email to see your results</h3>
                                <input type="email" class="form-control mb-4" id="userEmail" placeholder="Your Email" required />
                                <button class="btn next-question-btn" id="submitQuizBtn">Submit & See Results</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        {{-- <div class="main-quiz-card">
            <div class="quiz-card-container">
                <div class="main-quiz-header">
                    <div class="quiz-header">
                        <div class="question-number-circle">05</div>
                        <div class="progress-container">
                            <div class="progress-bar-fill" style="width: 12.5%"></div>
                            <span class="progress-text">01 of 08</span>
                        </div>

                        <h2 class="question-text">
                            DO YOU HAVE SIMPLE TOOLS TO HELP YOUR TEAM STAY ORGANISED?
                        </h2>
                    </div>
                </div>
                <div class="main-quiz-body">
                    <div class="quiz-body-container">
                        <div class="answer-options">
                            <div class="answer-item">
                                <span class="answer-letter">A</span>
                                <p class="answer-text">Not Really</p>
                            </div>
                            <div class="answer-item">
                                <span class="answer-letter">B</span>
                                <p class="answer-text">We try—but it's messy</p>
                            </div>
                            <div class="answer-item">
                                <span class="answer-letter">C</span>
                                <p class="answer-text">Yes—everyone uses them</p>
                            </div>
                        </div>
                        <div class="quiz-navigation">
                            <button class="btn next-question-btn">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        @include('include.quiz-questions-js')
    </section>
@endsection
