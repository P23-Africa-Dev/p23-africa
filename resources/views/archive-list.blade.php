@extends('layouts.res-layout')
@section('title', 'Archive List | P23 Africa')

@section('content')
    <section class="archive-list">
        <div class="container">

            @if ($category)
                <h2>Showing results for "{{ $category->name }}"</h2>
            @endif
            <!-- Blog Card 1 -->
            {{-- @foreach ($olderBlogs as $blog) --}}
            @foreach ($blogs as $blog)
                <div class="blog-card">
                    <div class="blog-text">
                        <div class="blog-category">Category name</div>
                        <div class="blog-title"><a href="{{ route('resource-show', $blog->slug) }}"
                                class="">{{ $blog->title }}</a></div>
                        <div class="blog-subtitle">
                            {{ $blog->subtitle }}
                        </div>
                        <div class="blog-meta">
                            Written by {{ $blog->user->name }} &nbsp;&nbsp;&nbsp;&nbsp;
                            {{ \Carbon\Carbon::parse($blog->created_at)->format('l, F jS Y') }}
                        </div>
                    </div>
                    <div class="blog-image" style="background-image: url('{{ asset('storage/' . $blog->image_path) }}');">
                    </div>
                </div>
            @endforeach

            <div class="mt-4">
                {{ $blogs->withQueryString()->links('pagination::simple-bootstrap-5') }}
            </div>

            <br>
            <h4 class="mt-5">Other Categories:</h4>
            <div class="category-list">
                @foreach ($otherCategories as $cat)
                    <a href="{{ route('archive-list', ['category' => $cat->name]) }}" class="mx-2">{{ $cat->name }}</a> 
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
                <a href="{{ route('resource-hub') }}">Get Latest Insights →</a>
            </div>
        </div>
    </section>
@endsection
