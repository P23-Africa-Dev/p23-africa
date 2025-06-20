<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 table-responsive">
                    <div class="my-5">
                        <form action="{{ route('admin.categories.store') }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="name">Category Name</label>
                                <input type="text" name="name" class="form-control" required
                                    value="{{ old('name') }}">
                            </div>

                            <div class="form-group mb-3">
                                <label for="description">Description (optional)</label>
                                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-success">Save Category</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
