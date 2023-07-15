<?php

namespace App\Services\Standing;

use App\Repositories\Standing\StandingRepository;

class StandingService
{
    public function __construct(protected StandingRepository $repository)
    {
    }

    public function getStandingsOrderByPointsDesc()
    {
        return $this->repository->getStandingsOrderByPointsDesc();
    }


}
