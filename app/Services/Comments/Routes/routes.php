<?php

use App\Services\Comments\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::post('comments/', [CommentController::class, 'createNewComment']);
Route::get('posts/{id}/comments', [CommentController::class, 'showComments']);
