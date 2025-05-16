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
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td>{{ $event->title }}</td>
                                        <td>{{ $event->subtitle }}</td>
                                        <td>{{ $event->event_date }}</td>
                                        <td>{{ $event->event_time }}</td>
                                        <td>
                                            <span class="d-flex justify-content-center">
                                                <a href="{{ route('admin.events.edit', $event) }}"
                                                    class="fa fa-edit link-primary mr-2"></a>

                                                <form action="{{ route('admin.events.destroy', $event) }}"
                                                    method="POST" class="d-inline ml-2">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="fa fa-trash link-danger"></button>
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
