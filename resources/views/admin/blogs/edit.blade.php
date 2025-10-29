<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition">
            {{ __('Edit Resource Blog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg dark-transition">
                <div class="p-6 text-dark dark:text-gray-100 dark-transition">

                    @if (session('success'))
                        <div class="alert alert-success dark-transition">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger dark-transition">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger dark-transition">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ isset($blog) ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($blog))
                            @method('PUT')
                        @endif

                        <input name="title" value="{{ old('title', $blog->title ?? '') }}" placeholder="Blog Title"
                            required
                            class="form-control mb-4 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary">
                        <div class="mb-4">
                            <label for="created_at" class="text-theme-primary dark-transition">Date</label>
                            <input type="datetime-local" name="created_at" id="created_at"
                                class="form-control border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary"
                                value="{{ old('created_at', $blog->created_at ? $blog->created_at->format('Y-m-d\TH:i') : '') }}"
                                required>
                        </div>
                        <input name="subtitle" value="{{ old('subtitle', $blog->subtitle ?? '') }}"
                            placeholder="Blog Subtitle"
                            class="form-control mb-4 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary">

                        <select name="category_id"
                            class="form-control mb-3 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary">
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ isset($blog) && $blog->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <textarea name="content_1" id="description"
                            class="form-control mb-4 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary"
                            placeholder="First Content" required>{{ old('content_1', $blog->content_1 ?? '') }}</textarea>

                        <input type="file" name="image" id="image"
                            class="form-control p-2 mb-3 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary"
                            accept="image/*">
                        {{-- @if (isset($blog) && $blog->image_path)
                            <img src="{{ asset('storage/' . $blog->image_path) }}" width="200" class="mb-2">
                        @endif --}}

                        @if ($blog->image_path)
                            <div class="mb-2 position-relative" id="previewContainer" style="max-width: 300px;">
                                <img id="imagePreview" src="{{ asset('storage/' . $blog->image_path) }}"
                                    class="img-thumbnail border-theme dark-transition" alt="Current Image">
                            </div>
                            <div class="form-check mt-1 mb-5">
                                <input type="checkbox" name="remove_image" value="1"
                                    class="form-check-input border-theme dark-transition" id="removeImageCheck">
                                <label class="form-check-label text-theme-primary dark-transition"
                                    for="removeImageCheck">Remove current image</label>
                            </div>
                        @else
                            <div class="mb-5 position-relative" id="previewContainer"
                                style="max-width: 300px; display: none;">
                                <img id="imagePreview" src="#" class="img-thumbnail border-theme dark-transition"
                                    alt="Current Image">

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

                        <br>
                        <textarea name="content_2" id="description2"
                            class="form-control mb-4 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary"
                            placeholder="Second Content">{{ old('content_2', $blog->content_2 ?? '') }}</textarea>


                        <div class="mt-5 mb-2">
                            <label class="text-theme-primary dark-transition">Attach PDF</label>
                            <input type="file" name="pdf"
                                class="form-control p-2 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary">
                        </div>

                        @if (isset($blog) && $blog->pdf_path)
                            <div class="mb-3">
                                <label class="text-theme-primary dark-transition">Current PDF:</label>
                                <a href="{{ asset('storage/' . $blog->pdf_path) }}" target="_blank"
                                    class="text-primary dark:text-blue-400 dark-transition">View PDF</a>
                                <div class="form-check">
                                    <input class="form-check-input border-theme dark-transition" type="checkbox"
                                        name="remove_pdf" id="remove_pdf">
                                    <label class="form-check-label text-theme-primary dark-transition" for="remove_pdf">
                                        Remove PDF
                                    </label>
                                </div>
                            </div>
                        @endif


                        <button type="submit"
                            class="btn btn-dark dark:bg-gray-600 dark:border-gray-600 dark:hover:bg-gray-700 w-25 mt-4 dark-transition">{{ isset($blog) ? 'Update' : 'Publish' }}</button>
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
