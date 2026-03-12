<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition">
            {{ __('All Resource Blogs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg dark-transition">
                <div class="p-6 text-dark dark:text-gray-100 table-responsive dark-transition">

                    <a href="{{ orgRoute('admin.blogs.create') }}"
                        class="btn btn-dark dark:bg-gray-600 dark:border-gray-600 dark:hover:bg-gray-700 dark-transition">Create
                        Resource Blogs</a>
                    <a href="{{ orgRoute('admin.categories.index') }}"
                        class="btn btn-primary dark:bg-blue-600 dark:border-blue-600 dark:hover:bg-blue-700 dark-transition">Create
                        New Category</a>

                    <div class="my-5">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show dark-transition" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close dark:filter dark:invert" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show dark-transition" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close dark:filter dark:invert" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <table class="table table-hover bg-theme-primary dark-transition">
                            <thead class="bg-theme-secondary dark-transition">
                                <tr>
                                    <th class="text-theme-primary dark-transition">Title</th>
                                    <th class="text-theme-primary dark-transition">Subtitle</th>
                                    <th class="text-theme-primary dark-transition">Writer</th>
                                    <th class="text-theme-primary dark-transition">Date Posted</th>
                                    <th class="text-theme-primary dark-transition">Action</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($blogs as $blog)
                                    <tr class="hover-theme dark-transition">
                                        <td class="text-theme-primary dark-transition">
                                            {{ Str::limit($blog->title, 23) }}</td>
                                        <td class="text-theme-primary dark-transition">
                                            {{ Str::limit($blog->subtitle, 20) }}</td>
                                        <td class="text-theme-primary dark-transition">{{ $blog->user->name }}</td>
                                        <td class="text-theme-primary dark-transition">
                                            {{ \Carbon\Carbon::parse($blog->created_at)->format('l, F jS Y') }}</td>
                                        <td>
                                            <span class="d-flex justify-content-center">
                                                {{-- <button class="btn"><a
                                                        href="{{ route('events.show', $event->slug) }}" target="_blank"
                                                        class="fa fa-eye link-dark"></a></button> --}}

                                                <button class="btn"><a href="{{ orgRoute('admin.blogs.edit', $blog) }}"
                                                        class="fa fa-edit text-primary dark:text-blue-400 dark-transition"></a></button>

                                                <form action="{{ orgRoute('admin.blogs.destroy', $blog) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this blog? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn"><i
                                                            class="fa fa-trash text-danger dark:text-red-400 dark-transition"></i></button>
                                                </form>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center my-4">
                        {{ $blogs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
