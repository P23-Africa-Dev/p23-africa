<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
        <link rel="shortcut icon" href="{{ asset('images/service-group.png') }}" type="image/x-icon">

        <title>P23 Africa | Admin Dashboard</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">


        <script src="https://kit.fontawesome.com/3df60fe6e2.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
            crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">



        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    

        <!-- Scripts -->
        @env('local')
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endenv
        
        <!--{{-- Use built assets on production --}}-->
        @env('production')
            <link rel="stylesheet" href="{{ asset('build/assets/app-DTNRFIno.css') }}">
            <script src="{{ asset('build/assets/app-Bf4POITK.js') }}" defer></script>
        @endenv

        <x-head.tinymce-config/>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            
        </div>
    </body>
</html>
