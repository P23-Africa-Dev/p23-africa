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
    
    <!-- Flutterwave Payment Script (Fallback) -->
    
@endsection



@section('content')
    <section class="form-sales">
        <div class="form-sales-container">
            <div class="form-container">
                <div class="form-content">
                    <h1>
                        The Ultimate Funding Directory for
                        <span> African Entrepreneurs</span>
                    </h1>

                    <div class="form-info">
                        <form id="ultimateFundingForm">
                            @csrf
                            <div class="input-fields">
                                <div>
                                    <input type="text" name="name" placeholder="Enter Your Full name" required />
                                    <div class="error-message" id="nameError"></div>
                                </div>

                                <div>
                                    <input type="email" name="email" placeholder="Enter Your Email" required />
                                    <div class="error-message" id="emailError"></div>
                                </div>

                                <div>
                                    <input type="text" name="company" placeholder="Company/Startup name" required />
                                    <div class="error-message" id="companyError"></div>
                                </div>

                                <div>
                                    <select name="country" required>
                                        <option value="">Select your country</option>
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
                                        <option value="Other African Country">Other African Country</option>
                                        <option value="International">International</option>
                                    </select>
                                    <div class="error-message" id="countryError"></div>
                                </div>
                            </div>

                            <div class="summary-card">
                                <div class="pattern top"></div>
                                <div class="summary-item">
                                    <span class="item-description">1x Ultimate Funding Directory for African
                                        Entrepreneurs</span>
                                    <span class="item-price">$50.00</span>
                                </div>

                                <div class="summary-price">
                                    <span class="total-label">Total</span>
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
                                    <span>Alternative Payment Method</span>
                                </div>
                                <p>Card payment unavailable. Click below to pay with Flutterwave (supports mobile money,
                                    bank transfer, and other options).</p>
                                <button type="button" class="fallback-button" id="flutterwaveFallbackBtn">
                                    Pay with Flutterwave - $50
                                </button>
                            </div>

                            <div>
                                <button type="submit" class="payment-button" id="submitBtn">
                                    <span class="btn-text">Proceed to Payment - $50</span>
                                    <span class="spinner">
                                        <div></div>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="form-image desktop">
                    <img src="{{ asset('./img/desktop-fund.png') }}" loading="lazy" alt="Entrepreneur using laptop" />
                </div>
                <div class="form-image mobile">
                    <img src="{{ asset('./img/mobile-fund.png') }}" loading="lazy" alt="Entrepreneur using laptop" />
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

        <h2 class="payment-title">Payment Success!</h2>

        <div class="payment-info-box" id="successMessage">
            Your Ultimate Funding Directory has been sent to your email
        </div>

        <button class="payment-footer-btn" id="closeSuccessPopup">Back to Directory Page</button>
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
            <h3>Ultimate Funding Directory</h3>
            <p>Complete your secure payment</p>
            <div class="amount">$50.00</div>
        </div>
        <div class="stripe-modal-body">
            <div class="secure-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    viewBox="0 0 16 16">
                    <path
                        d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                </svg>
                <span>Secured by Stripe - Your payment information is encrypted</span>
            </div>
            <div id="stripe-payment-element"></div>
            <div id="stripe-card-errors" role="alert"></div>
        </div>
        <div class="stripe-modal-footer">
            <button type="button" class="stripe-pay-btn" id="stripePayBtn">
                <span class="btn-text">Pay $50.00</span>
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


