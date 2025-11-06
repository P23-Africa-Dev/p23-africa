@extends('layouts.master')
@section('title', 'Funding Readiness Guidebook E-Book | P23 Africa')

@section('meta')
    <meta name="description"
        content="Download your free Funding Readiness Guidebook. A step-by-step guide to becoming investor-ready, built specifically for African entrepreneurs seeking funding or sustainable growth.">
    <meta name="keywords"
        content="funding readiness, investor ready, African entrepreneurs, startup funding, pitch deck, business funding guide, investor directory, African startups, entrepreneurship guide">
    <meta name="author" content="Nurudeen O. Daniju">
    <meta name="generator" content="Funding Readiness Guidebook for African Entrepreneurs | P23 Africa">
    <meta property="og:title" content="Funding Readiness Guidebook | P23 Africa">
    <meta property="og:description"
        content="Master your business story, understand your numbers, and pitch with confidence. Get your free guide to attract investors or grow sustainably.">
    <meta property="og:image" content="{{ asset('img/mobile.png') }}">
    <meta property="og:type" content="website">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>  -->

<style>

  

   
body {
  font-family: "GT Walsheim ProCB";
}

.sales-container{
  max-width: 1204px;
  margin: 0 auto;
}

.intro-sales {
  padding: 2rem 2.5rem 2rem 1.9rem;
  background-image: url("/img/first-bg.png");
  background-repeat: repeat;
  background-size: contain;
  margin-top: 5rem;

}
.intro-container {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 2rem;
}

.intro-image.desktop {
  display: none;
}
.intro-image img {
  max-width: 100%;
}

.intro-image.mobile img {
  margin-top: 15px;
  margin-left: -6px;
}

.intro-content {
  /* border: 1px solid red; */
}

.intro-content h1 {
  font-size: 3.3rem;
  color: #0d4036;
  line-height: 1;
  font-weight: 900;
  /* font-family: "GT Walsheim ProCB";  */
}
.intro-content h1 span {
  color: #52ccb4;
}
.intro-content p {
  color: #0d4036;
  margin: 0.8rem 0;
  max-width: 19rem;
  font-family: "GT Walsheim Con";
  /* font-weight: 500; */
  font-weight: lighter;
  line-height: 1.6;
  font-size: 15px;
}
.intro-sales .cta-btn {
  display: inline-block;
  padding: 0.9rem 4rem;
  background: #52ccb4;
  color: #0d4036;
  font-family: "GT Walsheim Trial Cn Md";

  font-weight: 500;
  letter-spacing: 0.7px;
  border-radius: 6px;
  text-decoration: none;
  box-shadow: 2px 2px 1px rgba(0, 0, 0, 0.3);
}

/* =========================================BOOK SECTION */

.book-section {
  padding: 3rem 1.5rem;
  background-image: url("/img/second-bg.png");
  background-repeat: repeat;
  background-size: contain;
}

.book-container {
  text-align: center;
  color: #0d4036;
}

.book-header h3 {
  font-size: 1.3rem;
  font-weight: 700;
  font-family: "GT Walsheim ProCB";

}

.book-header p {
  max-width: 350px;
  margin: 1rem auto 2rem;
  padding: 0px 7px;
  line-height: 1.15;
  font-size: 17px;
  vertical-align: middle;
  font-family: "GT Walsheim Con";
  /* font-weight: 600; */
}

.book-cards {
  display: grid;
  gap: 0.1rem;
  margin-top: 1.5rem;
}

.book-card {
  overflow: hidden;
}

.book-card .card-image {
  position: relative;
  width: 100%;
  height: 400px;
}

.book-card .card-image img {
  width: 100%;
  height: 100%;
}

.book-section .closing-text {
  max-width: 350px;
  margin: 2.5rem auto 1.5rem;
  font-size: 1.3rem;
  padding: 0px 20px;

  font-weight: 700;
  font-family: "GT Walsheim ProCB";

}

.book-section .cta-btn {
  display: inline-block;
  padding: 1rem 2rem;
  background: #52ccb4;
  color: #0d4036;
  font-weight: 200;
  border-radius: 6px;
  text-decoration: none;
  margin-top: 1.3rem;
  box-shadow: 2px 2px 1px rgba(0, 0, 0, 0.3);

  font-family: "GT Walsheim Trial Cn Md";

  /* font-weight: 500; */
}

