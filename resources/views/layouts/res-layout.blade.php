<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @yield('meta')
        <title>@yield('title')</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/resource.css') }}">

        <!-- Font Awesome for icons -->
        <script src="https://kit.fontawesome.com/3df60fe6e2.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="#"><span>P23</span> Africa</a>
                <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon text-white"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="#">Who We Are</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">What We Do</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Our Events</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Resource Hub</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Let’s Collaborate</a></li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <button class="subscribe-btn me-3">Subscribe</button>
                    <div class="social-icons">
                        <i class="fab fa-linkedin-in"></i>
                        <i class="fab fa-facebook-f"></i>
                        <i class="fab fa-instagram"></i>
                        <i class="fab fa-youtube"></i>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>


        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>
