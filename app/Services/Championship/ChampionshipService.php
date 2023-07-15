<?php

namespace App\Services\Championship;

use App\Models\Championship;
use App\Models\Fixture;
use App\Repositories\Championship\ChampionshipRepository;
use App\Repositories\Fixture\FixtureRepository;
use App\Repositories\Standing\StandingRepository;
use App\Services\Standing\StandingService;

class ChampionshipService
{


    public function __construct(
        protected ChampionshipRepository $championshipRepository,
        protected StandingRepository $standingRepository,
        protected FixtureRepository $fixtureRepository
    )
    {
    }

    public function getChampionships()
    {
        return $this->championshipRepository->getChampionshipOrderedByPrediction(5);
    }

    public function updateChampionships(Fixture $match)
    {
        $standings = $this->standingRepository->getStandingsOrderByPointsDesc();

        $week = $match->week;
        $weekCount = ($standings->count() - 1) * 2;
        $leftWeek = $weekCount - $week;
        $firstTeam  = $standings->first();

        if ($leftWeek > 2)
        {
            return;
        }

        $firstTeamPoint   = $firstTeam->points;
        $possibleMaxPoint = StandingService::POINT_WINS * $leftWeek;

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
