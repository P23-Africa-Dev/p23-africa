@extends('layouts.brn-layout')
@section('title', 'NOEL54 Interest Form | P23 Africa')

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
    .brn-form .form-control {
        margin-bottom: 2px !important;
    }

    .brn-form .form-group {
        margin-bottom: 15px;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .brn-form .next-btn {
        background-color: #193e47;
        color: #ffffff;
        padding: 12px 60px;
        /* width: 265px; */
        border-radius: 8px;
        font-size: 1.1rem;
        display: inline;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border: none;
        margin-top: 50px;
    }

    .brn-form .next-btn:hover {
        background-color: #1deaa5;
        color: #193e47;
    }

    .brn-form .prev-btn {
        background-color: #1deaa5;
        color: #ffffff;
        padding: 12px 60px;
        border-radius: 8px;
        font-size: 1.1rem;
        display: inline;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border: none;
        margin-top: 50px;
    }

    .brn-form .prev-btn:hover {
        background-color: #193e47;
        color: #1deaa5;
    }

    .brn-form-icons-card.selected {
        border-color: #6beabf;
        box-shadow: 0 4px 15px rgba(107, 234, 191, 0.4);
    }

    .brn-form-icons-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        padding: 10px;
        border-radius: 8px;
    }

    .brn-form-icons-card:hover {
        border-color: #6beabf;
        box-shadow: 0 4px 15px rgba(107, 234, 191, 0.4);
    }

    .brn-form-icons-header {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 20px;
    }




    /* Styling for the entire progress indicator section */
    .form-progress-indicator-wrapper {
        position: relative;
        width: 90%;
        max-width: 600px;
        margin: 0 auto 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .progress-image {
        width: 90%;
        height: auto;
        display: block;
    }

    .step-text-container {
        width: 120%;
        max-width: 720px;
        display: flex;
        justify-content: space-between;
        padding-top: 15px;
    }

    .step-text {
        flex: 1;
        text-align: center;
        padding: 0 5px;
    }

    .step-title {
        font-size: 0.9rem;
        font-weight: 1000;
        margin: 0;
        color: #111;
        white-space: nowrap;
    }

    .step-desc {
        font-size: 0.7rem;
        color: #555;
        margin: 0;
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
        background-color: #fef2f2 !important;
    }

    .form-control:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(25, 62, 71, 0.1);
    }

    .brn-input-checked-group .error-message {
        background-color: #fef2f2;
        border: 1px solid #ef4444;
        border-radius: 4px;
        padding: 8px;
        margin-top: 8px;
    }

    #brnForm .btn i {
        display: none;

    }

    .brn-input-checked-options-row {
        margin-top: -7px;
    }

    /* Phone input styling */
    .phone-input-container {
        position: relative;
    }

    .country-code-select {
        font-size: 14px;
        text-align: center;
        background-color: #f8f9fa;
        border-color: #ced4da;
    }

    .country-code-select:focus {
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(25, 62, 71, 0.1);
    }

    .phone-input-container input[name="phone"]:focus {
        box-shadow: 0 0 0 3px rgba(25, 62, 71, 0.1);
    }





    /* UPDATED CHANGES */
    @media (max-width: 768px) {
        .step-text {
            display: none;
        }

        .step-text.active {
            display: block;
            display: flex;
            flex-direction: column;
            text-align: left;
            margin-left: 16px;
        }



        #brnForm .brn-form-icons-text {
            font-size: 0.9rem;
        }

        #brnForm .btn.next-btn,
        #brnForm .btn.prev-btn,
        #brnForm .btn.btn-submit {
            padding: 9px 40px;
            font-size: 1rem;
            margin-top: 20px;
        }

    }
</style>

