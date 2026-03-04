{{-- =====================================================================
    Topbar / Navigation - P23 Africa Admin
    Uses admin-ui.css design tokens
    ===================================================================== --}}
<nav class="topbar" x-data="{ userMenu: false }">

    {{-- Hamburger (mobile sidebar toggle) --}}
    <button class="topbar-hamburger" aria-label="Toggle sidebar" @click="$dispatch('sidebar-mobile-toggle')">
        <i class="bi bi-list" style="font-size:1.35rem;"></i>
    </button>

    {{-- Brand logo --}}
    <a href="{{ route('admin.dashboard') }}" class="topbar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="P23 Africa" class="logo-theme-invert"
            style="height:36px;width:auto;">
        {{-- <span class="topbar-brand-name d-none d-md-inline">P23 Africa</span> --}}
    </a>

    <span class="topbar-spacer"></span>

    {{-- Theme toggle pill --}}
    <div class="theme-pill" x-data="themeToggle()">
        <button @click="setTheme('light')" :class="currentTheme === 'light' ? 'active' : ''" title="Light mode"
            aria-label="Light mode">
            <i class="bi bi-sun-fill"></i>
        </button>
        <button @click="setTheme('dark')" :class="currentTheme === 'dark' ? 'active' : ''" title="Dark mode"
            aria-label="Dark mode">
            <i class="bi bi-moon-fill"></i>
        </button>
        <button @click="setTheme('system')" :class="currentTheme === 'system' ? 'active' : ''" title="System"
            aria-label="System theme">
            <i class="bi bi-circle-half"></i>
        </button>
    </div>

    {{-- Notification shortcut (desktop) --}}
    @auth
        <button class="topbar-btn d-none d-sm-flex position-relative" onclick="NotifSystem.toggle()"
            aria-label="Notifications" title="Notifications">
            <i class="bi bi-bell" style="font-size:1.1rem;"></i>
            <span id="nav-notif-dot"
                style="position:absolute;top:6px;right:6px;width:8px;height:8px;
                     border-radius:50%;background:var(--p-danger);display:none;
                     border:2px solid var(--bg-primary);"></span>
        </button>
    @endauth

    {{-- User menu --}}
    @auth
        <div style="position:relative;" @click.away="userMenu=false">
            <button @click="userMenu=!userMenu"
                style="display:flex;align-items:center;gap:.5rem;background:transparent;border:none;cursor:pointer;padding:.25rem .375rem;border-radius:var(--radius-sm);"
                aria-label="User menu">
                <div class="topbar-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span class="topbar-user-name">{{ Auth::user()->name }}</span>
                <i class="bi bi-chevron-down"
                    style="font-size:.65rem;color:var(--text-muted);margin-left:2px;transition:transform .2s;"
                    :style="userMenu ? 'transform:rotate(180deg)' : ''"></i>
            </button>

            <div x-show="userMenu" x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                style="position:absolute;right:0;top:calc(100% + 6px);min-width:210px;
                    background:var(--bg-primary);border:1px solid var(--border);
                    border-radius:var(--radius-md);box-shadow:var(--shadow-md);
                    padding:.375rem;z-index:200;">

                <div style="padding:.625rem .75rem .5rem;border-bottom:1px solid var(--border);margin-bottom:.25rem;">
                    <div style="font-size:.8125rem;font-weight:600;color:var(--text-primary);">{{ Auth::user()->name }}
                    </div>
                    <div style="font-size:.7rem;color:var(--text-muted);">{{ Auth::user()->email }}</div>
                    <div style="margin-top:.25rem;">
                        @if (Auth::user()->hasRole('admin'))
                            <span
                                style="font-size:.65rem;font-weight:600;padding:2px 8px;border-radius:999px;background:rgba(99,102,241,.15);color:var(--p-primary);">Administrator</span>
                        @elseif(Auth::user()->hasRole('supervisor'))
                            <span
                                style="font-size:.65rem;font-weight:600;padding:2px 8px;border-radius:999px;background:rgba(6,182,212,.15);color:var(--p-accent);">Supervisor</span>
                        @else
                            <span
                                style="font-size:.65rem;font-weight:600;padding:2px 8px;border-radius:999px;background:rgba(16,185,129,.15);color:var(--p-success);">Staff</span>
                        @endif
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}"
                    style="display:flex;align-items:center;gap:.625rem;padding:.5rem .75rem;border-radius:var(--radius-sm);color:var(--text-primary);font-size:.875rem;text-decoration:none;"
                    onmouseenter="this.style.background='var(--bg-hover)'"
                    onmouseleave="this.style.background='transparent'">
                    <i class="bi bi-person-circle" style="color:var(--text-muted);"></i>
                    Profile &amp; Settings
                </a>

                @if (Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.activity.logs') }}"
                        style="display:flex;align-items:center;gap:.625rem;padding:.5rem .75rem;border-radius:var(--radius-sm);color:var(--text-primary);font-size:.875rem;text-decoration:none;"
                        onmouseenter="this.style.background='var(--bg-hover)'"
                        onmouseleave="this.style.background='transparent'">
                        <i class="bi bi-activity" style="color:var(--text-muted);"></i>
                        Activity Logs
                    </a>
                @endif

                <div style="height:1px;background:var(--border);margin:.25rem 0;"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        style="display:flex;align-items:center;gap:.625rem;padding:.5rem .75rem;border-radius:var(--radius-sm);color:var(--p-danger);font-size:.875rem;background:transparent;border:none;width:100%;cursor:pointer;text-align:left;"
                        onmouseenter="this.style.background='rgba(239,68,68,.08)'"
                        onmouseleave="this.style.background='transparent'">
                        <i class="bi bi-box-arrow-right"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    @endauth

</nav>

<script>
    function themeToggle() {
        return {
            currentTheme: localStorage.getItem('theme') || 'system',
            init() {
                this.applyTheme();
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                    if (this.currentTheme === 'system') this.applyTheme();
                });
                // Sync nav notification dot with FAB badge
                const syncDot = () => {
                    const badge = document.getElementById('notif-badge');
                    const dot = document.getElementById('nav-notif-dot');
                    if (badge && dot)
                        dot.style.display = (badge.style.display !== 'none' && badge.textContent.trim()) ? 'block' :
                        'none';
                };
                const badge = document.getElementById('notif-badge');
                if (badge) new MutationObserver(syncDot).observe(badge, {
                    attributes: true,
                    childList: true,
                    characterData: true
                });
            },
            setTheme(t) {
                this.currentTheme = t;
                localStorage.setItem('theme', t);
                this.applyTheme();
                window.dispatchEvent(new CustomEvent('theme-changed', {
                    detail: {
                        theme: t
                    }
                }));
            },
            applyTheme() {
                const dark = this.currentTheme === 'dark' ||
                    (this.currentTheme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', dark);
                document.body.classList.toggle('dark', dark);
            }
        };
    }
</script>
