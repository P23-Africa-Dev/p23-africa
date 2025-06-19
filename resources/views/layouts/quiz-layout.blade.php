<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">


        @yield('meta')

        <title>@yield('title')</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
        
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">

        <style>
            .typing-text {
                display: inline;
                white-space: normal;
                border-right: 2px solid black;
                animation: blinkCursor 0.75s step-end infinite;
                font-family: inherit;
            }

            @keyframes blinkCursor {

                0%,
                100% {
                    border-color: transparent;
                }

                50% {
                    border-color: rgba(0, 0, 0, 0);
                }
            }

            @keyframes popIn {
                0% {
                    opacity: 0;
                    transform: scale(0.8);
                }

                60% {
                    opacity: 1;
                    transform: scale(1.1);
                }

                100% {
                    transform: scale(1);
                }
            }

            .animate-pop-in {
                animation: popIn 0.6s ease-out;
            }
        </style>
    </head>

    <body>

        <main>
            @yield('content')
        </main>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Quiz Navbar Toggler Icon
            const navbarToggler = document.querySelector(".navbar-toggler");
            const togglerIcon = navbarToggler.querySelector(".navbar-toggler-icon");

            navbarToggler.addEventListener("click", () => {
                if (navbarToggler.getAttribute("aria-expanded") === "true") {
                    togglerIcon.classList.remove("navbar-toggler-icon");
                    togglerIcon.innerHTML = '<i class="bi bi-x-lg fs-4"></i>'; // Using Bootstrap Icons
                } else {
                    togglerIcon.classList.add("navbar-toggler-icon");
                    togglerIcon.innerHTML = "";
                }
            });
        </script>
    </body>

</html>
