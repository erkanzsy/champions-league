<?php

namespace App\Repositories\Standing;


use App\Models\Standing;
use Illuminate\Database\Eloquent\Collection;

interface StandingInterface
{
    public function getStandingByTeamId(int $id): Standing;
    public function getStandingsOrderByPointsDesc(): Collection;

}
