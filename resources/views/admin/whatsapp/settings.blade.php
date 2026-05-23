<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.25rem;">WhatsApp Settings</h2>
            <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Company-safe configuration with
                developer-locked credentials.</p>
        </div>
    </x-slot>

    <div class="py-4">
        @include('admin.whatsapp.partials.nav')

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                <div class="card mb-3">
                    <div class="card-header fw-semibold">Company View</div>
                    <div class="card-body">
                        @if ($activeAccount)
                            <form method="POST" action="{{ orgRoute('admin.whatsapp.settings.account.save') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $activeAccount->id }}">
                                <input type="hidden" name="phone_number" value="{{ $activeAccount->phone_number }}">

                                <div class="mb-3">
                                    <label class="form-label">Business Name</label>
                                    <input type="text" name="business_name" class="form-control"
                                        value="{{ old('business_name', $activeAccount->business_name) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Business Number</label>
                                    <input type="text" class="form-control"
                                        value="{{ $activeAccount->phone_number }}" disabled readonly>
                                    <small class="text-muted">Read-only. Contact developer for number changes.</small>
                                </div>

                                <button class="btn btn-success" type="submit">Save Business Name</button>
                            </form>
                        @else
                            <div class="alert alert-warning mb-0">
                                No active WhatsApp account is configured yet. Ask a developer to complete setup.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Developer-Locked Credentials</span>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-dark btn-sm"
                                id="unlockDeveloperSettingsButton">
                                <i class="bi bi-shield-lock me-1"></i> Unlock Developer Settings
                            </button>
                            <button type="button"
                                class="btn btn-outline-secondary btn-sm {{ $unlocked ? '' : 'd-none' }}"
                                id="relockDeveloperSettingsButton">
                                <i class="bi bi-lock me-1"></i> Relock
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info {{ $unlocked ? '' : 'd-none' }}" id="developerUnlockedAlert">
                            Developer editing session is active for up to 15 minutes.
                        </div>

                        <form method="POST" action="{{ orgRoute('admin.whatsapp.settings.sensitive.save') }}"
                            id="sensitiveSettingsForm">
                            @csrf

                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label"><i class="bi bi-lock me-1"></i>Phone Number ID</label>
                                    <input type="text" name="phone_number_id" class="form-control sensitive-field"
                                        value="{{ $sensitiveMasked['phone_number_id'] }}" disabled>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label"><i class="bi bi-lock me-1"></i>Business Account ID</label>
                                    <input type="text" name="business_account_id"
                                        class="form-control sensitive-field"
                                        value="{{ $sensitiveMasked['business_account_id'] }}" disabled>
                                </div>
                            </div>

                            <div class="row g-2 mt-1">
                                <div class="col-12 col-md-6">
                                    <label class="form-label"><i class="bi bi-lock me-1"></i>WABA ID</label>
                                    <input type="text" name="waba_id" class="form-control sensitive-field"
                                        value="{{ $sensitiveMasked['waba_id'] }}" disabled>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label"><i class="bi bi-lock me-1"></i>Status</label>
                                    <select name="status" class="form-select sensitive-field" disabled>
                                        <option value="active">{{ $sensitiveMasked['status'] }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-2 mt-2">
                                <label class="form-label"><i class="bi bi-lock me-1"></i>Access Token</label>
                                <textarea name="access_token" class="form-control sensitive-field" rows="3" disabled>{{ $sensitiveMasked['access_token'] }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-lock me-1"></i>Webhook Verify Token</label>
                                <input type="text" name="webhook_verify_token" class="form-control sensitive-field"
                                    value="{{ $sensitiveMasked['webhook_verify_token'] }}" disabled>
                            </div>

                            <button class="btn btn-dark" type="submit" id="saveSensitiveButton" disabled>
                                Save Developer Fields
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card mb-3">
                    <div class="card-header fw-semibold">Runtime Configuration</div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Enabled</span><strong>{{ $config['enabled'] ? 'Yes' : 'No' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between"><span>Queue
                                    Enabled</span><strong>{{ $config['queue'] ? 'Yes' : 'No' }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>Poll
                                    Interval</span><strong>{{ $config['poll_interval'] }}s</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>Graph
                                    Version</span><strong>{{ $config['graph_version'] }}</strong></li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header fw-semibold">Saved Accounts</div>
                    <div class="card-body">
                        @forelse ($accounts as $account)
                            <div class="border rounded p-2 mb-2">
                                <div class="fw-semibold">{{ $account->business_name }}</div>
                                <div class="small text-muted">{{ $account->phone_number }}</div>
                            </div>
                        @empty
                            <div class="text-muted">No WhatsApp accounts saved yet.</div>
                        @endforelse
                    </div>
                </div>

                @if ($canManageDeveloperSecurity)
                    <div class="mt-3">
                        <a href="{{ orgRoute('admin.whatsapp.developer-security') }}"
                            class="btn btn-outline-dark w-100">
                            Open Developer Security Settings
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('admin.whatsapp.partials.DeveloperAccessModal')

    @push('scripts')
        <script>
            (function() {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const unlockUrl = '{{ orgRoute('admin.whatsapp.settings.unlock') }}';
                const lockUrl = '{{ orgRoute('admin.whatsapp.settings.lock') }}';
                const sensitiveDataUrl = '{{ orgRoute('admin.whatsapp.settings.sensitive-data') }}';
                let unlocked = {{ $unlocked ? 'true' : 'false' }};

                const unlockButton = document.getElementById('unlockDeveloperSettingsButton');
                const relockButton = document.getElementById('relockDeveloperSettingsButton');
                const saveSensitiveButton = document.getElementById('saveSensitiveButton');
                const sensitiveForm = document.getElementById('sensitiveSettingsForm');
                const unlockedAlert = document.getElementById('developerUnlockedAlert');
                const modalEl = document.getElementById('developerAccessModal');
                const passcodeInput = document.getElementById('developerPasscodeInput');
                const passcodeError = document.getElementById('developerPasscodeError');
                const passcodeSubmit = document.getElementById('developerPasscodeSubmit');
                const contactDeveloperButton = document.getElementById('contactDeveloperButton');
                const sensitiveFields = Array.from(document.querySelectorAll('.sensitive-field'));
                const modal = modalEl ? new bootstrap.Modal(modalEl) : null;

                function setSensitiveDisabled(disabled) {
                    sensitiveFields.forEach((field) => {
                        field.disabled = disabled;
                    });

                    if (saveSensitiveButton) {
                        saveSensitiveButton.disabled = disabled;
                    }
                }

                function updateUiState() {
                    if (unlockedAlert) {
                        unlockedAlert.classList.toggle('d-none', !unlocked);
                    }

                    if (relockButton) {
                        relockButton.classList.toggle('d-none', !unlocked);
                    }

                    setSensitiveDisabled(!unlocked);
                }

                function showModal() {
                    if (passcodeError) {
                        passcodeError.textContent = '';
                    }
                    if (passcodeInput) {
                        passcodeInput.value = '';
                        passcodeInput.classList.remove('is-invalid');
                    }
                    if (modal) {
                        modal.show();
                    }
                }

                function fillSensitiveData(data) {
                    if (!data) {
                        return;
                    }

                    if (sensitiveForm?.elements.namedItem('phone_number_id')) {
                        sensitiveForm.elements.namedItem('phone_number_id').value = data.phone_number_id || '';
                    }
                    if (sensitiveForm?.elements.namedItem('business_account_id')) {
                        sensitiveForm.elements.namedItem('business_account_id').value = data.business_account_id || '';
                    }
                    if (sensitiveForm?.elements.namedItem('waba_id')) {
                        sensitiveForm.elements.namedItem('waba_id').value = data.waba_id || '';
                    }
                    if (sensitiveForm?.elements.namedItem('status')) {
                        const statusSelect = sensitiveForm.elements.namedItem('status');
                        statusSelect.innerHTML = '';
                        ['active', 'inactive'].forEach((status) => {
                            const option = document.createElement('option');
                            option.value = status;
                            option.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                            option.selected = status === data.status;
                            statusSelect.appendChild(option);
                        });
                    }
                    if (sensitiveForm?.elements.namedItem('access_token')) {
                        sensitiveForm.elements.namedItem('access_token').value = data.access_token || '';
                    }
                    if (sensitiveForm?.elements.namedItem('webhook_verify_token')) {
                        sensitiveForm.elements.namedItem('webhook_verify_token').value = data.webhook_verify_token || '';
                    }
                }

                async function fetchSensitiveData() {
                    const response = await fetch(sensitiveDataUrl, {
                        method: 'GET',
                        headers: {
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                    });

                    const payload = await response.json();

                    if (!response.ok || !payload.success) {
                        throw new Error(payload.message || 'Developer access required.');
                    }

                    fillSensitiveData(payload.data || {});
                }

                async function unlockWithPasscode() {
                    if (!passcodeInput || !passcodeSubmit) {
                        return;
                    }

                    passcodeSubmit.disabled = true;

                    try {
                        const response = await fetch(unlockUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                Accept: 'application/json',
                                'X-CSRF-TOKEN': csrf,
                            },
                            body: JSON.stringify({
                                passcode: passcodeInput.value || '',
                            }),
                        });

                        const payload = await response.json();

                        if (!response.ok || !payload.success) {
                            throw new Error(payload.message || 'Invalid passcode.');
                        }

                        await fetchSensitiveData();
                        unlocked = true;
                        updateUiState();

                        if (modal) {
                            modal.hide();
                        }
                    } catch (error) {
                        if (passcodeError) {
                            passcodeError.textContent = error.message || 'Invalid passcode.';
                        }
                        if (passcodeInput) {
                            passcodeInput.classList.add('is-invalid');
                        }
                    } finally {
                        passcodeSubmit.disabled = false;
                    }
                }

                async function relock() {
                    await fetch(lockUrl, {
                        method: 'POST',
                        headers: {
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                    });

                    window.location.reload();
                }

                if (unlockButton) {
                    unlockButton.addEventListener('click', showModal);
                }

                if (passcodeSubmit) {
                    passcodeSubmit.addEventListener('click', unlockWithPasscode);
                }

                if (passcodeInput) {
                    passcodeInput.addEventListener('keydown', function(event) {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            unlockWithPasscode();
                        }
                    });
                }

                if (relockButton) {
                    relockButton.addEventListener('click', relock);
                }

                if (contactDeveloperButton) {
                    contactDeveloperButton.addEventListener('click', function() {
                        alert(
                            'Contact Developer: Please reach out to your system developer for secure credential updates.');
                    });
                }

                sensitiveFields.forEach((field) => {
                    field.addEventListener('focus', function() {
                        if (!unlocked) {
                            field.blur();
                            showModal();
                        }
                    });
                });

                if (sensitiveForm) {
                    sensitiveForm.addEventListener('submit', function(event) {
                        if (!unlocked) {
                            event.preventDefault();
                            showModal();
                        }
                    });
                }

                updateUiState();

                if (unlocked) {
                    fetchSensitiveData().catch(function() {
                        unlocked = false;
                        updateUiState();
                    });
                }
            })();
        </script>
    @endpush
</x-app-layout>
