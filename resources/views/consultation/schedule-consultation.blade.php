@extends('layouts.master')
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
    <meta property="og:image" content="{{ asset('img/mobile.png') }}">
    <meta property="og:type" content="website">

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>  -->
<style>
    img {
        max-width: 100%;
    }

    .schedule-conversation-section {
        background-image: url(../img/first-bg.png);
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        padding: 2rem 0rem 0rem 0rem;
        min-height: 100vh;
        width: 100%;

        overflow-x: hidden;
    }


    .schedule-conversation-section .intro-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .schedule-conversation-section .left-content {
        padding-top: 4rem;
    }

    .schedule-conversation-section .left-content h3 {
        font-size: 2.1rem;
        color: #0d4036;
        font-family: "GT Walsheim ProCB";
        line-height: 1.1;
        padding: 0 20px 0px 25px;
        max-width: 370px;
    }

    .schedule-conversation-section .left-content .info {
        display: flex;
    }

    .schedule-conversation-section .left-content .para {
        display: none;
    }

    .schedule-conversation-section .left-content .info-content {
        display: none;
        flex-direction: column;
        gap: 5px;
        margin-top: 20px;
    }

    .schedule-conversation-section .left-content .info-content div.btn {
        align-self: start;
    }

    .schedule-conversation-section .left-content .info-content div.btn button {
        background-color: #0d4036;
        color: #fff;
        padding: 0.8rem 1.6rem;
        font-family: "GT Walsheim Con";
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        letter-spacing: 0.6px;
        box-shadow: 0px 12px 15px -3px rgba(0, 0, 0, 0.4);
        outline: none;
        border: none;
    }

    .schedule-conversation-section .left-content .info-content div.btn button div {
        width: 15px;
        height: 15px;
        position: relative;
    }

    .schedule-conversation-section .left-content .info-content div.btn button div img {
        object-fit: cover;
    }

    .schedule-conversation-section .left-content .info-content {
        position: relative;
        left: -40px;
    }

    .schedule-conversation-section .left-content .info-content div.para p {
        color: #0d4036;
        font-family: "GT Walsheim Trial Cn Lt";

        font-size: 1.2rem;
        line-height: 1.2;
        margin-top: 10px;
        margin-right: 20px;
    }



    .schedule-conversation-section .info {
        position: relative;
        display: flex;
        margin-top: 20px;
        width: 100%;
    }

    /* .schedule-conversation-section .left-content .content-pattern {
  width: 120px;
  height: 100px;
  position: relative;
  left: -90px;
} */

    .schedule-conversation-section .left-content .content-pattern {
        display: none;
    }

    .schedule-conversation-section .left-content .schedule-pattern {
        height: 90px;
        width: auto;
        position: absolute;
        right: -7px;
        top: -30px;
    }

    #calendar-wrapper {
        width: 100%;
        padding: 20px 60px;
        min-height: 100vh;
        color: #fff;
        background-color: #06221d;
        display: flex;
        justify-content: center;
        flex-direction: column;
        font-family: "GT Walsheim Con";
        overflow: hidden;
        margin-bottom: 10px;
    }



    .calendar-header h3 {
        margin-bottom: 10px;
        font-family: "GT Walsheim Con";
        font-weight: bold;
        letter-spacing: 1.4px;
        margin-top: 30px;
        font-size: 21px;
    }

    .nav-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .nav-row button {
        background: none;
        border: none;
        color: #d8e3dd;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 30px;
        border-radius: 6px;
    }

    .nav-row button img {
        width: auto;
        height: 15px;
    }

    .month-year-inputs {
        flex: 1;
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: center;
    }

    .month-input-container {
        position: relative;
        flex: 1;
    }

    #monthInput {
        width: 100%;
        background: transparent;
        border: 1px solid #3b4a42;
        border-radius: 6px;
        padding: 8px 10px;
        color: #e7f2ec;
        font-family: "GT Walsheim Con";
        letter-spacing: 1.3px;
        text-align: center;
        font-size: 22px;
    }

    #monthInput:focus {
        border: 1px solid #88c1b750;
        outline: none;
    }

    #monthInput::placeholder {
        color: #fff;
        font-size: 19px;
    }

    #yearInput {
        width: 80px;
        background: transparent;
        border: 1px solid #3b4a42;
        border-radius: 6px;
        padding: 8px 10px;
        color: #e7f2ec;
        font-family: "GT Walsheim Con";
        letter-spacing: 1.3px;
        text-align: center;
        font-size: 22px;

        /* Remove spinner in Firefox */
        -moz-appearance: textfield;
    }

    /* Remove arrows in Chrome/Safari/Edge */
    #yearInput::-webkit-outer-spin-button,
    #yearInput::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }


    #yearInput:focus {
        border: 1px solid #88c1b750;
        outline: none;
    }

    #yearInput::placeholder {
        color: #fff;
        font-size: 19px;
    }

    .month-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: linear-gradient(180deg, rgba(16, 34, 28, 1), rgba(10, 22, 18, 1));
        border-radius: 6px;
        border: 1px solid rgba(60, 95, 81, 0.3);
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .month-suggestion {
        padding: 8px 12px;
        color: #e7f2ec;
        cursor: pointer;
        font-family: "GT Walsheim Con";
        font-size: 16px;
        transition: background 0.2s ease;
    }

    .month-suggestion:hover,
    .month-suggestion.highlighted {
        background: rgba(136, 193, 183, 0.2);
    }

    .month-suggestion.selected {
        background: rgba(136, 193, 183, 0.3);
        color: #88C1B7;
    }

    #calendar-grid {
        margin-top: 20px;
    }

    .weekdays {
        /* display: inline-block; */
        width: 100%;
        display: flex;
        /* text-align: center; */
        font-family: "GT Walsheim Pro";
        margin-bottom: 10px;
        color: #4F4F4F;
        /* opacity: 0.8; */
    }

    .weekday {
        /* display: inline-block; */
        width: 14%;
        text-align: center;
        /* margin-bottom: 10px; */
        opacity: 0.8;
    }

    #dates {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
    }


    .date {
        padding: 10px 0;
        text-align: center;
        cursor: pointer;
        background: #0d403666;
        /* border: 1px solid #28352f; */
        color: #f2f2f2 !important;
    }


    .date.other-month {
        color: #808080 !important;
        opacity: 0.6;
    }

    .date.today {
        background: #88C1B7;
        color: #0D4036 !important;
    }

    .date.disabled {
        color: #808080 !important;
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* .date.disabled:hover {
        background: #1a1a1a !important;
        cursor: not-allowed;
    } */

    .date.selected {
        background: #88C1B7;
        color: #0D4036 !important;
    }

    .selected-date,
    .selected-time {
        margin-top: -1rem;
        font-family: "GT Walsheim Con";
        font-weight: 100;
        opacity: 0.76;


    }

    .time-box {
        margin-top: 60px;
        padding: 15px;
        background: #0e302a;
        border-radius: 10px;
    }

    .timezone-row {
        display: flex;
        gap: 30px;
        justify-content: space-between;
        margin-bottom: 4px;
    }

    .timezone-row>label {
        font-size: 19px;
        margin-top: 0.15rem;
        padding-left: 4px;
    }

    .time-restriction-notice {
        background: rgba(136, 193, 183, 0.1);
        border: 1px solid rgba(136, 193, 183, 0.3);
        border-radius: 6px;
        padding: 8px 12px;
        margin-top: 10px;
    }

    .time-restriction-notice p {
        color: #88C1B7;
        font-size: 14px;
        margin: 0;
        font-family: "GT Walsheim Con";
        text-align: center;
    }

    select {
        background: #1a2c25;
        color: #e7f2ec;
        border: 1px solid #3b4a42;
        padding: 6px 8px;
        border-radius: 6px;
    }

    .time-select-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 20px;
    }

    .submit-schedule {
        display: flex;
        align-items: center;
        justify-content: center;

    }

    .output-box {
        margin-top: 30px;
        line-height: 1.5;
        letter-spacing: 1.3px;
        font-size: 0.8rem;
        font-family: "GT Walsheim Con";
    }

    .output-box.desktop {
        margin-top: 30px;
        line-height: 1.5;
        font-family: "GT Walsheim Con";
        display: none;
    }

    .calendar-wrapper-container .mobile-btn {
        margin-top: 30px;

    }

    .calendar-wrapper-container .mobile-btn button {
        background-color: #ffffff;
        color: #0d4036;
        padding: 0.8rem 1.6rem;
        font-family: "GT Walsheim Con";
        text-decoration: none;

        text-align: center;
        gap: 20px;
        letter-spacing: 0.6px;
        box-shadow: 0px 12px 15px -3px rgba(0, 0, 0, 0.4);
        outline: none;
        border: none;
        font-size: 0.9rem;
        width: 70%;
        margin: 0 auto;
        margin-bottom: 2rem;
        margin-left: -0.3rem;
    }

    .calendar-wrapper-container .mobile-btn button img {
        width: 12px;
        height: 12px;
    }

   
       /* ====================================== CALL FOR HELP */
    .call-for-help {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        justify-content: end;
    }

    .call-for-help .callforhelp-container {
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: "GT Walsheim Trial Cn Lt";
        gap: 10px;
        /* position: fixed;
        bottom: 70px; */
    }

        .call-for-help .callforhelp-container:hover {
        transform: translateY(-2px);
    }


    .call-for-help .callforhelp-container button {
        background-color: #CCED60;
        width: 60px;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        border: none;
        border-radius: 50px;
        outline: none;
    }

      .call-for-help .callforhelp-container button:hover {
        background-color: #D4F26A;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        transform: scale(1.05);
    }

    .call-for-help .callforhelp-container button div img {
        object-fit: cover;
        width: auto;
        height: 30px;
    }

    .callforhelp-container p {
        font-size: 0.9rem;
        margin-top: 10px;
        letter-spacing: 0.9px;
         color: #0d4036;
        padding: 2px 5px;
        border-radius: 10px;
        background: #eee;
    }


      /* Responsive positioning for floating chat */
    @media (max-width: 767px) {
        .call-for-help {
            bottom: 15px;
            right: 15px;
        }

        .call-for-help .callforhelp-container button {
            width: 55px;
            height: 55px;
        }

        .call-for-help .callforhelp-container button div img {
            height: 25px;
        }

        .callforhelp-container p {
            font-size: 0.8rem;
            margin-right: 6px;
        }
    }

    @media (min-width: 768px) {
        .call-for-help {
            bottom: 25px;
            right: 25px;
        }
    }


    /* Overlay */
    .chat-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(0, 0, 0, 0.25);
        opacity: 0;
        visibility: hidden;
        transition: 0.3s ease;
        z-index: 1500;
    }

    .chat-overlay.show {
        opacity: 1;
        visibility: visible;
    }


    /* Slide-in Panel */
    .chat-panel {
        position: fixed;
        top: 0;
        right: -420px;
        /* transform: translateX(1000px); */
        width: 100%;
        background: transparent;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: end;
        flex-direction: column;

        opacity: 0;
        visibility: hidden;

        z-index: 2000;


        transition: right 0.35s ease-in-out;

    }

    .chat-panel .chat-panel-container {
        width: 350px;
        background: transparent;
        box-shadow: -4px 0 18px rgba(0, 0, 0, 0.18);
        background-color: #0d4036;
        border-radius: 20px;


    }

    .chat-panel-container .panel-container {
        z-index: 2;
        display: flex;
        justify-content: center;
        flex-direction: column;
        position: relative;
        height: 82vh;
        z-index: 1;
        background: #fff;
        border-radius: 40px;

    }

    .panel-container img.chat-inset-pattern {
        position: absolute;
        width: auto;
        max-width: 100%;
        /* height: 100%; */
        top: 0px;
        object-fit: contain;
        z-index: 1;
        border-top-right-radius: 20px;
        border-top-left-radius: 20px;
    }

    .chat-header,
    .chat-body,
    .chat-input-container {
        position: relative;
        z-index: 0;
        /* background:  */
    }


    .chat-panel.open {
        right: 0;
        opacity: 1;
        visibility: visible;

    }

    /* Header */
    .chat-header {
        padding-top: 40px;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        background: transparent;
        position: relative;
        z-index: 4;
    }

    .close-chat-btn {
        background: none;
        border: none;
        color: #fff;
        font-size: 22px;
        cursor: pointer;
    }


    .chat-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: transparent;
        border-top-left-radius: 60px;


    }

    .chat-info .chat-info-left-content {
        display: flex;
        align-items: center;
        color: #0D4036;
        margin-left: 25px;
        margin-top: 20px;
        gap: 9px;


    }

    .chat-info-left-content .avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: #0D4036;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .chat-info-left-content .avatar img {
        width: auto;
        height: 10px;
        ;
    }

    .chat-info-left-content .avatar .active {
        position: absolute;
        top: 0px;
        right: -2px;
        width: 10px;
        height: 10px;
        border-radius: 50px;
        background: #2CD931;
    }

    .chat-info .chat-info-left-content img.arr-left {
        object-fit: cover;
        widows: auto;
        height: 13px;
        margin-right: 10px;
    }


    .chat-info .chat-info-right-content {
        display: flex;
        align-items: center;
        color: #0D4036;
        background-color: transparent;
        padding: 10px;
        position: absolute;
        right: 5px;
        z-index: 3;
    }


    .chat-info-right-content .call-info-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 37px;
        height: 37px;
        border-radius: 50px;
        background-color: #fff;
    }

    .call-info-icon img {
        object-fit: cover;
        width: auto;
        height: 14px;

    }

    .chat-info h3 {
        margin: 0;
        font-family: "GT Walsheim Trial Cn Md";
        font-weight: bold;
        color: #0D4036;
        line-height: 0.9;
        font-size: 1.2rem;
        letter-spacing: 0.8px;
    }

    .chat-info .ct-cont small {
        font-family: "GT Walsheim Trial Cn Md";
        font-weight: 100;
        letter-spacing: 0.8px;
        font-size: 0.82rem;
    }

    /* Chat body */
    .chat-body {
        padding: 20px;
        padding-top: 5rem;
        flex: 1;
        overflow-y: auto;
        background: #ffff;

    }


    .msg {
        width: fit-content;
        max-width: 80%;
        padding: 10px 15px;
        margin-bottom: 10px;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.4;
    }

    /* Bot Message */
    .msg.bot {
        background: #06221D;
        color: #fff;
        border-radius: 0px;
        font-size: 13px;
        font-family: "GT Walsheim Trial Cn Lt";
        font-weight: lighter;
        /* padding-right: 44px; */


        border-bottom-left-radius: 15px;

        border-top-right-radius: 15px;
        border-bottom-right-radius: 15px;
        border-bottom-left-radius: 15px;
    }

    .msg.bot span{
        opacity: 0.96;
        letter-spacing: 0.9px;


    }

    /* User Message */
    .msg.user {
        padding: 11px 14px;
        background: #D1F0EB;
        border-radius: 0px;

        border-bottom-left-radius: 15px;

        border-top-right-radius: 0px;
        border-bottom-right-radius: 15px;
        border-bottom-left-radius: 15px;
        border-top-left-radius: 15px;
        margin-left: auto;

        color: #83818C;
        font-family: "GT Walsheim Trial Cn Md";
        font-weight: 600;

        font-size: 0.8rem;
        letter-spacing: 1px;
    }

    .msg.longtext {
        min-width: 180px;
        padding: 14px 14px;


    }


    /* Input */
    .chat-input-container {
        padding: 12px;
        display: flex;
        /* justify-content: center; */
        gap: 10px;
        background: #ffff;
        border: none;
        border-bottom-right-radius: 20px;
        border-bottom-left-radius: 20px;
    }

    .chat-input-container .input-container {
        box-shadow: 0px 2px 6px -2px rgba(0, 0, 0, 0.5);
        margin: 0 auto;
        background: #ffff;
        width: 100%;
        padding: 6px 0px;
        padding-left: 20px;
        border-radius: 17px;
        /* max-width: 100%; */

    }

    .chat-input-container input {
        padding: 5px 14px;
        height: 18px;
        border-radius: 0px;

        outline: none;
        border: none;
        width: 80%;
        border-left: 1px solid #E4E4E4;
        color: #0D4036;
    }

    .chat-input-container input::placeholder {
        color: #605E6C;
        letter-spacing: 0.9px;
        font-family: "GT Walsheim Trial Cn Lt";

    }

    .send-btn {
        border: none;
        background: transparent;
        color: #fff;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        cursor: pointer;
    }

    .send-btn img {
        object-fit: cover;
        width: auto;
        height: 18px;
    }





    /* Wrapper for tooltip */
    .prev-wrapper {
        position: relative;
        display: inline-block;
    }

    /* Blur disabled state for previous button */
    #prevMonth.disabled-prev {
        filter: blur(2px);
        opacity: 0.3;
        cursor: not-allowed;
    }

    /* Tooltip styling */
    .prev-tooltip {
        position: absolute;
        bottom: -40px;
        left: 50%;
        transform: translateX(-50%);
        background: #1c312b;
        color: #cce7dd;
        font-size: 12px;
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #2d4c40;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease-out;
        z-index: 20;
    }

    /* Tooltip visible */
    .prev-tooltip.show {
        opacity: 1;
    }

    /* Tooltip small arrow */
    .prev-tooltip::after {
        content: "";
        position: absolute;
        top: -6px;
        left: 50%;
        transform: translateX(-50%);
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-bottom: 6px solid #1c312b;
    }

    /* MODAL OVERLAY */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #5656569d;
        background-blend-mode: overlay;
        /* <-- transparent white */
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    /* MODAL BOX */
    .modal-box {
        background-image: url(../img/first-bg.png);
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;

        padding: 60px 0px;
        width: 350px;
        border-radius: 14px;
        color: #0D4036;
        text-align: center;
        animation: fadeInUp 0.35s ease-out;
        font-family: "GT Walsheim Con";

        width: 80vw;
    }

    .modal-box h3 {
        margin-bottom: 15px;
        color: #0D4036;
        font-family: "GT Walsheim Con";
        font-size: 1.45rem;
        text-transform: capitalize;
        font-weight: bold;

    }

    .modal-box p {
        font-size: 0.9rem;
        max-width: 240px;
        margin: 30px auto;
        font-family: "GT Walsheim Trial Cn Lt";


        line-height: 1.26;
        /* margin-bottom: 30px; */
    }

    .modal-box h4 {
        display: none
    }

    .modal-box a {
        display: block;
        text-decoration: none;
        width: 60%;
        margin: 0 auto;
        margin-bottom: 8px;
        padding: 0.8rem 0rem;
        font-size: 0.7rem;
        letter-spacing: 0.8px;
        font-family: "GT Walsheim Pro";
        border-radius: 6px;



    }

    .modal-box .submit-btn-modal {
        background-color: #0D4036;
        color: #fff;

    }

    .modal-box .submit-btn-modal:last-of-type {
        background-color: transparent;
        border: 1px solid #DCDEE0;
        color: #000;
        margin-top: 20px;

    }

    .close-btn:hover {
        background: #25443b;
    }

    /* ANIMATION */
    @keyframes fadeInUp {
        0% {
            transform: translateY(20px);
            opacity: 0;
        }

        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* ---------- Custom Dropdown (Style A - floating macOS) ---------- */
    .custom-dropdown {
        position: relative;
        display: inline-block;
        min-width: 14px;
        /* border: 1px solid red; */
        border-radius: 10px;
        overflow: visible;
        margin-right: 8px;
        outline: none;
        vertical-align: middle;
    }

    .custom-dropdown.timeZone {
        width: 150px;
    }

    /* Toggle button */
    .custom-dropdown .cd-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 8px 10px;
        min-width: 64px;
        background: transparent;
        border: none;
        color: #e7f2ec;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 500;
        backdrop-filter: blur(4px);
        transition: transform 160ms ease, box-shadow 160ms ease;
        /* box-shadow: 0 2px 8px rgba(2, 8, 6, 0.25); */
    }

    .custom-dropdown.timeZone .cd-toggle {
        width: 100%;
        border: 1px solid #8b8b8bef;
        border-radius: 6px;
    }

    /* Hover / focus */
    .custom-dropdown .cd-toggle:hover,
    .custom-dropdown:focus-within .cd-toggle {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(2, 8, 6, 0.35);
    }

    /* Selected text & caret */
    .cd-selected {
        font-size: 14px;
        font-family: "GT Walsheim Trial Cn Md";
        font-weight: 600;
        color: #fff;
        letter-spacing: 0.8px;

    }

    .cd-caret {
        font-size: 14px;
    }

    .cd-caret img {
        width: auto;
        height: 7px;
        transition: transform 0.25s ease;

    }



    .cd-toggle.rotated img {
        transform: rotate(180deg);
    }

    /* Floating list */
    .custom-dropdown .cd-list {
        position: absolute;
        top: calc(100% + 10px);
        left: 50%;
        transform: translateX(-50%) translateY(6px);
        min-width: 120%;
        max-height: 220px;
        overflow: auto;
        background: linear-gradient(180deg, rgba(16, 34, 28, 1), rgba(10, 22, 18, 1));
        border-radius: 12px;
        padding: 8px 6px;
        margin: 0;
        list-style: none;
        border: 1px solid rgba(60, 95, 81, 0.12);
        opacity: 0;
        pointer-events: none;
        box-shadow: 0 10px 30px rgba(3, 10, 8, 0.6);
        transform-origin: top center;
        transition: opacity 180ms ease, transform 200ms cubic-bezier(0.2, 0.9, 0.2, 1);
        z-index: 60;
    }

    /* When opened */
    .custom-dropdown.open .cd-list {
        opacity: 1;
        pointer-events: auto;
        transform: translateX(-50%) translateY(0) scale(1);
    }

    /* Option styles */
    .cd-option {
        padding: 8px 12px;
        margin: 4px 6px;
        border-radius: 8px;
        cursor: pointer;
        color: #d9efe4;
        font-size: 14px;
        transition: background 120ms ease, transform 120ms ease;
        white-space: nowrap;
    }

    /* Hover / focused option */
    .cd-option:hover,
    .cd-option.focused {
        background: rgba(65, 110, 92, 0.12);
        transform: translateX(6px);
    }

    /* selected option highlight */
    .cd-option.selected {
        background: rgba(96, 170, 135, 0.16);
        border-left: 3px solid rgba(96, 170, 135, 0.28);
    }

    /* scrollbar (subtle) */
    .custom-dropdown .cd-list::-webkit-scrollbar {
        width: 8px;
    }

    .custom-dropdown .cd-list::-webkit-scrollbar-thumb {
        background: rgba(20, 40, 35, 0.4);
        border-radius: 8px;
    }

    /* time separators */
    .time-sep {
        font-size: 18px;
        margin: 0 6px;
        color: #bcdacb;
        opacity: 0.9;
        vertical-align: middle;
    }

    /* Scrollable Time Picker Styles */
    .scroll-time-picker {
        display: flex;
        justify-content: center;
        align-items: center;
        /* gap: 20px; */
        margin: 0px 0;
        border-radius: 12px;
        /* border: 1px solid rgba(136, 193, 183, 0.2); */
    }

    .time-wheel-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    .time-wheel-container.ampm {
        /* margin-left: 10px; */
    }

    .time-wheel-header {
        font-size: 24px;
        font-weight: bold;
        color: #88C1B7;
        margin-bottom: 10px;
        font-family: "GT Walsheim Con";
        min-width: 40px;
        text-align: center;
    }

    .time-wheel {
        width: 60px;
        height: 120px;
        overflow: hidden;
        position: relative;
        /* background: rgba(6, 34, 29, 0.6); */
        border-radius: 8px;
        /* border: 1px solid rgba(136, 193, 183, 0.3); */
    }

    .time-wheel-container.ampm .time-wheel {
        width: 50px;
    }

    .time-wheel-list {
        position: relative;
        transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        padding: 40px 0;
    }

    .time-wheel-item {
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #4F4F4F;
        font-family: "GT Walsheim Con";
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }

    .time-wheel-item.active {
        color: #fff;
        font-weight: bold;
        font-size: 18px;
        /* background: rgba(136, 193, 183, 0.1); */
    }

    .time-wheel-item:hover {
        /* color: rgba(136, 193, 183, 0.8); */
    }

    /* Selection indicators */
    .time-wheel::before,
    .time-wheel::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        height: 1px;
        background: rgba(136, 193, 183, 0.3);
        z-index: 2;
    }

    .time-wheel::before {
        top: 40px;
    }

    .time-wheel::after {
        bottom: 40px;
    }

    /* Time Display */
    .time-display {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
        padding: 15px 20px;
        background: rgba(6, 34, 29, 0.8);
        border-radius: 8px;
        border: 1px solid rgba(136, 193, 183, 0.3);
    }

    #display-time {
        font-size: 20px;
        font-weight: bold;
        color: #88C1B7;
        font-family: "GT Walsheim Con";
        letter-spacing: 2px;
    }

    #display-period {
        font-size: 18px;
        color: #88C1B7;
        font-family: "GT Walsheim Con";
        margin-left: 5px;
        font-weight: bold;
    }

    /* ensure modal/backdrop etc remain as previous styles provided earlier */

    /* FLASH MESSAGE SYSTEM */
    #flashContainer {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        pointer-events: none;
        z-index: 99999;
    }

    .flash-msg {
        position: absolute;
        background: #1c2f29;
        color: #dff6ea;
        border: 1px solid #274c3e;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 13px;
        opacity: 0;
        transform: translate(50px, -8px);
        transition: opacity 0.35s ease, transform 0.35s ease;
        pointer-events: none;
        white-space: nowrap;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
    }

    .flash-msg.show {
        opacity: 1;
        transform: translateY(0);
    }

    .flash-msg.hide {
        opacity: 0;
        transform: translateY(-8px);
    }

    @media (max-width: 767px) {
        .month-year-inputs {
            gap: 8px;
        }

        #monthInput {
            font-size: 18px;
        }

        #yearInput {
            width: 70px;
            font-size: 18px;
        }

        .month-suggestions {
            font-size: 14px;
        }

        .month-suggestion {
            padding: 6px 10px;
            font-size: 14px;
        }
    }

    @media (min-width: 768px) {
        .schedule-conversation-section .left-content h3 {
            font-size: 2.7rem;
            padding-left: 40px;

            max-width: 650px;
        }

        .schedule-conversation-section .left-content .info-content {
            /* max-width: 500px; */
        }

        .schedule-conversation-section .left-content .info-content div.para p {
            font-size: 1.5rem;
        }

            .calendar-wrapper-container .mobile-btn {
            width: 50%;
        }
    }

    @media (min-width: 1024px) {
        .schedule-conversation-section {
            padding: 0rem;
        }

        .schedule-conversation-section .intro-container {
            flex-direction: column;
        }

        .schedule-conversation-section .left-content {
            padding-top: 7rem;
        }

        .schedule-conversation-section .left-content h3 {
            font-size: 3.6rem;
        }

        .schedule-conversation-section .left-content .content-pattern {
            display: none;
            /* transf
    orm: scale(1.2); */
        }

        .schedule-conversation-section .left-content .schedule-pattern {
            display: block;
        }
    }

    @media (min-width: 1200px) {
        .schedule-conversation-section .intro-container {
            flex-direction: row;
            gap: 80px;
        }


        #calendar-wrapper {
            padding-top: 5rem;
        }

        .calendar-wrapper-container .mobile-btn {
            display: none;
        }

        .schedule-conversation-section .left-content .para {
            display: block;
        }

        .schedule-conversation-section .left-content {
            padding-top: 10rem;
        }

        .schedule-conversation-section .left-content h3 {
            font-size: 4rem;
        }

        .left-content .para {
            max-width: 355px;
            margin-left: 7rem;
            font-family: "GT Walsheim Con";

            color: #0D4036;
            margin-top: -1rem;

        }

        .left-content .para p {
            font-family: "GT Walsheim Trial Cn Lt";

            font-size: 19px;
            line-height: 1;

        }

        .schedule-conversation-section .left-content .schedule-pattern {
            display: none;
        }
    }

    @media (min-width: 1400px) {
        .schedule-conversation-section .intro-container {
            gap: 30px;
        }

        .schedule-conversation-section .left-content {
            padding-top: 17rem;

        }

        .schedule-conversation-section .left-content h3 {
            font-size: 4.8rem;
            max-width: initial;
            margin-left: 60px;
            margin-bottom: 30px;
            width: 560px;
            line-height: 0.9;
        }

        .schedule-conversation-section .left-content .info-content {
            position: relative;
            left: 18px;
            margin-top: 0px;
            max-width: initial;
            display: flex;
        }

        .schedule-conversation-section .left-content .info-content div.para p {
            font-size: 1.3rem;
            max-width: 260px;
            margin-top: 20px;
        }

        .schedule-conversation-section .left-content .info-content div.btn {
            border: none;
            margin-left: 7rem;

        }

        .schedule-conversation-section .left-content .info-content div.btn button {
            padding: 0.7rem 3rem;
        }

        .schedule-conversation-section .left-content .content-pattern {
            width: 150px;
            height: 105px;
            position: relative;
            left: -8px;
            line-height: 1;
            display: block;
            /* transform: scale(1.2); */
        }

        .schedule-conversation-section .desktop-image-cont img.desktop {
            height: 100%;
            object-fit: cover;
            display: block;
            /* margin: -70px 0px 0px -50px; */
            position: relative;

            /* object-position: top; */
        }

        .calendar-wrapper-container {
            max-width: 600px;
            /* border: 1px solid; */
            margin: 0 auto;
            width: 100%;
            /* display: non; */
        }

        .calendar-header h3 {

            font-size: 24px;
        }

        #calendar-wrapper {

            margin-bottom: 0px;
        }

        #calendar-grid {
            /* max-width: 550px; */
            /* margin: 0 auto; */
            /* margin-right: 10rem; */
            padding: 0;

        }

        #dates {
            gap: 10px;
            margin-left: 2.5rem;
            /* max-width: 550px; */


        }


        .timezone-row {
            gap: 80px;
        }

        .date {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px 4px;
            margin-bottom: -4px !important;
            height: auto;
            width: 35px;
            margin: 0;
            box-sizing: border-box;
            font-size: 0.8rem !important;

        }

        .weekdays {
            gap: 0px;
            margin-bottom: 1rem;
            transform: translate(-20px);
            margin-left: 2rem;
            font-size: 0.9rem;
            max-width: 760px;

        }

        .nav-row {
            margin-block: 30px;
            margin-right: 0rem;
            margin-top: 1rem;

        }

        .month-year-inputs {
            gap: 15px;
        }

        #yearInput {
            width: 100px;
        }

        .time-box {
            /* margin: 0px 40px; */
            /* margin-top: 50px; */
            max-width: 650px;
            margin: 0 auto;
            background-color: transparent;
            display: flex;
            /* margin-top: 2rem; */
            margin-bottom: 5rem;
            margin-left: 2rem;
            margin-top: 2.2rem;



        }

        .time-box-container {
            background-color: #0d403666;
            padding: 12px 10px;
            padding-bottom: 7px;
            border-radius: 10px;
        }

        .calendar-wrapper-container .output-box.mobile {
            display: none;
        }

        .calendar-wrapper-container .output-box.desktop {
            display: block;
            padding: 0px 40px;
        }



        .modal-box {
            width: 53vw;

        }

        .modal-box h3 {

            font-size: 2rem;


        }

        .modal-box p {
            font-size: 1.2rem;
            max-width: 340px;

        }






        .modal-box .submit-btn-modal:last-of-type {

            margin-top: 20px;

        }

          .panel-container img.chat-inset-pattern {
           right: 0.3px;
    }



    }
