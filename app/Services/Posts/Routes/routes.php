<?php

use Illuminate\Support\Facades\Route;
use App\Services\Posts\Controllers\PostController;

Route::prefix('post')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::post('/new-post', [PostController::class, 'createNewPost']);
    });

});