@extends('layouts.consultations')
@section('title', 'Consultation Summary | P23 Africa')

@section('meta')
    <meta name="description"
        content="Book a personalized business consultation with P23 Africa. Get expert guidance on funding readiness, market positioning, strategy development, and investor engagement tailored to African entrepreneurs and growing businesses.">
    <meta name="keywords"
        content="book consultation, business consultation, P23 Africa, African entrepreneurs, business strategy, funding readiness, investor preparation, growth consulting, startup advisory, business development Africa">
    <meta name="author" content="P23 Africa">
    <meta name="generator" content="Book Consultation | P23 Africa">

    <meta property="og:title" content="Book a Consultation | P23 Africa">
    <meta property="og:description"
        content="Schedule a one-on-one consultation to strengthen your business strategy, improve funding readiness, and unlock growth opportunities with expert advisors at P23 Africa.">
    <meta property="og:image" content="{{ asset('img/mobile.png') }}">
    <meta property="og:type" content="website">

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>  -->

<style>
    .summary-main-container {
        background-image: url(../img/bg-summary.png);
        background-repeat: repeat;
        background-size: cover;
        background-position: center;
        min-height: 100vh;
        background-color: #ffffffe4;
        background-blend-mode: soft-light;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .summary-container {
        padding: 2rem 2rem;
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 30px;
    }

    .summary-container p {
        color: #0d4036;
        font-family: "GT Walsheim Con";
        letter-spacing: 0.7px;
    }

    .summary-left-content h1 {
        color: #591044;
        text-transform: uppercase;
    }

    .summary-left-content h1 span.light {
        font-family: "Montserrat", sans-serif;
        font-weight: 300;
        font-size: 1rem;
        letter-spacing: 6px;
    }

    .summary-left-content h1 span.bold {
        font-weight: bolder;
        font-size: 2.8rem;
        line-height: 0.9;
        margin-top: 2px;
        display: block;
        font-family: "GT Walsheim ProCB";
    }

    .summary-left-content .title p {
        font-size: 0.78rem;
        max-width: 290px;
        margin-top: 6px;
    }

    .summary-left-content .title p span {
        font-weight: bolder;
        font-family: "GT Walsheim ProCB";
    }

    .summary-left-content .overview {
        margin-top: 30px;
    }

    .summary-left-content .overview h3 {
        font-family: "GT Walsheim ProCB";
        margin-bottom: 7px;
        font-size: 1.1rem;
        color: #0d4036;
    }

    .summary-left-content .overview p {
        letter-spacing: 0.5px;
        font-size: 0.85rem;
        padding-right: 8px;
        max-width: 322px;
        line-height: 1.3;
    }

    /* ================ SUMMMARY CHALLENGES AND GOALS================= */

    .summary-left-content .summary-section {
        margin-top: 30px;
    }

    .summary-section h4 {
        font-family: "GT Walsheim ProCB";
        margin-bottom: 7px;
        font-size: 1.1rem;
        color: #0d4036;
    }

    .list-container .lists {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
        margin: 0 auto;
        margin-top: 30px;
    }

    .summary-section .list-card {
        position: relative;
        background: #fff;
        color: #0d4036;
        height: 60px;

        border-radius: 14px;
        border: 1px solid #e2e2e2;
        box-shadow: 0px 9px 13px -3px rgb(30, 210, 170, 0.3);

        transform: scale(1.01);
        color: #1ed2aa;
        padding: 15px 10px;
        width: 37vw;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;

        font-size: 0.6rem;
    }

    .summary-section .list-card input {
        display: none;
    }

    .list-text {
        font-weight: 600;
        font-size: 0.75rem;
        line-height: 1.1;
        pointer-events: none;
        font-family: "GT Walsheim Con";
        padding: 0px 5px;
        letter-spacing: 0.7px;
        color: #118f73;
        /* max-width: 115px; */
    }

    /* === CHECKMARK CIRCLE === */
    .list-checkmark {
        position: absolute;
        top: -15px;
        right: -8px;
        width: 28px;
        height: 28px;
        background-color: #118f73;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transform: scale(0.5);
        transition: all 0.3s ease;

        transform: scale(1);
    }

    .list-checkmark img {
        width: 14px;
        height: 14px;
        object-fit: contain;
        border-radius: 50%;
        filter: brightness(0) invert(1);
    }

    /* =================================== SUMMARY BOX */

    .summary-box {
        position: relative;
        background-color: #0a3d43;
        color: #ffffff;
        padding: 30px;
        padding-bottom: 50px;
        border-radius: 10px;
        border: 2px solid #a3e6bdb2;
        margin-top: 50px;
    }

    .summary-box::before {
        content: "";
        position: absolute;
        top: -22px;
        left: 18px;
        width: 0;
        height: 0;
        border-left: 15px solid transparent;
        border-right: 15px solid transparent;
        border-bottom: 25px solid #0a3d43;
        transform: rotate(-3deg);
        z-index: 10;
    }

    .summary-box::after {
        content: "";
        position: absolute;
        top: -25.3px;
        left: 16px;
        width: 0;
        height: 0;
        border-left: 17px solid transparent;
        border-right: 17px solid transparent;
        border-bottom: 25px solid #a3e6bdb2;
        transform: rotate(-4deg);
        z-index: 9;
    }

    .summary-box h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-top: 0;
        margin-bottom: 10px;
        font-family: "GT Walsheim ProCB";

        line-height: 1.3;
    }

    .summary-box ul {
        padding-left: 10px;
    }

    .summary-box ul li {
        font-family: "GT Walsheim Con";
        font-size: 0.8rem;
        letter-spacing: 0.8px;
        line-height: 1.2;
    }

    .summary-box ul:first-of-type {
        list-style: disc;
        margin: 0;
        margin-bottom: 40px;
    }

    .summary-box p.subtitle {
        font-size: 0.8rem;
        margin-bottom: 8px;
        font-family: "GT Walsheim Con";
        color: #fff;
    }

    .summary-box .benefit-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .summary-box .benefit-list li {
        position: relative;
        padding-left: 15px;
        margin-bottom: 6px;
        /* font-size: 1.05rem; */
        line-height: 1.4;
    }

    .summary-box .benefit-list li::before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 10px;
        height: 10px;
        /* Custom SVG checkmark icon colored with #A3E6BD */
        background-image: url("../img/list-icon.png");
        background-size: cover;
        background-repeat: no-repeat;
        border-radius: 0;
    }

    .payment-btn {
        margin-top: 50px;
    }

    .payment-btn a {
        text-decoration: none;
        background: #591044f0;
        color: #fff;
    }

    .payment-btn .btn {
        padding: 14px 40px;
        border-radius: 10px;
        border: none;
        font-size: 0.9rem;
        letter-spacing: 0.7px;
        cursor: pointer;
        transition: 0.2s;
        font-family: "GT Walsheim Con";
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        background: #591044;
        color: #fff;
        width: 100%;
        box-shadow: 0px 4px 15px -3px rgba(0, 0, 0, 0.6);
    }

    .payment-btn .btn:hover,
    .payment-btn .btn:focus,
    .payment-btn .btn:active {
        background: #591044f0;
        color: #fff;

    }

    .payment-btn .btn i {
        color: #fff;
        font-size: 17px;
    }

    /* --------------- RESPONSIVE: MOBILE COLUMN --------------- */

    /* Medium screens (small laptops) and up */
    @media (min-width: 768px) {
        .summary-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            box-shadow: 0px 9px 15px -8px rgba(0, 0, 0, 0.8);
            width: 85vw;
            margin: 40px auto;
            border-radius: 20px;
            padding: 1rem 0rem;
            padding-left: 3rem;
        }

        .summary-section .list-card {
            width: 160px;
        }

        .summary-right-content .summary-section {
            margin-top: 70px;
        }

        .summary-box,
        .payment-btn {
            margin-right: 30px;
        }
    }

    @media (min-width: 1240px) {
        .summary-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            width: 80vw;
            padding-bottom: 3rem;
        }

        .summary-left-content h1 span.light {
            letter-spacing: 8px;
            font-size: 1.2rem;
        }

        .summary-left-content h1 span.bold {
            font-weight: bolder;
            font-size: 3.95rem;
            margin-top: 9px;
            line-height: 0.8;
        }

        .summary-left-content .title p {
            font-size: 0.9rem;
            max-width: 340px;
            line-height: 1.2;
            margin-top: 1.1rem;
        }

        .summary-left-content .overview {
            margin-top: 50px;
        }

        .summary-left-content .overview h3 {
            font-size: 1.2rem;
        }

        .summary-left-content .overview p {
            font-size: 1rem;
            max-width: 506px;
            padding-right: 10px;
            line-height: 1.4;
        }

        .list-container .lists {
            gap: 40px;
        }

        .challenges .list-container .lists .list-card:last-of-type {
            /* margin-top: -1.5rem; */
        }

        .list-container {
            max-width: 460px;
        }

        .summary-section h4 {
            font-size: 1.25rem;
        }

        .summary-section .list-card {
            width: 210px;
            padding: 10px;
            height: 76px;
        }

        .list-text {
            max-width: 150px;
            font-size: 0.9rem;
        }

        .summary-right-content .summary-section {
            margin-top: 70px;
        }

        .summary-right-content .summary-section.goals {
            padding-left: 23px;
        }

        .summary-box {
            padding-bottom: 30px;
            margin-top: 30px;
        }

        .summary-box::before {
            top: 58px;
            left: -24px;
            border-top: 15px solid transparent;
            border-bottom: 15px solid transparent;
            border-right: 25px solid #0a3d43;
            border-left: none;
            transform: rotate(3deg);
            z-index: 10;
        }

        .summary-box::after {
            top: 57px;
            left: -25px;
            border-top: 17px solid transparent;
            border-bottom: 17px solid transparent;
            border-right: 25px solid #a3e6bdb2;
            border-left: none;
            transform: rotate(1deg);
            z-index: 9;
        }

        .summary-box,
        .payment-btn {
            margin-right: 26px;
        }

        .summary-box h3 {
            letter-spacing: 1px;
            font-size: 1.4rem;
        }

        .summary-box ul {
            padding-left: 24px;
        }

        .summary-box ul li {
            font-size: 1rem;
            line-height: 1.3;
        }

        .summary-box ul:first-of-type {
            margin-bottom: 10px;
        }

        .summary-box p.subtitle {
            margin-bottom: 2px;
            font-size: 1.1rem;
            padding-left: 24px;
        }

        .summary-box .benefit-list {
            padding-left: 24px;
        }

        .summary-box .benefit-list li {
            margin-bottom: 0px;
            font-size: 1.1rem;
            line-height: 1.3;
        }

        .payment-btn {
            margin-top: 45px;
            padding-right: 5rem;
        }

        .payment-btn .btn {
            width: 80%;
            padding: 15px 40px;
            margin: 0 auto;
        }
    }
