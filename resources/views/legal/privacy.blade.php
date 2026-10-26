@extends('layouts.master')
@section('title', __('legal.privacy_title').' | P23 Africa')

@section('meta')
    <meta name="description" content="{{ __('legal.privacy_meta') }}">
@endsection

@section('content')
    <section class="container" style="max-width: 860px; padding: 8rem 1.25rem 4rem;">
        <h1>{{ __('legal.privacy_title') }}</h1>
        @foreach (__('legal.privacy') as $paragraph)
            <p>{{ $paragraph }}</p>
        @endforeach
    </section>
@endsection
