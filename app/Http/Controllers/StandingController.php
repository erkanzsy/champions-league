<?php

namespace App\Http\Controllers;


use App\Models\Standing;

class StandingController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $teams = Standing::all();

        return response()->json([
            'status' => 'success',
            'data' => $teams,
        ]);
    }
}
