<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Seat Code</th>
            <th>Event</th>
            <th>Booked At</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($seats as $seat)
            <tr>
                <td>{{ $seat->name }}</td>
                <td>{{ $seat->email }}</td>
                <td>{{ $seat->seat_code }}</td>
                <td>{{ $seat->event->title }}</td>
                <td>{{ $seat->created_at->format('d M, Y H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
