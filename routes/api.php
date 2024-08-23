<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoyageController;
use App\Http\Controllers\VesselOpexController;
use App\Http\Controllers\VesselController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/voyages', [VoyageController::class, 'store']);
Route::put('/voyages/{voyage}', [VoyageController::class, 'update']);
Route::post('/vessels/{vessel}/vessel-opex', [VesselOpexController::class, 'store']);
Route::get('/vessels/{vessel}/financial-report', [VesselController::class, 'financialReport']);
