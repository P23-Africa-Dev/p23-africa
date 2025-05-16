@extends('layouts.master')
@section('title', 'All Events | P23 Africa')

{{-- <head>
    <script src="https://cdn.tailwindcss.com"></script>
</head> --}}

@section('content')
    <section class="all_event_hero content-section animate-on-scroll">
        <div id="all_event_hero_desktop">
            <img src="{{ asset('images/All-Desktopt-Event.png') }}" loading='lazy' alt="Card image">
        </div>
        <div id="all_event_hero_mobile">
            <img src="{{ asset('images/All-Event-Mobile.png') }}" loading='lazy' alt="Card image">
        </div>
    </section>


    <section class="track_events">
        <div class="container">

            <!-- Mobile Tabs -->
            <div class="tabs-mobile">
                <button class="tab-btn active" onclick="switchTab('month', this)">Happening This Month</button>
                <button class="tab-btn" onclick="switchTab('next', this)">Happening Next Month</button>
            </div>

            <div class="flex-layout">

                <!-- Sidebar (Desktop Only) -->
                <div class="sidebar1">
                    <div class="event-box">
                        <h4><img src="{{ asset('images/calendar.png') }}" loading='lazy' alt=""> 1st April - 30th
                            April</h4>
                        <p>Happening This Month</p>
                    </div>
                    <div class="event-box">
                        <h4><img src="{{ asset('images/calendar.png') }}" loading='lazy' alt=""> 1st May - 31st May
                        </h4>
                        <p>Happening Next Month</p>
                    </div>
                    <div class="event-box">
                        <h4><img src="{{ asset('images/calendar.png') }}" loading='lazy' alt=""> 1st June - 30th
                            June</h4>
                        <p>Happening In Two Month</p>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="main-content">
                    <!-- This Month -->
                    <div id="month" class="tab-panel active">
                        @foreach ($events as $event)
                            <div class="event-card">
                                {{-- <div style="font-size: 24px;">📅</div> --}}
                                <div>
                                    <h3><img src="{{ asset('images/calendar.png') }}" loading='lazy' alt=""><a
                                            href="{{ route('events.show', $event->slug) }}"> {{ $event->title }} <i>
                                                ({{ $event->subtitle }})</i></a></h3>
                                    <p>{!! Str::limit($event->description, 233) !!}</p>
                                    <div class="event-card-footer">
                                        <span class="countdown status" id="countdown-{{ $event->id }}"
                                            data-event-id="{{ $event->id }}"
                                            data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}">
                                            Loading...</span>
                                        <span class="time">{{ $event->event_time }} - 5:30PM</span>
                                        <button class="btn">Book A Seat →</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Duplicate for more events -->
                    </div>

                    <!-- Next Month -->
                    <div id="next" class="tab-panel">
                        <div class="event-card">
                            {{-- <div style="font-size: 24px;">📅</div> --}}
                            <div>
                                <h3><img src="{{ asset('images/calendar.png') }}" loading='lazy' alt=""> Next Month
                                    Event</h3>
                                <p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                                    veniam.</p>
                                <div class="event-card-footer">
                                    <span class="status">Happening in 3 Days</span>
                                    <span class="time">4:00PM - 5:30PM</span>
                                    <button class="btn">Book A Seat →</button>
                                </div>
                            </div>
                        </div>

                        <!-- More events -->
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function () {
        function updateCountdowns() {
            const countdownElements = document.querySelectorAll('.countdown');

            countdownElements.forEach(el => {
                const dateTime = el.dataset.eventDatetime;
                const countDownDate = new Date(dateTime).getTime();

                const now = new Date().getTime();
                const distance = countDownDate - now;

                if (distance < 0) {
                    el.innerHTML = "Event Started";
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                // const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                el.innerHTML = `Happening in ${hours}h ${minutes}m`;
            });
        }

        // Show countdown immediately
        updateCountdowns();

        // Then keep updating every minute
        setInterval(updateCountdowns, 60000);
    });
</script>