</style>

@section('content')
    <section class="summary-main-container">
        <div class="summary-container">
            <!-- ---------------------------LEFT CONTENT -->
            <div class="summary-left-content">
                <div class="title">
                    <h1>
                        <span class="light">Your</span> <br />
                        <span class="bold">Consultation Summary</span>
                    </h1>


                    <p>
                        Thank You <span id="userName">{{ $application->name }}</span>, We've Captured Your
                        Key Goals And Challenges.
                    </p>
                </div>

                <div class="overview">
                    <h3>Business Overview:</h3>
                    <p>

                        {{-- (<strong>{{ $application->company }}</strong>)  --}}
                        {{ $application->business_description }}
                        {{-- <br><br> --}}
                        {{-- <strong>Industry:</strong> {{ $application->industry }} --}}
                    </p>
                </div>

                <div class="summary-section challenges">
                    <h4>Your Key Challenges:</h4>
                    <div id="challengeList" class="list-container">
                        <div class="lists">
                            @foreach (($application->selected_challenges ?? []) as $challenge)
                                <label class="list-card">
                                    <span class="list-text">{{ $challenge }}</span>
                                    <span class="list-checkmark"><img src="{{ asset('img/marked.png') }}"
                                            alt="" /></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- ---------------------------RIGHT CONTENT -->
            <div class="summary-right-content">
                <div class="summary-section goals">
                    <h4>Your Current Goals:</h4>
                    <div id="goalList" class="list-container">
                        <div class="lists">
                            @foreach (($application->selected_goals ?? []) as $goal)
                                <label class="list-card">
                                    <span class="list-text">{{ $goal }}</span>
                                    <span class="list-checkmark"><img src="{{ asset('img/marked.png') }}"
                                            alt="" /></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="summary-box">
                    <h3>Your 1hr 30m Consultation Will;</h3>
                    <ul>
                        <li>Review Your Business Position And Growth Stage</li>
                        <li>Identify The Key Blockers Slowing Your Progress</li>
                        <li>Provide Practical, Step-By-Step Recommendations</li>
                        <li>Outline A Clear Roadmap To Move Forward With Confidence</li>
                    </ul>

                    <p class="subtitle">You'll Leave The Session With:</p>
                    <ul class="benefit-list">
                        <li>Clarity</li>
                        <li>Direction</li>
                        <li>Action Steps You Can Implement Immediately</li>
                    </ul>
                </div>
                <div class="payment-btn">
                    <form method="POST" action="{{ route('consultation.payment.initiate') }}">
                        @csrf
                        <input type="hidden" name="booking" value="{{ $application->booking_token }}">
                        <button type="submit" class="btn proceed-btn">
                            Proceed to Payment
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>


