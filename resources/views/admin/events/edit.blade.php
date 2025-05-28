<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.events.update', $event->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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

                        <div class="mb-5">
                            @if ($event->image)
                                <label class="text-nowrap pr-3 mb-1">Replace Image</label>
                            @else
                                <label class="text-nowrap pr-3 mb-1">Event Image</label>
                            @endif
                            <input type="file" id="image" name="image"
                                class="form-control border border-1 border-dark p-2" accept="image/*">
                        </div>

                        @if ($event->image)
                            <div class="mb-2 position-relative" id="previewContainer" style="max-width: 300px;">
                                <img id="imagePreview" src="{{ asset('storage/' . $event->image) }}"
                                    class="img-thumbnail" alt="Current Image">

                                <!-- Remove Button -->
                                {{-- <button type="button" id="removeImageBtn"
                                    style="
                position: absolute; 
                top: 5px; 
                right: 5px; 
                background: rgba(0,0,0,0.6); 
                color: white; 
                border: none; 
                border-radius: 50%; 
                width: 25px; 
                height: 25px; 
                line-height: 20px;
                font-size: 18px;
                cursor: pointer;"
                                    title="Remove image">
                                    &times;
                                </button> --}}
                            </div>

                            <div class="form-check mt-1 mb-5">
                                <input type="checkbox" name="remove_image" value="1" class="form-check-input"
                                    id="removeImageCheck">
                                <label class="form-check-label" for="removeImageCheck">Remove current image</label>
                            </div>
                        @else
                            <div class="mb-5 position-relative" id="previewContainer"
                                style="max-width: 300px; display: none;">
                                <img id="imagePreview" src="#" class="img-thumbnail" alt="Current Image">

                                <button type="button" id="removeImageBtn"
                                    style="
                position: absolute; 
                top: 5px; 
                right: 5px; 
                background: rgba(0,0,0,0.6); 
                color: white; 
                border: none; 
                border-radius: 50%; 
                width: 25px; 
                height: 25px; 
                line-height: 20px;
                font-size: 18px;
                cursor: pointer;"
                                    title="Remove image">
                                    &times;
                                </button>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-success">Update Event</button>
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
                    previewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.setAttribute('src', '#');
            previewContainer.style.display = 'none';
        });
    }
</script>
