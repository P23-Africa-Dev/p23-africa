@php
    $theme = $theme ?? 'dark'; // 'dark' = black text/logo/icons, 'light' = white text/logo/icons
    $active = $active ?? '';
    $logoSrc = ($theme === 'light') ? asset('images/p23-logo-white.png') : asset('images/p23-logo-black.svg');
    $iconLinkedin = ($theme === 'light') ? asset('images/about/icon-linkedin.svg') : asset('images/icon-linkedin-dark.svg');
    $iconFacebook = ($theme === 'light') ? asset('images/about/icon-facebook.svg') : asset('images/icon-facebook-dark.svg');
    $iconInstagram = ($theme === 'light') ? asset('images/about/icon-instagram.svg') : asset('images/icon-instagram-dark.svg');
    $iconYoutube = ($theme === 'light') ? asset('images/about/icon-youtube.svg') : asset('images/icon-youtube-dark.svg');
@endphp

{{-- NOTE: Intentionally NOT id="navbar" - the legacy master layout script adds .bg-scrolling to #navbar,
     which carries !important white/black backgrounds from style.css / services-new.css. --}}
<header class="p23-navbar-wrapper p23-navbar--{{ $theme }}" id="p23Navbar">
    <div class="p23-navbar-container">
        {{-- Left: Logo + Gap 106px + Nav Links (Figma Frame 55) --}}
        <div class="p23-nav-left">
            <a href="{{ route('homepage') }}" class="p23-nav-logo" aria-label="P23 Africa Homepage">
                <img src="{{ $logoSrc }}" alt="P23 Africa" width="62" height="30">
            </a>

            <nav class="p23-nav-links" id="p23NavLinks" aria-label="Main Navigation">
                <a href="{{ url('about') }}" class="p23-nav-link {{ $active === 'who-we-are' ? 'active' : '' }}">Who We Are</a>
                <a href="{{ url('services') }}" class="p23-nav-link {{ $active === 'what-we-do' ? 'active' : '' }}">What We Do</a>
                <a href="{{ route('event') }}" class="p23-nav-link {{ $active === 'events' ? 'active' : '' }}">Events</a>
                <a href="{{ route('resource-hub') }}" class="p23-nav-link {{ $active === 'resource-hub' ? 'active' : '' }}">Resource Hub</a>
                <a href="{{ route('brn') }}" class="p23-nav-link {{ $active === 'business-network' ? 'active' : '' }}">Business Network</a>
            </nav>
        </div>

        {{-- Right: Book Consultation + Gap 36px + Social Icons (Figma Group 427318899 / 427318900) --}}
        <div class="p23-nav-right">
            <a href="{{ url('book-consultation') }}" class="p23-nav-consult-btn">Book Consultation</a>

            <div class="p23-nav-socials" aria-label="Social Media">
                <a href="https://linkedin.com/company/p23-africa/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                    <img src="{{ $iconLinkedin }}" alt="LinkedIn" width="20" height="20">
                </a>
                <a href="https://facebook.com/p23africa" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <img src="{{ $iconFacebook }}" alt="Facebook" width="20" height="20">
                </a>
                <a href="https://instagram.com/p23africa" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <img src="{{ $iconInstagram }}" alt="Instagram" width="20" height="20">
                </a>
                <a href="https://youtube.com/@p23africa" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                    <img src="{{ $iconYoutube }}" alt="YouTube" width="20" height="20">
                </a>
            </div>
        </div>

        {{-- Mobile Hamburger Toggle --}}
        <button type="button" class="p23-nav-toggle" id="p23MenuToggle" aria-label="Open navigation menu" aria-expanded="false" aria-controls="p23MobileDrawer">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
    </div>

    {{-- Mobile Drawer Backdrop & Menu --}}
    <div class="p23-nav-backdrop" id="p23NavBackdrop" aria-hidden="true"></div>
    <div class="p23-mobile-drawer" id="p23MobileDrawer" aria-hidden="true">
        <div class="p23-mobile-drawer-header">
            <a href="{{ route('homepage') }}" class="p23-mobile-logo" aria-label="P23 Africa Homepage">
                <img src="{{ $logoSrc }}" alt="P23 Africa" width="62" height="30">
            </a>
            <button type="button" class="p23-mobile-drawer-close" id="p23MenuClose" aria-label="Close navigation menu">
                &times;
            </button>
        </div>
        <nav class="p23-mobile-links">
            <a href="{{ url('about') }}" class="p23-mobile-link {{ $active === 'who-we-are' ? 'active' : '' }}">Who We Are</a>
            <a href="{{ url('services') }}" class="p23-mobile-link {{ $active === 'what-we-do' ? 'active' : '' }}">What We Do</a>
            <a href="{{ route('event') }}" class="p23-mobile-link {{ $active === 'events' ? 'active' : '' }}">Events</a>
            <a href="{{ route('resource-hub') }}" class="p23-mobile-link {{ $active === 'resource-hub' ? 'active' : '' }}">Resource Hub</a>
            <a href="{{ route('brn') }}" class="p23-mobile-link {{ $active === 'business-network' ? 'active' : '' }}">Business Network</a>
        </nav>
        <div class="p23-mobile-cta">
            <a href="{{ url('book-consultation') }}" class="p23-nav-consult-btn">Book Consultation</a>
        </div>
        <div class="p23-mobile-socials">
            <a href="https://linkedin.com/company/p23-africa/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                <img src="{{ $iconLinkedin }}" alt="LinkedIn" width="22" height="22">
            </a>
            <a href="https://facebook.com/p23africa" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                <img src="{{ $iconFacebook }}" alt="Facebook" width="22" height="22">
            </a>
            <a href="https://instagram.com/p23africa" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                <img src="{{ $iconInstagram }}" alt="Instagram" width="22" height="22">
            </a>
            <a href="https://youtube.com/@p23africa" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                <img src="{{ $iconYoutube }}" alt="YouTube" width="22" height="22">
            </a>
        </div>
    </div>
</header>

<script>
(function() {
    function initP23Navbar() {
        const toggle = document.getElementById('p23MenuToggle');
        const close = document.getElementById('p23MenuClose');
        const drawer = document.getElementById('p23MobileDrawer');
        const backdrop = document.getElementById('p23NavBackdrop');
        const navbar = document.getElementById('p23Navbar');

        function openMenu() {
            if (drawer) drawer.classList.add('active');
            if (backdrop) backdrop.classList.add('active');
            if (toggle) toggle.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            if (drawer) drawer.classList.remove('active');
            if (backdrop) backdrop.classList.remove('active');
            if (toggle) toggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        if (toggle) toggle.addEventListener('click', openMenu);
        if (close) close.addEventListener('click', closeMenu);
        if (backdrop) backdrop.addEventListener('click', closeMenu);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && drawer && drawer.classList.contains('active')) {
                closeMenu();
            }
        });

        // Scroll listener for sticky navbar
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    if (navbar) {
                        if (window.scrollY > 40) {
                            navbar.classList.add('p23-nav--scrolled');
                        } else {
                            navbar.classList.remove('p23-nav--scrolled');
                        }
                    }
                    ticking = false;
                });
                ticking = true;
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initP23Navbar);
    } else {
        initP23Navbar();
    }
})();
</script>
