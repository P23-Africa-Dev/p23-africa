@extends('layouts.stu-layout')
@section('title', 'University Partnership | P23 Africa')

@section('meta')
    <meta name="description" content="Partnering With Universities To Help Students Turn Data Into Rewards.">
    <meta name="keywords" content="Partnering With Universities To Help Students Turn Data Into Rewards.">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator"
        content="Join a continent-wide network where high-performing students collect real-world data that fuels business, policy, and research | P23 Africa">
    <meta property="og:image" content="{{ asset('images/stintro-bg.png') }}">
@endsection

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script> --}}

<style>
    .custom-modal {
        max-width: 70% !important;
        /* Adjust width as needed */
        width: 70% !important;
    }

    .custom-modal .modal-content {
        height: auto;
        /* Adjust height as needed */
    }

    @media screen and (max-width: 580px) {
        .custom-modal {
            max-width: 100% !important;
            width: 100% !important;
            height: auto;
        }

        .custom-modal .modal-content {
            height: auto;
            /* Adjust height as needed */
        }
    }
</style>


@section('content')
    <section class="whole-student">
        <section class="students-page-intro-section reveal-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12 left-content">
                        <span class="badge">Student Network</span>
                        <h1 class="main-heading">
                            Partnering With Universities To Help Students
                            <span class="highlight"> Turn Data </span> <span class="into">Into Rewards.</span>
                        </h1>
                        <p class="description">
                            Join a continent-wide network where high-performing students
                            collect real-world data that fuels business, policy, and research.
                        </p>
                        <a data-bs-toggle="modal" data-bs-target="#seatModal" href="#" class="join-button">
                            Join The Network <i class="fa fa-arrow-right arrow"></i>
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-12 right-content">
                        <img src="./images/stintro-bg.png" alt="Happy student" class="student-image" />
                        <div class="decorative-circles d-none d-lg-block">
                            <img src="./images/st-frame-intro.png" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="student-page-insight-section reveal-section">
            <div class="container">
                <h2 class="section-title mx-md-5 mx-3">Who Uses These Insights</h2>
                <div class="container page-insight-cards">
                    <div class="row g-4 align-items-start">
                        <div class="col-md-6 col-lg-4 d-flex">
                            <div class="card-item">
                                <div class="pb-md-3 pb-2 pt-md-4">
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
                                <div class="pb-md-3 pb-2 pt-md-4">
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
                                <div class="pb-md-3 pb-2 pt-md-4">
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

        <section class="reveal-section">
            <div class="students-page-driven-section">
                <div class="container">
                    <div class="page-driven-container">
                        <div class="left-content">
                            <div class="heading">
                                <h1>Students from Key Disciplines Driving Africa's Growth</h1>
                                <div class="icons-container">
                                    <img src="./images/title-bg.png" alt="" />
                                </div>
                            </div>

                            <p class="d-none d-lg-block">
                                We welcome curious, high-performing students from departments
                                that shape business, society, and innovation. If you're
                                passionate about solving real-world problems with data, insight,
                                and collaboration, this is for you.
                            </p>
                        </div>
                        <div class="slider-glow-wrapper position-relative">
                            <div class="slider-glow-top"></div>
                            <div class="slider-glow-bottom"></div>

                            <div class="slider-wrapper right-content">
                                <div class="slider-column">
                                    <div class="slider-track slider-one">
                                        @for ($i = 0; $i < 100; $i++)
                                            @foreach ($images as $image)
                                                {{-- @php
                                                    $bgClasses = ['blue', 'light-yellow', 'blue', 'light-yellow'];
                                                    $title = [
                                                        'Sociology & Anthropology',
                                                        'Business Administration & Management',
                                                        'Sociology & Anthropology',
                                                        'Business Administration & Management',
                                                    ];
                                                @endphp --}}
                                                <div class="card-item">
                                                    {{-- <h3 class="card-title">{{ $title[$index % count($title)] }}</h3>
                                                    <div class="card-image">
                                                        <img src="{{ asset('images/' . $image) }}" loading="lazy"
                                                            alt="Student" />
                                                    </div> --}}
                                                    <img src="{{ asset('images/' . $image) }}" loading="lazy"
                                                        alt="Student" />
                                                </div>
                                            @endforeach
                                        @endfor
                                    </div>
                                </div>

                                <div class="d-block d-sm-none slider-glow-wrapper2">
                                    <div class="slider-glow-bottom2"></div>
                                    <div class="slider-glow-top2"></div>

                                    <div class="fade-overlay">
                                        <div class="page-driven-content-overlay-mobile">
                                            <p class="">
                                                We welcome curious, high-performing students from departments
                                                that shape business, society, and innovation. If you're
                                                passionate about solving real-world problems with data,
                                                insight, and collaboration, this is for you.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="slider-column">
                                    <div class="slider-track slider-two">
                                        @for ($i = 0; $i < 100; $i++)
                                            @foreach ($images2 as $image2)
                                                {{-- @php
                                                    $bgClasses2 = ['orange', 'dark-blue', 'orange', 'dark-blue'];
                                                    $title2 = [
                                                        'Mass Communication & Media Studies',
                                                        'Political Science & Public Policy',
                                                        'Mass Communication & Media Studies',
                                                        'Political Science & Public Policy',
                                                    ];
                                                @endphp --}}
                                                <div class="card-item">
                                                    {{-- <h3 class="card-title">{{ $title2[$index2 % count($title2)] }}</h3>
                                                    <div class="card-image circle-mask">
                                                        <img src="{{ asset('images/' . $image2) }}" loading="lazy"
                                                            alt="Student" />
                                                    </div> --}}
                                                    <img src="{{ asset('images/' . $image2) }}" loading="lazy"
                                                        alt="Student" />
                                                </div>
                                            @endforeach
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="students-page-policyandresearch-section reveal-section">
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
                    <div class="grid-item" style="margin-top: 2rem;">
                        <div class="item-number">/04</div>
                        <div class="item-title">Grow With Mentorship</div>
                        <p>
                            Bridge the gap between academia and industry with applied
                            learning. Exposure to practical research methods
                        </p>
                    </div>
                    <div class="grid-item highlight">
                        <div class="item-number">/05</div>
                        <div class="item-title" style="max-width: 300px;">Boost Research <span
                                class="boost">Impact</span></div>
                        <p>
                            Drive meaningful change with data-backed discoveries that extend
                            beyond the classroom.
                        </p>
                        <a data-bs-toggle="modal" data-bs-target="#seatModal" href="#" class="btn-join">Join The
                            Movement <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="reveal-section">
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
                            real insights, we're building this platform for you.
                        </p>
                        <div class="checklist">
                            <div class="check-item">
                                <img src="./images/Check circle.png" alt="" /> For Students
                            </div>
                            <div class="check-item">
                                <img src="./images/Check circle.png" alt="" /> For University
                            </div>
                        </div>
                        <a data-bs-toggle="modal" data-bs-target="#seatModal" href="#" class="btn-join">Join The
                            Movement <i class="fa fa-arrow-right mx-3"></i></a>
                    </div>
                </div>
            </div>
        </section>




        <!-- Seat Booking Modal -->
        <div class="modal fade w-100" id="seatModal" tabindex="-1" aria-labelledby="seatModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered custom-modal">
                <div class="modal-content rounded-5 py-3 px-5">
                    <div class="modal-header border-0">
                        {{-- <h5 class="modal-title text-success" id="seatModalLabel">Book a Seat</h5> --}}
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mb-2" style="font-family: 'GT Walsheim Con';">
                        <h4 style="color: #0D4036; font-weight: 500;">Interest Form</h4>
                        <em>Kindly input your details to show your interest</em>
                        </p><br>
                        <form id="studentForm" class="ms-auto me-auto w-75">
                            @csrf
                            <div class="row g-20">
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control mb-5"
                                        placeholder="Full name" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="phone" class="form-control mb-5"
                                        placeholder="Contact Number (Optional)">
                                </div>

                                <div class="col-md-6">
                                    <input type="email" name="email" class="form-control mb-5" placeholder="Email"
                                        required>
                                </div>

                                <div class="col-md-6">
                                    <input type="text" name="occupation" class="form-control mb-5"
                                        placeholder="Occupation (Optional)">
                                </div>

                                <div class="col-md-6">
                                    <input type="text" name="uniname" class="form-control mb-5"
                                        placeholder="University Name" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="region" class="form-control mb-5" placeholder="Region"
                                        required>
                                </div>
                            </div>



                            <button type="submit" class="btn w-50" id="submitBtn">
                                <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                    id="submitSpinner"></span>
                                Submit <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <!-- Success Popup Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4 border-0 shadow rounded-4">
                    <h5 class="mb-3" style="color: #0D4036; font-weight: 500; font-family: 'GT Walsheim Con';">
                        Thank you, we’ll get back as soon we review <br> your request
                        {{-- <span class="">— please check your inbox.</span> --}}
                    </h5>
                    <a href="{{ route('student') }}" id="closeModel" class="btn mt-3"
                        style="background-color: #0D4036; color: #fff; font-family: 'GT Walsheim Con';">
                        Done <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

