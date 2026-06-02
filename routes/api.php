<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AtmController;
use App\Http\Controllers\TorniqueteController;
use App\Http\Controllers\EntradaController;

Route::apiResource('atms', AtmController::class);
Route::post('atms/{atm}/open', [AtmController::class, 'open']);

Route::apiResource('torniquetes', TorniqueteController::class);

// Allow registering an entrada including atm and allowed_by_user
Route::post('entradas/register', [EntradaController::class, 'store']);
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

