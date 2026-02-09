<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class SendWelcomeEmail implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // prevent another job with same uniqueId for 10 minutes
    public $uniqueFor = 600;

    public function uniqueId(): string
    {
        return (string) $this->userId;
    }

    public function __construct(public int $userId) {}

    public function handle(): void
    {

        \Log::info('SendWelcomeEmail start', ['userId' => $this->userId]);

        $user = User::find($this->userId);
        if (!$user) return;

        $apiKey = env('BREVO_API_KEY');
        if (empty($apiKey)) {
            Log::error('BREVO_API_KEY is missing; cannot send email.');
            return;
        }
        Log::info('BREVO key present (masked).');

        // 1) Render your existing registration email view
        // You have: resources/views/emails/registration.blade.php
        $emailHtml = view('emails.registration', [
            'user'   => $user,
            'member' => $user,
        ])->render();

        // 2) Base payload for Brevo
        $payload = [
            'sender' => [
                'name'  => env('MAIL_FROM_NAME', 'ENREMCO'),
                'email' => env('MAIL_FROM_ADDRESS', 'support@enremco.com'),
            ],
            'to' => [[ 'email' => $user->email, 'name' => $user->name ]],
            'subject' => 'Welcome to ENREMCO',
            'htmlContent' => $emailHtml,
        ];

        // 3) Generate PDF via wkhtmltopdf from a Blade view
        try {
            $wk = env('WKHTMLTOPDF_PATH', '/usr/local/bin/wkhtmltopdf');

            // PDF body
            $pdfHtml = view('pdf.welcome', [
                'user'   => $user,
                'member' => $user,
            ])->render();

            $tmpDir  = storage_path('app/tmp');
            if (!is_dir($tmpDir)) { @mkdir($tmpDir, 0775, true); }

            $htmlPath = "{$tmpDir}/welcome-{$user->id}.html";
            $pdfPath  = "{$tmpDir}/welcome-{$user->id}.pdf";

            File::put($htmlPath, $pdfHtml);

            // Convert local HTML file to PDF
            $cmd = sprintf(
                '%s --quiet %s %s 2>&1',
                escapeshellcmd($wk),
                escapeshellarg($htmlPath),
                escapeshellarg($pdfPath)
            );
            exec($cmd, $out, $code);

            if ($code === 0 && file_exists($pdfPath)) {
                $payload['attachment'] = [[
                    'name'    => "welcome-{$user->id}.pdf",
                    'content' => base64_encode(file_get_contents($pdfPath)),
                ]];
            } else {
                Log::error('wkhtmltopdf failed', ['code' => $code, 'out' => $out]);
            }

            @unlink($htmlPath);
            @unlink($pdfPath);
        } catch (\Throwable $e) {
            Log::error('PDF generation exception', ['err' => $e->getMessage()]);
        }

        // 4) Send via Brevo HTTPS API (no SMTP)
        $resp = Http::timeout(20)->withHeaders([
            'api-key'      => $apiKey,
            'accept'       => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', $payload);

        if (!$resp->successful()) {
            Log::error('Brevo API (welcome) failed', ['status' => $resp->status(), 'body' => $resp->body()]);
        }


        if ($resp->successful()) {
            \Log::info('SendWelcomeEmail ok', ['status' => $resp->status()]);
        } else {
            \Log::error('SendWelcomeEmail fail', ['status' => $resp->status(), 'body' => $resp->body()]);
        }

    }
}
