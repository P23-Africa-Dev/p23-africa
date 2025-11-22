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
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    img {
        max-width: 100%;
    }

    .box-container {
        max-width: initial;
        margin: 0 auto;
    }

    .book-conversation-section {
        background-image: url(../img/first-bg.png);
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        padding: 2rem 0rem 0rem 0rem;

        overflow-x: hidden;
    }

    .book-conversation-section .intro-container {
        display: flex;
        flex-direction: column;
        gap: 40px;
    }

    .book-conversation-section .left-content {
        margin-top: 5rem
    }

    .book-conversation-section .left-content h3 {
        font-size: 2.1rem;
        color: #0d4036;
        font-family: "GT Walsheim ProCB";
        line-height: 1.1;
        padding: 0 20px 0px 40px;
        max-width: 370px;
    }

    .book-conversation-section .left-content .info-content {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-top: 20px;
    }

    .book-conversation-section .left-content .info-content div.btn {
        align-self: start;
        margin-left: -11px;
    }

    .book-conversation-section .left-content .info-content div.btn a {
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
    }

    .book-conversation-section .left-content .info-content div.btn a div {
        width: 15px;
        height: 15px;
        position: relative;
    }

    .book-conversation-section .left-content .info-content div.btn a div img {
        object-fit: cover;
    }

    .book-conversation-section .left-content .info-content {
        position: relative;
        left: -40px;
    }

    .book-conversation-section .left-content .info-content div.para p {
        color: #0d4036;
        font-family: "GT Walsheim Trial Cn Lt";

        font-size: 1.2rem;
        line-height: 1.2;
        margin-top: 10px;
        margin-right: 20px;
    }

    .book-conversation-section .left-content .info-content div.para p span {
        font-weight: bold;
        font-family: "GT Walsheim ProCB";
    }

    .book-conversation-section .info {
        position: relative;
        display: flex;
        margin-top: 20px;
    }

    .book-conversation-section .left-content .content-pattern {
        width: 120px;
        height: 100px;
        position: relative;
        left: -70px;
    }

    .book-conversation-section .right-content {
        background-color: #06221d;
        margin-bottom: 20px;
        width: 100%;

    }

    .book-conversation-section .right-content img.desktop {
        display: none;
    }

    .book-conversation-section .right-content img.mobile {
        width: 100%;
    }

    @media (min-width: 768px) {
        .book-conversation-section .left-content h3 {
            font-size: 2.7rem;
            padding-left: 40px;

            max-width: 650px;
        }

        .book-conversation-section .left-content .info-content {
            max-width: 500px;
        }

        .book-conversation-section .left-content .info-content div.para p {
            font-size: 1.5rem;
        }
    }

    @media (min-width: 1024px) {
        .book-conversation-section {
            padding: 0rem;
        }

        .book-conversation-section .intro-container {
            flex-direction: column;
            gap: 80px;
        }

        .book-conversation-section .left-content {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        .book-conversation-section .left-content h3 {
            font-size: 3rem;
        }

        .book-conversation-section .right-content {
            position: relative;
            height: 100%;
            min-height: 100%;
        }

        .book-conversation-section .right-content img.desktop {
            display: block;
            object-fit: cover;
            width: 100%;
        }

        .book-conversation-section .right-content img.mobile {
            display: none;
        }
    }

    @media (min-width: 1200px) {
        .book-conversation-section .intro-container {
            flex-direction: row;
            gap: 80px;
        }

        .book-conversation-section .left-content {
            padding-top: 10rem;
            margin-top: 0rem
        }

        .book-conversation-section .left-content h3 {
            font-size: 4rem;
        }

        .book-conversation-section .right-content {
            margin-bottom: 0px;
        }
    }

    @media (min-width: 1400px) {
        .book-conversation-section .intro-container {
            gap: 0px;
        }

        .book-conversation-section .left-content {
            padding-top: 10rem;
            /* flex: 0.38; */
            width: 55rem;
            /* border: 1px solid red; */
            /* max-width: 550px; */
        }

        .book-conversation-section .left-content h3 {
            font-size: 4.1rem;
            max-width: initial;
            margin-left: 60px;
            margin-bottom: 30px;
            line-height: 1;

            width: 500px;
        }

        .book-conversation-section .left-content .info-content {
            position: relative;
            left: 18px;
            margin-top: 0px;
            max-width: initial;
        }

        .book-conversation-section .left-content .info-content div.para p {
            font-size: 1.3rem;
            max-width: 260px;
            margin-top: 20px;
        }

            .book-conversation-section .left-content .info-content div.btn {
        align-self: start;
        margin-left: 0px;
    }

        .book-conversation-section .left-content .info-content div.btn a {
            padding: 1.2rem 2.5rem;
        }

        .book-conversation-section .left-content .content-pattern {
            width: 150px;
            height: 105px;
            position: relative;
            left: -8px;
            line-height: 1;
            /* transform: scale(1.2); */
        }

        /* =================================================  RIGHT CONTNENT */
        .book-conversation-section .right-content {
            position: relative;
            width: 100%;
            /* border: 1px solid red; */
            display: flex;
            align-items: center;
            justify-content: flex-end;
            background-color: transparent;
            /* min-height: 100vh; */
            /* padding-bottom: 2rem; */
            margin-bottom: 0px;
            /* margin-top: 50px; */
            /* height: 600px; */
            /* background-image: url(../img/first-image.png); */


        }


        /* .book-conversation-section .right-content::before {
            content: "";
            position: absolute;
            right: 0;
            top: 0;
            width: 70%;
            height: 100%;
            margin-top: 40px;

            background-color: #06221d;
            z-index: 1;
            scrollbar-width: none;

        } */

        .book-conversation-section .desktop-image-cont,
        .book-conversation-section .right-content img {
            position: relative;

            z-index: 3;
        }

        .book-conversation-section .desktop-image-cont {
            /* width: 86%;
            transform: scale(1.15);
            scrollbar-width: none; */
            overflow: hidden;
        }



        .book-conversation-section .desktop-image-cont img.desktop {
            object-fit: top;
            max-width: 100%;
            display: block;
            position: relative;
            top: 48px;
            overflow: auto;
            scrollbar-width: none;

            scrollbar-width: none;
            padding-top: 1rem;
            min-height: 820px;



        }


    }
</style>

@section('content')

    <section class="book-conversation-section">
        <div class="box-container">
            <div class="intro-container">
                <div class="left-content">
                    <h3>Let’s help you unlock growth clarity for your business.</h3>

                    <div class="info">
                        <img src="{{ asset('img/left-conv-pattern.png') }}" class="content-pattern" alt="" />
                        <div class="info-content">
                            <div class="btn">
                                <a href="{{ route('consultation.application-forms') }}">Let’s get started
                                    <div>
                                        <img src="{{ asset('img/right.png') }}" alt="" />
                                    </div>
                                </a>
                            </div>
                            <div class="para">
                                <p>
                                    <span>The consultation</span> will analyze your challenges,
                                    identify growth blockers, and design a roadmap to help you
                                    scale sustainably in African markets.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-content">
                    <img class="mobile" src="{{ asset('img/mobile-main-bg.png') }}" loading="lazy" alt="" />
                    <div class="desktop-image-cont">
                        <img class="desktop" loading="lazy" src="{{ asset('img/first-image.png') }}" alt="" />
                        {{-- <img class="desktop" loading="lazy" src="../img/right-desktop-bg.png" alt="" /> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


<script></script>
