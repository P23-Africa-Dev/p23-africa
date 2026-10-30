@extends('layouts.master')
@section('title')
    {{ $event->translated('title') }}
    @if ($event->translated('subtitle'))
        ({{ $event->translated('subtitle') }})
    @endif
    | P23 Africa
@endsection

@section('meta')
    <meta property="og:image" content="{{ asset('storage/' . $event->image) }}">
    <meta name="description" content="{!! $event->translated('description') !!}">

    <meta name="keywords" content="{!! $event->translated('description') !!}">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="{!! $event->translated('description') !!}">
@endsection




@section('content')
    <section id="whole-event">
        <section class="event_hero content-section animate-on-scroll">
            <div class="px-md-5">
                <div id="event_hero_desktop" class="px-md-4">
                    @if ($event->image)
                        <div class="event-wrapper">
                            <img src="{{ asset('images/event-frame.png') }}" loading='lazy' alt="Frame"
                                class="frame-img">
                            <img src="{{ asset('storage/' . $event->image) }}" loading='lazy' alt="Main Event"
                                class="main-img">
                        </div>
                        {{-- <img src="{{ asset('storage/' . $event->image) }}" loading='lazy' class="" alt="Event Image"> --}}
                    @else
                        <div class="event-wrapper">
                            <img src="{{ asset('images/event-frame.png') }}" loading='lazy' alt="Event Frame"
                                class="frame-img">
                        </div>
                    @endif
                </div>
                {{-- <div id="event_hero_desktop" class="px-md-4">
                <img src="{{ asset('images/event-header.png') }}" loading='lazy' alt="Card image">
            </div>
            <div id="event_hero_mobile">
                <img src="{{ asset('images/event-header-mobile.png') }}" loading='lazy' alt="Card image">
            </div> --}}
            </div>
        </section>



        <section class="about-event">
            <div class="container py-5">
                <div class="row justify-content-between">
                    <div class="col-md-6">
                        <h6 class="text-uppercase fw-bold">About This Event</h6>
                        <h1 class="fw-bold display-5 text-uppercase">{{ $event->translated('title') }}</h1>
                        @if ($event->translated('subtitle'))
                            <h3 class="mb-4 text-capitalize">{{ $event->translated('subtitle') }}</h3>
                        @endif
                    </div>

                    <!-- Event Details -->
                    <div class="col-md-6">
                        <div class="event-time rounded-3 mb-5 text-white" id="desktop">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-calendar-alt fa-2x me-3"></i>
                                        <p>
                                            <strong>{{ \Carbon\Carbon::parse($event->event_date)->format('jS F, Y') }}</strong><br />
                                            <strong>{{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}
                                                GMT+1</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-map-marker-alt fa-2x me-3"></i>
                                        <p>
                                            <strong>{{ $event->translated('venue') ?? 'Google Meet' }}</strong><br />
                                            <strong>1hr 30mins</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="fw-semibold fs-5 mt-md-4 mt-lg-4 mt-2" id="paragragh">
                    {!! $event->translated('description') !!}
                </div>
                {{-- <p class="fw-semibold fs-5 text-dark">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p> --}}


                <div class="event-time rounded-3 mb-5 text-white" id="mobile">
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-calendar-alt fa-2x me-3"></i>
                                <p>
                                    <strong>{{ \Carbon\Carbon::parse($event->event_date)->format('jS F, Y') }}</strong><br />
                                    <strong>{{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }} GMT+1</strong>
                                </p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-map-marker-alt fa-2x me-3"></i>
                                <p>
                                    <strong>{{ $event->translated('venue') ?? 'Google Meet' }}</strong><br />
                                    <strong>1hr 30mins</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons and Counter -->
                <div class="align-items-center mt-5">
                    <div class="d-flex">

                        <button class="btn me-4 mb-3 px-5 py-2 btn-color actionBtn" id="actionBtn"
                            data-event-id="{{ $event->id }}"
                            data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}"
                            data-event-link="{{ $event->link }}" data-bs-toggle="modal" data-bs-target="#seatModal">Book A
                            Seat <i class="fas fa-arrow-right ms-2"></i></button>

                        <!-- Event page button -->
                        {{-- <button id="actionBtn" class="btn btn-success"
                        data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}">
                        Book a seat
                    </button> --}}

                        {{-- <div class="my-auto number-button">
                            <button class="btn border me-2" onclick="decrement()">–</button>
                            <span id="seatCount" class="fs-4 fw-bold">1</span>
                            <button class="btn border ms-2" onclick="increment()">+</button>
                        </div> --}}
                    </div>

                    <div class="d-block align-items-center ms-auto float-end mt-4">
                        <button class="btn me-3 mb-3 px-5 py-2 share-button d-block" data-url="{{ url()->current() }}"
                            onclick="shareEvent(this)" id="btn-share">Share This Event <i
                                class="fas fa-share ms-2"></i></button>


                        <div class="d-block float-end">
                            @auth
                                @if (auth()->user()->is_admin)
                                    <a href="{{ orgRoute('admin.events.edit', $event->id) }}" target="_blank"
                                        class="btn btn-warning">
                                        Edit Event
                                    </a>
                                @endif
                            @endauth
                        </div>
                        <small id="copy-success" style="display: none; color: green;">Link copied to clipboard!</small>
                    </div>
                </div>
            </div>
        </section>

        @include('include.book-seat')



        @if (session('invalid_seat'))
            
        @endif

        <!-- Modal for inserting seat number -->
        <div class="modal fade" id="joinEventModal" tabindex="-1" aria-labelledby="joinEventLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4">
                    <h5 class="text-center mb-3">Enter Your Seat Code</h5>
                    <form method="POST" action="{{ locale_route('event.join') }}">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <input type="text" name="seat_code" class="form-control mb-3" placeholder="Enter your seat code"
                            required>
                        <button type="submit" class="btn btn-dark w-100">Join Event</button>
                    </form>
                </div>
            </div>
        </div>


        <br><br>
    </section>
@endsection
@include('include.bookjs')


