@extends('layouts.master')
@section('title', 'Contact | P23 Africa')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
    <meta name="keywords"
        content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Business Consulting & Growth Solutions for Africa | P23 Africa">
    <meta property="og:image" content="{{ asset('images/contact_header.jpg') }}">
@endsection

<style>
    img {
        max-width: 100%;
    }

    .box-container {
        max-width: 1280px;
        margin: 0 auto;
    }

    .contact-section {
        background-color: #fff;
    }

    .intro-contact-container {
        background: linear-gradient(#062c25, #1d6154);

        color: #fff;
        padding: 6rem 3rem;
        border-bottom-right-radius: 5rem;
        box-shadow: 6px 8px 15px -3px rgba(0, 0, 0, 0.6);
    }

    .intro-contact-container .intro-content h2 {
        color: #20b486;
        font-size: 2.5rem;
        margin-bottom: 0.6rem;
        font-family: "GT Walsheim ProCB";
        letter-spacing: 0.8px;
    }

    .intro-contact-container .intro-content h4 {
        font-size: 1rem;
        margin-bottom: 0.35rem;
        font-family: "GT Walsheim Con";
        letter-spacing: 0.7px;
    }

    .intro-contact-container .intro-content p {
        font-size: 1rem;
        font-family: "GT Walsheim Con";
        letter-spacing: 0.6px;
        max-width: 250px;
    }

    /* ======================= Contact Information */

    .contact-info {
        padding: 2.5rem 0rem 0rem 0rem;
        margin-top: 0rem !important;
    }

    .contact-info h2.heading {
        color: #0d4036;
        display: none;
        font-family: "GT Walsheim Trial Cn Md";

    }

    .contact-info .contact-info-icons {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: -2px;
        padding: 0rem 2rem;
    }

    .contact-info-icons .icon {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: #0d4036;
        flex-direction: column;
        gap: 10px;
    }

    .contact-info-icons .icon img {
        width: 20px;
        height: 20px;
    }

    .contact-info-icons .icon p {
        font-family: "GT Walsheim Con";
    }

    .contact-info-icons .icon p a {
        text-decoration: none;
        color: #0d4036;
    }

    /* Parent form container */
    .contact-info-form {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 20px;
        margin-top: -4rem;
        /* margin-top: 30px;
  padding: 2.5rem 2rem; */
    }

    .contact-info-form__container {
        /* max-width: 450px; */
        background: linear-gradient(#1d6154, #062c25);
        padding: 50px 30px 40px 30px;
        border-radius: 15px;
        color: #ffffff;
        box-sizing: border-box;
        box-shadow: 0px 17px 15px -3px rgba(0, 0, 0, 0.5);

    }

    .contact-info-form__title {
        font-size: 20px;
        margin-bottom: 30px;
        font-family: "GT Walsheim Trial Cn Lt";
        letter-spacing: 0.5px;
        line-height: 1.2;
    }

    .contact-info-form__label {
        font-size: 14px;
        margin-top: 15px;
        display: block;
        font-family: "GT Walsheim Con";
    }

    .contact-info-form__input,
    .name-field .floating-input {
        width: 100%;
        padding: 18px 14px;
        border-radius: 10px;
        border: 1px solid #fff;
        background: transparent;
        color: #fff;
        margin-top: 5px;
        outline: none;
        font-family: "GT Walsheim Con";
    }

    .contact-info-form__input.email {
        margin-top: 20px;
    }

    input::placeholder,
    textarea::placeholder {
        color: rgba(255, 255, 255, 0.9);
        letter-spacing: 0.7px;
        font-family: "GT Walsheim Con";
        font-size: 11px;
    }

    input.email::placeholder,
    textarea::placeholder {
        opacity: 0.5;
    }

    /* NAME FIELD ONLY */
    .contact-info-form__group.name-field {
        position: relative;
        margin-top: 20px;
    }

    .name-field .floating-label {
        position: absolute;
        top: -10px;
        left: 10px;
        padding: 2px 4px;
        margin: 0px 5px;
        font-size: 12px;
        background: #19574b;
        color: #fff;
        border-radius: 50px;
        pointer-events: none;
        font-family: "GT Walsheim Con";
        opacity: 0.9;
    }

    .contact-info-form__section-title {
        margin-top: 25px;
        font-size: 16px;
        font-weight: 600;
        font-family: "GT Walsheim Con";
        letter-spacing: 0.7px;
        line-height: 1;
    }

    .contact-info-form__subtitle {
        margin-top: 5px;
        font-size: 13px;
        opacity: 0.8;
        font-family: "GT Walsheim Con";
        letter-spacing: 0.9px;
    }

    .form_group_field{
        display: grid;
        grid-template-columns:  repeat(1, 1fr);
        gap: 20px;
    }





    .country_with_phone {
  font-family: "GT Walsheim Con";

  color: #fff;
  width: 100%;
  margin-top: 30px;
}

/* Field label */
.contact-info-form__group.full-width{
    position: relative;
}

.country_with_phone .floating-label2 {
    position: absolute;
    top: -10px;
    left: 10px;
    padding: 2px 4px;
    margin: 0px 5px;
    font-size: 12px;
    background: #19574b;
    color: #fff;
    border-radius: 50px;
    pointer-events: none;
    font-family: "GT Walsheim Con";
    opacity: 0.9;

}

/* Row layout */
.country_with_phone .phone-row {
  display: flex;
  align-items: center;
  gap: 10px;
  width: 100%;
}

/* COUNTRY DROPDOWN */
.country_with_phone .country-select {
  position: relative;
  width: 70px;
  flex-shrink: 0;
  z-index: 50;
}

.country_with_phone .selected-country {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  border: 1px solid #fff;
  border-radius: 8px;
  padding: 18px 8px;
  cursor: pointer;
  transition: all 0.25s ease;
}


.country_with_phone .flag-icon {
  width: 26px;
  height: 18px;
  border-radius: 3px;
  object-fit: cover;
}

.country_with_phone .arrow {
  font-size: 11px;
  color: #ccc;
  transition: transform 0.3s ease;
}

/* Dropdown list */
.country_with_phone .country-list {
  position: absolute;
  top: calc(100% + 6px);
  left: 0;
  width: 220px;
  background: rgba(20, 20, 20, 0.98);
  border: 1px solid rgba(255, 255, 255, 0.15);
  border-radius: 8px;
  list-style: none;
  padding: 6px 0;
  margin: 0;
  opacity: 0;
  transform: translateY(-10px);
  pointer-events: none;
  transition: all 0.3s ease;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
  backdrop-filter: blur(8px);
}

.country_with_phone .country-select.open .country-list {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}

.country_with_phone .country-select.open .arrow {
  transform: rotate(180deg);
}

/* Dropdown items */
.country_with_phone .country-list li {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 12px;
  color: #fff;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s ease, padding-left 0.2s ease;
}

.country_with_phone .country-list li img {
  width: 22px;
  height: 15px;
  border-radius: 3px;
}

.country_with_phone .country-list li:hover {
  background: rgba(0, 191, 166, 0.2);
  padding-left: 16px;
}

/* PHONE INPUT */
.country_with_phone .phone-input-wrapper {
  display: flex;
  align-items: center;
  width: 100%;
  border: 1px solid #fff;
  border-radius: 8px;
  padding: 18px 10px;
  transition: border-color 0.25s ease;
  position: relative;
}

.phone-input-wrapper .floating-phone {
    position: absolute;
    top: -10px;
    left: 10px;
    padding: 2px 4px;
    margin: 0px 5px;
    font-size: 12px;
    background: #19574b;
    color: #fff;
    border-radius: 50px;
    pointer-events: none;
    font-family: "GT Walsheim Con";
    opacity: 0.9;

}



.country_with_phone .prefix {
  font-size: 15px;
  color: #fff;
  margin-right: 8px;
  opacity: 0.85;
}

.country_with_phone .floating-input.phone {
  width: 100%;
  background: transparent;
  border: none;
  outline: none;
  color: #fff;
  font-size: 15px;
}




















    .contact-info-form__tags {
        margin-top: 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-family: "GT Walsheim Con";
    }

    .tag {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 30px;
        background: #ffffff;
        color: #1d6154;
        border-radius: 25px;
        cursor: pointer;
        font-size: 11.6px;
        transition: all 0.25s ease;
        border: 1px solid transparent;
    }

    .tag.selected {
        color: #062c25;
    }

    .tag-icon {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }

    .contact-info-form__textarea {
        width: 100%;
        height: 130px;
        margin-top: 50px;
        padding: 15px;
        border-radius: 12px;
        border: 1px solid #fff;
        background: transparent;
        color: #fff;
        outline: none;
        resize: none;
    }

    .contact-info-form__btn {
        width: 100%;
        margin-top: 25px;
        background: #22c58b;
        color: #fff;
        padding: 14px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        transition: 0.25s ease;
    }

    .contact-info-form__btn:hover {
        opacity: 0.9;
    }

    .contact-info-location {
        padding: 0 2rem;
        margin-top: 52px
    }

    .contact-info-location .heading {
        color: #0d4036;
        font-size: 18px;
        line-height: 1.2;
        font-family: "GT Walsheim Trial Cn Md";
        font-weight: 800;
        letter-spacing: 0.8px;
        margin-bottom: 20px;


    }

    .contact-info-location .location-content {
        margin: 10px 0px;
        display: flex;
        flex-direction: column;
        gap: 1px;

    }

    .contact-info-location p {
        font-family: "GT Walsheim Con";
        letter-spacing: 0.5px;
        line-height: 0.4;

    }

    .contact-info-location .location-map {
        box-shadow: 0px 17px 15px -3px rgba(0, 0, 0, 0.5);
        margin-top: 25px;
    }

    .contact-info-location .location-map iframe {
        height: 350px;
    }

    /* ============================= Social */
    .contact-info-socials {
        padding: 0 2rem;
        margin-top: 40px;
        padding-bottom: 2rem;
    }

    .contact-info-socials h3 {
        color: #0d4036;
        font-size: 18px;
        line-height: 1.2;
        font-family: "GT Walsheim Trial Cn Md";
        font-weight: 800;
        letter-spacing: 0.8px;
    }

    .contact-info-socials .socials {
        margin: 10px 0px;
        display: flex;
        flex-direction: row;
        gap: 12px;
        color: #0d4036;
    }

    .contact-info-socials .socials a {
        color: #0d4036;
        font-size: 20px;
        text-decoration: none;
    }

    .intro-vrt-image {
        display: none;
    }

    /* ================================================ NewsLetter */
    .news-letter {
        background-color: #094b5d;
        /* Dark Teal background */

        padding: 30px 40px;
        border-radius: 5px;
        margin: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        display: none;
    }

    .contact-news-letter img {
        display: none;
    }

    @media (min-width: 768px) {

        /* ======================= Contact Information */
        .contact-info h2.heading {
            display: block;
            padding: 0 2rem;

            margin-bottom: 20px;
        }
    }

    @media (min-width: 1200px) {
        /* ======================= Contact Information */

        .box-container {
            max-width: 1070px;
            margin: 0 auto;
        }

        .contact-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }

        .intro-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            margin-top: 6rem;
        }

        .intro-contact-container {
            border-bottom-right-radius: 0rem;
            box-shadow: none;
        }

        .intro-contact-container .intro-content h4 {
            font-family: "GT Walsheim Trial Cn Lt";
        }

        .intro-contact-container .intro-content p {
            font-size: 1.3rem;
            font-family: "GT Walsheim ProCB";

            max-width: initial;
        }

     
       
        .contact-info-location {
            padding: 2rem 2rem;
            margin-top: 18rem;
        }

        .contact-info .contact-info-icons {
            padding: 0;
            max-width: 420px;
        }

        .contact-info h2.heading {
            padding: 0;
        }

        .intro-container {
            position: relative;
        }

        .intro-vrt-image {
            display: block;
            max-height: 8vh;
            position: relative;
            width: 400px;
            height: 200px;
            margin-left: 60px;
            margin-top: -5rem;
        }

        .intro-vrt-image img {
            position: absolute;
            object-fit: cover;
        }

        .contact-info-form__container {
            padding: 10px 40px;

        }

        .contact-info-form {
            padding: 0;
            /* padding-top: 2rem; */
        }
        
    .form_group_field{
        grid-template-columns:  repeat(2, 1fr);
        gap: 20px;
    }

        .tag {
            gap: 15px;
            padding: 8px 30px;

            font-size: 15px;
        }

        .tag-icon {
            width: 28px;
            height: 28px;
        }

        .contact-info-location .location-content p {
            /* line-height: 0.9; */
        }

        .contact-info-location,
        .contact-info-socials {
            margin-left: 34px;
        }

        .contact-info-socials {
            margin-top: 0px;
        }

        .contact-info-location .location-map iframe {
            height: 270px;
        }

        .contact-info-icons .icon p a,
        .contact-info-icons .icon p {
            color: #000;
            font-weight: 500;
        }

        .contact-info-icons .icon.phone p a {
            text-decoration: underline;
            letter-spacing: 0.7px;
            font-weight: 600;
        }

        /* ======================================================== NewsLetter */
        .news-letter {
            display: block;
            z-index: 9999;
        }

        .contact-news-letter {
            margin-top: 60px;

        }

        .contact-news-letter-container {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
        }

        .contact-news-letter__content {
            flex-shrink: 0;
            margin-right: 80px;
        }

        .contact-news-letter__title {
            font-size: 4.6em;
            font-family: "GT Walsheim ProCB";
            color: white;
            font-weight: 700;
            margin: 0;
            line-height: 1;
        }

        .contact-news-letter__text {
            font-size: 1.1em;
            font-weight: 300;
            color: rgba(255, 255, 255, 0.695);
            font-family: "GT Walsheim Trial Cn Lt";
            letter-spacing: 12px;
        }

        /* Styling for the form area */
        .contact-news-letter__form {
            display: flex;
            flex-grow: 1;
            background-color: #f9f7f6;
            box-shadow: 3px 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-radius: 7px;
            overflow: hidden;
        }

        /* Styling for the email input field */
        .contact-news-letter__input {
            flex-grow: 1;
            padding: 15px 20px;
            border: none;
            outline: none;
            background-color: transparent;
            font-size: 1em;
            color: #333;
        }

        .contact-news-letter__input::placeholder {
            color: #0d4036;
        }

        /* Styling for the Subscribe button */
        .contact-news-letter__button {
            background-color: #0d4036;
            /* Dark Green button */
            color: white;
            border-top-left-radius: 7px;
            border-bottom-left-radius: 7px;
            border: none;
            padding: 15px 40px;
            cursor: pointer;
            font-size: 12px;
            letter-spacing: 0.8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            font-family: "GT Walsheim Con";
            flex-shrink: 0;
        }

        .contact-news-letter__button:hover {
            background-color: #0d4036f2;
        }

        .contact-news-letter {
            position: relative;
        }

        .contact-news-letter img {
            display: block;
            position: absolute;
            top: -120px;
            width: 250px;
            height: 250px;
            opacity: 0.8;
        }

        .contact-news-letter .left-pattern {
            position: absolute;
            left: 0px;
            z-index: -1;
        }

        .contact-news-letter .right-pattern {
            position: absolute;
            right: 0px;
        }
    }

    /* Error styles */
    .error {
        border-color: #e74c3c !important;
        box-shadow: 0 0 5px rgba(231, 76, 60, 0.3) !important;
    }

    .error-message {
        color: #e74c3c;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .alert {
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        border: 1px solid;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    /* Spinner styles */
    .spinner {
        display: none;
        opacity: 0.7;
    }

    .contact-info-form__btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Popup Modal Styles */
    .popup-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 10000;
        animation: fadeIn 0.3s ease-out;
    }

    .popup-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        border-radius: 15px;
        padding: 40px 30px;
        max-width: 450px;
        width: 90%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        animation: slideIn 0.3s ease-out;
        text-align: center;
    }

    .popup-modal.success {
        border-top: 4px solid #22c58b;
    }

    .popup-modal.error {
        border-top: 4px solid #e74c3c;
    }

    .popup-icon {
        font-size: 48px;
        margin-bottom: 20px;
    }

    .popup-icon.success {
        color: #22c58b;
    }

    .popup-icon.error {
        color: #e74c3c;
    }

    .popup-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #0d4036;
        font-family: "GT Walsheim ProCB";
    }

    .popup-message {
        font-size: 16px;
        color: #666;
        margin-bottom: 25px;
        line-height: 1.5;
        font-family: "GT Walsheim Con";
    }

    .popup-close-btn {
        background: #0d4036;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-family: "GT Walsheim Con";
    }

    .popup-close-btn:hover {
        background: #062c25;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translate(-50%, -60%);
        }

        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }
