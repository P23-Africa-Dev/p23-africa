<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event Click Report') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        <h2>Event Click Report: {{ $event->title }}</h2>

        @php
            $currentFilter = request('filter', 'today');
        @endphp

        <div class="my-3">
            @foreach (['today' => 'Today', '7days' => 'Last 7 Days', '30days' => 'Last 30 Days', 'all' => 'All Time'] as $key => $label)
                <a href="{{ route('admin.events.clicks', ['event' => $event->id, 'filter' => $key]) }}"
                    class="btn btn-sm {{ $currentFilter == $key ? 'btn-danger' : 'btn-primary' }} mx-1"
                    onmouseover="this.classList.add('btn-danger');"
                    onmouseout="if ('{{ $currentFilter }}' != '{{ $key }}') this.classList.remove('btn-danger');">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <table class="table table-bordered mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>Metric</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Unique Mobile Clicks</td>
                    <td>{{ $mobileClicks }}</td>
                </tr>
                <tr>
                    <td>Unique Desktop Clicks</td>
                    <td>{{ $desktopClicks }}</td>
                </tr>
                <tr>
                    <td>Total Unique Clicks</td>
                    <td>{{ $allUniqueClicks }}</td>
                </tr>
                <tr>
                    <td>All Clicks (Including Revisits)</td>
                    <td>{{ $allClicksWithRevisits }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-app-layout>
