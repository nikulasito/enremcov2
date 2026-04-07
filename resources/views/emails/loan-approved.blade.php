<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Loan Approved</title>
</head>

<body style="margin:0; padding:0; background:#f6f8f7; font-family:Arial, Helvetica, sans-serif; color:#111814;">
    <div
        style="max-width:640px; margin:24px auto; background:#ffffff; border:1px solid #dce5e0; border-radius:12px; overflow:hidden;">
        <div style="background:#112119; color:#ffffff; padding:20px 24px;">
            <h1 style="margin:0; font-size:20px; line-height:1.3;">Loan Application Approved</h1>
        </div>

        <div style="padding:24px;">
            <p style="margin:0 0 12px;">Hello {{ $member->name ?? 'Member' }},</p>
            <p style="margin:0 0 16px;">
                Great news. Your loan application has been approved.
            </p>

            <table role="presentation" cellpadding="0" cellspacing="0"
                style="width:100%; border-collapse:collapse; margin:0 0 16px;">
                <tr>
                    <td style="padding:8px 0; color:#638875; width:180px;">Application No.</td>
                    <td style="padding:8px 0; font-weight:700;">{{ $application->application_no ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#638875;">Loan Type</td>
                    <td style="padding:8px 0; font-weight:700; text-transform:uppercase;">
                        {{ $application->loan_type ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#638875;">Approved Amount</td>
                    <td style="padding:8px 0; font-weight:700;">
                        PHP
                        {{ number_format((float) ($application->approved_amount ?? $application->loan_amount ?? 0), 2) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#638875;">Status</td>
                    <td style="padding:8px 0; font-weight:700;">
                        {{ strtoupper((string) ($application->status ?? 'approved')) }}</td>
                </tr>
            </table>

            @if(!empty($application->remarks))
                <p style="margin:0 0 12px;"><strong>Admin Notes:</strong> {{ $application->remarks }}</p>
            @endif

            <p style="margin:16px 0 0;">
                Please log in to your member dashboard for more details.
            </p>
        </div>

        <div style="padding:16px 24px; background:#f6f8f7; color:#638875; font-size:12px;">
            ENREMCO
        </div>
    </div>
</body>

</html>