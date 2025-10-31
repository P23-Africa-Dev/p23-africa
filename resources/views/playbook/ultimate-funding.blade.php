@extends('layouts.master')
@section('title', 'Ultimate Funding & Investor Directory for African Entrepreneurs | P23 Africa')

@section('meta')
    <meta name="description"
        content="Access the most complete Africa-focused funding and investor directory. Find active investors, global grant programs, and funding opportunities designed specifically for African entrepreneurs.">
    <meta name="keywords"
        content="funding directory, investor directory, African investors, startup funding, venture capital Africa, grant programs, angel investors, African entrepreneurs, funding opportunities, investor database">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Ultimate Funding & Investor Directory for African Entrepreneurs | P23 Africa">
    <meta property="og:title" content="Ultimate Funding & Investor Directory | P23 Africa">
    <meta property="og:description"
        content="Direct access to active investors, global grant programs, and funding opportunities. The ultimate directory curated for African entrepreneurs seeking the right capital.">
    <meta property="og:image" content="{{ asset('images/funding-directory-meta.png') }}">
    <meta property="og:type" content="website">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Flutterwave Payment Script -->
    <script src="https://checkout.flutterwave.com/v3.js"></script>
@endsection

{{-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> --}}

<style>
    body {
        font-family: "GT Walsheim Trial Md";
    }

    .container {
        max-width: 1024px;
        margin: 0 auto;
    }

    .intro-sales {
        padding: 2rem 1.5rem;
        background-image: url("/img/first-bg.png");


        background-repeat: repeat;
        background-size: contain;
        margin-top: 3rem;
    }

    .intro-sales.funding-ebook h1 span {
        color: #52CCB4;
    }

    .intro-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .intro-content h1 {
        font-size: 2.8rem;
        font-weight: 900;
        color: #0d4036;
        line-height: 1.1;
        /* font-family: 'GT Walsheim Trial Cn Bl'; */

    }

    .intro-content h1 span {
        color: #52ccb4;
    }

    .intro-content p {
        color: #0d4036;
        font-weight: 200;
        margin: 1rem 0 1.5rem;
        max-width: 22rem;
    }

    .intro-sales .cta-btn {
        display: inline-block;
        padding: 0.9rem 4rem;
        background: #52ccb4;
        color: #0d4036;
        font-weight: 200;
        border-radius: 6px;
        text-decoration: none;
        margin-bottom: 2rem;
        box-shadow: 5px 2px 1px rgba(0, 0, 0, 0.5);
    }

    .stats-box {
        background: white;
        padding: 1.2rem 1.5rem;
        border-radius: 8px;
        width: fit-content;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
    }

    .stats-box h2 {
        font-size: 2rem;
        font-weight: 800;
        color: #0d4036;
    }

    .stats-box p {
        margin-top: 0.3rem;
        color: #0d4036;
        font-size: 0.9rem;
    }

    .intro-image.desktop {
        display: none;
    }

    .intro-image img {
        width: 100%;
        border-radius: 40px;
        object-fit: cover;
    }

    /* =========================================BOOK SECTION */

    .book-section {
        padding: 3rem 1.5rem;
        background-image: url("/img/second-bg.png");
        background-repeat: repeat;
        background-size: contain;
    }

    .book-container {
        text-align: center;
        color: #0d4036;
    }

    .book-header h3 {
        font-size: 1.3rem;
        font-weight: 700;
    }

    .book-header p {
        max-width: 500px;
        margin: 1rem auto 2rem;
        line-height: 1.6;
        font-weight: 500;
    }

    .book-cards {
        display: grid;
        gap: 0rem;
        margin-top: 1.5rem;
    }

    .book-card {
        /* border-radius: 12px; */

        overflow: hidden;
    }

    .book-card .card-image {
        position: relative;
        width: 100%;
        height: 400px;

        /* justify-content: flex-start; */
    }

    .book-card .card-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .book-card h4 {
        margin: 1rem 0 0.5rem;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .book-card p {
        padding: 0 1rem;
        line-height: 1.5;
        font-size: 0.95rem;
    }

    .closing-text {
        max-width: 650px;
        margin: 2.5rem auto 1.5rem;
        font-size: 1rem;
    }

    .book-section .cta-btn {
        display: inline-block;
        padding: 1rem 2rem;
        background: #0d4036;
        color: white;
        font-weight: 600;
        text-decoration: none;
        border-radius: 8px;
    }

    /* OVERLAY */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.35s ease, visibility 0.35s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* MODAL ANIMATION */
    .modal-box {
        background: #ffffff;
        background-image: url("bg-texture.png");
        background-size: cover;
        border-radius: 18px;
        padding: 2rem;
        width: 100%;
        max-width: 400px;
        position: relative;
        text-align: center;

        /* Animation starting state */
        transform: translateY(40px) scale(0.92);
        opacity: 0;
        transition: transform 0.35s ease, opacity 0.35s ease;
    }

    /* When overlay is active, animate modal in */
    .modal-overlay.active .modal-box {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    /* TEXT */
    .modal-box h3 {
        color: #0d4036;
        font-size: 1.1rem;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 1.5rem;
    }

    /* INPUTS */
    .modal-box input {
        width: 100%;
        padding: 1rem;
        border-radius: 10px;
        border: 2px solid #e1e5e9;
        outline: none;
        margin-bottom: 1rem;
        font-size: 0.95rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        transition: border-color 0.3s ease;
    }

    .modal-box input:focus {
        border-color: #52ccb4;
        box-shadow: 0 4px 10px rgba(82, 204, 180, 0.2);
    }

    .modal-box input.error {
        border-color: #e74c3c;
    }

    .modal-box select {
        width: 100%;
        padding: 12px 16px;
        margin: 8px 0;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        background-color: #fff;
        color: #333;
        cursor: pointer;
        transition: border-color 0.3s ease;
    }

    .modal-box select:focus {
        outline: none;
        border-color: #52CCB4;
        box-shadow: 0 4px 10px rgba(82, 204, 180, 0.2);
    }

    .modal-box select.error {
        border-color: #e74c3c;
    }

    .payment-info {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
        border: 2px solid #52CCB4;
        border-radius: 8px;
        padding: 16px;
        margin: 16px 0;
        text-align: center;
    }

    .price-display {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        font-weight: bold;
    }

    .price-label {
        color: #0d4036;
        font-size: 16px;
    }

    .price-amount {
        color: #52CCB4;
        font-size: 24px;
        font-weight: 700;
    }

    .payment-description {
        font-size: 14px;
        color: #0d4036;
        margin: 0;
        line-height: 1.4;
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

    /* SUCCESS MESSAGE */
    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        border: 1px solid #c3e6cb;
        display: none;
    }

    .success-message.show {
        display: block;
    }

    /* SPINNER ANIMATION */
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* BUTTON DISABLED STATE */
    .form-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* BUTTON */
    .form-btn {
        width: 100%;
        padding: 1rem;
        background: #0d4036;
        color: white;
        font-weight: 600;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }

    /* CLOSE BUTTON */
    .close-modal {
        position: absolute;
        top: 8px;
        right: 12px;
        background: none;
        border: none;
        font-size: 1.8rem;
        color: #0d4036;
        cursor: pointer;
    }

    /* ================================== Premium Section */

    .premium-section {
        background-color: #fff;
    }

    .premium-section .content {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .premium-section .text-content {
        text-align: center;
        background-color: #e5ae56;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-radius: 10px;
        padding: 30px;
        text-align: left;
        gap: 60px;
        margin: 20px;
    }

    .premium-section .text-content-upper,
    .premium-section .text-content-below {
        /* max-width: 210px; */
    }

    .premium-section .text-content-upper h2 {
        font-size: 20px;
        color: #0d4036;
        padding-bottom: 20px;
    }

    .premium-section .text-content-upper p {
        font-size: 16px;
        color: #0d4036;
    }

    .premium-section .text-content-below p {
        font-size: 19px;
        color: #0d4036;
        font-weight: 700;
    }

    .premium-section .info {
        background-image: url("/img/last-bg.png");


        background-repeat: no-repeat;
        background-size: cover;
        padding: 20px 50px;
        margin-bottom: 20px;
        border-radius: 10px;

        color: #fff;

    }

    .premium-section .info-container {
        display: flex;
        flex-direction: column;
    }

    .premium-section .info h3 {
        font-size: 35px;
        color: #fff;
        margin-bottom: 10px;
    }

    .premium-section .info .info-headings {
        display: flex;
        color: #fff;
        margin-top: 30px;
    }

    .premium-section .info .info-headings>div {
        display: flex;
        flex-direction: column;
    }

    .premium-section .info .info-headings>div h5 {
        font-size: 40px;
    }

    .premium-section .info ul {
        list-style: disc;
        padding-left: 0;
        color: #fff;
        margin-top: 40px;
    }

    .premium-section .info ul li {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .premium-section .learn-more {
        display: inline-block;
        margin-top: 20px;
        background-color: #cced60;
        color: #0d4036;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 15px;
        width: 100%;
        text-align: center;
        box-shadow: 5px 2px 1px rgba(0, 0, 0, 0.5);

    }


    /* Desktop */
    @media (min-width: 768px) {
        .intro-container {
            /* max-width: 600px;
    margin: 0 auto; */
        }

        .intro-image {
            max-width: 80%;
        }
    }

    @media (min-width: 900px) {
        .intro-container {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }

        .intro-content {
            max-width: 45%;
        }

        .intro-image.mobile {
            display: none;
        }

        .intro-image.desktop {
            display: block;
        }

        .intro-image {
            max-width: 60%;
        }

        .intro-content h1 {
            font-size: 5rem;
        }

        .intro-sales.funding-ebook h1 {
            font-size: 4rem;

        }

        .book-cards {
            grid-template-columns: repeat(3, 1fr);
        }

        .premium-section .content {
            flex-direction: row;
            padding: 20px;
        }

        .premium-section .text-content {
            margin: 0px;
            max-width: 350px;
        }

        .premium-section .info {
            margin: 0px;
            width: 100%;
        }

        .premium-section .info-container {
            display: flex;
        }

        .premium-section .info {
            border-radius: 10px;
            margin-bottom: 0px;
        }

        .premium-section .info-container h3 {
            font-size: 27px;
            padding-right: 30px;
            max-width: 400px;
        }

        .premium-section .info .info-headings {
            margin-top: 0px;
        }

        .premium-section .learn-more {
            width: 100%;
            white-space: nowrap;
        }
    }
</style>

@section('content')
    <section class="intro-sales funding-ebook">
        <div class="container">
            <div class="intro-container">
                <div class="intro-content">
                    <h1>
                        The Ultimate Funding Directory for <span>African Entrepreneurs</span>
                    </h1>

                    <p>
                        The most complete, Africa-focused funding and investor directory, curated to help entrepreneurs find
                        the right capital faster.
                    </p>

                    <a href="#getGuide" class="cta-btn">Find Out More</a>
                </div>

                <div class="intro-image desktop">
                    <img src="{{ asset('/img/desktop-fund.png') }}" alt="Entrepreneur using laptop" />

                </div>
                <div class="intro-image mobile">
                    <img src="{{ asset('/img/mobile-fund.png') }}" alt="Entrepreneur using mobile" />
                </div>
            </div>
        </div>
    </section>

    <section class="book-section">
        <div class="container">
            <div class="book-container">
                <div class="book-header">
                    <h3>What you’ll Find in Our Funding Directory</h3>
                    <p>
                        You’ve prepared your business. Now it’s time to find the right partners to fund your growth.
                    </p>
                </div>

                <div class="book-cards">
                    <div class="book-card card-1">
                        <div class="card-image">
                            <img src="{{ asset('./img/fund-1.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="book-card card-2">
                        <div class="card-image">
                            <img src="{{ asset('./img/fund-2.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="book-card card-3" id="getGuide">
                        <div class="card-image">
                            <img src="{{ asset('./img/fund-3.png') }}" alt="" />
                        </div>
                    </div>
                </div>

                <p class="closing-text">
                    This directory gives you direct access to active investors, global grant programs, and funding
                    opportunities designed for African entrepreneurs.
                </p>

                <a href="#" class="cta-btn" id="openModal">Get Access to the Funding Directory</a>
            </div>
        </div>
    </section>

    <section class="premium-section">
        <div class="container">
            <div class="content">
                <div class="text-content">
                    <div class="text-content-upper">
                        <h2>Get Access to the Ultimate Funding & Investor Directory</p>

                            <a href="#" class="learn-more">Get your Funding Directory Here!</a>
                    </div>

                </div>
                <div class="info">
                    <div class="info-container">
                        <h3>“Before this directory, finding serious investors was guesswork. Now I know exactly who to
                            approach.”</h3>

                        <p>Amina K, Founder of a HealthTech Startup, Nigeria</p>

                    </div>


                </div>
            </div>
        </div>
    </section>

    <!-- FORM MODAL -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-box">
            <h3>Get Access to the Ultimate Funding Directory<br />Premium Access - $50</h3>

            <div class="success-message" id="successMessage"></div>

            <form id="downloadForm">
                @csrf
                <input type="text" name="name" placeholder="Full name" required />
                <div class="error-message" id="nameError"></div>

                <input type="email" name="email" placeholder="Email" required />
                <div class="error-message" id="emailError"></div>

                <input type="text" name="company" placeholder="Company/Startup name" required />
                <div class="error-message" id="companyError"></div>

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

                <select name="funding_stage" required>
                    <option value="">Select your funding stage</option>
                    <option value="Pre-seed">Pre-seed (Idea stage)</option>
                    <option value="Seed">Seed (Early stage)</option>
                    <option value="Series A">Series A (Growth stage)</option>
                    <option value="Series B+">Series B+ (Expansion stage)</option>
                    <option value="Bootstrap">Bootstrap (Self-funded)</option>
                    <option value="Revenue">Revenue generating</option>
                    <option value="Not applicable">Not applicable</option>
                </select>
                <div class="error-message" id="funding_stageError"></div>

                <div class="payment-info">
                    <div class="price-display">
                        <span class="price-label">Premium Directory Access:</span>
                        <span class="price-amount">$50.00</span>
                    </div>
                    <p class="payment-description">
                        🌟 One-time payment for lifetime access to the complete directory<br>
                        💎 500+ verified investors & funding opportunities<br>
                        📧 Instant delivery via email after payment
                    </p>
                </div>

                <button type="submit" class="form-btn" id="submitBtn">
                    <span class="btn-text">Create Account & Pay $50</span>
                    <span class="spinner" style="display: none;">
                        <div
                            style="display: inline-block; width: 16px; height: 16px; border: 2px solid transparent; border-top: 2px solid white; border-radius: 50%; animation: spin 1s linear infinite;">
                        </div>
                    </span>
                </button>
            </form>

            <button class="close-modal" id="closeModal">&times;</button>
        </div>
    </div>
@endsection


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
            const company = formData.get('company').trim();
            const country = formData.get('country').trim();
            const funding_stage = formData.get('funding_stage').trim();

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

            if (!funding_stage) {
                showError('funding_stageError', 'Please select your funding stage.');
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
                        country: country,
                        funding_stage: funding_stage
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Account created successfully, now initiate payment
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
                // public_key: "FLWPUBK_TEST-68f71675ffc7bcaf905e8893d405171a-X", // Replace with your actual public key
                public_key: "FLWPUBK-366fbf9270d878c2e5e67b0a89f037e7-X",
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
                    console.log(data);
                    // Handle successful payment
                    handlePaymentCallback(downloadId, data);
                },
                onclose: function() {
                    // User closed payment modal
                    showError('emailError',
                        'Payment was cancelled. Please try again to access the directory.');
                },
            });
        }

        // Handle payment callback
        async function handlePaymentCallback(downloadId, paymentData) {
            try {
                const response = await fetch('/ultimate-funding/payment-callback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        download_id: downloadId,
                        payment_reference: paymentData.tx_ref,
                        payment_status: paymentData.status,
                        amount: paymentData.amount,
                        transaction_id: paymentData.transaction_id
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showSuccess(data.message);
                    downloadForm.reset();

                    // Auto-close modal after 5 seconds
                    setTimeout(() => {
                        modalOverlay.classList.remove("active");
                        clearSuccess();
                    }, 5000);
                } else {
                    showError('emailError', data.message ||
                        'Payment verification failed. Please contact support.');
                }
            } catch (error) {
                console.error('Payment verification error:', error);
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

            errorElement.textContent = message;
            errorElement.classList.add('show');
            inputElement.classList.add('error');
        }

        function clearErrors() {
            const errorElements = document.querySelectorAll('.error-message');
            const inputElements = document.querySelectorAll('.modal-box input, .modal-box select');

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
