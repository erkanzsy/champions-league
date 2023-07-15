<?php

namespace App\Http\Controllers;


use App\Models\Team;

class TeamController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $teams = Team::all();

        return response()->json([
            'status' => 'success',
            'data' => $teams,
        ]);
    }
}
