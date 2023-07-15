<?php

namespace App\Http\Controllers;


use App\Services\Team\TeamService;

class TeamController extends Controller
{
    public function index(TeamService $service): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $service->getAllTeams(),
        ]);
    }
}
