<?php

namespace App\Services\Fixture;

use App\Events\MatchPlayed;
use App\Models\Fixture;
use App\Models\Team;
use App\Repositories\Fixture\FixtureRepository;

class FixtureService
{
    /**
     * @param FixtureRepository $fixtureInterface
     */
    public function __construct(protected FixtureRepository $fixtureInterface)
    {
    }

    public function play(int $week)
    {
        $matches = $this->fixtureInterface->getFixtureByWeek($week);

        if ($matches->isEmpty())
        {
            throw new \Exception("Week is not eligible!");
        }

        /** @var Fixture $match */
        foreach ($matches as $match)
        {
            $this->playMatch($match);
        }
    }

    private function playMatch(Fixture $fixture)
    {
        $homeScore = $this->calculateScore($fixture->home_rate);
        $awayScore = $this->calculateScore($fixture->away_rate);

        $fixture->home_score = $homeScore;
        $fixture->away_score = $awayScore;

        MatchPlayed::dispatch($fixture);

        if ($homeScore > $awayScore)
        {
            $fixture->winner = $fixture->home->id;
        } elseif ($awayScore > $homeScore)
        {
            $fixture->winner = $fixture->away->id;
        }

        $fixture->save();
    }

    private function calculateScore(float $rate)
    {
        return rand(0, 5) + (int)($rate / 100 * 5);
    }
}
