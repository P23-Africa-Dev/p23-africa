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
    </head>

    <body>
        <style>
            /* @media only screen and (max-width: 980px) {
            .navbar .collapse .nav-item{
                margin-top: 1rem;
            }
        } */
        </style>
        {{-- NAVBAR --}}
        @include('layouts.includes.navbar')


        <main class="">
            @yield('content')
        </main>



        @include('layouts.includes.footer')

        <script>
            $(window).scroll(function() {
                var scroll = $(window).scrollTop();
                if (scroll > 100) {
                    $('#navbar').addClass('bg-scrolling');
                } else {
                    $('#navbar').removeClass('bg-scrolling');
                }
            })
            // END NAVBAR SCROLL ANIMATION
        </script>


        <script src="{{ asset('js/script.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
        </script>
    </body>

</html>
