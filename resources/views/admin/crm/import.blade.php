<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Import NOEL Waitlist Leads') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Import leads from CSV or Excel files</p>
            </div>
            <a href="{{ orgRoute('admin.crm.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-2"></i>Back to Leads
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('import_errors'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    <h6 class="alert-heading mb-2"><i class="fa fa-exclamation-triangle me-2"></i>Import Warnings</h6>
                    <ul class="mb-0 ps-3">
                        @foreach (session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @if (session('more_errors'))
                        <p class="mt-2 mb-0 text-muted">...and {{ session('more_errors') }} more errors.</p>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- File Format Requirements Card -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h5 class="mb-0 text-dark dark:text-gray-100">
                        <i class="fa fa-file-alt me-2 text-primary"></i>File Format Requirements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-dark dark:text-gray-100 mb-3">Recommended Columns</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    <strong>Full Name</strong> <span
                                        class="badge bg-warning text-dark ms-1">Recommended</span>
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    <strong>Email</strong> <span
                                        class="badge bg-warning text-dark ms-1">Recommended</span>
                                </li>
                            </ul>
                            <small class="text-muted d-block mt-2">
                                <i class="fa fa-info-circle me-1"></i>
                                Empty names will be imported as "Unknown". Empty or invalid emails will be imported
                                without email.
                            </small>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-dark dark:text-gray-100 mb-3">Optional Columns</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fa fa-circle text-secondary me-2" style="font-size: 8px;"></i>
                                    Company
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-circle text-secondary me-2" style="font-size: 8px;"></i>
                                    Employee Count
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-circle text-secondary me-2" style="font-size: 8px;"></i>
                                    Year Founded
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-circle text-secondary me-2" style="font-size: 8px;"></i>
                                    Industry
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-circle text-secondary me-2" style="font-size: 8px;"></i>
                                    Country
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-circle text-secondary me-2" style="font-size: 8px;"></i>
                                    Company LinkedIn Profile
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-circle text-secondary me-2" style="font-size: 8px;"></i>
                                    CEO LinkedIn Profile
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="alert alert-info mb-0 mt-3">
                        <i class="fa fa-info-circle me-2"></i>
                        <strong>Note:</strong> Column headers are case-insensitive. File must be CSV (.csv) or Excel
                        (.xlsx, .xls) format. Maximum file size: 10MB. All rows will be imported, with warnings shown
                        for missing data.
                    </div>
                </div>
            </div>

            <!-- Template Preview Card -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                <div class="card-header bg-transparent border-0 pt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark dark:text-gray-100">
                            <i class="fa fa-table me-2 text-primary"></i>Template Preview
                        </h5>
                        <div class="btn-group">
                            <a href="{{ orgRoute('admin.crm.import.template', ['format' => 'csv']) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-download me-1"></i>Download CSV Template
                            </a>
                            <a href="{{ orgRoute('admin.crm.import.template', ['format' => 'xlsx']) }}"
                                class="btn btn-sm btn-outline-success">
                                <i class="fa fa-download me-1"></i>Download Excel Template
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted dark:text-gray-400 mb-3">
                        Your file should look like this example. The first row must contain the column headers.
                    </p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0" style="font-size: 0.85rem;">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-nowrap">Full Name</th>
                                    <th class="text-nowrap">Company</th>
                                    <th class="text-nowrap">Employee Count</th>
                                    <th class="text-nowrap">Year Founded</th>
                                    <th class="text-nowrap">Industry</th>
                                    <th class="text-nowrap">Country</th>
                                    <th class="text-nowrap">Company LinkedIn Profile</th>
                                    <th class="text-nowrap">CEO LinkedIn Profile</th>
                                    <th class="text-nowrap">Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>Acme Corporation</td>
                                    <td>50-100</td>
                                    <td>2015</td>
                                    <td>Technology</td>
                                    <td>Nigeria</td>
                                    <td class="text-truncate" style="max-width: 150px;">
                                        https://linkedin.com/company/acme</td>
                                    <td class="text-truncate" style="max-width: 150px;">
                                        https://linkedin.com/in/johndoe
                                    </td>
                                    <td>john@acme.com</td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>Tech Solutions Ltd</td>
                                    <td>10-50</td>
                                    <td>2020</td>
                                    <td>Finance</td>
                                    <td>Kenya</td>
                                    <td class="text-truncate" style="max-width: 150px;">
                                        https://linkedin.com/company/techsol</td>
                                    <td class="text-truncate" style="max-width: 150px;">
                                        https://linkedin.com/in/janesmith</td>
                                    <td>jane@techsolutions.co.ke</td>
                                </tr>
                                <tr>
                                    <td>Michael Johnson</td>
                                    <td>African Ventures</td>
                                    <td>100-500</td>
                                    <td>2010</td>
                                    <td>Real Estate</td>
                                    <td>South Africa</td>
                                    <td class="text-truncate" style="max-width: 150px;">
                                        https://linkedin.com/company/africanventures</td>
                                    <td class="text-truncate" style="max-width: 150px;">
                                        https://linkedin.com/in/michaelj</td>
                                    <td>michael@africanventures.za</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Upload Form Card -->
            <div class="card border-0 shadow-sm dark:bg-gray-800">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h5 class="mb-0 text-dark dark:text-gray-100">
                        <i class="fa fa-upload me-2 text-primary"></i>Upload File
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ orgRoute('admin.crm.import.process') }}"
                        enctype="multipart/form-data" id="importForm">
                        @csrf

                        <!-- Drive Selection -->
                        <div class="mb-4">
                            <label class="form-label text-dark dark:text-gray-100">
                                <i class="fa fa-folder me-2"></i>Select Drive <span class="text-danger">*</span>
                            </label>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <select name="drive_id" id="driveSelect"
                                        class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                        <option value="">-- Select Existing Drive --</option>
                                        @foreach ($drives as $drive)
                                            <option value="{{ $drive->id }}" data-color="{{ $drive->color }}">
                                                {{ $drive->name }}
                                                @if ($drive->is_default)
                                                    (Default)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-primary w-100"
                                        data-bs-toggle="modal" data-bs-target="#newDriveModal">
                                        <i class="fa fa-plus me-2"></i>Create New Drive
                                    </button>
                                </div>
                            </div>
                            <div id="selectedDriveInfo" class="mt-2 d-none">
                                <small class="text-muted">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Leads will be imported into: <strong id="selectedDriveName"></strong>
                                </small>
                            </div>
                            <div id="newDriveInfo" class="mt-2 d-none">
                                <div class="alert alert-success py-2 mb-0">
                                    <i class="fa fa-check-circle me-1"></i>
                                    New drive "<strong id="newDriveNameDisplay"></strong>" will be created
                                    <button type="button" class="btn-close btn-sm float-end"
                                        id="cancelNewDrive"></button>
                                </div>
                                <input type="hidden" name="new_drive_name" id="newDriveNameInput">
                                <input type="hidden" name="new_drive_color" id="newDriveColorInput">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-dark dark:text-gray-100">
                                Select CSV or Excel File <span class="text-danger">*</span>
                            </label>
                            <div class="upload-area border-2 border-dashed rounded p-5 text-center"
                                style="border-color: #dee2e6; cursor: pointer; transition: all 0.2s ease;"
                                id="uploadArea">
                                <input type="file" name="file" id="fileInput" accept=".csv,.xlsx,.xls"
                                    class="d-none" required>
                                <div id="uploadPlaceholder">
                                    <i class="fa fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <h5 class="text-dark dark:text-gray-100">Drop your file here or click to browse
                                    </h5>
                                    <p class="text-muted mb-0">Supports: CSV, XLSX, XLS (Max 10MB)</p>
                                </div>
                                <div id="fileSelected" class="d-none">
                                    <i class="fa fa-file-excel fa-3x text-success mb-3"></i>
                                    <h5 class="text-dark dark:text-gray-100" id="fileName"></h5>
                                    <p class="text-muted mb-0" id="fileSize"></p>
                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                                        id="removeFile">
                                        <i class="fa fa-times me-1"></i>Remove
                                    </button>
                                </div>
                            </div>
                            @error('file')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ orgRoute('admin.crm.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="importBtn" disabled>
                                <i class="fa fa-upload me-2"></i>Import Leads
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- New Drive Modal -->
    <div class="modal fade" id="newDriveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header">
                    <h5 class="modal-title dark:text-gray-100">
                        <i class="fa fa-folder-plus me-2 text-primary"></i>Create New Drive
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-dark dark:text-gray-100">Drive Name <span
                                class="text-danger">*</span></label>
                        <input type="text" id="modalDriveName"
                            class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                            placeholder="e.g., NOEL Waitlist, Event Leads, etc.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark dark:text-gray-100">Drive Color</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <input type="color" id="modalDriveColor" value="#0d6efd"
                                class="form-control form-control-color" style="width: 50px;">
                            <div class="drive-color-presets d-flex gap-1">
                                <button type="button" class="btn btn-sm color-preset"
                                    style="background: #0d6efd; width: 30px; height: 30px;"
                                    data-color="#0d6efd"></button>
                                <button type="button" class="btn btn-sm color-preset"
                                    style="background: #198754; width: 30px; height: 30px;"
                                    data-color="#198754"></button>
                                <button type="button" class="btn btn-sm color-preset"
                                    style="background: #dc3545; width: 30px; height: 30px;"
                                    data-color="#dc3545"></button>
                                <button type="button" class="btn btn-sm color-preset"
                                    style="background: #ffc107; width: 30px; height: 30px;"
                                    data-color="#ffc107"></button>
                                <button type="button" class="btn btn-sm color-preset"
                                    style="background: #6c757d; width: 30px; height: 30px;"
                                    data-color="#6c757d"></button>
                                <button type="button" class="btn btn-sm color-preset"
                                    style="background: #0dcaf0; width: 30px; height: 30px;"
                                    data-color="#0dcaf0"></button>
                                <button type="button" class="btn btn-sm color-preset"
                                    style="background: #6f42c1; width: 30px; height: 30px;"
                                    data-color="#6f42c1"></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmNewDrive">
                        <i class="fa fa-check me-2"></i>Use This Drive
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .upload-area:hover {
                border-color: #0d6efd !important;
                background-color: rgba(13, 110, 253, 0.05);
            }

            .upload-area.dragover {
                border-color: #0d6efd !important;
                background-color: rgba(13, 110, 253, 0.1);
            }

            .table th,
            .table td {
                vertical-align: middle;
            }

            .color-preset {
                border: 2px solid transparent;
                border-radius: 4px;
            }

            .color-preset:hover,
            .color-preset.active {
                border-color: #212529;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const uploadArea = document.getElementById('uploadArea');
                const fileInput = document.getElementById('fileInput');
                const uploadPlaceholder = document.getElementById('uploadPlaceholder');
                const fileSelected = document.getElementById('fileSelected');
                const fileName = document.getElementById('fileName');
                const fileSize = document.getElementById('fileSize');
                const removeFile = document.getElementById('removeFile');
                const importBtn = document.getElementById('importBtn');
                const importForm = document.getElementById('importForm');

                // Drive selection elements
                const driveSelect = document.getElementById('driveSelect');
                const selectedDriveInfo = document.getElementById('selectedDriveInfo');
                const selectedDriveName = document.getElementById('selectedDriveName');
                const newDriveInfo = document.getElementById('newDriveInfo');
                const newDriveNameDisplay = document.getElementById('newDriveNameDisplay');
                const newDriveNameInput = document.getElementById('newDriveNameInput');
                const newDriveColorInput = document.getElementById('newDriveColorInput');
                const cancelNewDrive = document.getElementById('cancelNewDrive');

                // Modal elements
                const modalDriveName = document.getElementById('modalDriveName');
                const modalDriveColor = document.getElementById('modalDriveColor');
                const confirmNewDrive = document.getElementById('confirmNewDrive');
                const colorPresets = document.querySelectorAll('.color-preset');

                let fileReady = false;
                let driveReady = false;

                // Update import button state
                function updateImportButton() {
                    importBtn.disabled = !(fileReady && driveReady);
                }

                // Drive selection change
                driveSelect.addEventListener('change', function() {
                    if (this.value) {
                        const selectedOption = this.options[this.selectedIndex];
                        selectedDriveName.textContent = selectedOption.text;
                        selectedDriveInfo.classList.remove('d-none');
                        newDriveInfo.classList.add('d-none');
                        newDriveNameInput.value = '';
                        newDriveColorInput.value = '';
                        driveReady = true;
                    } else {
                        selectedDriveInfo.classList.add('d-none');
                        driveReady = newDriveNameInput.value !== '';
                    }
                    updateImportButton();
                });

                // Color preset clicks
                colorPresets.forEach(preset => {
                    preset.addEventListener('click', function() {
                        modalDriveColor.value = this.dataset.color;
                        colorPresets.forEach(p => p.classList.remove('active'));
                        this.classList.add('active');
                    });
                });

                // Confirm new drive
                confirmNewDrive.addEventListener('click', function() {
                    const name = modalDriveName.value.trim();
                    if (!name) {
                        alert('Please enter a drive name');
                        return;
                    }

                    // Set hidden inputs
                    newDriveNameInput.value = name;
                    newDriveColorInput.value = modalDriveColor.value;

                    // Update UI
                    newDriveNameDisplay.textContent = name;
                    newDriveInfo.classList.remove('d-none');
                    selectedDriveInfo.classList.add('d-none');
                    driveSelect.value = '';

                    driveReady = true;
                    updateImportButton();

                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('newDriveModal')).hide();

                    // Reset modal
                    modalDriveName.value = '';
                    modalDriveColor.value = '#0d6efd';
                    colorPresets.forEach(p => p.classList.remove('active'));
                });

                // Cancel new drive
                cancelNewDrive.addEventListener('click', function() {
                    newDriveInfo.classList.add('d-none');
                    newDriveNameInput.value = '';
                    newDriveColorInput.value = '';
                    driveReady = driveSelect.value !== '';
                    updateImportButton();
                });

                // Click to upload
                uploadArea.addEventListener('click', function(e) {
                    if (e.target !== removeFile && !removeFile.contains(e.target)) {
                        fileInput.click();
                    }
                });

                // Drag and drop
                uploadArea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    uploadArea.classList.add('dragover');
                });

                uploadArea.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    uploadArea.classList.remove('dragover');
                });

                uploadArea.addEventListener('drop', function(e) {
                    e.preventDefault();
                    uploadArea.classList.remove('dragover');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        handleFile(files[0]);
                    }
                });

                // File input change
                fileInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        handleFile(this.files[0]);
                    }
                });

                // Remove file
                removeFile.addEventListener('click', function(e) {
                    e.stopPropagation();
                    fileInput.value = '';
                    uploadPlaceholder.classList.remove('d-none');
                    fileSelected.classList.add('d-none');
                    fileReady = false;
                    updateImportButton();
                });

                // Handle file selection
                function handleFile(file) {
                    const validExtensions = ['csv', 'xlsx', 'xls'];
                    const extension = file.name.split('.').pop().toLowerCase();

                    if (!validExtensions.includes(extension)) {
                        alert('Please select a valid CSV or Excel file.');
                        return;
                    }

                    if (file.size > 10 * 1024 * 1024) {
                        alert('File size must be less than 10MB.');
                        return;
                    }

                    // Update file input
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;

                    // Show file info
                    fileName.textContent = file.name;
                    fileSize.textContent = formatFileSize(file.size);
                    uploadPlaceholder.classList.add('d-none');
                    fileSelected.classList.remove('d-none');
                    fileReady = true;
                    updateImportButton();
                }

                // Format file size
                function formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                }

                // Form submission validation
                importForm.addEventListener('submit', function(e) {
                    if (!driveReady) {
                        e.preventDefault();
                        alert('Please select a drive or create a new one.');
                        return;
                    }
                    importBtn.disabled = true;
                    importBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Importing...';
                });
            });
        </script>
    @endpush
</x-app-layout>
