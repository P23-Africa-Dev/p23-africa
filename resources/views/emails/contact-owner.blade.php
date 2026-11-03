<h3>New Contact Submission</h3>
<p><strong>Name:</strong> {{ $data['name'] }}</p>
@if (!empty($data['company_name']))
    <p><strong>Company:</strong> {{ $data['company_name'] }}</p>
@endif
<p><strong>Email:</strong> {{ $data['email'] }}</p>
@if (!empty($data['phone']))
    <p><strong>Phone:</strong> {{ $data['phone'] }}</p>
@endif
@if (!empty($data['country_code']))
    <p><strong>Country Code:</strong> {{ $data['country_code'] }}</p>
@endif
@if (!empty($data['budget_range']))
    <p><strong>Budget Range:</strong> ${{ number_format($data['budget_range']) }}</p>
@endif

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

@include('contact.project-details', ['details' => $data['project_details'] ?? []])

<hr>
<p><strong>Submission Details:</strong></p>
<p><strong>Submitted on:</strong> {{ $data['created_at'] ?? now()->format('Y-m-d H:i:s') }}</p>
@if (isset($data['ip_address']))
    <p><strong>IP Address:</strong> {{ $data['ip_address'] }}</p>
@endif
