<?php

use App\Http\Controllers\Api\v1\CartsController;
use App\Http\Controllers\Api\v1\ProductsController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::prefix('v1')->group(function () {
    Route::get('/products', [ProductsController::class, 'index']);
    Route::post('/products', [ProductsController::class, 'store']);
    Route::get('/products/{barcode}', [ProductsController::class, 'show']);
    Route::put('/products/{id}', [ProductsController::class, 'update']);
    Route::delete('/products/{id}', [ProductsController::class, 'destroy']);

    Route::get('/carts', [CartsController::class, 'index']);
    Route::post('/carts', [CartsController::class, 'store']);
    Route::get('/carts/{id}', [CartsController::class, 'show']);

    Route::get('storage/barcodes/{image}', function ($filename) {
        $path = storage_path('app/public/barcodes/' . $filename);

        if (!Storage::exists('public/barcodes/' . $filename)) {
            abort(404);
        }

        $file = Storage::get('public/barcodes/' . $filename);
        $type = Storage::mimeType('public/barcodes/' . $filename);

        return Response::make($file, 200, [
            'Content-Type' => $type,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    });
});
