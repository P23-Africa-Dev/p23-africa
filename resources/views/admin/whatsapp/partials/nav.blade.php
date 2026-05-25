<div class="card mb-3">
    <div class="card-body py-2">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ orgRoute('admin.whatsapp.overview') }}"
                class="btn btn-sm {{ request()->routeIs('admin.whatsapp.overview', 'admin.whatsapp.overview.index', 'org.admin.whatsapp.overview', 'org.admin.whatsapp.overview.index') ? 'btn-success' : 'btn-outline-success' }}">
                Overview
            </a>
            <a href="{{ orgRoute('admin.whatsapp.conversations') }}"
                class="btn btn-sm {{ request()->routeIs('admin.whatsapp.conversations', 'org.admin.whatsapp.conversations') ? 'btn-success' : 'btn-outline-success' }}">
                Conversations
            </a>
            <a href="{{ orgRoute('admin.whatsapp.templates') }}"
                class="btn btn-sm {{ request()->routeIs('admin.whatsapp.templates', 'admin.whatsapp.templates.*', 'org.admin.whatsapp.templates', 'org.admin.whatsapp.templates.*') ? 'btn-success' : 'btn-outline-success' }}">
                Templates
            </a>
            <a href="{{ orgRoute('admin.whatsapp.contacts') }}"
                class="btn btn-sm {{ request()->routeIs('admin.whatsapp.contacts', 'admin.whatsapp.contacts.*', 'org.admin.whatsapp.contacts', 'org.admin.whatsapp.contacts.*') ? 'btn-success' : 'btn-outline-success' }}">
                Contacts
            </a>
            <a href="{{ orgRoute('admin.whatsapp.events.contacts') }}"
                class="btn btn-sm {{ request()->routeIs('admin.whatsapp.events.contacts', 'org.admin.whatsapp.events.contacts') ? 'btn-success' : 'btn-outline-success' }}">
                Event Contacts
            </a>
            <a href="{{ orgRoute('admin.whatsapp.events.broadcast') }}"
                class="btn btn-sm {{ request()->routeIs('admin.whatsapp.events.broadcast', 'org.admin.whatsapp.events.broadcast') ? 'btn-success' : 'btn-outline-success' }}">
                Event Broadcast
            </a>
            <a href="{{ orgRoute('admin.whatsapp.events.campaign-history') }}"
                class="btn btn-sm {{ request()->routeIs('admin.whatsapp.events.campaign-history', 'org.admin.whatsapp.events.campaign-history') ? 'btn-success' : 'btn-outline-success' }}">
                Campaign History
            </a>
            <a href="{{ orgRoute('admin.whatsapp.settings') }}"
                class="btn btn-sm {{ request()->routeIs('admin.whatsapp.settings', 'admin.whatsapp.settings.*', 'org.admin.whatsapp.settings', 'org.admin.whatsapp.settings.*') ? 'btn-success' : 'btn-outline-success' }}">
                Settings
            </a>
            @if (auth()->user() &&
                    (auth()->user()->hasRole('super-admin') ||
                        auth()->user()->hasRole('developer') ||
                        (auth()->user()->hasRole('admin') && is_null(auth()->user()->organization_id))))
                <a href="{{ orgRoute('admin.whatsapp.developer-security') }}"
                    class="btn btn-sm {{ request()->routeIs('admin.whatsapp.developer-security', 'admin.whatsapp.developer-security.*', 'org.admin.whatsapp.developer-security', 'org.admin.whatsapp.developer-security.*') ? 'btn-success' : 'btn-outline-success' }}">
                    Developer Security
                </a>
            @endif
            <a href="{{ orgRoute('admin.whatsapp.account-setup') }}"
                class="btn btn-sm {{ request()->routeIs('admin.whatsapp.account-setup', 'org.admin.whatsapp.account-setup') ? 'btn-success' : 'btn-outline-success' }}">
                Account Setup
            </a>
            <a href="{{ orgRoute('admin.whatsapp.webhook-logs') }}"
                class="btn btn-sm {{ request()->routeIs('admin.whatsapp.webhook-logs', 'org.admin.whatsapp.webhook-logs') ? 'btn-success' : 'btn-outline-success' }}">
                Webhook Logs
            </a>
        </div>
    </div>
</div>
