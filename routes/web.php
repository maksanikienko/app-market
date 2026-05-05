<?php

use App\Http\Controllers\BasketController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (JSON endpoints) 
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    
    Route::get('/products', [App\Http\Controllers\Api\ProductController::class, 'index'])->name('api.products');
    Route::get('/products/featured', [App\Http\Controllers\Api\ProductController::class, 'featured'])->name('api.products.featured');
    Route::get('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'show'])->where('id', '[0-9]+')->name('api.products.show');
    Route::get('/categories', [App\Http\Controllers\Api\CategoryController::class, 'index'])->name('api.categories');
    Route::get('/product/{id}', [MainController::class, 'product'])->name('api.product');

    Route::prefix('basket')->group(function () {
        Route::post('/add/{id}', [BasketController::class, 'add'])->name('api.basket-add');
        Route::get('/', [BasketController::class, 'index'])->name('api.basket');
        Route::post('/remove/{id}', [BasketController::class, 'remove'])->name('api.basket-remove');
        Route::post('/update', [BasketController::class, 'update'])->name('api.basket-update');
        Route::post('/place', [BasketController::class, 'place'])->name('api.basket-place');
    });

//    Route::post('/basket/remove-all/{id}', [BasketController::class, 'basketRemoveAll'])->name('api.basket-remove-all');
//    Route::post('/basket/place', [BasketController::class, 'basketConfirm'])->name('api.basket-confirm');
    
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index']);
        Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show']);
        Route::apiResource('categories', App\Http\Controllers\Admin\CategoryController::class);
        Route::apiResource('products', App\Http\Controllers\Admin\ProductController::class);
    });
});

/*
|--------------------------------------------------------------------------
| Authentication (JSON endpoints)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| SPA Catch-all Route 
|--------------------------------------------------------------------------
*/
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*')->name('spa');