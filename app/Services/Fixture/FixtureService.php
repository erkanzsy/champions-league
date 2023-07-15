<?php

namespace App\Services\Fixture;

use App\Events\MatchPlayed;
use App\Models\Fixture;
use App\Models\Team;
use App\Repositories\Fixture\FixtureRepository;
use Symfony\Component\VarDumper\VarDumper;

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

    public function playAll()
    {
        $matches = $this->fixtureInterface->getUnPlayedFixturesOrderByWeek();

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

    private function playMatch(Fixture $match)
    {
        $match->refresh();

        $homeScore = $this->calculateScore($match->home_rate);
        $awayScore = $this->calculateScore($match->away_rate);

        $match->home_score = $homeScore;
        $match->away_score = $awayScore;

        MatchPlayed::dispatch($match);

        if ($homeScore > $awayScore)
        {
            $match->winner = $match->home->id;
        } elseif ($awayScore > $homeScore)
        {
            $match->winner = $match->away->id;
        }

        $match->save();
    }

    private function calculateScore(float $rate)
    {
        return rand(0, 5) + (int)($rate / 100 * 5);
    }
}
