@extends('layouts.master')
@section('title', 'All Events | P23 Africa')

<head>
    <script src="https://cdn.tailwindcss.com"></script>

</head>


@section('content')
    <section class="all_event_hero content-section animate-on-scroll">
        <div id="all_event_hero_desktop">
            <img src="{{ asset('images/All-Desktopt-Event.png') }}" alt="Card image">
        </div>
        <div id="all_event_hero_mobile">
            <img src="{{ asset('images/All-Event-Mobile.png') }}" alt="Card image">
        </div>
    </section>


    <section class="track_events">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <!-- Mobile Tabs -->
            <div class="lg:hidden flex gap-4 mb-6">
                <button class="tab-btn px-4 py-2 rounded bg-[#073B3A] text-white font-semibold"
                    onclick="showTab('month')">Happening This Month</button>
                <button class="tab-btn px-4 py-2 rounded bg-gray-100 text-[#073B3A] font-semibold"
                    onclick="showTab('next')">Happening Next Month</button>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">

                <!-- Sidebar (desktop only) -->
                <div class="hidden lg:flex flex-col gap-4 w-1/3">
                    <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
                        <div class="text-3xl">📅</div>
                        <div>
                            <h4 class="font-bold text-lg">1st April - 30th April</h4>
                            <p class="text-green-600 font-semibold text-sm">Happening This Month</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
                        <div class="text-3xl">📅</div>
                        <div>
                            <h4 class="font-bold text-lg">1st April - 30th April</h4>
                            <p class="text-green-600 font-semibold text-sm">Happening Next Month</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
                        <div class="text-3xl">📅</div>
                        <div>
                            <h4 class="font-bold text-lg">1st April - 30th April</h4>
                            <p class="text-green-600 font-semibold text-sm">Happening In Two Month</p>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="flex-1">
                    <!-- This Month Events -->
                    <div id="month" class="tab-panel block">
                        <div class="bg-white rounded-lg shadow p-6 mb-6">
                            <div class="flex items-start gap-4">
                                <div class="text-3xl">📅</div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-[#073B3A]">Event Name</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor
                                        incididunt ut labore.
                                    </p>
                                    <div class="flex flex-wrap items-center mt-4 justify-between gap-4">
                                        <p class="text-green-600 font-semibold text-sm">Happening in 3hr</p>
                                        <p class="text-orange-600 font-bold text-sm">2:00PM - 3:30PM</p>
                                        <button
                                            class="border border-[#073B3A] text-[#073B3A] px-4 py-2 rounded flex items-center gap-2 hover:bg-[#073B3A] hover:text-white transition text-sm">
                                            Book A Seat <span>→</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Copy this block for more events -->
                    </div>

                    <!-- Next Month Events -->
                    <div id="next" class="tab-panel hidden">
                        <div class="bg-white rounded-lg shadow p-6 mb-6">
                            <div class="flex items-start gap-4">
                                <div class="text-3xl">📅</div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-[#073B3A]">Next Month Event</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                                        veniam.
                                    </p>
                                    <div class="flex flex-wrap items-center mt-4 justify-between gap-4">
                                        <p class="text-green-600 font-semibold text-sm">Happening in 3 Days</p>
                                        <p class="text-orange-600 font-bold text-sm">4:00PM - 5:30PM</p>
                                        <button
                                            class="border border-[#073B3A] text-[#073B3A] px-4 py-2 rounded flex items-center gap-2 hover:bg-[#073B3A] hover:text-white transition text-sm">
                                            Book A Seat <span>→</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- More events go here -->
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
