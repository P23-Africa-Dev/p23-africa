
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

{{-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> --}}

<style>

  


.form-sales-container {
  max-width: 1420px;
  margin: 0 auto;
}

.form-sales {
  padding: 2rem 1.7rem;
  /* padding: 2rem 2.5rem 2rem 1.9rem; */
  background-image: url("/img/first-bg.png");
  background-repeat: repeat;
  background-size: contain;
  margin-top: 5rem;

}
.form-container {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 2rem;
}

.form-image.desktop {
  display: none;
}
.form-image img {
  max-width: 100%;
}

.form-image.mobile img {
  margin-top: 15px;
  margin-left: -6px;
}

.form-content h1 {
  font-size: 2.15rem;
  color: #0d4036;
  line-height: 1.1;
  font-weight: 900;
  font-family: "GT Walsheim ProCB";

}
.form-content h1 span {
  color: #52ccb4;
}

.form-info {
  margin-top: 30px;
}
.form-info .input-fields {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}

.form-info input {
  width: 100%;
  padding: 1rem 0.5rem 1.4rem 1.6rem;
  border-radius: 5px;
  border: none;
  outline: none;
  margin-bottom: 1.5rem;
  font-size: 0.95rem;
  /* box-shadow: 2px 10px 10px rgba(0, 0, 0, 0.12); */
  border-bottom: 1.6px solid #0d4036;
}
.form-info input::placeholder {
  color: #afaeae;
  font-family: "GT Walsheim Con";
  letter-spacing: 0.4px;
  font-size: 11px;
}
.form-info .payment-button {
  display: inline-block;
  padding: 0.9rem 4rem;
  background: #0d4036;
  font-family: "GT Walsheim Con";

  outline: none;

  color: #fff;
  font-weight: 400;
  border-radius: 6px;
  margin-top: 50px;
  border: none;
  letter-spacing: 0.5px;
}

/* #FBFBFB */
.form-info .summary-card {
  /* border-top: 4px solid #f4d9fb;
  border-bottom: 4px solid #f4d9fb; */
  border-radius: 15px;

  background-color: #fbfbfb;
  padding: 0px 30px;
  width: 100%;
}
.summary-card .pattern {
  width: 96%;
  margin: 0 auto;
  background-color: #f4d9fb;
  height: 5px;
  border-radius: 20px 20px 0px 0px ;
}
.summary-card .pattern.bottom {
  margin-top: 20px;

  border-radius: 0px 0px 20px 20px ;
}
.summary-card .pattern.top {
  margin-bottom: 20px;

}
.form-info .summary-card .summary-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #abaaaa;
  padding-bottom: 24px;
  padding-top: 20px;
}
.form-info .summary-card .summary-price {
  display: flex;
  justify-content: space-between;
  align-items: center;

  padding-bottom: 24px;
  padding-top: 20px;
}

.item-description {
  color: #6b7b78;
  font-family: "GT Walsheim Con";
  font-size: 18px;
  font-weight: 500;
  letter-spacing: -0.4px;
}
.item-price,
.total-label {
  color: #0d4036;
  font-weight: 900;
  font-family: "GT Walsheim ProCB";

}

.total-label {
  font-family: "GT Walsheim ProCB";

  font-size: 18px;
  font-weight: 900;
}
/* Tablet */
@media (min-width: 768px) {
  .form-container {
    grid-template-columns: repeat(2, 1fr);
    gap: 0rem;
  }
  .form-content {
    display: flex;
    flex-direction: column;
    padding-top: 0rem;
    margin-left: 7%;
  }
  .form-content h1 {
    font-size: 1.5rem;
  }

  .form-image.desktop {
    display: block;

    margin-left: 0%;
    overflow: hidden;
    max-width: initial;
    margin-left: 0%;
    max-width: 50rem;
    position: relative;
  }

  .form-image.desktop img {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    object-fit: contain;
    overflow: hidden;
  }

  .form-image.mobile {
    display: none;
  }
  .form-info {
    margin-top: 20px;
  }

  .form-info .summary-card {
    margin: 0px 20px;
  }
}

@media (min-width: 900px) {
  .form-container {
    grid-template-columns: repeat(2, 1fr);
    gap: 0rem;
  }

  .form-content h1 {
    padding-right: 8px;
  }

  .form-image.desktop {
    max-width: 100%;
  }
  .form-image.desktop img {
    height: 100%;
    width: 100%;
  }
}

