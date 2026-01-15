<?php

use Illuminate\Support\Facades\Route;
use App\Services\Users\Controllers\AuthController;

Route::prefix('users')->group(function () {
    Route::prefix('registration')->group(function () {
        Route::post('/', [AuthController::class, 'registration']);
        Route::post('/email/verification-notification', [AuthController::class, 'newLink'])
            ->middleware(['throttle:6,1'])
            ->name('verification.send');
        Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');
    });

    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'destroy']);
        Route::get('/me', [AuthController::class, 'me']);
        // Route::post('/refresh', [AuthController::class, 'refresh']);
    });
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/forget-password', [AuthController::class, 'recoveryPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});