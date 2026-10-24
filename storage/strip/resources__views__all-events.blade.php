@extends('layouts.master')
@section('title', 'All Events | P23 Africa')

@section('meta')
    <meta name="description"
        content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
    <meta name="keywords"
        content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Business Consulting & Growth Solutions for Africa | P23 Africa">
    <meta property="og:image" content="{{ asset('images/All-Desktopt-Event.png') }}">
@endsection

<head>
    {{--  --}}
    
</head>



@section('content')
    <section class="all_event_hero content-section animate-on-scroll">
        <div id="all_event_hero_desktop">
            <img src="{{ asset('images/All-Desktopt-Event.png') }}" loading='lazy' alt="Card image">
        </div>
        <div id="all_event_hero_mobile">
            <img src="{{ asset('images/All-Event-Mobile.png') }}" loading='lazy' alt="Card image">
        </div>
    </section>


    <section class="track_events content-section animate-on-scroll">
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

            @php
                use Carbon\Carbon;

                $thisMonth = Carbon::now();
                $nextMonth = $thisMonth->copy()->addMonth();
                $twoMonths = $thisMonth->copy()->addMonths(2);

                function getMonthRange(Carbon $date)
                {
                    $start = $date->copy()->startOfMonth();
                    $end = $date->copy()->endOfMonth();

                    $startDay = $start->format('jS');
                    $monthYear = $start->format('F');

                    $endDay = $end->format('jS');

                    return "{$startDay} {$monthYear} - {$endDay} {$monthYear}";
                }
            @endphp

            <div class="flex-layout">

                <!-- Sidebar (Desktop Only) -->
                <div class="sidebar1">
                    <div class="event-box filter-btn" data-filter="this_month">
                        <h4><img src="{{ asset('images/calendar.png') }}" loading='lazy' alt="">
                            {{ getMonthRange($thisMonth) }}</h4>
                        <p>Happening This Month</p>
                    </div>
                    <div class="event-box filter-btn" data-filter="next_month">
                        <h4><img src="{{ asset('images/calendar.png') }}" loading='lazy' alt="">
                            {{ getMonthRange($nextMonth) }}
                        </h4>
                        <p>Happening Next Month</p>
                    </div>
                    <div class="event-box filter-btn" data-filter="two_months">
                        <h4><img src="{{ asset('images/calendar.png') }}" loading='lazy' alt="">
                            {{ getMonthRange($twoMonths) }}</h4>
                        <p>Happening In Two Month</p>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="main-content">
                    <!-- This Month -->
                    <div id="month" class="tab-panel active">
                        <div id="event-list">
                            @if ($filter === 'brn')
                                <h2 class="mb-4 brn-events">All BRN Events</h2>
                            @endif

                            @foreach ($events as $event)
                                <div class="event-card">
                                    {{-- <div style="font-size: 24px;">📅</div> --}}
                                    <div>
                                        <h3><img src="{{ asset('images/calendar.png') }}" loading='lazy' alt=""><a
                                                href="{{ locale_route('events.show', ['slug' => $event->slug]) }}"> {{ $event->translated('title') }} <i>
                                                    @if ($event->translated('subtitle'))
                                                        ({{ $event->translated('subtitle') }})
                                                    @endif

                                                </i></a></h3>
                                        <p>{!! Str::limit($event->translated('description'), 233) !!}</p>
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
                    <div id="next" class="next-side">
                        <div id="" class="">
                            <div id="">
                                @foreach ($next_month_events as $event)
                                    <div class="event-card">
                                        {{-- <div style="font-size: 24px;">📅</div> --}}
                                        <div>
                                            <h3><img src="{{ asset('images/calendar.png') }}" loading='lazy'
                                                    alt=""><a href="{{ locale_route('events.show', ['slug' => $event->slug]) }}">
                                                    {{ $event->translated('title') }} <i>
                                                        @if ($event->translated('subtitle'))
                                                            ({{ $event->translated('subtitle') }})
                                                        @endif

                                                    </i></a></h3>
                                            <p>{!! Str::limit($event->translated('description'), 233) !!}</p>
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

{{--  --}}

@include('include.bookjs')


{{--  --}}
