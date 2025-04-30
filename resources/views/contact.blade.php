@extends('layouts.master')
@section('title', 'Contact | P23 Africa')


@section('content')
    <!-- Contact Section -->
    <section class="contact-section">
        
    </section>
    <div class="container" id="sec2">
        <div class="row">
            <!-- Text Side -->
            <div class="col-12 col-md-6 contact-text text-center text-md-start mb-4 mb-md-0">
                <h2>Contact Us</h2>
                <p>Ready to expand your business?<br>
                    <strong>Contact P23 Africa and let’s make growth happen.</strong>
                </p>
            </div>

            <!-- Image Side -->
            <div class="col-12 col-md-6 text-center">
                <div class="contact-image mx-auto" style="max-width: 380px;">
                    <img src="{{ asset('images/contact_header.jpg') }}" alt="Smiling Woman" />
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Info -->
    <section class="contact-info">
        <div class="container text-left text-md-start">
            <h5 class="text-center text-md-start">Contact Information</h5>
            <div style="gap: 20px;" class="d-flex flex-lg-row    col-lg-6 justify-content-center justify-content-md-start text-left text-md-start">
                <div class="col-md-4 info-box mb-4 mb-md-0">
                    <i class="fas fa-phone-alt"></i>
                    <p><a href="tel:+442080921281" class="text-dark text-decoration-none fw-bold">+44 208 0921 281</a></p>
                </div>
                <div class="col-md-4 info-box mb-4 mb-md-0">
                    <i class="fas fa-envelope"></i>
                    <p><a href="mailto:hello@p23africa.com"
                            class="text-dark text-decoration-none fw-bold">hello@p23africa.com</a></p>
                </div>
                <div class="col-md-4 info-box">
                    <i class="fas fa-map-marker-alt"></i>
                    <p class="fw-bold">London UK</p>
                </div>
            </div>
        </div>
    </section>

    <section id="contact_form">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div id="form">
                        <div>
                            <h6>Leave us a message</h6>
                            @if(session('success'))
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: "{{ session('success') }}"
                                });
                            </script>
                        @endif
                            <div>
                                <form action="{{ route('contact.submit') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Name" name="name" id="">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="Email Address" name="email" id="">
                                    </div>
                                    <div class="form-group">
                                        <textarea name="message" class="form-control" placeholder="Your Message" id="" cols="30" rows="7"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn send_messages">Send</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div id="map">
                        <strong>Location</strong>
                        <p>
                            185 Tower Bridge Rd <br>
                            185 Tower Bridge Rd, London SE1 2UF, UK
                        </p>
                        <div>
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9582196.954783212!2d-15.016855774923814!3d54.1020496614009!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x25a3b1142c791a9%3A0xc4f8a0433288257a!2sUnited%20Kingdom!5e0!3m2!1sen!2sng!4v1745502051728!5m2!1sen!2sng" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>

                        <br>
                        <strong>Social Media</strong>
                        <div id="icons">
                            <a href="#" class="fa-brands fa-linkedin mx-1"></a>
                            <a href="#" class="fa-brands fa-square-facebook mx-1"></a>
                            <a href="#" class="fa-brands fa-square-instagram mx-1"></a>
                            <a href="#" class="fa-brands fa-youtube mx-1"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
