<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Edit Lead') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">{{ $lead->full_name }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ orgRoute('admin.crm.show', $lead) }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Back to Lead
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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

            {{-- Display all validation errors at the top --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <h6 class="alert-heading"><i class="fa fa-exclamation-triangle me-2"></i>Please fix the following
                        errors:</h6>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ orgRoute('admin.crm.update', $lead) }}">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="mb-0 text-dark dark:text-gray-100">
                            <i class="fa fa-user me-2 text-primary"></i>Basic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-dark dark:text-gray-100">First Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="first_name" maxlength="255"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name', $lead->first_name) }}" required>
                                <div class="invalid-feedback" data-validation="first_name"></div>
                                @error('first_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark dark:text-gray-100">Last Name</label>
                                <input type="text" name="last_name" maxlength="255"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name', $lead->last_name) }}">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark dark:text-gray-100">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" maxlength="255"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('email') is-invalid @enderror"
                                    value="{{ old('email', $lead->email) }}" required>
                                <div class="invalid-feedback" data-validation="email"></div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark dark:text-gray-100">Phone</label>
                                <input type="text" name="phone" maxlength="20"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $lead->phone) }}">
                                <div class="invalid-feedback" data-validation="phone"></div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Information -->
                <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="mb-0 text-dark dark:text-gray-100">
                            <i class="fa fa-building me-2 text-primary"></i>Company Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-dark dark:text-gray-100">Company</label>
                                <input type="text" name="company" maxlength="255"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('company') is-invalid @enderror"
                                    value="{{ old('company', $lead->company) }}">
                                @error('company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark dark:text-gray-100">Job Title</label>
                                <input type="text" name="job_title" maxlength="255"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('job_title') is-invalid @enderror"
                                    value="{{ old('job_title', $lead->job_title) }}">
                                @error('job_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark dark:text-gray-100">Year Founded</label>
                                <input type="number" name="year_founded" min="1901" max="{{ date('Y') }}"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('year_founded') is-invalid @enderror"
                                    value="{{ old('year_founded', $lead->year_founded) }}" placeholder="e.g., 2015">
                                @error('year_founded')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label text-dark dark:text-gray-100">Industry</label>
                                <select name="industry"
                                    class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('industry') is-invalid @enderror">
                                    <option value="">Select Industry</option>
                                    @foreach ($industries as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('industry', $lead->industry) === $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('industry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-dark dark:text-gray-100">Website</label>
                                <input type="url" name="website" maxlength="255"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('website') is-invalid @enderror"
                                    value="{{ old('website', $lead->website) }}" placeholder="https://example.com">
                                <div class="invalid-feedback" data-validation="website"></div>
                                @error('website')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted website-hint" style="display:none;">
                                    <i class="fa fa-info-circle me-1"></i>URL will be auto-formatted with https://
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="mb-0 text-dark dark:text-gray-100">
                            <i class="fa fa-map-marker-alt me-2 text-primary"></i>Location
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-dark dark:text-gray-100">Country</label>
                                <input type="text" name="country" maxlength="100"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('country') is-invalid @enderror"
                                    value="{{ old('country', $lead->country) }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark dark:text-gray-100">City</label>
                                <input type="text" name="city" maxlength="100"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('city') is-invalid @enderror"
                                    value="{{ old('city', $lead->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-dark dark:text-gray-100">Address</label>
                                <textarea name="address" rows="2" maxlength="500"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('address') is-invalid @enderror">{{ old('address', $lead->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lead Details -->
                <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="mb-0 text-dark dark:text-gray-100">
                            <i class="fa fa-tags me-2 text-primary"></i>Lead Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-dark dark:text-gray-100">Status <span
                                        class="text-danger">*</span></label>
                                <select name="status"
                                    class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('status') is-invalid @enderror"
                                    required>
                                    @foreach ($statuses as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('status', $lead->status) === $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark dark:text-gray-100">Source <span
                                        class="text-danger">*</span></label>
                                <select name="source"
                                    class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('source') is-invalid @enderror"
                                    required>
                                    @foreach ($sources as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('source', $lead->source) === $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark dark:text-gray-100">Priority <span
                                        class="text-danger">*</span></label>
                                <select name="priority"
                                    class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('priority') is-invalid @enderror"
                                    required>
                                    @foreach ($priorities as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('priority', $lead->priority) === $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark dark:text-gray-100">Estimated Value</label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">{{ $crmCurrency }}</span>
                                    <input type="number" step="0.01" min="0" max="9999999999999.99"
                                        name="estimated_value"
                                        class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('estimated_value') is-invalid @enderror"
                                        value="{{ old('estimated_value', $lead->estimated_value) }}">
                                </div>
                                <div class="invalid-feedback" data-validation="estimated_value"></div>
                                @error('estimated_value')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark dark:text-gray-100">Assigned To</label>
                                <select name="assigned_to"
                                    class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('assigned_to') is-invalid @enderror">
                                    <option value="">Unassigned</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark dark:text-gray-100">Next Follow Up</label>
                                <input type="datetime-local" name="next_follow_up"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('next_follow_up') is-invalid @enderror"
                                    value="{{ old('next_follow_up', $lead->next_follow_up ? $lead->next_follow_up->format('Y-m-d\TH:i') : '') }}">
                                @error('next_follow_up')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lost Reason (show only if status is lost) -->
                <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4" id="lostReasonCard"
                    style="{{ $leadStatuses->where('is_lost', true)->pluck('slug')->contains($lead->status) ? '' : 'display: none;' }}">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="mb-0 text-dark dark:text-gray-100">
                            <i class="fa fa-times-circle me-2 text-danger"></i>Lost Reason
                        </h5>
                    </div>
                    <div class="card-body">
                        <textarea name="lost_reason" rows="3" maxlength="500"
                            class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('lost_reason') is-invalid @enderror"
                            placeholder="Explain why this lead was lost...">{{ old('lost_reason', $lead->lost_reason) }}</textarea>
                        @error('lost_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Interested Services -->
                @unless (auth()->user()->organization_id)
                    <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                        <div class="card-header bg-transparent border-0 pt-4">
                            <h5 class="mb-0 text-dark dark:text-gray-100">
                                <i class="fa fa-briefcase me-2 text-primary"></i>Interested Services
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach ($services as $service)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="interested_services[]"
                                                value="{{ $service }}" id="service_{{ Str::slug($service) }}"
                                                {{ in_array($service, old('interested_services', $lead->interested_services ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label text-dark dark:text-gray-100"
                                                for="service_{{ Str::slug($service) }}">
                                                {{ $service }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endunless

                <!-- Requirements & Notes -->
                <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="mb-0 text-dark dark:text-gray-100">
                            <i class="fa fa-file-alt me-2 text-primary"></i>Requirements & Notes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label text-dark dark:text-gray-100">Requirements</label>
                                <textarea name="requirements" rows="4" maxlength="5000"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('requirements') is-invalid @enderror"
                                    placeholder="Describe the lead's requirements and needs...">{{ old('requirements', $lead->requirements) }}</textarea>
                                <div class="form-text text-muted"><span class="char-count"
                                        data-target="requirements">0</span>/5000 characters</div>
                                @error('requirements')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-dark dark:text-gray-100">Internal Notes</label>
                                <textarea name="notes" rows="3" maxlength="5000"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('notes') is-invalid @enderror"
                                    placeholder="Add any internal notes about this lead...">{{ old('notes', $lead->notes) }}</textarea>
                                <div class="form-text text-muted"><span class="char-count"
                                        data-target="notes">0</span>/5000 characters</div>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger" onclick="deleteLead()">
                        <i class="fa fa-trash me-2"></i>Delete Lead
                    </button>
                    <div class="d-flex gap-2">
                        <a href="{{ orgRoute('admin.crm.show', $lead) }}"
                            class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Update Lead
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Show/hide lost reason card based on status
            const lostSlugs = @json($leadStatuses->where('is_lost', true)->pluck('slug')->values());
            document.querySelector('[name="status"]').addEventListener('change', function() {
                const lostReasonCard = document.getElementById('lostReasonCard');
                if (lostSlugs.includes(this.value)) {
                    lostReasonCard.style.display = 'block';
                } else {
                    lostReasonCard.style.display = 'none';
                }
            });

            function deleteLead() {
                if (confirm('Are you sure you want to delete this lead? This action cannot be undone.')) {
                    fetch('{{ orgRoute('admin.crm.destroy', $lead) }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = '{{ orgRoute('admin.crm.index') }}';
                            } else {
                                alert(data.message || 'Failed to delete lead');
                            }
                        })
                        .catch(() => alert('Failed to delete lead'));
                }
            }

            // ── Real-time form validation ───────────────────────────
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');

                // ── Helper: show / clear validation state ──────────────
                function setInvalid(input, msg) {
                    input.classList.add('is-invalid');
                    input.classList.remove('is-valid');
                    const fb = input.closest('.col-md-6, .col-md-4, .col-md-8, .col-md-12')
                        ?.querySelector('[data-validation]');
                    if (fb) {
                        fb.textContent = msg;
                        fb.style.display = 'block';
                    }
                }

                function setValid(input) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                    const fb = input.closest('.col-md-6, .col-md-4, .col-md-8, .col-md-12')
                        ?.querySelector('[data-validation]');
                    if (fb) {
                        fb.textContent = '';
                        fb.style.display = 'none';
                    }
                }

                function clearState(input) {
                    input.classList.remove('is-invalid', 'is-valid');
                    const fb = input.closest('.col-md-6, .col-md-4, .col-md-8, .col-md-12')
                        ?.querySelector('[data-validation]');
                    if (fb) {
                        fb.textContent = '';
                        fb.style.display = 'none';
                    }
                }

                // ── Website: auto-prepend https:// on blur ─────────────
                const websiteInput = form.querySelector('[name="website"]');
                if (websiteInput) {
                    const websiteHint = document.querySelector('.website-hint');
                    websiteInput.addEventListener('focus', function() {
                        if (websiteHint) websiteHint.style.display = 'block';
                    });
                    websiteInput.addEventListener('blur', function() {
                        let val = this.value.trim();
                        if (val && !/^https?:\/\//i.test(val)) {
                            val = 'https://' + val;
                            this.value = val;
                        }
                        if (websiteHint) websiteHint.style.display = 'none';
                        if (val) {
                            try {
                                new URL(val);
                                if (/^https?:\/\/.+\..+/.test(val)) {
                                    setValid(this);
                                } else {
                                    setInvalid(this, 'Please enter a valid URL (e.g. https://example.com)');
                                }
                            } catch (e) {
                                setInvalid(this, 'Please enter a valid URL (e.g. https://example.com)');
                            }
                        } else {
                            clearState(this);
                        }
                    });
                }

                // ── Email: validate format on blur ──────────────────────
                const emailInput = form.querySelector('[name="email"]');
                if (emailInput) {
                    emailInput.addEventListener('input', function() {
                        this.value = this.value.trim().toLowerCase();
                    });
                    emailInput.addEventListener('blur', function() {
                        const val = this.value.trim();
                        if (val) {
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (emailRegex.test(val)) {
                                setValid(this);
                            } else {
                                setInvalid(this, 'Please enter a valid email address.');
                            }
                        } else {
                            setInvalid(this, 'Email is required.');
                        }
                    });
                }

                // ── First Name: required check on blur ──────────────────
                const firstNameInput = form.querySelector('[name="first_name"]');
                if (firstNameInput) {
                    firstNameInput.addEventListener('blur', function() {
                        if (!this.value.trim()) {
                            setInvalid(this, 'First name is required.');
                        } else if (this.value.length > 255) {
                            setInvalid(this, 'First name must not exceed 255 characters.');
                        } else {
                            setValid(this);
                        }
                    });
                }

                // ── Phone: allow only digits, +, -, spaces, () ──────────
                const phoneInput = form.querySelector('[name="phone"]');
                if (phoneInput) {
                    phoneInput.addEventListener('input', function() {
                        this.value = this.value.replace(/[^\d+\-\(\)\s]/g, '');
                    });
                    phoneInput.addEventListener('blur', function() {
                        const val = this.value.trim();
                        if (val) {
                            if (val.length > 20) {
                                setInvalid(this, 'Phone number must not exceed 20 characters.');
                            } else if (val.replace(/[\s\-\(\)]/g, '').length < 7) {
                                setInvalid(this, 'Phone number seems too short.');
                            } else {
                                setValid(this);
                            }
                        } else {
                            clearState(this);
                        }
                    });
                }

                // ── Year Founded: range check ───────────────────────────
                const yearInput = form.querySelector('[name="year_founded"]');
                if (yearInput) {
                    yearInput.addEventListener('blur', function() {
                        const val = this.value.trim();
                        if (val) {
                            const year = parseInt(val);
                            const currentYear = new Date().getFullYear();
                            if (isNaN(year) || year < 1901 || year > currentYear) {
                                setInvalid(this, 'Year must be between 1901 and ' + currentYear + '.');
                            } else {
                                setValid(this);
                            }
                        } else {
                            clearState(this);
                        }
                    });
                }

                // ── Estimated Value: range check ────────────────────────
                const estValueInput = form.querySelector('[name="estimated_value"]');
                if (estValueInput) {
                    estValueInput.addEventListener('blur', function() {
                        const val = this.value.trim();
                        if (val) {
                            const num = parseFloat(val);
                            if (isNaN(num) || num < 0) {
                                setInvalid(this, 'Value must be a positive number.');
                            } else if (num > 9999999999999.99) {
                                setInvalid(this, 'Value is too large. Maximum is 9,999,999,999,999.99.');
                            } else {
                                setValid(this);
                            }
                        } else {
                            clearState(this);
                        }
                    });
                }

                // ── Generic max-length fields: validate on input ────────
                const maxLenFields = form.querySelectorAll('[maxlength]');
                maxLenFields.forEach(function(field) {
                    field.addEventListener('input', function() {
                        const max = parseInt(this.getAttribute('maxlength'));
                        if (this.value.length > max) {
                            this.value = this.value.substring(0, max);
                        }
                    });
                });

                // ── Character counters for textareas ────────────────────
                document.querySelectorAll('.char-count').forEach(function(counter) {
                    const target = form.querySelector('[name="' + counter.dataset.target + '"]');
                    if (target) {
                        counter.textContent = target.value.length;
                        target.addEventListener('input', function() {
                            counter.textContent = this.value.length;
                        });
                    }
                });

                // ── Address max-length feedback ─────────────────────────
                const addressField = form.querySelector('[name="address"]');
                if (addressField) {
                    addressField.addEventListener('blur', function() {
                        if (this.value.length > 500) {
                            setInvalid(this, 'Address must not exceed 500 characters.');
                        } else {
                            clearState(this);
                        }
                    });
                }

                // ── Form submit: final check ────────────────────────────
                form.addEventListener('submit', function(e) {
                    let hasError = false;

                    const firstName = form.querySelector('[name="first_name"]');
                    if (firstName && !firstName.value.trim()) {
                        setInvalid(firstName, 'First name is required.');
                        hasError = true;
                    }

                    const email = form.querySelector('[name="email"]');
                    if (email) {
                        const val = email.value.trim();
                        if (!val) {
                            setInvalid(email, 'Email is required.');
                            hasError = true;
                        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                            setInvalid(email, 'Please enter a valid email address.');
                            hasError = true;
                        }
                    }

                    // Auto-fix website before submit
                    const website = form.querySelector('[name="website"]');
                    if (website) {
                        let val = website.value.trim();
                        if (val && !/^https?:\/\//i.test(val)) {
                            val = 'https://' + val;
                            website.value = val;
                        }
                    }

                    // Check estimated value
                    const estVal = form.querySelector('[name="estimated_value"]');
                    if (estVal && estVal.value.trim()) {
                        const num = parseFloat(estVal.value);
                        if (isNaN(num) || num < 0 || num > 9999999999999.99) {
                            setInvalid(estVal, 'Estimated value must be between 0 and 9,999,999,999,999.99.');
                            hasError = true;
                        }
                    }

                    if (hasError) {
                        e.preventDefault();
                        const firstErr = form.querySelector('.is-invalid');
                        if (firstErr) firstErr.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
