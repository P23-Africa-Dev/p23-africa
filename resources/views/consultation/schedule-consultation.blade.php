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

    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
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

    #monthInput {
        flex: 1;
        background: transparent;
        border: 1px solid #3b4a42;
        border-radius: 6px;
        padding: 8px 10px;
        color: #e7f2ec;
        font-family: "GT Walsheim Con";
        letter-spacing: 1.3px;
        text-align: center;
        border: none;
        font-size: 22px;
    }

    #monthInput:focus {
        border: none;
        outline: none;
    }

    #monthInput::placeholder {
        color: #fff;
        font-size: 19px;
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

    /* .date.today {
        background: #88C1B7;
        color: #0D4036 !important;
    } */

    .date.selected {
        background: #88C1B7;
        color: #0D4036 !important;
    }

    .selected-date, .selected-time {
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
        margin-bottom: 15px;
    }

    .timezone-row>label {
        font-size: 18px;
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
        margin-top: 60px;
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
    }

    .calendar-wrapper-container .mobile-btn button img {
        width: 12px;
        height: 12px;
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
        background: rgba(255, 255, 255, 0.7);
        /* <-- transparent white */
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
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
    }

    .cd-caret {
        opacity: 0.75;
        font-size: 12px;
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
        margin: 20px 0;
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
            font-size: 3rem;
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
            margin-top: -2rem;

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
            gap: 0px;
        }

        .schedule-conversation-section .left-content {
            padding-top: 17rem;
            /* flex: 0.38; */
            width: 55rem;
            /* border: 1px solid red; */
            /* max-width: 550px; */
        }

        .schedule-conversation-section .left-content h3 {
            font-size: 4.1rem;
            max-width: initial;
            margin-left: 60px;
            margin-bottom: 30px;
            width: 500px;
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
            max-width: 780px;
            /* border: 1px solid; */
            margin: 0 auto;
            width: 100%;
            /* display: none; */
        }

           #calendar-wrapper {
    
        margin-bottom: 0px;
    }

        #calendar-grid {
            max-width: 650px;
            margin: 0 auto;
            margin-right: 4rem;

        }

        .nav-row {
            margin-block: 30px;
            margin-right: 6rem;

        }

        .time-box {
            /* margin: 0px 40px; */
            margin-top: 50px;
            max-width: 650px;
            margin: 0 auto;
            background-color: transparent;
            display: flex;
            margin-top: 2rem;
            margin-bottom: 5rem;



        }

        .time-box-container {
            background-color: #0d403666;
            padding: 20px;
            border-radius: 10px;
        }

        .calendar-wrapper-container .output-box.mobile {
            display: none;
        }

        .calendar-wrapper-container .output-box.desktop {
            display: block;
            padding: 0px 40px;
        }

        #dates {
            gap: 0px;
        }

        .timezone-row {
            gap: 80px;
        }

        .date {
            padding: 4px;
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 35px;
            /* margin-left:  -5rem; */
        }

        .weekdays {
            gap: 10px;
            margin-bottom: 1rem;
            transform: translate(-20px);
        }

        .modal-box {
            width: 55vw;

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
                        <img src="../img/left-conv-pattern.png" class="content-pattern" alt="" />
                        <img src="../img/schedule-pattern.png" class="schedule-pattern" alt="" />
                        <div class="info-content">
                            <!-- SUBMIT BUTTON -->

                            <div class="btn">
                                <button class="submit-schedule">
                                    Submit
                                    <div><img src="../img/right.png" alt="" /></div>
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
                                        <img src="../img/left.png" alt="" />
                                    </button>
                                    <div class="prev-tooltip">
                                        You cannot go before the current month
                                    </div>
                                </div>

                                <input type="text" id="monthInput" placeholder="November 2025" />

                                <button id="nextMonth">
                                    <img src="../img/right-arrow.png" alt="" />
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
                                            <span class="cd-caret">▾</span>
                                        </button>
                                        <ul class="cd-list" role="listbox" aria-hidden="true">
                                            <li role="option" data-value="GMT" class="cd-option">GMT</li>
                                            <li role="option" data-value="UTC" class="cd-option">UTC</li>
                                            <li role="option" data-value="WAT" class="cd-option">WAT</li>
                                        </ul>
                                    </div>
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
                                <div><img src="../img/right-colored-r.png" alt="" /></div>
                            </button>
                        </div>
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
                <a href="#" class="submit-btn-modal">
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
                    value: "WAT"
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
                currentIndex: 28
            }, // Default to 28
            second: {
                element: null,
                items: [],
                currentIndex: 55
            }, // Default to 55
            ampm: {
                element: null,
                items: ['AM', 'PM'],
                currentIndex: 1
            } // Default to PM
        };

        // Initialize time wheels
        function initTimeWheels() {
            // Generate hours (01-12)
            for (let i = 1; i <= 12; i++) {
                timeWheels.hour.items.push(String(i).padStart(2, '0'));
            }

            // Generate minutes/seconds (00-59)
            for (let i = 0; i <= 59; i++) {
                timeWheels.minute.items.push(String(i).padStart(2, '0'));
                timeWheels.second.items.push(String(i).padStart(2, '0'));
            }

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
            timeWheels[type].currentIndex = index;
            const list = timeWheels[type].element.querySelector('.time-wheel-list');

            const itemHeight = 40;
            const centerOffset = -index * itemHeight;
            list.style.transform = `translateY(${centerOffset}px)`;

            updateActiveItem(type);
            updateTimeDisplay();
        }

        function updateActiveItem(type) {
            const items = timeWheels[type].element.querySelectorAll('.time-wheel-item');
            items.forEach((item, index) => {
                item.classList.toggle('active', index === timeWheels[type].currentIndex);
            });
        }

        // Initialize the time wheels
        initTimeWheels();

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

            monthInput.value = `${current.toLocaleString("default", {
        month: "long"
    })} ${year}`;

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
                div.onclick = () => selectDate(prevDate, div);

                datesContainer.appendChild(div);
            }

            /* --------------------------- CURRENT MONTH DAYS --------------------------- */
            for (let day = 1; day <= daysInMonth; day++) {
                const dateObj = new Date(year, month, day);
                const div = document.createElement("div");
                div.className = "date";
                div.textContent = day;

                // highlight today
                if (
                    dateObj.toDateString() === today.toDateString()
                ) {
                    div.classList.add("today");
                }

                // disable past days in current month
                if (year === minYear && month === minMonth && day < today.getDate()) {
                    div.classList.add("disabled");
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
                div.onclick = () => selectDate(nextDate, div);

                datesContainer.appendChild(div);
            }

            updatePrevButtonState();
        }

        function selectDate(dateObj, div) {
            selectedDate = dateObj;

            document.querySelectorAll(".date").forEach((d) => d.classList.remove("selected"));
            div.classList.add("selected");

            updateAllSelectedDate(selectedDate.toDateString());
            updateTimeDisplay();
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

        /* ------------------------- Month input typing block ------------------------- */
        monthInput.addEventListener("change", () => {
            const parts = monthInput.value.trim().split(" ");
            if (parts.length !== 2) return;

            const [monthName, yearStr] = parts;
            const year = parseInt(yearStr);
            const monthIndex = new Date(`${monthName} 1, ${year}`).getMonth();

            if (isNaN(monthIndex)) return;
            if (year < minYear) return;
            if (year === minYear && monthIndex < minMonth) return; // too early

            current = new Date(year, monthIndex);
            renderCalendar();
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

                modalDateText.textContent = "Date: " + selectedDate.toDateString();
                modalTimeText.textContent =
                    "Time: " +
                    `${hour}:${minute}:${second} ${ampm} ${timezoneDropdown.getValue()}`;

                modal.style.display = "flex";
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

    });
</script>
