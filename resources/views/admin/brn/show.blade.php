<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition">
                {{ __('NOEL Application Details') }}
            </h2>
            <a href="{{ route('admin.brn.index') }}"
                class="btn btn-secondary dark:bg-gray-600 dark:border-gray-600 dark:hover:bg-gray-700 dark-transition">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg dark-transition">
                <div class="p-6 text-dark dark:text-gray-100 dark-transition">
                    <!-- Application Status -->
                    <div class="mb-6">
                        <span class="text-sm font-medium text-theme-secondary dark-transition">Application
                            Status:</span>
                        <span
                            class="ms-2 px-3 py-1 rounded-pill text-sm font-medium
                            @if ($submission->status === 'approved') bg-success text-white dark:bg-green-600
                            @elseif($submission->status === 'declined') bg-danger text-white dark:bg-red-600
                            @else bg-warning text-dark dark:bg-yellow-600 dark:text-yellow-100 @endif dark-transition">
                            {{ ucfirst($submission->status) }}
                        </span>
                    </div>

                    <!-- Personal Information -->
                    <div class="mb-8">
                        <h3
                            class="text-lg font-semibold text-theme-primary mb-4 border-bottom pb-2 dark-transition border-theme">
                            Personal Information</h3>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Full Name</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->name ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Email Address</label>
                                <div class="form-control bg-theme-tertiary border-theme dark-transition">
                                    <a href="mailto:{{ $submission->email }}"
                                        class="text-primary dark:text-blue-400 dark-transition">
                                        {{ $submission->email ?? 'N/A' }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Phone Number</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->phone ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Position/Title</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->position ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">LinkedIn Profile</label>
                                <div class="form-control bg-theme-tertiary border-theme dark-transition">
                                    @if ($submission->linkedin)
                                        <a href="{{ $submission->linkedin }}" target="_blank"
                                            class="text-primary dark:text-blue-400 dark-transition">
                                            {{ $submission->linkedin }}
                                        </a>
                                    @else
                                        <span class="text-theme-primary dark-transition">N/A</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Country</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->country ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Company Information -->
                    <div class="mb-8">
                        <h3
                            class="text-lg font-semibold text-theme-primary mb-4 border-bottom pb-2 dark-transition border-theme">
                            Company Information</h3>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Company Name</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->company_name ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Registration
                                    Number</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->reg_number ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Company Website</label>
                                <div class="form-control bg-theme-tertiary border-theme dark-transition">
                                    @if ($submission->website)
                                        <a href="{{ $submission->website }}" target="_blank"
                                            class="text-primary dark:text-blue-400 dark-transition">
                                            {{ $submission->website }}
                                        </a>
                                    @else
                                        <span class="text-theme-primary dark-transition">N/A</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Year Established</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->year_established ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Industry</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->industry ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Categories</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->categories ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Years of
                                    Operation</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->years_of_operation ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Number of
                                    Employees</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->number_of_employees ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Offer Value</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->offer_value ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">B2B Interest</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    @if ($submission->b2b_interest)
                                        <span class="text-capitalize">{{ $submission->b2b_interest }}</span>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Goals and Outcomes -->
                    <div class="mb-8">
                        <h3
                            class="text-lg font-semibold text-theme-primary mb-4 border-bottom pb-2 dark-transition border-theme">
                            Goals & Desired Outcomes</h3>
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label text-theme-secondary dark-transition">Goals</label>
                                <div class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition"
                                    style="min-height: 100px; white-space: pre-wrap;">{{ $submission->goals ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-theme-secondary dark-transition">Selected Outcome</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->selected_outcome ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Application Metadata -->
                    <div class="mb-8">
                        <h3
                            class="text-lg font-semibold text-theme-primary mb-4 border-bottom pb-2 dark-transition border-theme">
                            Application Details</h3>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Submitted On</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->created_at ? $submission->created_at->format('F j, Y \a\t g:i A') : 'N/A' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-theme-secondary dark-transition">Last Updated</label>
                                <div
                                    class="form-control bg-theme-tertiary text-theme-primary border-theme dark-transition">
                                    {{ $submission->updated_at ? $submission->updated_at->format('F j, Y \a\t g:i A') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-top pt-4 border-theme dark-transition">
                        <div class="d-flex flex-wrap gap-3">
                            @if ($submission->status === 'pending')
                                <form action="{{ route('admin.brn.approve', $submission->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-success dark:bg-green-600 dark:border-green-600 dark:hover:bg-green-700 dark-transition">
                                        <i class="fas fa-check me-2"></i>Approve Application
                                    </button>
                                </form>
                                <form action="{{ route('admin.brn.decline', $submission->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-warning dark:bg-yellow-600 dark:border-yellow-600 dark:hover:bg-yellow-700 dark-transition">
                                        <i class="fas fa-times me-2"></i>Decline Application
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.brn.destroy', $submission->id) }}" method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this application? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="btn btn-danger dark:bg-red-600 dark:border-red-600 dark:hover:bg-red-700 dark-transition">
                                    <i class="fas fa-trash me-2"></i>Delete Application
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
