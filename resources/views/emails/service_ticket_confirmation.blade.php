<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Aczet Service Ticket Confirmation</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background: #F8FAFC; color: #1E293B; margin: 0; padding: 30px 10px;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="background: #FFFFFF; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #E2E8F0;">
        <tr style="background: #0B132B;">
            <td style="padding: 30px; text-align: center;">
                <h1 style="color: #FFFFFF; font-size: 28px; margin: 0; font-weight: 800;">
                    aczet<span style="color: #FF5A36;">.</span>
                </h1>
                <p style="color: #94A3B8; font-size: 13px; margin: 4px 0 0; text-transform: uppercase; letter-spacing: 2px;">Technical Support & Warranty Center</p>
            </td>
        </tr>

        <tr>
            <td style="padding: 36px 30px;">
                <h2 style="color: #0F172A; font-size: 20px; margin: 0 0 16px;">Service Ticket #{{ $ticket->ticket_number }} Created</h2>
                <p style="color: #475569; font-size: 15px; line-height: 1.6; margin: 0 0 24px;">
                    Dear <strong>{{ $ticket->customer_name }}</strong>, your warranty / repair service request has been registered in our global technical support system.
                </p>

                <table width="100%" cellpadding="12" cellspacing="0" style="background: #F8FAFC; border-radius: 8px; border: 1px solid #E2E8F0; margin-bottom: 24px;">
                    <tr>
                        <td style="font-weight: 700; color: #64748B; width: 140px; font-size: 13px;">Ticket Number:</td>
                        <td style="font-weight: 800; color: #FF5A36; font-size: 14px;">{{ $ticket->ticket_number }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 700; color: #64748B; font-size: 13px;">Product Model:</td>
                        <td style="font-weight: 700; color: #0F172A; font-size: 14px;">{{ $ticket->product_name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 700; color: #64748B; font-size: 13px;">Serial Number:</td>
                        <td style="font-weight: 600; color: #0F172A; font-size: 14px;">{{ $ticket->serial_number }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 700; color: #64748B; font-size: 13px;">Issue Summary:</td>
                        <td style="color: #475569; font-size: 13px; line-height: 1.4;">{{ $ticket->issue_description }}</td>
                    </tr>
                </table>

                <p style="color: #64748B; font-size: 14px; line-height: 1.5;">
                    A service engineer will inspect the reported issue and contact you with troubleshooting steps or return authorization (RMA) instructions.
                </p>
            </td>
        </tr>

        <tr style="background: #F1F5F9; border-top: 1px solid #E2E8F0;">
            <td style="padding: 20px 30px; text-align: center; color: #64748B; font-size: 12px;">
                &copy; {{ date('Y') }} Aczet Inc. Customer Care &amp; Calibration Laboratory.
            </td>
        </tr>
    </table>
</body>
</html>
