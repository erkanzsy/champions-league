<?php

namespace App\Console\Commands;

use App\Services\League\LeagueService;
use Illuminate\Console\Command;

class PrepareLeagueCommand extends Command
{
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

    public function __construct(protected LeagueService $leagueService)
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
       $this->leagueService->create();
    }
}
