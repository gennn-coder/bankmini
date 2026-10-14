<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NasabahApiController;

/*
|--------------------------------------------------------------------------
| API Routes for Mobile E-Teller Bank Mini
|--------------------------------------------------------------------------
*/

Route::prefix('nasabah')->group(function () {
    Route::post('/login', [NasabahApiController::class, 'login']);
    Route::get('/dashboard/{id}', [NasabahApiController::class, 'dashboard']);
    Route::get('/mutasi/{id}', [NasabahApiController::class, 'mutasi']);
    Route::post('/lupa-sandi', [NasabahApiController::class, 'lupaSandi']);
    Route::post('/lupa-pin', [NasabahApiController::class, 'lupaPin']);
});
