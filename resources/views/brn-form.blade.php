@extends('layouts.brn-layout')
@section('title', 'NOELHQ Interest Form | P23 Africa')

@section('meta')
    <meta name="description"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth.">
    <meta name="keywords"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth.">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth | P23 Africa">
    <meta property="og:image" content="{{ asset('images/brn-form-meta.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

{{-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> --}}

<style>
    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .brn-form .next-btn {
        background-color: #193e47;
        color: #ffffff;
        padding: 12px 60px;
        /* width: 265px; */
        border-radius: 8px;
        font-size: 1.1rem;
        display: inline;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border: none;
        margin-top: 50px;
    }

    .brn-form .next-btn:hover {
        background-color: #1deaa5;
        color: #193e47;
    }

    .brn-form .prev-btn {
        background-color: #1deaa5;
        color: #ffffff;
        padding: 12px 60px;
        border-radius: 8px;
        font-size: 1.1rem;
        display: inline;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border: none;
        margin-top: 50px;
    }

    .brn-form .prev-btn:hover {
        background-color: #193e47;
        color: #1deaa5;
    }

    .brn-form-icons-card.selected {
        border-color: #6beabf;
        box-shadow: 0 4px 15px rgba(107, 234, 191, 0.4);
    }

    .brn-form-icons-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        padding: 10px;
        border-radius: 8px;
    }

    .brn-form-icons-card:hover {
        border-color: #6beabf;
        box-shadow: 0 4px 15px rgba(107, 234, 191, 0.4);
    }

    .brn-form-icons-header {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 20px;
    }




    /* Styling for the entire progress indicator section */
    .form-progress-indicator-wrapper {
        position: relative;
        width: 90%;
        max-width: 600px;
        margin: 0 auto 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .progress-image {
        width: 90%;
        height: auto;
        display: block;
    }

    .step-text-container {
        width: 120%;
        max-width: 720px;
        display: flex;
        justify-content: space-between;
        padding-top: 15px;
    }

    .step-text {
        flex: 1;
        text-align: center;
        padding: 0 5px;
    }

    .step-title {
        font-size: 0.9rem;
        font-weight: 1000;
        margin: 0;
        color: #111;
        white-space: nowrap;
    }

    .step-desc {
        font-size: 0.7rem;
        color: #555;
        margin: 0;
    }

    /* Error styling */
    .error-message {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-control.error {
        border: 2px solid #ef4444 !important;
        background-color: #fef2f2 !important;
    }

    .form-control:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(25, 62, 71, 0.1);
    }

    .brn-input-checked-group .error-message {
        background-color: #fef2f2;
        border: 1px solid #ef4444;
        border-radius: 4px;
        padding: 8px;
        margin-top: 8px;
    }

    #brnForm .btn i {
        display: none;

    }

    .brn-input-checked-options-row {
        margin-top: -7px;
    }





    /* UPDATED CHANGES */
    @media (max-width: 768px) {
        .step-text {
            display: none;
        }

        .step-text.active {
            display: block;
            display: flex;
            flex-direction: column;
            text-align: left;
            margin-left: 16px;
        }



        #brnForm .brn-form-icons-text {
            font-size: 0.9rem;
        }

        #brnForm .btn.next-btn,
        #brnForm .btn.prev-btn,
        #brnForm .btn.btn-submit {
            padding: 9px 40px;
            font-size: 1rem;
            margin-top: 20px;
        }

    }
</style>

