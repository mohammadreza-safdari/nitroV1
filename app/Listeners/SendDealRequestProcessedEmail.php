<?php

namespace App\Listeners;

use App\Events\DealRequestProcessed;
use App\Mail\DealRequestProcessedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Support\Facades\Mail;

class SendDealRequestProcessedEmail implements ShouldQueue
{
    public $afterCommit = true;
    /**
     * Handle the event.
     */
    public function handle(DealRequestProcessed $event): void
    {
        Mail::to($event->user->email)->send(
            new DealRequestProcessedMail(
                $event->deal,
                $event->requestType,
                $event->status,
            )
        );
    }

    public function middleware(): array
    {
        return [
            new RateLimited('mailtrap-emails'),
        ];
    }
}
