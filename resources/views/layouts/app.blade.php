<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/Logo2.png') }}" type="image/x-icon">
    <title>{{ !empty($currentOrg) ? $currentOrg->name . ' | Admin Dashboard' : 'P23 Africa | Admin Dashboard' }}</title>
    <script>
        (function() {
            var t = localStorage.getItem('theme') || 'light';
            var d = t === 'dark' || (t === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (d) document.documentElement.classList.add('dark');
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/3df60fe6e2.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-ui.css') }}">
    @env('local')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endenv
    @env('production')
        <link rel="stylesheet" href="{{ asset('build/assets/app-B1LvQkRQ.css') }}">
        <script src="{{ asset('build/assets/app-BGjDqbHR.js') }}" defer></script>
    @endenv
    <x-head.tinymce-config />
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head')
</head>

<body class="font-sans antialiased admin-body">
    @include('layouts.navigation')
    @include('layouts.sidebar')

    <div id="main-content" x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false' }" :style="sidebarOpen ? 'margin-left:260px' : 'margin-left:70px'"
        class="main-content-area" @sidebar-toggle.window="sidebarOpen = $event.detail.open">

        @isset($header)
            <header class="page-header">
                <div class="page-header-inner">{{ $header }}</div>
            </header>
        @endisset

        <main class="page-body">{{ $slot }}</main>
    </div>

    @auth
        <div id="notif-fab-container">
            <button id="notif-bell-btn" onclick="NotifSystem.toggle()" aria-label="Notifications">
                <i class="fas fa-bell"></i>
                <span id="notif-badge" style="display:none">0</span>
            </button>
            <div id="notif-panel" role="dialog" aria-label="Notifications panel">
                <div class="notif-panel-header">
                    <span><i class="fas fa-bell me-2"></i>Notifications</span>
                    <div class="d-flex gap-2">
                        <button class="notif-action-btn" onclick="NotifSystem.markAllRead()" title="Mark all read"><i
                                class="fas fa-check-double"></i></button>
                        <button class="notif-action-btn" onclick="NotifSystem.close()"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div id="notif-list" class="notif-list">
                    <div class="notif-loading">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    </div>
                </div>
                <div class="notif-panel-footer"><small>Auto-refreshes every 15 seconds</small></div>
            </div>
        </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    @if (auth()->check() && auth()->user()->hasRole('admin'))
        <script>
            (function() {
                async function pollActivity() {
                    try {
                        const r = await fetch("{{ route('admin.activity.unseen-count') }}", {
                            headers: {
                                "Accept": "application/json",
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        });
                        if (!r.ok) return;
                        const d = await r.json();
                        const b = document.getElementById("activity-unseen-badge");
                        if (b) {
                            if (d.count > 0) {
                                b.textContent = d.count > 99 ? "99+" : d.count;
                                b.style.display = "flex";
                            } else b.style.display = "none";
                        }
                    } catch (e) {}
                }
                pollActivity();
                setInterval(pollActivity, 30000);
            })();
        </script>
    @endif

    @auth
        @php
            $layoutOrgPrefix = '';
            $layoutUser = auth()->user();
            if ($layoutUser && $layoutUser->organization_id && !$layoutUser->hasRole('admin')) {
                $layoutOrg = $layoutUser->organization;
                if ($layoutOrg && $layoutOrg->slug) {
                    $layoutOrgPrefix = '/' . $layoutOrg->slug;
                }
            }
            // Use org-prefixed notification URLs if user has an org
            $notifUnreadCountUrl = $layoutOrgPrefix
                ? url($layoutOrgPrefix . '/admin/notifications/unread-count')
                : route('admin.notifications.unread-count');
            $notifAllUnreadCountUrl = $layoutOrgPrefix
                ? url($layoutOrgPrefix . '/admin/notifications/all-unread-count')
                : route('admin.notifications.all-unread-count');
            $notifAllUrl = $layoutOrgPrefix
                ? url($layoutOrgPrefix . '/admin/notifications/all')
                : route('admin.notifications.all');
            $notifMarkAllReadUrl = $layoutOrgPrefix
                ? url($layoutOrgPrefix . '/admin/notifications/mark-all-read')
                : route('admin.notifications.mark-all-read');
            $notifMarkReadBase = $layoutOrgPrefix
                ? url($layoutOrgPrefix . '/admin/notifications')
                : '/admin/notifications';
        @endphp
        <script>
            (function() {
                let last = 0;
                async function poll() {
                    try {
                        const r = await fetch("{{ $notifUnreadCountUrl }}", {
                            headers: {
                                "Accept": "application/json",
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        });
                        if (!r.ok) return;
                        const d = await r.json();
                        const b = document.getElementById("task-notification-badge");
                        if (b) {
                            if (d.count > 0) {
                                b.textContent = d.count > 99 ? "99+" : d.count;
                                b.style.display = "flex";
                            } else b.style.display = "none";
                        }
                        last = d.count;
                    } catch (e) {}
                }
                poll();
                setInterval(poll, 30000);
            })
            ();
        </script>
    @endauth

    @auth
        <script>
            const NotifSystem = (function() {
                let isOpen = false,
                    lastCount = 0;
                const POLL = 15000;

                function esc(s) {
                    if (!s) return "";
                    const d = document.createElement("div");
                    d.textContent = s;
                    return d.innerHTML;
                }

                function setBadge(n) {
                    const b = document.getElementById("notif-badge");
                    if (!b) return;
                    if (n > 0) {
                        b.textContent = n > 99 ? "99+" : n;
                        b.style.display = "flex";
                    } else b.style.display = "none";
                }
                async function updateCount() {
                    try {
                        const r = await fetch("{{ $notifAllUnreadCountUrl }}", {
                            headers: {
                                "Accept": "application/json",
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        });
                        if (!r.ok) return;
                        const d = await r.json();
                        setBadge(d.count);
                        if (isOpen) loadList();
                        lastCount = d.count;
                    } catch (e) {}
                }
                async function loadList() {
                    const list = document.getElementById("notif-list");
                    if (!list) return;
                    try {
                        const r = await fetch("{{ $notifAllUrl }}", {
                            headers: {
                                "Accept": "application/json",
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        });
                        if (!r.ok) return;
                        const d = await r.json();
                        renderList(d.notifications || []);
                    } catch (e) {
                        list.innerHTML =
                            "<div class='notif-empty'><i class='fas fa-exclamation-circle'></i><p>Failed to load</p></div>";
                    }
                }
                const iconMap = {
                    brn_application: "fas fa-file-alt",
                    consultation: "fas fa-calendar-check",
                    contact_form: "fas fa-envelope",
                    event_feedback: "fas fa-star",
                    playbook_download: "fas fa-download",
                    project_created: "fas fa-project-diagram",
                    task_created: "fas fa-tasks",
                    task_assigned: "fas fa-user-plus",
                    taskassigned: "fas fa-user-plus",
                    task_comment: "fas fa-comment"
                };

                function renderList(items) {
                    const list = document.getElementById("notif-list");
                    if (!list) return;
                    if (!items.length) {
                        list.innerHTML =
                            "<div class='notif-empty'><i class='fas fa-bell-slash fa-2x mb-2 opacity-25'></i><p>No notifications yet</p></div>";
                        return;
                    }
                    list.innerHTML = items.map(n => {
                        const u = !n.read_at;
                        const icon = n.icon || iconMap[n.type] || "fas fa-bell";
                        return "<div class='notif-item" + (u ? " unread" : "") + "' data-id='" + esc(n.id) +
                            "' onclick=\"NotifSystem.clickItem('" + esc(n.id) + "','" + esc(n.url || "") +
                            "')\">" +
                            "<div class='notif-icon-wrap notif-type-" + esc(n.type) + "'><i class='" + esc(
                                icon) + "'></i></div>" +
                            "<div class='notif-content'>" +
                            "<div class='notif-title-row'><span class='notif-title'>" + esc(n.title) +
                            "</span>" + (u ? "<span class='notif-new-dot'></span>" : "") + "</div>" +
                            "<p class='notif-msg'>" + esc(n.message) + "</p>" +
                            "<small class='notif-time'><i class='fas fa-clock me-1'></i>" + esc(n.created_at) +
                            "</small>" +
                            "</div></div>";
                    }).join("");
                }
                async function clickItem(id, url) {
                    try {
                        await fetch("{{ $notifMarkReadBase }}/" + id + "/mark-read", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        });
                    } catch (e) {}
                    const el = document.querySelector(".notif-item[data-id='" + id + "']");
                    if (el) el.classList.remove("unread");
                    setBadge(Math.max(0, lastCount - 1));
                    lastCount = Math.max(0, lastCount - 1);
                    if (url) window.location.href = url;
                }
                async function markAllRead() {
                    try {
                        await fetch("{{ $notifMarkAllReadUrl }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        });
                    } catch (e) {}
                    setBadge(0);
                    lastCount = 0;
                    document.querySelectorAll(".notif-item.unread").forEach(el => el.classList.remove("unread"));
                }

                function toggle() {
                    isOpen ? close() : show();
                }

                function show() {
                    const p = document.getElementById("notif-panel");
                    if (!p) return;
                    isOpen = true;
                    p.classList.add("visible");
                    loadList();
                }

                function close() {
                    const p = document.getElementById("notif-panel");
                    if (!p) return;
                    p.classList.remove("visible");
                    isOpen = false;
                }
                document.addEventListener("click", e => {
                    const f = document.getElementById("notif-fab-container");
                    if (f && !f.contains(e.target)) close();
                });
                updateCount();
                setInterval(updateCount, POLL);
                return {
                    toggle,
                    close,
                    markAllRead,
                    clickItem
                };
            })();
            window.toggleNotificationPanel = () => NotifSystem.toggle();
            window.markAllNotificationsRead = () => NotifSystem.markAllRead();
        </script>
    @endauth

    @stack('scripts')
</body>

</html>
