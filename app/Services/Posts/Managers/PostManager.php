<?php
namespace App\Services\Posts\Managers;

use App\Services\Posts\Repositories\PostRepository;
use App\Services\Posts\Repositories\CategoryRepository;
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

            $category = $this->categoryRepository->findOrCreateCategories($data['category']);

            //подив як можна зробити простіше, випадково чи  не намудрено
            $tagIds = [];
            if (!empty($data['tags'])) {
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
}
