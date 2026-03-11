@php
    $senderName = $leaveRequest->user->name;
    $senderFirst = explode(' ', trim($senderName))[0];
    $supervisorName = $leaveRequest->supervisor->name;
    $dashboardUrl =
        $organization && $organization->slug
            ? url('/' . $organization->slug . '/admin/leave-requests/' . $leaveRequest->id)
            : url('/admin/leave-requests/' . $leaveRequest->id);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Modified — {{ $orgName }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #e8edf5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #1e293b;
        }

        .outer {
            width: 100%;
            background: #e8edf5;
        }

        .card {
            width: 600px;
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
        }

        .hdr {
            background: linear-gradient(150deg, #1e3a5f 0%, #1e40af 40%, #2563eb 75%, #3b82f6 100%);
            padding: 40px 48px 0;
            text-align: center;
        }

        .hdr h1 {
            font-size: 26px;
            font-weight: 800;
            color: #fff;
            margin: 0 0 8px;
        }

        .hdr p {
            font-size: 14px;
            color: rgba(255, 255, 255, .7);
            margin: 0;
        }

        .wave {
            background: #1e3a5f;
            line-height: 0;
            font-size: 0;
        }

        .accent {
            background: linear-gradient(90deg, #1e3a5f 0%, #3b82f6 50%, #1e3a5f 100%);
            height: 3px;
        }

        .body-cell {
            padding: 36px 48px 8px;
        }

        .greeting {
            border-left: 4px solid #3b82f6;
            background: #eff6ff;
            border-radius: 0 10px 10px 0;
            padding: 14px 18px;
            margin-bottom: 28px;
            font-size: 14px;
            line-height: 1.7;
            color: #1e3a5f;
        }

        .greeting strong {
            color: #1e3a5f;
        }

        .detail-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .detail-head {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-bottom: 1px solid #bfdbfe;
            padding: 12px 20px;
            font-size: 12px;
            font-weight: 700;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .detail-row {
            padding: 10px 20px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
        }

        .detail-value {
            font-weight: 600;
            color: #0f172a;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-info {
            background: rgba(59, 130, 246, .15);
            color: #1e40af;
        }

        .modified {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #78350f;
            line-height: 1.65;
        }

        .modified strong {
            color: #92400e;
        }

        .cta-cell {
            padding: 8px 48px 36px;
            text-align: center;
        }

        .cta {
            display: inline-block;
            padding: 14px 40px;
            background: linear-gradient(135deg, #0e4035 0%, #1d6154 45%, #22c58b 100%);
            color: #fff !important;
            text-decoration: none;
            border-radius: 999px;
            font-size: 15px;
            font-weight: 700;
        }

        .cta-note {
            font-size: 12px;
            color: #64748b;
            margin-top: 12px;
        }

        .footer {
            background: #f8fafc;
            padding: 24px 48px;
            text-align: center;
        }

        .footer-brand {
            font-size: 16px;
            font-weight: 800;
            color: #0e4035;
            margin-bottom: 4px;
        }

        .footer-note {
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .footer-note a {
            color: #1d6154;
            text-decoration: none;
        }

        @media (max-width:620px) {
            .card {
                width: 100% !important;
                border-radius: 0 !important;
            }

            .hdr,
            .body-cell,
            .cta-cell,
            .footer {
                padding-left: 24px !important;
                padding-right: 24px !important;
            }
        }
    </style>
</head>

<body>
    <table class="outer" cellpadding="0" cellspacing="0" role="presentation" width="100%">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table class="card" cellpadding="0" cellspacing="0" role="presentation" width="600">
                    <tr>
                        <td class="hdr">
                            <h1>✏️ Leave Request Modified</h1>
                            <p>Your supervisor has suggested changes to your leave request</p>
                            <div class="wave" style="margin-top:32px;">
                                <svg viewBox="0 0 600 40" xmlns="http://www.w3.org/2000/svg"
                                    style="display:block;width:100%;height:40px;" preserveAspectRatio="none">
                                    <path d="M0,20 C150,40 450,0 600,20 L600,40 L0,40 Z" fill="#ffffff" />
                                </svg>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="accent" height="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="body-cell">
                            <div class="greeting">
                                Hi <strong>{{ $senderFirst }}</strong>,<br>
                                <strong>{{ $supervisorName }}</strong> has reviewed your leave request and suggested
                                some modifications. Please review the changes and respond.
                            </div>

                            <div class="detail-card">
                                <div class="detail-head">Original Request</div>
                                <div class="detail-row">
                                    <span class="detail-label">Leave Type</span><br>
                                    <span class="detail-value">{{ $leaveRequest->type_name }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Original Duration</span><br>
                                    <span class="detail-value"
                                        style="text-decoration:line-through;color:#94a3b8;">{{ $leaveRequest->start_date->format('M d, Y') }}
                                        — {{ $leaveRequest->end_date->format('M d, Y') }}
                                        ({{ $leaveRequest->duration_days }}
                                        day{{ $leaveRequest->duration_days > 1 ? 's' : '' }})</span>
                                </div>
                            </div>

                            <div class="detail-card">
                                <div class="detail-head"
                                    style="background:linear-gradient(135deg,#eff6ff 0%,#dbeafe 100%);border-bottom-color:#bfdbfe;color:#1e40af;">
                                    Proposed Changes</div>
                                <div class="detail-row">
                                    <span class="detail-label">New Duration</span><br>
                                    <span class="detail-value"
                                        style="color:#2563eb;">{{ $leaveRequest->modified_start_date->format('M d, Y') }}
                                        — {{ $leaveRequest->modified_end_date->format('M d, Y') }}
                                        ({{ $leaveRequest->modified_duration_days }}
                                        day{{ $leaveRequest->modified_duration_days > 1 ? 's' : '' }})</span>
                                </div>
                                @if ($leaveRequest->supervisor_note)
                                    <div class="detail-row">
                                        <span class="detail-label">Supervisor's Note</span><br>
                                        <span class="detail-value">{{ $leaveRequest->supervisor_note }}</span>
                                    </div>
                                @endif
                                <div class="detail-row">
                                    <span class="detail-label">Status</span><br>
                                    <span class="badge badge-info">Awaiting Your Response</span>
                                </div>
                            </div>

                            <div class="modified">
                                <strong>⚡ Action Required:</strong> Please click the button below to review the
                                modifications and respond with <strong>"Okay"</strong> or <strong>"Not Okay"</strong>.
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="cta-cell">
                            <a href="{{ $dashboardUrl }}" class="cta">Review & Respond &rarr;</a>
                            <p class="cta-note">You'll be able to accept or decline the proposed changes</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 48px;">
                            <hr style="border:none;border-top:1px solid #e2e8f0;margin:0;">
                        </td>
                    </tr>
                    <tr>
                        <td class="footer">
                            <div class="footer-brand">{{ $orgName }}</div>
                            <p class="footer-note">This email was sent to
                                <strong>{{ $leaveRequest->user->email }}</strong>.<br><a
                                    href="{{ $dashboardUrl }}">View in dashboard</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
