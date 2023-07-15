<?php

namespace App\Listeners;

use App\Events\MatchPlayed;
use App\Models\Championship;
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

        $standings = $this->standingRepository->getStandingsOrderByPointsDesc();

        $week = $match->week;
        $weekCount = ($standings->count() - 1) * 2;
        $leftWeek = $weekCount - $week;
        $firstTeam  = $standings->first();

        if ($leftWeek < 1)
        {
            Championship::where('team_id', '!=',$firstTeam->team_id)->update(['prediction' => 0]);
            Championship::where('team_id', '=',$firstTeam->team_id)->update(['prediction' => 100]);

            return;
        } elseif ($leftWeek > 2)
        {
            return;
        }


        $firstTeamPoint   = $firstTeam->points;
        $possibleMaxPoint = UpdateStanding::POINT_WINS * $leftWeek;

        $otherIds = $this->standingRepository->getStandingsIdsByPointsBetween(0, $firstTeamPoint - $possibleMaxPoint);

        Championship::whereIn('team_id',$otherIds)->update(['prediction' => 0]);

        $possibleTeamsAndTotalPoints = $this->standingRepository->getPointsAndTeamsWhereNotIn($otherIds);

        $totalPoints = array_sum(array_values($possibleTeamsAndTotalPoints));

        foreach ($possibleTeamsAndTotalPoints as $teamId => $points)
        {
            Championship::where('team_id', '=', $teamId)->update(['prediction' => $points / $totalPoints * 100]);
        }
    }

}
