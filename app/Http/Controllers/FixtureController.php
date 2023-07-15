<?php

namespace App\Http\Controllers;


use App\Models\Fixture;
use App\Services\Fixture\FixtureService;
use Illuminate\Support\Facades\Artisan;

class FixtureController extends Controller
{
    public function __construct(protected FixtureService $fixture)
    {
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $teams = Fixture::all();

        return response()->json([
            'status' => 'success',
            'data' => $teams,
        ]);
    }

    public function playAll(): \Illuminate\Http\JsonResponse
    {
        $this->fixture->playAll();

        return response()->json([
            'status' => 'success',
            'data' => [],
        ]);
    }

    public function reset(): \Illuminate\Http\JsonResponse
    {
        Artisan::call('migrate:reset');
        Artisan::call('migrate');
        Artisan::call('app:prepare-league-command');

        return response()->json([
            'status' => 'success',
            'data' => [],
        ]);
    }

    public function playWeek(int $week): \Illuminate\Http\JsonResponse
    {
        $this->fixture->play($week);

        $matchesOnWeek = Fixture::where(['week' => $week])->get();

        return response()->json([
            'status' => 'success',
            'data' => $matchesOnWeek,
        ]);
    }
}
