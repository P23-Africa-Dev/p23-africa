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
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('warning'))
                            <div class="alert alert-warning">{{ session('warning') }}</div>
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

                        <form action="{{ orgRoute('admin.staff.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror" required>
                            </div>

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror" required>
                            </div>

                            @if (auth()->user()->hasRole('admin'))
                                <div class="mb-3">
                                    <label>Role</label>
                                    <select name="role" class="form-control">
                                        <option value="staff" {{ old('role', 'staff') === 'staff' ? 'selected' : '' }}>
                                            Staff</option>
                                        <option value="supervisor"
                                            {{ old('role') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
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
                            @else
                                {{-- Supervisors can only add staff role, org is auto-set --}}
                                <input type="hidden" name="role" value="staff">
                                @if (auth()->user()->organization_id)
                                    <div class="mb-3">
                                        <label>Organization</label>
                                        <input type="text" class="form-control"
                                            value="{{ auth()->user()->organization->name ?? 'Your Organization' }}"
                                            disabled>
                                    </div>
                                @endif
                            @endif

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
