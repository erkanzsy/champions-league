<?php

namespace App\Listeners;

use App\Events\MatchPlayed;
use App\Services\Standing\StandingService;

class UpdateStanding
{

    /**
     * Create the event listener.
     */
    public function __construct(protected StandingService $standingService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MatchPlayed $event): void
    {
        $match = $event->fixture;

        $this->standingService->updateStanding($match);
    }
}
