<?php

use App\Services\Posts\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('post')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::post('/new-post', [PostController::class, 'createNewPost']);
        Route::patch('/{id}', [PostController::class, 'updatePost'])->name('posts.edit');
        Route::delete('/{id}', [PostController::class, 'deletePost']);
    });
    Route::get('/{id}', [PostController::class, 'showPost'])->name('posts.show');
});
Route::get('/posts', [PostController::class, 'showListPosts']);
