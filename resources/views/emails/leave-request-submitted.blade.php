@php
    $senderName = $leaveRequest->user->name;
    $senderFirst = explode(' ', trim($senderName))[0];
    $supervisorName = $leaveRequest->supervisor->name;
    $supervisorFirst = explode(' ', trim($supervisorName))[0];
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
    <title>New Leave Request — {{ $orgName }}</title>
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
            background: linear-gradient(150deg, #0a2e24 0%, #0e4035 40%, #16684f 75%, #1b8c68 100%);
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
            background: #0e4035;
            line-height: 0;
            font-size: 0;
        }

        .accent {
            background: linear-gradient(90deg, #0e4035 0%, #22c58b 50%, #0e4035 100%);
            height: 3px;
        }

        .body-cell {
            padding: 36px 48px 8px;
        }

        .greeting {
            border-left: 4px solid #22c58b;
            background: #f0fdf7;
            border-radius: 0 10px 10px 0;
            padding: 14px 18px;
            margin-bottom: 28px;
            font-size: 14px;
            line-height: 1.7;
            color: #2d4a3e;
        }

        .greeting strong {
            color: #0e4035;
        }

        .detail-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .detail-head {
            background: linear-gradient(135deg, #f0fdf9 0%, #e8f9f3 100%);
            border-bottom: 1px solid #d1fae5;
            padding: 12px 20px;
            font-size: 12px;
            font-weight: 700;
            color: #0a3028;
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

        .badge-warning {
            background: rgba(245, 158, 11, .15);
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
                            <h1>📋 New Leave Request</h1>
                            <p>A team member has submitted a leave request</p>
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
                                Hi <strong>{{ $supervisorFirst }}</strong>,<br>
                                <strong>{{ $senderName }}</strong> has submitted a leave request that requires your
                                review.
                            </div>

                            <div class="detail-card">
                                <div class="detail-head">Leave Request Details</div>
                                <div class="detail-row">
                                    <span class="detail-label">Employee</span><br>
                                    <span class="detail-value">{{ $senderName }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Leave Type</span><br>
                                    <span class="detail-value">{{ $leaveRequest->type_name }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Duration</span><br>
                                    <span class="detail-value">{{ $leaveRequest->start_date->format('M d, Y') }} —
                                        {{ $leaveRequest->end_date->format('M d, Y') }}
                                        ({{ $leaveRequest->duration_days }}
                                        day{{ $leaveRequest->duration_days > 1 ? 's' : '' }})</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Reason</span><br>
                                    <span class="detail-value">{{ $leaveRequest->reason }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Status</span><br>
                                    <span class="badge badge-warning">Pending Review</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="cta-cell">
                            <a href="{{ $dashboardUrl }}" class="cta">Review Leave Request &rarr;</a>
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
                            <p class="footer-note">
                                This email was sent to <strong>{{ $leaveRequest->supervisor->email }}</strong> because
                                you are a supervisor at {{ $orgName }}.<br>
                                <a href="{{ $dashboardUrl }}">View in dashboard</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
