<div
    style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;max-width:600px;margin:0 auto;background:#ffffff;">

    <div style="height:4px;background:linear-gradient(90deg,#c8102e,#a00d24);border-radius:4px 4px 0 0;"></div>

    <div style="padding:32px 40px 24px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;">
        <table style="width:100%;border-collapse:collapse;">
            <tr>
                <td>
                    <p
                        style="margin:0 0 4px;font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;">
                        Contact Form</p>
                    <h1 style="margin:0;font-size:20px;font-weight:600;color:#111827;">New Contact Message</h1>
                </td>
                <td style="text-align:right;vertical-align:top;">
                    <span
                        style="display:inline-block;background:#eff6ff;color:#1d4ed8;font-size:11px;font-weight:600;letter-spacing:.05em;text-transform:uppercase;padding:4px 10px;border-radius:20px;border:1px solid #bfdbfe;">ENQUIRY</span>
                </td>
            </tr>
        </table>
    </div>

    <div
        style="height:1px;background:#f3f4f6;margin:0 40px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;">
    </div>

    <div style="padding:24px 40px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;">
        <table style="width:100%;border-collapse:collapse;">
            <tr>
                <td style="width:48px;vertical-align:top;padding-right:16px;">
                    <div
                        style="width:44px;height:44px;border-radius:50%;background:#fff1f2;font-size:16px;font-weight:600;color:#c8102e;text-align:center;line-height:44px;">
                        {{ strtoupper(substr($name, 0, 1)) }}
                    </div>
                </td>
                <td style="vertical-align:middle;">
                    <p style="margin:0 0 2px;font-size:15px;font-weight:600;color:#111827;">{{ $name }}</p>
                    <p style="margin:0;font-size:13px;color:#6b7280;">
                        <a href="mailto:{{ $email }}"
                            style="color:#c8102e;text-decoration:none;">{{ $email }}</a>
                    </p>
                </td>
                <td style="vertical-align:middle;text-align:right;">
                    <p style="margin:0;font-size:11px;color:#9ca3af;">
                        {{ $sentAt }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin:0 40px;border-radius:8px;border:1px solid #e5e7eb;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td
                    style="padding:12px 20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;width:30%;background:#f9fafb;white-space:nowrap;">
                    Name</td>
                <td style="padding:12px 20px;font-size:13px;color:#111827;">{{ $name }}</td>
            </tr>
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td
                    style="padding:12px 20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;background:#f9fafb;white-space:nowrap;">
                    Email</td>
                <td style="padding:12px 20px;font-size:13px;font-family:monospace;">
                    <a href="mailto:{{ $email }}"
                        style="color:#c8102e;text-decoration:none;">{{ $email }}</a>
                </td>
            </tr>
            <tr>
                <td
                    style="padding:12px 20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;background:#f9fafb;vertical-align:top;white-space:nowrap;">
                    Message</td>
                <td style="padding:12px 20px;font-size:13px;color:#374151;line-height:1.75;">{!! nl2br(e($userMessage)) !!}
                </td>
            </tr>
        </table>
    </div>

    <div
        style="padding:16px 40px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0 0 4px 4px;text-align:center;margin-top:24px;">
        <p style="margin:0;font-size:11px;color:#d1d5db;">
            &copy; {{ date('Y') }} Sultan's Arabic Grill &middot; Automated notification &middot; Do not reply to
            this
            email
        </p>
    </div>

</div>
