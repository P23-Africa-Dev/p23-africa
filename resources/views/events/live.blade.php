@extends('layouts.master')
@section('title', 'Live Event | P23 Africa')

@section('content')
    <div class="container text-center" style="margin: 8rem auto;">
        <div class="">
            <h2 class="mb-4">Welcome {{ $seat->name }}</h2>
            <p class="text-muted mb-5">You’ve joined the live session!</p>

            <div class="d-flex justify-content-center">
                <div class="row">
                    <!-- Event Information -->
                    <div class="col-md-6 text-start">
                        <h4><strong>{{ strtoupper($event->title) }}</strong></h4>
                        <p class="mb-1"><em>{{ $event->subtitle }}</em></p>
                        <p>{!! Str::limit($event->description, 90) !!}</p>
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->toFormattedDateString() }}
                        </p>
                        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</p>
                    </div>

                    <!-- Speaker Image -->
                    <div class="col-md-6">
                        <div class="border border-3 rounded-circle overflow-hidden mx-auto"
                            style="width: 200px; height: 200px;">
                            <img src="{{ asset('images/testimonial3.png') }}" class="img-fluid" alt="Speaker">
                        </div>
                            {{-- <iframe width="100%" height="350" src="https://www.youtube.com/embed/GDszyEAH8aA?si=xvBqaLnRY8ClItFF" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe> --}}
                    </div>
                </div>
            </div>

            <!-- Optionally: mic/chat buttons -->
            <div class="row mt-5 justify-content-center">
                <div class="col-auto">
                    <button class="btn btn-outline-secondary"><i class="bi bi-mic-fill"></i></button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-secondary"><i class="bi bi-chat-dots-fill"></i></button>
                </div>
            </div>
        </div>
    </div>
@endsection
