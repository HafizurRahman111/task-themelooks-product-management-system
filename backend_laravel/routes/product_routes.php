<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


Route::prefix('api/products')->group(function () {
    Route::get('/get', [ProductController::class, 'getProducts']);

    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('{id}', [ProductController::class, 'destroy'])->name('products.destroy');

   
});

