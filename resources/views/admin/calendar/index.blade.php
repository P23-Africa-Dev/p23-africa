<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Calendar') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">
                    View and manage your scheduled consultations and events
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Calendar Card -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark dark:text-gray-100 dark-transition">
                            <i class="fa fa-calendar-alt me-2 text-primary"></i>Google Calendar
                        </h5>
                        <a href="https://calendar.google.com/calendar/u/0/r?cid=contact@p23africa.com" target="_blank"
                            class="btn btn-sm btn-primary dark-transition">
                            <i class="fa fa-external-link-alt me-2"></i>Open in Google Calendar
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Calendar iframe Container -->
                    <div class="calendar-container"
                        style="position: relative; width: 100%; padding-bottom: 75%; min-height: 600px;">
                        <iframe
                            src="https://calendar.google.com/calendar/embed?src=contact%40p23africa.com&ctz=Europe%2FLondon&mode=WEEK&showTitle=0&showNav=1&showDate=1&showPrint=0&showTabs=1&showCalendars=0&showTz=1"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; border-radius: 0 0 0.5rem 0.5rem;"
                            frameborder="0" scrolling="no">
                        </iframe>
                    </div>
                </div>
            </div>

            <!-- Calendar Legend & Info -->
            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition h-100">
                        <div class="card-body">
                            <h6 class="text-dark dark:text-gray-100 fw-bold mb-3">
                                <i class="fa fa-info-circle me-2 text-info"></i>Calendar Information
                            </h6>
                            <ul class="list-unstyled mb-0" style="font-size: 0.875rem;">
                                <li class="mb-2 text-muted dark:text-gray-400">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    All scheduled consultations appear automatically
                                </li>
                                <li class="mb-2 text-muted dark:text-gray-400">
                                    <i class="fa fa-video text-primary me-2"></i>
                                    Google Meet links included in events
                                </li>
                                <li class="mb-2 text-muted dark:text-gray-400">
                                    <i class="fa fa-sync text-info me-2"></i>
                                    Calendar syncs in real-time
                                </li>
                                <li class="mb-2 text-muted dark:text-gray-400">
                                    <i class="fa fa-clock text-warning me-2"></i>
                                    Timezone: Europe/London (GMT)
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition h-100">
                        <div class="card-body">
                            <h6 class="text-dark dark:text-gray-100 fw-bold mb-3">
                                <i class="fa fa-lightbulb me-2 text-warning"></i>Quick Tips
                            </h6>
                            <ul class="list-unstyled mb-0" style="font-size: 0.875rem;">
                                <li class="mb-2 text-muted dark:text-gray-400">
                                    <i class="fa fa-mouse-pointer text-primary me-2"></i>
                                    Click any event to view full details
                                </li>
                                <li class="mb-2 text-muted dark:text-gray-400">
                                    <i class="fa fa-arrows-alt text-success me-2"></i>
                                    Use navigation buttons to change dates
                                </li>
                                <li class="mb-2 text-muted dark:text-gray-400">
                                    <i class="fa fa-th text-info me-2"></i>
                                    Switch between Day, Week, and Month views
                                </li>
                                <li class="mb-2 text-muted dark:text-gray-400">
                                    <i class="fa fa-external-link-alt text-warning me-2"></i>
                                    Open in Google Calendar for full features
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 dark-transition mt-4">
                <div class="card-body">
                    <h6 class="text-dark dark:text-gray-100 fw-bold mb-3">
                        <i class="fa fa-bolt me-2 text-primary"></i>Quick Actions
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.consultation.index') }}"
                            class="btn btn-outline-primary dark-transition">
                            <i class="fa fa-handshake me-2"></i>View Consultations
                        </a>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-success dark-transition">
                            <i class="fa fa-calendar me-2"></i>Manage Events
                        </a>
                        <a href="https://calendar.google.com/calendar/u/0/r/settings" target="_blank"
                            class="btn btn-outline-info dark-transition">
                            <i class="fa fa-cog me-2"></i>Calendar Settings
                        </a>
                        <a href="https://support.google.com/calendar" target="_blank"
                            class="btn btn-outline-secondary dark-transition">
                            <i class="fa fa-question-circle me-2"></i>Help & Support
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <style>
            /* Responsive Calendar Container */
            @media (max-width: 768px) {
                .calendar-container {
                    padding-bottom: 100% !important;
                    min-height: 500px !important;
                }
            }

            @media (max-width: 576px) {
                .calendar-container {
                    padding-bottom: 120% !important;
                    min-height: 450px !important;
                }
            }

            /* Dark mode calendar frame */
            .dark .calendar-container iframe {
                filter: brightness(0.9);
            }

            /* Button hover effects */
            .btn-outline-primary:hover,
            .btn-outline-success:hover,
            .btn-outline-info:hover,
            .btn-outline-secondary:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            }

            /* Card hover effects */
            .card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
            }

            /* Gap utility for older browsers */
            .gap-2>* {
                margin-right: 0.5rem;
                margin-bottom: 0.5rem;
            }

            .gap-2>*:last-child {
                margin-right: 0;
            }
        </style>
    @endpush
</x-app-layout>
