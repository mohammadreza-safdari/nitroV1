<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use App\Events\DealRequestProcessed;
use App\Listeners\SendDealRequestProcessedEmail;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            DealRequestProcessed::class,
            SendDealRequestProcessedEmail::class,
        );
        RateLimiter::for('mailtrap-emails', function (object $job) {
            return Limit::perMinute(1);
        });
    }
}
