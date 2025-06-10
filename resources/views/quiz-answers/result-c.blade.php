@extends('layouts.quiz-layout')
@section('title', 'Quiz Result (CLOUDY CLIMBER) | P23 Africa')

@section('meta')
    <meta name="description" content="You’re on the path—but a little clarity could make your next step a leap.">
    <meta name="keywords" content="You’re on the path—but a little clarity could make your next step a leap.">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Business Consulting & Growth Solutions for Africa | P23 Africa">
    <meta property="og:image" content="{{ asset('images/cloudy_climber.png') }}">
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
                            <img src="{{ asset('images/cloudy_climber.png') }}" loading="lazy" alt="SurveyMonkey Graphs"
                                class="img-fluid" />
                        </div>
                        <ul class="chart-legend">
                            <div class="legend d-flex gap-2 flex-column">
                                <li>
                                    <div class="color-box purple"></div>
                                    Inconsistent tools & effort-based growth
                                </li>
                                <li>
                                    <div class="color-box dark-green"></div>
                                    Lack of visibility into performance
                                </li>
                                <li>
                                    <div class="color-box green"></div>
                                    Follow-up delays & customer drop-off
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
                                <p>Inconsistent tools & effort-based growth <span style="left: 15px;">40%</span></p>
                            </div>
                        </div>
                        <div class="metric">
                            <div class="progress-bar-container green-bg mb-3">
                                <div class="progress-fill green" data-width="35%"></div>
                            </div>
                            <div class="metric-item green">
                                <div class="color-box"></div>
                                <p>Lack of visibility into performance <span style="left: 15px;">35%</span></p>
                            </div>
                        </div>
                        <div class="metric">
                            <div class="progress-bar-container yellow-bg mb-3">
                                <div class="progress-fill yellow" data-width="30%"></div>
                            </div>
                            <div class="metric-item yellow">
                                <div class="color-box"></div>
                                <p>Follow-up delays & customer drop-off <span style="left: 15px;">30%</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="right-panel">
                        <div class="edge-shape"></div>

                        <div class="right-panel-container">
                            <div class=" common-risks-section mb-2">
                                <h3 class="mb-3">What this often looks like:</h3>
                                <div class="risk-list">
                                    <ul>
                                        <li>Follow-ups happening—but not timely</li>
                                        <li>Team using tools inconsistently or inefficiently</li>
                                        <li>
                                            Some growth—but mostly from effort, not strategy
                                        </li>
                                        <li>Unclear visibility into sales or marketing performance</li>
                                        <li>Customers falling out of the funnel without you knowing why</li>
                                    </ul>
                                </div>
                            </div>

                            <div class=" solution-section mb-2">
                                <h3 class="mb-3">What You Need:</h3>
                                <p>
                                    A proper growth strategy with practical, affordable systems. A few well-placed tools, a
                                    clear action plan, and team alignment can unlock serious gains.
                                </p>
                            </div>

                            <div class=" next-step-section">
                                <h3 class="mb-3">Next Step:</h3>
                                <p>
                                    Let’s review your business together. Our audit can show you what’s working, what’s
                                    not—and how to scale without burning out.
                                </p>
                            </div>
                        </div>

                        <div class="buttons-group gap-3">
                            <a href="#" class="btn btn-purple-outline">Book Business Audit Now!</a>
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
            const title = "Cloudy Climber";
            const subtitle =
                "You’re on the path—but a little clarity could make your next step a leap.";

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
