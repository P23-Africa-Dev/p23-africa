<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('BRN Interested Applicants') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 table-responsive">
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
                                    <td>{{ $submission->full_name }}</td>
                                    <td>{{ $submission->email }}</td>
                                    <td>{{ $submission->company_name }}</td>
                                    <td>{{ ucfirst($submission->status) }}</td>
                                    <td>
                                        @if ($submission->status === 'pending')
                                            <form action="{{ route('admin.brn.approve', $submission->id) }}" method="POST"
                                                style="display:inline">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.brn.decline', $submission->id) }}" method="POST"
                                                style="display:inline">
                                                @csrf
                                                <button class="btn btn-danger btn-sm">Decline</button>
                                            </form>
                                        @else
                                            <span>—</span>
                                        @endif
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
