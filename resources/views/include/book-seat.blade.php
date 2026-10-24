<style>
    #seatForm input {
        border: 0;
        border-bottom: 2px solid #0D4036;
        box-shadow: 2px 2px 4px #0000008e;
    }

    #seatForm .btn {
        background-color: #0D4036;
        color: #fff;
        font-family: 'GT Walsheim Con';
    }

    .modal-content {
        border-radius: 1rem;
    }

    /* Phone input styling */
    .country-code-select {
        border: 0;
        border-bottom: 2px solid #0D4036;
        box-shadow: 2px 2px 4px #0000008e;
        background: white;
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 4px 4px 0 0;
    }

    .phone-input {
        border: 0;
        border-bottom: 2px solid #0D4036;
        box-shadow: 2px 2px 4px #0000008e;
    }

    .country-code-select:focus,
    .phone-input:focus {
        outline: none;
        border-bottom-color: #0a2e26;
    }
</style>

<!-- Seat Booking Modal -->
<div class="modal fade w-100" id="seatModal" tabindex="-1" aria-labelledby="seatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 py-3 px-5">
            <div class="modal-header border-0">
                {{-- <h5 class="modal-title text-success" id="seatModalLabel">Book a Seat</h5> --}}
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-2" style="color: #0D4036; font-weight: 500; font-family: 'GT Walsheim Con';"><em>Book
                        your seat to be a part of this exclusive event and gain valuable insights from trusted
                        experts.</em></p><br>
                <form id="seatForm">
                    @csrf
                    <input type="hidden" name="event_id" id="event_id">
                    <div id="dynamic-fields">
                        <!-- Dynamic fields will be populated by JavaScript -->
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
                Thank you for signing up. We've sent event details to your email <br>
                <span class="">— please check your inbox.</span>
            </h5>
            <a href="{{ locale_route('events.all-events') }}" class="btn mt-3"
                style="background-color: #0D4036; color: #fff; font-family: 'GT Walsheim Con';">
                View Other Events <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</div>
