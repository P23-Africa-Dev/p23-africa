<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Resource Blog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ isset($blog) ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($blog))
                            @method('PUT')
                        @endif

                        <input name="title" value="{{ old('title', $blog->title ?? '') }}" placeholder="Blog Title"
                            required class="form-control mb-3 border border-1 border-dark">
                        <input name="subtitle" value="{{ old('subtitle', $blog->subtitle ?? '') }}"
                            placeholder="Blog Subtitle" class="form-control mb-3 border border-1 border-dark">

                        <textarea name="content_1" class="form-control mb-3 border border-1 border-dark" placeholder="First Content" rows="8" required>{{ old('content_1', $blog->content_1 ?? '') }}</textarea>

                        <input type="file" name="image" id="image" class="form-control p-2 mb-3 border border-1 border-dark" accept="image/*">
                        @if (isset($blog) && $blog->image_path)
                            <img src="{{ asset('storage/' . $blog->image_path) }}" width="200" class="mb-2">
                        @endif
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

                        <textarea name="content_2" class="form-control mb-3 border border-1 border-dark" rows="8" placeholder="Second Content">{{ old('content_2', $blog->content_2 ?? '') }}</textarea>

                        <button type="submit"
                            class="btn btn-dark w-25">{{ isset($blog) ? 'Update' : 'Publish' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
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
