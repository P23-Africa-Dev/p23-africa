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
@endsection

{{-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> --}}

<style>

    body .interest-form-layout-container{
             font-family: "Montserrat", sans-serif;

    }
            body .hidden {
            display: none;
        
            }
            /* .interest-form-layout-container */
            .interest-form-layout-container {
            max-width: 1264px;
            margin: 0 auto;
            margin-top: 100px;
            margin-bottom: 60px;
            }

            .form-layout-container {
            padding: 20px 22px;
            /* padding: 20px 12px 20px 23px; */
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            }

            /*====================================================================
            ========================== Interest Heading Heading Container ========================= */
            .interest-form-heading {
            max-width: 600px;
            margin: 0 auto;
            }
            .interest-form-heading .header h1 {
            font-size: 37px;
            font-weight: 500;
            color: #0d4036;
            margin: 0 0 10px 0;
            }

            
            .interest-form-layout-container .interest-form-heading > div.third-form-heading .header h1{
                          font-size: 30px;

            }
      

            .interest-form-heading .subtitle {
            display: flex;
            align-items: center;
            }

            .interest-form-heading .subtitle .line {
            width: 20vw;
            max-width: 120px;
            height: 3px;
            background-color: #137077;
            border-radius: 2px;
            margin-right: 6px;
            }

            .interest-form-heading .subtitle p {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: #137077;
            }

      
      

            /* 2. Description Section */
            .interest-form-heading .description {
            margin: 30px 0;
            }

            .interest-form-heading .description p {
            font-size: 13px;
            color: #0d4036;
            line-height: 1.5;
            margin: 0;
            }

            /* 3. Progress Stepper */
            .progress-step-container {
            width: 100%;
            }
            .interest-form-heading .progress-stepper {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            margin-top: 10px;
            }

            /* This ::before pseudo-element creates the gray line */
            .interest-form-heading .progress-stepper::before {
            content: "";
            position: absolute;
            top: 21px;
            left: 12%;
            right: 12%;
            height: 4px;
            background-color: #ebefee;
            z-index: 1;
            transform: translateY(-50%);
            }

            .interest-form-heading .progress-stepper::after {
            content: "";
            position: absolute;
            top: 21px;
            left: 12%;
            right: 12%;
            height: 4px;
            background-color: #137077;
            z-index: 1;
            transform: translateY(-50%);
            width: var(--progress-width, 0%);
            }

            .interest-form-heading .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 80px;
            position: relative;
            z-index: 2;
            }

            .interest-form-heading .step .circle {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            transition: all 0.3s ease;

            /* Inactive State Styles */
            background-color: #ebefee;
            border: 3px solid #cfd0d0;
            color: #cfd0d0;
            }

            .interest-form-heading .step .label {
            margin-top: 8px;
            font-size: 10px;
            font-weight: 800;
            transition: all 0.3s ease;
          
            color: #137077;
            font-weight: 700;
            }

            /* Active State Styles */

            .interest-form-heading .step.active .circle {
            background-color: #3daab2;
            border-color: #137077;
            color: #ffffff;
            }
