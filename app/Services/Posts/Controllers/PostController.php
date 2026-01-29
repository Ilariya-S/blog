<?php

namespace App\Services\Posts\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Posts\Managers\PostManager;
use App\Services\Posts\Models\Post;
use App\Services\Posts\Requests\CreatePostRequest;
use App\Services\Posts\Requests\UpdatePostRequest;
use App\Services\Posts\Resources\PostResource;
use Illuminate\Support\Facades\Gate;

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

    public function updatePost(UpdatePostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        Gate::authorize('update', $post);
        $payload = $request->validated();
        $updatedPost = $this->postManager->updatePost($post, $payload);

        return response()->json([
            'message' => 'Post updated successfully',
            'data' => $updatedPost->load('tags', 'category'),
        ]);
    }

    public function deletePost($id)
    {
        $post = Post::findOrFail($id);
        Gate::authorize('delete', $post);
        $this->postManager->deletePost($post->id);

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }

    public function showPost($id)
    {
        $currentUserId = auth('api')->id();
        $post = $this->postManager->getPostAndLogView($id, $currentUserId);

        return response()->json([
            'data' => new PostResource($post),
        ]);
    }
}
