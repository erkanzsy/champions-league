<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/team', [\App\Http\Controllers\TeamController::class, 'index'])->name('team.index');

Route::get('/fixture', [\App\Http\Controllers\FixtureController::class, 'index']);
Route::get('/fixture/reset', [\App\Http\Controllers\FixtureController::class, 'reset']);
Route::get('/fixture/play/all', [\App\Http\Controllers\FixtureController::class, 'playAll']);
Route::get('/fixture/play/{week}', [\App\Http\Controllers\FixtureController::class, 'playWeek']);

Route::get('/standing', [\App\Http\Controllers\StandingController::class, 'index']);

Route::get('/league', [\App\Http\Controllers\LeagueController::class, 'index']);

Route::get('/championship', [\App\Http\Controllers\ChampionshipController::class, 'index']);
