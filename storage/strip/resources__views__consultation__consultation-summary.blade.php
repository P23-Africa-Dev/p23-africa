@extends('layouts.consultations')
@section('title', 'Consultation Summary | P23 Africa')

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
    <meta property="og:image" content="{{ asset('img/mobile.png') }}">
    <meta property="og:type" content="website">

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


<!--   -->



@section('content')
    <section class="summary-main-container">
        <div class="summary-container">
            <!-- ---------------------------LEFT CONTENT -->
            <div class="summary-left-content">
                <div class="title">
                    <h1>
                        <span class="light">Your</span> <br />
                        <span class="bold">Consultation Summary</span>
                    </h1>


                    <p>
                        Thank You <span id="userName">{{ $application->name }}</span>, We've Captured Your
                        Key Goals And Challenges.
                    </p>
                </div>

                <div class="overview">
                    <h3>Business Overview:</h3>
                    <p>

                        {{-- (<strong>{{ $application->company }}</strong>)  --}}
                        {{ $application->business_description }}
                        {{-- <br><br> --}}
                        {{-- <strong>Industry:</strong> {{ $application->industry }} --}}
                    </p>
                </div>

                <div class="summary-section challenges">
                    <h4>Your Key Challenges:</h4>
                    <div id="challengeList" class="list-container">
                        <div class="lists">
                            @foreach (($application->selected_challenges ?? []) as $challenge)
                                <label class="list-card">
                                    <span class="list-text">{{ $challenge }}</span>
                                    <span class="list-checkmark"><img src="{{ asset('img/marked.png') }}"
                                            alt="" /></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- ---------------------------RIGHT CONTENT -->
            <div class="summary-right-content">
                <div class="summary-section goals">
                    <h4>Your Current Goals:</h4>
                    <div id="goalList" class="list-container">
                        <div class="lists">
                            @foreach (($application->selected_goals ?? []) as $goal)
                                <label class="list-card">
                                    <span class="list-text">{{ $goal }}</span>
                                    <span class="list-checkmark"><img src="{{ asset('img/marked.png') }}"
                                            alt="" /></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="summary-box">
                    <h3>Your 1hr 30m Consultation Will;</h3>
                    <ul>
                        <li>Review Your Business Position And Growth Stage</li>
                        <li>Identify The Key Blockers Slowing Your Progress</li>
                        <li>Provide Practical, Step-By-Step Recommendations</li>
                        <li>Outline A Clear Roadmap To Move Forward With Confidence</li>
                    </ul>

                    <p class="subtitle">You'll Leave The Session With:</p>
                    <ul class="benefit-list">
                        <li>Clarity</li>
                        <li>Direction</li>
                        <li>Action Steps You Can Implement Immediately</li>
                    </ul>
                </div>
                <div class="payment-btn">
                    <form method="POST" action="{{ route('consultation.payment.initiate') }}">
                        @csrf
                        <input type="hidden" name="booking" value="{{ $application->booking_token }}">
                        <button type="submit" class="btn proceed-btn">
                            Proceed to Payment
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>


@endsection



