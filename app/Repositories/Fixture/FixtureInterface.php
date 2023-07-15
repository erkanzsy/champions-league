<?php

namespace App\Repositories\Fixture;


use Illuminate\Database\Eloquent\Collection;

interface FixtureInterface
{
    public function getFixtureByWeek(int $week): Collection;
}