</style>
@section('content')


    <section class="schedule-conversation-section">
        <div class="box-container">
            <div class="intro-container">
                <div class="left-content">
                    <h3>Schedule Your Consultation</h3>

                    <div class="para">
                        <p>
                            Pick a convenient date and time for your session. You’ll
                            receive a confirmation email once it’s booked.
                        </p>
                    </div>

                    <div class="info">
                        <img src="{{ asset('img/left-conv-pattern.png') }}" class="content-pattern" alt="" />
                        <img src="{{ asset('img/schedule-pattern.png') }}" class="schedule-pattern" alt="" />
                        <div class="info-content">
                            <!-- SUBMIT BUTTON -->

                            <div class="btn">
                                <button class="submit-schedule">
                                    Submit
                                    <div><img src="{{ asset('img/right.png') }}" alt="" /></div>
                                </button>
                            </div>


                        </div>
                    </div>
                </div>

                <!-- CALENDAR AREA -->
                <!-- <div class="right-content"> -->
                <div id="calendar-wrapper">
                    <div class="calendar-wrapper-container">
                        <div class="calendar-header">
                            <h3>Select Day</h3>

                            {{--  --}}
                            <div class="nav-row">
                                <div class="prev-wrapper">
                                    <button id="prevMonth">
                                        <img src="{{ asset('img/left.png') }}" alt="" />
                                    </button>
                                    <div class="prev-tooltip">
                                        You cannot go before the current month
                                    </div>
                                </div>

                                <div class="month-year-inputs">
                                    <div class="month-input-container">
                                        <input type="text" id="monthInput" placeholder="November" />
                                        <div class="month-suggestions" id="monthSuggestions"></div>
                                    </div>
                                    <input type="number" id="yearInput" placeholder="2025" min="2025" max="2050" />
                                </div>

                                <button id="nextMonth">
                                    <img src="{{ asset('img/right-arrow.png') }}" alt="" />
                                </button>
                            </div>
                        </div>

                        <div id="calendar-grid">
                            <div class="weekdays">
                                <div class="weekday">Mon</div>
                                <div class="weekday">Tue</div>
                                <div class="weekday">Wed</div>
                                <div class="weekday">Thu</div>
                                <div class="weekday">Fri</div>
                                <div class="weekday">Sat</div>
                                <div class="weekday">Sun</div>
                            </div>

                            <div id="dates"></div>
                        </div>

                        <div class="output-box mobile">
                            <p><strong>Selected Date:</strong></p>
                            <p class="selected-date">—</p>
                        </div>

                        <!-- TIME AREA  -->
                        <div class="time-box">
                            <div class="time-box-container">
                                <div class="timezone-row">
                                    <label>Set time</label>
                                    <div class="custom-dropdown timeZone" id="dropdown-timezone" tabindex="0"
                                        role="listbox" aria-label="Timezone">
                                        <button class="cd-toggle" aria-haspopup="listbox" aria-expanded="false">
                                            <span class="cd-selected">GMT</span>
                                            <span class="cd-caret">
                                                <img src="{{ asset('img/arrow-up-01.png') }}" alt="" />
                                            </span>
                                        </button>
                                        <ul class="cd-list" role="listbox" aria-hidden="true">
                                            <li role="option" data-value="GMT" class="cd-option">GMT</li>
                                            <li role="option" data-value="UTC" class="cd-option">UTC</li>
                                            <li role="option" data-value="WAT" class="cd-option">WAT</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="time-restriction-notice" id="timeRestrictionNotice" style="display: none;">
                                    <p>⏰ For today's date, only times at least 1 hour ahead are available</p>
                                </div>

                                <!-- Scrollable Time Picker -->
                                <div class="scroll-time-picker">
                                    <!-- Hour Picker -->
                                    <div class="time-wheel-container">
                                        <div class="time-wheel" id="hour-wheel">
                                            <div class="time-wheel-list">
                                                <!-- Hours will be populated by JavaScript -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Minute Picker -->
                                    <div class="time-wheel-container">
                                        <div class="time-wheel" id="minute-wheel">
                                            <div class="time-wheel-list">
                                                <!-- Minutes will be populated by JavaScript -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Second Picker -->
                                    <div class="time-wheel-container">
                                        <div class="time-wheel" id="second-wheel">
                                            <div class="time-wheel-list">
                                                <!-- Seconds will be populated by JavaScript -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- AM/PM Picker -->
                                    <div class="time-wheel-container ampm">
                                        <div class="time-wheel" id="ampm-wheel">
                                            <div class="time-wheel-list">
                                                <div class="time-wheel-item" data-value="AM">AM</div>
                                                <div class="time-wheel-item" data-value="PM">PM</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SELECTED OUTPUT -->
                            <div class="output-box desktop">
                                <p><strong>Selected Date:</strong></p>
                                <p class="selected-date">—</p>

                                <p style="margin-top: 10px">
                                    <strong>Selected Time:</strong>
                                </p>
                                <p class="selected-time">—</p>
                            </div>
                        </div>

                        <div class="output-box mobile">
                            <p style="margin-top: 10px"><strong>Selected Time:</strong></p>
                            <p class="selected-time">—</p>
                        </div>

                        <div class="mobile-btn">
                            <button class="submit-schedule">
                                Submit
                                <div><img src="{{ asset('img/right-colored-r.png') }}" alt="" /></div>
                            </button>
                        </div>

                        <div class="call-for-help">

                            <div class="callforhelp-container">
                                <p>Call For Help</p>
                                <button>
                                    <div><img src="{{ asset('img/customer-service.png') }}" alt="" /></div>
                                </button>
                            </div>
                        </div>

                        <!-- CHAT MODAL OVERLAY -->
                        <div id="chat-overlay" class="chat-overlay"></div>

                        <!-- CHAT SLIDE-IN PANEL -->
                        <div id="chat-panel" class="chat-panel">

                            <div class="chat-panel-container">


                                <div class="panel-container">

                                  
                                    <img class="chat-inset-pattern" src="{{ asset('img/side-chat-pattern.png') }}"
                                        alt="" />
                                    <div class="chat-header">


                                        <div class="chat-info">
                                            <div class="chat-info-left-content">
                                                <img class="arr-left" src="{{ asset('img/arrow-left.png') }}"
                                                    alt="" />
                                                <div class="avatar">
                                                    <div class="active"></div>
                                                    <img src="{{ asset('images/logo.png') }}" alt="" />
                                                </div>
                                                <div class="ct-cont">
                                                    <h3>P23 Africa</h3>
                                                    <small>Online</small>
                                                </div>
                                            </div>
                                            <div class="chat-info-right-content">
                                                <div class="call-info-icon">
                                                    <img src="{{ asset('img/phone.png') }}" alt="" />
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="chat-body" id="chat-body">
                                          <div class="msg bot">
                                            <span>
                                                Hey 👋
                                            </span>
                                        </div>
                                        <div class="msg bot">
                                            <span>
                                                Welcome to P23 Africa, what can we help you with?
                                            </span>

                                        </div>
                                    </div>

                                    <div class="chat-input-container">
                                        <div class="input-container">
                                            <input id="chat-input" placeholder="Type your message here"
                                                maxlength="1000" />
                                            <button class="send-btn" id="send-btn">
                                                <img class="arr-left" src="{{ asset('img/send-icon.png') }}"
                                                    alt="" />
                                            </button>
                                        </div>
                                    </div>

                                </div>

                            </div>


                        </div>


                        <!-- Hidden form for actual submission -->
                        <form id="scheduleForm" method="POST" action="{{ route('consultation.schedule.submit') }}"
                            style="display: none;">
                            @csrf
                            <input type="hidden" name="session_date" id="hiddenDate">
                            <input type="hidden" name="session_time" id="hiddenTime">
                            <input type="hidden" name="timezone" id="hiddenTimezone">
                        </form>
                    </div>
                </div>
                <!-- </div> -->
            </div>
        </div>

        <!-- POPUP MODAL -->
        <div id="scheduleModal" class="modal-overlay">
            <div class="modal-box">
                <h3>consultation confirmed!</h3>
                <p>A confirmation email containing the date, time, and Google Meet link has been sent to your registered
                    email address.</p>
                <h4 id="modalDateText"></h4>
                <h4 id="modalTimeText"></h4>

                <a href="#" class="submit-btn-modal">
                    Open customer@example.com
                </a>
                <a href="{{ route('consultation.index') }}" class="submit-btn-modal">
                    Return to Homepage
                </a>
            </div>
        </div>
    </section>

    <!-- FLASH MESSAGE CONTAINER -->
    <div id="flashContainer"></div>
