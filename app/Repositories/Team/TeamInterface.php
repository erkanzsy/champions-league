<?php

namespace App\Repositories\Team;


use Illuminate\Database\Eloquent\Collection;

interface TeamInterface
{
    public function getAllTeams(): Collection;
}
