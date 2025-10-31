<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition">
            {{ __('Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg dark-transition">
                <div class="p-6 text-dark dark:text-gray-100 table-responsive dark-transition">

                    <a href="{{ route('admin.categories.create') }}"
                        class="btn btn-dark dark:bg-gray-600 dark:border-gray-600 dark:hover:bg-gray-700 dark-transition">Add
                        New Category</a>

                    <div class="my-5">
                        <table class="table table-bordered bg-theme-primary dark-transition">
                            <thead class="bg-theme-secondary dark-transition">
                                <tr>
                                    <th class="text-theme-primary dark-transition">Name</th>
                                    <th class="text-theme-primary dark-transition">Description</th>
                                    <th class="text-theme-primary dark-transition">Blogs Count</th>
                                    <th class="text-theme-primary dark-transition">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr class="hover-theme dark-transition">
                                        <td class="text-theme-primary dark-transition">{{ $category->name }}</td>
                                        <td class="text-theme-primary dark-transition">{{ $category->description }}</td>
                                        <td class="text-theme-primary dark-transition">{{ $category->blogs->count() }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                class="btn btn-sm btn-warning dark:bg-yellow-600 dark:border-yellow-600 dark:hover:bg-yellow-700 dark-transition">Edit</a>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="btn btn-sm btn-danger dark:bg-red-600 dark:border-red-600 dark:hover:bg-red-700 dark-transition"
                                                    onclick="return confirm('Delete this category?')">Delete</button>
                                            </form>
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
