<?php

namespace App\Http\Controllers;


use App\Services\Standing\StandingService;

class StandingController extends Controller
{
    public function index(StandingService $standingService): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $standingService->getStandingsOrderByPointsDesc(),
        ]);
    }
}
