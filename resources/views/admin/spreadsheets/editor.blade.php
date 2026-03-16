<x-app-layout>
    @push('head')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/plugins/css/pluginsCss.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/plugins/plugins.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/css/luckysheet.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/assets/iconfont/iconfont.css" />
        <script>
            document.addEventListener('DOMContentLoaded', () => document.body.classList.add('ss-editor-page'));
        </script>
        <style>
            /* ── Layout: give the editor its own full-height page so page-body padding
                                              doesn't cause infinite scroll ─────────────────────────────────────── */
            body.ss-editor-page .page-body {
                padding: 0 !important;
                max-width: 100% !important;
            }

            /* ── Wrapper that fills the remaining viewport below header + toolbar ── */
            #ss-wrapper {
                display: flex;
                flex-direction: column;
                /* Fill from top of page-body to bottom of viewport */
                height: calc(100vh - var(--topbar-h, 64px) - var(--page-header-h, 65px) - var(--toolbar-h, 46px));
                min-height: 400px;
                position: relative;
            }

            /* ── Luckysheet must fill its parent exactly ─────────────────────────── */
            #luckysheet-container {
                flex: 1 1 0;
                min-height: 0;
                /* flex child must not overflow */
                width: 100%;
                position: relative;
                /* Luckysheet positions children relative to this */
            }

            /* ── Fullscreen: overlay the entire viewport, covering sidebar + topbar ─ */
            #ss-fullscreen-wrap {
                position: fixed;
                inset: 0;
                z-index: 9000;
                display: none;
                /* shown via JS */
                flex-direction: column;
                background: var(--bg-page, #fff);
            }

            #ss-fullscreen-wrap.active {
                display: flex;
            }

            #ss-fullscreen-toolbar {
                display: flex;
                align-items: center;
                gap: .5rem;
                padding: .5rem .75rem;
                background: var(--bg-primary, #fff);
                border-bottom: 1px solid var(--border, #e2e8f0);
                flex-shrink: 0;
            }

            #luckysheet-fs-container {
                flex: 1 1 0;
                min-height: 0;
                width: 100%;
                position: relative;
            }

            /* ── Toolbar ─────────────────────────────────────────────────────────── */
            #ss-toolbar {
                display: flex;
                align-items: center;
                gap: .5rem;
                flex-wrap: wrap;
                padding: .5rem 1rem;
                background: var(--bg-primary, #fff);
                border-bottom: 1px solid var(--border, #e2e8f0);
                flex-shrink: 0;
            }

            #ss-toolbar .btn {
                font-size: .8rem;
                padding: .3rem .65rem;
            }

            /* ── Passcode overlay ────────────────────────────────────────────────── */
            #passcodeOverlay {
                position: absolute;
                inset: 0;
                background: rgba(255, 255, 255, .93);
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
                z-index: 200;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            :root[data-bs-theme="dark"] #passcodeOverlay,
            .dark #passcodeOverlay {
                background: rgba(15, 23, 42, .93);
            }

            .passcode-box {
                text-align: center;
                max-width: 340px;
                width: 100%;
                padding: 2rem 1.5rem;
                border-radius: 12px;
                background: var(--bg-primary, #fff);
                box-shadow: 0 20px 60px rgba(0, 0, 0, .15);
            }

            .passcode-box input {
                text-align: center;
                font-size: 1.25rem;
                letter-spacing: 4px;
                font-family: monospace;
            }

            /* ── Save indicator ──────────────────────────────────────────────────── */
            #saveIndicator {
                font-size: .75rem;
                transition: opacity .4s;
                white-space: nowrap;
            }
        </style>
    @endpush

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div style="min-width:0;">
                <h2 class="font-semibold mb-0"
                    style="font-size:1.05rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    <i class="bi bi-file-earmark-spreadsheet me-1"
                        style="color:var(--p-success);"></i>{{ $spreadsheet->name }}
                </h2>
                @if ($spreadsheet->description)
                    <p class="mb-0" style="font-size:.72rem;color:var(--text-muted);">{{ $spreadsheet->description }}
                    </p>
                @endif
            </div>
            <div class="d-flex align-items-center gap-2">
                <span id="saveIndicator" style="opacity:0;color:var(--p-success);font-size:.75rem;">
                    <i class="bi bi-check-circle-fill"></i> Saved
                </span>
                <a href="{{ orgRoute('admin.spreadsheets.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </x-slot>

    {{-- ── Main inline toolbar + editor (normal mode) ─────────────────── --}}
    <div id="ss-wrapper">

        {{-- Toolbar --}}
        <div id="ss-toolbar">
            <button onclick="saveSheet('main')" class="btn btn-success btn-sm" id="btnSave">
                <i class="bi bi-floppy me-1"></i> Save
            </button>
            <a href="{{ orgRoute('admin.spreadsheets.export', ['spreadsheet' => $spreadsheet->id]) }}"
                class="btn btn-outline-primary btn-sm">
                <i class="bi bi-download me-1"></i> Export XLSX
            </a>
            <button onclick="ssEnterFullscreen()" class="btn btn-outline-secondary btn-sm" title="Fullscreen">
                <i class="bi bi-fullscreen"></i>
            </button>

            @if ($isSupervisor)
                <div class="ms-auto d-flex align-items-center gap-2">
                    <button onclick="showPasscodeManager()" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-key me-1"></i> Passcode
                    </button>
                    <form action="{{ orgRoute('admin.spreadsheets.destroy', ['spreadsheet' => $spreadsheet->id]) }}"
                        method="POST" onsubmit="return confirm('Delete this spreadsheet permanently?')" class="mb-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Luckysheet main container --}}
        <div id="luckysheet-container" style="position:relative;">
            {{-- Passcode overlay (staff only) --}}
            @if ($needsPasscode)
                <div id="passcodeOverlay">
                    <div class="passcode-box">
                        <div
                            style="width:52px;height:52px;border-radius:50%;background:rgba(99,102,241,.12);
                                color:var(--p-primary);display:inline-flex;align-items:center;
                                justify-content:center;margin-bottom:1rem;">
                            <i class="bi bi-lock" style="font-size:1.4rem;"></i>
                        </div>
                        <h5 style="font-size:.9375rem;font-weight:600;margin-bottom:.25rem;">Editing Locked</h5>
                        <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:1rem;">
                            Enter the edit passcode to make changes, or request it from a supervisor.
                        </p>
                        <div class="mb-2">
                            <input type="text" id="passcodeInput" class="form-control form-control-sm" maxlength="10"
                                placeholder="Enter passcode" autocomplete="off">
                        </div>
                        <div id="passcodeError" class="text-danger mb-2" style="font-size:.8rem;display:none;"></div>
                        <div class="d-flex gap-2 mb-2">
                            <button onclick="verifyPasscode()" class="btn btn-primary btn-sm flex-fill" id="btnVerify">
                                <i class="bi bi-unlock me-1"></i> Unlock
                            </button>
                            <button onclick="requestPasscode()" class="btn btn-outline-secondary btn-sm flex-fill"
                                id="btnRequest">
                                <i class="bi bi-bell me-1"></i> Request
                            </button>
                        </div>
                        <button onclick="viewReadOnly()" class="btn btn-link btn-sm p-0" style="font-size:.75rem;">
                            View read-only (no editing)
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Fullscreen overlay (separate DOM, separate Luckysheet instance) ── --}}
    <div id="ss-fullscreen-wrap">
        <div id="ss-fullscreen-toolbar">
            <span style="font-size:.875rem;font-weight:600;flex:1;">
                <i class="bi bi-file-earmark-spreadsheet me-1" style="color:var(--p-success);"></i>
                {{ $spreadsheet->name }}
            </span>
            <span id="saveIndicatorFs"
                style="opacity:0;color:var(--p-success);font-size:.75rem;transition:opacity .4s;">
                <i class="bi bi-check-circle-fill"></i> Saved
            </span>
            <button onclick="saveSheet('fs')" class="btn btn-success btn-sm">
                <i class="bi bi-floppy me-1"></i> Save
            </button>
            <a href="{{ orgRoute('admin.spreadsheets.export', ['spreadsheet' => $spreadsheet->id]) }}"
                class="btn btn-outline-primary btn-sm">
                <i class="bi bi-download me-1"></i> Export XLSX
            </a>
            <button onclick="ssExitFullscreen()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-fullscreen-exit me-1"></i> Exit Fullscreen
            </button>
        </div>
        <div id="luckysheet-fs-container"></div>
    </div>

    {{-- ── Passcode Manager Modal (supervisor) ──────────────────────────── --}}
    @if ($isSupervisor)
        <div class="modal fade" id="passcodeManagerModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h5 class="modal-title" style="font-size:.9375rem;">
                            <i class="bi bi-key me-1"></i> Manage Edit Passcode
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div id="mgrPasscodeInfo" class="mb-3" style="font-size:.8125rem;color:var(--text-muted);">
                            <div class="spinner-border spinner-border-sm"></div>
                        </div>
                        <div id="mgrPasscodeDisplay" class="d-none mb-3">
                            <div class="p-3 rounded"
                                style="background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.25);">
                                <div style="font-size:.7rem;color:var(--text-muted);margin-bottom:.25rem;">CURRENT
                                    PASSCODE</div>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <span id="mgrPasscodeValue"
                                        style="font-family:monospace;font-size:1.5rem;font-weight:700;letter-spacing:4px;color:var(--p-success);"></span>
                                    <button onclick="copyPasscode()" class="btn btn-sm btn-outline-success p-1 lh-1"
                                        title="Copy">
                                        <i class="bi bi-clipboard" style="font-size:.8rem;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button onclick="genPasscodeFromEditor()" class="btn btn-primary btn-sm w-100 mb-2">
                            <i class="bi bi-arrow-repeat me-1"></i> Generate New Passcode
                        </button>
                        <p class="mb-0" style="font-size:.7rem;color:var(--text-muted);">
                            Share this passcode with staff who need to edit spreadsheets.<br>
                            Supervisors &amp; admins never need a passcode.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        {{-- Load jQuery explicitly BEFORE plugin.js.
             plugin.js bundles jQuery 2.2.4 + many jQuery plugins and normally sets window.$/window.jQuery.
             But in some environments its UMD wrapper doesn't set the globals.
             Pre-loading jQuery guarantees a working $ is available no matter what.
             plugin.js will then overwrite window.$ with its own 2.2.4 + plugins (which is fine).
             We save a _jQ backup AFTER plugin.js so the backup includes all the plugins. --}}
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/plugins/js/plugin.js"></script>
        <script>
            /* Save a backup of whichever jQuery is now on window.$ (should be plugin.js's 2.2.4).
                       If plugin.js failed to set globals, window.$ is still 3.7.1 from the CDN above. */
            window._jQ = window.jQuery || window.$;
        </script>
        <script src="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/luckysheet.umd.js"></script>
        <script>
            /* Final safety net: ensure window.$ is a working jQuery before the app script runs */
            if (typeof window.$ !== 'function' || typeof window.$.fn === 'undefined') {
                window.$ = window._jQ;
                window.jQuery = window._jQ;
            }
        </script>
        <script>
            (function() {
                /* ── Constants ──────────────────────────────────────────────── */
                const DATA_URL = '{{ orgRoute('admin.spreadsheets.data', ['spreadsheet' => $spreadsheet->id]) }}';
                const SAVE_URL = '{{ orgRoute('admin.spreadsheets.save', ['spreadsheet' => $spreadsheet->id]) }}';
                const VERIFY_URL =
                    '{{ orgRoute('admin.spreadsheets.verify-passcode', ['spreadsheet' => $spreadsheet->id]) }}';
                const REQUEST_URL =
                    '{{ orgRoute('admin.spreadsheets.request-passcode', ['spreadsheet' => $spreadsheet->id]) }}';
                const GEN_URL = '{{ orgRoute('admin.spreadsheets.generate-passcode') }}';
                const INFO_URL = '{{ orgRoute('admin.spreadsheets.passcode-info') }}';
                const CSRF = document.querySelector('meta[name=csrf-token]').content;
                const IS_STAFF = {{ $needsPasscode ? 'true' : 'false' }};

                let editUnlocked = !IS_STAFF;
                let sheetsData = []; // cached after first fetch
                let fsActive = false;

                /* ── Helpers ────────────────────────────────────────────────── */

                /** Ensure window.$ is jQuery before Luckysheet uses it */
                function ensureJQuery() {
                    if (typeof window.$ !== 'function' || typeof window.$.fn === 'undefined' || typeof window.$.fn
                        .jquery === 'undefined') {
                        if (window._jQ) {
                            window.$ = window._jQ;
                            window.jQuery = window._jQ;
                            console.warn('[SS] Restored window.$ from _jQ backup');
                        } else {
                            console.error('[SS] jQuery is NOT available. window.$ =', typeof window.$, window.$);
                        }
                    }
                }

                function post(url, body) {
                    return fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        },
                        body: JSON.stringify(body)
                    }).then(r => r.json().then(data => {
                        // Surface the parsed body even on error responses so callers can inspect it
                        if (!r.ok) {
                            return Promise.reject(data);
                        }
                        return data;
                    }));
                }

                /* ── Compute correct height for the luckysheet container ────── */
                function computeHeight() {
                    const topbar = 64; // --topbar-h
                    const header = document.querySelector('.page-header')?.offsetHeight || 65;
                    const toolbar = document.getElementById('ss-toolbar')?.offsetHeight || 46;
                    const winH = window.innerHeight;
                    return Math.max(winH - topbar - header - toolbar, 300);
                }

                function applyHeight() {
                    const h = computeHeight();
                    const wrapper = document.getElementById('ss-wrapper');
                    const cont = document.getElementById('luckysheet-container');
                    if (wrapper) wrapper.style.height = h + 'px';
                    if (cont) cont.style.height = h + 'px';
                }

                /* ── Init main Luckysheet ───────────────────────────────────── */
                function initMain() {
                    applyHeight();
                    fetch(DATA_URL, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': CSRF
                            }
                        })
                        .then(r => r.json().then(data => {
                            if (!r.ok) return Promise.reject(data);
                            return data;
                        }))
                        .then(res => {
                            sheetsData = (res.data && res.data.length) ? res.data : [{
                                name: 'Sheet1',
                                celldata: [],
                                index: 0,
                                status: 1
                            }];

                            ensureJQuery();
                            luckysheet.create({
                                container: 'luckysheet-container',
                                data: sheetsData,
                                showinfobar: false,
                                showsheetbar: true,
                                showstatisticBar: true,
                                enableAddRow: true,
                                enableAddBackTop: false,
                                allowEdit: editUnlocked,
                                lang: 'en',
                                forceCalculation: true,
                            });
                        })
                        .catch(err => {
                            const msg = (err && (err.error || err.message)) ?
                                (err.error || err.message) :
                                'Could not reach the server. Check your connection.';
                            document.getElementById('luckysheet-container').innerHTML =
                                '<div class="d-flex flex-column align-items-center justify-content-center h-100 gap-2 p-4" style="color:var(--p-danger,#dc3545);">' +
                                '<i class="bi bi-exclamation-triangle fs-3"></i>' +
                                '<strong style="font-size:.9rem;">Failed to load spreadsheet data</strong>' +
                                '<p class="text-muted mb-1" style="font-size:.8rem;max-width:400px;text-align:center;">' +
                                msg + '</p>' +
                                '<button class="btn btn-sm btn-outline-danger" onclick="initMain()">' +
                                '<i class="bi bi-arrow-clockwise me-1"></i>Retry</button>' +
                                '</div>';
                            console.error('Spreadsheet load error:', err);
                        });
                }

                /* ── Fullscreen ─────────────────────────────────────────────── */
                window.ssEnterFullscreen = function() {
                    fsActive = true;
                    const wrap = document.getElementById('ss-fullscreen-wrap');
                    wrap.classList.add('active');

                    // snapshot current sheets from main instance
                    try {
                        sheetsData = luckysheet.getAllSheets();
                    } catch (e) {}

                    const h = window.innerHeight - (document.getElementById('ss-fullscreen-toolbar')?.offsetHeight ||
                        46);
                    const fsCont = document.getElementById('luckysheet-fs-container');
                    fsCont.style.height = h + 'px';

                    // Luckysheet can only have one instance — destroy main, init in fullscreen
                    ensureJQuery();
                    luckysheet.destroy();
                    luckysheet.create({
                        container: 'luckysheet-fs-container',
                        data: sheetsData,
                        showinfobar: false,
                        showsheetbar: true,
                        showstatisticBar: true,
                        enableAddRow: true,
                        enableAddBackTop: false,
                        allowEdit: editUnlocked,
                        lang: 'en',
                        forceCalculation: true,
                    });
                };

                window.ssExitFullscreen = function() {
                    fsActive = false;
                    // snapshot from fullscreen before destroying
                    try {
                        sheetsData = luckysheet.getAllSheets();
                    } catch (e) {}

                    ensureJQuery();
                    luckysheet.destroy();
                    document.getElementById('ss-fullscreen-wrap').classList.remove('active');

                    // Re-init main instance with latest data
                    applyHeight();
                    luckysheet.create({
                        container: 'luckysheet-container',
                        data: sheetsData,
                        showinfobar: false,
                        showsheetbar: true,
                        showstatisticBar: true,
                        enableAddRow: true,
                        enableAddBackTop: false,
                        allowEdit: editUnlocked,
                        lang: 'en',
                        forceCalculation: true,
                    });
                };

                /* ── Save ───────────────────────────────────────────────────── */
                window.saveSheet = function(mode) {
                    if (!editUnlocked) {
                        alert('Please enter the passcode first to enable editing.');
                        return;
                    }
                    const btnId = mode === 'fs' ? null : 'btnSave';
                    const indId = mode === 'fs' ? 'saveIndicatorFs' : 'saveIndicator';
                    const btn = btnId ? document.getElementById(btnId) : null;

                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';
                    }

                    let data;
                    try {
                        data = luckysheet.getAllSheets();
                    } catch (e) {
                        data = sheetsData;
                    }

                    post(SAVE_URL, {
                            sheet_data: JSON.stringify(data)
                        })
                        .then(res => {
                            if (btn) {
                                btn.disabled = false;
                                btn.innerHTML = '<i class="bi bi-floppy me-1"></i> Save';
                            }
                            if (res.success) {
                                sheetsData = data;
                                const ind = document.getElementById(indId);
                                if (ind) {
                                    ind.style.opacity = '1';
                                    setTimeout(() => ind.style.opacity = '0', 3000);
                                }
                            } else {
                                alert(res.error || 'Save failed.');
                            }
                        })
                        .catch(err => {
                            if (btn) {
                                btn.disabled = false;
                                btn.innerHTML = '<i class="bi bi-floppy me-1"></i> Save';
                            }
                            const msg = (err && (err.error || err.message)) ? (err.error || err.message) :
                                'Network error. Please try again.';
                            alert('Save failed: ' + msg);
                        });
                };

                /* Ctrl+S */
                document.addEventListener('keydown', e => {
                    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                        e.preventDefault();
                        saveSheet(fsActive ? 'fs' : 'main');
                    }
                    if (e.key === 'Escape' && fsActive) ssExitFullscreen();
                });

                /* ── Resize handler ─────────────────────────────────────────── */
                window.addEventListener('resize', () => {
                    if (!fsActive) {
                        applyHeight();
                        try {
                            luckysheet.resize();
                        } catch (e) {}
                    } else {
                        const h = window.innerHeight - (document.getElementById('ss-fullscreen-toolbar')
                            ?.offsetHeight || 46);
                        document.getElementById('luckysheet-fs-container').style.height = h + 'px';
                        try {
                            luckysheet.resize();
                        } catch (e) {}
                    }
                });

                /* ── Passcode (staff) ───────────────────────────────────────── */
                window.verifyPasscode = function() {
                    const code = (document.getElementById('passcodeInput')?.value || '').trim();
                    if (!code) return;
                    const btn = document.getElementById('btnVerify');
                    btn.disabled = true;
                    post(VERIFY_URL, {
                            passcode: code
                        })
                        .then(res => {
                            btn.disabled = false;
                            if (res.success) {
                                document.getElementById('passcodeOverlay').remove();
                                editUnlocked = true;
                                try {
                                    luckysheet.setConfig({
                                        allowEdit: true
                                    });
                                } catch (e) {}
                            } else {
                                const err = document.getElementById('passcodeError');
                                err.textContent = res.error || 'Invalid passcode.';
                                err.style.display = 'block';
                            }
                        })
                        .catch(() => {
                            btn.disabled = false;
                            alert('Network error.');
                        });
                };

                window.requestPasscode = function() {
                    const btn = document.getElementById('btnRequest');
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sending…';
                    post(REQUEST_URL, {})
                        .then(res => {
                            btn.disabled = false;
                            if (res.success) {
                                btn.innerHTML = '<i class="bi bi-check me-1"></i>Sent!';
                                btn.className = btn.className.replace('btn-outline-secondary',
                                    'btn-outline-success');
                            } else {
                                btn.innerHTML = '<i class="bi bi-bell me-1"></i> Request';
                                alert(res.error || 'Could not send request.');
                            }
                        })
                        .catch(() => {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="bi bi-bell me-1"></i> Request';
                        });
                };

                window.viewReadOnly = function() {
                    document.getElementById('passcodeOverlay').remove();
                };

                /* Enter key on passcode input */
                document.addEventListener('DOMContentLoaded', () => {
                    const inp = document.getElementById('passcodeInput');
                    if (inp) inp.addEventListener('keydown', e => {
                        if (e.key === 'Enter') verifyPasscode();
                    });
                });

                /* ── Passcode manager (supervisor) ──────────────────────────── */
                window.showPasscodeManager = function() {
                    const el = document.getElementById('passcodeManagerModal');
                    if (!el) return;
                    const modal = new bootstrap.Modal(el);
                    modal.show();
                    document.getElementById('mgrPasscodeDisplay').classList.add('d-none');
                    document.getElementById('mgrPasscodeInfo').innerHTML =
                        '<div class="spinner-border spinner-border-sm"></div>';
                    fetch(INFO_URL, {
                            headers: {
                                Accept: 'application/json',
                                'X-CSRF-TOKEN': CSRF
                            }
                        })
                        .then(r => r.json())
                        .then(d => {
                            document.getElementById('mgrPasscodeInfo').innerHTML = d.exists ?
                                'Created by <strong>' + d.created_by +
                                '</strong><br><span style="font-size:.7rem;">Last regenerated ' + (d
                                    .regenerated_at || 'never') + '</span>' :
                                '<span style="color:var(--p-warning);">No passcode set yet.</span>';
                        });
                };

                window.genPasscodeFromEditor = function() {
                    post(GEN_URL, {}).then(d => {
                        if (d.success) {
                            document.getElementById('mgrPasscodeDisplay').classList.remove('d-none');
                            document.getElementById('mgrPasscodeValue').textContent = d.passcode;
                            document.getElementById('mgrPasscodeInfo').innerHTML =
                                '<span style="color:var(--p-success);"><i class="bi bi-check-circle me-1"></i>Generated!</span>';
                        }
                    });
                };

                window.copyPasscode = function() {
                    const v = document.getElementById('mgrPasscodeValue')?.textContent || '';
                    navigator.clipboard.writeText(v).then(() => {
                        const btn = event.currentTarget;
                        btn.innerHTML = '<i class="bi bi-check" style="font-size:.8rem;"></i>';
                        setTimeout(() => btn.innerHTML =
                            '<i class="bi bi-clipboard" style="font-size:.8rem;"></i>', 2000);
                    });
                };

                /* ── Boot ───────────────────────────────────────────────────── */
                window.initMain = initMain;
                document.addEventListener('DOMContentLoaded', initMain);
            })();
        </script>
    @endpush
</x-app-layout>
