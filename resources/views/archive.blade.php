@extends('layouts.res-layout')
@section('title', 'Archive | P23 Africa')

@section('content')
    <section class="archive">
        <section class="archive-header">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 my-auto">
                        <h1>Explore Our Archive of Business <span>Blogs</span> And <span>Reports</span></h1>

                        <div class="search">
                            <form action="">
                                <input type="text" placeholder="Search your preferred Blog">
                                <button type="submit">Search</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img src="{{ asset('images/archive-header.png') }}" alt="">
                    </div>
                </div>
            </div>
        </section>

        <section class="archive-card">
            <div class="container">
                <!-- Box 0 -->
                <div class="box" style="background-image: url('../images/archive-card0.png');">
                    {{-- <div class="overlay-number overlay-light">01</div>
                    <div class="text-wrapper">
                        <p class="title">Leadership & Executive Insights</p>
                        <p class="description">Expert-driven articles designed to help professionals stay informed and lead
                            with impact.</p>
                    </div> --}}
                </div>

                <!-- Box 1 -->
                <div class="box" style="background-image: url('../images/archive-card1.png');">
                    {{-- <div class="overlay-number overlay-light">01</div> --}}
                    <div class="text-wrapper">
                        <p class="title">Leadership & Executive Insights</p>
                        <p class="description">Expert-driven articles designed to help professionals stay informed and lead
                            with impact.</p>
                    </div>
                </div>

                <!-- Box 2 -->
                <div class="box" style="background-image: url('../images/archive-card2.png');">
                    {{-- <div class="overlay-number overlay-light">02</div> --}}
                    <div class="text-wrapper">
                        <p class="title">Business Growth & Strategy</p>
                        <p class="description">Growth-oriented insights to help businesses scale effectively.</p>
                    </div>
                </div>

                <!-- Box 3 -->
                <div class="box" style="background-image: url('../images/archive-card3.png');">
                    {{-- <div class="overlay-number overlay-light">03</div> --}}
                    <div class="text-wrapper">
                        <p class="title">Referrals & Relationship</p>
                        <p class="description">Build long-term relationships with strategic insights on networking and
                            referrals.</p>
                    </div>
                </div>

                <!-- Box 4 -->
                <div class="box" style="background-image: url('../images/archive-card4.png');">
                    {{-- <div class="overlay-number overlay-dark">04</div> --}}
                    <div class="text-wrapper dark-text">
                        <p class="title">Workshop & Event Highlights</p>
                        <p class="description">Event coverage and behind-the-scenes from P23’s exclusive business events.
                        </p>
                    </div>
                </div>

                <!-- Box 5 -->
                <div class="box" style="background-image: url('../images/archive-card5.png');">
                    {{-- <div class="overlay-number overlay-light">05</div> --}}
                    <div class="text-wrapper">
                        <p class="title">Case Studies & Success Stories</p>
                        <p class="description">Real-life stories and testimonials showing measurable success and strategic
                            value.</p>
                    </div>
                </div>
            </div>

            <div class="container btn-cd">
                <a href="{{ route('archive-list') }}" class="btn">View all Archived Blogs</a>
            </div>
        </section>
    </section>
@endsection
