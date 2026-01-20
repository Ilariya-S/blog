<?php

namespace App\Services\Users\Repositories;

use App\Services\Users\Contacts\UserRepositoryInterface;
use App\Services\Users\Models\User;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function model()
    {
        return User::class;
    }
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    public function getUserWithPosts(int $id)
    {
        return $this->model->with([
            'posts' => function ($query) {
                $query->latest();
            }
        ])->findOrFail($id);
    }
    public function updateAvatar(int $userId, string $path): bool
    {
        return $this->model->where('id', $userId)->update(['image' => $path]);
    }

}