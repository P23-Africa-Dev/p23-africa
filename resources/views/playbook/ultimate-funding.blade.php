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
    <meta property="og:image" content="{{ asset('img/desktop-fund.png') }}">
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

    .sales-container {
        max-width: 1204px;
        margin: 0 auto;
    }

    /* .sales-premimum-container {
        max-width: 1204px;
        margin: 0 auto;
    } */


    .intro-sales {
        padding: 2rem 2.5rem 2rem 1.9rem;
        background-image: url("/img/first-bg.png");
        background-repeat: repeat;
        background-size: contain;
        margin-top: 5rem;
    }

    .intro-container {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 2rem;
    }

    .intro-image.desktop {
        display: none;
    }

    .intro-image img {
        max-width: 100%;
    }

    .intro-image.mobile img {
        margin-top: 15px;
        margin-left: -6px;
    }

    .intro-content h1 {
        font-size: 3.3rem;
        color: #0d4036;
        line-height: 1;
        font-weight: 900;

    }

    .intro-content h1 span {
        color: #52ccb4;
    }

    .intro-content p {
        color: #0d4036;
        margin: 0.8rem 0;
        max-width: 19rem;
        font-family: "GT Walsheim Con";
        padding-right: 30px;

        font-weight: 500;
        line-height: 1.3;
        font-size: 15px;
    }

    .intro-sales .cta-btn {
        display: inline-block;
        padding: 0.9rem 2rem;
        background: #52ccb4;
        font-family: "GT Walsheim Trial Cn Md";

        /* font-weight: 500; */

        color: #0d4036;
        font-weight: 500;
        border-radius: 6px;
        text-decoration: none;
        box-shadow: 2px 2px 1px rgba(0, 0, 0, 0.3);
        margin-top: 15px;
        text-align: center;
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
        font-size: 1.25rem;
        font-weight: 700;
        padding: 0px 30px;
        font-family: "GT Walsheim ProCB";

    }

    .book-header p {
        max-width: 500px;
        margin: 1rem auto 2rem;
        padding: 0px 30px;
        line-height: 1.3;
        font-size: 20px;
        vertical-align: middle;
        font-family: "GT Walsheim Con";

        font-weight: 400;
    }

    .book-cards {
        display: grid;
        gap: 0.1rem;
        margin-top: 1.5rem;
    }

    .book-card {
        overflow: hidden;
    }

    .book-card .card-image {
        position: relative;
        width: 100%;
        height: 400px;
    }

    .book-card .card-image img {
        width: 100%;
        height: 100%;
    }

    .book-section .closing-text {
        max-width: 350px;
        margin: 2.5rem auto 1.5rem;
        font-size: 1.3rem;
        padding: 0px 20px;
        line-height: 1.2;
        font-family: "GT Walsheim ProCB";


        font-weight: 700;
    }

    .book-section .cta-btn {
        display: inline-block;
        padding: 1rem 2rem;
        background: #52ccb4;
        color: #0d4036;
        font-family: "GT Walsheim Trial Cn Md";

        border-radius: 6px;
        text-decoration: none;
        margin-top: 1.3rem;
        box-shadow: 2px 3px 1px rgba(0, 0, 0, 0.3);
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
        background-image: url("/img/first-bg.png");

        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 10px;
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
        line-height: 1.4;
        font-family: "GT Walsheim Trial Cn Md";
        font-weight: 500;
        margin-bottom: 3rem;
        padding: 0px 60px;
    }

    /* INPUTS */
    .modal-box input {
        width: 100%;
        padding: 1rem 1rem 1rem 2rem;
        border-radius: 10px;
        border: none;
        outline: none;
        margin-bottom: 2.3rem;
        font-size: 0.95rem;
        box-shadow: 2px 10px 10px rgba(0, 0, 0, 0.12);
        border-bottom: 1.6px solid #0d4036;
    }

    .modal-box input::placeholder {
        color: #afaeae;
        font-family: "GT Walsheim Con";
        letter-spacing: 0.4px;
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

    /* PROCESSING OVERLAY STYLES */
    #processingOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    #processingOverlay.show {
        opacity: 1;
        visibility: visible;
    }

    #processingOverlay.fade-out {
        opacity: 0;
        visibility: hidden;
    }

    .overlay-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(13, 64, 54, 0.85);
        backdrop-filter: blur(3px);
    }

    .processing-spinner {
        position: absolute;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 20px 25px;
        border-radius: 15px;
        font-weight: bold;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 250px;
        text-align: center;
        flex-direction: column;
    }

    .spinner-icon {
        font-size: 24px;
        color: white;
    }

    .processing-text {
        font-size: 16px;
        font-weight: 600;
    }

    .success-icon {
        font-size: 24px;
        color: #4CAF50;
    }

    .countdown-text {
        font-size: 16px;
        font-weight: 600;
        animation: pulse 1s infinite;
    }

    #successBanner {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(-100%);
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        font-weight: bold;
        font-size: 16px;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        z-index: 10001;
        transition: transform 0.3s ease;
        opacity: 0;
    }

    #successBanner.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }

    #successBanner.fade-out {
        opacity: 0;
        transform: translateX(-50%) translateY(-100%);
    }

    .success-content {
        display: flex;
        align-items: center;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
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
        justify-content: center;
        align-items: center;
        border-radius: 10px;
        padding: 25% 32px;
        text-align: left;
        margin: 40px 14px 13px 14px;
    }

    .premium-section .text-content-upper h2 {
        font-size: 22px;
        color: #0d4036;
        padding-bottom: 20px;
        padding-right: 30px;
        font-family: "GT Walsheim ProCB";

    }

    .premium-section .info {
        background-image: url("/img/last-bg.png");
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        padding: 90px 40px;
    }

    .premium-section .info h3 {
        font-size: 32px;
        color: #fff;
        font-family: "GT Walsheim ProCB";

        margin-bottom: 30px;
        padding-right: 20px;
    }

    .premium-section .info p {
        font-size: 19px;
        /* margin-top: 32px; */
        color: #fff;
        max-width: 260px;
        font-family: "GT Walsheim Con";
    }

    .premium-section .learn-more {
        display: inline-block;
        margin-top: 0px;
        background-color: #cced60;
        color: #0d4036;
        padding: 1.1rem 1.2rem;
        text-decoration: none;
        border-radius: 7px;
        width: 95%;
        border: 1px solid #cced60;
        text-align: center;
        box-shadow: 2px 2px 1px rgba(0, 0, 0, 0.3);
        font-family: "GT Walsheim Trial Cn Md";
        transition: background-color 1s ease-in-out;
        letter-spacing: 0.5px;
    }

    .premium-section .learn-more:hover {
        background-color: #fff;
        color: #0d4036;
        border: 1px solid #0d4036;
    }

    /* Tablet */
    @media (min-width: 768px) {
        .intro-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 0rem;
        }

        .intro-content {
            display: flex;
            flex-direction: column;
            padding-top: 1rem;
            margin-left: 7%;
        }

        .intro-content h1 {
            font-size: 2.5rem;
        }

        .intro-image.desktop {
            display: block;
            margin-left: -7%;
            overflow: hidden;
        }

        .intro-sales .cta-btn {
            width: 70%;
        }

        .intro-image.desktop img {
            width: 100%;
            height: 100%;
            top: 0;
            object-fit: contain;
            overflow: hidden;
        }

        .intro-image.mobile {
            display: none;
        }

        .premium-section .text-content {
            padding: 7% 30px;
            text-align: left;
        }

        /* =========================================BOOK SECTION */

        .book-cards {
            max-width: 400px;
            margin: 0 auto;
        }

        .book-cards {
            max-width: initial;
            grid-template-columns: repeat(3, 1fr);
        }

        .book-header p {
            padding: 0px 15px;
        }

        .book-card .card-image {
            width: 100%;
            height: 270px;
        }

        .book-section .closing-text {
            max-width: 450px;
            font-size: 1rem;
            padding: 0px 20px;
        }

        .book-section .cta-btn {
            margin-top: 0.3rem;
            box-shadow: 2px 2px 1px rgba(0, 0, 0, 0.3);
        }

        .premium-section .learn-more {
            width: 50%;
        }

        .premium-section .text-content-upper {
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }

        .premium-section .info-container {
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }

        .premium-section .info p {
            max-width: initial;
        }

        .premium-section .text-content-upper h2 {
            padding: 0px 40px;
            padding-bottom: 20px;
        }

        .premium-section .learn-more {

            width: 95%;

        }

    }

    @media (min-width: 900px) {
        .intro-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 0rem;
        }

        .intro-content h1 {
            font-size: 3.7rem;
            padding-right: 8px;
        }

        .intro-sales .cta-btn {
            width: 40%;
        }

        .intro-image.desktop {
            max-width: 100%;
            /* padding-right: 30%; */
        }

        .intro-image.desktop img {
            /* margin-right: -80%; */
            height: 100%;
            width: 100%;
        }

        /* =========================================BOOK SECTION */
        .book-card .card-image {
            width: 100%;
            height: 400px;
        }


        /* ==================================== PREMIUM SECTION */
        .premium-section .content {
            flex-direction: row;
            padding: 20px;
        }

        .premium-section .text-content {
            margin: 0px;
            width: 480px;
            padding: 40px 30px;
            gap: 20px;
        }

        .premium-section .learn-more {
            padding: 1.1rem 1.2rem;

            width: 90%;
        }

        .premium-section .info {
            width: 100%;
        }

        .premium-section .info-container {
            display: flex;
            flex-direction: column;
            gap: 30px;
            width: 100%;
        }

        .premium-section .info {
            border-radius: 10px;
            padding: 40px 50px;
        }

        .premium-section .info ul {
            margin-top: 15px;
        }
    }

    @media (min-width: 1024px) {
        .intro-content {
            padding-left: 80px;
        }

        .intro-image.desktop {
            max-width: 100%;
            width: 500px;
        }

        .premium-section .text-content-upper {
            max-width: initial;
            margin: initial;
            text-align: left;
        }

        .premium-section .info-container {
            max-width: initial;
            margin: initial;
            text-align: left;
        }

        .premium-section .info p {
            max-width: initial;
        }

        .premium-section .text-content-upper h2 {
            padding: initial;
            padding-bottom: 20px;
        }

        .premium-section .info h3 {
            margin-bottom: 0px;
            padding-right: 0px;
        }

        .premium-section .learn-more {
            width: 100%;
            font-size: 14px;
        }
    }

    @media (min-width: 1200px) {
        .intro-container {
            transform: translateX(-70px);
        }

        .intro-content {
            margin-left: 0%;
            padding-top: 0rem;
        }

        .intro-content h1 {
            font-size: 5.2rem;
            padding-right: 0px;
        }

        .intro-content p {
            /* font-size: 5.2rem; */
            /* padding-right: 0px; */
            max-width: 19rem;
            font-size: 17px;
            max-width: 26rem;
            margin-top: 24px;
        }

        .intro-sales .cta-btn {
            margin-top: 5px;
            letter-spacing: 0.5px;
            font-size: 18px;
        }

        .intro-image.desktop {
            max-width: initial;
            margin-left: 0%;
            width: 50rem;
            transform: translateX(-180px);
        }

        .intro-image.desktop img {
            margin-top: 10px;
        }

        .book-section {
            padding: 5rem 1.5rem;
            background-image: url("/img/second-bg.png");
            background-repeat: repeat;
            background-size: contain;
        }

        .book-header h3 {
            font-size: 1.26rem;
        }

        .book-header p {
            max-width: 570px;
            padding: 0px 40px;
            line-height: 1;
        }

        .book-section .closing-text {
            max-width: 640px;
            font-size: 1.3rem;
            letter-spacing: 0.2px;
        }

        .book-section .cta-btn {
            padding: 1rem 3rem;
            font-size: 20px;
            margin-top: 20px;
        }

        .premium-section .info {
            padding: 60px;
        }

        .premium-section .info h3 {
            font-size: 36px;
        }

        .premium-section .info p {
            max-width: 300px;
            font-weight: lighter;
            letter-spacing: 0.3px;
        }

        .premium-section .info-container {
            max-width: 600px;
        }

        .premium-section .text-content-upper h2 {
            font-size: 19px;
            padding-right: 30px;
        }
    }