/* 
            .interest-form-heading .step.active .label {
            color: #137077;
            font-weight: 700;
            } */
            .step.completed .circle {
                   background-color: #3daab2;

            border-color: #137077;
            color: #fff;
            }

      
            /*====================================================================
            ========================== Interest Form Container ========================= */
            .interest-form-container {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            }

            .interest-form-container .form-container {
            background-color: #137077;
            padding: 65px 30px;
            border-radius: 35px;
            width: 100%;
            max-width: 650px;

            margin-top: 40px;

            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            }

            .interest-form {
            display: flex;
            flex-direction: column;
            }

            /* Form Group (Label + Input/Select) */
            .interest-form-group {
            margin-bottom: 25px;
            }

            .interest-form-group:last-of-type {
            margin-bottom: 40px;
            }

            label {
            display: block;
            font-size: 13px;
            font-weight: 400;
            color: #f0f0f0;
            margin-bottom: 8px;
            margin-left: 25px;
            font-family: "GT Walsheim Trial Md";


        
            }

       

            /* Input Field Styling */
            input {
            width: 100%;
            padding: 18px 20px;
            border: 1px solid #fff;
            border-radius: 30px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 16px;
            font-family: "Montserrat", sans-serif;
            outline: none;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box;
              padding-left:30px;
            }

            input::placeholder {
            color: #ececec80;
            font-size: 12px;
              font-family: "GT Walsheim Trial Md";
            
            }

      
            /* Custom Select Styling */
            .interest-form-custom-select-wrapper {
            position: relative;
            width: 100%;
            }

            /* Select Input */
            /* Base wrapper */
            .custom-select {
            position: relative;
            width: 100%;
            font-family: "Montserrat", sans-serif;
            color: #ffffff;
            }

            /* Trigger (main visible input) */
            .custom-select-trigger {
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid #a8bdbf;
            border-radius: 30px;
            padding: 18px 20px;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            }

            .custom-select-trigger:hover {
            background-color: rgba(255, 255, 255, 0.3);
            }

            /* Text inside the trigger */
            .custom-select-text {
            color: rgba(255, 255, 255, 0.9);
             font-family: "GT Walsheim Trial Md";
            }

            /* Arrow styling */
            .custom-select-arrow {
            transition: transform 0.3s ease;
            }

            /* Dropdown list */
            .custom-options {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: #137077;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            opacity: 0;
            pointer-events: none;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            overflow: hidden;
            z-index: 10;
                  font-family: "GT Walsheim Trial Md";
                  font-weight: 400;
            }

            /* Option item */
            .custom-option {
            display: block;
            padding: 12px 18px;
            font-size: 13px;
            color: #ffffff;
            cursor: pointer;
            transition: background 0.2s ease;
            }

            .custom-option:hover {
            background-color: rgba(255, 255, 255, 0.15);
            }

            /* When dropdown is active */
            .custom-select.open .custom-options {
            opacity: 1;
            pointer-events: all;
            transform: translateY(0);
            }

            .custom-select.open .custom-select-arrow {
            transform: rotate(180deg);
            }

            /* Active option (highlighted) */
            .custom-option.active {
            background-color: rgba(255, 255, 255, 0.25);
            }

           

            /* ===================================================== FOR FORM THREE  */
          /* Container for all tags */
          .interest-form-tags {
            display: flex;
            grid-template-columns: repeat(3, 1fr);
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
            width: 100%;
            justify-content: center;
          }

          /* Individual tag wrapper */
          .interest-form-tag {
            background-color: #ffffff;
            border-radius: 40px;
            display: flex;
            align-items: center;
            padding: 8px 25px;
            gap: 10px;
            cursor: pointer;
          }
          .interest-form-tag span {
            display: block;
            font-size: 11px;
            color: #193E47;
            font-weight: 300px;
            font-family: "GT Walsheim Trial Md";


          }
          .interest-form-tag span:first-of-type {
            width: 25px;
            height: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50px;
            border: 2px solid #0d4036;
            padding: 2px;
          }
          .interest-form-tag span svg {
            color: #fff;

            width: 20px;
            height: 20px;
            font-weight: 900;
          }
          .interest-form-tag span.active svg {
            color: #0d4036;
          }
          .interest-form-group label[for="revenue"]{
            display: flex;
            flex-direction: column;
            justify-content: start;
            
            
          
       

          }

            /* Sublabel for revenue field */
            .interest-form-sublabel {
            font-size: 13px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.7);
            display: block;
            white-space: nowrap;
            margin-top: 3px;
            }

            /* Textarea Styling */
            textarea {
            width: 100%;
            padding: 15px 20px;
            border: none;
            border-radius: 30px; /* Highly rounded corners for the single-line input/textarea */
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 16px;
            font-family: "Montserrat", sans-serif;
            outline: none;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box;
            resize: none; /* Prevent manual resizing */
                border: 1px solid #fff;
            }

            /* Specific styling for the larger description textarea */
            #companyDescription {
            border-radius: 20px; /* Slightly less rounded for the multi-line field */
            min-height: 120px;
            padding-top: 20px;
            }

            textarea:focus {
            background-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5);
            }

            textarea::placeholder {
            color: rgba(255, 255, 255, 0.6);
                font-weight: 500;

              font-family: "GT Walsheim Trial Md";
              font-size: 12px;
            }

            /* Question and List Styling */
            .interest-form-question {
            font-size: 12px;
            font-weight: 600;
            color: #f0f0f0;
            margin-left: 40px;
            line-height: 1.5;
            }

            .interest-form-list {
            color: #f0f0f0;
            font-size: 12px;
            font-weight: 500;
            padding-left: 20px;
            margin-left: 40px;
            margin-top: 5px;
            }

            .interest-form-list li {
            margin-bottom: 5px;
            }

            /* Custom Radio Button Group Styling (Three Buttons) */
            .interest-form-option-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
            justify-content: center;
            /* margin-bottom: 30px; */
            }

            .interest-form-option input[type="radio"] {
            /* Hide the actual radio button */
            display: none;
            }

            .interest-form-option label {
            display: block;
            padding: 12px 18px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 10px;
            font-weight: 600;
            text-align: center;
            transition: background-color 0.3s ease, color 0.3s ease;

            background-color: white;
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: #0d4036;
            margin: 0; /* Override default label margin */
            white-space: nowrap;
            }

            /* Checked (Active) State for the Option Button */
            .interest-form-option input[type="radio"]:checked + label {
            background-color: transparent;
            border-color: #ffffff;
            color: #0d4036; /* Dark teal text when active */
            }

            .interest-form-option label:hover {
            background-color: rgba(255, 255, 255, 0.3);
            }

            /* Submit Button Override */
            .interest-form-submit-button {
            padding: 13px 45px;
            background-color: #e2f091;
            color: #4a6a00;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            font-weight: 500;

              font-family: "GT Walsheim Trial Md";
            }

            .interest-form-submit-button:hover {
            background-color: #d6e885;
            transform: translateY(-2px);
            }

            .interest-form-submit-button:active {
            background-color: #cedb7e;
            transform: translateY(0);
            }

            /* Proceed Button Styling */
            .interest-form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            }
            .interest-form .first-stage-form-container .interest-form-actions {
            justify-content: end;
            }

            /* Return Button Styling (New) */
            .interest-form-return-button {
            padding: 13px 45px;
            background-color: transparent;
            color: #ffffff; /* White text */
            border: 1px solid white;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                font-weight: 500;

              font-family: "GT Walsheim Trial Md";
            }

            .interest-form-return-button:hover {
            background-color: rgba(255, 255, 255, 0.3);
            }

            /* Adjust the proceed button alignment from the previous form to work with the button group */
            .interest-form-proceed-button {
            /* align-self: flex-end; */
            padding: 13px 45px;

            background-color: #cced60;
            color: #0d4036;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
              font-weight: 500;

              font-family: "GT Walsheim Trial Md";
            }

            .interest-form-proceed-button:hover {
            background-color: #d6e885;
            transform: translateY(-2px);
            }

            .interest-form-proceed-button:active {
            background-color: #cedb7e;
            transform: translateY(0);
            }

            .interest-form .second-stage-form-container .interest-form-actions {
            /* width: 50%; */
            }

            .interest-form-layout-container .desktop-heading {
            display: none;
            }
            .interest-form-layout-container .mobile-heading {
            display: block;
            }

            /*====================================================================
            ========================== Interest Footer Container ========================= */
            .interest-form-footer {
            width: 100%;
            margin: 60px auto;
            padding-left: 5px;
            max-width: 650px;
            }

            .interest-form-footer h2 {
            font-size: 25px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 15px 0;
            }

            .interest-form-footer-contact-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 20px;
            }
            .interest-form-footer-contact-row > div:first-of-type {
            display: flex;
            flex-direction: column;
            gap: 15px;
            }

            .interest-form-footer-contact-row > div:last-of-type {
            display: flex;
            justify-content: start;
            align-items: end;
            }
            .interest-form-footer-contact-row > div:last-of-type span:last-of-type {
            margin-bottom: 9px;
            }

            .interest-form-footer-contact-item {
            display: flex;
            align-items: center;
            }

            .interest-form-footer-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #0d4036;
            color: #ffffff;
            font-size: 20px;
            margin-right: 6px;
            flex-shrink: 0;
            }

            .interest-form-footer-contact-item a,
            .interest-form-footer-contact-item span {
            font-size: 13px;
            font-weight: 500;
            color: #555555;
            text-decoration: none;
            transition: color 0.3s ease;
            }
            .interest-form-footer-contact-item span i{
                color: #fff;
            }

            .interest-form-footer-contact-item a:hover {
            color: #0d4036;
            }
            .interest-form-footer-contact-item .phone {
            text-decoration: underline;
            }
            /* Specific styling for the location text which is not a link */
            .interest-form-footer-contact-item span:not(.interest-form-footer-icon) {
            color: #555555;
            }

            /* Desktop specific styles */
            @media (min-width: 992px) {
            .form-layout-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
                padding-right: 20px;
            }

            .row-two-container {
                display: flex;
                flex-direction: column;
                justify-content: space-around;
            }

            .row-two-container .interest-form-heading.desktop-heading {
                width: 100%;
                margin: 0;
            }
            .form-heading-card {
                max-width: 500px;
            }
            .interest-form-heading .header h1 {
                font-size: 39px;
            }

            .interest-form-heading.desktop-heading .description p {
                font-size: 15px;
            }

            .interest-form-footer {
                margin: 0px;
                padding-left: 0px;
                max-width: auto;
            }
            .interest-form-container .form-container {
                margin-top: 0px;
                max-width: 500px;
                padding: 65px 40px;
            }

                    label {
            font-size: 14px;
            font-weight: 400;
          
            margin-left: 40px;
       


        
            }

   
                  .interest-form-group label[for="revenue"]{
            flex-direction: row;
            white-space: nowrap;
            align-items: center;

          
       

          }


            .interest-form-return-button,
            .interest-form-proceed-button {
                /* Uses the slightly transparent input background for its base color */
                padding: 15px 60px;
            }

            .interest-form-layout-container .desktop-heading {
                display: block;
            }
            .interest-form-layout-container .mobile-heading {
                display: none;
            }

            .interest-form-footer-contact-row {
                display: flex;
                flex-wrap: wrap;
                align-items: start;
                gap: 20px;
            }

            .interest-form-footer-contact-row > div:first-of-type {
                display: flex;
                flex-direction: row;
                gap: 20px;
            }
            .interest-form-footer-contact-row > div:last-of-type {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .interest-form-footer-contact-row > div span {
                white-space: nowrap;
            }
            .interest-form-footer-contact-row > div:last-of-type span:last-of-type {
                margin-bottom: 0px;
            }
            }

            @media (min-width: 1440px) {
            .form-layout-container {
                padding: 0px;
            }
            }



