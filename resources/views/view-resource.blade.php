@extends('layouts.res-layout')

@section('title')
    {{ $blog->title }}
    @if ($blog->subtitle)
        ({{ $blog->subtitle }})
    @endif
    Resource Details | P23 Africa
@endsection

@section('meta')
    <meta property="og:image" content="{{ asset('storage/' . $blog->image_path) }}">
    <meta name="description" content="{!! nl2br(e($blog->content_1)) !!}">

    <meta name="keywords" content="{!! nl2br(e($blog->content_1)) !!}">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="{!! nl2br(e($blog->content_1)) !!}">
@endsection

@section('content')
    <section class="whole-view-resource">
        <div class="whole-header">
            <section class="container py-5">
                <!-- Heading Section -->
                <section class="resourse-details-header">
                    <div class="header">
                        {{-- <span>INTRO</span> --}}
                        <h2 class="section-title">{{ $blog->title }}</h2>
                        <p class="section-subtitle">
                            {{ $blog->subtitle }}
                        </p>
                    </div>
                </section>
            </section>
            <div class="side-image">
                <img src="{{ asset('images/view-resource-side.png') }}" loading='lazy' alt="">
            </div>
        </div>
        <section class="resourse-details-content">
            <div class="dotted-divider"></div>
            <!-- Article Section -->
            <section class="container py-5">
                <div class="row align-items-center">
                    <div class="col-12 my-5 mt-md-0">
                        {{-- <div class="article-title">Finibus Bonorum et Malorum</div> --}}
                        <div class="date-author">Written by {{ $blog->user->name }} &nbsp; | &nbsp;
                            {{ $blog->created_at->format('l, F jS Y') }}</div>
                        <div class="content">
                            <p>
                                {!! $blog->content_1 !!}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if ($blog->image_path)
                            <img src="{{ asset('storage/' . $blog->image_path) }}" loading='lazy' alt="Insight Image"
                                class="article-img" />
                            {{-- @else
                            <img src="{{ asset('images/resource-detail.png') }}" loading='lazy' alt="Insight Image"
                                class="article-img" /> --}}
                        @endif
                    </div>
                    <div class="col-md-6 mt-4 mt-md-0">
                        <div class="content">
                            <p>
                                {!! $blog->content_2 !!}
                            </p>
                            <div class="d-flex justify-content-between" style="margin-top: 30px;">
                                <a href="{{ route('resource-hub') }}" class="cta-button">Get More Insights <span
                                        class="mx-2"></span> →</a>

                                @if ($blog->pdf_path)
                                    <div class="my-auto">
                                        <a href="{{ asset('storage/' . $blog->pdf_path) }}" class="btn btn-success"
                                            download>
                                            📥 Download PDF
                                        </a>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </section>
    </section>
@endsection
