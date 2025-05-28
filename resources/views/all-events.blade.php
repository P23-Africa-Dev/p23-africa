@extends('layouts.master')
@section('title', 'All Events | P23 Africa')

<head>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<style>
    #suggestion-box a {
        cursor: pointer;
    }

    .track_events #search-input {
        width: 432px;
        border-radius: 10px;
        border: 2px solid #0000004D;
    }

    .track_events #search {
        float: inline-end;
    }

    .track_events #search-input::placeholder {
        font-family: "GT Walsheim Con";
    }

    @media (max-width: 768px) {
        .track_events #search-input {
            max-width: 100%;
        }

        .track_events #search {
            float: none;
        }
    }
</style>

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
            <div class="row justify-content-between mb-5">
                <div class="col-md-6">

                </div>
                <div class="col-md-6">
                    <div class="position-relative" id="search">
                        <input type="text" id="search-input" class="form-control"
                            placeholder="Search your preferred event">
                        <div id="suggestion-box" class="list-group position-absolute w-100 shadow-sm"
                            style="z-index: 1000; display: none;"></div>
                    </div>
                </div>
            </div>
            <!-- Mobile Tabs -->
            <div class="tabs-mobile">
                <button class="tab-btn active" onclick="switchTab('month', this)">Happening This Month</button>
                <button class="tab-btn" onclick="switchTab('next', this)">Happening Next Month</button>
            </div>

            <div class="flex-layout">

                <!-- Sidebar (Desktop Only) -->
                <div class="sidebar1">
                    <div class="event-box filter-btn" data-filter="this_month">
                        <h4><img src="{{ asset('images/calendar.png') }}" loading='lazy' alt=""> 1st April - 30th
                            April</h4>
                        <p>Happening This Month</p>
                    </div>
                    <div class="event-box filter-btn" data-filter="next_month">
                        <h4><img src="{{ asset('images/calendar.png') }}" loading='lazy' alt=""> 1st May - 31st May
                        </h4>
                        <p>Happening Next Month</p>
                    </div>
                    <div class="event-box filter-btn" data-filter="two_months">
                        <h4><img src="{{ asset('images/calendar.png') }}" loading='lazy' alt=""> 1st June - 30th
                            June</h4>
                        <p>Happening In Two Month</p>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="main-content">
                    <!-- This Month -->
                    <div id="month" class="tab-panel active">
                        <div id="event-list">
                            @foreach ($events as $event)
                                <div class="event-card">
                                    {{-- <div style="font-size: 24px;">📅</div> --}}
                                    <div>
                                        <h3><img src="{{ asset('images/calendar.png') }}" loading='lazy' alt=""><a
                                                href="{{ route('events.show', $event->slug) }}"> {{ $event->title }} <i>
                                                    @if ($event->subtitle)
                                                        ({{ $event->subtitle }})
                                                    @endif

                                                </i></a></h3>
                                        <p>{!! Str::limit($event->description, 233) !!}</p>
                                        <div class="event-card-footer">
                                            <span class="countdown status"
                                                data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}">
                                                Loading...</span>

                                            @php
                                                $startTime = \Carbon\Carbon::parse($event->event_time);
                                                $endTime = $startTime->copy()->addMinutes(90);
                                            @endphp

                                            <span class="time">{{ $startTime->format('g:i A') }} -
                                                {{ $endTime->format('g:i A') }}</span>
                                            <button class="btn actionBtn" id="actionBtn"
                                                data-event-id="{{ $event->id }}"
                                                data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}"
                                                data-event-link="{{ $event->link }}" data-bs-toggle="modal"
                                                data-bs-target="#seatModal">Book A Seat →</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        <!-- Duplicate for more events -->
                        <div class="mt-4">
                            {{ $events->links('pagination::simple-bootstrap-5') }}
                        </div>
                    </div>

                    <!-- Next Month -->
                    <div id="next" class="">
                        <div id="" class="">
                            <div id="">
                                @foreach ($next_month_events as $event)
                                    <div class="event-card">
                                        {{-- <div style="font-size: 24px;">📅</div> --}}
                                        <div>
                                            <h3><img src="{{ asset('images/calendar.png') }}" loading='lazy'
                                                    alt=""><a href="{{ route('events.show', $event->slug) }}">
                                                    {{ $event->title }} <i>
                                                        @if ($event->subtitle)
                                                            ({{ $event->subtitle }})
                                                        @endif

                                                    </i></a></h3>
                                            <p>{!! Str::limit($event->description, 233) !!}</p>
                                            <div class="event-card-footer">
                                                <span class="countdown status"
                                                    data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}">
                                                    Loading...</span>

                                                @php
                                                    $startTime = \Carbon\Carbon::parse($event->event_time);
                                                    $endTime = $startTime->copy()->addMinutes(90);
                                                @endphp

                                                <span class="time">{{ $startTime->format('g:i A') }} -
                                                    {{ $endTime->format('g:i A') }}</span>
                                                <button class="btn actionBtn" id="actionBtn"
                                                    data-event-id="{{ $event->id }}"
                                                    data-event-datetime="{{ $event->event_date }} {{ $event->event_time }}"
                                                    data-event-link="{{ $event->link }}" data-bs-toggle="modal"
                                                    data-bs-target="#seatModal">Book A Seat →</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                            <!-- Duplicate for more events -->
                            <div class="mt-4">
                                {{ $events->links('pagination::simple-bootstrap-5') }}
                            </div>
                        </div>

                        <!-- More events -->
                    </div>
                </div>

            </div>
        </div>
    </section>


    @include('include.book-seat')
