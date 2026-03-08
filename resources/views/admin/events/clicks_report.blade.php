<x-app-layout>
    <x-admin-dark-mode />

    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.events.index') }}" class="mr-4 transition-colors duration-200"
                style="color: var(--text-secondary);">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <h2 class="font-semibold text-2xl leading-tight" style="color: var(--text-primary);">
                <i class="fas fa-chart-line mr-3" style="color: #6366f1 !important;"></i>{{ __('Event Click Report') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-xl sm:rounded-lg" style="background-color: var(--bg-primary);">
                <div class="p-6">

                    <!-- Event Title -->
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 rounded-lg mb-8"
                        style="color: #fff !important;">
                        <h3 class="text-lg font-semibold mb-1" style="color: #fff !important;">
                            <i class="fas fa-calendar-alt mr-2" style="color: #fff !important;"></i>{{ $event->title }}
                        </h3>
                        <p style="color: rgba(199, 210, 254, 1) !important;">Click analytics and traffic breakdown</p>
                    </div>

                    @php
                        $currentFilter = request('filter', 'today');
                    @endphp

                    <!-- Filter Buttons -->
                    <div class="flex flex-wrap gap-2 mb-8">
                        @foreach (['today' => 'Today', '7days' => 'Last 7 Days', '30days' => 'Last 30 Days', 'all' => 'All Time'] as $key => $label)
                            <a href="{{ route('admin.events.clicks', ['event' => $event->id, 'filter' => $key]) }}"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm"
                                style="{{ $currentFilter == $key
                                    ? 'background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff !important; box-shadow: 0 2px 8px rgba(99,102,241,0.3);'
                                    : 'background-color: var(--bg-secondary); color: var(--text-secondary); border: 1px solid var(--border);' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-5 rounded-xl shadow-lg"
                            style="color: #fff !important;">
                            <div class="flex items-center">
                                <div class="p-3 rounded-lg" style="background: rgba(255,255,255,0.2);">
                                    <i class="fas fa-mobile-alt fa-lg" style="color: #fff !important;"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm" style="color: rgba(255,255,255,0.8) !important;">Unique Mobile
                                        Clicks</p>
                                    <p class="text-2xl font-bold" style="color: #fff !important;">{{ $mobileClicks }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-5 rounded-xl shadow-lg"
                            style="color: #fff !important;">
                            <div class="flex items-center">
                                <div class="p-3 rounded-lg" style="background: rgba(255,255,255,0.2);">
                                    <i class="fas fa-desktop fa-lg" style="color: #fff !important;"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm" style="color: rgba(255,255,255,0.8) !important;">Unique Desktop
                                        Clicks</p>
                                    <p class="text-2xl font-bold" style="color: #fff !important;">{{ $desktopClicks }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-5 rounded-xl shadow-lg"
                            style="color: #fff !important;">
                            <div class="flex items-center">
                                <div class="p-3 rounded-lg" style="background: rgba(255,255,255,0.2);">
                                    <i class="fas fa-users fa-lg" style="color: #fff !important;"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm" style="color: rgba(255,255,255,0.8) !important;">Total Unique
                                        Clicks</p>
                                    <p class="text-2xl font-bold" style="color: #fff !important;">{{ $allUniqueClicks }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-5 rounded-xl shadow-lg"
                            style="color: #fff !important;">
                            <div class="flex items-center">
                                <div class="p-3 rounded-lg" style="background: rgba(255,255,255,0.2);">
                                    <i class="fas fa-chart-bar fa-lg" style="color: #fff !important;"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm" style="color: rgba(255,255,255,0.8) !important;">All Clicks
                                        (Incl. Revisits)</p>
                                    <p class="text-2xl font-bold" style="color: #fff !important;">
                                        {{ $allClicksWithRevisits }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Table -->
                    <div class="rounded-lg overflow-hidden" style="border: 1px solid var(--border);">
                        <div class="px-6 py-4"
                            style="background-color: var(--bg-secondary); border-bottom: 1px solid var(--border);">
                            <h4 class="text-lg font-semibold" style="color: var(--text-primary);">
                                <i class="fas fa-table mr-2" style="color: #6366f1 !important;"></i>Detailed Breakdown
                            </h4>
                        </div>
                        <table class="min-w-full divide-y" style="border-color: var(--border);">
                            <thead>
                                <tr style="background-color: var(--bg-secondary);">
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider"
                                        style="color: var(--text-muted) !important;">Metric</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider"
                                        style="color: var(--text-muted) !important;">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="border-bottom: 1px solid var(--border);">
                                    <td class="px-6 py-4 text-sm" style="color: var(--text-primary);">
                                        <i class="fas fa-mobile-alt mr-2" style="color: #3b82f6 !important;"></i>Unique
                                        Mobile Clicks
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold" style="color: var(--text-primary);">
                                        {{ $mobileClicks }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid var(--border);">
                                    <td class="px-6 py-4 text-sm" style="color: var(--text-primary);">
                                        <i class="fas fa-desktop mr-2" style="color: #8b5cf6 !important;"></i>Unique
                                        Desktop Clicks
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold" style="color: var(--text-primary);">
                                        {{ $desktopClicks }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid var(--border);">
                                    <td class="px-6 py-4 text-sm" style="color: var(--text-primary);">
                                        <i class="fas fa-users mr-2" style="color: #22c55e !important;"></i>Total
                                        Unique Clicks
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold" style="color: var(--text-primary);">
                                        {{ $allUniqueClicks }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 text-sm" style="color: var(--text-primary);">
                                        <i class="fas fa-chart-bar mr-2" style="color: #f97316 !important;"></i>All
                                        Clicks (Including Revisits)
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold" style="color: var(--text-primary);">
                                        {{ $allClicksWithRevisits }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