</style>

@section('content')
    <!-- Success Alert for Payment -->
    <div id="paymentSuccessAlert" class="alert alert-success"
        style="display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 90%; max-width: 500px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2" style="color: #28a745; font-size: 1.2rem;"></i>
            <div>
                <strong>Payment Successful!</strong><br>
                <small>The Ultimate Funding Directory has been sent to your email.</small>
            </div>
            <button type="button" class="btn-close ms-auto"
                onclick="document.getElementById('paymentSuccessAlert').style.display='none'"></button>
        </div>
    </div>

    <section class="intro-sales funding-ebook">
        <div class="sales-container">
            <div class="intro-container">
                <div class="intro-content">
                    <h1>
                        The Ultimate Funding Directory for <span>African Entrepreneurs</span>
                    </h1>

                    <p>
                        The most complete, Africa-focused funding and investor directory, curated to help entrepreneurs find
                        the right capital faster.
                    </p>

                    <a href="{{ locale_route('playbook.checkout') }}" class="cta-btn">Download Now</a>
                </div>

                <div class="intro-image desktop">
                    <img src="{{ asset('/img/desktop-fund.png') }}" loading="lazy" alt="Entrepreneur using laptop" />

                </div>
                <div class="intro-image mobile">
                    <img src="{{ asset('/img/mobile-fund.png') }}" loading="lazy" alt="Entrepreneur using mobile" />
                </div>
            </div>
        </div>
    </section>

    <section class="book-section">
        <div class="sales-container">
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
                            <img src="{{ asset('./img/fund-1.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                    <div class="book-card card-2">
                        <div class="card-image">
                            <img src="{{ asset('./img/fund-2.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                    <div class="book-card card-3" id="getGuide">
                        <div class="card-image">
                            <img src="{{ asset('./img/fund-3.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                </div>

                <p class="closing-text">
                    This directory gives you direct access to active investors, global grant programs, and funding
                    opportunities designed for African entrepreneurs.
                </p>

                <a href="{{ locale_route('playbook.checkout') }}" class="cta-btn">Get Access to the Funding Directory</a>
            </div>
        </div>
    </section>

    <section class="premium-section">
        <div class="sales-container ">
            <div class="content">
                <div class="text-content">
                    <div class="text-content-upper">
                        <h2>Get Access to the Ultimate Funding & Investor Directory</p>

                            <a href="{{ locale_route('playbook.checkout') }}" class="learn-more">Get your Funding Directory
                                Here!</a>
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
@endsection