@endsection

{{-- <script src="{{ asset('js/event.js') }}"></script> --}}

@include('include.bookjs')
<script>
    // Event End Time
    document.addEventListener('DOMContentLoaded', function() {
        const eventTime = "14:00"; // You can replace this with a dynamic value

        // Parse the event time
        const [hours, minutes] = eventTime.split(':').map(Number);
        const eventDate = new Date();
        eventDate.setHours(hours, minutes, 0); // Set to event time

        // Add 1 hour 30 minutes
        eventDate.setMinutes(eventDate.getMinutes() + 90);

        // Format the new end time
        const endHours = String(eventDate.getHours()).padStart(2, '0');
        const endMinutes = String(eventDate.getMinutes()).padStart(2, '0');
        const endTime = `${endHours}:${endMinutes}`;

        // Display it
        document.getElementById('calculated-end-time').textContent = endTime;
    });



    // Event Filter Hover and Click Effect
    $(document).ready(function() {
        // Hover effect (only if not clicked)
        $('.event-box').hover(
            function() {
                if (!$(this).hasClass('clicked')) {
                    $(this).addClass('active-class');
                }
            },
            function() {
                if (!$(this).hasClass('clicked')) {
                    $(this).removeClass('active-class');
                }
            }
        );

        // Click effect: keep only one clicked + active at a time
        $('.event-box').on('click', function() {
            // Remove from all others
            $('.event-box').removeClass('active-class clicked');

            // Add to this one
            $(this).addClass('active-class clicked');
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        function getFormattedDate(date) {
            const day = date.getDate();
            const suffix = (d => {
                if (d > 3 && d < 21) return 'th';
                switch (d % 10) {
                    case 1:
                        return 'st';
                    case 2:
                        return 'nd';
                    case 3:
                        return 'rd';
                    default:
                        return 'th';
                }
            })(day);

            const month = date.toLocaleString('default', {
                month: 'long'
            });
            const year = date.getFullYear();
            return `${day}${suffix} ${month}, ${year}`;
        }

        function updateCountdowns() {
            const countdownElements = document.querySelectorAll('.countdown');
            countdownElements.forEach(el => {
                const dateTime = el.dataset.eventDatetime;
                const eventDate = new Date(dateTime);
                const countDownDate = eventDate.getTime();

                const now = new Date();
                const today = now.toISOString().split('T')[0];
                const eventDateOnly = eventDate.toISOString().split('T')[0];
                const distance = countDownDate - now.getTime();

                if (today === eventDateOnly) {
                    if (distance < 0) {
                        el.innerHTML = "Event Started";
                        return;
                    }
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    el.innerHTML = `Happening in ${hours}h ${minutes}m`;
                } else {
                    el.innerHTML = getFormattedDate(eventDate);
                }
            });
        }

        updateCountdowns();
        setInterval(updateCountdowns, 60000);
    });



    // Events Filter + Search
    // $(document).ready(function () {
    //     const $searchInput = $('#search-input');
    //     const $suggestionBox = $('#suggestion-box');
    //     const $eventList = $('#event-list');

    //     function fetchSuggestions(term = '') {
    //         if (!term.trim()) {
    //             $suggestionBox.hide().empty();
    //             return;
    //         }

    //         $.ajax({
    //             url: '{{ route('events.search') }}',
    //             data: { term },
    //             success: function (data) {
    //                 $suggestionBox.empty();

    //                 if (data.length === 0) {
    //                     $suggestionBox.append(
    //                         `<a href="#" class="list-group-item list-group-item-action disabled">No matching events</a>`
    //                     );
    //                 } else {
    //                     data.slice(0, 6).forEach(event => {
    //                         const subtitle = event.subtitle ? ` <i>(${event.subtitle})</i>` : '';
    //                         $suggestionBox.append(`
    //                             <a href="/events/${event.slug}" class="list-group-item list-group-item-action">
    //                                 <strong>${event.title}</strong>${subtitle}
    //                             </a>
    //                         `);
    //                     });
    //                 }

    //                 $suggestionBox.show();
    //             },
    //             error: function (xhr, status, error) {
    //                 console.error('Error fetching suggestions:', error);
    //             }
    //         });
    //     }

    //     $searchInput.on('input', function () {
    //         const term = $(this).val();
    //         fetchSuggestions(term);
    //     });

    //     $(document).on('click', function (e) {
    //         if (!$(e.target).closest('#search-input, #suggestion-box').length) {
    //             $suggestionBox.hide();
    //         }
    //     });

    //     $searchInput.on('keydown', function (e) {
    //         if (e.key === 'Escape') {
    //             $suggestionBox.hide();
    //         }
    //     });

    //     $('.filter-btn').on('click', function () {
    //         let filter = $(this).data('filter');
    //         $('#search-input').val('');
    //         $('#suggestion-box').hide();

    //         $.ajax({
    //             url: '{{ route('events.search') }}',
    //             data: { filter },
    //             success: function (data) {
    //                 $eventList.empty();

    //                 if (data.length === 0) {
    //                     $eventList.append('<div class="text-muted text-center w-100">No events found.</div>');
    //                 } else {
    //                     data.forEach(event => {
    //                         const desc = event.description.length > 233 ? event.description.slice(0, 230) + '...' : event.description;
    //                         const subtitle = event.subtitle ? `<i>(${event.subtitle})</i>` : '';

    //                         const html = `
    //                             <div class="event-card">
    //                                 <div>
    //                                     <h3>
    //                                         <img src="{{ asset('images/calendar.png') }}" loading="lazy" alt="">
    //                                         <a href="/events/${event.slug}">${event.title} ${subtitle}</a>
    //                                     </h3>
    //                                     <p>${desc}</p>
    //                                     <div class="event-card-footer">
    //                                         <span class="countdown status" data-event-datetime="${event.event_date} ${event.event_time}">Loading...</span>
    //                                         <span class="time">${event.event_time} - 5:30PM</span>
    //                                         <button class="btn">Book A Seat →</button>
    //                                     </div>
    //                                 </div>
    //                             </div>
    //                         `;
    //                         $eventList.append(html);
    //                     });

    //                     setTimeout(() => {
    //                         const event = new Event('DOMContentLoaded');
    //                         document.dispatchEvent(event);
    //                     }, 200);
    //                 }
    //             },
    //             error: function (xhr, status, error) {
    //                 console.error('Error fetching filtered events:', error);
    //             }
    //         });
    //     });
    // });


    $(document).ready(function() {
        const $eventList = $('#event-list');

        function fetchEvents(term = '', filter = '') {
            $.ajax({
                url: '{{ route('events.search') }}',
                data: {
                    term,
                    filter
                },
                success: function(data) {
                    $eventList.empty();

                    if (data.length === 0) {
                        $eventList.append(
                            '<div class="text-muted text-center w-100">No events found.</div>');
                    } else {
                        data.forEach(event => {
                            const desc = event.description.length > 233 ? event.description
                                .slice(0, 230) + '...' : event.description;
                            const subtitle = event.subtitle ? `<i>(${event.subtitle})</i>` :
                                '';

                            const html = `
                                <div class="event-card">
                                    <div>
                                        <h3>
                                            <img src="{{ asset('images/calendar.png') }}" loading="lazy" alt="">
                                            <a href="/events/${event.slug}">
                                                ${event.title} ${subtitle}
                                            </a>
                                        </h3>
                                        <p>${desc}</p>
                                        <div class="event-card-footer">
                                            <span class="countdown status" data-event-datetime="${event.event_date} ${event.event_time}">
                                                Loading...
                                            </span>
                                            <span class="time">${event.event_time} - 5:30PM</span>
                                            <button class="btn">Book A Seat →</button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $eventList.append(html);
                        });

                        // Re-run countdown updater after injecting new HTML
                        setTimeout(() => {
                            const event = new Event('DOMContentLoaded');
                            document.dispatchEvent(event);
                        }, 200);
                    }
                },
                error: function() {
                    $eventList.html(
                        '<div class="text-danger text-center w-100">Error loading events.</div>'
                    );
                }
            });
        }

        // Live Search
        $('#search-input').on('input', function() {
            let term = $(this).val();
            fetchEvents(term);
        });

        // Filter Buttons
        $('.filter-btn').on('click', function() {
            let filter = $(this).data('filter');
            $('#search-input').val(''); // Clear input
            fetchEvents('', filter);
        });
    });
</script>

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        function getFormattedDate(date) {
            const day = date.getDate();
            const suffix = (d => {
                if (d > 3 && d < 21) return 'th';
                switch (d % 10) {
                    case 1:
                        return 'st';
                    case 2:
                        return 'nd';
                    case 3:
                        return 'rd';
                    default:
                        return 'th';
                }
            })(day);

            const month = date.toLocaleString('default', {
                month: 'long'
            });
            const year = date.getFullYear();

            return `${day}${suffix} ${month}, ${year}`;
        }

        function updateCountdowns() {
            const countdownElements = document.querySelectorAll('.countdown');

            countdownElements.forEach(el => {
                const dateTime = el.dataset.eventDatetime;
                const eventDate = new Date(dateTime);
                const countDownDate = eventDate.getTime();

                const now = new Date();
                const today = now.toISOString().split('T')[0];
                const eventDateOnly = eventDate.toISOString().split('T')[0];

                const distance = countDownDate - now.getTime();

                if (today === eventDateOnly) {
                    if (distance < 0) {
                        el.innerHTML = "Event Started";
                        return;
                    }

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    el.innerHTML = `Happening in ${hours}h ${minutes}m`;
                } else {
                    el.innerHTML = getFormattedDate(eventDate);
                }
            });
        }

        updateCountdowns();
        setInterval(updateCountdowns, 60000);
    });



    // Events Filter
    $(document).ready(function() {
        const $eventList = $('#event-list');

        function fetchEvents(term = '', filter = '') {
            $.ajax({
                url: '{{ route('events.search') }}',
                data: {
                    term,
                    filter
                },
                success: function(data) {
                    $eventList.empty();
                    if (data.length === 0) {
                        $eventList.append(
                            '<div class="col-12"><p class="text-muted text-center">No events found.</p></div>'
                            );
                    } else {
                        data.forEach(event => {
                            $eventList.append(`
                            <div class="col-md-4 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h5>${event.title}</h5>
                                        <p>${event.description.slice(0, 80)}...</p>
                                        <small><strong>${new Date(event.event_date).toDateString()}</strong></small>
                                    </div>
                                </div>
                            </div>
                        `);
                        });
                    }
                }
            });
        }

        // Live Search
        $('#search-input').on('input', function() {
            let term = $(this).val();
            fetchEvents(term);
        });

        // Filters
        $('.filter-btn').on('click', function() {
            let filter = $(this).data('filter');
            $('#search-input').val('');
            fetchEvents('', filter);
        });
    });
</script> --}}
