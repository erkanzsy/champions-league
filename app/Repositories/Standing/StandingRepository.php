<?php

namespace App\Repositories\Standing;


use App\Models\Standing;
use Illuminate\Database\Eloquent\Collection;

class StandingRepository implements StandingInterface
{
    public function getStandingByTeamId(int $id): Standing
    {
        return Standing::where(['team_id' => $id])->first();
    }

    public function getStandingsOrderByPointsDesc(): Collection
    {
        return Standing::orderBy('points', 'desc')->get();
    }
}
