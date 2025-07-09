<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @yield('meta')
        <title>@yield('title')</title>

        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/student.css') }}">

        <!-- Swiper CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Font Awesome for icons -->
        <script src="https://kit.fontawesome.com/3df60fe6e2.js" crossorigin="anonymous"></script>

        <style>
            .navbar .mobile {
                display: none !important;
            }

            @media screen and (max-width: 580px) {
                .navbar .mobile {
                    display: block !important;
                }

                .navbar .desktop {
                    display: none !important;
                }
            }

            .reveal-section {
                opacity: 0;
                transform: translateY(40px);
                transition: all 0.6s ease-out;
            }

            .reveal-section.visible {
                opacity: 1;
                transform: translateY(0);
            }

            .modal-content {
                border-radius: 1rem;
            }

            #studentForm input {
                border: 0;
                border-bottom: 2px solid #0D4036;
                box-shadow: 2px 2px 4px #0000008e;
            }

            #studentForm .btn {
                background-color: #0D4036;
                color: #fff;
                font-family: 'GT Walsheim Con';
            }

            /* .animate-on-scroll {
                opacity: 0 !important;
                transform: translateY(50px) !important;
                transition: all 0.6s ease-out !important;
            }

            .animate-on-scroll.visible {
                opacity: 1 !important;
                transform: translateY(0) !important;
            } */
        </style>
    </head>

    <body>
        <!-- Navbar -->
        <nav class="navbar-full navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ route('homepage') }}">
                    <img src="{{ asset('images/logo.png') }}" loading='lazy' alt="">
                </a>
                <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    {{-- <span class="navbar-toggler-icon"></span> --}}
                    <img src="{{ asset('images/hamburger.png') }}" loading='lazy' alt="">
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav pt-md-0 pt-3">
                        <li class="nav-item"><a class="nav-link" href="{{ url('about') }}">Who We Are</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('services') }}">What We Do</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('event') }}">Our Events</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('resource-hub') }}">Resource Hub</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('contact') }}">Let’s Collaborate</a></li>
                    </ul>

                    <div class="d-flex align-items-right mt-5 mobile">
                        <button class="subscribe-btn me-3 mb-2">Subscribe</button>
                        <div class="social-icons">
                            <a href="https://linkedin.com/company/p23-africa/" target="_blank">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://facebook.com/p23africa" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/p23africa/" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.youtube.com/channel/UCmhdbCCyjpINgZ0Fgu20ZBQ" target="_blank">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-right desktop">
                    <button class="subscribe-btn me-3">Subscribe</button>
                    <div class="social-icons my-auto    ">
                        <a href="https://linkedin.com/company/p23-africa/" target="_blank">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="https://facebook.com/p23africa" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/p23africa/" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.youtube.com/channel/UCmhdbCCyjpINgZ0Fgu20ZBQ" target="_blank">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>


        <!-- Footer -->

        <footer class="footer">
            <div class="container-fluid">
                <div class="row gy-4">
                    <div class="col-md-3 col-lg-3 col-7">
                        <img src="{{ asset('images/logo.png') }}" loading='lazy' alt="">
                        <p class="mt-3 logo-text">P23 Africa LTD is registered in the United Kingdom. Company No:
                            15246700</p>
                    </div>

                    <div class="col-md-2 col-lg-2 col-4 footer-links desk">
                        <p>Who We Are</p>
                        <a href="{{ url('about') }}">About Us</a>
                        <a href="#">Business Referral Network</a>
                        <a href="#">University Partnership</a>
                        <a href="#">The 54th Pitch</a>
                    </div>

                    <div class="col-md-2 col-lg-2 col-4 footer-links footer-quick-links">
                        <p>Quick Links</p>
                        <a href="{{ url('about') }}">Who We Are</a>
                        <a href="{{ url('services') }}">What We Do</a>
                        <a href="{{ route('event') }}">Events</a>
                        <a href="{{ route('resource-hub') }}">Resource Hub</a>
                        <a href="{{ url('contact') }}">Let’s Collaborate</a>
                    </div>

                    <div class="col-md-2 footer-links strategy">
                        <p>What We Do</p>
                        <a href="{{ url('services') }}#strategy">Business Strategy</a>
                        <a href="{{ url('services') }}#market">Market Entry</a>
                        <a href="{{ url('services') }}#research">Business Research</a>
                        <a href="{{ url('services') }}#sales">Sales</a>
                        <a href="{{ url('services') }}#marketing">Marketing</a>
                        <a href="{{ url('services') }}#audit">Business Audit</a>
                    </div>

                    <div class="col-md-2 footer-links first-icon">
                        <p>Contact Us</p>
                        <div class="social-icons mt-2 d-flex">
                            <a href="https://linkedin.com/company/p23-africa/" target="_blank"><i
                                    class="fab fa-linkedin"></i></a>
                            <a href="https://facebook.com/p23africa" target="_blank"><i
                                    class="fab fa-facebook"></i></a>
                            <a href="https://www.instagram.com/p23africa/" target="_blank"><i
                                    class="fab fa-instagram"></i></a>
                            <a href="https://www.youtube.com/channel/UCmhdbCCyjpINgZ0Fgu20ZBQ" target="_blank">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-2 footer-links second-icon">
                        <p>Contact Us</p>
                        <div class="social-icons mt-2 d-flex">
                            <a href="https://linkedin.com/company/p23-africa/" target="_blank"><i
                                    class="fab fa-linkedin"></i></a>
                            <a href="https://facebook.com/p23africa" target="_blank"><i
                                    class="fab fa-facebook"></i></a>
                            <a href="https://www.instagram.com/p23africa/" target="_blank"><i
                                    class="fab fa-instagram"></i></a>
                            <a href="https://www.youtube.com/channel/UCmhdbCCyjpINgZ0Fgu20ZBQ" target="_blank">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3 subscribe subscribe_desktop">
                        <p>Subscribe to get Updates</p>
                        <input type="email" class="form-control mb-2" placeholder="Enter your email">
                        <button class="btn btn-subscribe">Subscribe <i class="fas fa-arrow-right ms-1"></i></button>
                    </div>

                    <div class="col-md-3 subscribe subscribe_mobile">
                        <p>Subscribe to get Updates</p>
                        <input type="email" class="form-control mb-2" placeholder="Enter your email">
                        <button class="btn btn-subscribe"><i class="fas fa-arrow-right ms-1"></i></button>
                    </div>

                </div>

                <div class="footer-bottom footer-bottom_desktop mt-4">
                    ©P23 Africa 2025 • <a href="#">Privacy Policy</a> • <a href="#">Terms & Conditions</a>
                </div>
                <div class="footer-bottom footer-bottom_mobile mt-4">
                    ©P23 Africa 2025
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>

        <script>
            let isScrolled = false;

            // Scroll detection
            $(window).scroll(function() {
                const scroll = $(window).scrollTop();

                if (scroll > 100) {
                    $('.navbar').addClass('bg-scrolling');
                    isScrolled = true;
                } else {
                    $('.navbar').removeClass('bg-scrolling');
                    isScrolled = false;
                    $('.nav .nav-link').removeClass('active-custom');
                }
            });



            // ANIMATE ON SCROLL
            document.addEventListener("DOMContentLoaded", () => {
                const sections = document.querySelectorAll(".reveal-section");

                const observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("visible");
                            observer.unobserve(entry
                                .target); // Remove this line if you want the animation again on scroll
                        }
                    });
                }, {
                    threshold: 0.2
                });

                sections.forEach(section => {
                    observer.observe(section);
                });
            });



            // Navbar Toggler Icon
            const navbarToggler = document.querySelector(".navbar-toggler");
            const togglerIcon = navbarToggler.querySelector(".navbar-toggler-icon");

            navbarToggler.addEventListener("click", () => {
                if (navbarToggler.getAttribute("aria-expanded") === "true") {
                    togglerIcon.classList.remove("navbar-toggler-icon");
                    togglerIcon.innerHTML = '<i class="bi bi-x-lg fs-4"></i>'; // Using Bootstrap Icons
                } else {
                    togglerIcon.classList.add("navbar-toggler-icon");
                    togglerIcon.innerHTML = "";
                }
            });





            const trackOne = document.querySelector(".slider-one");
            const trackTwo = document.querySelector(".slider-two");

            // Duplicate for upward scrolling (normal duplication)
            trackOne.innerHTML += trackOne.innerHTML;

            // For downward scrolling, duplicate *twice* to prevent blank space
            // trackTwo.innerHTML += trackTwo.innerHTML + trackTwo.innerHTML;

            function startScrolling(track, direction = "up", speed = 30) {
                const distance = track.scrollHeight / 3; // since we have 3x content

                const y = direction === "up" ? -distance : distance;
                const initialY = direction === "down" ? -distance : 0; // start offset to avoid blank

                gsap.set(track, {
                    y: initialY
                });

                const tween = gsap.to(track, {
                    y: y,
                    ease: "none",
                    duration: speed,
                    repeat: -1,
                    modifiers: {
                        y: gsap.utils.unitize(y => parseFloat(y) % distance)
                    }
                });

                // Pause on hover
                track.addEventListener("mouseenter", () => tween.pause());
                track.addEventListener("mouseleave", () => tween.play());
            }


            startScrolling(trackOne, "up", 1200);
            startScrolling(trackTwo, "down", 1200);

            // document.addEventListener('DOMContentLoaded', () => {
            //     function startInfiniteScroll(selector, speed = 30, direction = 'up') {
            //         const track = document.querySelector(selector);
            //         if (!track) return;

            //         const distance = track.scrollHeight / 2; // duplicated, so use half

            //         gsap.to(track, {
            //             y: direction === 'up' ? -distance : distance,
            //             ease: "none",
            //             duration: speed,
            //             repeat: -1,
            //             modifiers: {
            //                 y: gsap.utils.unitize(y => parseFloat(y) % distance)
            //             }
            //         });
            //     }

            //     startInfiniteScroll('.slider-one', 30, 'up'); // first slider scrolls up
            //     startInfiniteScroll('.slider-two', 30, 'down'); // second slider scrolls down
            // });
        </script>
    </body>

</html>
