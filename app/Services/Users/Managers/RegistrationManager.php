<?php
namespace App\Services\Users\Managers;

use App\Services\Users\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class RegistrationManager
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function store(array $data)
    {
        return $this->repository->create($data);
    }
}
