<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('NOEL Interested Applicants') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 table-responsive">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submissions as $submission)
                                <tr>
                                    <td>{{ $submission->name }}</td>
                                    <td>{{ $submission->email }}</td>
                                    <td>{{ $submission->company_name }}</td>
                                    <td>{{ ucfirst($submission->status) }}</td>
                                    <td>
                                        <!-- View Button -->
                                        <a href="{{ route('admin.brn.show', $submission->id) }}"
                                            class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.brn.destroy', $submission->id) }}" method="POST"
                                            style="display:inline"
                                            onsubmit="return confirm('Are you sure you want to delete this application?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                title="Delete Application">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                        @if ($submission->status === 'pending')
                                            <!-- Approve Button -->
                                            <form action="{{ route('admin.brn.approve', $submission->id) }}"
                                                method="POST" style="display:inline">
                                                @csrf
                                                <button class="btn btn-success btn-sm"
                                                    title="Approve Application">Approve</button>
                                            </form>
                                            <!-- Decline Button -->
                                            <form action="{{ route('admin.brn.decline', $submission->id) }}"
                                                method="POST" style="display:inline">
                                                @csrf
                                                <button class="btn btn-warning btn-sm"
                                                    title="Decline Application">Decline</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination Links -->
                    <div class="mt-6">
                        {{ $submissions->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>


</x-app-layout>
