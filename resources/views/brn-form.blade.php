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
    {{-- <!--<meta property="og:image" content="{{ asset('images/brn-form-meta.png') }}">--> --}}
    {{-- <meta property="og:image" content="{{ asset('images/noel-thumbnail.png') }}"> --}}
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
        background: #ffffffc6;
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
        font-size: 32px;
        font-weight: 500;
        color: #0d4036;
        margin: 0 0 10px 0;
        line-height: 0.7;
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
        font-size: 12px;
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
        padding: 65px 24px;
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
        margin-bottom: 20px;
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

    .fourth-stage-form-container label {
        font-family: "GT Walsheim Con" !important;
        line-height: 1.18;


    }




    /* Input Field Styling */
    input {
        width: 100%;
        padding: 18px 20px;
        border: 1px solid #fff;
        border-radius: 30px;
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff;
        font-size: 13px !important;
        font-family: "GT Walsheim Trial Md" !important;

        outline: none;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        box-sizing: border-box;
        padding-left: 30px;
    }

    input::placeholder {
        color: #ececec80;
        font-size: 12px;
        font-family: "GT Walsheim Trial Md";
        letter-spacing: 0.9px;

    }


    /* For Webkit browsers (Chrome, Safari, Edge) */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* For Firefox */
    input[type="number"] {
        -moz-appearance: textfield;
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
        font-family: "GT Walsheim Trial Md";

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

    .custom-select-trigger img {
        width: 9px;
        object-fit: cover;
        height: auto;
    }

    /* Text inside the trigger */
    .custom-select-text {
        font-family: "GT Walsheim Trial Md";
        padding-left: 7px;
        color: #ececec80;
        /* color: #fff !important; */

    }


    .custom-select-text.selected {
        color: #ffffff !important;
    }

    [data-select] .custom-select-text.selected {
        color: #ffffff !important;
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
        overflow-y: auto;
        z-index: 0;
        font-family: "GT Walsheim Trial Md";
        font-weight: 400;
        max-height: 180px;

    }

    /* Custom scrollbar for country list */
    .custom-options::-webkit-scrollbar {
        width: 6px;
    }

    .custom-options::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 3px;
    }

    .custom-options::-webkit-scrollbar-thumb {
        background: #fff;
        border-radius: 3px;
    }

    .custom-options::-webkit-scrollbar-thumb:hover {
        background: #ffffffac;
    }

    div#countryReg-options {
        scrollbar-width: none;
        background: #137077;
    }

    div#number-of-employees {
        z-index: 5;
    }

    div#industry-selects-group .custom-options {
        z-index: 5;
    }






    /* Scrollable options container */
    .custom-options-scrollable {
        max-height: 180px;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 10;
        position: relative;
        background: #137077;


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
        background: #fff;
        border-radius: 3px;
    }

    /* padding: 13px 45px; */


    .custom-options-scrollable::-webkit-scrollbar-thumb:hover {
        background: #ffffffac;
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

    input#countrySearchReg {
        z-index: 40;

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
    /* .custom-option.active {
        color: #fff !important;
    } */




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
        height: 130px;
        overflow: hidden;
        overflow-y: auto

    }


        /* Custom scrollbar for country list */
    .interest-form-tags::-webkit-scrollbar {
        width: 3px;
    }

    .interest-form-tags::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 3px;
    }

    .interest-form-tags::-webkit-scrollbar-thumb {
        background: #fff;
        border-radius: 3px;
    }

    .interest-form-tags::-webkit-scrollbar-thumb:hover {
        background: #ffffffac;
    }



#industry-selects-group .industry-message{
    font-size: 10px;
    margin-left: 30px;
    color: #f0f0f0;
    margin-top: 6px;
}


    /* Individual tag wrapper */
    .interest-form-tag {
        background-color: #ffffff;
        border-radius: 40px;
        display: flex;
        align-items: center;
        padding: 3.5px 9px;
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
        width: 20px;
        height: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50px;
        border: 2px solid #0d4036;
        padding: 2px;
    }


    .interest-form-tag span svg {
        color: #fff;

        width: 13px;
        height: 13px;
        font-weight: 900;
    }

    .interest-form-tag span.active svg {
        color: #0d4036;
        font-weight: bolder;
    }


    /* Applied when an industry is selected */
.interest-form-tags.industry-selected .interest-form-tag{
           padding: 5px 10px;
 /* adjust as needed */
}

/* Default tags state (before industry selection) */
.interest-form-tag.is-default {
    /* filter: blur(2px); */
    opacity: 0.7;
    cursor: not-allowed;
    pointer-events: none;
}

