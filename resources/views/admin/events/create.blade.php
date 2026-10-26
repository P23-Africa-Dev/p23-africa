<x-app-layout>
    <x-admin-dark-mode />

    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ orgRoute('admin.events.index') }}"
                class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors duration-200">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight dark-transition">
                <i class="fas fa-plus-circle mr-3 text-blue-600"></i>{{ __('Create New Event') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">

                    <form action="{{ orgRoute('admin.events.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <!-- Form Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 rounded-lg mb-8"
                            style="color: #fff !important;">
                            <h3 class="text-lg font-semibold mb-2" style="color: #fff !important;">Event Information
                            </h3>
                            <p style="color: rgba(191, 219, 254, 1) !important;">Fill in the details below to create
                                your event</p>
                        </div>

                        <!-- Basic Information Section -->
                        <div style="background-color: #f9fafb; padding: 1.5rem; border-radius: 0.5rem;"
                            class="dark:bg-gray-700">
                            <h4 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                                <i class="fas fa-info-circle mr-2" style="color: #2563eb !important;"></i>Basic
                                Information
                            </h4>

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event
                                        Title *</label>
                                    <input name="title" type="text"
                                        class="w-full px-4 py-3 border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        placeholder="Enter a compelling event title" value="{{ old('title') }}"
                                        required>
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subtitle</label>
                                    <input name="subtitle" type="text"
                                        class="w-full px-4 py-3 border {{ $errors->has('subtitle') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        placeholder="Optional subtitle or tagline" value="{{ old('subtitle') }}">
                                    @error('subtitle')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description
                                        *</label>
                                    <textarea name="description" rows="5"
                                        class="w-full px-4 py-3 border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        placeholder="Describe your event in detail..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Schedule & Location Section -->
                        <div style="background-color: #f9fafb; padding: 1.5rem; border-radius: 0.5rem;"
                            class="dark:bg-gray-700">
                            <h4 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                                <i class="fas fa-calendar-alt mr-2" style="color: #16a34a !important;"></i>Schedule &
                                Location
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event
                                        Date *</label>
                                    <input type="date" name="event_date"
                                        class="w-full px-4 py-3 border {{ $errors->has('event_date') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        value="{{ old('event_date') }}" required>
                                    @error('event_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event
                                        Time *</label>
                                    <input type="time" name="event_time"
                                        class="w-full px-4 py-3 border {{ $errors->has('event_time') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        value="{{ old('event_time') }}" required>
                                    @error('event_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Venue
                                        *</label>
                                    <input name="venue" type="text"
                                        class="w-full px-4 py-3 border {{ $errors->has('venue') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        placeholder="e.g., Google Meet, Conference Room A, or Physical Address"
                                        value="{{ old('venue') }}" required>
                                    @error('venue')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Display Settings Section -->
                        <div style="background-color: #f9fafb; padding: 1.5rem; border-radius: 0.5rem;"
                            class="dark:bg-gray-700">
                            <h4 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                                <i class="fas fa-cog mr-2" style="color: #9333ea !important;"></i>Display Settings
                            </h4>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event
                                    Display Page *</label>
                                <select name="display"
                                    class="w-full px-4 py-3 border {{ $errors->has('display') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                    required>
                                    <option value="open_to_all"
                                        {{ old('display') == 'open_to_all' ? 'selected' : '' }}>Open to All (Event +
                                        BRN)</option>
                                    <option value="event_only"
                                        {{ old('display') == 'event_only' || old('display') == null ? 'selected' : '' }}>
                                        Event Page Only</option>
                                    <option value="brn_only" {{ old('display') == 'brn_only' ? 'selected' : '' }}>BRN
                                        Page Only</option>
                                </select>
                                @error('display')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Visibility & Access Section -->
                        <div style="background-color: #f9fafb; padding: 1.5rem; border-radius: 0.5rem;"
                            class="dark:bg-gray-700">
                            <h4 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                                <i class="fas fa-eye mr-2" style="color: #4f46e5 !important;"></i>Visibility & Access
                            </h4>

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="visibility"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event
                                        Visibility *</label>
                                    <select name="visibility" id="visibility"
                                        class="w-full px-4 py-3 border {{ $errors->has('visibility') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        required>
                                        <option value="public"
                                            {{ old('visibility') == 'public' || old('visibility') == null ? 'selected' : '' }}>
                                            🌐 Public - Anyone can access</option>
                                        <option value="private"
                                            {{ old('visibility') == 'private' ? 'selected' : '' }}>
                                            🔒 Private - Invitation only</option>
                                    </select>
                                    @error('visibility')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div id="link-input-wrapper">
                                    <label for="link"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event
                                        Link</label>
                                    <input type="url" name="link" id="link"
                                        class="w-full px-4 py-3 border {{ $errors->has('link') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                        placeholder="https://example.com/meeting-link" value="{{ old('link') }}">
                                    @error('link')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Meeting link or
                                            registration URL for
                                            participants</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Booking Fields Selection -->
                        <div style="background-color: #f9fafb; padding: 1.5rem; border-radius: 0.5rem;"
                            class="dark:bg-gray-700">
                            <h4 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                                <i class="fas fa-clipboard-list mr-2" style="color: #ea580c !important;"></i>Booking
                                Form Fields
                            </h4>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">Select which information you'd like to
                                collect from attendees
                                when they book a seat</p>
                            @error('booking_fields')
                                <div
                                    class="mb-4 p-3 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 rounded-md">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="name" id="field_name"
                                            {{ in_array('name', old('booking_fields', [])) || old('booking_fields') === null ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_name">
                                            <i class="fas fa-user mr-2 text-blue-500"></i>Full Name
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="email" id="field_email"
                                            {{ in_array('email', old('booking_fields', [])) || old('booking_fields') === null ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_email">
                                            <i class="fas fa-envelope mr-2 text-green-500"></i>Email Address
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="phone" id="field_phone"
                                            {{ in_array('phone', old('booking_fields', [])) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_phone">
                                            <i class="fas fa-phone mr-2 text-purple-500"></i>Phone Number
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="job_title"
                                            id="field_job_title"
                                            {{ in_array('job_title', old('booking_fields', [])) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_job_title">
                                            <i class="fas fa-briefcase mr-2 text-indigo-500"></i>Job Title
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="organization"
                                            id="field_organization"
                                            {{ in_array('organization', old('booking_fields', [])) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_organization">
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
                                            id="field_location"
                                            {{ in_array('location', old('booking_fields', [])) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_location">
                                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Location
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="industry"
                                            id="field_industry"
                                            {{ in_array('industry', old('booking_fields', [])) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_industry">
                                            <i class="fas fa-industry mr-2 text-yellow-500"></i>Industry
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="expectations"
                                            id="field_expectations"
                                            {{ in_array('expectations', old('booking_fields', [])) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_expectations">
                                            <i class="fas fa-lightbulb mr-2 text-orange-500"></i>Expectations
                                        </label>
                                    </div>

                                    <div
                                        class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200">
                                        <input
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                            type="checkbox" name="booking_fields[]" value="referral_source"
                                            id="field_referral_source"
                                            {{ in_array('referral_source', old('booking_fields', [])) ? 'checked' : '' }}>
                                        <label
                                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                            for="field_referral_source">
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
                                            <strong>Tip:</strong> Name and Email are selected by default as they're
                                            typically required for most events. You can deselect them if needed.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Event Image Section -->
                        <div style="background-color: #f9fafb; padding: 1.5rem; border-radius: 0.5rem;"
                            class="dark:bg-gray-700">
                            <h4 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                                <i class="fas fa-image mr-2" style="color: #db2777 !important;"></i>Event Image
                            </h4>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload
                                        Event
                                        Image</label>
                                    <div
                                        class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 {{ $errors->has('image') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} border-dashed rounded-lg hover:border-blue-400 dark:hover:border-blue-500 transition-colors duration-200">
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
                                    @error('image')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
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

                        @include('admin.partials.content-translations', [
                            'translationFields' => [
                                'title' => 'Title',
                                'subtitle' => 'Subtitle',
                                'description' => 'Description',
                                'venue' => 'Venue',
                            ],
                        ])

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between pt-6">
                            <a href="{{ orgRoute('admin.events.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-6 py-3 rounded-lg font-medium transition-colors duration-200">
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

    // Auto-format URL for meeting link
    const linkInput = document.getElementById('link');
    if (linkInput) {
        linkInput.addEventListener('input', function() {
            let url = this.value.trim();

            // Auto-prepend https:// immediately if user starts typing without protocol
            if (url && !url.match(/^https?:\/\//i) && !url.includes('://')) {
                // Only add https:// if the user isn't in the middle of typing a protocol
                if (!url.startsWith('http') && !url.startsWith('ftp')) {
                    this.value = 'https://' + url;
                    // Move cursor to end
                    this.setSelectionRange(this.value.length, this.value.length);
                }
            }
        });

        // Also handle blur as backup
        linkInput.addEventListener('blur', function() {
            let url = this.value.trim();
            if (url && !url.match(/^https?:\/\//i)) {
                this.value = 'https://' + url;
            }
        });
    }
</script>