@endsection


<script>
    document.addEventListener("DOMContentLoaded", () => {

        /* ------------------------- Elements ------------------------- */
        const datesContainer = document.getElementById("dates");
        const monthInput = document.getElementById("monthInput");
        const yearInput = document.getElementById("yearInput");
        const monthSuggestions = document.getElementById("monthSuggestions");
        // const selectedDateText = document.getElementById("selectedDate");
        // const selectedTimeText = document.getElementById("selectedTime");

        const prevBtn = document.getElementById("prevMonth");
        const prevTooltip = document.querySelector(".prev-tooltip");

        /* Modal elements */
        // const submitBtn = document.getElementById("submitSchedule");
        const modal = document.getElementById("scheduleModal");
        const modalDateText = document.getElementById("modalDateText");
        const modalTimeText = document.getElementById("modalTimeText");
        const closeModal = document.getElementById("closeModal");

        /* ------------------------- Date limits & state ------------------------- */
        const today = new Date();
        const minYear = today.getFullYear();
        const minMonth = today.getMonth(); // 0-indexed
        let selectedDate = null;
        let current = new Date(minYear, minMonth);

        /* ------------------------- Month autocomplete data ------------------------- */
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        let highlightedIndex = -1;



        function updateAllSelectedDate(dateString) {
            document.querySelectorAll(".selected-date").forEach(el => {
                el.textContent = dateString;
            });
        }

        function updateAllSelectedTime(timeString) {
            document.querySelectorAll(".selected-time").forEach(el => {
                el.textContent = timeString;
            });
        }

        /* ------------------------- Month autocomplete functions ------------------------- */
        function showMonthSuggestions(input) {
            const filteredMonths = monthNames.filter(month =>
                month.toLowerCase().startsWith(input.toLowerCase())
            );

            if (filteredMonths.length === 0 || (filteredMonths.length === 1 && filteredMonths[0]
                    .toLowerCase() === input.toLowerCase())) {
                hideMonthSuggestions();
                return;
            }

            monthSuggestions.innerHTML = '';
            filteredMonths.forEach((month, index) => {
                const suggestion = document.createElement('div');
                suggestion.className = 'month-suggestion';
                suggestion.textContent = month;
                suggestion.onclick = () => selectMonth(month);
                monthSuggestions.appendChild(suggestion);
            });

            monthSuggestions.style.display = 'block';
            highlightedIndex = -1;
        }

        function hideMonthSuggestions() {
            monthSuggestions.style.display = 'none';
            highlightedIndex = -1;
        }

        function selectMonth(monthName) {
            monthInput.value = monthName;
            hideMonthSuggestions();
            updateCalendarFromInputs();
        }

        function highlightSuggestion(direction) {
            const suggestions = monthSuggestions.querySelectorAll('.month-suggestion');
            if (suggestions.length === 0) return;

            // Remove previous highlight
            if (highlightedIndex >= 0) {
                suggestions[highlightedIndex].classList.remove('highlighted');
            }

            // Update index
            if (direction === 'down') {
                highlightedIndex = (highlightedIndex + 1) % suggestions.length;
            } else if (direction === 'up') {
                highlightedIndex = highlightedIndex <= 0 ? suggestions.length - 1 : highlightedIndex - 1;
            }

            // Apply new highlight
            if (highlightedIndex >= 0) {
                suggestions[highlightedIndex].classList.add('highlighted');
            }
        }

        function selectHighlightedSuggestion() {
            const suggestions = monthSuggestions.querySelectorAll('.month-suggestion');
            if (highlightedIndex >= 0 && highlightedIndex < suggestions.length) {
                selectMonth(suggestions[highlightedIndex].textContent);
                return true;
            }
            return false;
        }


        /* ------------------------- Custom Dropdown Component ------------------------- */
        /*
          createDropdown({ id, options, initialIndex }) =>
          returns { el, open(), close(), getValue(), setValue(val) }
        */
        function createDropdown({
            id,
            options = [],
            initialIndex = 0
        }) {
            const root = document.getElementById(id);
            const toggle = root.querySelector(".cd-toggle");
            const selectedSpan = root.querySelector(".cd-selected");
            const list = root.querySelector(".cd-list");

            // populate list (clear first)
            list.innerHTML = "";
            options.forEach((opt, i) => {
                const li = document.createElement("li");
                li.className = "cd-option";
                li.setAttribute("data-value", opt.value ?? opt);
                li.textContent = opt.label ?? opt;
                li.tabIndex = -1;
                li.role = "option";
                if (i === initialIndex) li.classList.add("selected");
                list.appendChild(li);
            });

            // internal state
            let isOpen = false;
            let focusedIndex = initialIndex;

            function open() {
                root.classList.add("open");
                toggle.classList.add("rotated");
                toggle.setAttribute("aria-expanded", "true");
                list.setAttribute("aria-hidden", "false");
                isOpen = true;
                // focus the currently selected option if any
                const items = list.querySelectorAll(".cd-option");
                if (items[focusedIndex]) {
                    items[focusedIndex].classList.add("focused");
                    items[focusedIndex].focus();
                    scrollIntoViewIfNeeded(items[focusedIndex], list);
                }
                document.addEventListener("click", onDocClick);
                document.addEventListener("keydown", onKeyDown);
            }

            function close() {
                root.classList.remove("open");
                toggle.classList.remove("rotated");
                toggle.setAttribute("aria-expanded", "false");
                list.setAttribute("aria-hidden", "true");
                isOpen = false;
                clearFocused();
                document.removeEventListener("click", onDocClick);
                document.removeEventListener("keydown", onKeyDown);
            }

            function getValue() {
                const sel = list.querySelector(".cd-option.selected");
                return sel ? sel.getAttribute("data-value") : null;
            }

            function setValue(val) {
                const items = Array.from(list.querySelectorAll(".cd-option"));
                items.forEach((it) => it.classList.remove("selected"));
                const found = items.find(
                    (it) => it.getAttribute("data-value") === String(val)
                );
                if (found) {
                    found.classList.add("selected");
                    selectedSpan.textContent = found.textContent;
                    focusedIndex = items.indexOf(found);
                } else {
                    // fallback: set first
                    if (items[0]) {
                        items[0].classList.add("selected");
                        selectedSpan.textContent = items[0].textContent;
                        focusedIndex = 0;
                    }
                }
            }

            function onToggleClick(e) {
                e.stopPropagation();
                if (isOpen) close();
                else open();
            }

            toggle.addEventListener("click", onToggleClick);


            function onOptionClick(e) {
                const li = e.currentTarget;
                // remove selected from others
                list.querySelectorAll(".cd-option").forEach((o) => {
                    o.classList.remove("selected", "focused");
                });
                li.classList.add("selected");
                selectedSpan.textContent = li.textContent;
                focusedIndex = Array.from(list.children).indexOf(li);
                close();
                // dispatch custom 'change' event on root for listeners
                root.dispatchEvent(new CustomEvent("cd-change", {
                    detail: getValue()
                }));
            }

            function onDocClick(e) {
                if (!root.contains(e.target)) close();
            }

            function clearFocused() {
                list
                    .querySelectorAll(".cd-option")
                    .forEach((o) => o.classList.remove("focused"));
            }

            function onKeyDown(e) {
                if (!isOpen) return;

                const items = Array.from(list.querySelectorAll(".cd-option"));
                if (e.key === "ArrowDown") {
                    e.preventDefault();
                    focusedIndex = Math.min(items.length - 1, focusedIndex + 1);
                    items.forEach((it) => it.classList.remove("focused"));
                    items[focusedIndex].classList.add("focused");
                    scrollIntoViewIfNeeded(items[focusedIndex], list);
                } else if (e.key === "ArrowUp") {
                    e.preventDefault();
                    focusedIndex = Math.max(0, focusedIndex - 1);
                    items.forEach((it) => it.classList.remove("focused"));
                    items[focusedIndex].classList.add("focused");
                    scrollIntoViewIfNeeded(items[focusedIndex], list);
                } else if (e.key === "Enter") {
                    e.preventDefault();
                    if (items[focusedIndex]) {
                        items[focusedIndex].click();
                    }
                } else if (e.key === "Escape") {
                    e.preventDefault();
                    close();
                }
            }

            // small helper to ensure option visible when keyboard navigates
            function scrollIntoViewIfNeeded(el, container) {
                const containerTop = container.scrollTop;
                const containerBottom = containerTop + container.clientHeight;
                const elTop = el.offsetTop;
                const elBottom = elTop + el.offsetHeight;
                if (elTop < containerTop) container.scrollTop = elTop - 4;
                else if (elBottom > containerBottom)
                    container.scrollTop = elBottom - container.clientHeight + 4;
            }

            // event bindings
            toggle.addEventListener("click", onToggleClick);
            root
                .querySelectorAll(".cd-option")
                .forEach((li) => li.addEventListener("click", onOptionClick));

            // expose API
            return {
                el: root,
                open,
                close,
                getValue,
                setValue
            };
        }

        /* ------------------------- Build dropdowns with options ------------------------- */

        function pad(n) {
            return String(n).padStart(2, "0");
        }

        /* timezone dropdown already has DOM items in HTML; we'll initialize it differently */
        const timezoneDropdown = createDropdown({
            id: "dropdown-timezone",
            options: [{
                    label: "GMT",
                    value: "GMT"
                },
                {
                    label: "UTC",
                    value: "UTC"
                },
                {
                    label: "WAT",
                    value: "Africa/Lagos"
                },
            ],
            initialIndex: 0,
        });

        /* ------------------------- Scrollable Time Picker ------------------------- */

        // Time wheel data
        const timeWheels = {
            hour: {
                element: null,
                items: [],
                currentIndex: 5
            }, // Default to 06
            minute: {
                element: null,
                items: [],
                currentIndex: 0
            }, // Default to 00
            second: {
                element: null,
                items: [],
                currentIndex: 0
            }, // Default to 00
            ampm: {
                element: null,
                items: ['AM', 'PM'],
                currentIndex: 0
            } // Default to AM
        };

        // Initialize time wheels
        function initTimeWheels() {
            // Generate hours (10 AM to 8 PM only)
            // 10 AM, 11 AM, 12 PM, 1 PM, 2 PM, 3 PM, 4 PM, 5 PM, 6 PM, 7 PM, 8 PM
            const allowedHours = [10, 11, 12, 1, 2, 3, 4, 5, 6, 7, 8];
            allowedHours.forEach(hour => {
                timeWheels.hour.items.push(String(hour).padStart(2, '0'));
            });

            // Generate minutes/seconds (00-59)
            for (let i = 0; i <= 59; i++) {
                timeWheels.minute.items.push(String(i).padStart(2, '0'));
                timeWheels.second.items.push(String(i).padStart(2, '0'));
            }

            // Set default time to 10:00 AM (or current time if within working hours)
            const now = new Date();
            let currentHour = now.getHours();
            const currentMinute = now.getMinutes();
            const currentSecond = 0; // Set seconds to 00 by default

            let defaultHourIndex = 0; // 10 AM by default
            let defaultAmPmIndex = 0; // AM by default
            let defaultMinuteIndex = 0; // 00 minutes by default

            // Check if current time is within working hours (10 AM - 8 PM)
            if (currentHour >= 10 && currentHour <= 20) {
                // Convert to 12-hour format for display
                let displayHour = currentHour;
                let ampm = 'AM';

                if (currentHour >= 12) {
                    ampm = 'PM';
                    if (currentHour > 12) {
                        displayHour = currentHour - 12;
                    }
                }

                // Find the index in our allowed hours
                const hourIndex = timeWheels.hour.items.findIndex(h => parseInt(h) === displayHour);
                if (hourIndex !== -1) {
                    defaultHourIndex = hourIndex;
                    defaultAmPmIndex = ampm === 'AM' ? 0 : 1;
                    defaultMinuteIndex = currentMinute;
                }
            }

            // Update default indices
            timeWheels.hour.currentIndex = defaultHourIndex;
            timeWheels.minute.currentIndex = defaultMinuteIndex;
            timeWheels.second.currentIndex = 0;
            timeWheels.ampm.currentIndex = defaultAmPmIndex;

            // Setup each wheel
            setupTimeWheel('hour');
            setupTimeWheel('minute');
            setupTimeWheel('second');
            setupTimeWheel('ampm');

            // Update initial display
            updateTimeDisplay();
        }

        function setupTimeWheel(type) {
            const wheel = document.getElementById(`${type}-wheel`);
            const list = wheel.querySelector('.time-wheel-list');

            timeWheels[type].element = wheel;

            // Populate wheel items
            list.innerHTML = '';
            timeWheels[type].items.forEach((item, index) => {
                const div = document.createElement('div');
                div.className = 'time-wheel-item';
                div.textContent = item;
                div.dataset.value = item;
                div.dataset.index = index;
                list.appendChild(div);
            });

            // Set initial position
            const itemHeight = 40;
            const centerOffset = -timeWheels[type].currentIndex * itemHeight;
            list.style.transform = `translateY(${centerOffset}px)`;

            // Update active item
            updateActiveItem(type);

            // Add scroll functionality
            let isScrolling = false;
            let startY = 0;
            let startTransform = 0;

            wheel.addEventListener('wheel', (e) => {
                e.preventDefault();
                const delta = e.deltaY > 0 ? 1 : -1;
                scrollWheel(type, delta);
            });

            // Touch/mouse drag functionality
            wheel.addEventListener('mousedown', startDrag);
            wheel.addEventListener('touchstart', startDrag, {
                passive: false
            });

            function startDrag(e) {
                isScrolling = true;
                startY = e.type === 'mousedown' ? e.clientY : e.touches[0].clientY;

                const transform = list.style.transform;
                const match = transform.match(/translateY\((-?\d+)px\)/);
                startTransform = match ? parseInt(match[1]) : 0;

                document.addEventListener('mousemove', onDrag);
                document.addEventListener('touchmove', onDrag, {
                    passive: false
                });
                document.addEventListener('mouseup', endDrag);
                document.addEventListener('touchend', endDrag);
            }

            function onDrag(e) {
                if (!isScrolling) return;
                e.preventDefault();

                const currentY = e.type === 'mousemove' ? e.clientY : e.touches[0].clientY;
                const deltaY = currentY - startY;
                const newTransform = startTransform + deltaY;

                list.style.transform = `translateY(${newTransform}px)`;
            }

            function endDrag() {
                if (!isScrolling) return;
                isScrolling = false;

                // Snap to nearest item
                const transform = list.style.transform;
                const match = transform.match(/translateY\((-?\d+)px\)/);
                const currentTransform = match ? parseInt(match[1]) : 0;

                const itemHeight = 40;
                const newIndex = Math.max(0, Math.min(
                    timeWheels[type].items.length - 1,
                    Math.round(-currentTransform / itemHeight)
                ));

                setWheelIndex(type, newIndex);

                document.removeEventListener('mousemove', onDrag);
                document.removeEventListener('touchmove', onDrag);
                document.removeEventListener('mouseup', endDrag);
                document.removeEventListener('touchend', endDrag);
            }
        }

        function scrollWheel(type, direction) {
            const newIndex = Math.max(0, Math.min(
                timeWheels[type].items.length - 1,
                timeWheels[type].currentIndex + direction
            ));
            setWheelIndex(type, newIndex);
        }

        function setWheelIndex(type, index) {
            // Only validate working hours for gentle reminders (don't block selection)
            validateWorkingHours(type, index);

            // Validate time selection if today is selected (only for past times)
            if (selectedDate && selectedDate.toDateString() === today.toDateString()) {
                const todayValidatedIndex = validateTimeSelection(type, index);
                if (todayValidatedIndex !== index) {
                    index = todayValidatedIndex;
                    // Show a brief flash message about time restriction
                    const now = new Date();
                    const minHour = now.getHours() + 1;
                    flashMessage(
                        `Please select a time at least 1 hour ahead (after ${minHour}:${now.getMinutes().toString().padStart(2, '0')})`
                    );
                }
            }

            timeWheels[type].currentIndex = index;
            const list = timeWheels[type].element.querySelector('.time-wheel-list');

            const itemHeight = 40;
            const centerOffset = -index * itemHeight;
            list.style.transform = `translateY(${centerOffset}px)`;

            updateActiveItem(type);
            updateTimeDisplay();
        }

        function validateWorkingHours(type, newIndex) {
            // Allow all selections during normal interaction
            // Only show a gentle reminder message for times outside working hours

            // Get current selections (with the proposed change)
            const currentHour = type === 'hour' ? parseInt(timeWheels.hour.items[newIndex]) : parseInt(
                timeWheels.hour.items[timeWheels.hour.currentIndex]);
            const currentAmPm = type === 'ampm' ? timeWheels.ampm.items[newIndex] : timeWheels.ampm.items[
                timeWheels.ampm.currentIndex];

            // Convert to 24-hour format for validation
            let hour24 = currentHour;
            if (currentAmPm === 'PM' && currentHour !== 12) {
                hour24 += 12;
            } else if (currentAmPm === 'AM' && currentHour === 12) {
                hour24 = 0;
            }

            // Check if time is outside working hours (10 AM - 8 PM = 10:00 - 20:00)
            if (hour24 < 10 || hour24 > 20) {
                // Show a gentle notification but don't block the selection
                if (hour24 < 10) {
                    showWorkingHoursNotice('Consultation hours start at 10:00 AM');
                } else if (hour24 > 20) {
                    showWorkingHoursNotice('Consultation hours end at 8:00 PM');
                }
            }

            return newIndex; // Always allow the selection
        }

        function validateTimeSelection(type, newIndex) {
            if (!selectedDate || selectedDate.toDateString() !== today.toDateString()) {
                return newIndex; // No validation needed for future dates
            }

            const now = new Date();
            let minHour = now.getHours() + 1;
            const minMinute = now.getMinutes();

            // Handle hour overflow
            if (minHour >= 24) {
                minHour = 23;
            }

            // Get current selections (with the proposed change)
            const currentHour = type === 'hour' ? parseInt(timeWheels.hour.items[newIndex]) : parseInt(
                timeWheels.hour.items[timeWheels.hour.currentIndex]);
            const currentMinute = type === 'minute' ? parseInt(timeWheels.minute.items[newIndex]) : parseInt(
                timeWheels.minute.items[timeWheels.minute.currentIndex]);
            const currentAmPm = type === 'ampm' ? timeWheels.ampm.items[newIndex] : timeWheels.ampm.items[
                timeWheels.ampm.currentIndex];

            // Convert current selection to 24-hour format
            let current24Hour = currentHour;
            if (currentAmPm === 'PM' && currentHour !== 12) {
                current24Hour += 12;
            } else if (currentAmPm === 'AM' && currentHour === 12) {
                current24Hour = 0;
            }

            // Check if the proposed time is valid
            if (current24Hour < minHour) {
                // Find the minimum valid option for this type
                if (type === 'hour') {
                    let targetHour = minHour > 12 ? minHour - 12 : minHour;
                    if (targetHour === 0) targetHour = 12;
                    const minHourIndex = timeWheels.hour.items.findIndex(h => parseInt(h) === targetHour);
                    return minHourIndex !== -1 ? minHourIndex : newIndex;
                } else if (type === 'ampm') {
                    return minHour >= 12 ? 1 : 0; // PM if minHour >= 12, otherwise AM
                }
                return newIndex;
            } else if (current24Hour === minHour && currentMinute < minMinute) {
                if (type === 'minute') {
                    const minMinuteIndex = timeWheels.minute.items.findIndex(m => parseInt(m) >= minMinute);
                    return minMinuteIndex !== -1 ? minMinuteIndex : newIndex;
                }
                return newIndex;
            }

            return newIndex; // Time is valid
        }

        function updateActiveItem(type) {
            const items = timeWheels[type].element.querySelectorAll('.time-wheel-item');
            items.forEach((item, index) => {
                item.classList.toggle('active', index === timeWheels[type].currentIndex);
            });
        }

        // Initialize the time wheels
        initTimeWheels();

        // Set initial time restrictions if today is already selected
        updateTimeRestrictions();

        /* ------------------------- Update time display from scrollable wheels ------------------------- */
        function updateTimeDisplay() {
            const hour = timeWheels.hour.items[timeWheels.hour.currentIndex];
            const minute = timeWheels.minute.items[timeWheels.minute.currentIndex];
            const second = timeWheels.second.items[timeWheels.second.currentIndex];
            const ampm = timeWheels.ampm.items[timeWheels.ampm.currentIndex];
            const tz = timezoneDropdown.getValue();

            updateAllSelectedTime(`${hour}:${minute}:${second} ${ampm} ${tz}`);
        }


        function getSelectedTime() {
            const hour = timeWheels.hour.items[timeWheels.hour.currentIndex];
            const minute = timeWheels.minute.items[timeWheels.minute.currentIndex];
            const second = timeWheels.second.items[timeWheels.second.currentIndex];
            const ampm = timeWheels.ampm.items[timeWheels.ampm.currentIndex];

            return `${hour}:${minute}:${second} ${ampm}`;
        }

        /* Time wheel events are handled internally, only timezone dropdown needs listener */
        timezoneDropdown.el.addEventListener("cd-change", updateTimeDisplay);

        /* ------------------------- Calendar render & selection logic ------------------------- */

        function renderCalendar() {
            const year = current.getFullYear();
            const month = current.getMonth();

            monthInput.value = current.toLocaleString("default", {
                month: "long"
            });
            yearInput.value = year;

            const firstDay = new Date(year, month, 1);
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const startIndex = (firstDay.getDay() + 6) % 7;

            const prevMonthDays = new Date(year, month, 0).getDate();

            datesContainer.innerHTML = "";

            /* --------------------- PREVIOUS MONTH VISIBLE DAYS --------------------- */
            for (let i = 0; i < startIndex; i++) {
                const dayNum = prevMonthDays - startIndex + i + 1;
                const div = document.createElement("div");
                div.className = "date other-month";
                div.textContent = dayNum;
                div.dataset.date = `${year}-${month}-${dayNum}`;

                // Previous month click selects correct date
                const prevDate = new Date(year, month - 1, dayNum);

                // Check if this previous month date is in the past
                const isPastDate = prevDate < today && prevDate.toDateString() !== today.toDateString();

                // Check if this date is Sunday (0 = Sunday)
                const isSunday = prevDate.getDay() === 0;

                if (isPastDate || isSunday) {
                    div.classList.add("disabled");
                    div.onclick = null;
                } else {
                    div.onclick = () => selectDate(prevDate, div);
                }

                datesContainer.appendChild(div);
            }

            /* --------------------------- CURRENT MONTH DAYS --------------------------- */
            for (let day = 1; day <= daysInMonth; day++) {
                const dateObj = new Date(year, month, day);
                const div = document.createElement("div");
                div.className = "date";
                div.textContent = day;

                // Check if this date is in the past
                const isPastDate = dateObj < today && dateObj.toDateString() !== today.toDateString();

                // Check if this date is Sunday (0 = Sunday)
                const isSunday = dateObj.getDay() === 0;

                // highlight today
                if (dateObj.toDateString() === today.toDateString()) {
                    div.classList.add("today");
                }

                // disable past days and Sundays
                if (isPastDate || isSunday) {
                    div.classList.add("disabled");
                    div.onclick = null; // Remove click handler
                } else {
                    div.onclick = () => selectDate(dateObj, div);
                }

                datesContainer.appendChild(div);
            }

            /* ------------------------ NEXT MONTH VISIBLE DAYS ------------------------ */
            const totalCells = startIndex + daysInMonth;
            const remaining = 42 - totalCells; // 6 rows calendar

            for (let i = 1; i <= remaining; i++) {
                const div = document.createElement("div");
                div.className = "date other-month";
                div.textContent = i;

                const nextDate = new Date(year, month + 1, i);

                // Check if this date is Sunday (0 = Sunday)
                const isSunday = nextDate.getDay() === 0;

                if (isSunday) {
                    div.classList.add("disabled");
                    div.onclick = null;
                } else {
                    div.onclick = () => selectDate(nextDate, div);
                }

                datesContainer.appendChild(div);
            }

            updatePrevButtonState();
        }

        function selectDate(dateObj, div) {
            selectedDate = dateObj;

            // Remove selected and today classes from all dates
            document.querySelectorAll(".date").forEach((d) => {
                d.classList.remove("selected", "today");
            });

            // Add selected class to the clicked date
            div.classList.add("selected");

            // If the selected date is today, also add the today class
            if (dateObj.toDateString() === today.toDateString()) {
                div.classList.add("today");
            }

            updateAllSelectedDate(selectedDate.toDateString());

            // Update time restrictions based on selected date
            updateTimeRestrictions();
            updateTimeDisplay();
        }

        function updateTimeRestrictions() {
            const timeRestrictionNotice = document.getElementById('timeRestrictionNotice');

            if (!selectedDate) {
                if (timeRestrictionNotice) timeRestrictionNotice.style.display = 'none';
                return;
            }

            const isToday = selectedDate.toDateString() === today.toDateString();

            if (isToday) {
                restrictTimeToCurrentPlusOne();
                if (timeRestrictionNotice) timeRestrictionNotice.style.display = 'block';
            } else {
                resetTimeRestrictions();
                if (timeRestrictionNotice) timeRestrictionNotice.style.display = 'none';
            }
        }

        function restrictTimeToCurrentPlusOne() {
            const now = new Date();
            let minHour = now.getHours() + 1; // At least 1 hour ahead
            const minMinute = now.getMinutes();

            // Ensure minimum time is within working hours (10 AM - 8 PM)
            if (minHour < 10) {
                minHour = 10; // Start at 10 AM
            } else if (minHour > 20) {
                // If it's past 8 PM, they can't book for today
                return;
            }

            // Convert to 12-hour format
            let min12Hour = minHour > 12 ? minHour - 12 : minHour;
            if (min12Hour === 0) min12Hour = 12;
            const minAmPm = minHour >= 12 ? 'PM' : 'AM';

            // Update time wheels to reflect restrictions
            restrictTimeWheels(min12Hour, minMinute, minAmPm, minHour);
        }

        function resetTimeRestrictions() {
            // Reset all time wheels to allow any time
            resetTimeWheels();
        }

        function restrictTimeWheels(minHour, minMinute, minAmPm, originalMinHour) {
            // Find the appropriate indices for minimum allowed time
            const minAmPmIndex = minAmPm === 'AM' ? 0 : 1;
            const minHourIndex = timeWheels.hour.items.findIndex(h => parseInt(h) === minHour);

            // If current time selection is invalid, update to minimum allowed time
            const currentHour = parseInt(timeWheels.hour.items[timeWheels.hour.currentIndex]);
            const currentAmPm = timeWheels.ampm.items[timeWheels.ampm.currentIndex];
            const currentMinute = parseInt(timeWheels.minute.items[timeWheels.minute.currentIndex]);

            const currentAmPmIndex = currentAmPm === 'AM' ? 0 : 1;

            // Convert current selection to 24-hour format for comparison
            let current24Hour = currentHour;
            if (currentAmPm === 'PM' && currentHour !== 12) {
                current24Hour += 12;
            } else if (currentAmPm === 'AM' && currentHour === 12) {
                current24Hour = 0;
            }

            // Check if current time is invalid
            let needsUpdate = false;

            if (current24Hour < originalMinHour) {
                needsUpdate = true;
            } else if (current24Hour === originalMinHour && currentMinute < minMinute) {
                needsUpdate = true;
            }

            if (needsUpdate) {
                // Set to minimum allowed time
                setWheelIndex('ampm', minAmPmIndex);
                if (minHourIndex !== -1) {
                    setWheelIndex('hour', minHourIndex);
                }

                // If same hour, set to minimum minute
                if (current24Hour === originalMinHour) {
                    const minMinuteIndex = timeWheels.minute.items.findIndex(m => parseInt(m) >= minMinute);
                    if (minMinuteIndex !== -1) {
                        setWheelIndex('minute', minMinuteIndex);
                    }
                }

                // Set seconds to 00 for cleaner time
                setWheelIndex('second', 0);
            }
        }

        function resetTimeWheels() {
            // No restrictions needed - all times are available
            // Keep current selections as they are
        }


        /* ------------------------- Prev / Next month logic + tooltip ------------------------- */

        prevBtn.onclick = () => {
            const y = current.getFullYear();
            const m = current.getMonth();
            const atLimit = y === minYear && m === minMonth;

            if (atLimit) {
                prevTooltip.classList.add("show");
                setTimeout(() => prevTooltip.classList.remove("show"), 1800);
                return;
            }

            current.setMonth(m - 1);
            renderCalendar();
        };

        document.getElementById("nextMonth").onclick = () => {
            current.setMonth(current.getMonth() + 1);
            renderCalendar();
        };

        function updatePrevButtonState() {
            const y = current.getFullYear();
            const m = current.getMonth();

            if (y === minYear && m === minMonth) {
                prevBtn.classList.add("disabled-prev");
            } else {
                prevBtn.classList.remove("disabled-prev");
            }
        }

        /* ------------------------- Month and Year input handlers ------------------------- */
        function updateCalendarFromInputs() {
            const monthName = monthInput.value.trim();
            const year = parseInt(yearInput.value);

            if (!monthName || isNaN(year)) return;

            const monthIndex = monthNames.findIndex(month =>
                month.toLowerCase() === monthName.toLowerCase()
            );

            if (monthIndex === -1) return;
            if (year < minYear) return;
            if (year === minYear && monthIndex < minMonth) return;

            current = new Date(year, monthIndex);
            renderCalendar();
        }

        // Month input with autocomplete
        monthInput.addEventListener('input', (e) => {
            const input = e.target.value;
            if (input.length > 0) {
                showMonthSuggestions(input);
            } else {
                hideMonthSuggestions();
            }
        });

        monthInput.addEventListener('keydown', (e) => {
            if (monthSuggestions.style.display === 'block') {
                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        highlightSuggestion('down');
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        highlightSuggestion('up');
                        break;
                    case 'Enter':
                        e.preventDefault();
                        if (!selectHighlightedSuggestion()) {
                            updateCalendarFromInputs();
                        }
                        break;
                    case 'Escape':
                        e.preventDefault();
                        hideMonthSuggestions();
                        break;
                }
            } else if (e.key === 'Enter') {
                updateCalendarFromInputs();
            }
        });

        monthInput.addEventListener('blur', () => {
            // Delay hiding to allow click on suggestions
            setTimeout(() => {
                hideMonthSuggestions();
                updateCalendarFromInputs();
            }, 150);
        });

        // Year input handler
        yearInput.addEventListener('input', (e) => {
            let year = parseInt(e.target.value);
            if (year < minYear) {
                e.target.value = minYear;
            } else if (year > 2050) {
                e.target.value = 2050;
            }
            updateCalendarFromInputs();
        });
        yearInput.addEventListener('change', updateCalendarFromInputs);

        // Click outside to hide suggestions
        document.addEventListener('click', (e) => {
            const monthContainer = document.querySelector('.month-input-container');
            if (!monthContainer.contains(e.target)) {
                hideMonthSuggestions();
            }
        });






        /* ------------------------- Initial render ------------------------- */
        renderCalendar();




        /* ------------------------- Submit logic + modal ------------------------- */
        document.querySelectorAll(".submit-schedule").forEach(btn => {

            // Track which button was last clicked (needed for flashMessage positioning)
            btn.addEventListener("click", () => {
                document.querySelectorAll(".submit-schedule")
                    .forEach(b => b.classList.remove("last-clicked"));

                btn.classList.add("last-clicked");
            });

            // Main submit action
            btn.onclick = () => {

                if (!selectedDate) {
                    flashMessage("Please select a date before submitting.", btn);
                    return;
                }

                const hour = timeWheels.hour.items[timeWheels.hour.currentIndex];
                const minute = timeWheels.minute.items[timeWheels.minute.currentIndex];
                const second = timeWheels.second.items[timeWheels.second.currentIndex];
                const ampm = timeWheels.ampm.items[timeWheels.ampm.currentIndex];

                // Convert to 24-hour format for validation
                let hour24 = parseInt(hour);
                if (ampm === 'PM' && hour24 !== 12) {
                    hour24 += 12;
                } else if (ampm === 'AM' && hour24 === 12) {
                    hour24 = 0;
                }

                // Gentle reminder about working hours (but don't block submission)
                if (hour24 < 10 || hour24 > 20) {
                    const isOutsideHours = hour24 < 10 ? 'before 10:00 AM' : 'after 8:00 PM';
                    if (!confirm(
                            `You've selected a time ${isOutsideHours}. Our normal consultation hours are 10:00 AM to 8:00 PM, Monday to Saturday. Would you like to proceed with this booking?`
                        )) {
                        return; // User chose not to proceed
                    }
                }

                // Format the date for the form submission (YYYY-MM-DD)
                const formattedDate = selectedDate.getFullYear() + '-' +
                    String(selectedDate.getMonth() + 1).padStart(2, '0') + '-' +
                    String(selectedDate.getDate()).padStart(2, '0');

                const formattedTime = `${String(hour24).padStart(2, '0')}:${minute}`;

                // Set hidden form values
                document.getElementById('hiddenDate').value = formattedDate;
                document.getElementById('hiddenTime').value = formattedTime;
                document.getElementById('hiddenTimezone').value = timezoneDropdown.getValue();

                // Submit the form
                const form = document.getElementById('scheduleForm');

                // Create a FormData object to submit via AJAX
                const formData = new FormData(form);

                // Show loading state on button
                btn.disabled = true;
                btn.textContent = 'Submitting...';

                // Submit form via AJAX
                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success modal with submitted details
                            modalDateText.textContent = "Date: " + selectedDate.toDateString();
                            modalTimeText.textContent =
                                "Time: " +
                                `${hour}:${minute}:${second} ${ampm} ${timezoneDropdown.getValue()}`;

                            // Update email button with actual user email if provided
                            if (data.user_email) {
                                const emailBtn = document.querySelector(
                                        '.submit-btn-modal[textContent*="Open"]') ||
                                    document.querySelector('.submit-btn-modal');
                                if (emailBtn) {
                                    emailBtn.textContent = `Open ${data.user_email}`;
                                    emailBtn.onclick = function(e) {
                                        e.preventDefault();
                                        window.location.href = `mailto:${data.user_email}`;
                                    };
                                }
                            }

                            modal.style.display = "flex";
                        } else {
                            flashMessage(data.message || "An error occurred. Please try again.",
                                btn);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        flashMessage("An error occurred. Please try again.", btn);
                    })
                    .finally(() => {
                        // Reset button state
                        btn.disabled = false;
                        btn.textContent = 'Submit';
                    });
            };
        });




        /* close if clicking outside */
        modal.addEventListener("click", (e) => {
            // Close only if clicking on the overlay (not inside the box)
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });

        /* small inline flash function instead of alert */
        function flashMessage(msg, attachTo = null) {
            const flashContainer = document.getElementById("flashContainer");

            // If no element was passed, use the button that last triggered submission
            if (!attachTo) {
                attachTo = document.querySelector(".submit-schedule.last-clicked");
            }

            // Fallback: first submit button if none marked yet
            if (!attachTo) {
                attachTo = document.querySelector(".submit-schedule");
            }

            const toast = document.createElement("div");
            toast.className = "flash-msg";
            toast.textContent = msg;

            const rect = attachTo.getBoundingClientRect();
            toast.style.top = rect.top + window.scrollY - 50 + "px";
            toast.style.left = rect.left + window.scrollX + "px";

            flashContainer.appendChild(toast);

            requestAnimationFrame(() => toast.classList.add("show"));
            setTimeout(() => toast.classList.add("hide"), 2000);
            setTimeout(() => toast.remove(), 2600);
        }

        /* Gentle working hours notification */
        let workingHoursNoticeTimeout;

        function showWorkingHoursNotice(msg) {
            // Clear any existing timeout
            if (workingHoursNoticeTimeout) {
                clearTimeout(workingHoursNoticeTimeout);
            }

            // Show a subtle notice near the time picker
            let notice = document.getElementById('workingHoursNotice');
            if (!notice) {
                notice = document.createElement('div');
                notice.id = 'workingHoursNotice';
                notice.style.cssText = `
                    position: absolute;
                    top: -30px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: rgba(136, 193, 183, 0.1);
                    border: 1px solid rgba(136, 193, 183, 0.3);
                    color: #88C1B7;
                    padding: 5px 10px;
                    border-radius: 4px;
                    font-size: 12px;
                    font-family: "GT Walsheim Con";
                    z-index: 100;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    pointer-events: none;
                `;

                // Add to time box container
                const timeBox = document.querySelector('.time-box-container') || document.querySelector(
                    '.time-box');
                if (timeBox) {
                    timeBox.style.position = 'relative';
                    timeBox.appendChild(notice);
                }
            }

            notice.textContent = msg;
            notice.style.opacity = '1';

            // Hide after 3 seconds
            workingHoursNoticeTimeout = setTimeout(() => {
                notice.style.opacity = '0';
            }, 3000);
        }


        /* ------------------------- Accessibility: keyboard open/close for dropdowns ------------------------- */
        /* Provide space/enter to open focused dropdowns */
        document.querySelectorAll(".custom-dropdown").forEach((root) => {
            root.addEventListener("keydown", (e) => {
                if (e.key === " " || e.key === "Enter") {
                    e.preventDefault();
                    // simulate toggle click
                    root.querySelector(".cd-toggle").click();
                }
            });
        });

        // Handle modal buttons
        document.querySelectorAll('.submit-btn-modal').forEach(btn => {
            if (btn.textContent.includes('Open customer') || btn.textContent.includes(
                    'customer@example.com')) {
                // This would be dynamically updated with the user's actual email
                btn.textContent = 'Open Email';
                btn.onclick = function(e) {
                    e.preventDefault();
                    // Open default email client
                    window.location.href = 'mailto:';
                };
            } else if (btn.textContent.includes('Return to Homepage')) {
                btn.onclick = function(e) {
                    e.preventDefault();
                    window.location.href = '/';
                };
            }
        });



        // CALL FOR HELP CHAT LOGIC
        const helpContainer = document.querySelector('.callforhelp-container');
        const chatPanel = document.getElementById('chat-panel');
        const chatOverlay = document.getElementById('chat-overlay');
        const chatPanelContainer = document.querySelector('.chat-panel-container');
        const chatBody = document.getElementById('chat-body');
        const chatInput = document.getElementById('chat-input');
        const sendBtn = document.getElementById('send-btn');

          // Get application data from session
        const applicationId = {{ Session::get('consultation_application_id', 'null') }};
        const applicationData = @json(optional($application));
     

        // Open Chat Panel
        helpContainer.addEventListener('click', () => {
            chatPanel.classList.add('open');
            chatOverlay.classList.add('show');
            document.body.style.overflow = "hidden";

            // Focus on input after panel opens
            setTimeout(() => {
                chatInput.focus();
            }, 300);
        });

        function closeChatPanel() {
            chatPanel.classList.remove('open');
            chatOverlay.classList.remove('show');
            document.body.style.overflow = "";
        }

        // Close when clicking outside the chat-panel-container
        chatPanel.addEventListener('click', (e) => {
            if (!chatPanelContainer.contains(e.target)) {
                closeChatPanel();
            }
        });

        // Close chat when clicking overlay
        chatOverlay.addEventListener('click', closeChatPanel);

        // Send message function
        async function sendChatMessage() {
            const message = chatInput.value.trim();

            if (!message) {
                showFlashMessage('Please type a message', 'warning');
                return;
            }

            if (!applicationId) {
                showFlashMessage('Session expired. Please refresh the page.', 'error');
                return;
            }

            // Disable input and button during sending
            chatInput.disabled = true;
            sendBtn.disabled = true;
            sendBtn.style.opacity = '0.6';

            // Add user message to chat immediately (optimistic UI)
            addMessageToChat(message, 'user', true);
            chatInput.value = '';

            // Show immediate feedback
            showFlashMessage('Sending your message...', 'info');

            try {
                const response = await fetch('/api/consultation/chat/send-message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            ?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        application_id: applicationId,
                        message: message,
                        sender_name: applicationData?.name || 'Guest',
                        sender_email: applicationData?.email || null
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    // Show success animation on the last message
                    const lastMessage = chatBody.lastElementChild;
                    if (lastMessage && lastMessage.classList.contains('sending')) {
                        lastMessage.classList.remove('sending');
                        lastMessage.classList.add('sent');

                        // Add timestamp
                        setTimeout(() => {
                            const timestamp = document.createElement('div');
                            timestamp.style.fontSize = '11px';
                            timestamp.style.color = '#999';
                            timestamp.style.marginTop = '4px';
                            timestamp.textContent = 'Just now ✓';
                            lastMessage.appendChild(timestamp);
                        }, 500);
                    }

                    // Show immediate success feedback
                    showFlashMessage(
                        'Your message has been sent and someone from our team will get back to you shortly!',
                        'success');

                    // Show automated response after a short delay
                    setTimeout(() => {
                        addMessageToChat(
                            "Thank you for reaching out! We've received your message and will get back to you soon. In the meantime, feel free to ask any other questions.",
                            'bot'
                        );
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to send message');
                }

            } catch (error) {
                console.error('Chat message error:', error);

                // Remove the failed message
                const lastMessage = chatBody.lastElementChild;
                if (lastMessage && lastMessage.classList.contains('sending')) {
                    lastMessage.remove();
                }

                // Show specific error messages based on error type
                let errorMessage = 'Failed to send message. Please try again.';
                if (error.message.includes('500')) {
                    errorMessage = 'Server error occurred. Please try again in a moment.';
                } else if (error.message.includes('404')) {
                    errorMessage = 'Service not found. Please refresh the page and try again.';
                } else if (error.message.includes('Network')) {
                    errorMessage = 'Network error. Please check your connection and try again.';
                }

                showFlashMessage(errorMessage, 'error');
                chatInput.value = message; // Restore the message
            } finally {
                // Re-enable input and button
                chatInput.disabled = false;
                sendBtn.disabled = false;
                sendBtn.style.opacity = '1';
                chatInput.focus();
            }
        }

        // Add message to chat UI
        function addMessageToChat(message, type, sending = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `msg ${type}`;

            if (sending) {
                messageDiv.classList.add('sending');
                messageDiv.style.opacity = '0.7';
            }

            messageDiv.textContent = message;

            // Add animation
            messageDiv.style.transform = 'translateY(20px)';
            messageDiv.style.opacity = sending ? '0.7' : '0';

            chatBody.appendChild(messageDiv);

            // Animate message in
            setTimeout(() => {
                messageDiv.style.transition = 'all 0.3s ease';
                messageDiv.style.transform = 'translateY(0)';
                messageDiv.style.opacity = sending ? '0.7' : '1';
            }, 10);

            // Scroll to bottom
            setTimeout(() => {
                chatBody.scrollTop = chatBody.scrollHeight;
            }, 100);
        }

        // Send button click handler
        sendBtn.addEventListener('click', sendChatMessage);

        // Enter key handler
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendChatMessage();
            }
        });

        // Add some CSS animations via JavaScript
        const style = document.createElement('style');
        style.textContent = `
            .msg.sending {
                position: relative;
            }
            .msg.sending::after {
                content: '';
                position: absolute;
                right: -20px;
                top: 50%;
                transform: translateY(-50%);
                width: 12px;
                height: 12px;
                border: 2px solid #ddd;
                border-top: 2px solid #0d4036;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            .msg.sent {
                opacity: 1 !important;
            }
            @keyframes spin {
                0% { transform: translateY(-50%) rotate(0deg); }
                100% { transform: translateY(-50%) rotate(360deg); }
            }
            #chat-input:disabled {
                background-color: #f5f5f5;
                cursor: not-allowed;
            }
        `;
        document.head.appendChild(style);

        // Flash message function for user feedback
        function showFlashMessage(message, type = 'info') {
            // Remove any existing flash messages
            const existingFlash = document.querySelector('.flash-message');
            if (existingFlash) {
                existingFlash.remove();
            }

            // Create flash message element
            const flashDiv = document.createElement('div');
            flashDiv.className = `flash-message flash-${type}`;
            flashDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                font-weight: 500;
                max-width: 350px;
                word-wrap: break-word;
                transform: translateX(400px);
                transition: all 0.3s ease;
                ${type === 'success' ? 'background: #d4edda; color: #155724; border-left: 4px solid #28a745;' : ''}
                ${type === 'error' ? 'background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545;' : ''}
                ${type === 'warning' ? 'background: #fff3cd; color: #856404; border-left: 4px solid #ffc107;' : ''}
                ${type === 'info' ? 'background: #d1ecf1; color: #0c5460; border-left: 4px solid #17a2b8;' : ''}
            `;

            // Add icon based on type
            const icon = type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' : 'ℹ️';
            flashDiv.innerHTML = `<strong>${icon}</strong> ${message}`;

            // Add to document
            document.body.appendChild(flashDiv);

            // Animate in
            setTimeout(() => {
                flashDiv.style.transform = 'translateX(0)';
            }, 10);

            // Auto remove after 4 seconds
            setTimeout(() => {
                flashDiv.style.transform = 'translateX(400px)';
                setTimeout(() => {
                    if (flashDiv.parentNode) {
                        flashDiv.parentNode.removeChild(flashDiv);
                    }
                }, 300);
            }, 4000);
        }

    });
</script>
