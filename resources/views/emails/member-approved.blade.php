<!DOCTYPE html>
<html>
<head>
    <title>Membership Approved</title>
</head>
<body>
    <h2>Hi {{ $member->name }},</h2>
    <p>Congratulations! Your membership has been approved.</p>
    <p>You can now log in and access your account.</p>
    <p>Login link: <a href="https://enremco.com/login">Click Here</a></p>
    <br>
    <p>Thank you,</p>
    <p><strong>{{ config('app.name') }}</strong></p>
</body>
</html>
