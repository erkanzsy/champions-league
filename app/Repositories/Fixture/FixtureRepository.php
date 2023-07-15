<?php

namespace App\Repositories\Fixture;

use App\Models\Fixture;
use Illuminate\Database\Eloquent\Collection;

class FixtureRepository implements FixtureInterface
{

    public function getFixtureByWeek(int $week): Collection
    {
        return Fixture::where(['week' => $week])->get();
    }

    public function getUnPlayedFixturesOrderByWeek(): Collection
    {
        return Fixture::whereNull('home_score')
            ->orderBy('week')
            ->get();
    }

    public function getWonMatchByTeamAndWeek(int $teamId, int $start, int $end): Collection
    {
        return Fixture::where(['winner' => $teamId])
            ->where(function ($query) use ($start, $end) {
                $query->where('week', '>', $start)
                    ->where('week', '<', $end);
            })
            ->get();
    }
}
