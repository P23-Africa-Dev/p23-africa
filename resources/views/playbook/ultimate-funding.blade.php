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
  padding: 0.9rem 4rem;
  background: #52ccb4;
  font-family: "GT Walsheim Trial Cn Md";

  /* font-weight: 500; */

  color: #0d4036;
  font-weight: 500;
  border-radius: 6px;
  text-decoration: none;
  box-shadow: 2px 2px 1px rgba(0, 0, 0, 0.3);
  margin-top: 15px;
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

                    <a href="#getGuide" class="cta-btn">Find Out More</a>
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

                <a href="/playbook/ultimate-funding/checkout" class="cta-btn" >Get Access to the Funding Directory</a>
                <!-- <a href="#" class="cta-btn" id="openModal">Get Access to the Funding Directory</a> -->
            </div>
        </div>
    </section>

    <section class="premium-section">
        <div class="sales-container ">
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

                {{-- <select name="funding_stage" required>
                    <option value="">Select your funding stage</option>
                    <option value="Pre-seed">Pre-seed (Idea stage)</option>
                    <option value="Seed">Seed (Early stage)</option>
                    <option value="Series A">Series A (Growth stage)</option>
                    <option value="Series B+">Series B+ (Expansion stage)</option>
                    <option value="Bootstrap">Bootstrap (Self-funded)</option>
                    <option value="Revenue">Revenue generating</option>
                    <option value="Not applicable">Not applicable</option>
                </select>
                <div class="error-message" id="funding_stageError"></div> --}}

                <div class="payment-info">
                    <div class="price-display">
                        <span class="price-label">Premium Directory Access:</span>
                        <span class="price-amount">$50</span>
                    </div>
                    <p class="payment-description">
                        🌟 One-time payment for lifetime access to the complete e-book<br>
                        {{-- 💎 500+ verified investors & funding opportunities<br> --}}
                        📧 Instant delivery via email after payment
                    </p>
                </div>

                <button type="submit" class="form-btn" id="submitBtn">
                    <span class="btn-text">Download Now & Pay $50</span>
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
        // Check for payment success parameter
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.get('payment') === 'success') {
            document.getElementById('paymentSuccessAlert').style.display = 'block';
            // Auto-hide after 10 seconds
            setTimeout(() => {
                document.getElementById('paymentSuccessAlert').style.display = 'none';
            }, 10000);
            // Remove the parameter from URL without page reload
            window.history.replaceState({}, document.title, window.location.pathname);
        } else if (urlParams.get('payment') === 'processing') {
            // Show processing overlay and start verification
            showProcessingOverlay();
            startPaymentVerification();
            // Remove the parameter from URL without page reload
            window.history.replaceState({}, document.title, window.location.pathname);
        }

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
            // const funding_stage = formData.get('funding_stage').trim();

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

            // if (!funding_stage) {
            //     showError('funding_stageError', 'Please select your funding stage.');
            //     hasErrors = true;
            // }

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
                        // funding_stage: funding_stage
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Check the action type from response
                    if (data.action === 'redirect_to_payment') {
                        // Email recognized, show countdown message and redirect to payment
                        showCountdownMessage(data.message, data.countdown, () => {
                            initializeFlutterwavePayment(data.download_id, data.user_email,
                                data.user_name, data.amount);
                        });
                    } else if (data.action === 'proceed_to_payment') {
                        // New user, proceed directly to payment
                        initializeFlutterwavePayment(data.download_id, data.user_email, data
                            .user_name, data.amount);
                    }
                } else {
                    // Handle different error scenarios
                    if (data.action === 'already_purchased') {
                        // User already purchased
                        showError('emailError', data.message);
                    } else if (data.errors) {
                        // Handle validation errors
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
                showError('emailError',
                    'Network error. Please check your connection and try again.');
            } finally {
                setLoading(false);
            }
        });

        // Flutterwave Payment Integration
        function initializeFlutterwavePayment(downloadId, userEmail, userName, amount) {
            FlutterwaveCheckout({
                //public_key: "FLWPUBK_TEST-68f71675ffc7bcaf905e8893d405171a-X", // Replace with your actual public key
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
                    // Immediately redirect back to page when Flutterwave returns success
                    if (data.status === 'successful') {
                        // Store payment data for verification
                        sessionStorage.setItem('ultimateFundingPayment', JSON.stringify({
                            transaction_id: data.transaction_id,
                            tx_ref: data.tx_ref,
                            downloadId: downloadId,
                            status: data.status,
                            amount: data.amount,
                            currency: data.currency
                        }));
                        // Immediate redirect with processing parameter
                        window.location.href = window.location.pathname + '?payment=processing';
                    } else {
                        // Handle failed payment
                        showError('emailError', 'Payment was not successful. Please try again.');
                    }
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
                    // Payment verified successfully - trigger countdown completion
                    completeCountdownAndRedirect();
                    console.log('Payment verified successfully');
                } else {
                    // Hide countdown and show error
                    hideFloatingCountdown();
                    showError('emailError', data.message ||
                        'Payment verification failed. Please contact support.');
                }
            } catch (error) {
                // Hide countdown and show error
                hideFloatingCountdown();
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

        function showCountdownMessage(message, seconds, callback) {
            const successElement = document.getElementById('successMessage');
            let countdown = seconds;

            // Show initial message with countdown
            const updateMessage = () => {
                successElement.textContent = `${message.replace('10s', countdown + 's')}`;
                successElement.classList.add('show');
            };

            updateMessage();

            // Start countdown
            const countdownInterval = setInterval(() => {
                countdown--;
                updateMessage();

                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    clearSuccess();
                    callback(); // Execute the callback (redirect to payment)
                }
            }, 1000);
        }

        // Floating countdown functions
        function showFloatingCountdown() {
            // Create floating countdown element
            const countdownDiv = document.createElement('div');
            countdownDiv.id = 'floatingCountdown';
            countdownDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #28a745, #20c997);
                color: white;
                padding: 15px 20px;
                border-radius: 15px;
                font-weight: bold;
                font-size: 16px;
                box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
                z-index: 10000;
                display: flex;
                align-items: center;
                gap: 10px;
                animation: slideIn 0.5s ease-out;
                min-width: 220px;
                text-align: center;
            `;

            // Add CSS animation keyframes if not already added
            if (!document.getElementById('countdownStyles')) {
                const style = document.createElement('style');
                style.id = 'countdownStyles';
                style.textContent = `
                    @keyframes slideIn {
                        from {
                            transform: translateX(100%);
                            opacity: 0;
                        }
                        to {
                            transform: translateX(0);
                            opacity: 1;
                        }
                    }
                    @keyframes pulse {
                        0%, 100% { transform: scale(1); }
                        50% { transform: scale(1.05); }
                    }
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                `;
                document.head.appendChild(style);
            }

            countdownDiv.innerHTML = `
                <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                <div>
                    <div style="font-size: 14px;">Payment Successful!</div>
                    <div id="countdownText" style="font-size: 16px; animation: pulse 1s infinite;">
                        <i class="fas fa-spinner" style="animation: spin 1s linear infinite; margin-right: 8px;"></i>
                        Verifying payment...
                    </div>
                </div>
            `;

            document.body.appendChild(countdownDiv);

            // Start verification timeout countdown (max 30 seconds)
            let maxWaitTime = 30;
            const timeoutInterval = setInterval(() => {
                maxWaitTime--;
                const countdownText = document.getElementById('countdownText');
                if (countdownText && maxWaitTime > 0) {
                    countdownText.innerHTML = `
                        <i class="fas fa-spinner" style="animation: spin 1s linear infinite; margin-right: 8px;"></i>
                        Verifying payment...
                    `;
                } else if (maxWaitTime <= 0) {
                    // Timeout - redirect anyway
                    clearInterval(timeoutInterval);
                    window.location.href = window.location.pathname + '?payment=success';
                }
            }, 1000);

            // Store interval ID for cleanup
            countdownDiv.timeoutInterval = timeoutInterval;
        }

        function completeCountdownAndRedirect() {
            const countdownDiv = document.getElementById('floatingCountdown');
            if (countdownDiv) {
                // Clear timeout interval
                if (countdownDiv.timeoutInterval) {
                    clearInterval(countdownDiv.timeoutInterval);
                }

                // Update to show 3-second countdown
                const countdownText = document.getElementById('countdownText');
                if (countdownText) {
                    let timeLeft = 3;
                    countdownText.innerHTML = `Redirecting in ${timeLeft}s`;
                    countdownText.style.animation = 'pulse 1s infinite';

                    const finalCountdown = setInterval(() => {
                        timeLeft--;
                        countdownText.textContent = timeLeft > 0 ? `Redirecting in ${timeLeft}s` :
                            'Redirecting...';

                        if (timeLeft <= 0) {
                            clearInterval(finalCountdown);
                            // Redirect to success page
                            window.location.href = window.location.pathname + '?payment=success';
                        }
                    }, 1000);
                }
            } else {
                // If countdown doesn't exist, redirect immediately
                window.location.href = window.location.pathname + '?payment=success';
            }
        }

        function hideFloatingCountdown() {
            const countdownDiv = document.getElementById('floatingCountdown');
            if (countdownDiv) {
                // Clear all intervals
                if (countdownDiv.timeoutInterval) {
                    clearInterval(countdownDiv.timeoutInterval);
                }
                // Remove the element
                countdownDiv.remove();
            }
        }

        // Processing overlay functions
        function showProcessingOverlay() {
            // Create processing overlay element
            const overlay = document.createElement('div');
            overlay.id = 'processingOverlay';
            overlay.innerHTML = `
                <div class="overlay-background"></div>
                <div class="processing-spinner">
                    <div class="spinner-icon">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <div class="processing-text">Verifying payment...</div>
                </div>
            `;
            document.body.appendChild(overlay);

            // Show with fade-in animation
            setTimeout(() => {
                overlay.classList.add('show');
            }, 100);

            return overlay;
        }

        function showSuccessCountdown() {
            const overlay = document.getElementById('processingOverlay');
            if (overlay) {
                // Update overlay to show success countdown
                const spinner = overlay.querySelector('.processing-spinner');
                if (spinner) {
                    let timeLeft = 3;
                    spinner.innerHTML = `
                        <div class="success-icon">
                            <i class="fas fa-check-circle" style="color: #4CAF50; font-size: 2rem;"></i>
                        </div>
                        <div class="countdown-text" id="countdownText">Payment Successful! Redirecting in ${timeLeft}s</div>
                    `;

                    const finalCountdown = setInterval(() => {
                        timeLeft--;
                        const countdownText = document.getElementById('countdownText');
                        if (countdownText) {
                            countdownText.textContent = timeLeft > 0 ?
                                `Payment Successful! Redirecting in ${timeLeft}s` : 'Redirecting...';
                        }

                        if (timeLeft <= 0) {
                            clearInterval(finalCountdown);
                            hideProcessingOverlay();
                            // Show success message
                            showSuccessMessage();
                        }
                    }, 1000);
                }
            }
        }

        function hideProcessingOverlay() {
            const overlay = document.getElementById('processingOverlay');
            if (overlay) {
                overlay.classList.add('fade-out');
                setTimeout(() => {
                    overlay.remove();
                }, 300);
            }
        }

        function showSuccessMessage() {
            // Create success message banner
            const successBanner = document.createElement('div');
            successBanner.id = 'successBanner';
            successBanner.innerHTML = `
                <div class="success-content">
                    <i class="fas fa-check-circle" style="color: #4CAF50; margin-right: 12px;"></i>
                    <span>Payment Successful!</span>
                </div>
            `;
            document.body.appendChild(successBanner);

            // Show with slide-down animation
            setTimeout(() => {
                successBanner.classList.add('show');
            }, 100);

            // Auto-hide after 5 seconds
            setTimeout(() => {
                successBanner.classList.add('fade-out');
                setTimeout(() => {
                    successBanner.remove();
                }, 300);
            }, 5000);
        }

        function startPaymentVerification() {
            // Get payment data from session storage
            const paymentData = JSON.parse(sessionStorage.getItem('ultimateFundingPayment') || '{}');

            console.log('Payment data from session storage:', paymentData);

            if (!paymentData.transaction_id) {
                console.error('No payment data found - stored data:', paymentData);
                console.log('Session storage contents:', sessionStorage.getItem('ultimateFundingPayment'));
                hideProcessingOverlay();
                // Fallback - still show success even if verification fails
                setTimeout(() => {
                    window.location.href = window.location.pathname + '?payment=success';
                }, 2000);
                return;
            }

            console.log('Starting verification with transaction_id:', paymentData.transaction_id, 'tx_ref:',
                paymentData.tx_ref);
            // Start verification process
            verifyPayment(paymentData.transaction_id, paymentData.tx_ref);
        }
        async function verifyPayment(transactionId, txRef) {
            try {
                console.log('Starting payment verification with:', {
                    transactionId,
                    txRef
                });

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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

                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);

                if (response.ok && data.success) {
                    // Payment verified successfully
                    console.log('Payment verification successful');
                    showSuccessCountdown();
                    // Clear stored payment data
                    sessionStorage.removeItem('ultimateFundingPayment');
                } else {
                    // Verification failed
                    console.error('Payment verification failed:', data.message);
                    hideProcessingOverlay();
                    // Still redirect to success page with error handling
                    setTimeout(() => {
                        window.location.href = window.location.pathname + '?payment=success';
                    }, 2000);
                }
            } catch (error) {
                console.error('Verification error:', error);
                hideProcessingOverlay();
                // Still redirect to success page
                setTimeout(() => {
                    window.location.href = window.location.pathname + '?payment=success';
                }, 2000);
            }
        }
    });
</script>
