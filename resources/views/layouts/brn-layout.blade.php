<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('meta')
    <title>@yield('title')</title>

    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ asset('css/brn.css') }}"> --}}

    <!-- Scripts -->
    @env('local')
        @vite(['resources/css/brn.css', 'resources/js/brn.js'])
    @endenv

    <!--{{-- Use built assets on production --}}-->
    @env('production')
        <link rel="stylesheet" href="{{ asset('build/assets/brn-Bb4qXUBP.css') }}">
        <script src="{{ asset('build/assets/brn-DT6Zyp-t.js') }}" defer></script>
    @endenv

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


        /* .animate-on-scroll {
                opacity: 0 !important;
                transform: translateY(50px) !important;
                transition: all 0.6s ease-out !important;
            }

            .animate-on-scroll.visible {
                opacity: 1 !important;
                transform: translateY(0) !important;
            } */

        .navbar-full.navbar .navbar-toggler:focus,
        .navbar-full.navbar .navbar-toggler:active,
        .navbar-full.navbar .navbar-toggler:focus-visible {
            outline: none !important;
            box-shadow: none !important;
            border: none !important;
        }

        /* Let Bootstrap handle navbar collapse transitions naturally */
        .navbar-collapse {
            /* Remove custom transitions that conflict with Bootstrap */
        }
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
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                {{-- <span class="navbar-toggler-icon"></span> --}}
                <img src="{{ asset('images/hamburger.png') }}" loading='lazy' alt="">
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav pt-md-0 pt-3">
                    <li class="nav-item"><a class="nav-link" href="{{ url('about') }}">Who we are</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('services') }}">What we do</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('event') }}">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('resource-hub') }}">Resource hub</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('brn') }}">Business network</a></li>
                </ul>

                <div class="d-flex align-items-right mt-5 mobile">
                    <a href="#" class="btn login-btn my-auto">Log In</a>
                    <span class="mx-3"></span>
                    <a href="#" class="btn register-btn my-auto">Register</a>
                </div>
            </div>

            <div class="d-flex align-items-right desktop">
                <a href="#" class="btn login-btn my-auto">Log In</a>
                <span class="mx-3"></span>
                <a href="#" class="btn register-btn my-auto">Register</a>
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
                    <a href="{{ url('about') }}">Who we are</a>
                    <a href="{{ url('services') }}">What we do</a>
                    <a href="{{ route('event') }}">Events</a>
                    <a href="{{ route('resource-hub') }}">Resource hub</a>
                    <a href="{{ route('brn') }}">Business network</a>
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
                        <a href="https://facebook.com/p23africa" target="_blank"><i class="fab fa-facebook"></i></a>
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
                        <a href="https://facebook.com/p23africa" target="_blank"><i class="fab fa-facebook"></i></a>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery loaded after Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener("DOMContentLoaded", function() {
            let isScrolled = false;

            // Scroll detection (using vanilla JS to avoid conflicts)
            window.addEventListener('scroll', function() {
                const scroll = window.pageYOffset || document.documentElement.scrollTop;

                if (scroll > 100) {
                    document.querySelector('.navbar').classList.add('bg-scrolling');
                    isScrolled = true;
                } else {
                    document.querySelector('.navbar').classList.remove('bg-scrolling');
                    isScrolled = false;
                    document.querySelectorAll('.nav .nav-link').forEach(link => {
                        link.classList.remove('active-custom');
                    });
                }
            });

            // ANIMATE ON SCROLL
            const sections = document.querySelectorAll(".reveal-section");

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("visible");
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.2
            });

            sections.forEach(section => {
                observer.observe(section);
            });

            // Let Bootstrap handle hamburger menu functionality completely
        });
    </script>

    @include('include.version-update')
</body>

</html>
