@extends('layouts.brn-layout')
@section('title', 'BRN Form | P23 Africa')

@section('meta')
    <meta name="description"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth.">
    <meta name="keywords"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth.">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator"
        content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth | P23 Africa">
    <meta property="og:image" content="{{ asset('images/brn-form-meta.png') }}">
@endsection

{{-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> --}}

<style>
    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
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
                                            <img id="progress-image" src="./images/step1.svg" alt="Form Progress"
                                                class="progress-image">
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
                                        <input type="text" name="name" required class="form-control"
                                            placeholder="Enter full name" />

                                        <input type="email" name="email" required class="form-control"
                                            placeholder="Enter your official email address" />
                                        <input type="text" required name="phone" class="form-control"
                                            placeholder="Enter your WhatsApp phone Number" />
                                        <input type="text" required name="linkedin" class="form-control"
                                            placeholder="Enter your linkedin profile" />

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
                                <img class="brn-group" src="./images/brn-group.png" alt="">
                                <img class="brn-image" src="./images/BRN.png" alt="" />
                            </div>
                        </div>
                    </div>

                    <div class="brn-container form-step" id="form2">
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
                                            <img id="progress-image" src="./images/step2.svg" alt="Form Progress"
                                                class="progress-image">
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
                                            placeholder="Enter your Company name" />
                                        <input type="text" required name="country" class="form-control"
                                            placeholder="Enter the country of your business headquarter" />
                                        <input type="text" required name="position" class="form-control"
                                            placeholder="Enter your position in the business" />

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
                                        <button type="button" class="btn prev-btn" data-prev="form1"><span
                                                class="ms-2">
                                                <i class="fa fa-arrow-left"></i></span> Back </button>
                                        <button type="button" class="btn next-btn" data-next="form3">Next <span
                                                class="ms-2">
                                                <i class="fa fa-arrow-right"></i></span></button>
                                        {{-- </form> --}}
                                    </div>
                                    <div class="brn-form-side-bg">
                                        <img src="./images/brn-frame.png" alt="" />
                                    </div>
                                </div>
                            </div>

                            <div class="brn-grouped-conainer">
                                <img class="brn-group" src="./images/brn-group.png" alt="" />
                                <img class="brn-image" src="./images/BRN.png" alt="" />
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
                                            <img id="progress-image" src="./images/step3.svg" alt="Form Progress"
                                                class="progress-image">
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
                                            placeholder="Enter your goals for joining the BRN" />

                                        <!-- ============== BRN ICONS CONTENT  -->
                                        <div class="brn-form-icons">
                                            <div class="brn-form-icons-header">
                                                What outcomes matter most to you?
                                            </div>
                                            <div class="brn-form-icons-options">
                                                <div class="brn-first-icons">
                                                    <div class="brn-form-icons-card" data-value="Network with like minds">
                                                        <img src="./images/checkmark-badge.png" alt="" />
                                                        <span class="brn-form-icons-text">Network with like minds</span>
                                                    </div>
                                                    <div class="brn-form-icons-card"
                                                        data-value="No more jump one platform for another">
                                                        <img src="./images/checkmark-badge.png" alt="" />
                                                        <span class="brn-form-icons-text">No more jump one platform for
                                                            another</span>
                                                    </div>
                                                </div>
                                                <div class="brn-second-icons">
                                                    <div class="brn-form-icons-card" data-value="Grow and Scale 1000%">
                                                        <img src="./images/checkmark-badge.png" alt="" />
                                                        <span class="brn-form-icons-text">Grow and Scale 1000%</span>
                                                    </div>
                                                    <div class="brn-form-icons-card" data-value="All you need is here">
                                                        <img src="./images/checkmark-badge.png" alt="" />
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
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn prev-btn" data-prev="form2"><span
                                                    class="ms-2">
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
                                        <img src="./images/brn-frame.png" alt="" />
                                    </div>
                                </div>
                            </div>

                            <div class="brn-grouped-conainer">
                                <img class="brn-group" src="./images/brn-group.png" alt="" />
                                <img class="brn-image" src="./images/BRN.png" alt="" />
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </section>
@endsection

<script>
    // Form Submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('brnForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const spinner = submitBtn.querySelector('.spinner-border');
        const responseMessage = document.getElementById('responseMessage');
        const modal = new bootstrap.Modal(document.getElementById('responseModal'));

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Show spinner
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            // Simulate form submission
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) { // Assuming backend sends success: true
                        responseMessage.textContent = data.message ||
                        'Form submitted successfully!';
                        modal.show();

                        // Clear form only if submission succeeded
                        form.reset();
                    } else {
                        responseMessage.textContent = data.message ||
                            'Submission failed. Please try again.';
                        modal.show();
                    }
                })
                .catch(error => {
                    responseMessage.textContent = 'Something went wrong. Please try again.';
                    modal.show();
                })
                .finally(() => {
                    // Reset button
                    spinner.classList.add('d-none');
                    btnText.classList.remove('d-none');
                    form.reset();
                });
        });
    });



    // Handle icon selection
    document.addEventListener('DOMContentLoaded', function() {
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
            });
        });
    });


    // Form Slider with required field validation
    document.addEventListener('DOMContentLoaded', function() {
        const steps = document.querySelectorAll('.form-step');
        const nextBtns = document.querySelectorAll('.next-btn');
        const prevBtns = document.querySelectorAll('.prev-btn');

        function showStep(stepId) {
            steps.forEach(step => step.classList.remove('active'));
            const nextStep = document.getElementById(stepId);
            if (nextStep) nextStep.classList.add('active');
        }

        nextBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentStep = this.closest('.form-step');

                // Find required inputs in this step
                const requiredInputs = currentStep.querySelectorAll(
                    'input[required], select[required], textarea[required]');

                let valid = true;

                requiredInputs.forEach(input => {
                    if (!input.value.trim()) {
                        valid = false;
                        input.style.border = '1px solid red';
                    } else {
                        input.style.border = '';
                    }
                });

                if (!valid) {
                    // Scroll to first invalid field
                    const firstInvalid = currentStep.querySelector(
                        'input[required]:invalid, select[required]:invalid, textarea[required]:invalid'
                        ) || requiredInputs[0];
                    if (firstInvalid) firstInvalid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return; // Stop navigation
                }

                // Move to next step
                const next = this.dataset.next;
                showStep(next);
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const prev = this.dataset.prev;
                showStep(prev);
            });
        });
    });
</script>
