@extends('layouts.sales-layout')
@section('title', __('sales_page.title'))
@section('meta')
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="description"
        content="{{ __('sales_page.meta') }}">
    <meta name="keywords"
        content="{{ __('sales_page.meta') }}">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="{{ __('sales_page.meta_generator') }}">
    <meta property="og:image" content="{{ asset('images/intro-bg.png') }}">
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>

</style>

@section('content')
    <section class="sales">
        <section class="sales-growth-section">
            <div class="content-row">
                <div class="container mx-auto">
                    <div class="row">
                        <div class="col-12 col-lg-8 text-left text-lg-start mb-4 mb-lg-0">
                            <h1 class="fw-bold text-white">
                                {{ __('sales_page.stop') }} <span>{{ __('sales_page.leads') }}</span>
                            </h1>
                            <h2 class="text-white">{{ __('sales_page.start_closing') }} <span>{{ __('sales_page.deals') }}</span></h2>
                        </div>
                        <div class="col-12 col-lg-4 text-left text-lg-start">
                            <div class="sales-divider"></div>
                            <div class="sales-toolkit-info text-white d-flex flex-column align-items-start">
                                <p class="lead-sm mb-3">
                                    <strong>{{ __('sales_page.toolkit') }}</strong>
                                    {{ __('sales_page.toolkit_line') }}
                                </p>
                                <div class="row text-center stats-row">
                                    <div class="col-4">
                                        <h3 class="fw-bold mb-0 numbers" id="countries">05 +</h3>
                                        <p class="text-uppercase">{{ __('sales_page.countries') }}</p>
                                    </div>
                                    <div class="col-4">
                                        <h3 class="fw-bold mb-0 numbers" id="businessess">1000 +</h3>
                                        <p class="text-uppercase">{{ __('sales_page.businesses') }}</p>
                                    </div>
                                    <div class="col-4">
                                        <h3 class="fw-bold mb-0 numbers" id="partnerships">100 +</h3>
                                        <p class="text-uppercase">{{ __('sales_page.partnership') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="">
                    <div class="col-12 p-0">

                        {{-- <div class="video-fade-overlay video-fade-right"></div>
                        <div class="video-fade-overlay video-fade-left"></div> --}}

                        <div class="video-container position-relative">
                            <img src="{{ asset('images/intro-bg.png') }}" alt="{{ __('sales_page.alt_video') }}"
                                class="img-fluid d-none d-md-block">
                            <img src="{{ asset('images/intro-bg-mobile.png') }}" alt="{{ __('sales_page.alt_video') }}"
                                class="img-fluid d-block d-md-none">

                            <div class="overlay d-flex justify-content-center align-items-center">
                                <!-- Desktop Play Button -->
                                <button class="play-button btn bg-transparent border-0 p-0 d-none d-md-block"
                                    aria-label="{{ __('sales_page.play') }}" id="play-video-desktop">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#fff"
                                        class="bi bi-play-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z" />
                                    </svg>
                                </button>

                                <!-- Mobile Play Button -->
                                <button class="play-button btn bg-transparent border-0 p-0 d-block d-md-none"
                                    aria-label="{{ __('sales_page.play') }}" id="play-video-mobile">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#fff"
                                        class="bi bi-play-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Bootstrap Modal for Video -->
                        <!-- Single Bootstrap Modal -->
                        <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered custom-modal">
                                <div class="modal-content bg-dark border-0">
                                    <div class="modal-header border-0">
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="{{ __('sales_page.close') }}"></button>
                                    </div>
                                    <div class="modal-body p-0">
                                        <div class="ratio ratio-16x9">
                                            <iframe id="videoIframe" src="" title="YouTube video"
                                                allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="how-it-works-section py-5 reveal-section">
            <div class="container py-lg-5">
                <div class="row text-left mb-5 works-heading">
                    <div class="col-12">
                        <p class="text-uppercase mb-2">{{ __('sales_page.how') }}</p>
                        <h2 class="">{{ __('sales_page.simple') }}</h2>
                    </div>
                </div>

                <div class="mobile">
                    <img src="{{ asset('images/work-card-mobile.png') }}" loading="lazy" alt="{{ __('sales_page.alt_card') }}">
                </div>

                <div class="desktop">
                    <div class="row justify-content-center g-2 pb-5">
                        <div class="col-12 col-md-6 col-lg-3 d-flex">
                            <img src="{{ asset('images/work-card1.png') }}" loading="lazy" alt="{{ __('sales_page.alt_card') }}">
                        </div>

                        <div class="col-12 col-md-6 col-lg-3 d-flex">
                            <img src="{{ asset('images/work-card2.png') }}" loading="lazy" alt="{{ __('sales_page.alt_card') }}">
                        </div>

                        <div class="col-12 col-md-6 col-lg-3 position-relative">
                            <img src="{{ asset('images/work-card3.png') }}" loading="lazy" alt="{{ __('sales_page.alt_card') }}">
                        </div>

                        <div class="col-12 col-md-6 col-lg-3 d-flex">
                            <img src="{{ asset('images/work-card4.png') }}" loading="lazy" alt="{{ __('sales_page.alt_card') }}">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="business-audit reveal-section">
            <div class="container">
                <div class="row d-flex flex-lg-row flex-column align-items-start">
                    <div class="col-12 col-lg-6 mb-5 mb-lg-0 text-left text-lg-start">
                        <div class="text-section">
                            <h1>{{ __('sales_page.audit_title') }}</h1>
                            <p>
                                {{ __('sales_page.audit_body') }}
                            </p>
                            <a href="https://calendar.google.com/calendar/u/0/appointments/schedules/AcZssZ3tzdnFU-zme-lCpKoHGZktqjUVs4rll_QJtcUzK71d9-l3M0WIFvjtnJpLmosUraV8XkUJeD_k"
                                target="_blank" class="btn custom-button">
                                {{ __('sales_page.audit_cta') }}
                            </a>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 d-flex justify-content-center">
                        <div class="image-container">
                            <img src="{{ asset('images/business-audit.png') }}" loading="lazy"
                                alt="{{ __('sales_page.alt_team') }}" class="img-fluid" />
                            {{-- <p class="image-description">
                                Built for African Teams. Proven to Grow Sales. Our clients have
                                seen up to 68% increase in sales using this system. We built
                                this for real businesses—no expensive software, no complex CRM.
                            </p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="offer-section reveal-section">
            <div class="container">
                <h2>{{ __('sales_page.offer') }}</h2>
                <div class="row justify-content-center">
                    <div class="desktop">
                        <img src="{{ asset('images/sales-count-desktop.png') }}" loading="lazy" alt="{{ __('sales_page.alt_offer') }}">
                    </div>
                    <div class="mobile">
                        <img src="{{ asset('images/sales-count-mobile.png') }}" loading="lazy" alt="{{ __('sales_page.alt_offer') }}">
                    </div>
                    {{-- <div class="col-6 col-md-6 col-lg-3">
                        <div class="offer-item">
                            <div class="number">01</div>
                            <div class="dot"></div>
                            <p>B2B service businesses</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-lg-3">
                        <div class="offer-item">
                            <div class="number">02</div>
                            <div class="dot"></div> 
                            <p style="font-size: 29px;">Fast-growing teams</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-lg-3">
                        <div class="offer-item">
                            <div class="number">03</div>
                            <div class="dot"></div>
                            <p>Fintech & tech-enabled startups</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-lg-3">
                        <div class="offer-item">
                            <div class="number">04</div>
                            <div class="dot"></div>
                            <p>Sales teams without a working system</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </section>

        <section class="clients-section reveal-section">
            <div class="container">
                <h2>{{ __('sales_page.clients') }}</h2>
            </div>
        </section>

        <div class="clients-section-main">
            <div class="container clients-container">
                <div class="clients-wrapper">
                    <div class="clients">
                        <div class="client-card">
                            <div class="mobile">
                                <img src="{{ asset('images/test1.png') }}" loading="lazy" alt="{{ __('sales_page.alt_testimonial') }}">
                            </div>
                            <div class="image-wrapper">
                                <img src="{{ asset('images/testimony-3.png') }}" loading="lazy" alt="Client 1"
                                    class="" />
                                <div class="overlay">
                                    <div class="overlay-text">
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span> <br><br>
                                        {{ __('sales_page.quote_juliet') }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between my-auto align-items-center w-full px-md-2 px-4">
                                <h4>Juliet Chukwudi</h4>
                                <p>{{ __('sales_page.role_juliet') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="clients">
                        <div class="client-card">
                            <div class="mobile">
                                <img src="{{ asset('images/test2.png') }}" loading="lazy" alt="{{ __('sales_page.alt_testimonial') }}">
                            </div>
                            <div class="image-wrapper">
                                <img src="{{ asset('images/testimony-4.png') }}" loading="lazy" alt="Client 2"
                                    class="" />
                                <div class="overlay">
                                    <div class="overlay-text">
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span> <br><br>
                                        {{ __('sales_page.quote_james') }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between my-auto align-items-center w-full px-md-2 px-4">
                                <h4>James Owoicho</h4>
                                <p>{{ __('sales_page.role_james') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="clients">
                        <div class="client-card last">
                            <div class="mobile">
                                <img src="{{ asset('images/test3.png') }}" loading="lazy" alt="{{ __('sales_page.alt_testimonial') }}">
                            </div>
                            <div class="image-wrapper">
                                <img src="{{ asset('images/client-last.jpg') }}" loading="lazy" alt="Client 3"
                                    class="" />
                                <div class="overlay">
                                    <div class="overlay-text">
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span> <br><br>
                                        {{ __('sales_page.quote_vera') }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between my-auto align-items-center w-full px-md-2 px-4">
                                <h4>Vera Agugam</h4>
                                <p>{{ __('sales_page.role_vera') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="slider-controls">
                    <span class="arrow left"><i><img src="{{ asset('images/left.png') }}" loading='lazy'
                                alt=""></i></span>
                    <span class="arrow right"><i><img src="{{ asset('images/right.png') }}" loading='lazy'
                                alt=""></i></span>
                </div>
            </div>
        </div>

        <section class="trained-teams-section reveal-section">
            <div class="container">
                <div class="line-top"></div>
                <h2>{{ __('sales_page.trained') }} <strong>{{ __('sales_page.teams') }}</strong></h2>
                <div class="line-bottom"></div>
            </div>
        </section>

        <section class="lead-section reveal-section">
            <div class="container-fluid">
                <div class="row g-0">
                    <div class="col-md-6 col-lg-5 d-none d-md-block">
                        <div class="image-box">
                            <img src="{{ asset('images/cta-bg.png') }}" loading="lazy" class="image1"
                                alt="{{ __('sales_page.alt_people') }}" />
                        </div>
                        <div class="image-box2">
                            <img src="{{ asset('images/cta-bg.png') }}" loading="lazy" class="image2"
                                alt="{{ __('sales_page.alt_people') }}" />
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-7">
                        <div class="content-box">
                            <div class="pricing">
                                <div id="pack">
                                    <h2>{{ __('sales_page.packages') }}</h2>
                                    {{-- <hr> --}}
                                </div>
                                <div class="price">
                                    <div class="row col-md-10">
                                        <div class="col-6">
                                            <div class="card px-2 px-md-4 py-3 mx-0 d-flex flex-column h-100 w-100">
                                                <div>
                                                    <strong>{{ __('sales_page.standard') }}</strong>
                                                    <p>
                                                        {{ __('sales_page.standard_body') }}
                                                    </p>
                                                    <b>{{ __('sales_page.includes') }}</b>
                                                    <ul class="para">
                                                        <li>{{ __('sales_page.std_1') }}
                                                        </li>
                                                        <li>{{ __('sales_page.std_2') }}</li>
                                                        <li>{{ __('sales_page.std_3') }}</li>
                                                        <li>{{ __('sales_page.std_4') }}</li>
                                                        <li>{{ __('sales_page.std_5') }}</li>
                                                    </ul>
                                                </div>


                                                <div class="mt-auto">
                                                    <span class="d-flex">
                                                        <h1>$280</h1><small>{{ __('sales_page.one_time') }}</small>
                                                    </span>
                                                    <a href="#" class="btn w-100">{{ __('sales_page.standard') }}</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6 d-flex">
                                            <div class="card px-2 px-md-4 py-3 mx-0 d-flex flex-column h-100 w-100"
                                                id="pro">
                                                <div>
                                                    <strong>{{ __('sales_page.pro') }}</strong>
                                                    <p>
                                                        {{ __('sales_page.pro_body') }}
                                                    </p>

                                                    <b>{{ __('sales_page.includes') }}</b>
                                                    <ul>
                                                        <li>{{ __('sales_page.pro_1') }}</li>
                                                        <li>{{ __('sales_page.pro_2') }}</li>
                                                        <li>{{ __('sales_page.pro_3') }}</li>
                                                        <li>{{ __('sales_page.pro_4') }}</li>
                                                        <li>{{ __('sales_page.pro_5') }}</li>
                                                        <li>{{ __('sales_page.pro_6') }}</li>
                                                    </ul>
                                                </div>

                                                <div class="mt-auto">
                                                    <div class="d-flex">
                                                        <h1>$500</h1><small>{{ __('sales_page.one_time') }}</small>
                                                    </div>
                                                    <a href="#" class="btn w-100">{{ __('sales_page.pro') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <small>Starting from: </small>
                            <h1>$280</h1><br> --}}
                            <h2>{{ __('sales_page.ready') }}</h2>
                            <p>
                                {{ __('sales_page.ready_body') }}
                            </p>
                            <a href="https://calendar.google.com/calendar/u/0/appointments/schedules/AcZssZ3tzdnFU-zme-lCpKoHGZktqjUVs4rll_QJtcUzK71d9-l3M0WIFvjtnJpLmosUraV8XkUJeD_k"
                                target="_blank" class="cta-btn">
                                {{ __('sales_page.claim') }}
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pattern-shape pattern-top-right">
                <img src="{{ asset('images/Vector -cta.png') }}" loading="lazy" alt="" />
            </div>
            <div class="pattern-shape pattern-bottom-right">
                <img src="{{ asset('images/Vector -cta.png') }}" loading="lazy" alt="" />
            </div>
        </section>
    </section>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const desktopVideoURL = "https://www.youtube.com/embed/l8JvWxEFVW0?autoplay=1";
        const mobileVideoURL = "https://www.youtube.com/embed/unzc3SdI4dg?autoplay=1";

        const videoModalEl = document.getElementById('videoModal');
        const videoIframe = document.getElementById('videoIframe');
        const videoModal = new bootstrap.Modal(videoModalEl);

        document.getElementById('play-video-desktop').addEventListener('click', function() {
            videoIframe.src = desktopVideoURL;
            videoModal.show();
        });

        document.getElementById('play-video-mobile').addEventListener('click', function() {
            videoIframe.src = mobileVideoURL;
            videoModal.show();
        });

        videoModalEl.addEventListener('hidden.bs.modal', function() {
            videoIframe.src = "";
        });
    });






    $(document).ready(function() {
        // Define range flows
        const ranges = {
            countries: ["00", "05", 10, 15, 20],
            businessess: [500, 1000, 1500, 2000, 3000],
            partnerships: [100, 300, 600, 800, 1000]
        };

        // Animation function
        function animateNumbers(id, values, interval = 500) {
            let index = 0;

            function update() {
                if (index < values.length) {
                    $(`#${id}`).text(values[index] + " +");
                    index++;
                    setTimeout(update, interval);
                }
            }
            update();
        }

        // Trigger animations
        animateNumbers("countries", ranges.countries);
        animateNumbers("businessess", ranges.businessess);
        animateNumbers("partnerships", ranges.partnerships);
    });


    $(function() {
        const $wrapper = $('.clients-wrapper');
        const $testimonials = $('.clients');
        const total = $testimonials.length;
        let currentIndex = 0;

        function updateWidths() {
            const screenWidth = $(window).width();
            if (screenWidth < 580) {
                // Set for small screens
                $wrapper.css('width', `${total * 100}%`);
                $testimonials.css('width', `${100 / total}%`);
            } else {
                // Set for larger screens (still full width per testimonial)
                $wrapper.css('width', `100%`);
                $testimonials.css('width', `100%`);
            }
            // Reposition to current index after width change
            updateSlider(currentIndex);
        }

        function updateSlider(index) {
            const offset = -index * (100 / total);
            $wrapper.css('transform', `translateX(${offset}%)`);
        }

        $('.arrow.left').click(function() {
            currentIndex = (currentIndex - 1 + total) % total;
            updateSlider(currentIndex);
        });

        $('.arrow.right').click(function() {
            currentIndex = (currentIndex + 1) % total;
            updateSlider(currentIndex);
        });

        // Init on load
        updateWidths();

        // Recalculate on window resize
        $(window).resize(function() {
            updateWidths();
        });
    });
</script>
