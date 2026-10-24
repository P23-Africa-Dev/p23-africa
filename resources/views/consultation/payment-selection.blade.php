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

<style>
    .payment-selection-container {
        background-image: url(../img/bg-effect.png);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        min-height: 100vh;
        background-color: #ffffffe4;
        background-blend-mode: soft-light;
        padding: 40px 20px;
    }

    .payment-wrapper {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0px 9px 15px -8px rgba(0, 0, 0, 0.8);
    }

    .payment-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .payment-header h1 {
        color: #591044;
        font-family: "GT Walsheim ProCB";
        font-size: 2.2rem;
        margin-bottom: 10px;
    }

    .payment-header p {
        color: #0d4036;
        font-family: "GT Walsheim Con";
        font-size: 1rem;
        letter-spacing: 0.7px;
    }

    .consultation-details {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        border-left: 4px solid #1ed2aa;
    }

    .consultation-details h3 {
        color: #0d4036;
        font-family: "GT Walsheim ProCB";
        margin-bottom: 15px;
        font-size: 1.1rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        font-family: "GT Walsheim Con";
        color: #0d4036;
    }

    .detail-row:last-child {
        margin-bottom: 0;
        padding-top: 15px;
        border-top: 2px solid #e9ecef;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .payment-methods {
        margin-bottom: 30px;
    }

    .payment-methods h3 {
        color: #0d4036;
        font-family: "GT Walsheim ProCB";
        margin-bottom: 20px;
        font-size: 1.3rem;
        text-align: center;
    }

    .payment-options {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .payment-option {
        background: #fff;
        border: 2px solid #e9ecef;
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .payment-option:hover {
        border-color: #1ed2aa;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(30, 210, 170, 0.2);
    }

    .payment-option.selected {
        border-color: #1ed2aa;
        background: rgba(30, 210, 170, 0.05);
    }

    .payment-option input {
        display: none;
    }

    .payment-logo {
        height: 60px;
        margin-bottom: 15px;
        object-fit: contain;
    }

    .payment-option h4 {
        color: #0d4036;
        font-family: "GT Walsheim ProCB";
        margin-bottom: 8px;
        font-size: 1.1rem;
    }

    .payment-option p {
        color: #6c757d;
        font-family: "GT Walsheim Con";
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .proceed-payment-btn {
        width: 100%;
        background: #591044;
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 18px;
        font-size: 1.1rem;
        font-family: "GT Walsheim Con";
        letter-spacing: 0.8px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0px 4px 15px -3px rgba(0, 0, 0, 0.6);
    }

    .proceed-payment-btn:hover {
        background: #591044f0;
        transform: translateY(-1px);
    }

    .proceed-payment-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #0d4036;
        text-decoration: none;
        font-family: "GT Walsheim Con";
        margin-bottom: 20px;
        transition: color 0.3s ease;
    }

    .back-link:hover {
        color: #1ed2aa;
    }

    @media (max-width: 768px) {
        .payment-options {
            grid-template-columns: 1fr;
        }

        .payment-wrapper {
            padding: 30px 20px;
        }

        .payment-header h1 {
            font-size: 1.8rem;
        }
    }

    .fw-checkout-logo img {
        border-radius: 0 !important;
        object-fit: contain !important;
    }

    .payment-error-banner {
        display: none;
        margin: 0 0 20px;
        padding: 12px 14px;
        border-radius: 10px;
        border: 1px solid #f5c2c7;
        background: #f8d7da;
        color: #842029;
        font-family: "GT Walsheim Con";
        font-size: 0.95rem;
        line-height: 1.45;
    }
</style>

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
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        // Payment gateway configurations
        const STRIPE_PUBLIC_KEY = '{{ config('services.stripe.public_key') }}';
        const BOOKING_TOKEN = @json($application->booking_token);
        const BASE_DISPLAY_AMOUNT = 'GBP 75.00';
        const LOCAL_CURRENCY = detectUserCurrency();
        const SERVER_PAYMENT_ERROR = @json(session('error'));

        // Initialize Stripe
        let stripe;
        if (STRIPE_PUBLIC_KEY && STRIPE_PUBLIC_KEY !== '') {
            stripe = Stripe(STRIPE_PUBLIC_KEY);
        }

        let selectedPaymentMethod = null;

        document.addEventListener('DOMContentLoaded', function() {
            if (SERVER_PAYMENT_ERROR) {
                handlePaymentError(SERVER_PAYMENT_ERROR);
            }

            // Simple and reliable payment option selection
            const paymentOptions = document.querySelectorAll('.payment-option');
            const proceedBtn = document.getElementById('proceedBtn');

            paymentOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all options
                    paymentOptions.forEach(opt => opt.classList.remove('selected'));

                    // Add selected class to clicked option
                    this.classList.add('selected');

                    // Check the radio button
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                    selectedPaymentMethod = radio.value;

                    // Enable proceed button
                    proceedBtn.disabled = false;
                });
            });
        });

        function processPayment() {
            if (!selectedPaymentMethod) {
                alert('Please select a payment method');
                return;
            }

            clearPaymentError();

            // Disable button during processing
            const proceedBtn = document.getElementById('proceedBtn');
            proceedBtn.disabled = true;
            proceedBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

            if (selectedPaymentMethod === 'stripe') {
                processStripePayment();
            }
        }

        function processStripePayment() {
            // Create Stripe card element modal
            createStripePaymentModal();
        }

        function createStripePaymentModal() {
            let createdPaymentIntentId = '';

            // Create modal for Stripe card input
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; right: 0; bottom: 0; 
                background: rgba(0,0,0,0.8); z-index: 10000; display: flex; 
                align-items: center; justify-content: center;
            `;

            const modalContent = document.createElement('div');
            modalContent.style.cssText = `
                background: white; padding: 30px; border-radius: 15px; 
                max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto;
            `;

            modalContent.innerHTML = `
                <div style="text-align: center; margin-bottom: 20px;">
                    <h3 style="color: #591044; margin: 0 0 10px 0;">Complete Payment</h3>
                    <p id="stripeAmountCopy" style="color: #6c757d; margin: 0;">Enter your card details to complete the payment in your local currency</p>
                </div>
                <div id="stripe-card-element" style="padding: 15px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px;"></div>
                <div id="stripe-card-errors" style="color: #dc3545; margin-bottom: 15px; font-size: 14px;"></div>
                <div style="display: flex; gap: 10px;">
                    <button id="stripe-cancel-btn" style="flex: 1; padding: 12px; border: 1px solid #ddd; background: white; border-radius: 8px; cursor: pointer;">Cancel</button>
                    <button id="stripe-pay-btn" style="flex: 2; padding: 12px; background: #591044; color: white; border: none; border-radius: 8px; cursor: pointer;">Pay ${BASE_DISPLAY_AMOUNT}</button>
                </div>
            `;

            modal.appendChild(modalContent);
            document.body.appendChild(modal);

            // Create Stripe elements
            const elements = stripe.elements();
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#424770',
                        '::placeholder': {
                            color: '#aab7c4'
                        },
                    },
                },
            });

            cardElement.mount('#stripe-card-element');

            // Handle card errors
            cardElement.on('change', ({
                error
            }) => {
                const displayError = document.getElementById('stripe-card-errors');
                if (error) {
                    displayError.textContent = error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Handle cancel
            document.getElementById('stripe-cancel-btn').addEventListener('click', () => {
                document.body.removeChild(modal);
                resetPaymentButton();
            });

            // Handle payment
            document.getElementById('stripe-pay-btn').addEventListener('click', () => {
                const payBtn = document.getElementById('stripe-pay-btn');
                const cancelBtn = document.getElementById('stripe-cancel-btn');
                payBtn.disabled = true;
                cancelBtn.disabled = true;
                payBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing Payment...';

                // Create PaymentIntent on server
                fetch('{{ route('consultation.payment.create-intent') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            booking: BOOKING_TOKEN,
                            payment_method: 'stripe',
                            amount: 75.00,
                            preferred_currency: LOCAL_CURRENCY,
                        })
                    })
                    .then(async response => {
                        const data = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            const err = new Error(data.message || data.error ||
                                'Unable to initialize payment. Please try again.');
                            err.details = data.details || '';
                            throw err;
                        }

                        return data;
                    })
                    .then(data => {
                        createdPaymentIntentId = data.payment_intent_id || '';
                        const displayAmount = data.display_amount || BASE_DISPLAY_AMOUNT;
                        const amountCopy = document.getElementById('stripeAmountCopy');
                        if (amountCopy) {
                            amountCopy.textContent = `Enter your card details to complete the ${displayAmount} payment`;
                        }

                        payBtn.innerHTML = `Pay ${displayAmount}`;
                        updateDisplayedAmount(displayAmount);

                        if (data.client_secret) {
                            return stripe.confirmCardPayment(data.client_secret, {
                                payment_method: {
                                    card: cardElement,
                                    billing_details: {
                                        name: '{{ $application->name }}',
                                        email: '{{ $application->email }}',
                                    }
                                }
                            });
                        } else {
                            throw new Error(data.message || data.error ||
                                'Failed to create payment intent');
                        }
                    })
                    .then(result => {
                        if (result.error) {
                            document.body.removeChild(modal);
                            const normalizedError = normalizeStripeError(result.error);

                            const failedTransactionId = (result.error.payment_intent && result.error.payment_intent.id) ?
                                result.error.payment_intent.id : createdPaymentIntentId;

                            submitPaymentCallback('stripe', failedTransactionId || '', 'failed', {
                                stripe_error: normalizedError,
                                payment_intent: result.error.payment_intent || null,
                            });

                            const friendlyMessage = getFriendlyStripeErrorMessage(normalizedError,
                                result.error.message);
                            handlePaymentError(friendlyMessage, normalizedError.decline_code || normalizedError.code ||
                                'card_declined');
                        } else {
                            // Payment succeeded - show success state briefly
                            const payBtn = document.getElementById('stripe-pay-btn');
                            payBtn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Payment Successful!';
                            payBtn.style.background = '#28a745';

                            console.log('Stripe payment successful, redirecting...');

                            // Close modal after brief success display
                            setTimeout(() => {
                                document.body.removeChild(modal);
                                submitPaymentCallback('stripe', result.paymentIntent.id, 'successful',
                                    result
                                    .paymentIntent);
                            }, 1500);
                        }
                    })
                    .catch(error => {
                        document.body.removeChild(modal);
                        console.error('Stripe payment error:', error);
                        handlePaymentError(
                            error.message || 'Payment processing failed',
                            error.details || ''
                        );
                    });
            });
        }

        function submitPaymentCallback(gateway, transactionId, status, data) {
            // Prevent double submission
            if (window._paymentCallbackSubmitted) return;
            window._paymentCallbackSubmitted = true;

            try {
                // Show loading state while redirecting
                const proceedBtn = document.getElementById('proceedBtn');
                if (proceedBtn) {
                    if (status === 'successful' || status === 'success') {
                        proceedBtn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Payment Successful - Redirecting...';
                        proceedBtn.style.background = '#28a745';
                    } else {
                        proceedBtn.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Logging payment failure...';
                        proceedBtn.style.background = '#a93a2d';
                    }
                }

                console.log(`${gateway} payment callback:`, {
                    transactionId,
                    status,
                    data
                });

                // Create a form to submit the payment callback data
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('consultation.payment.callback') }}';
                form.style.display = 'none';

                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfInput);

                // Add payment data
                const fields = {
                    'gateway': gateway,
                    'transaction_id': transactionId || '',
                    'status': status || 'pending_verification',
                    'payment_data': JSON.stringify(data || {}),
                    'booking': BOOKING_TOKEN || ''
                };

                for (const [name, value] of Object.entries(fields)) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = value;
                    form.appendChild(input);
                }

                document.body.appendChild(form);

                // Submit after brief UX delay
                setTimeout(() => {
                    try {
                        form.submit();
                    } catch (submitError) {
                        console.error('Form submit failed:', submitError);
                        // Fallback: use fetch to POST the callback
                        fallbackPaymentCallback(gateway, transactionId, status, data);
                    }
                }, 800);

                // Ultimate safety net: if page hasn't navigated in 10 seconds, force redirect
                setTimeout(() => {
                    console.warn('Payment callback timeout - forcing redirect via fetch fallback');
                    fallbackPaymentCallback(gateway, transactionId, status, data);
                }, 10000);

            } catch (err) {
                console.error('submitPaymentCallback crashed:', err);
                fallbackPaymentCallback(gateway, transactionId, status, data);
            }
        }

        // Fallback: use fetch + manual redirect if form submission fails
        function fallbackPaymentCallback(gateway, transactionId, status, data) {
            if (window._paymentFallbackUsed) return;
            window._paymentFallbackUsed = true;

            console.log('Using fetch fallback for payment callback');

            fetch('{{ route('consultation.payment.callback') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'text/html'
                    },
                    body: JSON.stringify({
                        gateway: gateway,
                        transaction_id: transactionId || '',
                        status: status || 'pending_verification',
                        payment_data: JSON.stringify(data || {}),
                        booking: BOOKING_TOKEN || ''
                    }),
                    redirect: 'follow'
                })
                .then(response => {
                    // Server returns a redirect — follow it
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        // If no redirect response, route user based on callback status.
                        if (status === 'successful' || status === 'success' || status === 'pending' || status ===
                            'pending_verification') {
                            window.location.href = '{{ route('consultation.payment-success') }}?booking=' +
                                encodeURIComponent(BOOKING_TOKEN || '');
                        } else {
                            window.location.href = '{{ route('consultation.payment-selection') }}?booking=' +
                                encodeURIComponent(BOOKING_TOKEN || '');
                        }
                    }
                })
                .catch(fetchErr => {
                    console.error('Fetch fallback also failed:', fetchErr);
                    alert(
                        'Payment was processed but we had trouble updating your record. Please contact support at contact@p23africa.com with your transaction reference.'
                    );
                    if (status === 'successful' || status === 'success' || status === 'pending' || status ===
                        'pending_verification') {
                        window.location.href = '{{ route('consultation.payment-success') }}?booking=' +
                            encodeURIComponent(BOOKING_TOKEN || '');
                    } else {
                        window.location.href = '{{ route('consultation.payment-selection') }}?booking=' +
                            encodeURIComponent(BOOKING_TOKEN || '');
                    }
                });
        }

        function normalizeStripeError(error) {
            if (!error) {
                return {};
            }

            return {
                type: error.type || null,
                code: error.code || null,
                decline_code: error.decline_code || null,
                message: error.message || null,
                charge: error.charge || null,
                payment_intent_id: (error.payment_intent && error.payment_intent.id) ? error.payment_intent.id : null,
                payment_method_id: (error.payment_method && error.payment_method.id) ? error.payment_method.id : null,
            };
        }

        function handlePaymentError(message, details = '') {
            const banner = document.getElementById('paymentErrorBanner');
            if (banner) {
                const safeMessage = message || 'Payment failed. Please try again.';
                banner.textContent = details ? `${safeMessage} (${details})` : safeMessage;
                banner.style.display = 'block';
                banner.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            alert('Payment Error: ' + message);
            resetPaymentButton();
        }

        function getFriendlyStripeErrorMessage(error, fallbackMessage = '') {
            const errorCode = error && error.code ? error.code : '';
            const declineCode = error && error.decline_code ? error.decline_code : '';

            if (declineCode === 'insufficient_funds') {
                return 'Your bank declined this payment due to insufficient funds. Please try another card or contact your bank.';
            }

            if (declineCode === 'do_not_honor') {
                return 'Your bank did not approve this payment. Please contact your bank or try a different card.';
            }

            if (declineCode === 'generic_decline' || errorCode === 'card_declined') {
                return 'Your card was declined by your bank. Please try another card, ensure online/international payments are enabled, or contact your bank.';
            }

            if (errorCode === 'authentication_required') {
                return 'Your bank requires additional authentication for this payment. Please try again and complete any verification prompts.';
            }

            if (errorCode === 'expired_card') {
                return 'This card has expired. Please use another card.';
            }

            if (errorCode === 'incorrect_cvc') {
                return 'The card security code is incorrect. Please check and try again.';
            }

            return fallbackMessage || 'Payment failed. Please try again or use another card.';
        }

        function clearPaymentError() {
            const banner = document.getElementById('paymentErrorBanner');
            if (banner) {
                banner.style.display = 'none';
                banner.textContent = '';
            }
        }

        function resetPaymentButton() {
            const proceedBtn = document.getElementById('proceedBtn');
            proceedBtn.disabled = false;
            const proceedLabel = document.getElementById('proceedAmountLabel');
            if (proceedLabel && !proceedLabel.textContent.trim()) {
                proceedLabel.textContent = BASE_DISPLAY_AMOUNT;
            }

            proceedBtn.innerHTML =
                '<i class="fa-solid fa-lock"></i> Secure Payment - <span id="proceedAmountLabel">' +
                (proceedLabel ? proceedLabel.textContent : BASE_DISPLAY_AMOUNT) +
                '</span> <i class="fa-solid fa-arrow-right"></i>';
        }

        function updateDisplayedAmount(displayAmount) {
            const safeAmount = displayAmount || BASE_DISPLAY_AMOUNT;
            const summaryAmount = document.getElementById('paymentAmountLabel');
            const proceedAmount = document.getElementById('proceedAmountLabel');

            if (summaryAmount) {
                summaryAmount.textContent = safeAmount;
            }

            if (proceedAmount) {
                proceedAmount.textContent = safeAmount;
            }
        }

        function detectUserCurrency() {
            const localeCandidates = Array.isArray(navigator.languages) && navigator.languages.length ?
                navigator.languages : [navigator.language || 'en-GB'];

            let region = null;
            for (const locale of localeCandidates) {
                const matchedRegion = extractRegionFromLocale(locale);
                if (matchedRegion) {
                    region = matchedRegion;
                    break;
                }
            }

            if (!region) {
                const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || '';
                region = mapTimezoneToRegion(timezone);
            }

            if (!region) {
                region = 'GB';
            }

            const map = {
                GB: 'GBP',
                US: 'USD',
                CA: 'CAD',
                AU: 'AUD',
                NZ: 'NZD',
                IE: 'EUR',
                FR: 'EUR',
                DE: 'EUR',
                ES: 'EUR',
                IT: 'EUR',
                PT: 'EUR',
                NL: 'EUR',
                BE: 'EUR',
                NG: 'NGN',
                GH: 'GHS',
                KE: 'KES',
                ZA: 'ZAR',
            };

            return map[region] || 'GBP';
        }

        function extractRegionFromLocale(locale) {
            if (!locale || typeof locale !== 'string') {
                return null;
            }

            const normalized = locale.replace('_', '-').toUpperCase();
            const parts = normalized.split('-');

            // Handles patterns like en-NG and zh-Hans-CN.
            for (let i = parts.length - 1; i >= 0; i--) {
                if (/^[A-Z]{2}$/.test(parts[i])) {
                    return parts[i];
                }
            }

            return null;
        }

        function mapTimezoneToRegion(timezone) {
            const timezoneMap = {
                'Africa/Lagos': 'NG',
                'Africa/Accra': 'GH',
                'Africa/Nairobi': 'KE',
                'Africa/Johannesburg': 'ZA',
                'Europe/London': 'GB',
                'America/New_York': 'US',
                'America/Chicago': 'US',
                'America/Los_Angeles': 'US',
                'Europe/Paris': 'FR',
                'Europe/Berlin': 'DE',
            };

            return timezoneMap[timezone] || null;
        }
    </script>
@endsection
