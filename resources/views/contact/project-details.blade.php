@if (!empty($details))
<section class="mt-4">
    <h3>Business and project details</h3>
    <dl>
        @foreach (['role' => 'Role in the company', 'industry' => 'Industry', 'business_description' => 'Business overview', 'expansion_markets' => 'Expansion markets', 'referral_source' => 'How they heard about us', 'referral_other' => 'Referral details', 'start_timeline' => 'Start timeline', 'additional_notes' => 'Additional notes'] as $key => $label)
            @if (!empty($details[$key]))
                <dt>{{ $label }}</dt>
                <dd>{{ is_array($details[$key]) ? implode(', ', $details[$key]) : $details[$key] }}</dd>
            @endif
        @endforeach
    </dl>
</section>
@endif
