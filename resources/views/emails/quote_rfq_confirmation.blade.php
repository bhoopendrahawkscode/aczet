<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Aczet Official Consolidated RFQ Proforma</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background: #F8FAFC; color: #1E293B; margin: 0; padding: 30px 10px;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="650" style="background: #FFFFFF; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #E2E8F0;">
        <!-- Header -->
        <tr style="background: #0B132B;">
            <td style="padding: 30px; text-align: center;">
                <h1 style="color: #FFFFFF; font-size: 28px; margin: 0; font-weight: 800;">
                    aczet<span style="color: #FF5A36;">.</span>
                </h1>
                <p style="color: #94A3B8; font-size: 13px; margin: 4px 0 0; text-transform: uppercase; letter-spacing: 2px;">Consolidated Request for Quote (RFQ)</p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 36px 30px;">
                <h2 style="color: #0F172A; font-size: 20px; margin: 0 0 12px;">RFQ Received - Reference #RFQ-{{ $lead->id }}</h2>
                <p style="color: #475569; font-size: 15px; line-height: 1.6; margin: 0 0 24px;">
                    Dear <strong>{{ $lead->name }}</strong>, thank you for requesting an official commercial quotation for Aczet precision laboratory systems. Below are the instruments included in your consolidated RFQ package:
                </p>

                <!-- Product Table -->
                <table width="100%" cellpadding="10" cellspacing="0" style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 8px; margin-bottom: 24px;">
                    <thead>
                        <tr style="background: #F1F5F9; border-bottom: 1px solid #E2E8F0;">
                            <th align="left" style="font-size: 12px; color: #475569; text-transform: uppercase;">Model</th>
                            <th align="left" style="font-size: 12px; color: #475569; text-transform: uppercase;">Instrument Description</th>
                            <th align="center" style="font-size: 12px; color: #475569; text-transform: uppercase;">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $p)
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="font-weight: 700; color: #FF5A36; font-size: 13px;">{{ $p->model_number }}</td>
                            <td style="font-size: 13px; color: #1E293B; font-weight: 600;">{{ $p->name }}</td>
                            <td align="center" style="font-size: 13px; font-weight: 700; background: #F8FAFC;">1</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                    <h4 style="margin: 0 0 8px; color: #0F172A; font-size: 14px;">Next Steps:</h4>
                    <p style="margin: 0; font-size: 13px; color: #64748B; line-height: 1.5;">
                        1. Our regional sales engineer will issue the formal Proforma Invoice with export duties & freight estimates.<br>
                        2. Certified calibration datasheets and validation protocols (IQ/OQ/PQ) will be attached.
                    </p>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr style="background: #F1F5F9; border-top: 1px solid #E2E8F0;">
            <td style="padding: 20px 30px; text-align: center; color: #64748B; font-size: 12px; line-height: 1.5;">
                &copy; {{ date('Y') }} Aczet Inc. ISO 9001:2015 &amp; OIML Certified Global Metrology.
            </td>
        </tr>
    </table>
</body>
</html>
