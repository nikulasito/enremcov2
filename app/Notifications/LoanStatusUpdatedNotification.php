<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LoanStatusUpdatedNotification extends Notification
{
    use Queueable;

    public LoanApplication $application;

    public function __construct(LoanApplication $application)
    {
        $this->application = $application;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Loan Application Update',
            'application_id' => $this->application->id,
            'application_no' => $this->application->application_no,
            'status' => $this->application->status,
            'loan_type' => $this->application->loan_type,
            'loan_amount' => (float) $this->application->loan_amount,
            'reviewed_at' => optional($this->application->reviewed_at)->toDateTimeString(),
        ];
    }
}
