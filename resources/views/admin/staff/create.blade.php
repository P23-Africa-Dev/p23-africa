<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Staff') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 table-responsive">
                    <div class="my-5">
                        <form action="{{ route('admin.staff.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Role</label>
                                <select name="role" class="form-control">
                                    <option value="staff" selected>Staff</option>
                                    <option value="supervisor">Supervisor</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Organization</label>
                                <select name="organization_id" class="form-control">
                                    <option value="">-- None --</option>
                                    @foreach ($organizations as $org)
                                        <option value="{{ $org->id }}"
                                            @if (old('organization_id')) {{ old('organization_id') == $org->id ? 'selected' : '' }}
                                            @elseif(!empty($defaultOrg))
                                                {{ $defaultOrg->id == $org->id ? 'selected' : '' }} @endif>
                                            {{ $org->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- <div class="mb-3">
                                <label>Permissions</label><br>
                                <input type="checkbox" name="permissions[]" value="manage_events"> Manage Events<br>
                                <input type="checkbox" name="permissions[]" value="resource_hub"> Resource Hub<br>
                                <input type="checkbox" name="permissions[]" value="view_students"> View Students<br>
                            </div> --}}

                            <button type="submit" class="btn btn-primary">Add Staff</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
