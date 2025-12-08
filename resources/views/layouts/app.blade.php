<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/Logo2.png') }}" type="image/x-icon">

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
        <link rel="stylesheet" href="{{ asset('build/assets/app-D-jcgBiz.css') }}">
        <script src="{{ asset('build/assets/app-BGjDqbHR.js') }}" defer></script>
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

        /* Responsive Sidebar Content Adjustment */
        @media (max-width: 991px) {
            .main-content-wrapper {
                margin-left: 0 !important;
            }
        }

        @media (min-width: 992px) {
            .main-content-wrapper {
                /* Desktop sidebar spacing handled by Alpine.js */
            }
        }
    </style>
</head>

<body class="font-sans antialiased h-full dark-transition" style="background-color: var(--bg-secondary) !important;">
    <div class="min-h-screen dark-transition" style="background-color: var(--bg-secondary) !important;">
        @include('layouts.navigation')

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content Area with Sidebar Offset -->
        <div x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') === 'true' || localStorage.getItem('sidebarOpen') === null }" :style="sidebarOpen ? 'margin-left: 260px;' : 'margin-left: 70px;'"
            class="main-content-wrapper dark-transition mt-[64px]"
            style="transition: margin-left 0.3s ease; margin-top: 64px;">

            <!-- Page Heading -->
            @isset($header)
                <header
                    class="bg-white dark:bg-gray-800 shadow border-b border-gray-100 dark:border-gray-700 dark-transition"
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

    </div>

    <!-- Floating Notification Bell -->
    @auth
        <div id="notification-bell-container" class="position-fixed" style="bottom: 24px; right: 24px; z-index: 9999;">
            <!-- Notification Bell Button -->
            <button id="notification-bell-btn" onclick="toggleNotificationPanel()"
                class="btn btn-primary rounded-circle shadow-lg position-relative d-flex align-items-center justify-content-center"
                style="width: 56px; height: 56px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <i class="fas fa-bell" style="font-size: 1.4rem;"></i>
                <!-- Notification Badge -->
                <span id="notification-badge"
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="display: none; font-size: 0.65rem; min-width: 18px; animation: pulse 2s infinite;">
                    0
                </span>
            </button>

            <!-- Notification Panel -->
            <div id="notification-panel" class="position-absolute bg-white dark:bg-gray-800 rounded-3 shadow-lg border"
                style="bottom: 70px; right: 0; width: 380px; max-height: 500px; display: none; transform: translateY(20px); opacity: 0; transition: transform 0.3s ease, opacity 0.3s ease; background-color: var(--bg-primary); border-color: var(--border-color);">

                <!-- Panel Header -->
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom"
                    style="border-color: var(--border-color);">
                    <h6 class="mb-0 fw-bold" style="color: var(--text-primary);">
                        <i class="fas fa-bell me-2"></i>Notifications
                    </h6>
                    <div class="d-flex gap-2">
                        <button onclick="markAllNotificationsRead()" class="btn btn-sm btn-outline-secondary"
                            title="Mark all as read">
                            <i class="fas fa-check-double"></i>
                        </button>
                        <button onclick="toggleNotificationPanel()" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Notifications List -->
                <div id="notification-list" class="overflow-auto" style="max-height: 400px;">
                    <div class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

                <!-- Panel Footer -->
                <div class="p-2 border-top text-center" style="border-color: var(--border-color);">
                    <small class="text-muted">Notifications auto-refresh every 15 seconds</small>
                </div>
            </div>
        </div>

        <style>
            @keyframes pulse {
                0% {
                    box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
                }

                70% {
                    box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
                }
            }

            #notification-bell-btn:hover {
                transform: scale(1.1);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2) !important;
            }

            #notification-panel.show {
                display: block !important;
                transform: translateY(0) !important;
                opacity: 1 !important;
            }

            .notification-item {
                transition: background-color 0.2s ease;
                cursor: pointer;
            }

            .notification-item:hover {
                background-color: var(--bg-tertiary);
            }

            .notification-item.unread {
                border-left: 3px solid #3b82f6;
            }

            .notification-item.unread::before {
                content: '';
                position: absolute;
                left: 8px;
                top: 50%;
                transform: translateY(-50%);
                width: 8px;
                height: 8px;
                background-color: #3b82f6;
                border-radius: 50%;
            }

            .notification-icon {
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                flex-shrink: 0;
            }

            .notification-icon.brn_application {
                background-color: rgba(59, 130, 246, 0.15);
                color: #3b82f6;
            }

            .notification-icon.consultation {
                background-color: rgba(16, 185, 129, 0.15);
                color: #10b981;
            }

            .notification-icon.contact_form {
                background-color: rgba(139, 92, 246, 0.15);
                color: #8b5cf6;
            }

            .notification-icon.event_feedback {
                background-color: rgba(245, 158, 11, 0.15);
                color: #f59e0b;
            }

            .notification-icon.playbook_download {
                background-color: rgba(236, 72, 153, 0.15);
                color: #ec4899;
            }

            .notification-icon.project_created {
                background-color: rgba(6, 182, 212, 0.15);
                color: #06b6d4;
            }

            .notification-icon.task_created {
                background-color: rgba(34, 197, 94, 0.15);
                color: #22c55e;
            }

            .notification-icon.task_assigned {
                background-color: rgba(249, 115, 22, 0.15);
                color: #f97316;
            }

            .notification-icon.task_comment {
                background-color: rgba(99, 102, 241, 0.15);
                color: #6366f1;
            }

            .notification-icon.taskassigned {
                background-color: rgba(249, 115, 22, 0.15);
                color: #f97316;
            }

            /* Dark mode adjustments */
            .dark #notification-panel {
                background-color: var(--bg-primary) !important;
            }

            .dark .notification-item:hover {
                background-color: var(--bg-tertiary) !important;
            }
        </style>
    @endauth

    <!-- Bootstrap JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Activity Log Notifications (Admin Only) -->
    @if (auth()->check() && auth()->user()->hasRole('admin'))
        <script>
            (function() {
                const ActivityNotifications = {
                    lastKnownCount: 0,
                    pollInterval: 30000, // 30 seconds
                    notificationPermission: Notification.permission,

                    init() {
                        this.requestNotificationPermission();
                        this.updateBadge();
                        this.startPolling();
                    },

                    async requestNotificationPermission() {
                        if ('Notification' in window && Notification.permission === 'default') {
                            try {
                                const permission = await Notification.requestPermission();
                                this.notificationPermission = permission;
                                console.log('Notification permission:', permission);
                            } catch (e) {
                                console.log('Notification permission request failed:', e);
                            }
                        }
                    },

                    async updateBadge() {
                        try {
                            const response = await fetch('{{ route('admin.activity.unseen-count') }}', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            });

                            if (!response.ok) return;

                            const data = await response.json();
                            const badge = document.getElementById('activity-unseen-badge');

                            if (badge) {
                                if (data.count > 0) {
                                    badge.textContent = data.count > 99 ? '99+' : data.count;
                                    badge.style.display = 'flex';
                                    badge.style.alignItems = 'center';
                                    badge.style.justifyContent = 'center';
                                } else {
                                    badge.style.display = 'none';
                                }
                            }

                            // Check for new activities and show notification
                            if (data.count > this.lastKnownCount && this.lastKnownCount > 0) {
                                this.fetchAndNotify();
                            }

                            this.lastKnownCount = data.count;
                        } catch (e) {
                            console.log('Failed to update activity badge:', e);
                        }
                    },

                    async fetchAndNotify() {
                        if (this.notificationPermission !== 'granted') return;

                        try {
                            const response = await fetch('{{ route('admin.activity.new-activities') }}', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            });

                            if (!response.ok) return;

                            const data = await response.json();

                            if (data.activities && data.activities.length > 0) {
                                const activity = data.activities[0]; // Show the latest one
                                this.showNotification(activity);
                            }
                        } catch (e) {
                            console.log('Failed to fetch new activities:', e);
                        }
                    },

                    showNotification(activity) {
                        if (!('Notification' in window) || Notification.permission !== 'granted') {
                            return;
                        }

                        const title = 'New Activity Log';
                        const options = {
                            body: `${activity.causer_name} ${activity.description}${activity.subject_type ? ' - ' + activity.subject_type : ''}`,
                            icon: '{{ asset('images/Logo2.png') }}',
                            badge: '{{ asset('images/Logo2.png') }}',
                            tag: 'activity-' + activity.id,
                            requireInteraction: false,
                            silent: false,
                        };

                        try {
                            const notification = new Notification(title, options);

                            notification.onclick = function() {
                                window.focus();
                                window.location.href = '{{ route('admin.activity.logs') }}';
                                notification.close();
                            };

                            // Auto close after 5 seconds
                            setTimeout(() => notification.close(), 5000);
                        } catch (e) {
                            console.log('Failed to show notification:', e);
                        }
                    },

                    startPolling() {
                        setInterval(() => {
                            this.updateBadge();
                        }, this.pollInterval);
                    }
                };

                // Initialize when DOM is ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', () => ActivityNotifications.init());
                } else {
                    ActivityNotifications.init();
                }
            })();
        </script>
    @endif

    <!-- Task Assignment Notifications (For All Authenticated Users) -->
    @auth
        <script>
            (function() {
                const TaskNotifications = {
                    lastKnownCount: 0,
                    lastNotificationId: null,
                    pollInterval: 30000, // 30 seconds
                    notificationPermission: 'default',

                    init() {
                        if ('Notification' in window) {
                            this.notificationPermission = Notification.permission;
                        }
                        this.requestNotificationPermission();
                        this.updateBadge();
                        this.startPolling();
                    },

                    async requestNotificationPermission() {
                        if ('Notification' in window && Notification.permission === 'default') {
                            try {
                                const permission = await Notification.requestPermission();
                                this.notificationPermission = permission;
                                console.log('Task notification permission:', permission);
                            } catch (e) {
                                console.log('Task notification permission request failed:', e);
                            }
                        }
                    },

                    async updateBadge() {
                        try {
                            const response = await fetch('{{ route('admin.notifications.unread-count') }}', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            });

                            if (!response.ok) return;

                            const data = await response.json();
                            const badge = document.getElementById('task-notification-badge');

                            if (badge) {
                                if (data.count > 0) {
                                    badge.textContent = data.count > 99 ? '99+' : data.count;
                                    badge.style.display = 'flex';
                                    badge.style.alignItems = 'center';
                                    badge.style.justifyContent = 'center';
                                } else {
                                    badge.style.display = 'none';
                                }
                            }

                            // Check for new notifications and show browser notification
                            if (data.count > this.lastKnownCount && this.lastKnownCount >= 0) {
                                this.fetchAndNotify();
                            }

                            this.lastKnownCount = data.count;
                        } catch (e) {
                            console.log('Failed to update task notification badge:', e);
                        }
                    },

                    async fetchAndNotify() {
                        if (this.notificationPermission !== 'granted') return;

                        try {
                            const response = await fetch('{{ route('admin.notifications.tasks') }}', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            });

                            if (!response.ok) return;

                            const data = await response.json();

                            if (data.notifications && data.notifications.length > 0) {
                                // Show notification for the latest unread one
                                const latestUnread = data.notifications.find(n => !n.read_at);
                                if (latestUnread && latestUnread.id !== this.lastNotificationId) {
                                    this.showNotification(latestUnread);
                                    this.lastNotificationId = latestUnread.id;
                                }
                            }
                        } catch (e) {
                            console.log('Failed to fetch task notifications:', e);
                        }
                    },

                    showNotification(notification) {
                        if (!('Notification' in window) || Notification.permission !== 'granted') {
                            return;
                        }

                        const title = '📋 New Task Assigned';
                        const priorityEmoji = {
                            'urgent': '🔴',
                            'high': '🟠',
                            'medium': '🟡',
                            'low': '🟢'
                        };

                        let body = `${notification.task_title}`;
                        if (notification.project_name) {
                            body += ` - ${notification.project_name}`;
                        }
                        body += `\nAssigned by: ${notification.assigned_by}`;
                        if (notification.priority) {
                            body +=
                                ` ${priorityEmoji[notification.priority] || ''} ${notification.priority.charAt(0).toUpperCase() + notification.priority.slice(1)} Priority`;
                        }

                        const options = {
                            body: body,
                            icon: '{{ asset('images/Logo2.png') }}',
                            badge: '{{ asset('images/Logo2.png') }}',
                            tag: 'task-' + notification.id,
                            requireInteraction: true,
                            silent: false,
                        };

                        try {
                            const browserNotification = new Notification(title, options);

                            browserNotification.onclick = function() {
                                window.focus();
                                if (notification.project_id) {
                                    window.location.href = '{{ url('/admin/tasks/kanban') }}?project_id=' +
                                        notification.project_id;
                                } else {
                                    window.location.href = '{{ route('admin.tasks.kanban') }}';
                                }
                                browserNotification.close();
                            };

                            // Auto close after 10 seconds
                            setTimeout(() => browserNotification.close(), 10000);
                        } catch (e) {
                            console.log('Failed to show task notification:', e);
                        }
                    },

                    startPolling() {
                        setInterval(() => {
                            this.updateBadge();
                        }, this.pollInterval);
                    }
                };

                // Initialize when DOM is ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', () => TaskNotifications.init());
                } else {
                    TaskNotifications.init();
                }
            })
            ();
        </script>
    @endauth

    <!-- Comprehensive Notification System -->
    @auth
        <script>
            (function() {
                const NotificationSystem = {
                    pollInterval: 15000, // 15 seconds
                    lastKnownCount: 0,
                    lastNotificationId: null,
                    notificationPermission: 'default',
                    isPanelOpen: false,
                    notifications: [],

                    init() {
                        if ('Notification' in window) {
                            this.notificationPermission = Notification.permission;
                        }
                        this.requestNotificationPermission();
                        this.updateNotifications();
                        this.startPolling();

                        // Close panel when clicking outside
                        document.addEventListener('click', (e) => {
                            const panel = document.getElementById('notification-panel');
                            const btn = document.getElementById('notification-bell-btn');
                            if (panel && btn && !panel.contains(e.target) && !btn.contains(e.target)) {
                                this.closePanel();
                            }
                        });

                        console.log('Notification System initialized');
                    },

                    async requestNotificationPermission() {
                        if ('Notification' in window && Notification.permission === 'default') {
                            try {
                                const permission = await Notification.requestPermission();
                                this.notificationPermission = permission;
                                console.log('Browser notification permission:', permission);
                            } catch (e) {
                                console.log('Notification permission request failed:', e);
                            }
                        }
                    },

                    async updateNotifications() {
                        try {
                            // Get unread count
                            const countResponse = await fetch(
                                '{{ route('admin.notifications.all-unread-count') }}', {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                    }
                                });

                            if (countResponse.ok) {
                                const countData = await countResponse.json();
                                this.updateBadge(countData.count);

                                // Check for new notifications and show browser notification
                                if (countData.count > this.lastKnownCount && this.lastKnownCount >= 0) {
                                    this.fetchAndShowBrowserNotification();
                                }
                                this.lastKnownCount = countData.count;
                            }

                            // If panel is open, refresh the list
                            if (this.isPanelOpen) {
                                await this.loadNotificationList();
                            }

                        } catch (e) {
                            console.log('Failed to update notifications:', e);
                        }
                    },

                    updateBadge(count) {
                        const badge = document.getElementById('notification-badge');
                        if (badge) {
                            if (count > 0) {
                                badge.textContent = count > 99 ? '99+' : count;
                                badge.style.display = 'flex';
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    },

                    async loadNotificationList() {
                        const list = document.getElementById('notification-list');
                        if (!list) return;

                        try {
                            const response = await fetch('{{ route('admin.notifications.all') }}', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                this.notifications = data.notifications;
                                this.renderNotificationList(data.notifications);
                            }
                        } catch (e) {
                            console.log('Failed to load notifications:', e);
                            list.innerHTML = `
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                <p class="mb-0">Failed to load notifications</p>
                            </div>
                        `;
                        }
                    },

                    renderNotificationList(notifications) {
                        const list = document.getElementById('notification-list');
                        if (!list) return;

                        if (notifications.length === 0) {
                            list.innerHTML = `
                            <div class="text-center py-5" style="color: var(--text-secondary);">
                                <i class="fas fa-bell-slash fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0">No notifications yet</p>
                            </div>
                        `;
                            return;
                        }

                        list.innerHTML = notifications.map(notification => {
                            const isUnread = !notification.read_at;
                            const iconClass = this.getIconClass(notification.type);
                            const icon = notification.icon || 'fas fa-bell';

                            return `
                            <div class="notification-item position-relative p-3 border-bottom ${isUnread ? 'unread' : ''}" 
                                 style="border-color: var(--border-color);"
                                 onclick="NotificationSystem.handleNotificationClick('${notification.id}', '${notification.url || ''}')"
                                 data-id="${notification.id}">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="notification-icon ${notification.type}">
                                        <i class="${icon}"></i>
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6 class="mb-1 fw-semibold text-truncate" style="color: var(--text-primary); font-size: 0.9rem;">
                                                ${this.escapeHtml(notification.title)}
                                            </h6>
                                            ${isUnread ? '<span class="badge bg-primary" style="font-size: 0.65rem;">New</span>' : ''}
                                        </div>
                                        <p class="mb-1 small" style="color: var(--text-secondary); line-height: 1.4;">
                                            ${this.escapeHtml(notification.message)}
                                        </p>
                                        <small style="color: var(--text-secondary); opacity: 0.7;">
                                            <i class="fas fa-clock me-1"></i>${notification.created_at}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        `;
                        }).join('');
                    },

                    getIconClass(type) {
                        const icons = {
                            'brn_application': 'fas fa-file-alt',
                            'consultation': 'fas fa-calendar-check',
                            'contact_form': 'fas fa-envelope',
                            'event_feedback': 'fas fa-star',
                            'playbook_download': 'fas fa-download',
                            'project_created': 'fas fa-project-diagram',
                            'task_created': 'fas fa-tasks',
                            'task_assigned': 'fas fa-user-plus',
                            'taskassigned': 'fas fa-user-plus',
                            'task_comment': 'fas fa-comment',
                        };
                        return icons[type] || 'fas fa-bell';
                    },

                    async handleNotificationClick(notificationId, url) {
                        // Mark as read
                        try {
                            await fetch(`/admin/notifications/${notificationId}/mark-read`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            });

                            // Update badge count
                            this.lastKnownCount = Math.max(0, this.lastKnownCount - 1);
                            this.updateBadge(this.lastKnownCount);

                            // Update the item in the list
                            const item = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
                            if (item) {
                                item.classList.remove('unread');
                                const newBadge = item.querySelector('.badge.bg-primary');
                                if (newBadge) newBadge.remove();
                            }
                        } catch (e) {
                            console.log('Failed to mark notification as read:', e);
                        }

                        // Navigate if URL provided
                        if (url) {
                            window.location.href = url;
                        }
                    },

                    async markAllAsRead() {
                        try {
                            await fetch('{{ route('admin.notifications.mark-all-read') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            });

                            // Update UI
                            this.lastKnownCount = 0;
                            this.updateBadge(0);

                            // Remove unread styling from all items
                            document.querySelectorAll('.notification-item.unread').forEach(item => {
                                item.classList.remove('unread');
                                const badge = item.querySelector('.badge.bg-primary');
                                if (badge) badge.remove();
                            });

                        } catch (e) {
                            console.log('Failed to mark all as read:', e);
                        }
                    },

                    async fetchAndShowBrowserNotification() {
                        if (this.notificationPermission !== 'granted') return;

                        try {
                            const response = await fetch('{{ route('admin.notifications.latest-unread') }}', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.notifications && data.notifications.length > 0) {
                                    const latest = data.notifications[0];
                                    if (latest.id !== this.lastNotificationId) {
                                        this.showBrowserNotification(latest);
                                        this.lastNotificationId = latest.id;
                                    }
                                }
                            }
                        } catch (e) {
                            console.log('Failed to fetch latest notification:', e);
                        }
                    },

                    showBrowserNotification(notification) {
                        if (!('Notification' in window) || Notification.permission !== 'granted') {
                            return;
                        }

                        const typeEmojis = {
                            'brn_application': '📄',
                            'consultation': '📅',
                            'contact_form': '✉️',
                            'event_feedback': '⭐',
                            'playbook_download': '📥',
                            'project_created': '📊',
                            'task_created': '✅',
                            'task_assigned': '👤',
                            'task_comment': '💬',
                        };

                        const emoji = typeEmojis[notification.type] || '🔔';
                        const title = `${emoji} ${notification.title}`;

                        const options = {
                            body: notification.message,
                            icon: '{{ asset('images/Logo2.png') }}',
                            badge: '{{ asset('images/Logo2.png') }}',
                            tag: 'notification-' + notification.id,
                            requireInteraction: false,
                            silent: false,
                        };

                        try {
                            const browserNotification = new Notification(title, options);

                            browserNotification.onclick = function() {
                                window.focus();
                                if (notification.url) {
                                    window.location.href = notification.url;
                                }
                                browserNotification.close();
                            };

                            // Auto close after 8 seconds
                            setTimeout(() => browserNotification.close(), 8000);
                        } catch (e) {
                            console.log('Failed to show browser notification:', e);
                        }
                    },

                    togglePanel() {
                        const panel = document.getElementById('notification-panel');
                        if (!panel) return;

                        this.isPanelOpen = !this.isPanelOpen;

                        if (this.isPanelOpen) {
                            panel.style.display = 'block';
                            // Trigger reflow for animation
                            panel.offsetHeight;
                            panel.classList.add('show');
                            this.loadNotificationList();
                        } else {
                            this.closePanel();
                        }
                    },

                    closePanel() {
                        const panel = document.getElementById('notification-panel');
                        if (!panel) return;

                        panel.classList.remove('show');
                        this.isPanelOpen = false;

                        // Wait for animation to complete before hiding
                        setTimeout(() => {
                            if (!this.isPanelOpen) {
                                panel.style.display = 'none';
                            }
                        }, 300);
                    },

                    escapeHtml(text) {
                        if (!text) return '';
                        const div = document.createElement('div');
                        div.textContent = text;
                        return div.innerHTML;
                    },

                    startPolling() {
                        setInterval(() => {
                            this.updateNotifications();
                        }, this.pollInterval);
                    }
                };

                // Make globally available
                window.NotificationSystem = NotificationSystem;
                window.toggleNotificationPanel = () => NotificationSystem.togglePanel();
                window.markAllNotificationsRead = () => NotificationSystem.markAllAsRead();

                // Initialize when DOM is ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', () => NotificationSystem.init());
                } else {
                    NotificationSystem.init();
                }
            })
            ();
        </script>
    @endauth

    <!-- Custom Scripts Stack -->
    @stack('scripts')
</body>

</html>
