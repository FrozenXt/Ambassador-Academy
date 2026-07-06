<div
    style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;max-width:600px;margin:0 auto;background:#ffffff;">

    <div style="height:4px;background:linear-gradient(90deg,#4f46e5,#7c3aed);border-radius:4px 4px 0 0;"></div>

    <div style="padding:32px 40px 24px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;">
        <table style="width:100%;border-collapse:collapse;">
            <tr>
                <td>
                    <p
                        style="margin:0 0 4px;font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;">
                        Configuration Check</p>
                    <h1 style="margin:0;font-size:20px;font-weight:600;color:#111827;">Email Delivery Verified</h1>
                </td>
                <td style="text-align:right;vertical-align:top;">
                    <span
                        style="display:inline-block;background:#f0fdf4;color:#15803d;font-size:11px;font-weight:600;letter-spacing:.05em;text-transform:uppercase;padding:4px 10px;border-radius:20px;border:1px solid #bbf7d0;">Success</span>
                </td>
            </tr>
        </table>
    </div>

    <div
        style="height:1px;background:#f3f4f6;margin:0 40px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;">
    </div>

    <div style="padding:24px 40px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;">
        <p style="margin:0 0 6px;font-size:14px;color:#374151;">Your mail configuration is working correctly.</p>
        <p style="margin:0;font-size:13px;color:#6b7280;">This test email confirms that outbound delivery is active and
            properly routed through your SMTP settings.</p>
    </div>

    <div style="margin:0 40px;border-radius:8px;border:1px solid #e5e7eb;overflow:hidden;">
        <div style="padding:10px 20px;background:#f9fafb;border-bottom:1px solid #e5e7eb;">
            <p
                style="margin:0;font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;">
                Configuration Details</p>
        </div>
        <table style="width:100%;border-collapse:collapse;">
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td
                    style="padding:12px 20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;width:36%;white-space:nowrap;">
                    Mailer</td>
                <td style="padding:12px 20px;font-size:13px;color:#111827;font-weight:500;">{{ $mailerLabel }}</td>
            </tr>
            <tr style="background:#fafafa;border-bottom:1px solid #f3f4f6;">
                <td
                    style="padding:12px 20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;white-space:nowrap;">
                    Host</td>
                <td style="padding:12px 20px;font-size:13px;color:#111827;font-family:monospace;">{{ $host }}
                </td>
            </tr>
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td
                    style="padding:12px 20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;white-space:nowrap;">
                    Port</td>
                <td style="padding:12px 20px;font-size:13px;color:#111827;font-family:monospace;">{{ $port }}
                </td>
            </tr>
            <tr style="background:#fafafa;">
                <td
                    style="padding:12px 20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;white-space:nowrap;">
                    From</td>
                <td style="padding:12px 20px;font-size:13px;color:#4f46e5;font-family:monospace;">{{ $fromName }}
                    &lt;{{ $fromAddress }}&gt;</td>
            </tr>
        </table>
    </div>

    <div style="padding:24px 40px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;">
        <div style="padding:14px 20px;background:#fffbeb;border-radius:8px;border:1px solid #fde68a;">
            <table style="width:100%;border-collapse:collapse;">
                <tr>
                    <td
                        style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:#92400e;padding-right:8px;white-space:nowrap;">
                        Delivered to</td>
                    <td style="font-size:13px;color:#78350f;font-weight:500;font-family:monospace;">{{ $toEmail }}
                    </td>
                    <td style="text-align:right;font-size:11px;color:#92400e;white-space:nowrap;">{{ $sentAt }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div
        style="padding:16px 40px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0 0 4px 4px;text-align:center;">
        <p style="margin:0;font-size:11px;color:#d1d5db;">
            &copy; {{ $year }} &middot; Automated test from CMS Email Settings &middot; No action required
        </p>
    </div>

</div>
