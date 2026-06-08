<?php

use App\Http\Controllers\BasketController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ClassifierController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ErrorLogController;
use App\Http\Controllers\Admin\LocationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/products', [ProductController::class, 'index'])->name('api.products');
Route::get('/products/featured', [ProductController::class, 'featured'])->name('api.products.featured');
Route::get('/products/variant-options', [ProductController::class, 'variantOptions'])->name('api.products.variant-options');
Route::get('/products/{id}', [ProductController::class, 'show'])->where('id', '[0-9]+')->name('api.products.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('api.categories');
Route::get('/brands', [BrandController::class, 'index'])->name('api.brands');
Route::get('/classifiers', [ClassifierController::class, 'index'])->name('api.classifiers');

/*
|--------------------------------------------------------------------------
| Basket (session-based, works for guests and auth users)
|--------------------------------------------------------------------------
*/
Route::prefix('basket')->controller(BasketController::class)->group(function () {
    Route::get('/', 'index')->name('api.basket');
    Route::post('/add/{id}', 'add')->name('api.basket-add');
    Route::post('/remove/{id}', 'remove')->name('api.basket-remove');
    Route::post('/update', 'update')->name('api.basket-update');
    Route::post('/place', 'place')->name('api.basket-place');
});

/*
|--------------------------------------------------------------------------
| Profile (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('profile')->group(function () {
    Route::get('/orders', [ProfileController::class, 'orders'])->name('api.profile.orders');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    Route::apiResource('categories', AdminCategoryController::class);
    Route::post('/products/{id}/restore', [AdminProductController::class, 'restore']);
    Route::delete('/products/{id}/force-delete', [AdminProductController::class, 'forceDelete']);
    Route::apiResource('products', AdminProductController::class);

    Route::controller(ProductImageController::class)->prefix('products/{product}/images')->group(function () {
        Route::post('/', 'store');
        Route::put('/reorder', 'reorder');
        Route::delete('/{media}', 'destroy');
    });

    Route::get('/locations', [LocationController::class, 'index']);
    Route::get('/stock', [LocationController::class, 'stock']);

    Route::get('/errors', [ErrorLogController::class, 'index']);
    Route::delete('/errors/{activity}', [ErrorLogController::class, 'destroy']);
});
