<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title')</title>


        <meta name="description"
            content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
        <meta name="keywords"
            content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
        <meta name="author" content="Nurudeen O. Daniju">
        <meta name="generator" content="Business Consulting & Growth Solutions for Africa | P23 Africa">
        {{-- <meta property="og:image" content="images/building002.jpeg"> --}}
        <link rel="shortcut icon" href="{{ asset('images/Logo.png') }}" type="image/x-icon">


        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <script src="https://kit.fontawesome.com/3df60fe6e2.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
            crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

        <link
            href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
            rel="stylesheet" />

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            .nav-link.active-custom {
                color: #0D4036 !important;
                font-weight: 800 !important;
            }
        </style>
    </head>

    <body>
        {{-- NAVBAR --}}
        @include('layouts.includes.navbar')


        <main class="">
            @yield('content')
        </main>



        @include('layouts.includes.footer')


        {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
        <script src="{{ asset('js/script.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
        </script>

        <script>
            let isScrolled = false;

            // Scroll detection
            $(window).scroll(function() {
                const scroll = $(window).scrollTop();

                if (scroll > 100) {
                    $('#navbar').addClass('bg-scrolling');
                    isScrolled = true;
                } else {
                    $('#navbar').removeClass('bg-scrolling');
                    isScrolled = false;
                    $('#nav .nav-link').removeClass('active-custom');
                }
            });

            // Menu click handler (bound once)
            $('#nav .nav-link').on('click', function(e) {
                if (isScrolled) {
                    $('#nav .nav-link').removeClass('active-custom');
                    $(this).addClass('active-custom');
                }
            });



            // ANIMATE ON SCROLL
            $(document).ready(function() {
                function animateOnScroll() {
                    $('.animate-on-scroll').each(function() {
                        var elementTop = $(this).offset().top;
                        var windowBottom = $(window).scrollTop() + $(window).height();

                        if (elementTop < windowBottom - 50) {
                            $(this).addClass('visible');
                        }
                    });
                }

                animateOnScroll(); // Run on page load
                $(window).on('scroll', animateOnScroll); // Run on scroll
            });
        </script>
    </body>

</html>
