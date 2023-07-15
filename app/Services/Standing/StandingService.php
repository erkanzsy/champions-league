<?php

namespace App\Services\Standing;

use App\Models\Fixture;
use App\Repositories\Standing\StandingRepository;

class StandingService
{
    const POINT_WINS = 3;

    const POINT_DRAWS = 1;

    const POINT_LOSSES = 0;

    public function __construct(protected StandingRepository $standingRepository)
    {
    }

    public function getStandingsOrderByPointsDesc()
    {
        return $this->standingRepository->getStandingsOrderByPointsDesc();
    }

    public function updateStanding(Fixture $match)
    {
        $homeScore = $match->home_score;
        $awayScore = $match->away_score;

        $diff = $homeScore - $awayScore;

        $homeStanding = $this->standingRepository->getStandingByTeamId($match->home_id);
        $awayStanding = $this->standingRepository->getStandingByTeamId($match->away_id);

        $homeStanding->played += 1;
        $awayStanding->played += 1;

        $homeStanding->goals_for += $homeScore;
        $homeStanding->goals_against += $awayScore;
        $homeStanding->goal_difference += $diff;

        $awayStanding->goals_for += $awayScore;
        $awayStanding->goals_against += $homeScore;
        $awayStanding->goal_difference -= $diff;

        if ($homeScore > $awayScore)
        {
            $homeStanding->wins += 1;
            $awayStanding->losses += 1;

            $homeStanding->points += self::POINT_WINS;
        } elseif ($homeScore < $awayScore)
        {
            $awayStanding->wins += 1;
            $homeStanding->losses += 1;

            $awayStanding->points += self::POINT_WINS;
        } else {
            $awayStanding->draws += 1;
            $homeStanding->draws += 1;

            $homeStanding->points += self::POINT_DRAWS;
            $awayStanding->points += self::POINT_DRAWS;
        }

        $homeStanding->save();
        $awayStanding->save();
    }

}
