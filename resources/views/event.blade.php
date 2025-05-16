@extends('layouts.master')
@section('title', 'Event | P23 Africa')


@section('content')
    <section class="event_hero content-section animate-on-scroll">
        <div id="event_hero_desktop">
            <img src="{{ asset('images/event_hero_header.png') }}" loading='lazy' alt="Card image">
        </div>
        <div id="event_hero_mobile">
            <img src="{{ asset('images/event_hero_mobile.jpg') }}" loading='lazy' alt="Card image">
        </div>
    </section>

    <section class="upcoming_events">
        <div class="event-section">
            <!-- Events Grid -->
            <div class="row g-md-5 g-4">
                <!-- Featured Card -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="event-card1 h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="section-title">Upcoming Event For The Year</div>
                        </div>
                        <p class="section-subtitle">
                            Explore upcoming events tailored to equip, connect, and empower businesses across Africa and
                            beyond.
                        </p>
                        <a href="{{ url('all-events') }}" class="btn btn-outline-light2 mt-3">
                            See All Events <i class="bi bi-arrow-right px-3"></i>
                        </a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="event-card featured h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="event-icon">📅</div>
                            <div class="event-title">Vision & Execution</div>
                        </div>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua.
                        </p>
                        <button class="btn btn-outline-light btn-book">
                            Book A Seat <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Reusable Event Cards -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="event-card h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="event-icon">📅</div>
                            <div class="event-title">Event Name</div>
                        </div>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim ad minim veniam, quis nostrud
                            exercitation ullamco.
                        </p>
                        <button class="btn btn-outline-dark btn-book">
                            Book A Seat <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="event-card h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="event-icon">📅</div>
                            <div class="event-title">Event Name</div>
                        </div>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim ad minim veniam, quis nostrud
                            exercitation ullamco.
                        </p>
                        <button class="btn btn-outline-dark btn-book">
                            Book A Seat <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="event-card h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="event-icon">📅</div>
                            <div class="event-title">Event Name</div>
                        </div>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim ad minim veniam, quis nostrud
                            exercitation ullamco.
                        </p>
                        <button class="btn btn-outline-dark btn-book">
                            Book A Seat <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="event-card h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="event-icon">📅</div>
                            <div class="event-title">Event Name</div>
                        </div>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim ad minim veniam, quis nostrud
                            exercitation ullamco.
                        </p>
                        <button class="btn btn-outline-dark btn-book">
                            Book A Seat <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="past_events">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-lg-4 my-auto">
                    <div class="">
                            <h2 class="event-title">Past Events</h2>
                            <p class="event-subtitle">
                                Explore our past events tailored to equip, connect, and empower businesses across Africa and
                                beyond.
                            </p>
                    </div>
                </div>
                <!-- Event Card -->
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card-wrapper">
                        <img src="{{ asset('images/past-event.jpg') }}" loading='lazy' alt="Event" class="event-image" />
                        <div class="event-overlay brown">
                            <h5>Vision VS Execution</h5>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="view-link">View Gallery →</a>
                                <span class="event-meta">Virtual (Google Meet)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Repeat Cards -->
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card-wrapper">
                        <img src="{{ asset('images/past-event.jpg') }}" loading='lazy' alt="Event" class="event-image" />
                        <div class="event-overlay">
                            <h5>Vision VS Execution</h5>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="view-link">View Gallery →</a>
                                <span class="event-meta">Virtual (Google Meet)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card-wrapper">
                        <img src="{{ asset('images/past-event.jpg') }}" loading='lazy' alt="Event" class="event-image" />
                        <div class="event-overlay brown">
                            <h5>Vision VS Execution</h5>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="view-link">View Gallery →</a>
                                <span class="event-meta">Virtual (Google Meet)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card-wrapper">
                        <img src="{{ asset('images/past-event.jpg') }}" loading='lazy' alt="Event" class="event-image" />
                        <div class="event-overlay">
                            <h5>Vision VS Execution</h5>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="view-link">View Gallery →</a>
                                <span class="event-meta">Virtual (Google Meet)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card-wrapper">
                        <img src="{{ asset('images/past-event.jpg') }}" loading='lazy' alt="Event" class="event-image" />
                        <div class="event-overlay brown">
                            <h5>Vision VS Execution</h5>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="view-link">View Gallery →</a>
                                <span class="event-meta">Virtual (Google Meet)</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="event_footer content-section animate-on-scroll mb-2">
        <div id="event_footer_desktop">
            <img src="{{ asset('images/event_footer-desktop.jpg') }}" loading='lazy' alt="Card image">
        </div>
        <div id="event_footer_mobile">
            <img src="{{ asset('images/event_footer-mobile.jpg') }}" loading='lazy' alt="Card image">
        </div>
    </section>
@endsection
