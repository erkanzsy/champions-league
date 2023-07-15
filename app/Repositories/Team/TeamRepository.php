<?php

namespace App\Repositories\Team;


use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository implements TeamInterface
{
    public function getAllTeams(): Collection
    {
        return Team::all();
    }
}
