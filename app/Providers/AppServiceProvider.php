<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mailer\Envelope;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Show awaiting approval count in admin nav
        View::composer('layouts.admin-navigation', function ($view) {
            $newMembersCount = User::where('status', 'Awaiting Approval')->count();
            $view->with('newMembersCount', $newMembersCount);
        });

        // Register SendGrid API transport
        // $apiKey = config('services.sendgrid.api_key');

        // if (!$apiKey) {
        //     throw new \RuntimeException('SendGrid API key is not set.');
        // }

        // app(MailManager::class)->extend('sendgrid', function () use ($apiKey) {
        //     return new SendgridApiTransport($apiKey);
        // });

Mail::extend('brevo', function () {
    return new class implements \Symfony\Component\Mailer\Transport\TransportInterface {
        public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
        {
            $apiKey = env('BREVO_API_KEY');

            $to = $envelope?->getRecipients()[0]->getAddress() ?? null;
            $subject = $message->getHeaders()->get('subject')?->getBody();
            $body = '';
            if ($message instanceof \Symfony\Component\Mime\Email) {
                $body = $message->getHtmlBody() ?: $message->getTextBody();
            } else {
                $body = (string) $message->getBody();
            }

            $to = [];
            foreach ($envelope?->getRecipients() ?? [] as $recipient) {
                $to[] = ['email' => $recipient->getAddress()];
            }

            // Fire the request and capture response
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'api-key' => $apiKey,
                'Accept' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name'  => env('MAIL_FROM_NAME'),
                    'email' => env('MAIL_FROM_ADDRESS'),
                ],
                'to'          => $to,
                'subject'     => $subject,
                'htmlContent' => $body,
            ]);

            // Log useful info so we can see what happened
            \Log::info('Brevo driver request', [
                'to'       => $to,
                'subject'  => $subject,
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);

            return new \Symfony\Component\Mailer\SentMessage($message, $envelope);
        }

        public function __toString(): string
        {
            return 'brevo';
        }
    };
});

    }
}
