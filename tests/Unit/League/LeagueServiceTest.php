<?php

namespace Tests\Unit\League;

use App\Services\League\LeagueService;
use PHPUnit\Framework\TestCase;

class LeagueServiceTest extends TestCase
{
    protected LeagueService $leagueService;

    public function testCreateMethod()
    {
        $teams = ['Team A', 'Team B', 'Team C', 'Team D'];

        $leagueService = new LeagueService();

        $firstPeriod = $leagueService->createPeriod($teams);
        $secondPeriod = $leagueService->createPeriod($teams, $firstPeriod);

        $fixture = array_merge($firstPeriod, $secondPeriod);

        $this->assertEquals(6, count($firstPeriod));
        $this->assertEquals(6, count($secondPeriod));
        $this->assertEquals(12, count($fixture));

        $allPossibleMatches = json_decode('[["Team A","Team B"],["Team A","Team C"],["Team A","Team D"],["Team B","Team D"],["Team C","Team B"],["Team C","Team D"],["Team D","Team A"],["Team B","Team A"],["Team C","Team A"],["Team B","Team C"],["Team D","Team B"],["Team D","Team C"]]', true);

        foreach ($allPossibleMatches as $match)
        {
            $this->assertTrue(in_array($match, $fixture));
        }
    }


}
