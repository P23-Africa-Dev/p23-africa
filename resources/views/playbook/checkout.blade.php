@extends('layouts.master')
@section('title', 'Ultimate Funding Directory Checkout | P23 Africa')

@section('meta')
    <meta name="description"
        content="Complete your purchase for the Ultimate Funding & Investor Directory. Access active investors, global grant programs, and funding opportunities designed specifically for African entrepreneurs.">
    <meta name="keywords"
        content="funding directory checkout, investor directory, African investors, startup funding, venture capital Africa, grant programs, angel investors, African entrepreneurs">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Ultimate Funding & Investor Directory Checkout | P23 Africa">
    <meta property="og:title" content="Ultimate Funding Directory Checkout | P23 Africa">
    <meta property="og:description"
        content="Direct access to active investors, global grant programs, and funding opportunities. Complete your purchase to access the ultimate directory curated for African entrepreneurs.">
    <meta property="og:image" content="{{ asset('img/desktop-fund.png') }}">
    <meta property="og:type" content="website">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Stripe Payment Script -->
    <script src="https://js.stripe.com/v3/"></script>
    <!-- Flutterwave Payment Script (Fallback) -->
    <script src="https://checkout.flutterwave.com/v3.js"></script>
@endsection

<style>
    body {
        font-family: "GT Walsheim Trial Md";
    }

    .form-sales-container {
        max-width: 1420px;
        margin: 0 auto;
    }

    .form-sales {
        padding: 2rem 1.7rem;
        background-image: url("/img/first-bg.png");
        background-repeat: repeat;
        background-size: contain;
        margin-top: 5rem;
    }

    .form-container {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 2rem;
    }

    .form-image.desktop {
        display: none;
    }

    .form-image img {
        max-width: 100%;
    }

    .form-image.mobile img {
        margin-top: 15px;
        margin-left: -6px;
    }

    .form-content h1 {
        font-size: 2.15rem;
        color: #0d4036;
        line-height: 1.1;
        font-weight: 900;
        font-family: "GT Walsheim ProCB";
    }

    .form-content h1 span {
        color: #52ccb4;
    }

    .form-info {
        margin-top: 30px;
    }

    .form-info .input-fields {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-info input,
    .form-info select {
        width: 100%;
        padding: 1rem 0.5rem 1.4rem 1.6rem;
        border-radius: 5px;
        border: none;
        outline: none;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        border-bottom: 1.6px solid #0d4036;
        background: #fff;
    }

    .form-info input::placeholder,
    .form-info select option {
        color: #afaeae;
        font-family: "GT Walsheim Con";
        letter-spacing: 0.4px;
        font-size: 11px;
    }

    .form-info input:focus,
    .form-info select:focus {
        border-color: #52ccb4;
        box-shadow: 0 4px 10px rgba(82, 204, 180, 0.2);
    }

    .form-info input.error,
    .form-info select.error {
        border-color: #e74c3c;
    }

    .error-message {
        color: #e74c3c;
        font-size: 12px;
        margin-top: -10px;
        margin-bottom: 10px;
        display: none;
    }

    .error-message.show {
        display: block;
    }

    .form-info .payment-button {
        display: inline-block;
        padding: 0.9rem 4rem;
        background: #0d4036;
        font-family: "GT Walsheim Con";
        outline: none;
        color: #fff;
        font-weight: 400;
        border-radius: 6px;
        margin-top: 50px;
        border: none;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-info .payment-button:hover {
        background: #0a3229;
    }

    .form-info .payment-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .form-info .summary-card {
        border-radius: 15px;
        background-color: #fbfbfb;
        padding: 0px 30px;
        width: 100%;
    }

    .summary-card .pattern {
        width: 96%;
        margin: 0 auto;
        background-color: #f4d9fb;
        height: 5px;
        border-radius: 20px 20px 0px 0px;
    }

    .summary-card .pattern.bottom {
        margin-top: 20px;
        border-radius: 0px 0px 20px 20px;
    }

    .summary-card .pattern.top {
        margin-bottom: 20px;
    }

    .form-info .summary-card .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #abaaaa;
        padding-bottom: 24px;
        padding-top: 20px;
    }

    .form-info .summary-card .summary-price {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 24px;
        padding-top: 20px;
    }

    .item-description {
        color: #6b7b78;
        font-family: "GT Walsheim Con";
        font-size: 18px;
        font-weight: 500;
        letter-spacing: -0.4px;
    }

    .item-price,
    .total-label {
        color: #0d4036;
        font-weight: 900;
        font-family: "GT Walsheim ProCB";
    }

    .total-label {
        font-family: "GT Walsheim ProCB";
        font-size: 18px;
        font-weight: 900;
    }

    /* Spinner styles */
    .spinner {
        display: none;
    }

    .spinner div {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* Success popup styles */
    .success-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #ffffff;
        border: 2px solid #52ccb4;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        z-index: 2000;
        max-width: 500px;
        width: 90%;
        text-align: center;
        opacity: 0;
        visibility: hidden;
        transform: translate(-50%, -50%) scale(0.8);
        transition: all 0.3s ease;
    }

    .success-popup.show {
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, -50%) scale(1);
    }

    .success-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 1999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .success-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    /* Payment success styles */
    .payment-success-icon {
        height: 60px;
        width: 60px;
        border-radius: 50%;
        background: #23A26D1F;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .payment-success-icon span {
        height: 28px;
        width: 28px;
        background: #23A26D;
        border-radius: 50%;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .payment-success-icon span svg {
        font-size: 10px;
        font-weight: bold;
    }

    .payment-title {
        margin: 0 0 24px;
        font-size: 22px;
        font-weight: 600;
        font-family: "Poppins", sans-serif;
        color: #0d4036;
    }

    .payment-info-box {
        padding: 14px 18px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: transparent;
        font-size: 14px;
        text-align: center;
        margin-top: 20px;
        color: #121212;
        font-family: "Poppins", sans-serif;
    }

    .payment-footer-btn {
        margin-top: 36px;
        background: #063b2e;
        color: #fff;
        border: none;
        padding: 10px 5px;
        width: 100%;
        font-size: 16px;
        font-weight: 500;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s ease;
        font-family: "Poppins", sans-serif;
        max-width: 600px;
    }

    .payment-footer-btn:hover {
        background: #052e24;
    }

    /* Stripe Card Element Styles */
    .stripe-card-container {
        margin-top: 20px;
        margin-bottom: 20px;
        display: none;
    }

    .stripe-card-container.show {
        display: block;
    }

    .stripe-card-label {
        font-size: 14px;
        color: #0d4036;
        margin-bottom: 8px;
        font-family: "GT Walsheim Con";
        font-weight: 500;
    }

    #card-element {
        background: #fff;
        padding: 1rem;
        border-radius: 5px;
        border-bottom: 1.6px solid #0d4036;
    }

    #card-errors {
        color: #e74c3c;
        font-size: 12px;
        margin-top: 8px;
        font-family: "GT Walsheim Con";
    }

    /* Stripe Payment Modal Styles */
    .stripe-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 3000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .stripe-modal-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .stripe-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.8);
        background: #ffffff;
        border-radius: 16px;
        padding: 0;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        z-index: 3001;
        max-width: 450px;
        width: 95%;
        max-height: 90vh;
        overflow-y: auto;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .stripe-modal.show {
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, -50%) scale(1);
    }

    /* Custom scrollbar for Stripe modal */
    .stripe-modal::-webkit-scrollbar {
        width: 6px;
    }

    .stripe-modal::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 0 16px 16px 0;
    }

    .stripe-modal::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .stripe-modal::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    .stripe-modal-header {
        background: linear-gradient(135deg, #0d4036 0%, #1a5c4e 100%);
        padding: 20px 24px;
        color: #fff;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .stripe-modal-header h3 {
        margin: 0 0 5px;
        font-size: 18px;
        font-weight: 600;
        font-family: "GT Walsheim ProCB";
    }

    .stripe-modal-header p {
        margin: 0;
        font-size: 13px;
        opacity: 0.9;
        font-family: "GT Walsheim Con";
    }

    .stripe-modal-header .amount {
        font-size: 28px;
        font-weight: 700;
        margin-top: 10px;
        font-family: "GT Walsheim ProCB";
    }

    .stripe-modal-body {
        padding: 24px;
    }

    .stripe-modal-body .secure-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f0f9f7;
        padding: 10px 14px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 13px;
        color: #0d4036;
        font-family: "GT Walsheim Con";
    }

    .stripe-modal-body .secure-badge svg {
        color: #52ccb4;
    }

    #stripe-payment-element {
        min-height: 150px;
    }

    #stripe-card-errors {
        color: #e74c3c;
        font-size: 13px;
        margin-top: 12px;
        font-family: "GT Walsheim Con";
    }

    .stripe-modal-footer {
        padding: 0 24px 24px;
    }

    .stripe-pay-btn {
        width: 100%;
        padding: 14px 24px;
        background: #0d4036;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.3s ease;
        font-family: "GT Walsheim Con";
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .stripe-pay-btn:hover:not(:disabled) {
        background: #0a3229;
    }

    .stripe-pay-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .stripe-pay-btn .btn-spinner {
        width: 18px;
        height: 18px;
        border: 2px solid transparent;
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        display: none;
    }

    .stripe-pay-btn.loading .btn-spinner {
        display: inline-block;
    }

    .stripe-pay-btn.loading .btn-text {
        display: none;
    }

    .stripe-modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: #fff;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }

    .stripe-modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .stripe-powered {
        text-align: center;
        margin-top: 16px;
        font-size: 12px;
        color: #888;
        font-family: "GT Walsheim Con";
    }

    .stripe-powered svg {
        vertical-align: middle;
        margin-left: 4px;
    }

    /* Fallback Notice */
    .fallback-notice {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
        display: none;
        z-index: 2000;
    }

    .fallback-notice.show {
        display: block;
        z-index: 2000;
        position: relative;
    }

    .fallback-notice p {
        color: #856404;
        font-size: 14px;
        margin: 0 0 10px;
        font-family: "GT Walsheim Con";
    }

    .fallback-button {
        display: inline-block;
        padding: 0.7rem 2rem;
        background: #f5a623;
        font-family: "GT Walsheim Con";
        outline: none;
        color: #fff;
        font-weight: 400;
        border-radius: 6px;
        border: none;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .fallback-button:hover {
        background: #e09515;
    }

    /* Payment Method Indicator */
    .payment-method-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e8f5e9;
        color: #2e7d32;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-family: "GT Walsheim Con";
        margin-bottom: 15px;
    }

    .payment-method-badge.fallback {
        background: #fff3e0;
        color: #e65100;
    }

    /* Tablet styles */
    @media (min-width: 768px) {
        .form-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 0rem;
        }

        .form-content {
            display: flex;
            flex-direction: column;
            padding-top: 0rem;
            margin-left: 7%;
        }

        .form-content h1 {
            font-size: 1.5rem;
        }

        .form-image.desktop {
            display: block;
            margin-left: 0%;
            overflow: hidden;
            max-width: initial;
            max-width: 50rem;
            position: relative;
        }

        .form-image.desktop img {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            object-fit: contain;
            overflow: hidden;
        }

        .form-image.mobile {
            display: none;
        }

        .form-info {
            margin-top: 20px;
        }

        .form-info .summary-card {
            margin: 0px 20px;
        }
    }

    @media (min-width: 900px) {
        .form-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 0rem;
        }

        .form-content h1 {
            padding-right: 8px;
        }

        .form-image.desktop {
            max-width: 100%;
        }

        .form-image.desktop img {
            height: 100%;
            width: 100%;
        }
    }

    @media (min-width: 1024px) {
        .form-content {
            padding-left: 80px;
        }

        .form-image.desktop {
            max-width: 100%;
            width: 500px;
        }
    }

    @media (min-width: 1200px) {
        .form-sales {
            padding-bottom: 10rem;
        }

        .form-info {
            margin-top: 10px;
        }

        .form-content {
            margin-right: -5%;
            margin-top: 50px;
            padding-top: 1.6rem;
        }

        .form-info .summary-card {
            margin-left: -2px;
            max-width: 410px;
        }

        .form-content h1 {
            padding-right: 0px;
            font-size: 2rem;
        }

        .form-info .input-fields {
            max-width: 410px;
        }

        .form-image.desktop {
            max-width: initial;
            margin-left: 0%;
            width: 47rem;
            overflow: visible;
        }

        .form-image.desktop img {
            transform: translateX(-120px);
            margin-top: 3.2rem;
        }
    }
