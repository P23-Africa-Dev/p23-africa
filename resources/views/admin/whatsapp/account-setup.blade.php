<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.25rem;">WhatsApp Account Setup</h2>
            <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Meta WhatsApp Cloud API onboarding
                checklist for production deployment.</p>
        </div>
    </x-slot>

    <div class="py-4">
        @include('admin.whatsapp.partials.nav')

        <div class="card mb-3">
            <div class="card-header fw-semibold">Webhook Configuration</div>
            <div class="card-body">
                <div class="mb-2"><strong>Webhook URL:</strong></div>
                <div class="p-2 border rounded bg-light" style="font-family: monospace; word-break: break-all;">
                    {{ $webhookUrl }}</div>
                <div class="small text-muted mt-2">Use this URL in the Meta App dashboard for both verify and event
                    callbacks.</div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header fw-semibold">Meta Business Manager Steps</div>
                    <div class="card-body">
                        <ol class="mb-0">
                            <li>Create or open your Meta app and add WhatsApp product.</li>
                            <li>Connect a WhatsApp Business Account (WABA).</li>
                            <li>Create or import a phone number for Cloud API.</li>
                            <li>Generate a permanent system-user token with whatsapp_business_messaging scope.</li>
                            <li>Set webhook callback URL and verify token.</li>
                            <li>Subscribe to message, message_status, and template events.</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header fw-semibold">Application Runtime Settings</div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Graph API version: <strong>{{ $graphVersion }}</strong></li>
                            <li>Graph Base URL: <strong>{{ $baseUrl }}</strong></li>
                            <li>Enable queue worker fallback via scheduler on shared hosting.</li>
                            <li>Use database queue connection for reliability.</li>
                            <li>Keep verify token and webhook secret private.</li>
                            <li>Validate incoming signatures before processing events.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header fw-semibold">Test Flow Checklist</div>
            <div class="card-body">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" disabled>
                    <label class="form-check-label">Webhook verification successful (GET challenge response).</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" disabled>
                    <label class="form-check-label">Incoming message appears in conversation thread.</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" disabled>
                    <label class="form-check-label">Outgoing text sends through queue and status transitions to
                        sent/delivered/read.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" disabled>
                    <label class="form-check-label">Webhook logs show processed events and zero signature
                        failures.</label>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