<script>
    // Pop Up
    document.addEventListener('DOMContentLoaded', function() {
        const studentForm = document.getElementById('studentForm');
        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('submitSpinner');

        // Attach click listener to all "Book A Seat" buttons
        // document.querySelectorAll('.actionBtn').forEach(button => {
        //     button.addEventListener('click', function() {
        //         currentEventId = this.getAttribute('data-event-id');
        //         eventIdInput.value = currentEventId;
        //     });
        // });

        studentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // if (!currentEventId) {
            //     alert("Event ID not found. Please try again.");
            //     return;
            // }

            spinner.classList.remove('d-none');
            submitBtn.disabled = true;

            const formData = new FormData(studentForm);

            fetch(`/submit-student`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    spinner.classList.add('d-none');
                    submitBtn.disabled = false;

                    if (data.success === false) {
                        alert('Something went wrong. Please try again.');
                    } else {
                        studentForm.reset();
                        bootstrap.Modal.getInstance(document.getElementById('seatModal')).hide();
                        new bootstrap.Modal(document.getElementById('successModal')).show();
                    }
                })
                .catch(error => {
                    console.error(error);
                    spinner.classList.add('d-none');
                    submitBtn.disabled = false;
                    alert('Error submitting the form.');
                });
        });
    });
</script>
