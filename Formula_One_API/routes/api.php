<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Middleware\EnsureTokenCanCrud;
use Illuminate\Support\Facades\Route;

Route::post('tokens/guest', [AuthController::class, 'guestToken']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
	Route::get('teams', [TeamController::class, 'index']);
	Route::get('teams/{team}', [TeamController::class, 'show']);
	Route::get('drivers', [DriverController::class, 'index']);
	Route::get('drivers/{driver}', [DriverController::class, 'show']);
});

Route::middleware(['auth:sanctum', EnsureTokenCanCrud::class])->group(function (): void {
	Route::post('teams', [TeamController::class, 'store']);
	Route::put('teams/{team}', [TeamController::class, 'update']);
	Route::patch('teams/{team}', [TeamController::class, 'update']);
	Route::delete('teams/{team}', [TeamController::class, 'destroy']);

	Route::post('drivers', [DriverController::class, 'store']);
	Route::put('drivers/{driver}', [DriverController::class, 'update']);
	Route::patch('drivers/{driver}', [DriverController::class, 'update']);
	Route::delete('drivers/{driver}', [DriverController::class, 'destroy']);

	Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('me', [AuthController::class, 'me']);
