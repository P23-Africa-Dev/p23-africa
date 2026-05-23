<div class="modal fade" id="developerAccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-shield-lock me-1"></i>
                    Developer Access Required
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3" style="font-size:.875rem;">
                    Sensitive WhatsApp credentials are locked. Enter the developer passcode to unlock edit access for 15
                    minutes.
                </p>

                <div class="mb-3">
                    <label class="form-label">Developer Passcode</label>
                    <input type="password" class="form-control" id="developerPasscodeInput" autocomplete="off"
                        placeholder="Enter passcode">
                    <div class="invalid-feedback" id="developerPasscodeError"></div>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success" id="developerPasscodeSubmit">
                        <i class="bi bi-unlock me-1"></i>
                        Enter Passcode
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="contactDeveloperButton">
                        <i class="bi bi-envelope me-1"></i>
                        Contact Developer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