@section('content')
    <section class="brn-body">
        <div class="brn-main-container">
            <div class="container">
                <form id="brnForm" action="{{ locale_route('brn.submit') }}" method="POST" enctype="multipart/form-data"
                    novalidate>
                    @csrf
                    <div class="brn-container form-step active" id="form1">


                        <div class="brn-header-container">
                            <div class="brn-header">
                                <h1>Personal Information</h1>
                                <div class="brn-header-desc">
                                    <span></span>
                                    <p>Let's start with your basic details</p>
                                </div>
                            </div>
                        </div>
                        <div class="brn-form-container">
                            <div class="form-conainer">
                                <div class="brn-content">
                                    <div class="brn-form">
                                        <div class="form-progress-indicator-wrapper">
                                            <img id="progress-image" src="{{ asset('images/step1.svg') }}"
                                                alt="Form Progress" class="progress-image">
                                            <div class="step-text-container">
                                                <div class="step-text active" data-step="1">
                                                    <p class="step-title">Personal Information</p>
                                                    <p class="step-desc">Let's start with your <br> basic details</p>
                                                </div>
                                                <div class="step-text " data-step="2">
                                                    <p class="step-title">Company Information</p>
                                                    <p class="step-desc">Tell us about your <br> business</p>
                                                </div>
                                                <div class="step-text" data-step="3">
                                                    <p class="step-title">Choose Interest</p>
                                                    <p class="step-desc">What would you like to get <br> from this
                                                        solution
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <form action=""> --}}
                                        <div class="form-group">
                                            <input type="text" name="name" class="form-control"
                                                placeholder="Full Name" />
                                        </div>

                                        <div class="form-group" style="position: relative;">
                                            <input type="email" name="email" class="form-control"
                                                placeholder="Enter your valid email address" />
                                            <div id="email-status"
                                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); display: none;">
                                                <svg id="email-checkmark"
                                                    style="width: 20px; height: 20px; color: #10b981; display: none;"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <svg id="email-spinner"
                                                    style="width: 20px; height: 20px; color: #6b7280; display: none; animation: spin 1s linear infinite;"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <circle style="opacity: 0.25;" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path style="opacity: 0.75;" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="phone-input-container" style="display: flex; gap: 0;">
                                                <select name="country_code" class="form-control country-code-select"
                                                    style="flex: 0 0 120px; border-right: none; border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                                    <option value="+93" data-flag="🇦🇫">🇦🇫 +93</option>
                                                    <option value="+355" data-flag="🇦🇱">🇦🇱 +355</option>
                                                    <option value="+213" data-flag="🇩🇿">🇩🇿 +213</option>
                                                    <option value="+376" data-flag="🇦🇩">🇦🇩 +376</option>
                                                    <option value="+244" data-flag="🇦🇴">🇦🇴 +244</option>
                                                    <option value="+54" data-flag="🇦🇷">🇦🇷 +54</option>
                                                    <option value="+374" data-flag="🇦🇲">🇦🇲 +374</option>
                                                    <option value="+61" data-flag="🇦🇺">🇦🇺 +61</option>
                                                    <option value="+43" data-flag="🇦🇹">🇦🇹 +43</option>
                                                    <option value="+994" data-flag="🇦🇿">🇦🇿 +994</option>
                                                    <option value="+973" data-flag="🇧🇭">🇧🇭 +973</option>
                                                    <option value="+880" data-flag="🇧🇩">🇧🇩 +880</option>
                                                    <option value="+375" data-flag="🇧🇾">🇧🇾 +375</option>
                                                    <option value="+32" data-flag="🇧🇪">🇧🇪 +32</option>
                                                    <option value="+229" data-flag="🇧🇯">🇧🇯 +229</option>
                                                    <option value="+975" data-flag="🇧🇹">🇧🇹 +975</option>
                                                    <option value="+591" data-flag="🇧🇴">🇧🇴 +591</option>
                                                    <option value="+387" data-flag="🇧🇦">🇧🇦 +387</option>
                                                    <option value="+267" data-flag="🇧🇼">🇧🇼 +267</option>
                                                    <option value="+55" data-flag="🇧🇷">🇧🇷 +55</option>
                                                    <option value="+673" data-flag="🇧🇳">🇧🇳 +673</option>
                                                    <option value="+359" data-flag="🇧🇬">🇧🇬 +359</option>
                                                    <option value="+226" data-flag="🇧🇫">🇧🇫 +226</option>
                                                    <option value="+257" data-flag="🇧🇮">🇧🇮 +257</option>
                                                    <option value="+855" data-flag="🇰🇭">🇰🇭 +855</option>
                                                    <option value="+237" data-flag="🇨🇲">🇨🇲 +237</option>
                                                    <option value="+1" data-flag="🇨🇦">🇨🇦 +1</option>
                                                    <option value="+238" data-flag="🇨🇻">🇨🇻 +238</option>
                                                    <option value="+236" data-flag="🇨🇫">🇨🇫 +236</option>
                                                    <option value="+235" data-flag="🇹🇩">🇹🇩 +235</option>
                                                    <option value="+56" data-flag="🇨🇱">🇨🇱 +56</option>
                                                    <option value="+86" data-flag="🇨🇳">🇨🇳 +86</option>
                                                    <option value="+57" data-flag="🇨🇴">🇨🇴 +57</option>
                                                    <option value="+269" data-flag="🇰🇲">🇰🇲 +269</option>
                                                    <option value="+242" data-flag="🇨🇬">🇨🇬 +242</option>
                                                    <option value="+506" data-flag="🇨🇷">🇨🇷 +506</option>
                                                    <option value="+385" data-flag="🇭🇷">🇭🇷 +385</option>
                                                    <option value="+53" data-flag="🇨🇺">🇨🇺 +53</option>
                                                    <option value="+357" data-flag="🇨🇾">🇨🇾 +357</option>
                                                    <option value="+420" data-flag="🇨🇿">🇨🇿 +420</option>
                                                    <option value="+45" data-flag="🇩🇰">🇩🇰 +45</option>
                                                    <option value="+253" data-flag="🇩🇯">🇩🇯 +253</option>
                                                    <option value="+593" data-flag="🇪🇨">🇪🇨 +593</option>
                                                    <option value="+20" data-flag="🇪🇬">🇪🇬 +20</option>
                                                    <option value="+503" data-flag="🇸🇻">🇸🇻 +503</option>
                                                    <option value="+240" data-flag="🇬🇶">🇬🇶 +240</option>
                                                    <option value="+291" data-flag="🇪🇷">🇪🇷 +291</option>
                                                    <option value="+372" data-flag="🇪🇪">🇪🇪 +372</option>
                                                    <option value="+251" data-flag="🇪🇹">🇪🇹 +251</option>
                                                    <option value="+679" data-flag="🇫🇯">🇫🇯 +679</option>
                                                    <option value="+358" data-flag="🇫🇮">🇫🇮 +358</option>
                                                    <option value="+33" data-flag="🇫🇷">🇫🇷 +33</option>
                                                    <option value="+241" data-flag="🇬🇦">🇬🇦 +241</option>
                                                    <option value="+220" data-flag="🇬🇲">🇬🇲 +220</option>
                                                    <option value="+995" data-flag="🇬🇪">🇬🇪 +995</option>
                                                    <option value="+49" data-flag="🇩🇪">🇩🇪 +49</option>
                                                    <option value="+233" data-flag="🇬🇭">🇬🇭 +233</option>
                                                    <option value="+30" data-flag="🇬🇷">🇬🇷 +30</option>
                                                    <option value="+502" data-flag="🇬🇹">🇬🇹 +502</option>
                                                    <option value="+224" data-flag="🇬🇳">🇬🇳 +224</option>
                                                    <option value="+245" data-flag="🇬🇼">🇬🇼 +245</option>
                                                    <option value="+592" data-flag="🇬🇾">🇬🇾 +592</option>
                                                    <option value="+509" data-flag="🇭🇹">🇭🇹 +509</option>
                                                    <option value="+504" data-flag="🇭🇳">🇭🇳 +504</option>
                                                    <option value="+36" data-flag="🇭🇺">🇭🇺 +36</option>
                                                    <option value="+354" data-flag="🇮🇸">🇮🇸 +354</option>
                                                    <option value="+91" data-flag="🇮🇳">🇮🇳 +91</option>
                                                    <option value="+62" data-flag="🇮🇩">🇮🇩 +62</option>
                                                    <option value="+98" data-flag="🇮🇷">🇮🇷 +98</option>
                                                    <option value="+964" data-flag="🇮🇶">🇮🇶 +964</option>
                                                    <option value="+353" data-flag="🇮🇪">🇮🇪 +353</option>
                                                    <option value="+972" data-flag="🇮🇱">🇮🇱 +972</option>
                                                    <option value="+39" data-flag="🇮🇹">🇮🇹 +39</option>
                                                    <option value="+225" data-flag="🇨🇮">🇨🇮 +225</option>
                                                    <option value="+81" data-flag="🇯🇵">🇯🇵 +81</option>
                                                    <option value="+962" data-flag="🇯🇴">🇯🇴 +962</option>
                                                    <option value="+7" data-flag="🇰🇿">🇰🇿 +7</option>
                                                    <option value="+254" data-flag="🇰🇪">🇰🇪 +254</option>
                                                    <option value="+686" data-flag="🇰🇮">🇰🇮 +686</option>
                                                    <option value="+850" data-flag="🇰🇵">🇰🇵 +850</option>
                                                    <option value="+82" data-flag="🇰🇷">🇰🇷 +82</option>
                                                    <option value="+965" data-flag="🇰🇼">🇰🇼 +965</option>
                                                    <option value="+996" data-flag="🇰🇬">🇰🇬 +996</option>
                                                    <option value="+856" data-flag="🇱🇦">🇱🇦 +856</option>
                                                    <option value="+371" data-flag="🇱🇻">🇱🇻 +371</option>
                                                    <option value="+961" data-flag="🇱🇧">🇱🇧 +961</option>
                                                    <option value="+266" data-flag="🇱🇸">🇱🇸 +266</option>
                                                    <option value="+231" data-flag="🇱🇷">🇱🇷 +231</option>
                                                    <option value="+218" data-flag="🇱🇾">🇱🇾 +218</option>
                                                    <option value="+423" data-flag="🇱🇮">🇱🇮 +423</option>
                                                    <option value="+370" data-flag="🇱🇹">🇱🇹 +370</option>
                                                    <option value="+352" data-flag="🇱🇺">🇱🇺 +352</option>
                                                    <option value="+389" data-flag="🇲🇰">🇲🇰 +389</option>
                                                    <option value="+261" data-flag="🇲🇬">🇲🇬 +261</option>
                                                    <option value="+265" data-flag="🇲🇼">🇲🇼 +265</option>
                                                    <option value="+60" data-flag="🇲🇾">🇲🇾 +60</option>
                                                    <option value="+960" data-flag="🇲🇻">🇲🇻 +960</option>
                                                    <option value="+223" data-flag="🇲🇱">🇲🇱 +223</option>
                                                    <option value="+356" data-flag="🇲🇹">🇲🇹 +356</option>
                                                    <option value="+692" data-flag="🇲🇭">🇲🇭 +692</option>
                                                    <option value="+222" data-flag="🇲🇷">🇲🇷 +222</option>
                                                    <option value="+230" data-flag="🇲🇺">🇲🇺 +230</option>
                                                    <option value="+52" data-flag="🇲🇽">🇲🇽 +52</option>
                                                    <option value="+691" data-flag="🇫🇲">🇫🇲 +691</option>
                                                    <option value="+373" data-flag="🇲🇩">🇲🇩 +373</option>
                                                    <option value="+377" data-flag="🇲🇨">🇲🇨 +377</option>
                                                    <option value="+976" data-flag="🇲🇳">🇲🇳 +976</option>
                                                    <option value="+382" data-flag="🇲🇪">🇲🇪 +382</option>
                                                    <option value="+212" data-flag="🇲🇦">🇲🇦 +212</option>
                                                    <option value="+258" data-flag="🇲🇿">🇲🇿 +258</option>
                                                    <option value="+95" data-flag="🇲🇲">🇲🇲 +95</option>
                                                    <option value="+264" data-flag="🇳🇦">🇳🇦 +264</option>
                                                    <option value="+674" data-flag="🇳🇷">🇳🇷 +674</option>
                                                    <option value="+977" data-flag="🇳🇵">🇳🇵 +977</option>
                                                    <option value="+31" data-flag="🇳🇱">🇳🇱 +31</option>
                                                    <option value="+64" data-flag="🇳🇿">🇳🇿 +64</option>
                                                    <option value="+505" data-flag="🇳🇮">🇳🇮 +505</option>
                                                    <option value="+227" data-flag="🇳🇪">🇳🇪 +227</option>
                                                    <option value="+234" data-flag="🇳🇬" selected>🇳🇬 +234</option>
                                                    <option value="+47" data-flag="🇳🇴">🇳🇴 +47</option>
                                                    <option value="+968" data-flag="🇴🇲">🇴🇲 +968</option>
                                                    <option value="+92" data-flag="🇵🇰">🇵🇰 +92</option>
                                                    <option value="+680" data-flag="🇵🇼">🇵🇼 +680</option>
                                                    <option value="+507" data-flag="🇵🇦">🇵🇦 +507</option>
                                                    <option value="+675" data-flag="🇵🇬">🇵🇬 +675</option>
                                                    <option value="+595" data-flag="🇵🇾">🇵🇾 +595</option>
                                                    <option value="+51" data-flag="🇵🇪">🇵🇪 +51</option>
                                                    <option value="+63" data-flag="🇵🇭">🇵🇭 +63</option>
                                                    <option value="+48" data-flag="🇵🇱">🇵🇱 +48</option>
                                                    <option value="+351" data-flag="🇵🇹">🇵🇹 +351</option>
                                                    <option value="+974" data-flag="🇶🇦">🇶🇦 +974</option>
                                                    <option value="+40" data-flag="🇷🇴">🇷🇴 +40</option>
                                                    <option value="+7" data-flag="🇷🇺">🇷🇺 +7</option>
                                                    <option value="+250" data-flag="🇷🇼">🇷🇼 +250</option>
                                                    <option value="+966" data-flag="🇸🇦">🇸🇦 +966</option>
                                                    <option value="+221" data-flag="🇸🇳">🇸🇳 +221</option>
                                                    <option value="+381" data-flag="🇷🇸">🇷🇸 +381</option>
                                                    <option value="+248" data-flag="🇸🇨">🇸🇨 +248</option>
                                                    <option value="+232" data-flag="🇸🇱">🇸🇱 +232</option>
                                                    <option value="+65" data-flag="🇸🇬">🇸🇬 +65</option>
                                                    <option value="+421" data-flag="🇸🇰">🇸🇰 +421</option>
                                                    <option value="+386" data-flag="🇸🇮">🇸🇮 +386</option>
                                                    <option value="+677" data-flag="🇸🇧">🇸🇧 +677</option>
                                                    <option value="+252" data-flag="🇸🇴">🇸🇴 +252</option>
                                                    <option value="+27" data-flag="🇿🇦">🇿🇦 +27</option>
                                                    <option value="+211" data-flag="🇸🇸">🇸🇸 +211</option>
                                                    <option value="+34" data-flag="🇪🇸">🇪🇸 +34</option>
                                                    <option value="+94" data-flag="🇱🇰">🇱🇰 +94</option>
                                                    <option value="+249" data-flag="🇸🇩">🇸🇩 +249</option>
                                                    <option value="+597" data-flag="🇸🇷">🇸🇷 +597</option>
                                                    <option value="+268" data-flag="🇸🇿">🇸🇿 +268</option>
                                                    <option value="+46" data-flag="🇸🇪">🇸🇪 +46</option>
                                                    <option value="+41" data-flag="🇨🇭">🇨🇭 +41</option>
                                                    <option value="+963" data-flag="🇸🇾">🇸🇾 +963</option>
                                                    <option value="+886" data-flag="🇹🇼">🇹🇼 +886</option>
                                                    <option value="+992" data-flag="🇹🇯">🇹🇯 +992</option>
                                                    <option value="+255" data-flag="🇹🇿">🇹🇿 +255</option>
                                                    <option value="+66" data-flag="🇹🇭">🇹🇭 +66</option>
                                                    <option value="+228" data-flag="🇹🇬">🇹🇬 +228</option>
                                                    <option value="+676" data-flag="🇹🇴">🇹🇴 +676</option>
                                                    <option value="+216" data-flag="🇹🇳">🇹🇳 +216</option>
                                                    <option value="+90" data-flag="🇹🇷">🇹🇷 +90</option>
                                                    <option value="+993" data-flag="🇹🇲">🇹🇲 +993</option>
                                                    <option value="+688" data-flag="🇹🇻">🇹🇻 +688</option>
                                                    <option value="+256" data-flag="🇺🇬">🇺🇬 +256</option>
                                                    <option value="+380" data-flag="🇺🇦">🇺🇦 +380</option>
                                                    <option value="+971" data-flag="🇦🇪">🇦🇪 +971</option>
                                                    <option value="+44" data-flag="🇬🇧">🇬🇧 +44</option>
                                                    <option value="+1" data-flag="🇺🇸">🇺🇸 +1</option>
                                                    <option value="+598" data-flag="🇺🇾">🇺🇾 +598</option>
                                                    <option value="+998" data-flag="🇺🇿">🇺🇿 +998</option>
                                                    <option value="+678" data-flag="🇻🇺">🇻🇺 +678</option>
                                                    <option value="+379" data-flag="🇻🇦">🇻🇦 +379</option>
                                                    <option value="+58" data-flag="🇻🇪">🇻🇪 +58</option>
                                                    <option value="+84" data-flag="🇻🇳">🇻🇳 +84</option>
                                                    <option value="+967" data-flag="🇾🇪">🇾🇪 +967</option>
                                                    <option value="+260" data-flag="🇿🇲">🇿🇲 +260</option>
                                                    <option value="+263" data-flag="🇿🇼">🇿🇼 +263</option>
                                                </select>
                                                <input type="tel" name="phone" class="form-control"
                                                    placeholder="Enter phone number"
                                                    style="flex: 1; border-left: none; border-top-left-radius: 0; border-bottom-left-radius: 0;" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="url" name="linkedin" class="form-control"
                                                placeholder="Enter your linkedin profile (e.g., https://linkedin.com/in/yourname)" />
                                        </div>

                                        {{-- <a href="#" class="btn btn-submit" id="buttonOne">
                                            Next
                                            <span class="ms-2">
                                                <i class="bi bi-arrow-right"></i></span>
                                        </a> --}}
                                        <button type="button" class="btn next-btn" data-next="form2">Next <span
                                                class="ms-2">
                                                <i class="fa fa-arrow-right"></i></span></button>
                                        {{-- </form> --}}
                                    </div>
                                    <div class="brn-form-side-bg">
                                        <img src="{{ asset('images/brn-frame.png') }}" alt="" />
                                    </div>
                                </div>
                            </div>

                            <div class="brn-grouped-conainer">
                                <img class="brn-group" src="{{ asset('images/brn-group.png') }}" alt="">
                                <img class="brn-image" src="{{ asset('images/NOEL.svg') }}" alt="" />
                            </div>
                        </div>
                    </div>

                    <div class="brn-container form-step  " id="form2">
                        <div class="brn-header-container">
                            <div class="brn-header">
                                <h1>Company Information</h1>
                                <div class="brn-header-desc">
                                    <span></span>
                                    <p>Let's start with your basic details</p>
                                </div>
                            </div>
                        </div>
                        <div class="brn-form-container">
                            <div class="form-conainer">
                                <div class="brn-content">
                                    <div class="brn-form">

                                        <div class="form-progress-indicator-wrapper">
                                            <img id="progress-image" src="{{ asset('images/step2.svg') }}"
                                                alt="Form Progress" class="progress-image">
                                            <div class="step-text-container">
                                                <div class="step-text active" data-step="1">
                                                    <p class="step-title">Personal Information</p>
                                                    <p class="step-desc">Let's start with your <br> basic details</p>
                                                </div>
                                                <div class="step-text" data-step="2">
                                                    <p class="step-title">Company Information</p>
                                                    <p class="step-desc">Tell us about your <br> business</p>
                                                </div>
                                                <div class="step-text" data-step="3">
                                                    <p class="step-title">Choose Interest</p>
                                                    <p class="step-desc">What would you like to get <br> from this
                                                        solution
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <form action=""> --}}
                                        <div class="form-group">
                                            <input type="text" name="company_name" class="form-control"
                                                placeholder="Company Name" />

                                        </div>
                                        <div class="form-group">
                                            <select name="country" class="form-control">
                                                <option value="">Select Country</option>
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
                                                <option value="Saint Vincent and the Grenadines">Saint Vincent and the
                                                    Grenadines</option>
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
                                        <div class="form-group">
                                            <input type="text" name="position" class="form-control"
                                                placeholder="Position in the business" />
                                        </div>

                                        <div class="brn-input-checked">
                                            <div class="brn-input-checked-group">
                                                <span class="brn-input-checked-label">Select your business years of
                                                    operation</span>
                                                <div class="brn-input-checked-options-row">
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" value="0-5" name="years_of_operation"
                                                            id="years0-5" class="brn-input-checked-checkbox" />
                                                        <label for="years0-5" class="brn-input-checked-text">0-5</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" value="6-10" name="years_of_operation"
                                                            id="years6-10" class="brn-input-checked-checkbox" />
                                                        <label for="years6-10" class="brn-input-checked-text">6-10</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" value="11-Above" name="years_of_operation"
                                                            id="years11-above" class="brn-input-checked-checkbox" />
                                                        <label for="years11-above"
                                                            class="brn-input-checked-text">11-Above</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="brn-input-checked-group">
                                                <span class="brn-input-checked-label">Select your number of
                                                    employees</span>
                                                <div class="brn-input-checked-options-row">
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" value="1-10" name="number_of_employees"
                                                            id="employees1-10" class="brn-input-checked-checkbox" />
                                                        <label for="employees1-10"
                                                            class="brn-input-checked-text">1-10</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" value="11-50" name="number_of_employees"
                                                            id="employees11-50" class="brn-input-checked-checkbox" />
                                                        <label for="employees11-50"
                                                            class="brn-input-checked-text">11-50</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" value="50-200" name="number_of_employees"
                                                            id="employees50-200" class="brn-input-checked-checkbox" />
                                                        <label for="employees50-200"
                                                            class="brn-input-checked-text">50-200</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" value="200+" name="number_of_employees"
                                                            id="employees200-plus" class="brn-input-checked-checkbox" />
                                                        <label for="employees200-plus"
                                                            class="brn-input-checked-text">200+</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <a href="#" class="btn btn-submit" id="buttonTwo">
                                            Next
                                            <span class="ms-2">
                                                <i class="bi bi-arrow-right"></i></span>
                                        </a> --}}
                                        <div class="d-flex justify-content-between gap-2 ">

                                            <button type="button" class="btn prev-btn" data-prev="form1"><span
                                                    class="">
                                                    <i class="fa fa-arrow-left"></i></span> Back </button>
                                            <button type="button" class="btn next-btn" data-next="form3">Next <span
                                                    class="">
                                                    <i class="fa fa-arrow-right"></i></span></button>

                                        </div>

                                        {{-- </form> --}}
                                    </div>
                                    <div class="brn-form-side-bg">
                                        <img src="{{ asset('images/brn-frame.png') }}" alt="" />
                                    </div>
                                </div>
                            </div>

                            <div class="brn-grouped-conainer">
                                <img class="brn-group" src="{{ asset('images/brn-group.png') }}" alt="" />
                                <img class="brn-image" src="{{ asset('images/NOEL.svg') }}" alt="" />
                            </div>
                        </div>
                    </div>

                    <div class="brn-container form-step" id="form3">
                        <div class="brn-header-container">
                            <div class="brn-header">
                                <h1>What You Want To Gain</h1>
                                <div class="brn-header-desc">
                                    <span></span>
                                    <p>What outcomes matter most for you?</p>
                                </div>
                            </div>
                        </div>
                        <div class="brn-form-container">
                            <div class="form-conainer">
                                <div class="brn-content">
                                    <div class="brn-form">

                                        <div class="form-progress-indicator-wrapper">
                                            <img id="progress-image" src="{{ asset('images/step3.svg') }}"
                                                alt="Form Progress" class="progress-image">
                                            <div class="step-text-container">
                                                <div class="step-text active" data-step="1">
                                                    <p class="step-title">Personal Information</p>
                                                    <p class="step-desc">Let's start with your <br> basic details</p>
                                                </div>
                                                <div class="step-text" data-step="2">
                                                    <p class="step-title">Company Information</p>
                                                    <p class="step-desc">Tell us about your <br> business</p>
                                                </div>
                                                <div class="step-text" data-step="3">
                                                    <p class="step-title">Choose Interest</p>
                                                    <p class="step-desc">What would you like to get <br> from this
                                                        solution
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <form action=""> --}}
                                        <input type="text" name="goals" class="form-control"
                                            placeholder="Input reason for joining" />

                                        <!-- ============== BRN ICONS CONTENT  -->
                                        <div class="brn-form-icons">
                                            <div class="brn-form-icons-header">
                                                What outcome matter most to you?
                                            </div>
                                            <div class="brn-form-icons-options">
                                                <div class="brn-first-icons">
                                                    <div class="brn-form-icons-card" data-value="Network with like minds">
                                                        <img src="{{ asset('images/checkmark-badge.png') }}"
                                                            alt="" />
                                                        <span class="brn-form-icons-text">Network with like minds</span>
                                                    </div>
                                                    <div class="brn-form-icons-card"
                                                        data-value="No more jump one platform for another">
                                                        <img src="{{ asset('images/checkmark-badge.png') }}"
                                                            alt="" />
                                                        <span class="brn-form-icons-text">No more jump one platform for
                                                            another</span>
                                                    </div>
                                                </div>
                                                <div class="brn-second-icons">
                                                    <div class="brn-form-icons-card" data-value="Grow and Scale 100%">
                                                        <img src="{{ asset('images/checkmark-badge.png') }}"
                                                            alt="" />
                                                        <span class="brn-form-icons-text">Grow and Scale 100%</span>
                                                    </div>
                                                    <div class="brn-form-icons-card" data-value="All you need is here">
                                                        <img src="{{ asset('images/checkmark-badge.png') }}"
                                                            alt="" />
                                                        <span class="brn-form-icons-text">All you need is here</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Hidden input to store the selected value -->
                                            <input type="hidden" name="selected_outcome" id="selectedOutcome" />
                                        </div>

                                        {{-- <div class="brn-input-checked">
                                            <div class="brn-input-checked-group">
                                                <span class="brn-input-checked-label">Select your number of
                                                    employees</span>
                                                <div class="brn-input-checked-options-row">
                                                    <div class="brn-input-checked-option">
                                                        <input type="checkbox" id="employees1-10"
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="employees1-10"
                                                            class="brn-input-checked-text">1-10</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="checkbox" id="employees11-50"
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="employees11-50"
                                                            class="brn-input-checked-text">11-50</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="checkbox" id="employees50-200"
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="employees50-200"
                                                            class="brn-input-checked-text">50-200</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="checkbox" id="employees200-plus"
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="employees200-plus"
                                                            class="brn-input-checked-text">200+</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}

                                        {{-- <a href="#" class="btn btn-submit" id="buttonThree">
                                            Next
                                            <span class="ms-2">
                                                <i class="bi bi-arrow-right"></i></span>
                                        </a> --}}
                                        <div class="d-flex justify-content-between gap-2 ">
                                            <button type="button" class="btn prev-btn" data-prev="form2"><span
                                                    class="">
                                                    <i class="bi bi-arrow-left"></i></span> Back</button>

                                            <button type="submit" id="submitBtn" class="btn btn-submit d-inline">
                                                <span class="btn-text">Submit</span>
                                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                                    aria-hidden="true"></span>
                                            </button>
                                        </div>
                                        {{-- </form> --}}
                                    </div>
                                    <div class="brn-form-side-bg">
                                        <img src="{{ asset('images/brn-frame.png') }}" alt="" />
                                    </div>
                                </div>
                            </div>

                            <div class="brn-grouped-conainer">
                                <img class="brn-group" src="{{ asset('images/brn-group.png') }}" alt="" />
                                <img class="brn-image" src="{{ asset('images/NOEL.svg') }}" alt="" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="responseModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4">
                    <h5 class="modal-title" id="responseMessage"></h5>
                    <button type="button" class="btn btn-primary mt-3" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>


        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script> -->
        {{-- <script src="./js/bootstrap.min.js"></script> --}}
    </section>
@endsection

<script>
    // Comprehensive form handling - single DOMContentLoaded event
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
                } else {
                    formData[element.name] = element.value;
                }
            });

            // Combine country code and phone number for storage
            const countryCode = form.querySelector('select[name="country_code"]');
            const phoneInput = form.querySelector('input[name="phone"]');
            if (countryCode && phoneInput && phoneInput.value.trim()) {
                formData['full_phone'] = countryCode.value + phoneInput.value.trim();
            }

            // Save current step
            const activeStep = document.querySelector('.form-step.active');
            if (activeStep) {
                localStorage.setItem(STEP_STORAGE_KEY, activeStep.id);
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

                    // Restore selected outcome cards
                    if (formData.selected_outcome) {
                        const cards = document.querySelectorAll('.brn-form-icons-card');
                        cards.forEach(card => {
                            card.classList.remove('selected');
                            if (card.dataset.value === formData.selected_outcome) {
                                card.classList.add('selected');
                            }
                        });
                    }

                    // Auto-revalidate email if it exists and is valid format
                    if (formData.email && isValidEmail(formData.email)) {
                        // Small delay to ensure DOM is ready
                        setTimeout(() => {
                            validateEmailInDatabase(formData.email);
                        }, 100);
                    }

                    // Restore current step
                    if (savedStep && savedStep !== 'form1') {
                        document.querySelectorAll('.form-step').forEach(step => step.classList.remove(
                            'active'));
                        const stepToShow = document.getElementById(savedStep);
                        if (stepToShow) {
                            stepToShow.classList.add('active');
                        }
                    }
                }
            } catch (error) {
                console.error('Error restoring form data:', error);
            }
        }

        // Clear form data from localStorage
        function clearFormData() {
            localStorage.removeItem(FORM_STORAGE_KEY);
            localStorage.removeItem(STEP_STORAGE_KEY);
        }

        // Email validation utility functions (moved here to be available during restore)
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

            fetch('{{ locale_route('brn.check-email') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        showEmailStatus('hide');
                        // Clear any existing errors first, then show the new one
                        clearError(emailInput);
                        showError(emailInput, data.message);
                    } else {
                        showEmailStatus('success');
                        clearError(emailInput);
                        emailInput.style.border = '2px solid #10b981';
                        emailInput.style.backgroundColor = '#f0fdf4';
                    }
                })
                .catch(error => {
                    console.error('Email validation error:', error);
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

            // Save when outcome cards are selected
            const cards = document.querySelectorAll('.brn-form-icons-card');
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    setTimeout(saveFormData, 100); // Small delay to ensure DOM is updated
                });
            });
        }

        // Initialize form persistence
        restoreFormData();
        setupAutoSave();

        // Optional: Add a way to clear saved data (for development/testing)
        // You can call this in console: window.clearBrnFormData()
        window.clearBrnFormData = function() {
            clearFormData();
            location.reload();
        };

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
                    const errorMessage = errors[field][0]; // Get first error message

                    // Ensure the input is clean before adding server error
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

        // Define validateStep function to avoid duplication with validateStepNavigation
        function validateStep(stepElement) {
            return validateStepNavigation(stepElement);
        }

        function showError(input, message) {
            // Clear any existing errors for this input first
            clearError(input);

            // Add error styling
            input.style.border = '2px solid #ef4444';
            input.style.backgroundColor = '#fef2f2';

            // Create error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.color = '#ef4444';
            errorDiv.style.fontSize = '0.875rem';
            errorDiv.style.marginTop = '4px';
            errorDiv.textContent = message;

            // Insert error message after input
            input.parentNode.insertBefore(errorDiv, input.nextSibling);
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate all steps before submission
            let allValid = true;
            const allSteps = document.querySelectorAll('.form-step');

            allSteps.forEach(step => {
                if (!validateStep(step)) {
                    allValid = false;
                }
            });

            if (!allValid) {
                // Show first error and switch to that step
                const firstErrorStep = document.querySelector('.form-step .error-message').closest(
                    '.form-step');
                if (firstErrorStep) {
                    // Switch to the step with error
                    document.querySelectorAll('.form-step').forEach(step => step.classList.remove(
                        'active'));
                    firstErrorStep.classList.add('active');

                    // Scroll to first error
                    const firstError = firstErrorStep.querySelector('.error-message');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }
                return;
            }

            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
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
                    // Success response
                    responseMessage.textContent = data.message ||
                        'Your application was submitted successfully!';
                    responseMessage.style.color = '#059669';
                    modal.show();

                    // Clear saved form data from localStorage
                    clearFormData();

                    // Reset form and go back to first step
                    form.reset();
                    document.querySelectorAll('.form-step').forEach(step => step.classList.remove(
                        'active'));
                    document.getElementById('form1').classList.add('active');

                    // Clear any selected outcome cards
                    document.querySelectorAll('.brn-form-icons-card').forEach(card => {
                        card.classList.remove('selected');
                    });
                })
                .catch(error => {
                    if (error.status === 422 && error.data.errors) {
                        // Validation errors from server
                        showServerErrors(error.data.errors);
                        responseMessage.textContent =
                            'Please correct the errors below and try again.';
                        responseMessage.style.color = '#ef4444';
                        modal.show();
                    } else if (error.status === 422 && error.data.message) {
                        // Custom validation message (like duplicate email)
                        responseMessage.textContent = error.data.message;
                        responseMessage.style.color = '#ef4444';
                        modal.show();
                    } else {
                        // Network or other errors
                        responseMessage.textContent =
                            'Something went wrong. Please check your internet connection and try again.';
                        responseMessage.style.color = '#ef4444';
                        modal.show();
                    }
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.classList.remove('d-none');
                });
        });

        // Clear server errors when user starts typing
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', function() {
                const serverError = this.parentNode.querySelector('.server-error');
                if (serverError) {
                    serverError.remove();
                    this.style.border = '';
                    this.style.backgroundColor = '';
                }
            });
        });

        // Handle icon selection with error clearing
        const cards = document.querySelectorAll('.brn-form-icons-card');
        const hiddenInput = document.getElementById('selectedOutcome');

        cards.forEach(card => {
            card.addEventListener('click', function() {
                // Remove 'selected' class from all cards
                cards.forEach(c => c.classList.remove('selected'));

                // Add 'selected' class to the clicked card
                this.classList.add('selected');

                // Update the hidden input value with the selected card's data-value
                hiddenInput.value = this.dataset.value;

                // Clear any error message for outcome selection
                const iconsContainer = document.querySelector('.brn-form-icons');
                const errorMsg = iconsContainer.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }

                // Save form data after selection
                saveFormData();
            });
        });

        // Form Slider with comprehensive validation
        const steps = document.querySelectorAll('.form-step');
        const nextBtns = document.querySelectorAll('.next-btn');
        const prevBtns = document.querySelectorAll('.prev-btn');

        function showStep(stepId) {
            const steps = document.querySelectorAll('.form-step');

            steps.forEach(step => step.classList.remove('active'));

            const nextStep = document.getElementById(stepId);
            if (nextStep) nextStep.classList.add('active');

            const stepTexts = nextStep.querySelectorAll('.step-text');
            stepTexts.forEach(text => text.classList.remove('active'));

            const activeStepIndex = Array.from(steps).findIndex(step => step.id === stepId);
            const activeStepText = nextStep.querySelector(`.step-text[data-step="${activeStepIndex + 1}"]`);
            if (activeStepText) activeStepText.classList.add('active');

            // Save current step to localStorage
            localStorage.setItem(STEP_STORAGE_KEY, stepId);
        }



        function clearError(input) {
            input.style.border = '';
            input.style.backgroundColor = '';

            // Remove error message
            const errorMsg = input.parentNode.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.remove();
            }
        }

        function validateStepNavigation(stepElement) {
            const stepId = stepElement.id;
            let isValid = true;

            // Clear all previous errors in this step
            stepElement.querySelectorAll('.error-message').forEach(msg => msg.remove());
            stepElement.querySelectorAll('input, select, textarea').forEach(input => {
                clearError(input);
            });

            if (stepId === 'form1') {
                // Step 1: Personal Information validation
                const name = stepElement.querySelector('input[name="name"]');
                const email = stepElement.querySelector('input[name="email"]');
                const phone = stepElement.querySelector('input[name="phone"]');
                const linkedin = stepElement.querySelector('input[name="linkedin"]');

                if (!name.value.trim()) {
                    showError(name, 'Full name is required');
                    isValid = false;
                }

                if (!email.value.trim()) {
                    showError(email, 'Email address is required');
                    isValid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
                    showError(email, 'Please enter a valid email address');
                    isValid = false;
                } else {
                    // Check if email has red border (indicating it already exists)
                    const emailStyle = window.getComputedStyle(email);
                    if (emailStyle.borderColor === 'rgb(239, 68, 68)' || email.style.borderColor.includes(
                            '#ef4444')) {
                        showError(email, 'This email address is already registered');
                        isValid = false;
                    }
                    // Check if email validation is complete (has green checkmark)
                    const emailCheckmark = document.getElementById('email-checkmark');
                    if (emailCheckmark && emailCheckmark.style.display !== 'block') {
                        showError(email, 'Please wait for email validation to complete');
                        isValid = false;
                    }
                }

                if (!phone.value.trim()) {
                    showError(phone, 'Phone number is required');
                    isValid = false;
                } else if (!/^[0-9\s\-\+\(\)]{7,15}$/.test(phone.value.trim())) {
                    showError(phone, 'Please enter a valid phone number (7-15 digits)');
                    isValid = false;
                }

                if (!linkedin.value.trim()) {
                    showError(linkedin, 'LinkedIn profile is required');
                    isValid = false;
                } else if (!linkedin.value.toLowerCase().includes('linkedin.com')) {
                    showError(linkedin, 'Please enter a valid LinkedIn profile URL');
                    isValid = false;
                }

            } else if (stepId === 'form2') {
                // Step 2: Company Information validation
                const companyName = stepElement.querySelector('input[name="company_name"]');
                const country = stepElement.querySelector('select[name="country"]');
                const position = stepElement.querySelector('input[name="position"]');
                const yearsOfOperation = stepElement.querySelector('input[name="years_of_operation"]:checked');
                const numberOfEmployees = stepElement.querySelector(
                    'input[name="number_of_employees"]:checked');

                if (!companyName.value.trim()) {
                    showError(companyName, 'Company name is required');
                    isValid = false;
                }

                if (!country.value.trim()) {
                    showError(country, 'Business headquarters country is required');
                    isValid = false;
                }

                if (!position.value.trim()) {
                    showError(position, 'Your position in the business is required');
                    isValid = false;
                }

                if (!yearsOfOperation) {
                    const yearsGroup = stepElement.querySelector('.brn-input-checked-group');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '8px';
                    errorDiv.textContent = 'Please select your business years of operation';
                    yearsGroup.appendChild(errorDiv);
                    isValid = false;
                }

                if (!numberOfEmployees) {
                    const employeesGroup = stepElement.querySelectorAll('.brn-input-checked-group')[1];
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '8px';
                    errorDiv.textContent = 'Please select your number of employees';
                    employeesGroup.appendChild(errorDiv);
                    isValid = false;
                }

            } else if (stepId === 'form3') {
                // Step 3: Outcomes validation
                const goals = stepElement.querySelector('input[name="goals"]');
                const selectedOutcome = stepElement.querySelector('input[name="selected_outcome"]');

                // Validate goals input
                if (!goals.value.trim()) {
                    showError(goals, 'Please input your reason for joining');
                    isValid = false;
                }

                // Validate selected outcome
                if (!selectedOutcome || !selectedOutcome.value) {
                    const iconsContainer = stepElement.querySelector('.brn-form-icons');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '12px';
                    errorDiv.style.textAlign = 'center';
                    errorDiv.textContent = 'Please select what outcomes matter most to you';
                    iconsContainer.appendChild(errorDiv);
                    isValid = false;
                }
            }

            return isValid;
        }

        nextBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentStep = this.closest('.form-step');

                if (validateStepNavigation(currentStep)) {
                    // Move to next step only if validation passes
                    const next = this.dataset.next;
                    showStep(next);
                } else {
                    // Scroll to first error
                    const firstError = currentStep.querySelector('.error-message');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    // Show validation feedback
                    btn.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        btn.style.transform = '';
                    }, 150);
                }
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const prev = this.dataset.prev;
                showStep(prev);
            });
        });

        // Real-time validation feedback
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', function() {
                if (this.style.border && this.style.border.includes('red')) {
                    clearError(this);
                }
            });
        });

        // Clear radio button errors when selected
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const group = this.closest('.brn-input-checked-group');
                const errorMsg = group.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            });
        });

        // Real-time email validation setup
        const emailInput = document.querySelector('input[name="email"]');
        let emailTimeout;

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
                    // If it doesn't look like LinkedIn, return original
                    return url;
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
                    normalizedUrl = 'linkedin.com';
                }
            }

            // Add https prefix
            return 'https://' + normalizedUrl;
        }

        if (emailInput) {
            emailInput.addEventListener('input', function() {
                clearTimeout(emailTimeout);
                const email = this.value.trim();

                // Clear previous styling
                this.style.border = '';
                this.style.backgroundColor = '';

                if (email === '') {
                    showEmailStatus('hide');
                    return;
                }

                // Debounce the API call
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

        // LinkedIn URL auto-formatting
        const linkedinInput = document.querySelector('input[name="linkedin"]');
        if (linkedinInput) {
            linkedinInput.addEventListener('blur', function() {
                const currentValue = this.value.trim();
                if (currentValue && currentValue.toLowerCase().includes('linkedin')) {
                    const normalizedUrl = normalizeLinkedInURL(currentValue);
                    if (normalizedUrl !== currentValue) {
                        this.value = normalizedUrl;

                        // Clear any existing errors since we've formatted the URL
                        const errorMsg = this.parentNode.querySelector('.error-message');
                        if (errorMsg) {
                            errorMsg.remove();
                        }
                        this.style.border = '';
                        this.style.backgroundColor = '';

                        // Save the normalized URL
                        saveFormData();
                    }
                }
            });

            // Also format on paste
            linkedinInput.addEventListener('paste', function() {
                setTimeout(() => {
                    const currentValue = this.value.trim();
                    if (currentValue && currentValue.toLowerCase().includes('linkedin')) {
                        const normalizedUrl = normalizeLinkedInURL(currentValue);
                        if (normalizedUrl !== currentValue) {
                            this.value = normalizedUrl;

                            // Clear any existing errors since we've formatted the URL
                            const errorMsg = this.parentNode.querySelector('.error-message');
                            if (errorMsg) {
                                errorMsg.remove();
                            }
                            this.style.border = '';
                            this.style.backgroundColor = '';

                            // Save the normalized URL
                            saveFormData();
                        }
                    }
                }, 100);
            });
        }
    });
</script>