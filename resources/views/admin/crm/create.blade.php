<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Add New Lead') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Create a new lead in your CRM</p>
            </div>
            <a href="{{ orgRoute('admin.crm.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-2"></i>Back to Leads
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ orgRoute('admin.crm.store') }}">
                @csrf

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
                                <input type="text" name="first_name"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark dark:text-gray-100">Last Name</label>
                                <input type="text" name="last_name"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name') }}">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark dark:text-gray-100">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark dark:text-gray-100">Phone</label>
                                <input type="text" name="phone"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
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
                                <input type="text" name="company"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('company') is-invalid @enderror"
                                    value="{{ old('company') }}">
                                @error('company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark dark:text-gray-100">Job Title</label>
                                <input type="text" name="job_title"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('job_title') is-invalid @enderror"
                                    value="{{ old('job_title') }}">
                                @error('job_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark dark:text-gray-100">Year Founded</label>
                                <input type="number" name="year_founded" min="1901" max="{{ date('Y') }}"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('year_founded') is-invalid @enderror"
                                    value="{{ old('year_founded') }}" placeholder="e.g., 2015">
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
                                            {{ old('industry') === $key ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('industry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-dark dark:text-gray-100">Website</label>
                                <input type="url" name="website"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('website') is-invalid @enderror"
                                    value="{{ old('website') }}" placeholder="https://example.com">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                <input type="text" name="country"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('country') is-invalid @enderror"
                                    value="{{ old('country') }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark dark:text-gray-100">City</label>
                                <input type="text" name="city"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('city') is-invalid @enderror"
                                    value="{{ old('city') }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-dark dark:text-gray-100">Address</label>
                                <textarea name="address" rows="2"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
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
                                            {{ old('status', $leadStatuses->firstWhere('is_default', true)?->slug ?? 'new') === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
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
                                            {{ old('source', 'website') === $key ? 'selected' : '' }}>
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
                                            {{ old('priority', 'medium') === $key ? 'selected' : '' }}>
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
                                        class="input-group-text dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">$</span>
                                    <input type="number" step="0.01" min="0" name="estimated_value"
                                        class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('estimated_value') is-invalid @enderror"
                                        value="{{ old('estimated_value') }}">
                                </div>
                                @error('estimated_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark dark:text-gray-100">Assigned To</label>
                                <select name="assigned_to"
                                    class="form-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('assigned_to') is-invalid @enderror">
                                    <option value="">Unassigned</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
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
                                    value="{{ old('next_follow_up') }}">
                                @error('next_follow_up')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interested Services -->
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
                                            {{ in_array($service, old('interested_services', [])) ? 'checked' : '' }}>
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
                                <textarea name="requirements" rows="4"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('requirements') is-invalid @enderror"
                                    placeholder="Describe the lead's requirements and needs...">{{ old('requirements') }}</textarea>
                                @error('requirements')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-dark dark:text-gray-100">Internal Notes</label>
                                <textarea name="notes" rows="3"
                                    class="form-control dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('notes') is-invalid @enderror"
                                    placeholder="Add any internal notes about this lead...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ orgRoute('admin.crm.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-2"></i>Create Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
