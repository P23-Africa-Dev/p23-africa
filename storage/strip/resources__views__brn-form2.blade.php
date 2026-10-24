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

{{--  --}}



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


        <!--  -->
        {{--  --}}
    </section>
@endsection

