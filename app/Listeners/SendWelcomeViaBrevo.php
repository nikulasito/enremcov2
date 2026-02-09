<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Support\Facades\Log;

class SendWelcomeViaBrevo
{
    public function handle(Registered $event): void
    {
        Log::info('Registered event caught', ['user_id' => $event->user->id]);

        SendWelcomeEmail::dispatch($event->user->id);
    }
}
