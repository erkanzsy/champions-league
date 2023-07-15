<?php

namespace App\Http\Controllers;


use App\Models\Championship;

class ChampionshipController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $teams = Championship::orderBy('prediction', 'desc')->take(5)->get();

        return response()->json([
            'status' => 'success',
            'data' => $teams,
        ]);
    }
}