</style>

@section('content')
   <section class="interest-form-layout-container">
      <div class="form-layout-container">
        <div class="row-one-container">
          <!-- Mobile Heading -->
          <section class="interest-form-heading mobile-heading">
            <!-- First Form Heading -->
            <div class="first-form-heading hidden">
              <div class="form-heading-card">
                <div class="header">
                  <h1>Basic Information</h1>
                  <div class="subtitle">
                    <span class="line"></span>
                    <p>Let's start with your basic details</p>
                  </div>
                </div>

                <div class="description">
                  <p>
                    NOEL transforms executive networking from casual connections
                    to strategic growth. Our platform facilitates verified,
                    high-value introductions between senior leaders and
                    investors. Focus on building relationships that drive real
                    business outcomes.
                  </p>
                </div>
                <div class="progress-step-container">
                  <div class="progress-stepper">
                    <div class="step active">
                      <div class="circle">1</div>
                      <div class="label">Basic Information</div>
                    </div>

                    <div class="step">
                      <div class="circle">2</div>
                      <div class="label">Credibility Check</div>
                    </div>

                    <div class="step">
                      <div class="circle">3</div>
                      <div class="label">Industry Preference</div>
                    </div>

                    <div class="step">
                      <div class="circle">4</div>
                      <div class="label">Industry Insight</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Second Form Heading -->
            <div class="second-form-heading hidden">
              <div class="form-heading-card">
                <div class="header">
                  <h1>Credibility Check</h1>
                  <div class="subtitle">
                    <span class="line"></span>
                    <p>Stand out among other businesses</p>
                  </div>
                </div>

                <div class="description">
                  <p>
                    NOEL transforms executive networking from casual connections
                    to strategic growth. Our platform facilitates verified,
                    high-value introductions between senior leaders and
                    investors. Focus on building relationships that drive real
                    business outcomes.
                  </p>
                </div>
                <div class="progress-step-container">
                  <div class="progress-stepper">
                    <div class="step active">
                      <div class="circle">1</div>
                      <div class="label">Basic Information</div>
                    </div>

                    <div class="step">
                      <div class="circle">2</div>
                      <div class="label">Credibility Check</div>
                    </div>

                    <div class="step">
                      <div class="circle">3</div>
                      <div class="label">Industry Preference</div>
                    </div>

                    <div class="step">
                      <div class="circle">4</div>
                      <div class="label">Industry Insight</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Third Form Heading -->
            <div class="third-form-heading hidden">
              <div class="form-heading-card">
                <div class="header">
                  <h1>Industry Preference</h1>
                  <div class="subtitle">
                    <span class="line"></span>
                    <p>Boost visibility for your matches</p>
                  </div>
                </div>

                <div class="description">
                  <p>
                    NOEL transforms executive networking from casual connections
                    to strategic growth. Our platform facilitates verified,
                    high-value introductions between senior leaders and
                    investors. Focus on building relationships that drive real
                    business outcomes.
                  </p>
                </div>
                <div class="progress-step-container">
                  <div class="progress-stepper">
                    <div class="step active">
                      <div class="circle">1</div>
                      <div class="label">Basic Information</div>
                    </div>

                    <div class="step">
                      <div class="circle">2</div>
                      <div class="label">Credibility Check</div>
                    </div>

                    <div class="step">
                      <div class="circle">3</div>
                      <div class="label">Industry Preference</div>
                    </div>

                    <div class="step">
                      <div class="circle">4</div>
                      <div class="label">Industry Insight</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Fourth Form Heading -->
            <div class="fourth-form-heading">
              <div class="form-heading-card">
                <div class="header">
                  <h1>Industry Insight</h1>
                  <div class="subtitle">
                    <span class="line"></span>
                    <p>Stand out among other businesses</p>
                  </div>
                </div>

                <div class="description">
                  <p>
                    NOEL transforms executive networking from casual connections
                    to strategic growth. Our platform facilitates verified,
                    high-value introductions between senior leaders and
                    investors. Focus on building relationships that drive real
                    business outcomes.
                  </p>
                </div>
                <div class="progress-step-container">
                  <div class="progress-stepper">
                    <div class="step active">
                      <div class="circle">1</div>
                      <div class="label">Basic Information</div>
                    </div>

                    <div class="step">
                      <div class="circle">2</div>
                      <div class="label">Credibility Check</div>
                    </div>

                    <div class="step">
                      <div class="circle">3</div>
                      <div class="label">Industry Preference</div>
                    </div>

                    <div class="step">
                      <div class="circle">4</div>
                      <div class="label">Industry Insight</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- Form Section -->
          <section class="interest-form-container">
            <div class="form-container">
              <form class="interest-form">
                <!-- First Stage Form -->
                <div class="first-stage-form-container hidden">
                  <div class="interest-form-group">
                    <label for="fullName">Full Name</label>
                    <input
                      type="text"
                      id="fullName"
                      placeholder="Input Full name"
                    />
                  </div>

                  <div class="interest-form-group">
                    <label for="title">Title</label>
                    <div class="custom-select" id="customSelect">
                      <div class="custom-select-trigger">
                        <span class="custom-select-text"
                          >Input your title, e.g CEO</span
                        >
                        <svg
                          class="custom-select-arrow"
                          xmlns="http://www.w3.org/2000/svg"
                          width="18"
                          height="18"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="#ffffff"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        >
                          <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                      </div>
                      <div class="custom-options">
                        <span class="custom-option" data-value="CEO">CEO</span>
                        <span class="custom-option" data-value="CTO">CTO</span>
                        <span class="custom-option" data-value="CFO">CFO</span>
                        <span class="custom-option" data-value="Manager"
                          >Manager</span
                        >
                        <span class="custom-option" data-value="Director"
                          >Director</span
                        >
                        <span class="custom-option" data-value="Other"
                          >Other</span
                        >
                      </div>
                    </div>

                    <!-- Hidden native select for form submission -->
                    <select id="title" name="title" style="display: none">
                      <option value="">Input your title, e.g CEO</option>
                      <option value="CEO">CEO</option>
                      <option value="CTO">CTO</option>
                      <option value="CFO">CFO</option>
                      <option value="Manager">Manager</option>
                      <option value="Director">Director</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>

                  <div class="interest-form-group">
                    <label for="businessName">Official Business Name</label>
                    <input
                      type="text"
                      id="businessName"
                      placeholder="Input your business Name"
                    />
                  </div>

                  <div class="interest-form-group">
                    <label for="emailAddress">Email Address</label>
                    <input
                      type="email"
                      id="emailAddress"
                      placeholder="Input Email Address"
                    />
                  </div>

                  <div class="interest-form-group">
                    <label for="phoneNumber">Phone Number</label>
                    <input
                      type="tel"
                      id="phoneNumber"
                      placeholder="Input phone number"
                    />
                  </div>

                  <div class="interest-form-actions">
                    <button type="submit" class="interest-form-proceed-button">
                      Proceed
                    </button>
                  </div>
                </div>

                <!-- Second Form -->
                <div class="second-stage-form-container hidden">
                  <div class="interest-form-group">
                    <label for="regNumber">Company Registration Number</label>
                    <input
                      type="text"
                      id="regNumber"
                      placeholder="Input you company Reg. Number"
                    />
                  </div>

                  <div class="interest-form-group">
                    <label for="countryReg">Country Of Registration</label>
                    <input
                      type="text"
                      id="countryReg"
                      placeholder="Input your country of Reg."
                    />
                  </div>

                  <div class="interest-form-group">
                    <label for="website"
                      >Company Website or Social Handle</label
                    >
                    <input
                      type="text"
                      id="website"
                      placeholder="Input your company website/socials"
                    />
                  </div>

                  <div class="interest-form-group">
                    <label for="linkedin">LinkedIn Profile</label>
                    <input
                      type="url"
                      id="linkedin"
                      placeholder="Add your profile Url (Optional)"
                    />
                  </div>

                  <div class="interest-form-group">
                    <label for="yearEst">Year of Establishment</label>
                    <input
                      type="number"
                      id="yearEst"
                      placeholder="Input year of establishment"
                      pattern="[0-9]{4}"
                      min="1900"
                      max="2100"
                    />
                  </div>

                  <div class="interest-form-actions">
                    <button type="button" class="interest-form-return-button">
                      Return
                    </button>
                    <button type="submit" class="interest-form-proceed-button">
                      Proceed
                    </button>
                  </div>
                </div>

                <!-- third Form -->
                <div class="third-stage-form-container hidden">
                  <!-- Industries -->
                  <div class="interest-form-group">
                    <label for="industry"
                      >What industry does your business operate in?</label
                    >
                    <div class="custom-select" data-select="industry">
                      <div class="custom-select-trigger">
                        <span class="custom-select-text"
                          >Primary & Industrial sector</span
                        >
                        <svg
                          class="custom-select-arrow"
                          xmlns="http://www.w3.org/2000/svg"
                          width="18"
                          height="18"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="#ffffff"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        >
                          <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                      </div>
                      <div class="custom-options">
                        <span class="custom-option" data-value="agriculture"
                          >Agribusiness</span
                        >
                        <span class="custom-option" data-value="manufacturing"
                          >Manufacturing</span
                        >
                        <span class="custom-option" data-value="mining"
                          >Mining</span
                        >
                        <span class="custom-option" data-value="logistics"
                          >Logistics</span
                        >
                      </div>
                    </div>

                    <select name="industry" id="industry" style="display: none">
                      <option value="">Primary & Industrial sector</option>
                      <option value="agriculture">Agribusiness</option>
                      <option value="manufacturing">Manufacturing</option>
                      <option value="mining">Mining</option>
                      <option value="logistics">Logistics</option>
                    </select>
                  </div>

                  <!-- Categories -->
                  <div class="interest-form-group">
                    <div
                      class="interest-form-tags"
                      id="categoryContainer"
                    ></div>
                  </div>

                  <!-- Hidden field to store selected categories for form submission -->
                  <input
                    type="hidden"
                    name="categories"
                    id="selectedCategories"
                  />

                  <!-- Employees Custom Select -->
                  <div class="interest-form-group">
                    <label for="employees">Numbers of Employees</label>

                    <div class="custom-select" data-select="employees">
                      <div class="custom-select-trigger">
                        <span class="custom-select-text"
                          >Select Employees range</span
                        >
                        <svg
                          class="custom-select-arrow"
                          xmlns="http://www.w3.org/2000/svg"
                          width="18"
                          height="18"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="#ffffff"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        >
                          <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                      </div>

                      <div class="custom-options">
                        <span class="custom-option" data-value="1-10"
                          >1-10</span
                        >
                        <span class="custom-option" data-value="11-50"
                          >11-50</span
                        >
                        <span class="custom-option" data-value="51-200"
                          >51-200</span
                        >
                        <span class="custom-option" data-value="201-500"
                          >201-500</span
                        >
                        <span class="custom-option" data-value="500+"
                          >500+</span
                        >
                      </div>
                    </div>

                    <!-- Hidden native select -->
                    <select
                      id="employees"
                      name="employees"
                      style="display: none"
                    >
                      <option value="">Select Employees range</option>
                      <option value="1-10">1-10</option>
                      <option value="11-50">11-50</option>
                      <option value="51-200">51-200</option>
                      <option value="201-500">201-500</option>
                      <option value="500+">500+</option>
                    </select>
                  </div>

                  <!-- Revenue Custom Select -->
                  <div class="interest-form-group">
                    <label for="revenue">
                      <span>
                      Approximate annual revenue
                      </span>
                      <span class="interest-form-sublabel"
                        >(confidential – for internal use only)</span>
                    </label>

                    <div class="custom-select" data-select="revenue">
                      <div class="custom-select-trigger">
                        <span class="custom-select-text"
                          >Select revenue range</span
                        >
                        <svg
                          class="custom-select-arrow"
                          xmlns="http://www.w3.org/2000/svg"
                          width="18"
                          height="18"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="#ffffff"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        >
                          <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                      </div>

                      <div class="custom-options">
                        <span class="custom-option" data-value="0-1M"
                          >Below $1M</span
                        >
                        <span class="custom-option" data-value="1-5M"
                          >$1M - $5M</span
                        >
                        <span class="custom-option" data-value="5-20M"
                          >$5M - $20M</span
                        >
                        <span class="custom-option" data-value="20M+"
                          >$20M+</span
                        >
                      </div>
                    </div>

                    <!-- Hidden native select -->
                    <select id="revenue" name="revenue" style="display: none">
                      <option value="">Select revenue range</option>
                      <option value="0-1M">Below $1M</option>
                      <option value="1-5M">$1M - $5M</option>
                      <option value="5-20M">$5M - $20M</option>
                      <option value="20M+">$20M+</option>
                    </select>
                  </div>

                  <div class="interest-form-actions">
                    <button type="button" class="interest-form-return-button">
                      Return
                    </button>
                    <button type="submit" class="interest-form-proceed-button">
                      Proceed
                    </button>
                  </div>
                </div>

                <!-- fourth Form -->
                <div class="fourth-stage-form-container">
                  <div class="interest-form-group">
                    <label for="offerValue"
                      >What value can your company offer to other CEOs in the
                      network?</label
                    >
                    <textarea
                      id="offerValue"
                      rows="1"
                      placeholder="Enter your value"
                    ></textarea>
                  </div>

                  <div class="interest-form-group">
                    <label for="companyDescription"
                      >Briefly describe what your company does and who your
                      clients/customers are (max 150 words)</label
                    >
                    <textarea
                      id="companyDescription"
                      rows="5"
                      placeholder="Enter you Value"
                    ></textarea>
                  </div>

                  <div class="interest-form-group">
                    <label for="interest"
                      >What are you most interested in from this network?</label
                    >

                    <div class="custom-select" data-select="interest">
                      <div class="custom-select-trigger">
                        <span class="custom-select-text">Select interest</span>
                        <span class="custom-select-arrow">
                           <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="18"
                          height="18"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="#ffffff"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        >
                          <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                        </span>
                      </div>

                      <div class="custom-options">
                        <div class="custom-option" data-value="networking">
                          Networking
                        </div>
                        <div class="custom-option" data-value="leads">
                          Verified Business Leads
                        </div>
                        <div class="custom-option" data-value="tools">
                          Automated Sales Tools
                        </div>
                        <div class="custom-option" data-value="consulting">
                          Consulting Support
                        </div>
                      </div>
                    </div>

                    <!-- hidden select to sync value -->
                    <select name="interest" hidden>
                      <option value="networking">Networking</option>
                      <option value="leads">Verified Business Leads</option>
                      <option value="tools">Automated Sales Tools</option>
                      <option value="consulting">Consulting Support</option>
                    </select>
                  </div>

                  <div class="interest-form-group">
                    <p class="interest-form-question">
                      If your business isn’t eligible for the CEO Referral
                      Network at this time, would you be interested in joining
                      our B2B platform to access:
                    </p>
                    <ul class="interest-form-list">
                      <li>Verified business leads</li>
                      <li>Automated sales tools</li>
                      <li>Growth audits and consulting support?</li>
                    </ul>

                    <div class="interest-form-option-buttons">
                      <div class="interest-form-option">
                        <input
                          type="radio"
                          id="opt-yes"
                          name="b2b_interest"
                          value="yes"
                          checked
                        />
                        <label for="opt-yes">Yes, I'm interested</label>
                      </div>
                      <div class="interest-form-option">
                        <input
                          type="radio"
                          id="opt-maybe"
                          name="b2b_interest"
                          value="maybe"
                        />
                        <label for="opt-maybe"
                          >Maybe, send me more information</label
                        >
                      </div>
                      <div class="interest-form-option">
                        <input
                          type="radio"
                          id="opt-no"
                          name="b2b_interest"
                          value="no"
                        />
                        <label for="opt-no">No, not interested</label>
                      </div>
                    </div>
                  </div>

                  <div class="interest-form-actions">
                    <button type="button" class="interest-form-return-button">
                      Return
                    </button>
                    <button type="submit" class="interest-form-submit-button">
                      Submit
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </section>
        </div>

        <div class="row-two-container">
          <!-- Desktop Heading -->
          <section class="interest-form-heading desktop-heading">
            <!-- First Form Heading -->
            <div class="first-form-heading hidden">
              <div class="form-heading-card">
                <div class="header">
                  <h1>Basic Information</h1>
                  <div class="subtitle">
                    <span class="line"></span>
                    <p>Let's start with your basic details</p>
                  </div>
                </div>

                <div class="description">
                  <p>
                    NOEL transforms executive networking from casual connections
                    to strategic growth. Our platform facilitates verified,
                    high-value introductions between senior leaders and
                    investors. Focus on building relationships that drive real
                    business outcomes.
                  </p>
                </div>
                <div class="progress-step-container">
                  <div class="progress-stepper ">
                    <div class="step ">
                      <div class="circle">1</div>
                      <div class="label">Basic Information</div>
                    </div>

                    <div class="step">
                      <div class="circle">2</div>
                      <div class="label">Credibility Check</div>
                    </div>

                    <div class="step">
                      <div class="circle">3</div>
                      <div class="label">Industry Preference</div>
                    </div>

                    <div class="step">
                      <div class="circle">4</div>
                      <div class="label">Industry Insight</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Second Form Heading -->
            <div class="second-form-heading hidden">
              <div class="form-heading-card">
                <div class="header">
                  <h1>Credibility Check</h1>
                  <div class="subtitle">
                    <span class="line"></span>
                    <p>Stand out among other businesses</p>
                  </div>
                </div>

                <div class="description">
                  <p>
                    NOEL transforms executive networking from casual connections
                    to strategic growth. Our platform facilitates verified,
                    high-value introductions between senior leaders and
                    investors. Focus on building relationships that drive real
                    business outcomes.
                  </p>
                </div>
                <div class="progress-step-container">
                  <div class="progress-stepper">
                    <div class="step active">
                      <div class="circle">1</div>
                      <div class="label">Basic Information</div>
                    </div>

                    <div class="step">
                      <div class="circle">2</div>
                      <div class="label">Credibility Check</div>
                    </div>

                    <div class="step">
                      <div class="circle">3</div>
                      <div class="label">Industry Preference</div>
                    </div>

                    <div class="step">
                      <div class="circle">4</div>
                      <div class="label">Industry Insight</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Third Form Heading -->
            <div class="third-form-heading hidden">
              <div class="form-heading-card">
                <div class="header">
                  <h1>Industry Preference</h1>
                  <div class="subtitle">
                    <span class="line"></span>
                    <p>Boost visibility for your matches</p>
                  </div>
                </div>

                <div class="description">
                  <p>
                    NOEL transforms executive networking from casual connections
                    to strategic growth. Our platform facilitates verified,
                    high-value introductions between senior leaders and
                    investors. Focus on building relationships that drive real
                    business outcomes.
                  </p>
                </div>
                <div class="progress-step-container">
                  <div class="progress-stepper">
                    <div class="step active">
                      <div class="circle">1</div>
                      <div class="label">Basic Information</div>
                    </div>

                    <div class="step">
                      <div class="circle">2</div>
                      <div class="label">Credibility Check</div>
                    </div>

                    <div class="step">
                      <div class="circle">3</div>
                      <div class="label">Industry Preference</div>
                    </div>

                    <div class="step">
                      <div class="circle">4</div>
                      <div class="label">Industry Insight</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Fourth Form Heading -->
            <div class="fourth-form-heading">
              <div class="form-heading-card">
                <div class="header">
                  <h1>Industry Insight</h1>
                  <div class="subtitle">
                    <span class="line"></span>
                    <p>Stand out among other businesses</p>
                  </div>
                </div>

                <div class="description">
                  <p>
                    NOEL transforms executive networking from casual connections
                    to strategic growth. Our platform facilitates verified,
                    high-value introductions between senior leaders and
                    investors. Focus on building relationships that drive real
                    business outcomes.
                  </p>
                </div>
                <div class="progress-step-container">
                  <div class="progress-stepper">
                    <div class="step active">
                      <div class="circle">1</div>
                      <div class="label">Basic Information</div>
                    </div>

                    <div class="step">
                      <div class="circle">2</div>
                      <div class="label">Credibility Check</div>
                    </div>

                    <div class="step">
                      <div class="circle">3</div>
                      <div class="label">Industry Preference</div>
                    </div>

                    <div class="step">
                      <div class="circle">4</div>
                      <div class="label">Industry Insight</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- footer -->
          <section class="interest-form-footer">
            <div class="form-footer">
              <h2>Contact Information</h2>

              <div class="interest-form-footer-contact-row">
                <div>
                  <div class="interest-form-footer-contact-item">
                    <span class="interest-form-footer-icon"
                      ><i class="fas fa-phone-alt"></i
                    ></span>
                    <a href="tel:+442080921281" class="phone"
                      >+44 208 0921 281</a
                    >
                  </div>

                  <div class="interest-form-footer-contact-item">
                    <span class="interest-form-footer-icon"
                      ><i class="fas fa-envelope"></i
                    ></span>
                    <a href="mailto:hello@p23africa.com">hello@p23africa.com</a>
                  </div>
                </div>

                <div class="interest-form-footer-contact-item">
                  <span class="interest-form-footer-icon"
                    ><i class="fas fa-map-marker-alt"></i
                  ></span>
                  <span>London UK</span>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </section>
