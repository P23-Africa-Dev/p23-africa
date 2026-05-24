<x-app-layout>
    @push('head')
        <style>
            .wa-contacts-compact .card-header,
            .wa-contacts-compact .card-body,
            .wa-contacts-compact .card-footer {
                padding-top: .5rem;
                padding-bottom: .5rem;
            }

            .wa-contacts-compact .form-label,
            .wa-contacts-compact .small,
            .wa-contacts-compact small,
            .wa-contacts-compact th,
            .wa-contacts-compact td {
                font-size: .76rem;
            }

            .wa-contacts-compact .form-control,
            .wa-contacts-compact .form-select,
            .wa-contacts-compact .btn {
                font-size: .78rem;
            }

            .wa-contacts-compact .btn {
                min-height: 29px;
                padding-top: .15rem;
                padding-bottom: .15rem;
            }

            .wa-contacts-compact .table td,
            .wa-contacts-compact .table th {
                vertical-align: top;
                padding-top: .4rem;
                padding-bottom: .4rem;
            }
        </style>
    @endpush

    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.25rem;">WhatsApp Contacts</h2>
            <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Manage contact identities used by
                WhatsApp conversations.</p>
        </div>
    </x-slot>

    <div class="py-3 wa-contacts-compact">
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
                    <div class="col-12 col-md-9">
                        <label class="form-label">Search</label>
                        <input class="form-control" name="search" value="{{ $search }}"
                            placeholder="Search name, phone, company or email">
                    </div>
                    <div class="col-12 col-md-3 d-flex gap-1">
                        <button class="btn btn-success w-100" type="submit">Filter</button>
                        <a class="btn btn-outline-secondary w-100"
                            href="{{ orgRoute('admin.whatsapp.contacts') }}">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Create Contact</div>
            <div class="card-body">
                <form method="POST" action="{{ orgRoute('admin.whatsapp.contacts.store') }}"
                    enctype="multipart/form-data" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-12 col-md-4">
                        <label class="form-label">Name</label>
                        <input class="form-control" name="name" placeholder="Contact name">
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="form-label">Phone</label>
                        <input class="form-control" name="phone" placeholder="+233501234567" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <button class="btn btn-success w-100" type="submit">Add Contact</button>
                    </div>

                    <div class="col-12">
                        <details>
                            <summary class="small text-muted">Optional details</summary>
                            <div class="row g-2 mt-1">
                                <div class="col-12 col-md-3">
                                    <label class="form-label">Country code</label>
                                    <input class="form-control" name="country_code" placeholder="233">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label">Email</label>
                                    <input class="form-control" type="email" name="email"
                                        placeholder="name@example.com">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label">Company</label>
                                    <input class="form-control" name="company" placeholder="Company">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label">Avatar URL</label>
                                    <input class="form-control" name="avatar" placeholder="https://...">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label">Avatar file</label>
                                    <input class="form-control" type="file" name="avatar_file" accept="image/*">
                                </div>
                                <div class="col-12 col-md-8">
                                    <label class="form-label">Notes</label>
                                    <input class="form-control" name="notes" placeholder="Optional notes">
                                </div>
                            </div>
                        </details>
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
                                <th>Validation</th>
                                <th>Company</th>
                                <th>Conversations</th>
                                <th class="pe-3">Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($contacts as $contact)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold">{{ $contact->name ?: 'Unknown' }}</div>
                                        @if ($contact->unsaved_contact)
                                            <span class="badge bg-info text-dark">Unsaved</span>
                                        @endif
                                    </td>
                                    <td>{{ $contact->phone }}</td>
                                    <td>
                                        @include('admin.whatsapp.partials.ContactValidationBadge', [
                                            'contact' => $contact,
                                        ])
                                    </td>
                                    <td>{{ $contact->company ?: '-' }}</td>
                                    <td>{{ $contact->conversations_count }}</td>
                                    <td class="pe-3">
                                        <form method="POST"
                                            action="{{ orgRoute('admin.whatsapp.contacts.update', ['contact' => $contact->id]) }}"
                                            class="row g-1" enctype="multipart/form-data">
                                            @csrf
                                            @method('PATCH')
                                            <div class="col-12 col-md-4">
                                                <input type="text" class="form-control form-control-sm"
                                                    name="name" value="{{ $contact->name }}"
                                                    placeholder="Display name">
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <input type="text" class="form-control form-control-sm"
                                                    name="country_code" value="{{ $contact->country_code }}"
                                                    placeholder="Code">
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <input type="email" class="form-control form-control-sm"
                                                    name="email" value="{{ $contact->email }}"
                                                    placeholder="Email">
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    name="company" value="{{ $contact->company }}"
                                                    placeholder="Company">
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <input type="url" class="form-control form-control-sm"
                                                    name="avatar" value="{{ $contact->avatar }}"
                                                    placeholder="Avatar URL">
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <input type="file" class="form-control form-control-sm"
                                                    name="avatar_file" accept="image/*">
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <input type="url" class="form-control form-control-sm"
                                                    name="profile_picture" value="{{ $contact->profile_picture }}"
                                                    placeholder="Profile image URL">
                                            </div>
                                            <div class="col-12 col-md-8">
                                                <input type="text" class="form-control form-control-sm"
                                                    name="notes" value="{{ $contact->notes }}"
                                                    placeholder="Notes">
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <button class="btn btn-sm btn-outline-success w-100"
                                                    type="submit">Save</button>
                                            </div>
                                        </form>
                                        <form method="POST"
                                            action="{{ orgRoute('admin.whatsapp.contacts.destroy', ['contact' => $contact->id]) }}"
                                            class="mt-1"
                                            onsubmit="return confirm('Delete this contact? Conversations remain but contact record is removed.')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"
                                                type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No contacts found.</td>
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
