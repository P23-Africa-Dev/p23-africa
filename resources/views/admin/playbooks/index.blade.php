<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('P23 Africa\'s Founders Ebook Downloads') }}
            </h2>
            <a href="{{ orgRoute('admin.playbooks.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Ebooks Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 table-responsive">
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
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Company</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($playbooks as $index => $playbook)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $playbook->name }}</td>
                                        <td>{{ $playbook->email }}</td>
                                        <td>{{ $playbook->company }}</td>
                                        <td>{{ \Carbon\Carbon::parse($playbook->email_sent_at)->format('l, F jS Y') }}
                                        </td>
                                        <td>
                                            <span class="d-flex justify-content-center">
                                                <form action="{{ orgRoute('admin.playbooks.destroy', $playbook) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this download? This action cannot be undone.');">
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
                        {{ $playbooks->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
