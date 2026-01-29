<?php

namespace App\Services\Posts\Repositories;

use App\Services\Posts\Contacts\PostRepositoryInterface;
use App\Services\Posts\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
// нове
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

    public function update(array $data, $post): Post
    {
        $post->update($data);
        if (isset($data['tags_ids'])) {
            $post->tags()->sync($data['tags_ids']);
        }

        return $post;
    }

    public function delete($id): bool
    {
        return $this->model->destroy($id);
    }

    public function findWithDetails(int $id): Post
    {
        return $this->model->with(['users', 'category', 'tags'])
            ->findOrFail($id);
    }

    public function incrementViews(Post $post): void
    {
        $post->increment('views');
    }

    // нове
    public function getFilteredPosts(array $filters): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->with(['users', 'tags'])
            ->withCount('comments');
        // my posts
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // popular posts
        if (($filters['sort'] ?? '') === 'popular') {
            $query->orderBy('views', 'desc');
        } else {
            $query->latest(); // all posts
        }

        // posts without comments
        if (!empty($filters['unanswered'])) {
            $query->has('comments', '=', 0);
        }

        // filter of tags
        if (!empty($filters['tags'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('title', $filters['tags']);
            });
        }

        return $query->paginate(10);
    }
}
