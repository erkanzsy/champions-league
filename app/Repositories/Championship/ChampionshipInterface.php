<?php

namespace App\Repositories\Championship;


use Illuminate\Database\Eloquent\Collection;

interface ChampionshipInterface
{
    public function getChampionshipOrderedByPrediction(int $count): Collection;
}
