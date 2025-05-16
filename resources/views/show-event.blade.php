@extends('layouts.master')
@section('title', 'Event | P23 Africa')

<style>
    .bg-success {
        background-color: #27866d !important;
        border: 2px dashed #cbe5dc;
    }

    .btn-success {
        background-color: #014f43;
        border: none;
    }

    .btn-success:hover {
        background-color: #012e27;
    }

    .fw-semibold {
        font-weight: 600;
    }

    .about-event {
        margin: 5rem 0;
    }

    .about-event h6 {
        font-size: 16px;
        color: #0D4036;
        font-weight: 700;
        font-family: 'GT Walsheim Con';
    }

    .about-event h1 {
        /* font-size: 56px; */
        font-size: 47px;
        color: #0D4036;
        font-weight: 800;
        font-family: 'GT Walsheim ConB';
    }

    .about-event h3 {
        font-size: 36px;
        color: #0D4036;
        font-weight: 400;
        font-family: 'GT Walsheim Con';
    }

    .about-event .event-time {
        background: url("../images/event-box.png");
        background-size: cover;
        background-repeat: no-repeat;
        /* position: relative; */
        /* width: 500px; */
        width: 100%;
        padding: 40px 0px 30px 35px;
    }

    .about-event .event-time p {
        font-size: 20px;
        font-family: 'GT Walsheim Con';
    }

    #paragragh p {
        color: #0D4036;
        font-weight: 800;
        font-family: "GT Walsheim Con";
        font-size: 24px;
        width: 100%
    }
</style>


@section('content')
    <section class="event_hero content-section animate-on-scroll">
        <div class="container">
            <div id="event_hero_desktop">
                <img src="{{ asset('images/event-header.png') }}" loading='lazy' alt="Card image">
            </div>
            <div id="event_hero_mobile">
                <img src="{{ asset('images/event-header-mobile.png') }}" loading='lazy' alt="Card image">
            </div>
        </div>
    </section>


    <section class="about-event">
        <div class="container py-5">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <h6 class="text-uppercase fw-bold">About This Event</h6>
                    <h1 class="fw-bold display-5 text-uppercase">{{ $event->title }}</h1>
                    @if ($event->subtitle)
                        <h3 class="mb-4 text-capitalize">{{ $event->subtitle }}</h3>
                    @endif
                </div>

                <!-- Event Details -->
                <div class="col-md-6">
                    <div class="event-time rounded-3 mb-5 text-white">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-calendar-alt fa-2x me-3"></i>
                                    <p>
                                        <strong>{{ \Carbon\Carbon::parse($event->date)->format('jS F, Y') }}</strong><br />
                                        {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-map-marker-alt fa-2x me-3"></i>
                                    <p>
                                        <strong>Google Meet</strong><br />
                                        1hr 30mins
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="fw-semibold fs-5" id="paragragh">
                {!! $event->description !!}
            </div>
            {{-- <p class="fw-semibold fs-5 text-dark">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p> --}}

            <!-- Buttons and Counter -->
            <div class="align-items-center mt-5">
                <div class="d-flex">

                    <button class="btn btn-success text-white me-4 mb-3">Book A Seat <i
                            class="fas fa-arrow-right ms-2"></i></button>


                    <button class="btn btn-light border me-2" onclick="decrement()">–</button>
                    <span id="seatCount" class="fs-4 fw-bold">1</span>
                    <button class="btn btn-light border ms-2" onclick="increment()">+</button>
                </div>

                <div class="d-block align-items-center ms-auto float-end mt-4">
                    <button class="btn btn-outline-dark me-3 mb-3">Share This Event <i
                            class="fas fa-arrow-right ms-2"></i></button>
                </div>

            </div>
        </div>
    </section>
@endsection

<script>
    // Counter
    let count = 1;

    function increment() {
        count++;
        document.getElementById('seatCount').textContent = count;
    }

    function decrement() {
        if (count > 1) {
            count--;
            document.getElementById('seatCount').textContent = count;
        }
    }



    // Date & Time
    const eventDate = new Date("{{ $event->date }} {{ $event->time }}").getTime();

    const x = setInterval(function() {
        const now = new Date().getTime();
        const distance = eventDate - now;

        if (distance < 0) {
            clearInterval(x);
            document.getElementById("countdown").innerHTML = "Event started!";
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

        document.getElementById("countdown").innerHTML = `Starts in ${hours}h ${minutes}m`;
    }, 1000);
</script>
