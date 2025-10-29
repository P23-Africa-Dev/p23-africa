<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition">
            {{ __('NOEL Interested Applicants') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg dark-transition">
                <div class="p-6 text-dark dark:text-gray-100 table-responsive dark-transition">
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

                    <div class="my-5">
                        <table class="table table-hover bg-theme-primary dark-transition">
                            <thead class="bg-theme-secondary dark-transition">
                                <tr>
                                    <th class="text-theme-primary dark-transition">Name</th>
                                    <th class="text-theme-primary dark-transition">Email</th>
                                    <th class="text-theme-primary dark-transition">Company</th>
                                    <th class="text-theme-primary dark-transition">Status</th>
                                    <th class="text-theme-primary dark-transition">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submissions as $submission)
                                    <tr class="hover-theme dark-transition">
                                        <td class="text-theme-primary dark-transition">{{ $submission->name }}</td>
                                        <td class="text-theme-primary dark-transition">{{ $submission->email }}</td>
                                        <td class="text-theme-primary dark-transition">{{ $submission->company_name }}
                                        </td>
                                        <td class="text-theme-primary dark-transition">
                                            @if ($submission->status === 'pending')
                                                <span
                                                    class="badge bg-warning text-dark dark:bg-yellow-600 dark:text-yellow-100 dark-transition">{{ ucfirst($submission->status) }}</span>
                                            @elseif($submission->status === 'approved')
                                                <span
                                                    class="badge bg-success dark:bg-green-600 dark-transition">{{ ucfirst($submission->status) }}</span>
                                            @else
                                                <span
                                                    class="badge bg-danger dark:bg-red-600 dark-transition">{{ ucfirst($submission->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-theme-primary dark-transition">
                                            <div class="d-flex gap-2">
                                                <!-- View Button -->
                                                <a href="{{ route('admin.brn.show', $submission->id) }}"
                                                    class="btn btn-sm btn-primary dark:bg-blue-600 dark:border-blue-600 dark:hover:bg-blue-700 dark-transition"
                                                    title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.brn.destroy', $submission->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this application?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger dark:bg-red-600 dark:border-red-600 dark:hover:bg-red-700 dark-transition"
                                                        title="Delete Application">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>

                                                @if ($submission->status === 'pending')
                                                    <!-- Approve Button -->
                                                    <form action="{{ route('admin.brn.approve', $submission->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button
                                                            class="btn btn-sm btn-success dark:bg-green-600 dark:border-green-600 dark:hover:bg-green-700 dark-transition"
                                                            title="Approve Application">Approve</button>
                                                    </form>
                                                    <!-- Decline Button -->
                                                    <form action="{{ route('admin.brn.decline', $submission->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button
                                                            class="btn btn-sm btn-warning dark:bg-yellow-600 dark:border-yellow-600 dark:hover:bg-yellow-700 dark-transition"
                                                            title="Decline Application">Decline</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $submissions->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
