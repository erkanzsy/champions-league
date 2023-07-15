<?php

namespace App\Repositories\League;


use App\Models\League;
use Illuminate\Database\Eloquent\Collection;

interface LeagueInterface
{
    public function getLeagueById(int $id): League;

    public function getAll(int $id): Collection;
}
