<x-app-layout>
    <x-admin-dark-mode />

    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.events.index') }}"
                class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors duration-200">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight dark-transition">
                <i class="fas fa-edit mr-3 text-blue-600"></i>{{ __('Edit Event') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <form action="{{ route('admin.events.update', $event->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Form Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Edit Event Information</h3>
                            <p class="text-blue-100">Update the details below to modify your event</p>
                        </div>

                        <!-- Basic Information Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                <i class="fas fa-info-circle mr-2 text-blue-600"></i>Basic Information
                            </h4>

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event
                                        Title *</label>
                                    <input type="text" name="title"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        value="{{ old('title', $event->title) }}" required>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subtitle</label>
                                    <input type="text" name="subtitle"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        value="{{ old('subtitle', $event->subtitle) }}">
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description
                                        *</label>
                                    <textarea name="description" rows="6"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        required>{!! old('description', $event->description) !!}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule & Location Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                <i class="fas fa-calendar-alt mr-2 text-green-600"></i>Schedule & Location
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event
                                        Date *</label>
                                    <input type="date" name="event_date"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        value="{{ old('event_date', $event->event_date) }}" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event
                                        Time *</label>
                                    <input type="time" name="event_time"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        value="{{ old('event_time', $event->event_time) }}" required>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Venue
                                        *</label>
                                    <input name="venue" type="text"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        value="{{ old('venue', $event->venue) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Display Settings Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                <i class="fas fa-cog mr-2 text-purple-600"></i>Display Settings
                            </h4>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event
                                    Display Page *</label>
                                <select name="display"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                    required>
                                    <option value="{{ old('display', $event->display) }}">
                                        {{ str_replace('_', ' ', $event->display) }}</option>
                                    <option value="open_to_all">Open to All (Event + BRN)</option>
                                    <option value="event_only">Event Page Only</option>
                                    <option value="brn_only">BRN Page Only</option>
                                </select>
                            </div>
                        </div>

                        <!-- Visibility & Access Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                <i class="fas fa-eye mr-2 text-indigo-600"></i>Event Link
                            </h4>

                            @if ($event->visibility === 'public')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event
                                        Link</label>
                                    <input type="url" name="link"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        value="{{ old('link', $event->link) }}">
                                </div>
                            @else
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event
                                        Link (Private Event)</label>
                                    <input type="url" name=""
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-400"
                                        value="{{ old('link', $event->link) }}" disabled>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Private events don't
                                        require external links</p>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control"
                                value="{{ old('title', $event->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Subtitle</label>
                            <input type="text" name="subtitle" class="form-control"
                                value="{{ old('subtitle', $event->subtitle) }}">
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" id="description" rows="6" required>{!! old('description', $event->description) !!}</textarea>
                        </div>

                        @if ($event->visibility === 'public')
                            <div class="mb-3">
                                <label>Event Link</label>
                                <input type="url" name="link" class="form-control"
                                    value="{{ old('link', $event->link) }}">
                            </div>
                        @else
                            <div class="mb-3">
                                <label>Event Link</label>
                                <input type="url" name="" class="form-control"
                                    value="{{ old('link', $event->link) }}" disabled>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label>Date</label>
                            <input type="date" name="event_date" class="form-control"
                                value="{{ old('event_date', $event->event_date) }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Time</label>
                            <input type="time" name="event_time" class="form-control"
                                value="{{ old('event_time', $event->event_time) }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Venue</label>
                            <input type="text" name="venue" class="form-control"
                                value="{{ old('venue', $event->venue) }}" required>
                        </div>

                        <!-- Booking Fields Selection -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                <i class="fas fa-clipboard-list mr-2 text-orange-600"></i>Booking Form Fields
                            </h4>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">Select which information you'd like to
                                collect from attendees when they book a seat</p>

                            @php
                                $selectedFields = old('booking_fields', $event->getBookingFields());
                                $availableFields = App\Models\Event::getAvailableBookingFields();
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="name"
                                            id="field_name_edit"
                                            {{ in_array('name', $selectedFields) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_name_edit">
                                            <i class="fas fa-user mr-2 text-blue-500"></i>Full Name
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="email"
                                            id="field_email_edit"
                                            {{ in_array('email', $selectedFields) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_email_edit">
                                            <i class="fas fa-envelope mr-2 text-green-500"></i>Email Address
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="phone"
                                            id="field_phone_edit"
                                            {{ in_array('phone', $selectedFields) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_phone_edit">
                                            <i class="fas fa-phone mr-2 text-purple-500"></i>Phone Number
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="job_title"
                                            id="field_job_title_edit"
                                            {{ in_array('job_title', $selectedFields) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_job_title_edit">
                                            <i class="fas fa-briefcase mr-2 text-indigo-500"></i>Job Title
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="organization"
                                            id="field_organization_edit"
                                            {{ in_array('organization', $selectedFields) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_organization_edit">
                                            <i class="fas fa-building mr-2 text-teal-500"></i>Organization
                                        </label>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="location"
                                            id="field_location_edit"
                                            {{ in_array('location', $selectedFields) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_location_edit">
                                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Location
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="industry"
                                            id="field_industry_edit"
                                            {{ in_array('industry', $selectedFields) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_industry_edit">
                                            <i class="fas fa-industry mr-2 text-yellow-500"></i>Industry
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="expectations"
                                            id="field_expectations_edit"
                                            {{ in_array('expectations', $selectedFields) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_expectations_edit">
                                            <i class="fas fa-lightbulb mr-2 text-orange-500"></i>Expectations
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="referral_source"
                                            id="field_referral_source_edit"
                                            {{ in_array('referral_source', $selectedFields) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_referral_source_edit">
                                            <i class="fas fa-share-alt mr-2 text-pink-500"></i>How did you hear about
                                            us?
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mt-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700 dark:text-blue-300">
                                            <strong>Tip:</strong> You can modify which fields are required for this
                                            event. Changes will apply to new bookings only.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Event Image Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                <i class="fas fa-image mr-2 text-pink-600"></i>Event Image
                            </h4>

                            <div class="space-y-4">
                                @if ($event->image)
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('storage/' . $event->image) }}"
                                                alt="Current Event Image"
                                                class="w-24 h-24 rounded-lg object-cover border-2 border-gray-200 dark:border-gray-600">
                                        </div>
                                        <div class="flex-1">
                                            <h5 class="text-sm font-medium text-gray-900 dark:text-white">Current Image
                                            </h5>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Upload a new image to
                                                replace the current one</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" name="remove_image" value="1"
                                            id="removeImageCheck"
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 dark:border-gray-600 rounded">
                                        <label for="removeImageCheck"
                                            class="ml-2 text-sm text-red-600 dark:text-red-400">
                                            <i class="fas fa-trash mr-1"></i>Remove current image
                                        </label>
                                    </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        @if ($event->image)
                                            Replace Image
                                        @else
                                            Upload Event Image
                                        @endif
                                    </label>
                                    <div
                                        class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-blue-400 dark:hover:border-blue-500 transition-colors duration-200">
                                        <div class="space-y-1 text-center">
                                            <i
                                                class="fas fa-cloud-upload-alt text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                                <label for="image"
                                                    class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload a file</span>
                                                    <input id="image" name="image" type="file"
                                                        class="sr-only" accept="image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB
                                            </p>
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
                                class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Cancel
                            </a>
                            <button type="submit"
                                class="bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i>Update Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageBtn = document.getElementById('removeImageBtn');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.setAttribute('src', event.target.result);
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.setAttribute('src', '#');
            previewContainer.classList.add('hidden');
        });
    }

    // Add dynamic booking fields functionality
    document.addEventListener('DOMContentLoaded', function() {
        loadBookingFields();
    });

    let bookingFieldsData = [];

    function loadBookingFields() {
        fetch('/admin/events/booking-fields')
            .then(response => response.json())
            .then(data => {
                bookingFieldsData = data;
                updateBookingFieldsDisplay();
            })
            .catch(error => {
                console.error('Error loading booking fields:', error);
            });
    }

    function updateBookingFieldsDisplay() {
        const container = document.getElementById('booking-fields-container');
        if (!container) return;

        container.innerHTML = '';

        // Get currently selected fields
        const currentFields = @json($event->booking_fields ?? []);

        bookingFieldsData.forEach(field => {
            const isChecked = currentFields.some(selected => selected.field === field.field);
            const isRequired = currentFields.find(selected => selected.field === field.field)?.required ||
            false;

            const fieldHtml = `
                <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="field_${field.field}" 
                                   name="booking_fields[]" 
                                   value="${field.field}"
                                   ${isChecked ? 'checked' : ''}
                                   onchange="toggleFieldRequired('${field.field}')"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                            <label for="field_${field.field}" class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                ${field.label}
                            </label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-xs text-gray-600 dark:text-gray-400">Required:</label>
                            <input type="checkbox" 
                                   id="required_${field.field}" 
                                   name="required_fields[]" 
                                   value="${field.field}"
                                   ${isRequired ? 'checked' : ''}
                                   ${!isChecked ? 'disabled' : ''}
                                   class="h-3 w-3 text-red-600 focus:ring-red-500 border-gray-300 dark:border-gray-600 rounded">
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">${field.description}</p>
                </div>
            `;
            container.innerHTML += fieldHtml;
        });
    }

    function toggleFieldRequired(fieldName) {
        const fieldCheckbox = document.getElementById(`field_${fieldName}`);
        const requiredCheckbox = document.getElementById(`required_${fieldName}`);

        if (fieldCheckbox && requiredCheckbox) {
            requiredCheckbox.disabled = !fieldCheckbox.checked;
            if (!fieldCheckbox.checked) {
                requiredCheckbox.checked = false;
            }
        }
    }
</script>
