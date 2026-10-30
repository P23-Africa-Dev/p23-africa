@extends('layouts.fb')

@section('content')
    <div class="container my-5">
        <fieldset class="border rounded-3 p-3">
            <legend class="float-none w-auto px-3">
                <h2>{{ __('events.feedback_title') }}</h2>
            </legend>

            <form action="{{ locale_route('feedback.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event_id }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <label>{{ __('events.rate') }}</label><br>
                <label><input type="radio" name="rating" value="Excellent" required> {{ __('events.excellent') }}</label><br>
                <label><input type="radio" name="rating" value="Good"> {{ __('events.good') }}</label><br>
                <label><input type="radio" name="rating" value="Fair"> {{ __('events.fair') }}</label><br>
                <label><input type="radio" name="rating" value="Poor"> {{ __('events.poor') }}</label><br><br>

                <label>{{ __('events.comments') }}</label><br>
                <textarea name="comments" rows="4" class="form-control"></textarea><br>

                <button type="submit" class="btn btn-primary">{{ __('events.submit_feedback') }}</button>
            </form>
        </fieldset>
    </div>
@endsection
