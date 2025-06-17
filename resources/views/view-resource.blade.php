@extends('layouts.res-layout')
@section('title', 'Resource Details | P23 Africa')

@section('content')
    <section class="whole-view-resource">
        <section class="container py-5">
            <!-- Heading Section -->
            <section class="resourse-details-header">
                <div class="header">
                    <span>INTRO</span>
                    <h2 class="section-title">{{ $blog->title }}</h2>
                    <p class="section-subtitle">
                        {{ $blog->subtitle }}
                    </p>
                </div>
            </section>

            <div class="dotted-divider"></div>

            <!-- Article Section -->
            <section class="resourse-details-content">
                <div class="row align-items-center">
                    <div class="col-12 my-5 mt-md-0">
                        {{-- <div class="article-title">Finibus Bonorum et Malorum</div> --}}
                        <div class="date-author">Written by {{ $blog->user->name }} &nbsp; | &nbsp;
                            {{ $blog->created_at->format('l, F jS Y') }}</div>
                        <div class="content">
                            <p>
                                {!! nl2br(e($blog->content_1)) !!}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if ($blog->image_path)
                            <img src="{{ asset('storage/' . $blog->image_path) }}" alt="Insight Image" class="article-img" />
                        @else
                            <img src="{{ asset('images/resource-detail.png') }}" alt="Insight Image" class="article-img" />
                        @endif
                    </div>
                    <div class="col-md-6 mt-4 mt-md-0">
                        <div class="content">
                            <p>
                                {!! nl2br(e($blog->content_2)) !!}
                            </p>
                            <a href="{{ route('resource-hub') }}" class="cta-button">Get More Insights →</a>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </section>
@endsection
