<?php

namespace App\Services\Users\Contacts;

interface UserRepositoryInterface
{
    public function model();
    public function create(array $data);

}
