<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking List') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <h2>Bookings for: {{ $event->title }}</h2>

        <button id="copyEmailsBtn" class="btn btn-success mb-3">Copy All Emails</button>
        <textarea id="emailList" class="form-control mb-3" rows="3" readonly>
@foreach ($bookings as $booking)
{{ $booking->email }},
@endforeach
</textarea>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Seat Code</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $index => $booking)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $booking->name }}</td>
                        <td>{{ $booking->email }}</td>
                        <td>{{ $booking->seat_code }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('copyEmailsBtn').addEventListener('click', function() {
            const textarea = document.getElementById('emailList');
            textarea.select();
            textarea.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(textarea.value.trim()).then(() => {
                alert('All emails copied to clipboard!');
            });
        });
    </script>

</x-app-layout>
