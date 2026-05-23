<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.25rem;">Developer Security Settings</h2>
            <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">
                Manage WhatsApp developer passcode, lock sessions, and review security activity logs.
            </p>
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
            <div class="col-12 col-lg-5">
                <div class="card mb-3">
                    <div class="card-header fw-semibold">Change Developer Passcode</div>
                    <div class="card-body">
                        <form method="POST"
                            action="{{ orgRoute('admin.whatsapp.developer-security.passcode.update') }}">
                            @csrf

                            <div class="mb-2">
                                <label class="form-label">Master Passcode</label>
                                <div class="input-group">
                                    <input type="password" name="master_passcode" class="form-control"
                                        data-toggle-password required>
                                    <button type="button" class="btn btn-outline-secondary"
                                        data-toggle-password-button>
                                        Show
                                    </button>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">New Developer Passcode</label>
                                <div class="input-group">
                                    <input type="password" name="new_passcode" class="form-control" minlength="8"
                                        data-toggle-password required>
                                    <button type="button" class="btn btn-outline-secondary"
                                        data-toggle-password-button>
                                        Show
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm New Developer Passcode</label>
                                <div class="input-group">
                                    <input type="password" name="new_passcode_confirmation" class="form-control"
                                        minlength="8" data-toggle-password required>
                                    <button type="button" class="btn btn-outline-secondary"
                                        data-toggle-password-button>
                                        Show
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success">Update Passcode</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header fw-semibold">Emergency Controls</div>
                    <div class="card-body d-flex flex-wrap gap-2">
                        <form method="POST" action="{{ orgRoute('admin.whatsapp.developer-security.passcode.reset') }}"
                            onsubmit="return confirm('Reset passcode to default value?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning">Reset Passcode</button>
                        </form>

                        <form method="POST" action="{{ orgRoute('admin.whatsapp.developer-security.force-relock') }}"
                            onsubmit="return confirm('Force relock all active unlock sessions now?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">Force Relock Sessions</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header fw-semibold">Unlock and Edit Audit Logs</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                        <th>IP</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($logs as $log)
                                        <tr>
                                            <td>{{ $log->id }}</td>
                                            <td>{{ $log->user?->name ?? 'System' }}</td>
                                            <td>{{ str_replace('_', ' ', ucfirst($log->action)) }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $log->status === 'success' ? 'bg-success' : ($log->status === 'failure' ? 'bg-danger' : 'bg-secondary') }}">
                                                    {{ ucfirst($log->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $log->ip ?? '-' }}</td>
                                            <td>{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">No security log
                                                records yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $logs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('[data-toggle-password-button]').forEach((button) => {
                button.addEventListener('click', () => {
                    const input = button.closest('.input-group')?.querySelector('[data-toggle-password]');
                    if (!input) {
                        return;
                    }

                    const isPassword = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPassword ? 'text' : 'password');
                    button.textContent = isPassword ? 'Hide' : 'Show';
                });
            });
        </script>
    @endpush
</x-app-layout>
