@extends('layouts.brn-layout')
@section('title', 'NOELHQ Sales | P23 Africa')

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

  
body {
  font-family: "GT Walsheim Trial Md";
}

.container {
  max-width: 1024px;
  margin: 0 auto;
}

.intro-sales {
  padding: 2rem 1.5rem;
  background-image: url("./img/first-bg.png");
  background-repeat: repeat;
  background-size: contain;
}

.intro-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.intro-content h1 {
  font-size: 2.8rem;
  font-weight: 900;
  color: #0d4036;
  line-height: 1.1;
}

.intro-content h1 span {
  color: #52ccb4;
}

.intro-content p {
  color: #0d4036;
  font-weight: 200;
  margin: 1rem 0 1.5rem;
  max-width: 22rem;
}

.intro-sales .cta-btn {
  display: inline-block;
  padding: 0.9rem 4rem;
  background: #52ccb4;
  color: #0d4036;
  font-weight: 200;
  border-radius: 6px;
  text-decoration: none;
  margin-bottom: 2rem;
  box-shadow: 5px 2px 1px rgba(0, 0, 0, 0.5);
}

.stats-box {
  background: white;
  padding: 1.2rem 1.5rem;
  border-radius: 8px;
  width: fit-content;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
}

.stats-box h2 {
  font-size: 2rem;
  font-weight: 800;
  color: #0d4036;
}

.stats-box p {
  margin-top: 0.3rem;
  color: #0d4036;
  font-size: 0.9rem;
}

.intro-image.desktop {
  display: none;
}

.intro-image img {
  width: 100%;
  border-radius: 40px;
  object-fit: cover;
}

/* =========================================BOOK SECTION */

.book-section {
  padding: 3rem 1.5rem;
  background-image: url("./img/second-bg.png");
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
}

.book-header p {
  max-width: 650px;
  margin: 1rem auto 2rem;
  line-height: 1.6;
}

.book-cards {
  display: grid;
  gap: 1.5rem;
  margin-top: 1.5rem;
}

.book-card {
  /* border-radius: 12px; */

  overflow: hidden;
}

.book-card .card-image {
  position: relative;
  width: 100%;
  height: 400px;

  /* justify-content: flex-start; */
}

.book-card .card-image img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.book-card h4 {
  margin: 1rem 0 0.5rem;
  font-size: 1.1rem;
  font-weight: 700;
}

.book-card p {
  padding: 0 1rem;
  line-height: 1.5;
  font-size: 0.95rem;
}

.closing-text {
  max-width: 650px;
  margin: 2.5rem auto 1.5rem;
  font-size: 1rem;
}

.book-section .cta-btn {
  display: inline-block;
  padding: 1rem 2rem;
  background: #0d4036;
  color: white;
  font-weight: 600;
  text-decoration: none;
  border-radius: 8px;
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
  background: #ffffff;
  background-image: url("bg-texture.png");
  background-size: cover;
  border-radius: 18px;
  padding: 2rem;
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
  font-weight: 700;
  line-height: 1.4;
  margin-bottom: 1.5rem;
}

/* INPUTS */
.modal-box input {
  width: 100%;
  padding: 1rem;
  border-radius: 10px;
  border: none;
  outline: none;
  margin-bottom: 1rem;
  font-size: 0.95rem;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
}

/* BUTTON */
.form-btn {
  width: 100%;
  padding: 1rem;
  background: #0d4036;
  color: white;
  font-weight: 600;
  border-radius: 10px;
  border: none;
  cursor: pointer;
  font-size: 1rem;
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
  padding: 30px;
  text-align: left;
  gap: 60px;
  margin: 20px;
}
.premium-section .text-content-upper,
.premium-section .text-content-below {
  max-width: 210px;
}

.premium-section .text-content-upper h2 {
  font-size: 20px;
  color: #0d4036;
  padding-bottom: 20px;
}
.premium-section .text-content-upper p {
  font-size: 16px;
  color: #0d4036;
}
.premium-section .text-content-below p {
  font-size: 19px;
  color: #0d4036;
  font-weight: 700;
}

.premium-section .info {
  background-color: #2f626e;
  padding: 20px 50px;
}

.premium-section .info h3 {
  font-size: 35px;
  color: #fff;
  margin-bottom: 10px;
}

.premium-section .info .info-headings {
  display: flex;
  color: #fff;
  margin-top: 30px;
}
.premium-section .info .info-headings > div {
  display: flex;
  flex-direction: column;
}

.premium-section .info .info-headings > div h5 {
  font-size: 40px;
}

