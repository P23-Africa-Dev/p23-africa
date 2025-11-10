<h3>New Contact Submission</h3>
<p><strong>Name:</strong> {{ $data['name'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>

@if (isset($data['services']) && is_array($data['services']) && count($data['services']) > 0)
    <p><strong>Services Requested:</strong></p>
    <ul>
        @foreach ($data['services'] as $service)
            <li>{{ $service }}</li>
        @endforeach
    </ul>
@endif

<p><strong>Message:</strong></p>
<p>{{ $data['message'] }}</p>

<hr>
<p><strong>Submission Details:</strong></p>
<p><strong>Submitted on:</strong> {{ $data['created_at'] ?? now()->format('Y-m-d H:i:s') }}</p>
@if (isset($data['ip_address']))
    <p><strong>IP Address:</strong> {{ $data['ip_address'] }}</p>
@endif
