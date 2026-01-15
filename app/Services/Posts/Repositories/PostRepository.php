<?php

namespace App\Services\Posts\Repositories;

use App\Services\Posts\Contacts\PostRepositoryInterface;
use App\Services\Posts\Models\Post;
use Prettus\Repository\Eloquent\BaseRepository;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    public function model()
    {
        return Post::class;
    }
    public function create(array $data): Post
    {
        $post = $this->model->create($data);
        if (!empty($data['tags_ids'])) {
            $post->tags()->attach($data['tags_ids']);
        }
        return $post;
    }

}