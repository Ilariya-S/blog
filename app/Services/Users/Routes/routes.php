<?php

use Illuminate\Support\Facades\Route;
use App\Services\Users\Controllers\UserController;

Route::prefix('users')->group(function () {
    Route::prefix('registration')->group(function () {
        Route::post('/', [UserController::class, 'registration']);
        Route::post('/email/verification-notification', [UserController::class, 'newlink'])
            ->middleware(['throttle:6,1']);
        Route::get('/email/verify/{id}/{hash}', [UserController::class, 'verify'])
            ->middleware(['signed', 'throttle:6,1']);
    });

    Route::post('/login', [UserController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [UserController::class, 'destroy']);
        Route::get('/me', [UserController::class, 'me']);
        Route::post('/refresh', [UserController::class, 'refresh']);
    });
});