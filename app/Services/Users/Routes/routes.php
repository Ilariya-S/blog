<?php

use Illuminate\Support\Facades\Route;
use App\Services\Users\Controllers\UserController;

Route::prefix('users')->group(function () {
    //регістрація -> створення користувача
    Route::prefix('registration')->group(function () {
        Route::post('/', [UserController::class, 'registration']);
        Route::get('/verify-email', [UserController::class, 'verifyemail'])
            ->name('verification.notice');
        Route::get('/email/verify/{id}/{hash}', [UserController::class, 'verify'])
            ->name('verification.verify');
        Route::post('/email/verification-notification', [UserController::class, 'newlink'])
            ->middleware(['throttle:6,1'])->name('verification.send');
    });

    //авторизація -> вхід в акаунт (перевірка токена)
    Route::get('/autorisation', [UserController::class, 'autorisation']);
});