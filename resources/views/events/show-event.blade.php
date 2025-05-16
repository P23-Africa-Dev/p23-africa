@extends('layouts.master')
@section('title', 'Event | P23 Africa')

<style>
    h1,
    h3 {
        color: #014f43;
    }

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
</style>


@section('content')
    <section>
        <div class="container py-5">
            <div class="row">
                <div class="col-md-7">
                    <h6 class="text-uppercase fw-bold">About This Event</h6>
                    <h1 class="fw-bold display-5 text-uppercase">{{ $event->title }}</h1>
                    @if ($event->subtitle)
                        <h3 class="text-secondary mb-4 text-capitalize">{{ $event->subtitle }}</h3>
                    @endif
                </div>

                <!-- Event Details -->
                <div class="col-md-5">
                    <div class="bg-success text-white p-4 rounded-3 mb-5">
                        <div class="row text-center">
                            <div class="col-md-6 d-flex align-items-center justify-content-center">
                                <i class="fas fa-calendar-alt fa-2x me-3"></i>
                                <div>
                                    <strong>{{ \Carbon\Carbon::parse($event->date)->format('jS F, Y') }}</strong><br />
                                    {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-center justify-content-center mt-3 mt-md-0">
                                <i class="fas fa-map-marker-alt fa-2x me-3"></i>
                                <div>
                                    <strong>Google Meet</strong><br />
                                    1hr 30mins
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="fw-semibold fs-5 text-dark">
                {!! $event->description !!}
            </div>
            {{-- <p class="fw-semibold fs-5 text-dark">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p> --}}

            <!-- Buttons and Counter -->
            <div class="d-flex flex-wrap align-items-center mt-5">
                <button class="btn btn-outline-dark me-3 mb-3">Share This Event <i
                        class="fas fa-arrow-right ms-2"></i></button>
                <button class="btn btn-success text-white me-4 mb-3">Book A Seat <i
                        class="fas fa-arrow-right ms-2"></i></button>

                <div class="d-flex align-items-center ms-auto">
                    <button class="btn btn-light border me-2" onclick="decrement()">–</button>
                    <span id="seatCount" class="fs-4 fw-bold">1</span>
                    <button class="btn btn-light border ms-2" onclick="increment()">+</button>
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
