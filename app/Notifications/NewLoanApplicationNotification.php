<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewLoanApplicationNotification extends Notification
{
    use Queueable;

    public function __construct(public LoanApplication $loan)
    {
    }

    public function via($notifiable): array
    {
        // database works even if you have no mail config yet
        return ['mail'];
        // If you want email too later: return ['database','mail'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'New Loan Application',
            'application_no' => $this->loan->application_no,
            'loan_type' => $this->loan->loan_type,
            'loan_amount' => $this->loan->loan_amount,
            'member_name' => $this->loan->full_name,
            'user_id' => $this->loan->user_id,
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Loan Application Submitted')
            ->line("Application: {$this->loan->application_no}")
            ->line("Member: {$this->loan->full_name}")
            ->line("Type: {$this->loan->loan_type}")
            ->line("Amount: ₱" . number_format($this->loan->loan_amount, 2));
    }
}
