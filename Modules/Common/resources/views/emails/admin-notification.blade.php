<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>New Contact Message</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Alegreya:wght@400;500;600;700;800&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;500;600;700;800&display=swap');
    </style>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f9; font-family: 'Albert Sans', Arial, Helvetica, sans-serif;">
    @php
        $embeddedLogo = !empty($logoAbsolutePath) ? $message->embed($logoAbsolutePath) : null;
    @endphp
    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f4f6f9">
        <tr>
            <td align="center">
                <table width="500" cellpadding="0" cellspacing="0" border="0"
                    style="background:#ffffff; margin-top:30px; border-radius:8px; overflow:hidden; border-top:4px solid #8a1c34;">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding:30px 20px 10px 20px;">

                            @if ($embeddedLogo)
                                <img src="{{ $embeddedLogo }}" alt="{{ $siteName ?? 'Logo' }}" width="56"
                                    height="56"
                                    style="display:block; margin:0 auto 12px auto; border-radius:8px; object-fit:contain;">
                            @endif

                            <h1
                                style="
    font-family: 'Alegreya', serif;
    font-weight: 800;
    font-size: 24px;
    line-height: 1;
    letter-spacing: 0;
    text-align: center;
    margin:0 0 5px 0;
    color:#14432f;
">
                                {{ $isBooking ? 'New Booking Request' : 'New Contact Message' }}
                            </h1>

                            <p
                                style="
    font-family: 'Albert Sans', Arial, Helvetica, sans-serif;
    font-weight: 600;
    font-size: 12px;
    line-height: 1;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    text-align: center;
    margin: 8px 0 0 0;
    color: #e8a93d;
    background: #fdeaea;
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
">
                                {{ $formLabel ?? 'Website Enquiry' }}
                            </p>
                        </td>
                    </tr>

                    <!-- Intro -->
                    <tr>
                        <td style="padding:20px 40px 0 40px;">
                            <p
                                style="
    font-family: 'Albert Sans', Arial, Helvetica, sans-serif;
    font-size: 16px;
    line-height: 1.5;
    letter-spacing: 0;
    margin:0;
    color:#374151;
">
                                You've received a new message from the contact form on
                                <strong>{{ $siteName ?? 'the website' }}</strong>.
                            </p>
                        </td>
                    </tr>

                    <!-- Sender Info -->
                    <tr>
                        <td style="padding:20px 40px 0 40px;">
                            <p
                                style="
    font-family: 'Albert Sans', Arial, Helvetica, sans-serif;
    font-weight: 700;
    font-size: 16px;
    line-height: 1.5;
    letter-spacing: 0;
    margin:0 0 8px 0;
    color: #14432f;
">
                                Sender Details
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="background:#f9f8f6; border-radius:6px;">
                                <tr>
                                    <td style="padding:14px 16px;">
                                        <p
                                            style="font-family: 'Albert Sans', Arial, Helvetica, sans-serif; font-weight: 400; font-size: 14px; line-height: 1.8; margin:0; color: #374151;">
                                            <strong style="color:#8a1c34;">Name:</strong> {{ $name }}<br>
                                            <strong style="color:#8a1c34;">Email:</strong>
                                            <a href="mailto:{{ $email }}"
                                                style="color:#14432f; text-decoration:none;">{{ $email }}</a><br>
                                            @if (!empty($phone) && $phone !== '—')
                                                <strong style="color:#8a1c34;">Phone:</strong> {{ $phone }}<br>
                                            @endif
                                            @if (!empty($subject))
                                                <strong style="color:#8a1c34;">Subject:</strong> {{ $subject }}<br>
                                            @endif
                                            <strong style="color:#8a1c34;">Sent:</strong> {{ $sentAt }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Message -->
                    <tr>
                        <td style="padding:20px 40px 0 40px">
                            <p
                                style="
    font-family: 'Albert Sans', Arial, Helvetica, sans-serif;
    font-weight: 700;
    font-size: 14px;
    line-height: 1.2;
    letter-spacing: 0;
    margin:0 0 10px 0;
    color: #14432f;
">
                                Message
                            </p>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td
                                        style="
    background:#fdf6e3;
    border-left:4px solid #e8a93d;
    padding:14px 16px;
    font-family: 'Albert Sans', Arial, Helvetica, sans-serif;
    font-size:13px;
    line-height:1.7;
    color:#374151;
    border-bottom-left-radius: 4px;
    border-top-left-radius: 4px;
">
                                        {!! nl2br(e($userMessage)) !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- CTA button to admin dashboard -->
                    @if (!empty($dashboardUrl))
                        <tr>
                            <td align="center" style="padding:26px 40px 0 40px;">
                                <a href="{{ $dashboardUrl }}" target="_blank"
                                    style="
    display:inline-block;
    background:#14432f;
    color:#ffffff;
    font-family: 'Albert Sans', Arial, Helvetica, sans-serif;
    font-weight:600;
    font-size:13px;
    text-decoration:none;
    padding:12px 28px;
    border-radius:6px;
    letter-spacing:0.3px;
">
                                    View in Dashboard
                                </a>
                            </td>
                        </tr>
                    @endif

                    <!-- Callout / Footer note -->
                    <tr>
                        <td style="padding:24px 40px 20px 40px">
                            <hr style="border:none; border-top:1px solid #e5e5e5; margin:10px 0 20px 0;">
                            <p
                                style="
    font-family: 'Albert Sans', Arial, Helvetica, sans-serif;
    font-weight: 400;
    font-size: 12px;
    line-height: 1.2;
    letter-spacing: 0.12px;
    text-align: center;
    margin:0;
    color: #666666;
">
                                Reply directly to this email to respond to
                                <a href="mailto:{{ $email }}" style="color:#8a1c34; text-decoration:none;"
                                    target="_blank">
                                    {{ $name }}
                                </a>.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#14432f" style="padding:25px 20px; text-align:center; color:#ffffff;">
                            @if ($embeddedLogo)
                                <img src="{{ $embeddedLogo }}" alt="{{ $siteName ?? 'Logo' }}" width="36"
                                    height="36"
                                    style="display:block; margin:0 auto 10px auto; border-radius:6px; object-fit:contain;">
                            @endif
                            <p style="margin:0; font-size:12px; opacity:0.85; padding:0;">
                                &copy; {{ $year ?? date('Y') }} {{ $siteName ?? 'Ambassador Academy' }} &middot;
                                Automated notification &middot; Do not reply-all
                            </p>
                        </td>
                    </tr>
                </table>
                <!-- End Container -->
            </td>
        </tr>
    </table>
</body>

</html>
