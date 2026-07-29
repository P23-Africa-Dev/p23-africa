<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.25rem;">WhatsApp Profile Sync</h2>
            <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Meta-controlled business name and
                profile review status.</p>
        </div>
    </x-slot>

    <div class="py-4">
        @include('admin.whatsapp.partials.nav')

        <div class="alert alert-light border mb-3">
            <strong>Current Account:</strong>
            {{ $activeAccount?->business_name ?: 'No active account' }}
            @if ($activeAccount?->phone_number)
                <span class="text-muted">({{ $activeAccount->phone_number }})</span>
            @endif
        </div>

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
            <div class="col-12 col-lg-8">
                @include('admin.whatsapp.partials.profile-sync-card', ['activeAccount' => $activeAccount])

                <div class="card">
                    <div class="card-header fw-semibold">Meta Approval Flow</div>
                    <div class="card-body">
                        <p class="mb-2">Meta decides whether the phone number display name is visible to recipients.
                            This dashboard can only request profile updates and track the review state.</p>
                        <ul class="mb-0">
                            <li><strong>Pending Approval:</strong> local request queued or awaiting Meta processing.
                            </li>
                            <li><strong>Waiting Review:</strong> Meta received the update and is reviewing it.</li>
                            <li><strong>Approved:</strong> Meta approved the display name and business profile.</li>
                            <li><strong>Rejected:</strong> Meta rejected the display name or profile update.</li>
                            <li><strong>Sync Failed:</strong> the Graph API request failed before review.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card mb-3">
                    <div class="card-header fw-semibold">Current Preview</div>
                    <div class="card-body text-center">
                        @if ($activeAccount?->profile_image_url)
                            <img src="{{ $activeAccount->profile_image_url }}" alt="{{ $activeAccount->business_name }}"
                                class="img-fluid rounded border mb-3"
                                style="max-width:180px;max-height:180px;object-fit:cover;">
                        @else
                            <div class="border rounded d-flex align-items-center justify-content-center bg-light mx-auto mb-3"
                                style="width:180px;height:180px;">
                                <i class="bi bi-building fs-1 text-muted"></i>
                            </div>
                        @endif

                        <div class="fw-semibold fs-5">{{ $activeAccount?->business_name ?? 'No account' }}</div>
                        <div class="text-muted small">{{ $activeAccount?->phone_number ?? 'No business number' }}</div>
                    </div>
                </div>

                <a href="{{ orgRoute('admin.whatsapp.settings') }}" class="btn btn-outline-dark w-100">Back to
                    Settings</a>
            </div>
        </div>
    </div>
</x-app-layout>
