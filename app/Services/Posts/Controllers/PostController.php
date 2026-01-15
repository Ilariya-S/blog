<?php

namespace App\Services\Posts\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Posts\Requests\CreatePostRequest;
use App\Services\Posts\Managers\PostManager;
class PostController extends Controller
{
    public function __construct(
        private PostManager $postManager,
    ) {
    }

    public function createNewPost(CreatePostRequest $request)
    {
        $payload = $request->validated();
        $post = $this->postManager->createPost($payload, auth()->id());
        return response()->json([
            'status' => 'success',
            'data' => $post->load('tags', 'category'),
        ]);
    }

}