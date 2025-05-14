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

                    <a href="{{ route('admin.events.create') }}">Create Event</a>

                    @foreach ($events as $event)
                        <div>
                            <h3>{{ $event->title }}</h3>
                            <p>{{ $event->subtitle }}</p>
                            <a href="{{ route('admin.events.edit', $event) }}">Edit</a>
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
