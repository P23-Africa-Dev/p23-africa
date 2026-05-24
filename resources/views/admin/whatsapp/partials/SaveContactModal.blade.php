<div class="modal fade" id="waSaveContactModal" tabindex="-1" aria-labelledby="waSaveContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="waSaveContactModalLabel">Save Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form data-wa-save-contact-form>
                <div class="modal-body">
                    <input type="hidden" name="phone_number" data-wa-save-phone>
                    <input type="hidden" name="country_code" data-wa-save-country-code>

                    <div class="mb-2">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" data-wa-save-name required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="john@example.com">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Company</label>
                        <input type="text" class="form-control" name="company" placeholder="Company name">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3" name="notes" placeholder="Optional notes"></textarea>
                    </div>
                    <div class="small" data-wa-save-contact-result></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Contact</button>
                </div>
            </form>
        </div>
    </div>
</div>
