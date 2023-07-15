<?php

namespace App\Listeners;

use App\Events\MatchPlayed;
use App\Models\Team;
use App\Repositories\Fixture\FixtureRepository;
use App\Repositories\Team\TeamRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use function PHPUnit\Framework\matches;

class UpdateChampionship
{
    /**
     * Create the event listener.
     */
    public function __construct(protected TeamRepository $teamRepository)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MatchPlayed $event): void
    {
        $match = $event->fixture;

        $weekLeft = $match->week;

    }

}
