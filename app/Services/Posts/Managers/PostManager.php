<?php

namespace App\Services\Posts\Managers;

use App\Services\Posts\Models\Post;
use App\Services\Posts\Repositories\CategoryRepository;
use App\Services\Posts\Repositories\PostRepository;
use App\Services\Posts\Repositories\TagRepository;
use Illuminate\Support\Facades\DB;

class PostManager
{
    public function __construct(
        private PostRepository $postRepository,
        private CategoryRepository $categoryRepository,
        private TagRepository $tagRepository,
    ) {
    }

    public function createPost(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            $data['category_id'] = $this->categoryRepository
                ->findOrCreateCategories($data['category'])
                ->id;

            $tagIds = [];
            if (isset($data['tags'])) {
                foreach ($data['tags'] as $tagTitle) {
                    $tag = $this->tagRepository->findOrCreateTag($tagTitle);
                    $tagIds[] = $tag->id;
                }
            }
            $data['user_id'] = $userId;
            $data['tags_ids'] = $tagIds;

            return $this->postRepository->create($data);
        });
    }

    public function updatePost(int $post, array $data)
    {
        return DB::transaction(function () use ($post, $data) {
            if (isset($data['category'])) {
                $category = $this->categoryRepository->findOrCreateCategories($data['category']);
                $data['category_id'] = $category->id;
            }
            if (isset($data['tags'])) {
                $tagIds = [];
                foreach ($data['tags'] as $tagName) {
                    $tag = $this->tagRepository->findOrCreateTag($tagName);
                    $tagIds[] = $tag->id;
                }
                $data['tags_ids'] = $tagIds;
            }

            return $this->postRepository->update($data, $post);
        });
    }

    public function deletePost(int $postId): bool
    {
        return $this->postRepository->delete($postId);
    }

    public function getPostAndLogView(int $id, ?int $currentUserId): Post
    {
        $post = $this->postRepository->findWithDetails($id);

        if ($currentUserId !== $post->user_id) {
            $this->postRepository->incrementViews($post);
        }

        return $post;
    }

    public function getPostList(string $type, ?array $tags = null)
    {
        $filters = ['tags' => $tags];

        switch ($type) {
            case 'my':
                $filters['user_id'] = auth()->id();
                break;
            case 'popular':
                $filters['sort'] = 'popular';
                break;
            case 'unanswered':
                $filters['unanswered'] = true;
                break;
        }

        return $this->postRepository->getFilteredPosts($filters);
    }
}
