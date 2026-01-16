<?php

namespace App\Services\Posts\Contacts;

interface PostRepositoryInterface
{
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);


}
