@extends('layouts.res-layout')
@section('title', 'Archive List | P23 Africa')

@section('meta')
    <meta name="description"
        content="Explore Expert Articles, Market Trends, And Practical Advice To Scale Your Business Across Africa And Beyond.">
    <meta name="keywords"
        content="Explore Expert Articles, Market Trends, And Practical Advice To Scale Your Business Across Africa And Beyond.">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator"
        content="Explore Expert Articles, Market Trends, And Practical Advice To Scale Your Business Across Africa And Beyond | P23 Africa">
    <meta property="og:image" content="{{ asset('images/archive-header.png') }}">
@endsection





@section('content')
    <section class="archive-list">
        <div class="container">

            <div class="search desktop mb-5">
                <div class="search-bar">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search your preferred Blog"
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
                            <div class="blog-category">{{ $blog->category?->translated('name') ?? 'No Category' }}</div>
                            <div class="blog-title">{{ $blog->translated('title') }}</div>
                            <div class="blog-subtitle">
                                {{ $blog->translated('subtitle') }}
                            </div>
                            <div class="blog-meta">
                                Written by {{ $blog->user->name }} &nbsp;&nbsp;&nbsp;&nbsp;
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
            <h4 class="mt-5">Other Categories:</h4>
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
                <a href="{{ locale_route('resource-hub') }}">Get Latest Insights →</a>
            </div>
        </div>
    </section>
@endsection


