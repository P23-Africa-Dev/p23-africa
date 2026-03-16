<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold mb-1" style="font-size:1.25rem;">
                    <i class="bi bi-upload me-2"></i>Upload Spreadsheet
                </h2>
                <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">
                    Upload an Excel file (.xlsx, .xls, .csv)
                </p>
            </div>
            <a href="{{ orgRoute('admin.spreadsheets.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ orgRoute('admin.spreadsheets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label" style="font-size:.8125rem;font-weight:600;">
                                Spreadsheet Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name" class="form-control" required
                                   value="{{ old('name') }}" placeholder="e.g. Monthly Budget">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label" style="font-size:.8125rem;font-weight:600;">
                                Description
                            </label>
                            <textarea name="description" id="description" class="form-control" rows="2"
                                      placeholder="Optional description...">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="file" class="form-label" style="font-size:.8125rem;font-weight:600;">
                                Excel File <span class="text-danger">*</span>
                            </label>
                            <div id="dropZone" class="border rounded p-4 text-center position-relative"
                                 style="border-style:dashed!important;border-color:var(--border);cursor:pointer;transition:all .2s;"
                                 onclick="document.getElementById('file').click()">
                                <i class="bi bi-cloud-arrow-up" style="font-size:2rem;color:var(--text-muted);"></i>
                                <p class="mb-1" style="font-size:.875rem;color:var(--text-muted);">
                                    Click to browse or drag & drop
                                </p>
                                <p class="mb-0" style="font-size:.7rem;color:var(--text-muted);">
                                    .xlsx, .xls, or .csv (max 10MB)
                                </p>
                                <div id="fileInfo" class="mt-2 d-none">
                                    <span class="badge bg-success" id="fileName"></span>
                                </div>
                                <input type="file" name="file" id="file" class="d-none" accept=".xlsx,.xls,.csv" required
                                       onchange="showFileName(this)">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="bi bi-upload me-1"></i> Upload & Process
                            </button>
                            <a href="{{ orgRoute('admin.spreadsheets.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showFileName(input) {
            const info = document.getElementById('fileInfo');
            const name = document.getElementById('fileName');
            if (input.files.length) {
                name.textContent = input.files[0].name;
                info.classList.remove('d-none');
            }
        }

        // Drag & drop
        const dz = document.getElementById('dropZone');
        ['dragenter','dragover'].forEach(e => dz.addEventListener(e, ev => { ev.preventDefault(); dz.style.borderColor='var(--p-primary)'; dz.style.background='rgba(99,102,241,.04)'; }));
        ['dragleave','drop'].forEach(e => dz.addEventListener(e, ev => { ev.preventDefault(); dz.style.borderColor=''; dz.style.background=''; }));
        dz.addEventListener('drop', ev => {
            const dt = ev.dataTransfer;
            if (dt.files.length) {
                document.getElementById('file').files = dt.files;
                showFileName(document.getElementById('file'));
            }
        });
    </script>
    @endpush
</x-app-layout>
