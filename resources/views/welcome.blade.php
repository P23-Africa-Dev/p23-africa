@extends('layouts.master')
@section('title', 'Business Consulting & Growth Solutions for Africa | P23 Africa')
@section('meta')
    <meta name="description"
        content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
    <meta name="keywords"
        content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Business Consulting & Growth Solutions for Africa | P23 Africa">
    <meta property="og:image" content="https://p23africa.com/images/homepage-thumbnail-og.png">
@endsection

<style>

 
    /* Resource Report PDF */
    .pdf-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 1000;
        padding: 20px;
    }

    .pdf-popup-content {
        background: white;
        width: 100%;
        max-width: 1000px;
        height: 90vh;
        margin: 20px auto;
        position: relative;
        border-radius: 8px;
        padding: 20px;
    }

    .pdf-viewer {
        width: 100%;
        height: calc(100% - 50px);
        border: none;
    }

    .close-popup {
        position: absolute;
        right: 20px;
        top: 10px;
        font-size: 24px;
        cursor: pointer;
        color: #333;
    }

    .download-btn {
        display: inline-block;
        padding: 8px 16px;
        background: #28a745;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .download-btn:hover {
        background: #218838;
        color: white;
    }

    .hero-text a {
        margin-top: 5px;
        padding: 20px 40px !important;
        border-radius: 10px;
        font-size: 20px;
        font-weight: 400;
        width: 38% !important;
    }
    .hero.content-section a{
        font-size: 20px;

    }

    #highlight a {
        padding: 15px 40px;
        /* height: 64px;
        width: 351px; */
        border-radius: 10px;
        background-color: #0D4036;
        
        text-align: center;
        color: #fff;
        border: 1px solid #fff;
        font-size: 13px;
        cursor: pointer;
        text-decoration: none;
        transition: 500ms;
        font-family: "GT Walsheim Con";
        font-weight: 400;
        /* width: 45%; */
    }

    #highlight .book {
        margin-top: 58px;
        z-index: 999 !important;
        position: relative;
    }

    #highlight a:hover {
        background-color: #fff;
        color: #003c32;
    }

    @media screen and (max-width: 580px) {
        .hero-text a {
            margin-top: -15px;
            padding: 20px 50px !important;
            border-radius: 10px;
            font-size: 20px;
            width: 70% !important;
        }

        #how_we_help {
            margin-bottom: 0rem !important;
        }
            .hero.content-section a{
        font-size: 16px;

    }


        /* #how_we_help .service-card {
            padding-right: 90px !important;
        } */
    }


    .network-section {
        position: relative;
        width: 100%;
        background-image: url(../img/network-effect-bg.png);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        /* background-color: #0B1727;
        background-blend-mode: soft-light; */
        overflow: hidden;
        margin-bottom: 90px;
    }



    .network-container {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        padding: 5rem 0rem;

    }

    .network-header {
        text-align: center;
        margin-bottom: 3rem;
        color: white;

    }

    .network-body {
        display: flex;
        flex-direction: column;
    }


    .network-header p {

        font-size: 1.3rem;
        letter-spacing: 0.9px;
        line-height: 0.8;
         font-family: 'GT Walsheim Trial Cn Th';


        /* font-weight: 100; */


    }

    .network-header h2 {
        line-height: 0.2;
        font-size: 1.3rem;
        letter-spacing: 0.8px;

        font-family: "GT Walsheim ProCB";


    }


    .network-content {
        display: flex;
        text-align: center;
        align-items: center;
        justify-content: center;

        flex-direction: column;
        gap: 30px;
        color: #fff;
    }



    .network-content .network-content-bottom h3.content-title {
        font-family: "GT Walsheim ProCB";
        font-size: 30px;
    }

    .network-content .network-content-bottom p.content-desc {
        font-family: "GT Walsheim Trial Cn Lt";
        padding: 0rem 3.4rem;
        letter-spacing: 0.5px;
        font-weight: 100;
        font-size: 17px;
    }


    .network-content .network-content-bottom .cta-button {
        display: inline-flex;
        align-items: center;
        gap: 1rem;
        background-color: #27e6a7;
        color: #04201a;
        padding: 0.7rem 2.7rem;
        border-radius: 10px;
        text-decoration: none;
        font-family: "GT Walsheim Trial Cn Lt";

        font-weight: 400;
        font-size: 1rem;
        letter-spacing: 0.9px;
        margin-top: 1.4rem;
        transition: background-color .7s;



    }

    .network-content .network-content-bottom .cta-button:hover {

        background-color: #fff;




    }

    .network-content .network-content-bottom .cta-button img {
        width: 15px;
    }

    .network-content .network-content-top p {
        display: flex;
        flex-direction: column;
        gap: 0px;
        /* letter-spacing: 1.5px */
    }

    .network-content .network-content-top p span {

        /* letter-spacing: 1px; */
        line-height: 1;
        font-size: 1.3rem;
    }

    .network-content .network-content-top p.match-text {
        margin-top: 3rem;
        /* margin-bottom: 3rem; */
        color: #27E6A7;
        font-family: "Montserrat", sans-serif;
        font-weight: bold;

    }

    /* Cards */
    .network-cards {
        display: flex;
        justify-content: center;
        margin-bottom: 1.4rem;

    }

    .card-wrapper {
        position: relative;
        overflow: visible;
      
        overflow: hidden;

        /* height: 300px; */

    }

        .card-wrapper img {
     
        overflow: visible;
        max-width: 100%;
        object-fit: cover;
    }


    .card-wrapper.left-card-image {
        left: 30px;
        top: 70px;
        /* border:  1px solid red; */

    }

    .card-wrapper.right-card-image {
        right: 30px;
        top: -10px;
    }

    .card-wrapper.right-card-image img.desktop {
        display: none
    }



    /* Prevent flicker */
    .left-card-image,
    .right-card-image,
    .network-content-top {
        opacity: 0;
        will-change: transform, opacity;
    }

    /* Optional smoother motion */
    .network-content {
        overflow: hidden;
    }


    @media (min-width: 992px) {

   
         .network-container {
     
        padding-bottom: 0rem;

    }
        .network-body {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }

        .network-content {
            text-align: left;
            align-items: start;
            justify-content: center;

            flex-direction: column-reverse;
            gap: 0px;

            width: 500px;

        }


        .network-header p {

            font-size: 3.6rem;
            letter-spacing: 1.3px;
            line-height: 1.4;


            font-weight: 100;


        }

        .network-header h2 {
            line-height: 0.2;
            font-size: 3.6rem;



        }

        .network-content {
            transform: translate(-30px, 0px);
        }

        .network-content .network-content-bottom p.content-desc {
            padding: 0rem;

            font-size: 19px;
            line-height: 1.3;
            letter-spacing: 0.9px;
            width: 380px;
        }

        .network-cards {
            width: 700px;
            height: 600px;
            margin: 1rem 0rem;
            transform: translate(-30px, -15px);
            overflow: hidden;
            padding-left: 100px;
            padding-top: 20px;
      



        }

        .card-wrapper {
            position: relative;
            overflow: visible;
                   width: 600px;
        height: 600px;

        }

        .card-wrapper img {
            /* width: 100%;
            max-width: 100%; */
             object-fit: cover;
          width: 100%;
            /* height: 430px; */
        }

        .card-wrapper.left-card-image {
            left: 95px;
            top: 145px;

        }

        .card-wrapper.right-card-image {
            right: 20px;
            top: -20px;
        }

        .card-wrapper.right-card-image img.desktop {
            display: block;
        }

        .card-wrapper.right-card-image img.mobile {
            display: none;
        }

        .network-section{
            margin-bottom: 0px;
        }


        .network-content .network-content-bottom {
            margin-top: 6rem;
        }

        .network-content .network-content-bottom .cta-button {
            font-size: 20px;
        }

        .network-content .network-content-top {
            margin-top: 1rem;
        }

        .network-content .network-content-top p {
            margin-top: 1.5rem;
            margin-bottom: 0rem;



        }

        .network-content .network-content-top p span {
            font-size: 1.8rem;
        }

           .network-content .network-content-top p.match-text {
        margin-top: 1.6rem;
    

    }


    #highlight a{
        font-size: 17px;
        padding: 17px 43px;

    }

    .who-we-are h2{
        font-size: 42px !important;
        margin-bottom: 13.2px !important;
    }

    .who-we-are p{
        line-height: 29px !important;
        font-size: 18px !important;
        max-width: 520px !important;
      
    }


    }

    /* ========================================================
       NEW SECTIONS: CAPABILITIES, APPROACH, SECTORS, WHY P23, CTA
       ======================================================== */

    /* 1. Core Capabilities */
    .capabilities-grid {
        margin-bottom: 2rem;
    }
    .capability-card {
        background-color: #ffffff;
        border: 1px solid rgba(13, 64, 54, 0.1);
        border-radius: 16px;
        padding: 2.2rem 2rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        position: relative;
        overflow: hidden;
    }
    .capability-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: transparent;
        transition: background 0.3s ease;
    }
    .capability-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 36px rgba(13, 64, 54, 0.1);
        border-color: rgba(39, 230, 167, 0.4);
    }
    .capability-card:hover::before {
        background: #27E6A7;
    }
    .capability-card.featured-card {
        background: #003c32;
        color: #ffffff;
        border-color: #003c32;
    }
    .capability-card.featured-card .capability-title {
        color: #ffffff;
    }
    .capability-card.featured-card .capability-desc {
        color: #d0eee9;
    }
    .capability-card.featured-card .capability-link {
        color: #27E6A7;
    }
    .capability-icon {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        background: rgba(39, 230, 167, 0.15);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }
    .capability-card.featured-card .capability-icon {
        background: rgba(255, 255, 255, 0.12);
    }
    .capability-icon img {
        width: 28px;
        height: 28px;
        object-fit: contain;
    }
    .capability-icon i {
        font-size: 24px;
        color: #0d4036;
    }
    .capability-card.featured-card .capability-icon i {
        color: #27E6A7;
    }
    .capability-title {
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #0d4036;
        margin-bottom: 0.8rem;
    }
    .capability-desc {
        font-family: "GT Walsheim Trial Cn Rg", sans-serif;
        font-size: 17px;
        line-height: 1.55;
        color: #556461;
        margin-bottom: 1.4rem;
        flex-grow: 1;
    }
    .capability-link {
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 15px;
        font-weight: 600;
        color: #0d4036;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: gap 0.25s ease;
    }
    .capability-link:hover {
        gap: 12px;
        color: #27E6A7;
    }

    /* 2. Approach Section */
    .approach-section {
        padding: 6rem 0;
        background-color: #f7faf9;
        border-top: 1px solid #e7ecea;
        border-bottom: 1px solid #e7ecea;
    }
    .section-eyebrow {
        display: inline-block;
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        color: #0d4036;
        background: rgba(39, 230, 167, 0.2);
        padding: 5px 14px;
        border-radius: 20px;
        margin-bottom: 1rem;
    }
    .approach-header h2 {
        font-family: "Montserrat", sans-serif;
        font-size: 40px;
        font-weight: 700;
        color: #101e1a;
        margin-bottom: 0.5rem;
    }
    .approach-subline {
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 22px;
        color: #0d4036;
        font-weight: 600;
        margin-bottom: 2rem;
    }
    .approach-callout {
        background: #ffffff;
        border-left: 5px solid #27E6A7;
        border-radius: 12px;
        padding: 1.8rem 2.2rem;
        margin-bottom: 3.5rem;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
    }
    .approach-callout p {
        font-family: "GT Walsheim Trial Cn Rg", sans-serif;
        font-size: 20px;
        line-height: 1.6;
        color: #1a2e29;
        margin: 0;
    }
    .approach-callout p strong {
        color: #0d4036;
        font-family: "GT Walsheim ProCB", sans-serif;
    }
    .step-card {
        background: #ffffff;
        border: 1px solid #e4eae7;
        border-radius: 16px;
        padding: 2.2rem 1.8rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        position: relative;
    }
    .step-card:hover {
        transform: translateY(-5px);
        border-color: #27E6A7;
        box-shadow: 0 12px 30px rgba(13, 64, 54, 0.08);
    }
    .step-badge {
        font-family: "Montserrat", sans-serif;
        font-size: 13px;
        font-weight: 800;
        color: #0d4036;
        background: #d0eee9;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 1.2rem;
        width: fit-content;
    }
    .step-title {
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #101e1a;
        margin-bottom: 0.8rem;
    }
    .step-desc {
        font-family: "GT Walsheim Trial Cn Rg", sans-serif;
        font-size: 16.5px;
        line-height: 1.55;
        color: #556461;
        margin: 0;
    }

    /* 3. Sectors Section */
    .sectors-section {
        padding: 6rem 0;
        background-color: #ffffff;
    }
    .sectors-header {
        text-align: center;
        max-width: 780px;
        margin: 0 auto 3.5rem auto;
    }
    .sectors-header h2 {
        font-family: "Montserrat", sans-serif;
        font-size: 38px;
        font-weight: 700;
        color: #101e1a;
        margin-top: 0.8rem;
        margin-bottom: 0.75rem;
    }
    .sectors-header p {
        font-family: "GT Walsheim Trial Cn Rg", sans-serif;
        font-size: 18px;
        color: #60726d;
        margin: 0;
    }
    .sector-card {
        background: #fafcfb;
        border: 1px solid #e7efe9;
        border-radius: 14px;
        padding: 1.8rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }
    .sector-card:hover {
        background: #ffffff;
        border-color: #0d4036;
        transform: translateY(-4px);
        box-shadow: 0 10px 28px rgba(13, 64, 54, 0.08);
    }
    .sector-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        background: rgba(13, 64, 54, 0.06);
        color: #0d4036;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 1.2rem;
        transition: all 0.3s ease;
    }
    .sector-card:hover .sector-icon {
        background: #27E6A7;
        color: #04201a;
    }
    .sector-title {
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 19px;
        font-weight: 700;
        color: #101e1a;
        margin-bottom: 0.65rem;
    }
    .sector-desc {
        font-family: "GT Walsheim Trial Cn Rg", sans-serif;
        font-size: 15.5px;
        line-height: 1.5;
        color: #556461;
        margin: 0;
    }

    /* 4. Why P23 Africa Section */
    .why-section {
        padding: 6.5rem 0;
        background-color: #003c32;
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .why-header {
        margin-bottom: 3.5rem;
    }
    .why-header .why-eyebrow {
        color: #27E6A7;
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 0.8rem;
    }
    .why-header h2 {
        font-family: "Montserrat", sans-serif;
        font-size: 42px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.6rem;
    }
    .why-tagline {
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 22px;
        color: #27E6A7;
        margin-bottom: 1.4rem;
    }
    .why-lead {
        font-family: "GT Walsheim Trial Cn Rg", sans-serif;
        font-size: 19px;
        line-height: 1.6;
        color: #d0eee9;
        max-width: 800px;
        margin: 0;
    }
    .why-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        padding: 2.2rem;
        height: 100%;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    .why-card:hover {
        background: rgba(255, 255, 255, 0.09);
        border-color: #27E6A7;
        transform: translateY(-4px);
    }
    .why-card-icon {
        font-size: 24px;
        color: #27E6A7;
        margin-bottom: 1.2rem;
    }
    .why-card-title {
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.8rem;
    }
    .why-card-desc {
        font-family: "GT Walsheim Trial Cn Rg", sans-serif;
        font-size: 16.5px;
        line-height: 1.6;
        color: #d0eee9;
        margin: 0;
    }

    /* 5. Closing Pre-Footer CTA */
    .cta-closing-section {
        padding: 6.5rem 0;
        background: linear-gradient(135deg, #022b24 0%, #001a14 100%);
        color: #ffffff;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .cta-closing-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: 50%;
        transform: translateX(-50%);
        width: 600px;
        height: 350px;
        background: radial-gradient(circle, rgba(39, 230, 167, 0.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .cta-closing-inner {
        position: relative;
        z-index: 2;
        max-width: 820px;
        margin: 0 auto;
    }
    .cta-closing-inner .cta-pill {
        display: inline-block;
        background: rgba(39, 230, 167, 0.15);
        border: 1px solid rgba(39, 230, 167, 0.3);
        color: #27E6A7;
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        padding: 6px 18px;
        border-radius: 20px;
        margin-bottom: 1.5rem;
    }
    .cta-closing-inner h2 {
        font-family: "Montserrat", sans-serif;
        font-size: 42px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 1.5rem;
        line-height: 1.25;
    }
    .cta-closing-inner .cta-desc {
        font-family: "GT Walsheim Trial Cn Rg", sans-serif;
        font-size: 19px;
        line-height: 1.6;
        color: #d0eee9;
        margin-bottom: 1.6rem;
    }
    .cta-closing-inner .cta-prompt {
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 22px;
        color: #27E6A7;
        margin-bottom: 2.5rem;
    }
    .cta-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background-color: #27E6A7;
        color: #04201a !important;
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 18px;
        font-weight: 600;
        padding: 15px 36px;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 8px 24px rgba(39, 230, 167, 0.3);
    }
    .cta-btn-primary:hover {
        background-color: #ffffff;
        color: #003c32 !important;
        transform: translateY(-2px);
    }
    .cta-btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background-color: transparent;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.35);
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 18px;
        font-weight: 600;
        padding: 15px 36px;
        border-radius: 10px;
        text-decoration: none;
        margin-left: 1rem;
        transition: all 0.3s ease;
    }
    .cta-btn-secondary:hover {
        border-color: #ffffff;
        background-color: rgba(255, 255, 255, 0.08);
        transform: translateY(-2px);
    }

    @media screen and (max-width: 768px) {
        .approach-header h2,
        .sectors-header h2,
        .why-header h2,
        .cta-closing-inner h2 {
            font-size: 30px !important;
        }
        .approach-subline,
        .why-tagline,
        .cta-closing-inner .cta-prompt {
            font-size: 19px !important;
        }
        .cta-btn-secondary {
            margin-left: 0;
            margin-top: 1rem;
            width: 100%;
            justify-content: center;
        }
        .cta-btn-primary {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@section('content')
    <header id="body">
        <div id="header">
            <section class="hero content-section animate-on-scroll">
                <div class="row hero-sec-row">
                    <div class="col-md-7 hero-text my-auto">
                        <h1>Helping Global <br> Businesses Enter &amp; <br><span id="fonts">Grow Across Africa</span></h1>
                        <br>
                        <p>
                          We help you understand African markets, validate opportunities, acquire customers and partners, and establish the foundations for sustainable growth.
                        </p>
                        <br>
                        <div>
                            <a href="{{ url('contact') }}" class="d-flex justify-content-between"><span>Contact Us</span>
                                <span>→</span></a>
                        </div>
                    </div>
                    <div class="col-md-5 animated-images mt-5 mt-md-0">
                        <div class="fade-overlay fade-top"></div>
                        <div class="fade-overlay fade-bottom"></div>

                        <div class="column">
                            @foreach ($images as $image)
                                <img src="{{ asset('images/' . $image) }}" loading='lazy' class=""
                                    alt="header-slider">
                            @endforeach

                            @foreach ($images as $image)
                                <img src="{{ asset('images/' . $image) }}" loading='lazy' class=""
                                    alt="header-slider">
                            @endforeach
                        </div>
                        <div class="column2">
                            @foreach ($images2 as $image2)
                                <img src="{{ asset('images/' . $image2) }}" loading='lazy' class=""
                                    alt="header-slider">
                            @endforeach

                            @foreach ($images2 as $image2)
                                <img src="{{ asset('images/' . $image2) }}" loading='lazy' class=""
                                    alt="header-slider">
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </header>

    <div style="background-color: #003c32;" id="space">

    </div>
    <section id="highlight"
        class="position-relative overflow-hidden content-section d-flex align-items-center justify-content-center">
        <div class="highlight-overlay top-0 start-0"></div>
        <div class="me-auto ms-auto text-center" style="width: 60%;">
            <h3>
                We help you identify what’s holding your business back and map a clear path forward.
            </h3>
            <div class="book">
                <a href="{{ url('book-consultation') }}" class="">Book a Consultation</a>
            </div>
        </div>
    </section>
    <div style="background-color: #003c32;" class="py-3">

    </div>


    <section class="container-fluid p-0 who-we-are content-section animate-on-scroll">
        <div class="row g-0 align-items-center">
            <!-- Left Column -->
            <div class="col-md-6 position-relative overflow-hidden d-flex align-items-center justify-content-center">
                <div class="bg-overlay position-absolute top-0 start-0"></div>
                <img src="{{ asset('images/about.jpg') }}" loading='lazy' alt="People"
                    class="img-fluid position-relative z-2 p-3 p-md-5">
            </div>

            <!-- Right Column -->
            <div class="col-md-6 d-flex flex-column justify-content-center my-auto" style="display: flex !important; flex-direction: column !important; justify-content: center !important;">
                <h2 class="fw-bold mb-4">Who We Are</h2>
                <p class="mb-3">
                    <strong>Africa is the future. Getting it right requires local understanding.</strong>
                </p>
                <p class="mb-3">
                    Africa is not a single market. Each country has its own customers, regulations, commercial environment, routes to market, competitive landscape and business culture.
                </p>
                <p class="mb-3">
                    For companies looking to enter or expand, the challenge is often not identifying the opportunity, it is knowing where to focus, who to work with and how to execute successfully.
                </p>
                <p class="mb-4">
                    <strong>P23 Africa bridges that gap.</strong> We combine market intelligence, commercial expertise and local networks to help businesses move from market opportunity to practical execution.
                </p>
                <div>
                    <a href="{{ url('about') }}" class="text-decoration-none learn-more"
                        title="Learn more about P23 Africa">
                        Learn more
                        {{-- Learn more about P23 Africa --}}
                        <img src="{{ asset('images/arrow.png') }}" loading='lazy' alt="">
                    </a>
                </div>
            </div>
    </section>

    <section id="how_we_help" class="content-section animate-on-scroll">
        <div class="py-5 content">
            <span class="section-eyebrow">OUR CAPABILITIES</span>
            <h2 class="section-title">Commercial Solutions for African Expansion</h2>
            <div class="custom-underline"></div>

            <div class="row g-4 capabilities-grid">
                <!-- 1. Market Entry & Expansion -->
                <div class="col-lg-4 col-md-6">
                    <div class="capability-card featured-card">
                        <div>
                            <div class="capability-icon">
                                <i class="bi bi-compass"></i>
                            </div>
                            <h3 class="capability-title">Market Entry & Expansion</h3>
                            <p class="capability-desc">Build practical strategies for entering and developing new African markets.</p>
                        </div>
                        <a href="{{ url('services') }}#market" class="capability-link">
                            Explore market entry <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- 2. Sales & Business Development -->
                <div class="col-lg-4 col-md-6">
                    <div class="capability-card">
                        <div>
                            <div class="capability-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <h3 class="capability-title">Sales & Business Development</h3>
                            <p class="capability-desc">Generate opportunities, develop relationships and build commercial pipelines.</p>
                        </div>
                        <a href="{{ url('services') }}#sales" class="capability-link">
                            Explore business development <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- 3. Distributor & Partner Development -->
                <div class="col-lg-4 col-md-6">
                    <div class="capability-card">
                        <div>
                            <div class="capability-icon">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                            <h3 class="capability-title">Distributor & Partner Development</h3>
                            <p class="capability-desc">Find, assess and verify the right local commercial relationships.</p>
                        </div>
                        <a href="{{ url('services') }}" class="capability-link">
                            Explore partner development <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- 4. Operations & Market Support -->
                <div class="col-lg-6 col-md-6">
                    <div class="capability-card">
                        <div>
                            <div class="capability-icon">
                                <i class="bi bi-gear-wide-connected"></i>
                            </div>
                            <h3 class="capability-title">Operations & Market Support</h3>
                            <p class="capability-desc">Build the local capabilities and relationships required to operate effectively.</p>
                        </div>
                        <a href="{{ url('services') }}" class="capability-link">
                            Explore operational support <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- 5. Investment & Growth Readiness -->
                <div class="col-lg-6 col-md-12">
                    <div class="capability-card">
                        <div>
                            <div class="capability-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h3 class="capability-title">Investment & Growth Readiness</h3>
                            <p class="capability-desc">Strengthen businesses preparing for investment, partnerships or significant expansion.</p>
                        </div>
                        <a href="{{ url('services') }}" class="capability-link">
                            Explore growth readiness <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="global_reach">
            <h2><strong>Global Reach.</strong> <span>Local Approach</span></h2>

            <div class="row justify-content-center text-center stats me-auto ms-auto mt-5">
                <div class="col-6 col-md-3 stat-box">
                    <div class="number" id="countries"></div>
                    <div class="label">Countries</div>
                </div>
                <div class="col-6 col-md-3 stat-box">
                    <div class="number" id="businesses"></div>
                    <div class="label">Businesses</div>
                </div>
                <div class="col-6 col-md-3 stat-box">
                    <div class="number" id="partnership"></div>
                    <div class="label">Partnership</div>
                </div>
                <div class="col-6 col-md-3 stat-box">
                    <div class="number" id="network"></div>
                    <div class="label">Expert Network</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Methodology / Approach Section -->
    <section id="approach" class="approach-section content-section animate-on-scroll">
        <div class="container">
            <div class="approach-header">
                <span class="section-eyebrow">OUR METHODOLOGY</span>
                <h2>A Practical Approach to African Expansion</h2>
                <div class="approach-subline">Understand. Validate. Connect. Execute.</div>
            </div>

            <div class="approach-callout">
                <p>
                    <strong>We don’t believe in producing market reports that sit on a shelf.</strong>
                    Our approach is designed to help businesses make better-informed commercial decisions and take practical action.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <span class="step-badge">STAGE 01</span>
                        <h3 class="step-title">Understand</h3>
                        <p class="step-desc">We research the market, sector, customer landscape and competitive environment.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <span class="step-badge">STAGE 02</span>
                        <h3 class="step-title">Validate</h3>
                        <p class="step-desc">We test assumptions and gather real-world feedback from customers, partners and market participants.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <span class="step-badge">STAGE 03</span>
                        <h3 class="step-title">Connect</h3>
                        <p class="step-desc">We identify relevant customers, distributors, partners, investors and other stakeholders.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <span class="step-badge">STAGE 04</span>
                        <h3 class="step-title">Execute</h3>
                        <p class="step-desc">We support the commercial activity required to turn the opportunity into a functioning market presence.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sectors Section -->
    <section id="sectors" class="sectors-section content-section animate-on-scroll">
        <div class="container">
            <div class="sectors-header">
                <span class="section-eyebrow">SECTOR EXPERTISE</span>
                <h2>We Work with Businesses Across Key Sectors</h2>
                <p>Specialized commercial strategies tailored to industry realities across Africa.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="sector-card">
                        <div class="sector-icon"><i class="bi bi-tools"></i></div>
                        <h3 class="sector-title">Industrial & Equipment</h3>
                        <p class="sector-desc">Helping manufacturers test demand, identify customers and verify distributors before committing to a market.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="sector-card">
                        <div class="sector-icon"><i class="bi bi-cpu"></i></div>
                        <h3 class="sector-title">Technology & Digital</h3>
                        <p class="sector-desc">Supporting technology companies seeking customers, partners and routes to market across Africa.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="sector-card">
                        <div class="sector-icon"><i class="bi bi-cart3"></i></div>
                        <h3 class="sector-title">FMCG & Consumer</h3>
                        <p class="sector-desc">Helping brands understand consumer markets, identify distribution opportunities and develop routes to market.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="sector-card">
                        <div class="sector-icon"><i class="bi bi-heart-pulse"></i></div>
                        <h3 class="sector-title">Healthcare & Life Sciences</h3>
                        <p class="sector-desc">Supporting companies navigating market opportunities, commercial relationships and local market requirements.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="sector-card">
                        <div class="sector-icon"><i class="bi bi-tree"></i></div>
                        <h3 class="sector-title">Agriculture & Agribusiness</h3>
                        <p class="sector-desc">Identifying opportunities across agricultural value chains and supporting commercial development.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="sector-card">
                        <div class="sector-icon"><i class="bi bi-lightning-charge"></i></div>
                        <h3 class="sector-title">Energy & Infrastructure</h3>
                        <p class="sector-desc">Supporting businesses operating in sectors where local relationships, market knowledge and long-term commercial development are critical.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="sector-card">
                        <div class="sector-icon"><i class="bi bi-briefcase"></i></div>
                        <h3 class="sector-title">Professional & Business Services</h3>
                        <p class="sector-desc">Helping service businesses identify opportunities, develop partnerships and build their presence across African markets.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why P23 Africa Section -->
    <section id="why_p23" class="why-section content-section animate-on-scroll">
        <div class="container">
            <div class="why-header">
                <span class="why-eyebrow">THE P23 ADVANTAGE</span>
                <h2>Why P23 Africa?</h2>
                <div class="why-tagline">Local insight. International perspective. Commercial focus.</div>
                <p class="why-lead">
                    African market expansion can be complex. We help simplify the process by combining an understanding of international business requirements with knowledge of African markets and commercial environments.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="why-card">
                        <div class="why-card-icon"><i class="bi bi-bullseye"></i></div>
                        <h3 class="why-card-title">Practical, not theoretical</h3>
                        <p class="why-card-desc">Our focus is on information and activity that can lead to commercial decisions and measurable progress.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="why-card">
                        <div class="why-card-icon"><i class="bi bi-people-fill"></i></div>
                        <h3 class="why-card-title">Relationship-led</h3>
                        <p class="why-card-desc">Strong local relationships are fundamental to successful business development across Africa.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="why-card">
                        <div class="why-card-icon"><i class="bi bi-sliders"></i></div>
                        <h3 class="why-card-title">Flexible engagement</h3>
                        <p class="why-card-desc">From a focused market validation project to ongoing commercial support, our involvement can adapt to your requirements.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="why-card">
                        <div class="why-card-icon"><i class="bi bi-crosshair"></i></div>
                        <h3 class="why-card-title">Built around your objectives</h3>
                        <p class="why-card-desc">We don’t apply a one-size-fits-all market-entry model. We start with your business, proposition and objectives.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


 



    <section class="testimonial-section content-section animate-on-scroll">
        <div class="container">
            <h2>What Our Clients Say About Us</h2>
        </div>
    </section>

    <div class="testimonial-section-main">
        <div class="container testimonial-container">
            <div class="testimonial-wrapper">
                <div class="testimonial">
                    <div class="testimonial-card text-center">
                        <img src="{{ asset('images/testimonial01.png') }}" loading='lazy' alt="Chioma Ike">
                        <p>
                            Working with this team was a game-changer. Their insights and strategies helped us grow and
                            navigate new markets with confidence.
                        </p>
                        <h5><b>Chioma Chukwu</b></h5>
                        <h4>C.E.0</h4>
                    </div>
                </div>
                <div class="testimonial">
                    <div class="testimonial-card text-center">
                        <img src="{{ asset('images/testimonial3.png') }}" loading='lazy' alt="Charles">
                        <p>
                            Their expertise and tailored approach transformed our business operations and boosted our
                            efficiency. Highly recommended.
                        </p>
                        <h5><b>John Blakey</b></h5>
                        <h4>C.E.0</h4>
                    </div>
                </div>
                <div class="testimonial">
                    <div class="testimonial-card text-center">
                        <img src="{{ asset('images/testimonial03.png') }}" loading='lazy' alt="Family Guy">
                        <p class="">
                            A true partner in our success. They provided the guidance we needed to scale our business while
                            overcoming challenges.
                        </p>

                        <h5><b>Emily Nguyen</b></h5>
                        <h4>Managing Director</h4>
                    </div>
                </div>
            </div>

            <div class="slider-controls">
                <span class="arrow left"><i><img src="{{ asset('images/left.png') }}" loading='lazy'
                            alt=""></i></span>
                <span class="arrow right"><i><img src="{{ asset('images/right.png') }}" loading='lazy'
                            alt=""></i></span>
            </div>
        </div>
    </div>
    <hr id="horiz">

    <section class="insight-section content-section animate-on-scroll">
        <div class="">
            <h2>P23 Insights</h2>
            <div class="row insight-content">
                <div class="col-md-6 insight-image-wrapper">
                    <img src="{{ asset('images/insight-side.jpg') }}" loading='lazy' alt="Scolding Donald"
                        class="insight-image" />
                    <div class="bottom-bar"></div>
                </div>
                <div class="col-md-6 insight-text">
                    <p class="date">17th November 2025</p>
                    <h3><b> Essential Guide to Doing Business in Africa</b></h3>
                    <p class="description">
                        Our essential guide helps foreign investors navigate Africa’s diverse markets, build local
                        partnerships, and understand regulations, offering practical strategies for sustainable success.
                    </p>
                    <a href="{{ route('resource-hub') }}" class="read-more">Read More <span>→</span></a>
                </div>
            </div>
        </div>
    </section>

    <section class="carousel-container content-section animate-on-scroll">
        <div class="slider">
            <!-- Main Set -->
            {{-- @foreach ($blogs as $blog)
                <div class="insight-card text-left card">
                    @if ($blog->pdf_path)
                        <button class="view-pdf-btn cursor-pointer bg-white border border-0 text-left"
                            data-pdf="{{ asset('storage/' . $blog->pdf_path) }}">
                        @else
                            <a href="{{ route('resource-show', $blog->slug) }}" class="text-decoration-none">
                    @endif
                    @if ($blog->image_path)
                        <img src="{{ asset('storage/' . $blog->image_path) }}" loading='lazy' alt="Card image">
                    @else
                        <img src="{{ asset('images/no-image.jpg') }}" loading='lazy' alt="No image available">
                    @endif
                    <div class="card-body">
                        <div class="date text-start w-100">
                            {{ \Carbon\Carbon::parse($blog->created_at)->format('l, F jS Y') }}</div>
                        <h5 class="text-start w-100">{{ $blog->title }}</h5>
                        <p class="text-start w-100"> {{ \Illuminate\Support\Str::limit($blog->subtitle, 138) }}</p>
                    </div>
                    @if (!$blog->pdf_path)
                        </a>
                    @else
                        </button>
                    @endif
                </div>
            @endforeach --}}
            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/funding.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">2nd November 2025</div>
                    <h5>State of Funding 2025</h5>
                    <p>Africa’s funding landscape in 2025 shows steady growth, but challenges like tighter global capital
                        and cautious investor sentiment remain.</p>
                </div>
            </a>

            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/girl.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">10th November 2025</div>
                    <h5>Understanding buyer habits</h5>
                    <p>Buyer habits in Africa are evolving fast, driven by digital adoption and a growing middle class, but
                        differences across regions and trust issues still pose challenges.</p>
                </div>
            </a>

            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/plant.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">22nd October 2025</div>
                    <h5>Top Agri sectors to watch</h5>
                    <p>Africa’s agriculture sector is full of opportunity, with agri-tech, food processing, and sustainable
                        farming emerging as key areas to watch.</p>
                </div>
            </a>

            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/carousel00.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">9th October 2025</div>
                    <h5>Business trends 2025</h5>
                    <p>Business trends in Africa are evolving rapidly, with growth in digital transformation, green energy,
                        and emerging markets driving new opportunities across the continent.</p>
                </div>
            </a>

            <!-- Duplicate Set for Seamless Loop -->
            {{-- @foreach ($blogs as $blog)
                <div class="insight-card text-left card">
                    @if ($blog->pdf_path)
                        <button class="view-pdf-btn cursor-pointer bg-white border border-0 text-left"
                            data-pdf="{{ asset('storage/' . $blog->pdf_path) }}">
                        @else
                            <a href="{{ route('resource-show', $blog->slug) }}" class="text-decoration-none">
                    @endif
                    @if ($blog->image_path)
                        <img src="{{ asset('storage/' . $blog->image_path) }}" loading='lazy' alt="Card image">
                    @else
                        <img src="{{ asset('images/no-image.jpg') }}" loading='lazy' alt="No image available">
                    @endif
                    <div class="card-body">
                        <div class="date text-start w-100">
                            {{ \Carbon\Carbon::parse($blog->created_at)->format('l, F jS Y') }}</div>
                        <h5 class="text-start w-100">{{ $blog->title }}</h5>
                        <p class="text-start w-100"> {{ \Illuminate\Support\Str::limit($blog->subtitle, 138) }}</p>
                    </div>
                    @if (!$blog->pdf_path)
                        </a>
                    @else
                        </button>
                    @endif
                </div>
            @endforeach --}}

            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/funding.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">2nd November 2025</div>
                    <h5>State of Funding 2025</h5>
                    <p>Africa’s funding landscape in 2025 shows steady growth, but challenges like tighter global capital
                        and cautious investor sentiment remain.</p>
                </div>
            </a>

            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/girl.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">10th November 2025</div>
                    <h5>Understanding buyer habits</h5>
                    <p>Buyer habits in Africa are evolving fast, driven by digital adoption and a growing middle class, but
                        differences across regions and trust issues still pose challenges.</p>
                </div>
            </a>

            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/plant.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">22nd October 2025</div>
                    <h5>Top Agri sectors to watch</h5>
                    <p>Africa’s agriculture sector is full of opportunity, with agri-tech, food processing, and sustainable
                        farming emerging as key areas to watch.</p>
                </div>
            </a>

            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/carousel00.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">9th October 2025</div>
                    <h5>Business trends 2025</h5>
                    <p>Business trends in Africa are evolving rapidly, with growth in digital transformation, green energy,
                        and emerging markets driving new opportunities across the continent.</p>
                </div>
            </a>
        </div>

        <div id="btn" class="mt-4">
            <a href="{{ route('resource-hub') }}" class="btn">More on the Resource Hub</a>
        </div>

        <div class="pdf-popup" id="pdfPopup">
            <div class="pdf-popup-content">
                <span class="close-popup">&times;</span>
                <a href="" class="download-btn" id="downloadPdf" download>
                    📥 Download PDF
                </a>
                <iframe class="pdf-viewer" id="pdfViewer" src="" frameborder="0"></iframe>
            </div>
        </div>
    </section>
    <hr id="horiz">

    <!-- Closing Conversion CTA Section -->
    <section class="cta-closing-section content-section animate-on-scroll">
        <div class="container">
            <div class="cta-closing-inner">
                <span class="cta-pill">START YOUR EXPANSION</span>
                <h2>Ready to Explore the African Market?</h2>
                <p class="cta-desc">
                    Whether you’re considering Africa for the first time, looking to expand into additional markets, searching for customers or distributors, or preparing an established business for its next stage of growth, P23 Africa can help.
                </p>
                <div class="cta-prompt">Let’s discuss what you’re trying to achieve.</div>
                <div class="d-flex justify-content-center flex-wrap">
                    <a href="{{ url('book-consultation') }}" class="cta-btn-primary">
                        Book a Consultation <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ url('contact') }}" class="cta-btn-secondary">
                        Contact Our Team
                    </a>
                </div>
            </div>
        </div>
    </section>


    <script>
        $(function() {
            const $wrapper = $('.testimonial-wrapper');
            const $testimonials = $('.testimonial');
            const total = $testimonials.length;
            let currentIndex = 0;

            function updateWidths() {
                const screenWidth = $(window).width();
                if (screenWidth < 580) {
                    // Set for small screens
                    $wrapper.css('width', `${total * 100}%`);
                    $testimonials.css('width', `${100 / total}%`);
                } else {
                    // Set for larger screens (still full width per testimonial)
                    $wrapper.css('width', `100%`);
                    $testimonials.css('width', `100%`);
                }
                // Reposition to current index after width change
                updateSlider(currentIndex);
            }

            function updateSlider(index) {
                const offset = -index * (100 / total);
                $wrapper.css('transform', `translateX(${offset}%)`);
            }

            $('.arrow.left').click(function() {
                currentIndex = (currentIndex - 1 + total) % total;
                updateSlider(currentIndex);
            });

            $('.arrow.right').click(function() {
                currentIndex = (currentIndex + 1) % total;
                updateSlider(currentIndex);
            });

            // Init on load
            updateWidths();

            // Recalculate on window resize
            $(window).resize(function() {
                updateWidths();
            });
        });



        // Report Pop Up
        document.addEventListener('DOMContentLoaded', function() {
            const viewButtons = document.querySelectorAll('.view-pdf-btn');
            const popup = document.getElementById('pdfPopup');
            const pdfViewer = document.getElementById('pdfViewer');
            const downloadBtn = document.getElementById('downloadPdf');
            const closeBtn = document.querySelector('.close-popup');

            viewButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent any default behavior
                    e.stopPropagation(); // Stop event bubbling

                    const pdfUrl = this.getAttribute('data-pdf');
                    pdfViewer.src = pdfUrl;
                    downloadBtn.href = pdfUrl;
                    popup.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            });

            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                popup.style.display = 'none';
                pdfViewer.src = '';
                document.body.style.overflow = 'auto';
            });

            popup.addEventListener('click', function(e) {
                if (e.target === popup) {
                    popup.style.display = 'none';
                    pdfViewer.src = '';
                    document.body.style.overflow = 'auto';
                }
            });
        });
    </script>
@endsection
