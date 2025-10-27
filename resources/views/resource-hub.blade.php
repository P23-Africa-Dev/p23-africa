@extends('layouts.res-layout')
@section('title', 'Resource Hub | P23 Africa')

@section('meta')
    <meta name="description"
        content="Explore Expert Articles, Market Trends, And Practical Advice To Scale Your Business Across Africa And Beyond.">
    <meta name="keywords"
        content="Explore Expert Articles, Market Trends, And Practical Advice To Scale Your Business Across Africa And Beyond.">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator"
        content="Explore Expert Articles, Market Trends, And Practical Advice To Scale Your Business Across Africa And Beyond | P23 Africa">
    <meta property="og:image" content="{{ asset('images/resource-header.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

@section('content')
    <section class="whole-resource">
        <!-- Blog Section -->
        <section class="blog-sec">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 text-start mb-5 px-md-4 mb-lg-0">
                        <div class="mob">
                            <img src="{{ asset('images/resource-hub.png') }}" loading='lazy' class="mb-3" alt="">
                            <img src="{{ asset('images/rectangle-line.png') }}" loading='lazy' alt="">
                        </div>
                        <div class="blog-date">1st May 2025</div>
                        <h2 class="blog-title">Understanding Buyer Habits</h2>
                        <p class="blog-description">
                            Buyer habits in Africa are evolving fast, driven by digital adoption and a growing middle class,
                            but differences across regions and trust issues still pose challenges.
                        </p>
                        <a href="#" class="btn btn-custom mt-4">
                            Read Blog
                            <span>&rarr;</span>
                        </a>
                    </div>
                    <div class="col-lg-6 px-md-5 d-flex justify-content-center">
                        <div class="image-container">
                            <img src="{{ asset('images/resource-header-cropped.png') }}" loading='lazy' alt="Woman Image" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <style>
            .feedback-box {
                background-color: #f2f2f2;
                border-radius: 16px;
                padding: 30px 40px;
                text-align: center;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .feedback-box h2 {
                margin-bottom: 20px;
                font-size: 20px;
                color: #333;
            }

            .star-rating {
                display: flex;
                justify-content: center;
                gap: 10px;
            }

            .star {
                font-size: 40px;
                color: #ccc;
                cursor: pointer;
                transition: color 0.2s ease;
            }

            .star.hover,
            .star.selected {
                color: #ffc107;
            }


            /* Resource Report PDF */
            .pdf-popup {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                z-index: 1000;
                padding: 20px;
            }

            .pdf-popup-content {
                background: white;
                width: 100%;
                max-width: 1000px;
                height: 90vh;
                margin: 20px auto;
                position: relative;
                border-radius: 8px;
                padding: 20px;
            }

            .pdf-viewer {
                width: 100%;
                height: calc(100% - 50px);
                border: none;
            }

            .close-popup {
                position: absolute;
                right: 20px;
                top: 10px;
                font-size: 24px;
                cursor: pointer;
                color: #333;
            }

            .download-btn {
                display: inline-block;
                padding: 8px 16px;
                background: #28a745;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                margin-bottom: 10px;
            }

            .download-btn:hover {
                background: #218838;
                color: white;
            }

            /* Playbook Download Styles */
            .playbook-download-section {
                text-align: center;
                margin-top: 40px;
            }

            .btn-playbook {
                background: linear-gradient(135deg, #193e47 0%, #1deaa5 100%);
                color: white;
                border: none;
                padding: 15px 30px;
                font-size: 18px;
                font-weight: 600;
                border-radius: 50px;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(25, 62, 71, 0.3);
                min-width: 350px;
            }

            .btn-playbook:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(25, 62, 71, 0.4);
            }

            .btn-playbook:disabled {
                opacity: 0.7;
                cursor: not-allowed;
                transform: none;
            }

            /* Playbook Popup Styles */
            .playbook-popup {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                z-index: 1001;
                padding: 20px;
                animation: fadeIn 0.3s ease;
            }

            .playbook-popup.show {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .playbook-popup-content {
                background: white;
                width: 100%;
                max-width: 500px;
                border-radius: 15px;
                padding: 40px 30px;
                position: relative;
                animation: slideIn 0.3s ease;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            }

            .close-playbook-popup {
                position: absolute;
                right: 20px;
                top: 15px;
                font-size: 28px;
                cursor: pointer;
                color: #666;
                transition: color 0.2s ease;
            }

            .close-playbook-popup:hover {
                color: #193e47;
            }

            .playbook-title {
                font-size: 24px;
                font-weight: 700;
                color: #193e47;
                margin-bottom: 10px;
                text-align: center;
            }

            .playbook-subtitle {
                color: #666;
                text-align: center;
                margin-bottom: 30px;
                line-height: 1.5;
            }

            .form-group-playbook {
                margin-bottom: 20px;
            }

            .form-group-playbook label {
                display: block;
                margin-bottom: 5px;
                font-weight: 600;
                color: #333;
            }

            .form-group-playbook input {
                width: 100%;
                padding: 12px 15px;
                border: 2px solid #e1e5e9;
                border-radius: 8px;
                font-size: 16px;
                transition: border-color 0.3s ease;
            }

            .form-group-playbook input:focus {
                outline: none;
                border-color: #1deaa5;
                box-shadow: 0 0 0 3px rgba(29, 234, 165, 0.1);
            }

            .form-group-playbook input.error {
                border-color: #e74c3c;
            }

            .error-message {
                color: #e74c3c;
                font-size: 14px;
                margin-top: 5px;
                display: none;
            }

            .error-message.show {
                display: block;
            }

            .submit-playbook-btn {
                width: 100%;
                background: linear-gradient(135deg, #193e47 0%, #1deaa5 100%);
                color: white;
                border: none;
                padding: 15px;
                font-size: 16px;
                font-weight: 600;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.3s ease;
                margin-top: 10px;
            }

            .submit-playbook-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 15px rgba(25, 62, 71, 0.3);
            }

            .submit-playbook-btn:disabled {
                opacity: 0.7;
                cursor: not-allowed;
                transform: none;
            }

            .spinner {
                display: inline-block;
                width: 16px;
                height: 16px;
                border: 2px solid transparent;
                border-top: 2px solid white;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-30px) scale(0.9);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            @media (max-width: 768px) {
                .btn-playbook {
                    min-width: 280px;
                    font-size: 16px;
                    padding: 12px 25px;
                }

                .playbook-popup-content {
                    padding: 30px 20px;
                    margin: 10px;
                }

                .playbook-title {
                    font-size: 20px;
                }
            }

            /* Toast Notification Styles */
            .toast-notification {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: linear-gradient(135deg, #193e47 0%, #1deaa5 100%);
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                z-index: 1002;
                transform: translateX(400px);
                transition: transform 0.3s ease;
                max-width: 350px;
                min-width: 300px;
            }

            .toast-notification.show {
                transform: translateX(0);
            }

            .toast-notification .toast-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 5px;
            }

            .toast-notification .toast-title {
                font-weight: 600;
                font-size: 16px;
                margin: 0;
            }

            .toast-notification .toast-close {
                background: none;
                border: none;
                color: white;
                font-size: 18px;
                cursor: pointer;
                padding: 0;
                margin-left: 10px;
            }

            .toast-notification .toast-message {
                font-size: 14px;
                margin: 0;
                line-height: 1.4;
            }

            .toast-notification.success {
                background: #0d4036;
            }

            .toast-notification.error {
                background: #dc3545;
            }

            @media (max-width: 768px) {
                .toast-notification {
                    bottom: 10px;
                    right: 10px;
                    left: 10px;
                    max-width: none;
                    min-width: auto;
                }
            }
        </style>

        <script>
            const stars = document.querySelectorAll('.star');
            let selectedRating = 0;

            stars.forEach((star, index) => {
                // Hover effect
                star.addEventListener('mouseover', () => {
                    highlightStars(index);
                });

                // Remove hover effect
                star.addEventListener('mouseout', () => {
                    resetStars();
                });

                // Click to select rating
                star.addEventListener('click', () => {
                    selectedRating = index + 1;
                    updateSelectedStars();
                    // Optional: Send to backend or log
                    console.log("Selected Rating:", selectedRating);
                });
            });

            function highlightStars(index) {
                for (let i = 0; i <= index; i++) {
                    stars[i].classList.add('hover');
                }
            }

            function resetStars() {
                stars.forEach(star => star.classList.remove('hover'));
            }

            function updateSelectedStars() {
                stars.forEach((star, idx) => {
                    star.classList.toggle('selected', idx < selectedRating);
                });
            }
        </script>


        <section class="mind-behind-insight reveal-section">
            <div class="container py-5">
                <div class="section-title">Minds Behind the Insights</div>

                <!-- Desktop View -->
                <div class="row carousel-desktop">
                    <div class="col-md-4 mb-4">
                        <div class="team-card first">
                            <div class="team-name">Timothy<br>Danver</div>
                            <img src="{{ asset('images/first-mind.png') }}" loading='lazy' alt="Person 1">
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="team-card mid">
                            <div class="team-name">Simi<br>Adeniran</div>
                            <img src="{{ asset('images/second-mind.png') }}" loading='lazy' alt="Person 2">
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="team-card third">
                            <div class="team-name">Grace<br>Mwangi</div>
                            <img src="{{ asset('images/third-mind.png') }}" loading='lazy' alt="Person 3">
                        </div>
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="carousel-mobile">
                    <div class="carousel-container">
                        <div class="carousel-inner-custom" id="carouselSlides">
                            <div class="carousel-item-custom">
                                <div class="team-card first">
                                    <div class="team-name">Timothy<br>Danver</div>
                                    <img src="{{ asset('images/first-mind.png') }}" loading='lazy' alt="Person 1">
                                </div>
                            </div>
                            <div class="carousel-item-custom">
                                <div class="team-card mid">
                                    <div class="team-name">Simi<br>Adeniran</div>
                                    <img src="{{ asset('images/second-mind.png') }}" loading='lazy' alt="Person 2">
                                </div>
                            </div>
                            <div class="carousel-item-custom">
                                <div class="team-card">
                                    <div class="team-name">Grace<br>Mwangi</div>
                                    <img src="{{ asset('images/third-mind.png') }}" loading='lazy' alt="Person 3">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Arrow Buttons -->
                    <div class="arrow-btns">
                        <button class="arrow-btn" onclick="slideLeft()">←</button>
                        <button class="arrow-btn" onclick="slideRight()">→</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="blog">
            <div class="container blog-section">
                <div class="row2">
                    <div class="column">
                        <div class="py-5">
                            <div class="blog-title text-uppercase mb-2">Our Most Recent Blogs</div>
                            <div class="blog-subtitle">Explore Expert Articles, Market Trends, And Practical Advice To
                                Scale
                                Your
                                Business
                                Across Africa And Beyond.</div>
                        </div>

                        @foreach ($recentBlogs->take(3) as $index => $blog)
                            {{-- @if ($index < 3) --}}
                            @php
                                $bgClasses = [
                                    'blog-blue', // Green
                                    'blog-yellow', // Red
                                    'blog-green', // Blue
                                ];
                            @endphp

                            <div class="blog-card {{ $bgClasses[$index] }}">
                                <div class="date">{{ \Carbon\Carbon::parse($blog->created_at)->format('l, F jS Y') }}
                                </div>
                                <div class="heading">{{ $blog->title }}</div>
                                <div class="content mb-4">
                                    {!! \Illuminate\Support\Str::limit($blog->content_1, 600) !!}
                                </div>


                                @if ($blog->pdf_path)
                                    <div class="my-auto">
                                        <button class="btn btn-success view-pdf-btn"
                                            data-pdf="{{ asset('storage/' . $blog->pdf_path) }}">
                                            📥 View Report
                                        </button>
                                    </div>
                                @else
                                    <a href="{{ route('resource-show', $blog->slug) }}" class="read-more-btn">Read More
                                        →</a>
                                @endif
                            </div>
                            {{-- @endif --}}
                        @endforeach
                    </div>
                    <div class="column">
                        @foreach ($recentBlogs->slice(3, 3) as $index => $blog)
                            {{-- @if ($index >= 3 && $index < 6) --}}
                            @php
                                $bgClasses = [
                                    'blog-green', // Green
                                    'blog-pink', // Red
                                    'blog-blue', // Blue
                                ];
                                $bgIndex = $index - 3;
                            @endphp
                            <div class="blog-card {{ $bgClasses[$bgIndex] }}">
                                <div class="date">{{ \Carbon\Carbon::parse($blog->created_at)->format('l, F jS Y') }}
                                </div>
                                <div class="heading">{{ $blog->title }}</div>
                                <div class="content mb-4">
                                    {!! \Illuminate\Support\Str::limit($blog->content_1, 1200) !!}
                                </div>
                                @if ($blog->pdf_path)
                                    <div class="my-auto">
                                        <button class="btn btn-success view-pdf-btn"
                                            data-pdf="{{ asset('storage/' . $blog->pdf_path) }}">
                                            📥 View Report
                                        </button>
                                    </div>
                                @else
                                    <a href="{{ route('resource-show', $blog->slug) }}" class="read-more-btn">Read More
                                        →</a>
                                @endif
                            </div>
                            {{-- @endif --}}
                        @endforeach

                        <div class="">
                            <a href="{{ route('archive-page') }}" class="btn btn-archive"><span>View Archived Blogs
                                </span> <span class="mx-2"></span>
                                <span> →</span></a>
                        </div>
                    </div>
                </div>

                <div class="playbook-download-section">
                    <button id="playbookBtn" class="btn-playbook">
                        <span id="playbookBtnText">Download the P23 Africa's Founders Playbook</span>
                        <span id="playbookCountdown" style="display: none;">Sending... (<span
                                id="countdownTimer">10</span>s)</span>
                    </button>
                </div>
            </div>

            <div class="pdf-popup" id="pdfPopup">
                <div class="pdf-popup-content">
                    <span class="close-popup">&times;</span>
                    <a href="" class="download-btn" id="downloadPdf" download>
                        📥 Download PDF
                    </a>
                    <iframe class="pdf-viewer" id="pdfViewer" src="" frameborder="0"></iframe>
                </div>
            </div>

            <!-- Playbook Download Popup -->
            <div class="playbook-popup" id="playbookPopup">
                <div class="playbook-popup-content">
                    <span class="close-playbook-popup">&times;</span>
                    <div class="playbook-form-container">
                        <h3 class="playbook-title">Get Your Free Founders Playbook</h3>
                        <p class="playbook-subtitle">Enter your details to receive the P23 Africa's Founders Playbook
                            directly in your inbox.</p>

                        <form id="playbookForm" class="playbook-form">
                            @csrf
                            <div class="form-group-playbook">
                                <label for="playbook_name">Full Name *</label>
                                <input type="text" id="playbook_name" name="name" required>
                                <div class="error-message" id="nameError"></div>
                            </div>

                            <div class="form-group-playbook">
                                <label for="playbook_email">Email Address *</label>
                                <input type="email" id="playbook_email" name="email" required>
                                <div class="error-message" id="emailError"></div>
                            </div>

                            <div class="form-group-playbook">
                                <label for="playbook_company">Company Name *</label>
                                <input type="text" id="playbook_company" name="company" required>
                                <div class="error-message" id="companyError"></div>
                            </div>

                            <button type="submit" class="submit-playbook-btn" id="submitPlaybookBtn">
                                <span class="submit-text">Get My Playbook</span>
                                <span class="submit-loading" style="display: none;">
                                    <span class="spinner"></span> Submitting...
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const viewButtons = document.querySelectorAll('.view-pdf-btn');
                    const popup = document.getElementById('pdfPopup');
                    const pdfViewer = document.getElementById('pdfViewer');
                    const downloadBtn = document.getElementById('downloadPdf');
                    const closeBtn = document.querySelector('.close-popup');

                    viewButtons.forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.preventDefault(); // Prevent any default behavior
                            e.stopPropagation(); // Stop event bubbling

                            const pdfUrl = this.getAttribute('data-pdf');
                            pdfViewer.src = pdfUrl;
                            downloadBtn.href = pdfUrl;
                            popup.style.display = 'block';
                            document.body.style.overflow = 'hidden';
                        });
                    });

                    closeBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        popup.style.display = 'none';
                        pdfViewer.src = '';
                        document.body.style.overflow = 'auto';
                    });

                    popup.addEventListener('click', function(e) {
                        if (e.target === popup) {
                            popup.style.display = 'none';
                            pdfViewer.src = '';
                            document.body.style.overflow = 'auto';
                        }
                    });
                });

                // Playbook Download Functionality
                const playbookBtn = document.getElementById('playbookBtn');
                const playbookPopup = document.getElementById('playbookPopup');
                const playbookForm = document.getElementById('playbookForm');
                const closePlaybookPopup = document.querySelector('.close-playbook-popup');
                const submitBtn = document.getElementById('submitPlaybookBtn');

                // Initialize global variable for submission result
                window.playbookSubmissionResult = null;

                // Show popup when playbook button is clicked
                playbookBtn.addEventListener('click', function() {
                    playbookPopup.classList.add('show');
                    document.body.style.overflow = 'hidden';

                    // Restore any previously saved form data
                    restoreFormData();
                });

                // Close popup functionality
                function closePlaybookModal() {
                    playbookPopup.classList.remove('show');
                    document.body.style.overflow = 'auto';
                    // Don't reset form immediately - let user reopen with data intact
                }

                closePlaybookPopup.addEventListener('click', closePlaybookModal);

                playbookPopup.addEventListener('click', function(e) {
                    if (e.target === playbookPopup) {
                        closePlaybookModal();
                    }
                });

                // Form validation
                function validateForm() {
                    let isValid = true;
                    const name = document.getElementById('playbook_name');
                    const email = document.getElementById('playbook_email');
                    const company = document.getElementById('playbook_company');

                    // Clear previous errors
                    clearErrors();

                    // Validate name
                    if (!name.value.trim()) {
                        showError('nameError', 'Full name is required');
                        name.classList.add('error');
                        isValid = false;
                    }

                    // Validate email
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!email.value.trim()) {
                        showError('emailError', 'Email address is required');
                        email.classList.add('error');
                        isValid = false;
                    } else if (!emailRegex.test(email.value.trim())) {
                        showError('emailError', 'Please enter a valid email address');
                        email.classList.add('error');
                        isValid = false;
                    }

                    // Validate company
                    if (!company.value.trim()) {
                        showError('companyError', 'Company name is required');
                        company.classList.add('error');
                        isValid = false;
                    }

                    return isValid;
                }

                function showError(elementId, message) {
                    const errorElement = document.getElementById(elementId);
                    errorElement.textContent = message;
                    errorElement.classList.add('show');
                }

                function clearErrors() {
                    const inputs = playbookForm.querySelectorAll('input');
                    const errors = playbookForm.querySelectorAll('.error-message');

                    inputs.forEach(input => input.classList.remove('error'));
                    errors.forEach(error => {
                        error.classList.remove('show');
                        error.textContent = '';
                    });
                }

                function resetForm() {
                    playbookForm.reset();
                    clearErrors();
                    clearStoredFormData(); // Clear localStorage when form is reset

                    // Reset submit button
                    submitBtn.disabled = false;
                    submitBtn.querySelector('.submit-text').style.display = 'inline';
                    submitBtn.querySelector('.submit-loading').style.display = 'none';
                }

                // Function to restore form data from localStorage
                function restoreFormData() {
                    const savedData = localStorage.getItem('playbookFormData');
                    if (savedData) {
                        try {
                            const formData = JSON.parse(savedData);
                            document.getElementById('playbook_name').value = formData.name || '';
                            document.getElementById('playbook_email').value = formData.email || '';
                            document.getElementById('playbook_company').value = formData.company || '';
                        } catch (e) {
                            console.error('Error restoring form data:', e);
                        }
                    }
                }

                // Function to clear stored form data
                function clearStoredFormData() {
                    localStorage.removeItem('playbookFormData');
                }

                // Countdown functionality
                function startCountdown() {
                    const btnText = document.getElementById('playbookBtnText');
                    const countdown = document.getElementById('playbookCountdown');
                    const timer = document.getElementById('countdownTimer');

                    btnText.style.display = 'none';
                    countdown.style.display = 'inline';
                    playbookBtn.disabled = true;

                    let timeLeft = 10;
                    timer.textContent = timeLeft;

                    const countdownInterval = setInterval(() => {
                        timeLeft--;
                        timer.textContent = timeLeft;

                        if (timeLeft <= 0) {
                            clearInterval(countdownInterval);
                            btnText.style.display = 'inline';
                            countdown.style.display = 'none';
                            playbookBtn.disabled = false;
                            btnText.textContent = 'Playbook Sent! Check Your Email';

                            // Show toast notification immediately when countdown completes
                            if (window.playbookSubmissionResult) {
                                const data = window.playbookSubmissionResult;
                                if (data.success) {
                                    // Clear stored form data on success
                                    clearStoredFormData();

                                    showToast(
                                        'Playbook Sent Successfully!',
                                        'The P23 Africa\'s Founders Playbook has been sent to your email. Please check your inbox.',
                                        'success'
                                    );
                                } else {
                                    showToast(
                                        'Submission Failed',
                                        data.message || 'An unexpected error occurred. Please try again.',
                                        'error',
                                        true
                                    );
                                    resetMainButton();
                                }
                                // Clear the stored result
                                window.playbookSubmissionResult = null;
                            } else {
                                // If submission result is not ready yet, show processing message
                                showToast(
                                    'Processing Your Request',
                                    'Your playbook request is being processed. You should receive it shortly in your email.',
                                    'success'
                                );

                                // Keep checking for the result for up to 30 seconds
                                let checkCount = 0;
                                const resultChecker = setInterval(() => {
                                    checkCount++;
                                    if (window.playbookSubmissionResult) {
                                        clearInterval(resultChecker);
                                        const data = window.playbookSubmissionResult;

                                        // Hide the processing toast
                                        const existingToast = document.querySelector('.toast-notification');
                                        if (existingToast) {
                                            hideToast(existingToast);
                                        }

                                        // Show final result after a brief delay
                                        setTimeout(() => {
                                            if (data.success) {
                                                clearStoredFormData();
                                                showToast(
                                                    'Playbook Sent Successfully!',
                                                    'The P23 Africa\'s Founders Playbook has been sent to your email. Please check your inbox.',
                                                    'success'
                                                );
                                            } else {
                                                showToast(
                                                    'Submission Failed',
                                                    data.message ||
                                                    'An unexpected error occurred. Please try again.',
                                                    'error',
                                                    true
                                                );
                                                resetMainButton();
                                            }
                                            window.playbookSubmissionResult = null;
                                        }, 500);
                                    } else if (checkCount >= 30) {
                                        // Stop checking after 30 seconds
                                        clearInterval(resultChecker);

                                        // Hide processing toast and show timeout message
                                        const existingToast = document.querySelector('.toast-notification');
                                        if (existingToast) {
                                            hideToast(existingToast);
                                        }

                                        setTimeout(() => {
                                            showToast(
                                                'Request Taking Longer Than Expected',
                                                'Your playbook request is still being processed. Please check your email in a few minutes.',
                                                'success'
                                            );
                                        }, 500);
                                    }
                                }, 1000);
                            }

                            // Reset button text after 6 seconds (only if success)
                            setTimeout(() => {
                                if (btnText.textContent === 'Playbook Sent! Check Your Email') {
                                    btnText.textContent = 'Download the P23 Africa\'s Founders Playbook';
                                }
                            }, 6000);
                        }
                    }, 1000);

                    return countdownInterval;
                }

                // Toast notification functionality
                function showToast(title, message, type = 'success', showRetry = false) {
                    // Remove existing toast if any
                    const existingToast = document.querySelector('.toast-notification');
                    if (existingToast) {
                        existingToast.remove();
                    }

                    // Create toast element
                    const toast = document.createElement('div');
                    toast.className = `toast-notification ${type}`;

                    const retryButton = showRetry ? `
                        <button class="toast-retry-btn" style="
                            background: rgba(255,255,255,0.2);
                            border: 1px solid rgba(255,255,255,0.3);
                            color: white;
                            padding: 5px 10px;
                            border-radius: 4px;
                            font-size: 12px;
                            cursor: pointer;
                            margin-top: 8px;
                            transition: background 0.2s ease;
                        " onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                            Try Again
                        </button>
                    ` : '';

                    toast.innerHTML = `
                        <div class="toast-header">
                            <h4 class="toast-title">${title}</h4>
                            <button class="toast-close">&times;</button>
                        </div>
                        <p class="toast-message">${message}</p>
                        ${retryButton}
                    `;

                    // Add to body
                    document.body.appendChild(toast);

                    // Show toast
                    setTimeout(() => toast.classList.add('show'), 100);

                    // Close functionality
                    const closeBtn = toast.querySelector('.toast-close');
                    closeBtn.addEventListener('click', () => hideToast(toast));

                    // Retry functionality
                    if (showRetry) {
                        const retryBtn = toast.querySelector('.toast-retry-btn');
                        if (retryBtn) {
                            retryBtn.addEventListener('click', () => {
                                hideToast(toast);
                                // Reopen the popup with saved data
                                playbookPopup.classList.add('show');
                                document.body.style.overflow = 'hidden';
                                restoreFormData();
                            });
                        }
                    }

                    // Auto hide after 15 seconds for success, 10 seconds for retry toasts
                    const autoHideDelay = showRetry ? 10000 : (type === 'success' ? 15000 : 7000);
                    setTimeout(() => hideToast(toast), autoHideDelay);

                    return toast;
                }

                function hideToast(toast) {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.parentNode.removeChild(toast);
                        }
                    }, 300);
                }

                // Form submission
                playbookForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (!validateForm()) {
                        return;
                    }

                    // Capture form data BEFORE hiding the popup
                    const formDataObject = {
                        name: document.getElementById('playbook_name').value.trim(),
                        email: document.getElementById('playbook_email').value.trim(),
                        company: document.getElementById('playbook_company').value.trim(),
                        _token: document.querySelector('meta[name="csrf-token"]').content || document.querySelector(
                            'input[name="_token"]').value
                    };

                    // Store in localStorage as backup
                    localStorage.setItem('playbookFormData', JSON.stringify(formDataObject));

                    // Debug: Log form data
                    console.log('Form data being sent:', formDataObject);

                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.querySelector('.submit-text').style.display = 'none';
                    submitBtn.querySelector('.submit-loading').style.display = 'inline';

                    // Hide popup immediately after capturing data
                    closePlaybookModal();

                    // Start countdown on main button
                    const countdownInterval = startCountdown();

                    // Create FormData object from captured data
                    const formData = new FormData();
                    Object.keys(formDataObject).forEach(key => {
                        formData.append(key, formDataObject[key]);
                    });

                    // Submit to server
                    fetch('{{ route('playbook.download') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': formDataObject._token,
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(async response => {
                            const data = await response.json();

                            if (!response.ok) {
                                // Handle different HTTP status codes
                                if (response.status === 422) {
                                    // Validation errors
                                    let errorMessages = [];
                                    if (data.errors) {
                                        Object.values(data.errors).forEach(fieldErrors => {
                                            if (Array.isArray(fieldErrors)) {
                                                errorMessages = errorMessages.concat(fieldErrors);
                                            } else {
                                                errorMessages.push(fieldErrors);
                                            }
                                        });
                                    }

                                    const errorMessage = errorMessages.length > 0 ?
                                        errorMessages.join(' ') :
                                        'Please check your form inputs and try again.';

                                    throw new Error(`Validation Error: ${errorMessage}`);
                                } else if (response.status === 500) {
                                    throw new Error('Server Error: ' + (data.message ||
                                        'Internal server error occurred.'));
                                } else {
                                    throw new Error('HTTP Error: ' + (data.message ||
                                        `Request failed with status ${response.status}`));
                                }
                            }

                            return data;
                        })
                        .then(data => {
                            // Store the result for when countdown completes
                            window.playbookSubmissionResult = data;
                        })
                        .catch(error => {
                            console.error('Playbook download error:', error);

                            // Clear countdown and reset button immediately on error
                            clearInterval(countdownInterval);
                            resetMainButton();

                            // Clear any stored result
                            window.playbookSubmissionResult = null;

                            // Show error toast immediately
                            let errorTitle = 'Submission Failed';
                            let errorMessage = 'An error occurred. Please try again.';

                            if (error.message.includes('Validation Error:')) {
                                errorTitle = 'Form Validation Error';
                                errorMessage = error.message.replace('Validation Error: ', '');
                                showToast(errorTitle, errorMessage + ' Click "Try Again" to fix your form.',
                                    'error', true);
                            } else if (error.message.includes('Server Error:')) {
                                errorTitle = 'Server Error';
                                errorMessage = error.message.replace('Server Error: ', '');
                                showToast(errorTitle, errorMessage, 'error', true);
                            } else if (error.message.includes('HTTP Error:')) {
                                errorTitle = 'Request Error';
                                errorMessage = error.message.replace('HTTP Error: ', '');
                                showToast(errorTitle, errorMessage, 'error', true);
                            } else if (error.message.includes('Failed to fetch') || error.message.includes(
                                    'NetworkError')) {
                                errorTitle = 'Network Error';
                                errorMessage = 'Please check your internet connection and try again.';
                                showToast(errorTitle, errorMessage, 'error', true);
                            } else {
                                showToast(errorTitle, errorMessage, 'error', true);
                            }
                        })
                        .finally(() => {
                            // Reset submit button (this will run immediately but popup is already closed)
                            submitBtn.disabled = false;
                            submitBtn.querySelector('.submit-text').style.display = 'inline';
                            submitBtn.querySelector('.submit-loading').style.display = 'none';
                        });
                });

                // Helper function to reset the main button
                function resetMainButton() {
                    const btnText = document.getElementById('playbookBtnText');
                    const countdown = document.getElementById('playbookCountdown');
                    btnText.style.display = 'inline';
                    countdown.style.display = 'none';
                    playbookBtn.disabled = false;
                    btnText.textContent = 'Download the P23 Africa\'s Founders Playbook';
                }
            </script>
        </section>
    </section>
@endsection

@include('include.resourse-js')
