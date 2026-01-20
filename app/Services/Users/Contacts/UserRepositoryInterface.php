<?php

namespace App\Services\Users\Contacts;

interface UserRepositoryInterface
{
    public function create(array $data);
    public function getUserWithPosts(int $id);
    public function updateAvatar(int $userId, string $path);

}
