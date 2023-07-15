<?php

namespace App\Repositories\Standing;


use App\Models\Standing;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StandingRepository implements StandingInterface
{
    public function getStandingByTeamId(int $id): Standing
    {
        return Standing::where(['team_id' => $id])->first();
    }

    public function getStandingsOrderByPointsDesc(): Collection
    {
        return Standing::orderBy('points', 'desc')->get();
    }

    public function getStandingsIdsByPointsBetween(int $start, int $end): array
    {
        return Standing::where(function ($query) use ($start, $end) {
            $query->where('points', '>=', $start)
                ->where('points', '<', $end);
        })->pluck('id')->toArray();
    }

    public function getStandingsIdsNotIn(array $ids): Collection
    {
        return Standing::whereNotIn('team_id', $ids)->get();
    }


    public function getPointsAndTeamsWhereNotIn(array $ids)
    {
        return DB::table('standings')
            ->whereNotIn('team_id', $ids)
            ->pluck('points', 'team_id')
            ->toArray();
    }
}
