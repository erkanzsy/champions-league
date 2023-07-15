<?php

namespace App\Repositories\League;


use App\Models\League;

interface LeagueInterface
{
    public function getLeagueById(int $id): League;
}
