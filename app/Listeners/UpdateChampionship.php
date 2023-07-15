<?php

namespace App\Listeners;

use App\Events\MatchPlayed;
use App\Repositories\Fixture\FixtureRepository;
use App\Repositories\Standing\StandingRepository;

class UpdateChampionship
{
    /**
     * Create the event listener.
     */
    public function __construct(protected StandingRepository $standingRepository, protected FixtureRepository $fixtureRepository)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MatchPlayed $event): void
    {
        $match = $event->fixture;

        $week = $match->week;
        $standings = $this->standingRepository->getStandingsOrderByPointsDesc();
        $weekCount = ($standings->count() - 1) * 2;
        $leftWeek = $weekCount - $week;

        if ($leftWeek > 3)
        {
            #return;
        }

        $firstTeam  = $standings->first();
        $secondTeam = $standings->first();

        $firstTeamPoint = $firstTeam->points;
        $secondTeamPoint = $secondTeam->points;

        if ($firstTeamPoint > ($secondTeamPoint + (UpdateStanding::POINT_WINS * $leftWeek)))
        {
            // hesaplama
            // firsttime %100 other 0
        } else {

        }
    }

}
