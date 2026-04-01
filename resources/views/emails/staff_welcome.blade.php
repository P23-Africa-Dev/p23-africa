@php
    $firstName = explode(' ', trim($user->name))[0];
    $initials = collect(explode(' ', trim($user->name)))
        ->map(fn($w) => strtoupper($w[0]))
        ->take(2)
        ->implode('');
    $loginUrl = $organization && $organization->slug ? url('/' . $organization->slug . '/admin/login') : route('login');
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
    <title>Welcome to {{ $orgName }}</title>
    <!--[if mso]>
    <noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
    <![endif]-->
    <style>
        /* ── Reset ─────────────────────────────────────────── */
        * {
            box-sizing: border-box;
        }

        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            border-collapse: collapse;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            display: block;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #e8edf5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #1e293b;
            width: 100% !important;
            min-width: 100%;
        }

        /* ── Outer wrapper ──────────────────────────────────── */
        .outer-table {
            width: 100%;
            background-color: #e8edf5;
        }

        /* ── Card ───────────────────────────────────────────── */
        .card {
            width: 600px;
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
        }

        /* ── Header ─────────────────────────────────────────── */
        .hdr-cell {
            background: #0e4035;
            background: linear-gradient(150deg, #0a2e24 0%, #0e4035 40%, #16684f 75%, #1b8c68 100%);
            padding: 52px 48px 0;
            text-align: center;
        }

        /* org pill / logo badge */
        .org-pill {
            display: inline-block;
            background: rgba(255, 255, 255, .10);
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 999px;
            padding: 6px 18px 6px 8px;
            margin-bottom: 28px;
        }

        .org-pill-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #34d399;
            border-radius: 50%;
            margin-right: 8px;
            vertical-align: middle;
        }

        .org-pill span {
            font-size: 12px;
            font-weight: 700;
            color: #d1fae5;
            letter-spacing: .6px;
            text-transform: uppercase;
            vertical-align: middle;
        }

        /* avatar circle */
        .avatar-ring {
            display: inline-block;
            background: rgba(255, 255, 255, .08);
            border: 3px solid rgba(52, 211, 153, .4);
            border-radius: 50%;
            padding: 5px;
            margin-bottom: 22px;
        }

        .avatar-inner {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: linear-gradient(135deg, #22c58b 0%, #0f9e71 100%);
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .avatar-initials {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            letter-spacing: .5px;
            line-height: 1;
        }

        .hdr-headline {
            font-size: 30px;
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 10px;
            letter-spacing: -.4px;
            line-height: 1.22;
        }

        .hdr-headline .highlight {
            color: #6ee7b7;
        }

        .hdr-sub {
            font-size: 15px;
            color: rgba(255, 255, 255, .70);
            margin: 0 0 0;
        }

        /* wave separator */
        .wave-cell {
            background: #0e4035;
            line-height: 0;
            font-size: 0;
        }

        /* ── Accent bar ─────────────────────────────────────── */
        .accent-bar-cell {
            background: linear-gradient(90deg, #0e4035 0%, #22c58b 50%, #0e4035 100%);
            height: 3px;
        }

        /* ── Body ───────────────────────────────────────────── */
        .body-cell {
            padding: 44px 48px 8px;
        }

        /* greeting block */
        .greeting-block {
            border-left: 4px solid #22c58b;
            background: #f0fdf7;
            border-radius: 0 10px 10px 0;
            padding: 16px 20px;
            margin-bottom: 32px;
        }

        .greeting-block p {
            font-size: 15px;
            color: #2d4a3e;
            line-height: 1.75;
            margin: 0;
        }

        .greeting-block strong {
            color: #0e4035;
        }

        /* ── Section label ──────────────────────────────────── */
        .section-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin: 0 0 12px;
        }

        /* ── Credentials card ───────────────────────────────── */
        .cred-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 28px;
        }

        .cred-head-cell {
            background: linear-gradient(135deg, #f0fdf9 0%, #e8f9f3 100%);
            border-bottom: 1px solid #d1fae5;
            padding: 14px 22px;
        }

        .cred-head-icon {
            display: inline-block;
            width: 30px;
            height: 30px;
            background: #0e4035;
            border-radius: 7px;
            text-align: center;
            vertical-align: middle;
            margin-right: 10px;
            line-height: 30px;
        }

        .cred-head-title {
            font-size: 13px;
            font-weight: 700;
            color: #0a3028;
            letter-spacing: .4px;
            text-transform: uppercase;
            vertical-align: middle;
        }

        .cred-row-cell {
            padding: 13px 22px;
            border-bottom: 1px solid #f1f5f9;
        }

        .cred-row-cell.last {
            border-bottom: none;
        }

        .cred-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            padding-bottom: 3px;
            display: inline;
        }

        .cred-value {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 12px;
            display: inline-block;
            word-break: break-all;
        }

        /* ── Security notice ────────────────────────────────── */
        .notice-cell {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 4px solid #f59e0b;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 32px;
        }

        .notice-icon-cell {
            font-size: 20px;
            line-height: 1;
            vertical-align: top;
            padding-top: 2px;
            padding-right: 12px;
            width: 28px;
        }

        .notice-text {
            font-size: 13px;
            color: #78350f;
            line-height: 1.65;
        }

        .notice-text strong {
            color: #92400e;
        }

        /* ── Steps ──────────────────────────────────────────── */
        .steps-heading-cell {
            padding-bottom: 14px;
        }

        .step-outer-cell {
            padding-bottom: 10px;
        }

        .step-num-cell {
            width: 30px;
            vertical-align: top;
            padding-top: 1px;
            padding-right: 14px;
        }

        .step-num-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0e4035 0%, #22c58b 100%);
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            font-size: 12px;
            font-weight: 800;
            color: #fff;
        }

        .step-text-cell {
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
            padding-top: 4px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 10px;
        }

        .step-text-cell.last {
            border-bottom: none;
        }

        .step-text-cell strong {
            color: #1e293b;
        }

        /* ── CTA button ─────────────────────────────────────── */
        .cta-outer-cell {
            padding: 8px 48px 44px;
            text-align: center;
        }

        .cta-link {
            display: inline-block;
            padding: 15px 44px;
            background: #1d6154;
            background: linear-gradient(135deg, #0e4035 0%, #1d6154 45%, #22c58b 100%);
            color: #ffffff !important;
            text-decoration: none !important;
            border-radius: 999px;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .3px;
            mso-padding-alt: 0;
        }

        .cta-subtext {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 12px;
        }

        .cta-subtext a {
            color: #1d6154;
            text-decoration: none;
        }

        /* ── Divider ────────────────────────────────────────── */
        .divider-cell {
            padding: 0 48px;
        }

        .divider-line {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 0;
        }

        /* ── Footer ─────────────────────────────────────────── */
        .footer-cell {
            background: #f8fafc;
            padding: 28px 48px 32px;
            text-align: center;
        }

        .footer-brand {
            font-size: 16px;
            font-weight: 800;
            color: #0e4035;
            margin-bottom: 4px;
        }

        .footer-tagline {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 20px;
        }

        .footer-dots {
            font-size: 12px;
            color: #cbd5e1;
            margin-bottom: 16px;
            letter-spacing: 4px;
        }

        .footer-note {
            font-size: 11.5px;
            color: #94a3b8;
            line-height: 1.65;
        }

        .footer-note a {
            color: #1d6154;
            text-decoration: none;
        }

        /* ── Responsive ─────────────────────────────────────── */
        @media only screen and (max-width: 620px) {
            .card {
                width: 100% !important;
                border-radius: 0 !important;
            }

            .hdr-cell {
                padding: 36px 24px 0 !important;
            }

            .hdr-headline {
                font-size: 24px !important;
            }

            .body-cell {
                padding: 30px 24px 8px !important;
            }

            .cta-outer-cell {
                padding: 8px 24px 36px !important;
            }

            .footer-cell {
                padding: 24px !important;
            }

            .divider-cell {
                padding: 0 24px !important;
            }
        }
    </style>
</head>

<body>
    <!-- Outer wrapper table -->
    <table class="outer-table" cellpadding="0" cellspacing="0" role="presentation" width="100%">
        <tr>
            <td align="center" style="padding: 40px 16px;">

                <!-- Card table -->
                <table class="card" cellpadding="0" cellspacing="0" role="presentation" width="600">

                    {{-- ══════════════ HEADER ══════════════ --}}
                    <tr>
                        <td class="hdr-cell" align="center">

                            {{-- Org pill --}}
                            {{-- <table cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td>
                                        <div class="org-pill">
                                            <span class="org-pill-dot"></span>
                                            <span>{{ $orgName }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </table> --}}

                            {{-- Avatar with initials --}}
                            {{-- <div class="avatar-ring">
                                <table cellpadding="0" cellspacing="0" role="presentation">
                                    <tr>
                                        <td class="avatar-inner">
                                            <span class="avatar-initials">{{ $initials }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div> --}}

                            {{-- Headline --}}
                            <h1 class="hdr-headline">
                                Welcome, <span class="highlight">{{ $firstName }}</span>!
                            </h1>
                            <p class="hdr-sub">Your account has been set up and is ready to use.</p>

                            {{-- Wave SVG separator --}}
                            <div class="wave-cell" style="margin-top: 36px; line-height: 0; font-size: 0;">
                                <svg viewBox="0 0 600 40" xmlns="http://www.w3.org/2000/svg"
                                    style="display:block; width:100%; height:40px;" preserveAspectRatio="none">
                                    <path d="M0,20 C150,40 450,0 600,20 L600,40 L0,40 Z" fill="#ffffff" />
                                </svg>
                            </div>

                        </td>
                    </tr>

                    {{-- Accent bar --}}
                    <tr>
                        <td class="accent-bar-cell" height="3">&nbsp;</td>
                    </tr>

                    {{-- ══════════════ BODY ══════════════ --}}
                    <tr>
                        <td class="body-cell">

                            {{-- Greeting block --}}
                            <div class="greeting-block">
                                <p>
                                    Hi <strong>{{ $firstName }}</strong>, we're delighted to have you on board!
                                    Your staff account on <strong>{{ $orgName }}</strong> is ready.
                                    Below are your login credentials, please keep them safe and confidential.
                                </p>
                            </div>

                            {{-- Section label &nbsp; 🔑 --}}
                            <p class="section-label"><span>Login Credentials</span></p>

                            {{-- Credentials card --}}
                            <div class="cred-card">

                                {{-- Card header --}}
                                <table cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                    <tr>
                                        <td class="cred-head-cell">
                                            {{-- <span class="cred-head-icon">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="#6ee7b7"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    style="display:inline-block; vertical-align:middle; margin-top:-2px;">
                                                    <path
                                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                                </svg>
                                            </span> --}}
                                            <span class="cred-head-title">Your Login Details</span>
                                        </td>
                                    </tr>

                                    {{-- Email row --}}
                                    <tr>
                                        <td class="cred-row-cell">
                                            <span class="cred-label">Login Email</span>
                                            <span class="cred-value">{{ $user->email }}</span>
                                        </td>
                                    </tr>

                                    {{-- Password row --}}
                                    <tr>
                                        <td class="cred-row-cell last">
                                            <span class="cred-label">Temporary Password</span>
                                            <span class="cred-value">{{ $password }}</span>
                                        </td>
                                    </tr>
                                </table>

                            </div>{{-- /cred-card --}}

                            {{-- Security notice --}}
                            <table cellpadding="0" cellspacing="0" role="presentation" width="100%"
                                style="border:1px solid #fde68a; border-left:4px solid #f59e0b; border-radius:10px; background:#fffbeb; margin-bottom:32px;">
                                <tr>
                                    <td class="notice-icon-cell">⚠️</td>
                                    <td class="notice-text">
                                        <strong>Security Notice:</strong> This is a temporary password.
                                        Please change it immediately after your first login.
                                        Never share your credentials with anyone.
                                    </td>
                                </tr>
                            </table>

                            {{-- Getting started steps --}}
                            <p class="section-label">Getting Started</p>
                            <table cellpadding="0" cellspacing="0" role="presentation" width="100%"
                                style="margin-bottom:32px;">

                                @foreach ([['Click <strong>Login to Dashboard</strong> below and sign in with the credentials above.', false], ['Navigate to <strong>Profile &amp; Settings</strong> and set a new secure password right away.', false], ['Complete your profile, add your photo and contact details so your team can reach you.', false], ['Explore your dashboard and familiarise yourself with your tasks and projects.', true]] as [$text, $isLast])
                                    <tr>
                                        <td class="step-num-cell">
                                            <table cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                    <td class="step-num-circle">{{ $loop->iteration }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="step-text-cell {{ $isLast ? 'last' : '' }}">{!! $text !!}
                                        </td>
                                    </tr>
                                @endforeach

                            </table>

                        </td>
                    </tr>

                    {{-- ══════════════ CTA ══════════════ --}}
                    <tr>
                        <td class="cta-outer-cell">

                            {{-- MSO VML button fallback for Outlook --}}
                            <!--[if mso]>
                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word"
                    href="{{ $loginUrl }}"
                    style="height:50px; v-text-anchor:middle; width:220px;" arcsize="50%"
                    fillcolor="#1d6154" strokecolor="#1d6154">
                    <w:anchorlock/>
                    <center style="color:#ffffff;font-family:sans-serif;font-size:15px;font-weight:bold;">
                        Login to Dashboard &rarr;
                    </center>
                </v:roundrect>
                <![endif]-->
                            <!--[if !mso]><!-->
                            <a href="{{ $loginUrl }}" class="cta-link">
                                Login to Dashboard &rarr;
                            </a>
                            <!--<![endif]-->

                            <p class="cta-subtext">
                                Or copy this link: <a href="{{ $loginUrl }}">{{ $loginUrl }}</a>
                            </p>

                        </td>
                    </tr>

                    {{-- Divider --}}
                    <tr>
                        <td class="divider-cell">
                            <hr class="divider-line">
                        </td>
                    </tr>

                    {{-- ══════════════ FOOTER ══════════════ --}}
                    <tr>
                        <td class="footer-cell">

                            <div class="footer-brand">{{ $orgName }}</div>

                            @if ($organization)
                                <div class="footer-tagline">Powered by {{ config('app.name', 'P23 Africa') }}</div>
                            @else
                                <div class="footer-tagline">Powering Africa's Tomorrow</div>
                            @endif

                            <div class="footer-dots">• • •</div>

                            <p class="footer-note">
                                This email was sent to <strong>{{ $user->email }}</strong> because a staff account was
                                created for you on {{ $orgName }}.<br>
                                If you believe this was a mistake, please contact&nbsp;<a
                                    href="mailto:support@p23africa.org">support@p23africa.org</a>.
                            </p>

                        </td>
                    </tr>

                </table>
                {{-- /card --}}

            </td>
        </tr>
    </table>
    {{-- /outer wrapper --}}

</body>

</html>
