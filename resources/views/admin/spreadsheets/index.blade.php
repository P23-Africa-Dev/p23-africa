<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="font-semibold mb-1" style="font-size:1.25rem;">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Spreadsheets
                </h2>
                <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">
                    Upload, view, and edit spreadsheets for your organisation
                </p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor'))
                    <a href="{{ orgRoute('admin.spreadsheets.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-plus-circle"></i> Upload Spreadsheet
                    </a>
                    <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1"
                            onclick="managePasscode()">
                        <i class="bi bi-key"></i> <span class="d-none d-sm-inline">Passcode</span>
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($spreadsheets->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-file-earmark-spreadsheet" style="font-size:3rem;color:var(--text-muted);"></i>
                <h5 class="mt-3" style="color:var(--text-muted);">No spreadsheets yet</h5>
                <p style="color:var(--text-muted);font-size:.875rem;">
                    Upload an XLSX file to get started.
                </p>
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor'))
                    <a href="{{ orgRoute('admin.spreadsheets.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Upload Spreadsheet
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach ($spreadsheets as $ss)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100" style="transition:all .2s;cursor:pointer;"
                         onclick="window.location='{{ orgRoute('admin.spreadsheets.show', ['spreadsheet' => $ss->id]) }}'"
                         onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)'"
                         onmouseout="this.style.transform='';this.style.boxShadow=''">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div style="width:42px;height:42px;border-radius:10px;background:rgba(16,185,129,.12);
                                            color:var(--p-success);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-file-earmark-spreadsheet" style="font-size:1.2rem;"></i>
                                </div>
                                <div style="min-width:0;flex:1;">
                                    <h6 class="mb-1" style="font-size:.9rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $ss->name }}
                                    </h6>
                                    @if ($ss->description)
                                        <p class="mb-1" style="font-size:.75rem;color:var(--text-muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                            {{ $ss->description }}
                                        </p>
                                    @endif
                                    <div style="font-size:.7rem;color:var(--text-muted);">
                                        <span title="Original file">{{ $ss->original_filename }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center" style="font-size:.7rem;color:var(--text-muted);padding:.5rem .75rem;">
                            <span>
                                <i class="bi bi-person me-1"></i>{{ $ss->creator->name ?? 'Unknown' }}
                            </span>
                            <span>
                                <i class="bi bi-clock me-1"></i>{{ $ss->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Passcode Management Modal --}}
    <div class="modal fade" id="passcodeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-size:.9375rem;">
                        <i class="bi bi-key me-1"></i> Edit Passcode
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="passcodeInfo" class="mb-3">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                    </div>
                    <div id="passcodeDisplay" class="d-none mb-3">
                        <div class="alert alert-success py-2" style="font-size:.8125rem;">
                            <strong>Current Passcode:</strong>
                            <span id="passcodeValue" style="font-family:monospace;font-size:1.1rem;letter-spacing:2px;"></span>
                        </div>
                    </div>
                    <button onclick="generatePasscode()" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-arrow-repeat me-1"></i> Generate New Passcode
                    </button>
                    <p class="mt-2 mb-0" style="font-size:.7rem;color:var(--text-muted);">
                        Staff members will need this passcode to edit spreadsheets.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function managePasscode() {
            const modal = new bootstrap.Modal(document.getElementById('passcodeModal'));
            modal.show();
            // Load info
            fetch('{{ orgRoute("admin.spreadsheets.passcode-info") }}', {
                headers: {'Accept':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}
            })
            .then(r => r.json())
            .then(data => {
                const info = document.getElementById('passcodeInfo');
                if (data.exists) {
                    info.innerHTML = '<div style="font-size:.8125rem;color:var(--text-muted);">'
                        + 'Created by <strong>' + data.created_by + '</strong><br>'
                        + 'Last regenerated ' + (data.regenerated_at || 'never')
                        + '</div>';
                } else {
                    info.innerHTML = '<div style="font-size:.8125rem;color:var(--text-muted);">No passcode set yet. Generate one below.</div>';
                }
            });
        }

        function generatePasscode() {
            fetch('{{ orgRoute("admin.spreadsheets.generate-passcode") }}', {
                method: 'POST',
                headers: {
                    'Accept':'application/json',
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('passcodeDisplay').classList.remove('d-none');
                    document.getElementById('passcodeValue').textContent = data.passcode;
                    document.getElementById('passcodeInfo').innerHTML =
                        '<div style="font-size:.8125rem;color:var(--p-success);"><i class="bi bi-check-circle me-1"></i>Passcode generated!</div>';
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
