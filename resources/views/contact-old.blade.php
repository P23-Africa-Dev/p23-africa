@extends('layouts.master')
@section('title', 'Contact | P23 Africa')

@section('meta')
    <meta name="description"
        content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
    <meta name="keywords"
        content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Business Consulting & Growth Solutions for Africa | P23 Africa">
    <meta property="og:image" content="{{ asset('images/contact_header.jpg') }}">
@endsection

<style>
    /* Phone input styling */
    .phone-input-container {
        position: relative;
    }

    .country-code-select {
        font-size: 14px;
        text-align: center;
        /* background-color: #1a75cf !important; */
        background: #1d6154 !important;
        border-color: #ced4da;
    }

    .country-code-select:focus {
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(25, 62, 71, 0.1);
    }

    .phone-input-container input[name="phone_no"]:focus {
        box-shadow: 0 0 0 3px rgba(25, 62, 71, 0.1);
    }
</style>


@section('content')
    <section id="whole_contact">
        <!-- Contact Section -->
        <section class="contact-section animate-on-scroll">

        </section>
        <div class="container" id="sec2">
            <div class="row">
                <!-- Text Side -->
                <div class="col-12 col-md-6 contact-text text-center text-md-start mb-4 mb-md-0">
                    <h2>Contact Us</h2>
                    <p>Ready to expand your business?<br>
                        <span>Contact P23 Africa and let’s make growth happen.</span>
                    </p>
                </div>

                <!-- Image Side -->
                <div class="col-12 col-md-6 text-center">
                    <div class="contact-image mx-auto" style="max-width: 380px;">
                        <img src="{{ asset('images/contact_header.png') }}" loading='lazy' alt="Smiling Woman" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <section class="contact-info animate-on-scroll">
            <div class="container text-left text-md-start">
                <h5 class="text-center text-md-start">Contact Information</h5>
                <div style="gap: 10px"
                    class="d-flex flex-lg-row col-lg-6 justify-content-center justify-content-md-start text-left text-md-start"
                    id="con">
                    <div class="col-md-4 info-box mb-4 mb-md-0">
                        <i class="fas fa-phone"></i>
                        <p><a href="tel:+442080921281" class="text-dark text-decoration-none fw-bold">+44 208 0921 281</a>
                        </p>
                    </div>
                    <div class="col-md-4 info-box mb-4 mb-md-0">
                        <i class="fas fa-envelope"></i>
                        <p><a href="mailto:hello@p23africa.com"
                                class="text-dark text-decoration-none fw-bold">hello@p23africa.com</a></p>
                    </div>
                    <div class="col-md-4 info-box">
                        <i class="fas fa-map-marker-alt"></i>
                        <p class="fw-bold">London UK</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact_form" class="animate-on-scroll">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div id="form">
                            <div>
                                <h6>Leave us a message</h6>
                                @if (session('success'))
                                    <script>
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: "{{ session('success') }}"
                                        });
                                    </script>
                                @elseif (session('error'))
                                    <script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: "{{ session('error') }}"
                                        });
                                    </script>
                                @endif
                                <div>
                                    <form action="{{ route('contact.submit') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" class="form-control" required placeholder="Name"
                                                name="name" id="">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control" required placeholder="Email Address"
                                                name="email" id="">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Job Title (Optional)"
                                                name="job_title" id="">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Company Name (Optional)"
                                                name="company_name" id="">
                                        </div>
                                        <div class="form-group">
                                            <div class="phone-input-container" style="display: flex; gap: 0;">
                                                <select name="country_code" class="form-control country-code-select"
                                                    style="flex: 0 0 100px; border-right: none; border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                                    <option value="+234" data-flag="🇳🇬">🇳🇬 +234</option>
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
                                                    <option value="+234" data-flag="🇳🇬">🇳🇬 +234</option>
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
                                                    <option value="+44" data-flag="🇬🇧" selected>🇬🇧 +44</option>
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
                                                <input type="tel" name="phone_no" class="form-control" required
                                                    placeholder="Enter phone number"
                                                    style="flex: 1; border-left: none; border-top-left-radius: 0; border-bottom-left-radius: 0;" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <textarea name="message" class="form-control" required placeholder="Your Message" id="" cols="30"
                                                rows="7"></textarea>
                                        </div>

                                        <div class="g-recaptcha" data-sitekey="6LeFmtMrAAAAAKQ4SHr9Z9NdNPyjwmljCV-iMUo6">
                                        </div>

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li style="font-size: 11px;">{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="form-group container">
                                            <button type="submit" class="btn send_messages">Send</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div id="map">
                            <strong>Location</strong>
                            <p>
                                185 Tower Bridge Rd <br>
                                185 Tower Bridge Rd, London SE1 2UF, UK
                            </p>
                            <div id="google-map">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2483.5780880978436!2d-0.08011252412324342!3d51.50260951105425!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48760345c64c3edb%3A0x617596184d03a84c!2s185%20Tower%20Bridge%20Rd%2C%20London%20SE1%202UF%2C%20UK!5e0!3m2!1sen!2sng!4v1746443001887!5m2!1sen!2sng"
                                    width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                                {{-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9582196.954783212!2d-15.016855774923814!3d54.1020496614009!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x25a3b1142c791a9%3A0xc4f8a0433288257a!2sUnited%20Kingdom!5e0!3m2!1sen!2sng!4v1745502051728!5m2!1sen!2sng" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> --}}
                            </div>

                            <br>
                            <strong>Social Media</strong>
                            <div id="icons">
                                <a href="https://linkedin.com/company/p23-africa/" target="_blank"
                                    class="fa-brands fa-linkedin mx-1"></a>
                                <a href="https://facebook.com/p23africa" target="_blank"
                                    class="fa-brands fa-square-facebook mx-1"></a>
                                <a href="https://www.instagram.com/p23africa/" target="_blank"
                                    class="fa-brands fa-square-instagram mx-1"></a>
                                <a href="#" class="fa-brands fa-youtube mx-1"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="animate-on-scroll pb-5">
            <div id="contact-img">
                <img src="{{ asset('images/contact-box.png') }}" loading='lazy' style="" alt="">
            </div>
            <div class="subscribe-box">
                <div class="subcribe-content">
                    <div class="subscribe-text">
                        <h2>Subscribe</h2>
                        <p>to our Newsletter</p>
                    </div>
                    <form class="subscribe-form" action="#" method="post">
                        <input type="email" name="email" placeholder="Email" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
        </section>
    </section>

    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
@endsection
