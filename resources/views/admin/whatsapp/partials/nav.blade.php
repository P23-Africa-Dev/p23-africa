@php
    $waAccountService = app(\App\Services\WhatsApp\WhatsAppAccountService::class);
    $waActiveAccount =
        $waActiveAccount ?? ($activeAccount ?? $waAccountService->getActiveAccountForUser(auth()->user()));
    $waActiveAccounts = $waActiveAccounts ?? $waAccountService->allActiveAccounts();
@endphp

<div class="card mb-3">
    <div class="card-body py-2 pb-5">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-start">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ orgRoute('admin.whatsapp.overview') }}"
                    class="btn btn-sm {{ request()->routeIs('admin.whatsapp.overview', 'admin.whatsapp.overview.index') ? 'btn-success' : 'btn-outline-success' }}">
                    Overview
                </a>
                <a href="{{ orgRoute('admin.whatsapp.conversations') }}"
                    class="btn btn-sm {{ request()->routeIs('admin.whatsapp.conversations') ? 'btn-success' : 'btn-outline-success' }}">
                    Conversations
                </a>
                <a href="{{ orgRoute('admin.whatsapp.templates') }}"
                    class="btn btn-sm {{ request()->routeIs('admin.whatsapp.templates', 'admin.whatsapp.templates.*') ? 'btn-success' : 'btn-outline-success' }}">
                    Templates
                </a>
                <a href="{{ orgRoute('admin.whatsapp.contacts') }}"
                    class="btn btn-sm {{ request()->routeIs('admin.whatsapp.contacts', 'admin.whatsapp.contacts.*') ? 'btn-success' : 'btn-outline-success' }}">
                    Contacts
                </a>
                <a href="{{ orgRoute('admin.whatsapp.events.contacts') }}"
                    class="btn btn-sm {{ request()->routeIs('admin.whatsapp.events.contacts') ? 'btn-success' : 'btn-outline-success' }}">
                    Event Contacts
                </a>
                <a href="{{ orgRoute('admin.whatsapp.events.broadcast') }}"
                    class="btn btn-sm {{ request()->routeIs('admin.whatsapp.events.broadcast') ? 'btn-success' : 'btn-outline-success' }}">
                    Event Broadcast
                </a>
                <a href="{{ orgRoute('admin.whatsapp.events.campaign-history') }}"
                    class="btn btn-sm {{ request()->routeIs('admin.whatsapp.events.campaign-history') ? 'btn-success' : 'btn-outline-success' }}">
                    Campaign History
                </a>
                <a href="{{ orgRoute('admin.whatsapp.settings') }}"
                    class="btn btn-sm {{ request()->routeIs('admin.whatsapp.settings', 'admin.whatsapp.settings.*') ? 'btn-success' : 'btn-outline-success' }}">
                    Settings
                </a>
                @if (auth()->user() &&
                        (auth()->user()->hasRole('super-admin') ||
                            auth()->user()->hasRole('developer') ||
                            (auth()->user()->hasRole('admin') && is_null(auth()->user()->organization_id))))
                    <a href="{{ orgRoute('admin.whatsapp.developer-security') }}"
                        class="btn btn-sm {{ request()->routeIs('admin.whatsapp.developer-security', 'admin.whatsapp.developer-security.*') ? 'btn-success' : 'btn-outline-success' }}">
                        Developer Security
                    </a>
                @endif
                <a href="{{ orgRoute('admin.whatsapp.account-setup') }}"
                    class="btn btn-sm {{ request()->routeIs('admin.whatsapp.account-setup') ? 'btn-success' : 'btn-outline-success' }}">
                    Account Setup
                </a>
                <a href="{{ orgRoute('admin.whatsapp.webhook-logs') }}"
                    class="btn btn-sm {{ request()->routeIs('admin.whatsapp.webhook-logs') ? 'btn-success' : 'btn-outline-success' }}">
                    Webhook Logs
                </a>
            </div>

            <div class="dropdown position-static" style="">
                <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" id="waAccountDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                    {{ $waActiveAccount?->phone_number ?: 'No active sender' }}
                    @if ($waActiveAccount?->business_name)
                        / {{ $waActiveAccount->business_name }}
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="waAccountDropdown" style="">
                    @forelse ($waActiveAccounts as $account)
                        <li>
                            <form method="POST" action="{{ orgRoute('admin.whatsapp.accounts.switch') }}"
                                style="min-width: 220px">
                                @csrf
                                <input type="hidden" name="account_id" value="{{ $account->id }}">
                                <button type="submit"
                                    class="dropdown-item d-flex justify-content-between align-items-center"
                                    tabindex="0" style="user-select: none">
                                    <span>{{ $account->phone_number }} / {{ $account->business_name }}</span>
                                    @if ((int) ($waActiveAccount?->id ?? 0) === (int) $account->id)
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </button>
                            </form>
                        </li>
                    @empty
                        <li><span class="dropdown-item-text text-muted">No active accounts</span></li>
                    @endforelse
                </ul>
                <style>
                    /* Prevent dropdown from closing or losing focus unexpectedly */
                    .dropdown-menu[aria-labelledby="waAccountDropdown"] form {
                        margin-bottom: 0;
                    }

                    /* Prevent hover over dropdown-menu from disturbing trigger button stacking context */
                    .dropdown-menu[aria-labelledby="waAccountDropdown"] {
                        position: absolute;
                        top: 100%;
                        right: 0;
                        left: auto;
                        min-width: 220px;
                        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .1);
                    }

                    .dropdown-menu[aria-labelledby="waAccountDropdown"] button:focus {
                        outline: none;
                        background: #f8f9fa;
                    }
                </style>
                <script>
                    // Extra insurance against flicker: keep dropdown open on click or inside interact
                    document.addEventListener('DOMContentLoaded', function() {
                        var dropdown = document.getElementById('waAccountDropdown');
                        var menu = dropdown?.nextElementSibling;
                        if (dropdown && menu) {
                            menu.addEventListener('mouseenter', function() {
                                dropdown.setAttribute('aria-expande', 'true');
                                menu.classList.add('show');
                            });
                            menu.addEventListener('mouseleave', function() {
                                // Keep menu open until user clicks outside or selects
                            });
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</div>
