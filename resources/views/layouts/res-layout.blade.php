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
        <link rel="stylesheet" href="{{ asset('css/resource.css') }}">

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
        </style>
    </head>

    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('images/logo.png') }}" loading='lazy' alt="">
                </a>
                <button class="navbar-toggler text-white border-1 border-light" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="{{ url('about') }}">Who We Are</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('services') }}">What We Do</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('event') }}">Our Events</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('resource-hub') }}">Resource Hub</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Let’s Collaborate</a></li>
                    </ul>

                    <div class="d-flex align-items-right mobile">
                        <button class="subscribe-btn me-3">Subscribe</button>
                        <div class="social-icons">
                            <i class="fab fa-linkedin-in"></i>
                            <i class="fab fa-facebook-f"></i>
                            <i class="fab fa-instagram"></i>
                            <i class="fab fa-youtube"></i>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-right desktop">
                    <button class="subscribe-btn me-3">Subscribe</button>
                    <div class="social-icons">
                        <i class="fab fa-linkedin-in"></i>
                        <i class="fab fa-facebook-f"></i>
                        <i class="fab fa-instagram"></i>
                        <i class="fab fa-youtube"></i>
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
                        <a href="#">Let’s Collaborate</a>
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
                            <a href="#"><i class="fab fa-youtube"></i></a>
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
                            <a href="#"><i class="fab fa-youtube"></i></a>
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
    </body>

</html>
