<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Staff: {{ $staff->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 table-responsive">
                    <div class="my-5">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.staff.update', $staff->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ old('email', $staff->email) }}"
                                    class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" value="{{ old('name', $staff->name) }}"
                                    class="form-control" required>
                            </div>

                            {{-- <input type="text" name="name" value="{{ old('name', $staff->name) }}" required>
                            <input type="email" name="email" value="{{ old('email', $staff->email) }}" required> --}}

                            {{-- <h4>Permissions</h4> --}}
                            {{-- <div class="mb-3">
                                <label>Permissions</label><br>
                                <input type="checkbox" name="permissions[]" value="manage_events"> Manage Events<br>
                                <input type="checkbox" name="permissions[]" value="resource_hub"> Resource Hub<br>
                                <input type="checkbox" name="permissions[]" value="view_students"> View Students<br>
                            </div> --}}
                            {{-- @foreach ($permissions as $permission)
                                <label>
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        {{ in_array($permission->name, $staffPermissions) ? 'checked' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                </label><br>
                            @endforeach --}}

                            <button type="submit" class="btn btn-primary">Update Staff</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
