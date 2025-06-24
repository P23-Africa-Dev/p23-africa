@extends('layouts.fb')

@section('content')
    <div class="container my-5">
        <fieldset class="border rounded-3 p-3">
            <legend class="float-none w-auto px-3">
                <h2>We’d love your feedback</h2>
            </legend>

            <form action="{{ route('feedback.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event_id }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <label>How would you rate this event?</label><br>
                <label><input type="radio" name="rating" value="Excellent" required> Excellent</label><br>
                <label><input type="radio" name="rating" value="Good"> Good</label><br>
                <label><input type="radio" name="rating" value="Fair"> Fair</label><br>
                <label><input type="radio" name="rating" value="Poor"> Poor</label><br><br>

                <label>Comments</label><br>
                <textarea name="comments" rows="4" class="form-control"></textarea><br>

                <button type="submit" class="btn btn-primary">Submit Feedback</button>
            </form>
        </fieldset>
    </div>
@endsection
