<?php

namespace App\Services\Posts\Contacts;

interface PostRepositoryInterface
{
    public function create(array $data);

    public function update(array $data, $post);

    public function delete($id);

    public function findWithDetails(int $id);

    public function getFilteredPosts(array $filters);
}