</style>

@section('content')
    <section class="form-sales">
        <div class="form-sales-container">
            <div class="form-container">
                <div class="form-content">
                    <h1>
                        {{ __('playbook.uf_for') }}
                        <span> {{ __('playbook.uf_span') }}</span>
                    </h1>

                    <div class="form-info">
                        <form id="ultimateFundingForm">
                            @csrf
                            <div class="input-fields">
                                <div>
                                    <input type="text" name="name" placeholder="{{ __('playbook.ph_your_name') }}" required />
                                    <div class="error-message" id="nameError"></div>
                                </div>

                                <div>
                                    <input type="email" name="email" placeholder="{{ __('playbook.ph_your_email') }}" required />
                                    <div class="error-message" id="emailError"></div>
                                </div>

                                <div>
                                    <input type="text" name="company" placeholder="{{ __('playbook.ph_startup') }}" required />
                                    <div class="error-message" id="companyError"></div>
                                </div>

                                <div>
                                    <select name="country" required>
                                        <option value="">{{ __('playbook.select_country') }}</option>
                                        <option value="Nigeria">Nigeria</option>
                                        <option value="Kenya">Kenya</option>
                                        <option value="South Africa">South Africa</option>
                                        <option value="Ghana">Ghana</option>
                                        <option value="Uganda">Uganda</option>
                                        <option value="Tanzania">Tanzania</option>
                                        <option value="Rwanda">Rwanda</option>
                                        <option value="Ethiopia">Ethiopia</option>
                                        <option value="Egypt">Egypt</option>
                                        <option value="Morocco">Morocco</option>
                                        <option value="Algeria">Algeria</option>
                                        <option value="Tunisia">Tunisia</option>
                                        <option value="Botswana">Botswana</option>
                                        <option value="Zambia">Zambia</option>
                                        <option value="Zimbabwe">Zimbabwe</option>
                                        <option value="Senegal">Senegal</option>
                                        <option value="Ivory Coast">Ivory Coast</option>
                                        <option value="Cameroon">Cameroon</option>
                                        <option value="Other African Country">{{ __('playbook.other_african') }}</option>
                                        <option value="International">{{ __('playbook.international') }}</option>
                                    </select>
                                    <div class="error-message" id="countryError"></div>
                                </div>
                            </div>

                            <div class="summary-card">
                                <div class="pattern top"></div>
                                <div class="summary-item">
                                    <span class="item-description">{{ __('playbook.item_desc') }}</span>
                                    <span class="item-price">$50.00</span>
                                </div>

                                <div class="summary-price">
                                    <span class="total-label">{{ __('playbook.total') }}</span>
                                    <span class="item-price">$50.00</span>
                                </div>
                                <div class="pattern bottom"></div>
                            </div>

                            <!-- Flutterwave Fallback Notice (Only shows if Stripe fails) -->
                            <div class="fallback-notice" id="fallbackNotice">
                                <div class="payment-method-badge fallback">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                        <path
                                            d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z" />
                                    </svg>
                                    <span>{{ __('playbook.alt_pay') }}</span>
                                </div>
                                <p>{{ __('playbook.flutter_note') }}</p>
                                <button type="button" class="fallback-button" id="flutterwaveFallbackBtn">
                                    {{ __('playbook.pay_flutter') }}
                                </button>
                            </div>

                            <div>
                                <button type="submit" class="payment-button" id="submitBtn">
                                    <span class="btn-text">{{ __('playbook.proceed_50') }}</span>
                                    <span class="spinner">
                                        <div></div>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="form-image desktop">
                    <img src="{{ asset('./img/desktop-fund.png') }}" loading="lazy" alt="{{ __('playbook.alt_laptop') }}" />
                </div>
                <div class="form-image mobile">
                    <img src="{{ asset('./img/mobile-fund.png') }}" loading="lazy" alt="{{ __('playbook.alt_laptop') }}" />
                </div>
            </div>
        </div>
    </section>

    <!-- SUCCESS POPUP -->
    <div class="success-overlay" id="successOverlay"></div>
    <div class="success-popup" id="successPopup">
        <div class="payment-success-icon">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
            </span>
        </div>

        <h2 class="payment-title">{{ __('playbook.pay_success') }}</h2>

        <div class="payment-info-box" id="successMessage">
            {{ __('playbook.sent_box') }}
        </div>

        <button class="payment-footer-btn" id="closeSuccessPopup">{{ __('playbook.back_dir') }}</button>
    </div>

    <!-- STRIPE PAYMENT MODAL -->
    <div class="stripe-modal-overlay" id="stripeModalOverlay"></div>
    <div class="stripe-modal" id="stripeModal">
        <div class="stripe-modal-header">
            <button type="button" class="stripe-modal-close" id="stripeModalClose">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                    viewBox="0 0 16 16">
                    <path
                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                </svg>
            </button>
            <h3>{{ __('playbook.modal_title') }}</h3>
            <p>{{ __('playbook.modal_sub') }}</p>
            <div class="amount">$50.00</div>
        </div>
        <div class="stripe-modal-body">
            <div class="secure-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    viewBox="0 0 16 16">
                    <path
                        d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                </svg>
                <span>{{ __('playbook.secured') }}</span>
            </div>
            <div id="stripe-payment-element"></div>
            <div id="stripe-card-errors" role="alert"></div>
        </div>
        <div class="stripe-modal-footer">
            <button type="button" class="stripe-pay-btn" id="stripePayBtn">
                <span class="btn-text">{{ __('playbook.pay_50') }}</span>
                <span class="btn-spinner"></span>
            </button>
            <div class="stripe-powered">
                Powered by
                <svg width="33" height="14" viewBox="0 0 33 14" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M32.7 7.14C32.7 4.78 31.56 2.96 29.36 2.96C27.15 2.96 25.84 4.78 25.84 7.12C25.84 9.9 27.39 11.26 29.64 11.26C30.74 11.26 31.56 11.02 32.18 10.68V8.88C31.56 9.18 30.84 9.36 29.94 9.36C29.06 9.36 28.28 9.04 28.18 8H32.68C32.68 7.86 32.7 7.4 32.7 7.14ZM28.16 6.34C28.16 5.42 28.74 4.98 29.36 4.98C29.96 4.98 30.52 5.42 30.52 6.34H28.16Z"
                        fill="#635BFF" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M22.34 2.96C21.44 2.96 20.86 3.38 20.54 3.68L20.42 3.1H18.26V14L20.62 13.5L20.63 10.72C20.96 10.96 21.44 11.26 22.32 11.26C24.08 11.26 25.68 9.82 25.68 7.02C25.67 4.46 24.06 2.96 22.34 2.96ZM21.76 9.28C21.16 9.28 20.8 9.06 20.63 8.8L20.62 5.52C20.8 5.24 21.18 5.02 21.76 5.02C22.66 5.02 23.28 6.02 23.28 7.14C23.28 8.28 22.68 9.28 21.76 9.28Z"
                        fill="#635BFF" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.22 2.36L17.6 1.86V0L15.22 0.5V2.36Z"
                        fill="#635BFF" />
                    <path d="M17.6 3.1H15.22V11.1H17.6V3.1Z" fill="#635BFF" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12.72 3.76L12.58 3.1H10.46V11.1H12.82V5.7C13.38 4.96 14.3 5.1 14.6 5.2V3.1C14.28 2.98 13.28 2.8 12.72 3.76Z"
                        fill="#635BFF" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.94 1.04L5.62 1.52L5.61 9C5.61 10.28 6.56 11.26 7.84 11.26C8.54 11.26 9.04 11.12 9.32 10.96V9.06C9.06 9.16 7.93 9.5 7.93 8.22V5.16H9.32V3.1H7.93L7.94 1.04Z"
                        fill="#635BFF" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M2.4 5.52C2.4 5.16 2.7 5.02 3.18 5.02C3.88 5.02 4.76 5.24 5.46 5.64V3.36C4.7 3.06 3.94 2.96 3.18 2.96C1.28 2.96 0 4 0 5.62C0 8.12 3.44 7.74 3.44 8.82C3.44 9.24 3.08 9.38 2.58 9.38C1.82 9.38 0.84 9.06 0.06 8.6V10.9C0.92 11.26 1.78 11.26 2.58 11.26C4.52 11.26 5.88 10.32 5.88 8.68C5.86 5.96 2.4 6.42 2.4 5.52Z"
                        fill="#635BFF" />
                </svg>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // DOM elements
        const ultimateFundingForm = document.getElementById("ultimateFundingForm");
        const submitBtn = document.getElementById("submitBtn");
        const btnText = submitBtn.querySelector(".btn-text");
        const spinner = submitBtn.querySelector(".spinner");
        const successPopup = document.getElementById("successPopup");
        const successOverlay = document.getElementById("successOverlay");
        const successMessage = document.getElementById("successMessage");
        const closeSuccessPopup = document.getElementById("closeSuccessPopup");
        const fallbackNotice = document.getElementById("fallbackNotice");
        const flutterwaveFallbackBtn = document.getElementById("flutterwaveFallbackBtn");

        // Stripe Modal Elements
        const stripeModal = document.getElementById("stripeModal");
        const stripeModalOverlay = document.getElementById("stripeModalOverlay");
        const stripeModalClose = document.getElementById("stripeModalClose");
        const stripePayBtn = document.getElementById("stripePayBtn");
        const stripeCardErrors = document.getElementById("stripe-card-errors");

        // Initialize Stripe
        const stripePublicKey = "{{ config('services.stripe.public_key') }}";
        let stripe = null;
        let elements = null;
        let paymentElement = null;
        let currentDownloadId = null;
        let currentUserEmail = null;
        let currentUserName = null;
        let currentAmount = 50;
        let stripeAvailable = false;
        let clientSecret = null;

        // Try to initialize Stripe
        try {
            if (stripePublicKey && typeof Stripe !== 'undefined') {
                stripe = Stripe(stripePublicKey);
                stripeAvailable = true;
                console.log('Stripe initialized successfully with key:', stripePublicKey.substring(0, 20) +
                    '...');
            } else {
                console.error('Stripe not available. Key:', stripePublicKey ? 'present' : 'missing',
                    'Stripe object:', typeof Stripe);
            }
        } catch (error) {
            console.error('Stripe initialization failed:', error);
            stripeAvailable = false;
        }

        // Success popup controls
        closeSuccessPopup.addEventListener("click", () => {
            hideSuccessPopup();
            window.location.href = "{{ locale_route('playbook.landing2') }}";
        });

        successOverlay.addEventListener("click", () => {
            hideSuccessPopup();
            window.location.href = "{{ locale_route('playbook.landing2') }}";
        });

        // Stripe Modal controls
        stripeModalClose.addEventListener("click", () => {
            hideStripeModal();
            showFlutterwaveFallback();
        });

        stripeModalOverlay.addEventListener("click", () => {
            hideStripeModal();
            showFlutterwaveFallback();
        });

        // Stripe Pay Button
        stripePayBtn.addEventListener("click", handleStripePayment);

        // Flutterwave fallback button
        flutterwaveFallbackBtn.addEventListener("click", function() {
            if (currentDownloadId && currentUserEmail && currentUserName) {
                initializeFlutterwavePayment(currentDownloadId, currentUserEmail, currentUserName,
                    currentAmount);
            }
        });

        // Form submission
        ultimateFundingForm.addEventListener("submit", async function(e) {
            e.preventDefault();

            clearErrors();
            hideFallbackNotice();

            // Get form data
            const formData = new FormData(ultimateFundingForm);
            const name = formData.get('name').trim();
            const email = formData.get('email').trim();
            const company = formData.get('company').trim();
            const country = formData.get('country').trim();

            // Basic validation
            let hasErrors = false;

            if (!name) {
                showError('nameError', 'Full name is required.');
                hasErrors = true;
            }

            if (!email) {
                showError('emailError', 'Email address is required.');
                hasErrors = true;
            } else if (!isValidEmail(email)) {
                showError('emailError', @json(__('playbook.err_email_dot')));
                hasErrors = true;
            }

            if (!company) {
                showError('companyError', 'Company/Startup name is required.');
                hasErrors = true;
            }

            if (!country) {
                showError('countryError', @json(__('playbook.err_country')));
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            // Show loading state
            setLoading(true);

            try {
                // First, create the download record
                const response = await fetch('/ultimate-funding/download', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        company: company,
                        country: country
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Store data for potential fallback
                    currentDownloadId = data.download_id;
                    currentUserEmail = data.user_email;
                    currentUserName = data.user_name;
                    currentAmount = data.amount || 50;

                    console.log('Download record response:', {
                        downloadId: currentDownloadId,
                        email: currentUserEmail,
                        amount: currentAmount,
                        stripeAvailable: stripeAvailable,
                        action: data.action
                    });

                    // Handle different actions
                    if (data.action === 'already_purchased') {
                        showError('emailError', data.message ||
                            'You have already purchased this directory.');
                        setLoading(false);
                        return;
                    }

                    // Try Stripe first (for both new and existing unpaid records)
                    if (stripeAvailable) {
                        console.log('Attempting Stripe payment...');
                        await initializeStripePayment(data.download_id);
                    } else {
                        // Stripe not available, show Flutterwave fallback
                        console.log('Stripe not available, showing Flutterwave fallback');
                        showFlutterwaveFallback();
                        setLoading(false);
                    }
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorElement = document.getElementById(field + 'Error');
                            if (errorElement) {
                                showError(field + 'Error', data.errors[field][0]);
                            }
                        });
                    } else {
                        showError('emailError', data.message ||
                            @json(__('playbook.err_generic')));
                    }
                    setLoading(false);
                }
            } catch (error) {
                console.error('Error:', error);
                showError('emailError',
                    @json(__('playbook.err_network')));
                setLoading(false);
            }
        });

        // Initialize Stripe Payment Modal
        async function initializeStripePayment(downloadId) {
            try {
                const requestBody = {
                    download_id: downloadId,
                    amount: currentAmount
                };

                console.log('Sending Stripe payment intent request:', requestBody);

                // Create payment intent
                const response = await fetch('/ultimate-funding/create-payment-intent', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(requestBody)
                });

                const data = await response.json();

                console.log('Stripe payment intent response:', data);

                if (response.ok && data.success) {
                    clientSecret = data.client_secret;

                    console.log('Payment intent created, client_secret received');

                    // Create Payment Element
                    const appearance = {
                        theme: 'stripe',
                        variables: {
                            colorPrimary: '#0d4036',
                            colorBackground: '#ffffff',
                            colorText: '#0d4036',
                            colorDanger: '#e74c3c',
                            fontFamily: 'system-ui, sans-serif',
                            spacingUnit: '4px',
                            borderRadius: '8px'
                        }
                    };

                    try {
                        elements = stripe.elements({
                            appearance,
                            clientSecret: clientSecret
                        });

                        console.log('Stripe elements created');

                        paymentElement = elements.create('payment', {
                            layout: 'tabs'
                        });

                        console.log('Payment element created, mounting...');

                        const mountElement = document.getElementById('stripe-payment-element');
                        console.log('Mount element:', mountElement);

                        paymentElement.mount('#stripe-payment-element');

                        console.log('Payment element mounted successfully');

                        // Show the Stripe modal
                        setLoading(false);
                        showStripeModal();
                    } catch (elementsError) {
                        console.error('Error creating/mounting Stripe elements:', elementsError);
                        setLoading(false);
                        showFlutterwaveFallback();
                    }
                } else {
                    // Stripe intent creation failed, show Flutterwave fallback
                    console.error('Stripe intent creation failed:', data.message);
                    console.log('Showing Flutterwave fallback due to Stripe failure');
                    setLoading(false);
                    showFlutterwaveFallback();
                    // Show user-friendly message
                    alert(
                        @json(__('playbook.card_down'))
                        );
                }
            } catch (error) {
                console.error('Stripe payment initialization error:', error);
                console.log('Showing Flutterwave fallback due to error:', error.message);
                setLoading(false);
                showFlutterwaveFallback();
                // Show user-friendly message
                alert(
                    @json(__('playbook.card_down'))
                    );
            }
        }

        // Handle Stripe Payment
        async function handleStripePayment() {
            if (!clientSecret || !elements) {
                stripeCardErrors.textContent = @json(__('playbook.session_pay'));
                return;
            }

            setStripePayLoading(true);
            stripeCardErrors.textContent = '';

            try {
                const {
                    error,
                    paymentIntent
                } = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        return_url: window.location.href, // Fallback, but we handle redirect below
                    },
                    redirect: 'if_required'
                });

                if (error) {
                    // Payment failed
                    console.error('Stripe payment error:', error);
                    stripeCardErrors.textContent = error.message;
                    setStripePayLoading(false);

                    // If it's a card error, keep modal open to retry
                    // If it's a more serious error, close modal and show Flutterwave
                    if (error.type === 'card_error' || error.type === 'validation_error') {
                        // User can fix and retry
                    } else {
                        hideStripeModal();
                        showFlutterwaveFallback();
                    }
                } else if (paymentIntent && paymentIntent.status === 'succeeded') {
                    // Payment successful, verify on backend
                    await verifyStripePayment(paymentIntent.id);
                } else if (paymentIntent && paymentIntent.status === 'requires_action') {
                    // 3D Secure or additional authentication needed - handled by Stripe
                    stripeCardErrors.textContent =
                        @json(__('playbook.auth_required'));
                    setStripePayLoading(false);
                }
            } catch (error) {
                console.error('Payment processing error:', error);
                stripeCardErrors.textContent = @json(__('playbook.pay_failed'));
                setStripePayLoading(false);
            }
        }

        // Verify Stripe Payment
        async function verifyStripePayment(paymentIntentId) {
            try {
                const response = await fetch('/ultimate-funding/stripe-payment-callback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        payment_intent_id: paymentIntentId,
                        download_id: currentDownloadId
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    setStripePayLoading(false);
                    hideStripeModal();
                    showSuccessPopup(data.message);
                } else {
                    setStripePayLoading(false);
                    stripeCardErrors.textContent = data.message ||
                        @json(__('playbook.verify_fail'));
                }
            } catch (error) {
                console.error('Verification error:', error);
                setStripePayLoading(false);
                stripeCardErrors.textContent =
                    @json(__('playbook.verify_ref'));
            }
        }

        // Show/Hide Stripe Modal
        function showStripeModal() {
            console.log('showStripeModal called');
            console.log('stripeModal element:', stripeModal);
            console.log('stripeModalOverlay element:', stripeModalOverlay);
            stripeModal.classList.add('show');
            stripeModalOverlay.classList.add('show');
            console.log('Stripe modal should now be visible');
        }

        function hideStripeModal() {
            console.log('hideStripeModal called');
            stripeModal.classList.remove('show');
            stripeModalOverlay.classList.remove('show');
            stripeCardErrors.textContent = '';
        }

        // Show Flutterwave Fallback
        function showFlutterwaveFallback() {
            console.log('showFlutterwaveFallback called - showing Flutterwave option');
            fallbackNotice.classList.add('show');
            // Scroll to make the fallback visible
            fallbackNotice.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            console.log('Flutterwave fallback notice should now be visible');
        }

        function hideFallbackNotice() {
            fallbackNotice.classList.remove('show');
        }

        // Flutterwave Payment Integration (Fallback)
        function initializeFlutterwavePayment(downloadId, userEmail, userName, amount) {
            setLoading(true);

            FlutterwaveCheckout({
                public_key: "{{ config('services.flutterwave.public_key') }}",
                tx_ref: "UF_" + downloadId + "_" + Date.now(),
                amount: amount,
                currency: "USD",
                payment_options: "card,mobilemoney,ussd,banktransfer",
                customer: {
                    email: userEmail,
                    phone_number: "",
                    name: userName,
                },
                customizations: {
                    title: "Ultimate Funding & Investor Directory",
                    description: "Premium access to Africa's most comprehensive funding database",
                    logo: "{{ asset('images/Logo2.png') }}",
                },
                callback: function(data) {
                    if (data.status === 'successful') {
                        verifyFlutterwavePayment(data.transaction_id, data.tx_ref);
                    } else {
                        setLoading(false);
                        showError('emailError', @json(__('playbook.pay_not_ok')));
                    }
                },
                onclose: function() {
                    setLoading(false);
                },
            });
        }

        // Verify Flutterwave Payment
        async function verifyFlutterwavePayment(transactionId, txRef) {
            try {
                const response = await fetch('/ultimate-funding/payment-callback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        transaction_id: transactionId,
                        tx_ref: txRef
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    setLoading(false);
                    showSuccessPopup(data.message);
                } else {
                    setLoading(false);
                    showError('emailError', data.message ||
                        @json(__('playbook.verify_fail')));
                }
            } catch (error) {
                console.error('Verification error:', error);
                setLoading(false);
                showError('emailError',
                    @json(__('playbook.verify_ref'))
                );
            }
        }

        // Helper functions
        function setLoading(loading) {
            submitBtn.disabled = loading;
            btnText.style.display = loading ? 'none' : 'inline';
            spinner.style.display = loading ? 'inline' : 'none';
        }

        function setStripePayLoading(loading) {
            stripePayBtn.disabled = loading;
            if (loading) {
                stripePayBtn.classList.add('loading');
            } else {
                stripePayBtn.classList.remove('loading');
            }
        }

        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            if (!errorElement) {
                console.error(`Error element with ID ${elementId} not found`);
                return;
            }

            const inputElement = errorElement.previousElementSibling;
            errorElement.textContent = message;
            errorElement.classList.add('show');

            if (inputElement && (inputElement.tagName === 'INPUT' || inputElement.tagName === 'SELECT')) {
                inputElement.classList.add('error');
            }
        }

        function clearErrors() {
            const errorElements = document.querySelectorAll('.error-message');
            const inputElements = document.querySelectorAll('.form-info input, .form-info select');

            errorElements.forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });

            inputElements.forEach(el => {
                el.classList.remove('error');
            });

            if (stripeCardErrors) {
                stripeCardErrors.textContent = '';
            }
        }

        function showSuccessPopup(message) {
            successMessage.textContent = message;
            successOverlay.classList.add('show');
            successPopup.classList.add('show');
        }

        function hideSuccessPopup() {
            successOverlay.classList.remove('show');
            successPopup.classList.remove('show');
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    });
</script>
