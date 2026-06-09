<div class="modal fade" id="waTemplateMessageModal" tabindex="-1" aria-labelledby="waTemplateMessageModalLabel"
    aria-hidden="true" data-wa-template-modal>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="waTemplateMessageModalLabel">Send Template Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form data-wa-template-send-form>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-light text-dark" data-wa-template-context>Conversation</span>
                        <small class="text-muted" data-wa-template-recipient-summary></small>
                    </div>

                    <div class="border rounded p-2 mb-3 d-none" data-wa-template-recipient-wrap>
                        <div class="row g-2">
                            <div class="col-12 col-md-4">
                                <label class="form-label">Name (optional)</label>
                                <input type="text" class="form-control form-control-sm"
                                    data-wa-template-recipient-name placeholder="e.g. John Doe">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control form-control-sm"
                                    data-wa-template-recipient-phone placeholder="+2348012345678">
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label">Country Code</label>
                                <input type="text" class="form-control form-control-sm"
                                    data-wa-template-recipient-country-code placeholder="234">
                            </div>
                            <div class="col-12 col-md-2 d-flex align-items-end">
                                <button class="btn btn-outline-secondary btn-sm w-100" type="button"
                                    data-wa-template-validate-recipient>Validate</button>
                            </div>
                        </div>
                        <div class="small mt-2" data-wa-template-recipient-validation></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Search Approved Templates</label>
                        <input type="text" class="form-control form-control-sm" data-wa-template-search
                            placeholder="Search by name, category, language...">
                    </div>

                    <div class="border rounded p-2 mb-3" style="max-height: 180px; overflow-y: auto;"
                        data-wa-template-list></div>

                    <div class="d-none" data-wa-template-selected-wrap>
                        <div class="border rounded p-2 mb-2">
                            <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                                <strong data-wa-template-selected-name></strong>
                                <span class="badge bg-success" data-wa-template-selected-status>Approved</span>
                                <span class="badge bg-secondary" data-wa-template-selected-category></span>
                                <span class="badge bg-light text-dark" data-wa-template-selected-language></span>
                            </div>
                            <small class="text-muted">Last Updated: <span
                                    data-wa-template-selected-updated></span></small>
                        </div>

                        <div class="border rounded p-2 mb-2" data-wa-template-variables-wrap>
                            <div class="fw-semibold mb-2">Template Variables</div>
                            <div class="row g-2" data-wa-template-variable-fields></div>
                        </div>

                        <div class="border rounded p-2">
                            <div class="fw-semibold mb-2">Live Preview</div>
                            <div class="small" data-wa-template-preview style="white-space: pre-wrap;"></div>
                        </div>
                    </div>

                    <div class="text-muted small" data-wa-template-empty-state>
                        Select a template to populate variables and preview the message.
                    </div>

                    <div class="small mt-3" data-wa-template-send-result></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" data-wa-template-send-button disabled>
                        Send Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
