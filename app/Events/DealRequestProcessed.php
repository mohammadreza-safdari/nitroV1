<?php

namespace App\Events;

use App\Models\Sales\Deal;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

class DealRequestProcessed implements ShouldDispatchAfterCommit
{
    use Dispatchable, SerializesModels;
    public function __construct(
        public Deal $deal,
        public User $user,
        public string $requestType,
        public string $status,
    ) {}
}
