<!DOCTYPE html>
<html>
<head>
    <title>Membership Disapproval</title>
</head>
<body>
    <p>Dear {{ $member->name }},</p>

    <p>We regret to inform you that your membership application has been disapproved.</p>
    <p><strong>Reason:</strong></p>
    <p>{{ $reason }}</p>
    <br>
    <p>If you believe this was an error or have any questions, feel free to reach out to our office.</p>

    <p>Best regards,<br>Your Admin Team</p>
</body>
</html>
