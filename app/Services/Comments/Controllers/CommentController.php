<?php

namespace App\Services\Comments\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Comments\Managers\CommentManager;
use App\Services\Comments\Requests\CommentCreateRequest;
use App\Services\Comments\Resources\CommentResource;

class CommentController extends Controller
{
    public function __construct(
        private CommentManager $commentManager,
    ) {
    }

    public function createNewComment(CommentCreateRequest $request)
    {
        $payload = $request->validated();
        $comment = $this->commentManager->createComment($payload, auth()->id());

        return response()->json([
            'status' => 'success',
            'comment' =>$comment,
            ]);
    }

    public function showComments(int $postId)
    {
        $comments = $this->commentManager->getCommentsTree($postId);

        return CommentResource::collection($comments);
    }
}