@media (min-width: 1024px) {
  .form-content {
    padding-left: 80px;
  }

  .form-image.desktop {
    max-width: 100%;
    width: 500px;
  }
}
@media (min-width: 1200px) {
  .form-sales{
  padding-bottom: 10rem;

  }

  .form-info {
  margin-top: 10px;
}
  .form-content {
    margin-right: -5%;
    margin-top: 50px;
    padding-top: 1.6rem;
  }

  .form-info .summary-card {
    margin-left: -2px;
   max-width: 410px;


  }
  .form-content h1 {
    padding-right: 0px;
    font-size: 2rem;
  }

  .form-info .input-fields {
   max-width: 410px;

}

  .form-image.desktop {
    max-width: initial;
    margin-left: 0%;
    width: 47rem;
    overflow: visible;

  }
  .form-image.desktop img {
   
    transform: translateX(-120px) ;

    margin-top: 3.2rem;
    

  }
}






/* ========================================================   PAYMENT SUCCES STYLES */



.payment-section {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px 16px;
        margin: 5rem 0rem 0rem 0rem;
  background-image: url("/img/first-bg.png");
    background-repeat: repeat;
  background-size: contain;
  

    }

    .payment-card {
        background: #fff;
        max-width: 620px;
        width: 100%;
        padding: 32px 24px;
        border-radius: 16px;
        box-shadow: 0px 3px 5px rgba(0,0,0,0.4);
        text-align: center;
    }

    .payment-success-icon {
        height: 60px;
        width: 60px;
        border-radius: 50%;
        background: #23A26D1F;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .payment-success-icon span {
        height: 28px;
        width: 28px;
        background: #23A26D;
        border-radius: 50%;
        color: #fff;
        padding: 5px;

        
        
    }

    .payment-success-icon span svg{
      font-size: 10px;
      font-weight: bold;
    }


    .payment-title {
        margin: 0 0 24px;
        font-size: 22px;
        font-weight: 600;
         font-family: "Poppins", sans-serif;

    }

    .payment-details {
        width: 100%;
        margin-top: 20px;
    }

    .payment-details > div.payment-divider.first{
      margin-bottom: 30px;
    
    }

    .payment-details .payment-divider{
       width: 60%;
       margin: 30px auto 20px auto;
       height: 1.5px;
         border: 1px  dashed #DCDEE0 ;

    }

    .payment-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #444;
         font-family: "Poppins", sans-serif;

    }

    .payment-row span {
        color: #707070;
        font-weight: 400;
         font-family: "Poppins", sans-serif;


    }
    .payment-row p {
        color: #121212;
        font-weight: 500;
    }
    .payment-row p.amount {
        color: #121212;

        font-size: 18px;
        font-weight: 700;
    }


    .payment-info-box {
        padding: 14px 18px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: transparent;
        font-size: 14px;
        text-align: center;
        margin-top: 20px;
        color: #121212;
        margin-top: 50px;
         font-family: "Poppins", sans-serif;

    }

    .payment-footer-btn {
        margin-top: 36px;
        background: #063b2e;
        color: #fff;
        border: none;
        padding: 10px 5px;
        width: 100%;
        font-size: 16px;
        font-weight: 500;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s ease;
         font-family: "Poppins", sans-serif;
         max-width: 600px;

    }



    @media (max-width: 480px) {
        .payment-card {
            padding: 24px 18px;
        }
    }


</style>

@section('content')
  


      <section class="payment-section">
      <div class="payment-card">
        <div class="payment-success-icon">
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
</svg>

          </span>
        </div>

        <h2 class="payment-title">Payment Success!</h2>

        <div class="payment-details">

          <div class="payment-divider first" ></div>

          <div class="payment-row">
            <span>References Number</span>
            <p>000085752257</p>
          </div>

          <div class="payment-row">
            <span>Date</span>
            <p>Mar 22, 2023</p>
          </div>

          <div class="payment-row">
            <span>Time</span>
            <p>07:80 AM</p>
          </div>

          <div class="payment-row">
            <span>Payment Method</span>
            <p>Credit Card</p>
          </div>


          <div class="payment-row">
            <span>Amount</span>
            <p class="amount">$10.1</p>
          </div>

          <div class="payment-divider" ></div>

        </div>

        <div class="payment-info-box">
          Your Guide has been sent to customer@example.com
        </div>

      </div>

        <button class="payment-footer-btn">Back to Home</button>

    </section>


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
