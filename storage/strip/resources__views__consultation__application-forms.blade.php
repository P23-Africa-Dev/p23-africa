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


<!--   -->



@section('content')
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
                            <h2>{{ __('consultation.overview') }}</h2>
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
                            <h2>{{ __('consultation.goals') }}</h2>

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
                            <h2>{{ __('consultation.challenges') }}</h2>

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
                            <h2>{{ __('consultation.outcome') }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step Content -->
            <form id="multiStepForm" method="POST" action="{{ locale_route('consultation.submit') }}">
                @csrf

                <!-- STEP 1 -->
                <div class="form-step active">
                    <div class="form-grid">
                        <div class="grid-input">
                            <input type="text" name="full_name" placeholder="Full Name" value="{{ old('full_name') }}"
                                required />
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

                                        <div class="flag-item" data-flag="https://flagcdn.com/jp.svg" data-code="+81">
                                            <img src="https://flagcdn.com/jp.svg"> Japan (+81)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/de.svg" data-code="+49">
                                            <img src="https://flagcdn.com/de.svg"> Germany (+49)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/fr.svg" data-code="+33">
                                            <img src="https://flagcdn.com/fr.svg"> France (+33)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/it.svg" data-code="+39">
                                            <img src="https://flagcdn.com/it.svg"> Italy (+39)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ru.svg" data-code="+7">
                                            <img src="https://flagcdn.com/ru.svg"> Russia (+7)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/br.svg" data-code="+55">
                                            <img src="https://flagcdn.com/br.svg"> Brazil (+55)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/mx.svg" data-code="+52">
                                            <img src="https://flagcdn.com/mx.svg"> Mexico (+52)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/au.svg" data-code="+61">
                                            <img src="https://flagcdn.com/au.svg"> Australia (+61)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/za.svg" data-code="+27">
                                            <img src="https://flagcdn.com/za.svg"> South Africa (+27)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ng.svg" data-code="+234">
                                            <img src="https://flagcdn.com/ng.svg"> Nigeria (+234)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ke.svg" data-code="+254">
                                            <img src="https://flagcdn.com/ke.svg"> Kenya (+254)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/gh.svg" data-code="+233">
                                            <img src="https://flagcdn.com/gh.svg"> Ghana (+233)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ug.svg" data-code="+256">
                                            <img src="https://flagcdn.com/ug.svg"> Uganda (+256)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/tz.svg" data-code="+255">
                                            <img src="https://flagcdn.com/tz.svg"> Tanzania (+255)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/eg.svg" data-code="+20">
                                            <img src="https://flagcdn.com/eg.svg"> Egypt (+20)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/dz.svg" data-code="+213">
                                            <img src="https://flagcdn.com/dz.svg"> Algeria (+213)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ma.svg" data-code="+212">
                                            <img src="https://flagcdn.com/ma.svg"> Morocco (+212)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/tn.svg" data-code="+216">
                                            <img src="https://flagcdn.com/tn.svg"> Tunisia (+216)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ly.svg" data-code="+218">
                                            <img src="https://flagcdn.com/ly.svg"> Libya (+218)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/et.svg" data-code="+251">
                                            <img src="https://flagcdn.com/et.svg"> Ethiopia (+251)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/zm.svg" data-code="+260">
                                            <img src="https://flagcdn.com/zm.svg"> Zambia (+260)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/zw.svg" data-code="+263">
                                            <img src="https://flagcdn.com/zw.svg"> Zimbabwe (+263)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/mw.svg" data-code="+265">
                                            <img src="https://flagcdn.com/mw.svg"> Malawi (+265)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/bw.svg" data-code="+267">
                                            <img src="https://flagcdn.com/bw.svg"> Botswana (+267)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/sz.svg" data-code="+268">
                                            <img src="https://flagcdn.com/sz.svg"> Eswatini (+268)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ls.svg" data-code="+266">
                                            <img src="https://flagcdn.com/ls.svg"> Lesotho (+266)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/na.svg" data-code="+264">
                                            <img src="https://flagcdn.com/na.svg"> Namibia (+264)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/mz.svg" data-code="+258">
                                            <img src="https://flagcdn.com/mz.svg"> Mozambique (+258)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/mg.svg" data-code="+261">
                                            <img src="https://flagcdn.com/mg.svg"> Madagascar (+261)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/mu.svg" data-code="+230">
                                            <img src="https://flagcdn.com/mu.svg"> Mauritius (+230)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/sc.svg" data-code="+248">
                                            <img src="https://flagcdn.com/sc.svg"> Seychelles (+248)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/rw.svg" data-code="+250">
                                            <img src="https://flagcdn.com/rw.svg"> Rwanda (+250)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/sn.svg" data-code="+221">
                                            <img src="https://flagcdn.com/sn.svg"> Senegal (+221)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ci.svg" data-code="+225">
                                            <img src="https://flagcdn.com/ci.svg"> Ivory Coast (+225)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/bf.svg" data-code="+226">
                                            <img src="https://flagcdn.com/bf.svg"> Burkina Faso (+226)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ml.svg" data-code="+223">
                                            <img src="https://flagcdn.com/ml.svg"> Mali (+223)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ne.svg" data-code="+227">
                                            <img src="https://flagcdn.com/ne.svg"> Niger (+227)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/td.svg" data-code="+235">
                                            <img src="https://flagcdn.com/td.svg"> Chad (+235)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/cm.svg" data-code="+237">
                                            <img src="https://flagcdn.com/cm.svg"> Cameroon (+237)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/cf.svg" data-code="+236">
                                            <img src="https://flagcdn.com/cf.svg"> Central African Republic (+236)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/cg.svg" data-code="+242">
                                            <img src="https://flagcdn.com/cg.svg"> Republic of Congo (+242)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/cd.svg" data-code="+243">
                                            <img src="https://flagcdn.com/cd.svg"> Democratic Republic of Congo (+243)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ga.svg" data-code="+241">
                                            <img src="https://flagcdn.com/ga.svg"> Gabon (+241)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/gq.svg" data-code="+240">
                                            <img src="https://flagcdn.com/gq.svg"> Equatorial Guinea (+240)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/st.svg" data-code="+239">
                                            <img src="https://flagcdn.com/st.svg"> São Tomé and Príncipe (+239)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/cv.svg" data-code="+238">
                                            <img src="https://flagcdn.com/cv.svg"> Cape Verde (+238)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/gn.svg" data-code="+224">
                                            <img src="https://flagcdn.com/gn.svg"> Guinea (+224)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/gw.svg" data-code="+245">
                                            <img src="https://flagcdn.com/gw.svg"> Guinea-Bissau (+245)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/lr.svg" data-code="+231">
                                            <img src="https://flagcdn.com/lr.svg"> Liberia (+231)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/sl.svg" data-code="+232">
                                            <img src="https://flagcdn.com/sl.svg"> Sierra Leone (+232)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/tg.svg" data-code="+228">
                                            <img src="https://flagcdn.com/tg.svg"> Togo (+228)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/bj.svg" data-code="+229">
                                            <img src="https://flagcdn.com/bj.svg"> Benin (+229)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/mr.svg" data-code="+222">
                                            <img src="https://flagcdn.com/mr.svg"> Mauritania (+222)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/gm.svg" data-code="+220">
                                            <img src="https://flagcdn.com/gm.svg"> Gambia (+220)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/bi.svg" data-code="+257">
                                            <img src="https://flagcdn.com/bi.svg"> Burundi (+257)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/dj.svg" data-code="+253">
                                            <img src="https://flagcdn.com/dj.svg"> Djibouti (+253)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/er.svg" data-code="+291">
                                            <img src="https://flagcdn.com/er.svg"> Eritrea (+291)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/so.svg" data-code="+252">
                                            <img src="https://flagcdn.com/so.svg"> Somalia (+252)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ss.svg" data-code="+211">
                                            <img src="https://flagcdn.com/ss.svg"> South Sudan (+211)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/sd.svg" data-code="+249">
                                            <img src="https://flagcdn.com/sd.svg"> Sudan (+249)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ao.svg" data-code="+244">
                                            <img src="https://flagcdn.com/ao.svg"> Angola (+244)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/km.svg" data-code="+269">
                                            <img src="https://flagcdn.com/km.svg"> Comoros (+269)
                                        </div>

                                        <!-- Non-African Countries -->
                                        <div class="flag-item" data-flag="https://flagcdn.com/ca.svg" data-code="+1">
                                            <img src="https://flagcdn.com/ca.svg"> Canada (+1)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/es.svg" data-code="+34">
                                            <img src="https://flagcdn.com/es.svg"> Spain (+34)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/pt.svg" data-code="+351">
                                            <img src="https://flagcdn.com/pt.svg"> Portugal (+351)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/nl.svg" data-code="+31">
                                            <img src="https://flagcdn.com/nl.svg"> Netherlands (+31)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/be.svg" data-code="+32">
                                            <img src="https://flagcdn.com/be.svg"> Belgium (+32)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ch.svg" data-code="+41">
                                            <img src="https://flagcdn.com/ch.svg"> Switzerland (+41)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/at.svg" data-code="+43">
                                            <img src="https://flagcdn.com/at.svg"> Austria (+43)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/se.svg" data-code="+46">
                                            <img src="https://flagcdn.com/se.svg"> Sweden (+46)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/no.svg" data-code="+47">
                                            <img src="https://flagcdn.com/no.svg"> Norway (+47)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/dk.svg" data-code="+45">
                                            <img src="https://flagcdn.com/dk.svg"> Denmark (+45)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/fi.svg" data-code="+358">
                                            <img src="https://flagcdn.com/fi.svg"> Finland (+358)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ie.svg" data-code="+353">
                                            <img src="https://flagcdn.com/ie.svg"> Ireland (+353)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/nz.svg" data-code="+64">
                                            <img src="https://flagcdn.com/nz.svg"> New Zealand (+64)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/sg.svg" data-code="+65">
                                            <img src="https://flagcdn.com/sg.svg"> Singapore (+65)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/my.svg" data-code="+60">
                                            <img src="https://flagcdn.com/my.svg"> Malaysia (+60)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/th.svg" data-code="+66">
                                            <img src="https://flagcdn.com/th.svg"> Thailand (+66)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ph.svg" data-code="+63">
                                            <img src="https://flagcdn.com/ph.svg"> Philippines (+63)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/id.svg" data-code="+62">
                                            <img src="https://flagcdn.com/id.svg"> Indonesia (+62)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/vn.svg" data-code="+84">
                                            <img src="https://flagcdn.com/vn.svg"> Vietnam (+84)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/kr.svg" data-code="+82">
                                            <img src="https://flagcdn.com/kr.svg"> South Korea (+82)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/pk.svg" data-code="+92">
                                            <img src="https://flagcdn.com/pk.svg"> Pakistan (+92)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/bd.svg" data-code="+880">
                                            <img src="https://flagcdn.com/bd.svg"> Bangladesh (+880)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/lk.svg" data-code="+94">
                                            <img src="https://flagcdn.com/lk.svg"> Sri Lanka (+94)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ar.svg" data-code="+54">
                                            <img src="https://flagcdn.com/ar.svg"> Argentina (+54)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/cl.svg" data-code="+56">
                                            <img src="https://flagcdn.com/cl.svg"> Chile (+56)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/co.svg" data-code="+57">
                                            <img src="https://flagcdn.com/co.svg"> Colombia (+57)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/pe.svg" data-code="+51">
                                            <img src="https://flagcdn.com/pe.svg"> Peru (+51)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ve.svg" data-code="+58">
                                            <img src="https://flagcdn.com/ve.svg"> Venezuela (+58)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ec.svg" data-code="+593">
                                            <img src="https://flagcdn.com/ec.svg"> Ecuador (+593)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/uy.svg" data-code="+598">
                                            <img src="https://flagcdn.com/uy.svg"> Uruguay (+598)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/py.svg" data-code="+595">
                                            <img src="https://flagcdn.com/py.svg"> Paraguay (+595)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/bo.svg" data-code="+591">
                                            <img src="https://flagcdn.com/bo.svg"> Bolivia (+591)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/gy.svg" data-code="+592">
                                            <img src="https://flagcdn.com/gy.svg"> Guyana (+592)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/sr.svg" data-code="+597">
                                            <img src="https://flagcdn.com/sr.svg"> Suriname (+597)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/tr.svg" data-code="+90">
                                            <img src="https://flagcdn.com/tr.svg"> Turkey (+90)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ae.svg" data-code="+971">
                                            <img src="https://flagcdn.com/ae.svg"> United Arab Emirates (+971)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/sa.svg" data-code="+966">
                                            <img src="https://flagcdn.com/sa.svg"> Saudi Arabia (+966)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/qa.svg" data-code="+974">
                                            <img src="https://flagcdn.com/qa.svg"> Qatar (+974)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/kw.svg" data-code="+965">
                                            <img src="https://flagcdn.com/kw.svg"> Kuwait (+965)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/bh.svg" data-code="+973">
                                            <img src="https://flagcdn.com/bh.svg"> Bahrain (+973)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/om.svg" data-code="+968">
                                            <img src="https://flagcdn.com/om.svg"> Oman (+968)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/jo.svg" data-code="+962">
                                            <img src="https://flagcdn.com/jo.svg"> Jordan (+962)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/lb.svg" data-code="+961">
                                            <img src="https://flagcdn.com/lb.svg"> Lebanon (+961)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/sy.svg" data-code="+963">
                                            <img src="https://flagcdn.com/sy.svg"> Syria (+963)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/iq.svg" data-code="+964">
                                            <img src="https://flagcdn.com/iq.svg"> Iraq (+964)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ir.svg" data-code="+98">
                                            <img src="https://flagcdn.com/ir.svg"> Iran (+98)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/af.svg" data-code="+93">
                                            <img src="https://flagcdn.com/af.svg"> Afghanistan (+93)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/il.svg" data-code="+972">
                                            <img src="https://flagcdn.com/il.svg"> Israel (+972)
                                        </div>

                                        <div class="flag-item" data-flag="https://flagcdn.com/ps.svg" data-code="+970">
                                            <img src="https://flagcdn.com/ps.svg"> Palestine (+970)
                                        </div>

                                    </div>

                                </div>

                                <!-- PREFIX + INPUT -->
                                <div class="number-wrapper">
                                    <span id="countryPrefix" style="display: none;"></span>
                                    <input type="text" id="phoneInput" placeholder="Select country first" disabled>
                                </div>

                                <!-- Hidden field for form submission -->
                                <input type="hidden" name="phone" id="combinedPhone" value="{{ old('phone') }}" />

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
                                value="{{ old('website_or_handle') }}" />
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
                                <input type="checkbox" name="business_goals[]" value="Launch a new product or service" />
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
                                <input type="checkbox" name="key_challenges[]" value="Difficulty acquiring customers" />
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



