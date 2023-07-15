<?php

namespace App\Http\Controllers;


use App\Models\League;

class LeagueController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $teams = League::all();

        return response()->json([
            'status' => 'success',
            'data' => $teams,
        ]);
    }
}
