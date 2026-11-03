@extends('layouts.master')
@section('title', 'Key Challenges | P23 Africa')
@section('body_class', 'p23-black-navbar-page contact-flow-page')
@section('hide_navbar', '1')
@section('navbar_consultation_label', 'Book Consultation')
@section('meta')
<meta name="robots" content="noindex">
<link rel="stylesheet" href="{{ asset('css/services-new.css') }}">
<link rel="stylesheet" href="{{ asset('css/contact-flow.css') }}?v=5">
@endsection
@section('content')
@php
    $selected = old('key_challenges', []);
    $token = $assessmentToken ?? request('assessment');
@endphp
<section class="cf-stage cf-kc-stage" aria-label="Key challenges">
    <form id="cfKeyChallenges" class="cf-form cf-kc" method="POST" action="{{ locale_route('contact.consultation.challenges.submit') }}" novalidate>
        @csrf
        @if (!empty($token))
        <input type="hidden" name="assessment" value="{{ $token }}">
        @endif
        <header class="cf-header cf-kc-header">
            <h1 tabindex="-1">{{ __('consultation.challenges') }}</h1>
            <p>{{ __('consultation.challenges_prompt') }}</p>
        </header>
        @if ($errors->any())
        <div class="cf-alert" role="alert">{{ $errors->first('key_challenges') ?: $errors->first() }}</div>
        @endif
        <div class="cf-body cf-kc-body">
            <fieldset class="cf-kc-group">
                <legend class="cf-sr-only">{{ __('consultation.challenges_prompt') }}</legend>
                <div class="cf-kc-choices">
                    @foreach ($challenges as $challenge)
                    <label class="cf-choice cf-kc-choice">
                        <input type="checkbox" name="key_challenges[]" value="{{ $challenge }}" @checked(in_array($challenge, $selected, true))>
                        <span>{{ $challenge }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="cf-error" id="cfKcError" role="status" aria-live="polite"></p>
            </fieldset>
            <div class="cf-actions">
                <a class="cf-back" href="{{ locale_route('contact.consultation.schedule', !empty($token) ? ['assessment' => $token] : []) }}" aria-label="Back to scheduling"><img src="{{ asset('images/contact/arrow-back.svg') }}" width="16" height="12.727" alt=""></a>
                <button type="submit" class="cf-next" aria-label="Continue to consultation summary"><img src="{{ asset('images/contact/arrow-next.svg') }}" width="16" height="12.727" alt=""></button>
            </div>
        </div>
    </form>
</section>
<script>
(() => {
    const form = document.getElementById('cfKeyChallenges');
    const error = document.getElementById('cfKcError');
    const boxes = [...form.querySelectorAll('input[name="key_challenges[]"]')];
    const max = 3;
    boxes.forEach(box => box.addEventListener('change', () => {
        if (boxes.filter(b => b.checked).length > max) {
            box.checked = false;
            error.textContent = @json(__('consultation.err_challenge_max'));
            return;
        }
        error.textContent = '';
    }));
    form.addEventListener('submit', event => {
        if (!boxes.some(b => b.checked)) {
            event.preventDefault();
            error.textContent = @json(__('consultation.err_challenge'));
            boxes[0].focus();
            return;
        }
        form.querySelector('.cf-next').disabled = true;
    });
})();
</script>
@endsection
@section('footer')
@include('layouts.includes.footer3')
@endsection
