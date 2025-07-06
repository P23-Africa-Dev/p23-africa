@extends('layouts.brn-layout')
@section('title', 'BRN Form | P23 Africa')

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
        <section class="main-container">
            <div class="brn-container">
                <div class="brn-header-container">
                    <div class="brn-header">
                        <h1>Interest Form</h1>
                        <p>
                            The Business Referral Network is our community of founders and
                            CXOs who come together to share valuable referrals and create
                            business partnerships. The BRN and their events are strictly by
                            invitation. Please register your interest and we will reach out to
                            you if you are selected.
                        </p>
                    </div>
                </div>
                <div class="brn-form-container">
                    <div class="form-conainer">
                        <div class="brn-content">
                            <div class="brn-form">
                                <form action="">
                                    <input type="text" class="form-control" placeholder="Enter full name" />
                                    <input type="text" class="form-control"
                                        placeholder="Enter your WhatsApp phone Number" />
                                    <input type="email" class="form-control"
                                        placeholder="Enter your official email address" />
                                    <input type="text" class="form-control" placeholder="Enter your company name" />
                                    <input type="text" class="form-control"
                                        placeholder="Enter the country of your business headquarter" />
                                    <input type="text" class="form-control"
                                        placeholder="Enter your business years of operation" />
                                    <input type="text" class="form-control"
                                        placeholder="Enter your number of employees" />
                                    <input type="text" class="form-control"
                                        placeholder="Tell us your number of employees" />
                                    <input type="text" class="form-control"
                                        placeholder="Enter your position in the business" />
                                    <input type="text" class="form-control" placeholder="Enter your linkedin profile" />
                                    <input type="text" class="form-control"
                                        placeholder="Enter your goals for joining the BRN" />
                                    <input type="text" class="form-control"
                                        placeholder="Enter your most Business achievement" />
                                    <input type="text" class="form-control"
                                        placeholder="Enter  your company most biggest challenge" />
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="consentCheck" />
                                        <label class="form-check-label" for="consentCheck">
                                            I hereby consent to receive regular mail and text updates
                                            from PSS Africa
                                        </label>
                                    </div>
                                    <button type="submit" class="btn-submit">
                                        Submit
                                        <span class="ms-2"> <i class="bi bi-arrow-right"></i></span>
                                    </button>
                                </form>
                            </div>
                            <div class="brn-form-side-bg"></div>
                        </div>
                    </div>
                    <img class="brn-image" src="./images/BRN.png" alt="" />
                </div>
            </div>
        </section>
    </section>
@endsection
