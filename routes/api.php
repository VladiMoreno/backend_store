<?php

use App\Http\Controllers\Api\v1\CartsController;
use App\Http\Controllers\Api\v1\ProductsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/products', [ProductsController::class, 'index']);
    Route::post('/products', [ProductsController::class, 'store']);
    Route::get('/products/{id}', [ProductsController::class, 'show']);
    Route::put('/products/{id}', [ProductsController::class, 'update']);
    Route::delete('/products/{id}', [ProductsController::class, 'destroy']);

    Route::get('/carts', [CartsController::class, 'index']);
    Route::post('/carts', [CartsController::class, 'store']);
    Route::get('/carts/{id}', [CartsController::class, 'index']);
});
