@extends('layouts.consultations')
@section('title', 'Payment | P23 Africa')

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

    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
@endsection


<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>  -->


<style>
    span,
    a {
        font-family: "GT Walsheim Con";
    }

    .payment-main-container {

        background-image: url(../img/payment-success-page.png);


        background-repeat: repeat;
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 100vh;

        color: #fff;
    }


    .payment-box-content {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        text-align: center;
        margin: 8rem 0rem;
        max-width: 400px;
        margin: 0 auto;
    }

    .success-icon-container {
        width: 80px;
        height: 80px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50px;
        background: #7EE6BA1F;

    }

    .success-icon {
        width: 30px;
        height: 30px;
        background-color: #fff;
        border-radius: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .success-icon i {
        color: #06271f;
        font-weight: bold;
    }

    .payment-box-content h1.payment-status {
        font-family: "GT Walsheim Con";
        color: #fff;
        font-size: 1.4rem;
        margin-top: 0.5rem;
    }

    .payment-box-content .payment-card {
        display: flex;
        /* justify-content: center; */
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        /* background-color: #ffffff; */
        position: relative;
        width: 100%;
        border-radius: 20px;
        color: #06271f;
        padding: 2rem;

        margin-top: 50px;
        position: relative;

        /* background-image: url(../img/payment-bg.png); */
    }

    .payment-box-content .payment-card .payment-card-cont {
        z-index: 2;
    }

    .payment-box-content .payment-card img {
        position: absolute;
        object-fit: contain;
        z-index: 0;
        top: 0;
        /* width: auto; */
        width: auto;
        max-width: 100%;
        /* height: 100%; */
        height: 269px;
        border-radius: 20px;
        /* transform: translateX(-28px); */
        /* box-shadow: 0px 10px 13px -3px rgba(0, 0, 0, 0.9); */

    }

    .payment-card .card-title {
        padding: 1.5rem 2rem 1rem 2rem;
        /* background-color: #06271f; */
        background-image: url(../img/payment-success-page.png);

        border-radius: 20px;
        font-family: "GT Walsheim Trial Cn Md";
        font-weight: 700;
        letter-spacing: 0.9px;

        position: absolute;
        color: #fff;
        font-size: 0.8rem;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
    }

    .payment-card .amount-date-time {
        margin-top: 25px;
    }

    .amount-date-time .amount {
        color: #121212;
        font-size: 2.6rem;
        font-family: "GT Walsheim Con";
        font-weight: 600;
        letter-spacing: 0.9px;
    }

    .amount-date-time .date-time {
        display: flex;
        justify-content: space-between;
        max-width: 110px;
        margin: 0 auto;
        margin-top: 4px;
    }

    .amount-date-time span {
        font-size: 0.75rem;
        color: #121212;
    }

    .payment-card .details-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        max-width: 250px;
        margin: 0 auto;
        margin-top: 0px;
    }

    .details-row .detail-item {
        padding: 10px 0px;
    }

    .details-row .detail-item span {
        display: block;
        letter-spacing: 0.25px;
        font-size: 0.75rem;

    }

    .details-row .divider {
        width: 1.2px;
        background-color: #0d4036;
        opacity: 0.7;
    }

    .details-row .detail-item span.label {
        color: #707070;
        margin-bottom: 3px;
    }

    .details-row .detail-item span.value {
        color: #121212;
        font-size: 0.7rem;
    }

    p.thank-you-message {
        font-family: "GT Walsheim Con";
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.9px;
        max-width: 300px;
        margin: 0 auto;
        margin-top: 60px;
        color: #fff;
    }

    a.proceed-button {
        width: 100%;
        padding: 1rem;
        font-size: 0.8rem;
        letter-spacing: 0.6px;
        border: 1px solid #549386;
        font-family: 600;
        color: #fff;
        background-color: #0d4036;
        margin: 40px auto;
        text-decoration: none;
        border-radius: 10px;
        font-family: "GT Walsheim Trial Cn Md";
        box-shadow: 0px 10px 13px -3px rgba(0, 0, 0, 0.9);


    }

    @media (min-width: 1400px) {
        .payment-box-content {
            margin-top: 2rem;
        }
    }
</style>
@section('content')
    <section class="payment-main-container">
        <div class="payment-container">
            <div class="payment-box-content">
                <div class="success-icon-container">
                    <div class="success-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </div>

                @if (session('is_pending_verification'))
                    <h1 class="payment-status">{{ __('consultation.payment_started') }}</h1>
                @else
                    <h1 class="payment-status">{{ __('consultation.payment_success') }}</h1>
                @endif


                <div class="payment-card">
                    <img src="{{ asset('img/payment-bg.png') }}" alt="" />

                    <div class="payment-card-cont">

                        <div class="amount-date-time">
                            <span class="amount">{{ strtoupper($latestPayment->currency ?? 'GBP') }} {{ number_format($latestPayment->amount ?? 75, 2) }}</span>
                            <div class="date-time">
                                <span class="date">{{ ($latestPayment->created_at ?? now())->format('M d, Y') }}</span>
                                <span class="time">{{ ($latestPayment->created_at ?? now())->format('g:iA') }}</span>
                            </div>
                        </div>
                        <div class="details-row">
                            <div class="detail-item">
                                <span class="label">{{ __('consultation.reference') }}</span>
                                <span class="value">{{ Str::limit($latestPayment->payment_reference ?? 'N/A', 10) }}</span>
                            </div>
                            <div class="divider"></div>

                            <div class="detail-item">
                                <span class="label">{{ __('consultation.pay_method_label') }}</span>
                                <span class="value">{{ ucfirst($latestPayment->payment_method ?? 'N/A') }}</span>
                            </div>
                        </div>

                    </div>

                    @if (session('is_pending_verification'))
                        <p class="thank-you-message">
                            {{ __('consultation.thanks_pending', ['name' => $application->name]) }}
                        </p>
                    @else
                        <p class="thank-you-message">
                            {{ __('consultation.thanks_booked', ['name' => $application->name]) }}
                        </p>
                    @endif

                    <a href="{{ locale_route('consultation.schedule-consultation', ['booking' => $application->booking_token]) }}" class="proceed-button">
                        {{ __('consultation.proceed_schedule') }}
                    </a>
                </div>
            </div>
    </section>


@endsection


<script></script>
