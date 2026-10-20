<div id="navbar" class="fixed-top navbar-full">
    <div id="">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a href="{{ route('homepage') }}" class="navbar-brand logo">
                    <img src="{{ asset('images/logo.png') }}" loading='lazy' alt="">
                </a>
                <button type="button" class="navbar-toggler" id="menuToggle" aria-label="Open navigation menu" aria-controls="sidebar" aria-expanded="false">
                    {{-- <span class="fa-solid fa-bars-staggered"></span> --}}
                    <img src="{{ asset('images/hamburger.png') }}" loading='lazy' alt="">
                </button>

                <div class="collapse navbar-collapse main-nav">
                    <ul class="navbar-nav me-auto ms-auto mb-2 mb-lg-0" id="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('about') }}">Who we are</a>

                            {{-- <div class="dropdown-menu dropdown-mega">
                                <div class="container py-4" style="width: 70%; padding-left: 84px;">
                                    <div class="row justify-content-left">
                                        <div class="col-md-6  line">
                                            <a href="{{ url('about') }}" class="d-block mb-3 mt-3"><b>About Us</b></a>
                                            <p >Accelerating success for businesses across the globe — that’s the P23 
                                                Africa promise.</p>
                                        </div>
                                        <div class="col-md-6 " style=" transform: translateX(25px);">
                                            <a href="{{ route('brn') }}" class="d-block mb-3 mt-3"><b>Business
                                                    Network</b></a>
                                            <p>Where top CEOs meet, connect, and grow through trusted referrals.</p>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('services') }}">What we do </a>


                            {{-- <div class="dropdown-menu dropdown-mega">
                                <div class="container py-4" style="width: 70%;">
                                    <div class="row">
                                        <div class="col-md-3  line">
                                            <a href="{{ url('services') }}#strategy" class="d-block mb-3"><b>Business
                                                    Strategy</b></a>
                                            <p>Insights on how we bring about growth in Africa</p>
                                        </div>
                                        <div class="col-md-3 line">
                                            <a href="{{ url('services') }}#market" class="d-block mb-3"><b>Market
                                                    Entry</b></a>
                                            <p>Wanna know how we have become great</p>
                                        </div>
                                        <div class="col-md-3 line">
                                            <a href="{{ url('services') }}#research" class="d-block mb-3"><b>Business
                                                    Research</b></a>
                                            <p>Wanna know how we have become great</p>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ url('services') }}#sales" class="d-block mb-3"><b>Sales</b></a>
                                            <p>Wanna know how we have become great</p>
                                        </div>

                                        <div class="col-md-3 mt-4 line">
                                            <a href="{{ url('services') }}#marketing"
                                                class="d-block mb-3"><b>Marketing</b></a>
                                            <p>Wanna know how we have become great</p>
                                        </div>
                                        <div class="col-md-3 mt-4">
                                            <a href="{{ url('services') }}#audit" class="d-block mb-3"><b>Business
                                                    Audit</b></a>
                                            <p>Wanna know how we have become great</p>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('event') }}">Events</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('resource-hub') }}">Resource hub</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('brn') }}">Business network</a>
                        </li>
                    </ul>

                    <div class="contact">
                        <a href="{{ url('contact') }}" class="btn" id="btn">Contact us</a>
                    </div>


                    <div class="subscribe">
                        <a href="/book-consultation" class="btn" id="btn">Book a Consultation</a>
                    </div>

                    <div class="social-icons">
                        <a href="https://linkedin.com/company/p23-africa/" target="_blank"><i
                                class="fab fa-linkedin mx-1"></i></a>
                        <a href="https://facebook.com/p23africa" target="_blank"><i
                                class="fab fa-facebook mx-1"></i></a>
                        <a href="https://www.instagram.com/p23africa/" target="_blank"><i
                                class="bi bi-instagram mx-1"></i></a>
                        <a href="https://www.youtube.com/channel/UCmhdbCCyjpINgZ0Fgu20ZBQ" target="_blank"><i
                                class="fab fa-youtube mx-1"></i></a>
                    </div>
                </div>
        </nav>

        <!-- Overlay -->
        <div class="overlay" id="overlay"></div>

        <div class="sidebar" id="sidebar">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold"> </span>
                <button type="button" class="close-btn" id="closeBtn" aria-label="Close navigation menu">&times;</button>
            </div>
            <div id="mainMenu" class="main-menu">
                <ul class="navbar-nav me-auto ms-auto mb-2 mb-lg-0" id="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('about') }}">Who We Are</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('services') }}">What We Do</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('event') }}">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('resource-hub') }}">Resource Hub</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('brn') }}">Business network</a>
                    </li>
                </ul>

                <div class="contact">
                    <a href="{{ url('contact') }}" class="btn" id="btn">Contact Us</a>
                </div>


                <div class="subscribe">
                    <a href="#" class="btn" id="btn">Subscribe</a>
                </div>
                <div class="social-icons">
                    <a href="https://linkedin.com/company/p23-africa/" target="_blank"><i
                            class="fab fa-linkedin mx-1"></i></a>
                    <a href="https://facebook.com/p23africa" target="_blank"><i class="fab fa-facebook mx-1"></i></a>
                    <a href="https://www.instagram.com/p23africa/" target="_blank"><i
                            class="fab fa-instagram mx-1"></i></a>
                    <a href="https://www.youtube.com/channel/UCmhdbCCyjpINgZ0Fgu20ZBQ" target="_blank"><i
                            class="fab fa-youtube mx-1"></i></a>
                </div>
            </div>

            <!-- Submenus -->
            {{-- <div class="submenu" id="submenu1">
                <a class="nav-link text-primary" id="backBtn1"><i class="bi bi-chevron-left"></i> Back</a>
                <ul class="list-unstyled mt-2">
                    <li>
                        <a href="{{ url('about') }}" class="nav-link">
                            <h5>About Us</h5>
                            <p>
                                Accelerating success for businesses across the globe — that’s the P23 Africa promise.
                            </p>
                        </a>
                    </li>
                    <hr>
                    <li>
                        <a class="nav-link">
                            <h5>Business Network</h5>
                            <p>
                                Where top CEOs meet, connect, and grow through trusted referrals.
                            </p>
                        </a>
                    </li>
                </ul>
            </div> --}}
        </div>
    </div>
</div>
