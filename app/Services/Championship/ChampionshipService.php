<?php

namespace App\Services\Championship;

use App\Repositories\Championship\ChampionshipRepository;

class ChampionshipService
{


    public function __construct(protected ChampionshipRepository $championshipRepository)
    {
    }

    public function getChampionships()
    {
        return $this->championshipRepository->getChampionshipOrderedByPrediction(5);
    }
}
