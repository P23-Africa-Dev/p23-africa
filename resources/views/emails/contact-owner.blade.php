<h3>New Contact Submission</h3>
<p><strong>Name:</strong> {{ $data['name'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>
<p><strong>Phone Number:</strong> {{ $data['country_code'] . ' ' . $data['phone_no'] }}</p>
<p><strong>Job Title:</strong> {{ $data['job_title'] ?? 'Not provided' }}</p>
<p><strong>Company Name:</strong> {{ $data['company_name'] ?? 'Not provided' }}</p>
<p><strong>Message:</strong></p>
<p>{{ $data['message'] }}</p>
