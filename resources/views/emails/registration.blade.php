<p>Dear {{ $member->name }},</p>

<p>Thank you for registering. Please verify your email address by clicking the link below:</p>

<p>
    <a href="{{ URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), ['id' => $member->id, 'hash' => sha1($member->email)]) }}"
        style="display: inline-block; padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">
        Verify Email
    </a>
</p>

<p>Additionally, please visit the ENREMCO Office to complete the verification of your registration.</p>

<p>If you did not register, please disregard this email.</p>

<p>From: ENREMCO Team</p>