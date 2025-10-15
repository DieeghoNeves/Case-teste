<?php

use App\Http\Controllers\Api\V1\OrderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/ping', function () {
    return response()->json(['message' => 'API online'], 200);
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);

    Route::post('/orders', [OrderController::class, 'store']);

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->whereNumber('order'); 

    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])
        ->whereNumber('order');
});
