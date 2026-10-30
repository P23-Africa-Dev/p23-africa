        <!-- Footer -->

        <footer class="footer">
            <div class="container-fluid">
                <div class="row gy-4">
                    <div class="col-md-3 col-lg-3 col-7">
                        <img src="{{ asset('images/logo.png') }}" loading='lazy' alt="">
                        <p class="mt-3 logo-text">{{ __('footer.company') }}</p>
                    </div>

                    <div class="col-md-2 col-lg-2 col-4 footer-links desk">
                        <p>{{ __('footer.who_we_are') }}</p>
                        <a href="{{ locale_route('about') }}">{{ __('footer.about_us') }}</a>
                        <a href="{{ locale_route('brn') }}">{{ __('footer.business_network') }}</a>
                        <!-- <a href="#">University Partnership</a>
                        <a href="https://the54thpitch.co.uk">The 54th Pitch</a> -->
                    </div>

                    <div class="col-md-2 col-lg-2 col-4 footer-links footer-quick-links">
                        <p>{{ __('footer.quick_links') }}</p>
                        <a href="{{ locale_route('about') }}">{{ __('footer.who_we_are') }}</a>
                        <a href="{{ locale_route('services') }}">{{ __('footer.what_we_do') }}</a>
                        <a href="{{ locale_route('event') }}">{{ __('nav.events') }}</a>
                        <a href="{{ locale_route('resource-hub') }}">{{ __('nav.resource_hub_title') }}</a>
                        <a href="{{ locale_route('contact') }}">{{ __('footer.lets_collaborate') }}</a>
                    </div>

                    <div class="col-md-2 footer-links strategy">
                        <p>What We Do</p>
                        <a href="{{ route('services.market-demand-testing') }}">Market Demand Testing</a>
                        <a href="{{ route('services.outbound-demand-testing') }}">Outbound Demand Testing</a>
                        <a href="{{ route('services.distributor-partner') }}">Distributor Verification</a>
                        <a href="{{ url('services') }}">Commercial Services</a>
                    </div>

                    <div class="col-md-2 footer-links first-icon">
                        <p>{{ __('footer.contact_us') }}</p>
                        <div class="social-icons mt-2 d-flex">
                            <a href="https://linkedin.com/company/p23-africa/" target="_blank"><i class="fab fa-linkedin"></i></a>
                            <a href="https://facebook.com/p23africa" target="_blank"><i class="fab fa-facebook"></i></a>
                            <a href="https://www.instagram.com/p23africa/" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.youtube.com/channel/UCmhdbCCyjpINgZ0Fgu20ZBQ" target="_blank"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>

                    <div class="col-md-2 footer-links second-icon">
                        <p>{{ __('footer.contact_us') }}</p>
                        <div class="social-icons mt-2 d-flex">
                            <a href="https://linkedin.com/company/p23-africa/" target="_blank"><i class="fab fa-linkedin"></i></a>
                            <a href="https://facebook.com/p23africa" target="_blank"><i class="fab fa-facebook"></i></a>
                            <a href="https://www.instagram.com/p23africa/" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.youtube.com/channel/UCmhdbCCyjpINgZ0Fgu20ZBQ" target="_black"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-md-3 subscribe subscribe_desktop">
                        <p>{{ __('footer.subscribe_title') }}</p>
                        <input type="email" class="form-control mb-2" placeholder="{{ __('footer.email_placeholder') }}">
                        <button class="btn btn-subscribe">{{ __('footer.subscribe') }} <i class="fas fa-arrow-right ms-1"></i></button>
                    </div>

                    <div class="col-md-3 subscribe subscribe_mobile">
                        <p>{{ __('footer.subscribe_title') }}</p>
                        <input type="email" class="form-control mb-2" placeholder="{{ __('footer.email_placeholder') }}">
                        <button class="btn btn-subscribe"><i class="fas fa-arrow-right ms-1"></i></button>
                    </div>

                </div>

                <div class="footer-bottom footer-bottom_desktop mt-4">
                    {{ __('footer.copyright') }} • <a href="{{ locale_route('privacy') }}">{{ __('footer.privacy') }}</a> • <a href="{{ locale_route('terms') }}">{{ __('footer.terms') }}</a>
                </div>
                <div class="footer-bottom footer-bottom_mobile mt-4">
                    {{ __('footer.copyright') }}
                </div>
            </div>
        </footer>