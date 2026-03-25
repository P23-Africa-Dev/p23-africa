@extends('layouts.consultations')
@section('title', 'Select Payment Method | P23 Africa')

@section('meta')
    <meta name="description"
        content="Choose your preferred payment method for your P23 Africa consultation booking - Flutterwave or Stripe payment options available.">
    <meta name="keywords"
        content="payment, consultation booking, P23 Africa, Flutterwave, Stripe, business consultation payment">
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
        grid-template-columns: 1fr 1fr;
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
</style>

@section('content')
    <section class="payment-selection-container">
        <div class="payment-wrapper">
            <a href="{{ route('consultation.consultation-summary') }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Summary
            </a>

            <div class="payment-header">
                <h1>Select Payment Method</h1>
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
                    <span><strong>$100.00 USD</strong></span>
                </div>
            </div>

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

                        <label class="payment-option" for="flutterwave" data-gateway="flutterwave">
                            <input type="radio" id="flutterwave" name="payment_method" value="flutterwave" required>
                            <img src="{{ asset('img/stripe-logo.png') }}" alt="Flutterwave" class="payment-logo"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display:none; font-size: 2rem; color: #f5a623;">💳</div>
                            <h4>Flutterwave</h4>
                            <p>Pay with cards, bank transfers, mobile money and more payment methods across <b>Africa</b>
                            </p>
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
                    Secure Payment - $100.00
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </section>

    <!-- Flutterwave Inline SDK -->
    <script src="https://checkout.flutterwave.com/v3.js"></script>

    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        // Payment gateway configurations
        const FLUTTERWAVE_PUBLIC_KEY = '{{ config('services.flutterwave.public_key') }}';
        const STRIPE_PUBLIC_KEY = '{{ config('services.stripe.public_key') }}';

        // Initialize Stripe
        let stripe;
        if (STRIPE_PUBLIC_KEY && STRIPE_PUBLIC_KEY !== '') {
            stripe = Stripe(STRIPE_PUBLIC_KEY);
        }

        let selectedPaymentMethod = null;

        document.addEventListener('DOMContentLoaded', function() {
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

            // Disable button during processing
            const proceedBtn = document.getElementById('proceedBtn');
            proceedBtn.disabled = true;
            proceedBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

            if (selectedPaymentMethod === 'flutterwave') {
                processFlutterwavePayment();
            } else if (selectedPaymentMethod === 'stripe') {
                processStripePayment();
            }
        }

        function processFlutterwavePayment() {
            // First create a pending payment record on the server to get a reliable tx_ref
            fetch('{{ route('consultation.payment.create-flutterwave') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    const txRef = data.tx_ref;

                    FlutterwaveCheckout({
                        public_key: FLUTTERWAVE_PUBLIC_KEY,
                        tx_ref: txRef,
                        amount: 1.00,
                        currency: 'NGN',
                        country: 'US',
                        payment_options: 'card,mobilemoney,ussd,banktransfer',
                        customer: {
                            email: '{{ $application->email }}',
                            phonenumber: '{{ $application->phone ?? 'N/A' }}',
                            name: '{{ $application->name }}',
                        },
                        callback: function(response) {
                            try {
                                console.log('Flutterwave callback data:', response);
                                console.log('Flutterwave payment status:', response.status);

                                // Handle different payment statuses
                                if (response.status === 'successful' || response.status === 'success' ||
                                    response.status === 'completed') {
                                    console.log('Payment successful, redirecting...');
                                    submitPaymentCallback('flutterwave', response.transaction_id,
                                        'successful',
                                        response);
                                } else if (response.status === 'pending' || response.status ===
                                    'processing') {
                                    console.log('Payment pending verification, redirecting...');
                                    submitPaymentCallback('flutterwave', response.transaction_id,
                                        'pending_verification', response);
                                } else if (response.status === 'cancelled' || response.status ===
                                    'failed') {
                                    console.log('Payment failed or cancelled:', response.status);
                                    handlePaymentError('Payment was ' + response.status +
                                        '. Please try again.');
                                } else {
                                    console.log('Unknown payment status:', response.status,
                                        'treating as pending');
                                    submitPaymentCallback('flutterwave', response.transaction_id,
                                        'pending_verification', response);
                                }
                            } catch (err) {
                                console.error('Error in Flutterwave callback handler:', err);
                                // Safety net: if anything crashes, still try to record the payment
                                try {
                                    submitPaymentCallback('flutterwave',
                                        response.transaction_id || txRef,
                                        response.status || 'pending_verification',
                                        response);
                                } catch (e2) {
                                    console.error('Safety net also failed, forcing redirect:', e2);
                                    window.location.href = '{{ route('consultation.payment.callback') }}' +
                                        '?gateway=flutterwave&transaction_id=' + encodeURIComponent(txRef) +
                                        '&status=pending_verification&_token=' +
                                        encodeURIComponent(document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content'));
                                }
                            }
                        },
                        onclose: function() {
                            resetPaymentButton();
                        },
                        meta: {
                            application_id: '{{ $application->id }}',
                            consultation_type: 'business_strategy',
                        },
                        customizations: {
                            title: 'P23 Africa Consultation',
                            description: '90-minute Business Strategy Session',
                            logo: '{{ asset('img/flutterwave-logo.png') }}',
                        },
                    });
                })
                .catch(error => {
                    console.error('Failed to initialize Flutterwave payment:', error);
                    handlePaymentError(error.message || 'Failed to initialize payment. Please try again.');
                });
        }

        function processStripePayment() {
            // Create Stripe card element modal
            createStripePaymentModal();
        }

        function createStripePaymentModal() {
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
                    <p style="color: #6c757d; margin: 0;">Enter your card details to complete the $100.00 payment</p>
                </div>
                <div id="stripe-card-element" style="padding: 15px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px;"></div>
                <div id="stripe-card-errors" style="color: #dc3545; margin-bottom: 15px; font-size: 14px;"></div>
                <div style="display: flex; gap: 10px;">
                    <button id="stripe-cancel-btn" style="flex: 1; padding: 12px; border: 1px solid #ddd; background: white; border-radius: 8px; cursor: pointer;">Cancel</button>
                    <button id="stripe-pay-btn" style="flex: 2; padding: 12px; background: #591044; color: white; border: none; border-radius: 8px; cursor: pointer;">Pay $100.00</button>
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
                            payment_method: 'stripe',
                            amount: 100.00,
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
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
                            throw new Error(data.error || 'Failed to create payment intent');
                        }
                    })
                    .then(result => {
                        if (result.error) {
                            document.body.removeChild(modal);
                            handlePaymentError(result.error.message);
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
                        handlePaymentError(error.message || 'Payment processing failed');
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
                    proceedBtn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Payment Successful - Redirecting...';
                    proceedBtn.style.background = '#28a745';
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
                    'payment_data': JSON.stringify(data || {})
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
                        payment_data: JSON.stringify(data || {})
                    }),
                    redirect: 'follow'
                })
                .then(response => {
                    // Server returns a redirect — follow it
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        // If no redirect, go to payment success directly
                        window.location.href = '{{ route('consultation.payment-success') }}';
                    }
                })
                .catch(fetchErr => {
                    console.error('Fetch fallback also failed:', fetchErr);
                    alert(
                        'Payment was processed but we had trouble updating your record. Please contact support at contact@p23africa.com with your transaction reference.'
                    );
                    window.location.href = '{{ route('consultation.payment-success') }}';
                });
        }

        function handlePaymentError(message) {
            alert('Payment Error: ' + message);
            resetPaymentButton();
        }

        function resetPaymentButton() {
            const proceedBtn = document.getElementById('proceedBtn');
            proceedBtn.disabled = false;
            proceedBtn.innerHTML =
                '<i class="fa-solid fa-lock"></i> Secure Payment - $100.00 <i class="fa-solid fa-arrow-right"></i>';
        }
    </script>
@endsection
