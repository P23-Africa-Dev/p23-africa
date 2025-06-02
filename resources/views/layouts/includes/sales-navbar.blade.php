<div id="navbar" class="fixed-top navbar-full navbar-full-sales">
    <div id="">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a href="{{ route('homepage') }}" class="navbar-brand logo">
                    <img src="{{ asset('images/logo.png') }}" loading='lazy' alt="">
                </a>
                <div class="navbar-toggler" id="menuToggle">
                    {{-- <span class="fa-solid fa-bars-staggered"></span> --}}
                    <img src="{{ asset('images/hamburger.png') }}" loading='lazy' alt="">
                </div>

                <div class="collapse navbar-collapse main-nav mx-5">
                    <ul class="navbar-nav me-auto ms-auto mx-auto mb-2 mb-lg-0" id="nav">
                        <li class="nav-item">
                            <a class="nav-link mx-5" href="{{ route('sales') }}">Home </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-5" href="#">How it works</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-5" href="#">Quiz</a>
                        </li>
                    </ul>   

                    <div class="contact">
                        <a href="#" class="btn" id="btn">Contact Us</a>
                    </div>


                    <div class="subscribe">
                        <a href="#" class="btn" id="btn">Subscribe</a>
                    </div>

                    <div class="social-icons">
                        <a href="https://linkedin.com/company/p23-africa/" target="_blank"><i
                                class="fab fa-linkedin mx-1"></i></a>
                        <a href="https://facebook.com/p23africa" target="_blank"><i
                                class="fab fa-facebook mx-1"></i></a>
                        <a href="https://www.instagram.com/p23africa/" target="_blank"><i
                                class="bi bi-instagram mx-1"></i></a>
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
                        <a class="nav-link" href="{{ route('sales') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">How it works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Quiz</a>
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
                    <a href="#"><i class="fab fa-youtube mx-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
