<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1');
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

Route::prefix('auth/google')->controller(SocialAuthController::class)->group(function () {
    Route::get('redirect', 'redirect');
    Route::get('callback', 'callback');
});