<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Downloaded Playbooks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 table-responsive">
                    <div class="my-5">

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Company</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($playbooks as $index => $playbook)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $playbook->name }}</td>
                                        <td>{{ $playbook->email }}</td>
                                        <td>{{ $playbook->company }}</td>
                                        <td>{{ \Carbon\Carbon::parse($playbook->email_sent_at)->format('l, F jS Y') }}</td>
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
