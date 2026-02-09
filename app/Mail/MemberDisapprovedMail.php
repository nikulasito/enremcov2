<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MemberDisapprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $reason;

    public function __construct($member, $reason)
    {
        $this->member = $member;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Membership Disapproval Notice')
                    ->view('emails.member-disapproved')
                    ->with([
                        'name' => $this->member->name,
                        'reason' => $this->reason,
                    ]);
    }
}
