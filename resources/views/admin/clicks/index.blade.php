<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Advanced Analytics Dashboard') }}
            </h2>
            <div class="flex space-x-2 flex-wrap items-center">
                <a href="{{ orgRoute('admin.clicks.export', request()->query()) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
                <button onclick="refreshData()"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-sync mr-2"></i>Refresh
                </button>

                @php
                    // Check if there are records that need country backfilling
                    $needsBackfill = \App\Models\PageVisit::whereIn('country', ['Others', 'Unknown'])
                        ->orWhereNull('country')
                        ->count();
                @endphp

                @if ($needsBackfill > 0)
                    <button onclick="showBackfillModal()"
                        class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-globe-americas mr-2"></i>Update Countries ({{ number_format($needsBackfill) }})
                    </button>
                @endif

                <div class="text-xs text-gray-500 mt-1">
                    Data cached for 5min
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Analytics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-eye text-3xl text-blue-500"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Visits</dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ number_format($analytics['total_visits']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users text-3xl text-green-500"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Unique Visitors</dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ number_format($analytics['unique_visitors']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file-alt text-3xl text-purple-500"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pages Viewed</dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ number_format($analytics['total_pages']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-3xl text-orange-500"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Avg. Time on Page</dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $analytics['avg_time_on_page'] ? number_format($analytics['avg_time_on_page'], 1) . 's' : 'N/A' }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Daily Visits Chart -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 chart-container" style="height: 300px;">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Daily Visits (Last 5 Days)</h3>
                        <div style="height: 240px; width: 100%;">
                            <canvas id="dailyVisitsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Device Distribution Chart -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 chart-container" style="height: 300px;">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Device Distribution</h3>
                        <div style="height: 240px; width: 100%;">
                            <canvas id="deviceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Filters</h3>

                    <form method="GET" action="{{ orgRoute('admin.clicks.report') }}"
                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">

                        <!-- Date Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                            <select name="filter"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Time</option>
                                <option value="today" {{ $filter === 'today' ? 'selected' : '' }}>Today</option>
                                <option value="yesterday" {{ $filter === 'yesterday' ? 'selected' : '' }}>Yesterday
                                </option>
                                <option value="7days" {{ $filter === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="30days" {{ $filter === '30days' ? 'selected' : '' }}>Last 30 Days
                                </option>
                                <option value="this_month" {{ $filter === 'this_month' ? 'selected' : '' }}>This Month
                                </option>
                                <option value="last_month" {{ $filter === 'last_month' ? 'selected' : '' }}>Last Month
                                </option>
                                <option value="custom" {{ $filter === 'custom' ? 'selected' : '' }}>Custom Range
                                </option>
                            </select>
                        </div>

                        <!-- Custom Date Range -->
                        <div class="custom-date-range" style="{{ $filter !== 'custom' ? 'display: none;' : '' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="custom-date-range" style="{{ $filter !== 'custom' ? 'display: none;' : '' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Device Type Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Device Type</label>
                            <select name="device_type"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="all" {{ $deviceType === 'all' ? 'selected' : '' }}>All Devices
                                </option>
                                @foreach ($filterOptions['device_types'] as $device)
                                    <option value="{{ $device }}"
                                        {{ $deviceType === $device ? 'selected' : '' }}>
                                        {{ ucfirst($device) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Country Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <select name="country"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="all" {{ $country === 'all' ? 'selected' : '' }}>All Countries
                                </option>
                                @foreach ($filterOptions['countries'] as $countryOption)
                                    <option value="{{ $countryOption }}"
                                        {{ $country === $countryOption ? 'selected' : '' }}>
                                        {{ $countryOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Page Route Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Page</label>
                            <select name="page_route"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="all" {{ $pageRoute === 'all' ? 'selected' : '' }}>All Pages</option>
                                @foreach ($filterOptions['page_routes'] as $route)
                                    <option value="{{ $route }}"
                                        {{ $pageRoute === $route ? 'selected' : '' }}>
                                        @php
                                            $pageTitles = [
                                                'homepage' => 'Home Page',
                                                'Contact_Page' => 'Contact Us',
                                                'About_Page' => 'About Us',
                                                'Service_Page' => 'Our Services',
                                                'Sales_Page' => 'Sales',
                                                'Quiz_Page' => 'Sales Quiz',
                                                'NOEL_Form_Page' => 'NOEL Interest Form',
                                                'Quiz_Start_Page' => 'Quiz Start',
                                                'student' => 'Student Portal',
                                                'resource-hub' => 'Resource Hub',
                                                'event' => 'Events',
                                            ];
                                        @endphp
                                        {{ $pageTitles[$route] ?? ucwords(str_replace(['_', '-'], ' ', $route)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Browser Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Browser</label>
                            <select name="browser"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="all" {{ $browser === 'all' ? 'selected' : '' }}>All Browsers
                                </option>
                                @foreach ($filterOptions['browsers'] as $browserOption)
                                    <option value="{{ $browserOption }}"
                                        {{ $browser === $browserOption ? 'selected' : '' }}>
                                        {{ $browserOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Search -->
                        <div class="md:col-span-3 lg:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="flex">
                                <input type="text" name="search" value="{{ $search }}"
                                    placeholder="Search by page, country, city, IP..."
                                    class="flex-1 block w-full border border-gray-300 rounded-l-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ orgRoute('admin.clicks.report') }}"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md ml-2">
                                    Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Analytics Summary Boxes -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Top Pages -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Top Pages</h3>
                            @if ($analytics['top_pages']->count() > 5)
                                <button onclick="showTopPagesModal()"
                                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    See more <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            @endif
                        </div>
                        <div class="space-y-3">
                            @foreach ($analytics['top_pages']->take(5) as $page)
                                <div class="flex justify-between items-center">
                                    <div class="text-sm font-medium text-gray-900 truncate">
                                        {{ $page->page_title ?: str_replace('_', ' ', $page->route_name) }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $page->total }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Top Countries -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Top Countries</h3>
                            <div class="flex items-center space-x-3">
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-globe mr-1"></i>{{ $analytics['top_countries']->count() }}
                                    countries
                                </div>
                                @if ($analytics['top_countries']->count() > 5)
                                    <button onclick="showTopCountriesModal()"
                                        class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                        See more <i class="fas fa-arrow-right ml-1"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-3">
                            @if ($analytics['top_countries']->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-globe text-gray-300 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-500">No country data available yet</p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Run: <code class="bg-gray-100 px-2 py-1 rounded">php artisan
                                            analytics:backfill-countries</code>
                                    </p>
                                </div>
                            @else
                                @foreach ($analytics['top_countries']->take(5) as $index => $countryData)
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <div class="w-6 text-center mr-3">
                                                @php
                                                    $countryFlags = [
                                                        'Nigeria' => '🇳🇬',
                                                        'United States' => '🇺🇸',
                                                        'United Kingdom' => '🇬🇧',
                                                        'Ghana' => '🇬🇭',
                                                        'Kenya' => '🇰🇪',
                                                        'South Africa' => '🇿🇦',
                                                        'Canada' => '🇨🇦',
                                                        'Germany' => '🇩🇪',
                                                        'France' => '🇫🇷',
                                                        'Australia' => '🇦🇺',
                                                        'India' => '🇮🇳',
                                                        'Brazil' => '🇧🇷',
                                                        'Egypt' => '🇪🇬',
                                                        'Morocco' => '🇲🇦',
                                                        'Rwanda' => '🇷🇼',
                                                        'Tanzania' => '🇹🇿',
                                                        'Uganda' => '🇺🇬',
                                                        'Ethiopia' => '🇪🇹',
                                                        'Local' => '🏠',
                                                        'Unknown' => '❓',
                                                    ];
                                                @endphp
                                                <span
                                                    class="text-base">{{ $countryFlags[$countryData->country] ?? '🌍' }}</span>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">{{ $countryData->country }}
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="text-sm text-gray-500 mr-2">
                                                {{ number_format($countryData->total) }}</div>
                                            @php
                                                $totalVisits = $analytics['total_visits'] ?? 1;
                                                $percentage = ($countryData->total / $totalVisits) * 100;
                                            @endphp
                                            <div class="text-xs text-gray-400">({{ number_format($percentage, 1) }}%)
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Progress bar -->
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 -mt-1">
                                        <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-300"
                                            style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Top Cities -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Top Cities</h3>
                            @if (isset($analytics['top_cities']) && $analytics['top_cities']->count() > 5)
                                <button onclick="showTopCitiesModal()"
                                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    See more <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            @endif
                        </div>
                        <div class="space-y-3">
                            @if (isset($analytics['top_cities']) && $analytics['top_cities']->count() > 0)
                                @foreach ($analytics['top_cities']->take(5) as $cityData)
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $cityData->city }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $cityData->country }}</div>
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ number_format($cityData->total) }}</div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-city text-gray-300 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-500">No city data available yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Visits Table -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Detailed Visit Log</h3>
                        <div class="text-sm text-gray-500">
                            Showing {{ $visits->firstItem() }}-{{ $visits->lastItem() }} of {{ $visits->total() }}
                            visits
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Page</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Visitor Info</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Device & Browser</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Location</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Time</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Details</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($visits as $visit)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $visit->page_title ?: str_replace('_', ' ', $visit->route_name) }}
                                            </div>
                                            <div class="text-sm text-gray-500 truncate max-w-xs">
                                                {{ $visit->page_url }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $visit->ip_address }}</div>
                                            @if ($visit->is_unique_visitor)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Unique
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <i
                                                    class="fas fa-{{ $visit->device_type === 'mobile' ? 'mobile-alt' : ($visit->device_type === 'tablet' ? 'tablet-alt' : 'desktop') }} mr-1"></i>
                                                {{ ucfirst($visit->device_type) }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $visit->browser }} {{ $visit->browser_version }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $visit->country ?: 'Unknown' }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $visit->city ?: 'Unknown' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $visit->created_at->format('M j, Y') }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $visit->created_at->format('H:i:s') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <button onclick="showVisitDetails({{ $visit->id }})"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No visit data available for the selected filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $visits->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visit Details Modal -->
    <div id="visitDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900">Visit Details</h3>
                <div id="visitDetailsContent" class="mt-2 px-7 py-3">
                    <!-- Content will be loaded here -->
                </div>
                <div class="items-center px-4 py-3">
                    <button onclick="closeVisitDetails()"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-date-range {
            transition: all 0.3s ease;
        }

        .chart-container {
            position: relative;
            min-height: 300px;
            max-height: 300px;
            overflow: hidden;
        }

        .chart-container canvas {
            max-height: 240px !important;
            width: 100% !important;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        /* Prevent chart stretching issues */
        #dailyVisitsChart,
        #deviceChart {
            max-width: 100% !important;
            height: auto !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show loading indicators for charts initially
            const dailyVisitsChart = document.getElementById('dailyVisitsChart');
            const deviceChart = document.getElementById('deviceChart');

            // Add loading text
            dailyVisitsChart.parentElement.insertAdjacentHTML('beforeend',
                '<div id="daily-loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75"><i class="fas fa-spinner fa-spin mr-2"></i>Loading chart...</div>'
            );
            deviceChart.parentElement.insertAdjacentHTML('beforeend',
                '<div id="device-loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75"><i class="fas fa-spinner fa-spin mr-2"></i>Loading chart...</div>'
            );

            // Handle date filter changes
            document.querySelector('select[name="filter"]').addEventListener('change', function() {
                const customRanges = document.querySelectorAll('.custom-date-range');
                if (this.value === 'custom') {
                    customRanges.forEach(el => el.style.display = 'block');
                } else {
                    customRanges.forEach(el => el.style.display = 'none');
                }
            });

            // Load charts after a small delay to improve perceived performance
            setTimeout(() => {
                console.log('Starting chart initialization...');
                const startTime = performance.now();

                initializeCharts();

                const endTime = performance.now();
                console.log(`Charts loaded in ${Math.round(endTime - startTime)}ms`);
            }, 200); // Increased delay slightly

            function initializeCharts() {
                try {
                    // Daily Visits Chart with optimized settings
                    const dailyVisitsCtx = document.getElementById('dailyVisitsChart').getContext('2d');
                    const dailyVisitsData = @json($analytics['daily_visits']);

                    // Validate data before rendering
                    if (!dailyVisitsData || dailyVisitsData.length === 0) {
                        dailyVisitsCtx.canvas.parentElement.innerHTML =
                            '<div class="text-center text-gray-500 py-8">No data available</div>';
                        return;
                    }

                    // Limit data points to prevent performance issues
                    const limitedData = dailyVisitsData.slice(-5); // Only last 5 days max

                    new Chart(dailyVisitsCtx, {
                        type: 'line',
                        data: {
                            labels: limitedData.map(item => {
                                const date = new Date(item.date);
                                return date.toLocaleDateString('en-US', {
                                    month: 'short',
                                    day: 'numeric'
                                });
                            }),
                            datasets: [{
                                label: 'Total Visits',
                                data: limitedData.map(item => parseInt(item.total) || 0),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.1,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }, {
                                label: 'Unique Visitors',
                                data: limitedData.map(item => parseInt(item.unique_count) || 0),
                                borderColor: 'rgb(16, 185, 129)',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.1,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            aspectRatio: 2.5, // Fixed aspect ratio to prevent stretching
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                        maxTicksLimit: 6
                                    }
                                },
                                x: {
                                    ticks: {
                                        maxTicksLimit: 5
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    enabled: true,
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    cornerRadius: 4
                                }
                            },
                            animation: {
                                duration: 800 // Shorter animation
                            }
                        }
                    });

                    // Device Distribution Chart with optimized settings
                    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
                    const deviceData = @json($analytics['device_stats']);

                    // Validate device data
                    if (!deviceData || Object.keys(deviceData).length === 0) {
                        deviceCtx.canvas.parentElement.innerHTML =
                            '<div class="text-center text-gray-500 py-8">No device data available</div>';
                        return;
                    }

                    new Chart(deviceCtx, {
                        type: 'doughnut',
                        data: {
                            labels: Object.keys(deviceData).map(key => key.charAt(0).toUpperCase() + key
                                .slice(1)),
                            datasets: [{
                                data: Object.values(deviceData).map(val => parseInt(val) || 0),
                                backgroundColor: [
                                    'rgb(59, 130, 246)',
                                    'rgb(16, 185, 129)',
                                    'rgb(245, 158, 11)',
                                    'rgb(239, 68, 68)',
                                    'rgb(139, 92, 246)'
                                ],
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            aspectRatio: 1.5, // Fixed aspect ratio
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 15,
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    cornerRadius: 4
                                }
                            },
                            animation: {
                                duration: 800 // Shorter animation
                            }
                        }
                    });

                } catch (error) {
                    console.error('Chart initialization error:', error);

                    // Show error message and hide loading
                    const dailyLoading = document.getElementById('daily-loading');
                    const deviceLoading = document.getElementById('device-loading');

                    if (dailyLoading) {
                        dailyLoading.innerHTML =
                            '<div class="text-red-500 text-sm"><i class="fas fa-exclamation-triangle mr-2"></i>Chart loading failed</div>';
                    }

                    if (deviceLoading) {
                        deviceLoading.innerHTML =
                            '<div class="text-red-500 text-sm"><i class="fas fa-exclamation-triangle mr-2"></i>Chart loading failed</div>';
                    }

                    // Remove error messages after 3 seconds
                    setTimeout(() => {
                        dailyLoading?.remove();
                        deviceLoading?.remove();
                    }, 3000);
                }

                // Force remove loading indicators after timeout
                setTimeout(() => {
                    document.getElementById('daily-loading')?.remove();
                    document.getElementById('device-loading')?.remove();
                }, 2000);
            }

            // Auto-refresh functionality with debouncing
            let refreshTimeout;
            window.refreshData = function() {
                if (refreshTimeout) clearTimeout(refreshTimeout);

                // Show loading indicator
                const refreshBtn = document.querySelector('button[onclick="refreshData()"]');
                const originalText = refreshBtn.innerHTML;
                refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
                refreshBtn.disabled = true;

                refreshTimeout = setTimeout(() => {
                    window.location.reload();
                }, 500); // Small delay to prevent rapid clicks
            };

            // Visit details modal with loading
            window.showVisitDetails = function(visitId) {
                const modal = document.getElementById('visitDetailsModal');
                const content = document.getElementById('visitDetailsContent');

                content.innerHTML =
                    '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
                modal.classList.remove('hidden');

                // In a real implementation, you'd fetch this data via AJAX
                setTimeout(() => {
                    content.innerHTML = `
                        <div class="text-left space-y-2">
                            <p><strong>Visit ID:</strong> ${visitId}</p>
                            <p><strong>Session:</strong> sess_${Math.random().toString(36).substr(2, 9)}</p>
                            <p><strong>Screen:</strong> 1920x1080</p>
                            <p><strong>Timezone:</strong> Africa/Lagos</p>
                            <p><strong>Time on Page:</strong> ${Math.floor(Math.random() * 300)} seconds</p>
                        </div>
                    `;
                }, 800);
            };

            window.closeVisitDetails = function() {
                document.getElementById('visitDetailsModal').classList.add('hidden');
            };

            // Country backfill modal functions
            window.showBackfillModal = function() {
                document.getElementById('backfillModal').classList.remove('hidden');
            };

            window.closeBackfillModal = function() {
                document.getElementById('backfillModal').classList.add('hidden');
            };

            // Top Pages modal functions
            window.showTopPagesModal = function() {
                document.getElementById('topPagesModal').classList.remove('hidden');
            };

            window.closeTopPagesModal = function() {
                document.getElementById('topPagesModal').classList.add('hidden');
            };

            // Top Countries modal functions
            window.showTopCountriesModal = function() {
                document.getElementById('topCountriesModal').classList.remove('hidden');
            };

            window.closeTopCountriesModal = function() {
                document.getElementById('topCountriesModal').classList.add('hidden');
            };

            // Top Cities modal functions
            window.showTopCitiesModal = function() {
                document.getElementById('topCitiesModal').classList.remove('hidden');
            };

            window.closeTopCitiesModal = function() {
                document.getElementById('topCitiesModal').classList.add('hidden');
            };

            window.runBackfill = function() {
                const button = document.getElementById('runBackfillBtn');
                const status = document.getElementById('backfillStatus');

                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                status.innerHTML =
                    '<div class="text-blue-600"><i class="fas fa-info-circle mr-2"></i>Starting country data update...</div>';

                // Note: This would normally be an AJAX call to a route that runs the command
                // For now, we'll show instructions to run the command manually
                setTimeout(() => {
                    status.innerHTML = `
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h4 class="text-yellow-800 font-medium mb-2">Manual Command Required</h4>
                            <p class="text-yellow-700 text-sm mb-3">Please run this command in your terminal:</p>
                            <code class="block bg-gray-800 text-green-400 p-3 rounded text-sm font-mono">
                                php artisan analytics:backfill-countries --limit=100
                            </code>
                            <p class="text-yellow-700 text-xs mt-2">
                                This will update 100 records with real country data. Run multiple times until complete.
                            </p>
                        </div>
                    `;
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-terminal mr-2"></i>Show Commands';
                }, 1000);
            };
        });
    </script>

    <!-- Country Backfill Modal -->
    <div id="backfillModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-globe-americas mr-2 text-orange-500"></i>Update Country Data
                </h3>
                <button onclick="closeBackfillModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <h4 class="text-blue-800 font-medium mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Country Tracking Enhancement
                    </h4>
                    <p class="text-blue-700 text-sm">
                        Your analytics now detects real countries instead of showing "Others".
                        Update your historical records to see accurate geographical data.
                    </p>
                </div>

                @if ($needsBackfill > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ number_format($needsBackfill) }}</div>
                            <div class="text-sm text-orange-700">Records to Update</div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-green-600">195+</div>
                            <div class="text-sm text-green-700">Countries Supported</div>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-blue-600">FREE</div>
                            <div class="text-sm text-blue-700">GeoIP Service</div>
                        </div>
                    </div>
                @endif

                <div id="backfillStatus" class="mb-4">
                    <!-- Status updates will appear here -->
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button onclick="closeBackfillModal()"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    Cancel
                </button>
                <button id="runBackfillBtn" onclick="runBackfill()"
                    class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-play mr-2"></i>Start Update
                </button>
            </div>
        </div>
    </div>

    <!-- Top Pages Modal -->
    <div id="topPagesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-file-alt mr-2 text-purple-500"></i>All Pages
                </h3>
                <button onclick="closeTopPagesModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="max-h-96 overflow-y-auto">
                <div class="space-y-3">
                    @foreach ($analytics['top_pages'] as $index => $page)
                        <div
                            class="flex justify-between items-center p-2 {{ $index % 2 === 0 ? 'bg-gray-50' : '' }} rounded">
                            <div class="flex items-center">
                                <span class="text-gray-400 text-sm w-8">{{ $index + 1 }}.</span>
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    {{ $page->page_title ?: str_replace('_', ' ', $page->route_name) }}
                                </div>
                            </div>
                            <div class="text-sm text-gray-500 font-medium">{{ number_format($page->total) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-200 flex justify-end">
                <button onclick="closeTopPagesModal()"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Top Countries Modal -->
    <div id="topCountriesModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-globe mr-2 text-blue-500"></i>All Countries
                </h3>
                <button onclick="closeTopCountriesModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="max-h-96 overflow-y-auto">
                <div class="space-y-3">
                    @php
                        $countryFlags = [
                            'Nigeria' => '🇳🇬',
                            'United States' => '🇺🇸',
                            'United Kingdom' => '🇬🇧',
                            'Ghana' => '🇬🇭',
                            'Kenya' => '🇰🇪',
                            'South Africa' => '🇿🇦',
                            'Canada' => '🇨🇦',
                            'Germany' => '🇩🇪',
                            'France' => '🇫🇷',
                            'Australia' => '🇦🇺',
                            'India' => '🇮🇳',
                            'Brazil' => '🇧🇷',
                            'Egypt' => '🇪🇬',
                            'Morocco' => '🇲🇦',
                            'Rwanda' => '🇷🇼',
                            'Tanzania' => '🇹🇿',
                            'Uganda' => '🇺🇬',
                            'Ethiopia' => '🇪🇹',
                            'Local' => '🏠',
                            'Unknown' => '❓',
                        ];
                    @endphp
                    @foreach ($analytics['top_countries'] as $index => $countryData)
                        <div
                            class="flex justify-between items-center p-2 {{ $index % 2 === 0 ? 'bg-gray-50' : '' }} rounded">
                            <div class="flex items-center">
                                <span class="text-gray-400 text-sm w-8">{{ $index + 1 }}.</span>
                                <span class="text-base mr-2">{{ $countryFlags[$countryData->country] ?? '🌍' }}</span>
                                <div class="text-sm font-medium text-gray-900">{{ $countryData->country }}</div>
                            </div>
                            <div class="flex items-center">
                                <div class="text-sm text-gray-500 font-medium mr-2">
                                    {{ number_format($countryData->total) }}</div>
                                @php
                                    $totalVisits = $analytics['total_visits'] ?? 1;
                                    $percentage = ($countryData->total / $totalVisits) * 100;
                                @endphp
                                <div class="text-xs text-gray-400">({{ number_format($percentage, 1) }}%)</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-200 flex justify-end">
                <button onclick="closeTopCountriesModal()"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Top Cities Modal -->
    <div id="topCitiesModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-city mr-2 text-green-500"></i>All Cities
                </h3>
                <button onclick="closeTopCitiesModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="max-h-96 overflow-y-auto">
                <div class="space-y-3">
                    @if (isset($analytics['top_cities']))
                        @foreach ($analytics['top_cities'] as $index => $cityData)
                            <div
                                class="flex justify-between items-center p-2 {{ $index % 2 === 0 ? 'bg-gray-50' : '' }} rounded">
                                <div class="flex items-center">
                                    <span class="text-gray-400 text-sm w-8">{{ $index + 1 }}.</span>
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $cityData->city }}</div>
                                        <div class="text-xs text-gray-500">{{ $cityData->country }}</div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 font-medium">{{ number_format($cityData->total) }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-200 flex justify-end">
                <button onclick="closeTopCitiesModal()"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>

</x-app-layout>
