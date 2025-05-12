@extends('layouts.master')
@section('title', 'Event | P23 Africa')


@section('content')
    <section>

    </section>
    <section class="upcoming_events">
        <div class="event-section">
            <!-- Top Heading -->
            {{-- <div class="row mb-5">
                <div class="col-lg-6 col-md-8">
                    <h2 class="section-title">Upcoming Event For The Year</h2>
                    <p class="section-subtitle">
                        Explore upcoming events tailored to equip, connect, and empower businesses across Africa and beyond.
                    </p>
                    <button class="btn btn-outline-light mt-3">
                        See All Events <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div> --}}

            <!-- Events Grid -->
            <div class="row g-5">
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
                        <button class="btn btn-outline-light2 mt-3">
                            See All Events <i class="bi bi-arrow-right px-3"></i>
                        </button>
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
@endsection
