<?php

namespace App\Repositories\Championship;


use App\Models\Championship;
use Illuminate\Database\Eloquent\Collection;

class ChampionshipRepository implements ChampionshipInterface
{
    public function getChampionshipOrderedByPrediction(int $count): Collection
    {
        return Championship::orderBy('prediction', 'desc')->take(5)->get();
    }
}
