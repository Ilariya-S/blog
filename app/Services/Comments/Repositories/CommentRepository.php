<?php

namespace App\Services\Comments\Repositories;

use App\Services\Comments\Contacts\CommentRepositoryInterface;
use App\Services\Comments\Models\Comment;
use Prettus\Repository\Eloquent\BaseRepository;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    public function model()
    {
        return Comment::class;
    }

    public function getCommentsByPost(int $postId)
    {
        return $this->model->where('post_id', $postId)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();
    }

    public function create(array $data)
    {
        return parent::create($data);
    }
}
