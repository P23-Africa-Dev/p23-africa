@extends('layouts.quiz-layout')
@section('title', __('quiz.c_title'))

@section('meta')
    <meta name="description" content="{{ __('quiz.c_sub') }}">
    <meta name="keywords" content="{{ __('quiz.c_sub') }}">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="{{ __('quiz.meta_generator') }}">
    <meta property="og:image" content="{{ asset('images/cloudy_climber.png') }}">
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@section('content')
    <section class="fragile">
        <div class="fragile-funnel-container container">
            <div class="row g-4">
                <div class="col-lg-6 left-column">
                    <div class="header-section mb-4">
                        <h1>
                            <span>{{ __('quiz.you_are') }}</span>
                            <span id="typing-title" class="typing-text">{{ __('quiz.c_name') }}</span>
                        </h1>
                        <p id="typing-subtitle" class="typing-text">{{ __('quiz.c_sub') }}</p>
                    </div>

                    <div class="chart-container d-flex flex-column align-items-start mb-4 mb-lg-0">
                        <div class="chart-section">
                            <img src="{{ asset('images/cloudy_climber.png') }}" loading="lazy" alt="SurveyMonkey Graphs"
                                class="img-fluid" />
                        </div>
                        <ul class="chart-legend">
                            <div class="legend d-flex gap-2 flex-column">
                                <li>
                                    <div class="color-box purple"></div>
                                    {{ __('quiz.c_m1') }}
                                </li>
                                <li>
                                    <div class="color-box dark-green"></div>
                                    {{ __('quiz.c_m2') }}
                                </li>
                                <li>
                                    <div class="color-box green"></div>
                                    {{ __('quiz.c_m3') }}
                                </li>
                            </div>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6 right-column">
                    <div class="metrics-section mb-4">
                        <div class="metric">
                            <div class="progress-bar-container purple-bg mb-3">
                                <div class="progress-fill purple" data-width="40%"></div>
                            </div>
                            <div class="metric-item purple">
                                <div class="color-box purple"></div>
                                <p>{{ __('quiz.c_m1') }} <span style="left: 15px;">40%</span></p>
                            </div>
                        </div>
                        <div class="metric">
                            <div class="progress-bar-container green-bg mb-3">
                                <div class="progress-fill green" data-width="35%"></div>
                            </div>
                            <div class="metric-item green">
                                <div class="color-box"></div>
                                <p>{{ __('quiz.c_m2') }} <span style="left: 15px;">35%</span></p>
                            </div>
                        </div>
                        <div class="metric">
                            <div class="progress-bar-container yellow-bg mb-3">
                                <div class="progress-fill yellow" data-width="30%"></div>
                            </div>
                            <div class="metric-item yellow">
                                <div class="color-box"></div>
                                <p>{{ __('quiz.c_m3') }} <span style="left: 15px;">30%</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="right-panel">
                        <div class="edge-shape"></div>

                        <div class="right-panel-container">
                            <div class=" common-risks-section mb-2">
                                <h3 class="mb-3">{{ __('quiz.looks') }}</h3>
                                <div class="risk-list">
                                    <ul>
                                        <li>{{ __('quiz.c_r1') }}</li>
                                        <li>{{ __('quiz.c_r2') }}</li>
                                        <li>
                                            {{ __('quiz.c_r3') }}
                                        </li>
                                        <li>{{ __('quiz.c_r4') }}</li>
                                        <li>{{ __('quiz.c_r5') }}</li>
                                    </ul>
                                </div>
                            </div>

                            <div class=" solution-section mb-2">
                                <h3 class="mb-3">{{ __('quiz.need') }}</h3>
                                <p>
                                    {{ __('quiz.c_need') }}
                                </p>
                            </div>

                            <div class=" next-step-section">
                                <h3 class="mb-3">{{ __('quiz.next') }}</h3>
                                <p>
                                    {{ __('quiz.c_next') }}
                                </p>
                            </div>
                        </div>

                        <div class="buttons-group gap-3">
                            <a href="https://calendar.google.com/calendar/u/0/appointments/schedules/AcZssZ3tzdnFU-zme-lCpKoHGZktqjUVs4rll_QJtcUzK71d9-l3M0WIFvjtnJpLmosUraV8XkUJeD_k"
                                target="_blank" class="btn btn-purple-outline">{{ __('quiz.book_audit') }}</a>
                            <button class="btn btn-dark-green" id="play-video-btn">
                                {{ __('quiz.tips') }}
                                <i class="bi bi-arrow-right"></i>
                            </button>
                            <button class="btn btn-dark-green" id="play-video-btn2">
                                {{ __('quiz.tips') }}
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Modal -->
        <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered custom-modal">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="{{ __('quiz.close') }}"></button>
                    </div>
                    <div class="modal-body p-0 text-center">
                        <div class="ratio ratio-16x9" id="youtubePlayerContainer">
                            <div id="youtubePlayer"></div>
                        </div>

                        <!-- Hidden Appointment Button -->
                        <div id="appointmentButtonContainer" class="py-3" style="display: none;">
                            <a href="https://calendar.google.com/calendar/u/0/appointments/schedules/AcZssZ3tzdnFU-zme-lCpKoHGZktqjUVs4rll_QJtcUzK71d9-l3M0WIFvjtnJpLmosUraV8XkUJeD_k"
                                target="_blank" class="btn btn-success animate-pop-in">{{ __('quiz.book_audit') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <script>
        // Animate when the element is in view
        document.addEventListener("DOMContentLoaded", () => {
            const fills = document.querySelectorAll(".progress-fill");

            fills.forEach(fill => {
                const targetWidth = fill.getAttribute("data-width");

                setTimeout(() => {
                    fill.style.width = targetWidth;
                }, 500);
            });
        });


        document.addEventListener("DOMContentLoaded", () => {
            const title = @json(__('quiz.c_name'));
            const subtitle = @json(__('quiz.c_sub'));

            const titleEl = document.getElementById("typing-title");
            const subtitleEl = document.getElementById("typing-subtitle");

            let titleIndex = 0;
            let subtitleIndex = 0;

            function typeTitle() {
                if (titleIndex < title.length) {
                    titleEl.textContent += title.charAt(titleIndex);
                    titleIndex++;
                    setTimeout(typeTitle, 100);
                } else {
                    // Delay before typing subtitle
                    setTimeout(typeSubtitle, 400);
                }
            }

            function typeSubtitle() {
                if (subtitleIndex < subtitle.length) {
                    subtitleEl.textContent += subtitle.charAt(subtitleIndex);
                    subtitleIndex++;
                    setTimeout(typeSubtitle, 40);
                } else {
                    // Typing is complete — remove blinking cursor
                    subtitleEl.classList.remove("typing-text");
                }
            }

            titleEl.textContent = "";
            subtitleEl.textContent = "";
            typeTitle();
        });



        // Video Modal
        let player;

        const videoMap = {
            desktop: "https://www.youtube.com/embed/l8JvWxEFVW0?si=wTv4YqzGjwfhf53M",
            mobile: "https://www.youtube.com/embed/unzc3SdI4dg?si=X4L6veRWbw7rjcgP" // Replace with your mobile video
        };

        function createYouTubePlayer(videoURL) {
            const videoId = new URL(videoURL).pathname.split("/embed/")[1].split("?")[0];

            player = new YT.Player("youtubePlayer", {
                height: "390",
                width: "640",
                videoId: videoId,
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        function onPlayerReady(event) {
            event.target.playVideo();
        }

        function onPlayerStateChange(event) {
            // State 0 = Ended
            if (event.data === YT.PlayerState.ENDED) {
                const btn = document.getElementById('appointmentButtonContainer');
                btn.style.display = 'block';
                btn.classList.add('animate-pop-in');
            }
        }

        // jQuery ready
        $(document).ready(function() {
            const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));

            $('#play-video-btn').on('click', function() {
                openVideo(videoMap.desktop);
            });

            $('#play-video-btn2').on('click', function() {
                openVideo(videoMap.mobile);
            });

            function openVideo(videoUrl) {
                // Clear previous video
                $('#youtubePlayer').html('');
                $('#appointmentButtonContainer').hide().removeClass('animate-pop-in');

                videoModal.show();

                // Load new video
                setTimeout(() => {
                    createYouTubePlayer(videoUrl);
                }, 300);
            }

            // Clean up when modal is closed
            $('#videoModal').on('hidden.bs.modal', function() {
                if (player && player.destroy) {
                    player.destroy();
                }
                $('#youtubePlayer').html('');
                $('#appointmentButtonContainer').hide().removeClass('animate-pop-in');
            });
        });
    </script>

    <script src="https://www.youtube.com/iframe_api"></script>
@endsection
