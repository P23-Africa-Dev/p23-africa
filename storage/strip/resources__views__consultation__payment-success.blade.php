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


<!--   -->



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
                                <span class="label">Reference Number</span>
                                <span class="value">{{ Str::limit($latestPayment->payment_reference ?? 'N/A', 10) }}</span>
                            </div>
                            <div class="divider"></div>

                            <div class="detail-item">
                                <span class="label">Payment Method</span>
                                <span class="value">{{ ucfirst($latestPayment->payment_method ?? 'N/A') }}</span>
                            </div>
                        </div>

                    </div>

                    @if (session('is_pending_verification'))
                        <p class="thank-you-message">
                            Thank you {{ $application->name }}! Your bank transfer payment is being processed. You can
                            proceed to schedule your consultation - we'll confirm your payment shortly.
                        </p>
                    @else
                        <p class="thank-you-message">
                            Thank you {{ $application->name }} for booking your consultation with us.
                        </p>
                    @endif

                    <a href="{{ locale_route('consultation.schedule-consultation', ['booking' => $application->booking_token]) }}" class="proceed-button">
                        Proceed to scheduling your session
                    </a>
                </div>
            </div>
    </section>


@endsection



