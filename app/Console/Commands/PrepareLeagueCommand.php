<?php

namespace App\Console\Commands;

use App\Models\Championship;
use App\Models\Fixture;
use App\Models\League;
use App\Models\Standing;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class PrepareLeagueCommand extends Command
{
    const LEAGUE = "Süper Lig";
    const TEAMS = [
        "galatasaray" => 77,
        "besiktas"    => 67,
        "trabzon"     => 60,
        "basaksehir"  => 57,
        #"sivasspor"   => 50,
        #"konyaspor"   => 47,
        #"antalyaspor" => 43,
        #"fenerbahce"  => 30,
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prepare-league-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $league = $this->createLeague();
        $teams = $this->createTeams();
        $this->createStanding($league, $teams);
        $this->createChampionship($league, $teams);

        $_teams = array_keys($teams);
        $firstPeriod = $this->createPeriod($_teams);
        $secondPeriod = $this->createPeriod($_teams, $firstPeriod);

        $orderedFirstPeriod = $this->orderByWeek($_teams, $firstPeriod);
        $orderedSecondPeriod = $this->orderByWeek($_teams, $secondPeriod);

        $matches = $this->createFixture($league, $teams, array_merge($orderedFirstPeriod, $orderedSecondPeriod));

        $this->predictFirstWeek($matches, $teams);
    }

    private function createPeriod(array $teams, array $firstPeriod = [])
    {
        $matches = [];
        foreach ($teams as $home) {
            $otherTeams = array_values(array_diff($teams, [$home]));
            shuffle($otherTeams);

            foreach ($otherTeams as $away) {
                $_home = $home;
                $_away = $away;

                if (rand(0, 1)) {
                    $_home = $away;
                    $_away = $home;
                }

                $match = [$_home, $_away];

                if (in_array($match, $matches) || in_array(array_reverse($match), $matches)) {
                    continue;
                }

                if (in_array($match, $firstPeriod)) {
                    $match = array_reverse($match);
                }

                $matches[] = $match;
            }
        }

        return $matches;
    }

    private function orderByWeek(array $teams, array $matches)
    {
        shuffle($matches);
        $weeks = [];
        $processed = [];

        for ($x = 0; $x < count($teams); $x++) {
            $playedByTeams = [];

            foreach ($matches as $_match) {
                if (in_array($_match, $processed)) {
                    continue;
                }

                if (empty(array_intersect($playedByTeams, $_match))) {
                    $processed[] = $_match;
                    $weeks[$x][] = $_match;

                    $playedByTeams = array_merge($playedByTeams, $_match);
                }
            }

        }

        return $weeks;
    }

    private function createTeams(): array
    {
        $teams = [];
        foreach (self::TEAMS as $name => $strength)
        {
            $_team = Team::where('name', $name)->first();

            if (! $_team)
            {
                $_team = Team::create([
                    'name' => $name,
                    'strength' => $strength,
                ]);
            }

            $teams[$name] = $_team;
        }

        return $teams;
    }

    private function createLeague(): League
    {
        $league = League::where('name', self::LEAGUE)->first();

        if (! $league)
        {
            $league = League::create([
                'name' => self::LEAGUE,
            ]);
        }

        return $league;
    }

    private function createFixture(League $league, array $teams, array $weeks): Collection
    {
        $_matches = new Collection();
        foreach ($weeks as $week => $matches)
        {
            foreach ($matches as $match)
            {
                /** @var Team $home */
                $home = $teams[$match[0]];

                /** @var Team $away */
                $away = $teams[$match[1]];

                $_match = Fixture::where(['home_id' => $home->id, 'away_id' => $away->id])->first();

                if (! $_match)
                {
                    $_match = Fixture::create([
                        'league_id' => $league->id,
                        'home_id'   => $home->id,
                        'away_id'   => $away->id,
                        'date'   => Carbon::parse('2023-07-15'),
                        'term'   => 2024,
                        'week'   => $week + 1,
                    ]);
                }

                $_matches->add($_match);
            }
        }

        return $_matches;
    }

    private function createStanding(League $league, array $teams)
    {
        foreach ($teams as $name => $team)
        {
            $_standing = Standing::where('team_id', $team->id)->first();

            if (! $_standing)
            {
                $_standing = Standing::create([
                    'league_id' => $league->id,
                    'team_id' => $team->id,
                    'term' => 2024,
                ]);
            }
        }
    }

    private function createChampionship(League $league, array $teams)
    {
        /** @var Team $team */
        foreach ($teams as $name => $team)
        {
            $_championship = Championship::where('team_id', $team->id)->first();

            if (! $_championship)
            {
                $_championship = Championship::create([
                    'league_id' => $league->id,
                    'team_id' => $team->id,
                    'term' => 2024,
                    'prediction' => 0,
                ]);
            }
        }
    }

    private function predictFirstWeek(Collection $matches, array $teams)
    {
        $firstWeekMatches = $matches->where('week', 1);

        /** @var Fixture $match */
        foreach ($firstWeekMatches as $match)
        {
            $match->home_rate = $teams[$match->home->name]->strength;
            $match->away_rate = $teams[$match->away->name]->strength;

            $match->save();
        }
    }
}
