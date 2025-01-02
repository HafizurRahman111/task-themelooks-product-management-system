<?php

use App\Http\Controllers\ProductVariationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


// Product Routes
Route::prefix('api/products')->group(function () {
    Route::get('/get', [ProductController::class, 'getProducts']);

    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('{id}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('{id}', [ProductController::class, 'update'])->name('products.update');
    Route::get('delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// Product Variation Routes
Route::prefix('api/variations')->group(function () {

    Route::get('/', [ProductVariationController::class, 'index'])->name('variations.index');
    Route::get('{id}', [ProductVariationController::class, 'show'])->name('variations.show');

    Route::post('/', [ProductVariationController::class, 'store'])->name('variations.store');

    Route::put('{id}', [ProductVariationController::class, 'update'])->name('variations.update');
    Route::delete('{id}', [ProductVariationController::class, 'destroy'])->name('variations.destroy');

});
