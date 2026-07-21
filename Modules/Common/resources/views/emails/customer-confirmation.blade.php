<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>We've Received Your Message</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Alegreya:wght@400;500;600;700;800&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;500;600;700;800&display=swap');
    </style>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f9; font-family: 'Albert Sans', Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f4f6f9">
        <tr>
            <td align="center">
                <table width="500" cellpadding="0" cellspacing="0" border="0"
                    style="background:#ffffff; margin-top:30px; border-radius:8px; overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding:30px 20px 10px 20px;">

                            <h1
                                style="
    font-family: 'Alegreya', serif;
    font-weight: 800;
    font-size: 24px;
    line-height: 1;
    letter-spacing: 0;
    text-align: center;
    margin:15px 0 5px 0; color:#0d5c3f;
">
                                Thank You For Reaching Out
                            </h1>

                            <p
                                style="
    font-family: 'Albert Sans', Arial, Helvetica, sans-serif;
    font-weight: 400;
    font-size: 12px;
    line-height: 1;
    letter-spacing: 0;
    text-align: center;
    margin: 0;
    color: #b8860b;
">
                                We've Received Your Message
                            </p>
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="padding:20px 40px;">
                            <p
                                style="
    font-family: 'Albert Sans', Arial, Helvetica, sans-serif;
    font-size: 16px;
    line-height: 1.5;
    letter-spacing: 0;
    margin:0;
    color:#374151;
">
                                Dear {{ $name }},
                            </p>
                            <p
                                style="
    font-family: 'Albert Sans', Arial, Helvetica, sans-serif;
    font-size: 14px;
    line-height: 1.6;
    letter-spacing: 0;
    margin:12px 0 0 0;
    color:#374151;
">
                                Thank you for contacting <strong>Papa's bar and grill.</strong>. We've received your
                                message and one of our team members will get back to you shortly.
                            </p>
                        </td>
                    </tr>

                    <!-- Their message, for their records -->
                    <tr>
                        <td style="padding:0 40px 0 40px">
                            <p
                                style="
    font-family: 'Albert Sans', Arial, Helvetica, sans-serif;
    font-weight: 700;
    font-size: 14px;
    line-height: 1.2;
    letter-spacing: 0;
    margin:0 0 10px 0;
    color: #666666;
">
                                Your Message
                            </p>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td
                                        style="
    background:#FBF8EF;
    border-left:4px solid #b8860b;
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

                    <!-- Footer note -->
                    <tr>
                        <td style="padding:20px 40px 20px 40px">
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
                                This is an automated confirmation. Please do not reply to this email &mdash; our team
                                will contact you directly at <strong>{{ $email }}</strong>.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#0d5c3f" style="padding:25px 20px; text-align:center; color:#ffffff;">
                            <p style="margin:0; font-size:12px; opacity:0.8; padding:0;">
                                &copy; {{ date('Y') }} Papa's bar and grill.
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
