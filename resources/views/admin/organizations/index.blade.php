<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Organizations</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <a href="{{ route('admin.organizations.create') }}" class="btn btn-primary mb-3">Create
                        Organization</a>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Description</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orgs as $org)
                                <tr>
                                    <td>{{ $org->id }}</td>
                                    <td>{{ $org->name }}</td>
                                    <td>{{ $org->slug }}</td>
                                    <td>{{ $org->description }}</td>
                                    <td>{{ $org->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('admin.organizations.edit', $org->id) }}"
                                            class="btn btn-sm btn-secondary">Edit</a>

                                        <form action="{{ route('admin.organizations.destroy', $org->id) }}"
                                            method="POST" style="display:inline-block"
                                            onsubmit="return confirm('Delete this organization? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit">Delete</button>
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

</x-app-layout>
