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
            <a href="{{ orgRoute('admin.whatsapp.settings') }}"
                class="btn btn-sm {{ request()->routeIs('admin.whatsapp.settings', 'admin.whatsapp.settings.*', 'org.admin.whatsapp.settings', 'org.admin.whatsapp.settings.*') ? 'btn-success' : 'btn-outline-success' }}">
                Settings
            </a>
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
