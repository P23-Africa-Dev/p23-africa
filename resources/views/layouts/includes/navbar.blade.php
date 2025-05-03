<div id="navbar" class="fixed-top navbar-full">
    <div id="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a href="{{ route('homepage') }}" class="navbar-brand logo">
                    <img src="images/logo.png" alt="">
                </a>
                <div class="navbar-toggler" id="menuToggle">
                    {{-- <span class="fa-solid fa-bars-staggered"></span> --}}
                    <img src="{{ asset('images/hamburger.png') }}" alt="">
                </div>

                <div class="collapse navbar-collapse main-nav">
                        <ul class="navbar-nav me-auto ms-auto mb-2 mb-lg-0" id="nav">
                            <li class="nav-item">
                                <a class="nav-link active" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-target="submenu1" aria-current="page"
                                    href="#">Who We Are <i class="fa-solid fa-chevron-down mx-2"></i></a>

                                <div class="dropdown-menu">
                                    <div class="container py-4" style="width: 90%;">
                                        <div class="row justify-content-center">
                                            <div class="col-md-3">
                                                <a href="{{ url('about') }}" class="d-block mb-3"><b>About Us</b></a>
                                                <p>Accelerating success for businesses across the globe — that’s the P23 Africa promise.</p>
                                            </div>
                                            <div class="col-md-3">
                                                <a href="#" class="d-block mb-3"><b>Business Referral Network</b></a>
                                                <p>Where top CEOs meet, connect, and grow through trusted referrals.</p>
                                            </div>
                                            <div class="col-md-3">
                                                <a href="#" class="d-block mb-3"><b>University Partnership</b></a>
                                                <p>Connecting students with real-world research opportunities</p>
                                            </div>
                                            <div class="col-md-3">
                                                <a href="#" class="d-block mb-3"><b>The 54th Pitch</b></a>
                                                <p>Helping established businesses secure grant funding through expert pitch programs</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('services') }}">What We Do</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Our Events</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Resource Hub</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Let’s Collaborate</a>
                            </li>
                        </ul>

                        <div class="contact">
                            <a href="#" class="btn" id="btn">Contact Us</a>
                        </div>


                        <div class="subscribe">
                            <a href="#" class="btn" id="btn">Subscribe</a>
                        </div>

                        <div class="social-icons">
                            <a href="#"><i class="fab fa-linkedin mx-1"></i></a>
                            <a href="#"><i class="fab fa-facebook mx-1"></i></a>
                            <a href="#"><i class="fab fa-instagram mx-1"></i></a>
                            <a href="#"><i class="fab fa-youtube mx-1"></i></a>
                        </div>
            </div>
        </nav>

                <!-- Overlay -->
                <div class="overlay" id="overlay"></div>

                <div class="sidebar" id="sidebar">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold"> </span>
                        <span class="close-btn" id="closeBtn">
                            {{-- &times; --}}
                        </span>
                    </div>
                    <div id="mainMenu" class="main-menu">
                        <ul class="navbar-nav me-auto ms-auto mb-2 mb-lg-0" id="nav">
                            <li class="nav-item">
                                <a class="nav-link active" data-target="submenu1" aria-current="page"
                                    href="#">Who We Are</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('services') }}">What We Do</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Our Events</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Resource Hub</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Let’s Collaborate</a>
                            </li>
                        </ul>

                        <div class="contact">
                            <a href="#" class="btn" id="btn">Contact Us</a>
                        </div>


                        <div class="subscribe">
                            <a href="#" class="btn" id="btn">Subscribe</a>
                        </div>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-linkedin mx-1"></i></a>
                            <a href="#"><i class="fab fa-facebook mx-1"></i></a>
                            <a href="#"><i class="fab fa-instagram mx-1"></i></a>
                            <a href="#"><i class="fab fa-youtube mx-1"></i></a>
                        </div>
                    </div>

                    <!-- Submenus -->
                    <div class="submenu" id="submenu1">
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
                                    <h5>Business Referral Network</h5>
                                    <p>
                                        Where top CEOs meet, connect, and grow through trusted referrals.
                                    </p>
                                </a>
                            </li>
                            <hr>
                            <li>
                                <a class="nav-link">
                                    <h5>University Partnership</h5>
                                    <p>
                                        Connecting students with real-world research opportunities
                                    </p>
                                </a>
                            </li>
                            <hr>
                            <li>
                                <a class="nav-link">
                                    <h5>The 54th Pitch</h5>
                                    <p>
                                        Helping established businesses secure grant funding through expert pitch programs
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
    </div>
</div>
