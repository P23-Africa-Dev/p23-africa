@php
    $displayNameStatus = $activeAccount?->display_name_status ?? 'pending_approval';
    $profileSyncStatus = $activeAccount?->profile_sync_status ?? 'pending_approval';
    $lastProfileSync = $activeAccount?->last_profile_sync;

    $statusLabel = function (?string $status): string {
        return ucfirst(str_replace('_', ' ', $status ?: 'pending approval'));
    };
@endphp

<div class="card mb-3">
    <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
        <span>Meta Profile Sync</span>
        <a href="{{ orgRoute('admin.whatsapp.profile-sync') }}" class="btn btn-outline-secondary btn-sm">Preview
            Profile</a>
    </div>
    <div class="card-body">
        @if ($activeAccount)
            <div class="row g-3 align-items-center">
                <div class="col-12 col-md-4 text-center text-md-start">
                    @if ($activeAccount->profile_image_url)
                        <img src="{{ $activeAccount->profile_image_url }}" alt="{{ $activeAccount->business_name }}"
                            class="img-fluid rounded border" style="max-width:120px;max-height:120px;object-fit:cover;">
                    @else
                        <div class="border rounded d-inline-flex align-items-center justify-content-center bg-light"
                            style="width:120px;height:120px;">
                            <i class="bi bi-building fs-1 text-muted"></i>
                        </div>
                    @endif
                </div>
                <div class="col-12 col-md-8">
                    <div class="fw-semibold fs-5">{{ $activeAccount->business_name }}</div>
                    <div class="text-muted small">{{ $activeAccount->phone_number }}</div>
                    <div class="small mt-2">
                        <div><strong>Display Name Status:</strong> {{ $statusLabel($displayNameStatus) }}</div>
                        <div><strong>Profile Sync Status:</strong> {{ $statusLabel($profileSyncStatus) }}</div>
                        <div><strong>Last Sync:</strong> {{ $lastProfileSync?->format('M d, Y h:i A') ?? 'Never' }}
                        </div>
                        <div><strong>Meta Profile ID:</strong>
                            {{ $activeAccount->meta_profile_id ?: 'Not synced yet' }}</div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-3">
                <form method="POST" action="{{ orgRoute('admin.whatsapp.profile-sync.sync') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Sync Now</button>
                </form>

                <form method="POST" action="{{ orgRoute('admin.whatsapp.profile-sync.refresh') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">Refresh Meta Status</button>
                </form>

                <a href="{{ orgRoute('admin.whatsapp.profile-sync') }}" class="btn btn-outline-dark">Preview
                    Profile</a>
            </div>
        @else
            <div class="alert alert-warning mb-0">
                No active WhatsApp account is configured yet.
            </div>
        @endif
    </div>
</div>
