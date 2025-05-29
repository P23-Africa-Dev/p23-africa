<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Events') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

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

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td>{{ Str::limit($event->title, 23) }}</td>
                                        <td>{{ Str::limit($event->subtitle, 20) }}</td>
                                        <td>{{ $event->event_date }}</td>
                                        <td>{{ $event->event_time }}</td>
                                        <td>{{ $event->visibility }}</td>
                                        <td>
                                            <span class="d-flex justify-content-center">
                                                <button class="btn"><a
                                                        href="{{ route('admin.events.bookings', $event->id) }}" target="_blank"
                                                        class="fa fa-users link-dark"></a></button>

                                                <button class="btn"><a
                                                        href="{{ route('events.show', $event->slug) }}" target="_blank"
                                                        class="fa fa-eye link-dark"></a></button>

                                                <button class="btn"><a
                                                        href="{{ route('admin.events.edit', $event) }}"
                                                        class="fa fa-edit link-primary"></a></button>

                                                <form action="{{ route('admin.events.destroy', $event) }}"
                                                    method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="btn"><i
                                                            class="fa fa-trash link-danger"></i></button>
                                                </form>
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
