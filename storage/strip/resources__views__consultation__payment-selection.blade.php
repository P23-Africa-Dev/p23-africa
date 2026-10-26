@extends('layouts.consultations')
@section('title', 'Select Payment Method | P23 Africa')

@section('meta')
    <meta name="description"
        content="Complete your P23 Africa consultation booking with secure Stripe card payment.">
    <meta name="keywords"
        content="payment, consultation booking, P23 Africa, Stripe, business consultation payment">
    <meta name="author" content="P23 Africa">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection



@section('content')
    @php
        $bookingQuery = $application->booking_token ? ['booking' => $application->booking_token] : [];
    @endphp
    <section class="payment-selection-container">
        <div class="payment-wrapper">
            <a href="{{ route('consultation.consultation-summary', $bookingQuery) }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Summary
            </a>

            <div class="payment-header">
                <h1>{{ __('consultation.payment_method') }}</h1>
                <p>Choose your preferred payment method to complete your consultation booking</p>
            </div>

            <div class="consultation-details">
                <h3>Consultation Details</h3>
                <div class="detail-row">
                    <span>Consultation Type:</span>
                    <span>1-on-1 Business Strategy Session</span>
                </div>
                <div class="detail-row">
                    <span>Duration:</span>
                    <span>90 minutes</span>
                </div>
                <div class="detail-row">
                    <span>Consultant:</span>
                    <span>P23 Africa Expert</span>
                </div>
                <div class="detail-row">
                    <span>Total Amount:</span>
                    <span><strong id="paymentAmountLabel">GBP 75.00</strong></span>
                </div>
            </div>

            <div id="paymentErrorBanner" class="payment-error-banner" role="alert" aria-live="polite"></div>

            <form method="POST" action="{{ route('consultation.payment.process') }}" id="paymentForm">
                @csrf

                <div class="payment-methods">
                    <h3>Choose Payment Method</h3>

                    <div class="payment-options">
                        <label class="payment-option" for="stripe" data-gateway="stripe">
                            <input type="radio" id="stripe" name="payment_method" value="stripe" required>
                            <img src="{{ asset('img/stripe-logo.png') }}" alt="Stripe" class="payment-logo"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display:none; font-size: 2rem; color: #635bff;">💳</div>
                            <h4>Stripe</h4>
                            <p>Secure payment processing with major credit cards and digital wallets <b>Globally</b></p>
                        </label>
                    </div>

                    @error('payment_method')
                        <div style="color: #dc3545; text-align: center; margin-top: 10px; font-family: 'GT Walsheim Con';">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="button" class="proceed-payment-btn" id="proceedBtn" disabled onclick="processPayment()">
                    <i class="fa-solid fa-lock"></i>
                    Secure Payment - <span id="proceedAmountLabel">GBP 75.00</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </section>

    <!-- Stripe JS -->
    

    
@endsection
