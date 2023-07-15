<?php

namespace App\Listeners;

use App\Events\MatchPlayed;
use App\Services\Championship\ChampionshipService;

class UpdateChampionship
{
    /**
     * Create the event listener.
     */
    public function __construct(protected ChampionshipService $championshipService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MatchPlayed $event): void
    {
        $match = $event->fixture;

        $this->championshipService->updateChampionships($match);
    }

}
