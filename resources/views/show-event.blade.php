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
        margin: 7rem 0;
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
        background: url("../images/event-box.png") no-repeat center center;
        /* background-size: cover;
        background-repeat: no-repeat; */
        /* position: relative; */
        /* width: 500px; */
        width: 100%;
        padding: 40px 0px 30px 35px;
    }

    .about-event .event-time p {
        font-size: 20px;
        font-family: 'GT Walsheim Con';
    }

    .about-event .btn-color {
        background-color: #0D4036;
        color: #fff;
        font-family: 'GT Walsheim Con';
    }

    .about-event #btn-share {
        background-color: #fff;
        font-family: 'GT Walsheim Con';
        color: #0D4036;
        font-weight: 400;
        border-radius: 10px;
        border-bottom: 3px solid #0D4036;
    }

    .about-event .number-button button {
        border-radius: 100%;
        background-color: #EAE9E9;
        color: #0D4036;
        font-weight: 700;
        transition: 500ms;
        margin: auto;
    }

    .about-event .number-button button:hover {
        background-color: #0D4036;
        color: #EAE9E9;
    }

    #paragragh p {
        color: #0D4036;
        font-weight: 800;
        font-family: "GT Walsheim Con";
        font-size: 24px;
        width: 100%
    }

    #whole-event {
        background: linear-gradient(#ffffffbe, #ffffffbe),
            url("../images/bg-grey.png");
    }

    #seatForm input {
        border: 0;
        border-bottom: 2px solid #0D4036;
        box-shadow: 2px 2px 4px #0000008e;
    }

    #seatForm .btn {
        background-color: #0D4036;
        color: #fff;
        font-family: 'GT Walsheim Con';
    }

    .modal-content {
        border-radius: 1rem;
    }
</style>


