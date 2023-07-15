<?php

namespace App\Repositories\Standing;


use App\Models\Standing;

class StandingRepository implements StandingInterface
{
    public function getStandingByTeamId(int $id): Standing
    {
        return Standing::where(['team_id' => $id])->first();
    }
}
