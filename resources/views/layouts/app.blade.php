<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/service-group.png') }}" type="image/x-icon">

    <title>P23 Africa | Admin Dashboard</title>

    <!-- Theme Detection Script (runs before body renders) -->
    <script>
        (function() {
            // Get stored theme preference
            const theme = localStorage.getItem('theme') || 'dark';

            // Determine if dark mode should be applied
            let isDark = false;

            if (theme === 'dark') {
                isDark = true;
            } else if (theme === 'light') {
                isDark = false;
            } else { // system
                isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            }

            // Apply dark class immediately to prevent flash
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Debug logging
            console.log('Pre-render theme detection:', {
                theme,
                isDark,
                systemDark: window.matchMedia('(prefers-color-scheme: dark)').matches
            });
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">


    <script src="https://kit.fontawesome.com/3df60fe6e2.js" crossorigin="anonymous"></script>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Enhanced Dark Mode CSS -->
    <link rel="stylesheet" href="{{ asset('css/enhanced-dark-mode.css') }}">



    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <!-- Scripts -->
    @env('local')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endenv

    <!--{{-- Use built assets on production --}}-->
    @env('production')
        <link rel="stylesheet" href="{{ asset('build/assets/app-2r1gdpuA.css') }}">
        <script src="{{ asset('build/assets/app-Bf4POITK.js') }}" defer></script>
    @endenv

    <x-head.tinymce-config />

    <!-- Alpine.js for interactive components -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Enhanced Universal Dark Mode Auto-Conversion Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let isApplying = false;

            // Enhanced function to apply dark mode classes to existing Bootstrap and custom elements
            function applyUniversalDarkMode() {
                if (isApplying) return;
                isApplying = true;

                try {
                    const isDark = document.documentElement.classList.contains('dark');

                    // Apply to containers without explicit dark mode classes
                    const containers = document.querySelectorAll(
                        '.bg-white:not(.dark-mode-applied), .bg-light:not(.dark-mode-applied), .bg-secondary:not(.dark-mode-applied)'
                    );
                    containers.forEach(el => {
                        if (!el.classList.contains('dark-mode-applied')) {
                            el.classList.add('dark-mode-applied');
                            if (isDark) {
                                el.style.backgroundColor = 'var(--bg-primary)';
                                el.style.color = 'var(--text-primary)';
                            } else {
                                el.style.removeProperty('background-color');
                                el.style.removeProperty('color');
                            }
                        }
                    });

                    // Apply to text elements
                    const textElements = document.querySelectorAll(
                        '.text-dark:not(.dark-mode-applied), .text-secondary:not(.dark-mode-applied), .text-muted:not(.dark-mode-applied)'
                    );
                    textElements.forEach(el => {
                        if (!el.classList.contains('dark-mode-applied')) {
                            el.classList.add('dark-mode-applied');
                            if (isDark) {
                                if (el.classList.contains('text-muted') || el.classList.contains(
                                        'text-secondary')) {
                                    el.style.color = 'var(--text-secondary)';
                                } else {
                                    el.style.color = 'var(--text-primary)';
                                }
                            } else {
                                el.style.removeProperty('color');
                            }
                        }
                    });

                    // Apply to tables with enhanced styling
                    const tables = document.querySelectorAll('.table:not(.dark-mode-applied)');
                    tables.forEach(table => {
                        if (!table.classList.contains('dark-mode-applied')) {
                            table.classList.add('dark-mode-applied');
                            if (isDark) {
                                table.style.setProperty('--bs-table-bg', 'transparent');
                                table.style.setProperty('--bs-table-color', 'var(--text-primary)');
                                table.style.setProperty('--bs-table-border-color', 'var(--border-color)');
                                table.style.backgroundColor = 'transparent';
                                table.style.color = 'var(--text-primary)';
                            } else {
                                table.style.removeProperty('--bs-table-bg');
                                table.style.removeProperty('--bs-table-color');
                                table.style.removeProperty('--bs-table-border-color');
                                table.style.removeProperty('background-color');
                                table.style.removeProperty('color');
                            }
                        }
                    });

                    // Apply to form controls
                    const formControls = document.querySelectorAll(
                        '.form-control:not(.dark-mode-applied), .form-select:not(.dark-mode-applied), .form-check-input:not(.dark-mode-applied)'
                    );
                    formControls.forEach(control => {
                        if (!control.classList.contains('dark-mode-applied')) {
                            control.classList.add('dark-mode-applied');
                            if (isDark) {
                                control.style.backgroundColor = 'var(--bg-tertiary)';
                                control.style.borderColor = 'var(--border-color)';
                                control.style.color = 'var(--text-primary)';
                            } else {
                                control.style.removeProperty('background-color');
                                control.style.removeProperty('border-color');
                                control.style.removeProperty('color');
                            }
                        }
                    });

                    // Apply to borders
                    const borderedElements = document.querySelectorAll(
                        '.border:not(.dark-mode-applied), .border-dark:not(.dark-mode-applied)'
                    );
                    borderedElements.forEach(el => {
                        if (!el.classList.contains('dark-mode-applied')) {
                            el.classList.add('dark-mode-applied');
                            if (isDark) {
                                el.style.borderColor = 'var(--border-color)';
                            } else {
                                el.style.removeProperty('border-color');
                            }
                        }
                    });

                    // Apply to cards and panels
                    const cards = document.querySelectorAll(
                        '.card:not(.dark-mode-applied), .panel:not(.dark-mode-applied)'
                    );
                    cards.forEach(card => {
                        if (!card.classList.contains('dark-mode-applied')) {
                            card.classList.add('dark-mode-applied');
                            if (isDark) {
                                card.style.backgroundColor = 'var(--bg-primary)';
                                card.style.borderColor = 'var(--border-color)';
                                card.style.color = 'var(--text-primary)';
                            } else {
                                card.style.removeProperty('background-color');
                                card.style.removeProperty('border-color');
                                card.style.removeProperty('color');
                            }
                        }
                    });

                    // Apply to modals
                    const modals = document.querySelectorAll('.modal:not(.dark-mode-applied)');
                    modals.forEach(modal => {
                        if (!modal.classList.contains('dark-mode-applied')) {
                            modal.classList.add('dark-mode-applied');
                            const modalContent = modal.querySelector('.modal-content');
                            if (modalContent && isDark) {
                                modalContent.style.backgroundColor = 'var(--bg-primary)';
                                modalContent.style.borderColor = 'var(--border-color)';
                                modalContent.style.color = 'var(--text-primary)';
                            } else if (modalContent && !isDark) {
                                modalContent.style.removeProperty('background-color');
                                modalContent.style.removeProperty('border-color');
                                modalContent.style.removeProperty('color');
                            }
                        }
                    });

                    // Force update body background
                    if (isDark) {
                        document.body.style.backgroundColor = 'var(--bg-secondary)';
                        document.documentElement.style.backgroundColor = 'var(--bg-secondary)';
                    } else {
                        document.body.style.backgroundColor = 'var(--bg-secondary)';
                        document.documentElement.style.backgroundColor = 'var(--bg-secondary)';
                    }

                } catch (error) {
                    console.warn('Error applying universal dark mode:', error);
                } finally {
                    isApplying = false;
                }
            }

            // Apply on load
            applyUniversalDarkMode();

            // Listen for theme changes and reapply
            window.addEventListener('theme-changed', function(e) {
                setTimeout(() => {
                    applyUniversalDarkMode();
                }, 100);
            });

            // Observer for dynamically added content
            const observer = new MutationObserver(function(mutations) {
                let shouldReapply = false;
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1) { // Element node
                                shouldReapply = true;
                            }
                        });
                    }
                });

                if (shouldReapply) {
                    setTimeout(applyUniversalDarkMode, 500);
                }
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            // Periodic check for missed elements (reduced frequency)
            setInterval(applyUniversalDarkMode, 10000);
        });
    </script>

    <!-- Enhanced Dark Mode Styles -->
    <style>
        /* CSS Variables for Theme Colors */
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-tertiary: #e9ecef;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --border-color: #dee2e6;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        .dark {
            --bg-primary: #1f2937;
            --bg-secondary: #111827;
            --bg-tertiary: #374151;
            --text-primary: #f9fafb;
            --text-secondary: #d1d5db;
            --border-color: #4b5563;
            --shadow-color: rgba(0, 0, 0, 0.3);
        }

        /* Global smooth transitions */
        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        }

        /* Dark mode utilities */
        .dark-transition {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }

        /* Base body and html styling */
        html {
            background-color: var(--bg-secondary) !important;
        }

        body {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        /* Bootstrap overrides for comprehensive dark mode */
        .dark .bg-white {
            background-color: var(--bg-primary) !important;
        }

        .dark .bg-light {
            background-color: var(--bg-secondary) !important;
        }

        .dark .bg-secondary {
            background-color: var(--bg-tertiary) !important;
        }

        .dark .text-dark {
            color: var(--text-primary) !important;
        }

        .dark .text-muted {
            color: var(--text-secondary) !important;
        }

        .dark .border,
        .dark .border-top,
        .dark .border-bottom,
        .dark .border-left,
        .dark .border-right {
            border-color: var(--border-color) !important;
        }

        /* Enhanced Table Styling */
        .dark .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-primary);
            --bs-table-border-color: var(--border-color);
            --bs-table-striped-bg: rgba(75, 85, 99, 0.5);
            --bs-table-hover-bg: rgba(75, 85, 99, 0.3);
            background-color: transparent !important;
            color: var(--text-primary) !important;
        }

        .dark .table th,
        .dark .table td {
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .dark .table-striped>tbody>tr:nth-of-type(odd)>td,
        .dark .table-striped>tbody>tr:nth-of-type(odd)>th {
            background-color: rgba(75, 85, 99, 0.2) !important;
        }

        .dark .table-hover tbody tr:hover {
            background-color: rgba(75, 85, 99, 0.3) !important;
        }

        /* Enhanced Button Styling */
        .dark .btn-primary {
            --bs-btn-bg: #3b82f6;
            --bs-btn-border-color: #3b82f6;
            --bs-btn-hover-bg: #2563eb;
            --bs-btn-hover-border-color: #2563eb;
            --bs-btn-active-bg: #1d4ed8;
            --bs-btn-active-border-color: #1d4ed8;
        }

        .dark .btn-secondary {
            --bs-btn-bg: #6b7280;
            --bs-btn-border-color: #6b7280;
            --bs-btn-hover-bg: #4b5563;
            --bs-btn-hover-border-color: #4b5563;
            --bs-btn-color: #f9fafb;
        }

        .dark .btn-danger {
            --bs-btn-bg: #ef4444;
            --bs-btn-border-color: #ef4444;
            --bs-btn-hover-bg: #dc2626;
            --bs-btn-hover-border-color: #dc2626;
        }

        .dark .btn-success {
            --bs-btn-bg: #10b981;
            --bs-btn-border-color: #10b981;
            --bs-btn-hover-bg: #059669;
            --bs-btn-hover-border-color: #059669;
        }

        .dark .btn-warning {
            --bs-btn-bg: #f59e0b;
            --bs-btn-border-color: #f59e0b;
            --bs-btn-hover-bg: #d97706;
            --bs-btn-hover-border-color: #d97706;
        }

        .dark .btn-info {
            --bs-btn-bg: #3b82f6;
            --bs-btn-border-color: #3b82f6;
            --bs-btn-hover-bg: #2563eb;
            --bs-btn-hover-border-color: #2563eb;
        }

        .dark .btn-dark {
            --bs-btn-bg: var(--bg-tertiary);
            --bs-btn-border-color: var(--border-color);
            --bs-btn-hover-bg: #4b5563;
            --bs-btn-hover-border-color: #4b5563;
            --bs-btn-color: var(--text-primary);
        }

        .dark .btn-light {
            --bs-btn-bg: var(--bg-primary);
            --bs-btn-border-color: var(--border-color);
            --bs-btn-hover-bg: var(--bg-tertiary);
            --bs-btn-hover-border-color: var(--border-color);
            --bs-btn-color: var(--text-primary);
        }

        .dark .btn-outline-primary {
            --bs-btn-color: #3b82f6;
            --bs-btn-border-color: #3b82f6;
            --bs-btn-hover-bg: #3b82f6;
            --bs-btn-hover-border-color: #3b82f6;
        }

        /* Enhanced Form Controls */
        .dark .form-control,
        .dark .form-select,
        .dark .form-check-input {
            background-color: var(--bg-tertiary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .dark .form-control::placeholder {
            color: var(--text-secondary) !important;
        }

        .dark .form-control:focus,
        .dark .form-select:focus {
            background-color: var(--bg-tertiary) !important;
            border-color: #3b82f6 !important;
            color: var(--text-primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
        }

        /* Enhanced Modal Styling */
        .dark .modal-content {
            background-color: var(--bg-primary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .dark .modal-header {
            border-bottom-color: var(--border-color) !important;
            background-color: var(--bg-secondary) !important;
        }

        .dark .modal-footer {
            border-top-color: var(--border-color) !important;
            background-color: var(--bg-secondary) !important;
        }

        .dark .modal-title {
            color: var(--text-primary) !important;
        }

        /* Enhanced Alert Styling */
        .dark .alert {
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .dark .alert-success {
            background-color: rgba(16, 185, 129, 0.2) !important;
            border-color: #10b981 !important;
            color: #d1fae5 !important;
        }

        .dark .alert-danger {
            background-color: rgba(239, 68, 68, 0.2) !important;
            border-color: #ef4444 !important;
            color: #fecaca !important;
        }

        .dark .alert-warning {
            background-color: rgba(245, 158, 11, 0.2) !important;
            border-color: #f59e0b !important;
            color: #fde68a !important;
        }

        .dark .alert-info {
            background-color: rgba(59, 130, 246, 0.2) !important;
            border-color: #3b82f6 !important;
            color: #dbeafe !important;
        }

        /* Enhanced Card and Container Styling */
        .dark .card {
            background-color: var(--bg-primary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .dark .card-header {
            background-color: var(--bg-secondary) !important;
            border-bottom-color: var(--border-color) !important;
        }

        .dark .card-footer {
            background-color: var(--bg-secondary) !important;
            border-top-color: var(--border-color) !important;
        }

        /* Dashboard specific styling */
        .dark fieldset {
            border-color: var(--border-color) !important;
            background-color: transparent !important;
        }

        .dark legend {
            color: var(--text-primary) !important;
        }

        /* Enhanced Link Styling */
        .dark a,
        .dark .link-dark {
            color: #60a5fa !important;
        }

        .dark a:hover,
        .dark .link-dark:hover {
            color: #93c5fd !important;
        }

        /* Comprehensive text color fixes */
        .dark h1,
        .dark h2,
        .dark h3,
        .dark h4,
        .dark h5,
        .dark h6 {
            color: var(--text-primary) !important;
        }

        .dark p,
        .dark span,
        .dark div,
        .dark label {
            color: inherit;
        }

        .dark .text-primary {
            color: var(--text-primary) !important;
        }

        .dark .text-secondary {
            color: var(--text-secondary) !important;
        }

        /* Enhanced navigation and dropdown styling */
        .dark .dropdown-menu {
            background-color: var(--bg-primary) !important;
            border-color: var(--border-color) !important;
        }

        .dark .dropdown-item {
            color: var(--text-primary) !important;
        }

        .dark .dropdown-item:hover,
        .dark .dropdown-item:focus {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
        }

        .dark .dropdown-divider {
            border-color: var(--border-color) !important;
        }

        /* Enhanced shadow styling for dark mode */
        .dark .shadow,
        .dark .shadow-sm,
        .dark .shadow-lg {
            box-shadow: 0 0.125rem 0.25rem var(--shadow-color) !important;
        }

        /* Pagination styling */
        .dark .pagination .page-link {
            background-color: var(--bg-primary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .dark .pagination .page-link:hover {
            background-color: var(--bg-tertiary) !important;
            border-color: var(--border-color) !important;
        }

        .dark .pagination .page-item.active .page-link {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }

        /* List group styling */
        .dark .list-group-item {
            background-color: var(--bg-primary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .dark .list-group-item:hover {
            background-color: var(--bg-tertiary) !important;
        }

        /* Badge styling */
        .dark .badge {
            color: #ffffff !important;
        }

        /* Progress bar styling */
        .dark .progress {
            background-color: var(--bg-tertiary) !important;
        }

        /* Accordion styling */
        .dark .accordion-item {
            background-color: var(--bg-primary) !important;
            border-color: var(--border-color) !important;
        }

        .dark .accordion-header button {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        .dark .accordion-body {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }

        /* Logo Theme Inversion */
        .logo-theme-invert {
            filter: invert(1) brightness(1) contrast(1);
        }

        .dark .logo-theme-invert {
            filter: brightness(1) contrast(1);
            transition: filter 0.3s ease;
        }
    </style>
</head>

<body class="font-sans antialiased h-full dark-transition" style="background-color: var(--bg-secondary) !important;">
    <div class="min-h-screen dark-transition" style="background-color: var(--bg-secondary) !important;">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow border-b border-gray-100 dark:border-gray-700 dark-transition"
                style="background-color: var(--bg-primary) !important; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="text-gray-900 dark:text-gray-100 dark-transition"
                        style="color: var(--text-primary) !important;">
                        {{ $header }}
                    </div>
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="dark-transition"
            style="background-color: var(--bg-secondary) !important; min-height: calc(100vh - 64px);">
            {{ $slot }}
        </main>

    </div>
</body>

</html>
