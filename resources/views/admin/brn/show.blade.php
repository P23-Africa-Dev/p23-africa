<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('NOEL Application Details') }}
            </h2>
            <a href="{{ route('admin.brn.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Application Status -->
                    <div class="mb-6">
                        <span class="text-sm font-medium text-gray-500">Application Status:</span>
                        <span
                            class="ml-2 px-3 py-1 rounded-full text-sm font-medium
                            @if ($submission->status === 'approved') bg-green-100 text-green-800
                            @elseif($submission->status === 'declined') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($submission->status) }}
                        </span>
                    </div>

                    <!-- Personal Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $submission->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    <a href="mailto:{{ $submission->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $submission->email ?? 'N/A' }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $submission->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Position/Title</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $submission->position ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn Profile</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    @if ($submission->linkedin)
                                        <a href="{{ $submission->linkedin }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-800">
                                            {{ $submission->linkedin }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $submission->country ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Company Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Company Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    {{ $submission->company_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Registration Number</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    {{ $submission->reg_number ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Company Website</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    @if ($submission->website)
                                        <a href="{{ $submission->website }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-800">
                                            {{ $submission->website }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Year Established</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    {{ $submission->year_established ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Industry</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $submission->industry ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Categories</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    {{ $submission->categories ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Years of Operation</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    {{ $submission->years_of_operation ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Number of Employees</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    {{ $submission->number_of_employees ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Offer Value</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    {{ $submission->offer_value ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">B2B Interest</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    @if ($submission->b2b_interest)
                                        <span class="capitalize">{{ $submission->b2b_interest }}</span>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Goals and Outcomes -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Goals & Desired Outcomes</h3>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Goals</label>
                                <div class="text-gray-900 bg-gray-50 p-3 rounded-md min-h-[100px]">
                                    {{ $submission->goals ?? 'N/A' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Selected Outcome</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    {{ $submission->selected_outcome ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Application Metadata -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Application Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Submitted On</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    {{ $submission->created_at ? $submission->created_at->format('F j, Y \a\t g:i A') : 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">
                                    {{ $submission->updated_at ? $submission->updated_at->format('F j, Y \a\t g:i A') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t pt-6">
                        <div class="flex flex-wrap gap-3">
                            @if ($submission->status === 'pending')
                                <form action="{{ route('admin.brn.approve', $submission->id) }}" method="POST"
                                    style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check mr-2"></i>Approve Application
                                    </button>
                                </form>
                                <form action="{{ route('admin.brn.decline', $submission->id) }}" method="POST"
                                    style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-times mr-2"></i>Decline Application
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.brn.destroy', $submission->id) }}" method="POST"
                                style="display:inline"
                                onsubmit="return confirm('Are you sure you want to delete this application? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash mr-2"></i>Delete Application
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
