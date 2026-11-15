<?php

use App\Http\Controllers\Api\ShopFloorController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/login', [ShopFloorController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/work-orders', [ShopFloorController::class, 'orders']);
        Route::post('/stages/{stage}/start', [ShopFloorController::class, 'start']);
        Route::post('/stages/{stage}/complete', [ShopFloorController::class, 'complete']);
        Route::post('/stages/{stage}/issues', [ShopFloorController::class, 'issue']);
    });
});
