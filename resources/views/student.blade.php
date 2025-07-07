@extends('layouts.stu-layout')
@section('title', 'Student | P23 Africa')

@section('meta')
    <meta name="description"
        content="Explore Expert Articles, Market Trends, And Practical Advice To Scale Your Business Across Africa And Beyond.">
    <meta name="keywords"
        content="Explore Expert Articles, Market Trends, And Practical Advice To Scale Your Business Across Africa And Beyond.">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator"
        content="Explore Expert Articles, Market Trends, And Practical Advice To Scale Your Business Across Africa And Beyond | P23 Africa">
    <meta property="og:image" content="{{ asset('images/resource-header.png') }}">
@endsection

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

@section('content')
    <section>
        <section class="students-page-intro-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12 left-content">
                        <span class="badge">Student Network</span>
                        <h1 class="main-heading">
                            Partnering With Universities To Help Students
                            <span class="highlight">Turn Data</span> Into Rewards.
                        </h1>
                        <p class="description">
                            Join a continent-wide network where high-performing students
                            collect real-world data that fuels business, policy, and research.
                        </p>
                        <a href="#" class="join-button">
                            Join The Network <i class="bi bi-arrow-right arrow"></i>
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-12 right-content d-flex">
                        <img src="./images/stintro-bg.png" alt="Happy student" class="student-image" />
                        <div class="decorative-circles d-none d-lg-block">
                            <img src="./images/st-frame-intro.png" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="student-page-insight-section">
            <div class="container">
                <h2 class="section-title">Who Uses These Insights</h2>
                <div class="container page-insight-cards">
                    <div class="row g-5 align-items-start">
                        <div class="col-md-6 col-lg-4 d-flex">
                            <div class="card-item">
                                <div>
                                    <div class="card-icon">
                                        <img src="./images/b-logo-st.png" alt="" />
                                    </div>
                                    <h3 class="card-title">Businesses</h3>
                                    <p class="card-description">
                                        Gain real-time, grassroots data to inform market entry,
                                        customer behavior, and operational strategies.
                                    </p>
                                </div>
                                <a href="#" class="action-button">
                                    <div class="act-icon">
                                        <img src="./images/b-ai-chat-01-st.png" alt="" />
                                    </div>
                                    Make informed decisions with real-time market insights.
                                </a>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 d-flex">
                            <div class="card-item policymakers">
                                <div>
                                    <div class="card-icon">
                                        <img src="./images/polisy-log-st.png" alt="" />
                                    </div>
                                    <h3 class="card-title">Policymakers</h3>
                                    <p class="card-description">
                                        Access evidence-based insights to design inclusive,
                                        data-driven policies that reflect on-the-ground realities.
                                    </p>
                                </div>
                                <a href="#" class="action-button">
                                    <div class="act-icon">
                                        <img src="./images/ai-chat-01.png" alt="" />
                                    </div>
                                    Design people-centered policies backed by data.
                                </a>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 d-flex">
                            <div class="card-item researchers">
                                <div>
                                    <div class="card-icon">
                                        <img src="{{ asset('images/res-logo-st.png') }}" loading="lazy" alt="" />
                                    </div>
                                    <h3 class="card-title">Researchers</h3>
                                    <p class="card-description">
                                        Tap into rich, field-collected data to support
                                        context-specific academic and development research.
                                    </p>
                                </div>
                                <a href="#" class="action-button">
                                    <div class="act-icon">
                                        <img src="{{ asset('images/b-ai-chat-01-st.png') }}" loading="lazy"
                                            alt="" />
                                    </div>
                                    Conduct relevant research using contextual field data.
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="">
            <div class="students-page-driven-section">
                <div class="container">
                    <div class="page-driven-container">
                        <div class="left-content">
                            <div>
                                <h1>Students from Key Disciplines Driving Africa's Growth</h1>
                                <div class="icons-container">
                                    <img src="./images/title-bg.png" alt="" />
                                </div>
                            </div>

                            <p class="d-none d-lg-block">
                                We welcome curious, high-performing students from departments
                                that shape business, society, and innovation. If you're
                                passionate about solving real-world problems with data, insight,
                                and collaboration—this is for you.
                            </p>
                        </div>
                        <div class="right-content">
                            <div class="swiper slider-one">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide card-item blue">
                                        <h3 class="card-title">Sociology & Anthropology</h3>
                                        <div class="card-image">
                                            <img src="{{ asset('images/driven-bg-1.png') }}" loading="lazy"
                                                alt="Student 1" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide card-item light-yellow">
                                        <h3 class="card-title">
                                            Business Administration & Management
                                        </h3>
                                        <div class="card-image circle-mask">
                                            <img src="{{ asset('images/driven-bg-4.png') }}" loading="lazy"
                                                alt="Student 1" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide card-item blue">
                                        <h3 class="card-title">Sociology & Anthropology</h3>
                                        <div class="card-image">
                                            <img src="{{ asset('images/driven-bg-1.png') }}" loading="lazy"
                                                alt="Student 1" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide card-item light-yellow">
                                        <h3 class="card-title">
                                            Business Administration & Management
                                        </h3>
                                        <div class="card-image circle-mask">
                                            <img src="{{ asset('images/driven-bg-4.png') }}" loading="lazy"
                                                alt="Student 1" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-block d-sm-none">
                                <div class="fade-overlay">
                                    {{-- <div class="fade-overlay fade-top"></div>
                                    <div class="fade-overlay fade-bottom"></div> --}}

                                    <div class="page-driven-content-overlay-mobile">
                                        <p class="">
                                            We welcome curious, high-performing students from departments
                                            that shape business, society, and innovation. If you're
                                            passionate about solving real-world problems with data,
                                            insight, and collaboration—this is for you.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper slider-two">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide card-item orange">
                                        <h3 class="card-title">
                                            Mass Communication & Media Studies
                                        </h3>
                                        <div class="card-image circle-mask">
                                            <img src="{{ asset('images/driven-bg-2.png') }}" loading="lazy"
                                                alt="Student 1" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide card-item dark-blue">
                                        <h3 class="card-title">
                                            Political Science & Public Policy
                                        </h3>
                                        <div class="card-image circle-mask">
                                            <img src="{{ asset('images/driven-bg-3.png') }}" loading="lazy"
                                                alt="Student 1" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide card-item orange">
                                        <h3 class="card-title">
                                            Mass Communication & Media Studies
                                        </h3>
                                        <div class="card-image circle-mask">
                                            <img src="{{ asset('images/driven-bg-2.png') }}" loading="lazy"
                                                alt="Student 1" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide card-item dark-blue">
                                        <h3 class="card-title">
                                            Political Science & Public Policy
                                        </h3>
                                        <div class="card-image circle-mask">
                                            <img src="{{ asset('images/driven-bg-3.png') }}" loading="lazy"
                                                alt="Student 1" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="students-page-policyandresearch-section">
            <div class="container">
                <h2 class="section-title">
                    Why it matters <span> (Serving Business, Policy, and Research)</span>
                </h2>
                <div class="policyandresearch-grid-container">
                    <div class="grid-item">
                        <div class="item-number">/01</div>
                        <div class="item-title">Hands-On Experience</div>
                        <p>
                            Get involved in real field research that matters. Contribute to
                            studies with real-world relevance and influence.
                        </p>
                    </div>
                    <div class="grid-item">
                        <div class="item-number">/02</div>
                        <div class="item-title">Network & Collaborate</div>
                        <p>
                            Work alongside professionals across industries. Strengthen your
                            role in industry-level research.
                        </p>
                    </div>
                    <div class="grid-item">
                        <div class="item-number">/03</div>
                        <div class="item-title">Earn While You Learn</div>
                        <p>
                            Access funding for approved university participation and
                            mentorship. Receive compensation for verified contributions.
                        </p>
                    </div>
                    <div class="grid-item">
                        <div class="item-number">/04</div>
                        <div class="item-title">Grow With Mentorship</div>
                        <p>
                            Bridge the gap between academia and industry with applied
                            learning. Exposure to practical research methods
                        </p>
                    </div>
                    <div class="grid-item highlight">
                        <div class="item-number">/05</div>
                        <div class="item-title" style="max-width: 300px;">Boost Research Impact</div>
                        <p>
                            Drive meaningful change with data-backed discoveries that extend
                            beyond the classroom.
                        </p>
                        <a href="#" class="btn-join">Join The Movement <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="">
            <div class="container">
                <div class="students-page-future-section">
                    <div class="image-container">
                        <img src="./images/build-st-bg.png" alt="" />
                    </div>
                    <div class="content-right">
                        <h2>Let's Build the Future Together</h2>
                        <p>
                            Whether you're a student looking to build your career, a
                            university aiming to elevate your research, or a business needing
                            real insights—we're building this platform for you.
                        </p>
                        <div class="checklist">
                            <div class="check-item">
                                <img src="./images/Check circle.png" alt="" /> For Students
                            </div>
                            <div class="check-item">
                                <img src="./images/Check circle.png" alt="" /> For University
                            </div>
                        </div>
                        <a href="#" class="btn-join">Join The Movement <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
