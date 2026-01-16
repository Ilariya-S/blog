<?php
namespace App\Services\Posts\Managers;

use App\Services\Posts\Repositories\PostRepository;
use App\Services\Posts\Repositories\CategoryRepository;
use App\Services\Posts\Repositories\TagRepository;
use Illuminate\Support\Facades\DB;
use App\Services\Posts\Models\Post;

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

            $category = $this->categoryRepository->findOrCreateCategories($data['category']);

            //подив як можна зробити простіше, випадково чи  не намудрено
            $tagIds = [];
            if (isset($data['tags'])) {
                foreach ($data['tags'] as $tagTitle) {
                    $tag = $this->tagRepository->findOrCreateTag($tagTitle);
                    $tagIds[] = $tag->id;
                }
            }
            $postData = [
                'title' => $data['title'],
                'body' => $data['body'],
                'user_id' => $userId,
                'category_id' => $category->id,
                'tags_ids' => $tagIds,
            ];

            return $this->postRepository->create($postData);
        });
    }
    public function updatePost(int $postId, array $data)
    {
        return DB::transaction(function () use ($postId, $data, ) {

            Post::findOrFail($postId);
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
            return $this->postRepository->update($data, $postId);
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
}