@section('content')
    <section id="whole-event">
        <section class="event_hero content-section animate-on-scroll">
            <div class="px-md-5">
                <div id="event_hero_desktop" class="px-md-4">
                    @if ($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" loading='lazy' class="" alt="Event Image">
                    @else
                        <img src="{{ asset('images/event-frame.png') }}" loading='lazy' alt="Event Frame">
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

        <br><br><br>

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
                                <div class="col-6">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-calendar-alt fa-2x me-3"></i>
                                        <p>
                                            <strong>{{ \Carbon\Carbon::parse($event->event_date)->format('jS F, Y') }}</strong><br />
                                            <strong>{{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-map-marker-alt fa-2x me-3"></i>
                                        <p>
                                            <strong>Google Meet</strong><br />
                                            <strong>1hr 30mins</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="fw-semibold fs-5 mt-4" id="paragragh">
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

                        <button class="btn me-4 mb-3 px-5 py-2 btn-color" id="actionBtn"
                            data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}" data-event-link="{{ $event->link }}" data-bs-toggle="modal"
                            data-bs-target="#seatModal">Book A Seat <i class="fas fa-arrow-right ms-2"></i></button>

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
                                    <a href="{{ route('admin.events.edit', $event->id) }}" target="_blank"
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

        <!-- Seat Booking Modal -->
        <div class="modal fade w-100" id="seatModal" tabindex="-1" aria-labelledby="seatModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 py-3 px-5">
                    <div class="modal-header border-0">
                        {{-- <h5 class="modal-title text-success" id="seatModalLabel">Book a Seat</h5> --}}
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mb-2" style="color: #0D4036; font-weight: 500; font-family: 'GT Walsheim Con';">et
                            dolore
                            magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                            ullamco laboris nisi</p><br>
                        <form id="seatForm">
                            @csrf
                            <input type="text" name="name" class="form-control mb-5" placeholder="Full name" required>
                            <input type="email" name="email" class="form-control mb-5" placeholder="Email" required>
                            <input type="text" name="phone" class="form-control mb-5" placeholder="Phone no." required>

                            <button type="submit" class="btn w-50" id="submitBtn">
                                <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                    id="submitSpinner"></span>
                                Submit <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Popup Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4 border-0 shadow rounded-4">
                    <h5 class="mb-3" style="color: #0D4036; font-weight: 500; font-family: 'GT Walsheim Con';">
                        Thank you for signing up. We've sent event details to your email <br>
                        <span class="">— please check your inbox.</span>
                    </h5>
                    <a href="{{ route('events.all-events') }}" class="btn mt-3"
                        style="background-color: #0D4036; color: #fff; font-family: 'GT Walsheim Con';">
                        View Other Events <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        @if (session('invalid_seat'))
            <script>
                alert("{{ session('invalid_seat') }}");
            </script>
        @endif

        <!-- Modal for inserting seat number -->
        <div class="modal fade" id="joinEventModal" tabindex="-1" aria-labelledby="joinEventLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4">
                    <h5 class="text-center mb-3">Enter Your Seat Code</h5>
                    <form method="POST" action="{{ route('event.join') }}">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <input type="text" name="seat_code" class="form-control mb-3"
                            placeholder="Enter your seat code" required>
                        <button type="submit" class="btn btn-dark w-100">Join Event</button>
                    </form>
                </div>
            </div>
        </div>


        <br><br>
    </section>


@endsection

<script>
    //Access Live Event Page
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('actionBtn');
        const eventDateTime = new Date(btn.dataset.eventDatetime);
        const eventLink = btn.dataset.eventLink;

        function updateButtonState() {
            const now = new Date();
            if (now >= eventDateTime) {
                btn.textContent = "Join Event";
                btn.classList.remove('btn-color');
                btn.classList.add('btn-primary');

                btn.removeAttribute('data-bs-toggle', 'modal');
                btn.removeAttribute('data-bs-target', '#joinEventModal');

                // Add click-to-redirect
                btn.addEventListener('click', function() {
                    window.open(eventLink, '_blank');
                }, {
                    once: true
                });
            }
        }

        updateButtonState();
        setInterval(updateButtonState, 60000);
    });


    // Pop Up
    document.addEventListener('DOMContentLoaded', function() {
        const seatForm = document.getElementById('seatForm');
        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('submitSpinner');

        seatForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Show loading spinner
            spinner.classList.remove('d-none');
            submitBtn.disabled = true;

            const formData = new FormData(seatForm);

            fetch("{{ route('seats.store', $event->id) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    spinner.classList.add('d-none');
                    submitBtn.disabled = false;

                    if (data.success) {
                        alert('Something went wrong. Please try again.');
                    } else {
                        seatForm.reset();
                        const seatModal = bootstrap.Modal.getInstance(document.getElementById(
                            'seatModal'));
                        seatModal.hide();

                        const successModal = new bootstrap.Modal(document.getElementById(
                            'successModal'));
                        successModal.show();
                    }
                })
                .catch(error => {
                    console.error(error);
                    spinner.classList.add('d-none');
                    submitBtn.disabled = false;
                    alert('Error submitting the form.');
                });
        });
    });




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

    // const x = setInterval(function() {
    //     const now = new Date().getTime();
    //     const distance = eventDate - now;

    //     if (distance < 0) {
    //         clearInterval(x);
    //         document.getElementById("countdown").innerHTML = "Event started!";
    //         return;
    //     }

    //     const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    //     const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

    //     document.getElementById("countdown").innerHTML = `Starts in ${hours}h ${minutes}m`;
    // }, 1000);



    // Share Button
    function shareEvent(button) {
        const url = button.getAttribute('data-url');

        // Use native Web Share API if available (mostly on mobile)
        if (navigator.share) {
            navigator.share({
                    title: document.title,
                    text: 'Check out this event!',
                    url: url
                })
                .then(() => console.log('Shared successfully'))
                .catch((error) => console.log('Sharing failed', error));
        } else {
            // Fallback: Copy link to clipboard
            navigator.clipboard.writeText(url)
                .then(() => {
                    document.getElementById('copy-success').style.display = 'inline';
                    setTimeout(() => {
                        document.getElementById('copy-success').style.display = 'none';
                    }, 2000);
                })
                .catch(err => {
                    alert('Unable to copy. Please copy manually.');
                    console.error(err);
                });
        }
    }
</script>
