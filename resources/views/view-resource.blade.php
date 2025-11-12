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

<style>
    /* Resource Report PDF */
    .resourse-details-content .cta-button.pdf {
        padding: 10px 25px;
        font-weight: 600;
        font-size: 14px;
        border: 2px solid #1b3a2b;
        background-color: transparent;
        color: #1b3a2b;
        transition: 0.3s ease;
        border-radius: 0px;
        text-decoration: none;
        display: inline-block;
        letter-spacing: 1px;
    }

    .resourse-details-content .view-pdf-btn {
        padding: 10px 25px;
        font-weight: 600;
        font-size: 14px;
        border: 2px solid #1b3a2b;
        background-color: #1b3a2b;
        color: #fff;
        transition: 0.3s ease;
        border-radius: 0px;
        text-decoration: none;
        display: inline-block;
        letter-spacing: 1px;
    }

    .download-btn {
        display: inline-block;
        padding: 8px 16px;
        background: #28a745;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .download-btn:hover {
        background: #218838;
        color: white;
    }
</style>

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
                            @if ($blog->pdf_path)
                                <a href="{{ asset('storage/' . $blog->pdf_path) }}" target="_blank"
                                    class="text-danger"><i>Read Complete Report</i></a>
                            @endif
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
            <section class="container py-lg-5 py-md-4 py-3">
                <div class="row align-items-center">
                    <div class="col-12 my-md-5 my-lg-5 my-0 mt-md-0">
                        {{-- <div class="article-title">Finibus Bonorum et Malorum</div> --}}
                        @if (!$blog->pdf_path)
                            <div class="date-author">Written by {{ $blog->user->name }} &nbsp; | &nbsp;
                                {{ $blog->created_at->format('l, F jS Y') }}</div>
                            <div class="content">
                                <p>
                                    {!! $blog->content_1 !!}
                                </p>
                            </div>
                        @else
                            <div class="content">
                                <p>
                                    {!! $blog->content_1 !!}
                                </p>
                            </div>
                            <div class="date-author">Written by {{ $blog->user->name }} &nbsp; | &nbsp;
                                {{ $blog->created_at->format('l, F jS Y') }}</div>
                        @endif
                    </div>
                    <div class="col-12">
                        <div class="content-wrapper" style="position: relative;">
                            @if ($blog->image_path)
                                <img src="{{ asset('storage/' . $blog->image_path) }}" loading='lazy' alt="Insight Image"
                                    class="article-img float-md-end ms-md-4 mb-3"
                                    style="max-width: 100%; width: 100%; max-width: 450px; height: auto; border-radius: 8px;" />
                            @endif
                            <div class="content">
                                <p>
                                    {!! $blog->content_2 !!}
                                </p>
                                @if (!$blog->pdf_path)
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3"
                                        style="margin-top: 30px; clear: both;">
                                        <a href="{{ route('resource-hub') }}" class="cta-button">Get More Insights <span
                                                class="mx-2"></span> →</a>
                                    </div>
                                @else
                                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3"
                                        style="margin-top: 30px; clear: both;">
                                        <div>
                                            <a href="{{ asset('storage/' . $blog->pdf_path) }}" download=""
                                                class="view-pdf-btn">
                                                Download Report <span class="mx-2"></span> →
                                            </a>
                                        </div>

                                        <a href="{{ route('resource-hub') }}" class="cta-button pdf">Get More Insights
                                            <span class="mx-2"></span> →</a>
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
