@extends('layouts.res-layout')
@section('title', 'Archive | P23 Africa')

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<style>
    .search-dropdown {
        background: white;
        border: 1px solid #ccc;
        max-height: 250px;
        overflow-y: auto;
        position: absolute;
        top: 50px;
        z-index: 10;
        width: 100%;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .search-suggestion {
        padding: 10px;
        cursor: pointer;
        border-bottom: 2px solid #0d403644;
        padding-bottomom: 1rem;
        padding-top: 1rem;
    }

    .search-suggestion:hover {
        background-color: #f0f0f0;
    }

    .default-box {
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        padding: 20px;
        margin: 10px 0;
    }
</style>


@section('content')
    <section class="archive">
        <section class="archive-header">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 my-md-auto my-4">
                        <h1>Explore Our Archive of Business <span>Blogs</span> And <span>Reports</span></h1>

                        <div class="search desktop">
                            <div class="search-bar">
                                <input type="text" id="searchInput" placeholder="Search your preferred Blog"
                                    autocomplete="off">
                                <div id="searchResults" class="search-dropdown d-none"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img src="{{ asset('images/archive-header.png') }}" alt="">
                    </div>
                    <div class="search mobile">
                        <div class="search-bar">
                            <input type="text" id="searchInput" placeholder="Search your preferred Blog"
                                autocomplete="off">
                            <div id="searchResults" class="search-dropdown d-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @php
            $backgrounds = [
                'images/archive-card1.png',
                'images/archive-card2.png',
                'images/archive-card3.png',
                'images/archive-card4.png',
                'images/archive-card5.png',
            ];
        @endphp

        <section class="archive-card">
            <div class="container">
                <!-- Box 0 -->
                <div class="box" style="background-image: url('../images/archive-card0.png');">
                </div>

                @foreach ($categories as $index => $category)
                    @if ($index < 5)
                        <!-- Box 1 -->
                        <a href="{{ route('archive-list', ['category' => $category->name]) }}">
                            <div class="box" style="background-image: url('{{ asset($backgrounds[$index]) }}');">
                                <div class="text-wrapper {{ $index === 3 ? 'dark-text' : '' }}">
                                    <p class="title">{{ $category->name }}</p>
                                    <p class="description">{{ $category->description }}</p>
                                </div>
                            </div>
                        </a>
                    @else
                        <!-- Default Style for Remaining Categories -->
                        <a href="{{ route('archive-list', ['category' => $category->name]) }}">
                            <div class="box default-box">
                                <div class="text-wrapper">
                                    <p class="title">{{ $category->name }}</p>
                                    <p class="description">{{ $category->description }}</p>
                                </div>
                            </div>
                        </a>
                    @endif
                @endforeach
                <!-- Box 2 -->
                {{-- <div class="box" style="background-image: url('../images/archive-card2.png');">
                    <div class="text-wrapper">
                        <p class="title">Business Growth & Strategy</p>
                        <p class="description">Growth-oriented insights to help businesses scale effectively.</p>
                    </div>
                </div> --}}

                <!-- Box 3 -->
                {{-- <div class="box" style="background-image: url('../images/archive-card3.png');">
                    <div class="text-wrapper">
                        <p class="title">Referrals & Relationship</p>
                        <p class="description">Build long-term relationships with strategic insights on networking and
                            referrals.</p>
                    </div>
                </div> --}}

                <!-- Box 4 -->
                {{-- <div class="box" style="background-image: url('../images/archive-card4.png');">
                    <div class="text-wrapper dark-text">
                        <p class="title">Workshop & Event Highlights</p>
                        <p class="description">Event coverage and behind-the-scenes from P23’s exclusive business events.
                        </p>
                    </div>
                </div> --}}

                <!-- Box 5 -->
                {{-- <div class="box" style="background-image: url('../images/archive-card5.png');">
                    <div class="text-wrapper">
                        <p class="title">Case Studies & Success Stories</p>
                        <p class="description">Real-life stories and testimonials showing measurable success and strategic
                            value.</p>
                    </div>
                </div> --}}
            </div>

            <div class="container btn-cd">
                <a href="{{ route('archive-list') }}" class="btn">View all Archived Blogs</a>
            </div>
        </section>
    </section>
@endsection

<script>
    $(document).ready(function() {
        $('#searchInput').on('input', function() {
            let query = $(this).val().trim();

            if (query.length < 2) {
                $('#searchResults').addClass('d-none').empty();
                return;
            }

            $.ajax({
                url: '{{ route('archive.search') }}',
                method: 'GET',
                data: {
                    query
                },
                success: function(response) {
                    if (response.html) {
                        $('#searchResults').html(response.html).removeClass('d-none');
                    } else {
                        $('#searchResults').html('<div class="p-2">No results found</div>')
                            .removeClass('d-none');
                    }
                }
            });
        });

        $(document).click(function(e) {
            if (!$(e.target).closest('.search-bar').length) {
                $('#searchResults').addClass('d-none');
            }
        });
    });
</script>
