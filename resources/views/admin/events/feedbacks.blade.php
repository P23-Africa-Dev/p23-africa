<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event Feedback') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <h2>Feedback for "{{ $event->title }}"</h2>

        @if ($feedbacks->count())
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Rating</th>
                        <th>Comments</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($feedbacks as $feedback)
                        <tr>
                            <td>{{ $feedback->email }}</td>
                            <td>{{ $feedback->rating }}</td>
                            <td>{{ $feedback->comments ?? 'N/A' }}</td>
                            <td>{{ $feedback->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No feedback has been received yet.</p>
        @endif
    </div>
</x-app-layout>