/* OVERLAY */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.55);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  z-index: 1000;
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.35s ease, visibility 0.35s ease;
}

.modal-overlay.active {
  opacity: 1;
  visibility: visible;
}

/* MODAL ANIMATION */
.modal-box {
  background-image: url("/img/first-bg.png");

  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  border-radius: 10px;
  padding: 1rem;
  width: 100%;
  max-width: 400px;
  position: relative;
  text-align: center;

  /* Animation starting state */
  transform: translateY(40px) scale(0.92);
  opacity: 0;
  transition: transform 0.35s ease, opacity 0.35s ease;
}

/* When overlay is active, animate modal in */
.modal-overlay.active .modal-box {
  transform: translateY(0) scale(1);
  opacity: 1;
}

/* TEXT */
.modal-box h3 {
  color: #0d4036;
  font-size: 1.1rem;
  line-height: 1.4;
  font-family: "GT Walsheim Trial Cn Md";
  font-weight: 500;
  margin-bottom: 3rem;
  padding: 0px 60px;
}
 .modal-form-fields .modal-input-fields {
      display: grid;
      grid-template-columns: repeat(1, 1fr);
      gap: 9px;
      margin-bottom: 20px;
    }

/* INPUTS */
.modal-box input {
  width: 100%;
  padding: 1rem 1rem 1rem 2rem;
  border-radius: 10px;
  border: none;
  outline: none;
  margin-bottom: 1.3rem;
  font-size: 0.95rem;
  box-shadow: 2px 10px 10px rgba(0, 0, 0, 0.12);
  border-bottom: 1.6px solid #0d4036;
}
.modal-box input::placeholder {
  color: #afaeae;
  font-family: "GT Walsheim Con";
  letter-spacing: 0.4px;
}

   .modal-box input:focus {
        border-color: #52ccb4;
        box-shadow: 0 4px 10px rgba(82, 204, 180, 0.2);
    }

    .modal-box input.error {
        border-color: #e74c3c;
    }

    .error-message {
        color: #e74c3c;
        font-size: 12px;
        margin-top: -10px;
        margin-bottom: 10px;
        display: none;
    }

    .error-message.show {
        display: block;
    }

    /* SUCCESS MESSAGE */
    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        border: 1px solid #c3e6cb;
        display: none;
    }

    .success-message.show {
        display: block;
    }

    /* SPINNER ANIMATION */
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* BUTTON DISABLED STATE */
    .form-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

/* BUTTON */
.form-btn {
  padding: 1rem;
  background: #0d4036;
  color: white;
  border-radius: 10px;
  border: none;
  cursor: pointer;
  font-size: 1.2rem;
  padding: 20px 50px;
  font-family: "GT Walsheim Con";
  letter-spacing: 0.4px;
}

/* CLOSE BUTTON */
.close-modal {
  position: absolute;
  top: 8px;
  right: 12px;
  background: none;
  border: none;
  font-size: 1.8rem;
  color: #0d4036;
  cursor: pointer;
}

/* ================================== Premium Section */

.premium-section {
  background-color: #fff;
}

