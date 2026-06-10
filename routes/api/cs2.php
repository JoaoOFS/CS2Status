<?php

use App\Http\Controllers\Api\Cs2\Events\EventController;
use App\Http\Controllers\Api\Cs2\Matches\MatchController;
use App\Http\Controllers\Api\Cs2\Stages\StageController;
use App\Http\Controllers\Api\Cs2\Standings\StandingController;
use App\Http\Controllers\Api\Cs2\Teams\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('events', [EventController::class, 'index']);
Route::get('events/{event}', [EventController::class, 'show']);

Route::get('events/{event}/stages', [StageController::class, 'index']);

Route::get('stages/{stage}/teams', [TeamController::class, 'index']);
Route::get('stages/{stage}/matches', [MatchController::class, 'index']);
Route::get('stages/{stage}/standings', [StandingController::class, 'show']);

Route::get('matches/{match}', [MatchController::class, 'show']);
