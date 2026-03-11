@php
    $senderName = $leaveRequest->user->name;
    $senderFirst = explode(' ', trim($senderName))[0];
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
    <title>Leave Request Approved — {{ $orgName }}</title>
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
            background: linear-gradient(150deg, #064e3b 0%, #065f46 40%, #047857 75%, #059669 100%);
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
            background: #064e3b;
            line-height: 0;
            font-size: 0;
        }

        .accent {
            background: linear-gradient(90deg, #064e3b 0%, #10b981 50%, #064e3b 100%);
            height: 3px;
        }

        .body-cell {
            padding: 36px 48px 8px;
        }

        .greeting {
            border-left: 4px solid #10b981;
            background: #ecfdf5;
            border-radius: 0 10px 10px 0;
            padding: 14px 18px;
            margin-bottom: 28px;
            font-size: 14px;
            line-height: 1.7;
            color: #065f46;
        }

        .greeting strong {
            color: #064e3b;
        }

        .detail-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .detail-head {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-bottom: 1px solid #a7f3d0;
            padding: 12px 20px;
            font-size: 12px;
            font-weight: 700;
            color: #064e3b;
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

        .badge-success {
            background: rgba(16, 185, 129, .15);
            color: #065f46;
        }

        .cta-cell {
            padding: 8px 48px 36px;
            text-align: center;
        }

        .cta {
            display: inline-block;
            padding: 14px 40px;
            background: linear-gradient(135deg, #064e3b 0%, #047857 45%, #10b981 100%);
            color: #fff !important;
            text-decoration: none;
            border-radius: 999px;
            font-size: 15px;
            font-weight: 700;
        }

        .footer {
            background: #f8fafc;
            padding: 24px 48px;
            text-align: center;
        }

        .footer-brand {
            font-size: 16px;
            font-weight: 800;
            color: #064e3b;
            margin-bottom: 4px;
        }

        .footer-note {
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .footer-note a {
            color: #047857;
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
                            <h1>✅ Leave Request Approved</h1>
                            <p>Great news! Your leave request has been approved.</p>
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
                                Your leave request has been <strong>approved</strong> by
                                <strong>{{ $leaveRequest->supervisor->name }}</strong>.
                                @if ($leaveRequest->decision_note)
                                    <br><br><em>"{{ $leaveRequest->decision_note }}"</em>
                                @endif
                            </div>

                            <div class="detail-card">
                                <div class="detail-head">Approved Leave Details</div>
                                <div class="detail-row">
                                    <span class="detail-label">Leave Type</span><br>
                                    <span class="detail-value">{{ $leaveRequest->type_name }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Duration</span><br>
                                    <span
                                        class="detail-value">{{ $leaveRequest->effective_start_date->format('M d, Y') }}
                                        — {{ $leaveRequest->effective_end_date->format('M d, Y') }}
                                        ({{ $leaveRequest->effective_duration }}
                                        day{{ $leaveRequest->effective_duration > 1 ? 's' : '' }})</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Status</span><br>
                                    <span class="badge badge-success">Approved</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="cta-cell">
                            <a href="{{ $dashboardUrl }}" class="cta">View Details &rarr;</a>
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
