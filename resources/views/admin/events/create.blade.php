<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input name="title" class="form-control mb-2 border border-1 border-dark"
                            placeholder="Event Title" required><br>
                        <input name="subtitle" class="form-control mb-2 border border-1 border-dark"
                            placeholder="Subtitle (optional)"><br>
                        <textarea name="description" id="" class="form-control mb-2 border border-1 border-dark" rows="5"
                            placeholder="Description" required></textarea><br>
                        {{-- <input type="url" name="link" class="form-control mb-2 border border-1 border-dark"
                            placeholder="Event Link" required><br> --}}
                        <input type="date" name="event_date" class="form-control mb-2 border border-1 border-dark"
                            required><br>
                        <input type="time" name="event_time" class="form-control mb-2 border border-1 border-dark"
                            required><br>
                        <input name="venue" class="form-control mb-2 border border-1 border-dark"
                            placeholder="Venue: e.g Google meet or a Physical location"><br>

                        <!-- Visibility Selector -->
                        <div class="mb-4">
                            <label for="visibility">Visibility</label>
                            <select name="visibility" class="form-control" id="visibility" required>
                                <option value="public"
                                    {{ old('visibility', $event->visibility ?? '') == 'public' ? 'selected' : '' }}>
                                    Public</option>
                                <option value="private"
                                    {{ old('visibility', $event->visibility ?? '') == 'private' ? 'selected' : '' }}>
                                    Private</option>
                            </select>
                        </div>

                        <!-- Event Link (only if public) -->
                        <div class="mb-4" id="link-input-wrapper">
                            <label for="link">Event Link</label>
                            <input type="url" name="link" class="form-control"
                                value="{{ old('link', $event->link ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="text-nowrap pr-3 mb-1">Event Image</label>
                            <input type="file" name="image" id="image"
                                class="form-control border border-1 border-dark p-2" accept="image/*">
                        </div>
                        <div class="mb-5 position-relative" id="previewContainer"
                            style="max-width: 300px; display: none;">
                            <img id="imagePreview" src="#" alt="Image Preview" class="img-thumbnail">

                            <!-- Cancel icon -->
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
                        <button type="submit" class="btn btn-dark w-25">Submit</button>
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
                previewContainer.style.display = 'block';
            }

            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
            imagePreview.setAttribute('src', '#');
        }
    });

    removeImageBtn.addEventListener('click', function() {
        imageInput.value = '';
        previewContainer.style.display = 'none';
        imagePreview.setAttribute('src', '#');
    });
</script>
