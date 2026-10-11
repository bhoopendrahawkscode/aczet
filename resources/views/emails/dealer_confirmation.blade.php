<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Aczet Dealership Application Received</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background: #F8FAFC; color: #1E293B; margin: 0; padding: 30px 10px;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="background: #FFFFFF; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #E2E8F0;">
        <tr style="background: #0B132B;">
            <td style="padding: 30px; text-align: center;">
                <h1 style="color: #FFFFFF; font-size: 28px; margin: 0; font-weight: 800;">
                    aczet<span style="color: #FF5A36;">.</span>
                </h1>
                <p style="color: #94A3B8; font-size: 13px; margin: 4px 0 0; text-transform: uppercase; letter-spacing: 2px;">Global Distributor Network</p>
            </td>
        </tr>

        <tr>
            <td style="padding: 36px 30px;">
                <h2 style="color: #0F172A; font-size: 20px; margin: 0 0 16px;">Dealership Application Acknowledged</h2>
                <p style="color: #475569; font-size: 15px; line-height: 1.6; margin: 0 0 24px;">
                    Dear <strong>{{ $dealer->contact_person }}</strong>, thank you for applying to become an authorized Aczet distributor for <strong>{{ $dealer->country }}</strong>.
                </p>

                <table width="100%" cellpadding="12" cellspacing="0" style="background: #F8FAFC; border-radius: 8px; border: 1px solid #E2E8F0; margin-bottom: 24px;">
                    <tr>
                        <td style="font-weight: 700; color: #64748B; width: 140px; font-size: 13px;">Company Name:</td>
                        <td style="font-weight: 800; color: #0F172A; font-size: 14px;">{{ $dealer->company_name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 700; color: #64748B; font-size: 13px;">Target Territory:</td>
                        <td style="font-weight: 600; color: #0F172A; font-size: 14px;">{{ $dealer->country }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 700; color: #64748B; font-size: 13px;">Review Status:</td>
                        <td style="color: #D97706; font-weight: 700; font-size: 14px;">Under Review by Channel Sales</td>
                    </tr>
                </table>

                <p style="color: #64748B; font-size: 14px; line-height: 1.5;">
                    Our international channel manager will review your business credentials and schedule an initial partner evaluation call within 2 business days.
                </p>
            </td>
        </tr>

        <tr style="background: #F1F5F9; border-top: 1px solid #E2E8F0;">
            <td style="padding: 20px 30px; text-align: center; color: #64748B; font-size: 12px;">
                &copy; {{ date('Y') }} Aczet Inc. OEM &amp; Distributor Relations.
            </td>
        </tr>
    </table>
</body>
</html>
