@extends('layouts.master')
@section('title', 'Contact | P23 Africa')
@section('body_class', 'p23-black-navbar-page contact-flow-page')
@section('navbar_consultation_label', 'Book Consultation')
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="Tell P23 Africa about your business, your expansion plans, and the support you need. Start a conversation with our team.">
<link rel="stylesheet" href="{{ asset('css/services-new.css') }}">
<link rel="stylesheet" href="{{ asset('css/contact-flow.css') }}">
<script src="{{ asset('js/contact-flow.js') }}" defer></script>
@endsection
@section('content')
@php
    $countries = json_decode(file_get_contents(resource_path('data/contact-countries.json')), true);
    $selectedServices = old('selected_services', []);
@endphp
<section class="cf-stage" aria-label="Contact P23 Africa">
    <noscript><p class="cf-alert">Please enable JavaScript to use this three-step enquiry form.</p></noscript>
    <form id="contactFlow" class="cf-form" method="POST" action="{{ route('contact.submit') }}" novalidate>
        @csrf
        <input type="hidden" name="form_flow" value="contact-v2">
        <div class="cf-badge" aria-hidden="true">
            <img id="cfRing" class="cf-ring" src="{{ asset('images/contact/ring-1.svg') }}" alt="" data-ring-base="{{ asset('images/contact') }}">
            <img class="cf-ring-inner" src="{{ asset('images/contact/ring-inner.svg') }}" alt="">
            <span id="cfStepNumber">01</span>
        </div>
        <header class="cf-header">
            <div class="cf-progress">
                <span id="cfStepCount">01 of 03</span>
                <div id="cfProgress" role="progressbar" aria-label="Form progress" aria-valuemin="1" aria-valuemax="3" aria-valuenow="1"><span></span></div>
            </div>
            <h1 id="cfTitle" tabindex="-1">Business Overview</h1>
            <p id="cfDescription">First, let’s get to know you and your business so we can better understand how to support you.</p>
        </header>
        <div id="cfStatus" class="cf-alert" role="status" aria-live="polite" @if (!session('success') && !session('error') && !$errors->any()) hidden @endif>
            @if (session('success')) {{ session('success') }} @elseif (session('error')) {{ session('error') }} @elseif ($errors->any()) Please check the highlighted fields and try again. @endif
        </div>
        <div class="cf-body">
            <fieldset class="cf-step cf-overview" data-step="1">
                <legend class="cf-sr-only">Business Overview</legend>
                <div class="cf-column">
                    <div class="cf-field">
                        <label for="cfName" class="cf-sr-only">Full Name</label>
                        <input id="cfName" name="name" autocomplete="name" placeholder="Full Name" maxlength="255" required value="{{ old('name') }}" aria-describedby="error-name">
                        <span class="cf-error" id="error-name">@error('name'){{ $message }}@enderror</span>
                    </div>
                    <div class="cf-field">
                        <div class="cf-phone">
                            <div class="cf-country">
                                <label class="cf-sr-only" for="cfCountry">Phone country code</label>
                                <img id="cfFlagImage" src="{{ asset('images/contact/flag-za.png') }}" width="32" height="22" alt="">
                                <span id="cfFlagEmoji" aria-hidden="true" hidden></span>
                                <select id="cfCountry" name="country_code" required aria-label="Phone country code">
                                    @foreach ($countries as $country)
                                    <option value="{{ $country['code'] }}" data-flag="{{ $country['flag'] }}" @selected(old('country_code', '+27') === $country['code'])>{{ $country['name'] }} ({{ $country['code'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="cf-phone-number"><span id="cfDialCode" aria-hidden="true">+27</span><label class="cf-sr-only" for="cfPhone">Phone Number</label><input type="tel" id="cfPhone" name="phone" autocomplete="tel-national" maxlength="20" pattern="[0-9 ()+\-]{5,20}" required value="{{ old('phone') }}" aria-describedby="error-phone"></div>
                        </div>
                        <span class="cf-error" id="error-phone">@error('phone'){{ $message }}@enderror @error('country_code'){{ $message }}@enderror</span>
                    </div>
                    <div class="cf-field">
                        <label for="cfCompany" class="cf-sr-only">Company/Brand Name</label>
                        <input id="cfCompany" name="company_name" autocomplete="organization" placeholder="Company/Brand Name" maxlength="255" required value="{{ old('company_name') }}" aria-describedby="error-company_name">
                        <span class="cf-error" id="error-company_name">@error('company_name'){{ $message }}@enderror</span>
                    </div>
                    <div class="cf-field">
                        <label for="cfRole" class="cf-sr-only">Role in the Company</label>
                        <input id="cfRole" name="role" autocomplete="organization-title" placeholder="Role in the Company" maxlength="255" required value="{{ old('role') }}" aria-describedby="error-role">
                        <span class="cf-error" id="error-role">@error('role'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="cf-column cf-column-right">
                    <div class="cf-field">
                        <label for="cfEmail" class="cf-sr-only">Email Address</label>
                        <input type="email" id="cfEmail" name="email" autocomplete="email" placeholder="Email Address" maxlength="255" required value="{{ old('email') }}" aria-describedby="error-email">
                        <span class="cf-error" id="error-email">@error('email'){{ $message }}@enderror</span>
                    </div>
                    <div class="cf-field">
                        <label for="cfIndustry" class="cf-sr-only">Industry</label>
                        <select id="cfIndustry" name="industry" required aria-describedby="error-industry">
                            <option value="" disabled @selected(!old('industry'))>Industry</option>
                            @foreach (config('contact.industries') as $industry)<option @selected(old('industry') === $industry)>{{ $industry }}</option>@endforeach
                        </select>
                        <span class="cf-error" id="error-industry">@error('industry'){{ $message }}@enderror</span>
                    </div>
                    <div class="cf-field">
                        <label for="cfBusiness" class="cf-sr-only">Briefly describe what your business does (1–2 sentences).</label>
                        <textarea id="cfBusiness" name="business_description" placeholder="Briefly describe what your business does (1–2 sentences)." maxlength="1000" required aria-describedby="error-business_description">{{ old('business_description') }}</textarea>
                        <span class="cf-error" id="error-business_description">@error('business_description'){{ $message }}@enderror</span>
                    </div>
                </div>
            </fieldset>
            <fieldset class="cf-step cf-needs" data-step="2" hidden>
                <legend class="cf-sr-only">What do you need</legend>
                <div class="cf-column">
                    <fieldset class="cf-choice-group">
                        <legend>What do you hope to achieve or what is the need?</legend>
                        <div class="cf-choices cf-service-choices">
                            @foreach (config('contact.services') as $service)
                            <label class="cf-choice"><input type="checkbox" name="selected_services[]" value="{{ $service }}" @checked(in_array($service, $selectedServices))><span>{{ $service }}</span></label>
                            @endforeach
                        </div>
                        <span class="cf-error" id="error-selected_services">@error('selected_services'){{ $message }}@enderror</span>
                    </fieldset>
                    <div class="cf-market-group">
                        <label for="cfMarket">Expansion Market</label>
                        <div class="cf-market-input"><input id="cfMarket" placeholder="Input country choice" maxlength="100" list="cfCountries" autocomplete="off"><button type="button" id="cfAddMarket">Add</button></div>
                        <datalist id="cfCountries">@foreach ($countries as $country)<option value="{{ $country['name'] }}">@endforeach</datalist>
                        <ul class="cf-markets" id="cfMarkets" aria-label="Selected expansion markets">
                            @foreach (old('expansion_markets', []) as $market)<li><span>{{ $market }}</span><input type="hidden" name="expansion_markets[]" value="{{ $market }}"><button type="button" aria-label="Remove {{ $market }}">×</button></li>@endforeach
                        </ul>
                        <span class="cf-error" id="error-expansion_markets">@error('expansion_markets'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="cf-column cf-needs-right">
                    <div class="cf-field">
                        <label for="cfGoals">Briefly describe what you want to accomplish.</label>
                        <textarea id="cfGoals" name="message" maxlength="2000" required aria-describedby="error-message">{{ old('message') }}</textarea>
                        <span class="cf-error" id="error-message">@error('message'){{ $message }}@enderror</span>
                    </div>
                    <fieldset class="cf-referrals">
                        <legend>How did you hear about us?</legend>
                        <div class="cf-referral-options">
                            @foreach (config('contact.referral_sources') as $source)
                            <label><input type="radio" name="referral_source" value="{{ $source }}" @checked(old('referral_source') === $source) required><span>{{ $source }}</span></label>
                            @endforeach
                        </div>
                        <label class="cf-sr-only" for="cfReferralOther">Other referral source</label>
                        <input id="cfReferralOther" name="referral_other" placeholder="Others" maxlength="255" value="{{ old('referral_other') }}">
                        <span class="cf-error" id="error-referral_source">@error('referral_source'){{ $message }}@enderror @error('referral_other'){{ $message }}@enderror</span>
                    </fieldset>
                </div>
            </fieldset>
            <fieldset class="cf-step cf-readiness" data-step="3" hidden>
                <legend class="cf-sr-only">Readiness and Budget</legend>
                <div class="cf-column">
                    <fieldset class="cf-choice-group">
                        <legend>When are you looking to get started?</legend>
                        <div class="cf-choices">
                            @foreach (config('contact.timelines') as $timeline)
                            <label class="cf-choice"><input type="radio" name="start_timeline" value="{{ $timeline }}" @checked(old('start_timeline') === $timeline) required><span>{{ $timeline }}</span></label>
                            @endforeach
                        </div>
                        <span class="cf-error" id="error-start_timeline">@error('start_timeline'){{ $message }}@enderror</span>
                    </fieldset>
                    <div class="cf-field cf-budget">
                        <label for="cfBudget">What is your budget?</label>
                        <input type="number" id="cfBudget" name="budget_range" placeholder="Input your budget (USD)" min="100" max="2147483647" step="1" required value="{{ old('budget_range') }}" aria-describedby="error-budget_range">
                        <span class="cf-error" id="error-budget_range">@error('budget_range'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="cf-column cf-notes">
                    <div class="cf-field"><label for="cfNotes">Anything else you feel the team should know?</label><textarea id="cfNotes" name="additional_notes" maxlength="2000">{{ old('additional_notes') }}</textarea><span class="cf-error" id="error-additional_notes">@error('additional_notes'){{ $message }}@enderror</span></div>
                </div>
            </fieldset>
            <div class="cf-actions">
                <button type="button" class="cf-back" id="cfBack" aria-label="Previous step" disabled><img src="{{ asset('images/contact/arrow-back.svg') }}" width="16" height="12.727" alt=""></button>
                <button type="submit" class="cf-next" id="cfNext" aria-label="Next step"><img src="{{ asset('images/contact/arrow-next.svg') }}" width="16" height="12.727" alt=""><span id="cfSubmitLabel" hidden>Send enquiry</span></button>
            </div>
        </div>
    </form>
    <div class="cf-success" id="cfSuccess" tabindex="-1" hidden><h1>Thank you for reaching out.</h1><p>Your enquiry has been sent. Our team will get back to you soon.</p><a href="{{ url('services') }}">Explore our services</a></div>
</section>
@endsection
@section('footer')
@include('layouts.includes.footer3')
@endsection
