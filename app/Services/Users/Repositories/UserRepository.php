<?php

namespace App\Services\Users\Repositories;

use App\Services\Users\Contacts\UserRepositoryInterface;
use App\Services\Users\Models\User;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Hash;
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function model()
    {
        return User::class;
    }
    public function create(array $data)
    {
        //Хешуємо пароль перед збереженням
        $data['password'] = Hash::make($data['password']);
        unset($data['password_confirmation']);
        return $this->model->create($data);
    }

}