.premium-section .info ul {
  list-style: disc;
  padding-left: 0;
  color: #fff;
  margin-top: 40px;
}

.premium-section .info ul li {
  font-size: 16px;
  margin-bottom: 10px;
}

.premium-section .learn-more {
  display: inline-block;
  margin-top: 20px;
  background-color: #cced60;
  color: #0d4036;
  padding: 10px 20px;
  text-decoration: none;
  border-radius: 5px;
  font-size: 15px;
  width: 100%;
  text-align: center;
  box-shadow: 5px 2px 1px rgba(0, 0, 0, 0.5);

}


/* Desktop */
@media (min-width: 768px) {
  .intro-container {
    /* max-width: 600px;
    margin: 0 auto; */
  }
  .intro-image {
    max-width: 80%;
  }
}
@media (min-width: 900px) {
  .intro-container {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }

  .intro-content {
    max-width: 45%;
  }

  .intro-image.mobile {
    display: none;
  }
  .intro-image.desktop {
    display: block;
  }

  .intro-image {
    max-width: 60%;
  }

  .intro-content h1 {
    font-size: 3.8rem;
  }

  .book-cards {
    grid-template-columns: repeat(3, 1fr);
  }

  .premium-section .content {
    flex-direction: row;
    padding: 20px;
  }
  .premium-section .text-content {
    margin: 0px;
    flex: 35%;
  }
  .premium-section .info-container {
    display: flex;
  }
  .premium-section .info {
    border-radius: 10px;
  }

  .premium-section .info-container h3 {
    font-size: 35px;
    padding-right: 30px;
    max-width: 400px;
  }
  .premium-section .info .info-headings {
  margin-top: 0px;
    
  }
  .premium-section .learn-more{
    width: 60%;
  }
}




</style>

@section('content')
 <section class="intro-sales">
      <div class="container">
        <div class="intro-container">
          <div class="intro-content">
            <h1>
              Funding<br />
              Readiness<br />
              <span>Guidebook</span>
            </h1>

            <p>
              A step-by-step guide to becoming investor-ready, built for African
              entrepreneurs.
            </p>

            <a href="#" class="cta-btn">Find Out More</a>
          </div>

          <div class="intro-image desktop">
            <img src="./img/desktop.png" alt="Entrepreneur using laptop" />
          </div>
          <div class="intro-image mobile">
            <img src="./img/mobile.png" alt="Entrepreneur using laptop" />
          </div>
        </div>
      </div>
    </section>

    <section class="book-section">
      <div class="container">
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
                <img src="./img/card3.png" alt="" />
              </div>
            </div>
            <div class="book-card card-2">
              <div class="card-image">
                <img src="./img/card2.png" alt="" />
              </div>
            </div>
            <div class="book-card card-3">
              <div class="card-image">
                <img src="./img/card1.png" alt="" />
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
      <div class="container">
        <div class="content">
          <div class="text-content">
            <div class="text-content-upper">
              <h2>When You’re Ready to Raise...</h2>
              <p>Get Access to the Ultimate Funding & Investor Directory</p>
            </div>
            <div class="text-content-below">
              <p>Once you’ve completed your free guide, take the next step.</p>
              <span></span>
            </div>
          </div>
          <div class="info">
            <div class="info-container">
              <h3>Our premium Funding Directory includes:</h3>
              <div class="info-headings-container">
                <div class="info-headings">
                  <div>
                    <h5>40+</h5>
                    <span> African countries covered </span>
                  </div>
                  <div>
                    <h5>40+</h5>
                    <span> African countries covered </span>
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

        <form id="downloadForm">
          <input type="text" placeholder="Full name" required />
          <input type="email" placeholder="Email" required />
          <button type="submit" class="form-btn">Download Now</button>
        </form>

        <button class="close-modal" id="closeModal">&times;</button>
      </div>
    </div>
@endsection


    <script>
      const openModal = document.getElementById("openModal");
      const closeModal = document.getElementById("closeModal");
      const modalOverlay = document.getElementById("modalOverlay");

      openModal.addEventListener("click", (e) => {
        e.preventDefault();
        modalOverlay.classList.add("active");
      });

      closeModal.addEventListener("click", () => {
        modalOverlay.classList.remove("active");
      });

      modalOverlay.addEventListener("click", (e) => {
        if (e.target === modalOverlay) {
          modalOverlay.classList.remove("active");
        }
      });

      document
        .getElementById("downloadForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();
          alert("Your download will start now!");
          modalOverlay.classList.remove("active");
        });
    </script>