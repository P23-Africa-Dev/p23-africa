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

    <!-- Flutterwave Payment Script -->
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
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const ultimateFundingForm = document.getElementById("ultimateFundingForm");
        const submitBtn = document.getElementById("submitBtn");
        const btnText = submitBtn.querySelector(".btn-text");
        const spinner = submitBtn.querySelector(".spinner");
        const successPopup = document.getElementById("successPopup");
        const successOverlay = document.getElementById("successOverlay");
        const successMessage = document.getElementById("successMessage");
        const closeSuccessPopup = document.getElementById("closeSuccessPopup");

        // Success popup controls
        closeSuccessPopup.addEventListener("click", () => {
            hideSuccessPopup();
            // Redirect back to ultimate funding page
            window.location.href = "{{ route('playbook.landing2') }}";
        });

        successOverlay.addEventListener("click", () => {
            hideSuccessPopup();
            window.location.href = "{{ route('playbook.landing2') }}";
        });

        // Form submission
        ultimateFundingForm.addEventListener("submit", async function(e) {
            e.preventDefault();

            clearErrors();

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
                showError('emailError', 'Please enter a valid email address.');
                hasErrors = true;
            }

            if (!company) {
                showError('companyError', 'Company/Startup name is required.');
                hasErrors = true;
            }

            if (!country) {
                showError('countryError', 'Please select your country.');
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            // Show loading state
            setLoading(true);

            try {
                // First, create the user account
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
                    // Proceed to payment
                    initializeFlutterwavePayment(data.download_id, data.user_email, data.user_name,
                        data.amount);
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
                            'An error occurred. Please try again.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showError('emailError',
                    'Network error. Please check your connection and try again.');
            } finally {
                setLoading(false);
            }
        });

        // Flutterwave Payment Integration
        function initializeFlutterwavePayment(downloadId, userEmail, userName, amount) {
            FlutterwaveCheckout({
                public_key: "FLWPUBK_TEST-044e7163292b9b7d4f0d4db5022227c4-X",
                tx_ref: "UF_" + downloadId + "_" + Date.now(),
                amount: amount,
                currency: "USD",
                payment_options: "card,mobilemoney,ussd",
                customer: {
                    email: userEmail,
                    phone_number: "",
                    name: userName,
                },
                customizations: {
                    title: "Ultimate Funding & Investor Directory",
                    description: "Premium access to Africa's most comprehensive funding database",
                    logo: "{{ asset('images/logo.png') }}",
                },
                callback: function(data) {
                    // Handle successful payment
                    if (data.status === 'successful') {
                        // Verify payment
                        verifyPayment(data.transaction_id, data.tx_ref);
                    } else {
                        // Handle failed payment
                        setLoading(false);
                        showError('emailError', 'Payment was not successful. Please try again.');
                    }
                },
                onclose: function() {
                    // User closed payment modal
                    setLoading(false);
                    showError('emailError',
                        'Payment was cancelled. Please try again to access the directory.');
                },
            });
        }

        // Verify payment function
        async function verifyPayment(transactionId, txRef) {
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
                    // Payment verified successfully
                    setLoading(false);
                    showSuccessPopup(data.message);
                } else {
                    // Verification failed
                    setLoading(false);
                    showError('emailError', data.message ||
                        'Payment verification failed. Please contact support.');
                }
            } catch (error) {
                console.error('Verification error:', error);
                setLoading(false);
                showError('emailError',
                    'Payment verification failed. Please contact support with your transaction reference.'
                    );
            }
        }

        // Helper functions
        function setLoading(loading) {
            submitBtn.disabled = loading;
            btnText.style.display = loading ? 'none' : 'inline';
            spinner.style.display = loading ? 'inline' : 'none';
        }

        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            const inputElement = errorElement.previousElementSibling;

            if (!errorElement) {
                console.error(`Error element with ID ${elementId} not found`);
                return;
            }

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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const openModal = document.getElementById("openModal");
        const closeModal = document.getElementById("closeModal");
        const modalOverlay = document.getElementById("modalOverlay");
        const downloadForm = document.getElementById("downloadForm");
        const submitBtn = document.getElementById("submitBtn");
        const btnText = submitBtn.querySelector(".btn-text");
        const spinner = submitBtn.querySelector(".spinner");

        // Modal controls
        openModal.addEventListener("click", (e) => {
            e.preventDefault();
            modalOverlay.classList.add("active");
            clearErrors();
            clearSuccess();
        });

        closeModal.addEventListener("click", () => {
            modalOverlay.classList.remove("active");
            clearErrors();
            clearSuccess();
        });

        modalOverlay.addEventListener("click", (e) => {
            if (e.target === modalOverlay) {
                modalOverlay.classList.remove("active");
                clearErrors();
                clearSuccess();
            }
        });

        // Form submission
        downloadForm.addEventListener("submit", async function(e) {
            e.preventDefault();

            clearErrors();
            clearSuccess();

            // Get form data
            const formData = new FormData(downloadForm);
            const name = formData.get('name').trim();
            const email = formData.get('email').trim();

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
                showError('emailError', 'Please enter a valid email address.');
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            // Show loading state
            setLoading(true);

            try {
                const response = await fetch('/sales-book/download', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showSuccess(data.message);
                    downloadForm.reset();

                    // Auto-close modal after 3 seconds
                    setTimeout(() => {
                        modalOverlay.classList.remove("active");
                        clearSuccess();
                    }, 3000);

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
                            'An error occurred. Please try again.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showError('emailError',
                    'Network error. Please check your connection and try again.');
            } finally {
                setLoading(false);
            }
        });

        // Helper functions
        function setLoading(loading) {
            submitBtn.disabled = loading;
            btnText.style.display = loading ? 'none' : 'inline';
            spinner.style.display = loading ? 'inline' : 'none';
        }

        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            const inputElement = errorElement.previousElementSibling;

            errorElement.textContent = message;
            errorElement.classList.add('show');
            inputElement.classList.add('error');
        }

        function clearErrors() {
            const errorElements = document.querySelectorAll('.error-message');
            const inputElements = document.querySelectorAll('.modal-box input');

            errorElements.forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });

            inputElements.forEach(el => {
                el.classList.remove('error');
            });
        }

        function showSuccess(message) {
            const successElement = document.getElementById('successMessage');
            successElement.textContent = message;
            successElement.classList.add('show');
        }

        function clearSuccess() {
            const successElement = document.getElementById('successMessage');
            successElement.classList.remove('show');
            successElement.textContent = '';
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    });
</script>
