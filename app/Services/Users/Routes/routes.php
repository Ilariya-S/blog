<?php

use Illuminate\Support\Facades\Route;
use App\Services\Users\Controllers\UserController;

Route::prefix('users')->group(function () {
    // Реєстрація та верифікація
    Route::prefix('registration')->group(function () {
        Route::post('/', [UserController::class, 'registration']);

        // Маршрут для повторної відправки листа
        Route::post('/email/verification-notification', [UserController::class, 'newlink'])
            ->middleware(['throttle:6,1'])
            ->name('verification.send');

        // Головний маршрут верифікації (додано middleware 'signed')
        Route::get('/email/verify/{id}/{hash}', [UserController::class, 'verify'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');
    });

    // Авторизація (Вхід) - змінено на POST
    Route::post('/login', [UserController::class, 'login']);

    // Група маршрутів, що вимагають авторизації (JWT)
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [UserController::class, 'destroy']);
        Route::get('/me', [UserController::class, 'me']);
        Route::post('/refresh', [UserController::class, 'refresh']);
    });
});