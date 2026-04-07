<?php

namespace App\Mail;

use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $member;
    public LoanApplication $application;

    public function __construct(User $member, LoanApplication $application)
    {
        $this->member = $member;
        $this->application = $application;
    }

    public function build()
    {
        return $this
            ->subject('Your Loan Application Has Been Approved')
            ->view('emails.loan-approved');
    }
}