@section('content')
    <section class="brn-body">
        <div class="brn-main-container">
            <div class="container">
                <form id="brnForm" action="{{ route('brn.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="brn-container form-step active" id="form1">


                        <div class="brn-header-container">
                            <div class="brn-header">
                                <h1>Personal Information</h1>
                                <div class="brn-header-desc">
                                    <span></span>
                                    <p>Let's start with your basic details</p>
                                </div>
                            </div>
                        </div>
                        <div class="brn-form-container">
                            <div class="form-conainer">
                                <div class="brn-content">
                                    <div class="brn-form">
                                        <div class="form-progress-indicator-wrapper">
                                            <img id="progress-image" src="{{ asset('images/step1.svg') }}"
                                                alt="Form Progress" class="progress-image">
                                            <div class="step-text-container">
                                                <div class="step-text active" data-step="1">
                                                    <p class="step-title">Personal Information</p>
                                                    <p class="step-desc">Let's start with your <br> basic details</p>
                                                </div>
                                                <div class="step-text " data-step="2">
                                                    <p class="step-title">Company Information</p>
                                                    <p class="step-desc">Tell us about your <br> business</p>
                                                </div>
                                                <div class="step-text" data-step="3">
                                                    <p class="step-title">Choose Interest</p>
                                                    <p class="step-desc">What would you like to get <br> from this
                                                        solution
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <form action=""> --}}
                                        <input type="text" name="name" required class="form-control"
                                            placeholder="Full Name" />

                                        <div style="position: relative;">
                                            <input type="email" name="email" required class="form-control"
                                                placeholder="Enter your official email address" />
                                            <div id="email-status"
                                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); display: none;">
                                                <svg id="email-checkmark"
                                                    style="width: 20px; height: 20px; color: #10b981; display: none;"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <svg id="email-spinner"
                                                    style="width: 20px; height: 20px; color: #6b7280; display: none; animation: spin 1s linear infinite;"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <circle style="opacity: 0.25;" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path style="opacity: 0.75;" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <input type="text" required name="phone" class="form-control"
                                            placeholder="Enter your WhatsApp phone Number" />
                                        <input type="url" required name="linkedin" class="form-control"
                                            placeholder="Enter your linkedin profile (e.g., https://linkedin.com/in/yourname)" />

                                        {{-- <a href="#" class="btn btn-submit" id="buttonOne">
                                            Next
                                            <span class="ms-2">
                                                <i class="bi bi-arrow-right"></i></span>
                                        </a> --}}
                                        <button type="button" class="btn next-btn" data-next="form2">Next <span
                                                class="ms-2">
                                                <i class="fa fa-arrow-right"></i></span></button>
                                        {{-- </form> --}}
                                    </div>
                                    <div class="brn-form-side-bg">
                                        <img src="{{ asset('images/brn-frame.png') }}" alt="" />
                                    </div>
                                </div>
                            </div>

                            <div class="brn-grouped-conainer">
                                <img class="brn-group" src="{{ asset('images/brn-group.png') }}" alt="">
                                <img class="brn-image" src="{{ asset('images/NOEL.svg') }}" alt="" />
                            </div>
                        </div>
                    </div>

                    <div class="brn-container form-step  " id="form2">
                        <div class="brn-header-container">
                            <div class="brn-header">
                                <h1>Company Information</h1>
                                <div class="brn-header-desc">
                                    <span></span>
                                    <p>Let's start with your basic details</p>
                                </div>
                            </div>
                        </div>
                        <div class="brn-form-container">
                            <div class="form-conainer">
                                <div class="brn-content">
                                    <div class="brn-form">

                                        <div class="form-progress-indicator-wrapper">
                                            <img id="progress-image" src="{{ asset('images/step2.svg') }}"
                                                alt="Form Progress" class="progress-image">
                                            <div class="step-text-container">
                                                <div class="step-text active" data-step="1">
                                                    <p class="step-title">Personal Information</p>
                                                    <p class="step-desc">Let's start with your <br> basic details</p>
                                                </div>
                                                <div class="step-text" data-step="2">
                                                    <p class="step-title">Company Information</p>
                                                    <p class="step-desc">Tell us about your <br> business</p>
                                                </div>
                                                <div class="step-text" data-step="3">
                                                    <p class="step-title">Choose Interest</p>
                                                    <p class="step-desc">What would you like to get <br> from this
                                                        solution
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <form action=""> --}}
                                        <input type="text" required name="company_name" class="form-control"
                                            placeholder="Company name" />
                                        <input type="text" required name="country" class="form-control"
                                            placeholder="Country" />
                                        <input type="text" required name="position" class="form-control"
                                            placeholder="Position in the business" />

                                        <div class="brn-input-checked">
                                            <div class="brn-input-checked-group">
                                                <span class="brn-input-checked-label">Select your business years of
                                                    operation</span>
                                                <div class="brn-input-checked-options-row">
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" value="0-5" name="years_of_operation"
                                                            id="years0-5" required class="brn-input-checked-checkbox" />
                                                        <label for="years0-5" class="brn-input-checked-text">0-5</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" value="6-10" name="years_of_operation"
                                                            id="years6-10" required class="brn-input-checked-checkbox" />
                                                        <label for="years6-10" class="brn-input-checked-text">6-10</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" required value="11-Above"
                                                            name="years_of_operation" id="years11-above"
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="years11-above"
                                                            class="brn-input-checked-text">11-Above</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="brn-input-checked-group">
                                                <span class="brn-input-checked-label">Select your number of
                                                    employees</span>
                                                <div class="brn-input-checked-options-row">
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" value="1-10" name="number_of_employees"
                                                            id="employees1-10" required
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="employees1-10"
                                                            class="brn-input-checked-text">1-10</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" value="11-50" name="number_of_employees"
                                                            id="employees11-50" required
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="employees11-50"
                                                            class="brn-input-checked-text">11-50</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" value="50-200" name="number_of_employees"
                                                            id="employees50-200" required
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="employees50-200"
                                                            class="brn-input-checked-text">50-200</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="radio" value="200+" name="number_of_employees"
                                                            id="employees200-plus" required
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="employees200-plus"
                                                            class="brn-input-checked-text">200+</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <a href="#" class="btn btn-submit" id="buttonTwo">
                                            Next
                                            <span class="ms-2">
                                                <i class="bi bi-arrow-right"></i></span>
                                        </a> --}}
                                        <div class="d-flex justify-content-between gap-2 ">

                                            <button type="button" class="btn prev-btn" data-prev="form1"><span
                                                    class="">
                                                    <i class="fa fa-arrow-left"></i></span> Back </button>
                                            <button type="button" class="btn next-btn" data-next="form3">Next <span
                                                    class="">
                                                    <i class="fa fa-arrow-right"></i></span></button>

                                        </div>

                                        {{-- </form> --}}
                                    </div>
                                    <div class="brn-form-side-bg">
                                        <img src="{{ asset('images/brn-frame.png') }}" alt="" />
                                    </div>
                                </div>
                            </div>

                            <div class="brn-grouped-conainer">
                                <img class="brn-group" src="{{ asset('images/brn-group.png') }}" alt="" />
                                <img class="brn-image" src="{{ asset('images/NOEL.svg') }}" alt="" />
                            </div>
                        </div>
                    </div>

                    <div class="brn-container form-step" id="form3">
                        <div class="brn-header-container">
                            <div class="brn-header">
                                <h1>What You Want To Gain</h1>
                                <div class="brn-header-desc">
                                    <span></span>
                                    <p>What outcomes matter most for you?</p>
                                </div>
                            </div>
                        </div>
                        <div class="brn-form-container">
                            <div class="form-conainer">
                                <div class="brn-content">
                                    <div class="brn-form">

                                        <div class="form-progress-indicator-wrapper">
                                            <img id="progress-image" src="{{ asset('images/step3.svg') }}"
                                                alt="Form Progress" class="progress-image">
                                            <div class="step-text-container">
                                                <div class="step-text active" data-step="1">
                                                    <p class="step-title">Personal Information</p>
                                                    <p class="step-desc">Let's start with your <br> basic details</p>
                                                </div>
                                                <div class="step-text" data-step="2">
                                                    <p class="step-title">Company Information</p>
                                                    <p class="step-desc">Tell us about your <br> business</p>
                                                </div>
                                                <div class="step-text" data-step="3">
                                                    <p class="step-title">Choose Interest</p>
                                                    <p class="step-desc">What would you like to get <br> from this
                                                        solution
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <form action=""> --}}
                                        <input type="text" name="goals" class="form-control"
                                            placeholder="Input reason for joining" />

                                        <!-- ============== BRN ICONS CONTENT  -->
                                        <div class="brn-form-icons">
                                            <div class="brn-form-icons-header">
                                                What outcome matter most to you?
                                            </div>
                                            <div class="brn-form-icons-options">
                                                <div class="brn-first-icons">
                                                    <div class="brn-form-icons-card" data-value="Network with like minds">
                                                        <img src="{{ asset('images/checkmark-badge.png') }}"
                                                            alt="" />
                                                        <span class="brn-form-icons-text">Network with like minds</span>
                                                    </div>
                                                    <div class="brn-form-icons-card"
                                                        data-value="No more jump one platform for another">
                                                        <img src="{{ asset('images/checkmark-badge.png') }}"
                                                            alt="" />
                                                        <span class="brn-form-icons-text">No more jump one platform for
                                                            another</span>
                                                    </div>
                                                </div>
                                                <div class="brn-second-icons">
                                                    <div class="brn-form-icons-card" data-value="Grow and Scale 100%">
                                                        <img src="{{ asset('images/checkmark-badge.png') }}"
                                                            alt="" />
                                                        <span class="brn-form-icons-text">Grow and Scale 100%</span>
                                                    </div>
                                                    <div class="brn-form-icons-card" data-value="All you need is here">
                                                        <img src="{{ asset('images/checkmark-badge.png') }}"
                                                            alt="" />
                                                        <span class="brn-form-icons-text">All you need is here</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Hidden input to store the selected value -->
                                            <input type="hidden" required name="selected_outcome"
                                                id="selectedOutcome" />
                                        </div>

                                        {{-- <div class="brn-input-checked">
                                            <div class="brn-input-checked-group">
                                                <span class="brn-input-checked-label">Select your number of
                                                    employees</span>
                                                <div class="brn-input-checked-options-row">
                                                    <div class="brn-input-checked-option">
                                                        <input type="checkbox" id="employees1-10"
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="employees1-10"
                                                            class="brn-input-checked-text">1-10</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="checkbox" id="employees11-50"
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="employees11-50"
                                                            class="brn-input-checked-text">11-50</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="checkbox" id="employees50-200"
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="employees50-200"
                                                            class="brn-input-checked-text">50-200</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="checkbox" id="employees200-plus"
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="employees200-plus"
                                                            class="brn-input-checked-text">200+</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}

                                        {{-- <a href="#" class="btn btn-submit" id="buttonThree">
                                            Next
                                            <span class="ms-2">
                                                <i class="bi bi-arrow-right"></i></span>
                                        </a> --}}
                                        <div class="d-flex justify-content-between gap-2 ">
                                            <button type="button" class="btn prev-btn" data-prev="form2"><span
                                                    class="">
                                                    <i class="bi bi-arrow-left"></i></span> Back</button>

                                            <button type="submit" id="submitBtn" class="btn btn-submit d-inline">
                                                <span class="btn-text">Submit</span>
                                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                                    aria-hidden="true"></span>
                                            </button>
                                        </div>
                                        {{-- </form> --}}
                                    </div>
                                    <div class="brn-form-side-bg">
                                        <img src="{{ asset('images/brn-frame.png') }}" alt="" />
                                    </div>
                                </div>
                            </div>

                            <div class="brn-grouped-conainer">
                                <img class="brn-group" src="{{ asset('images/brn-group.png') }}" alt="" />
                                <img class="brn-image" src="{{ asset('images/NOEL.svg') }}" alt="" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="responseModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4">
                    <h5 class="modal-title" id="responseMessage"></h5>
                    <button type="button" class="btn btn-primary mt-3" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>


        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script> -->
        {{-- <script src="./js/bootstrap.min.js"></script> --}}
    </section>
@endsection

<script>
    // Comprehensive form handling - single DOMContentLoaded event
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('brnForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const spinner = submitBtn.querySelector('.spinner-border');
        const responseMessage = document.getElementById('responseMessage');
        const modal = new bootstrap.Modal(document.getElementById('responseModal'));

        function showServerErrors(errors) {
            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(msg => msg.remove());
            document.querySelectorAll('input, select, textarea').forEach(input => {
                input.style.border = '';
                input.style.backgroundColor = '';
            });

            // Show field-specific errors
            Object.keys(errors).forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    const errorMessage = errors[field][0]; // Get first error message

                    input.style.border = '2px solid #ef4444';
                    input.style.backgroundColor = '#fef2f2';

                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message server-error';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '4px';
                    errorDiv.textContent = errorMessage;

                    input.parentNode.insertBefore(errorDiv, input.nextSibling);
                }
            });

            // Scroll to first error
            const firstError = document.querySelector('.server-error');
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        // Define validateStep function in this scope
        function validateStep(stepElement) {
            const stepId = stepElement.id;
            let isValid = true;

            // Clear all previous errors in this step
            stepElement.querySelectorAll('.error-message').forEach(msg => msg.remove());
            stepElement.querySelectorAll('input, select, textarea').forEach(input => {
                input.style.border = '';
                input.style.backgroundColor = '';
            });

            if (stepId === 'form1') {
                // Step 1: Personal Information validation
                const name = stepElement.querySelector('input[name="name"]');
                const email = stepElement.querySelector('input[name="email"]');
                const phone = stepElement.querySelector('input[name="phone"]');
                const linkedin = stepElement.querySelector('input[name="linkedin"]');

                if (!name.value.trim()) {
                    showError(name, 'Full name is required');
                    isValid = false;
                }

                if (!email.value.trim()) {
                    showError(email, 'Email address is required');
                    isValid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
                    showError(email, 'Please enter a valid email address');
                    isValid = false;
                } else {
                    // Check if email has red border (indicating it already exists)
                    const emailStyle = window.getComputedStyle(email);
                    if (emailStyle.borderColor === 'rgb(239, 68, 68)' || email.style.borderColor.includes(
                            '#ef4444')) {
                        showError(email, 'This email address is already registered');
                        isValid = false;
                    }
                    // Check if email validation is complete (has green checkmark)
                    const emailCheckmark = document.getElementById('email-checkmark');
                    if (emailCheckmark && emailCheckmark.style.display !== 'block') {
                        showError(email, 'Please wait for email validation to complete');
                        isValid = false;
                    }
                }

                if (!phone.value.trim()) {
                    showError(phone, 'Phone number is required');
                    isValid = false;
                }

                if (!linkedin.value.trim()) {
                    showError(linkedin, 'LinkedIn profile is required');
                    isValid = false;
                } else if (!linkedin.value.includes('linkedin.com')) {
                    showError(linkedin, 'Please enter a valid LinkedIn profile URL');
                    isValid = false;
                }

            } else if (stepId === 'form2') {
                // Step 2: Company Information validation
                const companyName = stepElement.querySelector('input[name="company_name"]');
                const country = stepElement.querySelector('input[name="country"]');
                const position = stepElement.querySelector('input[name="position"]');
                const yearsOfOperation = stepElement.querySelector('input[name="years_of_operation"]:checked');
                const numberOfEmployees = stepElement.querySelector(
                    'input[name="number_of_employees"]:checked');

                if (!companyName.value.trim()) {
                    showError(companyName, 'Company name is required');
                    isValid = false;
                }

                if (!country.value.trim()) {
                    showError(country, 'Business headquarters country is required');
                    isValid = false;
                }

                if (!position.value.trim()) {
                    showError(position, 'Your position in the business is required');
                    isValid = false;
                }

                if (!yearsOfOperation) {
                    const yearsGroup = stepElement.querySelector('.brn-input-checked-group');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '8px';
                    errorDiv.textContent = 'Please select your business years of operation';
                    yearsGroup.appendChild(errorDiv);
                    isValid = false;
                }

                if (!numberOfEmployees) {
                    const employeesGroup = stepElement.querySelectorAll('.brn-input-checked-group')[1];
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '8px';
                    errorDiv.textContent = 'Please select your number of employees';
                    employeesGroup.appendChild(errorDiv);
                    isValid = false;
                }

            } else if (stepId === 'form3') {
                // Step 3: Outcomes validation
                const selectedOutcome = stepElement.querySelector('input[name="selected_outcome"]');

                if (!selectedOutcome || !selectedOutcome.value) {
                    const iconsContainer = stepElement.querySelector('.brn-form-icons');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '12px';
                    errorDiv.style.textAlign = 'center';
                    errorDiv.textContent = 'Please select what outcomes matter most to you';
                    iconsContainer.appendChild(errorDiv);
                    isValid = false;
                }
            }

            return isValid;
        }

        function showError(input, message) {
            // Add error styling
            input.style.border = '2px solid #ef4444';
            input.style.backgroundColor = '#fef2f2';

            // Create error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.color = '#ef4444';
            errorDiv.style.fontSize = '0.875rem';
            errorDiv.style.marginTop = '4px';
            errorDiv.textContent = message;

            // Insert error message after input
            input.parentNode.insertBefore(errorDiv, input.nextSibling);
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate all steps before submission
            let allValid = true;
            const allSteps = document.querySelectorAll('.form-step');

            allSteps.forEach(step => {
                if (!validateStep(step)) {
                    allValid = false;
                }
            });

            if (!allValid) {
                // Show first error and switch to that step
                const firstErrorStep = document.querySelector('.form-step .error-message').closest(
                    '.form-step');
                if (firstErrorStep) {
                    // Switch to the step with error
                    document.querySelectorAll('.form-step').forEach(step => step.classList.remove(
                        'active'));
                    firstErrorStep.classList.add('active');

                    // Scroll to first error
                    const firstError = firstErrorStep.querySelector('.error-message');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }
                return;
            }

            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw {
                                status: response.status,
                                data: err
                            };
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Success response
                    responseMessage.textContent = data.message ||
                        'Your application was submitted successfully!';
                    responseMessage.style.color = '#059669';
                    modal.show();

                    // Reset form and go back to first step
                    form.reset();
                    document.querySelectorAll('.form-step').forEach(step => step.classList.remove(
                        'active'));
                    document.getElementById('form1').classList.add('active');

                    // Clear any selected outcome cards
                    document.querySelectorAll('.brn-form-icons-card').forEach(card => {
                        card.classList.remove('selected');
                    });
                })
                .catch(error => {
                    if (error.status === 422 && error.data.errors) {
                        // Validation errors from server
                        showServerErrors(error.data.errors);
                        responseMessage.textContent =
                            'Please correct the errors below and try again.';
                        responseMessage.style.color = '#ef4444';
                        modal.show();
                    } else if (error.status === 422 && error.data.message) {
                        // Custom validation message (like duplicate email)
                        responseMessage.textContent = error.data.message;
                        responseMessage.style.color = '#ef4444';
                        modal.show();
                    } else {
                        // Network or other errors
                        responseMessage.textContent =
                            'Something went wrong. Please check your internet connection and try again.';
                        responseMessage.style.color = '#ef4444';
                        modal.show();
                    }
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.classList.remove('d-none');
                });
        });

        // Clear server errors when user starts typing
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', function() {
                const serverError = this.parentNode.querySelector('.server-error');
                if (serverError) {
                    serverError.remove();
                    this.style.border = '';
                    this.style.backgroundColor = '';
                }
            });
        });

        // Handle icon selection with error clearing
        const cards = document.querySelectorAll('.brn-form-icons-card');
        const hiddenInput = document.getElementById('selectedOutcome');

        cards.forEach(card => {
            card.addEventListener('click', function() {
                // Remove 'selected' class from all cards
                cards.forEach(c => c.classList.remove('selected'));

                // Add 'selected' class to the clicked card
                this.classList.add('selected');

                // Update the hidden input value with the selected card's data-value
                hiddenInput.value = this.dataset.value;

                // Clear any error message for outcome selection
                const iconsContainer = document.querySelector('.brn-form-icons');
                const errorMsg = iconsContainer.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            });
        });

        // Form Slider with comprehensive validation
        const steps = document.querySelectorAll('.form-step');
        const nextBtns = document.querySelectorAll('.next-btn');
        const prevBtns = document.querySelectorAll('.prev-btn');

        function showStep(stepId) {
            const steps = document.querySelectorAll('.form-step');

            steps.forEach(step => step.classList.remove('active'));

            const nextStep = document.getElementById(stepId);
            if (nextStep) nextStep.classList.add('active');

            const stepTexts = nextStep.querySelectorAll('.step-text');
            stepTexts.forEach(text => text.classList.remove('active'));

            const activeStepIndex = Array.from(steps).findIndex(step => step.id === stepId);
            const activeStepText = nextStep.querySelector(`.step-text[data-step="${activeStepIndex + 1}"]`);
            if (activeStepText) activeStepText.classList.add('active');
        }

        function showError(input, message) {
            // Remove existing error
            clearError(input);

            // Add error styling
            input.style.border = '2px solid #ef4444';
            input.style.backgroundColor = '#fef2f2';

            // Create error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.color = '#ef4444';
            errorDiv.style.fontSize = '0.875rem';
            errorDiv.style.marginTop = '4px';
            errorDiv.textContent = message;

            // Insert error message after input
            input.parentNode.insertBefore(errorDiv, input.nextSibling);
        }

        function clearError(input) {
            input.style.border = '';
            input.style.backgroundColor = '';

            // Remove error message
            const errorMsg = input.parentNode.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.remove();
            }
        }

        function validateStepNavigation(stepElement) {
            const stepId = stepElement.id;
            let isValid = true;

            // Clear all previous errors in this step
            stepElement.querySelectorAll('.error-message').forEach(msg => msg.remove());
            stepElement.querySelectorAll('input, select, textarea').forEach(input => {
                clearError(input);
            });

            if (stepId === 'form1') {
                // Step 1: Personal Information validation
                const name = stepElement.querySelector('input[name="name"]');
                const email = stepElement.querySelector('input[name="email"]');
                const phone = stepElement.querySelector('input[name="phone"]');
                const linkedin = stepElement.querySelector('input[name="linkedin"]');

                if (!name.value.trim()) {
                    showError(name, 'Full name is required');
                    isValid = false;
                }

                if (!email.value.trim()) {
                    showError(email, 'Email address is required');
                    isValid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
                    showError(email, 'Please enter a valid email address');
                    isValid = false;
                } else {
                    // Check if email has red border (indicating it already exists)
                    const emailStyle = window.getComputedStyle(email);
                    if (emailStyle.borderColor === 'rgb(239, 68, 68)' || email.style.borderColor.includes(
                            '#ef4444')) {
                        showError(email, 'This email address is already registered');
                        isValid = false;
                    }
                    // Check if email validation is complete (has green checkmark)
                    const emailCheckmark = document.getElementById('email-checkmark');
                    if (emailCheckmark && emailCheckmark.style.display !== 'block') {
                        showError(email, 'Please wait for email validation to complete');
                        isValid = false;
                    }
                }

                if (!phone.value.trim()) {
                    showError(phone, 'WhatsApp phone number is required');
                    isValid = false;
                }

                if (!linkedin.value.trim()) {
                    showError(linkedin, 'LinkedIn profile is required');
                    isValid = false;
                } else if (!linkedin.value.toLowerCase().includes('linkedin.com')) {
                    showError(linkedin, 'Please enter a valid LinkedIn profile URL');
                    isValid = false;
                }

            } else if (stepId === 'form2') {
                // Step 2: Company Information validation
                const companyName = stepElement.querySelector('input[name="company_name"]');
                const country = stepElement.querySelector('input[name="country"]');
                const position = stepElement.querySelector('input[name="position"]');
                const yearsOfOperation = stepElement.querySelector('input[name="years_of_operation"]:checked');
                const numberOfEmployees = stepElement.querySelector(
                    'input[name="number_of_employees"]:checked');

                if (!companyName.value.trim()) {
                    showError(companyName, 'Company name is required');
                    isValid = false;
                }

                if (!country.value.trim()) {
                    showError(country, 'Business headquarters country is required');
                    isValid = false;
                }

                if (!position.value.trim()) {
                    showError(position, 'Your position in the business is required');
                    isValid = false;
                }

                if (!yearsOfOperation) {
                    const yearsGroup = stepElement.querySelector('.brn-input-checked-group');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '8px';
                    errorDiv.textContent = 'Please select your business years of operation';
                    yearsGroup.appendChild(errorDiv);
                    isValid = false;
                }

                if (!numberOfEmployees) {
                    const employeesGroup = stepElement.querySelectorAll('.brn-input-checked-group')[1];
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '8px';
                    errorDiv.textContent = 'Please select your number of employees';
                    employeesGroup.appendChild(errorDiv);
                    isValid = false;
                }

            } else if (stepId === 'form3') {
                // Step 3: Outcomes validation
                const selectedOutcome = stepElement.querySelector('input[name="selected_outcome"]');

                if (!selectedOutcome || !selectedOutcome.value) {
                    const iconsContainer = stepElement.querySelector('.brn-form-icons');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '12px';
                    errorDiv.style.textAlign = 'center';
                    errorDiv.textContent = 'Please select what outcomes matter most to you';
                    iconsContainer.appendChild(errorDiv);
                    isValid = false;
                }
            }

            return isValid;
        }

        nextBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentStep = this.closest('.form-step');

                if (validateStepNavigation(currentStep)) {
                    // Move to next step only if validation passes
                    const next = this.dataset.next;
                    showStep(next);
                } else {
                    // Scroll to first error
                    const firstError = currentStep.querySelector('.error-message');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    // Show validation feedback
                    btn.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        btn.style.transform = '';
                    }, 150);
                }
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const prev = this.dataset.prev;
                showStep(prev);
            });
        });

        // Real-time validation feedback
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', function() {
                if (this.style.border && this.style.border.includes('red')) {
                    clearError(this);
                }
            });
        });

        // Clear radio button errors when selected
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const group = this.closest('.brn-input-checked-group');
                const errorMsg = group.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            });
        });

        // Real-time email validation
        const emailInput = document.querySelector('input[name="email"]');
        const emailStatus = document.getElementById('email-status');
        const emailCheckmark = document.getElementById('email-checkmark');
        const emailSpinner = document.getElementById('email-spinner');
        let emailTimeout;

        function showEmailStatus(type) {
            emailStatus.style.display = 'block';
            emailCheckmark.style.display = type === 'success' ? 'block' : 'none';
            emailSpinner.style.display = type === 'loading' ? 'block' : 'none';

            if (type === 'hide') {
                emailStatus.style.display = 'none';
            }
        }

        function validateEmailInDatabase(email) {
            if (!email || !isValidEmail(email)) {
                showEmailStatus('hide');
                return;
            }

            showEmailStatus('loading');

            fetch('{{ route('brn.check-email') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        showEmailStatus('hide');
                        showError(emailInput, data.message);
                        emailInput.style.border = '2px solid #ef4444';
                        emailInput.style.backgroundColor = '#fef2f2';
                    } else {
                        showEmailStatus('success');
                        clearError(emailInput);
                        emailInput.style.border = '2px solid #10b981';
                        emailInput.style.backgroundColor = '#f0fdf4';
                    }
                })
                .catch(error => {
                    console.error('Email validation error:', error);
                    showEmailStatus('hide');
                });
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        if (emailInput) {
            emailInput.addEventListener('input', function() {
                clearTimeout(emailTimeout);
                const email = this.value.trim();

                // Clear previous styling
                this.style.border = '';
                this.style.backgroundColor = '';

                if (email === '') {
                    showEmailStatus('hide');
                    return;
                }

                // Debounce the API call
                emailTimeout = setTimeout(() => {
                    validateEmailInDatabase(email);
                }, 500);
            });

            emailInput.addEventListener('blur', function() {
                const email = this.value.trim();
                if (email && isValidEmail(email)) {
                    validateEmailInDatabase(email);
                }
            });
        }
    });
</script>
