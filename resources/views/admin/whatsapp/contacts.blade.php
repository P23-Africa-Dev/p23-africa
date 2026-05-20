<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.25rem;">WhatsApp Contacts</h2>
            <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Manage contact identities used by
                WhatsApp conversations.</p>
        </div>
    </x-slot>

    <div class="py-4">
        @include('admin.whatsapp.partials.nav')

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ orgRoute('admin.whatsapp.contacts') }}" class="row g-2 align-items-end">
                    <div class="col-12 col-md-8">
                        <label class="form-label">Search</label>
                        <input class="form-control" name="search" value="{{ $search }}"
                            placeholder="Search by name or phone">
                    </div>
                    <div class="col-12 col-md-4 d-flex gap-2">
                        <button class="btn btn-success w-100" type="submit">Filter</button>
                        <a class="btn btn-outline-secondary w-100"
                            href="{{ orgRoute('admin.whatsapp.contacts') }}">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3">Name</th>
                                <th>Phone</th>
                                <th>Conversations</th>
                                <th class="pe-3">Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($contacts as $contact)
                                <tr>
                                    <td class="ps-3">{{ $contact->name ?: 'Unknown' }}</td>
                                    <td>{{ $contact->phone }}</td>
                                    <td>{{ $contact->conversations_count }}</td>
                                    <td class="pe-3">
                                        <form method="POST"
                                            action="{{ orgRoute('admin.whatsapp.contacts.update', ['contact' => $contact->id]) }}"
                                            class="row g-1">
                                            @csrf
                                            @method('PATCH')
                                            <div class="col-12 col-md-5">
                                                <input type="text" class="form-control form-control-sm"
                                                    name="name" value="{{ $contact->name }}"
                                                    placeholder="Display name">
                                            </div>
                                            <div class="col-12 col-md-5">
                                                <input type="url" class="form-control form-control-sm"
                                                    name="profile_picture" value="{{ $contact->profile_picture }}"
                                                    placeholder="Profile image URL">
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <button class="btn btn-sm btn-outline-success w-100"
                                                    type="submit">Save</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No contacts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">{{ $contacts->links() }}</div>
        </div>
    </div>
</x-app-layout>
