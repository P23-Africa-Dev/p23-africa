<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('admin.events.store') }}" method="POST">
                        @csrf
                        <input name="title" class="form-control mb-2 border border-1 border-dark"
                            placeholder="Event Title" required><br>
                        <input name="subtitle" class="form-control mb-2 border border-1 border-dark"
                            placeholder="Subtitle (optional)"><br>
                        <textarea name="description" class="form-control mb-2 border border-1 border-dark" rows="5"
                            placeholder="Description" required></textarea><br>
                        <input type="url" name="link" class="form-control mb-2 border border-1 border-dark"
                            placeholder="Event Link" required><br>
                        <input type="date" name="event_date" class="form-control mb-2 border border-1 border-dark"
                            required><br>
                        <input type="time" name="event_time" class="form-control mb-2 border border-1 border-dark"
                            required><br>
                        <button type="submit" class="btn btn-dark w-25">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
