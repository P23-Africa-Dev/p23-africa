<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title') </title>

    <!--<link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">-->
    <link rel="shortcut icon" href="{{ asset('images/favicon-new.png') }}" type="image/x-icon">
    <meta property="og:image" content="{{ asset('img/consultation-page-thumbnail-1.png') }}">

    @yield('meta')
    @include('partials.hreflang')

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://kit.fontawesome.com/3df60fe6e2.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />

    {{-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Alpine.js for Live Form Capture -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body>
    @include('partials.language-suggestion')
    @include('partials.language-switcher')
    {{-- NAVBAR --}}


    <main class="">
        @yield('content')
    </main>





    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/master.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

    {{-- CSRF Token Expiry Handler --}}
    <script>
        (function() {
            // Handle 419 errors on form submissions
            document.addEventListener('DOMContentLoaded', function() {
                // Intercept all form submissions
                document.querySelectorAll('form').forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        const submitBtn = form.querySelector(
                            'button[type="submit"], input[type="submit"]');

                        // Store original button state
                        if (submitBtn) {
                            submitBtn.dataset.originalText = submitBtn.innerHTML;
                            submitBtn.disabled = true;
                            submitBtn.innerHTML =
                                '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
                        }
                    });
                });

                // Setup AJAX error handling for jQuery
                if (typeof $ !== 'undefined') {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status === 419) {
                                handleCsrfExpiry();
                            }
                        }
                    });
                }

                // Setup fetch interceptor for 419 errors
                const originalFetch = window.fetch;
                window.fetch = function(...args) {
                    return originalFetch.apply(this, args).then(response => {
                        if (response.status === 419) {
                            handleCsrfExpiry();
                        }
                        return response;
                    });
                };
            });

            // Function to handle CSRF expiry
            function handleCsrfExpiry() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Session Expired',
                        text: 'Your session has expired for security reasons. The page will refresh to continue.',
                        icon: 'warning',
                        confirmButtonText: 'Refresh Page',
                        confirmButtonColor: '#591044',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        window.location.reload();
                    });
                } else {
                    if (confirm('Your session has expired. Click OK to refresh the page and continue.')) {
                        window.location.reload();
                    }
                }
            }

            // Expose globally for custom use
            window.handleCsrfExpiry = handleCsrfExpiry;
        })();
    </script>
    @include('partials.scroll-reveal')

</body>

</html>
