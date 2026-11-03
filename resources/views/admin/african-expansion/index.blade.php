<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                African Expansion Guide
            </h2>
            <a href="{{ orgRoute('admin.african-expansion.export', request()->query()) }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Export CSV
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <dt class="text-sm font-medium text-gray-500 truncate">Page visits</dt>
                        <dd class="text-2xl font-semibold text-gray-900">{{ number_format($totalVisits) }}</dd>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <dt class="text-sm font-medium text-gray-500 truncate">Guide opens</dt>
                        <dd class="text-2xl font-semibold text-gray-900">{{ number_format($totalDownloads) }}</dd>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <dt class="text-sm font-medium text-gray-500 truncate">Visit countries</dt>
                        <dd class="text-2xl font-semibold text-gray-900">{{ number_format($visitCountries) }}</dd>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <dt class="text-sm font-medium text-gray-500 truncate">Download countries</dt>
                        <dd class="text-2xl font-semibold text-gray-900">{{ number_format($downloadCountries) }}</dd>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <form method="GET" action="{{ orgRoute('admin.african-expansion.index') }}"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date range</label>
                            <select name="filter"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm"
                                onchange="this.form.querySelectorAll('.custom-date').forEach(el => el.style.display = this.value === 'custom' ? '' : 'none')">
                                <option value="all" @selected($filter === 'all')>All time</option>
                                <option value="today" @selected($filter === 'today')>Today</option>
                                <option value="7days" @selected($filter === '7days')>Last 7 days</option>
                                <option value="30days" @selected($filter === '30days')>Last 30 days</option>
                                <option value="custom" @selected($filter === 'custom')>Custom range</option>
                            </select>
                        </div>
                        <div class="custom-date" style="{{ $filter !== 'custom' ? 'display:none' : '' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start date</label>
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                        </div>
                        <div class="custom-date" style="{{ $filter !== 'custom' ? 'display:none' : '' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">End date</label>
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm">
                        </div>
                        <div>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium text-gray-900">Countries</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Country</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Visits</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guide opens</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($countries as $row)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->country_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ number_format($row->visit_count) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ number_format($row->download_count) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">No country data yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium text-gray-900">Recent activity</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">When</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Country</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Device</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Browser</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($activity as $row)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($row->route_name === 'African_Expansion_Guide_Download')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Guide open</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Visit</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $row->created_at?->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $row->country ?: 'Unknown' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $row->city ?: '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ ucfirst($row->device_type ?? '—') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ trim(($row->browser ?? '').' '.($row->browser_version ?? '')) ?: '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->ip_address }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">No activity yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($activity->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200">
                        {{ $activity->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
