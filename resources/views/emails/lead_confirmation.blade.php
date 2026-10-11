<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Aczet Quote & Inquiry Confirmation</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background: #F8FAFC; color: #1E293B; margin: 0; padding: 30px 10px;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="background: #FFFFFF; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #E2E8F0;">
        <!-- Header -->
        <tr style="background: #0B132B;">
            <td style="padding: 30px; text-align: center;">
                <h1 style="color: #FFFFFF; font-size: 28px; margin: 0; font-weight: 800; letter-spacing: 0.5px;">
                    aczet<span style="color: #FF5A36;">.</span>
                </h1>
                <p style="color: #94A3B8; font-size: 13px; margin: 4px 0 0; text-transform: uppercase; letter-spacing: 2px;">Precision Weighing & Analytical Systems</p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 36px 30px;">
                <h2 style="color: #0F172A; font-size: 20px; margin: 0 0 16px;">Thank You, {{ $lead->name }}!</h2>
                <p style="color: #475569; font-size: 15px; line-height: 1.6; margin: 0 0 24px;">
                    We have successfully received your product inquiry. A dedicated Aczet application engineer is reviewing your request and will provide an official technical proposal within 24 hours.
                </p>

                <!-- Inquiry Summary Box -->
                <table width="100%" cellpadding="12" cellspacing="0" style="background: #F8FAFC; border-radius: 8px; border: 1px solid #E2E8F0; margin-bottom: 24px;">
                    <tr>
                        <td style="font-weight: 700; color: #64748B; width: 140px; font-size: 13px;">Reference ID:</td>
                        <td style="font-weight: 800; color: #FF5A36; font-size: 14px;">#LEAD-{{ $lead->id }}</td>
                    </tr>
                    @if($lead->product)
                    <tr>
                        <td style="font-weight: 700; color: #64748B; font-size: 13px;">Product Model:</td>
                        <td style="font-weight: 700; color: #0F172A; font-size: 14px;">{{ $lead->product->name }} ({{ $lead->product->model_number }})</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-weight: 700; color: #64748B; font-size: 13px;">Region:</td>
                        <td style="font-weight: 600; color: #0F172A; font-size: 14px;">{{ $lead->region ?? 'Global' }}</td>
                    </tr>
                    @if($lead->company)
                    <tr>
                        <td style="font-weight: 700; color: #64748B; font-size: 13px;">Organization:</td>
                        <td style="color: #0F172A; font-size: 14px;">{{ $lead->company }}</td>
                    </tr>
                    @endif
                    @if($lead->message)
                    <tr>
                        <td style="font-weight: 700; color: #64748B; font-size: 13px;">Application Notes:</td>
                        <td style="color: #475569; font-size: 13px; line-height: 1.5;">{{ $lead->message }}</td>
                    </tr>
                    @endif
                </table>

                <div style="background: #FFF7ED; border-left: 4px solid #FF5A36; padding: 14px 18px; border-radius: 6px; margin-bottom: 24px;">
                    <p style="margin: 0; color: #9A3412; font-size: 13px; font-weight: 600;">
                        Need urgent calibration consultation? Reach our global helpline directly at <strong>+91 22 2847 4800</strong> or reply to this email.
                    </p>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr style="background: #F1F5F9; border-top: 1px solid #E2E8F0;">
            <td style="padding: 20px 30px; text-align: center; color: #64748B; font-size: 12px; line-height: 1.5;">
                &copy; {{ date('Y') }} Aczet Inc. Corporate Headquarters: Mumbai &bull; Dubai &bull; Frankfurt.<br>
                ISO 9001:2015 &amp; OIML Certified High Precision Weighing Technology.
            </td>
        </tr>
    </table>
</body>
</html>
