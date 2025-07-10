<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Resource Blogs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 table-responsive">

                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-dark">Create Resource Blogs</a>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Create New Category</a>

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
                                    <th>Subtitle</th>
                                    <th>Writer</th>
                                    <th>Date Posted</th>
                                    <th>Action</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($blogs as $blog)
                                    <tr>
                                        <td>{{ Str::limit($blog->title, 23) }}</td>
                                        <td>{{ Str::limit($blog->subtitle, 20) }}</td>
                                        <td>{{ $blog->user->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($blog->created_at)->format('l, F jS Y') }}</td>
                                        <td>
                                            <span class="d-flex justify-content-center">
                                                {{-- <button class="btn"><a
                                                        href="{{ route('events.show', $event->slug) }}" target="_blank"
                                                        class="fa fa-eye link-dark"></a></button> --}}

                                                <button class="btn"><a href="{{ route('admin.blogs.edit', $blog) }}"
                                                        class="fa fa-edit link-primary"></a></button>

                                                <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
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
                    <div class="d-flex justify-content-center my-4">
                        {{ $blogs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
