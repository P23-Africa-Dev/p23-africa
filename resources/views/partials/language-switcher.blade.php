@once
    <style>
        .locale-switcher {
            display: inline-flex;
            align-items: center;
            flex: 0 0 auto;
            gap: 0;
            margin-left: 8px;
            white-space: nowrap;
        }

        .locale-switcher a {
            color: #0d4036;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.2px;
            line-height: 1;
            opacity: 0.72;
            padding: 6px 5px;
            text-decoration: none;
        }

        .locale-switcher a.is-active {
            opacity: 1;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .locale-switcher--mobile {
            margin: 16px 0 8px;
        }

        .locale-switcher--on-dark a,
        .p23-navbar--light .locale-switcher a,
        .p23-navbar--light .p23-mobile-drawer .locale-switcher a {
            color: #fff;
            opacity: 0.9;
        }

        .locale-switcher a.is-active,
        .locale-switcher--on-dark a.is-active,
        .p23-navbar--light .locale-switcher a.is-active,
        .bg-scrolling .locale-switcher a.is-active,
        .sidebar .locale-switcher a.is-active,
        .p23-navbar--dark .locale-switcher a.is-active {
            opacity: 1;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .p23-navbar--dark .locale-switcher a,
        .p23-navbar--dark .p23-mobile-drawer .locale-switcher a,
        .bg-scrolling .locale-switcher a,
        .sidebar .locale-switcher a {
            color: #0d4036;
        }

        .navbar .navbar-nav .nav-link {
            margin-right: 10px !important;
            padding-left: 4px;
            padding-right: 4px;
            white-space: nowrap;
        }

        .main-nav {
            margin-left: 0 !important;
            min-width: 0;
        }

        .navbar .subscribe #btn,
        .navbar .subscribe #btn:hover,
        .navbar .subscribe #btn:focus,
        .navbar .subscribe.brn-consult .btn,
        .navbar .subscribe.brn-consult .btn:hover,
        .navbar .subscribe.brn-consult .btn:focus,
        .navbar .subscribe-btn,
        .navbar .subscribe-btn:hover,
        .navbar .subscribe-btn:focus,
        .subscribe-btn#btn,
        .subscribe-btn#btn:hover,
        .subscribe-btn#btn:focus {
            width: auto !important;
            height: auto;
            line-height: 1.25;
            text-decoration: none;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        }

        html {
            overflow-x: clip;
        }

        #navbar.navbar-full,
        .navbar-full.navbar,
        .navbar.navbar-expand-lg,
        .p23-navbar-wrapper {
            max-width: 100vw;
        }

        @media (max-width: 991px) {
            #p23Navbar .p23-nav-toggle {
                display: inline-flex !important;
                visibility: visible !important;
                flex: 0 0 auto;
                margin-left: auto;
                color: #0d4036;
            }

            #p23Navbar.p23-navbar--light .p23-nav-toggle {
                color: #fff;
            }

            #p23Navbar .p23-navbar-container {
                height: auto;
                min-height: 48px;
            }
        }

        .navbar.is-nav-compressed .navbar-collapse {
            display: none !important;
        }

        .navbar.is-nav-compressed .navbar-collapse.show,
        .navbar.is-nav-compressed .navbar-collapse.collapsing {
            display: block !important;
        }

        .navbar.is-nav-compressed .navbar-toggler {
            display: block !important;
        }

        .navbar.is-nav-compressed .desktop {
            display: none !important;
        }

        .navbar.is-nav-compressed .navbar-collapse.show .mobile,
        .navbar.is-nav-compressed .navbar-collapse.collapsing .mobile {
            display: flex !important;
        }
    </style>
    <script>
        (function() {
            function fitNavbars() {
                document.querySelectorAll('nav.navbar').forEach(function(nav) {
                    var wasCompressed = nav.classList.contains('is-nav-compressed');
                    if (wasCompressed) {
                        nav.classList.remove('is-nav-compressed');
                    }
                    if (window.innerWidth < 576 || nav.scrollWidth > nav.clientWidth + 2) {
                        nav.classList.add('is-nav-compressed');
                    }
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', fitNavbars);
            } else {
                fitNavbars();
            }
            window.addEventListener('resize', fitNavbars);
            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(fitNavbars);
            }
        })
        ();
    </script>
@endonce
<nav class="locale-switcher {{ $class ?? '' }}" aria-label="{{ __('nav.language') }}">
    @foreach (['en' => 'EN', 'fr' => 'FR', 'de' => 'DE', 'nl' => 'NL'] as $code => $label)
        <a href="{{ \App\Support\LocaleUrl::switch($code) }}"
            class="{{ app()->getLocale() === $code ? 'is-active' : '' }}" hreflang="{{ $code }}"
            lang="{{ $code }}"
            onclick="document.cookie='locale_choice={{ $code }};path=/;max-age=31536000;SameSite=Lax'">{{ $label }}</a>
    @endforeach
</nav>