/* When industry is selected, restore default look */
.interest-form-tags.industry-selected .interest-form-tag.is-default {
    filter: none;
    opacity: 1;
    cursor: pointer;
    pointer-events: auto;
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
        font-size: 13px !important;

        font-family: "GT Walsheim Trial Md" !important;
        letter-spacing: 0.5px;

        outline: none;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        box-sizing: border-box;
        resize: none !important;
        /* Prevent manual resizing */
        border: 1px solid #fff;
    }

    /* Specific styling for the larger description textarea */
    #companyDescription {
        /* border-radius: 20px; */
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
        color: #f0f0f0;
        margin-left: 23px;
        line-height: 1.18;
        /* line-height: 1.5; */
        font-family: "GT Walsheim Con";
        margin-bottom: 0 !important;

    }

    .interest-form-list {
        color: #f0f0f0;
        font-size: 12px;
        font-weight: 500;
        padding-left: 20px;
        margin-left: 40px;
        /* margin-top: 5px; */
        font-family: "GT Walsheim Con";

    }

    .interest-form-list li {
        margin-bottom: 5px;
        line-height: 1.1;

    }

    /* Custom Radio Button Group Styling (Three Buttons) */
    .interest-form-option-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 10px;
        justify-content: center;
        width: 100%;
        /* margin-bottom: 30px; */
    }

    .interest-form-option input[type="radio"] {
        /* Hide the actual radio button */
        display: none;
    }

    .interest-form-option label {
        display: block;
        padding: 8px 9px;

        border-radius: 17px;
        cursor: pointer;
        font-size: 10px;
        /* font-weight: 600; */
        text-align: center;
        letter-spacing: 0.5px;
        transition: background-color 0.3s ease, color 0.3s ease;

        background-color: transparent;
        border: 1px solid #fff;
        color: #fff;

        margin: 0;
        /* Override default label margin */
        white-space: nowrap;
    }

    /* Checked (Active) State for the Option Button */
    .interest-form-option input[type="radio"]:checked+label {
        background-color: #fff;
        border-color: #ffffff;
        color: #0d4036;

        /* Dark teal text when active */
    }

    .interest-form-option label:hover {
        background-color: rgba(255, 255, 255, 0.9);
        color: #0d4036;

    }

    /* Submit Button Override */
    .interest-form-submit-button {
        padding: 13px 45px;
        background-color: #CCED60;
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
        background-color: rgba(214, 232, 133, 0.956);
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
        padding: 13px 45px;

        background-color: #cced60;
        color: #0d4036;
        border: none;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        font-weight: 500;

        font-family: "GT Walsheim Trial Md";
        outline: none;
    }

    .interest-form-return-button,
    .interest-form-proceed-button,
    .interest-form-submit-button {
        /* Uses the slightly transparent input background for its base color */
        padding: 9px 35px;
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
    .interest-form-footer-contact-item span i.fa-phone-alt{
        transform: rotate(90deg);
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

    .error-message {
        display: flex !important;
        font-size: 12px !important;
        margin-left: 27px !important;
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

    .phone-input-container {
        display: flex;
        gap: 4px;
        align-items: center;
        position: relative;
    }

    .input-with-prefix {
        display: flex;
        background-color: rgba(255, 255, 255, 0.2) !important;
        border-radius: 30px;
        width: 100%;
        padding: 18px 10px;

        border: 1px solid #fff;
    }

    input#phoneNumber {
        width: 100%;
        flex: 1;
        background-color: transparent !important;
        border-radius: none;
        border: none;
        padding: 0px;
        padding-left: 11px;

    }

    /* Phone prefix block inside input group */
    .phone-prefix {
        /* padding: 10px 12px; */
        background: transparent;
        padding-right: 10px;
        /* border-left: 1px solid #ccc; */
        border-right: 1px solid #ccc;
        /* border-radius: 8px 0 0 8px; */
        font-size: 10.2px;
        /* min-width: 10px; */
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
    }

    /* When phonePrefix sits beside input */
    .phone-input-container input#phoneNumber {
        border-radius: 0 8px 8px 0 !important;
    }



    .country-select {
        position: relative;
        /* width: 100%; */
        cursor: pointer;
    }

    .selected-country {
        display: flex;
        align-items: center;
        gap: 6px;
        justify-content: center;
        border: 1px solid #ccc;
        padding: 18px 10px;
        border-radius: 30px;
        background: #4BA1A780;
        width: 73px;
        height: 57px;
    }



    .flag-icon {
        font-size: 20px;
        display: flex;
        align-items: center;
    }

    .flag-icon img {
        height: 16px !important;
        width: 24px !important;
    }

    .arrow {
        transition: transform 0.25s ease;
        font-size: 14px;
    }

    .arrow img {
        width: 12px;
        object-fit: cover;
        height: auto;
        opacity: 0.6;
    }

    .arrow.rotate-up {
        transform: rotate(180deg);
    }

    .country-dropdown {
        background: #137077;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        transform: translateY(-10px);
        transition: all 0.3s ease;
        border-radius: 8px;
        margin-top: 6px;
        display: none;
        position: absolute;
        z-index: 10;
        width: 200px;
    }

    /* Scrollbar Styling */
    .country-list::-webkit-scrollbar {
        width: 6px;
    }

    .country-list::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 3px;
    }

    .country-list::-webkit-scrollbar-thumb {
        background: #fff;
        border-radius: 3px;
    }

    .country-list::-webkit-scrollbar-thumb:hover {
        background: #ffffffac;

    }


    input#countrySearch {
        width: 89%;
        /* margin: 0 20px; */
        margin: 6px 0px;
        margin-left: 13px;
        padding: 8px 10px;

        border: none;
        border-bottom: 1px solid #eee;
        border-radius: 14px;
        color: #fff;
        font-size: 10px;

    }

    .country-list {
        max-height: 180px;
        overflow-y: auto;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .country-list li {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        cursor: pointer;
        transition: background 0.2s;
        color: white;
    }

    .country-list li.no-country {
        font-size: 10px !important;
        color: #fff !important;


    }

    .country-list li.no-country:hover {
        font-size: 10px !important;
        background: transparent !important;
        color: #fff !important;
    }

    .country-list li span {
        font-size: 10px;
    }

    .country-list li:hover {
        background-color: rgba(255, 255, 255, 0.15);

    }

    .country-list img {
        object-fit: contain;
        width: 14px;
        height: 14px;
    }


    /* ==================================  CHECKBOX FOR STAGE 2 Form Container */
    .checkbox-wrapper {
        padding-top: 17px;
    }

    .checkbox-container {
        color: #ffffff;
        font-size: 15px;
        display: flex;
        /* justify-content: center; */
        align-items: center;
        cursor: pointer;
        gap: 10px;


    }

    .checkbox-container {
        font-family: "GT Walsheim Con";

        font-weight: 100;
        opacity: 0.92;
        font-size: 14px;
        letter-spacing: 0.6px;
    }

    .checkbox-container input {
        display: none;
    }

    .checkmark {
        width: 18px;
        height: 18px;
        border: 2px solid #ffffff;
        border-radius: 3px;
        background: transparent;
        position: relative;
        transition: background 0.2s ease;
    }

    .checkbox-container input:checked+.checkmark {
        background: #ffffff;
    }

    .checkbox-container input:checked+.checkmark::after {
        content: "✓";
        color: #137077;
        font-size: 14px;
        position: absolute;
        top: -2px;
        left: 2px;
        font-weight: bold;
    }

    /* visual blur + dim when disabled by checkbox */
    .interest-form-group input.blurred {
        opacity: 0.25;
        transition: filter .18s ease, opacity .18s ease;
        pointer-events: none;
        /* prevents focusing / interaction visually */
    }

    /* keep native disabled appearance accessible too */
    .interest-form-group input:disabled {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }


    #responseModal .modal-body {
        font-family: "Montserrat", sans-serif;
        color: #0D4036;
    }


    #responseModal .modal-dialog {
        max-width: 800px !important;
    }

    #responseModal .modal-content {
        border-radius: 30px !important;
        padding: 30px 0px !important;
        background-image: url(../img/modal-bg.png);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        background-color: #ffffff;
        background-blend-mode: difference;

    }

    #responseModal .modal-body {
        max-width: 460px !important;
        margin: 0 auto !important;
    }

    #responseModal .modal-body button.btn-secondary.btn {
        background-color: #0D4036 !important;
        padding: 13px 70px;
        border-radius: 10px;
        margin-top: 20px;
        font-family: "GT Walsheim Con" !important;

    }

    .response-messages p {
        font-size: 15px;
        font-weight: light;
        margin-bottom: 2px;
        padding: 0px 18px;
        color: #0D4036;
    }

    .response-messages h4 {
        font-size: 17px;
        font-weight: 500;
        padding: 0px 17px;
        color: #0D4036;

    }




    /* Desktop specific styles */

    @media (min-width: 768px) {

        .interest-form-tag {

            padding: 8px 25px;

      
    }
                  .interest-form-return-button,
        .interest-form-proceed-button,
        .interest-form-submit-button {
            /* Uses the slightly transparent input background for its base color */
            padding: 13px 45px;
        }

        .custom-select-trigger img {
            width: 13px;

        }


        .interest-form-option label {
            padding: 12px 15px;


        }
    }

    @media (min-width: 992px) {
        .form-layout-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            padding-right: 20px;
        }

        .interest-form-heading .header h1 {

            line-height: 1;
        }

        .interest-form-heading .subtitle p {
            font-size: 15px;

        }

        .input-with-prefix {
            padding: 18px 20px;


        }


        .fourth-stage-form-container label.value-offer {
            max-width: 220px;

        }

        .fourth-stage-form-container label.company-desc {
            max-width: 280px;

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
            padding: 35px 40px;
            padding-bottom: 10px;
        }

        .interest-form-container .form-container .first-stage-form-container {
            padding-bottom: 50px;

        }

        .interest-form-container .form-container .third-stage-form-container,
        .interest-form-container .form-container .fourth-stage-form-container {
            padding-bottom: 40px;

        }

        .interest-form-container .form-container .fourth-stage-form-container {
            padding-bottom: 30px;

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
        .interest-form-proceed-button,
        .interest-form-submit-button {
            /* Uses the slightly transparent input background for its base color */
            padding: 11px 70px;
        }

        .first-stage-form-container .interest-form-proceed-button {
            padding: 11px 76px;
            font-weight: lighter;

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

        input {

            padding-left: 40px;
        }


        textarea#offerValue {
            padding-left: 40px;

        }

        textarea#companyDescription {
            padding-left: 40px;

        }

        /* Custom Radio Button Group Styling (Three Buttons) */
        .interest-form-option-buttons {

            flex-wrap: nowrap;

            /* margin-bottom: 30px; */
        }

        /* Text inside the trigger */
        .custom-select-text {
            /* color: rgba(255, 255, 255, 0.9) !important;
        font-family: "GT Walsheim Trial Md"; */
            padding-left: 20px;
        }

        .error-message {

            margin-left: 35px !important;
        }

        .interest-form-question {
            font-size: 14px;
            margin-bottom: 4px !important;
            padding-right: 40px !important;


        }

        .interest-form-list {
            font-size: 14px !important;

        }

        .interest-form-list li {
            line-height: 1;

        }


            .response-messages p {
        font-size: 18px;
        font-weight: light;
        margin-bottom: 2px;
        padding: 0px 18px;
        color: #0D4036;
    }

    .response-messages h4 {
        font-size: 20px;
        font-weight: 500;
        padding: 0px 17px;
        color: #0D4036;

    }



    }

    @media (min-width: 1440px) {
        .interest-form-layout-container {
            margin-top: 140px;
        }

        .form-layout-container {
            padding: 0px;
        }

        .interest-form-heading .header h1 {
            font-size: 42px;
        }

        .interest-form-option label {
            font-size: 11px;
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
                                    <input type="text" id="fullName" name="name" placeholder="Full name" />
                                </div>

                                <div class="interest-form-group">
                                    <label for="title">Title</label>
                                    <div class="custom-select" id="customSelect" data-select="title">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Title, e.g CEO</span>
                                            <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                alt="" />
                                    
                                        </div>
                                        <div class="custom-options">
                                            <span class="custom-option" data-value="CEO">CEO</span>
                                            <span class="custom-option" data-value="CTO">CTO</span>
                                            <span class="custom-option" data-value="CFO">CFO</span>
                                            <span class="custom-option" data-value="CFO">Founder</span>
                                            <span class="custom-option" data-value="CFO">Investor</span>
                                            <span class="custom-option" data-value="Manager">Managing Director</span>
                                            <span class="custom-option" data-value="Director">Director</span>
                                            {{-- <span class="custom-option" data-value="Other">Other</span> --}}
                                        </div>
                                    </div>

                                    <!-- Hidden native select for form submission -->
                                    <select id="title" name="position" style="display: none">
                                        <option value="">Title, e.g CEO</option>
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
                                        placeholder="Business Name" />
                                </div>

                                <div class="interest-form-group">
                                    <label for="emailAddress">Email Address</label>
                                    <input type="email" id="emailAddress" name="email"
                                        placeholder="Email Address" />
                                    <!-- Email validation status -->
                                    <div id="email-status" style="display: none; margin-top: 8px; font-size: 0.875rem;">
                                        <span id="email-checkmark" style="color: #10b981; display: none;">✓ Email
                                            available</span>
                                        <span id="email-spinner" style="color: #ffff; display: none;">⏳
                                            Validating...</span>
                                    </div>
                                </div>

                                {{-- Phone Number --}}
                                <div class="interest-form-group">
                                    <label for="phoneNumber">Phone Number</label>
                                    <div class="phone-input-container">

                                        <!-- Country Selector -->
                                        <div class="country-select" id="countrySelect">

                                            <!-- Selected Country Display -->
                                            <div class="selected-country" id="selectedCountry">
                                                <div class="flag-icon placeholder-flag" id="selectedFlag">🌍</div>
                                                <span class="arrow" id="countryArrow">
                                                    <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                        alt="" />
                                                </span>
                                            </div>

                                            <!-- Dropdown -->
                                            <!-- Dropdown -->
                                            <div class="country-dropdown" id="countryDropdown">
                                                <input type="text" id="countrySearch" class="country-search"
                                                    placeholder="Search country...">
                                                <ul class="country-list" id="countryList">
                                                    <li data-code="+93" data-flag="af"><img
                                                            src="https://flagcdn.com/w40/af.png"><span>Afghanistan
                                                            (+93)</span></li>
                                                    <li data-code="+355" data-flag="al"><img
                                                            src="https://flagcdn.com/w40/al.png"><span>Albania
                                                            (+355)</span></li>
                                                    <li data-code="+213" data-flag="dz"><img
                                                            src="https://flagcdn.com/w40/dz.png"><span>Algeria
                                                            (+213)</span></li>
                                                    <li data-code="+376" data-flag="ad"><img
                                                            src="https://flagcdn.com/w40/ad.png"><span>Andorra
                                                            (+376)</span></li>
                                                    <li data-code="+244" data-flag="ao"><img
                                                            src="https://flagcdn.com/w40/ao.png"><span>Angola (+244)</span>
                                                    </li>
                                                    <li data-code="+54" data-flag="ar"><img
                                                            src="https://flagcdn.com/w40/ar.png"><span>Argentina
                                                            (+54)</span></li>
                                                    <li data-code="+374" data-flag="am"><img
                                                            src="https://flagcdn.com/w40/am.png"><span>Armenia
                                                            (+374)</span></li>
                                                    <li data-code="+61" data-flag="au"><img
                                                            src="https://flagcdn.com/w40/au.png"><span>Australia
                                                            (+61)</span></li>
                                                    <li data-code="+43" data-flag="at"><img
                                                            src="https://flagcdn.com/w40/at.png"><span>Austria (+43)</span>
                                                    </li>
                                                    <li data-code="+994" data-flag="az"><img
                                                            src="https://flagcdn.com/w40/az.png"><span>Azerbaijan
                                                            (+994)</span></li>
                                                    <li data-code="+973" data-flag="bh"><img
                                                            src="https://flagcdn.com/w40/bh.png"><span>Bahrain
                                                            (+973)</span></li>
                                                    <li data-code="+880" data-flag="bd"><img
                                                            src="https://flagcdn.com/w40/bd.png"><span>Bangladesh
                                                            (+880)</span></li>
                                                    <li data-code="+375" data-flag="by"><img
                                                            src="https://flagcdn.com/w40/by.png"><span>Belarus
                                                            (+375)</span></li>
                                                    <li data-code="+32" data-flag="be"><img
                                                            src="https://flagcdn.com/w40/be.png"><span>Belgium (+32)</span>
                                                    </li>
                                                    <li data-code="+229" data-flag="bj"><img
                                                            src="https://flagcdn.com/w40/bj.png"><span>Benin (+229)</span>
                                                    </li>
                                                    <li data-code="+975" data-flag="bt"><img
                                                            src="https://flagcdn.com/w40/bt.png"><span>Bhutan (+975)</span>
                                                    </li>
                                                    <li data-code="+591" data-flag="bo"><img
                                                            src="https://flagcdn.com/w40/bo.png"><span>Bolivia
                                                            (+591)</span></li>
                                                    <li data-code="+55" data-flag="br"><img
                                                            src="https://flagcdn.com/w40/br.png"><span>Brazil (+55)</span>
                                                    </li>
                                                    <li data-code="+673" data-flag="bn"><img
                                                            src="https://flagcdn.com/w40/bn.png"><span>Brunei (+673)</span>
                                                    </li>
                                                    <li data-code="+359" data-flag="bg"><img
                                                            src="https://flagcdn.com/w40/bg.png"><span>Bulgaria
                                                            (+359)</span></li>
                                                    <li data-code="+226" data-flag="bf"><img
                                                            src="https://flagcdn.com/w40/bf.png"><span>Burkina Faso
                                                            (+226)</span></li>
                                                    <li data-code="+257" data-flag="bi"><img
                                                            src="https://flagcdn.com/w40/bi.png"><span>Burundi
                                                            (+257)</span></li>
                                                    <li data-code="+855" data-flag="kh"><img
                                                            src="https://flagcdn.com/w40/kh.png"><span>Cambodia
                                                            (+855)</span></li>
                                                    <li data-code="+237" data-flag="cm"><img
                                                            src="https://flagcdn.com/w40/cm.png"><span>Cameroon
                                                            (+237)</span></li>
                                                    <li data-code="+1" data-flag="ca"><img
                                                            src="https://flagcdn.com/w40/ca.png"><span>Canada (+1)</span>
                                                    </li>
                                                    <li data-code="+238" data-flag="cv"><img
                                                            src="https://flagcdn.com/w40/cv.png"><span>Cape Verde
                                                            (+238)</span></li>
                                                    <li data-code="+236" data-flag="cf"><img
                                                            src="https://flagcdn.com/w40/cf.png"><span>Central African
                                                            Republic (+236)</span></li>
                                                    <li data-code="+235" data-flag="td"><img
                                                            src="https://flagcdn.com/w40/td.png"><span>Chad (+235)</span>
                                                    </li>
                                                    <li data-code="+56" data-flag="cl"><img
                                                            src="https://flagcdn.com/w40/cl.png"><span>Chile (+56)</span>
                                                    </li>
                                                    <li data-code="+86" data-flag="cn"><img
                                                            src="https://flagcdn.com/w40/cn.png"><span>China (+86)</span>
                                                    </li>
                                                    <li data-code="+57" data-flag="co"><img
                                                            src="https://flagcdn.com/w40/co.png"><span>Colombia
                                                            (+57)</span></li>
                                                    <li data-code="+269" data-flag="km"><img
                                                            src="https://flagcdn.com/w40/km.png"><span>Comoros
                                                            (+269)</span></li>
                                                    <li data-code="+242" data-flag="cg"><img
                                                            src="https://flagcdn.com/w40/cg.png"><span>Congo (+242)</span>
                                                    </li>
                                                    <li data-code="+243" data-flag="cd"><img
                                                            src="https://flagcdn.com/w40/cd.png"><span>DR Congo
                                                            (+243)</span></li>
                                                    <li data-code="+506" data-flag="cr"><img
                                                            src="https://flagcdn.com/w40/cr.png"><span>Costa Rica
                                                            (+506)</span></li>
                                                    <li data-code="+225" data-flag="ci"><img
                                                            src="https://flagcdn.com/w40/ci.png"><span>Ivory Coast
                                                            (+225)</span></li>
                                                    <li data-code="+385" data-flag="hr"><img
                                                            src="https://flagcdn.com/w40/hr.png"><span>Croatia
                                                            (+385)</span></li>
                                                    <li data-code="+53" data-flag="cu"><img
                                                            src="https://flagcdn.com/w40/cu.png"><span>Cuba (+53)</span>
                                                    </li>
                                                    <li data-code="+357" data-flag="cy"><img
                                                            src="https://flagcdn.com/w40/cy.png"><span>Cyprus (+357)</span>
                                                    </li>
                                                    <li data-code="+420" data-flag="cz"><img
                                                            src="https://flagcdn.com/w40/cz.png"><span>Czech Republic
                                                            (+420)</span></li>
                                                    <li data-code="+45" data-flag="dk"><img
                                                            src="https://flagcdn.com/w40/dk.png"><span>Denmark (+45)</span>
                                                    </li>
                                                    <li data-code="+253" data-flag="dj"><img
                                                            src="https://flagcdn.com/w40/dj.png"><span>Djibouti
                                                            (+253)</span></li>
                                                    <li data-code="+593" data-flag="ec"><img
                                                            src="https://flagcdn.com/w40/ec.png"><span>Ecuador
                                                            (+593)</span></li>
                                                    <li data-code="+20" data-flag="eg"><img
                                                            src="https://flagcdn.com/w40/eg.png"><span>Egypt (+20)</span>
                                                    </li>
                                                    <li data-code="+503" data-flag="sv"><img
                                                            src="https://flagcdn.com/w40/sv.png"><span>El Salvador
                                                            (+503)</span></li>
                                                    <li data-code="+240" data-flag="gq"><img
                                                            src="https://flagcdn.com/w40/gq.png"><span>Equatorial Guinea
                                                            (+240)</span></li>
                                                    <li data-code="+291" data-flag="er"><img
                                                            src="https://flagcdn.com/w40/er.png"><span>Eritrea
                                                            (+291)</span></li>
                                                    <li data-code="+372" data-flag="ee"><img
                                                            src="https://flagcdn.com/w40/ee.png"><span>Estonia
                                                            (+372)</span></li>
                                                    <li data-code="+251" data-flag="et"><img
                                                            src="https://flagcdn.com/w40/et.png"><span>Ethiopia
                                                            (+251)</span></li>
                                                    <li data-code="+679" data-flag="fj"><img
                                                            src="https://flagcdn.com/w40/fj.png"><span>Fiji (+679)</span>
                                                    </li>
                                                    <li data-code="+358" data-flag="fi"><img
                                                            src="https://flagcdn.com/w40/fi.png"><span>Finland
                                                            (+358)</span></li>
                                                    <li data-code="+33" data-flag="fr"><img
                                                            src="https://flagcdn.com/w40/fr.png"><span>France (+33)</span>
                                                    </li>
                                                    <li data-code="+241" data-flag="ga"><img
                                                            src="https://flagcdn.com/w40/ga.png"><span>Gabon (+241)</span>
                                                    </li>
                                                    <li data-code="+220" data-flag="gm"><img
                                                            src="https://flagcdn.com/w40/gm.png"><span>Gambia (+220)</span>
                                                    </li>
                                                    <li data-code="+995" data-flag="ge"><img
                                                            src="https://flagcdn.com/w40/ge.png"><span>Georgia
                                                            (+995)</span></li>
                                                    <li data-code="+49" data-flag="de"><img
                                                            src="https://flagcdn.com/w40/de.png"><span>Germany (+49)</span>
                                                    </li>
                                                    <li data-code="+233" data-flag="gh"><img
                                                            src="https://flagcdn.com/w40/gh.png"><span>Ghana (+233)</span>
                                                    </li>
                                                    <li data-code="+30" data-flag="gr"><img
                                                            src="https://flagcdn.com/w40/gr.png"><span>Greece (+30)</span>
                                                    </li>
                                                    <li data-code="+502" data-flag="gt"><img
                                                            src="https://flagcdn.com/w40/gt.png"><span>Guatemala
                                                            (+502)</span></li>
                                                    <li data-code="+224" data-flag="gn"><img
                                                            src="https://flagcdn.com/w40/gn.png"><span>Guinea (+224)</span>
                                                    </li>
                                                    <li data-code="+245" data-flag="gw"><img
                                                            src="https://flagcdn.com/w40/gw.png"><span>Guinea-Bissau
                                                            (+245)</span></li>
                                                    <li data-code="+592" data-flag="gy"><img
                                                            src="https://flagcdn.com/w40/gy.png"><span>Guyana (+592)</span>
                                                    </li>
                                                    <li data-code="+509" data-flag="ht"><img
                                                            src="https://flagcdn.com/w40/ht.png"><span>Haiti (+509)</span>
                                                    </li>
                                                    <li data-code="+504" data-flag="hn"><img
                                                            src="https://flagcdn.com/w40/hn.png"><span>Honduras
                                                            (+504)</span></li>
                                                    <li data-code="+852" data-flag="hk"><img
                                                            src="https://flagcdn.com/w40/hk.png"><span>Hong Kong
                                                            (+852)</span></li>
                                                    <li data-code="+36" data-flag="hu"><img
                                                            src="https://flagcdn.com/w40/hu.png"><span>Hungary (+36)</span>
                                                    </li>
                                                    <li data-code="+354" data-flag="is"><img
                                                            src="https://flagcdn.com/w40/is.png"><span>Iceland
                                                            (+354)</span></li>
                                                    <li data-code="+91" data-flag="in"><img
                                                            src="https://flagcdn.com/w40/in.png"><span>India (+91)</span>
                                                    </li>
                                                    <li data-code="+62" data-flag="id"><img
                                                            src="https://flagcdn.com/w40/id.png"><span>Indonesia
                                                            (+62)</span></li>
                                                    <li data-code="+98" data-flag="ir"><img
                                                            src="https://flagcdn.com/w40/ir.png"><span>Iran (+98)</span>
                                                    </li>
                                                    <li data-code="+964" data-flag="iq"><img
                                                            src="https://flagcdn.com/w40/iq.png"><span>Iraq (+964)</span>
                                                    </li>
                                                    <li data-code="+353" data-flag="ie"><img
                                                            src="https://flagcdn.com/w40/ie.png"><span>Ireland
                                                            (+353)</span></li>
                                                    <li data-code="+972" data-flag="il"><img
                                                            src="https://flagcdn.com/w40/il.png"><span>Israel (+972)</span>
                                                    </li>
                                                    <li data-code="+39" data-flag="it"><img
                                                            src="https://flagcdn.com/w40/it.png"><span>Italy (+39)</span>
                                                    </li>
                                                    <li data-code="+81" data-flag="jp"><img
                                                            src="https://flagcdn.com/w40/jp.png"><span>Japan (+81)</span>
                                                    </li>
                                                    <li data-code="+962" data-flag="jo"><img
                                                            src="https://flagcdn.com/w40/jo.png"><span>Jordan (+962)</span>
                                                    </li>
                                                    <li data-code="+7" data-flag="kz"><img
                                                            src="https://flagcdn.com/w40/kz.png"><span>Kazakhstan
                                                            (+7)</span></li>
                                                    <li data-code="+254" data-flag="ke"><img
                                                            src="https://flagcdn.com/w40/ke.png"><span>Kenya (+254)</span>
                                                    </li>
                                                    <li data-code="+686" data-flag="ki"><img
                                                            src="https://flagcdn.com/w40/ki.png"><span>Kiribati
                                                            (+686)</span></li>
                                                    <li data-code="+850" data-flag="kp"><img
                                                            src="https://flagcdn.com/w40/kp.png"><span>North Korea
                                                            (+850)</span></li>
                                                    <li data-code="+82" data-flag="kr"><img
                                                            src="https://flagcdn.com/w40/kr.png"><span>South Korea
                                                            (+82)</span></li>
                                                    <li data-code="+965" data-flag="kw"><img
                                                            src="https://flagcdn.com/w40/kw.png"><span>Kuwait (+965)</span>
                                                    </li>
                                                    <li data-code="+996" data-flag="kg"><img
                                                            src="https://flagcdn.com/w40/kg.png"><span>Kyrgyzstan
                                                            (+996)</span></li>
                                                    <li data-code="+856" data-flag="la"><img
                                                            src="https://flagcdn.com/w40/la.png"><span>Laos (+856)</span>
                                                    </li>
                                                    <li data-code="+371" data-flag="lv"><img
                                                            src="https://flagcdn.com/w40/lv.png"><span>Latvia (+371)</span>
                                                    </li>
                                                    <li data-code="+961" data-flag="lb"><img
                                                            src="https://flagcdn.com/w40/lb.png"><span>Lebanon
                                                            (+961)</span></li>
                                                    <li data-code="+266" data-flag="ls"><img
                                                            src="https://flagcdn.com/w40/ls.png"><span>Lesotho
                                                            (+266)</span></li>
                                                    <li data-code="+231" data-flag="lr"><img
                                                            src="https://flagcdn.com/w40/lr.png"><span>Liberia
                                                            (+231)</span></li>
                                                    <li data-code="+218" data-flag="ly"><img
                                                            src="https://flagcdn.com/w40/ly.png"><span>Libya (+218)</span>
                                                    </li>
                                                    <li data-code="+423" data-flag="li"><img
                                                            src="https://flagcdn.com/w40/li.png"><span>Liechtenstein
                                                            (+423)</span></li>
                                                    <li data-code="+370" data-flag="lt"><img
                                                            src="https://flagcdn.com/w40/lt.png"><span>Lithuania
                                                            (+370)</span></li>
                                                    <li data-code="+352" data-flag="lu"><img
                                                            src="https://flagcdn.com/w40/lu.png"><span>Luxembourg
                                                            (+352)</span></li>
                                                    <li data-code="+853" data-flag="mo"><img
                                                            src="https://flagcdn.com/w40/mo.png"><span>Macau (+853)</span>
                                                    </li>
                                                    <li data-code="+389" data-flag="mk"><img
                                                            src="https://flagcdn.com/w40/mk.png"><span>North Macedonia
                                                            (+389)</span></li>
                                                    <li data-code="+261" data-flag="mg"><img
                                                            src="https://flagcdn.com/w40/mg.png"><span>Madagascar
                                                            (+261)</span></li>
                                                    <li data-code="+265" data-flag="mw"><img
                                                            src="https://flagcdn.com/w40/mw.png"><span>Malawi (+265)</span>
                                                    </li>
                                                    <li data-code="+60" data-flag="my"><img
                                                            src="https://flagcdn.com/w40/my.png"><span>Malaysia
                                                            (+60)</span></li>
                                                    <li data-code="+960" data-flag="mv"><img
                                                            src="https://flagcdn.com/w40/mv.png"><span>Maldives
                                                            (+960)</span></li>
                                                    <li data-code="+223" data-flag="ml"><img
                                                            src="https://flagcdn.com/w40/ml.png"><span>Mali (+223)</span>
                                                    </li>
                                                    <li data-code="+356" data-flag="mt"><img
                                                            src="https://flagcdn.com/w40/mt.png"><span>Malta (+356)</span>
                                                    </li>
                                                    <li data-code="+692" data-flag="mh"><img
                                                            src="https://flagcdn.com/w40/mh.png"><span>Marshall Islands
                                                            (+692)</span></li>
                                                    <li data-code="+222" data-flag="mr"><img
                                                            src="https://flagcdn.com/w40/mr.png"><span>Mauritania
                                                            (+222)</span></li>
                                                    <li data-code="+230" data-flag="mu"><img
                                                            src="https://flagcdn.com/w40/mu.png"><span>Mauritius
                                                            (+230)</span></li>
                                                    <li data-code="+52" data-flag="mx"><img
                                                            src="https://flagcdn.com/w40/mx.png"><span>Mexico (+52)</span>
                                                    </li>
                                                    <li data-code="+691" data-flag="fm"><img
                                                            src="https://flagcdn.com/w40/fm.png"><span>Micronesia
                                                            (+691)</span></li>
                                                    <li data-code="+373" data-flag="md"><img
                                                            src="https://flagcdn.com/w40/md.png"><span>Moldova
                                                            (+373)</span></li>
                                                    <li data-code="+377" data-flag="mc"><img
                                                            src="https://flagcdn.com/w40/mc.png"><span>Monaco (+377)</span>
                                                    </li>
                                                    <li data-code="+976" data-flag="mn"><img
                                                            src="https://flagcdn.com/w40/mn.png"><span>Mongolia
                                                            (+976)</span></li>
                                                    <li data-code="+382" data-flag="me"><img
                                                            src="https://flagcdn.com/w40/me.png"><span>Montenegro
                                                            (+382)</span></li>
                                                    <li data-code="+212" data-flag="ma"><img
                                                            src="https://flagcdn.com/w40/ma.png"><span>Morocco
                                                            (+212)</span></li>
                                                    <li data-code="+258" data-flag="mz"><img
                                                            src="https://flagcdn.com/w40/mz.png"><span>Mozambique
                                                            (+258)</span></li>
                                                    <li data-code="+95" data-flag="mm"><img
                                                            src="https://flagcdn.com/w40/mm.png"><span>Myanmar (+95)</span>
                                                    </li>
                                                    <li data-code="+264" data-flag="na"><img
                                                            src="https://flagcdn.com/w40/na.png"><span>Namibia
                                                            (+264)</span></li>
                                                    <li data-code="+674" data-flag="nr"><img
                                                            src="https://flagcdn.com/w40/nr.png"><span>Nauru (+674)</span>
                                                    </li>
                                                    <li data-code="+977" data-flag="np"><img
                                                            src="https://flagcdn.com/w40/np.png"><span>Nepal (+977)</span>
                                                    </li>
                                                    <li data-code="+31" data-flag="nl"><img
                                                            src="https://flagcdn.com/w40/nl.png"><span>Netherlands
                                                            (+31)</span></li>
                                                    <li data-code="+64" data-flag="nz"><img
                                                            src="https://flagcdn.com/w40/nz.png"><span>New Zealand
                                                            (+64)</span></li>
                                                    <li data-code="+505" data-flag="ni"><img
                                                            src="https://flagcdn.com/w40/ni.png"><span>Nicaragua
                                                            (+505)</span></li>
                                                    <li data-code="+227" data-flag="ne"><img
                                                            src="https://flagcdn.com/w40/ne.png"><span>Niger (+227)</span>
                                                    </li>
                                                    <li data-code="+234" data-flag="ng"><img
                                                            src="https://flagcdn.com/w40/ng.png"><span>Nigeria
                                                            (+234)</span></li>
                                                    <li data-code="+683" data-flag="nu"><img
                                                            src="https://flagcdn.com/w40/nu.png"><span>Niue (+683)</span>
                                                    </li>
                                                    <li data-code="+672" data-flag="nf"><img
                                                            src="https://flagcdn.com/w40/nf.png"><span>Norfolk Island
                                                            (+672)</span></li>
                                                    <li data-code="+47" data-flag="no"><img
                                                            src="https://flagcdn.com/w40/no.png"><span>Norway (+47)</span>
                                                    </li>
                                                    <li data-code="+968" data-flag="om"><img
                                                            src="https://flagcdn.com/w40/om.png"><span>Oman (+968)</span>
                                                    </li>
                                                    <li data-code="+92" data-flag="pk"><img
                                                            src="https://flagcdn.com/w40/pk.png"><span>Pakistan
                                                            (+92)</span></li>
                                                    <li data-code="+680" data-flag="pw"><img
                                                            src="https://flagcdn.com/w40/pw.png"><span>Palau (+680)</span>
                                                    </li>
                                                    <li data-code="+507" data-flag="pa"><img
                                                            src="https://flagcdn.com/w40/pa.png"><span>Panama (+507)</span>
                                                    </li>
                                                    <li data-code="+675" data-flag="pg"><img
                                                            src="https://flagcdn.com/w40/pg.png"><span>Papua New Guinea
                                                            (+675)</span></li>
                                                    <li data-code="+595" data-flag="py"><img
                                                            src="https://flagcdn.com/w40/py.png"><span>Paraguay
                                                            (+595)</span></li>
                                                    <li data-code="+51" data-flag="pe"><img
                                                            src="https://flagcdn.com/w40/pe.png"><span>Peru (+51)</span>
                                                    </li>
                                                    <li data-code="+63" data-flag="ph"><img
                                                            src="https://flagcdn.com/w40/ph.png"><span>Philippines
                                                            (+63)</span></li>
                                                    <li data-code="+48" data-flag="pl"><img
                                                            src="https://flagcdn.com/w40/pl.png"><span>Poland (+48)</span>
                                                    </li>
                                                    <li data-code="+351" data-flag="pt"><img
                                                            src="https://flagcdn.com/w40/pt.png"><span>Portugal
                                                            (+351)</span></li>
                                                    <li data-code="+974" data-flag="qa"><img
                                                            src="https://flagcdn.com/w40/qa.png"><span>Qatar (+974)</span>
                                                    </li>
                                                    <li data-code="+40" data-flag="ro"><img
                                                            src="https://flagcdn.com/w40/ro.png"><span>Romania (+40)</span>
                                                    </li>
                                                    <li data-code="+7" data-flag="ru"><img
                                                            src="https://flagcdn.com/w40/ru.png"><span>Russia (+7)</span>
                                                    </li>
                                                    <li data-code="+250" data-flag="rw"><img
                                                            src="https://flagcdn.com/w40/rw.png"><span>Rwanda (+250)</span>
                                                    </li>
                                                    <li data-code="+685" data-flag="ws"><img
                                                            src="https://flagcdn.com/w40/ws.png"><span>Samoa (+685)</span>
                                                    </li>
                                                    <li data-code="+378" data-flag="sm"><img
                                                            src="https://flagcdn.com/w40/sm.png"><span>San Marino
                                                            (+378)</span></li>
                                                    <li data-code="+239" data-flag="st"><img
                                                            src="https://flagcdn.com/w40/st.png"><span>São Tomé and
                                                            Príncipe (+239)</span></li>
                                                    <li data-code="+966" data-flag="sa"><img
                                                            src="https://flagcdn.com/w40/sa.png"><span>Saudi Arabia
                                                            (+966)</span></li>
                                                    <li data-code="+221" data-flag="sn"><img
                                                            src="https://flagcdn.com/w40/sn.png"><span>Senegal
                                                            (+221)</span></li>
                                                    <li data-code="+381" data-flag="rs"><img
                                                            src="https://flagcdn.com/w40/rs.png"><span>Serbia (+381)</span>
                                                    </li>
                                                    <li data-code="+248" data-flag="sc"><img
                                                            src="https://flagcdn.com/w40/sc.png"><span>Seychelles
                                                            (+248)</span></li>
                                                    <li data-code="+232" data-flag="sl"><img
                                                            src="https://flagcdn.com/w40/sl.png"><span>Sierra Leone
                                                            (+232)</span></li>
                                                    <li data-code="+65" data-flag="sg"><img
                                                            src="https://flagcdn.com/w40/sg.png"><span>Singapore
                                                            (+65)</span></li>
                                                    <li data-code="+421" data-flag="sk"><img
                                                            src="https://flagcdn.com/w40/sk.png"><span>Slovakia
                                                            (+421)</span></li>
                                                    <li data-code="+386" data-flag="si"><img
                                                            src="https://flagcdn.com/w40/si.png"><span>Slovenia
                                                            (+386)</span></li>
                                                    <li data-code="+677" data-flag="sb"><img
                                                            src="https://flagcdn.com/w40/sb.png"><span>Solomon Islands
                                                            (+677)</span></li>
                                                    <li data-code="+252" data-flag="so"><img
                                                            src="https://flagcdn.com/w40/so.png"><span>Somalia
                                                            (+252)</span></li>
                                                    <li data-code="+27" data-flag="za"><img
                                                            src="https://flagcdn.com/w40/za.png"><span>South Africa
                                                            (+27)</span></li>
                                                    <li data-code="+211" data-flag="ss"><img
                                                            src="https://flagcdn.com/w40/ss.png"><span>South Sudan
                                                            (+211)</span></li>
                                                    <li data-code="+34" data-flag="es"><img
                                                            src="https://flagcdn.com/w40/es.png"><span>Spain (+34)</span>
                                                    </li>
                                                    <li data-code="+94" data-flag="lk"><img
                                                            src="https://flagcdn.com/w40/lk.png"><span>Sri Lanka
                                                            (+94)</span></li>
                                                    <li data-code="+249" data-flag="sd"><img
                                                            src="https://flagcdn.com/w40/sd.png"><span>Sudan (+249)</span>
                                                    </li>
                                                    <li data-code="+597" data-flag="sr"><img
                                                            src="https://flagcdn.com/w40/sr.png"><span>Suriname
                                                            (+597)</span></li>
                                                    <li data-code="+268" data-flag="sz"><img
                                                            src="https://flagcdn.com/w40/sz.png"><span>Eswatini
                                                            (+268)</span></li>
                                                    <li data-code="+46" data-flag="se"><img
                                                            src="https://flagcdn.com/w40/se.png"><span>Sweden (+46)</span>
                                                    </li>
                                                    <li data-code="+41" data-flag="ch"><img
                                                            src="https://flagcdn.com/w40/ch.png"><span>Switzerland
                                                            (+41)</span></li>
                                                    <li data-code="+963" data-flag="sy"><img
                                                            src="https://flagcdn.com/w40/sy.png"><span>Syria (+963)</span>
                                                    </li>
                                                    <li data-code="+886" data-flag="tw"><img
                                                            src="https://flagcdn.com/w40/tw.png"><span>Taiwan (+886)</span>
                                                    </li>
                                                    <li data-code="+992" data-flag="tj"><img
                                                            src="https://flagcdn.com/w40/tj.png"><span>Tajikistan
                                                            (+992)</span></li>
                                                    <li data-code="+255" data-flag="tz"><img
                                                            src="https://flagcdn.com/w40/tz.png"><span>Tanzania
                                                            (+255)</span></li>
                                                    <li data-code="+66" data-flag="th"><img
                                                            src="https://flagcdn.com/w40/th.png"><span>Thailand
                                                            (+66)</span></li>
                                                    <li data-code="+670" data-flag="tl"><img
                                                            src="https://flagcdn.com/w40/tl.png"><span>Timor-Leste
                                                            (+670)</span></li>
                                                    <li data-code="+228" data-flag="tg"><img
                                                            src="https://flagcdn.com/w40/tg.png"><span>Togo (+228)</span>
                                                    </li>
                                                    <li data-code="+676" data-flag="to"><img
                                                            src="https://flagcdn.com/w40/to.png"><span>Tonga (+676)</span>
                                                    </li>
                                                    <li data-code="+216" data-flag="tn"><img
                                                            src="https://flagcdn.com/w40/tn.png"><span>Tunisia
                                                            (+216)</span></li>
                                                    <li data-code="+90" data-flag="tr"><img
                                                            src="https://flagcdn.com/w40/tr.png"><span>Turkey (+90)</span>
                                                    </li>
                                                    <li data-code="+993" data-flag="tm"><img
                                                            src="https://flagcdn.com/w40/tm.png"><span>Turkmenistan
                                                            (+993)</span></li>
                                                    <li data-code="+688" data-flag="tv"><img
                                                            src="https://flagcdn.com/w40/tv.png"><span>Tuvalu (+688)</span>
                                                    </li>
                                                    <li data-code="+256" data-flag="ug"><img
                                                            src="https://flagcdn.com/w40/ug.png"><span>Uganda (+256)</span>
                                                    </li>
                                                    <li data-code="+380" data-flag="ua"><img
                                                            src="https://flagcdn.com/w40/ua.png"><span>Ukraine
                                                            (+380)</span></li>
                                                    <li data-code="+971" data-flag="ae"><img
                                                            src="https://flagcdn.com/w40/ae.png"><span>United Arab Emirates
                                                            (+971)</span></li>
                                                    <li data-code="+44" data-flag="gb"><img
                                                            src="https://flagcdn.com/w40/gb.png"><span>United Kingdom
                                                            (+44)</span></li>
                                                    <li data-code="+1" data-flag="us"><img
                                                            src="https://flagcdn.com/w40/us.png"><span>United States
                                                            (+1)</span></li>
                                                    <li data-code="+598" data-flag="uy"><img
                                                            src="https://flagcdn.com/w40/uy.png"><span>Uruguay
                                                            (+598)</span></li>
                                                    <li data-code="+998" data-flag="uz"><img
                                                            src="https://flagcdn.com/w40/uz.png"><span>Uzbekistan
                                                            (+998)</span></li>
                                                    <li data-code="+678" data-flag="vu"><img
                                                            src="https://flagcdn.com/w40/vu.png"><span>Vanuatu
                                                            (+678)</span></li>
                                                    <li data-code="+39" data-flag="va"><img
                                                            src="https://flagcdn.com/w40/va.png"><span>Vatican City
                                                            (+39)</span></li>
                                                    <li data-code="+58" data-flag="ve"><img
                                                            src="https://flagcdn.com/w40/ve.png"><span>Venezuela
                                                            (+58)</span></li>
                                                    <li data-code="+84" data-flag="vn"><img
                                                            src="https://flagcdn.com/w40/vn.png"><span>Vietnam (+84)</span>
                                                    </li>
                                                    <li data-code="+967" data-flag="ye"><img
                                                            src="https://flagcdn.com/w40/ye.png"><span>Yemen (+967)</span>
                                                    </li>
                                                    <li data-code="+260" data-flag="zm"><img
                                                            src="https://flagcdn.com/w40/zm.png"><span>Zambia (+260)</span>
                                                    </li>
                                                    <li data-code="+263" data-flag="zw"><img
                                                            src="https://flagcdn.com/w40/zw.png"><span>Zimbabwe
                                                            (+263)</span></li>
                                                </ul>


                                            </div>
                                        </div>

                                        <!-- Hidden actual country code -->
                                        <input type="hidden" name="country_code" id="countryCode">
                                        <div class="input-with-prefix">

                                            <!-- Phone Prefix (new UI block) -->
                                            <div class="phone-prefix" id="phonePrefix">--</div>
                                            <!-- Phone Input -->
                                            <input type="tel" id="phoneNumber" name="phone"
                                                placeholder="Select country code first" disabled>
                                        </div>

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
                                            <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                alt="" />
                                        </div>
                                        <div class="custom-options countryReg-options" id="countryReg-options">
                                            <input type="text" class="country-search" id="countrySearchReg"
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
                                                <span class="custom-option" data-value="Saint Kitts and Nevis">Saint
                                                    Kitts
                                                    and
                                                    Nevis</span>
                                                <span class="custom-option" data-value="Saint Lucia">Saint Lucia</span>
                                                <span class="custom-option"
                                                    data-value="Saint Vincent and the Grenadines">Saint
                                                    Vincent and the
                                                    Grenadines</span>
                                                <span class="custom-option" data-value="Samoa">Samoa</span>
                                                <span class="custom-option" data-value="San Marino">San Marino</span>
                                                <span class="custom-option" data-value="Sao Tome and Principe">Sao Tome
                                                    and
                                                    Principe</span>
                                                <span class="custom-option" data-value="Saudi Arabia">Saudi
                                                    Arabia</span>
                                                <span class="custom-option" data-value="Senegal">Senegal</span>
                                                <span class="custom-option" data-value="Serbia">Serbia</span>
                                                <span class="custom-option" data-value="Seychelles">Seychelles</span>
                                                <span class="custom-option" data-value="Sierra Leone">Sierra
                                                    Leone</span>
                                                <span class="custom-option" data-value="Singapore">Singapore</span>
                                                <span class="custom-option" data-value="Slovakia">Slovakia</span>
                                                <span class="custom-option" data-value="Slovenia">Slovenia</span>
                                                <span class="custom-option" data-value="Solomon Islands">Solomon
                                                    Islands</span>
                                                <span class="custom-option" data-value="Somalia">Somalia</span>
                                                <span class="custom-option" data-value="South Africa">South
                                                    Africa</span>
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
                                                <span class="custom-option" data-value="Trinidad and Tobago">Trinidad
                                                    and
                                                    Tobago</span>
                                                <span class="custom-option" data-value="Tunisia">Tunisia</span>
                                                <span class="custom-option" data-value="Turkey">Turkey</span>
                                                <span class="custom-option"
                                                    data-value="Turkmenistan">Turkmenistan</span>
                                                <span class="custom-option" data-value="Tuvalu">Tuvalu</span>
                                                <span class="custom-option" data-value="Uganda">Uganda</span>
                                                <span class="custom-option" data-value="Ukraine">Ukraine</span>
                                                <span class="custom-option" data-value="United Arab Emirates">United
                                                    Arab
                                                    Emirates</span>
                                                <span class="custom-option" data-value="United Kingdom">United
                                                    Kingdom</span>
                                                <span class="custom-option" data-value="United States">United
                                                    States</span>
                                                <span class="custom-option" data-value="Uruguay">Uruguay</span>
                                                <span class="custom-option" data-value="Uzbekistan">Uzbekistan</span>
                                                <span class="custom-option" data-value="Vanuatu">Vanuatu</span>
                                                <span class="custom-option" data-value="Vatican City">Vatican
                                                    City</span>
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

                                    <div class="checkbox-wrapper">
                                        <label class="checkbox-container" for="no_linkedin">
                                            <input type="checkbox" id="no_linkedin" name="no_linkedin" />
                                            <span class="checkmark"></span>
                                            I don’t have a LinkedIn account.
                                        </label>
                                    </div>
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
                                <div class="interest-form-group" id="industry-selects-group">
                                    <label for="industry">What industry does your business operate in?</label>
                                    <div class="custom-select" data-select="industry">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Select your industry</span>
                                            <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                alt="" />
                                        </div>
                                      <div class="custom-options">
                                            <span class="custom-option" data-value="technologyAndDigitalServices">
                                                Technology & Digital Services
                                            </span>

                                            <span class="custom-option" data-value="financialAndProfessionalServices">
                                                Financial & Professional Services
                                            </span>

                                            <span class="custom-option" data-value="manufacturingAndIndustrial">
                                                Manufacturing & Industrial
                                            </span>

                                            <span class="custom-option" data-value="healthcareAndPharmaceuticals">
                                                Healthcare & Pharmaceuticals
                                            </span>

                                            <span class="custom-option" data-value="energyAndUtilities">
                                                Energy & Utilities
                                            </span>

                                            <span class="custom-option" data-value="retailAndConsumerGoods">
                                                Retail & Consumer Goods
                                            </span>

                                            <span class="custom-option" data-value="logisticsAndTransportation">
                                                Logistics & Transportation
                                            </span>

                                            <span class="custom-option" data-value="agricultureAndFoodProcessing">
                                                Agriculture & Food Processing
                                            </span>

                                            <span class="custom-option" data-value="realEstateAndConstruction">
                                                Real Estate & Construction
                                            </span>

                                            <span class="custom-option" data-value="mediaAndCommunications">
                                                Media & Communications
                                            </span>

                                            <span class="custom-option" data-value="educationAndTraining">
                                                Education & Training
                                            </span>

                                            <span class="custom-option" data-value="governmentAndPublicServices">
                                                Government & Public Services
                                            </span>

                                            <span class="custom-option" data-value="environmentalAndSpecializedServices">
                                                Environmental & Specialized Services
                                            </span>
                                      </div>

                                    </div>

                                    {{-- <p class="industry-message">kindly select your industry first</p> --}}

                                    <select name="industry" id="industry" style="display: none">
                                        <option value="technologyAndDigitalServices">  Technology & Digital Services</option>
                                        <option value="financialAndProfessionalServices"> Financial & Professional Services</option>
                                        <option value="manufacturingAndIndustrial"> Manufacturing & Industrial</option>
                                        <option value="healthcareAndPharmaceuticals"> Healthcare & Pharmaceuticals</option>
                                        <option value="energyAndUtilities"> Energy & Utilities</option>
                                        <option value="retailAndConsumerGoods"> Retail & Consumer Goods</option>
                                        <option value="logisticsAndTransportation"> Logistics & Transportation</option>
                                        <option value="agricultureAndFoodProcessing"> Agriculture & Food Processing</option>
                                        <option value="realEstateAndConstruction"> Real Estate & Construction</option>
                                        <option value="mediaAndCommunications"> Media & Communications</option>
                                        <option value="educationAndTraining">Education & Training</option>
                                        <option value="governmentAndPublicServices"> Government & Public Services</option>
                                        <option value="environmentalAndSpecializedServices"> Environmental & Specialized Services</option>
                                     
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
                                            <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                alt="" />
                                        </div>

                                        <div class="custom-options" id="number-of-employees">
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
                                            <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                alt="" />
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
                                    <label class="value-offer" for="offerValue">What value can your company offer to
                                        other CEOs in the
                                        network?</label>
                                    <textarea id="offerValue" name="offer_value" rows="1" placeholder="Enter your value"></textarea>
                                </div>

                                <div class="interest-form-group">
                                    <label for="companyDescription" class="company-desc">Briefly describe what your
                                        company does and who your
                                        clients/customers are (max 150 words)</label>
                                    <textarea id="companyDescription" name="goals" rows="5" placeholder="Enter your description"></textarea>
                                </div>

                                <div class="interest-form-group">
                                    <label for="interest">What are you most interested in from this network?</label>

                                    <div class="custom-select" data-select="interest">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Select interest</span>
                                            <span class="custom-select-arrow">
                                                <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                    alt="" />
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
                                {{-- <p id="responseMessage" class="mb-3"></p> --}}
                                <div id="responseMessage">

                                </div>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Done</button>
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
                                    <span class="interest-form-footer-icon phone"><i class="fas fa-phone-alt"></i></span>
                                    <a href="tel:+442080921281" class="phone">+44 7535 659835</a>
                                </div>

                                <div class="interest-form-footer-contact-item">
                                    <span class="interest-form-footer-icon"><i class="fas fa-envelope"></i></span>
                                    <a href="mailto:hello@p23africa.com">hello@p23africa.com</a>
                                </div>
                            </div>

                            <div class="interest-form-footer-contact-item">
                                <span class="interest-form-footer-icon location">
                                    <img class="location-icon" src="{{ asset('img/location_pin_place_map.png') }}"
                                                alt="" />
                                </span>
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

            // Get current CSRF token
            const csrfToken = document.querySelector('input[name="_token"]')?.value ||
                document.querySelector('meta[name="csrf-token"]')?.content;

            fetch('{{ route('brn.check-email') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email
                    }),
                    credentials: 'same-origin'
                })
                .then(async response => {
                    // Handle 419 error by refreshing token and retrying once
                    if (response.status === 419) {
                        const newToken = await refreshCsrfToken();
                        if (newToken) {
                            updateCsrfToken(newToken);
                            // Retry with new token
                            return fetch('{{ route('brn.check-email') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': newToken,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    email: email
                                }),
                                credentials: 'same-origin'
                            }).then(r => r.json());
                        }
                    }
                    return response.json();
                })
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
                    // Silently fail - don't block user from continuing
                    showEmailStatus('hide');
                    console.warn('Email check failed:', error);
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
            input.style.borderRadius = '30px';
            // input.style.backgroundColor = '#fef2f2';

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
            const hiddenSelect = document.querySelector(
                `select[name="${getFieldName(selectName)}"]`
            );

            // Toggle dropdown
            trigger.addEventListener("click", (e) => {
                e.stopPropagation();

                document.querySelectorAll(".custom-select.open").forEach((openSelect) => {
                    if (openSelect !== customSelect) openSelect.classList.remove(
                        "open");
                });

                customSelect.classList.toggle("open");
            });

            // Handle selecting an option
            options.forEach((option) => {
                option.addEventListener("click", (e) => {
                    e.stopPropagation();

                    const value = option.dataset.value;
                    const label = option.textContent;

                    // Update visible trigger text
                    text.textContent = label;

                    // Remove active + selected from all options
                    options.forEach((o) => o.classList.remove("active", "selected"));
                    text.classList.remove("selected");

                    // Add active + selected to clicked option
                    option.classList.add("active", "selected");

                    // Make trigger text white
                    text.classList.add("selected");

                    // Update hidden select
                    if (hiddenSelect) {
                        hiddenSelect.value = value;
                    }

                    // Close dropdown
                    customSelect.classList.remove("open");

                    // Update categories if needed
                    if (selectName === "industry") {
                        updateCategories(value);
                    }

                    // Save form data
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


        // FOR STEP 1 - Phone NUmber Input 

        const selectedCountry = document.getElementById("selectedCountry");
        const countryDropdown = document.getElementById("countryDropdown");
        const arrow = document.getElementById("countryArrow");

        const selectedFlag = document.getElementById("selectedFlag");
        const countryCodeInput = document.getElementById("countryCode");
        const phonePrefix = document.getElementById("phonePrefix");
        const phoneInput = document.getElementById("phoneNumber");

        const searchInput = document.getElementById("countrySearch");
        const list = document.getElementById("countryList");

        // Create a "No country found" placeholder item
        let noCountryItem = document.createElement("li");
        noCountryItem.className = "no-country";
        noCountryItem.style.display = "none";
        noCountryItem.style.justifyContent = "center";
        noCountryItem.style.padding = "8px";
        noCountryItem.style.color = "#6b7280";
        noCountryItem.textContent = "No country found";
        list.appendChild(noCountryItem);

        // Helper function to check if dropdown is open
        const isDropdownOpen = () => countryDropdown.classList.contains("open");

        // Toggle dropdown
        selectedCountry.addEventListener("click", (e) => {
            e.stopPropagation();
            if (isDropdownOpen()) {
                countryDropdown.style.display = "none";
                countryDropdown.classList.remove("open");
                arrow.classList.remove("rotate-up");
            } else {
                countryDropdown.style.display = "block";
                countryDropdown.classList.add("open");
                arrow.classList.add("rotate-up");
            }
        });

        // Prevent closing when clicking inside dropdown
        countryDropdown.addEventListener("click", (e) => {
            e.stopPropagation();
        });

        // Search filter
        searchInput.addEventListener("input", function() {
            const query = this.value.toLowerCase();
            const items = list.querySelectorAll("li:not(.no-country)");
            let matchFound = false;

            items.forEach(li => {
                if (li.innerText.toLowerCase().includes(query)) {
                    li.style.display = "flex";
                    matchFound = true;
                } else {
                    li.style.display = "none";
                }
            });

            // Show "No country found" if no matches
            noCountryItem.style.display = matchFound ? "none" : "flex";

            // Clear selected country info if no match
            if (!matchFound) {
                selectedFlag.innerHTML = `<span>🌍</span>`;
                countryCodeInput.value = "";
                phonePrefix.textContent = "--";
                phoneInput.disabled = true;
                phoneInput.placeholder = "Select country code first";
            }
        });

        // Select country
        list.querySelectorAll("li:not(.no-country)").forEach(item => {
            item.addEventListener("click", () => {
                const code = item.dataset.code;
                const flag = item.querySelector("img").src;

                selectedFlag.innerHTML = `<img src="${flag}" width="20">`;
                countryCodeInput.value = code;
                phonePrefix.textContent = code;

                phoneInput.disabled = false;
                phoneInput.placeholder = `Enter phone number`;

                countryDropdown.style.display = "none";
                countryDropdown.classList.remove("open");
                arrow.classList.remove("rotate-up");
            });
        });

        // Close dropdown on outside click
        document.addEventListener("click", () => {
            countryDropdown.style.display = "none";
            countryDropdown.classList.remove("open");
            arrow.classList.remove("rotate-up");
        });






        // FOR STEP 2 -  COUNTRY OF REGISTRATIOON
        const countrySearchInput = document.getElementById('countrySearchReg');
        const optionsContainer = document.querySelector('#countryReg-options .custom-options-scrollable');
        const allOptions = Array.from(optionsContainer.querySelectorAll('.custom-option'));

        // Create a "no results" element
        const noResults = document.createElement('span');
        noResults.className = 'no-results';
        noResults.textContent = 'No results found';
        noResults.style.display = 'none';
        noResults.style.color = '#ffff';
        noResults.style.padding = '8px 12px';
        optionsContainer.appendChild(noResults);

        // Listen for input in the search box
        countrySearchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let hasVisible = false;

            allOptions.forEach(option => {
                const text = option.dataset.value.toLowerCase();
                if (text.includes(query)) {
                    option.style.display = 'block';
                    hasVisible = true;
                } else {
                    option.style.display = 'none';
                }
            });

            // Show "no results" message if nothing matches
            noResults.style.display = hasVisible ? 'none' : 'block';
        });



        /*************************************
         * INDUSTRY → CATEGORY HANDLER
         *************************************/
        const industryCategories = {
  technologyAndDigitalServices: [
    "Software and SaaS Solutions",
    "FinTech and Digital Payment Systems",
    "Cybersecurity and Data Protection Services",
    "Cloud Computing and IT Infrastructure",
    "Artificial Intelligence and Machine Learning",
    "Telecommunications and Network Services",
    "E-commerce and Digital Platforms",
    "Blockchain and Digital Asset Management",
  ],

  financialAndProfessionalServices: [
    "Banking and Financial Institutions",
    "Investment and Asset Management",
    "Insurance and Risk Advisory Services",
    "Legal and Regulatory Compliance Services",
    "Accounting and Tax Advisory Services",
    "Management Consulting",
    "Marketing and Advertising Agencies",
    "Human Resources and Talent Acquisition",
  ],

  manufacturingAndIndustrial: [
    "Industrial Machinery and Equipment",
    "Automotive and Aerospace Manufacturing",
    "Electronics and Technology Components",
    "Chemicals and Raw Materials Production",
    "Construction Materials and Supplies",
    "Packaging and Container Manufacturing",
    "Medical Devices and Equipment Production",
    "Textiles and Apparel Manufacturing",
  ],

  healthcareAndPharmaceuticals: [
    "Pharmaceuticals and Biotechnology",
    "Hospitals and Clinical Healthcare Facilities",
    "Medical Equipment and Supplies",
    "Healthcare Technology Systems",
    "Laboratory and Diagnostic Services",
    "Senior Care and Rehabilitation Services",
    "Telemedicine and Digital Health Solutions",
    "Clinical Research and Trials",
  ],

  energyAndUtilities: [
    "Oil, Gas, and Petroleum",
    "Renewable Energy (Solar, Wind, Hydroelectric)",
    "Power Generation and Distribution",
    "Utilities and Water Services Management",
    "Mining and Natural Resources Extraction",
    "Energy Storage and Management Systems",
    "Environmental and Sustainability Services",
  ],

  retailAndConsumerGoods: [
    "Wholesale and Distribution Networks",
    "Retail Chains and Franchising Operations",
    "Food and Beverage Production",
    "Consumer Electronics",
    "Fashion and Luxury Goods",
    "Home Goods and Appliances",
    "Sports and Leisure Products",
  ],

  logisticsAndTransportation: [
    "Shipping and Freight Forwarding Services",
    "Air Cargo and Logistics",
    "Trucking and Ground Transportation",
    "Warehousing and Storage Facilities",
    "Supply Chain Management",
    "Fleet and Vehicle Management",
    "Ports and Terminal Operations",
  ],

  agricultureAndFoodProcessing: [
    "Farming and Agricultural Production",
    "Food Processing and Manufacturing",
    "Beverage Production",
    "Agricultural Technology (AgriTech)",
    "Livestock and Animal Products",
    "Commodity Trading and Export",
    "Agricultural Equipment and Supplies",
  ],

  realEstateAndConstruction: [
    "Commercial Real Estate Development",
    "Property Management Services",
    "Construction and Engineering Services",
    "Architecture and Design Services",
    "Facility Management",
    "Real Estate Investment",
    "Building Materials and Supplies",
  ],

  mediaAndCommunications: [
    "Broadcasting and Entertainment",
    "Digital Media and Publishing",
    "Advertising and Public Relations",
    "Telecommunications Services",
    "Content Creation and Production",
    "Event Management and Production",
  ],

  educationAndTraining: [
    "Corporate Training and Development",
    "Educational Technology (EdTech)",
    "Schools and Educational Institutions",
    "Professional Certification Programs",
    "Language and Skills Training",
    "E-learning and Online Education",
  ],

  governmentAndPublicServices: [
    "Government and Public Administration",
    "Defense and Security Services",
    "Infrastructure Development",
    "Municipal and Public Utilities",
    "Public Health Services",
    "Government Technology Solutions",
  ],

  environmentalAndSpecializedServices: [
    "Waste Management and Recycling",
    "Water Treatment and Management",
    "Environmental Consulting",
    "Engineering and Technical Services",
    "Research and Development",
    "Testing and Quality Assurance",
  ],
};


        const defaultTags = [
           "Software and SaaS Solutions",
             "Research and Development",
           "Oil, Gas, and Petroleum",
             "Public Health Services",
            //    "Consumer Electronics",
        ];

        let selectedCategories = {}; // store selections by industry

        const categoryContainer = document.getElementById("categoryContainer");
        const industrySelect = document.querySelector('select[name="industry"]');
        const hiddenCategoryInput = document.getElementById("selectedCategories");

        // Initial render (default tags)
        renderTags(defaultTags, true);

        /** Update when user selects a new industry **/
        // function updateCategories(industry) {
        //     categoryContainer.innerHTML = "";
        //     hiddenCategoryInput.value = "";

        //     if (!industry || !industryCategories[industry]) {
        //         renderTags(defaultTags, true);
        //         return;
        //     }

        //     const categories = industryCategories[industry];
        //     const saved = selectedCategories[industry] || [];

        //     renderTags(categories, false, saved);
        // }

        function updateCategories(industry) {
    categoryContainer.innerHTML = "";
    hiddenCategoryInput.value = "";

    // Remove padding fix by default
    categoryContainer.classList.remove("industry-selected");

    if (!industry || !industryCategories[industry]) {
        renderTags(defaultTags, true);
        return;
    }

    // Apply padding fix once industry is selected
    categoryContainer.classList.add("industry-selected");

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

        if (isDefault) {
            tag.classList.add("is-default");
        }

        if (saved.includes(category)) {
            tag.classList.add("active");
        }

        tag.innerHTML = `
            <span class="check-icon ${saved.includes(category) ? "active" : ""}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m4.5 12.75 6 6 9-13.5"/>
                </svg>
            </span>
            <span>${category}</span>
        `;

        // Prevent clicks on default tags
        if (!isDefault) {
            tag.addEventListener("click", () => {
                const currentIndustry = industrySelect.value;

                tag.classList.toggle("active");
                tag.querySelector(".check-icon").classList.toggle("active");

                if (!selectedCategories[currentIndustry]) {
                    selectedCategories[currentIndustry] = [];
                }

                if (tag.classList.contains("active")) {
                    selectedCategories[currentIndustry].push(category);
                } else {
                    selectedCategories[currentIndustry] =
                        selectedCategories[currentIndustry].filter(
                            (c) => c !== category
                        );
                }

                updateHiddenInput();
                saveFormData();
            });
        }

        categoryContainer.appendChild(tag);
    });
}

    //     function renderTags(categories, isDefault = false, saved = []) {
    //         categoryContainer.innerHTML = "";

    //         categories.forEach((category) => {
    //             const tag = document.createElement("div");
    //             tag.className = "interest-form-tag";
    //             if (saved.includes(category)) tag.classList.add("active");

    //             tag.innerHTML = `
    //   <span class="check-icon ${saved.includes(category) ? "active" : ""}">
    //     <svg xmlns="http://www.w3.org/2000/svg" fill="none"
    //       viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
    //       <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
    //     </svg>
    //   </span>
    //   <span>${category}</span>
    // `;



    //             // Add click event
    //             tag.addEventListener("click", () => {
    //                 const currentIndustry = industrySelect.value;

    //                 // Toggle visual state
    //                 tag.classList.toggle("active");
    //                 const icon = tag.querySelector(".check-icon");
    //                 icon.classList.toggle("active");

    //                 // Handle storage
    //                 if (!isDefault && currentIndustry) {
    //                     if (!selectedCategories[currentIndustry]) {
    //                         selectedCategories[currentIndustry] = [];
    //                     }

    //                     if (tag.classList.contains("active")) {
    //                         selectedCategories[currentIndustry].push(category);
    //                     } else {
    //                         selectedCategories[currentIndustry] = selectedCategories[
    //                             currentIndustry
    //                         ].filter((c) => c !== category);
    //                     }
    //                 }

    //                 updateHiddenInput();
    //                 saveFormData(); // Save after category selection
    //             });

    //             categoryContainer.appendChild(tag);
    //         });
    //     }

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

        // Backend validation endpoint URLs
        const validateStepUrl = '{{ route('brn.validate-step', ['step' => '__STEP__']) }}';
        const validateAllUrl = '{{ route('brn.validate-all') }}';

        // Handle navigation with backend validation
        proceedButtons.forEach((btn) => {
            btn.addEventListener("click", async (e) => {
                e.preventDefault();

                // Disable the button to prevent double-clicks
                btn.disabled = true;
                const originalText = btn.textContent;
                btn.textContent = 'Validating...';

                try {
                    // First run client-side validation for immediate feedback
                    if (!validateCurrentStep()) {
                        btn.disabled = false;
                        btn.textContent = originalText;
                        return;
                    }

                    // Then run backend validation for the current step
                    const backendValid = await validateStepWithBackend(currentStep + 1);

                    if (backendValid) {
                        if (currentStep < totalSteps - 1) {
                            currentStep++;
                            showStep(currentStep);
                        }
                    }
                } catch (error) {
                    console.warn(
                        'Backend validation error, proceeding with client validation:',
                        error);
                    // If backend validation fails due to network, allow proceeding
                    // The final submission will catch any issues
                    if (currentStep < totalSteps - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                } finally {
                    btn.disabled = false;
                    btn.textContent = originalText;
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

        // Backend validation function for a specific step
        async function validateStepWithBackend(stepNumber) {
            try {
                const formData = new FormData(form);
                const csrfToken = getCsrfToken();

                const url = validateStepUrl.replace('__STEP__', stepNumber);

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData,
                    credentials: 'same-origin'
                });

                // Handle CSRF token expiration
                if (response.status === 419) {
                    const newToken = await refreshCsrfToken();
                    if (newToken) {
                        updateCsrfToken(newToken);
                        // Retry once with new token
                        const retryResponse = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': newToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new FormData(form),
                            credentials: 'same-origin'
                        });
                        return await handleValidationResponse(retryResponse, stepNumber);
                    }
                }

                return await handleValidationResponse(response, stepNumber);

            } catch (error) {
                console.warn('Backend validation network error:', error);
                // Return true to allow proceeding - final submission will validate again
                return true;
            }
        }

        // Handle validation response
        async function handleValidationResponse(response, stepNumber) {
            const data = await response.json();

            if (response.ok && data.success) {
                // Update form fields with sanitized data if provided
                if (data.data) {
                    Object.keys(data.data).forEach(field => {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input && data.data[field] !== undefined && data.data[field] !== null) {
                            input.value = data.data[field];
                        }
                    });
                }
                return true;
            }

            if (data.errors) {
                // Show backend validation errors on the current step
                showBackendErrors(data.errors, stepNumber);
            }

            return false;
        }

        // Show backend validation errors on the form
        function showBackendErrors(errors, stepNumber) {
            const currentStage = formStages[stepNumber - 1];

            // Clear previous errors in the current stage
            currentStage.querySelectorAll('.error-message').forEach(msg => msg.remove());
            currentStage.querySelectorAll('input, select, textarea').forEach(input => {
                clearError(input);
            });

            // Show new errors
            Object.keys(errors).forEach(field => {
                const input = currentStage.querySelector(`[name="${field}"]`);
                if (input) {
                    const errorMessage = Array.isArray(errors[field]) ? errors[field][0] : errors[
                        field];
                    showError(input, errorMessage);
                } else {
                    // Try to find in the form (might be a custom select)
                    const formInput = form.querySelector(`[name="${field}"]`);
                    if (formInput) {
                        const errorMessage = Array.isArray(errors[field]) ? errors[field][0] : errors[
                            field];
                        showError(formInput, errorMessage);
                    }
                }
            });

            // Scroll to first error
            const firstError = currentStage.querySelector('.error-message');
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        // Validation function for current step (client-side)
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
                    const phoneContainer = currentStage.querySelector('.phone-input-container');

                    if (phoneContainer) {
                        // highlight border of phone input container
                        phoneContainer.style.border = "1px solid #ef4444";
                        phoneContainer.style.borderRadius = "30px";

                        // create error message
                        const error = document.createElement("div");
                        error.className = "error-message";
                        error.style.color = "#ef4444";
                        error.style.fontSize = "0.875rem";
                        error.style.marginTop = "6px";
                        error.textContent = "Phone number is required";

                        // append error message after the phone container
                        phoneContainer.parentNode.insertBefore(error, phoneContainer.nextSibling);
                    }

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

                const noLinkedIn = currentStage.querySelector('input[name="no_linkedin"]')?.checked || false;

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

                // if (!linkedin.value.trim()) {
                //     // showError(linkedin, 'LinkedIn profile is required');
                //     isValid = true;
                // } else if (!linkedin.value.toLowerCase().includes('linkedin')) {
                //     showError(linkedin, 'Please enter a valid LinkedIn profile URL');
                //     isValid = false;
                // }

                if (!noLinkedIn) {
                    // only validate LinkedIn if user has NOT checked "I don't have LinkedIn"
                    if (!linkedin.value.trim()) {
                        // linkedin is optional originally; 
                        // showError(linkedin, 'LinkedIn profile is required');
                        // isValid = false;

                    } else if (!linkedin.value.toLowerCase().includes('linkedin')) {
                        showError(linkedin, 'Please enter a valid LinkedIn profile URL');
                        isValid = false;
                    }
                } else {
                    // user opted-out of LinkedIn — ensure no lingering error state
                    clearError(linkedin);
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
                    showError(customSelect || industry.parentNode, 'Please select your industry, to get updated categories');
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


        // --- Toggle blur/disable based on checkbox ---
        (function() {
            const linkedinInput = document.querySelector('input[name="linkedin"]');
            const noLinkedInCheckbox = document.querySelector('input[name="no_linkedin"]');

            if (!linkedinInput || !noLinkedInCheckbox) return;

            function updateLinkedInState() {
                const checked = noLinkedInCheckbox.checked;

                if (checked) {
                    // visually blur + disable
                    linkedinInput.classList.add('blurred');
                    linkedinInput.disabled = true;
                    linkedinInput.setAttribute('aria-disabled', 'true');
                    // optional: clear the value to avoid accidentally submitting a bad URL
                    // linkedinInput.value = '';
                } else {
                    linkedinInput.classList.remove('blurred');
                    linkedinInput.disabled = false;
                    linkedinInput.removeAttribute('aria-disabled');
                }
            }

            // init
            updateLinkedInState();

            // listen for changes
            noLinkedInCheckbox.addEventListener('change', updateLinkedInState);
        })();


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

        // ========== ROBUST FORM SUBMISSION WITH AUTO-RETRY ==========

        // Function to refresh CSRF token
        async function refreshCsrfToken() {
            try {
                // Primary method: Use dedicated CSRF refresh endpoint
                const response = await fetch('{{ route('csrf.refresh') }}', {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.token) {
                        console.log('CSRF token refreshed successfully');
                        return data.token;
                    }
                }

                // Fallback: Try Sanctum cookie endpoint (if using Laravel Sanctum)
                try {
                    const sanctumResponse = await fetch('/sanctum/csrf-cookie', {
                        method: 'GET',
                        credentials: 'same-origin'
                    });

                    if (sanctumResponse.ok) {
                        const cookies = document.cookie.split(';');
                        for (let cookie of cookies) {
                            const [name, value] = cookie.trim().split('=');
                            if (name === 'XSRF-TOKEN') {
                                return decodeURIComponent(value);
                            }
                        }
                    }
                } catch (e) {
                    // Sanctum not available, continue to next fallback
                }

                // Last resort: fetch the form page to get new token from HTML
                const pageResponse = await fetch(window.location.href, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'text/html'
                    }
                });

                if (pageResponse.ok) {
                    const html = await pageResponse.text();
                    const tokenMatch = html.match(/name="_token"[^>]*value="([^"]+)"/);
                    if (tokenMatch && tokenMatch[1]) {
                        return tokenMatch[1];
                    }
                }
            } catch (e) {
                console.warn('Could not refresh CSRF token:', e);
            }
            return null;
        }

        // Get current CSRF token
        function getCsrfToken() {
            // 1. First priority: Form's hidden input
            const formTokenInput = form.querySelector('input[name="_token"]');
            if (formTokenInput && formTokenInput.value) {
                return formTokenInput.value;
            }

            // 2. Second priority: Meta tag
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            if (metaToken && metaToken.content) {
                return metaToken.content;
            }

            // 3. Third priority: Laravel's global object
            if (typeof window.Laravel !== 'undefined' && window.Laravel.csrfToken) {
                return window.Laravel.csrfToken;
            }

            return null;
        }

        // Update CSRF token in form
        function updateCsrfToken(newToken) {
            if (!newToken) return;

            const formTokenInput = form.querySelector('input[name="_token"]');
            if (formTokenInput) {
                formTokenInput.value = newToken;
            }

            const metaToken = document.querySelector('meta[name="csrf-token"]');
            if (metaToken) {
                metaToken.content = newToken;
            }
        }

        // Submit form with retry logic
        async function submitFormWithRetry(formData, maxRetries = 3) {
            let lastError = null;

            for (let attempt = 1; attempt <= maxRetries; attempt++) {
                try {
                    let csrfToken = getCsrfToken();

                    // If this is a retry after 419 error, refresh the token first
                    if (attempt > 1 || !csrfToken) {
                        const newToken = await refreshCsrfToken();
                        if (newToken) {
                            csrfToken = newToken;
                            updateCsrfToken(newToken);
                            // Update the FormData with new token
                            formData.set('_token', newToken);
                        }
                    }

                    if (!csrfToken) {
                        throw {
                            status: 'NO_TOKEN',
                            message: 'Security token missing. Please refresh the page.'
                        };
                    }

                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData,
                        credentials: 'same-origin'
                    });

                    // Handle 419 (CSRF token mismatch) - retry with fresh token
                    if (response.status === 419) {
                        console.log(`Attempt ${attempt}: Session expired, refreshing token...`);
                        lastError = {
                            status: 419,
                            message: 'Session expired'
                        };

                        if (attempt < maxRetries) {
                            // Wait a bit before retrying
                            await new Promise(resolve => setTimeout(resolve, 500 * attempt));
                            continue;
                        }
                    }

                    // Handle other errors
                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw {
                            status: response.status,
                            data: errorData,
                            message: errorData.message || 'Server error'
                        };
                    }

                    // Success!
                    return await response.json();

                } catch (error) {
                    lastError = error;

                    // Network errors - retry
                    if (error.name === 'TypeError' && error.message.includes('fetch')) {
                        console.log(`Attempt ${attempt}: Network error, retrying...`);
                        if (attempt < maxRetries) {
                            await new Promise(resolve => setTimeout(resolve, 1000 * attempt));
                            continue;
                        }
                    }

                    // Don't retry validation errors (422)
                    if (error.status === 422) {
                        throw error;
                    }

                    // Don't retry rate limiting (429)
                    if (error.status === 429) {
                        throw error;
                    }

                    // For other errors, retry if we have attempts left
                    if (attempt >= maxRetries) {
                        throw error;
                    }
                }
            }

            // All retries exhausted
            throw lastError || {
                status: 'UNKNOWN',
                message: 'Submission failed after multiple attempts'
            };
        }

        // Form submission handling
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Run client-side validation on all steps first
            let allValid = true;
            let firstInvalidStep = -1;

            for (let step = 0; step < totalSteps; step++) {
                const prevStep = currentStep;
                currentStep = step;
                if (!validateCurrentStep()) {
                    allValid = false;
                    if (firstInvalidStep === -1) {
                        firstInvalidStep = step;
                    }
                }
                currentStep = prevStep;
            }

            if (!allValid) {
                // Navigate to the first step with errors
                currentStep = firstInvalidStep;
                showStep(currentStep);
                return;
            }

            // Disable submit button and show spinner
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            try {
                const formData = new FormData(form);
                const data = await submitFormWithRetry(formData);

                // Success!
                responseMessage.innerHTML = `
    <p>You’ve just taken a big step toward meaningful business growth!</p>
    <h4>Check your email for next steps and more details from NOEL.</h4>
`;
                // responseMessage.style.color = '#059669';
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
                    text.classList.remove('selected');
                });
                document.querySelectorAll('.custom-option').forEach(option => option.classList
                    .remove('active'));

                // Clear categories
                categoryContainer.innerHTML = '';
                renderTags(defaultTags, true);
                Object.keys(selectedCategories).forEach(key => delete selectedCategories[key]);
                hiddenCategoryInput.value = '';

            } catch (error) {
                let errorMessage = 'Something went wrong. Please try again.';
                let showRefreshButton = false;
                let navigateToStep = null;

                if (error.status === 419 || error.status === 'NO_TOKEN') {
                    errorMessage =
                        'Your session has expired. Click the button below to refresh and try again.';
                    showRefreshButton = true;
                } else if (error.status === 422) {
                    if (error.data?.errors) {
                        // Handle step-based errors from backend
                        if (error.data?.first_error_step) {
                            navigateToStep = error.data.first_error_step -
                                1; // Convert to 0-based index
                            currentStep = navigateToStep;
                            showStep(currentStep);
                        }

                        // Show errors for the specific step
                        if (error.data?.errors_by_step && navigateToStep !== null) {
                            const stepErrors = error.data.errors_by_step[navigateToStep + 1];
                            if (stepErrors) {
                                showBackendErrors(stepErrors, navigateToStep + 1);
                            }
                        } else {
                            showServerErrors(error.data.errors);
                        }

                        errorMessage = error.data?.message ||
                            'Please correct the errors and try again.';
                    } else if (error.data?.message) {
                        errorMessage = error.data.message;
                    }
                } else if (error.status === 403) {
                    errorMessage = 'Access denied. Please refresh the page and try again.';
                    showRefreshButton = true;
                } else if (error.status === 429) {
                    errorMessage = 'Too many attempts. Please wait a few minutes and try again.';
                } else if (error.name === 'TypeError' || error.message?.includes('network')) {
                    errorMessage =
                        'Network error. Please check your internet connection and try again.';
                } else if (error.message) {
                    errorMessage = error.message;
                }

                // For validation errors, don't show modal - user can see errors on the form
                if (navigateToStep !== null) {
                    submitBtn.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.classList.remove('d-none');
                    return;
                }

                // Build response message with optional refresh button
                if (showRefreshButton) {
                    responseMessage.innerHTML = `
                        <span style="display: block; margin-bottom: 15px;">${errorMessage}</span>
                        <button onclick="location.reload()" 
                                style="padding: 10px 25px; background: #137077; color: white; border: none; 
                                       border-radius: 20px; cursor: pointer; font-weight: 500;">
                            Refresh Page
                        </button>
                    `;
                } else {
                    responseMessage.textContent = errorMessage;
                }
                responseMessage.style.color = '#193E47';
                modal.show();

            } finally {
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
                btnText.classList.remove('d-none');
            }
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



        // Scroll Back To Top FUnctionality

        (function() {

            const BUTTON_SELECTOR = ".interest-form-proceed-button, .interest-form-return-button";
            const SCROLL_OFFSET = 0; // adjust to fit your design

            // Handler: delegated click listener
            document.addEventListener("click", function(e) {

                const btn = e.target.closest && e.target.closest(BUTTON_SELECTOR);
                if (!btn) return;

                // If it's a submit (accidentally matched) or disabled, do nothing
                if (btn.matches("[type='submit'], .interest-form-submit-button") || btn.disabled)
                    return;

                requestAnimationFrame(() => {

                    try {
                        window.scrollTo({
                            top: SCROLL_OFFSET,
                            behavior: "smooth"
                        });
                    } catch (err) {
                        // Fallback for older browsers
                        window.scrollTo(0, 0);
                    }
                });
            }, false);
        })();



    });
</script>