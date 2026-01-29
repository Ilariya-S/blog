<?php

namespace App\Services\Comments\Managers;

use App\Services\Comments\Repositories\CommentRepository;
use App\Services\Posts\Models\Post;

class CommentManager
{
    public function __construct(
        private CommentRepository $commentRepository)
    {
    }

    public function createComment(array $data, ?int $userId)
    {
        return $this->commentRepository->create([
            ...$data,
            'user_id' => $userId,
            'parent_id' => $data['parent_id'] ?? null,
        ]);
    }

    public function getCommentsTree(int $postId)
    {
        Post::findOrFail($postId);

        return $this->commentRepository->getCommentsByPost($postId);
    }
}
