<?php

use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\TeamController;
use Illuminate\Support\Facades\Route;

Route::apiResource('teams', TeamController::class);
Route::apiResource('drivers', DriverController::class);
