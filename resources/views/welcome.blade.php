@extends('layouts.master')
@section('title', 'Helping Global Businesses Enter & Grow Across Africa | P23 Africa')
@section('meta')
    <meta name="description"
        content="P23 Africa helps global businesses understand African markets, validate opportunities, acquire customers and partners, and build sustainable commercial growth.">
    <meta name="keywords"
        content="market entry Africa, African business expansion, commercial solutions Africa, business consulting Africa, distributor development Africa, outbound sales demand testing, Nigeria Ghana Kenya South Africa business expansion, P23 Africa">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="P23 Africa - Helping Global Businesses Enter & Grow Across Africa">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Helping Global Businesses Enter & Grow Across Africa | P23 Africa">
    <meta property="og:description"
        content="We help you understand African markets, validate opportunities, acquire customers and partners, and establish the foundations for sustainable growth.">
    <meta property="og:image" content="https://p23africa.com/images/homepage-thumbnail-og.png">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Helping Global Businesses Enter & Grow Across Africa | P23 Africa">
    <meta name="twitter:description"
        content="We help you understand African markets, validate opportunities, acquire customers and partners, and establish the foundations for sustainable growth.">
    <meta name="twitter:image" content="https://p23africa.com/images/homepage-thumbnail-og.png">
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
        display: inline-flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        margin-top: 15px;
        padding: 15px 28px !important;
        border-radius: 12px;
        background-color: #0D4036;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.85);
        font-family: 'GT Walsheim Con', var(--font-sans), sans-serif;
        font-size: clamp(15px, 1.25vw, 17.5px) !important;
        font-weight: 500;
        line-height: 1.25;
        width: 100% !important;
        max-width: 330px !important;
        min-width: 260px;
        box-sizing: border-box;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        gap: 16px;
    }

    .hero-text a:hover {
        background-color: #ffffff !important;
        color: #003c32 !important;
        border-color: #ffffff;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.22);
        transform: translateY(-2px);
    }

    .hero-text a .hero-btn-arrow {
        flex-shrink: 0;
        font-size: 18px;
        line-height: 1;
        transition: transform 0.3s ease;
    }

    .hero-text a:hover .hero-btn-arrow {
        transform: translateX(4px);
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

    @media (max-width: 991px) {
        .hero-text a {
            max-width: 320px !important;
            padding: 14px 24px !important;
            font-size: 16px !important;
            margin-top: 10px;
        }
    }

    @media screen and (max-width: 580px) {
        .hero-text a {
            margin-top: 8px !important;
            padding: 13px 20px !important;
            border-radius: 10px;
            font-size: 15px !important;
            width: 100% !important;
            max-width: 320px !important;
            min-width: 0 !important;
        }

        #how_we_help {
            margin-bottom: 0rem !important;
        }
        .hero.content-section a {
            font-size: 15px;
        }
    }

    @media screen and (max-width: 380px) {
        .hero-text a {
            padding: 12px 14px !important;
            font-size: 13.5px !important;
            gap: 8px;
        }
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
    .capability-tags {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 5px 8px;
        margin: 1.1rem 0 1.5rem 0;
        padding-top: 1rem;
        border-top: 1px solid rgba(13, 64, 54, 0.08);
    }
    .capability-tags .tag-item {
        font-family: "GT Walsheim Trial Cn Rg", "Montserrat", sans-serif;
        font-size: 13.5px;
        font-weight: 500;
        color: #485c57;
        line-height: 1.35;
    }
    .capability-tags .tag-dot {
        color: #27E6A7;
        font-weight: 700;
        font-size: 13px;
        user-select: none;
    }
    .capability-card.featured-card .capability-tags {
        border-top-color: rgba(255, 255, 255, 0.15);
    }
    .capability-card.featured-card .capability-tags .tag-item {
        color: #c9e8e1;
    }
    .capability-card.featured-card .capability-tags .tag-dot {
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
        background: #ffffff;
        border: 1px solid rgba(13, 64, 54, 0.1);
        border-radius: 18px;
        padding: clamp(2rem, 3.5vw, 2.8rem);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    .sector-card:hover {
        background: #ffffff;
        border-color: rgba(39, 230, 167, 0.45);
        transform: translateY(-5px);
        box-shadow: 0 16px 36px rgba(13, 64, 54, 0.09);
    }
    .sector-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: rgba(39, 230, 167, 0.15);
        color: #0d4036;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 1.4rem;
        transition: all 0.3s ease;
    }
    .sector-card:hover .sector-icon {
        background: #0d4036;
        color: #27E6A7;
    }
    .sector-title {
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: clamp(21px, 1.8vw, 24px);
        font-weight: 700;
        color: #0d4036;
        margin-bottom: 0.8rem;
    }
    .sector-desc {
        font-family: "GT Walsheim Trial Cn Rg", "Inter", sans-serif;
        font-size: 16.5px;
        line-height: 1.6;
        color: #556461;
        margin: 0;
    }
    .sector-verticals {
        list-style: none;
        padding: 0;
        margin: 1.3rem 0 0 0;
        padding-top: 1.3rem;
        border-top: 1px solid rgba(13, 64, 54, 0.08);
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .sector-verticals li {
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: "GT Walsheim Trial Cn Rg", "Montserrat", sans-serif;
        font-size: 15px;
        font-weight: 500;
        color: #273e38;
    }
    .sector-verticals li i {
        color: #27E6A7;
        font-size: 16px;
        flex-shrink: 0;
    }
    .sectors-cta-wrap {
        text-align: center;
        margin-top: 3.5rem;
    }
    .sectors-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 16px 38px;
        background-color: #0d4036;
        color: #ffffff !important;
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-weight: 600;
        font-size: 16.5px;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 10px 24px rgba(13, 64, 54, 0.15);
        border: 1px solid transparent;
    }
    .sectors-cta-btn i {
        font-size: 17px;
        line-height: 1;
        transition: transform 0.3s ease;
    }
    .sectors-cta-btn:hover {
        background-color: #072b24;
        color: #27E6A7 !important;
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(13, 64, 54, 0.22);
    }
    .sectors-cta-btn:hover i {
        transform: translateX(4px);
    }
    @media (max-width: 580px) {
        .sectors-cta-wrap {
            margin-top: 2.2rem;
        }
        .sectors-cta-btn {
            width: 100%;
            max-width: 320px;
            justify-content: center;
            padding: 14px 24px;
            font-size: 15.5px;
        }
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

    /* Enhanced Service-Based Testimonials Styles */
    .testimonial-eyebrow {
        color: #27E6A7;
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 0.6rem;
    }
    .testimonial-sublead {
        color: #d0eee9;
        font-family: "GT Walsheim Trial Cn Rg", sans-serif;
        font-size: 18px;
        max-width: 720px;
        margin: 0;
        line-height: 1.5;
    }
    .testimonial-service-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e8f5f2;
        color: #07483e;
        font-family: "GT Walsheim ProCB", sans-serif;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 5px 14px;
        border-radius: 20px;
        margin: 4px auto 14px;
        border: 1px solid rgba(13, 64, 54, 0.12);
        transition: all 0.3s ease;
    }
    .testimonial-service-tag i {
        font-size: 13px;
        color: #19867d;
        transition: color 0.3s ease;
    }
    .testimonial-card:hover .testimonial-service-tag {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.35);
    }
    .testimonial-card:hover .testimonial-service-tag i {
        color: #27E6A7;
    }
    .testimonial-card .testimonial-quote {
        font-family: "GT Walsheim Trial Cn Rg", sans-serif;
        font-size: 16.5px !important;
        line-height: 1.6 !important;
        color: #3d4f4b;
        margin: 6px 0 16px;
        transition: color 0.3s ease;
    }
    .testimonial-card .testimonial-quote strong {
        font-weight: 700;
        color: #07483e;
        transition: color 0.3s ease;
    }
    .testimonial-card:hover .testimonial-quote {
        color: #eaf5f2 !important;
    }
    .testimonial-card:hover .testimonial-quote strong {
        color: #ffffff !important;
    }
    .testimonial-metric-row {
        display: flex;
        align-items: center;
        justify-content: space-around;
        background: #f4faf8;
        border: 1px solid rgba(13, 64, 54, 0.08);
        border-radius: 12px;
        padding: 10px 8px;
        margin-bottom: 14px;
        transition: all 0.3s ease;
    }
    .testimonial-card:hover .testimonial-metric-row {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.25);
    }
    .testimonial-metric-box {
        text-align: center;
        flex: 1;
    }
    .testimonial-metric-num {
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: #07483e;
        line-height: 1.1;
        transition: color 0.3s ease;
    }
    .testimonial-card:hover .testimonial-metric-num {
        color: #ffffff;
    }
    .testimonial-metric-label {
        font-family: "GT Walsheim Trial Cn Rg", sans-serif;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6a7c78;
        margin-top: 3px;
        line-height: 1.1;
        transition: color 0.3s ease;
    }
    .testimonial-card:hover .testimonial-metric-label {
        color: #d0eee9;
    }
    .testimonial-metric-divider {
        width: 1px;
        height: 24px;
        background: rgba(13, 64, 54, 0.12);
        transition: background 0.3s ease;
    }
    .testimonial-card:hover .testimonial-metric-divider {
        background: rgba(255, 255, 255, 0.25);
    }
    .testimonial-card h5 {
        font-size: 22px !important;
        margin-top: 6px !important;
        margin-bottom: 2px !important;
        transition: color 0.3s ease;
    }
    .testimonial-card h4 {
        font-size: 14px !important;
        color: #6a7c78;
        transition: color 0.3s ease;
    }
    /* ==========================================
       PARTNER LOGOS MARQUEE SECTION
       ========================================== */
    .partner-logos-section {
        position: relative;
        width: 100%;
        margin: 0;
        padding: 16px 0 14px 0;
        background: #fafcfb;
        border-top: 1px solid rgba(13, 64, 54, 0.07);
        border-bottom: 1px solid rgba(13, 64, 54, 0.07);
        overflow: hidden;
    }

    .partner-logos-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        text-align: center;
    }

    .partner-logos-title {
        font-family: "Montserrat", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-weight: 500;
        font-size: clamp(11.5px, 0.95vw, 13px);
        line-height: 16px;
        letter-spacing: 0.02em;
        text-align: center;
        text-transform: none;
        color: rgba(5, 0, 56, 0.55);
        margin: 0 0 10px 0;
    }

    .partner-marquee-wrapper {
        position: relative;
        width: 100%;
        overflow: hidden;
        mask-image: linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
        -webkit-mask-image: linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
        padding: 2px 0 4px 0;
    }

    .partner-marquee-track {
        display: flex;
        align-items: center;
        width: max-content;
        animation: partnerMarqueeScroll 38s linear infinite;
        will-change: transform;
    }

    .partner-marquee-wrapper:hover .partner-marquee-track {
        animation-play-state: paused;
    }

    .partner-marquee-group {
        display: flex;
        align-items: center;
        gap: clamp(28px, 3.5vw, 48px);
        padding-right: clamp(28px, 3.5vw, 48px);
        flex-shrink: 0;
    }

    .partner-logo-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 110px;
        padding: 0 6px;
        gap: 5px;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
        opacity: 0.92;
    }

    .partner-logo-item:hover {
        transform: translateY(-2px);
        opacity: 1;
    }

    .partner-logo-item img {
        height: 30px;
        max-height: 30px;
        max-width: 85px;
        width: auto;
        object-fit: contain;
        display: block;
        user-select: none;
        pointer-events: none;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .partner-logo-item:hover img {
        transform: scale(1.05);
    }

    .partner-logo-name {
        font-family: "Montserrat", "GT Walsheim ProCB", sans-serif;
        font-size: clamp(10.5px, 0.82vw, 11.5px);
        font-weight: 600;
        line-height: 1.2;
        letter-spacing: -0.01em;
        color: #1a3832;
        text-align: center;
        white-space: nowrap;
        transition: color 0.3s ease;
    }

    .partner-logo-item:hover .partner-logo-name {
        color: #07483e;
    }

    @keyframes partnerMarqueeScroll {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }

    @media (max-width: 768px) {
        .partner-logos-section {
            margin: 0;
            padding: 12px 0 10px 0;
        }
        .partner-logos-title {
            font-size: 11px;
            margin: 0 0 6px 0;
        }
        .partner-marquee-wrapper {
            padding: 2px 0;
        }
        .partner-marquee-track {
            animation-duration: 25s;
        }
        .partner-marquee-group {
            gap: 22px;
            padding-right: 22px;
        }
        .partner-logo-item {
            min-width: 90px;
            padding: 0 4px;
            gap: 4px;
        }
        .partner-logo-item img {
            height: 24px;
            max-height: 24px;
            max-width: 65px;
        }
        .partner-logo-name {
            font-size: 9.5px;
        }
    }

    /* ========================================================
       HOMEPAGE FEATURED CASE STUDIES (COMMERCIAL RESULTS)
       ======================================================== */
    .home-casestudy-section {
        position: relative;
        width: 100%;
        padding: clamp(65px, 7vw, 105px) 0;
        background: #f8faf9;
        border-top: 1px solid rgba(13, 64, 54, 0.06);
        border-bottom: 1px solid rgba(13, 64, 54, 0.06);
    }

    .home-casestudy-container {
        max-width: 1220px;
        margin: 0 auto;
        padding: 0 clamp(20px, 3.5vw, 36px);
    }

    .home-casestudy-header {
        text-align: center;
        max-width: 780px;
        margin: 0 auto clamp(32px, 4vw, 44px);
    }

    .home-casestudy-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #003c32;
        background: rgba(39, 230, 167, 0.16);
        border: 1px solid rgba(39, 230, 167, 0.38);
        padding: 6px 16px;
        border-radius: 100px;
        margin-bottom: 16px;
    }

    .home-casestudy-title {
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: clamp(28px, 3.5vw, 42px);
        font-weight: 700;
        line-height: 1.22;
        color: #07483e;
        margin: 0 0 14px 0;
        letter-spacing: -0.02em;
    }

    .home-casestudy-subtitle {
        font-family: "GT Walsheim Trial Cn Rg", "Inter", sans-serif;
        font-size: clamp(16px, 1.25vw, 18.5px);
        line-height: 1.6;
        color: #4e635e;
        margin: 0;
    }

    /* Tabs Bar (matching market-entry capsule with homepage dark teal theme) */
    .home-tabs-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        margin: 0 auto clamp(28px, 3.5vw, 40px);
    }

    .home-tabs-container {
        width: 100%;
        max-width: 680px;
        height: 56px;
        border-radius: 100px;
        background-color: #07483e;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        box-shadow: 0 10px 28px rgba(7, 72, 62, 0.18);
        position: relative;
    }

    .home-tab-btn {
        flex: 1;
        height: 46px;
        border-radius: 100px;
        border: none;
        background: transparent;
        color: #d0eee9;
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-weight: 600;
        font-size: clamp(12.5px, 1.05vw, 14.5px);
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 8px 14px;
        white-space: nowrap;
        user-select: none;
    }

    .home-tab-btn:hover:not(.active) {
        color: #ffffff;
        background-color: rgba(255, 255, 255, 0.08);
    }

    .home-tab-btn.active {
        background-color: #ffffff;
        color: #07483e;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    }

    .home-tab-btn .tab-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #27E6A7;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .home-tab-btn.active .tab-dot {
        opacity: 1;
        background-color: #07483e;
    }

    /* Panes and Cards */
    .home-case-panes-wrapper {
        position: relative;
        min-height: 420px;
    }

    .home-case-pane {
        display: none;
        opacity: 0;
        transform: translateY(12px);
        transition: opacity 0.35s ease, transform 0.35s ease;
    }

    .home-case-pane.active {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .home-case-card {
        background: #ffffff;
        border: 1px solid rgba(13, 64, 54, 0.1);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 16px 44px rgba(13, 64, 54, 0.07);
        display: grid;
        grid-template-columns: 46% 54%;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.35s ease;
    }

    .home-case-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 54px rgba(13, 64, 54, 0.12);
        border-color: rgba(39, 230, 167, 0.45);
    }

    .home-case-visual {
        position: relative;
        overflow: hidden;
        min-height: 380px;
        background: #02241e;
    }

    .home-case-visual img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .home-case-card:hover .home-case-visual img {
        transform: scale(1.05);
    }

    .home-case-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(7, 72, 62, 0.9);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        color: #ffffff;
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: 12.5px;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 100px;
        border: 1px solid rgba(39, 230, 167, 0.4);
        z-index: 2;
    }

    .home-case-article {
        padding: clamp(28px, 4vw, 44px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .home-case-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 16px;
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: 13.5px;
        color: #6a7f7a;
    }

    .home-case-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .home-case-meta i {
        color: #07483e;
        font-size: 14px;
    }

    .home-case-heading {
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: clamp(22px, 2.3vw, 29px);
        font-weight: 700;
        line-height: 1.3;
        color: #07483e;
        margin: 0 0 16px 0;
        letter-spacing: -0.01em;
    }

    .home-case-description {
        font-family: "GT Walsheim Trial Cn Rg", "Inter", sans-serif;
        font-size: clamp(16px, 1.2vw, 17.5px);
        line-height: 1.6;
        color: #405550;
        margin: 0 0 24px 0;
    }

    .home-case-metrics {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        padding: 16px 18px;
        background: #f4faf8;
        border: 1px solid rgba(13, 64, 54, 0.08);
        border-radius: 14px;
        margin-bottom: 26px;
    }

    .home-metric-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .home-metric-value {
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: clamp(18px, 1.7vw, 22px);
        font-weight: 700;
        color: #07483e;
        line-height: 1.1;
    }

    .home-metric-label {
        font-family: "GT Walsheim Trial Cn Rg", "Inter", sans-serif;
        font-size: 12.5px;
        color: #637772;
        line-height: 1.3;
    }

    .home-case-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 8px;
    }

    .home-case-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: 15.5px;
        font-weight: 700;
        color: #07483e;
        text-decoration: none;
        padding: 12px 24px;
        background: rgba(13, 64, 54, 0.06);
        border-radius: 100px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .home-case-link:hover {
        background: #07483e;
        color: #ffffff !important;
        transform: translateX(4px);
        box-shadow: 0 8px 20px rgba(7, 72, 62, 0.2);
    }

    .home-case-link .arrow {
        transition: transform 0.3s ease;
    }

    .home-case-link:hover .arrow {
        transform: translateX(4px);
    }

    .home-casestudy-all-link {
        text-align: center;
        margin-top: clamp(28px, 3.5vw, 40px);
    }

    .home-casestudy-all-link a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: 15px;
        font-weight: 600;
        color: #07483e;
        text-decoration: none;
        border-bottom: 2px solid rgba(39, 230, 167, 0.7);
        padding-bottom: 3px;
        transition: all 0.25s ease;
    }

    .home-casestudy-all-link a:hover {
        color: #00221c;
        border-bottom-color: #07483e;
        transform: translateX(2px);
    }

    @media (max-width: 991px) {
        .home-case-card {
            grid-template-columns: 1fr;
        }
        .home-case-visual {
            min-height: 260px;
            max-height: 320px;
        }
    }

    @media (max-width: 700px) {
        .home-tabs-container {
            height: auto;
            border-radius: 18px;
            flex-direction: column;
            padding: 6px;
        }
        .home-tab-btn {
            width: 100%;
            height: 42px;
            border-radius: 12px;
        }
        .home-case-metrics {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }
</style>

@section('content')
    <header id="body">
        <div id="header">
            <section class="hero content-section animate-on-scroll">
                <div class="row hero-sec-row">
                    <div class="col-md-7 hero-text my-auto">
                        <h1>Enter African Markets <br> With Evidence <br><span id="fonts">Not Assumptions</span></h1>
                        <br>
                        <p>
                          We help international businesses validate demand, verify distribution partners and build the sales operations needed to grow in Africa.<br>From testing real buyer interest to strengthening local sales teams and channel performance, we turn market-entry plans into commercial action.
                        </p>
                        <br>
                        <div class="hero-cta-wrapper">
                            <a href="{{ url('contact') }}" class="d-flex justify-content-between align-items-center">
                                <span class="hero-btn-label">Discuss Your Market Entry</span>
                                <span class="hero-btn-arrow" aria-hidden="true">→</span>
                            </a>
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

    {{-- ==========================================
         PARTNER LOGOS MARQUEE (RIGHT TO LEFT LOOP)
         ========================================== --}}
    @php
        $partnerLogos = [
            [
                'name' => 'CoolAir Systems',
                'src' => asset('images/partner-logos/coolair-systems.png'),
            ],
            [
                'name' => 'PackLine Machinery',
                'src' => asset('images/partner-logos/packline-machinery.png'),
            ],
            [
                'name' => 'CompactPower Equipment',
                'src' => asset('images/partner-logos/compactpower-equipment.png'),
            ],
            [
                'name' => 'Liftmasters International',
                'src' => asset('images/partner-logos/liftmasters-international.png'),
            ],
            [
                'name' => 'Agrimech International',
                'src' => asset('images/partner-logos/agrimech-international.png'),
            ],
            [
                'name' => 'Cloudworks Analytics',
                'src' => asset('images/partner-logos/cloudworks-analytics.png'),
            ],
            [
                'name' => 'Finedge Software',
                'src' => asset('images/partner-logos/finedge-software.png'),
            ],
        ];
    @endphp

    <section class="partner-logos-section" id="trusted-partners" aria-label="Trusted by leading organizations">
        <div class="partner-logos-container">
            <p class="partner-logos-title">Trusted by 25k+ businesses</p>
        </div>

        <div class="partner-marquee-wrapper">
            <div class="partner-marquee-track">
                {{-- Group 1 --}}
                <div class="partner-marquee-group">
                    @foreach ($partnerLogos as $logo)
                        <div class="partner-logo-item" title="{{ $logo['name'] }}">
                            <img src="{{ $logo['src'] }}" alt="{{ $logo['name'] }}" loading="lazy">
                            <span class="partner-logo-name">{{ $logo['name'] }}</span>
                        </div>
                    @endforeach
                    @foreach ($partnerLogos as $logo)
                        <div class="partner-logo-item" title="{{ $logo['name'] }}">
                            <img src="{{ $logo['src'] }}" alt="{{ $logo['name'] }}" loading="lazy">
                            <span class="partner-logo-name">{{ $logo['name'] }}</span>
                        </div>
                    @endforeach
                </div>
                {{-- Group 2 (Duplicate for seamless continuous loop) --}}
                <div class="partner-marquee-group" aria-hidden="true">
                    @foreach ($partnerLogos as $logo)
                        <div class="partner-logo-item" title="{{ $logo['name'] }}">
                            <img src="{{ $logo['src'] }}" alt="{{ $logo['name'] }}" loading="lazy">
                            <span class="partner-logo-name">{{ $logo['name'] }}</span>
                        </div>
                    @endforeach
                    @foreach ($partnerLogos as $logo)
                        <div class="partner-logo-item" title="{{ $logo['name'] }}">
                            <img src="{{ $logo['src'] }}" alt="{{ $logo['name'] }}" loading="lazy">
                            <span class="partner-logo-name">{{ $logo['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         FEATURED CASE STUDIES (COMMERCIAL RESULTS)
         ========================================== --}}
    <section class="home-casestudy-section" id="featured-case-studies" aria-label="Featured Case Studies">
        <div class="home-casestudy-container">
            
            {{-- Section Header --}}
            <div class="home-casestudy-header">
                <span class="home-casestudy-kicker">CASE STUDIES</span>
                <h2 class="home-casestudy-title">Commercial Results in Real Markets</h2>
                <p class="home-casestudy-subtitle">
                    Examine how international manufacturers and platforms validate real buyer demand and execute successful market expansion across Africa.
                </p>
            </div>

            {{-- Tabs Bar (Inspired by market-entry lines 282-312, aligned with homepage design system) --}}
            <div class="home-tabs-wrapper">
                <div class="home-tabs-container" role="tablist" aria-label="Case Study Industry Tabs">
                    <button type="button" 
                            class="home-tab-btn active" 
                            role="tab" 
                            aria-selected="true" 
                            aria-controls="home-pane-industrial" 
                            data-target="#home-pane-industrial"
                            id="tab-industrial"
                            onclick="switchHomeCaseTab('home-pane-industrial', this)">
                        <span class="tab-dot"></span>
                        Manufacturers
                    </button>
                    <button type="button" 
                            class="home-tab-btn" 
                            role="tab" 
                            aria-selected="false" 
                            aria-controls="home-pane-software" 
                            data-target="#home-pane-software"
                            id="tab-software"
                            onclick="switchHomeCaseTab('home-pane-software', this)">
                        <span class="tab-dot"></span>
                        Software &amp; SaaS
                    </button>
                    <button type="button" 
                            class="home-tab-btn" 
                            role="tab" 
                            aria-selected="false" 
                            aria-controls="home-pane-verification" 
                            data-target="#home-pane-verification"
                            id="tab-verification"
                            onclick="switchHomeCaseTab('home-pane-verification', this)">
                        <span class="tab-dot"></span>
                        Distributor Verification
                    </button>
                </div>
            </div>

            {{-- Case Study Showcase Panes --}}
            <div class="home-case-panes-wrapper">
                
                {{-- Pane 1: Manufacturers --}}
                <div class="home-case-pane active" id="home-pane-industrial" role="tabpanel" aria-labelledby="tab-industrial">
                    <div class="home-case-card">
                        <div class="home-case-visual">
                            <img src="{{ asset('images/case-studies/thermoflow.png') }}" 
                                 alt="Testing Demand for an Industrial Equipment Manufacturer" 
                                 loading="lazy">
                            <span class="home-case-badge">Manufacturers</span>
                        </div>

                        <div class="home-case-article">
                            <div>
                                <div class="home-case-meta">
                                    <span><i class="bi bi-geo-alt"></i> Nigeria (Lagos &amp; Abuja)</span>
                                    <span><i class="bi bi-clock"></i> 10-Week Demand Testing</span>
                                </div>

                                <h3 class="home-case-heading">
                                    Testing Demand for an Industrial Equipment Manufacturer
                                </h3>

                                <p class="home-case-description">
                                    Validated buyer interest, gathered market pricing insights and identified potential distribution partners in Nigeria.
                                </p>

                                <div class="home-case-metrics">
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">312</span>
                                        <span class="home-metric-label">Target Accounts</span>
                                    </div>
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">47</span>
                                        <span class="home-metric-label">Qualified Opportunities</span>
                                    </div>
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">Verified</span>
                                        <span class="home-metric-label">Distribution Route</span>
                                    </div>
                                </div>
                            </div>

                            <div class="home-case-footer">
                                <a href="{{ url('case-studies/thermoflow-hvac') }}" class="home-case-link" id="btn-read-case-industrial">
                                    Read the case study <span class="arrow">→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pane 2: Software & SaaS Companies --}}
                <div class="home-case-pane" id="home-pane-software" role="tabpanel" aria-labelledby="tab-software">
                    <div class="home-case-card">
                        <div class="home-case-visual">
                            <img src="{{ asset('images/case-studies/finedge.png') }}" 
                                 alt="Enterprise Client Acquisition & Partner Routes for B2B SaaS" 
                                 loading="lazy">
                            <span class="home-case-badge">Software &amp; SaaS</span>
                        </div>

                        <div class="home-case-article">
                            <div>
                                <div class="home-case-meta">
                                    <span><i class="bi bi-geo-alt"></i> Nigeria &amp; Kenya</span>
                                    <span><i class="bi bi-clock"></i> 12-Week Market Entry</span>
                                </div>

                                <h3 class="home-case-heading">
                                    Enterprise Client Acquisition &amp; Partner Routes for B2B SaaS
                                </h3>

                                <p class="home-case-description">
                                    Supporting technology companies and software platforms seeking enterprise clients, commercial partnerships, and routes to market across Africa.
                                </p>

                                <div class="home-case-metrics">
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">280</span>
                                        <span class="home-metric-label">Target Accounts</span>
                                    </div>
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">18</span>
                                        <span class="home-metric-label">Qualified Leads</span>
                                    </div>
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">2 Signed</span>
                                        <span class="home-metric-label">Channel Partners</span>
                                    </div>
                                </div>
                            </div>

                            <div class="home-case-footer">
                                <a href="{{ url('case-studies/cloudworks-analytics') }}" class="home-case-link" id="btn-read-case-software">
                                    Read the case study <span class="arrow">→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pane 3: Distributor Verification --}}
                <div class="home-case-pane" id="home-pane-verification" role="tabpanel" aria-labelledby="tab-verification">
                    <div class="home-case-card">
                        <div class="home-case-visual">
                            <img src="{{ asset('images/case-studies/coolair.png') }}" 
                                 alt="Verifying Local Distribution Partners Before Market Entry" 
                                 loading="lazy">
                            <span class="home-case-badge">Distributor Verification</span>
                        </div>

                        <div class="home-case-article">
                            <div>
                                <div class="home-case-meta">
                                    <span><i class="bi bi-geo-alt"></i> Kenya &amp; Tanzania</span>
                                    <span><i class="bi bi-clock"></i> 14-Week Partner Verification</span>
                                </div>

                                <h3 class="home-case-heading">
                                    Verifying Local Distribution Partners Before Market Entry
                                </h3>

                                <p class="home-case-description">
                                    Assessing prospective distribution partners before committing capital. Verifying on-ground sales capabilities, customer access, and territory coverage to establish reliable commercial routes.
                                </p>

                                <div class="home-case-metrics">
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">13</span>
                                        <span class="home-metric-label">Distributors Screened</span>
                                    </div>
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">5</span>
                                        <span class="home-metric-label">Shortlisted</span>
                                    </div>
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">2 Signed</span>
                                        <span class="home-metric-label">Verified Partners</span>
                                    </div>
                                </div>
                            </div>

                            <div class="home-case-footer">
                                <a href="{{ url('case-studies/coolair-systems') }}" class="home-case-link" id="btn-read-case-verification">
                                    Read the case study <span class="arrow">→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- All Case Studies Link --}}
            <div class="home-casestudy-all-link">
                <a href="{{ url('case-studies') }}" id="link-explore-all-case-studies">
                    Explore all verified market case studies <span>→</span>
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
                <!-- 1. Distributor Verification -->
                <div class="col-lg-4 col-md-6">
                    <div class="capability-card featured-card">
                        <div>
                            <div class="capability-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h3 class="capability-title">Distributor Verification</h3>
                            <p class="capability-desc">Assess prospective distribution partners before committing to a commercial relationship. Verify their capabilities, market coverage, customer access and suitability for your business.</p>
                            <div class="capability-tags">
                                <span class="tag-item">Partner due diligence</span>
                                <span class="tag-dot">·</span>
                                <span class="tag-item">Capability assessment</span>
                                <span class="tag-dot">·</span>
                                <span class="tag-item">Market coverage</span>
                                <span class="tag-dot">·</span>
                                <span class="tag-item">Commercial fit</span>
                            </div>
                        </div>
                        <a href="{{ url('services') }}#partner-dev" class="capability-link">
                            Explore partner verification <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- 2. Outbound Demand Testing -->
                <div class="col-lg-4 col-md-6">
                    <div class="capability-card">
                        <div>
                            <div class="capability-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <h3 class="capability-title">Outbound Demand Testing</h3>
                            <p class="capability-desc">Test your proposition with real prospective buyers and distributors before making significant market-entry investments. Gather direct feedback, assess buying interest and identify viable opportunities.</p>
                            <div class="capability-tags">
                                <span class="tag-item">Target buyer identification</span>
                                <span class="tag-dot">·</span>
                                <span class="tag-item">Direct outreach</span>
                                <span class="tag-dot">·</span>
                                <span class="tag-item">Demand validation</span>
                                <span class="tag-dot">·</span>
                                <span class="tag-item">Sales opportunity reporting</span>
                            </div>
                        </div>
                        <a href="{{ url('services') }}#sales" class="capability-link">
                            Explore demand testing <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- 3. Sales & Commercial Operations -->
                <div class="col-lg-4 col-md-12">
                    <div class="capability-card">
                        <div>
                            <div class="capability-icon">
                                <i class="bi bi-gear-wide-connected"></i>
                            </div>
                            <h3 class="capability-title">Sales & Commercial Operations</h3>
                            <p class="capability-desc">Build and support the local sales operation that turns market opportunity into revenue, including sales teams, channel partner performance, CRM systems and commercial reporting.</p>
                            <div class="capability-tags">
                                <span class="tag-item">Sales team setup</span>
                                <span class="tag-dot">·</span>
                                <span class="tag-item">Partner management</span>
                                <span class="tag-dot">·</span>
                                <span class="tag-item">CRM and reporting</span>
                                <span class="tag-dot">·</span>
                                <span class="tag-item">Ongoing execution</span>
                            </div>
                        </div>
                        <a href="{{ url('services') }}#operations" class="capability-link">
                            Explore commercial operations <i class="bi bi-arrow-right"></i>
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



    <!-- Who We Help Section -->
    <section id="sectors" class="sectors-section content-section animate-on-scroll">
        <div class="container">
            <div class="sectors-header">
                <span class="section-eyebrow">WHO WE HELP</span>
                <h2>Built for International Businesses Expanding into Africa</h2>
                <p>Specialized commercial strategies tailored to industry realities across Africa.</p>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- 1. Equipment and industrial manufacturers -->
                <div class="col-lg-6 col-md-6">
                    <div class="sector-card">
                        <div>
                            <div class="sector-icon"><i class="bi bi-tools"></i></div>
                            <h3 class="sector-title">Equipment and Industrial Manufacturers</h3>
                            <p class="sector-desc">Helping international manufacturers test market demand, identify target enterprise customers, and verify reliable distribution partners before committing to a market.</p>
                            <ul class="sector-verticals">
                                <li><i class="bi bi-check-circle-fill"></i> Heavy Equipment &amp; Industrial Machinery</li>
                                <li><i class="bi bi-check-circle-fill"></i> Construction, Infrastructure &amp; HVAC Equipment</li>
                                <li><i class="bi bi-check-circle-fill"></i> Agricultural, Manufacturing &amp; Medical Equipment</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 2. Software and SaaS companies -->
                <div class="col-lg-6 col-md-6">
                    <div class="sector-card">
                        <div>
                            <div class="sector-icon"><i class="bi bi-cpu"></i></div>
                            <h3 class="sector-title">Software and SaaS Companies</h3>
                            <p class="sector-desc">Supporting technology companies and software platforms seeking enterprise clients, commercial partnerships, and routes to market across Africa.</p>
                            <ul class="sector-verticals">
                                <li><i class="bi bi-check-circle-fill"></i> Fintech and financial technology</li>
                                <li><i class="bi bi-check-circle-fill"></i> Logistics and supply chain businesses</li>
                                <li><i class="bi bi-check-circle-fill"></i> Compliance and RegTech providers</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sectors-cta-wrap">
                <a href="{{ url('contact') }}" class="sectors-cta-btn">
                    <span>Contact Us</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
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
                    <p class="date">17th October 2026</p>
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
                    <div class="date">2nd October 2026</div>
                    <h5>State of Funding 2025</h5>
                    <p>Africa’s funding landscape in 2025 shows steady growth, but challenges like tighter global capital
                        and cautious investor sentiment remain.</p>
                </div>
            </a>

            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/girl.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">10th October 2026</div>
                    <h5>Understanding buyer habits</h5>
                    <p>Buyer habits in Africa are evolving fast, driven by digital adoption and a growing middle class, but
                        differences across regions and trust issues still pose challenges.</p>
                </div>
            </a>

            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/plant.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">22nd September 2026</div>
                    <h5>Top Agri sectors to watch</h5>
                    <p>Africa’s agriculture sector is full of opportunity, with agri-tech, food processing, and sustainable
                        farming emerging as key areas to watch.</p>
                </div>
            </a>

            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/carousel00.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">9th September 2026</div>
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
                    <div class="date">2nd October 2026</div>
                    <h5>State of Funding 2025</h5>
                    <p>Africa’s funding landscape in 2025 shows steady growth, but challenges like tighter global capital
                        and cautious investor sentiment remain.</p>
                </div>
            </a>

            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/girl.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">10th October 2026</div>
                    <h5>Understanding buyer habits</h5>
                    <p>Buyer habits in Africa are evolving fast, driven by digital adoption and a growing middle class, but
                        differences across regions and trust issues still pose challenges.</p>
                </div>
            </a>

            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/plant.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">22nd September 2026</div>
                    <h5>Top Agri sectors to watch</h5>
                    <p>Africa’s agriculture sector is full of opportunity, with agri-tech, food processing, and sustainable
                        farming emerging as key areas to watch.</p>
                </div>
            </a>

            <a href="{{ route('resource-hub') }}" class="insight-card text-decoration-none">
                <img src="{{ asset('images/carousel00.jpg') }}" loading='lazy' alt="Card image">
                <div class="card-body">
                    <div class="date">9th September 2026</div>
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
                    <a href="{{ url('contact') }}" class="cta-btn-primary">
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
        // Featured Case Studies Tab Switcher
        function switchHomeCaseTab(paneId, btn) {
            const buttons = document.querySelectorAll('.home-tab-btn');
            const panes = document.querySelectorAll('.home-case-pane');

            buttons.forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-selected', 'false');
            });

            if (btn) {
                btn.classList.add('active');
                btn.setAttribute('aria-selected', 'true');
            }

            panes.forEach(pane => {
                pane.classList.remove('active');
            });

            const target = document.getElementById(paneId);
            if (target) {
                target.classList.add('active');
            }
        }

        // Attach event listeners
        document.addEventListener('DOMContentLoaded', function () {
            const homeTabButtons = document.querySelectorAll('.home-tab-btn');
            homeTabButtons.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target') ? this.getAttribute('data-target').replace('#', '') : '';
                    if (targetId) {
                        switchHomeCaseTab(targetId, this);
                    }
                });
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
                    e.preventDefault();
                    e.stopPropagation();

                    const pdfUrl = this.getAttribute('data-pdf');
                    if (pdfViewer) pdfViewer.src = pdfUrl;
                    if (downloadBtn) downloadBtn.href = pdfUrl;
                    if (popup) popup.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            });

            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (popup) popup.style.display = 'none';
                    if (pdfViewer) pdfViewer.src = '';
                    document.body.style.overflow = 'auto';
                });
            }

            if (popup) {
                popup.addEventListener('click', function(e) {
                    if (e.target === popup) {
                        popup.style.display = 'none';
                        if (pdfViewer) pdfViewer.src = '';
                        document.body.style.overflow = 'auto';
                    }
                });
            }
        });
    </script>
@endsection
