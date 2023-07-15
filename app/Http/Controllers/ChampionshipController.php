<?php

namespace App\Http\Controllers;


use App\Models\Championship;
use App\Services\Championship\ChampionshipService;

class ChampionshipController extends Controller
{
    public function index(ChampionshipService $championshipService): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $championshipService->getChampionships(),
        ]);
    }
}
