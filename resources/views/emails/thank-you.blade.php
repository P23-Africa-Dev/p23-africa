<h3>Dear {{ $data['name'] }},</h3>

<p>
    Thank you for reaching out to P23 Africa. We appreciate your interest and the time taken to connect with us.
    <br>
    We have received your message and our team is currently reviewing   it. A member of our team will be in touch with you shortly to follow up and provide the appropriate next steps.
</p>
<p>
    In the meantime, you are welcome to stay connected with us and explore our activities and updates on our social platforms: <a href="https://linkedin.com/company/p23-africa/">Linkedin</a>, <a href="https://instagram.com/p23africa/">Instagram</a>, and <a
        href="https://tiktok.com/@p23africa?_t=ZN-8zOFQMxpTU6&_r=1">Tiktok</a>.
</p>

@if (!empty($data['services']) && is_array($data['services']) && count($data['services']) > 0)
    <p><strong>Services you inquired about:</strong></p>
    <ul>
        @foreach ($data['services'] as $service)
            <li>{{ $service }}</li>
        @endforeach
    </ul>
@endif

@if (!empty($data['budget_range']))
    <p><strong>Your Budget Range:</strong> ${{ number_format($data['budget_range']) }}</p>
@endif

<p>
    Thank you once again for contacting P23 Africa. We look forward to speaking with you soon.
</p>

<p>
    Warm regards,<br>
    <b>The P23 Africa Team</b>
</p>

<!--{{-- <h3>Hello {{ $data['name'] }}@if (!empty($data['company_name']))-->
<!--        from {{ $data['company_name'] }}-->
<!--    @endif,</h3>-->
<!--<p>-->
<!--    Thank you for reaching out to P23 Africa. We have received your request and our team will get back to you soon.-->
<!--    Until then, follow us on <a href="https://www.linkedin.com/company/p23-africa/">Linkedin</a> or <a-->
<!--        href="https://www.tiktok.com/@p23africa?_t=ZN-8zOFQMxpTU6&_r=1">Tiktok</a> to keep up with us.-->
<!--</p>-->

<!--@if (!empty($data['budget_range']))-->
<!--    <p><strong>Your Budget Range:</strong> ${{ number_format($data['budget_range']) }}</p>-->
<!--@endif-->

<!--@if (!empty($data['services']) && is_array($data['services']) && count($data['services']) > 0)-->
<!--    <p><strong>Services you inquired about:</strong></p>-->
<!--    <ul>-->
<!--        @foreach ($data['services'] as $service)-->
<!--            <li>{{ $service }}</li>-->
<!--        @endforeach-->
<!--    </ul>-->
<!--@endif --}}-->