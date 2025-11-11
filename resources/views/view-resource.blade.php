@extends('layouts.res-layout')

@section('title')
    {{ $blog->title }}
    @if ($blog->subtitle)
        ({{ $blog->subtitle }})
    @endif
    Resource Details | P23 Africa
@endsection

@section('meta')
    <meta property="og:image" content="{{ asset('storage/' . $blog->image_path) }}">
    <meta name="description" content="{!! nl2br(e($blog->content_1)) !!}">

    <meta name="keywords" content="{!! nl2br(e($blog->content_1)) !!}">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="{!! nl2br(e($blog->content_1)) !!}">
@endsection

<style>
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

    /* Modern PDF Preview Styles */
    .pdf-preview-wrapper {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 16px;
        padding: 24px;
        margin: 32px 0;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .pdf-preview-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #007bff, #28a745, #ffc107, #dc3545);
        border-radius: 16px 16px 0 0;
    }

    .pdf-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .pdf-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pdf-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #dc3545, #ff6b7a);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        font-weight: bold;
        box-shadow: 0 4px 16px rgba(220, 53, 69, 0.3);
    }

    .pdf-info h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        line-height: 1.3;
    }

    .pdf-info p {
        margin: 4px 0 0;
        font-size: 14px;
        color: #6c757d;
        line-height: 1.4;
    }

    .pdf-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .pdf-download-btn {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(40, 167, 69, 0.3);
    }

    .pdf-download-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(40, 167, 69, 0.4);
        color: white;
        text-decoration: none;
    }

    .pdf-fullscreen-btn {
        background: #6c757d;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .pdf-fullscreen-btn:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }

    .pdf-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.08);
        position: relative;
    }

    .pdf-loader {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        z-index: 1;
    }

    .pdf-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #007bff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .pdf-viewer-modern {
        width: 100%;
        border: none;
        background: white;
        transition: opacity 0.3s ease;
    }

    .pdf-viewer-modern.loading {
        opacity: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .pdf-preview-wrapper {
            padding: 16px;
            margin: 20px 0;
            border-radius: 12px;
        }

        .pdf-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .pdf-header-left {
            width: 100%;
        }

        .pdf-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .pdf-info h4 {
            font-size: 16px;
        }

        .pdf-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .pdf-download-btn {
            padding: 10px 20px;
            font-size: 13px;
        }

        .pdf-viewer-modern {
            height: 400px;
        }
    }

    @media (min-width: 769px) {
        .pdf-viewer-modern {
            height: 700px;
        }
    }

    @media (min-width: 1200px) {
        .pdf-viewer-modern {
            height: 800px;
        }
    }

    /* PDF Fallback Styles */
    .pdf-fallback {
        background: #f8f9fa;
        border-radius: 12px;
        border: 2px dashed #dee2e6;
        margin: 20px 0;
    }

    .pdf-fallback .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 10px 16px;
        border: none;
        transition: all 0.3s ease;
    }

    .pdf-fallback .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* iOS Safari specific fixes */
    @supports (-webkit-appearance: none) {
        .pdf-viewer-modern {
            -webkit-overflow-scrolling: touch;
        }
    }

    /* Mobile-specific enhancements */
    @media (max-width: 768px) {
        .pdf-fallback {
            margin: 16px 0;
            padding: 20px !important;
        }

        .pdf-fallback div {
            padding: 30px 16px !important;
        }

        .pdf-fallback .btn {
            width: 100%;
            margin-bottom: 8px;
        }

        .pdf-fallback div[style*="display: flex"] {
            flex-direction: column !important;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .pdf-preview-wrapper {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .pdf-info h4 {
            color: #ecf0f1;
        }

        .pdf-info p {
            color: #bdc3c7;
        }

        .pdf-container {
            background: #34495e;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .pdf-fallback {
            background: #34495e;
            border-color: rgba(255, 255, 255, 0.2);
            color: #ecf0f1;
        }
    }
</style>

@section('content')
    <section class="whole-view-resource">
        <div class="whole-header">
            <section class="container py-5">
                <!-- Heading Section -->
                <section class="resourse-details-header">
                    <div class="header">
                        {{-- <span>INTRO</span> --}}
                        <h2 class="section-title">{{ $blog->title }}</h2>
                        <p class="section-subtitle">
                            {{ $blog->subtitle }}
                        </p>
                    </div>
                </section>
            </section>
            <div class="side-image">
                <img src="{{ asset('images/view-resource-side.png') }}" loading='lazy' alt="">
            </div>
        </div>
        <section class="resourse-details-content">
            <div class="dotted-divider"></div>
            <!-- Article Section -->
            <section class="container py-lg-5 py-md-4 py-3">
                <div class="row align-items-center">
                    <div class="col-12 my-md-5 my-lg-5 my-0 mt-md-0">
                        {{-- <div class="article-title">Finibus Bonorum et Malorum</div> --}}
                        <div class="date-author">Written by {{ $blog->user->name }} &nbsp; | &nbsp;
                            {{ $blog->created_at->format('l, F jS Y') }}</div>
                        <div class="content">
                            <p>
                                {!! $blog->content_1 !!}
                            </p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="content-wrapper" style="position: relative;">
                            @if ($blog->image_path)
                                <img src="{{ asset('storage/' . $blog->image_path) }}" loading='lazy' alt="Insight Image"
                                    class="article-img float-md-end ms-md-4 mb-3"
                                    style="max-width: 100%; width: 100%; max-width: 450px; height: auto; border-radius: 8px;" />
                            @endif
                            <div class="content">
                                @if ($blog->pdf_path)
                                    <div class="pdf-preview-wrapper">
                                        <div class="pdf-header">
                                            <div class="pdf-header-left">
                                                <div class="pdf-icon">
                                                    PDF
                                                </div>
                                                <div class="pdf-info">
                                                    <h4>{{ $blog->title }} - Report</h4>
                                                    <p>Interactive PDF document • {{ $blog->created_at->format('M d, Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="pdf-actions">
                                                <button class="pdf-fullscreen-btn" onclick="toggleFullscreen()"
                                                    title="Toggle Fullscreen">
                                                    <svg width="16" height="16" fill="currentColor"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1h-4zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zM.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5z" />
                                                    </svg>
                                                </button>
                                                <a href="{{ asset('storage/' . $blog->pdf_path) }}"
                                                    class="pdf-download-btn" download>
                                                    <svg width="16" height="16" fill="currentColor"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                                        <path
                                                            d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                                    </svg>
                                                    Download Report
                                                </a>
                                            </div>
                                        </div>
                                        <div class="pdf-container" id="pdfContainer">
                                            <div class="pdf-loader" id="pdfLoader">
                                                <div class="pdf-spinner"></div>
                                                <p style="color: #6c757d; margin: 0; font-size: 14px;">Loading PDF...</p>
                                            </div>
                                            <iframe
                                                src="{{ asset('storage/' . $blog->pdf_path) }}#toolbar=1&navpanes=1&scrollbar=1"
                                                class="pdf-viewer-modern loading" id="pdfViewerMain"
                                                title="PDF Report Preview" onload="handlePdfLoad()"
                                                onerror="handlePdfError()">
                                            </iframe>

                                            <!-- Fallback for unsupported browsers -->
                                            <div class="pdf-fallback" id="pdfFallback" style="display: none;">
                                                <div style="text-align: center; padding: 40px; color: #6c757d;">
                                                    <svg width="64" height="64" fill="currentColor"
                                                        viewBox="0 0 16 16" style="margin-bottom: 16px; opacity: 0.6;">
                                                        <path
                                                            d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                                    </svg>
                                                    <h5 style="color: #495057; margin-bottom: 12px;">PDF Preview Not
                                                        Available</h5>
                                                    <p style="margin-bottom: 20px; font-size: 14px;">Your browser doesn't
                                                        support inline PDF viewing.</p>
                                                    <div
                                                        style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                                                        <a href="{{ asset('storage/' . $blog->pdf_path) }}"
                                                            class="btn btn-primary btn-sm" target="_blank"
                                                            style="text-decoration: none;">
                                                            <svg width="16" height="16" fill="currentColor"
                                                                viewBox="0 0 16 16" style="margin-right: 6px;">
                                                                <path
                                                                    d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z" />
                                                                <path
                                                                    d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z" />
                                                            </svg>
                                                            Open in New Tab
                                                        </a>
                                                        <a href="{{ asset('storage/' . $blog->pdf_path) }}"
                                                            class="btn btn-success btn-sm" download
                                                            style="text-decoration: none;">
                                                            <svg width="16" height="16" fill="currentColor"
                                                                viewBox="0 0 16 16" style="margin-right: 6px;">
                                                                <path
                                                                    d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                                                <path
                                                                    d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                                            </svg>
                                                            Download PDF
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p>
                                        {!! $blog->content_2 !!}
                                    </p>
                                @endif
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3"
                                    style="margin-top: 30px; clear: both;">
                                    <div>
                                        <a href="{{ route('resource-hub') }}" class="cta-button">Get More Insights <span
                                                class="mx-2"></span> →</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>

        <div class="pdf-popup" id="pdfPopup">
            <div class="pdf-popup-content">
                <span class="close-popup">&times;</span>
                <a href="" class="download-btn" id="downloadPdf" download>
                    📥 Download PDF
                </a>
                <iframe class="pdf-viewer" id="pdfViewerPopup" src="" frameborder="0"></iframe>
            </div>
        </div>


        <script>
            // Modern PDF Viewer Functions
            function handlePdfLoad() {
                const pdfLoader = document.getElementById('pdfLoader');
                const pdfViewer = document.getElementById('pdfViewerMain');

                if (pdfLoader) {
                    pdfLoader.style.display = 'none';
                }
                if (pdfViewer) {
                    pdfViewer.classList.remove('loading');
                }

                // Check if PDF actually loaded or just shows plugin message
                setTimeout(function() {
                    if (pdfViewer && pdfViewer.contentDocument) {
                        try {
                            const content = pdfViewer.contentDocument.body.innerHTML.toLowerCase();
                            if (content.includes('plugin') || content.includes('download') || content.includes(
                                    'open') || content === '') {
                                handlePdfError();
                            }
                        } catch (e) {
                            // Cross-origin restrictions - assume it's working
                            console.log('PDF loaded successfully (cross-origin restriction)');
                        }
                    }
                }, 2000);
            }

            function handlePdfError() {
                const pdfViewer = document.getElementById('pdfViewerMain');
                const pdfFallback = document.getElementById('pdfFallback');
                const pdfLoader = document.getElementById('pdfLoader');

                if (pdfLoader) {
                    pdfLoader.style.display = 'none';
                }
                if (pdfViewer) {
                    pdfViewer.style.display = 'none';
                }
                if (pdfFallback) {
                    pdfFallback.style.display = 'block';
                }
            }

            // Device detection for better PDF handling
            function isMobileDevice() {
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            }

            function isIOSDevice() {
                return /iPad|iPhone|iPod/.test(navigator.userAgent);
            }

            function isSafari() {
                return /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
            }

            function toggleFullscreen() {
                const pdfContainer = document.getElementById('pdfContainer');
                const pdfViewer = document.getElementById('pdfViewerMain');

                if (!document.fullscreenElement) {
                    pdfContainer.requestFullscreen().then(() => {
                        pdfViewer.style.height = '100vh';
                        pdfContainer.style.borderRadius = '0';
                    }).catch(err => {
                        console.log('Error attempting to enable fullscreen:', err.message);
                    });
                } else {
                    document.exitFullscreen().then(() => {
                        pdfViewer.style.height = '';
                        pdfContainer.style.borderRadius = '12px';
                    });
                }
            }

            // Handle fullscreen change events
            document.addEventListener('fullscreenchange', function() {
                const pdfContainer = document.getElementById('pdfContainer');
                const pdfViewer = document.getElementById('pdfViewerMain');

                if (!document.fullscreenElement) {
                    // Exited fullscreen
                    if (pdfViewer) {
                        pdfViewer.style.height = '';
                    }
                    if (pdfContainer) {
                        pdfContainer.style.borderRadius = '12px';
                    }
                }
            });

            // Add loading state management and device detection
            document.addEventListener('DOMContentLoaded', function() {
                const pdfViewer = document.getElementById('pdfViewerMain');
                const pdfContainer = document.getElementById('pdfContainer');

                if (pdfViewer) {
                    // Show loader initially
                    pdfViewer.addEventListener('load', handlePdfLoad);
                    pdfViewer.addEventListener('error', handlePdfError);

                    // Check for mobile devices and add helper text
                    if (isMobileDevice()) {
                        const pdfHeader = document.querySelector('.pdf-info p');
                        if (pdfHeader) {
                            pdfHeader.innerHTML += ' • Tap download if preview doesn\'t work';
                        }
                    }

                    // More lenient approach - only show fallback for iOS Safari
                    if (isIOSDevice() && isSafari()) {
                        setTimeout(function() {
                            handlePdfError();
                        }, 2000);
                    } else {
                        // For other browsers, give more time before fallback
                        setTimeout(function() {
                            const pdfLoader = document.getElementById('pdfLoader');
                            if (pdfLoader && pdfLoader.style.display !== 'none') {
                                // Check if iframe has actually loaded something
                                try {
                                    if (pdfViewer.contentDocument === null) {
                                        // PDF is likely loading or loaded successfully
                                        handlePdfLoad();
                                    }
                                } catch (e) {
                                    // Cross-origin - PDF is likely working
                                    handlePdfLoad();
                                }
                            }
                        }, 8000);
                    }
                }

                // Legacy popup functionality (if needed)
                const viewButtons = document.querySelectorAll('.view-pdf-btn');
                const popup = document.getElementById('pdfPopup');
                const pdfViewerPopup = document.getElementById('pdfViewerPopup');
                const downloadBtn = document.getElementById('downloadPdf');
                const closeBtn = document.querySelector('.close-popup');

                viewButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const pdfUrl = this.getAttribute('data-pdf');
                        pdfViewerPopup.src = pdfUrl;
                        downloadBtn.href = pdfUrl;
                        popup.style.display = 'block';
                        document.body.style.overflow = 'hidden';
                    });
                });

                if (closeBtn) {
                    closeBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        popup.style.display = 'none';
                        pdfViewerPopup.src = '';
                        document.body.style.overflow = 'auto';
                    });
                }

                if (popup) {
                    popup.addEventListener('click', function(e) {
                        if (e.target === popup) {
                            popup.style.display = 'none';
                            pdfViewerPopup.src = '';
                            document.body.style.overflow = 'auto';
                        }
                    });
                }
            });
        </script>
    </section>
@endsection