@endsection

<script>





// CUSTOM SELECT AND CATEGORIES

document.addEventListener("DOMContentLoaded", () => {
  /*************************************
   * GLOBAL CUSTOM SELECT HANDLER
   *************************************/
  const allCustomSelects = document.querySelectorAll(".custom-select");

  allCustomSelects.forEach((customSelect) => {
    const trigger = customSelect.querySelector(".custom-select-trigger");
    const text = customSelect.querySelector(".custom-select-text");
    const options = customSelect.querySelectorAll(".custom-option");
    const selectName = customSelect.dataset.select;
    const hiddenSelect = document.querySelector(`select[name="${selectName}"]`);

    // Toggle dropdown open/close
    trigger.addEventListener("click", (e) => {
      e.stopPropagation();
      document.querySelectorAll(".custom-select.open").forEach((openSelect) => {
        if (openSelect !== customSelect) openSelect.classList.remove("open");
      });
      customSelect.classList.toggle("open");
    });

    // Handle option click
    options.forEach((option) => {
      option.addEventListener("click", (e) => {
        e.stopPropagation();
        const value = option.dataset.value;
        const label = option.textContent;

        // Update visible text + active state
        text.textContent = label;
        options.forEach((o) => o.classList.remove("active"));
        option.classList.add("active");

        // Sync to hidden select
        if (hiddenSelect) hiddenSelect.value = value;

        // Close dropdown
        customSelect.classList.remove("open");

        //  If this is the "industry" select, trigger category update
        if (selectName === "industry") {
          updateCategories(value);
        }
      });
    });
  });

  // Close dropdown when clicking outside
  document.addEventListener("click", (e) => {
    document.querySelectorAll(".custom-select.open").forEach((openSelect) => {
      if (!openSelect.contains(e.target)) openSelect.classList.remove("open");
    });
  });

  /*************************************
   * INDUSTRY → CATEGORY HANDLER
   *************************************/
  const industryCategories = {
    agriculture: [
      "Crop Production",
      "Livestock Farming",
      "AgroTech",
      "Food Processing",
    ],
    manufacturing: [
      "Textile Production",
      "Automobile Manufacturing",
      "Electronics",
      "Pharmaceuticals",
    ],
    mining: [
      "Gold Mining",
      "Oil & Gas Exploration",
      "Quarrying",
      "Metal Refining",
    ],
    logistics: [
      "Freight Forwarding",
      "Warehousing",
      "Transportation",
      "Supply Chain Solutions",
    ],
  };

  const defaultTags = [
    "Agrobusiness",
    "Business Growth",
    "Sustainability",
    "Leadership",
  ];

  const selectedCategories = {}; // store selections by industry
  const categoryContainer = document.getElementById("categoryContainer");
  const industrySelect = document.querySelector('select[name="industry"]');
  const hiddenCategoryInput = document.getElementById("selectedCategories");

  // Initial render (default tags)
  renderTags(defaultTags, true);

  /** Update when user selects a new industry **/
  function updateCategories(industry) {
    categoryContainer.innerHTML = "";
    hiddenCategoryInput.value = "";

    if (!industry || !industryCategories[industry]) {
      renderTags(defaultTags, true);
      return;
    }

    const categories = industryCategories[industry];
    const saved = selectedCategories[industry] || [];

    renderTags(categories, false, saved);
  }

  /** Render tags **/
  function renderTags(categories, isDefault = false, saved = []) {
    categoryContainer.innerHTML = "";

    categories.forEach((category) => {
      const tag = document.createElement("div");
      tag.className = "interest-form-tag";
      if (saved.includes(category)) tag.classList.add("active");

      tag.innerHTML = `
        <span class="check-icon ${saved.includes(category) ? "active" : ""}">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
          </svg>
        </span>
        <span>${category}</span>
      `;

      // Add click event
      tag.addEventListener("click", () => {
        const currentIndustry = industrySelect.value;

        // Toggle visual state
        tag.classList.toggle("active");
        const icon = tag.querySelector(".check-icon");
        icon.classList.toggle("active");

        // Handle storage
        if (!isDefault && currentIndustry) {
          if (!selectedCategories[currentIndustry]) {
            selectedCategories[currentIndustry] = [];
          }

          if (tag.classList.contains("active")) {
            selectedCategories[currentIndustry].push(category);
          } else {
            selectedCategories[currentIndustry] = selectedCategories[
              currentIndustry
            ].filter((c) => c !== category);
          }
        }

        updateHiddenInput();
      });

      categoryContainer.appendChild(tag);
    });
  }

  /** Sync hidden field for submission **/
  function updateHiddenInput() {
    const allSelected = Object.values(selectedCategories).flat();
    hiddenCategoryInput.value = allSelected.join(",");
  }
});


