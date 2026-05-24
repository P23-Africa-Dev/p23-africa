<div class="modal fade" id="waNewChatModal" tabindex="-1" aria-labelledby="waNewChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="waNewChatModalLabel">Start New WhatsApp Chat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form data-wa-new-chat-form>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Contact name</label>
                        <input type="text" class="form-control form-control-sm" name="name"
                            placeholder="e.g. John Doe">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Phone number</label>
                        <input type="text" class="form-control form-control-sm" name="phone_number"
                            placeholder="+233501234567" required>
                    </div>
                    <div class="d-flex gap-2 mb-2">
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-wa-validate-number>Validate
                            Number</button>
                        <button class="btn btn-success btn-sm" type="submit" data-wa-start-chat>Start Chat</button>
                    </div>
                    <div class="small" data-wa-validation-result></div>
                </div>
            </form>
        </div>
    </div>
</div>
