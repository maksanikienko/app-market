<?php

use App\Http\Controllers\User\OrderController as UserOrderController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'user',
    'namespace' => 'User',
    'as' => 'user.',
], function () {
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');

});