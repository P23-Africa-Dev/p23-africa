@extends('layouts.res-layout')
@section('title', 'Resource Hub | P23 Africa')

@section('meta')
    <meta name="description"
        content="Explore Expert Articles, Market Trends, And Practical Advice To Scale Your Business Across Africa And Beyond.">
    <meta name="keywords"
        content="Explore Expert Articles, Market Trends, And Practical Advice To Scale Your Business Across Africa And Beyond.">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator"
        content="Explore Expert Articles, Market Trends, And Practical Advice To Scale Your Business Across Africa And Beyond | P23 Africa">
    <meta property="og:image" content="{{ asset('images/resource-header.png') }}">
@endsection

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

@section('content')
    <section class="whole-resource">
        <!-- Blog Section -->
        <section class="blog-sec">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 text-start mb-5 px-md-4 mb-lg-0">
                        <div class="mob">
                            <img src="{{ asset('images/resource-hub.png') }}" loading='lazy' class="mb-3" alt="">
                            <img src="{{ asset('images/rectangle-line.png') }}" loading='lazy' alt="">
                        </div>
                        <div class="blog-date">1st May 2025</div>
                        <h2 class="blog-title">Understanding Buyer Habits</h2>
                        <p class="blog-description">
                            Buyer habits in Africa are evolving fast, driven by digital adoption and a growing middle class,
                            but differences across regions and trust issues still pose challenges.
                        </p>
                        <a href="#" class="btn btn-custom mt-4">
                            Read Blog
                            <span>&rarr;</span>
                        </a>
                    </div>
                    <div class="col-lg-6 px-md-5 d-flex justify-content-center">
                        <div class="image-container">
                            <img src="{{ asset('images/resource-header-cropped.png') }}" loading='lazy' alt="Woman Image" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <style>
            .feedback-box {
                background-color: #f2f2f2;
                border-radius: 16px;
                padding: 30px 40px;
                text-align: center;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .feedback-box h2 {
                margin-bottom: 20px;
                font-size: 20px;
                color: #333;
            }

            .star-rating {
                display: flex;
                justify-content: center;
                gap: 10px;
            }

            .star {
                font-size: 40px;
                color: #ccc;
                cursor: pointer;
                transition: color 0.2s ease;
            }

            .star.hover,
            .star.selected {
                color: #ffc107;
            }


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

        <script>
            const stars = document.querySelectorAll('.star');
            let selectedRating = 0;

            stars.forEach((star, index) => {
                // Hover effect
                star.addEventListener('mouseover', () => {
                    highlightStars(index);
                });

                // Remove hover effect
                star.addEventListener('mouseout', () => {
                    resetStars();
                });

                // Click to select rating
                star.addEventListener('click', () => {
                    selectedRating = index + 1;
                    updateSelectedStars();
                    // Optional: Send to backend or log
                    console.log("Selected Rating:", selectedRating);
                });
            });

            function highlightStars(index) {
                for (let i = 0; i <= index; i++) {
                    stars[i].classList.add('hover');
                }
            }

            function resetStars() {
                stars.forEach(star => star.classList.remove('hover'));
            }

            function updateSelectedStars() {
                stars.forEach((star, idx) => {
                    star.classList.toggle('selected', idx < selectedRating);
                });
            }
        </script>


        <section class="mind-behind-insight reveal-section">
            <div class="container py-5">
                <div class="section-title">Minds Behind the Insights</div>

                <!-- Desktop View -->
                <div class="row carousel-desktop">
                    <div class="col-md-4 mb-4">
                        <div class="team-card first">
                            <div class="team-name">Timothy<br>Danver</div>
                            <img src="{{ asset('images/first-mind.png') }}" loading='lazy' alt="Person 1">
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="team-card mid">
                            <div class="team-name">Simi<br>Adeniran</div>
                            <img src="{{ asset('images/second-mind.png') }}" loading='lazy' alt="Person 2">
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="team-card third">
                            <div class="team-name">Grace<br>Mwangi</div>
                            <img src="{{ asset('images/third-mind.png') }}" loading='lazy' alt="Person 3">
                        </div>
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="carousel-mobile">
                    <div class="carousel-container">
                        <div class="carousel-inner-custom" id="carouselSlides">
                            <div class="carousel-item-custom">
                                <div class="team-card first">
                                    <div class="team-name">Timothy<br>Danver</div>
                                    <img src="{{ asset('images/first-mind.png') }}" loading='lazy' alt="Person 1">
                                </div>
                            </div>
                            <div class="carousel-item-custom">
                                <div class="team-card mid">
                                    <div class="team-name">Simi<br>Adeniran</div>
                                    <img src="{{ asset('images/second-mind.png') }}" loading='lazy' alt="Person 2">
                                </div>
                            </div>
                            <div class="carousel-item-custom">
                                <div class="team-card">
                                    <div class="team-name">Grace<br>Mwangi</div>
                                    <img src="{{ asset('images/third-mind.png') }}" loading='lazy' alt="Person 3">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Arrow Buttons -->
                    <div class="arrow-btns">
                        <button class="arrow-btn" onclick="slideLeft()">←</button>
                        <button class="arrow-btn" onclick="slideRight()">→</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="blog">
            <div class="container blog-section">
                <div class="row2">
                    <div class="column">
                        <div class="py-5">
                            <div class="blog-title text-uppercase mb-2">Our Most Recent Blogs</div>
                            <div class="blog-subtitle">Explore Expert Articles, Market Trends, And Practical Advice To
                                Scale
                                Your
                                Business
                                Across Africa And Beyond.</div>
                        </div>

                        @foreach ($recentBlogs->take(3) as $index => $blog)
                            {{-- @if ($index < 3) --}}
                            @php
                                $bgClasses = [
                                    'blog-blue', // Green
                                    'blog-yellow', // Red
                                    'blog-green', // Blue
                                ];
                            @endphp

                            <div class="blog-card {{ $bgClasses[$index] }}">
                                <div class="date">{{ \Carbon\Carbon::parse($blog->created_at)->format('l, F jS Y') }}
                                </div>
                                <div class="heading">{{ $blog->title }}</div>
                                <div class="content mb-4">
                                    {!! \Illuminate\Support\Str::limit($blog->content_1, 600) !!}
                                </div>


                                @if ($blog->pdf_path)
                                    <div class="my-auto">
                                        <button class="btn btn-success view-pdf-btn"
                                            data-pdf="{{ asset('storage/' . $blog->pdf_path) }}">
                                            📥 View Report
                                        </button>
                                    </div>
                                @else
                                    <a href="{{ route('resource-show', $blog->slug) }}" class="read-more-btn">Read More
                                        →</a>
                                @endif
                            </div>
                            {{-- @endif --}}
                        @endforeach
                    </div>
                    <div class="column">
                        @foreach ($recentBlogs->slice(3, 3) as $index => $blog)
                            {{-- @if ($index >= 3 && $index < 6) --}}
                            @php
                                $bgClasses = [
                                    'blog-green', // Green
                                    'blog-pink', // Red
                                    'blog-blue', // Blue
                                ];
                                $bgIndex = $index - 3;
                            @endphp
                            <div class="blog-card {{ $bgClasses[$bgIndex] }}">
                                <div class="date">{{ \Carbon\Carbon::parse($blog->created_at)->format('l, F jS Y') }}
                                </div>
                                <div class="heading">{{ $blog->title }}</div>
                                <div class="content mb-4">
                                    {!! \Illuminate\Support\Str::limit($blog->content_1, 1200) !!}
                                </div>
                                 @if ($blog->pdf_path)
                                    <div class="my-auto">
                                        <button class="btn btn-success view-pdf-btn"
                                            data-pdf="{{ asset('storage/' . $blog->pdf_path) }}">
                                            📥 View Report
                                        </button>
                                    </div>
                                @else
                                    <a href="{{ route('resource-show', $blog->slug) }}" class="read-more-btn">Read More
                                        →</a>
                                @endif
                            </div>
                            {{-- @endif --}}
                        @endforeach

                        <div class="">
                            <a href="{{ route('archive-page') }}" class="btn btn-archive"><span>View Archived Blogs
                                </span> <span class="mx-2"></span>
                                <span> →</span></a>
                        </div>
                    </div>
                </div>
            </div>

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
                        button.addEventListener('click', function() {
                            const pdfUrl = this.dataset.pdf;
                            pdfViewer.src = pdfUrl;
                            downloadBtn.href = pdfUrl;
                            popup.style.display = 'block';
                            document.body.style.overflow = 'hidden';
                        });
                    });

                    closeBtn.addEventListener('click', function() {
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
    </section>
@endsection

@include('include.resourse-js')
