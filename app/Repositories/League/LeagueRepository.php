<?php

namespace App\Repositories\League;

use App\Models\League;

class LeagueRepository implements LeagueInterface
{
    public function getLeagueById(int $id): League
    {
        return League::where(['id' => $id])->first();
    }
}
