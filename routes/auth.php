<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user', static function () {
        $user = auth()->user();
        return response()->json([
            ...$user->toArray(),
            'roles' => $user->getRoleNames(), // ['admin'] or []
        ]);
    });
});

Route::post('/logout', [LoginController::class, 'logout']);