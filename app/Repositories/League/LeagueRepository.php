<?php

namespace App\Repositories\League;

use App\Models\League;
use Illuminate\Database\Eloquent\Collection;

class LeagueRepository implements LeagueInterface
{
    public function getLeagueById(int $id): League
    {
        return League::where(['id' => $id])->first();
    }

    public function getAll(int $id): Collection
    {
        return League::all();
    }
}