@endsection


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const challengeCards = document.querySelectorAll("#challengeList .list-card");

        if (challengeCards.length === 3) {
            const lastCard = challengeCards[challengeCards.length - 1];
            lastCard.style.marginTop = "-1.5rem";
        }

        // Handle payment form submission with CSRF error handling
        const paymentForm = document.querySelector('.payment-btn form');
        if (paymentForm) {
            paymentForm.addEventListener('submit', function(e) {
                const submitBtn = paymentForm.querySelector('button[type="submit"]');

                // Show loading state
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
                }

                // Use fetch to check for 419 before actual submit
                fetch(paymentForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            ?.content || '',
                        'Accept': 'text/html',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(paymentForm)
                }).then(response => {
                    if (response.status === 419) {
                        e.preventDefault();
                        // CSRF expired - show refresh prompt
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Session Expired',
                                text: 'Your session has expired for security reasons. The page will refresh to continue.',
                                icon: 'warning',
                                confirmButtonText: 'Refresh Page',
                                confirmButtonColor: '#591044',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            alert('Your session has expired. The page will refresh.');
                            window.location.reload();
                        }
                        return;
                    }

                    // If response is OK, redirect to the response URL
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else if (response.ok) {
                        // For non-redirect responses, let the original form submission happen
                        return response.text().then(html => {
                            document.open();
                            document.write(html);
                            document.close();
                        });
                    }
                }).catch(error => {
                    console.error('Form submission error:', error);
                    // Reset button on error
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML =
                            'Proceed to Payment <i class="fa-solid fa-arrow-right"></i>';
                    }
                });

                // Prevent default form submission - we handle it via fetch
                e.preventDefault();
            });
        }
    });
</script>
