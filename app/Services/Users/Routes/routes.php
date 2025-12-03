<?php

use Illuminate\Support\Facades\Route;

use App\Services\Users\Controllers\UserController;

Route::prefix('users')->group(function () {
    //регістрація -> створення користувача
    Route::post('/registration', [UserController::class, 'registration']);
    //авторизація -> вхід в акаунт (перевірка токена)
    Route::get('/autorisation', [UserController::class, 'autorisation']);
});