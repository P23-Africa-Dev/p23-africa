@extends('layouts.master')
@section('title', 'Event | P23 Africa')

@section('meta')
    <meta name="description"
        content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
    <meta name="keywords"
        content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Business Consulting & Growth Solutions for Africa | P23 Africa">
    <meta property="og:image" content="{{ asset('images/event_hero_header.jpg') }}">
@endsection


@section('content')
    <section class="event_hero animate-on-scroll">
        <div id="event_hero_desktop1">
            <img src="{{ asset('images/event_hero_header.png') }}" loading='lazy' alt="Card image">
        </div>
        <div id="event_hero_mobile1">
            <img src="{{ asset('images/event_hero_mobile.jpg') }}" loading='lazy' alt="Card image">
        </div>
    </section>

    <section class="upcoming_events content-section animate-on-scroll">
        <div class="event-section py-2">
            <!-- Events Grid -->
            <div class="row g-md-5 g-4">
                <!-- Featured Card -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="event-card1 h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="section-title">Upcoming Events For The Year</div>
                        </div>
                        <p class="section-subtitle">
                            Explore upcoming events tailored to equip, connect, and empower businesses across Africa and
                            beyond.
                        </p>
                        <a href="{{ route('events.all-events') }}" class="btn btn-outline-light2 mt-3">
                            See All Events <i class="bi bi-arrow-right px-3"></i>
                        </a>
                    </div>
                </div>
                @foreach ($events as $event)
                    @if ($loop->first)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="event-card featured h-100">
                                <div class="d-flex align-items-center mb-2">
                                    {{-- <div class="event-icon">📅</div> --}}
                                    <img src="{{ asset('images/calendar-white.png') }}" id="img" loading='lazy'
                                        alt="">
                                    <div class="event-title"><a
                                            href="{{ route('events.show', $event->slug) }}">{{ Str::limit($event->title, 20) }}</a>
                                    </div>
                                </div>
                                <p>
                                    {!! Str::limit($event->description, 233) !!}
                                </p><br>
                                <div class="d-flex justify-content-between align-items-center my-auto btn-book">
                                    <div>
                                        <a href="{{ route('events.show', $event->slug) }}"
                                            class="btn btn-outline-light actionBtn" id="actionBtn"
                                            data-event-id="{{ $event->id }}"
                                            data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}"
                                            data-event-link="{{ $event->link }}" data-bs-toggle="modal"
                                            data-bs-target="#seatModal">
                                            Book A Seat <i class="bi bi-arrow-right"></i>
                                        </a>
                                        {{-- <a href="{{ route('events.show', $event->slug) }}"
                                            class="btn btn-outline-light btn-book actionBtn" id="actionBtn"
                                            data-event-id="{{ $event->id }}"
                                            data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}"
                                            data-event-link="{{ $event->link }}" data-bs-toggle="modal"
                                            data-bs-target="#seatModal">
                                            Book A Seat <i class="bi bi-arrow-right"></i>
                                        </a> --}}
                                    </div>

                                    <div>
                                        <a href="{{ route('events.show', $event->slug) }}" class="details">
                                            View Details <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Reusable Event Cards -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="event-card h-100">
                                <div class="d-flex align-items-center mb-2">
                                    {{-- <div class="event-icon">📅</div> --}}
                                    <img src="{{ asset('images/calendar.png') }}" loading='lazy' alt="">
                                    <div class="event-title"><a
                                            href="{{ route('events.show', $event->slug) }}">{{ Str::limit($event->title, 20) }}</a>
                                    </div>
                                </div>
                                <p>
                                    {!! Str::limit($event->description, 233) !!}
                                </p><br>
                                <div class="d-flex justify-content-between align-items-center my-auto btn-book">
                                    <div>
                                        <a href="{{ route('events.show', $event->slug) }}"
                                            class="btn btn-outline-dark actionBtn" id="actionBtn"
                                            data-event-id="{{ $event->id }}"
                                            data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}"
                                            data-event-link="{{ $event->link }}" data-bs-toggle="modal"
                                            data-bs-target="#seatModal">
                                            Book A Seat <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>

                                    <div>
                                        <a href="{{ route('events.show', $event->slug) }}" class="details">
                                            View Details <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                                {{-- <a href="{{ route('events.show', $event->slug) }}" class="btn btn-outline-dark btn-book"
                                    id="actionBtn" data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}"
                                    data-event-link="{{ $event->link }}" data-bs-toggle="modal"
                                    data-bs-target="#seatModal">
                                    Book A Seat <i class="bi bi-arrow-right"></i>
                                </a> --}}
                            </div>
                        </div>
                    @endif
                @endforeach

                {{-- <div class="col-12 col-md-6 col-lg-4">
                    <div class="event-card h-100">
                        <div class="d-flex align-items-center mb-2">
                            <img src="{{ asset('images/calendar.png') }}" loading='lazy' alt="">
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
                            <img src="{{ asset('images/calendar.png') }}" loading='lazy' alt="">
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
                            <img src="{{ asset('images/calendar.png') }}" loading='lazy' alt="">
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
                </div> --}}
            </div>
        </div>
    </section>

    <section class="past_events content-section animate-on-scroll">
        <div class="container">
            <div class="row g-md-5 g-4">
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
                @foreach ($past_events as $past_event)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card-wrapper">
                            @if ($past_event->image)
                                <img src="{{ asset('storage/' . $past_event->image) }}" loading='lazy' id="event-image"
                                    alt="Event" class="event-image" />
                            @else
                                <img src="{{ asset('images/past-event.jpg') }}" loading='lazy' alt="Event"
                                    class="event-image" />
                            @endif

                            <div class="event-overlay brown">
                                <h5>{{ $past_event->title }}</h5>
                                <p>{!! Str::limit($past_event->description, 80) !!}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('events.show', $past_event->slug) }}" class="view-link">View Gallery
                                        →</a>
                                    <span class="event-meta">Virtual (Google Meet)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Repeat Cards -->
                {{-- <div class="col-12 col-sm-6 col-lg-4">
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
                </div> --}}

            </div>
        </div>
    </section>

    @include('include.book-seat')

    <section class="event_footer content-section animate-on-scroll mb-2">
        <div id="event_footer_desktop">
            <img src="{{ asset('images/event_footer-desktop.jpg') }}" loading='lazy' alt="Card image">
        </div>
        <div id="event_footer_mobile">
            <img src="{{ asset('images/event_footer-mobile.jpg') }}" loading='lazy' alt="Card image">
        </div>
    </section>
@endsection

@include('include.bookjs')
