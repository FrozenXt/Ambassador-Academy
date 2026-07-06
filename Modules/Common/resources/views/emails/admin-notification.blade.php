<div
    style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;max-width:600px;margin:0 auto;background:#ffffff;">

    <div style="height:4px;background:linear-gradient(90deg,#c8102e,#a00d24);border-radius:4px 4px 0 0;"></div>

    <div style="padding:32px 40px 24px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;">
        <table style="width:100%;border-collapse:collapse;">
            <tr>
                <td>
                    <p
                        style="margin:0 0 4px;font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;">
                        MG Motor Nepal &middot; {{ $formLabel }}</p>
                    <h1 style="margin:0;font-size:20px;font-weight:600;color:#111827;">{{ $subjectLine }}</h1>
                </td>
                <td style="text-align:right;vertical-align:top;">
                    <span
                        style="display:inline-block;background:{{ $badgeColor }};color:{{ $badgeText }};font-size:11px;font-weight:600;letter-spacing:.05em;text-transform:uppercase;padding:4px 10px;border-radius:20px;border:1px solid {{ $badgeBorder }};">{{ $badgeLabel }}</span>
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
                        {{ $initial }}
                    </div>
                </td>
                <td style="vertical-align:middle;">
                    <p style="margin:0 0 2px;font-size:15px;font-weight:600;color:#111827;">{{ $name }}</p>
                    <p style="margin:0;font-size:13px;color:#6b7280;">
                        <a href="mailto:{{ $email }}"
                            style="color:#c8102e;text-decoration:none;">{{ $email }}</a>
                    </p>
                </td>
                @php
                    $now = now_np();
                @endphp

                <td style="vertical-align:middle;text-align:right;">
                    <p style="margin:0;font-size:11px;color:#9ca3af;">
                        {{ now_np()->format('d M Y') }}
                    </p>
                    <p style="margin:2px 0 0;font-size:11px;color:#9ca3af;">
                        {{ now_np()->format('h:i A') }} (NPT)
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
                    First Name</td>
                <td style="padding:12px 20px;font-size:13px;color:#111827;">{{ $contact->first_name }}</td>
            </tr>
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td
                    style="padding:12px 20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;background:#f9fafb;white-space:nowrap;">
                    Last Name</td>
                <td style="padding:12px 20px;font-size:13px;color:#111827;">{{ $contact->last_name }}</td>
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
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td
                    style="padding:12px 20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;background:#f9fafb;white-space:nowrap;">
                    Phone</td>
                <td style="padding:12px 20px;font-size:13px;color:#111827;font-family:monospace;">{{ $phone }}
                </td>
            </tr>

            {{-- Booking-specific rows --}}
            @if ($isBooking)
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td
                        style="padding:12px 20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;width:30%;background:#f9fafb;white-space:nowrap;">
                        Address</td>
                    <td style="padding:12px 20px;font-size:13px;color:#111827;">{{ $contact->address ?? '—' }}</td>
                </tr>
                <tr>
                    <td
                        style="padding:12px 20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;background:#f9fafb;white-space:nowrap;">
                        Interested Model</td>
                    <td style="padding:12px 20px;font-size:13px;font-weight:600;color:#c8102e;">{{ $contact->model }}
                    </td>
                </tr>
            @else
                {{-- Enquiry message row --}}
                <tr>
                    <td
                        style="padding:12px 20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;background:#f9fafb;vertical-align:top;white-space:nowrap;">
                        Message</td>
                    <td style="padding:12px 20px;font-size:13px;color:#374151;line-height:1.75;">{!! nl2br(e($contact->message ?? '—')) !!}
                    </td>
                </tr>
            @endif
        </table>
    </div>

    <div style="padding:28px 40px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;text-align:center;">
        <a href="{{ url('/admin/contacts?source=' . ($isBooking ? 'booking' : 'contact')) }}"
            style="display:inline-block;background:#c8102e;color:#ffffff;font-size:13px;font-weight:600;letter-spacing:.03em;padding:11px 28px;border-radius:8px;text-decoration:none;">
            Open in Dashboard →
        </a>
    </div>

    <div
        style="padding:16px 40px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0 0 4px 4px;text-align:center;">
        <p style="margin:0;font-size:11px;color:#d1d5db;">
            &copy; {{ $year }} MG Motor Nepal &middot; Automated notification &middot; Do not reply to this
            email
        </p>
    </div>

</div>
