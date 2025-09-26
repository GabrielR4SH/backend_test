<?php

use App\Http\Controllers\Api\RedirectController;
use Illuminate\Support\Facades\Route;

Route::apiResource('redirects', RedirectController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::get('redirects/{code}/stats', [RedirectController::class, 'stats'])->name('redirects.stats');
