<?php

namespace App\Listeners;

use App\Events\MatchPlayed;
use App\Models\Team;
use App\Repositories\Fixture\FixtureRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use function PHPUnit\Framework\matches;

class UpdatePrediction
{
    const WEEK_COUNT = 5;

    /**
     * Create the event listener.
     */
    public function __construct(protected FixtureRepository $fixtureRepository)
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

        $matchesOnNextWeek = $this->fixtureRepository->getFixtureByWeek($week + 1);

        foreach ($matchesOnNextWeek as $_match)
        {
            $homeRate = $this->calculateTeamRate($match->home, $week);
            $awayRate = $this->calculateTeamRate($match->away, $week);

            $_match->home_rate = $homeRate;
            $_match->away_rate = $awayRate;

            $_match->save();
        }
    }

    private function calculateTeamRate(Team $team, int $week)
    {
        $matches = $this->fixtureRepository->getWonMatchByTeamAndWeek($team->id, $week - self::WEEK_COUNT, $week);

        return (($matches->count() / self::WEEK_COUNT * 100 * 20) + ($team->strength * 80)) / 100;
    }
}
