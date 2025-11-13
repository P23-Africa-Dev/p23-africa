<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition">
            {{ __('Create Resource Blog') }}
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

                    <!-- Post Type Tabs -->
                    <ul class="nav nav-tabs mb-4" id="postTypeTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="blog-tab" data-bs-toggle="tab"
                                data-bs-target="#blog-content" type="button" role="tab"
                                aria-controls="blog-content" aria-selected="true">
                                <i class="fas fa-newspaper me-2"></i>Blog Post
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="report-tab" data-bs-toggle="tab"
                                data-bs-target="#report-content" type="button" role="tab"
                                aria-controls="report-content" aria-selected="false">
                                <i class="fas fa-file-pdf me-2"></i>Report
                            </button>
                        </li>
                    </ul>

                    <form action="{{ isset($blog) ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($blog))
                            @method('PUT')
                        @endif

                        <!-- Hidden input to track post type -->
                        <input type="hidden" name="post_type" id="postType" value="blog">

                        <input name="title" value="{{ old('title', $blog->title ?? '') }}" placeholder="Blog Title"
                            required id="titleInput"
                            class="form-control mb-3 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary">
                        <input name="subtitle" value="{{ old('subtitle', $blog->subtitle ?? '') }}"
                            placeholder="Blog Subtitle" id="subtitleInput"
                            class="form-control mb-3 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary">

                        <select name="category_id"
                            class="form-control mb-3 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ isset($blog) && $blog->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <textarea name="content_1"
                            class="form-control mb-3 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary"
                            placeholder="First Content" rows="8" id="description" required>{{ old('content_1', $blog->content_1 ?? '') }}</textarea>

                        <!-- Blog-specific fields -->
                        <div id="blogFields">
                            <input type="file" name="image" id="image"
                                class="form-control p-2 my-3 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary"
                                accept="image/*">
                            @if (isset($blog) && $blog->image_path)
                                <img src="{{ asset('storage/' . $blog->image_path) }}" width="200"
                                    class="mb-2 border border-theme dark-transition">
                            @endif
                            <div class="mb-5 position-relative" id="previewContainer"
                                style="max-width: 300px; display: none;">
                                <img id="imagePreview" src="#" alt="Image Preview"
                                    class="img-thumbnail border-theme dark-transition">

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

                            <textarea name="content_2"
                                class="form-control mb-3 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary"
                                rows="8" placeholder="Second Content" id="description2">{{ old('content_2', $blog->content_2 ?? '') }}</textarea>
                        </div>

                        <!-- Report-specific fields -->
                        <div id="reportFields" style="display: none;">
                            <div class="my-3">
                                <label class="text-theme-primary dark-transition">Attach PDF Report <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="pdf" id="pdfInput"
                                    class="form-control p-3 border border-1 border-theme dark-transition theme-transition bg-theme-tertiary text-theme-primary"
                                    accept=".pdf">
                                <small class="text-muted">Required for report posts</small>
                            </div>
                        </div>

                        @if (isset($blog) && $blog->pdf_path)
                            <div class="mb-3">
                                <label class="text-theme-primary dark-transition">Current PDF:</label>
                                <a href="{{ asset('storage/' . $blog->pdf_path) }}" target="_blank"
                                    class="text-primary dark:text-blue-400 dark-transition">View PDF</a>
                                <div class="form-check">
                                    <input class="form-check-input p-3 border-theme dark-transition" type="checkbox"
                                        name="remove_pdf" id="remove_pdf">
                                    <label class="form-check-label text-theme-primary dark-transition"
                                        for="remove_pdf">
                                        Remove PDF
                                    </label>
                                </div>
                            </div>
                        @endif

                        <button type="submit"
                            class="btn btn-dark dark:bg-gray-600 dark:border-gray-600 dark:hover:bg-gray-700 w-25 dark-transition mt-4">{{ isset($blog) ? 'Update' : 'Publish' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    // Post Type Toggle
    const blogTab = document.getElementById('blog-tab');
    const reportTab = document.getElementById('report-tab');
    const blogFields = document.getElementById('blogFields');
    const reportFields = document.getElementById('reportFields');
    const postTypeInput = document.getElementById('postType');
    const imageInput = document.getElementById('image');
    const pdfInput = document.getElementById('pdfInput');
    const content2Textarea = document.getElementById('description2');
    const titleInput = document.getElementById('titleInput');
    const subtitleInput = document.getElementById('subtitleInput');
    const content1Textarea = document.getElementById('description');

    blogTab.addEventListener('click', function() {
        postTypeInput.value = 'blog';
        blogFields.style.display = 'block';
        reportFields.style.display = 'none';

        // Update placeholders for Blog
        if (titleInput) titleInput.setAttribute('placeholder', 'Blog Title');
        if (subtitleInput) subtitleInput.setAttribute('placeholder', 'Blog Subtitle');
        if (content1Textarea) content1Textarea.setAttribute('placeholder', 'First Content');

        // Remove required from PDF, add to image and content_2
        if (pdfInput) pdfInput.removeAttribute('required');
        if (content2Textarea) content2Textarea.removeAttribute('required');
    });

    reportTab.addEventListener('click', function() {
        postTypeInput.value = 'report';
        blogFields.style.display = 'none';
        reportFields.style.display = 'block';

        // Update placeholders for Report
        if (titleInput) titleInput.setAttribute('placeholder', 'Report Title');
        if (subtitleInput) subtitleInput.setAttribute('placeholder', 'Report Subtitle');
        if (content1Textarea) content1Textarea.setAttribute('placeholder', 'Report Content');

        // Add required to PDF, remove from image and content_2
        if (pdfInput) pdfInput.setAttribute('required', 'required');
        if (imageInput) imageInput.removeAttribute('required');
        if (content2Textarea) content2Textarea.removeAttribute('required');
    });

    // Display Images
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
            } else {
                previewContainer.style.display = 'none';
                imagePreview.setAttribute('src', '#');
            }
        });
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            imageInput.value = '';
            previewContainer.style.display = 'none';
            imagePreview.setAttribute('src', '#');
        });
    }
</script>
