<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition">
            {{ __('Edit Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg dark-transition">
                <div class="p-6 text-dark dark:text-gray-100 table-responsive dark-transition">
                    <div class="my-5">
                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label for="name" class="text-theme-primary dark-transition">Category Name</label>
                                <input type="text" name="name"
                                    class="form-control border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary"
                                    required value="{{ old('name', $category->name) }}">
                            </div>

                            <div class="form-group mb-3">
                                <label for="description" class="text-theme-primary dark-transition">Description</label>
                                <textarea name="description"
                                    class="form-control border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary">{{ old('description', $category->description) }}</textarea>
                            </div>

                            <button type="submit"
                                class="btn btn-primary dark:bg-blue-600 dark:border-blue-600 dark:hover:bg-blue-700 dark-transition">Update
                                Category</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
