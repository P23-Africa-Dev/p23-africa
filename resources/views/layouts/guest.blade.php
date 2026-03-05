<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'P23 Africa') }} | Sign In</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --brand-dark: #1d6154;
            --brand-mid: #22c58b;
            --brand-light: #a7f3d0;
            --brand-glow: rgba(34, 197, 139, .22);
            --text-on-dark: rgba(255, 255, 255, .88);
            --text-muted-d: rgba(255, 255, 255, .55);
            --radius: 14px;
            --input-h: 48px;
        }

        html,
        body {
            height: 100%;
            font-family: 'Inter', sans-serif;
        }

        /* ── Layout ──────────────────────────────────── */
        .auth-shell {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ── LEFT PANEL ──────────────────────────────── */
        .auth-panel {
            position: relative;
            background: linear-gradient(150deg, #0a3d32 0%, #1d6154 45%, #22c58b 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            overflow: hidden;
        }

        /* floating blobs */
        .auth-panel::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(34, 197, 139, .25) 0%, transparent 70%);
            top: -120px;
            right: -120px;
            border-radius: 50%;
            animation: blobDrift 12s ease-in-out infinite alternate;
        }

        .auth-panel::after {
            content: '';
            position: absolute;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(255, 255, 255, .07) 0%, transparent 70%);
            bottom: -80px;
            left: -80px;
            border-radius: 50%;
            animation: blobDrift 16s ease-in-out infinite alternate-reverse;
        }

        @keyframes blobDrift {
            from {
                transform: translate(0, 0) scale(1);
            }

            to {
                transform: translate(30px, 20px) scale(1.08);
            }
        }

        /* grid pattern overlay */
        .panel-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .04) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        .panel-brand {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: .875rem;
        }

        .panel-brand img {
            height: 42px;
            width: auto;
            filter: brightness(0) invert(1);
        }

        .panel-brand-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.3px;
        }

        .panel-hero {
            position: relative;
            z-index: 2;
        }

        .panel-hero h2 {
            font-size: 2.6rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            letter-spacing: -.5px;
            margin-bottom: 1.1rem;
        }

        .panel-hero h2 span {
            color: var(--brand-mid);
        }

        .panel-hero p {
            font-size: 1rem;
            color: var(--text-on-dark);
            line-height: 1.7;
            max-width: 380px;
        }

        .panel-stats {
            position: relative;
            z-index: 2;
            display: flex;
            gap: 2rem;
        }

        .stat-item {
            color: var(--text-on-dark);
        }

        .stat-number {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }

        .stat-label {
            font-size: .78rem;
            margin-top: .2rem;
            color: var(--text-muted-d);
        }

        /* ── RIGHT PANEL (form) ──────────────────────── */
        .auth-form-side {
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
        }

        .auth-form-card {
            width: 100%;
            max-width: 420px;
            animation: slideUp .55s cubic-bezier(.22, .68, 0, 1.2) both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(28px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-heading {
            margin-bottom: 2rem;
        }

        .form-heading h1 {
            font-size: 1.875rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -.5px;
        }

        .form-heading p {
            font-size: .9rem;
            color: #64748b;
            margin-top: .375rem;
        }

        /* ── Form fields ─────────────────────────────── */
        .field-group {
            margin-bottom: 1.25rem;
        }

        .field-label {
            display: block;
            font-size: .8125rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: .5rem;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
            transition: color .2s;
        }

        .field-wrap:focus-within .field-icon {
            color: var(--brand-dark);
        }

        .field-input {
            width: 100%;
            height: var(--input-h);
            padding: 0 14px 0 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: var(--radius);
            background: #fff;
            font-family: 'Inter', sans-serif;
            font-size: .9375rem;
            color: #0f172a;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .field-input::placeholder {
            color: #94a3b8;
        }

        .field-input:focus {
            border-color: var(--brand-dark);
            box-shadow: 0 0 0 3px var(--brand-glow);
        }

        /* password toggle */
        .field-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 1rem;
            padding: 4px;
            line-height: 1;
            transition: color .2s;
        }

        .field-toggle:hover {
            color: var(--brand-dark);
        }

        .field-input.has-toggle {
            padding-right: 42px;
        }

        /* error messages */
        .field-error {
            font-size: .78rem;
            color: #ef4444;
            margin-top: .375rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        /* ── Remember / Forgot row ───────────────────── */
        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .85rem;
            color: #374151;
            cursor: pointer;
            user-select: none;
        }

        .remember-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--brand-dark);
            cursor: pointer;
        }

        .forgot-link {
            font-size: .85rem;
            font-weight: 600;
            color: var(--brand-dark);
            text-decoration: none;
            transition: color .15s;
        }

        .forgot-link:hover {
            color: var(--brand-mid);
            text-decoration: underline;
        }

        /* ── Submit button ───────────────────────────── */
        .btn-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .625rem;
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, var(--brand-dark) 0%, #28a875 60%, var(--brand-mid) 100%);
            background-size: 200% auto;
            color: #fff;
            border: none;
            border-radius: var(--radius);
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: .3px;
            transition: background-position .4s ease, box-shadow .2s, transform .15s;
            box-shadow: 0 4px 18px rgba(29, 97, 84, .35);
        }

        .btn-login:hover {
            background-position: right center;
            box-shadow: 0 6px 24px rgba(29, 97, 84, .45);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: .65;
            cursor: not-allowed;
            transform: none;
        }

        /* spinner inside button */
        .btn-spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2.5px solid rgba(255, 255, 255, .35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .65s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ── Session status / alert ─────────────────── */
        .auth-alert {
            display: flex;
            align-items: center;
            gap: .625rem;
            padding: .75rem 1rem;
            border-radius: var(--radius);
            font-size: .85rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .auth-alert.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .auth-alert.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        /* ── Responsive ─────────────────────────────── */
        @media (max-width: 900px) {
            .auth-shell {
                grid-template-columns: 1fr;
            }

            .auth-panel {
                display: none;
            }

            .auth-form-side {
                padding: 2rem 1.25rem;
                align-items: flex-start;
                padding-top: 3.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="auth-shell">

        {{-- ── LEFT: Branded panel ── --}}
        <aside class="auth-panel">
            <div class="panel-grid"></div>

            <div class="panel-brand">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}">
                {{-- <span class="panel-brand-name">{{ config('app.name') }}</span> --}}
            </div>

            <div class="panel-hero">
                <h2>Powering <span>Africa's</span><br>Tomorrow</h2>
                <p>A unified platform to manage programmes, staff, applications and community impact, all in one place.
                </p>
            </div>

            <div class="panel-stats">
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Active Programmes</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">12k+</div>
                    <div class="stat-label">Beneficiaries</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">30+</div>
                    <div class="stat-label">Partner Orgs</div>
                </div>
            </div>
        </aside>

        {{-- ── RIGHT: Form slot ── --}}
        <main class="auth-form-side">
            <div class="auth-form-card">
                {{ $slot }}
            </div>
        </main>

    </div>
</body>

</html>
