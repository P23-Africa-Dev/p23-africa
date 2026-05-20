<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.25rem;">WhatsApp Settings</h2>
            <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Configure account credentials and
                deployment-safe runtime options.</p>
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

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header fw-semibold">WhatsApp Account</div>
                    <div class="card-body">
                        <form method="POST" action="{{ orgRoute('admin.whatsapp.settings.account.save') }}">
                            @csrf
                            @if ($activeAccount)
                                <input type="hidden" name="id" value="{{ $activeAccount->id }}">
                            @endif

                            <div class="mb-2">
                                <label class="form-label">Business Name</label>
                                <input type="text" name="business_name" class="form-control"
                                    value="{{ old('business_name', $activeAccount?->business_name) }}" required>
                            </div>

                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control"
                                        value="{{ old('phone_number', $activeAccount?->phone_number) }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Phone Number ID</label>
                                    <input type="text" name="phone_number_id" class="form-control"
                                        value="{{ old('phone_number_id', $activeAccount?->phone_number_id) }}" required>
                                </div>
                            </div>

                            <div class="row g-2 mt-1">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Business Account ID (WABA)</label>
                                    <input type="text" name="waba_id" class="form-control"
                                        value="{{ old('waba_id', $activeAccount?->waba_id) }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="active"
                                            {{ old('status', $activeAccount?->status ?? 'active') === 'active' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="inactive"
                                            {{ old('status', $activeAccount?->status) === 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-2 mt-2">
                                <label class="form-label">Access Token</label>
                                <textarea name="access_token" class="form-control" rows="3" required>{{ old('access_token', $activeAccount?->access_token) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Webhook Verify Token</label>
                                <input type="text" name="webhook_verify_token" class="form-control"
                                    value="{{ old('webhook_verify_token', $activeAccount?->webhook_verify_token) }}"
                                    required>
                            </div>

                            <button class="btn btn-success" type="submit">Save Account</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card mb-3">
                    <div class="card-header fw-semibold">Runtime Configuration</div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Enabled</span><strong>{{ $config['enabled'] ? 'Yes' : 'No' }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>Queue
                                    Enabled</span><strong>{{ $config['queue'] ? 'Yes' : 'No' }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>Poll
                                    Interval</span><strong>{{ $config['poll_interval'] }}s</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>Graph
                                    Version</span><strong>{{ $config['graph_version'] }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>Base
                                    URL</span><strong>{{ $config['base_url'] }}</strong></li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header fw-semibold">Saved Accounts</div>
                    <div class="card-body">
                        @forelse ($accounts as $account)
                            <div class="border rounded p-2 mb-2">
                                <div class="fw-semibold">{{ $account->business_name }}</div>
                                <div class="small text-muted">{{ $account->phone_number }} |
                                    {{ $account->phone_number_id }}</div>
                                <div class="small">
                                    <span
                                        class="badge {{ $account->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($account->status) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">No WhatsApp accounts saved yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