.premium-section .content {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.premium-section .text-content {
  text-align: center;
  background-color: #e5ae56;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border-radius: 10px;
  padding: 40px;
  text-align: left;
  gap: 80px;
  margin: 16px;
  font-family: "GT Walsheim ProCB";

}
.premium-section .text-content-upper,
.premium-section .text-content-below {
  max-width: 210px;
}

.premium-section .text-content-upper h2 {
  font-size: 20px;
  color: #0d4036;
  padding-bottom: 20px;
  padding-right: 30px;
}
.premium-section .text-content-upper p {
  font-size: 16px;
  color: #0d4036;
  line-height: 1;
  margin-top: -4px;

  font-family: "GT Walsheim Con";
}
.premium-section .text-content-below p {
  font-size: 19px;
  color: #0d4036;
  line-height: 1;
  letter-spacing: 0.6px;
  font-weight: 400;
}
.premium-section .text-content-below p img {
  width: 50px;

  height: auto;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  position: relative;
  top: 5px;
  left: 10px;
}

.premium-section .info {
  background-image: url("/img/last-bg.png");
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  margin-bottom: 30px;
 

  padding: 35px 50px 20px 50px;
}

.premium-section .info h3 {
  font-size: 30px;
  color: #fff;
  margin-bottom: 10px;
  max-width: 250px;
  font-family: "GT Walsheim ProCB";

}

.premium-section .info .info-headings {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  color: #fff;
  margin-top: 30px;
}
.premium-section .info .info-headings > div {
  display: flex;
  flex-direction: column;
}

.premium-section .info .info-headings > div h5 {
  font-size: 40px;
  font-family: "GT Walsheim ProCB";

  
}
.premium-section .info .info-headings > div span {
  font-size: 13px;
  font-family: "GT Walsheim Con";
  letter-spacing: 0.5px;
  line-height: 1;
  margin-top: -5px;
}

.premium-section .info ul {
  list-style: disc;
  padding-left: 0;
  color: #fff;
  margin-top: 40px;
}

.premium-section .info ul li {
  font-size: 17px;

  font-family: "GT Walsheim Con";
  font-weight: 500;
  letter-spacing: 0.4px;
  margin-bottom: 10px;
  padding-right: 30px;
  line-height: 1;

}

.premium-section .learn-more {
  display: inline-block;
  margin-top: 60px;
  background-color: #cced60;
  color: #0d4036;
  padding: 1.1rem 1.6rem;

  text-decoration: none;
  border-radius: 5px;
  font-size: 15px;
  width: 100%;
  text-align: center;
  box-shadow: 2px 2px 1px rgba(0, 0, 0, 0.3);

  font-family: "GT Walsheim Con";
  font-weight: 500;
  margin-bottom: 20px;
  transition: background-color 0.3s ease-in-out;
}
.premium-section .learn-more:hover {
  background-color: #fff;
  color: #0d4036;
}

/* Tablet */
@media (min-width: 768px) {
  .intro-container {
    grid-template-columns: repeat(2, 1fr);
    gap: 0rem;
  }
  .intro-content {
    display: flex;
    flex-direction: column;
    justify-content: start;
    align-items: center;
    text-align: left;
    padding-top: 1rem;
  }
  .intro-content .intro-info {
    margin-left: 10%;
  }
  .intro-image.desktop {
    display: block;
  }

  .intro-image.mobile {
    display: none;
  }
  /* =========================================BOOK SECTION */

  .book-cards {
    max-width: 400px;
    margin: 0 auto;
  }

  .book-header p {
    max-width: 650px;
  }

  .book-cards {
    max-width: initial;
    grid-template-columns: repeat(3, 1fr);
  }

  .book-header p {
    padding: 0px 15px;
  }

  .book-card .card-image {
    width: 100%;
    height: 270px;
  }

  .book-section .closing-text {
    max-width: 450px;
    font-size: 1rem;
    padding: 0px 20px;
  }

  .book-section .cta-btn {
    margin-top: 0.3rem;
    box-shadow: 2px 2px 1px rgba(0, 0, 0, 0.3);
  }

   .modal-form-fields .modal-input-fields {
      grid-template-columns: repeat(2, 1fr);
    }

  .premium-section .learn-more {
    width: 50%;
  }
}
@media (min-width: 900px) {
  .intro-container {
    grid-template-columns: repeat(2, 1fr);
    gap: 0rem;
  }

  .intro-content {
    justify-content: start;
    align-items: end;
    text-align: left;
    padding-top: 4rem;
    margin-right: 16%;
    /* border: 1px solid red; */
  }
  .intro-content h1 {
    font-size: 4.5rem;
  }

  .intro-content .intro-info {
    margin-right: 9%;
  }

  .intro-image.desktop {
    padding: 1rem 1rem 0rem 0rem;
  }
  .intro-image.desktop {
    max-width: 100%;
    margin-left: -18%;
  }

  /* =========================================BOOK SECTION */
  .book-card .card-image {
    width: 100%;
    height: 400px;
  }

  .book-section .closing-text {
    font-size: 1.1rem;
  }

  /* =========================================MODAL */

  .modal-box {
    padding: 3rem 5rem;
    border-radius: 15px;
    max-width: 850px;
  }

  /* BUTTON */
  .form-btn {
    padding: 15px 60px;
    margin-top: 10px;
    font-size: 1rem;
  }
  .modal-box h3 {
    padding: 0px 0px;
  }

  /* ==================================== PREMIUM SECTION */
  .premium-section .content {
    flex-direction: row;
    padding: 20px;
  }
  .premium-section .text-content {
    margin: 0px;
    width: 440px;
    padding: 40px 30px;
    gap: 20px;
  }
  .premium-section .info-container {
    display: flex;
    gap: 30px;
  }
  .premium-section .info {
    border-radius: 10px;
    padding: 40px 50px;
  margin-bottom: 0px;

  }
  .premium-section .info ul {
    margin-top: 15px;
  }

  .premium-section .info-container h3 {
    font-size: 30px;
    padding-right: 30px;
    max-width: 300px;
  }
  .premium-section .info .info-headings {
    margin-top: 0px;
  }
  .premium-section .info ul li {
    font-size: 19px;
  }
  .premium-section .learn-more {
    width: 45%;
    margin-top: 5px;
  margin-bottom: 0px;

  }
}

@media (min-width: 1200px) {
    .intro-container {
    transform: translateX(40px);
  }
  .intro-content h1 {
    font-size: 5.2rem;
  }

  .intro-content p {
    font-size: 17px;
    line-height: 1.1;
    max-width: 21rem;
    margin-bottom: 1.2rem;
  }

  .intro-content .intro-info {
    margin-right: 0%;
    margin-left: 0%;
    align-self: start;
    transform: translateX(72px);

  }
}

</style>

@section('content')
    <section class="intro-sales">
        <div class="sales-container">
            <div class="intro-container">
                <div class="intro-content">
                    <h1>
                        Funding<br />
                        Readiness<br />
                        <span>Guidebook</span>
                    </h1>

                           <div class="intro-info">
                          <p>
                            A step-by-step guide to becoming investor-ready, built for
                            African entrepreneurs.
                          </p>

                              <a href="#getGuide" class="cta-btn">Find Out More</a>
                        </div>

                   
                </div>

                <div class="intro-image desktop">
                    <img src="{{ asset('./img/desktop.png') }}" loading="lazy" alt="Entrepreneur using laptop" />
                </div>
                <div class="intro-image mobile">
                    <img src="{{ asset('./img/mobile.png') }}" loading="lazy" alt="Entrepreneur using laptop" />
                </div>
            </div>
        </div>
    </section>

    <section class="book-section">
        <div class="sales-container">
            <div class="book-container">
                <div class="book-header">
                    <h3>About the Book</h3>
                    <p>
                        Funding is limited and competitive in Africa. This guide helps you
                        master your business story, understand your numbers, and pitch
                        with confidence — so you can attract the right investors or grow
                        sustainably without them.
                    </p>
                </div>

                <div class="book-cards">
                    <div class="book-card card-1">
                        <div class="card-image">
                            <img src="{{ asset('./img/card3.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                    <div class="book-card card-2">
                        <div class="card-image">
                            <img src="{{ asset('./img/card2.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                    <div class="book-card card-3" id="getGuide">
                        <div class="card-image">
                            <img src="{{ asset('./img/card1.png') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                </div>

                <p class="closing-text">
                    Walk away with a ready-to-use pitch deck, funding plan, and clarity
                    on your next move.
                </p>

                <a href="#" class="cta-btn" id="openModal">Get My Free Guide Now</a>
            </div>
        </div>
    </section>




        <section class="premium-section">
      <div class="sales-container">
        <div class="content">
          <div class="text-content">
            <div class="text-content-upper">
              <h2>When You’re Ready to Raise...</h2>
              <p>Get Access to the Ultimate Funding & Investor Directory</p>
            </div>
            <div class="text-content-below">
              <p>
                Once you’ve completed your free guide, take the next step.
                <img src="{{ asset('./img/arrow-right.png') }}" alt="" />
              </p>
              <!-- <span></span> -->
            </div>
          </div>
          <div class="info">
            <div class="info-container">
              <h3>Our premium Funding Directory includes:</h3>
              <div class="info-headings-container">
                <div class="info-headings">
                  <div>
                    <h5>40+</h5>
                    <span> African countries <br> covered </span>
                  </div>
                  <div>
                    <h5>100+</h5>
                    <span> Global grant funders/VC investors </span>
                  </div>
                </div>
                <ul>
                  <li>Verified investors actively funding African startups</li>
                  <li>
                    Bonus: Investor-ready templates and funding strategy
                    planners
                  </li>
                </ul>
              </div>
            </div>

            <a href="#" class="learn-more"
              >Learn More About the Investor Directory</a
            >
          </div>
        </div>
      </div>
    </section>

    <!-- FORM MODAL -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-box">
            <h3>Kindly Input Your Details Below<br />And Proceed To Download</h3>

            <div class="success-message" id="successMessage"></div>

            <form id="downloadForm">
                @csrf

                  <div class="modal-form-fields">
                    <div class="modal-input-fields">
                      <input type="text" name="name" placeholder="Enter Full name" required />
                      <div class="error-message" id="nameError"></div>

                      <input type="email" name="email" placeholder="Enter Email" required />
                      <div class="error-message" id="emailError"></div>
                    </div>

                    <div class="modal-input-fields">
                      <input type="text" name="companyName" placeholder="Company name" required />
                      <div class="error-message" id="nameError"></div>

                      <input type="text" name="phoneNumber" placeholder="Phone Number" required />
                      <div class="error-message" id="nameError"></div>

                
                    </div>
                </div>

                <button type="submit" class="form-btn" id="submitBtn">
                    <span class="btn-text">Download Now</span>
                    <span class="spinner" style="display: none;">
                        <div
                            style="display: inline-block; width: 16px; height: 16px; border: 2px solid transparent; border-top: 2px solid white; border-radius: 50%; animation: spin 1s linear infinite;">
                        </div>
                    </span>
                </button>
            </form>

            <button class="close-modal" id="closeModal"></button>
        </div>
    </div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const openModal = document.getElementById("openModal");
        const closeModal = document.getElementById("closeModal");
        const modalOverlay = document.getElementById("modalOverlay");
        const downloadForm = document.getElementById("downloadForm");
        const submitBtn = document.getElementById("submitBtn");
        const btnText = submitBtn.querySelector(".btn-text");
        const spinner = submitBtn.querySelector(".spinner");

        // Modal controls
        openModal.addEventListener("click", (e) => {
            e.preventDefault();
            modalOverlay.classList.add("active");
            clearErrors();
            clearSuccess();
        });

        closeModal.addEventListener("click", () => {
            modalOverlay.classList.remove("active");
            clearErrors();
            clearSuccess();
        });

        modalOverlay.addEventListener("click", (e) => {
            if (e.target === modalOverlay) {
                modalOverlay.classList.remove("active");
                clearErrors();
                clearSuccess();
            }
        });

        // Form submission
        downloadForm.addEventListener("submit", async function(e) {
            e.preventDefault();

            clearErrors();
            clearSuccess();

            // Get form data
            const formData = new FormData(downloadForm);
            const name = formData.get('name').trim();
            const email = formData.get('email').trim();

            // Basic validation
            let hasErrors = false;

            if (!name) {
                showError('nameError', 'Full name is required.');
                hasErrors = true;
            }

            if (!email) {
                showError('emailError', 'Email address is required.');
                hasErrors = true;
            } else if (!isValidEmail(email)) {
                showError('emailError', 'Please enter a valid email address.');
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            // Show loading state
            setLoading(true);

            try {
                const response = await fetch('/sales-book/download', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showSuccess(data.message);
                    downloadForm.reset();

                    // Auto-close modal after 3 seconds
                    setTimeout(() => {
                        modalOverlay.classList.remove("active");
                        clearSuccess();
                    }, 3000);

                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorElement = document.getElementById(field + 'Error');
                            if (errorElement) {
                                showError(field + 'Error', data.errors[field][0]);
                            }
                        });
                    } else {
                        showError('emailError', data.message ||
                            'An error occurred. Please try again.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showError('emailError',
                    'Network error. Please check your connection and try again.');
            } finally {
                setLoading(false);
            }
        });

        // Helper functions
        function setLoading(loading) {
            submitBtn.disabled = loading;
            btnText.style.display = loading ? 'none' : 'inline';
            spinner.style.display = loading ? 'inline' : 'none';
        }

        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            const inputElement = errorElement.previousElementSibling;

            errorElement.textContent = message;
            errorElement.classList.add('show');
            inputElement.classList.add('error');
        }

        function clearErrors() {
            const errorElements = document.querySelectorAll('.error-message');
            const inputElements = document.querySelectorAll('.modal-box input');

            errorElements.forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });

            inputElements.forEach(el => {
                el.classList.remove('error');
            });
        }

        function showSuccess(message) {
            const successElement = document.getElementById('successMessage');
            successElement.textContent = message;
            successElement.classList.add('show');
        }

        function clearSuccess() {
            const successElement = document.getElementById('successMessage');
            successElement.classList.remove('show');
            successElement.textContent = '';
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    });
</script>


