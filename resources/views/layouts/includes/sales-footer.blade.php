        <!-- Footer -->

        <footer class="footer">
            <div class="container-fluid">
                <div class="row gy-4">
                    <div class="col-md-4 col-lg-4 col-7">
                        <img src="{{ asset('images/logo.png') }}" loading='lazy' alt="">
                        <p class="mt-3 logo-text">P23 Africa LTD is registered in the United Kingdom. Company No: 15246700</p>
                    </div>

                    <div class="col-md-2 col-lg-2 col-4 footer-links desk">
                        <a href="{{ route('sales') }}">Home</a>
                        <a href="#">How It Works</a>
                        <a href="#">Quiz</a>
                    </div>

                    <div class="col-md-2 col-lg-2 col-4 footer-links footer-quick-links">
                        <p>Quick Links</p>
                        <a href="{{ route('sales') }}">Home</a>
                        <a href="#">How It Works</a>
                        <a href="#">Quiz</a>
                    </div>

                    {{-- <div class="col-md-2 footer-links strategy">
                        <p>What We Do</p>
                        <a href="{{ url('services') }}#strategy">Business Strategy</a>
                        <a href="{{ url('services') }}#market">Market Entry</a>
                        <a href="{{ url('services') }}#research">Business Research</a>
                        <a href="{{ url('services') }}#sales">Sales</a>
                        <a href="{{ url('services') }}#marketing">Marketing</a>
                        <a href="{{ url('services') }}#audit">Business Audit</a>
                    </div> --}}

                    <div class="col-md-2 footer-links first-icon">
                        <p>Contact Us</p>
                        <div class="social-icons mt-2 d-flex">
                            <a href="https://linkedin.com/company/p23-africa/" target="_blank"><i class="fab fa-linkedin"></i></a>
                            <a href="https://facebook.com/p23africa" target="_blank"><i class="fab fa-facebook"></i></a>
                            <a href="https://www.instagram.com/p23africa/" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>

                    <div class="col-md-2 footer-links second-icon">
                        <p>Contact Us</p>
                        <div class="social-icons mt-2 d-flex">
                            <a href="https://linkedin.com/company/p23-africa/" target="_blank"><i class="fab fa-linkedin"></i></a>
                            <a href="https://facebook.com/p23africa" target="_blank"><i class="fab fa-facebook"></i></a>
                            <a href="https://www.instagram.com/p23africa/" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-md-4 subscribe subscribe_desktop">
                        <p>Subscribe to get Updates</p>
                        <input type="email" class="form-control mb-2" placeholder="Enter your email">
                        <button class="btn btn-subscribe">Subscribe <i class="fas fa-arrow-right ms-1"></i></button>
                    </div>

                    <div class="col-md-4 subscribe subscribe_mobile">
                        <p>Subscribe to get Updates</p>
                        <input type="email" class="form-control mb-2" placeholder="Enter your email">
                        <button class="btn btn-subscribe"><i class="fas fa-arrow-right ms-1"></i></button>
                    </div>

                </div>

                <div class="footer-bottom footer-bottom_desktop mt-4 text-center">
                    ©P23 Africa 2025 • <a href="#">Privacy Policy</a> • <a href="#">Terms & Conditions</a>
                </div>
                <div class="footer-bottom footer-bottom_mobile mt-4">
                    ©P23 Africa 2025
                </div>
            </div>
        </footer>