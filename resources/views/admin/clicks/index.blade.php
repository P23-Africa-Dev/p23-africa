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
</x-app-layout>
