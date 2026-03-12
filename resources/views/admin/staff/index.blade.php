<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Staffs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 table-responsive">

                    <a href="{{ orgRoute('admin.staff.create') }}" class="btn btn-dark">Add Staff</a>
                    {{-- <a href="{{ orgRoute('admin.categories.index') }}" class="btn btn-primary">Create New Category</a> --}}

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

                        @if (session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                {{ session('warning') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staff as $index => $staffs)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $staffs->name }}</td>
                                        <td>{{ $staffs->email }}</td>
                                        <td>{{ $staffs->getRoleNames()->join(', ') }}</td>
                                        <td>
                                            @if ($staffs->isSuspended())
                                                <span class="badge bg-danger">Suspended</span>
                                            @else
                                                <span class="badge bg-success">Active</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($staffs->created_at)->format('l, F jS Y') }}</td>
                                        <td>
                                            <span class="d-flex justify-content-center">
                                                {{-- <button class="btn"><a
                                                        href="{{ route('events.show', $event->slug) }}" target="_blank"
                                                        class="fa fa-eye link-dark"></a></button> --}}

                                                <button class="btn"><a
                                                        href="{{ orgRoute('admin.staff.edit', $staffs) }}"
                                                        class="fa fa-edit link-primary"></a></button>

                                                <form action="{{ orgRoute('admin.staff.destroy', $staffs) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn"><i
                                                            class="fa fa-trash link-danger"></i></button>
                                                </form>
                                            </span>
                                        </td>

                                        <td>
                                            @if (auth()->user()->hasRole('admin'))
                                                @if ($staffs->hasRole('staff'))
                                                    <form method="POST"
                                                        action="{{ orgRoute('admin.staff.promote', $staffs->id) }}"
                                                        onsubmit="return confirm('Are you sure you want to promote this staff to supervisor?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">Promote to
                                                            Supervisor</button>
                                                    </form>
                                                @elseif ($staffs->hasRole('supervisor'))
                                                    <span class="badge bg-info">Supervisor</span>
                                                @else
                                                    <span class="badge bg-primary">Staff</span>
                                                @endif
                                            @else
                                                @if ($staffs->hasRole('supervisor'))
                                                    <span class="badge bg-info">Supervisor</span>
                                                @else
                                                    <span class="badge bg-primary">Staff</span>
                                                @endif
                                            @endif
                                        </td>

                                        <td>
                                            @if ($staffs->isSuspended())
                                                <form action="{{ orgRoute('admin.staff.reactivate', $staffs->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-success btn-sm">Reactivate</button>
                                                </form>
                                            @else
                                                <form action="{{ orgRoute('admin.staff.suspend', $staffs->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm">Suspend</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center my-4">
                        {{ $staff->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
