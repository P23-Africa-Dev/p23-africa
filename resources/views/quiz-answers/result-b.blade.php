@extends('layouts.quiz-layout')
@section('title', 'Quiz Result (HIDDEN POWERHOUSE) | P23 Africa')

@section('meta')
    <meta name="description" content="Your revenue stream is vulnerable—small cracks today could become big breaks tomorrow.">
    <meta name="keywords" content="Your revenue stream is vulnerable—small cracks today could become big breaks tomorrow.">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Business Consulting & Growth Solutions for Africa | P23 Africa">
    <meta property="og:image" content="{{ asset('images/Hidden_Powerhouse.png') }}">
@endsection

@section('content')
    <section class="fragile">
        <div class="fragile-funnel-container container">
            <div class="row g-4">
                <div class="col-lg-6 left-column">
                    <div class="header-section mb-4">
                        <h1>
                            <span>YOU ARE THE</span>
                            <span id="typing-title" class="typing-text"></span>
                        </h1>
                        <p id="typing-subtitle" class="typing-text">

                        </p>
                    </div>

                    <div class="chart-container d-flex flex-column align-items-start mb-4 mb-lg-0">
                        <div class="chart-section">
                            <img src="{{ asset('images/Hidden_Powerhouse.png') }}" loading="lazy" alt="SurveyMonkey Graphs"
                                class="img-fluid" />
                        </div>
                        <ul class="chart-legend">
                            <div class="legend d-flex gap-2 flex-column">
                                <li>
                                    <div class="color-box purple"></div>
                                    System & conversion optimization
                                </li>
                                <li>
                                    <div class="color-box dark-green"></div>
                                    New growth channels & strategies
                                </li>
                                <li>
                                    <div class="color-box green"></div>
                                    Refinement & performance insights
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
                                <p>System & conversion optimization <span>40%</span></p>
                            </div>
                        </div>
                        <div class="metric">
                            <div class="progress-bar-container green-bg mb-3">
                                <div class="progress-fill green" data-width="35%"></div>
                            </div>
                            <div class="metric-item green">
                                <div class="color-box"></div>
                                <p>New growth channels & strategies <span>35%</span></p>
                            </div>
                        </div>
                        <div class="metric">
                            <div class="progress-bar-container yellow-bg mb-3">
                                <div class="progress-fill yellow" data-width="25%"></div>
                            </div>
                            <div class="metric-item yellow">
                                <div class="color-box"></div>
                                <p>Refinement & performance insights <span>25%</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="right-panel">
                        <div class="edge-shape"></div>

                        <div class="right-panel-container">
                            <div class=" common-risks-section mb-2">
                                <h3 class="mb-3">Signs Of Readiness To Scale:</h3>
                                <div class="risk-list">
                                    <ul>
                                        <li>Consistent follow-ups and team clarity</li>
                                        <li>Tools and systems are in place and used well</li>
                                        <li>
                                            Clear understanding of customer journey
                                        </li>
                                        <li>Regular performance reviews and insights</li>
                                        <li>You’re proactive, not reactive</li>
                                    </ul>
                                </div>
                            </div>

                            <div class=" solution-section mb-2">
                                <h3 class="mb-3">What You Need:</h3>
                                <p>
                                    A tailored growth plan. Now’s the time to identify advanced strategies: tightening
                                    conversion points, exploring new channels, or creating scalable systems.
                                </p>
                            </div>

                            <div class=" next-step-section">
                                <h3 class="mb-3">Next Step:</h3>
                                <p>
                                    We can help you refine your growth plan and plug small gaps for big results. Let’s talk.
                                </p>
                            </div>
                        </div>

                        <div class="buttons-group gap-3">
                            <a href="https://calendar.google.com/calendar/u/0/appointments/schedules/AcZssZ3tzdnFU-zme-lCpKoHGZktqjUVs4rll_QJtcUzK71d9-l3M0WIFvjtnJpLmosUraV8XkUJeD_k" target="_blank" class="btn btn-purple-outline">Book Business Audit Now!</a>
                            <a href="#" class="btn btn-dark-green">
                                Get Growth Tips
                                <i class="bi bi-arrow-right"></i>
                            </a>
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
            const title = "Hidden Powerhouse";
            const subtitle =
                "Your revenue stream is vulnerable—small cracks today could become big breaks tomorrow.";

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

            // Start typing as soon as DOM is ready
            typeTitle();
        });
    </script>
@endsection
