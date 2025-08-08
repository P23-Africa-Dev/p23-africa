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
    .pdf-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 1000;
        padding: 20px;
    }

    .pdf-popup-content {
        background: white;
        width: 100%;
        max-width: 1000px;
        height: 90vh;
        margin: 20px auto;
        position: relative;
        border-radius: 8px;
        padding: 20px;
    }

    .pdf-viewer {
        width: 100%;
        height: calc(100% - 50px);
        border: none;
    }

    .close-popup {
        position: absolute;
        right: 20px;
        top: 10px;
        font-size: 24px;
        cursor: pointer;
        color: #333;
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
                                        <button class="btn btn-success view-pdf-btn"
                                            data-pdf="{{ asset('storage/' . $blog->pdf_path) }}">
                                            📥 View Report
                                        </button>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </section>

        <div class="pdf-popup" id="pdfPopup">
            <div class="pdf-popup-content">
                <span class="close-popup">&times;</span>
                <a href="" class="download-btn" id="downloadPdf" download>
                    📥 Download PDF
                </a>
                <iframe class="pdf-viewer" id="pdfViewer" src="" frameborder="0"></iframe>
            </div>
        </div>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const viewButtons = document.querySelectorAll('.view-pdf-btn');
                const popup = document.getElementById('pdfPopup');
                const pdfViewer = document.getElementById('pdfViewer');
                const downloadBtn = document.getElementById('downloadPdf');
                const closeBtn = document.querySelector('.close-popup');

                viewButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault(); // Prevent any default behavior
                        e.stopPropagation(); // Stop event bubbling

                        const pdfUrl = this.getAttribute('data-pdf');
                        pdfViewer.src = pdfUrl;
                        downloadBtn.href = pdfUrl;
                        popup.style.display = 'block';
                        document.body.style.overflow = 'hidden';
                    });
                });

                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    popup.style.display = 'none';
                    pdfViewer.src = '';
                    document.body.style.overflow = 'auto';
                });

                popup.addEventListener('click', function(e) {
                    if (e.target === popup) {
                        popup.style.display = 'none';
                        pdfViewer.src = '';
                        document.body.style.overflow = 'auto';
                    }
                });
            });
        </script>
    </section>
@endsection
