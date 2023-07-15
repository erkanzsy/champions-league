<?php

namespace App\Services\Team;

use App\Repositories\Team\TeamRepository;

class TeamService
{
    public function __construct(protected TeamRepository $repository)
    {
    }

    public function getAllTeams()
    {
        return $this->repository->getAllTeams();
    }
}