// FORM STEPS
document.addEventListener("DOMContentLoaded", () => {
  // Collect all forms and headings
  const formStages = document.querySelectorAll(
    ".first-stage-form-container, .second-stage-form-container, .third-stage-form-container, .fourth-stage-form-container"
  );

  const mobileHeadings = document.querySelectorAll(
    ".mobile-heading .first-form-heading, .mobile-heading .second-form-heading, .mobile-heading .third-form-heading, .mobile-heading .fourth-form-heading"
  );

  const desktopHeadings = document.querySelectorAll(
    ".desktop-heading .first-form-heading, .desktop-heading .second-form-heading, .desktop-heading .third-form-heading, .desktop-heading .fourth-form-heading"
  );

  const proceedButtons = document.querySelectorAll(
    ".interest-form-proceed-button"
  );
  const returnButtons = document.querySelectorAll(
    ".interest-form-return-button"
  );

  let currentStep = 0;
  const totalSteps = formStages.length;

  // Initialize form and heading display
  function showStep(step) {
    // Hide all stages
    formStages.forEach((form) => form.classList.add("hidden"));
    mobileHeadings.forEach((heading) => heading.classList.add("hidden"));
    desktopHeadings.forEach((heading) => heading.classList.add("hidden"));

    // Show current stage
    formStages[step].classList.remove("hidden");
    mobileHeadings[step].classList.remove("hidden");
    desktopHeadings[step].classList.remove("hidden");

    updateProgress(step);
  }

  // Update progress bar for all instances
  function updateProgress(step) {
    const progressSteppers = document.querySelectorAll(".progress-stepper");

    progressSteppers.forEach((stepper) => {
      const steps = stepper.querySelectorAll(".step");
      const progress = (step / totalSteps) * 100;
      stepper.style.setProperty("--progress-width", `${progress}%`);
      steps.forEach((s, i) => {
        s.classList.remove("active", "completed");
        if (i < step) s.classList.add("completed");
        else if (i === step) s.classList.add("active");
      });
    });
  }

  // Handle navigation
  proceedButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      if (currentStep < totalSteps - 1) {
        currentStep++;
        showStep(currentStep);
      }
    });
  });

  returnButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
      }
    });
  });

  // Initialize first view
  showStep(currentStep);
});

</script>
