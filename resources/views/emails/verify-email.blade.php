<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px;">
        <h4 style="color: #333;">Hello, {{ $member->name }}!</h4>

        <p>Thank you for signing up with ENREMCO. Please verify your email address by clicking the button below:</p>

        <p style="text-align: center;">
            <a href="{{ $verificationUrl }}"
               style="display: inline-block; padding: 12px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Verify Email
            </a>
        </p>

        <p>Additionally, please visit the ENREMCO Office to complete the verification of your registration.</p>

        <p>If you did not create an account, no further action is required.</p>

        <p>– The ENREMCO Team</p>
    </div>
</body>
</html>
