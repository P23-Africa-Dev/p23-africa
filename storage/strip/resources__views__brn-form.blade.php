@extends('layouts.brn-layout')
@section('title', 'NOELHQ Interest Form | P23 Africa')

@section('meta')
    <meta name="description"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth.">
    <meta name="keywords"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth.">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth | P23 Africa">
    {{-- <!--<meta property="og:image" content="{{ asset('images/brn-form-meta.png') }}">--> --}}
    {{-- <meta property="og:image" content="{{ asset('images/noel-thumbnail.png') }}"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

{{--  --}}



@section('content')
    <section class="interest-form-layout-container">
        <div class="form-layout-container">
            <div class="row-one-container">
                <!-- Mobile Heading -->
                <section class="interest-form-heading mobile-heading">
                    <!-- First Form Heading -->
                    <div class="first-form-heading hidden">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>{{ __('brn.basic') }}</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Let's start with your basic details</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Form Heading -->
                    <div class="second-form-heading hidden">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>{{ __('brn.credibility') }}</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Stand out among other businesses</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Third Form Heading -->
                    <div class="third-form-heading hidden">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>{{ __('brn.preference') }}</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Boost visibility for your matches</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fourth Form Heading -->
                    <div class="fourth-form-heading">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>{{ __('brn.insight') }}</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Stand out among other businesses</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Form Section -->
                <section class="interest-form-container">
                    <div class="form-container">
                        <form class="interest-form" id="brnForm" action="{{ locale_route('brn.submit') }}" method="POST">
                            @csrf
                            <!-- First Stage Form -->
                            <div class="first-stage-form-container hidden">
                                <div class="interest-form-group">
                                    <label for="fullName">Full Name</label>
                                    <input type="text" id="fullName" name="name" placeholder="Full name" />
                                </div>

                                <div class="interest-form-group">
                                    <label for="title">Title</label>
                                    <div class="custom-select" id="customSelect" data-select="title">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Title, e.g CEO</span>
                                            <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                alt="" />

                                        </div>
                                        <div class="custom-options">
                                            <span class="custom-option" data-value="CEO">CEO</span>
                                            <span class="custom-option" data-value="CTO">CTO</span>
                                            <span class="custom-option" data-value="CFO">CFO</span>
                                            <span class="custom-option" data-value="CFO">Founder</span>
                                            <span class="custom-option" data-value="CFO">Investor</span>
                                            <span class="custom-option" data-value="Manager">Managing Director</span>
                                            <span class="custom-option" data-value="Director">Director</span>
                                            {{-- <span class="custom-option" data-value="Other">Other</span> --}}
                                        </div>
                                    </div>

                                    <!-- Hidden native select for form submission -->
                                    <select id="title" name="position" style="display: none">
                                        <option value="">Title, e.g CEO</option>
                                        <option value="CEO">CEO</option>
                                        <option value="CTO">CTO</option>
                                        <option value="CFO">CFO</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Director">Director</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="interest-form-group">
                                    <label for="businessName">Official Business Name</label>
                                    <input type="text" id="businessName" name="company_name"
                                        placeholder="Business Name" />
                                </div>

                                <div class="interest-form-group">
                                    <label for="emailAddress">Email Address</label>
                                    <input type="email" id="emailAddress" name="email"
                                        placeholder="Email Address" />
                                    <!-- Email validation status -->
                                    <div id="email-status" style="display: none; margin-top: 8px; font-size: 0.875rem;">
                                        <span id="email-checkmark" style="color: #10b981; display: none;">✓ Email
                                            available</span>
                                        <span id="email-spinner" style="color: #ffff; display: none;">⏳
                                            Validating...</span>
                                    </div>
                                </div>

                                {{-- Phone Number --}}
                                <div class="interest-form-group">
                                    <label for="phoneNumber">Phone Number</label>
                                    <div class="phone-input-container">

                                        <!-- Country Selector -->
                                        <div class="country-select" id="countrySelect">

                                            <!-- Selected Country Display -->
                                            <div class="selected-country" id="selectedCountry">
                                                <div class="flag-icon placeholder-flag" id="selectedFlag">🌍</div>
                                                <span class="arrow" id="countryArrow">
                                                    <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                        alt="" />
                                                </span>
                                            </div>

                                            <!-- Dropdown -->
                                            <!-- Dropdown -->
                                            <div class="country-dropdown" id="countryDropdown">
                                                <input type="text" id="countrySearch" autofocus class="country-search"
                                                    placeholder="Search country...">
                                                <ul class="country-list" id="countryList">
                                                    <li data-code="+93" data-flag="af"><img
                                                            src="https://flagcdn.com/w40/af.png"><span>Afghanistan
                                                            (+93)</span></li>
                                                    <li data-code="+355" data-flag="al"><img
                                                            src="https://flagcdn.com/w40/al.png"><span>Albania
                                                            (+355)</span></li>
                                                    <li data-code="+213" data-flag="dz"><img
                                                            src="https://flagcdn.com/w40/dz.png"><span>Algeria
                                                            (+213)</span></li>
                                                    <li data-code="+376" data-flag="ad"><img
                                                            src="https://flagcdn.com/w40/ad.png"><span>Andorra
                                                            (+376)</span></li>
                                                    <li data-code="+244" data-flag="ao"><img
                                                            src="https://flagcdn.com/w40/ao.png"><span>Angola (+244)</span>
                                                    </li>
                                                    <li data-code="+54" data-flag="ar"><img
                                                            src="https://flagcdn.com/w40/ar.png"><span>Argentina
                                                            (+54)</span></li>
                                                    <li data-code="+374" data-flag="am"><img
                                                            src="https://flagcdn.com/w40/am.png"><span>Armenia
                                                            (+374)</span></li>
                                                    <li data-code="+61" data-flag="au"><img
                                                            src="https://flagcdn.com/w40/au.png"><span>Australia
                                                            (+61)</span></li>
                                                    <li data-code="+43" data-flag="at"><img
                                                            src="https://flagcdn.com/w40/at.png"><span>Austria (+43)</span>
                                                    </li>
                                                    <li data-code="+994" data-flag="az"><img
                                                            src="https://flagcdn.com/w40/az.png"><span>Azerbaijan
                                                            (+994)</span></li>
                                                    <li data-code="+973" data-flag="bh"><img
                                                            src="https://flagcdn.com/w40/bh.png"><span>Bahrain
                                                            (+973)</span></li>
                                                    <li data-code="+880" data-flag="bd"><img
                                                            src="https://flagcdn.com/w40/bd.png"><span>Bangladesh
                                                            (+880)</span></li>
                                                    <li data-code="+375" data-flag="by"><img
                                                            src="https://flagcdn.com/w40/by.png"><span>Belarus
                                                            (+375)</span></li>
                                                    <li data-code="+32" data-flag="be"><img
                                                            src="https://flagcdn.com/w40/be.png"><span>Belgium (+32)</span>
                                                    </li>
                                                    <li data-code="+229" data-flag="bj"><img
                                                            src="https://flagcdn.com/w40/bj.png"><span>Benin (+229)</span>
                                                    </li>
                                                    <li data-code="+975" data-flag="bt"><img
                                                            src="https://flagcdn.com/w40/bt.png"><span>Bhutan (+975)</span>
                                                    </li>
                                                    <li data-code="+591" data-flag="bo"><img
                                                            src="https://flagcdn.com/w40/bo.png"><span>Bolivia
                                                            (+591)</span></li>
                                                    <li data-code="+55" data-flag="br"><img
                                                            src="https://flagcdn.com/w40/br.png"><span>Brazil (+55)</span>
                                                    </li>
                                                    <li data-code="+673" data-flag="bn"><img
                                                            src="https://flagcdn.com/w40/bn.png"><span>Brunei (+673)</span>
                                                    </li>
                                                    <li data-code="+359" data-flag="bg"><img
                                                            src="https://flagcdn.com/w40/bg.png"><span>Bulgaria
                                                            (+359)</span></li>
                                                    <li data-code="+226" data-flag="bf"><img
                                                            src="https://flagcdn.com/w40/bf.png"><span>Burkina Faso
                                                            (+226)</span></li>
                                                    <li data-code="+257" data-flag="bi"><img
                                                            src="https://flagcdn.com/w40/bi.png"><span>Burundi
                                                            (+257)</span></li>
                                                    <li data-code="+855" data-flag="kh"><img
                                                            src="https://flagcdn.com/w40/kh.png"><span>Cambodia
                                                            (+855)</span></li>
                                                    <li data-code="+237" data-flag="cm"><img
                                                            src="https://flagcdn.com/w40/cm.png"><span>Cameroon
                                                            (+237)</span></li>
                                                    <li data-code="+1" data-flag="ca"><img
                                                            src="https://flagcdn.com/w40/ca.png"><span>Canada (+1)</span>
                                                    </li>
                                                    <li data-code="+238" data-flag="cv"><img
                                                            src="https://flagcdn.com/w40/cv.png"><span>Cape Verde
                                                            (+238)</span></li>
                                                    <li data-code="+236" data-flag="cf"><img
                                                            src="https://flagcdn.com/w40/cf.png"><span>Central African
                                                            Republic (+236)</span></li>
                                                    <li data-code="+235" data-flag="td"><img
                                                            src="https://flagcdn.com/w40/td.png"><span>Chad (+235)</span>
                                                    </li>
                                                    <li data-code="+56" data-flag="cl"><img
                                                            src="https://flagcdn.com/w40/cl.png"><span>Chile (+56)</span>
                                                    </li>
                                                    <li data-code="+86" data-flag="cn"><img
                                                            src="https://flagcdn.com/w40/cn.png"><span>China (+86)</span>
                                                    </li>
                                                    <li data-code="+57" data-flag="co"><img
                                                            src="https://flagcdn.com/w40/co.png"><span>Colombia
                                                            (+57)</span></li>
                                                    <li data-code="+269" data-flag="km"><img
                                                            src="https://flagcdn.com/w40/km.png"><span>Comoros
                                                            (+269)</span></li>
                                                    <li data-code="+242" data-flag="cg"><img
                                                            src="https://flagcdn.com/w40/cg.png"><span>Congo (+242)</span>
                                                    </li>
                                                    <li data-code="+243" data-flag="cd"><img
                                                            src="https://flagcdn.com/w40/cd.png"><span>DR Congo
                                                            (+243)</span></li>
                                                    <li data-code="+506" data-flag="cr"><img
                                                            src="https://flagcdn.com/w40/cr.png"><span>Costa Rica
                                                            (+506)</span></li>
                                                    <li data-code="+225" data-flag="ci"><img
                                                            src="https://flagcdn.com/w40/ci.png"><span>Ivory Coast
                                                            (+225)</span></li>
                                                    <li data-code="+385" data-flag="hr"><img
                                                            src="https://flagcdn.com/w40/hr.png"><span>Croatia
                                                            (+385)</span></li>
                                                    <li data-code="+53" data-flag="cu"><img
                                                            src="https://flagcdn.com/w40/cu.png"><span>Cuba (+53)</span>
                                                    </li>
                                                    <li data-code="+357" data-flag="cy"><img
                                                            src="https://flagcdn.com/w40/cy.png"><span>Cyprus (+357)</span>
                                                    </li>
                                                    <li data-code="+420" data-flag="cz"><img
                                                            src="https://flagcdn.com/w40/cz.png"><span>Czech Republic
                                                            (+420)</span></li>
                                                    <li data-code="+45" data-flag="dk"><img
                                                            src="https://flagcdn.com/w40/dk.png"><span>Denmark (+45)</span>
                                                    </li>
                                                    <li data-code="+253" data-flag="dj"><img
                                                            src="https://flagcdn.com/w40/dj.png"><span>Djibouti
                                                            (+253)</span></li>
                                                    <li data-code="+593" data-flag="ec"><img
                                                            src="https://flagcdn.com/w40/ec.png"><span>Ecuador
                                                            (+593)</span></li>
                                                    <li data-code="+20" data-flag="eg"><img
                                                            src="https://flagcdn.com/w40/eg.png"><span>Egypt (+20)</span>
                                                    </li>
                                                    <li data-code="+503" data-flag="sv"><img
                                                            src="https://flagcdn.com/w40/sv.png"><span>El Salvador
                                                            (+503)</span></li>
                                                    <li data-code="+240" data-flag="gq"><img
                                                            src="https://flagcdn.com/w40/gq.png"><span>Equatorial Guinea
                                                            (+240)</span></li>
                                                    <li data-code="+291" data-flag="er"><img
                                                            src="https://flagcdn.com/w40/er.png"><span>Eritrea
                                                            (+291)</span></li>
                                                    <li data-code="+372" data-flag="ee"><img
                                                            src="https://flagcdn.com/w40/ee.png"><span>Estonia
                                                            (+372)</span></li>
                                                    <li data-code="+251" data-flag="et"><img
                                                            src="https://flagcdn.com/w40/et.png"><span>Ethiopia
                                                            (+251)</span></li>
                                                    <li data-code="+679" data-flag="fj"><img
                                                            src="https://flagcdn.com/w40/fj.png"><span>Fiji (+679)</span>
                                                    </li>
                                                    <li data-code="+358" data-flag="fi"><img
                                                            src="https://flagcdn.com/w40/fi.png"><span>Finland
                                                            (+358)</span></li>
                                                    <li data-code="+33" data-flag="fr"><img
                                                            src="https://flagcdn.com/w40/fr.png"><span>France (+33)</span>
                                                    </li>
                                                    <li data-code="+241" data-flag="ga"><img
                                                            src="https://flagcdn.com/w40/ga.png"><span>Gabon (+241)</span>
                                                    </li>
                                                    <li data-code="+220" data-flag="gm"><img
                                                            src="https://flagcdn.com/w40/gm.png"><span>Gambia (+220)</span>
                                                    </li>
                                                    <li data-code="+995" data-flag="ge"><img
                                                            src="https://flagcdn.com/w40/ge.png"><span>Georgia
                                                            (+995)</span></li>
                                                    <li data-code="+49" data-flag="de"><img
                                                            src="https://flagcdn.com/w40/de.png"><span>Germany (+49)</span>
                                                    </li>
                                                    <li data-code="+233" data-flag="gh"><img
                                                            src="https://flagcdn.com/w40/gh.png"><span>Ghana (+233)</span>
                                                    </li>
                                                    <li data-code="+30" data-flag="gr"><img
                                                            src="https://flagcdn.com/w40/gr.png"><span>Greece (+30)</span>
                                                    </li>
                                                    <li data-code="+502" data-flag="gt"><img
                                                            src="https://flagcdn.com/w40/gt.png"><span>Guatemala
                                                            (+502)</span></li>
                                                    <li data-code="+224" data-flag="gn"><img
                                                            src="https://flagcdn.com/w40/gn.png"><span>Guinea (+224)</span>
                                                    </li>
                                                    <li data-code="+245" data-flag="gw"><img
                                                            src="https://flagcdn.com/w40/gw.png"><span>Guinea-Bissau
                                                            (+245)</span></li>
                                                    <li data-code="+592" data-flag="gy"><img
                                                            src="https://flagcdn.com/w40/gy.png"><span>Guyana (+592)</span>
                                                    </li>
                                                    <li data-code="+509" data-flag="ht"><img
                                                            src="https://flagcdn.com/w40/ht.png"><span>Haiti (+509)</span>
                                                    </li>
                                                    <li data-code="+504" data-flag="hn"><img
                                                            src="https://flagcdn.com/w40/hn.png"><span>Honduras
                                                            (+504)</span></li>
                                                    <li data-code="+852" data-flag="hk"><img
                                                            src="https://flagcdn.com/w40/hk.png"><span>Hong Kong
                                                            (+852)</span></li>
                                                    <li data-code="+36" data-flag="hu"><img
                                                            src="https://flagcdn.com/w40/hu.png"><span>Hungary (+36)</span>
                                                    </li>
                                                    <li data-code="+354" data-flag="is"><img
                                                            src="https://flagcdn.com/w40/is.png"><span>Iceland
                                                            (+354)</span></li>
                                                    <li data-code="+91" data-flag="in"><img
                                                            src="https://flagcdn.com/w40/in.png"><span>India (+91)</span>
                                                    </li>
                                                    <li data-code="+62" data-flag="id"><img
                                                            src="https://flagcdn.com/w40/id.png"><span>Indonesia
                                                            (+62)</span></li>
                                                    <li data-code="+98" data-flag="ir"><img
                                                            src="https://flagcdn.com/w40/ir.png"><span>Iran (+98)</span>
                                                    </li>
                                                    <li data-code="+964" data-flag="iq"><img
                                                            src="https://flagcdn.com/w40/iq.png"><span>Iraq (+964)</span>
                                                    </li>
                                                    <li data-code="+353" data-flag="ie"><img
                                                            src="https://flagcdn.com/w40/ie.png"><span>Ireland
                                                            (+353)</span></li>
                                                    <li data-code="+972" data-flag="il"><img
                                                            src="https://flagcdn.com/w40/il.png"><span>Israel (+972)</span>
                                                    </li>
                                                    <li data-code="+39" data-flag="it"><img
                                                            src="https://flagcdn.com/w40/it.png"><span>Italy (+39)</span>
                                                    </li>
                                                    <li data-code="+81" data-flag="jp"><img
                                                            src="https://flagcdn.com/w40/jp.png"><span>Japan (+81)</span>
                                                    </li>
                                                    <li data-code="+962" data-flag="jo"><img
                                                            src="https://flagcdn.com/w40/jo.png"><span>Jordan (+962)</span>
                                                    </li>
                                                    <li data-code="+7" data-flag="kz"><img
                                                            src="https://flagcdn.com/w40/kz.png"><span>Kazakhstan
                                                            (+7)</span></li>
                                                    <li data-code="+254" data-flag="ke"><img
                                                            src="https://flagcdn.com/w40/ke.png"><span>Kenya (+254)</span>
                                                    </li>
                                                    <li data-code="+686" data-flag="ki"><img
                                                            src="https://flagcdn.com/w40/ki.png"><span>Kiribati
                                                            (+686)</span></li>
                                                    <li data-code="+850" data-flag="kp"><img
                                                            src="https://flagcdn.com/w40/kp.png"><span>North Korea
                                                            (+850)</span></li>
                                                    <li data-code="+82" data-flag="kr"><img
                                                            src="https://flagcdn.com/w40/kr.png"><span>South Korea
                                                            (+82)</span></li>
                                                    <li data-code="+965" data-flag="kw"><img
                                                            src="https://flagcdn.com/w40/kw.png"><span>Kuwait (+965)</span>
                                                    </li>
                                                    <li data-code="+996" data-flag="kg"><img
                                                            src="https://flagcdn.com/w40/kg.png"><span>Kyrgyzstan
                                                            (+996)</span></li>
                                                    <li data-code="+856" data-flag="la"><img
                                                            src="https://flagcdn.com/w40/la.png"><span>Laos (+856)</span>
                                                    </li>
                                                    <li data-code="+371" data-flag="lv"><img
                                                            src="https://flagcdn.com/w40/lv.png"><span>Latvia (+371)</span>
                                                    </li>
                                                    <li data-code="+961" data-flag="lb"><img
                                                            src="https://flagcdn.com/w40/lb.png"><span>Lebanon
                                                            (+961)</span></li>
                                                    <li data-code="+266" data-flag="ls"><img
                                                            src="https://flagcdn.com/w40/ls.png"><span>Lesotho
                                                            (+266)</span></li>
                                                    <li data-code="+231" data-flag="lr"><img
                                                            src="https://flagcdn.com/w40/lr.png"><span>Liberia
                                                            (+231)</span></li>
                                                    <li data-code="+218" data-flag="ly"><img
                                                            src="https://flagcdn.com/w40/ly.png"><span>Libya (+218)</span>
                                                    </li>
                                                    <li data-code="+423" data-flag="li"><img
                                                            src="https://flagcdn.com/w40/li.png"><span>Liechtenstein
                                                            (+423)</span></li>
                                                    <li data-code="+370" data-flag="lt"><img
                                                            src="https://flagcdn.com/w40/lt.png"><span>Lithuania
                                                            (+370)</span></li>
                                                    <li data-code="+352" data-flag="lu"><img
                                                            src="https://flagcdn.com/w40/lu.png"><span>Luxembourg
                                                            (+352)</span></li>
                                                    <li data-code="+853" data-flag="mo"><img
                                                            src="https://flagcdn.com/w40/mo.png"><span>Macau (+853)</span>
                                                    </li>
                                                    <li data-code="+389" data-flag="mk"><img
                                                            src="https://flagcdn.com/w40/mk.png"><span>North Macedonia
                                                            (+389)</span></li>
                                                    <li data-code="+261" data-flag="mg"><img
                                                            src="https://flagcdn.com/w40/mg.png"><span>Madagascar
                                                            (+261)</span></li>
                                                    <li data-code="+265" data-flag="mw"><img
                                                            src="https://flagcdn.com/w40/mw.png"><span>Malawi (+265)</span>
                                                    </li>
                                                    <li data-code="+60" data-flag="my"><img
                                                            src="https://flagcdn.com/w40/my.png"><span>Malaysia
                                                            (+60)</span></li>
                                                    <li data-code="+960" data-flag="mv"><img
                                                            src="https://flagcdn.com/w40/mv.png"><span>Maldives
                                                            (+960)</span></li>
                                                    <li data-code="+223" data-flag="ml"><img
                                                            src="https://flagcdn.com/w40/ml.png"><span>Mali (+223)</span>
                                                    </li>
                                                    <li data-code="+356" data-flag="mt"><img
                                                            src="https://flagcdn.com/w40/mt.png"><span>Malta (+356)</span>
                                                    </li>
                                                    <li data-code="+692" data-flag="mh"><img
                                                            src="https://flagcdn.com/w40/mh.png"><span>Marshall Islands
                                                            (+692)</span></li>
                                                    <li data-code="+222" data-flag="mr"><img
                                                            src="https://flagcdn.com/w40/mr.png"><span>Mauritania
                                                            (+222)</span></li>
                                                    <li data-code="+230" data-flag="mu"><img
                                                            src="https://flagcdn.com/w40/mu.png"><span>Mauritius
                                                            (+230)</span></li>
                                                    <li data-code="+52" data-flag="mx"><img
                                                            src="https://flagcdn.com/w40/mx.png"><span>Mexico (+52)</span>
                                                    </li>
                                                    <li data-code="+691" data-flag="fm"><img
                                                            src="https://flagcdn.com/w40/fm.png"><span>Micronesia
                                                            (+691)</span></li>
                                                    <li data-code="+373" data-flag="md"><img
                                                            src="https://flagcdn.com/w40/md.png"><span>Moldova
                                                            (+373)</span></li>
                                                    <li data-code="+377" data-flag="mc"><img
                                                            src="https://flagcdn.com/w40/mc.png"><span>Monaco (+377)</span>
                                                    </li>
                                                    <li data-code="+976" data-flag="mn"><img
                                                            src="https://flagcdn.com/w40/mn.png"><span>Mongolia
                                                            (+976)</span></li>
                                                    <li data-code="+382" data-flag="me"><img
                                                            src="https://flagcdn.com/w40/me.png"><span>Montenegro
                                                            (+382)</span></li>
                                                    <li data-code="+212" data-flag="ma"><img
                                                            src="https://flagcdn.com/w40/ma.png"><span>Morocco
                                                            (+212)</span></li>
                                                    <li data-code="+258" data-flag="mz"><img
                                                            src="https://flagcdn.com/w40/mz.png"><span>Mozambique
                                                            (+258)</span></li>
                                                    <li data-code="+95" data-flag="mm"><img
                                                            src="https://flagcdn.com/w40/mm.png"><span>Myanmar (+95)</span>
                                                    </li>
                                                    <li data-code="+264" data-flag="na"><img
                                                            src="https://flagcdn.com/w40/na.png"><span>Namibia
                                                            (+264)</span></li>
                                                    <li data-code="+674" data-flag="nr"><img
                                                            src="https://flagcdn.com/w40/nr.png"><span>Nauru (+674)</span>
                                                    </li>
                                                    <li data-code="+977" data-flag="np"><img
                                                            src="https://flagcdn.com/w40/np.png"><span>Nepal (+977)</span>
                                                    </li>
                                                    <li data-code="+31" data-flag="nl"><img
                                                            src="https://flagcdn.com/w40/nl.png"><span>Netherlands
                                                            (+31)</span></li>
                                                    <li data-code="+64" data-flag="nz"><img
                                                            src="https://flagcdn.com/w40/nz.png"><span>New Zealand
                                                            (+64)</span></li>
                                                    <li data-code="+505" data-flag="ni"><img
                                                            src="https://flagcdn.com/w40/ni.png"><span>Nicaragua
                                                            (+505)</span></li>
                                                    <li data-code="+227" data-flag="ne"><img
                                                            src="https://flagcdn.com/w40/ne.png"><span>Niger (+227)</span>
                                                    </li>
                                                    <li data-code="+234" data-flag="ng"><img
                                                            src="https://flagcdn.com/w40/ng.png"><span>Nigeria
                                                            (+234)</span></li>
                                                    <li data-code="+683" data-flag="nu"><img
                                                            src="https://flagcdn.com/w40/nu.png"><span>Niue (+683)</span>
                                                    </li>
                                                    <li data-code="+672" data-flag="nf"><img
                                                            src="https://flagcdn.com/w40/nf.png"><span>Norfolk Island
                                                            (+672)</span></li>
                                                    <li data-code="+47" data-flag="no"><img
                                                            src="https://flagcdn.com/w40/no.png"><span>Norway (+47)</span>
                                                    </li>
                                                    <li data-code="+968" data-flag="om"><img
                                                            src="https://flagcdn.com/w40/om.png"><span>Oman (+968)</span>
                                                    </li>
                                                    <li data-code="+92" data-flag="pk"><img
                                                            src="https://flagcdn.com/w40/pk.png"><span>Pakistan
                                                            (+92)</span></li>
                                                    <li data-code="+680" data-flag="pw"><img
                                                            src="https://flagcdn.com/w40/pw.png"><span>Palau (+680)</span>
                                                    </li>
                                                    <li data-code="+507" data-flag="pa"><img
                                                            src="https://flagcdn.com/w40/pa.png"><span>Panama (+507)</span>
                                                    </li>
                                                    <li data-code="+675" data-flag="pg"><img
                                                            src="https://flagcdn.com/w40/pg.png"><span>Papua New Guinea
                                                            (+675)</span></li>
                                                    <li data-code="+595" data-flag="py"><img
                                                            src="https://flagcdn.com/w40/py.png"><span>Paraguay
                                                            (+595)</span></li>
                                                    <li data-code="+51" data-flag="pe"><img
                                                            src="https://flagcdn.com/w40/pe.png"><span>Peru (+51)</span>
                                                    </li>
                                                    <li data-code="+63" data-flag="ph"><img
                                                            src="https://flagcdn.com/w40/ph.png"><span>Philippines
                                                            (+63)</span></li>
                                                    <li data-code="+48" data-flag="pl"><img
                                                            src="https://flagcdn.com/w40/pl.png"><span>Poland (+48)</span>
                                                    </li>
                                                    <li data-code="+351" data-flag="pt"><img
                                                            src="https://flagcdn.com/w40/pt.png"><span>Portugal
                                                            (+351)</span></li>
                                                    <li data-code="+974" data-flag="qa"><img
                                                            src="https://flagcdn.com/w40/qa.png"><span>Qatar (+974)</span>
                                                    </li>
                                                    <li data-code="+40" data-flag="ro"><img
                                                            src="https://flagcdn.com/w40/ro.png"><span>Romania (+40)</span>
                                                    </li>
                                                    <li data-code="+7" data-flag="ru"><img
                                                            src="https://flagcdn.com/w40/ru.png"><span>Russia (+7)</span>
                                                    </li>
                                                    <li data-code="+250" data-flag="rw"><img
                                                            src="https://flagcdn.com/w40/rw.png"><span>Rwanda (+250)</span>
                                                    </li>
                                                    <li data-code="+685" data-flag="ws"><img
                                                            src="https://flagcdn.com/w40/ws.png"><span>Samoa (+685)</span>
                                                    </li>
                                                    <li data-code="+378" data-flag="sm"><img
                                                            src="https://flagcdn.com/w40/sm.png"><span>San Marino
                                                            (+378)</span></li>
                                                    <li data-code="+239" data-flag="st"><img
                                                            src="https://flagcdn.com/w40/st.png"><span>São Tomé and
                                                            Príncipe (+239)</span></li>
                                                    <li data-code="+966" data-flag="sa"><img
                                                            src="https://flagcdn.com/w40/sa.png"><span>Saudi Arabia
                                                            (+966)</span></li>
                                                    <li data-code="+221" data-flag="sn"><img
                                                            src="https://flagcdn.com/w40/sn.png"><span>Senegal
                                                            (+221)</span></li>
                                                    <li data-code="+381" data-flag="rs"><img
                                                            src="https://flagcdn.com/w40/rs.png"><span>Serbia (+381)</span>
                                                    </li>
                                                    <li data-code="+248" data-flag="sc"><img
                                                            src="https://flagcdn.com/w40/sc.png"><span>Seychelles
                                                            (+248)</span></li>
                                                    <li data-code="+232" data-flag="sl"><img
                                                            src="https://flagcdn.com/w40/sl.png"><span>Sierra Leone
                                                            (+232)</span></li>
                                                    <li data-code="+65" data-flag="sg"><img
                                                            src="https://flagcdn.com/w40/sg.png"><span>Singapore
                                                            (+65)</span></li>
                                                    <li data-code="+421" data-flag="sk"><img
                                                            src="https://flagcdn.com/w40/sk.png"><span>Slovakia
                                                            (+421)</span></li>
                                                    <li data-code="+386" data-flag="si"><img
                                                            src="https://flagcdn.com/w40/si.png"><span>Slovenia
                                                            (+386)</span></li>
                                                    <li data-code="+677" data-flag="sb"><img
                                                            src="https://flagcdn.com/w40/sb.png"><span>Solomon Islands
                                                            (+677)</span></li>
                                                    <li data-code="+252" data-flag="so"><img
                                                            src="https://flagcdn.com/w40/so.png"><span>Somalia
                                                            (+252)</span></li>
                                                    <li data-code="+27" data-flag="za"><img
                                                            src="https://flagcdn.com/w40/za.png"><span>South Africa
                                                            (+27)</span></li>
                                                    <li data-code="+211" data-flag="ss"><img
                                                            src="https://flagcdn.com/w40/ss.png"><span>South Sudan
                                                            (+211)</span></li>
                                                    <li data-code="+34" data-flag="es"><img
                                                            src="https://flagcdn.com/w40/es.png"><span>Spain (+34)</span>
                                                    </li>
                                                    <li data-code="+94" data-flag="lk"><img
                                                            src="https://flagcdn.com/w40/lk.png"><span>Sri Lanka
                                                            (+94)</span></li>
                                                    <li data-code="+249" data-flag="sd"><img
                                                            src="https://flagcdn.com/w40/sd.png"><span>Sudan (+249)</span>
                                                    </li>
                                                    <li data-code="+597" data-flag="sr"><img
                                                            src="https://flagcdn.com/w40/sr.png"><span>Suriname
                                                            (+597)</span></li>
                                                    <li data-code="+268" data-flag="sz"><img
                                                            src="https://flagcdn.com/w40/sz.png"><span>Eswatini
                                                            (+268)</span></li>
                                                    <li data-code="+46" data-flag="se"><img
                                                            src="https://flagcdn.com/w40/se.png"><span>Sweden (+46)</span>
                                                    </li>
                                                    <li data-code="+41" data-flag="ch"><img
                                                            src="https://flagcdn.com/w40/ch.png"><span>Switzerland
                                                            (+41)</span></li>
                                                    <li data-code="+963" data-flag="sy"><img
                                                            src="https://flagcdn.com/w40/sy.png"><span>Syria (+963)</span>
                                                    </li>
                                                    <li data-code="+886" data-flag="tw"><img
                                                            src="https://flagcdn.com/w40/tw.png"><span>Taiwan (+886)</span>
                                                    </li>
                                                    <li data-code="+992" data-flag="tj"><img
                                                            src="https://flagcdn.com/w40/tj.png"><span>Tajikistan
                                                            (+992)</span></li>
                                                    <li data-code="+255" data-flag="tz"><img
                                                            src="https://flagcdn.com/w40/tz.png"><span>Tanzania
                                                            (+255)</span></li>
                                                    <li data-code="+66" data-flag="th"><img
                                                            src="https://flagcdn.com/w40/th.png"><span>Thailand
                                                            (+66)</span></li>
                                                    <li data-code="+670" data-flag="tl"><img
                                                            src="https://flagcdn.com/w40/tl.png"><span>Timor-Leste
                                                            (+670)</span></li>
                                                    <li data-code="+228" data-flag="tg"><img
                                                            src="https://flagcdn.com/w40/tg.png"><span>Togo (+228)</span>
                                                    </li>
                                                    <li data-code="+676" data-flag="to"><img
                                                            src="https://flagcdn.com/w40/to.png"><span>Tonga (+676)</span>
                                                    </li>
                                                    <li data-code="+216" data-flag="tn"><img
                                                            src="https://flagcdn.com/w40/tn.png"><span>Tunisia
                                                            (+216)</span></li>
                                                    <li data-code="+90" data-flag="tr"><img
                                                            src="https://flagcdn.com/w40/tr.png"><span>Turkey (+90)</span>
                                                    </li>
                                                    <li data-code="+993" data-flag="tm"><img
                                                            src="https://flagcdn.com/w40/tm.png"><span>Turkmenistan
                                                            (+993)</span></li>
                                                    <li data-code="+688" data-flag="tv"><img
                                                            src="https://flagcdn.com/w40/tv.png"><span>Tuvalu (+688)</span>
                                                    </li>
                                                    <li data-code="+256" data-flag="ug"><img
                                                            src="https://flagcdn.com/w40/ug.png"><span>Uganda (+256)</span>
                                                    </li>
                                                    <li data-code="+380" data-flag="ua"><img
                                                            src="https://flagcdn.com/w40/ua.png"><span>Ukraine
                                                            (+380)</span></li>
                                                    <li data-code="+971" data-flag="ae"><img
                                                            src="https://flagcdn.com/w40/ae.png"><span>United Arab Emirates
                                                            (+971)</span></li>
                                                    <li data-code="+44" data-flag="gb"><img
                                                            src="https://flagcdn.com/w40/gb.png"><span>United Kingdom
                                                            (+44)</span></li>
                                                    <li data-code="+1" data-flag="us"><img
                                                            src="https://flagcdn.com/w40/us.png"><span>United States
                                                            (+1)</span></li>
                                                    <li data-code="+598" data-flag="uy"><img
                                                            src="https://flagcdn.com/w40/uy.png"><span>Uruguay
                                                            (+598)</span></li>
                                                    <li data-code="+998" data-flag="uz"><img
                                                            src="https://flagcdn.com/w40/uz.png"><span>Uzbekistan
                                                            (+998)</span></li>
                                                    <li data-code="+678" data-flag="vu"><img
                                                            src="https://flagcdn.com/w40/vu.png"><span>Vanuatu
                                                            (+678)</span></li>
                                                    <li data-code="+39" data-flag="va"><img
                                                            src="https://flagcdn.com/w40/va.png"><span>Vatican City
                                                            (+39)</span></li>
                                                    <li data-code="+58" data-flag="ve"><img
                                                            src="https://flagcdn.com/w40/ve.png"><span>Venezuela
                                                            (+58)</span></li>
                                                    <li data-code="+84" data-flag="vn"><img
                                                            src="https://flagcdn.com/w40/vn.png"><span>Vietnam (+84)</span>
                                                    </li>
                                                    <li data-code="+967" data-flag="ye"><img
                                                            src="https://flagcdn.com/w40/ye.png"><span>Yemen (+967)</span>
                                                    </li>
                                                    <li data-code="+260" data-flag="zm"><img
                                                            src="https://flagcdn.com/w40/zm.png"><span>Zambia (+260)</span>
                                                    </li>
                                                    <li data-code="+263" data-flag="zw"><img
                                                            src="https://flagcdn.com/w40/zw.png"><span>Zimbabwe
                                                            (+263)</span></li>
                                                </ul>


                                            </div>
                                        </div>

                                        <!-- Hidden actual country code -->
                                        <input type="hidden" name="country_code" id="countryCode">
                                        <div class="input-with-prefix">

                                            <!-- Phone Prefix (new UI block) -->
                                            <div class="phone-prefix" id="phonePrefix">--</div>
                                            <!-- Phone Input -->
                                            <input type="tel" id="phoneNumber" name="phone"
                                                placeholder="Select country code first" disabled>
                                        </div>

                                    </div>


                                </div>



                                <div class="interest-form-actions">
                                    <button type="button" class="interest-form-proceed-button" data-next="form2">
                                        Proceed
                                    </button>
                                </div>
                            </div>

                            <!-- Second Form -->
                            <div class="second-stage-form-container hidden">
                                <div class="interest-form-group">
                                    <label for="regNumber">Company Registration Number</label>
                                    <input type="text" id="regNumber" name="reg_number"
                                        placeholder="Input you company Reg. Number" />
                                </div>

                                <div class="interest-form-group">
                                    <label for="countryReg">Country Of Registration</label>
                                    <div class="custom-select" id="countryRegSelect" data-select="country">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Select your country</span>
                                            <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                alt="" />
                                        </div>
                                        <div class="custom-options countryReg-options" id="countryReg-options">
                                            <input type="text" autofocus class="country-search" id="countrySearchReg"
                                                placeholder="Search countries..." autocomplete="off">
                                            <div class="custom-options-scrollable">
                                                <span class="custom-option" data-value="Afghanistan">Afghanistan</span>
                                                <span class="custom-option" data-value="Albania">Albania</span>
                                                <span class="custom-option" data-value="Algeria">Algeria</span>
                                                <span class="custom-option" data-value="Andorra">Andorra</span>
                                                <span class="custom-option" data-value="Angola">Angola</span>
                                                <span class="custom-option" data-value="Argentina">Argentina</span>
                                                <span class="custom-option" data-value="Armenia">Armenia</span>
                                                <span class="custom-option" data-value="Australia">Australia</span>
                                                <span class="custom-option" data-value="Austria">Austria</span>
                                                <span class="custom-option" data-value="Azerbaijan">Azerbaijan</span>
                                                <span class="custom-option" data-value="Bahamas">Bahamas</span>
                                                <span class="custom-option" data-value="Bahrain">Bahrain</span>
                                                <span class="custom-option" data-value="Bangladesh">Bangladesh</span>
                                                <span class="custom-option" data-value="Barbados">Barbados</span>
                                                <span class="custom-option" data-value="Belarus">Belarus</span>
                                                <span class="custom-option" data-value="Belgium">Belgium</span>
                                                <span class="custom-option" data-value="Belize">Belize</span>
                                                <span class="custom-option" data-value="Benin">Benin</span>
                                                <span class="custom-option" data-value="Bhutan">Bhutan</span>
                                                <span class="custom-option" data-value="Bolivia">Bolivia</span>
                                                <span class="custom-option" data-value="Bosnia and Herzegovina">Bosnia and
                                                    Herzegovina</span>
                                                <span class="custom-option" data-value="Botswana">Botswana</span>
                                                <span class="custom-option" data-value="Brazil">Brazil</span>
                                                <span class="custom-option" data-value="Brunei">Brunei</span>
                                                <span class="custom-option" data-value="Bulgaria">Bulgaria</span>
                                                <span class="custom-option" data-value="Burkina Faso">Burkina Faso</span>
                                                <span class="custom-option" data-value="Burundi">Burundi</span>
                                                <span class="custom-option" data-value="Cambodia">Cambodia</span>
                                                <span class="custom-option" data-value="Cameroon">Cameroon</span>
                                                <span class="custom-option" data-value="Canada">Canada</span>
                                                <span class="custom-option" data-value="Cape Verde">Cape Verde</span>
                                                <span class="custom-option" data-value="Central African Republic">Central
                                                    African Republic</span>
                                                <span class="custom-option" data-value="Chad">Chad</span>
                                                <span class="custom-option" data-value="Chile">Chile</span>
                                                <span class="custom-option" data-value="China">China</span>
                                                <span class="custom-option" data-value="Colombia">Colombia</span>
                                                <span class="custom-option" data-value="Comoros">Comoros</span>
                                                <span class="custom-option" data-value="Congo">Congo</span>
                                                <span class="custom-option" data-value="Costa Rica">Costa Rica</span>
                                                <span class="custom-option" data-value="Croatia">Croatia</span>
                                                <span class="custom-option" data-value="Cuba">Cuba</span>
                                                <span class="custom-option" data-value="Cyprus">Cyprus</span>
                                                <span class="custom-option" data-value="Czech Republic">Czech
                                                    Republic</span>
                                                <span class="custom-option" data-value="Denmark">Denmark</span>
                                                <span class="custom-option" data-value="Djibouti">Djibouti</span>
                                                <span class="custom-option" data-value="Dominica">Dominica</span>
                                                <span class="custom-option" data-value="Dominican Republic">Dominican
                                                    Republic</span>
                                                <span class="custom-option" data-value="East Timor">East Timor</span>
                                                <span class="custom-option" data-value="Ecuador">Ecuador</span>
                                                <span class="custom-option" data-value="Egypt">Egypt</span>
                                                <span class="custom-option" data-value="El Salvador">El Salvador</span>
                                                <span class="custom-option" data-value="Equatorial Guinea">Equatorial
                                                    Guinea</span>
                                                <span class="custom-option" data-value="Eritrea">Eritrea</span>
                                                <span class="custom-option" data-value="Estonia">Estonia</span>
                                                <span class="custom-option" data-value="Ethiopia">Ethiopia</span>
                                                <span class="custom-option" data-value="Fiji">Fiji</span>
                                                <span class="custom-option" data-value="Finland">Finland</span>
                                                <span class="custom-option" data-value="France">France</span>
                                                <span class="custom-option" data-value="Gabon">Gabon</span>
                                                <span class="custom-option" data-value="Gambia">Gambia</span>
                                                <span class="custom-option" data-value="Georgia">Georgia</span>
                                                <span class="custom-option" data-value="Germany">Germany</span>
                                                <span class="custom-option" data-value="Ghana">Ghana</span>
                                                <span class="custom-option" data-value="Greece">Greece</span>
                                                <span class="custom-option" data-value="Grenada">Grenada</span>
                                                <span class="custom-option" data-value="Guatemala">Guatemala</span>
                                                <span class="custom-option" data-value="Guinea">Guinea</span>
                                                <span class="custom-option"
                                                    data-value="Guinea-Bissau">Guinea-Bissau</span>
                                                <span class="custom-option" data-value="Guyana">Guyana</span>
                                                <span class="custom-option" data-value="Haiti">Haiti</span>
                                                <span class="custom-option" data-value="Honduras">Honduras</span>
                                                <span class="custom-option" data-value="Hungary">Hungary</span>
                                                <span class="custom-option" data-value="Iceland">Iceland</span>
                                                <span class="custom-option" data-value="India">India</span>
                                                <span class="custom-option" data-value="Indonesia">Indonesia</span>
                                                <span class="custom-option" data-value="Iran">Iran</span>
                                                <span class="custom-option" data-value="Iraq">Iraq</span>
                                                <span class="custom-option" data-value="Ireland">Ireland</span>
                                                <span class="custom-option" data-value="Israel">Israel</span>
                                                <span class="custom-option" data-value="Italy">Italy</span>
                                                <span class="custom-option" data-value="Ivory Coast">Ivory Coast</span>
                                                <span class="custom-option" data-value="Jamaica">Jamaica</span>
                                                <span class="custom-option" data-value="Japan">Japan</span>
                                                <span class="custom-option" data-value="Jordan">Jordan</span>
                                                <span class="custom-option" data-value="Kazakhstan">Kazakhstan</span>
                                                <span class="custom-option" data-value="Kenya">Kenya</span>
                                                <span class="custom-option" data-value="Kiribati">Kiribati</span>
                                                <span class="custom-option" data-value="North Korea">North Korea</span>
                                                <span class="custom-option" data-value="South Korea">South Korea</span>
                                                <span class="custom-option" data-value="Kuwait">Kuwait</span>
                                                <span class="custom-option" data-value="Kyrgyzstan">Kyrgyzstan</span>
                                                <span class="custom-option" data-value="Laos">Laos</span>
                                                <span class="custom-option" data-value="Latvia">Latvia</span>
                                                <span class="custom-option" data-value="Lebanon">Lebanon</span>
                                                <span class="custom-option" data-value="Lesotho">Lesotho</span>
                                                <span class="custom-option" data-value="Liberia">Liberia</span>
                                                <span class="custom-option" data-value="Libya">Libya</span>
                                                <span class="custom-option"
                                                    data-value="Liechtenstein">Liechtenstein</span>
                                                <span class="custom-option" data-value="Lithuania">Lithuania</span>
                                                <span class="custom-option" data-value="Luxembourg">Luxembourg</span>
                                                <span class="custom-option" data-value="Macedonia">Macedonia</span>
                                                <span class="custom-option" data-value="Madagascar">Madagascar</span>
                                                <span class="custom-option" data-value="Malawi">Malawi</span>
                                                <span class="custom-option" data-value="Malaysia">Malaysia</span>
                                                <span class="custom-option" data-value="Maldives">Maldives</span>
                                                <span class="custom-option" data-value="Mali">Mali</span>
                                                <span class="custom-option" data-value="Malta">Malta</span>
                                                <span class="custom-option" data-value="Marshall Islands">Marshall
                                                    Islands</span>
                                                <span class="custom-option" data-value="Mauritania">Mauritania</span>
                                                <span class="custom-option" data-value="Mauritius">Mauritius</span>
                                                <span class="custom-option" data-value="Mexico">Mexico</span>
                                                <span class="custom-option" data-value="Micronesia">Micronesia</span>
                                                <span class="custom-option" data-value="Moldova">Moldova</span>
                                                <span class="custom-option" data-value="Monaco">Monaco</span>
                                                <span class="custom-option" data-value="Mongolia">Mongolia</span>
                                                <span class="custom-option" data-value="Montenegro">Montenegro</span>
                                                <span class="custom-option" data-value="Morocco">Morocco</span>
                                                <span class="custom-option" data-value="Mozambique">Mozambique</span>
                                                <span class="custom-option" data-value="Myanmar">Myanmar</span>
                                                <span class="custom-option" data-value="Namibia">Namibia</span>
                                                <span class="custom-option" data-value="Nauru">Nauru</span>
                                                <span class="custom-option" data-value="Nepal">Nepal</span>
                                                <span class="custom-option" data-value="Netherlands">Netherlands</span>
                                                <span class="custom-option" data-value="New Zealand">New Zealand</span>
                                                <span class="custom-option" data-value="Nicaragua">Nicaragua</span>
                                                <span class="custom-option" data-value="Niger">Niger</span>
                                                <span class="custom-option" data-value="Nigeria">Nigeria</span>
                                                <span class="custom-option" data-value="Norway">Norway</span>
                                                <span class="custom-option" data-value="Oman">Oman</span>
                                                <span class="custom-option" data-value="Pakistan">Pakistan</span>
                                                <span class="custom-option" data-value="Palau">Palau</span>
                                                <span class="custom-option" data-value="Panama">Panama</span>
                                                <span class="custom-option" data-value="Papua New Guinea">Papua New
                                                    Guinea</span>
                                                <span class="custom-option" data-value="Paraguay">Paraguay</span>
                                                <span class="custom-option" data-value="Peru">Peru</span>
                                                <span class="custom-option" data-value="Philippines">Philippines</span>
                                                <span class="custom-option" data-value="Poland">Poland</span>
                                                <span class="custom-option" data-value="Portugal">Portugal</span>
                                                <span class="custom-option" data-value="Qatar">Qatar</span>
                                                <span class="custom-option" data-value="Romania">Romania</span>
                                                <span class="custom-option" data-value="Russia">Russia</span>
                                                <span class="custom-option" data-value="Rwanda">Rwanda</span>
                                                <span class="custom-option" data-value="Saint Kitts and Nevis">Saint
                                                    Kitts
                                                    and
                                                    Nevis</span>
                                                <span class="custom-option" data-value="Saint Lucia">Saint Lucia</span>
                                                <span class="custom-option"
                                                    data-value="Saint Vincent and the Grenadines">Saint
                                                    Vincent and the
                                                    Grenadines</span>
                                                <span class="custom-option" data-value="Samoa">Samoa</span>
                                                <span class="custom-option" data-value="San Marino">San Marino</span>
                                                <span class="custom-option" data-value="Sao Tome and Principe">Sao Tome
                                                    and
                                                    Principe</span>
                                                <span class="custom-option" data-value="Saudi Arabia">Saudi
                                                    Arabia</span>
                                                <span class="custom-option" data-value="Senegal">Senegal</span>
                                                <span class="custom-option" data-value="Serbia">Serbia</span>
                                                <span class="custom-option" data-value="Seychelles">Seychelles</span>
                                                <span class="custom-option" data-value="Sierra Leone">Sierra
                                                    Leone</span>
                                                <span class="custom-option" data-value="Singapore">Singapore</span>
                                                <span class="custom-option" data-value="Slovakia">Slovakia</span>
                                                <span class="custom-option" data-value="Slovenia">Slovenia</span>
                                                <span class="custom-option" data-value="Solomon Islands">Solomon
                                                    Islands</span>
                                                <span class="custom-option" data-value="Somalia">Somalia</span>
                                                <span class="custom-option" data-value="South Africa">South
                                                    Africa</span>
                                                <span class="custom-option" data-value="South Sudan">South Sudan</span>
                                                <span class="custom-option" data-value="Spain">Spain</span>
                                                <span class="custom-option" data-value="Sri Lanka">Sri Lanka</span>
                                                <span class="custom-option" data-value="Sudan">Sudan</span>
                                                <span class="custom-option" data-value="Suriname">Suriname</span>
                                                <span class="custom-option" data-value="Swaziland">Swaziland</span>
                                                <span class="custom-option" data-value="Sweden">Sweden</span>
                                                <span class="custom-option" data-value="Switzerland">Switzerland</span>
                                                <span class="custom-option" data-value="Syria">Syria</span>
                                                <span class="custom-option" data-value="Taiwan">Taiwan</span>
                                                <span class="custom-option" data-value="Tajikistan">Tajikistan</span>
                                                <span class="custom-option" data-value="Tanzania">Tanzania</span>
                                                <span class="custom-option" data-value="Thailand">Thailand</span>
                                                <span class="custom-option" data-value="Togo">Togo</span>
                                                <span class="custom-option" data-value="Tonga">Tonga</span>
                                                <span class="custom-option" data-value="Trinidad and Tobago">Trinidad
                                                    and
                                                    Tobago</span>
                                                <span class="custom-option" data-value="Tunisia">Tunisia</span>
                                                <span class="custom-option" data-value="Turkey">Turkey</span>
                                                <span class="custom-option"
                                                    data-value="Turkmenistan">Turkmenistan</span>
                                                <span class="custom-option" data-value="Tuvalu">Tuvalu</span>
                                                <span class="custom-option" data-value="Uganda">Uganda</span>
                                                <span class="custom-option" data-value="Ukraine">Ukraine</span>
                                                <span class="custom-option" data-value="United Arab Emirates">United
                                                    Arab
                                                    Emirates</span>
                                                <span class="custom-option" data-value="United Kingdom">United
                                                    Kingdom</span>
                                                <span class="custom-option" data-value="United States">United
                                                    States</span>
                                                <span class="custom-option" data-value="Uruguay">Uruguay</span>
                                                <span class="custom-option" data-value="Uzbekistan">Uzbekistan</span>
                                                <span class="custom-option" data-value="Vanuatu">Vanuatu</span>
                                                <span class="custom-option" data-value="Vatican City">Vatican
                                                    City</span>
                                                <span class="custom-option" data-value="Venezuela">Venezuela</span>
                                                <span class="custom-option" data-value="Vietnam">Vietnam</span>
                                                <span class="custom-option" data-value="Yemen">Yemen</span>
                                                <span class="custom-option" data-value="Zambia">Zambia</span>
                                                <span class="custom-option" data-value="Zimbabwe">Zimbabwe</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden native select for form submission -->
                                    <select id="country" name="country" style="display: none">
                                        <option value="">Select your country</option>
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
                                        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines
                                        </option>
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


                                <div class="interest-form-group">
                                    <label for="website">Company Website or Social Handle</label>
                                    <input type="text" id="website" name="website"
                                        placeholder="Input your company website/socials" />
                                </div>

                                <div class="interest-form-group">
                                    <label for="linkedin">LinkedIn Profile</label>
                                    <input type="url" id="linkedin" name="linkedin"
                                        placeholder="Add your profile Url (Optional)" />

                                    <div class="checkbox-wrapper">
                                        <label class="checkbox-container" for="no_linkedin">
                                            <input type="checkbox" id="no_linkedin" name="no_linkedin" />
                                            <span class="checkmark"></span>
                                            I don’t have a LinkedIn account.
                                        </label>
                                    </div>
                                </div>

                                <div class="interest-form-group">
                                    <label for="yearEst">Year of Establishment</label>
                                    <input type="number" id="yearEst" name="year_established"
                                        placeholder="Input year of establishment" pattern="[0-9]{4}" min="1900"
                                        max="2100" />
                                </div>

                                <div class="interest-form-actions">
                                    <button type="button" class="interest-form-return-button" data-prev="form1">
                                        Return
                                    </button>
                                    <button type="button" class="interest-form-proceed-button" data-next="form3">
                                        Proceed
                                    </button>
                                </div>
                            </div>

                            <!-- third Form -->
                            <div class="third-stage-form-container hidden">
                                <!-- Industries -->
                                <div class="interest-form-group" id="industry-selects-group">
                                    <label for="industry">What industry does your business operate in?</label>
                                    <div class="custom-select" data-select="industry">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Select your industry</span>
                                            <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                alt="" />
                                        </div>
                                        <div class="custom-options">
                                            <span class="custom-option" data-value="Technology & Digital Services">
                                                Technology & Digital Services
                                            </span>

                                            <span class="custom-option" data-value="Financial & Professional Services">
                                                Financial & Professional Services
                                            </span>

                                            <span class="custom-option" data-value="Manufacturing & Industrial">
                                                Manufacturing & Industrial
                                            </span>

                                            <span class="custom-option" data-value="Healthcare & Pharmaceuticals">
                                                Healthcare & Pharmaceuticals
                                            </span>

                                            <span class="custom-option" data-value="Energy & Utilities">
                                                Energy & Utilities
                                            </span>

                                            <span class="custom-option" data-value="Retail & Consumer Goods">
                                                Retail & Consumer Goods
                                            </span>

                                            <span class="custom-option" data-value="Logistics & Transportation">
                                                Logistics & Transportation
                                            </span>

                                            <span class="custom-option" data-value="Agriculture & Food Processing">
                                                Agriculture & Food Processing
                                            </span>

                                            <span class="custom-option" data-value="Real Estate & Construction">
                                                Real Estate & Construction
                                            </span>

                                            <span class="custom-option" data-value="Media & Communications">
                                                Media & Communications
                                            </span>

                                            <span class="custom-option" data-value="Education & Training">
                                                Education & Training
                                            </span>

                                            <span class="custom-option" data-value="Government & Public Services">
                                                Government & Public Services
                                            </span>

                                            <span class="custom-option"
                                                data-value="Environmental & Specialized Services">
                                                Environmental & Specialized Services
                                            </span>
                                        </div>

                                    </div>

                                    {{-- <p class="industry-message">kindly select your industry first</p> --}}

                                    <select name="industry" id="industry" style="display: none">
                                        <option value="Technology & Digital Services"> Technology & Digital Services
                                        </option>
                                        <option value="Financial & Professional Services"> Financial & Professional
                                            Services</option>
                                        <option value="Manufacturing & Industrial"> Manufacturing & Industrial</option>
                                        <option value="Healthcare & Pharmaceuticals"> Healthcare & Pharmaceuticals
                                        </option>
                                        <option value="Energy & Utilities"> Energy & Utilities</option>
                                        <option value="Retail & Consumer Goods"> Retail & Consumer Goods</option>
                                        <option value="Logistics & Transportation"> Logistics & Transportation</option>
                                        <option value="Agriculture & Food Processing"> Agriculture & Food Processing
                                        </option>
                                        <option value="Real Estate & Construction"> Real Estate & Construction</option>
                                        <option value="Media & Communications"> Media & Communications</option>
                                        <option value="Education & Training"> Education & Training</option>
                                        <option value="Government & Public Services"> Government & Public Services
                                        </option>
                                        <option value="Environmental & Specialized Services"> Environmental & Specialized
                                            Services</option>

                                    </select>
                                </div>

                                <!-- Categories -->
                                <div class="interest-form-group">
                                    <div class="interest-form-tags" id="categoryContainer"></div>
                                </div>

                                <!-- Hidden field to store selected categories for form submission -->
                                <input type="hidden" name="categories" id="selectedCategories" />

                                <!-- Employees Custom Select -->
                                <div class="interest-form-group">
                                    <label for="employees">Numbers of Employees</label>

                                    <div class="custom-select" data-select="employees">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Select Employees range</span>
                                            <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                alt="" />
                                        </div>

                                        <div class="custom-options" id="number-of-employees">
                                            <span class="custom-option" data-value="1-10">1-10</span>
                                            <span class="custom-option" data-value="11-50">11-50</span>
                                            <span class="custom-option" data-value="50-200">50-200</span>
                                            <span class="custom-option" data-value="200+">200+</span>
                                        </div>
                                    </div>

                                    <!-- Hidden native select -->
                                    <select id="employees" name="number_of_employees" style="display: none">
                                        <option value="">Select Employees range</option>
                                        <option value="1-10">1-10</option>
                                        <option value="11-50">11-50</option>
                                        <option value="50-200">50-200</option>
                                        <option value="200+">200+</option>
                                    </select>
                                </div>

                                <!-- Revenue Custom Select -->
                                <div class="interest-form-group">
                                    <label for="revenue">
                                        <span>
                                            Approximate annual revenue
                                        </span>
                                        <span class="interest-form-sublabel">(confidential – for internal use only)</span>
                                    </label>

                                    <div class="custom-select" data-select="revenue">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Select revenue range</span>
                                            <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                alt="" />
                                        </div>

                                        <div class="custom-options">
                                            <span class="custom-option" data-value="Below $1M">Below $1M</span>
                                            <span class="custom-option" data-value="$1M - $5M">$1M - $5M</span>
                                            <span class="custom-option" data-value="$5M+">$5M+</span>
                                        </div>
                                    </div>

                                    <!-- Hidden native select -->
                                    <select id="revenue" name="annual_revenue" style="display: none">
                                        <option value="">Select revenue range</option>
                                        <option value="Below $1M">Below $1M</option>
                                        <option value="$1M - $5M">$1M - $5M</option>
                                        <option value="$5M+">$5M+</option>
                                    </select>
                                </div>

                                <div class="interest-form-actions">
                                    <button type="button" class="interest-form-return-button" data-prev="form2">
                                        Return
                                    </button>
                                    <button type="button" class="interest-form-proceed-button" data-next="form4">
                                        Proceed
                                    </button>
                                </div>
                            </div>

                            <!-- fourth Form -->
                            <div class="fourth-stage-form-container">
                                <div class="interest-form-group">
                                    <label class="value-offer" for="offerValue">What value can your company offer to
                                        other CEOs in the
                                        network?</label>
                                    <textarea id="offerValue" name="offer_value" rows="1" placeholder="Enter your value"></textarea>
                                </div>

                                <div class="interest-form-group">
                                    <label for="companyDescription" class="company-desc">Briefly describe what your
                                        company does and who your
                                        clients/customers are (max 150 words)</label>
                                    <textarea id="companyDescription" name="goals" rows="5" placeholder="Enter your description"></textarea>
                                </div>

                                <div class="interest-form-group">
                                    <label for="interest">What are you most interested in from this network?</label>

                                    <div class="custom-select" data-select="interest">
                                        <div class="custom-select-trigger">
                                            <span class="custom-select-text">Select interest</span>
                                            <span class="custom-select-arrow">
                                                <img class="custom-select-arrow" src="{{ asset('img/arrown.png') }}"
                                                    alt="" />
                                            </span>
                                        </div>

                                        <div class="custom-options">
                                            <div class="custom-option" data-value="Networking">
                                                Networking
                                            </div>
                                            <div class="custom-option" data-value="Verified Business Leads">
                                                Verified Business Leads
                                            </div>
                                            <div class="custom-option" data-value="Automated Sales Tools">
                                                Automated Sales Tools
                                            </div>
                                            <div class="custom-option" data-value="Consulting Support">
                                                Consulting Support
                                            </div>
                                        </div>
                                    </div>

                                    <!-- hidden select to sync value -->
                                    <select name="selected_outcome" id="selectedOutcome" style="display: none">
                                        <option value="">Select interest</option>
                                        <option value="Networking">Networking</option>
                                        <option value="Verified Business Leads">Verified Business Leads</option>
                                        <option value="Automated Sales Tools">Automated Sales Tools</option>
                                        <option value="Consulting Support">Consulting Support</option>
                                    </select>
                                </div>

                                <div class="interest-form-group">
                                    <p class="interest-form-question">
                                        If your business isn’t eligible for the CEO Referral
                                        Network at this time, would you be interested in joining
                                        our B2B platform to access:
                                    </p>
                                    <ul class="interest-form-list">
                                        <li>Verified business leads</li>
                                        <li>Automated sales tools</li>
                                        <li>Growth audits and consulting support?</li>
                                    </ul>

                                    <div class="interest-form-option-buttons">
                                        <div class="interest-form-option">
                                            <input type="radio" id="opt-yes" name="b2b_interest" value="yes"
                                                checked />
                                            <label for="opt-yes">Yes, I'm interested</label>
                                        </div>
                                        <div class="interest-form-option">
                                            <input type="radio" id="opt-maybe" name="b2b_interest"
                                                value="maybe" />
                                            <label for="opt-maybe">Maybe, send me more information</label>
                                        </div>
                                        <div class="interest-form-option">
                                            <input type="radio" id="opt-no" name="b2b_interest"
                                                value="no" />
                                            <label for="opt-no">No, not interested</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="interest-form-actions">
                                    <button type="button" class="interest-form-return-button" data-prev="form3">
                                        Return
                                    </button>
                                    <button type="submit" class="interest-form-submit-button" id="submitBtn">
                                        <span class="btn-text">Submit</span>
                                        <div class="spinner-border spinner-border-sm d-none" role="status"
                                            aria-hidden="true"></div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Response Modal -->
                <div class="modal fade" id="responseModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center py-4">
                                {{-- <p id="responseMessage" class="mb-3"></p> --}}
                                <div id="responseMessage">

                                </div>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Done</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row-two-container">
                <!-- Desktop Heading -->
                <section class="interest-form-heading desktop-heading">
                    <!-- First Form Heading -->
                    <div class="first-form-heading hidden">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>{{ __('brn.basic') }}</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Let's start with your basic details</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper ">
                                    <div class="step ">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Form Heading -->
                    <div class="second-form-heading hidden">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>{{ __('brn.credibility') }}</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Stand out among other businesses</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Third Form Heading -->
                    <div class="third-form-heading hidden">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>{{ __('brn.preference') }}</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Boost visibility for your matches</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fourth Form Heading -->
                    <div class="fourth-form-heading">
                        <div class="form-heading-card">
                            <div class="header">
                                <h1>{{ __('brn.insight') }}</h1>
                                <div class="subtitle">
                                    <span class="line"></span>
                                    <p>Stand out among other businesses</p>
                                </div>
                            </div>

                            <div class="description">
                                <p>
                                    NOEL transforms executive networking from casual connections
                                    to strategic growth. Our platform facilitates verified,
                                    high-value introductions between senior leaders and
                                    investors. Focus on building relationships that drive real
                                    business outcomes.
                                </p>
                            </div>
                            <div class="progress-step-container">
                                <div class="progress-stepper">
                                    <div class="step active">
                                        <div class="circle">1</div>
                                        <div class="label">Basic Information</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">2</div>
                                        <div class="label">Credibility Check</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">3</div>
                                        <div class="label">Industry Preference</div>
                                    </div>

                                    <div class="step">
                                        <div class="circle">4</div>
                                        <div class="label">Industry Insight</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- footer -->
                <section class="interest-form-footer">
                    <div class="form-footer">
                        <h2>Contact Information</h2>

                        <div class="interest-form-footer-contact-row">
                            <div>
                                <div class="interest-form-footer-contact-item">
                                    <span class="interest-form-footer-icon phone"><i
                                            class="fas fa-phone-alt"></i></span>
                                    <a href="tel:+447498006410" class="phone">+44 7498 006410</a>
                                </div>

                                <div class="interest-form-footer-contact-item">
                                    <span class="interest-form-footer-icon"><i class="fas fa-envelope"></i></span>
                                    <a href="mailto:hello@p23africa.com">hello@p23africa.com</a>
                                </div>
                            </div>

                            <div class="interest-form-footer-contact-item">
                                <span class="interest-form-footer-icon location">
                                    <img class="location-icon" src="{{ asset('img/location_pin_place_map.png') }}"
                                        alt="" />
                                </span>
                                <span>London UK</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection


