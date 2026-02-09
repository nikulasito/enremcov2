<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\View;
use Illuminate\Notifications\Messages\MailMessage;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */

    public function boot(): void
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            // Generate the signed verification URL (valid for 60 minutes)
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(60),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            // Render a Blade email with button
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Verify Your Email Address')
                ->view('emails.verify-email', [
                    'member' => $notifiable,
                    'verificationUrl' => $verificationUrl,
                ]);
        });
    }
}
