<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $pdfContent;

    public function __construct($member, $pdfContent)
    {
        $this->member = $member;
        $this->pdfContent = $pdfContent;
    }

    public function build()
    {
        return $this->subject('Your Registration Details')
                    ->html(view('emails.registration', ['member' => $this->member])->render())
                    ->text('emails.registration_plain') // optional plain text version
                    ->with(['member' => $this->member]);
    }
}
