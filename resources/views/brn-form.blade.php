@extends('layouts.brn-layout')
@section('title', 'NOELHQ Interest Form | P23 Africa')

@section('meta')
    <meta name="description"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth.">
    <meta name="keywords"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth.">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth | P23 Africa">
    <meta property="og:image" content="{{ asset('images/brn-form-meta.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

{{-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> --}}

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

    @font-face {
        font-family: "GT Walsheim Pro";

        src: url("/public/fonts/gt-walsheim-pro/GTWalsheimPro-Regular.woff2") format("woff2"),
            url("/public/fonts/gt-walsheim-pro/GTWalsheimPro-Regular.woff") format("woff");
    }

    @font-face {
        font-family: "GT Walsheim Trial Md";
        font-style: normal;
        font-weight: normal;
        src: local("GT Walsheim Trial Md"),
            url("/public/fonts/gt-trials/GT-Walsheim-Medium-Trial-BF651b7fc728fb3.woff") format("woff");
    }

    @font-face {
        font-family: "GT Walsheim Con";

        src: url("/public/fonts/gt-walsheim-pro/GTWalsheimPro-CondensedRegular.woff2") format("woff2"),
            url("/public/fonts/gt-walsheim-pro/GTWalsheimPro-Regular.woff") format("woff");
    }

    @font-face {
        font-family: "GT Walsheim Bold";

        src: url("/public/fonts/gt-walsheim-pro/GTWalsheimPro-Bold.woff2") format("woff2"),
            url("/public/fonts/gt-walsheim-pro/GTWalsheimPro-Regular.woff") format("woff");
    }

    @font-face {
        font-family: "GT Walsheim ConB";

        src: url("/public/fonts/gt-walsheim-pro/GTWalsheimPro-CondensedBold.woff2") format("woff2"),
            url("/public/fonts/gt-walsheim-pro/GTWalsheimPro-CondensedBold.woff") format("woff");
    }

    @font-face {
        font-family: "GT Walsheim ConL";

        src: url("/public/fonts/gt-walsheim-pro/GTWalsheimPro-CondensedLight.woff2") format("woff2"),
            url("/public/fonts/gt-walsheim-pro/GTWalsheimPro-CondensedLight.woff") format("woff");
    }

    @font-face {
        font-family: "IvyOraText BoldItalic";

        src: url("/public/fonts/ivyora-text/IvyOraText-BoldItalic.woff2") format("woff2"),
            url("/public/fonts/ivyora-text/IvyOraText-BoldItalic.woff") format("woff");
        /* Adjust file names and paths as needed */
    }

    @font-face {
        font-family: "IvyOra Text";

        src: url("/public/fonts/ivyora-text/IvyOraText-RegularItalic.woff2") format("woff2"),
            url("/public/fonts/ivyora-text/IvyOraText-RegularItalic.woff") format("woff");
        /* Adjust file names and paths as needed */
    }

    @font-face {
        font-family: "IvyOra Text Bold";

        src: url("/public/fonts/ivyora-text/IvyOraText-Bold.woff2") format("woff2"),
            url("/public/fonts/ivyora-text/IvyOraText-Bold.woff") format("woff");
        /* Adjust file names and paths as needed */
    }

    @font-face {
        font-family: "IvyOra Text Medium";

        src: url("/public/fonts/ivyora-text/IvyOraText-Medium.woff2") format("woff2"),
            url("/public/fonts/ivyora-text/IvyOraText-Medium.woff") format("woff");
        /* Adjust file names and paths as needed */
    }


    body .interest-form-layout-container {
        font-family: "Montserrat", sans-serif;

    }

    body .hidden {
        display: none;

    }

    /* .interest-form-layout-container */
    .interest-form-layout-container {
        max-width: 1264px;
        margin: 0 auto;
        margin-top: 100px;
        margin-bottom: 60px;
    }

    .form-layout-container {
        padding: 20px 22px;
        /* padding: 20px 12px 20px 23px; */
        display: grid;
        grid-template-columns: repeat(1, 1fr);
    }

    /*====================================================================
            ========================== Interest Heading Heading Container ========================= */
    .interest-form-heading {
        max-width: 600px;
        margin: 0 auto;
    }

    .interest-form-heading .header h1 {
        font-size: 37px;
        font-weight: 500;
        color: #0d4036;
        margin: 0 0 10px 0;
    }


    .interest-form-layout-container .interest-form-heading>div.third-form-heading .header h1 {
        font-size: 30px;

    }


    .interest-form-heading .subtitle {
        display: flex;
        align-items: center;
    }

    .interest-form-heading .subtitle .line {
        width: 20vw;
        max-width: 120px;
        height: 3px;
        background-color: #137077;
        border-radius: 2px;
        margin-right: 6px;
    }

    .interest-form-heading .subtitle p {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: #137077;
    }




    /* 2. Description Section */
    .interest-form-heading .description {
        margin: 30px 0;
    }

    .interest-form-heading .description p {
        font-size: 13px;
        color: #0d4036;
        line-height: 1.5;
        margin: 0;
    }

    /* 3. Progress Stepper */
    .progress-step-container {
        width: 100%;
    }

    .interest-form-heading .progress-stepper {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        margin-top: 10px;
    }

    /* This ::before pseudo-element creates the gray line */
    .interest-form-heading .progress-stepper::before {
        content: "";
        position: absolute;
        top: 21px;
        left: 12%;
        right: 12%;
        height: 4px;
        background-color: #ebefee;
        z-index: 1;
        transform: translateY(-50%);
    }

    .interest-form-heading .progress-stepper::after {
        content: "";
        position: absolute;
        top: 21px;
        left: 12%;
        right: 12%;
        height: 4px;
        background-color: #137077;
        z-index: 1;
        transform: translateY(-50%);
        width: var(--progress-width, 0%);
    }

    .interest-form-heading .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 80px;
        position: relative;
        z-index: 2;
    }

    .interest-form-heading .step .circle {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        transition: all 0.3s ease;

        /* Inactive State Styles */
        background-color: #ebefee;
        border: 3px solid #cfd0d0;
        color: #cfd0d0;
    }

    .interest-form-heading .step .label {
        margin-top: 8px;
        font-size: 10px;
        font-weight: 800;
        transition: all 0.3s ease;

        color: #137077;
        font-weight: 700;
    }

    /* Active State Styles */

    .interest-form-heading .step.active .circle {
        background-color: #3daab2;
        border-color: #137077;
        color: #ffffff;
    }

    /*
            .interest-form-heading .step.active .label {
            color: #137077;
            font-weight: 700;
            } */
    .step.completed .circle {
        background-color: #3daab2;

        border-color: #137077;
        color: #fff;
    }


    /*====================================================================
            ========================== Interest Form Container ========================= */
    .interest-form-container {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .interest-form-container .form-container {
        background-color: #137077;
        padding: 65px 30px;
        border-radius: 35px;
        width: 100%;
        max-width: 650px;

        margin-top: 40px;

        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .interest-form {
        display: flex;
        flex-direction: column;
    }

    /* Form Group (Label + Input/Select) */
    .interest-form-group {
        margin-bottom: 25px;
    }

    .interest-form-group:last-of-type {
        margin-bottom: 40px;
    }

    label {
        display: block;
        font-size: 13px;
        font-weight: 400;
        color: #f0f0f0;
        margin-bottom: 8px;
        margin-left: 25px;
        font-family: "GT Walsheim Trial Md";



    }



    /* Input Field Styling */
    input {
        width: 100%;
        padding: 18px 20px;
        border: 1px solid #fff;
        border-radius: 30px;
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff;
        font-size: 16px;
        font-family: "Montserrat", sans-serif;
        outline: none;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        box-sizing: border-box;
        padding-left: 30px;
    }

    input::placeholder {
        color: #ececec80;
        font-size: 12px;
        font-family: "GT Walsheim Trial Md";

    }


    /* Custom Select Styling */
    .interest-form-custom-select-wrapper {
        position: relative;
        width: 100%;
    }

    /* Select Input */
    /* Base wrapper */
    .custom-select {
        position: relative;
        width: 100%;
        font-family: "Montserrat", sans-serif;
        color: #ffffff;
    }

    /* Trigger (main visible input) */
    .custom-select-trigger {
        background-color: rgba(255, 255, 255, 0.2) !important;
        border: 1px solid #a8bdbf;
        border-radius: 30px;
        padding: 18px 20px;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .custom-select-trigger:hover {
        background-color: rgba(255, 255, 255, 0.3) !important;
    }

    /* Text inside the trigger */
    .custom-select-text {
        color: rgba(255, 255, 255, 0.9) !important;
        font-family: "GT Walsheim Trial Md";
    }

    /* Arrow styling */
    .custom-select-arrow {
        transition: transform 0.3s ease;
    }

    /* Dropdown list */
    .custom-options {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        background: #137077;
        border-radius: 14px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        opacity: 0;
        pointer-events: none;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        overflow: hidden;
        z-index: 10;
        font-family: "GT Walsheim Trial Md";
        font-weight: 400;
        max-height: 200px;
    }

    /* Scrollable options container */
    .custom-options-scrollable {
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Custom scrollbar for options */
    .custom-options-scrollable::-webkit-scrollbar {
        width: 6px;
    }

    .custom-options-scrollable::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }

    .custom-options-scrollable::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }

    .custom-options-scrollable::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }

    /* Search input for country dropdown */
    .country-search {
        position: sticky;
        top: 0;
        background: #137077;
        border: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        padding: 12px 18px;
        color: #ffffff;
        font-size: 13px;
        outline: none;
        font-family: "GT Walsheim Trial Md";
        border-radius: 0;
        width: 100%;
        box-sizing: border-box;
    }

    .country-search::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .country-search:focus {
        border-bottom-color: #ffffff;
        background-color: rgba(255, 255, 255, 0.1);
    }

    /* Option item */
    .custom-option {
        display: block;
        padding: 12px 18px;
        font-size: 13px;
        color: #ffffff;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .custom-option:hover {
        background-color: rgba(255, 255, 255, 0.15);
    }

    /* When dropdown is active */
    .custom-select.open .custom-options {
        opacity: 1;
        pointer-events: all;
        transform: translateY(0);
    }

    .custom-select.open .custom-select-arrow {
        transform: rotate(180deg);
    }

    /* Active option (highlighted) */
    .custom-option.active {
        background-color: rgba(255, 255, 255, 0.25) !important;
    }



    /* ===================================================== FOR FORM THREE  */
    /* Container for all tags */
    .interest-form-tags {
        display: flex;
        grid-template-columns: repeat(3, 1fr);
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 10px;
        width: 100%;
        justify-content: center;
    }

    /* Individual tag wrapper */
    .interest-form-tag {
        background-color: #ffffff;
        border-radius: 40px;
        display: flex;
        align-items: center;
        padding: 8px 25px;
        gap: 10px;
        cursor: pointer;
    }

    .interest-form-tag span {
        display: block;
        font-size: 11px;
        color: #193E47;
        font-weight: 300px;
        font-family: "GT Walsheim Trial Md";


    }

    .interest-form-tag span:first-of-type {
        width: 25px;
        height: 25px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50px;
        border: 2px solid #0d4036;
        padding: 2px;
    }

    .interest-form-tag span svg {
        color: #fff;

        width: 20px;
        height: 20px;
        font-weight: 900;
    }

    .interest-form-tag span.active svg {
        color: #0d4036;
    }

    .interest-form-group label[for="revenue"] {
        display: flex;
        flex-direction: column;
        justify-content: start;





    }

    /* Sublabel for revenue field */
    .interest-form-sublabel {
        font-size: 13px;
        font-weight: 400;
        color: rgba(255, 255, 255, 0.7);
        display: block;
        white-space: nowrap;
        margin-top: 3px;
    }

    /* Textarea Styling */
    textarea {
        width: 100%;
        padding: 15px 20px;
        border: none;
        border-radius: 30px;
        /* Highly rounded corners for the single-line input/textarea */
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff;
        font-size: 16px;
        font-family: "Montserrat", sans-serif;
        outline: none;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        box-sizing: border-box;
        resize: none;
        /* Prevent manual resizing */
        border: 1px solid #fff;
    }

    /* Specific styling for the larger description textarea */
    #companyDescription {
        border-radius: 20px;
        /* Slightly less rounded for the multi-line field */
        min-height: 120px;
        padding-top: 20px;
    }

    textarea:focus {
        background-color: rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5);
    }

    textarea::placeholder {
        color: rgba(255, 255, 255, 0.6);
        font-weight: 500;

        font-family: "GT Walsheim Trial Md";
        font-size: 12px;
    }

    /* Question and List Styling */
    .interest-form-question {
        font-size: 12px;
        font-weight: 600;
        color: #f0f0f0;
        margin-left: 40px;
        line-height: 1.5;
    }

    .interest-form-list {
        color: #f0f0f0;
        font-size: 12px;
        font-weight: 500;
        padding-left: 20px;
        margin-left: 40px;
        margin-top: 5px;
    }

    .interest-form-list li {
        margin-bottom: 5px;
    }

    /* Custom Radio Button Group Styling (Three Buttons) */
    .interest-form-option-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 10px;
        justify-content: center;
        /* margin-bottom: 30px; */
    }

    .interest-form-option input[type="radio"] {
        /* Hide the actual radio button */
        display: none;
    }

    .interest-form-option label {
        display: block;
        padding: 12px 18px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 10px;
        font-weight: 600;
        text-align: center;
        transition: background-color 0.3s ease, color 0.3s ease;

        background-color: white;
        border: 1px solid rgba(255, 255, 255, 0.5);
        color: #0d4036;
        margin: 0;
        /* Override default label margin */
        white-space: nowrap;
    }

    /* Checked (Active) State for the Option Button */
    .interest-form-option input[type="radio"]:checked+label {
        background-color: transparent;
        border-color: #ffffff;
        color: #0d4036;
        /* Dark teal text when active */
    }

    .interest-form-option label:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }

    /* Submit Button Override */
    .interest-form-submit-button {
        padding: 13px 45px;
        background-color: #e2f091;
        color: #4a6a00;
        border: none;
        border-radius: 30px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        font-weight: 500;

        font-family: "GT Walsheim Trial Md";
    }

    .interest-form-submit-button:hover {
        background-color: #d6e885;
        transform: translateY(-2px);
    }

    .interest-form-submit-button:active {
        background-color: #cedb7e;
        transform: translateY(0);
    }

    /* Proceed Button Styling */
    .interest-form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 50px;
    }

    .interest-form .first-stage-form-container .interest-form-actions {
        justify-content: end;
    }

    /* Return Button Styling (New) */
    .interest-form-return-button {
        padding: 13px 45px;
        background-color: transparent;
        color: #ffffff;
        /* White text */
        border: 1px solid white;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        font-weight: 500;

        font-family: "GT Walsheim Trial Md";
    }

    .interest-form-return-button:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }

    /* Adjust the proceed button alignment from the previous form to work with the button group */
    .interest-form-proceed-button {
        /* align-self: flex-end; */
        padding: 13px 45px;

        background-color: #cced60;
        color: #0d4036;
        border: none;
        border-radius: 30px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        font-weight: 500;

        font-family: "GT Walsheim Trial Md";
    }

    .interest-form-proceed-button:hover {
        background-color: #d6e885;
        transform: translateY(-2px);
    }

    .interest-form-proceed-button:active {
        background-color: #cedb7e;
        transform: translateY(0);
    }

    .interest-form .second-stage-form-container .interest-form-actions {
        /* width: 50%; */
    }

    .interest-form-layout-container .desktop-heading {
        display: none;
    }

    .interest-form-layout-container .mobile-heading {
        display: block;
    }

    /*====================================================================
            ========================== Interest Footer Container ========================= */
    .interest-form-footer {
        width: 100%;
        margin: 60px auto;
        padding-left: 5px;
        max-width: 650px;
    }

    .interest-form-footer h2 {
        font-size: 25px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 15px 0;
    }

    .interest-form-footer-contact-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 20px;
    }

    .interest-form-footer-contact-row>div:first-of-type {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .interest-form-footer-contact-row>div:last-of-type {
        display: flex;
        justify-content: start;
        align-items: end;
    }

    .interest-form-footer-contact-row>div:last-of-type span:last-of-type {
        margin-bottom: 9px;
    }

    .interest-form-footer-contact-item {
        display: flex;
        align-items: center;
    }

    .interest-form-footer-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #0d4036;
        color: #ffffff;
        font-size: 20px;
        margin-right: 6px;
        flex-shrink: 0;
    }

    .interest-form-footer-contact-item a,
    .interest-form-footer-contact-item span {
        font-size: 13px;
        font-weight: 500;
        color: #555555;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .interest-form-footer-contact-item span i {
        color: #fff;
    }

    .interest-form-footer-contact-item a:hover {
        color: #0d4036;
    }

    .interest-form-footer-contact-item .phone {
        text-decoration: underline;
    }

    /* Specific styling for the location text which is not a link */
    .interest-form-footer-contact-item span:not(.interest-form-footer-icon) {
        color: #555555;
    }

    /* Spinner styling */
    .spinner-border {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        vertical-align: text-bottom;
        border: 0.125em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border 0.75s linear infinite;
    }

    .spinner-border-sm {
        width: 0.875rem;
        height: 0.875rem;
        border-width: 0.1em;
    }

    .d-none {
        display: none !important;
    }

    @keyframes spinner-border {
        to {
            transform: rotate(360deg);
        }
    }

    /* Error styling */
    .error-message {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-control.error {
        border: 2px solid #ef4444 !important;
        background-color: rgba(255, 255, 255, 0.2) !important;
    }

    .form-control:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(25, 62, 71, 0.1);
    }

    /* Phone input styling */
    .phone-input-container {
        position: relative;
    }

    .country-code-select {
        font-size: 12px;
        text-align: center;
        background-color: rgba(255, 255, 255, 0.2) !important;
        border-color: #fff;
        color: #ffffff;
    }

    .country-code-select:focus {
        background-color: rgba(255, 255, 255, 0.3);
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
    }

    .country-code-select option {
        background-color: #137077;
        color: #ffffff;
        padding: 5px;
    }

    .phone-input-container input[name="phone"]:focus {
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
    }

    /* Desktop specific styles */
    @media (min-width: 992px) {
        .form-layout-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            padding-right: 20px;
        }

        .row-two-container {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
        }

        .row-two-container .interest-form-heading.desktop-heading {
            width: 100%;
            margin: 0;
        }

        .form-heading-card {
            max-width: 500px;
        }

        .interest-form-heading .header h1 {
            font-size: 39px;
        }

        .interest-form-heading.desktop-heading .description p {
            font-size: 15px;
        }

        .interest-form-footer {
            margin: 0px;
            padding-left: 0px;
            max-width: auto;
        }

        .interest-form-container .form-container {
            margin-top: 0px;
            max-width: 500px;
            padding: 65px 40px;
        }

        label {
            font-size: 14px;
            font-weight: 400;

            margin-left: 40px;




        }


        .interest-form-group label[for="revenue"] {
            flex-direction: row;
            white-space: nowrap;
            align-items: center;




        }


        .interest-form-return-button,
        .interest-form-proceed-button {
            /* Uses the slightly transparent input background for its base color */
            padding: 15px 60px;
        }

        .interest-form-layout-container .desktop-heading {
            display: block;
        }

        .interest-form-layout-container .mobile-heading {
            display: none;
        }

        .interest-form-footer-contact-row {
            display: flex;
            flex-wrap: wrap;
            align-items: start;
            gap: 20px;
        }

        .interest-form-footer-contact-row>div:first-of-type {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        .interest-form-footer-contact-row>div:last-of-type {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .interest-form-footer-contact-row>div span {
            white-space: nowrap;
        }

        .interest-form-footer-contact-row>div:last-of-type span:last-of-type {
            margin-bottom: 0px;
        }
    }

    @media (min-width: 1440px) {
        .form-layout-container {
            padding: 0px;
        }
    }
</style>

@section('content')
    <section class="interest-form-layout-container">
        <div class="form-layout-container">
            <div class="row-one-container">
                <!-- Mobile Heading -->
                <section class="interest-form-heading mobile-heading">
                    <!-- First Form Heading -->
                    <div class="first-form-heading hidden">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>Basic Information</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Let's start with your basic details</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Form Heading -->
                    <div class="second-form-heading hidden">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>Credibility Check</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Stand out among other businesses</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Third Form Heading -->
                    <div class="third-form-heading hidden">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>Industry Preference</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Boost visibility for your matches</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fourth Form Heading -->
                    <div class="fourth-form-heading">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>Industry Insight</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Stand out among other businesses</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Form Section -->
                <section class="interest-form-container">
                    <div class="form-container">
                        <form class="interest-form" id="brnForm" action="{{ route('brn.submit') }}" method="POST">
                            @csrf
                            <!-- First Stage Form -->
                            <div class="first-stage-form-container hidden">
                                <div class="interest-form-group">
                                    <label for="fullName">Full Name</label>
                                    <input type="text" id="fullName" name="name" placeholder="Input Full name" />
                                </div>

                                <div class="interest-form-group">
                                    <label for="title">Title</label>
                                    <div class="custom-select" id="customSelect" data-select="title">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Input your title, e.g CEO</span>
                                            <svg class="custom-select-arrow" xmlns="http://www.w3.org/2000/svg"
                                                width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </div>
                                        <div class="custom-options">
                                            <span class="custom-option" data-value="CEO">CEO</span>
                                            <span class="custom-option" data-value="CTO">CTO</span>
                                            <span class="custom-option" data-value="CFO">CFO</span>
                                            <span class="custom-option" data-value="Manager">Manager</span>
                                            <span class="custom-option" data-value="Director">Director</span>
                                            <span class="custom-option" data-value="Other">Other</span>
                                        </div>
                                    </div>

                                    <!-- Hidden native select for form submission -->
                                    <select id="title" name="position" style="display: none">
                                        <option value="">Input your title, e.g CEO</option>
                                        <option value="CEO">CEO</option>
                                        <option value="CTO">CTO</option>
                                        <option value="CFO">CFO</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Director">Director</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="interest-form-group">
                                    <label for="businessName">Official Business Name</label>
                                    <input type="text" id="businessName" name="company_name"
                                        placeholder="Input your business Name" />
                                </div>

                                <div class="interest-form-group">
                                    <label for="emailAddress">Email Address</label>
                                    <input type="email" id="emailAddress" name="email"
                                        placeholder="Input Email Address" />
                                    <!-- Email validation status -->
                                    <div id="email-status" style="display: none; margin-top: 8px; font-size: 0.875rem;">
                                        <span id="email-checkmark" style="color: #10b981; display: none;">✓ Email
                                            available</span>
                                        <span id="email-spinner" style="color: #6b7280; display: none;">⏳
                                            Validating...</span>
                                    </div>
                                </div>

                                <div class="interest-form-group">
                                    <label for="phoneNumber">Phone Number</label>
                                    <div class="phone-input-container" style="display: flex; gap: 8px;">
                                        <!-- Country code select -->
                                        <select name="country_code" class="country-code-select"
                                            style="flex: 0 0 130px; border-radius: 30px; padding: 18px 12px; border: 1px solid #fff; background-color: rgba(255, 255, 255, 0.2) !important; color: #ffffff; font-size: 12px; outline: none;">
                                            <option value="+93">🇦🇫 +93</option>
                                            <option value="+355">🇦🇱 +355</option>
                                            <option value="+213">🇩🇿 +213</option>
                                            <option value="+376">🇦🇩 +376</option>
                                            <option value="+244">🇦🇴 +244</option>
                                            <option value="+54">🇦🇷 +54</option>
                                            <option value="+374">🇦🇲 +374</option>
                                            <option value="+61">🇦🇺 +61</option>
                                            <option value="+43">🇦🇹 +43</option>
                                            <option value="+994">🇦🇿 +994</option>
                                            <option value="+973">🇧🇭 +973</option>
                                            <option value="+880">🇧🇩 +880</option>
                                            <option value="+375">🇧🇾 +375</option>
                                            <option value="+32">🇧🇪 +32</option>
                                            <option value="+229">🇧🇯 +229</option>
                                            <option value="+975">🇧🇹 +975</option>
                                            <option value="+591">🇧🇴 +591</option>
                                            <option value="+55">🇧🇷 +55</option>
                                            <option value="+673">🇧🇳 +673</option>
                                            <option value="+359">🇧🇬 +359</option>
                                            <option value="+226">🇧🇫 +226</option>
                                            <option value="+257">🇧🇮 +257</option>
                                            <option value="+855">🇰🇭 +855</option>
                                            <option value="+237">🇨🇲 +237</option>
                                            <option value="+1">🇨🇦 +1</option>
                                            <option value="+238">🇨🇻 +238</option>
                                            <option value="+236">🇨🇫 +236</option>
                                            <option value="+235">🇹🇩 +235</option>
                                            <option value="+56">🇨🇱 +56</option>
                                            <option value="+86">🇨🇳 +86</option>
                                            <option value="+57">🇨🇴 +57</option>
                                            <option value="+269">🇰🇲 +269</option>
                                            <option value="+242">🇨🇬 +242</option>
                                            <option value="+243">🇨🇩 +243</option>
                                            <option value="+506">🇨🇷 +506</option>
                                            <option value="+225">🇨🇮 +225</option>
                                            <option value="+385">🇭🇷 +385</option>
                                            <option value="+53">🇨🇺 +53</option>
                                            <option value="+357">🇨🇾 +357</option>
                                            <option value="+420">🇨🇿 +420</option>
                                            <option value="+45">🇩🇰 +45</option>
                                            <option value="+253">🇩🇯 +253</option>
                                            <option value="+593">🇪🇨 +593</option>
                                            <option value="+20">🇪🇬 +20</option>
                                            <option value="+503">🇸🇻 +503</option>
                                            <option value="+240">🇬🇶 +240</option>
                                            <option value="+291">🇪🇷 +291</option>
                                            <option value="+372">🇪🇪 +372</option>
                                            <option value="+251">🇪🇹 +251</option>
                                            <option value="+679">🇫🇯 +679</option>
                                            <option value="+358">🇫🇮 +358</option>
                                            <option value="+33">🇫🇷 +33</option>
                                            <option value="+241">🇬🇦 +241</option>
                                            <option value="+220">🇬🇲 +220</option>
                                            <option value="+995">🇬🇪 +995</option>
                                            <option value="+49">🇩🇪 +49</option>
                                            <option value="+233">🇬🇭 +233</option>
                                            <option value="+30">🇬🇷 +30</option>
                                            <option value="+502">🇬🇹 +502</option>
                                            <option value="+224">🇬🇳 +224</option>
                                            <option value="+245">🇬🇼 +245</option>
                                            <option value="+592">🇬🇾 +592</option>
                                            <option value="+509">🇭🇹 +509</option>
                                            <option value="+504">🇭🇳 +504</option>
                                            <option value="+852">🇭🇰 +852</option>
                                            <option value="+36">🇭🇺 +36</option>
                                            <option value="+354">🇮🇸 +354</option>
                                            <option value="+91">🇮🇳 +91</option>
                                            <option value="+62">🇮🇩 +62</option>
                                            <option value="+98">🇮🇷 +98</option>
                                            <option value="+964">🇮🇶 +964</option>
                                            <option value="+353">🇮🇪 +353</option>
                                            <option value="+972">🇮🇱 +972</option>
                                            <option value="+39">🇮🇹 +39</option>
                                            <option value="+81">🇯🇵 +81</option>
                                            <option value="+962">🇯🇴 +962</option>
                                            <option value="+7">🇰🇿 +7</option>
                                            <option value="+254">🇰🇪 +254</option>
                                            <option value="+686">🇰🇮 +686</option>
                                            <option value="+850">🇰🇵 +850</option>
                                            <option value="+82">🇰🇷 +82</option>
                                            <option value="+965">🇰🇼 +965</option>
                                            <option value="+996">🇰🇬 +996</option>
                                            <option value="+856">🇱🇦 +856</option>
                                            <option value="+371">🇱🇻 +371</option>
                                            <option value="+961">🇱🇧 +961</option>
                                            <option value="+266">🇱🇸 +266</option>
                                            <option value="+231">🇱🇷 +231</option>
                                            <option value="+218">🇱🇾 +218</option>
                                            <option value="+423">🇱🇮 +423</option>
                                            <option value="+370">🇱🇹 +370</option>
                                            <option value="+352">🇱🇺 +352</option>
                                            <option value="+853">🇲🇴 +853</option>
                                            <option value="+389">🇲🇰 +389</option>
                                            <option value="+261">🇲🇬 +261</option>
                                            <option value="+265">🇲🇼 +265</option>
                                            <option value="+60">🇲🇾 +60</option>
                                            <option value="+960">🇲🇻 +960</option>
                                            <option value="+223">🇲🇱 +223</option>
                                            <option value="+356">🇲🇹 +356</option>
                                            <option value="+692">🇲🇭 +692</option>
                                            <option value="+222">🇲🇷 +222</option>
                                            <option value="+230">🇲🇺 +230</option>
                                            <option value="+52">🇲🇽 +52</option>
                                            <option value="+691">🇫🇲 +691</option>
                                            <option value="+373">🇲🇩 +373</option>
                                            <option value="+377">🇲🇨 +377</option>
                                            <option value="+976">🇲🇳 +976</option>
                                            <option value="+382">🇲🇪 +382</option>
                                            <option value="+212">🇲🇦 +212</option>
                                            <option value="+258">🇲🇿 +258</option>
                                            <option value="+95">🇲🇲 +95</option>
                                            <option value="+264">🇳🇦 +264</option>
                                            <option value="+674">🇳🇷 +674</option>
                                            <option value="+977">🇳🇵 +977</option>
                                            <option value="+31">🇳🇱 +31</option>
                                            <option value="+64">🇳🇿 +64</option>
                                            <option value="+505">🇳🇮 +505</option>
                                            <option value="+227">🇳🇪 +227</option>
                                            <option value="+234" selected>🇳🇬 +234</option>
                                            <option value="+683">🇳🇺 +683</option>
                                            <option value="+672">🇳🇫 +672</option>
                                            <option value="+47">🇳🇴 +47</option>
                                            <option value="+968">🇴🇲 +968</option>
                                            <option value="+92">🇵🇰 +92</option>
                                            <option value="+680">🇵🇼 +680</option>
                                            <option value="+507">🇵🇦 +507</option>
                                            <option value="+675">🇵🇬 +675</option>
                                            <option value="+595">🇵🇾 +595</option>
                                            <option value="+51">🇵🇪 +51</option>
                                            <option value="+63">🇵🇭 +63</option>
                                            <option value="+48">🇵🇱 +48</option>
                                            <option value="+351">🇵🇹 +351</option>
                                            <option value="+974">🇶🇦 +974</option>
                                            <option value="+40">🇷🇴 +40</option>
                                            <option value="+7">🇷🇺 +7</option>
                                            <option value="+250">🇷🇼 +250</option>
                                            <option value="+685">🇼🇸 +685</option>
                                            <option value="+378">🇸🇲 +378</option>
                                            <option value="+239">🇸🇹 +239</option>
                                            <option value="+966">🇸🇦 +966</option>
                                            <option value="+221">🇸🇳 +221</option>
                                            <option value="+381">🇷🇸 +381</option>
                                            <option value="+248">🇸🇨 +248</option>
                                            <option value="+232">🇸🇱 +232</option>
                                            <option value="+65">🇸🇬 +65</option>
                                            <option value="+421">🇸🇰 +421</option>
                                            <option value="+386">🇸🇮 +386</option>
                                            <option value="+677">🇸🇧 +677</option>
                                            <option value="+252">🇸🇴 +252</option>
                                            <option value="+27">🇿🇦 +27</option>
                                            <option value="+211">🇸🇸 +211</option>
                                            <option value="+34">🇪🇸 +34</option>
                                            <option value="+94">🇱🇰 +94</option>
                                            <option value="+249">🇸🇩 +249</option>
                                            <option value="+597">🇸🇷 +597</option>
                                            <option value="+268">🇸🇿 +268</option>
                                            <option value="+46">🇸🇪 +46</option>
                                            <option value="+41">🇨🇭 +41</option>
                                            <option value="+963">🇸🇾 +963</option>
                                            <option value="+886">🇹🇼 +886</option>
                                            <option value="+992">🇹🇯 +992</option>
                                            <option value="+255">🇹🇿 +255</option>
                                            <option value="+66">🇹🇭 +66</option>
                                            <option value="+670">🇹🇱 +670</option>
                                            <option value="+228">🇹🇬 +228</option>
                                            <option value="+676">🇹🇴 +676</option>
                                            <option value="+216">🇹🇳 +216</option>
                                            <option value="+90">🇹🇷 +90</option>
                                            <option value="+993">🇹🇲 +993</option>
                                            <option value="+688">🇹🇻 +688</option>
                                            <option value="+256">🇺🇬 +256</option>
                                            <option value="+380">🇺🇦 +380</option>
                                            <option value="+971">🇦🇪 +971</option>
                                            <option value="+44">🇬🇧 +44</option>
                                            <option value="+1">🇺🇸 +1</option>
                                            <option value="+598">🇺🇾 +598</option>
                                            <option value="+998">🇺🇿 +998</option>
                                            <option value="+678">🇻🇺 +678</option>
                                            <option value="+39">🇻🇦 +39</option>
                                            <option value="+58">🇻🇪 +58</option>
                                            <option value="+84">🇻🇳 +84</option>
                                            <option value="+967">🇾🇪 +967</option>
                                            <option value="+260">🇿🇲 +260</option>
                                            <option value="+263">🇿🇼 +263</option>
                                        </select>
                                        <!-- Phone number input -->
                                        <input type="tel" id="phoneNumber" name="phone"
                                            placeholder="Input phone number" style="flex: 1;" />
                                    </div>
                                </div>

                                <div class="interest-form-actions">
                                    <button type="button" class="interest-form-proceed-button" data-next="form2">
                                        Proceed
                                    </button>
                                </div>
                            </div>

                            <!-- Second Form -->
                            <div class="second-stage-form-container hidden">
                                <div class="interest-form-group">
                                    <label for="regNumber">Company Registration Number</label>
                                    <input type="text" id="regNumber" name="reg_number"
                                        placeholder="Input you company Reg. Number" />
                                </div>

                                <div class="interest-form-group">
                                    <label for="countryReg">Country Of Registration</label>
                                    <div class="custom-select" data-select="country">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Select your country</span>
                                            <svg class="custom-select-arrow" xmlns="http://www.w3.org/2000/svg"
                                                width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </div>
                                        <div class="custom-options">
                                            <input type="text" class="country-search"
                                                placeholder="Search countries..." autocomplete="off">
                                            <div class="custom-options-scrollable">
                                                <span class="custom-option" data-value="Afghanistan">Afghanistan</span>
                                                <span class="custom-option" data-value="Albania">Albania</span>
                                                <span class="custom-option" data-value="Algeria">Algeria</span>
                                                <span class="custom-option" data-value="Andorra">Andorra</span>
                                                <span class="custom-option" data-value="Angola">Angola</span>
                                                <span class="custom-option" data-value="Argentina">Argentina</span>
                                                <span class="custom-option" data-value="Armenia">Armenia</span>
                                                <span class="custom-option" data-value="Australia">Australia</span>
                                                <span class="custom-option" data-value="Austria">Austria</span>
                                                <span class="custom-option" data-value="Azerbaijan">Azerbaijan</span>
                                                <span class="custom-option" data-value="Bahamas">Bahamas</span>
                                                <span class="custom-option" data-value="Bahrain">Bahrain</span>
                                                <span class="custom-option" data-value="Bangladesh">Bangladesh</span>
                                                <span class="custom-option" data-value="Barbados">Barbados</span>
                                                <span class="custom-option" data-value="Belarus">Belarus</span>
                                                <span class="custom-option" data-value="Belgium">Belgium</span>
                                                <span class="custom-option" data-value="Belize">Belize</span>
                                                <span class="custom-option" data-value="Benin">Benin</span>
                                                <span class="custom-option" data-value="Bhutan">Bhutan</span>
                                                <span class="custom-option" data-value="Bolivia">Bolivia</span>
                                                <span class="custom-option" data-value="Bosnia and Herzegovina">Bosnia and
                                                    Herzegovina</span>
                                                <span class="custom-option" data-value="Botswana">Botswana</span>
                                                <span class="custom-option" data-value="Brazil">Brazil</span>
                                                <span class="custom-option" data-value="Brunei">Brunei</span>
                                                <span class="custom-option" data-value="Bulgaria">Bulgaria</span>
                                                <span class="custom-option" data-value="Burkina Faso">Burkina Faso</span>
                                                <span class="custom-option" data-value="Burundi">Burundi</span>
                                                <span class="custom-option" data-value="Cambodia">Cambodia</span>
                                                <span class="custom-option" data-value="Cameroon">Cameroon</span>
                                                <span class="custom-option" data-value="Canada">Canada</span>
                                                <span class="custom-option" data-value="Cape Verde">Cape Verde</span>
                                                <span class="custom-option" data-value="Central African Republic">Central
                                                    African Republic</span>
                                                <span class="custom-option" data-value="Chad">Chad</span>
                                                <span class="custom-option" data-value="Chile">Chile</span>
                                                <span class="custom-option" data-value="China">China</span>
                                                <span class="custom-option" data-value="Colombia">Colombia</span>
                                                <span class="custom-option" data-value="Comoros">Comoros</span>
                                                <span class="custom-option" data-value="Congo">Congo</span>
                                                <span class="custom-option" data-value="Costa Rica">Costa Rica</span>
                                                <span class="custom-option" data-value="Croatia">Croatia</span>
                                                <span class="custom-option" data-value="Cuba">Cuba</span>
                                                <span class="custom-option" data-value="Cyprus">Cyprus</span>
                                                <span class="custom-option" data-value="Czech Republic">Czech
                                                    Republic</span>
                                                <span class="custom-option" data-value="Denmark">Denmark</span>
                                                <span class="custom-option" data-value="Djibouti">Djibouti</span>
                                                <span class="custom-option" data-value="Dominica">Dominica</span>
                                                <span class="custom-option" data-value="Dominican Republic">Dominican
                                                    Republic</span>
                                                <span class="custom-option" data-value="East Timor">East Timor</span>
                                                <span class="custom-option" data-value="Ecuador">Ecuador</span>
                                                <span class="custom-option" data-value="Egypt">Egypt</span>
                                                <span class="custom-option" data-value="El Salvador">El Salvador</span>
                                                <span class="custom-option" data-value="Equatorial Guinea">Equatorial
                                                    Guinea</span>
                                                <span class="custom-option" data-value="Eritrea">Eritrea</span>
                                                <span class="custom-option" data-value="Estonia">Estonia</span>
                                                <span class="custom-option" data-value="Ethiopia">Ethiopia</span>
                                                <span class="custom-option" data-value="Fiji">Fiji</span>
                                                <span class="custom-option" data-value="Finland">Finland</span>
                                                <span class="custom-option" data-value="France">France</span>
                                                <span class="custom-option" data-value="Gabon">Gabon</span>
                                                <span class="custom-option" data-value="Gambia">Gambia</span>
                                                <span class="custom-option" data-value="Georgia">Georgia</span>
                                                <span class="custom-option" data-value="Germany">Germany</span>
                                                <span class="custom-option" data-value="Ghana">Ghana</span>
                                                <span class="custom-option" data-value="Greece">Greece</span>
                                                <span class="custom-option" data-value="Grenada">Grenada</span>
                                                <span class="custom-option" data-value="Guatemala">Guatemala</span>
                                                <span class="custom-option" data-value="Guinea">Guinea</span>
                                                <span class="custom-option"
                                                    data-value="Guinea-Bissau">Guinea-Bissau</span>
                                                <span class="custom-option" data-value="Guyana">Guyana</span>
                                                <span class="custom-option" data-value="Haiti">Haiti</span>
                                                <span class="custom-option" data-value="Honduras">Honduras</span>
                                                <span class="custom-option" data-value="Hungary">Hungary</span>
                                                <span class="custom-option" data-value="Iceland">Iceland</span>
                                                <span class="custom-option" data-value="India">India</span>
                                                <span class="custom-option" data-value="Indonesia">Indonesia</span>
                                                <span class="custom-option" data-value="Iran">Iran</span>
                                                <span class="custom-option" data-value="Iraq">Iraq</span>
                                                <span class="custom-option" data-value="Ireland">Ireland</span>
                                                <span class="custom-option" data-value="Israel">Israel</span>
                                                <span class="custom-option" data-value="Italy">Italy</span>
                                                <span class="custom-option" data-value="Ivory Coast">Ivory Coast</span>
                                                <span class="custom-option" data-value="Jamaica">Jamaica</span>
                                                <span class="custom-option" data-value="Japan">Japan</span>
                                                <span class="custom-option" data-value="Jordan">Jordan</span>
                                                <span class="custom-option" data-value="Kazakhstan">Kazakhstan</span>
                                                <span class="custom-option" data-value="Kenya">Kenya</span>
                                                <span class="custom-option" data-value="Kiribati">Kiribati</span>
                                                <span class="custom-option" data-value="North Korea">North Korea</span>
                                                <span class="custom-option" data-value="South Korea">South Korea</span>
                                                <span class="custom-option" data-value="Kuwait">Kuwait</span>
                                                <span class="custom-option" data-value="Kyrgyzstan">Kyrgyzstan</span>
                                                <span class="custom-option" data-value="Laos">Laos</span>
                                                <span class="custom-option" data-value="Latvia">Latvia</span>
                                                <span class="custom-option" data-value="Lebanon">Lebanon</span>
                                                <span class="custom-option" data-value="Lesotho">Lesotho</span>
                                                <span class="custom-option" data-value="Liberia">Liberia</span>
                                                <span class="custom-option" data-value="Libya">Libya</span>
                                                <span class="custom-option"
                                                    data-value="Liechtenstein">Liechtenstein</span>
                                                <span class="custom-option" data-value="Lithuania">Lithuania</span>
                                                <span class="custom-option" data-value="Luxembourg">Luxembourg</span>
                                                <span class="custom-option" data-value="Macedonia">Macedonia</span>
                                                <span class="custom-option" data-value="Madagascar">Madagascar</span>
                                                <span class="custom-option" data-value="Malawi">Malawi</span>
                                                <span class="custom-option" data-value="Malaysia">Malaysia</span>
                                                <span class="custom-option" data-value="Maldives">Maldives</span>
                                                <span class="custom-option" data-value="Mali">Mali</span>
                                                <span class="custom-option" data-value="Malta">Malta</span>
                                                <span class="custom-option" data-value="Marshall Islands">Marshall
                                                    Islands</span>
                                                <span class="custom-option" data-value="Mauritania">Mauritania</span>
                                                <span class="custom-option" data-value="Mauritius">Mauritius</span>
                                                <span class="custom-option" data-value="Mexico">Mexico</span>
                                                <span class="custom-option" data-value="Micronesia">Micronesia</span>
                                                <span class="custom-option" data-value="Moldova">Moldova</span>
                                                <span class="custom-option" data-value="Monaco">Monaco</span>
                                                <span class="custom-option" data-value="Mongolia">Mongolia</span>
                                                <span class="custom-option" data-value="Montenegro">Montenegro</span>
                                                <span class="custom-option" data-value="Morocco">Morocco</span>
                                                <span class="custom-option" data-value="Mozambique">Mozambique</span>
                                                <span class="custom-option" data-value="Myanmar">Myanmar</span>
                                                <span class="custom-option" data-value="Namibia">Namibia</span>
                                                <span class="custom-option" data-value="Nauru">Nauru</span>
                                                <span class="custom-option" data-value="Nepal">Nepal</span>
                                                <span class="custom-option" data-value="Netherlands">Netherlands</span>
                                                <span class="custom-option" data-value="New Zealand">New Zealand</span>
                                                <span class="custom-option" data-value="Nicaragua">Nicaragua</span>
                                                <span class="custom-option" data-value="Niger">Niger</span>
                                                <span class="custom-option" data-value="Nigeria">Nigeria</span>
                                                <span class="custom-option" data-value="Norway">Norway</span>
                                                <span class="custom-option" data-value="Oman">Oman</span>
                                                <span class="custom-option" data-value="Pakistan">Pakistan</span>
                                                <span class="custom-option" data-value="Palau">Palau</span>
                                                <span class="custom-option" data-value="Panama">Panama</span>
                                                <span class="custom-option" data-value="Papua New Guinea">Papua New
                                                    Guinea</span>
                                                <span class="custom-option" data-value="Paraguay">Paraguay</span>
                                                <span class="custom-option" data-value="Peru">Peru</span>
                                                <span class="custom-option" data-value="Philippines">Philippines</span>
                                                <span class="custom-option" data-value="Poland">Poland</span>
                                                <span class="custom-option" data-value="Portugal">Portugal</span>
                                                <span class="custom-option" data-value="Qatar">Qatar</span>
                                                <span class="custom-option" data-value="Romania">Romania</span>
                                                <span class="custom-option" data-value="Russia">Russia</span>
                                                <span class="custom-option" data-value="Rwanda">Rwanda</span>
                                                <span class="custom-option" data-value="Saint Kitts and Nevis">Saint Kitts
                                                    and
                                                    Nevis</span>
                                                <span class="custom-option" data-value="Saint Lucia">Saint Lucia</span>
                                                <span class="custom-option"
                                                    data-value="Saint Vincent and the Grenadines">Saint Vincent and the
                                                    Grenadines</span>
                                                <span class="custom-option" data-value="Samoa">Samoa</span>
                                                <span class="custom-option" data-value="San Marino">San Marino</span>
                                                <span class="custom-option" data-value="Sao Tome and Principe">Sao Tome
                                                    and
                                                    Principe</span>
                                                <span class="custom-option" data-value="Saudi Arabia">Saudi Arabia</span>
                                                <span class="custom-option" data-value="Senegal">Senegal</span>
                                                <span class="custom-option" data-value="Serbia">Serbia</span>
                                                <span class="custom-option" data-value="Seychelles">Seychelles</span>
                                                <span class="custom-option" data-value="Sierra Leone">Sierra Leone</span>
                                                <span class="custom-option" data-value="Singapore">Singapore</span>
                                                <span class="custom-option" data-value="Slovakia">Slovakia</span>
                                                <span class="custom-option" data-value="Slovenia">Slovenia</span>
                                                <span class="custom-option" data-value="Solomon Islands">Solomon
                                                    Islands</span>
                                                <span class="custom-option" data-value="Somalia">Somalia</span>
                                                <span class="custom-option" data-value="South Africa">South Africa</span>
                                                <span class="custom-option" data-value="South Sudan">South Sudan</span>
                                                <span class="custom-option" data-value="Spain">Spain</span>
                                                <span class="custom-option" data-value="Sri Lanka">Sri Lanka</span>
                                                <span class="custom-option" data-value="Sudan">Sudan</span>
                                                <span class="custom-option" data-value="Suriname">Suriname</span>
                                                <span class="custom-option" data-value="Swaziland">Swaziland</span>
                                                <span class="custom-option" data-value="Sweden">Sweden</span>
                                                <span class="custom-option" data-value="Switzerland">Switzerland</span>
                                                <span class="custom-option" data-value="Syria">Syria</span>
                                                <span class="custom-option" data-value="Taiwan">Taiwan</span>
                                                <span class="custom-option" data-value="Tajikistan">Tajikistan</span>
                                                <span class="custom-option" data-value="Tanzania">Tanzania</span>
                                                <span class="custom-option" data-value="Thailand">Thailand</span>
                                                <span class="custom-option" data-value="Togo">Togo</span>
                                                <span class="custom-option" data-value="Tonga">Tonga</span>
                                                <span class="custom-option" data-value="Trinidad and Tobago">Trinidad and
                                                    Tobago</span>
                                                <span class="custom-option" data-value="Tunisia">Tunisia</span>
                                                <span class="custom-option" data-value="Turkey">Turkey</span>
                                                <span class="custom-option" data-value="Turkmenistan">Turkmenistan</span>
                                                <span class="custom-option" data-value="Tuvalu">Tuvalu</span>
                                                <span class="custom-option" data-value="Uganda">Uganda</span>
                                                <span class="custom-option" data-value="Ukraine">Ukraine</span>
                                                <span class="custom-option" data-value="United Arab Emirates">United Arab
                                                    Emirates</span>
                                                <span class="custom-option" data-value="United Kingdom">United
                                                    Kingdom</span>
                                                <span class="custom-option" data-value="United States">United
                                                    States</span>
                                                <span class="custom-option" data-value="Uruguay">Uruguay</span>
                                                <span class="custom-option" data-value="Uzbekistan">Uzbekistan</span>
                                                <span class="custom-option" data-value="Vanuatu">Vanuatu</span>
                                                <span class="custom-option" data-value="Vatican City">Vatican City</span>
                                                <span class="custom-option" data-value="Venezuela">Venezuela</span>
                                                <span class="custom-option" data-value="Vietnam">Vietnam</span>
                                                <span class="custom-option" data-value="Yemen">Yemen</span>
                                                <span class="custom-option" data-value="Zambia">Zambia</span>
                                                <span class="custom-option" data-value="Zimbabwe">Zimbabwe</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden native select for form submission -->
                                    <select id="country" name="country" style="display: none">
                                        <option value="">Select your country</option>
                                        <option value="Afghanistan">Afghanistan</option>
                                        <option value="Albania">Albania</option>
                                        <option value="Algeria">Algeria</option>
                                        <option value="Andorra">Andorra</option>
                                        <option value="Angola">Angola</option>
                                        <option value="Argentina">Argentina</option>
                                        <option value="Armenia">Armenia</option>
                                        <option value="Australia">Australia</option>
                                        <option value="Austria">Austria</option>
                                        <option value="Azerbaijan">Azerbaijan</option>
                                        <option value="Bahamas">Bahamas</option>
                                        <option value="Bahrain">Bahrain</option>
                                        <option value="Bangladesh">Bangladesh</option>
                                        <option value="Barbados">Barbados</option>
                                        <option value="Belarus">Belarus</option>
                                        <option value="Belgium">Belgium</option>
                                        <option value="Belize">Belize</option>
                                        <option value="Benin">Benin</option>
                                        <option value="Bhutan">Bhutan</option>
                                        <option value="Bolivia">Bolivia</option>
                                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                        <option value="Botswana">Botswana</option>
                                        <option value="Brazil">Brazil</option>
                                        <option value="Brunei">Brunei</option>
                                        <option value="Bulgaria">Bulgaria</option>
                                        <option value="Burkina Faso">Burkina Faso</option>
                                        <option value="Burundi">Burundi</option>
                                        <option value="Cambodia">Cambodia</option>
                                        <option value="Cameroon">Cameroon</option>
                                        <option value="Canada">Canada</option>
                                        <option value="Cape Verde">Cape Verde</option>
                                        <option value="Central African Republic">Central African Republic</option>
                                        <option value="Chad">Chad</option>
                                        <option value="Chile">Chile</option>
                                        <option value="China">China</option>
                                        <option value="Colombia">Colombia</option>
                                        <option value="Comoros">Comoros</option>
                                        <option value="Congo">Congo</option>
                                        <option value="Costa Rica">Costa Rica</option>
                                        <option value="Croatia">Croatia</option>
                                        <option value="Cuba">Cuba</option>
                                        <option value="Cyprus">Cyprus</option>
                                        <option value="Czech Republic">Czech Republic</option>
                                        <option value="Denmark">Denmark</option>
                                        <option value="Djibouti">Djibouti</option>
                                        <option value="Dominica">Dominica</option>
                                        <option value="Dominican Republic">Dominican Republic</option>
                                        <option value="East Timor">East Timor</option>
                                        <option value="Ecuador">Ecuador</option>
                                        <option value="Egypt">Egypt</option>
                                        <option value="El Salvador">El Salvador</option>
                                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                                        <option value="Eritrea">Eritrea</option>
                                        <option value="Estonia">Estonia</option>
                                        <option value="Ethiopia">Ethiopia</option>
                                        <option value="Fiji">Fiji</option>
                                        <option value="Finland">Finland</option>
                                        <option value="France">France</option>
                                        <option value="Gabon">Gabon</option>
                                        <option value="Gambia">Gambia</option>
                                        <option value="Georgia">Georgia</option>
                                        <option value="Germany">Germany</option>
                                        <option value="Ghana">Ghana</option>
                                        <option value="Greece">Greece</option>
                                        <option value="Grenada">Grenada</option>
                                        <option value="Guatemala">Guatemala</option>
                                        <option value="Guinea">Guinea</option>
                                        <option value="Guinea-Bissau">Guinea-Bissau</option>
                                        <option value="Guyana">Guyana</option>
                                        <option value="Haiti">Haiti</option>
                                        <option value="Honduras">Honduras</option>
                                        <option value="Hungary">Hungary</option>
                                        <option value="Iceland">Iceland</option>
                                        <option value="India">India</option>
                                        <option value="Indonesia">Indonesia</option>
                                        <option value="Iran">Iran</option>
                                        <option value="Iraq">Iraq</option>
                                        <option value="Ireland">Ireland</option>
                                        <option value="Israel">Israel</option>
                                        <option value="Italy">Italy</option>
                                        <option value="Ivory Coast">Ivory Coast</option>
                                        <option value="Jamaica">Jamaica</option>
                                        <option value="Japan">Japan</option>
                                        <option value="Jordan">Jordan</option>
                                        <option value="Kazakhstan">Kazakhstan</option>
                                        <option value="Kenya">Kenya</option>
                                        <option value="Kiribati">Kiribati</option>
                                        <option value="North Korea">North Korea</option>
                                        <option value="South Korea">South Korea</option>
                                        <option value="Kuwait">Kuwait</option>
                                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                                        <option value="Laos">Laos</option>
                                        <option value="Latvia">Latvia</option>
                                        <option value="Lebanon">Lebanon</option>
                                        <option value="Lesotho">Lesotho</option>
                                        <option value="Liberia">Liberia</option>
                                        <option value="Libya">Libya</option>
                                        <option value="Liechtenstein">Liechtenstein</option>
                                        <option value="Lithuania">Lithuania</option>
                                        <option value="Luxembourg">Luxembourg</option>
                                        <option value="Macedonia">Macedonia</option>
                                        <option value="Madagascar">Madagascar</option>
                                        <option value="Malawi">Malawi</option>
                                        <option value="Malaysia">Malaysia</option>
                                        <option value="Maldives">Maldives</option>
                                        <option value="Mali">Mali</option>
                                        <option value="Malta">Malta</option>
                                        <option value="Marshall Islands">Marshall Islands</option>
                                        <option value="Mauritania">Mauritania</option>
                                        <option value="Mauritius">Mauritius</option>
                                        <option value="Mexico">Mexico</option>
                                        <option value="Micronesia">Micronesia</option>
                                        <option value="Moldova">Moldova</option>
                                        <option value="Monaco">Monaco</option>
                                        <option value="Mongolia">Mongolia</option>
                                        <option value="Montenegro">Montenegro</option>
                                        <option value="Morocco">Morocco</option>
                                        <option value="Mozambique">Mozambique</option>
                                        <option value="Myanmar">Myanmar</option>
                                        <option value="Namibia">Namibia</option>
                                        <option value="Nauru">Nauru</option>
                                        <option value="Nepal">Nepal</option>
                                        <option value="Netherlands">Netherlands</option>
                                        <option value="New Zealand">New Zealand</option>
                                        <option value="Nicaragua">Nicaragua</option>
                                        <option value="Niger">Niger</option>
                                        <option value="Nigeria">Nigeria</option>
                                        <option value="Norway">Norway</option>
                                        <option value="Oman">Oman</option>
                                        <option value="Pakistan">Pakistan</option>
                                        <option value="Palau">Palau</option>
                                        <option value="Panama">Panama</option>
                                        <option value="Papua New Guinea">Papua New Guinea</option>
                                        <option value="Paraguay">Paraguay</option>
                                        <option value="Peru">Peru</option>
                                        <option value="Philippines">Philippines</option>
                                        <option value="Poland">Poland</option>
                                        <option value="Portugal">Portugal</option>
                                        <option value="Qatar">Qatar</option>
                                        <option value="Romania">Romania</option>
                                        <option value="Russia">Russia</option>
                                        <option value="Rwanda">Rwanda</option>
                                        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                        <option value="Saint Lucia">Saint Lucia</option>
                                        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines
                                        </option>
                                        <option value="Samoa">Samoa</option>
                                        <option value="San Marino">San Marino</option>
                                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                        <option value="Saudi Arabia">Saudi Arabia</option>
                                        <option value="Senegal">Senegal</option>
                                        <option value="Serbia">Serbia</option>
                                        <option value="Seychelles">Seychelles</option>
                                        <option value="Sierra Leone">Sierra Leone</option>
                                        <option value="Singapore">Singapore</option>
                                        <option value="Slovakia">Slovakia</option>
                                        <option value="Slovenia">Slovenia</option>
                                        <option value="Solomon Islands">Solomon Islands</option>
                                        <option value="Somalia">Somalia</option>
                                        <option value="South Africa">South Africa</option>
                                        <option value="South Sudan">South Sudan</option>
                                        <option value="Spain">Spain</option>
                                        <option value="Sri Lanka">Sri Lanka</option>
                                        <option value="Sudan">Sudan</option>
                                        <option value="Suriname">Suriname</option>
                                        <option value="Swaziland">Swaziland</option>
                                        <option value="Sweden">Sweden</option>
                                        <option value="Switzerland">Switzerland</option>
                                        <option value="Syria">Syria</option>
                                        <option value="Taiwan">Taiwan</option>
                                        <option value="Tajikistan">Tajikistan</option>
                                        <option value="Tanzania">Tanzania</option>
                                        <option value="Thailand">Thailand</option>
                                        <option value="Togo">Togo</option>
                                        <option value="Tonga">Tonga</option>
                                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                        <option value="Tunisia">Tunisia</option>
                                        <option value="Turkey">Turkey</option>
                                        <option value="Turkmenistan">Turkmenistan</option>
                                        <option value="Tuvalu">Tuvalu</option>
                                        <option value="Uganda">Uganda</option>
                                        <option value="Ukraine">Ukraine</option>
                                        <option value="United Arab Emirates">United Arab Emirates</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="United States">United States</option>
                                        <option value="Uruguay">Uruguay</option>
                                        <option value="Uzbekistan">Uzbekistan</option>
                                        <option value="Vanuatu">Vanuatu</option>
                                        <option value="Vatican City">Vatican City</option>
                                        <option value="Venezuela">Venezuela</option>
                                        <option value="Vietnam">Vietnam</option>
                                        <option value="Yemen">Yemen</option>
                                        <option value="Zambia">Zambia</option>
                                        <option value="Zimbabwe">Zimbabwe</option>
                                    </select>
                                </div>

                                <div class="interest-form-group">
                                    <label for="website">Company Website or Social Handle</label>
                                    <input type="text" id="website" name="website"
                                        placeholder="Input your company website/socials" />
                                </div>

                                <div class="interest-form-group">
                                    <label for="linkedin">LinkedIn Profile</label>
                                    <input type="url" id="linkedin" name="linkedin"
                                        placeholder="Add your profile Url (Optional)" />
                                </div>

                                <div class="interest-form-group">
                                    <label for="yearEst">Year of Establishment</label>
                                    <input type="number" id="yearEst" name="year_established"
                                        placeholder="Input year of establishment" pattern="[0-9]{4}" min="1900"
                                        max="2100" />
                                </div>

                                <div class="interest-form-actions">
                                    <button type="button" class="interest-form-return-button" data-prev="form1">
                                        Return
                                    </button>
                                    <button type="button" class="interest-form-proceed-button" data-next="form3">
                                        Proceed
                                    </button>
                                </div>
                            </div>

                            <!-- third Form -->
                            <div class="third-stage-form-container hidden">
                                <!-- Industries -->
                                <div class="interest-form-group">
                                    <label for="industry">What industry does your business operate in?</label>
                                    <div class="custom-select" data-select="industry">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Primary & Industrial sector</span>
                                            <svg class="custom-select-arrow" xmlns="http://www.w3.org/2000/svg"
                                                width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </div>
                                        <div class="custom-options">
                                            <span class="custom-option" data-value="agriculture">Agribusiness</span>
                                            <span class="custom-option" data-value="manufacturing">Manufacturing</span>
                                            <span class="custom-option" data-value="mining">Mining</span>
                                            <span class="custom-option" data-value="logistics">Logistics</span>
                                        </div>
                                    </div>

                                    <select name="industry" id="industry" style="display: none">
                                        <option value="">Primary & Industrial sector</option>
                                        <option value="agriculture">Agribusiness</option>
                                        <option value="manufacturing">Manufacturing</option>
                                        <option value="mining">Mining</option>
                                        <option value="logistics">Logistics</option>
                                    </select>
                                </div>

                                <!-- Categories -->
                                <div class="interest-form-group">
                                    <div class="interest-form-tags" id="categoryContainer"></div>
                                </div>

                                <!-- Hidden field to store selected categories for form submission -->
                                <input type="hidden" name="categories" id="selectedCategories" />

                                <!-- Employees Custom Select -->
                                <div class="interest-form-group">
                                    <label for="employees">Numbers of Employees</label>

                                    <div class="custom-select" data-select="employees">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Select Employees range</span>
                                            <svg class="custom-select-arrow" xmlns="http://www.w3.org/2000/svg"
                                                width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </div>

                                        <div class="custom-options">
                                            <span class="custom-option" data-value="1-10">1-10</span>
                                            <span class="custom-option" data-value="11-50">11-50</span>
                                            <span class="custom-option" data-value="50-200">50-200</span>
                                            <span class="custom-option" data-value="200+">200+</span>
                                        </div>
                                    </div>

                                    <!-- Hidden native select -->
                                    <select id="employees" name="number_of_employees" style="display: none">
                                        <option value="">Select Employees range</option>
                                        <option value="1-10">1-10</option>
                                        <option value="11-50">11-50</option>
                                        <option value="50-200">50-200</option>
                                        <option value="200+">200+</option>
                                    </select>
                                </div>

                                <!-- Revenue Custom Select -->
                                <div class="interest-form-group">
                                    <label for="revenue">
                                        <span>
                                            Approximate annual revenue
                                        </span>
                                        <span class="interest-form-sublabel">(confidential – for internal use only)</span>
                                    </label>

                                    <div class="custom-select" data-select="revenue">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Select revenue range</span>
                                            <svg class="custom-select-arrow" xmlns="http://www.w3.org/2000/svg"
                                                width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </div>

                                        <div class="custom-options">
                                            <span class="custom-option" data-value="0-5">Below $1M (0-5 years)</span>
                                            <span class="custom-option" data-value="6-10">$1M - $5M (6-10 years)</span>
                                            <span class="custom-option" data-value="11-Above">$5M+ (11+ years)</span>
                                        </div>
                                    </div>

                                    <!-- Hidden native select -->
                                    <select id="revenue" name="years_of_operation" style="display: none">
                                        <option value="">Select revenue range</option>
                                        <option value="0-5">Below $1M (0-5 years)</option>
                                        <option value="6-10">$1M - $5M (6-10 years)</option>
                                        <option value="11-Above">$5M+ (11+ years)</option>
                                    </select>
                                </div>

                                <div class="interest-form-actions">
                                    <button type="button" class="interest-form-return-button" data-prev="form2">
                                        Return
                                    </button>
                                    <button type="button" class="interest-form-proceed-button" data-next="form4">
                                        Proceed
                                    </button>
                                </div>
                            </div>

                            <!-- fourth Form -->
                            <div class="fourth-stage-form-container">
                                <div class="interest-form-group">
                                    <label for="offerValue">What value can your company offer to other CEOs in the
                                        network?</label>
                                    <textarea id="offerValue" name="offer_value" rows="1" placeholder="Enter your value"></textarea>
                                </div>

                                <div class="interest-form-group">
                                    <label for="companyDescription">Briefly describe what your company does and who your
                                        clients/customers are (max 150 words)</label>
                                    <textarea id="companyDescription" name="goals" rows="5" placeholder="Enter your description"></textarea>
                                </div>

                                <div class="interest-form-group">
                                    <label for="interest">What are you most interested in from this network?</label>

                                    <div class="custom-select" data-select="interest">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Select interest</span>
                                            <span class="custom-select-arrow">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                        </div>

                                        <div class="custom-options">
                                            <div class="custom-option" data-value="networking">
                                                Networking
                                            </div>
                                            <div class="custom-option" data-value="leads">
                                                Verified Business Leads
                                            </div>
                                            <div class="custom-option" data-value="tools">
                                                Automated Sales Tools
                                            </div>
                                            <div class="custom-option" data-value="consulting">
                                                Consulting Support
                                            </div>
                                        </div>
                                    </div>

                                    <!-- hidden select to sync value -->
                                    <select name="selected_outcome" id="selectedOutcome" style="display: none">
                                        <option value="">Select interest</option>
                                        <option value="networking">Networking</option>
                                        <option value="leads">Verified Business Leads</option>
                                        <option value="tools">Automated Sales Tools</option>
                                        <option value="consulting">Consulting Support</option>
                                    </select>
                                </div>

                                <div class="interest-form-group">
                                    <p class="interest-form-question">
                                        If your business isn’t eligible for the CEO Referral
                                        Network at this time, would you be interested in joining
                                        our B2B platform to access:
                                    </p>
                                    <ul class="interest-form-list">
                                        <li>Verified business leads</li>
                                        <li>Automated sales tools</li>
                                        <li>Growth audits and consulting support?</li>
                                    </ul>

                                    <div class="interest-form-option-buttons">
                                        <div class="interest-form-option">
                                            <input type="radio" id="opt-yes" name="b2b_interest" value="yes"
                                                checked />
                                            <label for="opt-yes">Yes, I'm interested</label>
                                        </div>
                                        <div class="interest-form-option">
                                            <input type="radio" id="opt-maybe" name="b2b_interest"
                                                value="maybe" />
                                            <label for="opt-maybe">Maybe, send me more information</label>
                                        </div>
                                        <div class="interest-form-option">
                                            <input type="radio" id="opt-no" name="b2b_interest"
                                                value="no" />
                                            <label for="opt-no">No, not interested</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="interest-form-actions">
                                    <button type="button" class="interest-form-return-button" data-prev="form3">
                                        Return
                                    </button>
                                    <button type="submit" class="interest-form-submit-button" id="submitBtn">
                                        <span class="btn-text">Submit</span>
                                        <div class="spinner-border spinner-border-sm d-none" role="status"
                                            aria-hidden="true"></div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Response Modal -->
                <div class="modal fade" id="responseModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center py-4">
                                <p id="responseMessage" class="mb-3"></p>
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row-two-container">
                <!-- Desktop Heading -->
                <section class="interest-form-heading desktop-heading">
                    <!-- First Form Heading -->
                    <div class="first-form-heading hidden">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>Basic Information</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Let's start with your basic details</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper ">
                                    <div class="step ">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Form Heading -->
                    <div class="second-form-heading hidden">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>Credibility Check</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Stand out among other businesses</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Third Form Heading -->
                    <div class="third-form-heading hidden">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>Industry Preference</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Boost visibility for your matches</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fourth Form Heading -->
                    <div class="fourth-form-heading">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>Industry Insight</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Stand out among other businesses</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- footer -->
                <section class="interest-form-footer">
                    <div class="form-footer">
                        <h2>Contact Information</h2>

                        <div class="interest-form-footer-contact-row">
                            <div>
                                <div class="interest-form-footer-contact-item">
                                    <span class="interest-form-footer-icon"><i class="fas fa-phone-alt"></i></span>
                                    <a href="tel:+442080921281" class="phone">+44 208 0921 281</a>
                                </div>

                                <div class="interest-form-footer-contact-item">
                                    <span class="interest-form-footer-icon"><i class="fas fa-envelope"></i></span>
                                    <a href="mailto:hello@p23africa.com">hello@p23africa.com</a>
                                </div>
                            </div>

                            <div class="interest-form-footer-contact-item">
                                <span class="interest-form-footer-icon"><i class="fas fa-map-marker-alt"></i></span>
                                <span>London UK</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection

<script>
    // FORM MANAGEMENT AND VALIDATION
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('brnForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const spinner = submitBtn.querySelector('.spinner-border');
        const responseMessage = document.getElementById('responseMessage');
        const modal = new bootstrap.Modal(document.getElementById('responseModal'));

        // Form persistence constants
        const FORM_STORAGE_KEY = 'brn_form_data';
        const STEP_STORAGE_KEY = 'brn_current_step';

        // Save form data to localStorage
        function saveFormData() {
            const formData = {};
            const formElements = form.querySelectorAll('input, select, textarea');

            formElements.forEach(element => {
                if (element.type === 'radio' || element.type === 'checkbox') {
                    if (element.checked) {
                        formData[element.name] = element.value;
                    }
                } else if (element.name && element.value) {
                    formData[element.name] = element.value;
                }
            });

            // Save current step using currentStep variable
            const stepNames = ['form1', 'form2', 'form3', 'form4'];
            if (currentStep >= 0 && currentStep < stepNames.length) {
                const stepName = stepNames[currentStep];
                localStorage.setItem(STEP_STORAGE_KEY, stepName);
            }

            localStorage.setItem(FORM_STORAGE_KEY, JSON.stringify(formData));
        }

        // Restore form data from localStorage
        function restoreFormData() {
            try {
                const savedData = localStorage.getItem(FORM_STORAGE_KEY);
                const savedStep = localStorage.getItem(STEP_STORAGE_KEY);

                if (savedData) {
                    const formData = JSON.parse(savedData);

                    // Restore input values
                    Object.keys(formData).forEach(fieldName => {
                        const elements = form.querySelectorAll(`[name="${fieldName}"]`);

                        elements.forEach(element => {
                            if (element.type === 'radio' || element.type === 'checkbox') {
                                if (element.value === formData[fieldName]) {
                                    element.checked = true;
                                }
                            } else {
                                element.value = formData[fieldName];
                            }
                        });
                    });

                    // Restore custom select values
                    if (formData.position) {
                        updateCustomSelect('title', formData.position);
                    }
                    if (formData.industry) {
                        updateCustomSelect('industry', formData.industry);
                    }
                    if (formData.number_of_employees) {
                        updateCustomSelect('employees', formData.number_of_employees);
                    }
                    if (formData.years_of_operation) {
                        updateCustomSelect('revenue', formData.years_of_operation);
                    }
                    if (formData.selected_outcome) {
                        updateCustomSelect('interest', formData.selected_outcome);
                    }

                    // Auto-revalidate email if it exists and is valid format
                    if (formData.email && isValidEmail(formData.email)) {
                        setTimeout(() => {
                            validateEmailInDatabase(formData.email);
                        }, 100);
                    }

                    // Note: Step restoration is now handled during initialization
                }
            } catch (error) {
                // console.error('Error restoring form data:', error);
            }
        }

        // Clear form data from localStorage
        function clearFormData() {
            localStorage.removeItem(FORM_STORAGE_KEY);
            localStorage.removeItem(STEP_STORAGE_KEY);
        }

        // Helper function to update custom select display
        function updateCustomSelect(selectName, value) {
            const customSelect = document.querySelector(`[data-select="${selectName}"]`);
            if (customSelect) {
                const trigger = customSelect.querySelector('.custom-select-text');
                const option = customSelect.querySelector(`[data-value="${value}"]`);
                const hiddenSelect = document.querySelector(`select[name="${getFieldName(selectName)}"]`);

                if (option && trigger) {
                    trigger.textContent = option.textContent;
                    customSelect.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove(
                        'active'));
                    option.classList.add('active');
                }

                if (hiddenSelect) {
                    hiddenSelect.value = value;
                }
            }
        }

        // Map custom select names to form field names
        function getFieldName(selectName) {
            const mapping = {
                'title': 'position',
                'industry': 'industry',
                'employees': 'number_of_employees',
                'revenue': 'years_of_operation',
                'interest': 'selected_outcome',
                'country': 'country'
            };
            return mapping[selectName] || selectName;
        }

        // Email validation utility functions
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function showEmailStatus(type) {
            const emailStatus = document.getElementById('email-status');
            const emailCheckmark = document.getElementById('email-checkmark');
            const emailSpinner = document.getElementById('email-spinner');

            emailStatus.style.display = 'block';
            emailCheckmark.style.display = type === 'success' ? 'block' : 'none';
            emailSpinner.style.display = type === 'loading' ? 'block' : 'none';

            if (type === 'hide') {
                emailStatus.style.display = 'none';
            }
        }

        function validateEmailInDatabase(email) {
            const emailInput = document.querySelector('input[name="email"]');

            if (!email || !isValidEmail(email)) {
                showEmailStatus('hide');
                return;
            }

            showEmailStatus('loading');

            fetch('{{ route('brn.check-email') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value ||
                            document.querySelector('meta[name="csrf-token"]')?.content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email: email
                    }),
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        showEmailStatus('hide');
                        clearError(emailInput);
                        showError(emailInput, data.message);
                    } else {
                        showEmailStatus('success');
                        clearError(emailInput);
                        emailInput.style.border = '2px solid #10b981';
                        emailInput.style.backgroundColor = 'rgba(16, 185, 129, 0.1)';
                    }
                })
                .catch(error => {
                    showEmailStatus('hide');
                });
        }

        // Auto-save form data on input changes
        function setupAutoSave() {
            const formElements = form.querySelectorAll('input, select, textarea');

            formElements.forEach(element => {
                element.addEventListener('input', saveFormData);
                element.addEventListener('change', saveFormData);
            });
        }

        // Initialize form persistence
        restoreFormData();
        setupAutoSave();

        // Clear saved data utility
        window.clearBrnFormData = function() {
            clearFormData();
            location.reload();
        };

        // Step navigation function
        function showStepByName(stepName) {
            const stepMapping = {
                'form1': 0,
                'form2': 1,
                'form3': 2,
                'form4': 3
            };

            const stepIndex = stepMapping[stepName];
            if (stepIndex !== undefined) {
                currentStep = stepIndex;
                showStep(currentStep);
            }
        }

        // Error handling functions
        function showError(input, message) {
            clearError(input);

            input.style.border = '2px solid #ef4444';
            input.style.backgroundColor = '#fef2f2';

            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.color = '#ef4444';
            errorDiv.style.fontSize = '0.875rem';
            errorDiv.style.marginTop = '4px';
            errorDiv.textContent = message;

            input.parentNode.insertBefore(errorDiv, input.nextSibling);
        }

        function clearError(input) {
            input.style.border = '';
            input.style.backgroundColor = '';

            const errorMsg = input.parentNode.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.remove();
            }
        }

        // Real-time email validation setup
        const emailInput = document.querySelector('input[name="email"]');
        let emailTimeout;

        if (emailInput) {
            emailInput.addEventListener('input', function() {
                clearTimeout(emailTimeout);
                const email = this.value.trim();

                clearError(this);

                if (email === '') {
                    showEmailStatus('hide');
                    return;
                }

                emailTimeout = setTimeout(() => {
                    validateEmailInDatabase(email);
                }, 500);
            });

            emailInput.addEventListener('blur', function() {
                const email = this.value.trim();
                if (email && isValidEmail(email)) {
                    validateEmailInDatabase(email);
                }
            });
        }

        // CUSTOM SELECT AND CATEGORIES (existing code)
        /*************************************
         * GLOBAL CUSTOM SELECT HANDLER
         *************************************/
        const allCustomSelects = document.querySelectorAll(".custom-select");

        allCustomSelects.forEach((customSelect) => {
            const trigger = customSelect.querySelector(".custom-select-trigger");
            const text = customSelect.querySelector(".custom-select-text");
            const options = customSelect.querySelectorAll(".custom-option");
            const selectName = customSelect.dataset.select;
            const hiddenSelect = document.querySelector(`select[name="${getFieldName(selectName)}"]`);

            // Toggle dropdown open/close
            trigger.addEventListener("click", (e) => {
                e.stopPropagation();
                document.querySelectorAll(".custom-select.open").forEach((openSelect) => {
                    if (openSelect !== customSelect) openSelect.classList.remove(
                        "open");
                });
                customSelect.classList.toggle("open");
            });

            // Handle option click
            options.forEach((option) => {
                option.addEventListener("click", (e) => {
                    e.stopPropagation();
                    const value = option.dataset.value;
                    const label = option.textContent;

                    // Update visible text + active state
                    text.textContent = label;
                    options.forEach((o) => o.classList.remove("active"));
                    option.classList.add("active");

                    // Sync to hidden select
                    if (hiddenSelect) {
                        hiddenSelect.value = value;
                    } else {
                        // console.warn(`Hidden select not found for ${selectName}`);
                    }

                    // Close dropdown
                    customSelect.classList.remove("open");

                    // If this is the "industry" select, trigger category update
                    if (selectName === "industry") {
                        updateCategories(value);
                    }

                    // Save form data after selection
                    saveFormData();
                });
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", (e) => {
            document.querySelectorAll(".custom-select.open").forEach((openSelect) => {
                if (!openSelect.contains(e.target)) openSelect.classList.remove("open");
            });
        });

        /*************************************
         * COUNTRY SEARCH FUNCTIONALITY
         *************************************/
        const countryDropdown = document.querySelector('[data-select="country"]');
        if (countryDropdown) {
            const countrySearchInput = countryDropdown.querySelector('.country-search');
            const countryOptionsContainer = countryDropdown.querySelector('.custom-options-scrollable');
            const countryOptions = countryOptionsContainer.querySelectorAll('.custom-option');

            // Handle search input
            if (countrySearchInput) {
                countrySearchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();

                    countryOptions.forEach(option => {
                        const countryName = option.textContent.toLowerCase();
                        if (countryName.includes(searchTerm)) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                });

                // Prevent dropdown from closing when clicking on search input
                countrySearchInput.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                // Clear search when dropdown closes
                countryDropdown.addEventListener('click', function() {
                    if (!countryDropdown.classList.contains('open')) {
                        countrySearchInput.value = '';
                        countryOptions.forEach(option => {
                            option.style.display = 'block';
                        });
                    }
                });

                // Focus search input when dropdown opens
                const countryTrigger = countryDropdown.querySelector('.custom-select-trigger');
                countryTrigger.addEventListener('click', function() {
                    setTimeout(() => {
                        if (countryDropdown.classList.contains('open')) {
                            countrySearchInput.focus();
                        }
                    }, 100);
                });
            }
        }

        /*************************************
         * INDUSTRY → CATEGORY HANDLER
         *************************************/
        const industryCategories = {
            agriculture: [
                "Crop Production",
                "Livestock Farming",
                "AgroTech",
                "Food Processing",
            ],
            manufacturing: [
                "Textile Production",
                "Automobile Manufacturing",
                "Electronics",
                "Pharmaceuticals",
            ],
            mining: [
                "Gold Mining",
                "Oil & Gas Exploration",
                "Quarrying",
                "Metal Refining",
            ],
            logistics: [
                "Freight Forwarding",
                "Warehousing",
                "Transportation",
                "Supply Chain Solutions",
            ],
        };

        const defaultTags = [
            "Agrobusiness",
            "Business Growth",
            "Sustainability",
            "Leadership",
        ];

        let selectedCategories = {}; // store selections by industry

        const categoryContainer = document.getElementById("categoryContainer");
        const industrySelect = document.querySelector('select[name="industry"]');
        const hiddenCategoryInput = document.getElementById("selectedCategories");

        // Initial render (default tags)
        renderTags(defaultTags, true);

        /** Update when user selects a new industry **/
        function updateCategories(industry) {
            categoryContainer.innerHTML = "";
            hiddenCategoryInput.value = "";

            if (!industry || !industryCategories[industry]) {
                renderTags(defaultTags, true);
                return;
            }

            const categories = industryCategories[industry];
            const saved = selectedCategories[industry] || [];

            renderTags(categories, false, saved);
        }

        /** Render tags **/
        function renderTags(categories, isDefault = false, saved = []) {
            categoryContainer.innerHTML = "";

            categories.forEach((category) => {
                const tag = document.createElement("div");
                tag.className = "interest-form-tag";
                if (saved.includes(category)) tag.classList.add("active");

                tag.innerHTML = `
      <span class="check-icon ${saved.includes(category) ? "active" : ""}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
          viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
        </svg>
      </span>
      <span>${category}</span>
    `;

                // Add click event
                tag.addEventListener("click", () => {
                    const currentIndustry = industrySelect.value;

                    // Toggle visual state
                    tag.classList.toggle("active");
                    const icon = tag.querySelector(".check-icon");
                    icon.classList.toggle("active");

                    // Handle storage
                    if (!isDefault && currentIndustry) {
                        if (!selectedCategories[currentIndustry]) {
                            selectedCategories[currentIndustry] = [];
                        }

                        if (tag.classList.contains("active")) {
                            selectedCategories[currentIndustry].push(category);
                        } else {
                            selectedCategories[currentIndustry] = selectedCategories[
                                currentIndustry
                            ].filter((c) => c !== category);
                        }
                    }

                    updateHiddenInput();
                    saveFormData(); // Save after category selection
                });

                categoryContainer.appendChild(tag);
            });
        }

        /** Sync hidden field for submission **/
        function updateHiddenInput() {
            const allSelected = Object.values(selectedCategories).flat();
            hiddenCategoryInput.value = allSelected.join(",");
        }


        // FORM STEPS (updated with validation)
        // Collect all forms and headings
        const formStages = document.querySelectorAll(
            ".first-stage-form-container, .second-stage-form-container, .third-stage-form-container, .fourth-stage-form-container"
        );

        const mobileHeadings = document.querySelectorAll(
            ".mobile-heading .first-form-heading, .mobile-heading .second-form-heading, .mobile-heading .third-form-heading, .mobile-heading .fourth-form-heading"
        );

        const desktopHeadings = document.querySelectorAll(
            ".desktop-heading .first-form-heading, .desktop-heading .second-form-heading, .desktop-heading .third-form-heading, .desktop-heading .fourth-form-heading"
        );

        const proceedButtons = document.querySelectorAll(
            ".interest-form-proceed-button"
        );
        const returnButtons = document.querySelectorAll(
            ".interest-form-return-button"
        );

        // Initialize currentStep from localStorage or default to 0
        function initializeCurrentStep() {
            const savedStep = localStorage.getItem(STEP_STORAGE_KEY);
            if (savedStep) {
                const stepMapping = {
                    'form1': 0,
                    'form2': 1,
                    'form3': 2,
                    'form4': 3
                };
                const stepIndex = stepMapping[savedStep] || 0;
                return stepIndex;
            }
            return 0;
        }

        let currentStep = initializeCurrentStep();
        const totalSteps = formStages.length;

        // Initialize form and heading display
        function showStep(step) {
            // Hide all stages
            formStages.forEach((form) => form.classList.add("hidden"));
            mobileHeadings.forEach((heading) => heading.classList.add("hidden"));
            desktopHeadings.forEach((heading) => heading.classList.add("hidden"));

            // Show current stage
            formStages[step].classList.remove("hidden");
            mobileHeadings[step].classList.remove("hidden");
            desktopHeadings[step].classList.remove("hidden");

            updateProgress(step);
            saveFormData(); // Save current step
        }

        // Update progress bar for all instances
        function updateProgress(step) {
            const progressSteppers = document.querySelectorAll(".progress-stepper");

            progressSteppers.forEach((stepper) => {
                const steps = stepper.querySelectorAll(".step");
                const progress = (step / totalSteps) * 100;
                stepper.style.setProperty("--progress-width", `${progress}%`);
                steps.forEach((s, i) => {
                    s.classList.remove("active", "completed");
                    if (i < step) s.classList.add("completed");
                    else if (i === step) s.classList.add("active");
                });
            });
        }

        // Handle navigation
        proceedButtons.forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.preventDefault();

                // Validate current step before proceeding
                if (validateCurrentStep()) {
                    if (currentStep < totalSteps - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                }
            });
        });

        returnButtons.forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.preventDefault();
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        });

        // Validation function for current step
        function validateCurrentStep() {
            const currentStage = formStages[currentStep];
            let isValid = true;

            // Clear previous errors
            currentStage.querySelectorAll('.error-message').forEach(msg => msg.remove());
            currentStage.querySelectorAll('input, select, textarea').forEach(input => {
                clearError(input);
            });

            if (currentStep === 0) {
                // Step 1: Basic Information validation
                const name = currentStage.querySelector('input[name="name"]');
                const email = currentStage.querySelector('input[name="email"]');
                const phone = currentStage.querySelector('input[name="phone"]');
                const position = currentStage.querySelector('select[name="position"]');
                const companyName = currentStage.querySelector('input[name="company_name"]');

                if (!name.value.trim()) {
                    showError(name, 'Full name is required');
                    isValid = false;
                }

                if (!email.value.trim()) {
                    showError(email, 'Email address is required');
                    isValid = false;
                } else if (!isValidEmail(email.value.trim())) {
                    showError(email, 'Please enter a valid email address');
                    isValid = false;
                }

                if (!phone.value.trim()) {
                    showError(phone, 'Phone number is required');
                    isValid = false;
                }

                if (!position.value.trim()) {
                    const customSelect = currentStage.querySelector('[data-select="title"]');
                    showError(customSelect || position.parentNode, 'Please select your title');
                    isValid = false;
                }

                if (!companyName.value.trim()) {
                    showError(companyName, 'Company name is required');
                    isValid = false;
                }

            } else if (currentStep === 1) {
                // Step 2: Company Information validation
                const regNumber = currentStage.querySelector('input[name="reg_number"]');
                const country = currentStage.querySelector('select[name="country"]');
                const website = currentStage.querySelector('input[name="website"]');
                const linkedin = currentStage.querySelector('input[name="linkedin"]');
                const yearEstablished = currentStage.querySelector('input[name="year_established"]');

                if (!regNumber.value.trim()) {
                    showError(regNumber, 'Company registration number is required');
                    isValid = false;
                }

                if (!country.value.trim()) {
                    const customSelect = currentStage.querySelector('[data-select="country"]');
                    showError(customSelect || country.parentNode, 'Country of registration is required');
                    isValid = false;
                }

                if (!website.value.trim()) {
                    showError(website, 'Company website or social handle is required');
                    isValid = false;
                }

                // if (!linkedin.value.trim()) {
                //     showError(linkedin, 'LinkedIn profile is required');
                //     isValid = false;
                // } else if (!linkedin.value.toLowerCase().includes('linkedin')) {
                //     showError(linkedin, 'Please enter a valid LinkedIn profile URL');
                //     isValid = false;
                // }

                if (!linkedin.value.trim()) {
                    // showError(linkedin, 'LinkedIn profile is required');
                    isValid = true;
                } else if (!linkedin.value.toLowerCase().includes('linkedin')) {
                    showError(linkedin, 'Please enter a valid LinkedIn profile URL');
                    isValid = false;
                }

                if (!yearEstablished.value.trim()) {
                    showError(yearEstablished, 'Year established is required');
                    isValid = false;
                } else if (isNaN(yearEstablished.value) || yearEstablished.value < 1800 || yearEstablished
                    .value > new Date().getFullYear()) {
                    showError(yearEstablished, 'Please enter a valid year');
                    isValid = false;
                }

            } else if (currentStep === 2) {
                // Step 3: Industry Preference validation
                const industry = currentStage.querySelector('select[name="industry"]');
                const employees = currentStage.querySelector('select[name="number_of_employees"]');
                const revenue = currentStage.querySelector('select[name="years_of_operation"]');

                if (!industry.value.trim()) {
                    const customSelect = currentStage.querySelector('[data-select="industry"]');
                    showError(customSelect || industry.parentNode, 'Please select your industry');
                    isValid = false;
                }

                if (!employees.value.trim()) {
                    const customSelect = currentStage.querySelector('[data-select="employees"]');
                    showError(customSelect || employees.parentNode, 'Please select number of employees');
                    isValid = false;
                }

                if (!revenue.value.trim()) {
                    const customSelect = currentStage.querySelector('[data-select="revenue"]');
                    showError(customSelect || revenue.parentNode, 'Please select revenue range');
                    isValid = false;
                }

            } else if (currentStep === 3) {
                // Step 4: Industry Insight validation
                const goals = currentStage.querySelector('textarea[name="goals"]');
                const selectedOutcome = currentStage.querySelector('select[name="selected_outcome"]');
                const b2bInterest = currentStage.querySelector('input[name="b2b_interest"]:checked');

                if (!goals.value.trim()) {
                    showError(goals, 'Please describe your company');
                    isValid = false;
                }

                if (!selectedOutcome.value.trim()) {
                    const customSelect = currentStage.querySelector('[data-select="interest"]');
                    showError(customSelect || selectedOutcome.parentNode, 'Please select your interest');
                    isValid = false;
                }

                if (!b2bInterest) {
                    // Show error for the radio button group
                    const radioGroup = currentStage.querySelector('.interest-form-option-buttons');
                    if (radioGroup) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'error-message';
                        errorDiv.style.color = '#ef4444';
                        errorDiv.style.fontSize = '0.875rem';
                        errorDiv.style.marginTop = '4px';
                        errorDiv.style.textAlign = 'center';
                        errorDiv.textContent = 'Please select your B2B interest level';
                        radioGroup.parentNode.insertBefore(errorDiv, radioGroup.nextSibling);
                    }
                    isValid = false;
                }
            }

            // Scroll to first error if validation failed
            if (!isValid) {
                const firstError = currentStage.querySelector('.error-message');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }

            return isValid;
        }

        // Initialize first view
        showStep(currentStep);

        // Clear server errors when user starts typing
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', function() {
                const serverError = this.parentNode.querySelector('.server-error');
                if (serverError) {
                    serverError.remove();
                    clearError(this);
                }
            });
        });

        // Form submission handling
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate all steps before submission
            let allValid = true;
            for (let step = 0; step < totalSteps; step++) {
                const prevStep = currentStep;
                currentStep = step;
                if (!validateCurrentStep()) {
                    allValid = false;
                    showStep(step);
                    break;
                }
                currentStep = prevStep;
            }

            if (!allValid) {
                return;
            }

            // Disable submit button
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            const formData = new FormData(form);

            // IMPROVED: Get CSRF token with proper priority order
            let csrfToken = null;

            // 1. First priority: Form's hidden input (most reliable)
            const formTokenInput = form.querySelector('input[name="_token"]');
            if (formTokenInput && formTokenInput.value) {
                csrfToken = formTokenInput.value;
            }

            // 2. Second priority: Meta tag
            if (!csrfToken) {
                const metaToken = document.querySelector('meta[name="csrf-token"]');
                if (metaToken && metaToken.content) {
                    csrfToken = metaToken.content;
                }
            }

            // 3. Third priority: Laravel's global object (if available)
            if (!csrfToken && typeof window.Laravel !== 'undefined') {
                csrfToken = window.Laravel.csrfToken;
            }

            // Verify token exists before submission
            if (!csrfToken) {
                submitBtn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');

                responseMessage.textContent =
                    'Security error: CSRF token missing. Please refresh the page and try again.';
                responseMessage.style.color = '#dc2626';
                modal.show();
                return;
            }

            // Make the request with improved headers
            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(response => {
                    // Handle token mismatch specifically
                    if (response.status === 419) {
                        throw {
                            status: 419,
                            message: 'Your session has expired. Please refresh the page and try again.'
                        };
                    }

                    if (!response.ok) {
                        return response.json().then(err => {
                            throw {
                                status: response.status,
                                data: err
                            };
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    responseMessage.textContent = data.message ||
                        'Your application was submitted successfully!';
                    responseMessage.style.color = '#059669';
                    modal.show();

                    // Clear saved form data
                    clearFormData();

                    // Reset form
                    form.reset();
                    currentStep = 0;
                    showStep(currentStep);

                    // Clear custom selects
                    document.querySelectorAll('.custom-select-text').forEach(text => {
                        const defaultText = text.closest('.custom-select').querySelector(
                            '.custom-option')?.textContent || 'Select option';
                        text.textContent = defaultText;
                    });
                    document.querySelectorAll('.custom-option').forEach(option => option.classList
                        .remove('active'));

                    // Clear categories
                    categoryContainer.innerHTML = '';
                    renderTags(defaultTags, true);
                    Object.keys(selectedCategories).forEach(key => delete selectedCategories[key]);
                    hiddenCategoryInput.value = '';
                })
                .catch(error => {
                    let errorMessage = 'Something went wrong. Please try again.';

                    if (error.status === 419) {
                        errorMessage =
                            'Your session has expired. Please refresh the page and try again.';
                    } else if (error.status === 422) {
                        if (error.data?.errors) {
                            showServerErrors(error.data.errors);
                            errorMessage = 'Please correct the errors below and try again.';
                        } else if (error.data?.message) {
                            errorMessage = error.data.message;
                        }
                    } else if (error.status === 403) {
                        errorMessage = 'Access denied. Please refresh the page and try again.';
                    } else if (error.status === 429) {
                        errorMessage = 'Too many attempts. Please wait a moment and try again.';
                    } else if (error.message) {
                        errorMessage = error.message;
                    }

                    responseMessage.textContent = errorMessage;
                    responseMessage.style.color = '#ef4444';
                    modal.show();
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.classList.remove('d-none');
                });
        });

        // Function to show server errors
        function showServerErrors(errors) {
            // Clear ALL previous errors thoroughly
            document.querySelectorAll('.error-message').forEach(msg => msg.remove());
            document.querySelectorAll('input, select, textarea').forEach(input => {
                clearError(input);
            });

            // Show field-specific errors
            Object.keys(errors).forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    const errorMessage = errors[field][0];
                    clearError(input);

                    input.style.border = '2px solid #ef4444';
                    input.style.backgroundColor = '#fef2f2';

                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message server-error';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '4px';
                    errorDiv.textContent = errorMessage;

                    input.parentNode.insertBefore(errorDiv, input.nextSibling);
                }
            });

            // Scroll to first error
            const firstError = document.querySelector('.server-error');
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        // LinkedIn URL normalization function
        function normalizeLinkedInURL(url) {
            if (!url || url.trim() === '') return '';

            let normalizedUrl = url.trim();

            // Remove common prefixes and normalize
            normalizedUrl = normalizedUrl.replace(/^(https?:\/\/)?(www\.)?/i, '');

            // Handle different LinkedIn domain variations
            normalizedUrl = normalizedUrl.replace(/^(linkedin\.com|linkedin\.in|in\.linkedin\.com)/i,
                'linkedin.com');

            // Ensure it starts with linkedin.com
            if (!normalizedUrl.toLowerCase().startsWith('linkedin.com')) {
                // If it looks like a LinkedIn profile path, prepend linkedin.com
                if (normalizedUrl.startsWith('/in/') || normalizedUrl.match(/^[a-zA-Z0-9\-_.]+$/)) {
                    normalizedUrl = 'linkedin.com/in/' + normalizedUrl.replace(/^\/in\//, '');
                } else {
                    // If it doesn't look like a valid LinkedIn URL, return as-is
                    return 'https://' + normalizedUrl;
                }
            }

            // Ensure /in/ path is present for profiles
            if (normalizedUrl.toLowerCase().startsWith('linkedin.com') &&
                !normalizedUrl.toLowerCase().includes('/company/') &&
                !normalizedUrl.toLowerCase().includes('/in/')) {
                const path = normalizedUrl.substring('linkedin.com'.length).replace(/^\/+/, '');
                if (path) {
                    normalizedUrl = 'linkedin.com/in/' + path;
                } else {
                    normalizedUrl = 'linkedin.com/in/';
                }
            }

            // Add https prefix
            return 'https://' + normalizedUrl;
        }

        // Website URL normalization function
        function normalizeWebsiteURL(url) {
            if (!url || url.trim() === '') return '';

            let normalizedUrl = url.trim();

            // If it already has a protocol, return as-is
            if (/^https?:\/\//i.test(normalizedUrl)) {
                return normalizedUrl;
            }

            // Remove www. if present at the beginning
            normalizedUrl = normalizedUrl.replace(/^www\./i, '');

            // Check if it looks like a social media handle (starts with @)
            if (normalizedUrl.startsWith('@')) {
                // Keep social handles as-is
                return normalizedUrl;
            }

            // Check if it's a social media URL pattern
            const socialPatterns = [
                /^(twitter\.com|x\.com)\//i,
                /^instagram\.com\//i,
                /^facebook\.com\//i,
                /^tiktok\.com\//i,
                /^youtube\.com\//i,
                /^linkedin\.com\//i
            ];

            let isSocialUrl = socialPatterns.some(pattern => pattern.test(normalizedUrl));

            // If it's not a social URL and doesn't have a domain extension, 
            // assume it's a domain and add .com
            if (!isSocialUrl && !normalizedUrl.includes('.') && !normalizedUrl.startsWith('@')) {
                normalizedUrl += '.com';
            }

            // Add https prefix for URLs
            if (!normalizedUrl.startsWith('@')) {
                return 'https://' + normalizedUrl;
            }

            return normalizedUrl;
        }

        // LinkedIn URL auto-formatting
        const linkedinInput = document.querySelector('input[name="linkedin"]');
        if (linkedinInput) {
            linkedinInput.addEventListener('blur', function() {
                const currentValue = this.value.trim();
                if (currentValue && currentValue.toLowerCase().includes('linkedin')) {
                    const normalizedValue = normalizeLinkedInURL(currentValue);
                    if (normalizedValue !== currentValue) {
                        this.value = normalizedValue;
                        // Trigger change event to save the updated value
                        this.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                        // Also trigger input event for any other listeners
                        this.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }
                } else if (currentValue) {
                    // If user entered something that doesn't include 'linkedin', 
                    // assume it's a username and format it
                    const normalizedValue = normalizeLinkedInURL(currentValue);
                    this.value = normalizedValue;
                    this.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                    this.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                }
            });

            // Also format on paste
            linkedinInput.addEventListener('paste', function() {
                setTimeout(() => {
                    const currentValue = this.value.trim();
                    if (currentValue) {
                        const normalizedValue = normalizeLinkedInURL(currentValue);
                        if (normalizedValue !== currentValue) {
                            this.value = normalizedValue;
                            this.dispatchEvent(new Event('change', {
                                bubbles: true
                            }));
                            this.dispatchEvent(new Event('input', {
                                bubbles: true
                            }));
                        }
                    }
                }, 100);
            });
        }

        // Website URL auto-formatting
        const websiteInput = document.querySelector('input[name="website"]');
        if (websiteInput) {
            websiteInput.addEventListener('blur', function() {
                const currentValue = this.value.trim();
                if (currentValue) {
                    const normalizedValue = normalizeWebsiteURL(currentValue);
                    if (normalizedValue !== currentValue) {
                        this.value = normalizedValue;
                        // Trigger change event to save the updated value
                        this.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                        // Also trigger input event for any other listeners
                        this.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }
                }
            });

            // Also format on paste
            websiteInput.addEventListener('paste', function() {
                setTimeout(() => {
                    const currentValue = this.value.trim();
                    if (currentValue) {
                        const normalizedValue = normalizeWebsiteURL(currentValue);
                        if (normalizedValue !== currentValue) {
                            this.value = normalizedValue;
                            this.dispatchEvent(new Event('change', {
                                bubbles: true
                            }));
                            this.dispatchEvent(new Event('input', {
                                bubbles: true
                            }));
                        }
                    }
                }, 100);
            });
        }

    });
</script>