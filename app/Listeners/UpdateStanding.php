<?php

namespace App\Listeners;

use App\Events\MatchPlayed;
use App\Repositories\Standing\StandingRepository;

class UpdateStanding
{
    /**
     * Create the event listener.
     */
    public function __construct(protected StandingRepository $standingRepository)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MatchPlayed $event): void
    {
        $match = $event->fixture;

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

            $homeStanding->points += 3;
        } elseif ($homeScore < $awayScore)
        {
            $awayStanding->wins += 1;
            $homeStanding->losses += 1;

            $awayStanding->points += 3;
        } else {
            $awayStanding->draws += 1;
            $homeStanding->draws += 1;

            $homeStanding->points += 1;
            $awayStanding->points += 1;
        }

        $homeStanding->save();
        $awayStanding->save();
    }
}
