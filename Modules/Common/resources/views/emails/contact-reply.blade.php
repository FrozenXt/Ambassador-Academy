<div
    style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;max-width:600px;margin:0 auto;background:#ffffff;">

    <div style="height:4px;background:linear-gradient(90deg,#4f46e5,#7c3aed);border-radius:4px 4px 0 0;"></div>

    <div style="padding:32px 40px 24px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;">
        <p
            style="margin:0 0 4px;font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;">
            Response to your inquiry</p>
        <h1 style="margin:0;font-size:20px;font-weight:600;color:#111827;">We've replied to your message</h1>
    </div>

    <div
        style="height:1px;background:#f3f4f6;margin:0 40px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;">
    </div>

    <div style="padding:24px 40px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;">
        <p style="margin:0 0 20px;font-size:14px;color:#374151;">Dear {{ $replyName }},</p>
        <p
            style="margin:0 0 16px;font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;">
            Our Reply</p>
        <div
            style="font-size:14px;line-height:1.75;color:#374151;padding:20px;background:#fafafa;border-radius:8px;border:1px solid #f3f4f6;white-space:pre-wrap;">
            {!! nl2br(e($reply)) !!}</div>
    </div>

    <div style="padding:0 40px 24px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;">
        <div style="padding:14px 20px;background:#f9fafb;border-radius:8px;border:1px solid #e5e7eb;">
            <table style="width:100%;border-collapse:collapse;">
                <tr>
                    <td
                        style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;padding-right:8px;white-space:nowrap;">
                        Your original subject</td>
                    <td style="font-size:13px;color:#374151;font-weight:500;">{{ $replySubject }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div
        style="padding:16px 40px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0 0 4px 4px;text-align:center;">
        <p style="margin:0;font-size:11px;color:#d1d5db;">
            &copy; {{ $year }} &middot; This is a reply to your submitted inquiry &middot; Please do not reply
            to this email
        </p>
    </div>

</div>
