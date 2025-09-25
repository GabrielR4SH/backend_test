<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RedirectController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('redirects', RedirectController::class);  // CRUD /api/redirects
Route::get('redirects/{redirect}/stats', [RedirectController::class, 'stats']);  // Stats
Route::get('redirects/{redirect}/logs', [RedirectController::class, 'logs']);  // Logs
