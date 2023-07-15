<?php

namespace App\Http\Controllers;


use App\Models\Standing;

class StandingController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $teams = Standing::orderBy('points', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $teams,
        ]);
    }
}
