<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.events.index') }}"
                class="mr-4 text-gray-600 hover:text-gray-900 transition-colors duration-200">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                <i class="fas fa-plus-circle mr-3 text-blue-600"></i>{{ __('Create New Event') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">

                    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <!-- Form Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-lg mb-8">
                            <h3 class="text-lg font-semibold mb-2">Event Information</h3>
                            <p class="text-blue-100">Fill in the details below to create your event</p>
                        </div>

                        <!-- Basic Information Section -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-info-circle mr-2 text-blue-600"></i>Basic Information
                            </h4>

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Title *</label>
                                    <input name="title" type="text"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                        placeholder="Enter a compelling event title" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                                    <input name="subtitle" type="text"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                        placeholder="Optional subtitle or tagline">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                                    <textarea name="description" rows="5"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                        placeholder="Describe your event in detail..." required></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule & Location Section -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-calendar-alt mr-2 text-green-600"></i>Schedule & Location
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Date *</label>
                                    <input type="date" name="event_date"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                        required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Time *</label>
                                    <input type="time" name="event_time"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                        required>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Venue *</label>
                                    <input name="venue" type="text"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                        placeholder="e.g., Google Meet, Conference Room A, or Physical Address"
                                        required>
                                </div>
                            </div>
                        </div>

                        <!-- Display Settings Section -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-cog mr-2 text-purple-600"></i>Display Settings
                            </h4>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Event Display Page *</label>
                                <select name="display"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    required>
                                    <option value="open_to_all">Open to All (Event + BRN)</option>
                                    <option value="event_only" selected>Event Page Only</option>
                                    <option value="brn_only">BRN Page Only</option>
                                </select>
                            </div>
                        </div>

                        <!-- Visibility & Access Section -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-eye mr-2 text-indigo-600"></i>Visibility & Access
                            </h4>

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="visibility" class="block text-sm font-medium text-gray-700 mb-2">Event
                                        Visibility *</label>
                                    <select name="visibility" id="visibility"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                        required>
                                        <option value="public">🌐 Public - Anyone can access</option>
                                        <option value="private">🔒 Private - Invitation only</option>
                                    </select>
                                </div>

                                <div id="link-input-wrapper">
                                    <label for="link" class="block text-sm font-medium text-gray-700 mb-2">Event
                                        Link</label>
                                    <input type="url" name="link" id="link"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                        placeholder="https://example.com/meeting-link">
                                    <p class="text-sm text-gray-500 mt-1">Meeting link or registration URL for
                                        participants</p>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Fields Selection -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-clipboard-list mr-2 text-orange-600"></i>Booking Form Fields
                            </h4>
                            <p class="text-gray-600 mb-6">Select which information you'd like to collect from attendees
                                when they book a seat</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div
                                        class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                                        <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            type="checkbox" name="booking_fields[]" value="name" id="field_name"
                                            checked>
                                        <label class="ml-3 text-sm font-medium text-gray-700 cursor-pointer"
                                            for="field_name">
                                            <i class="fas fa-user mr-2 text-blue-500"></i>Full Name
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            type="checkbox" name="booking_fields[]" value="email" id="field_email"
                                            checked>
                                        <label class="ml-3 text-sm font-medium text-gray-700 cursor-pointer"
                                            for="field_email">
                                            <i class="fas fa-envelope mr-2 text-green-500"></i>Email Address
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            type="checkbox" name="booking_fields[]" value="phone" id="field_phone">
                                        <label class="ml-3 text-sm font-medium text-gray-700 cursor-pointer"
                                            for="field_phone">
                                            <i class="fas fa-phone mr-2 text-purple-500"></i>Phone Number
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            type="checkbox" name="booking_fields[]" value="job_title"
                                            id="field_job_title">
                                        <label class="ml-3 text-sm font-medium text-gray-700 cursor-pointer"
                                            for="field_job_title">
                                            <i class="fas fa-briefcase mr-2 text-indigo-500"></i>Job Title
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            type="checkbox" name="booking_fields[]" value="organization"
                                            id="field_organization">
                                        <label class="ml-3 text-sm font-medium text-gray-700 cursor-pointer"
                                            for="field_organization">
                                            <i class="fas fa-building mr-2 text-teal-500"></i>Organization
                                        </label>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div
                                        class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            type="checkbox" name="booking_fields[]" value="location"
                                            id="field_location">
                                        <label class="ml-3 text-sm font-medium text-gray-700 cursor-pointer"
                                            for="field_location">
                                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Location
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            type="checkbox" name="booking_fields[]" value="industry"
                                            id="field_industry">
                                        <label class="ml-3 text-sm font-medium text-gray-700 cursor-pointer"
                                            for="field_industry">
                                            <i class="fas fa-industry mr-2 text-yellow-500"></i>Industry
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            type="checkbox" name="booking_fields[]" value="expectations"
                                            id="field_expectations">
                                        <label class="ml-3 text-sm font-medium text-gray-700 cursor-pointer"
                                            for="field_expectations">
                                            <i class="fas fa-lightbulb mr-2 text-orange-500"></i>Expectations
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            type="checkbox" name="booking_fields[]" value="referral_source"
                                            id="field_referral_source">
                                        <label class="ml-3 text-sm font-medium text-gray-700 cursor-pointer"
                                            for="field_referral_source">
                                            <i class="fas fa-share-alt mr-2 text-pink-500"></i>How did you hear about
                                            us?
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            <strong>Tip:</strong> Name and Email are selected by default as they're
                                            typically required for most events. You can deselect them if needed.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Event Image Section -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-image mr-2 text-pink-600"></i>Event Image
                            </h4>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Event
                                        Image</label>
                                    <div
                                        class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors duration-200">
                                        <div class="space-y-1 text-center">
                                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="image"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload a file</span>
                                                    <input id="image" name="image" type="file"
                                                        class="sr-only" accept="image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                        </div>
                                    </div>
                                </div>

                                <div id="previewContainer" class="hidden">
                                    <div class="relative inline-block">
                                        <img id="imagePreview" src="#" alt="Image Preview"
                                            class="max-w-sm rounded-lg shadow-md">
                                        <button type="button" id="removeImageBtn"
                                            class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors duration-200"
                                            title="Remove image">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between pt-6">
                            <a href="{{ route('admin.events.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Cancel
                            </a>
                            <button type="submit"
                                class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-8 py-3 rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i>Create Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    // Visibility
    document.addEventListener('DOMContentLoaded', () => {
        const visibility = document.getElementById('visibility');
        const linkWrapper = document.getElementById('link-input-wrapper');

        function toggleLinkInput() {
            if (visibility.value === 'public') {
                linkWrapper.style.display = 'block';
            } else {
                linkWrapper.style.display = 'none';
            }
        }

        visibility.addEventListener('change', toggleLinkInput);
        toggleLinkInput();
    });


    // Display Images
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageBtn = document.getElementById('removeImageBtn');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(event) {
                imagePreview.setAttribute('src', event.target.result);
                previewContainer.classList.remove('hidden');
            }

            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
            imagePreview.setAttribute('src', '#');
        }
    });

    removeImageBtn.addEventListener('click', function() {
        imageInput.value = '';
        previewContainer.classList.add('hidden');
        imagePreview.setAttribute('src', '#');
    });
</script>
