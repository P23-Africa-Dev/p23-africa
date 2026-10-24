@extends('layouts.res-layout')
@section('title', __('resources.list_title'))

@section('meta')
    <meta name="description"
        content="{{ __('resources.meta') }}">
    <meta name="keywords"
        content="{{ __('resources.meta') }}">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator"
        content="{{ __('resources.meta_generator') }}">
    <meta property="og:image" content="{{ asset('images/archive-header.png') }}">
@endsection

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<style>
    .search-dropdown {
        background: white;
        border: 1px solid #ccc;
        max-height: 250px;
        overflow-y: auto;
        position: absolute;
        /* top: 50px; */
        z-index: 10;
        width: 80%;
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
</style>

@section('content')
    <section class="archive-list">
        <div class="container">

            <div class="search desktop mb-5">
                <div class="search-bar">
                    <input type="text" class="form-control" id="searchInput" placeholder="{{ __('resources.search_ph') }}"
                        autocomplete="off">
                    <div id="searchResults" class="search-dropdown d-none"></div>
                </div>
            </div>

            @if ($category)
                <h2>{{ __('events.showing', ['name' => $category->translated('name')]) }}</h2>
            @endif
            <!-- Blog Card 1 -->
            {{-- @foreach ($olderBlogs as $blog) --}}
            @foreach ($blogs as $blog)
                <a href="{{ locale_route('resource-show', ['slug' => $blog->slug]) }}" class="archive-link">
                    <div class="blog-card">
                        <div class="blog-text">
                            <div class="blog-category">{{ $blog->category?->translated('name') ?? __('resources.no_category') }}</div>
                            <div class="blog-title">{{ $blog->translated('title') }}</div>
                            <div class="blog-subtitle">
                                {{ $blog->translated('subtitle') }}
                            </div>
                            <div class="blog-meta">
                                {{ __('resources.written_by', ['name' => $blog->user->name]) }} &nbsp;&nbsp;&nbsp;&nbsp;
                                {{ \Carbon\Carbon::parse($blog->created_at)->format('l, F jS Y') }}
                            </div>
                        </div>
                        <img src="{{ asset('storage/' . $blog->image_path) }}" class="blog-image" alt="">
                        {{-- <div class="blog-image"
                            style="background-image: url('{{ asset('storage/' . $blog->image_path) }}');">
                        </div> --}}
                    </div>
                </a>
            @endforeach

            <div class="mt-4">
                {{ $blogs->withQueryString()->links('pagination::simple-bootstrap-5') }}
            </div>

            <br>
            <h4 class="mt-5">{{ __('resources.other_categories') }}</h4>
            <div class="category-list">
                @foreach ($otherCategories as $cat)
                    <a href="{{ locale_route('archive-list', ['category' => $cat->name]) }}"
                        class="mx-2">{{ $cat->translated('name') }}</a> *
                @endforeach
            </div>
            <!-- Blog Card 2 -->
            {{-- <div class="blog-card">
                <div class="blog-text">
                    <div class="blog-category">Blog Category</div>
                    <div class="blog-title">Growth-Focused Business</div>
                    <div class="blog-subtitle">
                        Buyer habits in Africa are evolving fast, driven by digital adoption and a growing middle class, but
                        differences across regions and trust issues still pose challenges.
                    </div>
                    <div class="blog-meta">
                        Written by John doe &nbsp;&nbsp;&nbsp;&nbsp; Monday May 20
                    </div>
                </div>
                <div class="blog-image" style="background-image: url('/images/archive-list-frame-blue.png');"></div>
            </div> --}}

            <!-- Call to Action -->
            <div class="cta-button">
                <a href="{{ locale_route('resource-hub') }}">{{ __('resources.latest') }}</a>
            </div>
        </div>
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
                url: '{{ locale_route('archive.search') }}',
                method: 'GET',
                data: {
                    query
                },
                success: function(response) {
                    if (response.html) {
                        $('#searchResults').html(response.html).removeClass('d-none');
                    } else {
                        $('#searchResults').html('<div class="p-2">{{ __('resources.no_results') }}</div>')
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
