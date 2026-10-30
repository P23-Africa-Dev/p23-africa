@extends('layouts.master')
@section('title', __('legal.terms_title').' | P23 Africa')

@section('meta')
    <meta name="description" content="{{ __('legal.terms_meta') }}">
@endsection

@section('content')
    <section class="container" style="max-width: 860px; padding: 8rem 1.25rem 4rem;">
        <h1>{{ __('legal.terms_title') }}</h1>
        @foreach (__('legal.terms') as $paragraph)
            <p>{{ $paragraph }}</p>
        @endforeach
    </section>
@endsection
