<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Click Report') }}
        </h2>
    </x-slot>

    <style>
        .filter-btn {
            padding: 8px 16px;
            margin-right: 8px;
            text-decoration: none;
            border: 1px solid #444;
            background: #f2f2f2;
            color: #000;
        }

        .filter-btn.active {
            background-color: #0a3d43;
            color: white;
            font-weight: bold;
        }
    </style>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="mb-4">Click Count Report</h2>

                    <div style="margin-bottom: 20px;">
                        <a href="{{ route('admin.clicks.report', ['filter' => 'all']) }}"
                            class="filter-btn {{ $filter === 'all' ? 'active' : '' }}">
                            All
                        </a>
                        <a href="{{ route('admin.clicks.report', ['filter' => 'today']) }}"
                            class="filter-btn {{ $filter === 'today' ? 'active' : '' }}">
                            Today
                        </a>
                        <a href="{{ route('admin.clicks.report', ['filter' => '7days']) }}"
                            class="filter-btn {{ $filter === '7days' ? 'active' : '' }}">
                            Last 7 Days
                        </a>
                    </div>

                    <table border="1" class="table-bordered w-100" cellpadding="10">
                        <thead>
                            <tr>
                                <th>Route Name</th>
                                <th>Click Count</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($clicks as $click)
                                <tr>
                                    <td class="text-capitalize">{{ $click->route_name }}</td>
                                    <td>{{ $click->click_count }}</td>
                                    <td>{{ $click->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No click data available for this filter.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
