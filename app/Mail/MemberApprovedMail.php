<?php

// app/Mail/MemberApprovedMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class MemberApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;

    public function __construct(User $member)
    {
        $this->member = $member;
    }

    public function build()
    {
        return $this->subject('Membership Approved')
                    ->view('emails.member-approved'); // Make sure this view exists
    }
}
