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

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

@section('content')
    <section class="brn-body">
        <div class="brn-main-container">
            <div class="container">
                <div class="brn-container" id="form1">
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
                                    <form action="">
                                        <input type="text" class="form-control" placeholder="Enter full name" />
                                        <input type="email" class="form-control"
                                            placeholder="Enter your official email address" />
                                        <input type="text" class="form-control"
                                            placeholder="Enter your WhatsApp phone Number" />
                                        <input type="text" class="form-control"
                                            placeholder="Enter your linkedin profile" />

                                        <a href="#" class="btn btn-submit" id="buttonOne">
                                            Next
                                            <span class="ms-2">
                                                <i class="bi bi-arrow-right"></i></span>
                                        </a>
                                    </form>
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

                <div class="brn-container" id="form2">
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
                                    <form action="">
                                        <input type="text" class="form-control" placeholder="Enter your Company name" />
                                        <input type="email" class="form-control"
                                            placeholder="Enter the country of your business headquarter" />
                                        <input type="text" class="form-control"
                                            placeholder="Enter your position in the business" />

                                        <div class="brn-input-checked">
                                            <div class="brn-input-checked-group">
                                                <span class="brn-input-checked-label">Select your business years of
                                                    operation</span>
                                                <div class="brn-input-checked-options-row">
                                                    <div class="brn-input-checked-option">
                                                        <input type="checkbox" id="years0-5"
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="years0-5" class="brn-input-checked-text">0-5</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="checkbox" id="years6-10"
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="years6-10" class="brn-input-checked-text">6-10</label>
                                                    </div>
                                                    <div class="brn-input-checked-option">
                                                        <input type="checkbox" id="years11-above"
                                                            class="brn-input-checked-checkbox" />
                                                        <label for="years11-above"
                                                            class="brn-input-checked-text">11-Above</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="brn-input-checked-group">
                                                <span class="brn-input-checked-label">Select your number of employees</span>
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
                                        </div>

                                        <a href="#" class="btn btn-submit" id="buttonTwo">
                                            Next
                                            <span class="ms-2">
                                                <i class="bi bi-arrow-right"></i></span>
                                        </a>
                                    </form>
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

                <div class="brn-container" id="form3">
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
                                    <form action="">
                                        <input type="text" class="form-control"
                                            placeholder="Enter your goals for joining the BRN" />

                                        <!-- ============== BRN ICONS CONTENT  -->
                                        <div class="brn-form-icons">
                                            <div class="brn-form-icons-header">
                                                What outcomes matter most to you?
                                            </div>
                                            <div class="brn-form-icons-options">
                                                <div class="brn-first-icons">
                                                    <div class="brn-form-icons-card selected">
                                                        <img src="./images/checkmark-badge.png" alt="" />
                                                        <span class="brn-form-icons-text">Network with like minds</span>
                                                    </div>
                                                    <div class="brn-form-icons-card">
                                                        <img src="./images/checkmark-badge.png" alt="" />
                                                        <span class="brn-form-icons-text">No more jump one platform for
                                                            another</span>
                                                    </div>
                                                </div>
                                                <div class="brn-second-icons">
                                                    <div class="brn-form-icons-card">
                                                        <img src="./images/checkmark-badge.png" alt="" />
                                                        <span class="brn-form-icons-text">Grow and Scale 1000%</span>
                                                    </div>
                                                    <div class="brn-form-icons-card">
                                                        <img src="./images/checkmark-badge.png" alt="" />
                                                        <span class="brn-form-icons-text">All you need is here</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="brn-input-checked">
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
                                        </div>

                                        <a href="#" class="btn btn-submit" id="buttonThree">
                                            Next
                                            <span class="ms-2">
                                                <i class="bi bi-arrow-right"></i></span>
                                        </a>
                                    </form>
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
            </div>
            </nav>
            </header>


            <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
            </script> -->
            <script src="./js/bootstrap.min.js"></script>
    </section>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", () => {
        let formOne = document.getElementById('form1');
        let formTwo = document.getElementById('form2');
        let formThree = document.getElementById('form3');

        formTwo.style.display = 'none';
        formThree.style.display = 'none';

        buttonOne.addEventListener('click', () => {
            formOne.style.display = 'none'
            formTwo.style.display = 'block'
            formThree.style.display = 'none'
        })
        buttonTwo.addEventListener('click', () => {
            formOne.style.display = 'none'
            formTwo.style.display = 'none'
            formThree.style.display = 'block'
        })
        buttonThree.addEventListener('click', () => {
            formOne.style.display = 'none'
            formTwo.style.display = 'none'
            formThree.style.display = 'block'
            window.alert('No Back-end Integration Yet!')
        })
    })
</script>
