<h3>Hello {{ $data['name'] }}@if (!empty($data['company_name']))
        from {{ $data['company_name'] }}
    @endif,</h3>
<p>
    Thank you for reaching out to P23 Africa. We have received your request and our team will get back to you soon.
    Until then, follow us on <a href="https://www.linkedin.com/company/p23-africa/">Linkedin</a> or <a
        href="https://www.tiktok.com/@p23africa?_t=ZN-8zOFQMxpTU6&_r=1">Tiktok</a> to keep up with us.
</p>

@if (!empty($data['services']) && is_array($data['services']) && count($data['services']) > 0)
    <p><strong>Services you inquired about:</strong></p>
    <ul>
        @foreach ($data['services'] as $service)
            <li>{{ $service }}</li>
        @endforeach
    </ul>
@endif
