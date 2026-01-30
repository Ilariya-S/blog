<?php

namespace App\Services\Comments\Contacts;

interface CommentRepositoryInterface
{
    public function getCommentsByPost(int $postId);

    public function create(array $data);
}
