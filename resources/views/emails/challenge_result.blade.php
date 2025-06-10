<h2>Your Quiz Result</h2>
<p>Thanks for completing the quiz!</p>
<p>Your result is: <strong>{{ $label }}</strong></p>

@if($label == 'FRAGILE FUNNEL')
    <p>You might want to work on...</p>
@elseif($label == 'Hidden Powerhouse')
    <p>You have hidden strengths...</p>
@else
    <p>You’re already on a growth path!</p>
@endif
