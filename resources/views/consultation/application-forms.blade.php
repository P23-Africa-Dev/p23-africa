@extends('layouts.consultations')
@section('title', 'Book a Consultation | P23 Africa')

@section('meta')
    <meta name="description"
        content="Book a personalized business consultation with P23 Africa. Get expert guidance on funding readiness, market positioning, strategy development, and investor engagement tailored to African entrepreneurs and growing businesses.">
    <meta name="keywords"
        content="book consultation, business consultation, P23 Africa, African entrepreneurs, business strategy, funding readiness, investor preparation, growth consulting, startup advisory, business development Africa">
    <meta name="author" content="P23 Africa">
    <meta name="generator" content="Book Consultation | P23 Africa">

    <meta property="og:title" content="Book a Consultation | P23 Africa">
    <meta property="og:description"
        content="Schedule a one-on-one consultation to strengthen your business strategy, improve funding readiness, and unlock growth opportunities with expert advisors at P23 Africa.">
    <meta property="og:image" content="{{ asset('img/consultation-form.png') }}">
    <meta property="og:type" content="website">

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>  -->

<style>
    .multistep-form-container {
        background-image: url(../img/bg-effect.png);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        min-height: 100vh;
        background-color: #ffffffe4;
        background-blend-mode: soft-light;
        padding: 0px 14px;
        padding-bottom: 40px;
    }

    .multi-step-wrapper {
        /* width: 90%; */
        max-width: 1100px;
        margin: 0px auto;
        margin-top: 50px;
        padding: 20px 25px 37px 25px;
        border-radius: 25px;
        overflow: hidden;

        box-shadow: 0px 9px 15px -8px rgba(0, 0, 0, 0.8);

        padding-top: 70px;
        background-image: url(../img/form-inner-bg.png);
        /* background-color: #ffffffa9; */
        /* background-blend-mode: soft-light; */
        background-repeat: no-repeat;
        /* background-size:cover; */
        background-position: center;
    }

    /* ------------------ HEADER ------------------ */

    .step-header {
        background: #0a3d43;
        padding: 30px 0;
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: space-between;
        border-radius: 35px;
        position: relative;
    }

    .step-counter-container {
        position: absolute;
        top: -60px;
        left: 50%;
        transform: translateX(-50%);

        border: 7px solid #1e927b;
        width: 110px;
        height: 110px;
        background-color: #fff;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .step-counter {
        position: absolute;
        top: 0;
        left: 0;
    }

    .counter-circle {
        width: 90px;
        height: 90px;
        left: 4px;
        top: 4px;
        border-radius: 50%;
        background: #fff;

        display: flex;
        align-items: center;
        justify-content: center;

        position: relative;
    }

    .counter-circle::before {
        content: "";
        position: absolute;
        inset: 0;
        /* top: -4px; */
        border-radius: 50%;

        padding: 7px;

        background: linear-gradient(84.75deg,
                #e1e6e5 -0.34%,
                #e7e4b4 51.09%,
                #fcdd0a 102.52%);

        -webkit-mask: radial-gradient(farthest-side,
                #0000 calc(100% - 7px),
                #000 calc(100% - 7px));
        mask: radial-gradient(farthest-side,
                #0000 calc(100% - 7px),
                #000 calc(100% - 7px));
    }

    .counter-circle span {
        font-size: 50px;
        font-weight: 700;
        color: #000000;
        font-family: "GT Walsheim ProCB";
        line-height: 1.2;
    }

    .step-progress-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
        margin-top: 20px;
        width: 100%;
    }

    .step-titles .step-title {
        max-width: 340px;
        margin: 0 auto;
    }

    .step-title h2 {
        color: #ffffff;
        font-size: 22px;
        margin: 0;
        font-family: "Montserrat", sans-serif;
        font-weight: 900;
        line-height: 1;
        padding: 0px 50px;
        padding-top: 24px;
        max-width: 300px;
        margin: 0 auto;

        /* max-width: 260px; */

        text-align: center;
        text-transform: uppercase;
    }

    .step-title p.desktop-p {
        display: none;
    }

    .step-title p {
        margin-top: 6px;
        color: #fff;
        letter-spacing: 0.8px;
        font-size: 14px;
        line-height: 1;
        font-family: "GT Walsheim Con";
        display: flex;
        flex-direction: column;
        padding: 0px 20px;
        letter-spacing: 0.9px;
    }

    /* Progress Bar */

    .progress-area {
        padding-bottom: 25px;
        padding-top: 23px;
    }

    .progress-area .box-area {
        display: flex;
        flex-direction: column;
    }

    .step-divider {
        background-color: #196770;
        height: 1.1px;
        width: 72%;
    }

    .progress-text {
        color: #ffffff;
        font-size: 13px;
        font-family: "GT Walsheim Con";
        letter-spacing: 0.7px;
        text-align: center;
    }

    .progress-baar {
        margin-top: 6px;
        width: 160px;
        height: 10px;
        background: #fff;
        opacity: 0.8;
        border-radius: 50px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        width: 25%;
        background: #fcdd0a;
        border-radius: inherit;
    }

    /* ------------------ FORM BODY ------------------ */

    .form-step,
    .step-title {
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.4s ease, transform 0.4s ease;
        display: none;
    }

    .form-step.active,
    .step-title.active {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .fade-in {
        animation: fadeInStep 0.4s ease forwards;
    }

    @keyframes fadeInStep {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Grid layout (Desktop) */

    .form-grid,
    .goals-grid {
        padding-top: 33px;
    }

    .form-step {
        padding-bottom: 30px;
    }

    .form-grid,
    .form-grid .grid-input {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 14px;
    }

    .form-grid input,
    .form-grid textarea {
        padding: 18px 40px;
        font-size: 14px;
        border-radius: 40px;
        border: 1px solid #ddd;
        outline: none;
        /* background: #fff; */
        font-weight: bold;
        resize: none;
        color: #0d4036;
        box-shadow: 0px 14px 13px -3px rgba(0, 0, 0, 0.17);
        font-family: "GT Walsheim Con";
        letter-spacing: 0.8px;
    }



    .form-grid input::placeholder,
    .form-grid textarea::placeholder {
        font-size: 13px;
        letter-spacing: 0.6px;
        font-weight: 300;
        opacity: 0.86;
    }

    .form-grid textarea::placeholder {
        padding-right: 60px;
    }

    textarea {
        height: 120px;
        resize: none;
        border-radius: 25px !important;
    }

    /* .form-grid textarea {
   padding-right: 100px;
} */

    /* ============================ STEP 4 */
    .outcome-grid {
        display: grid;
        gap: 22px;
    }

    .outcome-grid label {
        font-family: "GT Walsheim Trial Cn Md";

        font-weight: 800;
        letter-spacing: 0.8px;
        color: #0d4036;
        font-size: 14px;
        margin-left: 22px;
    }

    .outcome-grid .outcome-input textarea {
        height: 100px;
    }

    /* ===================== CUSTOM INDUSTRY */

    .custom-select-wrapper {
        position: relative;
        width: 100%;
    }

    .custom-select {
        /* width: 80%; */
        padding: 18px 20px;
        background: #fff;
        border-radius: 40px;
        border: 1px solid #ddd;
        cursor: pointer;
        padding-left: 40px;
        color: gray;
        box-shadow: 3px 6px 10px rgba(0, 0, 0, 0.1);
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0px 14px 13px -3px rgba(0, 0, 0, 0.3);
    }

    .custom-select img {
        width: auto;
        height: 10px;
    }

    .custom-select .selected-option {
        font-family: "GT Walsheim Con";

    }

    .custom-select .dropdown-arrow {
        width: 18px;
        pointer-events: none;
        transition: transform 0.25s;
    }

    .custom-select.open .dropdown-arrow {
        transform: rotate(180deg);
    }

    /* Options Box */

    .custom-options {
        position: absolute;
        top: 58px;
        width: 95%;
        left: 10px;
        background: #fff;
        border-radius: 18px;
        border: 1px solid #ccc;
        list-style: none;
        padding: 10px 0;
        margin: 0;
        max-height: 200px;
        overflow-y: auto;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        display: none;
        z-index: 99;
        font-family: "GT Walsheim Con";

        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .custom-options::-webkit-scrollbar {
        display: none;
    }

    .custom-options.show {
        display: block;
    }

    .custom-options li {
        padding: 12px 20px;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s;
        font-family: "GT Walsheim Con";
    }

    .custom-options li:hover {
        background: rgba(30, 146, 123, 0.1);
    }

    /* ============================STAGE 2========================  */

    /* === GOALS GRID === */
    .goals-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
        margin: 0 auto;
        /* border: 1px solid red; */
        /* align-items: start; */
    }

    /* === GOAL CARD === */
    .goal-card {
        position: relative;
        background: #fff;
        color: #0d4036;
        height: 80px;
        text-align: left;


        border-radius: 14px;
        border: 1px solid #e2e2e2;
        box-shadow: 0px 10px 7px -3px rgba(0, 0, 0, 0.17);

        padding: 15px 0px;
        width: 35vw;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;

        font-size: 0.5rem;
    }

    .goal-card input {
        display: none;
    }

    .goal-text {
        font-size: 0.8rem;
        line-height: 1.1;
        pointer-events: none;
        /* font-family: "GT Walsheim Con"; */
        font-family: "GT Walsheim Trial Cn Md";

        font-weight: 800;

        padding: 0px 11.3px;
        letter-spacing: 0.7px;
        max-width: 115px;
    }

    .goal-text.prepare-text {
        padding: 0px 12px;
    }

    .goal-text.increase-text {
        padding: 0px 8px;
    }

    .goal-text.build-text {
        padding: 0px 9px;
    }

    .goal-text.launch-text {
        padding: 0px 30px;
    }

    /* === CHECKMARK CIRCLE === */
    .goal-checkmark {
        position: absolute;
        top: -15px;
        right: -8px;
        width: 28px;
        height: 28px;
        background-color: #1ed2aa;
        border-radius: 50%;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.3s ease;
    }

    .goal-checkmark img {
        width: 14px;
        height: 14px;
        object-fit: contain;
        border-radius: 50%;
        filter: brightness(0) invert(1);
    }

    /* === SELECTED STATE === */
    .goal-card.selected {
        box-shadow: 0px 9px 13px -3px rgb(30, 210, 170, 0.3);

        transform: scale(1.01);
        color: #1ed2aa;
    }

    /* Show checkmark when selected */
    .goal-card.selected .goal-checkmark {
        opacity: 1;
        transform: scale(1);
    }

    /* === BLURRED CARDS === */
    .goal-card.blurred {
        filter: blur(1px);
        opacity: 0.6;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    /* ============================STAGE 3========================  */

    /* Buttons */
    .step-buttons {
        margin-top: 60px;
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .form-btn {
        padding: 13px 40px;
        border-radius: 20px;
        border: none;
        font-size: 20px;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .step-buttons a {

        text-decoration: none;
    }

    .back-btn {
        background: transparent;
        border: 1px solid #b0d3cc;
        color: #0d4036;
    }

    .back-btn i {
        color: #0d4036;
    }

    .back-btn:disabled {
        opacity: 0.8;
        cursor: default;
    }

    .next-btn {
        background: #591044;
        color: #fff;
        box-shadow: 0px 4px 15px -3px rgba(0, 0, 0, 0.6);
    }

    .form-btn i {
        font-size: 17px;
    }

    .submit-btn {
        background: #591044;
        color: #fff;
    }

    /* --------------- ERROR MESSAGES --------------- */
    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
        display: block;
        font-family: "GT Walsheim Con";
        letter-spacing: 0.5px;
        padding-left: 20px;
    }

    .form-grid input.error,
    .form-grid textarea.error {
        border-color: #dc3545;
        box-shadow: 0px 14px 13px -3px rgba(220, 53, 69, 0.2);
    }

    .step-error {
        background: #dc3545;
        color: white;
        padding: 10px 20px;
        border-radius: 20px;
        margin: 10px 0;
        text-align: center;
        font-family: "GT Walsheim Con";
        font-size: 12px;
        letter-spacing: 0.5px;
        /* position: absolute;
        top: 40px; */
        /* display: none; */
    }

    .goal-card.error {
        border-color: #dc3545;
        box-shadow: 0px 10px 7px -3px rgba(220, 53, 69, 0.3);
    }

    /* --------------- PHONE INPUT WITH COUNTRY CODE --------------- */
    .phone-field {
        display: flex;
        align-items: center;
        gap: 12px;
        font-family: sans-serif;
    }

    /* FLAG BOX */
    .flag-box {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 16px 18px;
        border-radius: 50px;
        border: 1px solid #ddd;
        cursor: pointer;
        /* background: #fff; */
        position: relative;
        box-shadow: 0px 14px 13px -3px rgba(0, 0, 0, 0.17);
    }

    .flag-box img.flagImagee {
        width: 25px;
        height: 22px;
        /* border-radius: 3px; */
        object-fit: cover;
    }

    .dropdown-icon {
        width: 10px;
        transition: transform .3s ease;
    }

    .dropdown-icon.rotate {
        transform: rotate(180deg);
    }

    /* DROPDOWN */
    .flag-dropdown {
        position: absolute;
        top: 48px;
        left: 0;
        width: 200px;
        display: none;
        border-radius: 14px;
        border: 1px solid #eee;
        background: #0a3d43;
        box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
        max-height: 250px;
        overflow-y: auto;
        z-index: 5;
        overflow-x: hidden;

    }

    /* SEARCH INPUT */
    .flag-search-container {
        padding: 8px 12px;
        border-bottom: 1px solid #196770;
        background: #0a3d43;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    #flagSearch {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #196770;
        border-radius: 8px;
        background: #fff;
        color: #0a3d43;
        font-size: 12px;
        font-family: "GT Walsheim Con";
        outline: none;
    }

    #flagSearch::placeholder {
        color: #898D8D;
        opacity: 0.8;
    }

    #flagSearch:focus {
        border-color: #1ed2aa;
        box-shadow: 0 0 5px rgba(30, 210, 170, 0.3);
    }

    .flag-dropdown::-webkit-scrollbar {
        width: 6px;
    }

    .flag-dropdown::-webkit-scrollbar-thumb {
        background: #196770;
        border-radius: 8px;
    }

    .flag-item {
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        color: #fff;
        font-family: "GT Walsheim Con";

        font-size: 13px;
        /* transition: background 0.25s ease, transform 0.2s ease; */

    }


    .flag-item img {
        width: auto;
        height: 12px;
        transition: transform 0.25s ease;
    }

    /* HOVER EFFECT */
    .flag-item:hover {
        background: #196770;
    }

    .flag-item:hover img {
        transform: translateX(2px);
        /* enlarge flag slightly */
    }

    /* HIDDEN FLAG ITEMS DURING SEARCH */
    .flag-item.hidden {
        display: none;
    }

    /* NO RESULTS MESSAGE */
    .no-results {
        padding: 12px;
        text-align: center;
        color: #898D8D;
        font-size: 12px;
        font-family: "GT Walsheim Con";
        font-style: italic;
    }



    /* NUMBER FIELD */
    .number-wrapper {
        display: flex;
        align-items: center;
        gap: 0px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 40px;
        width: 100%;
        box-shadow: 0px 14px 13px -3px rgba(0, 0, 0, 0.17);
    }

    .number-wrapper input {
        padding: 14px 10px !important;
    }

    #countryPrefix {
        padding: 0px 12px 0px 18px;
        margin-right: 4px;
        font-family: "GT Walsheim Con";
        font-size: 13px;
        letter-spacing: 0.6px;
        opacity: 0.86;
        border-right: 1px solid #898d8d7b;
        color: #898D8D;

    }

    .form-grid input#phoneInput {
        border: none;
        outline: none;
        font-size: 15px;
        flex: 1;
        box-shadow: none;
        border-radius: 0px;
        border-top-right-radius: 40px;
        border-bottom-right-radius: 40px;
        padding: 16px 40px 16px 12px;

    }


    .country-code-select.error,
    .phone-number-input.error {
        border-color: #dc3545;
        box-shadow: 0px 14px 13px -3px rgba(220, 53, 69, 0.2);
    }

    .flag-box.error {
        border-color: #dc3545;
        box-shadow: 0px 14px 13px -3px rgba(220, 53, 69, 0.2);
    }

    /* Hidden phone field for form submission */
    .phone-hidden-field {
        display: none;
    }

    .phone-rounded {
        border-radius: 40px !important;
        /* padding-left: 40px; */

    }

    /* .grid-input .phone-rounded::placeholder{
        margin-left: 20px !important;
    } */


    /* --------------- RESPONSIVE: MOBILE COLUMN --------------- */

    /* Medium screens (small laptops) and up */
    @media (min-width: 768px) {
        /* .goals-grid {
    gap: 20px;
  } */

        .goal-card {
            width: 40vw;

            padding: 22px 12px;
        }

        .goal-text {
            font-size: 1rem;
            max-width: 150px;
            margin: 0 auto;
        }
    }

    @media (min-width: 1024px) {
        .multi-step-wrapper {
            padding: 0px;
            overflow: visible;
            /* margin-top: 70px; */
        }

        .step-header {
            border-radius: 0px;
        }

        .step-counter-container {
            position: absolute;
            top: -60px;
            left: 10%;
            transform: translateX(-50%);
            /* border: 1px solid red; */
        }

        .step-divider {
            display: none;
        }

        .step-progress-container {
            max-width: initial;
        }

        .step-header {
            text-align: center;
            gap: 20px;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            padding-bottom: 22px;
        }

        .step-titles .step-title {
            max-width: initial;
            margin: 0 auto;
            height: 84px;
        }

        .step-title p.desktop-p {
            display: block;
            line-height: 1;
        }

        .step-title p.mobile-p {
            display: none;
        }

        .step-title.title3 p {
            white-space: nowrap;
            margin-right: 60px;
        }

        /* .step-title.title2,
  .step-title.title3 {
    padding-bottom: 16px;
  }
  .step-title.title4 {
    padding-bottom: 40px;
  } */

        .step-title h2 {
            font-size: 22px;
            letter-spacing: 0.7px;
            max-width: initial;
        }

        .step-title p {
            font-size: 14px;
            max-width: 380px;
            margin: 0 auto;
            margin-top: 8px;
        }

        .progress-area {
            position: absolute;
            top: 10px;
            right: 10%;
            transform: translateX(20%);
            border-bottom: 0px;
        }

        .progress-text {
            text-align: right;
        }

        .form-grid .grid-input .input-industry {
            height: 50px;
            /* display: none; */
        }

        .form-grid .grid-input textarea {
            height: 120px;
            margin-top: -0.1rem;
        }

        .form-grid .grid-input.outcome-input textarea {
            /* height: 130px; */
            margin-top: 0rem;
            resize: none;
            height: 110px;

        }

        .form-grid {
            grid-template-columns: repeat(2, 1fr);
            max-width: 750px;
            margin: 0 auto;
        }

        .form-grid input {
            padding: 14px 40px;
        }




        textarea {
            height: 120px;
            margin-top: -2rem;
            resize: none;
        }

        /* STEP 4 */
        .outcome-grid {
            grid-template-columns: repeat(1, 1fr);
        }

        .outcome-grid textarea {
            height: 80px;
            margin-top: 0rem;
        }

        .goals-grid {
            max-width: 950px;
            margin: 0 auto;
        }

        .goal-card {
            width: 170px;

            padding: 22px 12px;
        }

        .goal-text {
            font-size: 0.9rem;
            max-width: 150px;
            margin: 0 auto;
            padding: 0px 18px;
        }

        .step-error {
            max-width: 500px;
            margin: 0 auto;
            margin-top: 20px;
        }
    }

    @media (min-width: 1440px) {

        .multistep-form-container {
            padding-top: 80px;
        }

        .multi-step-wrapper {

            background-image: url(../img/form-bg1.jpg);

            background-color: #ffffffa9;
            background-blend-mode: soft-light;
            background-repeat: repeat;
            background-size: cover;
            background-position: center;
        }


        .step-counter-container {
            width: 98px;
            height: 98px;
        }

        .counter-circle {
            width: 80px;
            height: 80px;
            left: 3px;
            top: 3px;
        }

        .counter-circle span {
            font-size: 32px;
        }

        .step4 .step-buttons {
            margin-top: 20px;
        }

        .form-grid,
        .goals-grid {
            padding-top: 60px;
        }

        .goals-grid {
            max-width: 950px;
            /* border: 1px solid red; */

            margin: 0 auto;
        }

        .goal-card {
            width: 190px;
            padding: 0px;
            /* height: 43px; */
            gap: 17px;
        }

        .goal-text {
            max-width: 130px;
            padding: 0px 3px;
        }

        .outcome-grid label {

            margin-left: 35px;
        }


        .form-grid input.email {
            /* padding: 0px; */
            /* height: 5px;; */
        }

    }
</style>

@section('content')

    <x-live-form-capture formId="multiStepForm" :saveUrl="route('consultation.save-draft')" :saveInterval="3000" :enableLogging="true">

        <!-- Live Capture Status Indicator (for testing) -->
        {{-- <div id="live-capture-status"
            style="position: fixed; top: 10px; right: 10px; z-index: 9999; padding: 8px 12px; background: #28a745; color: white; border-radius: 5px; font-size: 12px; display: none;">
            <i class="fa fa-wifi"></i> Live Capture Active
        </div> --}}

        <section class="multistep-form-container">
            <div class="multi-step-wrapper">
                <!-- Step Header -->
                <div class="step-header">
                    <div class="step-counter-container">
                        <div class="step-counter">
                            <div class="counter-circle">
                                <span>01</span>
                            </div>
                        </div>
                    </div>

                    <div class="step-progress-container">
                        <div class="progress-area">
                            <div class="box-area">
                                <span class="progress-text">01 of 04</span>
                                <div class="progress-baar">
                                    <div class="progress-fill"></div>
                                </div>
                            </div>
                        </div>

                        <div class="step-divider"></div>

                        <!--        STEP 1 TITLE                                   -->

                        <div class="step-titles">
                            <!-- STEP 1 -->
                            <div class="step-title active title1" data-title="BUSINESS OVERVIEW"
                                data-subtitle="We use this info to better understand your business before the session.">
                                <h2>BUSINESS OVERVIEW</h2>
                                <p class="mobile-p">
                                    <span>We use this info to better understand</span>
                                    <span> your business before the session</span>
                                </p>
                                <p class="desktop-p">
                                    We use this info to better understand your business before the
                                    session
                                </p>
                            </div>

                            <!-- STEP 2 -->
                            <div class="step-title title2" data-title="BRAND IDENTITY"
                                data-subtitle="Tell us more about your brand tone, target audience, and positioning.">
                                <h2>Current Stage & Goals</h2>

                                <p class="mobile-p">
                                    <span>What are your current top goals?</span>
                                    <span>(Select up to 2)</span>
                                </p>
                                <p class="desktop-p">
                                    What are your current top goals? (Select up to 2)
                                </p>
                            </div>

                            <!-- STEP 3 -->
                            <div class="step-title title3" data-title="challenges"
                                data-subtitle="Share your project goals and the key outcomes you want to achieve.">
                                <h2>Key Challenges</h2>

                                <p class="mobile-p">
                                    <span>What challenges are holding your </span>
                                    <span>business back? (Select up to 3)</span>
                                </p>
                                <p class="desktop-p">
                                    What challenges are holding your business back? (Select up to
                                    3)
                                </p>
                            </div>

                            <!-- STEP 4 -->
                            <div class="step-title title4" data-title="outcome">
                                <h2>Outcome</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step Content -->
                <form id="multiStepForm" method="POST" action="{{ route('consultation.submit') }}">
                    @csrf
                    <!-- Session ID for live form capture - will be populated by JavaScript -->
                    <input type="hidden" name="session_id" id="form_session_id" value="">

                    <!-- STEP 1 -->
                    <div class="form-step active">
                        <div class="form-grid">
                            <div class="grid-input">
                                <input type="text" name="full_name" placeholder="Full Name"
                                    value="{{ old('full_name') }}" required />
                                @error('full_name')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror

                                <div class="phone-field">

                                    <!-- FLAG SELECTOR -->
                                    <div class="flag-box" id="flagBox">
                                        <img id="flagImg" class="flagImagee" src="" alt="flag"
                                            style="display: none;">
                                        <span id="flagPlaceholder" class="flag-icon placeholder-flag">🌍</span>

                                        <img id="dropdownIcon" class="dropdown-icon" src="{{ asset('img/arrow-up.png') }}"
                                            alt="" />
                                        <!-- DROPDOWN -->
                                        <div class="flag-dropdown" id="flagDropdown">
                                            <!-- Search Input -->
                                            <div class="flag-search-container">
                                                <input type="text" id="flagSearch" placeholder="Search countries..."
                                                    autocomplete="off">
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/us.svg" data-code="+1">
                                                <img src="https://flagcdn.com/us.svg"> USA (+1)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/gb.svg" data-code="+44">
                                                <img src="https://flagcdn.com/gb.svg"> United Kingdom (+44)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/in.svg" data-code="+91">
                                                <img src="https://flagcdn.com/in.svg"> India (+91)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/cn.svg" data-code="+86">
                                                <img src="https://flagcdn.com/cn.svg"> China (+86)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/jp.svg"
                                                data-code="+81">
                                                <img src="https://flagcdn.com/jp.svg"> Japan (+81)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/de.svg"
                                                data-code="+49">
                                                <img src="https://flagcdn.com/de.svg"> Germany (+49)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/fr.svg"
                                                data-code="+33">
                                                <img src="https://flagcdn.com/fr.svg"> France (+33)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/it.svg"
                                                data-code="+39">
                                                <img src="https://flagcdn.com/it.svg"> Italy (+39)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ru.svg" data-code="+7">
                                                <img src="https://flagcdn.com/ru.svg"> Russia (+7)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/br.svg"
                                                data-code="+55">
                                                <img src="https://flagcdn.com/br.svg"> Brazil (+55)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/mx.svg"
                                                data-code="+52">
                                                <img src="https://flagcdn.com/mx.svg"> Mexico (+52)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/au.svg"
                                                data-code="+61">
                                                <img src="https://flagcdn.com/au.svg"> Australia (+61)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/za.svg"
                                                data-code="+27">
                                                <img src="https://flagcdn.com/za.svg"> South Africa (+27)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ng.svg"
                                                data-code="+234">
                                                <img src="https://flagcdn.com/ng.svg"> Nigeria (+234)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ke.svg"
                                                data-code="+254">
                                                <img src="https://flagcdn.com/ke.svg"> Kenya (+254)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/gh.svg"
                                                data-code="+233">
                                                <img src="https://flagcdn.com/gh.svg"> Ghana (+233)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ug.svg"
                                                data-code="+256">
                                                <img src="https://flagcdn.com/ug.svg"> Uganda (+256)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/tz.svg"
                                                data-code="+255">
                                                <img src="https://flagcdn.com/tz.svg"> Tanzania (+255)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/eg.svg"
                                                data-code="+20">
                                                <img src="https://flagcdn.com/eg.svg"> Egypt (+20)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/dz.svg"
                                                data-code="+213">
                                                <img src="https://flagcdn.com/dz.svg"> Algeria (+213)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ma.svg"
                                                data-code="+212">
                                                <img src="https://flagcdn.com/ma.svg"> Morocco (+212)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/tn.svg"
                                                data-code="+216">
                                                <img src="https://flagcdn.com/tn.svg"> Tunisia (+216)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ly.svg"
                                                data-code="+218">
                                                <img src="https://flagcdn.com/ly.svg"> Libya (+218)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/et.svg"
                                                data-code="+251">
                                                <img src="https://flagcdn.com/et.svg"> Ethiopia (+251)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/zm.svg"
                                                data-code="+260">
                                                <img src="https://flagcdn.com/zm.svg"> Zambia (+260)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/zw.svg"
                                                data-code="+263">
                                                <img src="https://flagcdn.com/zw.svg"> Zimbabwe (+263)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/mw.svg"
                                                data-code="+265">
                                                <img src="https://flagcdn.com/mw.svg"> Malawi (+265)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/bw.svg"
                                                data-code="+267">
                                                <img src="https://flagcdn.com/bw.svg"> Botswana (+267)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/sz.svg"
                                                data-code="+268">
                                                <img src="https://flagcdn.com/sz.svg"> Eswatini (+268)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ls.svg"
                                                data-code="+266">
                                                <img src="https://flagcdn.com/ls.svg"> Lesotho (+266)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/na.svg"
                                                data-code="+264">
                                                <img src="https://flagcdn.com/na.svg"> Namibia (+264)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/mz.svg"
                                                data-code="+258">
                                                <img src="https://flagcdn.com/mz.svg"> Mozambique (+258)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/mg.svg"
                                                data-code="+261">
                                                <img src="https://flagcdn.com/mg.svg"> Madagascar (+261)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/mu.svg"
                                                data-code="+230">
                                                <img src="https://flagcdn.com/mu.svg"> Mauritius (+230)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/sc.svg"
                                                data-code="+248">
                                                <img src="https://flagcdn.com/sc.svg"> Seychelles (+248)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/rw.svg"
                                                data-code="+250">
                                                <img src="https://flagcdn.com/rw.svg"> Rwanda (+250)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/sn.svg"
                                                data-code="+221">
                                                <img src="https://flagcdn.com/sn.svg"> Senegal (+221)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ci.svg"
                                                data-code="+225">
                                                <img src="https://flagcdn.com/ci.svg"> Ivory Coast (+225)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/bf.svg"
                                                data-code="+226">
                                                <img src="https://flagcdn.com/bf.svg"> Burkina Faso (+226)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ml.svg"
                                                data-code="+223">
                                                <img src="https://flagcdn.com/ml.svg"> Mali (+223)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ne.svg"
                                                data-code="+227">
                                                <img src="https://flagcdn.com/ne.svg"> Niger (+227)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/td.svg"
                                                data-code="+235">
                                                <img src="https://flagcdn.com/td.svg"> Chad (+235)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/cm.svg"
                                                data-code="+237">
                                                <img src="https://flagcdn.com/cm.svg"> Cameroon (+237)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/cf.svg"
                                                data-code="+236">
                                                <img src="https://flagcdn.com/cf.svg"> Central African Republic (+236)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/cg.svg"
                                                data-code="+242">
                                                <img src="https://flagcdn.com/cg.svg"> Republic of Congo (+242)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/cd.svg"
                                                data-code="+243">
                                                <img src="https://flagcdn.com/cd.svg"> Democratic Republic of Congo (+243)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ga.svg"
                                                data-code="+241">
                                                <img src="https://flagcdn.com/ga.svg"> Gabon (+241)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/gq.svg"
                                                data-code="+240">
                                                <img src="https://flagcdn.com/gq.svg"> Equatorial Guinea (+240)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/st.svg"
                                                data-code="+239">
                                                <img src="https://flagcdn.com/st.svg"> São Tomé and Príncipe (+239)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/cv.svg"
                                                data-code="+238">
                                                <img src="https://flagcdn.com/cv.svg"> Cape Verde (+238)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/gn.svg"
                                                data-code="+224">
                                                <img src="https://flagcdn.com/gn.svg"> Guinea (+224)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/gw.svg"
                                                data-code="+245">
                                                <img src="https://flagcdn.com/gw.svg"> Guinea-Bissau (+245)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/lr.svg"
                                                data-code="+231">
                                                <img src="https://flagcdn.com/lr.svg"> Liberia (+231)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/sl.svg"
                                                data-code="+232">
                                                <img src="https://flagcdn.com/sl.svg"> Sierra Leone (+232)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/tg.svg"
                                                data-code="+228">
                                                <img src="https://flagcdn.com/tg.svg"> Togo (+228)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/bj.svg"
                                                data-code="+229">
                                                <img src="https://flagcdn.com/bj.svg"> Benin (+229)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/mr.svg"
                                                data-code="+222">
                                                <img src="https://flagcdn.com/mr.svg"> Mauritania (+222)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/gm.svg"
                                                data-code="+220">
                                                <img src="https://flagcdn.com/gm.svg"> Gambia (+220)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/bi.svg"
                                                data-code="+257">
                                                <img src="https://flagcdn.com/bi.svg"> Burundi (+257)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/dj.svg"
                                                data-code="+253">
                                                <img src="https://flagcdn.com/dj.svg"> Djibouti (+253)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/er.svg"
                                                data-code="+291">
                                                <img src="https://flagcdn.com/er.svg"> Eritrea (+291)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/so.svg"
                                                data-code="+252">
                                                <img src="https://flagcdn.com/so.svg"> Somalia (+252)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ss.svg"
                                                data-code="+211">
                                                <img src="https://flagcdn.com/ss.svg"> South Sudan (+211)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/sd.svg"
                                                data-code="+249">
                                                <img src="https://flagcdn.com/sd.svg"> Sudan (+249)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ao.svg"
                                                data-code="+244">
                                                <img src="https://flagcdn.com/ao.svg"> Angola (+244)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/km.svg"
                                                data-code="+269">
                                                <img src="https://flagcdn.com/km.svg"> Comoros (+269)
                                            </div>

                                            <!-- Non-African Countries -->
                                            <div class="flag-item" data-flag="https://flagcdn.com/ca.svg" data-code="+1">
                                                <img src="https://flagcdn.com/ca.svg"> Canada (+1)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/es.svg"
                                                data-code="+34">
                                                <img src="https://flagcdn.com/es.svg"> Spain (+34)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/pt.svg"
                                                data-code="+351">
                                                <img src="https://flagcdn.com/pt.svg"> Portugal (+351)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/nl.svg"
                                                data-code="+31">
                                                <img src="https://flagcdn.com/nl.svg"> Netherlands (+31)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/be.svg"
                                                data-code="+32">
                                                <img src="https://flagcdn.com/be.svg"> Belgium (+32)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ch.svg"
                                                data-code="+41">
                                                <img src="https://flagcdn.com/ch.svg"> Switzerland (+41)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/at.svg"
                                                data-code="+43">
                                                <img src="https://flagcdn.com/at.svg"> Austria (+43)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/se.svg"
                                                data-code="+46">
                                                <img src="https://flagcdn.com/se.svg"> Sweden (+46)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/no.svg"
                                                data-code="+47">
                                                <img src="https://flagcdn.com/no.svg"> Norway (+47)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/dk.svg"
                                                data-code="+45">
                                                <img src="https://flagcdn.com/dk.svg"> Denmark (+45)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/fi.svg"
                                                data-code="+358">
                                                <img src="https://flagcdn.com/fi.svg"> Finland (+358)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ie.svg"
                                                data-code="+353">
                                                <img src="https://flagcdn.com/ie.svg"> Ireland (+353)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/nz.svg"
                                                data-code="+64">
                                                <img src="https://flagcdn.com/nz.svg"> New Zealand (+64)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/sg.svg"
                                                data-code="+65">
                                                <img src="https://flagcdn.com/sg.svg"> Singapore (+65)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/my.svg"
                                                data-code="+60">
                                                <img src="https://flagcdn.com/my.svg"> Malaysia (+60)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/th.svg"
                                                data-code="+66">
                                                <img src="https://flagcdn.com/th.svg"> Thailand (+66)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ph.svg"
                                                data-code="+63">
                                                <img src="https://flagcdn.com/ph.svg"> Philippines (+63)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/id.svg"
                                                data-code="+62">
                                                <img src="https://flagcdn.com/id.svg"> Indonesia (+62)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/vn.svg"
                                                data-code="+84">
                                                <img src="https://flagcdn.com/vn.svg"> Vietnam (+84)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/kr.svg"
                                                data-code="+82">
                                                <img src="https://flagcdn.com/kr.svg"> South Korea (+82)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/pk.svg"
                                                data-code="+92">
                                                <img src="https://flagcdn.com/pk.svg"> Pakistan (+92)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/bd.svg"
                                                data-code="+880">
                                                <img src="https://flagcdn.com/bd.svg"> Bangladesh (+880)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/lk.svg"
                                                data-code="+94">
                                                <img src="https://flagcdn.com/lk.svg"> Sri Lanka (+94)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ar.svg"
                                                data-code="+54">
                                                <img src="https://flagcdn.com/ar.svg"> Argentina (+54)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/cl.svg"
                                                data-code="+56">
                                                <img src="https://flagcdn.com/cl.svg"> Chile (+56)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/co.svg"
                                                data-code="+57">
                                                <img src="https://flagcdn.com/co.svg"> Colombia (+57)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/pe.svg"
                                                data-code="+51">
                                                <img src="https://flagcdn.com/pe.svg"> Peru (+51)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ve.svg"
                                                data-code="+58">
                                                <img src="https://flagcdn.com/ve.svg"> Venezuela (+58)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ec.svg"
                                                data-code="+593">
                                                <img src="https://flagcdn.com/ec.svg"> Ecuador (+593)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/uy.svg"
                                                data-code="+598">
                                                <img src="https://flagcdn.com/uy.svg"> Uruguay (+598)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/py.svg"
                                                data-code="+595">
                                                <img src="https://flagcdn.com/py.svg"> Paraguay (+595)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/bo.svg"
                                                data-code="+591">
                                                <img src="https://flagcdn.com/bo.svg"> Bolivia (+591)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/gy.svg"
                                                data-code="+592">
                                                <img src="https://flagcdn.com/gy.svg"> Guyana (+592)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/sr.svg"
                                                data-code="+597">
                                                <img src="https://flagcdn.com/sr.svg"> Suriname (+597)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/tr.svg"
                                                data-code="+90">
                                                <img src="https://flagcdn.com/tr.svg"> Turkey (+90)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ae.svg"
                                                data-code="+971">
                                                <img src="https://flagcdn.com/ae.svg"> United Arab Emirates (+971)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/sa.svg"
                                                data-code="+966">
                                                <img src="https://flagcdn.com/sa.svg"> Saudi Arabia (+966)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/qa.svg"
                                                data-code="+974">
                                                <img src="https://flagcdn.com/qa.svg"> Qatar (+974)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/kw.svg"
                                                data-code="+965">
                                                <img src="https://flagcdn.com/kw.svg"> Kuwait (+965)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/bh.svg"
                                                data-code="+973">
                                                <img src="https://flagcdn.com/bh.svg"> Bahrain (+973)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/om.svg"
                                                data-code="+968">
                                                <img src="https://flagcdn.com/om.svg"> Oman (+968)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/jo.svg"
                                                data-code="+962">
                                                <img src="https://flagcdn.com/jo.svg"> Jordan (+962)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/lb.svg"
                                                data-code="+961">
                                                <img src="https://flagcdn.com/lb.svg"> Lebanon (+961)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/sy.svg"
                                                data-code="+963">
                                                <img src="https://flagcdn.com/sy.svg"> Syria (+963)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/iq.svg"
                                                data-code="+964">
                                                <img src="https://flagcdn.com/iq.svg"> Iraq (+964)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ir.svg"
                                                data-code="+98">
                                                <img src="https://flagcdn.com/ir.svg"> Iran (+98)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/af.svg"
                                                data-code="+93">
                                                <img src="https://flagcdn.com/af.svg"> Afghanistan (+93)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/il.svg"
                                                data-code="+972">
                                                <img src="https://flagcdn.com/il.svg"> Israel (+972)
                                            </div>

                                            <div class="flag-item" data-flag="https://flagcdn.com/ps.svg"
                                                data-code="+970">
                                                <img src="https://flagcdn.com/ps.svg"> Palestine (+970)
                                            </div>

                                        </div>

                                    </div>

                                    <!-- PREFIX + INPUT -->
                                    <div class="number-wrapper">
                                        <span id="countryPrefix" style="display: none;"></span>
                                        <input type="text" id="phoneInput" placeholder="Select country first"
                                            disabled>
                                    </div>

                                    <!-- Hidden field for form submission -->
                                    <input type="hidden" name="phone" id="combinedPhone"
                                        value="{{ old('phone') }}" />

                                </div>




                                @error('phone')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror

                                <input type="text" name="company_brand_name" value="{{ old('company_brand_name') }}"
                                    placeholder="Company Name" required />
                                @error('company_brand_name')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror

                                <input type="text" name="website_or_handle" placeholder="Website/Social media"
                                    value="{{ old('website_or_handle') }}" required />
                                @error('website_or_handle')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror


                            </div>
                            <div class="grid-input industry-cont">
                                <input type="email" name="email" class="email" placeholder="Email Address"
                                    autocomplete="email" required value="{{ old('email') }}" />
                                @error('email')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror

                                <input type="text" class="input-industry" name="industry" placeholder="Industry"
                                    value="{{ old('industry') }}" required />
                                @error('industry')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror

                                <textarea name="business_description" class="brief"
                                    placeholder="Briefly describe what your business does (1–2 sentences)." required>{{ old('business_description') }}</textarea>
                                @error('business_description')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="step-buttons">
                            <button type="button" class="form-btn back-btn" disabled>
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                            <button type="button" class="form-btn next-btn">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2 -->
                    <div class="form-step">
                        <!-- STEP 2 -->
                        <div class="step2-wrapper">
                            <div class="goals-grid">
                                <label class="goal-card">
                                    <input type="checkbox" name="business_goals[]" value="Increase revenue or sales" />
                                    <span class="goal-text increase-text">Increase revenue or sales</span>
                                    <span class="goal-checkmark">
                                        <img src="{{ asset('img/marked.png') }}" alt="" />
                                    </span>
                                </label>

                                <label class="goal-card">
                                    <input type="checkbox" name="business_goals[]" value="Generate more leads" />
                                    <span class="goal-text">Generate more leads</span>
                                    <span class="goal-checkmark">
                                        <img src="{{ asset('img/marked.png') }}" alt="" />
                                    </span>
                                </label>

                                <label class="goal-card">
                                    <input type="checkbox" name="business_goals[]" value="Improve brand visibility" />
                                    <span class="goal-text">Improve brand visibility</span>
                                    <span class="goal-checkmark">
                                        <img src="{{ asset('img/marked.png') }}" alt="" />
                                    </span>
                                </label>

                                <label class="goal-card">
                                    <input type="checkbox" name="business_goals[]"
                                        value="Launch a new product or service" />
                                    <span class="goal-text launch-text">Launch a new product or service</span>
                                    <span class="goal-checkmark">
                                        <img src="{{ asset('img/marked.png') }}" alt="" />
                                    </span>
                                </label>

                                <label class="goal-card">
                                    <input type="checkbox" name="business_goals[]" value="Expand into new markets" />
                                    <span class="goal-text">Expand into new markets</span>
                                    <span class="goal-checkmark">
                                        <img src="{{ asset('img/marked.png') }}" alt="" />
                                    </span>
                                </label>

                                <label class="goal-card">
                                    <input type="checkbox" name="business_goals[]"
                                        value="Prepare for funding or investor conversations" />
                                    <span class="goal-text prepare-text">Prepare for funding or investor
                                        conversations</span>
                                    <span class="goal-checkmark">
                                        <img src="{{ asset('img/marked.png') }}" alt="" />
                                    </span>
                                </label>

                                <label class="goal-card">
                                    <input type="checkbox" name="business_goals[]"
                                        value="Build internal systems or operational structure" />
                                    <span class="goal-text build-text">Build internal systems or operational
                                        structure</span>
                                    <span class="goal-checkmark">
                                        <img src="{{ asset('img/marked.png') }}" alt="" />
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Add real fields for step 2 here -->

                        <div class="step-buttons">
                            <button type="button" class="form-btn back-btn" disabled>
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                            <button type="button" class="form-btn next-btn">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div class="form-step">
                        <div class="step3-wrapper">
                            <div class="goals-grid">
                                <label class="goal-card">
                                    <input type="checkbox" name="key_challenges[]" value="Low or inconsistent sales" />
                                    <span class="goal-text">Low or inconsistent sales</span>
                                    <span class="goal-checkmark"><img src="{{ asset('img/marked.png') }}"
                                            alt="" /></span>
                                </label>

                                <label class="goal-card">
                                    <input type="checkbox" name="key_challenges[]" value="Weak marketing strategy" />
                                    <span class="goal-text">Weak marketing strategy</span>
                                    <span class="goal-checkmark"><img src="{{ asset('img/marked.png') }}"
                                            alt="" /></span>
                                </label>

                                <label class="goal-card">
                                    <input type="checkbox" name="key_challenges[]"
                                        value="Difficulty acquiring customers" />
                                    <span class="goal-text">Difficulty acquiring customers</span>
                                    <span class="goal-checkmark"><img src="{{ asset('img/marked.png') }}"
                                            alt="" /></span>
                                </label>

                                <label class="goal-card">
                                    <input type="checkbox" name="key_challenges[]" value="Operational inefficiencies" />
                                    <span class="goal-text">Operational inefficiencies</span>
                                    <span class="goal-checkmark"><img src="{{ asset('img/marked.png') }}"
                                            alt="" /></span>
                                </label>

                                <label class="goal-card">
                                    <input type="checkbox" name="key_challenges[]" value="Monetization not working" />
                                    <span class="goal-text">Monetization not working</span>
                                    <span class="goal-checkmark"><img src="{{ asset('img/marked.png') }}"
                                            alt="" /></span>
                                </label>

                                <label class="goal-card">
                                    <input type="checkbox" name="key_challenges[]"
                                        value="Need clarity on product-market fit" />
                                    <span class="goal-text">Need clarity on product-market fit</span>
                                    <span class="goal-checkmark"><img src="{{ asset('img/marked.png') }}"
                                            alt="" /></span>
                                </label>

                                <label class="goal-card">
                                    <input type="checkbox" name="key_challenges[]" value="Scaling challenges" />
                                    <span class="goal-text">Scaling challenges</span>
                                    <span class="goal-checkmark"><img src="{{ asset('img/marked.png') }}"
                                            alt="" /></span>
                                </label>
                            </div>
                        </div>

                        <div class="step-buttons">
                            <button type="button" class="form-btn back-btn" disabled>
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                            <button type="button" class="form-btn next-btn">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 4 -->
                    <div class="form-step step4">
                        <div class="form-grid outcome-grid">
                            <div class="grid-input outcome-input">
                                <label for="motivation">What made you book this consultation today?</label>
                                <textarea name="motivation" required>{{ old('motivation') }}</textarea>
                                @error('motivation')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="grid-input outcome-input">
                                <label for="success_definition">What would make this session a success for you?</label>
                                <textarea name="success_definition" required>{{ old('success_definition') }}</textarea>
                                @error('success_definition')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="step-buttons">
                            <button type="button" class="form-btn back-btn" disabled>
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                            <button type="submit" class="form-btn next-btn" id="submitFormBtn">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

    @endsection


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const steps = document.querySelectorAll(".form-step");
            const stepTitles = document.querySelectorAll(".step-title");
            const nextBtns = document.querySelectorAll(".next-btn");
            const backBtns = document.querySelectorAll(".back-btn");

            const progressFill = document.querySelector(".progress-fill");
            const progressText = document.querySelector(".progress-text");
            const counterCircleText = document.querySelector(".counter-circle span");

            // Clear saved progress when starting fresh from consultation page
            // This ensures users always start from step 1
            localStorage.removeItem("currentStep");
            localStorage.removeItem("selectedBusinessGoals");
            localStorage.removeItem("selectedKeyChallenges");

            //  Always start from step 0 (first step)
            let currentStep = 0;

            // ============= VALIDATION FUNCTIONS =============

            // Clear previous errors
            function clearErrors(step) {
                const stepElement = steps[step];

                // Remove error classes from inputs
                stepElement.querySelectorAll('input, textarea').forEach(input => {
                    input.classList.remove('error');
                });

                // Remove error messages
                stepElement.querySelectorAll('.error-message').forEach(msg => {
                    msg.remove();
                });

                // Remove step error message
                stepElement.querySelectorAll('.step-error').forEach(error => {
                    error.remove();
                });

                // Remove error classes from goal cards  
                stepElement.querySelectorAll('.goal-card').forEach(card => {
                    card.classList.remove('error');
                });
            }

            // Show error message for input field
            function showInputError(input, message) {
                input.classList.add('error');

                const errorMsg = document.createElement('span');
                errorMsg.className = 'error-message';
                errorMsg.textContent = message;

                input.parentNode.insertBefore(errorMsg, input.nextSibling);
            }

            // Show step error message
            function showStepError(step, message) {
                const stepElement = steps[step];
                const existingError = stepElement.querySelector('.step-error');

                if (!existingError) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'step-error';
                    errorDiv.textContent = message;

                    const stepButtons = stepElement.querySelector('.step-buttons');
                    stepButtons.parentNode.insertBefore(errorDiv, stepButtons);
                }
            }

            // Validate email format
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // Validate phone format (with country code)
            function isValidPhone(phone) {
                // Phone should be in format "+countrycode phonenumber"
                const phoneRegex = /^\+\d{1,4}\s\d{4,14}$/;
                return phoneRegex.test(phone);
            }

            // Validate individual phone number (without country code)
            function isValidPhoneNumber(phoneNumber) {
                // Basic phone number validation (4-14 digits)
                const phoneRegex = /^\d{4,14}$/;
                return phoneRegex.test(phoneNumber.replace(/[\s\-\(\)]/g, ''));
            }

            // Check if country code is selected
            function isCountryCodeSelected() {
                const prefixSpan = document.getElementById('countryPrefix');
                return prefixSpan && prefixSpan.textContent.trim() !== '';
            }

            // Update phone border when country is not selected
            function updatePhoneBorder(prefixSpan, phoneNumberInput) {
                const hasCountry = prefixSpan.textContent.trim() !== "";

                if (!hasCountry) {
                    phoneNumberInput.classList.add("phone-rounded");
                    phoneNumberInput.classList.remove("phone-no-radius");
                } else {
                    phoneNumberInput.classList.remove("phone-rounded");
                    phoneNumberInput.classList.add("phone-no-radius");
                }
            }



            // Validate Step 1: Basic Information
            function validateStep1() {
                clearErrors(0);
                let isValid = true;

                const requiredFields = [{
                        name: 'full_name',
                        label: 'Full Name'
                    },
                    {
                        name: 'phone',
                        label: 'Phone Number'
                    },
                    {
                        name: 'company_brand_name',
                        label: 'Company Name'
                    },
                    {
                        name: 'website_or_handle',
                        label: 'Website/Social media'
                    },
                    {
                        name: 'email',
                        label: 'Email Address'
                    },
                    {
                        name: 'industry',
                        label: 'Industry'
                    },
                    {
                        name: 'business_description',
                        label: 'Business Description'
                    }
                ];

                requiredFields.forEach(field => {
                    if (field.name === 'phone') {
                        // Special handling for phone field
                        const phoneNumberInput = document.getElementById('phoneInput');
                        const combinedPhoneInput = document.getElementById('combinedPhone');
                        const flagBox = document.getElementById('flagBox');

                        if (!isCountryCodeSelected()) {
                            flagBox.classList.add('error');

                            const errorMsg = document.createElement('span');
                            errorMsg.className = 'error-message';
                            errorMsg.textContent = 'Please select a country code';

                            phoneNumberInput.closest('.phone-field')
                                .insertAdjacentElement('afterend', errorMsg);

                            isValid = false;

                        } else if (!phoneNumberInput.value.trim()) {
                            phoneNumberInput.classList.add('error');

                            const errorMsg = document.createElement('span');
                            errorMsg.className = 'error-message';
                            errorMsg.textContent = 'Phone Number is required';

                            phoneNumberInput.closest('.phone-field')
                                .insertAdjacentElement('afterend', errorMsg);

                            isValid = false;

                        } else if (!isValidPhoneNumber(phoneNumberInput.value.trim())) {

                            phoneNumberInput.classList.add('error');

                            const errorMsg = document.createElement('span');
                            errorMsg.className = 'error-message';
                            errorMsg.textContent = 'Please enter a valid phone number';

                            phoneNumberInput.closest('.phone-field')
                                .insertAdjacentElement('afterend', errorMsg);

                            isValid = false;

                        } else {
                            updateCombinedPhone();
                        }

                    } else {
                        const input = document.querySelector(
                            `input[name="${field.name}"], textarea[name="${field.name}"]`);
                        if (!input || !input.value.trim()) {
                            showInputError(input, `${field.label} is required`);
                            isValid = false;
                        } else {
                            // Additional validation for specific fields
                            if (field.name === 'email' && !isValidEmail(input.value.trim())) {
                                showInputError(input, 'Please enter a valid email address');
                                isValid = false;
                            } else if (field.name === 'business_description' && input.value.trim().length <
                                10) {
                                showInputError(input,
                                    'Please provide at least 10 characters describing your business');
                                isValid = false;
                            }
                        }
                    }
                });

                if (!isValid) {
                    showStepError(0, 'Please fill in all required fields correctly before proceeding.');
                }

                return isValid;
            }

            // Validate Step 2: Business Goals  
            function validateStep2() {
                clearErrors(1);

                const checkedGoals = document.querySelectorAll('.step2-wrapper input[type="checkbox"]:checked');

                if (checkedGoals.length === 0) {
                    showStepError(1, 'Please select at least one business goal.');

                    // Highlight goal cards as error
                    document.querySelectorAll('.step2-wrapper .goal-card').forEach(card => {
                        card.classList.add('error');
                    });

                    return false;
                } else if (checkedGoals.length > 2) {
                    showStepError(1, 'Please select no more than 2 business goals.');
                    return false;
                }

                return true;
            }

            // Validate Step 3: Key Challenges
            function validateStep3() {
                clearErrors(2);

                const checkedChallenges = document.querySelectorAll(
                    '.step3-wrapper input[type="checkbox"]:checked');

                if (checkedChallenges.length === 0) {
                    showStepError(2, 'Please select at least one key challenge.');

                    // Highlight goal cards as error  
                    document.querySelectorAll('.step3-wrapper .goal-card').forEach(card => {
                        card.classList.add('error');
                    });

                    return false;
                } else if (checkedChallenges.length > 3) {
                    showStepError(2, 'Please select no more than 3 key challenges.');
                    return false;
                }

                return true;
            }

            // Validate Step 4: Outcome Questions
            function validateStep4() {
                clearErrors(3);
                let isValid = true;

                const motivationField = document.querySelector('textarea[name="motivation"]');
                const successField = document.querySelector('textarea[name="success_definition"]');

                if (!motivationField.value.trim()) {
                    showInputError(motivationField, 'Please explain what made you book this consultation');
                    isValid = false;
                } else if (motivationField.value.trim().length < 10) {
                    showInputError(motivationField, 'Please provide at least 10 characters');
                    isValid = false;
                }

                if (!successField.value.trim()) {
                    showInputError(successField, 'Please define what would make this session successful');
                    isValid = false;
                } else if (successField.value.trim().length < 10) {
                    showInputError(successField, 'Please provide at least 10 characters');
                    isValid = false;
                }

                if (!isValid) {
                    showStepError(3, 'Please complete both questions before submitting.');
                }

                return isValid;
            }

            // ============= STEP MANAGEMENT =============

            //  Update Steps
            function updateStep() {
                steps.forEach((step) => step.classList.remove("active"));
                stepTitles.forEach((title) => title.classList.remove("active"));

                steps[currentStep].classList.add("active");
                stepTitles[currentStep].classList.add("active");

                const percent = ((currentStep + 1) / steps.length) * 100;
                progressFill.style.width = percent + "%";
                progressText.textContent = `0${currentStep + 1} of 0${steps.length}`;
                counterCircleText.textContent = `0${currentStep + 1}`;

                localStorage.setItem("currentStep", currentStep);

                const backButton = steps[currentStep].querySelector(".back-btn");
                if (backButton) backButton.disabled = currentStep === 0;

                document.querySelectorAll(".form-step, .step-title").forEach((el) => {
                    el.classList.remove("fade-in");
                    void el.offsetWidth;
                    el.classList.add("fade-in");
                });

                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            }

            //  NEXT BUTTONS
            nextBtns.forEach((btn, index) => {
                btn.addEventListener("click", (e) => {
                    // If it's not the submit button, prevent default and validate current step
                    if (btn.type !== 'submit') {
                        e.preventDefault();

                        let isValid = false;

                        // Validate current step
                        switch (currentStep) {
                            case 0:
                                isValid = validateStep1();
                                break;
                            case 1:
                                isValid = validateStep2();
                                break;
                            case 2:
                                isValid = validateStep3();
                                break;
                            case 3:
                                isValid = validateStep4();
                                break;
                            default:
                                isValid = true;
                        }

                        // Only proceed if validation passes
                        if (isValid && currentStep < steps.length - 1) {
                            currentStep++;
                            updateStep();
                        }
                    } else {
                        // It's the submit button - validate step 4 before allowing submission
                        if (!validateStep4()) {
                            e.preventDefault();
                            return false;
                        }
                    }
                });
            });

            //  BACK BUTTONS
            backBtns.forEach((btn) => {
                btn.addEventListener("click", () => {
                    if (currentStep > 0) {
                        currentStep--;
                        updateStep();
                    }
                });
            });

            // ============= GOAL CARD HANDLING =============

            // === Handle Selectable Goal Cards ===
            function handleSelectableCards(selector, maxSelection, storageKey) {
                const cards = document.querySelectorAll(`${selector} .goal-card`);
                const inputs = document.querySelectorAll(`${selector} input[type="checkbox"]`);

                inputs.forEach((input) => {
                    input.addEventListener("change", () => {
                        const checked = Array.from(inputs).filter((i) => i.checked);

                        // Apply selected / unselected styles
                        cards.forEach((card) => {
                            const checkbox = card.querySelector('input[type="checkbox"]');
                            card.classList.toggle("selected", checkbox.checked);
                            // Clear error state when selection is made
                            card.classList.remove("error");
                        });

                        // Blur unselected if max reached
                        if (checked.length >= maxSelection) {
                            cards.forEach((card) => {
                                const checkbox = card.querySelector(
                                    'input[type="checkbox"]');
                                if (!checkbox.checked) card.classList.add("blurred");
                            });
                        } else {
                            cards.forEach((card) => card.classList.remove("blurred"));
                        }

                        // Save selections to localStorage
                        const selectedValues = checked.map((i) => i.value);
                        localStorage.setItem(storageKey, JSON.stringify(selectedValues));

                        // Clear any existing error messages for this step
                        if (selector.includes('step2')) {
                            clearErrors(1);
                        } else if (selector.includes('step3')) {
                            clearErrors(2);
                        }
                    });
                });
            }

            // Step 2: Business Goals (max 2)
            handleSelectableCards(".step2-wrapper", 2, "selectedBusinessGoals");

            // Step 3: Key Challenges (max 3)
            handleSelectableCards(".step3-wrapper", 3, "selectedKeyChallenges");

            // === Apply max-width rule for long goal-text ===
            function adjustGoalTextWidths() {
                const goalTexts = document.querySelectorAll(".goal-card .goal-text");

                goalTexts.forEach(text => {
                    const wordCount = text.textContent.trim().split(/\s+/).length;

                    if (wordCount > 4) {
                        text.style.maxWidth = "100%";
                    }

                    if (wordCount === 3 || wordCount === 2) {
                        text.style.paddingLeft = "1px";
                        text.style.paddingRight = "1px";
                    }

                });
            }

            // Clear errors when user starts typing in input fields
            document.querySelectorAll('input, textarea').forEach(input => {
                input.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        this.classList.remove('error');
                        const errorMsg = this.parentNode.querySelector('.error-message');
                        if (errorMsg) {
                            errorMsg.remove();
                        }
                    }
                });
            });



            // Initialize phone field with existing value
            function initializePhoneField() {
                const combinedPhone = document.getElementById('combinedPhone');
                const phoneNumberInput = document.getElementById('phoneInput');
                const prefixSpan = document.getElementById('countryPrefix');
                const flagImg = document.getElementById('flagImg');
                const flagPlaceholder = document.getElementById('flagPlaceholder');

                // Only initialize if there's an existing value (like from validation errors)
                if (combinedPhone.value && combinedPhone.value.includes('+')) {
                    const parts = combinedPhone.value.split(' ');
                    const countryCode = parts[0];

                    // Find the corresponding flag item
                    const flagItem = document.querySelector(`[data-code="${countryCode}"]`);
                    if (flagItem) {
                        const flagSrc = flagItem.dataset.flag;

                        // Update UI
                        flagImg.src = flagSrc;
                        flagImg.style.display = "block";
                        flagPlaceholder.style.display = "none";

                        prefixSpan.textContent = countryCode;
                        prefixSpan.style.display = "block";

                        phoneNumberInput.disabled = false;
                        phoneNumberInput.placeholder = "Enter phone number";
                        phoneNumberInput.value = parts.slice(1).join(' ');
                    }
                }
            }



            const multiStepForm = document.getElementById('multiStepForm');
            if (multiStepForm) {
                multiStepForm.addEventListener('submit', updateCombinedPhone);
            }



            // New custom phone field JS

            const flagBox = document.getElementById('flagBox');
            const dropdown = document.getElementById('flagDropdown');
            const flagImg = document.getElementById('flagImg');
            const dropdownIcon = document.getElementById('dropdownIcon');
            const prefixSpan = document.getElementById('countryPrefix');
            const phoneNumberInput = document.getElementById('phoneInput');
            const combinedPhone = document.getElementById('combinedPhone');

            // OPEN / CLOSE DROPDOWN
            flagBox.addEventListener("click", () => {
                const isOpen = dropdown.style.display === "block";
                dropdown.style.display = isOpen ? "none" : "block";
                dropdownIcon.classList.toggle("rotate", !isOpen);

                // Focus search input when opening dropdown
                if (!isOpen) {
                    setTimeout(() => {
                        const searchInput = document.getElementById('flagSearch');
                        if (searchInput) {
                            searchInput.focus();
                            searchInput.value = ''; // Clear previous search
                            filterCountries(''); // Show all countries
                        }
                    }, 100);
                }
            });

            // SELECT COUNTRY
            function setupCountrySelection() {
                document.querySelectorAll(".flag-item").forEach((item, index) => {
                    // Make items focusable
                    item.tabIndex = 0;

                    item.addEventListener("click", (e) => {
                        e.stopPropagation(); // IMPORTANT FIX
                        selectCountry(item);
                    });

                    // Handle keyboard selection
                    item.addEventListener("keydown", (e) => {
                        const visibleItems = Array.from(document.querySelectorAll(
                            '.flag-item:not(.hidden)'));
                        const currentIndex = visibleItems.indexOf(item);

                        // Hide placeholder text
                        const flagPlaceholder = document.getElementById('flagPlaceholder');
                        if (flagPlaceholder) {
                            flagPlaceholder.style.display = "none";
                        }

                        // Show and update prefix
                        prefixSpan.textContent = newCode;
                        prefixSpan.style.display = "block";

                        // Enable phone input and update placeholder
                        phoneNumberInput.disabled = false;
                        phoneNumberInput.placeholder = "Enter phone number";

                        // Remove error styling if present
                        flagBox.classList.remove('error');
                        phoneNumberInput.classList.remove('error');

                        // Update hidden backend field
                        updateCombinedPhone();

                        // Close dropdown immediately
                        dropdown.style.display = "none";
                        dropdownIcon.classList.remove("rotate");

                        updatePhoneBorder(prefixSpan, phoneNumberInput);
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            selectCountry(item);
                        } else if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            const nextIndex = Math.min(currentIndex + 1, visibleItems.length - 1);
                            visibleItems[nextIndex]?.focus();
                        } else if (e.key === 'ArrowUp') {
                            e.preventDefault();
                            if (currentIndex === 0) {
                                // Go back to search input
                                flagSearch?.focus();
                            } else {
                                const prevIndex = Math.max(currentIndex - 1, 0);
                                visibleItems[prevIndex]?.focus();
                            }
                        } else if (e.key === 'Escape') {
                            dropdown.style.display = "none";
                            dropdownIcon.classList.remove("rotate");
                            flagBox.focus();
                        }
                    });
                });
            }

            function selectCountry(item) {
                const newFlag = item.dataset.flag;
                const newCode = item.dataset.code;

                // Update UI
                flagImg.src = newFlag;
                flagImg.style.display = "block";

                // Hide placeholder text
                const flagPlaceholder = document.getElementById('flagPlaceholder');
                if (flagPlaceholder) {
                    flagPlaceholder.style.display = "none";
                }

                // Show and update prefix
                prefixSpan.textContent = newCode;
                prefixSpan.style.display = "block";

                // Enable phone input and update placeholder
                phoneNumberInput.disabled = false;
                phoneNumberInput.placeholder = "Enter phone number";

                // Remove error styling if present
                flagBox.classList.remove('error');
                phoneNumberInput.classList.remove('error');

                // Update hidden backend field
                updateCombinedPhone();

                // Close dropdown immediately
                dropdown.style.display = "none";
                dropdownIcon.classList.remove("rotate");

                // Focus phone input
                phoneNumberInput.focus();
            }

            // Initialize country selection
            setupCountrySelection();


            // SEARCH FUNCTIONALITY
            const flagSearch = document.getElementById('flagSearch');
            if (flagSearch) {
                flagSearch.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    filterCountries(searchTerm);
                });

                // Prevent dropdown from closing when clicking on search input
                flagSearch.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                // Handle keyboard navigation
                flagSearch.addEventListener('keydown', function(e) {
                    const visibleItems = document.querySelectorAll('.flag-item:not(.hidden)');

                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        if (visibleItems.length > 0) {
                            visibleItems[0].focus();
                        }
                    } else if (e.key === 'Escape') {
                        dropdown.style.display = "none";
                        dropdownIcon.classList.remove("rotate");
                        flagBox.focus();
                    }
                });
            }

            // FILTER COUNTRIES FUNCTION
            function filterCountries(searchTerm) {
                const flagItems = document.querySelectorAll('.flag-item');
                let visibleCount = 0;

                // Remove existing no-results message
                const existingNoResults = dropdown.querySelector('.no-results');
                if (existingNoResults) {
                    existingNoResults.remove();
                }

                flagItems.forEach(item => {
                    const countryText = item.textContent.toLowerCase();
                    const countryCode = item.dataset.code.toLowerCase();

                    if (countryText.includes(searchTerm) || countryCode.includes(searchTerm)) {
                        item.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden');
                    }
                });

                // Show "no results" message if no countries match
                if (visibleCount === 0 && searchTerm.trim() !== '') {
                    const noResultsDiv = document.createElement('div');
                    noResultsDiv.className = 'no-results';
                    noResultsDiv.textContent = 'No countries found';
                    dropdown.appendChild(noResultsDiv);
                }
            }

            // CLICK OUTSIDE CLOSES DROPDOWN
            document.addEventListener("click", (e) => {
                if (!flagBox.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = "none";
                    dropdownIcon.classList.remove("rotate");
                }
            });



            // UPDATE HIDDEN FIELD (backend)
            function updateCombinedPhone() {
                const digitsOnly = phoneNumberInput.value.replace(/[^\d]/g, '');
                const countryCode = prefixSpan.textContent;

                if (digitsOnly.length && countryCode && countryCode.trim()) {
                    combinedPhone.value = `${countryCode} ${digitsOnly}`;
                } else {
                    combinedPhone.value = '';
                }
            }

            // UPDATE ON TYPING
            phoneNumberInput.addEventListener('input', updateCombinedPhone);



            phoneNumberInput.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    this.classList.remove('error');
                    const msg = this.closest('.phone-field').parentNode.querySelector('.error-message');
                    if (msg) msg.remove();
                }
            });

            // Clear flag box error when clicked
            flagBox.addEventListener('click', function() {
                if (flagBox.classList.contains('error')) {
                    flagBox.classList.remove('error');
                    const msg = phoneNumberInput.closest('.phone-field').parentNode.querySelector(
                        '.error-message');
                    if (msg) msg.remove();
                }
            });


            //  Initialize
            updateStep();
            adjustGoalTextWidths();
            initializePhoneField();
            updatePhoneBorder(prefixSpan, phoneNumberInput);

        });

        // Live Capture Status Indicator
        document.addEventListener('DOMContentLoaded', function() {
            const statusIndicator = document.getElementById('live-capture-status');

            // Show indicator when live capture is active
            setTimeout(() => {
                if (statusIndicator) {
                    statusIndicator.style.display = 'block';
                    console.log('Live form capture should be active now');
                }
            }, 2000);

            // Hide indicator after 10 seconds
            setTimeout(() => {
                if (statusIndicator) {
                    statusIndicator.style.display = 'none';
                }
            }, 12000);

            // Show indicator briefly when user types
            // document.addEventListener('input', function(e) {
            //     if (statusIndicator && e.target.closest('form')) {
            //         statusIndicator.style.display = 'block';
            //         statusIndicator.innerHTML = '<i class="fa fa-sync fa-spin"></i> Saving Draft...';
            //         statusIndicator.style.background = '#ffc107';

            //         setTimeout(() => {
            //             statusIndicator.innerHTML = '<i class="fa fa-check"></i> Draft Saved';
            //             statusIndicator.style.background = '#28a745';

            //             setTimeout(() => {
            //                 statusIndicator.style.display = 'none';
            //             }, 2000);
            //         }, 1000);
            //     }
            // });
        });
    </script>

</x-live-form-capture>
