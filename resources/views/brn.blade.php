@extends('layouts.brn-layout')
@section('title', 'BRN | P23 Africa')

@section('meta')
    <meta name="description"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth.">
    <meta name="keywords"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth.">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth | P23 Africa">
    <meta property="og:image" content="{{ asset('images/brn-meta.png') }}">
@endsection

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

@section('content')
    <section class="whole-brn">
        <section class="brn-intro-section">
            <div class="container">
                <div class="row align-items-center gx-5 justify-content-center">
                    <div class="col-12 col-lg-6 order-lg-2 mb-md-0 mb-5">
                        <div class="intro-content text-left mx-md-5">
                            <h1>
                                Where Top CEOs <br />
                                <span class="d-block">Connect and Grow</span>
                            </h1>

                            <p>
                                A private, trusted platform for vetted CEOs across Africa to exchange referrals, strategic
                                deals, and build partnerships through direct 1:1 connections.
                            </p>
                            <a href="{{ route('brn-form') }}" class="register-button">
                                Register Now!
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 order-lg-1">
                        <div class="image-grid">
                            <div class="image">
                                <div class="first">
                                    <!-- card dots -->
                                    <div class="card-dots">
                                        <div class="dot"></div>
                                        <div class="dot"></div>
                                        <div class="dot"></div>
                                    </div>
                                    <div class="send-icon">
                                        <img class="" src="{{ asset('images/send.png') }}" loading="lazy"
                                            class="" alt="" />
                                    </div>
                                    <img class="firstimage" loading="lazy" src="{{ asset('images/brn-intro-card2.jpg') }}"
                                        class="" alt="" />
                                </div>
                                <div class="second">
                                    <div class="card-dots">
                                        <div class="dot"></div>
                                        <div class="dot"></div>
                                        <div class="dot"></div>
                                    </div>
                                    <div class="card-image">
                                    
                                            <img src="{{ asset('images/banner-side.png') }}" loading="lazy"
                                                alt="" />
                                       
                                        {{-- <p>
                                            "Trusted referrals. Real <br />
                                            business growth."
                                        </p> --}}
                                    </div>
                                    <img src="{{ asset('images/brn-intro-card1.png') }}" loading="lazy" class="secondimage"
                                        alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="brn-how-we-operate-section">
            <div class="top-right-bg"><img src="{{ asset('images/work-top.png') }}" loading="lazy" alt="" /></div>
            <div class="container">
                <div class="section-header">
                    <h4>How We Operate</h4>
                    <h2>Simple, Trusted & Effective</h2>
                </div>
                <div class="card-wrapper">
                    <div class="operate-card card-1">
                        <div class="pb-md-3 pb-0">
                            <img src="{{ asset('images/arrow-group.png') }}" class="image" loading="lazy" alt="Arrow">
                        </div>
                        <div class="card-content px-4">
                            <h3>Join the Exclusive Circle</h3>
                            <p>
                                Apply, get approved, and start <span class="d-md-inline d-block">your 14-day free trial.</span>
                            </p>
                        </div>
                        <div class="pt-3">
                            <img src="{{ asset('images/arrows.png') }}" class="image2" loading="lazy" alt="Arrow">
                        </div>
                    </div>
                    <div class="operate-card card-2">
                        <div class="d-md-block d-none">
                            <img class="w-full" class="operate-card-image1" loading="lazy"
                            src="{{ asset('images/brn-intro3.png') }}" alt="CEO Image" />
                        </div>

                        <div class="d-md-none d-block">
                             <img class="w-full" class="operate-card-image1" loading="lazy"
                            src="{{ asset('images/brn-intro-mobile.png') }}" alt="CEO Image" />
                        </div>
                        <div class="card-content my-3 px-5 px-md-4">
                            <h3>Connect & Match</h3>
                            <p class="">
                                Browse the vetted directory, get matched with the right professionals, and share referrals.
                            </p>
                        </div>
                    </div>
                    <div class="operate-card card-3">
                        <img class="top" src="{{ asset('images/Vector (3).png') }}" loading="lazy" alt="" />
                        <div class="card-content">
                            <h3>Collaborate Privately</h3>
                            <p>
                                Message members directly and grow your network inside an exclusive community.
                            </p>
                        </div>
                        <img class="bottom" src="{{ asset('images/Vector (3).png') }}" loading="lazy" alt="" />
                    </div>
                </div>
            </div>
            <div class="bottom-left-bg">
                <img src="{{ asset('images/work-top.png') }}" loading="lazy" alt="" />
            </div>
        </section>


        <section class="buss-match d-md-block d-none">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 my-auto">
                        <img src="{{ asset('images/business_match_text.png') }}" loading="lazy" alt="Business Match">
                    </div>
                    <div class="col-md-8">
                        <img src="{{ asset('images/business_match.png') }}" loading="lazy" alt="Business Match">
                    </div>
                </div>
            </div>
        </section>
        <section class="buss-match d-md-none d-block">
            <div class="container">
                <img src="{{ asset('images/brn-mobile-one.png') }}" loading="lazy" alt="Valuable Leads">
            </div>
        </section>


        <section class="valuable-leads d-md-block d-none">
            <div class="container">
                <img src="{{ asset('images/valuable_leads.png') }}" loading="lazy" alt="Valuable Leads">
            </div>
        </section>
        <section class="valuable-leads d-md-none d-block mb-5">
            <div class="container">
                <img src="{{ asset('images/brn-mobile-two.png') }}" loading="lazy" alt="Valuable Leads">
            </div>
        </section>


        <section class="networking d-md-block d-none">
            <div class="container mt-5">
                <img src="{{ asset('images/networking.png') }}" loading="lazy" alt="Networking">
            </div>
        </section>
        <section class="networking d-md-none d-block">
            <div class="container">
                <img src="{{ asset('images/brn-mobile-three.png') }}" loading="lazy" alt="Networking">
            </div>
        </section>



        <section class="brn-testimonials-section">
            <div class="">
                <div class="container">
                    <div class="row header-row px-md-0 px-5">
                        <div class="col-12 col-lg-6">
                            <div class="title-group text-left text-lg-start">
                                <h2>Testimonials.</h2>
                                <h2>Member Spotlight.</h2>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 mt-3 mt-lg-4">
                            <div class="description text-left d-flex justify-content-end">
                                <p>
                                    Real success stories from CEOs who’ve grown through the power of referral-based
                                    networking.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-0">
                    <div class="col-12">
                        <div class="testimonial-card first-row">
                            <div class="text-block order-1 order-lg-1 dark">
                                <p class="company-name">Kenya</p>

                                <div>
                                    <h4 class="ceo-name">Linda Mwangi, Managing Partner.</h4>
                                    <p class="testimonial-text">
                                        This community has opened doors I’d struggled to unlock for years. The calibre of
                                        professionals here means every referral actually converts into real business.
                                    </p>
                                </div>
                            </div>

                            <div class="card-image order-2 order-lg-2">
                                <img src="{{ asset('images/testimonial-1.png') }}" loading="lazy" alt="CEO Image 2" />
                            </div>

                            <div class="text-block order-4 order-lg-3 dark">
                                <p class="company-name">Ghana</p>

                                <div>
                                    <h4 class="ceo-name">Kofi Mensah, CEO.</h4>
                                    <p class="testimonial-text">
                                        I love that I don’t waste time here. The matching tool introduced me to founders in
                                        Ghana and beyond who genuinely want to collaborate and grow together.
                                    </p>
                                </div>
                            </div>

                            <div class="card-image order-3 order-lg-4">
                                <img src="{{ asset('images/testimonial-brn.jpg') }}" loading="lazy" alt="CEO Image 2" />
                            </div>
                        </div>
                        <div class="testimonial-card first-row">
                            <div class="text-block order-1 order-lg-2 green">
                                <p class="company-name">Nigeria</p>

                                <div>
                                    <h4 class="ceo-name">Chijioke Okeke, Founder.</h4>
                                    <p class="testimonial-text">
                                        What sets this apart is the community. Everyone is qualified, serious, and open to
                                        real opportunities, not just networking for the sake of it.
                                    </p>
                                </div>
                            </div>

                            <div class="card-image order-2 order-lg-1">
                                <img src="{{ asset('images/testimonial-brn2.jpg') }}" loading="lazy"
                                    alt="CEO Image 2" />
                            </div>

                            <div class="text-block order-4 order-lg-4 green">
                                <p class="company-name">South Africa</p>

                                <div>
                                    <h4 class="ceo-name">Zanele Khumalo, Director.</h4>
                                    <p class="testimonial-text">
                                        Finally, a trusted network that filters for quality. Every introduction feels
                                        intentional, and I’ve already seen a clear ROI through new partnerships.
                                    </p>
                                </div>
                            </div>

                            <div class="card-image order-3 order-lg-3">
                                <img src="{{ asset('images/testimonial-brn3.jpg') }}" loading="lazy"
                                    alt="CEO Image 2" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="brn-events-meetups-section">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-12 col-lg-4">
                        <div class="events-intro text-left text-lg-start">
                            <h2>Events & Meetups</h2>
                            <p>
                                From closed-door roundtables to business retreats, every event
                                is designed to foster genuine collaboration and opportunity.
                            </p>
                            <a href="{{ route('events.all-events', ['filter' => 'brn']) }}" class="see-all-events-button">
                                See All Events
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    @foreach ($events as $event)
                        @if ($loop->first)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="event-card dark-card">
                                    <div class="card-icon">
                                        <img src="{{ asset('images/calendar-white2.png') }}" loading="lazy"
                                            alt="" />
                                        <h3><a href="{{ route('events.show', $event->slug) }}">{{ Str::limit($event->title, 20) }}</a></h3>
                                    </div>
                                    <div>
                                        {!! Str::limit($event->description, 233) !!}
                                    </div>
                                    <a href="{{ route('events.show', $event->slug) }}" class="book-seat-button actionBtn" id="actionBtn"
                                            data-event-id="{{ $event->id }}"
                                            data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}"
                                            data-event-link="{{ $event->link }}" data-bs-toggle="modal"
                                            data-bs-target="#seatModal">
                                        Book A Seat
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="event-card light-card">
                                    <div class="card-icon">
                                        <img src="{{ asset('images/calendar.png') }}" loading="lazy" alt="" />

                                        <h3>
                                            <a href="{{ route('events.show', $event->slug) }}">{{ Str::limit($event->title, 20) }}</a>
                                        </h3>
                                    </div>
                                    <div>
                                        {!! Str::limit($event->description, 233) !!}
                                    </div>
                                    <a href="{{ route('events.show', $event->slug) }}" class="book-seat-button actionBtn" id="actionBtn"
                                            data-event-id="{{ $event->id }}"
                                            data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}"
                                            data-event-link="{{ $event->link }}" data-bs-toggle="modal"
                                            data-bs-target="#seatModal">
                                        Book A Seat
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>

        <section class="brn-cta-section">
            <div class="container">
                <div class="row align-items-center gx-5">
                    <div class="d-none d-lg-block  col-lg-6 order-lg-1">
                        <div class="right-image">
                            <img src="{{ asset('images/cta-bg.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                    <div class="col-12 col-lg-6  order-lg-2 left-container">
                        <img class="top-image" src="{{ asset('images/Vector -cta.png') }}" loading="lazy"
                            alt="" />
                        <div class="left-content text-left">
                            <h2>Ready to Grow with Trusted Leaders?</h2>

                            <p>
                                Join a powerful ecosystem of referrals, relationships, and
                                results.
                            </p>
                            <a href="#" class="button">
                                Register Your Interest
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <img class="bottom-image" src="{{ asset('images/Vector.png') }}" loading="lazy" loading="lazy"
                            alt="" />
                    </div>
                </div>
            </div>
        </section>

         @include('include.book-seat')
    </section>
@endsection

@include('include.bookjs')