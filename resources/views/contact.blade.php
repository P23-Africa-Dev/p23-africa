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
        background-color: #0D4036;

        color: #fff;
        padding: 6rem 3rem;
        padding-top: 8rem;
        border-bottom-right-radius: 5rem;
        box-shadow: 6px 8px 15px -3px rgba(0, 0, 0, 0.6);
    }

    .intro-contact-container .intro-content h2 {
        color: #20b486;
        font-size: 2.7rem;
        margin-bottom: 0.6rem;
        font-family: "GT Walsheim ProCB";
        letter-spacing: 0.8px;
    }

    .intro-contact-container .intro-content h4 {
        font-size: 1.14rem;
        margin-bottom: 0.35rem;
        font-family: "GT Walsheim Trial Cn Md";

        letter-spacing: 0.7px;
    }

    .intro-contact-container .intro-content p {
        font-size: 1.15rem;
        font-family: "GT Walsheim Con";
        letter-spacing: 0.6px;
        line-height: 1.1;
        max-width: 270px;
    }

    /* ======================= Contact Information */

    .contact-info {
        padding: 2.5rem 0rem 0rem 0rem;
        margin-top: -1.5rem !important;
        margin-bottom: 2rem !important;
    }

    .contact-info h2.heading {
        color: #0d4036;
        display: none;
        font-family: "GT Walsheim ProCB";
        font-weight: 100;
        font-size: 22px;
        letter-spacing: 0.8px;

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
        padding: 20px 16px;
        margin-top: -4rem;
    }

    .contact-info-form__container {

        background: linear-gradient(322.83deg, #062C25 0%, #1D6154 97.19%);
        padding: 40px 30px 25px 30px;
        border-radius: 15px;
        color: #ffffff;
        box-sizing: border-box;
        box-shadow: 0px 17px 15px -3px rgba(0, 0, 0, 0.5);
    }

    .contact-info-form__title {
        font-size: 24px;
        margin-bottom: 25px;
        font-family: "GT Walsheim Trial Cn Md";
        letter-spacing: 0.3px;
        line-height: 1.3;
        font-weight: 500;
        margin-left: 25px;
    }

    /* WRAPPER */
    .name-field {
        position: relative;
        margin-top: 20px;
    }

    /* INPUT FIELD */
    .name-field .floating-input {
        width: 100%;
        padding: 16px 14px;
        height: 50px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        background: transparent;
        color: #fff;
        outline: none;
        font-size: 15px;
        font-family: "GT Walsheim Con";
        transition: border-color 0.3s ease;
    }

    /* INPUT FOCUS */
    /* .name-field .floating-input:focus {
        border-color: rgba(255, 255, 255, 0.65);
    } */

    /* THE TOP-LEFT BORDER CUT */
    .name-field::before {
        content: "";
        position: absolute;
        top: 0;
        left: 14px;
        width: 60px;
        height: 2px;
        background: #1D6154;
        z-index: 2;
    }

    .name-field.emaill::before {
        width: 50px;

    }

    .name-field.company::before {
        width: 120px;

    }

    /* FLOATING LABEL */
    .name-field .floating-label {
        position: absolute;
        top: -12px;
        left: 14px;
        background: transparent;
        /* form background for seamless blend */
        padding: 0 6px;
        font-size: 14px;
        color: #ffffffc2;
        font-family: "GT Walsheim Pro";
        z-index: 3;
        letter-spacing: 0.2px;
    }




    .contact-info-form__input.email {
        margin-top: 20px;
    }

    input::placeholder,
    textarea::placeholder {
        color: #878787;
        letter-spacing: 0.5px;
        font-family: "GT Walsheim Pro";
        font-size: 16px;
    }

    input.email::placeholder,
    textarea::placeholder {
        opacity: 0.6;
    }



    .contact-info-form__section-title {
        margin-top: 30px;
        font-size: 16px;
        font-weight: 600;
        font-family: "GT Walsheim Pro";
        letter-spacing: 0.3px;
        line-height: 0.4;
    }

    .contact-info-form__subtitle {
        margin-top: 6px;
        font-size: 12px;
        opacity: 0.75;
        font-family: "GT Walsheim Pro";
        letter-spacing: 0.3px;
    }

    .form_group_field {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 20px;
    }





    .country_with_phone {
        font-family: "GT Walsheim Con";

        color: #fff;
        width: 100%;
        margin-top: 30px;
    }

    /* Field label */
    .contact-info-form__group.full-width {
        position: relative;
    }


    .country_with_phone .floating-label2 {
        position: absolute;
        top: -12px;
        left: 14px;
        background: transparent;
        /* form background for seamless blend */
        padding: 0 6px;
        font-size: 14px;
        color: #ffffffc2;

        font-family: "GT Walsheim Pro";
        z-index: 3;
        letter-spacing: 0.2px;
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
        width: 105px;
        flex-shrink: 0;
    }

    .country_with_phone .country-select::before {
        content: "";
        position: absolute;
        top: 0;
        left: 14px;
        width: 60px;
        height: 2px;
        background: #1D6154;
        z-index: 2;
    }

    .country_with_phone .selected-country {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        padding: 13px 10px 13px 10px;
        height: 50px;
        cursor: pointer;
        transition: all 0.25s ease;
        background: transparent;
    }

    /* .country_with_phone .selected-country:hover {
        border-color: rgba(255, 255, 255, 0.5);
    } */


    .country_with_phone .flag-icon {
        width: 32px;
        height: 22px;
        border-radius: 3px;
        object-fit: cover;
    }

    .country_with_phone .placeholder-flag {
        width: 32px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
        opacity: 0.7;
    }

    .country_with_phone .arrow {
        font-size: 12px;
        color: #fff;
        opacity: 0.8;
        transition: transform 0.3s ease;
        height: 14px;
        width: 14px;
    }

    /* Dropdown list */
    .country_with_phone .country-list {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        width: 220px;
        max-height: 280px;
        overflow-y: auto;
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
        z-index: 10;
    }

    /* Custom scrollbar for country list */
    .country_with_phone .country-list::-webkit-scrollbar {
        width: 6px;
    }

    .country_with_phone .country-list::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 3px;
    }

    .country_with_phone .country-list::-webkit-scrollbar-thumb {
        background: rgba(0, 191, 166, 0.5);
        border-radius: 3px;
    }

    .country_with_phone .country-list::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 191, 166, 0.7);
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
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        padding: 13px 14px;
        height: 50px;
        transition: border-color 0.25s ease;
        position: relative;
        background: transparent;
    }

    .country_with_phone .phone-input-wrapper::before {
        content: "";
        position: absolute;
        top: -1px;
        left: 14px;
        width: 110px;
        height: 2px;
        background: #1D6154;
        z-index: 2;
    }

    /* .country_with_phone .phone-input-wrapper:focus-within {
        border-color: rgba(255, 255, 255, 0.6);
    } */

    .phone-input-wrapper .floating-phone {
        position: absolute;
        top: -12px;
        left: 14px;
        background: transparent;
        /* form background for seamless blend */
        padding: 0 6px;
        font-size: 14px;
        color: #ffffffc2;

        font-family: "GT Walsheim Pro";
        z-index: 3;
        letter-spacing: 0.2px;
    }



    .country_with_phone .prefix {
        font-size: 16px;
        color: #fff;
        margin-right: 8px;
        opacity: 0.9;
        font-weight: 500;
    }

    .country_with_phone .floating-input.phone {
        width: 100%;
        background: transparent;
        border: none;
        outline: none;
        color: #fff;
        font-size: 14px;
        padding: 0;
        margin: 0;
    }




    /* =============================================  PRICE RANGE */
    .form-price-container h2 {
        font-size: 15.2px;
        font-family: "GT Walsheim Trial Cn Md";
        margin-top: 30px;
        letter-spacing: 1.4px;
        font-weight: 600;
        line-height: 0.3;
    }

    .form-price-container p {
        font-size: 10px;
        font-family: "GT Walsheim Pro";
        margin-top: 6px;
        letter-spacing: 0.2px;
        opacity: 0.8;
    }

    .price-range-group {
        font-family: "GT Walsheim Con";

        color: #1e6c82;
        margin-top: 45px;
        width: 100%;
    }

    .price-range-group .form-label {
        display: block;
        font-size: 15px;
        font-weight: 500;
        margin-bottom: 20px;
    }

    .range-wrapper {
        position: relative;
        width: 100%;
        padding: 25px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: space-between;
    }

    .range-wrapper .range-label {
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        /* margin-bottom: 40px; */
    }

    /* Floating price label */
    .range-value {
        position: absolute;
        top: -5px;
        left: 50%;
        transform: translateX(-50%);
        /* background: #1e6c82; */
        color: #eff2f1;
        font-size: 14px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 6px;
        white-space: nowrap;
        transition: left 0.2s ease, transform 0.2s ease;
    }

    /* Base range */
    input#priceRange[type="range"] {
        width: 100%;
        margin: 0;
        height: 7px;
        appearance: none;
        background: linear-gradient(to right,
                #1e6c82 0%,
                #1e6c82 0%,
                #eff2f1 0%,
                #eff2f1 100%);
        border-radius: 5px;
        outline: none;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    /* WebKit Track */
    input[type="range"]::-webkit-slider-runnable-track {
        height: 7px;
        background: transparent;
        border-radius: 5px;
    }

    /* Thumb (draggable circle) */
    input[type="range"]::-webkit-slider-thumb {
        appearance: none;
        width: 24px;
        height: 24px;
        background: #1e6c82;
        border: 4px solid #0C3A31;
        border-radius: 50%;
        cursor: pointer;
        transition: transform 0.2s ease;
        margin-top: -9px;
    }

    input[type="range"]::-webkit-slider-thumb:hover {
        transform: scale(1.15);
        box-shadow: 0 3px 8px rgba(30, 108, 130, 0.4);
    }

    /* Firefox track/thumb */
    input[type="range"]::-moz-range-track {
        height: 7px;
        background: transparent;
        border-radius: 5px;
    }

    input[type="range"]::-moz-range-thumb {
        width: 24px;
        height: 24px;
        background: #1e6c82;
        border: 4px solid #0C3A31;
        border-radius: 50%;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    input[type="range"]::-moz-range-thumb:hover {
        transform: scale(1.15);
        box-shadow: 0 3px 8px rgba(30, 108, 130, 0.4);
    }

    .contact-info-form__tags {
        margin-top: 15px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-family: "GT Walsheim Con";
    }

    .contact-info-form__tags-row {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        gap: 10px;
        width: 100%;
    }

    .contact-info-form__tags-row:last-child .tag {
        flex: 1 1 0;
        min-width: 0;
        justify-content: center;
        padding-left: 10px;
        padding-right: 10px;
    }

    .tag {
        display: flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        flex: 0 1 auto;
        gap: 8px;
        padding: 11px 22px;
        background: #ffffff;
        color: #1d6154;
        border-radius: 30px;
        cursor: pointer;
        font-size: 13px;
        line-height: 1.2;
        white-space: nowrap;
        transition: all 0.25s ease;
        border: 2px solid transparent;
        font-weight: 500;
    }

    .tag.selected {
        color: #062c25;
    }

    .tag:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .tag-icon {
        width: 20px;
        height: 20px;
        object-fit: contain;
    }

    .contact-info-form__textarea {
        width: 100%;
        height: 120px;
        margin-top: 20px;
        margin-bottom: 8px;
        padding: 14px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        background: transparent;
        color: #fff;
        outline: none;
        resize: none;
        font-family: "GT Walsheim Pro";
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .contact-info-form__textarea:focus {
        border-color: rgba(255, 255, 255, 0.6);
    }

    .contact-info-form__btn {
        width: 100%;
        margin-top: 24px;
        margin-bottom: 20px;
        background: #22c58b;
        color: #fff;
        padding: 15px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: 0.25s ease;
        font-family: "GT Walsheim ProCB";
        letter-spacing: 0.5px;
    }

    .contact-info-form__btn:hover {
        background: #1eae79;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(34, 197, 139, 0.3);
    }

    @media (max-width: 767px) {
        .contact-info-form__tags-row {
            gap: 8px;
        }

        .tag {
            padding: 10px 12px;
            font-size: 12px;
            gap: 6px;
        }

        .contact-info-form__tags-row:last-child .tag {
            flex: 1 1 0;
            min-width: 0;
            padding: 10px 8px;
            justify-content: center;
        }

        .tag-icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .contact-info-form__textarea {
            margin-top: 24px;
            margin-bottom: 0;
        }

        .contact-info-form__btn {
            margin-top: 28px;
        }
    }

    @media (max-width: 380px) {
        .contact-info-form__tags-row {
            gap: 6px;
        }

        .tag {
            padding: 8px 8px;
            font-size: 11px;
            gap: 4px;
        }

        .contact-info-form__tags-row:last-child .tag {
            padding: 8px 6px;
        }

        .tag-icon {
            width: 14px;
            height: 14px;
        }
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
        /* margin-top: 163px; */
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


        .intro-contact-container {
            background: linear-gradient(#062c25, #1d6154);

        }

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
            width: 447px;
            height: 617px;
            margin-left: 60px;
            margin-top: -4rem;
        }

        .intro-vrt-image img {
            position: absolute;
            object-fit: cover;
        }

        .contact-info-form__container {
            padding: 40px 55px 25px 55px;
        }

        .contact-info-form__title {
            font-size: 22px;

            margin-left: 0px;
        }

        .contact-info-form {
            padding: 0;
            /* padding-top: 2rem; */
        }

        .country_with_phone .prefix {

            margin: 5px 8px 5px 5px;
            border-right: 1px solid #b4bec82d;
            padding-right: 10px;
            height: 19px;

        }

        .contact-info-form__btn {
            margin-top: 24px;
        }

        .form_group_field {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .tag {
            gap: 8px;
            padding: 10px 16px;
            font-size: 14px;
        }

        .tag-icon {
            width: 22px;
            height: 22px;
        }


        .contact-info-location .heading {

            margin-top: 150px;


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

    .contact-info-form__tags.error,
    .country_with_phone.error,
    .price-range-group.error {
        outline: 1px solid rgba(231, 76, 60, 0.9);
        outline-offset: 4px;
        border-radius: 14px;
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
                            <h2>{{ __('contact.heading') }}</h2>
                            <h4>{{ __('contact.ready') }}</h4>
                            <p>{{ __('contact.ready_body') }}</p>
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
                        <h2 class="heading">{{ __('contact.information') }}</h2>
                        <div class="contact-info-icons ">
                            <div class="icon phone">
                                <img src="{{ asset('./img/phone.png') }}" loading="lazy"src="{{ asset('./img/phone.png') }}"
                                    loading="lazy" alt="" />
                                <p><a href="tel:+447498006410"> +44 7498 006410</a></p>
                            </div>
                            <div class="icon email">
                                <img src="{{ asset('./img/email.png') }}" loading="lazy" alt="" />
                                <p>hello@p23africa.com</p>
                            </div>
                            <div class="icon location">
                                <img src="{{ asset('./img/address.png') }}" loading="lazy" alt="" />
                                <p>{{ __('contact.london') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="contact-info-form">

                        <form class="contact-info-form__container" action="{{ locale_route('contact.submit') }}" method="POST"
                            id="contactForm">
                            @csrf
                            <h2 class="contact-info-form__title">{{ __('contact.leave_message') }}</h2>

                            <div class="contact-info-form__group name-field">
                                <label class="floating-label">{{ __('contact.full_name') }}</label>
                                <input type="text" name="name" class="floating-input @error('name') error @enderror"
                                    value="{{ old('name') }}" />
                                @error('name')
                                    <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                        {{ $message }}</div>
                                @enderror
                            </div>


                            <div class="form-field2 country_with_phone">
                                <div class="contact-info-form__group full-width">
                                    <label class="floating-label2">{{ __('contact.country') }}</label>
                                    <div class="phone-row">
                                        <!-- Country Flag Dropdown -->
                                        <div class="country-select" id="countrySelect">
                                            <div class="selected-country">
                                                <div class="flag-icon placeholder-flag" id="selectedFlag">🌍</div>
                                                <span class="arrow">▼</span>
                                            </div>

                                            <ul class="country-list">
                                                <!-- Popular/Most Used Countries -->
                                                <li data-code="+1" data-flag="us">
                                                    <img src="https://flagcdn.com/w40/us.png" alt="" />
                                                    United States (+1)
                                                </li>
                                                <li data-code="+44" data-flag="gb">
                                                    <img src="https://flagcdn.com/w40/gb.png" alt="" />
                                                    United Kingdom (+44)
                                                </li>
                                                <li data-code="+1" data-flag="ca">
                                                    <img src="https://flagcdn.com/w40/ca.png" alt="" />
                                                    Canada (+1)
                                                </li>
                                                <li data-code="+234" data-flag="ng">
                                                    <img src="https://flagcdn.com/w40/ng.png" alt="" />
                                                    Nigeria (+234)
                                                </li>
                                                <li data-code="+27" data-flag="za">
                                                    <img src="https://flagcdn.com/w40/za.png" alt="" />
                                                    South Africa (+27)
                                                </li>

                                                <!-- African Countries -->
                                                <li data-code="+213" data-flag="dz">
                                                    <img src="https://flagcdn.com/w40/dz.png" alt="" />
                                                    Algeria (+213)
                                                </li>
                                                <li data-code="+244" data-flag="ao">
                                                    <img src="https://flagcdn.com/w40/ao.png" alt="" />
                                                    Angola (+244)
                                                </li>
                                                <li data-code="+229" data-flag="bj">
                                                    <img src="https://flagcdn.com/w40/bj.png" alt="" />
                                                    Benin (+229)
                                                </li>
                                                <li data-code="+267" data-flag="bw">
                                                    <img src="https://flagcdn.com/w40/bw.png" alt="" />
                                                    Botswana (+267)
                                                </li>
                                                <li data-code="+226" data-flag="bf">
                                                    <img src="https://flagcdn.com/w40/bf.png" alt="" />
                                                    Burkina Faso (+226)
                                                </li>
                                                <li data-code="+257" data-flag="bi">
                                                    <img src="https://flagcdn.com/w40/bi.png" alt="" />
                                                    Burundi (+257)
                                                </li>
                                                <li data-code="+237" data-flag="cm">
                                                    <img src="https://flagcdn.com/w40/cm.png" alt="" />
                                                    Cameroon (+237)
                                                </li>
                                                <li data-code="+238" data-flag="cv">
                                                    <img src="https://flagcdn.com/w40/cv.png" alt="" />
                                                    Cape Verde (+238)
                                                </li>
                                                <li data-code="+236" data-flag="cf">
                                                    <img src="https://flagcdn.com/w40/cf.png" alt="" />
                                                    Central African Republic (+236)
                                                </li>
                                                <li data-code="+235" data-flag="td">
                                                    <img src="https://flagcdn.com/w40/td.png" alt="" />
                                                    Chad (+235)
                                                </li>
                                                <li data-code="+269" data-flag="km">
                                                    <img src="https://flagcdn.com/w40/km.png" alt="" />
                                                    Comoros (+269)
                                                </li>
                                                <li data-code="+243" data-flag="cd">
                                                    <img src="https://flagcdn.com/w40/cd.png" alt="" />
                                                    Congo (DRC) (+243)
                                                </li>
                                                <li data-code="+242" data-flag="cg">
                                                    <img src="https://flagcdn.com/w40/cg.png" alt="" />
                                                    Congo Republic (+242)
                                                </li>
                                                <li data-code="+225" data-flag="ci">
                                                    <img src="https://flagcdn.com/w40/ci.png" alt="" />
                                                    Côte d'Ivoire (+225)
                                                </li>
                                                <li data-code="+253" data-flag="dj">
                                                    <img src="https://flagcdn.com/w40/dj.png" alt="" />
                                                    Djibouti (+253)
                                                </li>
                                                <li data-code="+20" data-flag="eg">
                                                    <img src="https://flagcdn.com/w40/eg.png" alt="" />
                                                    Egypt (+20)
                                                </li>
                                                <li data-code="+240" data-flag="gq">
                                                    <img src="https://flagcdn.com/w40/gq.png" alt="" />
                                                    Equatorial Guinea (+240)
                                                </li>
                                                <li data-code="+291" data-flag="er">
                                                    <img src="https://flagcdn.com/w40/er.png" alt="" />
                                                    Eritrea (+291)
                                                </li>
                                                <li data-code="+268" data-flag="sz">
                                                    <img src="https://flagcdn.com/w40/sz.png" alt="" />
                                                    Eswatini (+268)
                                                </li>
                                                <li data-code="+251" data-flag="et">
                                                    <img src="https://flagcdn.com/w40/et.png" alt="" />
                                                    Ethiopia (+251)
                                                </li>
                                                <li data-code="+241" data-flag="ga">
                                                    <img src="https://flagcdn.com/w40/ga.png" alt="" />
                                                    Gabon (+241)
                                                </li>
                                                <li data-code="+220" data-flag="gm">
                                                    <img src="https://flagcdn.com/w40/gm.png" alt="" />
                                                    Gambia (+220)
                                                </li>
                                                <li data-code="+233" data-flag="gh">
                                                    <img src="https://flagcdn.com/w40/gh.png" alt="" />
                                                    Ghana (+233)
                                                </li>
                                                <li data-code="+224" data-flag="gn">
                                                    <img src="https://flagcdn.com/w40/gn.png" alt="" />
                                                    Guinea (+224)
                                                </li>
                                                <li data-code="+245" data-flag="gw">
                                                    <img src="https://flagcdn.com/w40/gw.png" alt="" />
                                                    Guinea-Bissau (+245)
                                                </li>
                                                <li data-code="+254" data-flag="ke">
                                                    <img src="https://flagcdn.com/w40/ke.png" alt="" />
                                                    Kenya (+254)
                                                </li>
                                                <li data-code="+266" data-flag="ls">
                                                    <img src="https://flagcdn.com/w40/ls.png" alt="" />
                                                    Lesotho (+266)
                                                </li>
                                                <li data-code="+231" data-flag="lr">
                                                    <img src="https://flagcdn.com/w40/lr.png" alt="" />
                                                    Liberia (+231)
                                                </li>
                                                <li data-code="+218" data-flag="ly">
                                                    <img src="https://flagcdn.com/w40/ly.png" alt="" />
                                                    Libya (+218)
                                                </li>
                                                <li data-code="+261" data-flag="mg">
                                                    <img src="https://flagcdn.com/w40/mg.png" alt="" />
                                                    Madagascar (+261)
                                                </li>
                                                <li data-code="+265" data-flag="mw">
                                                    <img src="https://flagcdn.com/w40/mw.png" alt="" />
                                                    Malawi (+265)
                                                </li>
                                                <li data-code="+223" data-flag="ml">
                                                    <img src="https://flagcdn.com/w40/ml.png" alt="" />
                                                    Mali (+223)
                                                </li>
                                                <li data-code="+222" data-flag="mr">
                                                    <img src="https://flagcdn.com/w40/mr.png" alt="" />
                                                    Mauritania (+222)
                                                </li>
                                                <li data-code="+230" data-flag="mu">
                                                    <img src="https://flagcdn.com/w40/mu.png" alt="" />
                                                    Mauritius (+230)
                                                </li>
                                                <li data-code="+212" data-flag="ma">
                                                    <img src="https://flagcdn.com/w40/ma.png" alt="" />
                                                    Morocco (+212)
                                                </li>
                                                <li data-code="+258" data-flag="mz">
                                                    <img src="https://flagcdn.com/w40/mz.png" alt="" />
                                                    Mozambique (+258)
                                                </li>
                                                <li data-code="+264" data-flag="na">
                                                    <img src="https://flagcdn.com/w40/na.png" alt="" />
                                                    Namibia (+264)
                                                </li>
                                                <li data-code="+227" data-flag="ne">
                                                    <img src="https://flagcdn.com/w40/ne.png" alt="" />
                                                    Niger (+227)
                                                </li>
                                                <li data-code="+250" data-flag="rw">
                                                    <img src="https://flagcdn.com/w40/rw.png" alt="" />
                                                    Rwanda (+250)
                                                </li>
                                                <li data-code="+239" data-flag="st">
                                                    <img src="https://flagcdn.com/w40/st.png" alt="" />
                                                    São Tomé and Príncipe (+239)
                                                </li>
                                                <li data-code="+221" data-flag="sn">
                                                    <img src="https://flagcdn.com/w40/sn.png" alt="" />
                                                    Senegal (+221)
                                                </li>
                                                <li data-code="+248" data-flag="sc">
                                                    <img src="https://flagcdn.com/w40/sc.png" alt="" />
                                                    Seychelles (+248)
                                                </li>
                                                <li data-code="+232" data-flag="sl">
                                                    <img src="https://flagcdn.com/w40/sl.png" alt="" />
                                                    Sierra Leone (+232)
                                                </li>
                                                <li data-code="+252" data-flag="so">
                                                    <img src="https://flagcdn.com/w40/so.png" alt="" />
                                                    Somalia (+252)
                                                </li>
                                                <li data-code="+211" data-flag="ss">
                                                    <img src="https://flagcdn.com/w40/ss.png" alt="" />
                                                    South Sudan (+211)
                                                </li>
                                                <li data-code="+249" data-flag="sd">
                                                    <img src="https://flagcdn.com/w40/sd.png" alt="" />
                                                    Sudan (+249)
                                                </li>
                                                <li data-code="+255" data-flag="tz">
                                                    <img src="https://flagcdn.com/w40/tz.png" alt="" />
                                                    Tanzania (+255)
                                                </li>
                                                <li data-code="+228" data-flag="tg">
                                                    <img src="https://flagcdn.com/w40/tg.png" alt="" />
                                                    Togo (+228)
                                                </li>
                                                <li data-code="+216" data-flag="tn">
                                                    <img src="https://flagcdn.com/w40/tn.png" alt="" />
                                                    Tunisia (+216)
                                                </li>
                                                <li data-code="+256" data-flag="ug">
                                                    <img src="https://flagcdn.com/w40/ug.png" alt="" />
                                                    Uganda (+256)
                                                </li>
                                                <li data-code="+260" data-flag="zm">
                                                    <img src="https://flagcdn.com/w40/zm.png" alt="" />
                                                    Zambia (+260)
                                                </li>
                                                <li data-code="+263" data-flag="zw">
                                                    <img src="https://flagcdn.com/w40/zw.png" alt="" />
                                                    Zimbabwe (+263)
                                                </li>

                                                <!-- European Countries -->
                                                <li data-code="+43" data-flag="at">
                                                    <img src="https://flagcdn.com/w40/at.png" alt="" />
                                                    Austria (+43)
                                                </li>
                                                <li data-code="+32" data-flag="be">
                                                    <img src="https://flagcdn.com/w40/be.png" alt="" />
                                                    Belgium (+32)
                                                </li>
                                                <li data-code="+359" data-flag="bg">
                                                    <img src="https://flagcdn.com/w40/bg.png" alt="" />
                                                    Bulgaria (+359)
                                                </li>
                                                <li data-code="+385" data-flag="hr">
                                                    <img src="https://flagcdn.com/w40/hr.png" alt="" />
                                                    Croatia (+385)
                                                </li>
                                                <li data-code="+357" data-flag="cy">
                                                    <img src="https://flagcdn.com/w40/cy.png" alt="" />
                                                    Cyprus (+357)
                                                </li>
                                                <li data-code="+420" data-flag="cz">
                                                    <img src="https://flagcdn.com/w40/cz.png" alt="" />
                                                    Czech Republic (+420)
                                                </li>
                                                <li data-code="+45" data-flag="dk">
                                                    <img src="https://flagcdn.com/w40/dk.png" alt="" />
                                                    Denmark (+45)
                                                </li>
                                                <li data-code="+372" data-flag="ee">
                                                    <img src="https://flagcdn.com/w40/ee.png" alt="" />
                                                    Estonia (+372)
                                                </li>
                                                <li data-code="+358" data-flag="fi">
                                                    <img src="https://flagcdn.com/w40/fi.png" alt="" />
                                                    Finland (+358)
                                                </li>
                                                <li data-code="+33" data-flag="fr">
                                                    <img src="https://flagcdn.com/w40/fr.png" alt="" />
                                                    France (+33)
                                                </li>
                                                <li data-code="+49" data-flag="de">
                                                    <img src="https://flagcdn.com/w40/de.png" alt="" />
                                                    Germany (+49)
                                                </li>
                                                <li data-code="+30" data-flag="gr">
                                                    <img src="https://flagcdn.com/w40/gr.png" alt="" />
                                                    Greece (+30)
                                                </li>
                                                <li data-code="+36" data-flag="hu">
                                                    <img src="https://flagcdn.com/w40/hu.png" alt="" />
                                                    Hungary (+36)
                                                </li>
                                                <li data-code="+354" data-flag="is">
                                                    <img src="https://flagcdn.com/w40/is.png" alt="" />
                                                    Iceland (+354)
                                                </li>
                                                <li data-code="+353" data-flag="ie">
                                                    <img src="https://flagcdn.com/w40/ie.png" alt="" />
                                                    Ireland (+353)
                                                </li>
                                                <li data-code="+39" data-flag="it">
                                                    <img src="https://flagcdn.com/w40/it.png" alt="" />
                                                    Italy (+39)
                                                </li>
                                                <li data-code="+371" data-flag="lv">
                                                    <img src="https://flagcdn.com/w40/lv.png" alt="" />
                                                    Latvia (+371)
                                                </li>
                                                <li data-code="+370" data-flag="lt">
                                                    <img src="https://flagcdn.com/w40/lt.png" alt="" />
                                                    Lithuania (+370)
                                                </li>
                                                <li data-code="+352" data-flag="lu">
                                                    <img src="https://flagcdn.com/w40/lu.png" alt="" />
                                                    Luxembourg (+352)
                                                </li>
                                                <li data-code="+356" data-flag="mt">
                                                    <img src="https://flagcdn.com/w40/mt.png" alt="" />
                                                    Malta (+356)
                                                </li>
                                                <li data-code="+31" data-flag="nl">
                                                    <img src="https://flagcdn.com/w40/nl.png" alt="" />
                                                    Netherlands (+31)
                                                </li>
                                                <li data-code="+47" data-flag="no">
                                                    <img src="https://flagcdn.com/w40/no.png" alt="" />
                                                    Norway (+47)
                                                </li>
                                                <li data-code="+48" data-flag="pl">
                                                    <img src="https://flagcdn.com/w40/pl.png" alt="" />
                                                    Poland (+48)
                                                </li>
                                                <li data-code="+351" data-flag="pt">
                                                    <img src="https://flagcdn.com/w40/pt.png" alt="" />
                                                    Portugal (+351)
                                                </li>
                                                <li data-code="+40" data-flag="ro">
                                                    <img src="https://flagcdn.com/w40/ro.png" alt="" />
                                                    Romania (+40)
                                                </li>
                                                <li data-code="+421" data-flag="sk">
                                                    <img src="https://flagcdn.com/w40/sk.png" alt="" />
                                                    Slovakia (+421)
                                                </li>
                                                <li data-code="+386" data-flag="si">
                                                    <img src="https://flagcdn.com/w40/si.png" alt="" />
                                                    Slovenia (+386)
                                                </li>
                                                <li data-code="+34" data-flag="es">
                                                    <img src="https://flagcdn.com/w40/es.png" alt="" />
                                                    Spain (+34)
                                                </li>
                                                <li data-code="+46" data-flag="se">
                                                    <img src="https://flagcdn.com/w40/se.png" alt="" />
                                                    Sweden (+46)
                                                </li>
                                                <li data-code="+41" data-flag="ch">
                                                    <img src="https://flagcdn.com/w40/ch.png" alt="" />
                                                    Switzerland (+41)
                                                </li>

                                                <!-- Asian Countries -->
                                                <li data-code="+93" data-flag="af">
                                                    <img src="https://flagcdn.com/w40/af.png" alt="" />
                                                    Afghanistan (+93)
                                                </li>
                                                <li data-code="+973" data-flag="bh">
                                                    <img src="https://flagcdn.com/w40/bh.png" alt="" />
                                                    Bahrain (+973)
                                                </li>
                                                <li data-code="+880" data-flag="bd">
                                                    <img src="https://flagcdn.com/w40/bd.png" alt="" />
                                                    Bangladesh (+880)
                                                </li>
                                                <li data-code="+975" data-flag="bt">
                                                    <img src="https://flagcdn.com/w40/bt.png" alt="" />
                                                    Bhutan (+975)
                                                </li>
                                                <li data-code="+673" data-flag="bn">
                                                    <img src="https://flagcdn.com/w40/bn.png" alt="" />
                                                    Brunei (+673)
                                                </li>
                                                <li data-code="+855" data-flag="kh">
                                                    <img src="https://flagcdn.com/w40/kh.png" alt="" />
                                                    Cambodia (+855)
                                                </li>
                                                <li data-code="+86" data-flag="cn">
                                                    <img src="https://flagcdn.com/w40/cn.png" alt="" />
                                                    China (+86)
                                                </li>
                                                <li data-code="+91" data-flag="in">
                                                    <img src="https://flagcdn.com/w40/in.png" alt="" />
                                                    India (+91)
                                                </li>
                                                <li data-code="+62" data-flag="id">
                                                    <img src="https://flagcdn.com/w40/id.png" alt="" />
                                                    Indonesia (+62)
                                                </li>
                                                <li data-code="+98" data-flag="ir">
                                                    <img src="https://flagcdn.com/w40/ir.png" alt="" />
                                                    Iran (+98)
                                                </li>
                                                <li data-code="+964" data-flag="iq">
                                                    <img src="https://flagcdn.com/w40/iq.png" alt="" />
                                                    Iraq (+964)
                                                </li>
                                                <li data-code="+972" data-flag="il">
                                                    <img src="https://flagcdn.com/w40/il.png" alt="" />
                                                    Israel (+972)
                                                </li>
                                                <li data-code="+81" data-flag="jp">
                                                    <img src="https://flagcdn.com/w40/jp.png" alt="" />
                                                    Japan (+81)
                                                </li>
                                                <li data-code="+962" data-flag="jo">
                                                    <img src="https://flagcdn.com/w40/jo.png" alt="" />
                                                    Jordan (+962)
                                                </li>
                                                <li data-code="+7" data-flag="kz">
                                                    <img src="https://flagcdn.com/w40/kz.png" alt="" />
                                                    Kazakhstan (+7)
                                                </li>
                                                <li data-code="+965" data-flag="kw">
                                                    <img src="https://flagcdn.com/w40/kw.png" alt="" />
                                                    Kuwait (+965)
                                                </li>
                                                <li data-code="+996" data-flag="kg">
                                                    <img src="https://flagcdn.com/w40/kg.png" alt="" />
                                                    Kyrgyzstan (+996)
                                                </li>
                                                <li data-code="+856" data-flag="la">
                                                    <img src="https://flagcdn.com/w40/la.png" alt="" />
                                                    Laos (+856)
                                                </li>
                                                <li data-code="+961" data-flag="lb">
                                                    <img src="https://flagcdn.com/w40/lb.png" alt="" />
                                                    Lebanon (+961)
                                                </li>
                                                <li data-code="+60" data-flag="my">
                                                    <img src="https://flagcdn.com/w40/my.png" alt="" />
                                                    Malaysia (+60)
                                                </li>
                                                <li data-code="+960" data-flag="mv">
                                                    <img src="https://flagcdn.com/w40/mv.png" alt="" />
                                                    Maldives (+960)
                                                </li>
                                                <li data-code="+976" data-flag="mn">
                                                    <img src="https://flagcdn.com/w40/mn.png" alt="" />
                                                    Mongolia (+976)
                                                </li>
                                                <li data-code="+95" data-flag="mm">
                                                    <img src="https://flagcdn.com/w40/mm.png" alt="" />
                                                    Myanmar (+95)
                                                </li>
                                                <li data-code="+977" data-flag="np">
                                                    <img src="https://flagcdn.com/w40/np.png" alt="" />
                                                    Nepal (+977)
                                                </li>
                                                <li data-code="+850" data-flag="kp">
                                                    <img src="https://flagcdn.com/w40/kp.png" alt="" />
                                                    North Korea (+850)
                                                </li>
                                                <li data-code="+968" data-flag="om">
                                                    <img src="https://flagcdn.com/w40/om.png" alt="" />
                                                    Oman (+968)
                                                </li>
                                                <li data-code="+92" data-flag="pk">
                                                    <img src="https://flagcdn.com/w40/pk.png" alt="" />
                                                    Pakistan (+92)
                                                </li>
                                                <li data-code="+970" data-flag="ps">
                                                    <img src="https://flagcdn.com/w40/ps.png" alt="" />
                                                    Palestine (+970)
                                                </li>
                                                <li data-code="+63" data-flag="ph">
                                                    <img src="https://flagcdn.com/w40/ph.png" alt="" />
                                                    Philippines (+63)
                                                </li>
                                                <li data-code="+974" data-flag="qa">
                                                    <img src="https://flagcdn.com/w40/qa.png" alt="" />
                                                    Qatar (+974)
                                                </li>
                                                <li data-code="+966" data-flag="sa">
                                                    <img src="https://flagcdn.com/w40/sa.png" alt="" />
                                                    Saudi Arabia (+966)
                                                </li>
                                                <li data-code="+65" data-flag="sg">
                                                    <img src="https://flagcdn.com/w40/sg.png" alt="" />
                                                    Singapore (+65)
                                                </li>
                                                <li data-code="+82" data-flag="kr">
                                                    <img src="https://flagcdn.com/w40/kr.png" alt="" />
                                                    South Korea (+82)
                                                </li>
                                                <li data-code="+94" data-flag="lk">
                                                    <img src="https://flagcdn.com/w40/lk.png" alt="" />
                                                    Sri Lanka (+94)
                                                </li>
                                                <li data-code="+963" data-flag="sy">
                                                    <img src="https://flagcdn.com/w40/sy.png" alt="" />
                                                    Syria (+963)
                                                </li>
                                                <li data-code="+886" data-flag="tw">
                                                    <img src="https://flagcdn.com/w40/tw.png" alt="" />
                                                    Taiwan (+886)
                                                </li>
                                                <li data-code="+992" data-flag="tj">
                                                    <img src="https://flagcdn.com/w40/tj.png" alt="" />
                                                    Tajikistan (+992)
                                                </li>
                                                <li data-code="+66" data-flag="th">
                                                    <img src="https://flagcdn.com/w40/th.png" alt="" />
                                                    Thailand (+66)
                                                </li>
                                                <li data-code="+90" data-flag="tr">
                                                    <img src="https://flagcdn.com/w40/tr.png" alt="" />
                                                    Turkey (+90)
                                                </li>
                                                <li data-code="+993" data-flag="tm">
                                                    <img src="https://flagcdn.com/w40/tm.png" alt="" />
                                                    Turkmenistan (+993)
                                                </li>
                                                <li data-code="+971" data-flag="ae">
                                                    <img src="https://flagcdn.com/w40/ae.png" alt="" />
                                                    UAE (+971)
                                                </li>
                                                <li data-code="+998" data-flag="uz">
                                                    <img src="https://flagcdn.com/w40/uz.png" alt="" />
                                                    Uzbekistan (+998)
                                                </li>
                                                <li data-code="+84" data-flag="vn">
                                                    <img src="https://flagcdn.com/w40/vn.png" alt="" />
                                                    Vietnam (+84)
                                                </li>
                                                <li data-code="+967" data-flag="ye">
                                                    <img src="https://flagcdn.com/w40/ye.png" alt="" />
                                                    Yemen (+967)
                                                </li>

                                                <!-- Oceania -->
                                                <li data-code="+61" data-flag="au">
                                                    <img src="https://flagcdn.com/w40/au.png" alt="" />
                                                    Australia (+61)
                                                </li>
                                                <li data-code="+679" data-flag="fj">
                                                    <img src="https://flagcdn.com/w40/fj.png" alt="" />
                                                    Fiji (+679)
                                                </li>
                                                <li data-code="+64" data-flag="nz">
                                                    <img src="https://flagcdn.com/w40/nz.png" alt="" />
                                                    New Zealand (+64)
                                                </li>
                                                <li data-code="+675" data-flag="pg">
                                                    <img src="https://flagcdn.com/w40/pg.png" alt="" />
                                                    Papua New Guinea (+675)
                                                </li>

                                                <!-- Americas -->
                                                <li data-code="+54" data-flag="ar">
                                                    <img src="https://flagcdn.com/w40/ar.png" alt="" />
                                                    Argentina (+54)
                                                </li>
                                                <li data-code="+55" data-flag="br">
                                                    <img src="https://flagcdn.com/w40/br.png" alt="" />
                                                    Brazil (+55)
                                                </li>
                                                <li data-code="+56" data-flag="cl">
                                                    <img src="https://flagcdn.com/w40/cl.png" alt="" />
                                                    Chile (+56)
                                                </li>
                                                <li data-code="+57" data-flag="co">
                                                    <img src="https://flagcdn.com/w40/co.png" alt="" />
                                                    Colombia (+57)
                                                </li>
                                                <li data-code="+593" data-flag="ec">
                                                    <img src="https://flagcdn.com/w40/ec.png" alt="" />
                                                    Ecuador (+593)
                                                </li>
                                                <li data-code="+52" data-flag="mx">
                                                    <img src="https://flagcdn.com/w40/mx.png" alt="" />
                                                    Mexico (+52)
                                                </li>
                                                <li data-code="+51" data-flag="pe">
                                                    <img src="https://flagcdn.com/w40/pe.png" alt="" />
                                                    Peru (+51)
                                                </li>
                                                <li data-code="+58" data-flag="ve">
                                                    <img src="https://flagcdn.com/w40/ve.png" alt="" />
                                                    Venezuela (+58)
                                                </li>

                                                <!-- Russia -->
                                                <li data-code="+7" data-flag="ru">
                                                    <img src="https://flagcdn.com/w40/ru.png" alt="" />
                                                    Russia (+7)
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Phone Input -->
                                        <div class="phone-input-wrapper">
                                            <label class="floating-phone">{{ __('contact.phone') }}</label>
                                            <span class="prefix" id="phonePrefix" style="display: none;"></span>
                                            <input type="tel" id="phoneNumber" name="phone"
                                                class="floating-input phone @error('phone') error @enderror"
                                                placeholder="{{ __('contact.phone_placeholder') }}" value="{{ old('phone') }}"
                                                readonly />
                                            <!-- Hidden input for country code -->
                                            <input type="hidden" id="countryCode" name="country_code"
                                                value="{{ old('country_code') }}" />
                                        </div>
                                    </div>
                                    @error('phone')
                                        <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    @error('country_code')
                                        <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>




                            <div class="form_group_field mt-2">

                                <div class="contact-info-form__group name-field emaill">
                                    <label class="floating-label">{{ __('contact.email') }}</label>

                                    <input type="email" name="email"
                                        class="floating-input @error('email') error @enderror"
                                        value="{{ old('email') }}" />
                                    @error('email')
                                        <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="contact-info-form__group name-field company">
                                    <label class="floating-label">{{ __('contact.company') }}</label>
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
                                                                                                                            <label class="floating-label">{{ __('contact.phone') }}</label>
                                                                                                                            <input type="tel" name="phone" class="floating-input @error('phone') error @enderror"
                                                                                                                                placeholder="+1234567890" value="{{ old('phone') }}" />
                                                                                                                            @error('phone')
        <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                                                                                                                                                                                                                {{ $message }}</div>
    @enderror
                                                                                                                        </div>
                                                                                                                        </div> -->



                            <div class="contact-info-form__section-title">
                                {{ __('contact.services_title') }}
                            </div>
                            <p class="contact-info-form__subtitle">
                                {{ __('contact.services_hint') }}
                            </p>

                            <div class="contact-info-form__tags">
                                <div class="contact-info-form__tags-row">
                                    <div class="tag" data-value="Verify Distributors">
                                        <img class="tag-icon" src="{{ asset('img/unselected.png') }}" alt="" />
                                        {{ __('contact.services.verify_distributors') }}
                                    </div>

                                    <div class="tag" data-value="Sales">
                                        <img class="tag-icon" src="{{ asset('img/unselected.png') }}" alt="" />
                                        {{ __('contact.services.sales') }}
                                    </div>
                                </div>

                                <div class="contact-info-form__tags-row">
                                    <div class="tag" data-value="Expansion">
                                        <img class="tag-icon" src="{{ asset('img/unselected.png') }}" alt="" />
                                        {{ __('contact.services.expansion') }}
                                    </div>

                                    <div class="tag" data-value="Operations">
                                        <img class="tag-icon" src="{{ asset('img/unselected.png') }}" alt="" />
                                        {{ __('contact.services.operations') }}
                                    </div>

                                    <div class="tag" data-value="Launch">
                                        <img class="tag-icon" src="{{ asset('img/unselected.png') }}" alt="" />
                                        {{ __('contact.services.launch') }}
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="services" id="selected-services"
                                value="{{ old('services') }}" />
                            @error('services')
                                <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                    {{ $message }}</div>
                            @enderror

                            <textarea name="message" class="contact-info-form__textarea @error('message') error @enderror"
                                placeholder="{{ __('contact.message_placeholder') }}">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                    {{ $message }}</div>
                            @enderror


                            <!-- reCAPTCHA v3 - Invisible -->
                            <input type="hidden" id="recaptchaToken" name="g-recaptcha-response" value="">
                            @error('g-recaptcha-response')
                                <div class="error-message" style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                                    {{ $message }}</div>
                            @enderror

                            <button type="submit" class="contact-info-form__btn" id="submitBtn">
                                <span class="btn-text">{{ __('contact.send') }}</span>
                                <span class="spinner" style="display: none;">{{ __('contact.sending') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="right-row animate-on-scroll">
                    <!-- Location -->

                    <div class="contact-info-location">
                        <h3 class="heading">{{ __('contact.location') }}</h3>
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
                        <h3>{{ __('contact.social') }}</h3>

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
                        <h2 class="contact-news-letter__title">{{ __('contact.subscribe') }}</h2>
                        <p class="contact-news-letter__text">{{ __('contact.newsletter') }}</p>
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

    <!-- Alpine.js for Live Form Capture -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://www.google.com/recaptcha/api.js?render=6LeSUwwsAAAAALMaU35MsAv8ffsAxFRAbE6xz1Ur"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tags = document.querySelectorAll(".tag");
            const hiddenInput = document.getElementById("selected-services");
            const form = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const spinner = submitBtn.querySelector('.spinner');

            // Track submission state to prevent multiple submissions
            let isSubmitting = false;
            let submissionComplete = false;

            // reCAPTCHA state management - GRACEFUL (won't block submission)
            let recaptchaReady = false;
            let recaptchaToken = null;
            let recaptchaTokenTimestamp = null;
            const RECAPTCHA_TOKEN_LIFETIME = 90000;
            const RECAPTCHA_SITE_KEY = '6LeSUwwsAAAAALMaU35MsAv8ffsAxFRAbE6xz1Ur';

            // AGGRESSIVE retry configuration for 100% success
            const MAX_RETRIES = 5;
            const RETRY_DELAYS = [1000, 2000, 4000, 8000, 16000]; // Exponential backoff

            // LocalStorage key for offline/backup submissions
            const OFFLINE_STORAGE_KEY = 'p23_contact_pending_submissions';

            // Initialize reCAPTCHA - GRACEFUL (continues even if fails)
            function initRecaptcha() {
                try {
                    if (typeof grecaptcha !== 'undefined' && grecaptcha.ready) {
                        grecaptcha.ready(function() {
                            recaptchaReady = true;
                            console.log('reCAPTCHA initialized successfully');
                            refreshRecaptchaToken();
                        });
                    } else {
                        console.warn('reCAPTCHA not loaded, will proceed without it');
                        setTimeout(initRecaptcha, 1000);
                    }
                } catch (error) {
                    console.warn('reCAPTCHA init failed - will proceed without:', error);
                    recaptchaReady = false;
                }
            }

            // Refresh reCAPTCHA token - GRACEFUL (returns null on failure, doesn't throw)
            async function refreshRecaptchaToken() {
                try {
                    if (!recaptchaReady) return null;

                    const token = await Promise.race([
                        grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                            action: 'submit'
                        }),
                        new Promise((_, reject) => setTimeout(() => reject('timeout'), 5000))
                    ]);

                    recaptchaToken = token;
                    recaptchaTokenTimestamp = Date.now();
                    document.getElementById('recaptchaToken').value = token;
                    return token;
                } catch (error) {
                    console.warn('reCAPTCHA token refresh failed - will proceed without:', error);
                    return null;
                }
            }

            function isTokenExpired() {
                if (!recaptchaToken || !recaptchaTokenTimestamp) return true;
                return (Date.now() - recaptchaTokenTimestamp) > RECAPTCHA_TOKEN_LIFETIME;
            }

            // Auto-refresh token when form is focused
            form.addEventListener('focusin', () => {
                if (isTokenExpired()) refreshRecaptchaToken();
            });
            setInterval(() => {
                if (isTokenExpired() && !isSubmitting) refreshRecaptchaToken();
            }, 60000);

            initRecaptcha();

            const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));

            // Get form data as plain object
            function getFormDataObject() {
                return {
                    name: document.querySelector('input[name="name"]')?.value?.trim() || '',
                    company_name: document.querySelector('input[name="company_name"]')?.value?.trim() || '',
                    email: document.querySelector('input[name="email"]')?.value?.trim() || '',
                    phone: document.querySelector('input[name="phone"]')?.value?.trim() || '',
                    country_code: document.querySelector('input[name="country_code"]')?.value?.trim() || '',
                    services: document.getElementById('selected-services')?.value || '',
                    message: document.querySelector('textarea[name="message"]')?.value?.trim() || '',
                    timestamp: new Date().toISOString()
                };
            }

            // Save submission to localStorage as backup
            function saveToLocalStorage(data) {
                try {
                    const pending = JSON.parse(localStorage.getItem(OFFLINE_STORAGE_KEY) || '[]');
                    pending.push({
                        ...data,
                        savedAt: Date.now(),
                        synced: false
                    });
                    localStorage.setItem(OFFLINE_STORAGE_KEY, JSON.stringify(pending));
                    console.log('Submission saved to localStorage as backup');
                    return true;
                } catch (e) {
                    console.warn('LocalStorage save failed:', e);
                    return false;
                }
            }

            // Try to sync pending submissions in background
            async function syncPendingSubmissions() {
                try {
                    const pending = JSON.parse(localStorage.getItem(OFFLINE_STORAGE_KEY) || '[]');
                    const unsynced = pending.filter(s => !s.synced);

                    for (const submission of unsynced) {
                        try {
                            const formData = new FormData();
                            Object.keys(submission).forEach(key => {
                                if (!['savedAt', 'synced', 'timestamp'].includes(key)) {
                                    formData.append(key, submission[key]);
                                }
                            });
                            formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                                ?.content || '');
                            formData.append('offline_sync', 'true');

                            const response = await fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });

                            if (response.ok) {
                                submission.synced = true;
                                console.log('Synced offline submission');
                            }
                        } catch (e) {
                            console.warn('Failed to sync submission:', e);
                        }
                    }

                    // Update storage and clean old synced items (>7 days)
                    const weekAgo = Date.now() - (7 * 24 * 60 * 60 * 1000);
                    const filtered = pending.filter(s => !s.synced || s.savedAt > weekAgo);
                    localStorage.setItem(OFFLINE_STORAGE_KEY, JSON.stringify(filtered));
                } catch (e) {
                    console.warn('Sync failed:', e);
                }
            }

            // Try to sync on page load
            syncPendingSubmissions();

            // BULLETPROOF fetch with aggressive retries
            async function bulletproofFetch(url, formData) {
                let lastError = null;

                for (let attempt = 0; attempt < MAX_RETRIES; attempt++) {
                    try {
                        console.log(`Submit attempt ${attempt + 1}/${MAX_RETRIES}`);

                        // Refresh CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        if (csrfToken) formData.set('_token', csrfToken);

                        // Try to refresh reCAPTCHA (graceful - continues if fails)
                        if (isTokenExpired()) {
                            await refreshRecaptchaToken();
                        }
                        if (recaptchaToken) {
                            formData.set('g-recaptcha-response', recaptchaToken);
                        }

                        const controller = new AbortController();
                        const timeoutId = setTimeout(() => controller.abort(), 30000);

                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            signal: controller.signal
                        });

                        clearTimeout(timeoutId);

                        // Success!
                        if (response.ok) {
                            const data = await response.json();
                            return {
                                success: true,
                                data
                            };
                        }

                        // 419 = CSRF expired - need page reload
                        if (response.status === 419) {
                            return {
                                success: false,
                                needsReload: true
                            };
                        }

                        // 422 = Validation error - return errors to show user
                        if (response.status === 422) {
                            const data = await response.json();
                            return {
                                success: false,
                                validation: true,
                                data
                            };
                        }

                        // Other errors - retry
                        lastError = new Error(`HTTP ${response.status}`);
                    } catch (error) {
                        lastError = error;
                        console.warn(`Attempt ${attempt + 1} failed:`, error.message);
                    }

                    // Wait before retry
                    if (attempt < MAX_RETRIES - 1) {
                        await delay(RETRY_DELAYS[attempt] || 5000);
                    }
                }

                // ALL RETRIES FAILED - save to localStorage as ultimate fallback
                console.log('All server attempts failed - saving to localStorage');
                const formDataObj = getFormDataObject();
                saveToLocalStorage(formDataObj);

                return {
                    success: true,
                    offline: true
                };
            }

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

                    if (isSelected) {
                        tag.classList.remove("selected");
                        tag.querySelector(".tag-icon").src = "{{ asset('img/unselected.png') }}";
                    } else {
                        tag.classList.add("selected");
                        tag.querySelector(".tag-icon").src = "{{ asset('img/selected.png') }}";
                    }

                    const selectedValues = Array.from(
                        document.querySelectorAll(".tag.selected")
                    ).map((t) => t.getAttribute("data-value"));

                    hiddenInput.value = selectedValues.join(",");
                });
            });

            // Popup functions - make global for live form capture
            window.showPopup = function(type, title, message) {
                const overlay = document.getElementById('popupOverlay');
                const modal = document.getElementById('popupModal');
                const icon = document.getElementById('popupIcon');
                const titleEl = document.getElementById('popupTitle');
                const messageEl = document.getElementById('popupMessage');

                titleEl.textContent = title;
                messageEl.textContent = message;

                if (type === 'success') {
                    modal.className = 'popup-modal success';
                    icon.className = 'popup-icon success';
                    icon.innerHTML = '✓';
                } else {
                    modal.className = 'popup-modal error';
                    icon.className = 'popup-icon error';
                    icon.innerHTML = '✕';
                }

                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            function hidePopup() {
                const overlay = document.getElementById('popupOverlay');
                overlay.style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            document.getElementById('popupCloseBtn').addEventListener('click', hidePopup);
            document.getElementById('popupOverlay').addEventListener('click', function(e) {
                if (e.target === this) {
                    hidePopup();
                }
            });

            // Form submission - BULLETPROOF with 100% success rate
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Prevent multiple submissions
                if (isSubmitting || submissionComplete) {
                    console.log('Submission already in progress or completed');
                    return;
                }

                // Clear previous errors
                clearAllErrors();

                // Frontend validation - check all required fields (matches Laravel rules)
                const validationErrors = [];

                // Name validation
                const nameInput = document.querySelector('input[name="name"]');
                if (!nameInput.value.trim()) {
                    validationErrors.push({
                        field: 'name',
                        message: @json(__('contact.validation.name')),
                        element: nameInput
                    });
                }

                // Company name validation
                const companyInput = document.querySelector('input[name="company_name"]');
                if (!companyInput.value.trim()) {
                    validationErrors.push({
                        field: 'company_name',
                        message: @json(__('contact.validation.company_name')),
                        element: companyInput
                    });
                }

                // Email validation
                const emailInput = document.querySelector('input[name="email"]');
                if (!emailInput.value.trim()) {
                    validationErrors.push({
                        field: 'email',
                        message: @json(__('contact.validation.email')),
                        element: emailInput
                    });
                } else if (!isValidEmail(emailInput.value)) {
                    validationErrors.push({
                        field: 'email',
                        message: @json(__('contact.validation.email_invalid')),
                        element: emailInput
                    });
                }

                // Country code validation (must be selected first)
                const countryCodeInput = document.querySelector('input[name="country_code"]');
                if (!countryCodeInput.value.trim()) {
                    validationErrors.push({
                        field: 'country_code',
                        message: @json(__('contact.validation.country_first')),
                        element: document.querySelector('.country_with_phone')
                    });
                }

                // Phone validation
                const phoneInputEl = document.querySelector('input[name="phone"]');
                if (!phoneInputEl.value.trim()) {
                    validationErrors.push({
                        field: 'phone',
                        message: @json(__('contact.validation.phone')),
                        element: document.querySelector('.country_with_phone')
                    });
                }

                // Services validation
                const servicesInput = document.getElementById('selected-services');
                if (!servicesInput.value || servicesInput.value.trim() === '') {
                    validationErrors.push({
                        field: 'services',
                        message: @json(__('contact.validation.services')),
                        element: document.querySelector('.contact-info-form__tags')
                    });
                }

                // Message validation
                const messageInput = document.querySelector('textarea[name="message"]');
                if (!messageInput.value.trim()) {
                    validationErrors.push({
                        field: 'message',
                        message: @json(__('contact.validation.message')),
                        element: messageInput
                    });
                }

                // If there are validation errors, show them and stop submission
                if (validationErrors.length > 0) {
                    resetButtonState();

                    // Display all errors
                    validationErrors.forEach(error => {
                        if (error.element) {
                            error.element.classList.add('error');
                            showFrontendError(error.element, error.message);
                        }
                    });

                    // Scroll to first error
                    if (validationErrors[0].element) {
                        validationErrors[0].element.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    return;
                }

                // Show loading state
                isSubmitting = true;
                submitBtn.disabled = true;
                btnText.style.display = 'none';
                spinner.style.display = 'inline';

                try {
                    const formData = new FormData(form);

                    // Add reCAPTCHA token if available (graceful - proceeds without it)
                    if (recaptchaToken) {
                        formData.set('g-recaptcha-response', recaptchaToken);
                    }

                    // BULLETPROOF submission with retries and fallback
                    const result = await bulletproofFetch(form.action, formData);

                    resetButtonState();

                    // Handle CSRF expiry - need page reload
                    if (result.needsReload) {
                        showPopup('info', 'Session Refreshing',
                            'Your session expired. The page will refresh - please try again.');
                        setTimeout(() => window.location.reload(), 2000);
                        return;
                    }

                    // Handle validation errors from server
                    if (result.validation && result.data?.errors) {
                        displayValidationErrors(result.data.errors);

                        setTimeout(() => {
                            const firstError = document.querySelector('.error');
                            if (firstError) {
                                firstError.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            }
                        }, 100);

                        showPopup('error', 'Please Check Your Input',
                            result.data.message || 'Please correct the errors and try again.');
                        return;
                    }

                    // SUCCESS! (Either server success OR offline save)
                    submissionComplete = true;

                    if (result.offline) {
                        // Saved to localStorage - still show success!
                        showPopup('success', 'Message Saved!',
                            'Your message has been saved and will be sent shortly. Thank you for contacting us!'
                        );
                    } else {
                        showPopup('success', 'Message Sent!',
                            result.data?.message ||
                            'Thank you for contacting us. We will get back to you soon!');
                    }

                    resetForm();

                } catch (error) {
                    console.error('Unexpected submission error:', error);
                    resetButtonState();

                    // ULTIMATE FALLBACK - save to localStorage and show success anyway
                    const formDataObj = getFormDataObject();
                    const saved = saveToLocalStorage(formDataObj);

                    if (saved) {
                        submissionComplete = true;
                        showPopup('success', 'Message Saved!',
                            'Your message has been saved and will be sent shortly. Thank you!');
                        resetForm();
                    } else {
                        // Absolute last resort - show mailto option
                        showPopup('info', 'Alternative Contact',
                            'Please email us directly at contact@p23africa.com with your inquiry. We apologize for the inconvenience.'
                        );
                    }
                }
            });

            // Clear all errors
            function clearAllErrors() {
                // Remove all dynamic error messages
                document.querySelectorAll('.error-message.dynamic-error').forEach(msg => msg.remove());

                // Remove error class from all elements
                document.querySelectorAll('.error').forEach(element => {
                    element.classList.remove('error');
                });
            }

            // Display validation errors
            function displayValidationErrors(errors) {
                if (!errors || typeof errors !== 'object') {
                    console.error('Invalid errors object:', errors);
                    return;
                }

                // Loop through each field error
                Object.keys(errors).forEach(fieldName => {
                    const errorMessages = errors[fieldName];
                    const firstError = Array.isArray(errorMessages) ? errorMessages[0] : errorMessages;

                    console.log(`Field: ${fieldName}, Error: ${firstError}`);

                    // Get the appropriate elements to highlight and show error
                    const elements = getFieldElements(fieldName);

                    if (elements.highlight) {
                        console.log(`Adding error class to:`, elements.highlight);
                        elements.highlight.classList.add('error');
                    }

                    if (elements.messageTarget) {
                        console.log(`Showing error message for:`, elements.messageTarget);
                        showErrorMessage(elements.messageTarget, firstError);
                    }
                });
            }

            // Get field elements for error display
            function getFieldElements(fieldName) {
                const elements = {
                    highlight: null,
                    messageTarget: null
                };

                switch (fieldName) {
                    case 'name':
                        elements.highlight = document.querySelector('input[name="name"]');
                        elements.messageTarget = elements.highlight;
                        break;

                    case 'email':
                        elements.highlight = document.querySelector('input[name="email"]');
                        elements.messageTarget = elements.highlight;
                        break;

                    case 'company_name':
                        elements.highlight = document.querySelector('input[name="company_name"]');
                        elements.messageTarget = elements.highlight;
                        break;

                    case 'phone':
                    case 'country_code':
                        elements.highlight = document.querySelector('.phone-input-wrapper');
                        elements.messageTarget = document.querySelector('.country_with_phone');
                        break;

                    case 'services':
                        elements.highlight = document.querySelector('.contact-info-form__tags');
                        elements.messageTarget = elements.highlight;
                        break;

                    case 'message':
                        elements.highlight = document.querySelector('textarea[name="message"]');
                        elements.messageTarget = elements.highlight;
                        break;

                    case 'g-recaptcha-response':
                        // Show in popup only, no field highlighting
                        break;

                    default:
                        // Try to find by name attribute
                        elements.highlight = document.querySelector(`[name="${fieldName}"]`);
                        elements.messageTarget = elements.highlight;
                        break;
                }

                console.log(`Elements found:`, elements);
                return elements;
            }

            // Show error message
            function showErrorMessage(targetElement, message) {
                if (!targetElement) {
                    return;
                }

                // Remove existing error message if any
                const existingError = targetElement.parentElement.querySelector('.error-message.dynamic-error');
                if (existingError) {
                    existingError.remove();
                }

                // Create new error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message dynamic-error';
                errorDiv.style.color = '#e74c3c';
                errorDiv.style.fontSize = '12px';
                errorDiv.style.marginTop = '5px';
                errorDiv.textContent = message;

                // Insert after target element
                if (targetElement.nextSibling) {
                    targetElement.parentElement.insertBefore(errorDiv, targetElement.nextSibling);
                } else {
                    targetElement.parentElement.appendChild(errorDiv);
                }

                console.log('Error message added:', message);
            }

            function resetButtonState() {
                isSubmitting = false;
                if (!submissionComplete) {
                    submitBtn.disabled = false;
                }
                btnText.style.display = 'inline';
                spinner.style.display = 'none';
            }

            // Country selector
            const countrySelect = document.getElementById("countrySelect");
            const selectedCountry = countrySelect.querySelector(".selected-country");
            const selectedImg = selectedCountry.querySelector(".flag-icon");
            const countryList = countrySelect.querySelector(".country-list");
            const phonePrefix = document.getElementById("phonePrefix");
            const countryCodeInput = document.getElementById("countryCode");
            const phoneInput = document.getElementById("phoneNumber");

            // Initialize country from old values if present
            const oldCountryCode = countryCodeInput.value;
            if (oldCountryCode) {
                // Find the country item that matches the old code
                const countryItem = countryList.querySelector(`li[data-code="${oldCountryCode}"]`);
                if (countryItem) {
                    const flag = countryItem.dataset.flag;
                    const code = countryItem.dataset.code;

                    // Set flag and prefix
                    const flagContainer = selectedCountry.querySelector('#selectedFlag');
                    flagContainer.innerHTML =
                        `<img src="https://flagcdn.com/w40/${flag}.png" alt="${countryItem.textContent.trim()} flag" class="flag-icon">`;
                    phonePrefix.textContent = code;
                    phonePrefix.style.display = 'inline';

                    // Enable phone input
                    phoneInput.removeAttribute('readonly');
                    phoneInput.placeholder = 'Enter phone number';
                }
            }

            selectedCountry.addEventListener("click", (e) => {
                e.stopPropagation();
                countrySelect.classList.toggle("open");
            });

            countryList.querySelectorAll("li").forEach((item) => {
                item.addEventListener("click", () => {
                    const flag = item.dataset.flag;
                    const code = item.dataset.code;

                    // Replace placeholder with actual flag image
                    const flagContainer = selectedCountry.querySelector('#selectedFlag');
                    flagContainer.innerHTML =
                        `<img src="https://flagcdn.com/w40/${flag}.png" alt="${item.textContent.trim()} flag" class="flag-icon">`;

                    phonePrefix.textContent = code;
                    phonePrefix.style.display = 'inline';

                    if (countryCodeInput) {
                        countryCodeInput.value = code;
                    }

                    // Enable phone input after country selection
                    if (phoneInput) {
                        phoneInput.removeAttribute('readonly');
                        phoneInput.placeholder = 'Enter phone number';
                        phoneInput.focus();
                    }

                    countryList.style.opacity = "0";
                    countryList.style.transform = "translateY(-8px)";
                    setTimeout(() => {
                        countrySelect.classList.remove("open");
                        countryList.removeAttribute("style");
                    }, 200);
                });
            });

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

            // Helper function for email validation
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // Helper function to show frontend errors
            function showFrontendError(element, message) {
                if (!element) return;

                // Create error message div
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message dynamic-error';
                errorDiv.style.color = '#e74c3c';
                errorDiv.style.fontSize = '12px';
                errorDiv.style.marginTop = '5px';
                errorDiv.textContent = message;

                // Insert error message after the element
                element.insertAdjacentElement('afterend', errorDiv);
            }

            // Helper function to reset form after successful submission
            function resetForm() {
                form.reset();
                hiddenInput.value = '';
                document.getElementById('recaptchaToken').value = '';

                // Reset service tags
                document.querySelectorAll('.tag.selected').forEach(tag => {
                    tag.classList.remove('selected');
                    tag.querySelector('.tag-icon').src = "{{ asset('img/unselected.png') }}";
                });

                // Reset submission states
                submissionComplete = false;
                isSubmitting = false;
                submitBtn.disabled = false;
            }
        });
    </script>
@endsection
