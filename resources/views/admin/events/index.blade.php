<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 table-responsive">

                    <a href="{{ route('admin.events.create') }}" class="btn btn-dark">Create Event</a>

                    <div class="my-5">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Display</th>
                                    <th>Action</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td>{{ Str::limit($event->title, 23) }}</td>
                                        <td>{{ $event->event_date }}</td>
                                        <td>{{ $event->event_time }}</td>
                                        <td>{{ $event->visibility }}</td>
                                        <td class="text-capitalize">{{ str_replace('_', ' ', $event->display) }}</td>
                                        <td>
                                            <span class="d-flex justify-content-center">
                                                <button class="btn"><a
                                                        href="{{ route('admin.events.bookings', $event->id) }}"
                                                        target="_blank" class="fa fa-users link-dark"></a></button>

                                                <button class="btn"><a
                                                        href="{{ route('events.show', $event->slug) }}" target="_blank"
                                                        class="fa fa-eye link-dark"></a></button>

                                                <button class="btn"><a
                                                        href="{{ route('admin.events.edit', $event) }}"
                                                        class="fa fa-edit link-primary"></a></button>

                                                <button class="btn"><a
                                                        href="{{ route('admin.events.feedbacks', $event->id) }}"
                                                        target="_blank" class="fa-regular fa-comment-dots link-dark"></a></button>

                                                <button class="btn"><a
                                                        href="{{ route('admin.events.clicks', $event->id) }}"
                                                        target="_blank" class="fa fa-chart-line link-dark"></a></button>

                                                <form action="{{ route('admin.events.destroy', $event) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn"><i
                                                            class="fa fa-trash link-danger"></i></button>
                                                </form>
                                            </span>
                                        </td>
                                        <td>
                                            <span>
                                                @php
                                                    $eventDateTime = \Carbon\Carbon::parse(
                                                        $event->event_date . ' ' . $event->event_time,
                                                    );
                                                    $now = \Carbon\Carbon::now();
                                                    $hoursUntilEvent = $now->diffInHours($eventDateTime, false); // negative if past
                                                @endphp

                                                @if ($hoursUntilEvent <= 24 && $hoursUntilEvent > 0)
                                                    <form action="{{ route('admin.events.sendReminder', $event->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Send reminder emails to all participants?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary">Send Reminder
                                                            Now</button>
                                                    </form>
                                                @endif
                                            </span>

                                            <span>
                                                @php
                                                    $eventDateTime2 = \Carbon\Carbon::parse(
                                                        $event->event_date . ' ' . $event->event_time,
                                                    );
                                                    $now2 = \Carbon\Carbon::now();
                                                    $hoursUntilEvent2 = $now2->diffInHours($eventDateTime2, true); // negative if past

                                                    // $eventTimePassed = \Carbon\Carbon::parse($event->date_time)
                                                    //     ->addMinute()
                                                    //     ->lt(now());

                                                @endphp

                                                @if ($hoursUntilEvent2 <= 24 && $hoursUntilEvent2 > 0)
                                                    <form action="{{ route('admin.events.sendFeedback', $event->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-dark">Send Feedback
                                                            Request</button>
                                                    </form>
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>


</x-app-layout>