</style>


@section('content')
    <section class="whole-contact-section">
        <div class="contact-container">
            <!-- Intro Container -->
            <div class="intro-contact-container">
                <div class="box-container">
                    <div class="intro-container animate-on-scroll">
                        <!--COntent -->
                        <div class="intro-content">
                            <h2>Contact Us</h2>
                            <h4>Ready to expand your business?</h4>
                            <p>Contact P23 Africa and let’s make growth happen.</p>
                        </div>
                        <!-- Vertical desktop image -->
                        <div class="intro-vrt-image">

                            {{-- <!-- src="{{ asset('./img/card3.png') }}" loading="lazy" alt=""  --> --}}
                            <img src="{{ asset('./img/contact-img.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-container">
            <div class="contact-row">
                <div class="left-row animate-on-scroll">
                    <!--  -->
                    <div class="contact-info">
                        <h2 class="heading">Contact Information</h2>
                        <div class="contact-info-icons ">
                            <div class="icon phone">
                                <img src="{{ asset('./img/phone.png') }}" loading="lazy"src="{{ asset('./img/phone.png') }}"
                                    loading="lazy" alt="" />
                                <p><a href="tel:+447535659835"> +44 7535 659835</a></p>
                            </div>
                            <div class="icon email">
                                <img src="{{ asset('./img/email.png') }}" loading="lazy" alt="" />
                                <p>hello@p23africa.com</p>
                            </div>
                            <div class="icon location">
                                <img src="{{ asset('./img/address.png') }}" loading="lazy" alt="" />
                                <p>London UK</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="contact-info-form ">
                        <form class="contact-info-form__container" action="{{ route('contact.submit') }}" method="POST"
                            id="contactForm">
                            @csrf
                            <h2 class="contact-info-form__title">Leave us a message</h2>

                            <div class="contact-info-form__group name-field">
                                <label class="floating-label">Full Name</label>
                                <input type="text" name="name" class="floating-input @error('name') error @enderror"
                                    value="{{ old('name') }}" required />
                                @error('name')
                                    <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form_group_field">
                               
                            <div class="contact-info-form__group name-field">
                                    <label class="floating-label">Email</label>

                                <input type="email" name="email"
                                class="floating-input @error('email') error @enderror"
                           value="{{ old('email') }}" required />
                            @error('email')
                                <div class="error-message"
                                    style="color: #e74c3c; font-size: 12px; margin-top: -10px; margin-bottom: 10px;">
                                    {{ $message }}</div>
                            @enderror
                                </div>
                               

                            <div class="contact-info-form__group name-field">
                                <label class="floating-label">Company Name</label>
                                <input type="text" name="company_name"
                                    class="floating-input @error('company_name') error @enderror"
                                     value="{{ old('company_name') }}" />
                                @error('company_name')
                                    <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                            </div>

                            <!-- <div class="form-group2 field">
    <div class="contact-info-form__group name-field">
                                <label class="floating-label">Phone Number</label>
                                <input type="tel" name="phone" class="floating-input @error('phone') error @enderror"
                                    placeholder="+1234567890" value="{{ old('phone') }}" />
                                @error('phone')
                                    <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                            </div> -->

                                 <div class="form-field2 country_with_phone">
                  <div class="contact-info-form__group full-width">
                    <label class="floating-label2">Country</label>
                    <div class="phone-row">
                      <!-- Country Flag Dropdown -->
                      <div class="country-select" id="countrySelect">
                        <div class="selected-country">
                          <img
                            src="https://flagcdn.com/w40/ng.png"
                            alt="flag"
                            class="flag-icon"
                          />
                          <span class="arrow">▼</span>
                        </div>

                        <ul class="country-list">
                          <li data-code="+1" data-flag="us">
                            <img src="https://flagcdn.com/w40/us.png" alt="" />
                            United States (+1)
                          </li>
                          <li data-code="+44" data-flag="gb">
                            <img src="https://flagcdn.com/w40/gb.png" alt="" />
                            United Kingdom (+44)
                          </li>
                          <li data-code="+27" data-flag="za">
                            <img src="https://flagcdn.com/w40/za.png" alt="" />
                            South Africa (+27)
                          </li>
                          <li data-code="+234" data-flag="ng">
                            <img src="https://flagcdn.com/w40/ng.png" alt="" />
                            Nigeria (+234)
                          </li>
                          <li data-code="+91" data-flag="in">
                            <img src="https://flagcdn.com/w40/in.png" alt="" />
                            India (+91)
                          </li>
                          <li data-code="+61" data-flag="au">
                            <img src="https://flagcdn.com/w40/au.png" alt="" />
                            Australia (+61)
                          </li>
                        </ul>
                      </div>

                      <!-- Phone Input -->
                      <div class="phone-input-wrapper">
                                <label class="floating-phone">Phone Number</label>
                        <span class="prefix" id="phonePrefix">+234</span>
                        <input
                          type="tel"
                          id="phone"
                          class="floating-input phone"
                          placeholder="Enter phone number"
                        />
                      </div>
                    </div>
                  </div>
                </div>
                        

                            <div class="contact-info-form__section-title">
                                What service do you require
                            </div>
                            <p class="contact-info-form__subtitle">
                                Select from the options
                            </p>

                            <div class="contact-info-form__tags">
                                <div class="tag" data-value="Advise">
                                    <img class="tag-icon" src="{{ asset('img/unselected.png') }}" alt="" />
                                    Advise
                                </div>

                                <div class="tag" data-value="Marketing">
                                    <img class="tag-icon" src="{{ asset('img/unselected.png') }}" alt="" />
                                    Marketing
                                </div>

                                <div class="tag" data-value="Sales">
                                    <img class="tag-icon" src="{{ asset('img/unselected.png') }}" alt="" />
                                    Sales
                                </div>

                                <div class="tag" data-value="Launch">
                                    <img class="tag-icon" src="{{ asset('img/unselected.png') }}" alt="" />
                                    Launch
                                </div>

                                <div class="tag" data-value="Mentorship">
                                    <img class="tag-icon" src="{{ asset('img/unselected.png') }}" alt="" />
                                    Mentorship
                                </div>

                                <div class="tag" data-value="Operations">
                                    <img class="tag-icon" src="{{ asset('img/unselected.png') }}" alt="" />
                                    Operations
                                </div>
                            </div>

                            <input type="hidden" name="services" id="selected-services"
                                value="{{ old('services') }}" />
                            @error('services')
                                <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                    {{ $message }}</div>
                            @enderror

                            <textarea name="message" class="contact-info-form__textarea @error('message') error @enderror"
                                placeholder="Add additional message here" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                    {{ $message }}</div>
                            @enderror

                            <!-- reCAPTCHA -->
                            <div class="g-recaptcha" data-sitekey="6LeFmtMrAAAAAKQ4SHr9Z9NdNPyjwmljCV-iMUo6"
                                style="margin-top: 20px;">
                            </div>
                            @error('g-recaptcha-response')
                                <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                    {{ $message }}</div>
                            @enderror

                            <button type="submit" class="contact-info-form__btn" id="submitBtn">
                                <span class="btn-text">Send</span>
                                <span class="spinner" style="display: none;">Sending...</span>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="right-row animate-on-scroll">
                    <!-- Location -->

                    <div class="contact-info-location">
                        <h3 class="heading">Location</h3>
                        <div class="location-content">
                            <p>185 Tower Bridge Rd</p>
                            <p>185 Tower Bridge Rd, London SE1 2UF, UK</p>
                        </div>

                        <div class="location-map">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2483.5780880978436!2d-0.08011252412324342!3d51.50260951105425!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48760345c64c3edb%3A0x617596184d03a84c!2s185%20Tower%20Bridge%20Rd%2C%20London%20SE1%202UF%2C%20UK!5e0!3m2!1sen!2sng!4v1746443001887!5m2!1sen!2sng"
                                width="100%" style="border: 0" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="contact-info-socials">
                        <h3>Social Media</h3>

                        <div class="socials">
                            <a href="https://linkedin.com/company/p23-africa/" target="_blank"
                                class="fa-brands fa-linkedin mx-1"></a>
                            <a href="https://facebook.com/p23africa" target="_blank"
                                class="fa-brands fa-square-facebook mx-1"></a>
                            <a href="https://www.instagram.com/p23africa/" target="_blank"
                                class="fa-brands fa-square-instagram mx-1"></a>
                            <a href="#" class="fa-brands fa-youtube mx-1"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Letter -->
        <div class="contact-news-letter">
            <img src="./img/contact-left-pattern.png" class="left-pattern" alt="" />
            <div class="news-letter">
                <div class="contact-news-letter-container">
                    <div class="contact-news-letter__content">
                        <h2 class="contact-news-letter__title">Subscribe</h2>
                        <p class="contact-news-letter__text">to our Newsletter</p>
                    </div>
                    <form class="contact-news-letter__form">
                        <input type="email" placeholder="Email" class="contact-news-letter__input" required />
                        <button type="submit" class="contact-news-letter__button">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
            <img src="./img/contact-right-pattern.png" class="right-pattern" alt="" />
        </div>
    </section>

    <!-- Popup Modal -->
    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-modal" id="popupModal">
            <div class="popup-icon" id="popupIcon">
                <!-- Icon will be added by JavaScript -->
            </div>
            <h3 class="popup-title" id="popupTitle">
                <!-- Title will be added by JavaScript -->
            </h3>
            <p class="popup-message" id="popupMessage">
                <!-- Message will be added by JavaScript -->
            </p>
            <button class="popup-close-btn" id="popupCloseBtn">
                Close
            </button>
        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tags = document.querySelectorAll(".tag");
            const hiddenInput = document.getElementById("selected-services");
            const form = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const spinner = submitBtn.querySelector('.spinner');

            // Initialize with old values if present
            const oldServices = hiddenInput.value;
            if (oldServices) {
                const selectedServices = oldServices.split(',');
                tags.forEach((tag) => {
                    const tagValue = tag.getAttribute("data-value");
                    if (selectedServices.includes(tagValue)) {
                        tag.classList.add("selected");
                        tag.querySelector(".tag-icon").src = "{{ asset('img/selected.png') }}";
                    }
                });
            }

            tags.forEach((tag) => {
                tag.addEventListener("click", () => {
                    const isSelected = tag.classList.contains("selected");

                    // Toggle clicked tag
                    if (isSelected) {
                        tag.classList.remove("selected");
                        tag.querySelector(".tag-icon").src = "{{ asset('img/unselected.png') }}";
                    } else {
                        tag.classList.add("selected");
                        tag.querySelector(".tag-icon").src = "{{ asset('img/selected.png') }}";
                    }

                    // Collect all selected tag values
                    const selectedValues = Array.from(
                        document.querySelectorAll(".tag.selected")
                    ).map((t) => t.getAttribute("data-value"));

                    // Update hidden input value
                    hiddenInput.value = selectedValues.join(",");
                });
            });

            // Popup functions
            function showPopup(type, title, message) {
                const overlay = document.getElementById('popupOverlay');
                const modal = document.getElementById('popupModal');
                const icon = document.getElementById('popupIcon');
                const titleEl = document.getElementById('popupTitle');
                const messageEl = document.getElementById('popupMessage');

                // Set content
                titleEl.textContent = title;
                messageEl.textContent = message;

                // Set type-specific styles and icons
                if (type === 'success') {
                    modal.className = 'popup-modal success';
                    icon.className = 'popup-icon success';
                    icon.innerHTML = '✓';
                } else {
                    modal.className = 'popup-modal error';
                    icon.className = 'popup-icon error';
                    icon.innerHTML = '✕';
                }

                // Show popup
                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            function hidePopup() {
                const overlay = document.getElementById('popupOverlay');
                overlay.style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            // Close popup event listeners
            document.getElementById('popupCloseBtn').addEventListener('click', hidePopup);
            document.getElementById('popupOverlay').addEventListener('click', function(e) {
                if (e.target === this) {
                    hidePopup();
                }
            });

            // Form submission handling with AJAX
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                submitBtn.disabled = true;
                btnText.style.display = 'none';
                spinner.style.display = 'inline';

                // Basic validation
                const selectedValues = hiddenInput.value;
                if (!selectedValues || selectedValues.trim() === '') {
                    resetButtonState();
                    showPopup('error', 'Validation Error', 'Please select at least one service.');
                    return;
                }

                // Check reCAPTCHA
                if (typeof grecaptcha !== 'undefined') {
                    const recaptchaResponse = grecaptcha.getResponse();
                    if (recaptchaResponse.length === 0) {
                        resetButtonState();
                        showPopup('error', 'Validation Error',
                            'Please complete the reCAPTCHA verification.');
                        return;
                    }
                }

                // Prepare form data
                const formData = new FormData(form);

                // Submit form via AJAX
                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        resetButtonState();

                        if (data.success) {
                            showPopup('success', 'Message Sent!',
                                'Thank you for contacting us. We will get back to you soon!');
                            form.reset();
                            hiddenInput.value = '';
                            // Reset selected tags
                            document.querySelectorAll('.tag.selected').forEach(tag => {
                                tag.classList.remove('selected');
                                tag.querySelector('.tag-icon').src =
                                    "{{ asset('img/unselected.png') }}";
                            });
                            // Reset reCAPTCHA
                            if (typeof grecaptcha !== 'undefined') {
                                grecaptcha.reset();
                            }
                        } else {
                            const errorMessage = data.message ||
                                'There was an error sending your message. Please try again.';
                            showPopup('error', 'Error', errorMessage);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        resetButtonState();
                        showPopup('error', 'Error',
                            'There was an error sending your message. Please try again.');
                    });
            });

            function resetButtonState() {
                submitBtn.disabled = false;
                btnText.style.display = 'inline';
                spinner.style.display = 'none';
            }

            // Check for server-side messages on page load
            @if (session('success'))
                showPopup('success', 'Message Sent!', '{{ session('success') }}');
            @endif

            @if (session('error'))
                showPopup('error', 'Error', '{{ session('error') }}');
            @endif





















              const countrySelect = document.getElementById("countrySelect");
      const selectedCountry = countrySelect.querySelector(".selected-country");
      const selectedImg = selectedCountry.querySelector(".flag-icon");
      const countryList = countrySelect.querySelector(".country-list");
      const phonePrefix = document.getElementById("phonePrefix");

      // Toggle dropdown
      selectedCountry.addEventListener("click", (e) => {
        e.stopPropagation();
        countrySelect.classList.toggle("open");
      });

      // Handle country selection
      countryList.querySelectorAll("li").forEach((item) => {
        item.addEventListener("click", () => {
          const flag = item.dataset.flag;
          const code = item.dataset.code;

          selectedImg.src = `https://flagcdn.com/w40/${flag}.png`;
          phonePrefix.textContent = code;

          // Animate close
          countryList.style.opacity = "0";
          countryList.style.transform = "translateY(-8px)";
          setTimeout(() => {
            countrySelect.classList.remove("open");
            countryList.removeAttribute("style");
          }, 200);
        });
      });

      // Click outside closes dropdown
      document.addEventListener("click", (e) => {
        if (!countrySelect.contains(e.target)) {
          countryList.style.opacity = "0";
          countryList.style.transform = "translateY(-8px)";
          setTimeout(() => {
            countrySelect.classList.remove("open");
            countryList.removeAttribute("style");
          }, 200);
        }
      });
        });
    </script>
@endsection
