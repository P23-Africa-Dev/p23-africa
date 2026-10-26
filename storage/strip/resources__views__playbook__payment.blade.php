
@extends('layouts.master')
@section('title', 'Funding Readiness Guidebook E-Book | P23 Africa')

@section('meta')
    <meta name="description"
        content="Download your free Funding Readiness Guidebook. A step-by-step guide to becoming investor-ready, built specifically for African entrepreneurs seeking funding or sustainable growth.">
    <meta name="keywords"
        content="funding readiness, investor ready, African entrepreneurs, startup funding, pitch deck, business funding guide, investor directory, African startups, entrepreneurship guide">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Funding Readiness Guidebook for African Entrepreneurs | P23 Africa">
    <meta property="og:title" content="Funding Readiness Guidebook | P23 Africa">
    <meta property="og:description"
        content="Master your business story, understand your numbers, and pitch with confidence. Get your free guide to attract investors or grow sustainably.">
    <meta property="og:image" content="{{ asset('img/mobile.png') }}">
    <meta property="og:type" content="website">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

{{--  --}}



@section('content')
  


      <section class="payment-section">
      <div class="payment-card">
        <div class="payment-success-icon">
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
</svg>

          </span>
        </div>

        <h2 class="payment-title">Payment Success!</h2>

        <div class="payment-details">

          <div class="payment-divider first" ></div>

          <div class="payment-row">
            <span>References Number</span>
            <p>000085752257</p>
          </div>

          <div class="payment-row">
            <span>Date</span>
            <p>Mar 22, 2023</p>
          </div>

          <div class="payment-row">
            <span>Time</span>
            <p>07:80 AM</p>
          </div>

          <div class="payment-row">
            <span>Payment Method</span>
            <p>Credit Card</p>
          </div>


          <div class="payment-row">
            <span>Amount</span>
            <p class="amount">$10.1</p>
          </div>

          <div class="payment-divider" ></div>

        </div>

        <div class="payment-info-box">
          Your Guide has been sent to customer@example.com
        </div>

      </div>

        <button class="payment-footer-btn">Back to Home</button>

    </section>


@endsection



