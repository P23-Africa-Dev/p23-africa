<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Click Count Report') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="mb-2">Click Count Report</h2>

                    <div class="mb-3">
                        <a href="{{ route('admin.clicks.report', ['filter' => 'all']) }}"
                            class="btn btn-sm {{ $filter === 'all' ? 'btn-danger' : 'btn-primary' }}">
                            All
                        </a>
                        <a href="{{ route('admin.clicks.report', ['filter' => 'today']) }}"
                            class="btn btn-sm {{ $filter === 'today' ? 'btn-danger' : 'btn-primary' }}">
                            Today
                        </a>
                        <a href="{{ route('admin.clicks.report', ['filter' => '7days']) }}"
                            class="btn btn-sm {{ $filter === '7days' ? 'btn-danger' : 'btn-primary' }}">
                            Last 7 Days
                        </a>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Route Name</th>
                                <th>Device Type</th>
                                <th>Click Count</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($clicks as $click)
                                <tr>
                                    <td>{{ str_replace('_', ' ', $click->route_name) }}</td>
                                    <td>{{ ucfirst($click->device_type) }}</td>
                                    <td>{{ $click->click_count }}</td>
                                    <td>{{ $click->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No click data available for this filter.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // First, make sure Echo is properly initialized with CSRF token
            window.Echo = new Echo({
                broadcaster: 'reverb',
                key: import.meta.env.VITE_REVERB_APP_KEY,
                forceTLS: true,
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            });

            // Listen to the private clicks-track channel
            window.Echo.private('clicks-track')
                .listen('AdminClicksEvent', (event) => {
                    console.log('Received event:', event);
                    // Find if this click already exists in the table
                    const clickData = event.clicks || event;
                    const rows = document.querySelectorAll('table tbody tr');
                    let found = false;

                    rows.forEach(row => {
                        const routeNameCell = row.querySelector('td:first-child');
                        const deviceTypeCell = row.querySelector('td:nth-child(2)');

                        if (routeNameCell && deviceTypeCell) {
                            const routeName = routeNameCell.textContent.trim();
                            const deviceType = deviceTypeCell.textContent.trim().toLowerCase();

                            // If we found a matching row, update it
                            if (routeName === clickData.route_name.replace(/_/g, ' ') &&
                                deviceType === clickData.device_type) {
                                row.querySelector('td:nth-child(3)').textContent = clickData
                                .click_count;
                                row.querySelector('td:nth-child(4)').textContent = formatDateTime(
                                    clickData.updated_at || new Date());
                                found = true;

                                // Highlight the updated row
                                row.classList.add('table-warning');
                                setTimeout(() => row.classList.remove('table-warning'), 2000);
                            }
                        }
                    });

                    // If no matching row was found, add a new one
                    if (!found) {
                        addNewClickRow(clickData);
                    }

                    // If table was empty, remove the "No click data" row
                    const emptyRow = document.querySelector('tbody tr td[colspan="4"]');
                    if (emptyRow) {
                        emptyRow.parentElement.remove();
                    }
                });

            // Helper function to format date
            function formatDateTime(dateTime) {
                const date = new Date(dateTime);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');

                return `${year}-${month}-${day} ${hours}:${minutes}`;
            }

            // Helper function to add a new row to the table
            function addNewClickRow(clickData) {
                const tbody = document.querySelector('table tbody');
                const newRow = document.createElement('tr');
                newRow.classList.add('table-success');

                newRow.innerHTML = `
            <td>${clickData.route_name.replace(/_/g, ' ')}</td>
            <td>${clickData.device_type.charAt(0).toUpperCase() + clickData.device_type.slice(1)}</td>
            <td>${clickData.click_count}</td>
            <td>${formatDateTime(clickData.updated_at || new Date())}</td>
        `;

                tbody.prepend(newRow);
                setTimeout(() => newRow.classList.remove('table-success'), 2000);
            }
        });
    </script>
</x-app-layout>
