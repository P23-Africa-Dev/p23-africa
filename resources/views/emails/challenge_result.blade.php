<!DOCTYPE html>
<html>
    <head>
        <title>Quiz Result</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    </head>

    <body>
        <h2>Your Quiz Result</h2>
        <p>Your result is: <strong>{{ $label }}</strong></p>

        @if ($label == 'FRAGILE FUNNEL')
            <p>Your Revenue Stream Is Vulnerable—Small Cracks Today Could Become Big Breaks Tomorrow.</p>
            <br>
            <h4 class="mb-2">Common Risks At This Stage:</h4>
            <ul>
                <li>Leads Going Cold Due To Lack Of Follow-Up</li>
                <li>Team Confusion Around Goals And Responsibilities</li>
                <li>
                    Missed Sales Opportunities And Poor Customer Experience
                </li>
                <li>Marketing Spend With Little To No Return</li>
                <li>No Way To Measure Or Replicate Success</li>
            </ul>
            <br>
            <h4 class="mb-2">What You Need:</h4>
            <p>
                Foundational Systems And Tools. You Don't Need A CRM To Get
                Started—You Need A Basic, Consistent Sales Process, Clearer
                Accountability, And A Simple Way To Track Performance.
            </p>
            <br>
            <h4 class="mb-2">Next Step:</h4>
            <p>
                Book Your Free Business Audit. We'll Walk You Through The Top
                3 Things To Fix Fast.
            </p>
            <br><br>
            <p>Thanks for completing the quiz!</p><br>
            <a href="{{ route('fragile-funnel') }}" class="btn btn-primary">Full Result</a>
        @elseif($label == 'HIDDEN POWERHOUSE')
            <p>Your revenue stream is vulnerable—small cracks today could become big breaks tomorrow.</p>
            <br>
            <h4 class="mb-2">Signs Of Readiness To Scale:</h4>
            <ul>
                <li>Consistent follow-ups and team clarity</li>
                <li>Tools and systems are in place and used well</li>
                <li>
                    Clear understanding of customer journey
                </li>
                <li>Regular performance reviews and insights</li>
                <li>You’re proactive, not reactive</li>
            </ul>
            <br>
            <h4 class="mb-2">What You Need:</h4>
            <p>
                A tailored growth plan. Now’s the time to identify advanced strategies: tightening
                conversion points, exploring new channels, or creating scalable systems.
            </p>
            <br>
            <h4 class="mb-2">Next Step:</h4>
            <p>
                We can help you refine your growth plan and plug small gaps for big results. Let’s talk.
            </p>
            <br><br>
            <p>Thanks for completing the quiz!</p><br>
            <a href="{{ route('hidden-powerhouse') }}" class="btn btn-primary">Full Result</a>
        @else
            <p>You’re on the path—but a little clarity could make your next step a leap.</p>
            <br>
            <h4 class="mb-2">What this often looks like:</h4>
            <ul>
                <li>Follow-ups happening—but not timely</li>
                <li>Team using tools inconsistently or inefficiently</li>
                <li>
                    Some growth—but mostly from effort, not strategy
                </li>
                <li>Unclear visibility into sales or marketing performance</li>
                <li>Customers falling out of the funnel without you knowing why</li>
            </ul>
            <br>
            <h4 class="mb-2">What You Need:</h4>
            <p>
                A proper growth strategy with practical, affordable systems. A few well-placed tools, a
                clear action plan, and team alignment can unlock serious gains.
            </p>
            <br>
            <h4 class="mb-2">Next Step:</h4>
            <p>
                Let’s review your business together. Our audit can show you what’s working, what’s
                not—and how to scale without burning out.
            </p>
            <br><br>
            <p>Thanks for completing the quiz!</p><br>
            <a href="{{ route('cloudy-climber') }}" class="btn btn-primary">Full Result</a>
        @endif
    </body>

</html>
